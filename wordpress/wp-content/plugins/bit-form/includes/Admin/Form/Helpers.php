<?php

namespace BitCode\BitForm\Admin\Form;

use BitCode\BitForm\Core\Cryptography\Cryptography;
use BitCode\BitForm\Core\Database\FormEntryModel;
use BitCode\BitForm\Core\Util\FileHandler;
use BitCode\BitForm\Core\Util\Log;
use Exception;
use WP_Error;

class Helpers
{
  private static $encryptEntryIds = [];

  public static $file_upload_types = ['file-up', 'advanced-file-up'];

  public static $repeated_array_type_data_fields = ['check', 'image-select'];

  public static function filterNullEntries($entries)
  {
    $filteredEntries = [];
    foreach ($entries as $entry) {
      foreach ($entry as $key => $value) {
        if (is_null($value)) {
          unset($entry->$key);
        }
      }
      if (count((array) $entry)) {
        $filteredEntries[] = $entry;
      }
    }
    return $filteredEntries;
  }

  public static function scriptLoader($src, $id, $instanceObj = null, $selector = '', $attrs = [], $integrity = null, $contentId = '')
  {
    $attributes = wp_json_encode($attrs);
    $instObj = '';
    if ($instanceObj) {
      $instObj .= sprintf(
        '
      script.onload = function () {
          bfSelect("#{%1$s}").querySelectorAll("{%2$s}").forEach(function(fld){
          %3$s;
         });
  }
      ',
        $contentId,
        $selector,
        $instanceObj
      );
    }
    return sprintf(
      '
    var script =  document.createElement("script"), integrity = "%1$s", attrs = %2$s, id = "%3$s";
        script.src = "%4$s";
        script.id = id;
        if(integrity){
          script.integrity = integrity;
          script.crossOrigin = "anonymous";
        }
        if(attrs){
          Object.entries(attrs).forEach(function([key, val]){
            script.setAttribute(key,val);
          })
        }
        $instObj;
        var bodyElm = document.body;
        var alreadyExistScriptElm = bodyElm ? bodyElm.querySelector("script#$id"):null;
        if(alreadyExistScriptElm){
          bodyElm.removeChild(alreadyExistScriptElm)
        }
        if(!(window.recaptcha && id === "g-recaptcha-script")){
          bodyElm.appendChild(script);
        }
    ',
      $integrity,
      $attributes,
      $id,
      $src,
    );
  }

  public static function minifyJs($input)
  {
    if ('' === trim($input)) {
      return $input;
    }
    return preg_replace(
      [
        '/ {2,}/',
        '/\s*=\s*/',
        '/\s*,\s*/',
        '/\s+(?=\(|\{|\:|\?)|\t|(?:\r?\n[ \t]*)+/s'
      ],
      [' ', '=', ',', ''],
      $input
    );
  }

  public static function removeJsSingleLineComments($code)
  {
    $length = strlen($code);
    $result = '';
    $inString = false;
    $inTemplate = false;
    $inRegex = false;
    $escapeNext = false;
    $stringDelimiter = '';
    $i = 0;

    while ($i < $length) {
      $char = $code[$i];
      $nextChar = $i + 1 < $length ? $code[$i + 1] : '';

      if ($escapeNext) {
        $result .= $char;
        $escapeNext = false;
      } elseif ($inString) {
        $result .= $char;
        if ('\\' === $char) {
          $escapeNext = true;
        } elseif ($char === $stringDelimiter) {
          $inString = false;
        }
      } elseif ($inTemplate) {
        $result .= $char;
        if ('\\' === $char) {
          $escapeNext = true;
        } elseif ('`' === $char) {
          $inTemplate = false;
        }
      } elseif ($inRegex) {
        $result .= $char;
        if ('\\' === $char) {
          $escapeNext = true;
        } elseif ('/' === $char) {
          $inRegex = false;
        }
      } else {
        if ('"' === $char || "'" === $char) {
          $inString = true;
          $stringDelimiter = $char;
          $result .= $char;
        } elseif ('`' === $char) {
          $inTemplate = true;
          $result .= $char;
        } elseif ('/' === $char) {
          if ('/' === $nextChar) {
            // Single-line comment found
            while ($i < $length && "\n" !== $code[$i]) {
              $i++;
            }
            continue; // skip until newline
          } elseif ('*' === $nextChar) {
            // Block comment start, just copy it (optional, depending on need)
            $result .= $char;
          } else {
            // Assume division or regex
            $result .= $char;
          }
        } else {
          $result .= $char;
        }
      }
      $i++;
    }

    return $result;
  }

  /**
   * @method name : saveFile
   * @description : save js/css field to disk
   * @param  : $path => like(dirName/css), $fileName => main.css, $script
   * @return : boolean
   */
  public static function saveFile($path, $fileName, $script, $fileOpenMode = 'a')
  {
    try {
      $rootDir = BITFORMS_CONTENT_DIR . DIRECTORY_SEPARATOR;
      $path = trim($path, '/');
      $pathArr = explode('/', $path); // like "fieldname/user => [Fieldname, user]
      foreach ($pathArr as $d) {
        $rootDir .= $d . DIRECTORY_SEPARATOR;
        if (!realpath($rootDir)) {
          wp_mkdir_p($rootDir);
        }
      }
      $fullPath = $rootDir . $fileName;
      if ('a' === $fileOpenMode) {
        $result = FileHandler::appendFile($fullPath, $script);
      } else {
        $result = FileHandler::writeFile($fullPath, $script);
      }
      if (false === $result) {
        throw new Exception("Failed to write to file: $fullPath");
      }
      return true;
    } catch (\Exception $e) {
      Log::debug_log($e->getMessage());
      return false;
    }
  }

  /**
   * @method name : generatePathDirOrFile
   * @dscription : generate path for js/css file
   * @params : $path => like(dirName/css)
   * @return : a string of full path
   */
  public static function generatePathDirOrFile($path)
  {
    $rootDir = BITFORMS_CONTENT_DIR . DIRECTORY_SEPARATOR;
    $path = trim($path, '/');
    $pathArr = explode('/', $path); // like "fieldname/user => [Fieldname, user]
    foreach ($pathArr as $d) {
      $rootDir .= $d . DIRECTORY_SEPARATOR;
    }
    return rtrim($rootDir, DIRECTORY_SEPARATOR);
  }

  public static function fileRead($filePath)
  {
    return FileHandler::readFile($filePath);
  }

  public static function getDataFromNestedPath($data, $key)
  {
    $keys = explode('->', $key);
    $lastKey = array_pop($keys);
    $dataType = is_array($data) ? 'array' : (is_object($data) ? 'object' : '');
    if ('array' === $dataType) {
      return self::accessFromArray($data, $keys, $lastKey);
    }
    if ('object' === $dataType) {
      return self::accessFromObject($data, $keys, $lastKey);
    }
  }

  private static function accessFromObject($data, $keys, $lastKey)
  {
    foreach ($keys as $k) {
      if (!property_exists($data, $k)) {
        return null;
      }
      $data = $data->$k;
    }
    return isset($data->$lastKey) ? $data->$lastKey : null;
  }

  private static function accessFromArray($data, $keys, $lastKey)
  {
    foreach ($keys as $k) {
      if (!array_key_exists($k, $data)) {
        return null;
      }
      $data = $data[$k];
    }
    return isset($data[$lastKey]) ? $data[$lastKey] : null;
  }

  public static function setDataToNestedPath($data, $key, $value)
  {
    $keys = explode('->', $key);
    $lastKey = array_pop($keys);
    foreach ($keys as $k) {
      if (!array_key_exists($k, $data)) {
        $data->$k = (object) [];
      }
      $data = $data->$k;
    }
    $data->$lastKey = json_decode(wp_json_encode($value));
    ;
    return $data;
  }

  public static function property_exists_nested($obj, $path = '', $valToCheck = null, $checkNegativeVal = 0)
  {
    $path = explode('->', $path);
    $current = $obj;
    foreach ($path as $key) {
      if (is_object($current)) {
        if (property_exists($current, $key)) {
          $current = $current->{$key};
        } else {
          return false;
        }
      } else {
        return false;
      }
    }
    if (isset($valToCheck)) {
      if ($checkNegativeVal) {
        return $current !== $valToCheck;
      }
      return $current === $valToCheck;
    }
    return true;
  }

  public static function validateEntryTokenAndUser($entryToken, $entryId)
  {
    // check if the user is logged in
    if (is_user_logged_in()) {
      $user = wp_get_current_user();
      if (in_array('administrator', $user->roles) || current_user_can('manage_bitform')) {
        return true;
      }
      $entryModel = new FormEntryModel();
      $entry = $entryModel->get(
        'id, user_id, form_id',
        [
          'id'      => $entryId,
          'user_id' => $user->ID
        ]
      );
      if (!is_wp_error($entry) && !empty($entry)) {
        return true;
      }
    }
    // check if the entry token is valid
    if (isset($entryToken) && $entryToken) {
      $decryptEntryId = Cryptography::decrypt($entryToken, self::getBitformSalt());
      if ($decryptEntryId === $entryId) {
        return true;
      }
    }

    return false;
  }

  /**
   * Validate workflow trigger token with proper input sanitization
   *
   * @param object $request The AJAX request object
   * @param string $formID The form ID (already sanitized)
   * @return array ['valid' => bool, 'error' => string, 'triggerData' => object|null, 'isAdminBypass' => bool]
   */
  public static function validateWorkflowTriggerToken($request, $formID)
  {
    // Sanitize and validate cronNotOk array
    if (!isset($request->cronNotOk) || !is_array($request->cronNotOk)) {
      return [
        'valid'         => false,
        'error'         => 'Missing or invalid cronNotOk data',
        'triggerData'   => null,
        'isAdminBypass' => false
      ];
    }

    // Validate and sanitize entry ID and log ID (must be integers)
    if (!isset($request->cronNotOk[0]) || !is_numeric($request->cronNotOk[0])) {
      Log::debug_log('Invalid entry ID in cronNotOk[0]');
      return ['valid' => false, 'error' => 'Invalid entry ID', 'triggerData' => null, 'isAdminBypass' => false];
    }

    if (!isset($request->cronNotOk[1]) || !is_numeric($request->cronNotOk[1])) {
      Log::debug_log('Invalid log ID in cronNotOk[1]');
      return ['valid' => false, 'error' => 'Invalid log ID', 'triggerData' => null, 'isAdminBypass' => false];
    }

    $entryID = absint($request->cronNotOk[0]);
    $logID = absint($request->cronNotOk[1]);

    // Check for administrator bypass
    $isAdminBypass = false;
    if (is_user_logged_in()) {
      $user = wp_get_current_user();
      if (in_array('administrator', $user->roles) || current_user_can('manage_bitform')) {
        Log::debug_log('Admin bypass: Workflow triggered by ' . $user->user_login . ' for entryID=' . $entryID);
        return [
          'valid'         => true,
          'error'         => '',
          'triggerData'   => null,
          'isAdminBypass' => true
        ];
      }

      // For logged-in non-admin users: verify nonce
      if (isset($request->token, $request->id)) {
        if (!wp_verify_nonce($request->token, $request->id)) {
          Log::debug_log('Nonce verification failed for logged-in user. FormID=' . $formID);
          return [
            'valid'         => false,
            'error'         => 'Invalid nonce for logged-in user',
            'triggerData'   => null,
            'isAdminBypass' => false
          ];
        }
      } else {
        Log::debug_log('Missing nonce for logged-in user. FormID=' . $formID);
        return [
          'valid'         => false,
          'error'         => 'Missing nonce',
          'triggerData'   => null,
          'isAdminBypass' => false
        ];
      }
    }

    // For non-admin users (both logged-in and anonymous): validate one-time trigger token
    if (!isset($request->cronNotOk[3]) || empty($request->cronNotOk[3])) {
      Log::debug_log('Missing trigger token for formID=' . $formID . ', entryID=' . $entryID);
      return [
        'valid'         => false,
        'error'         => 'Missing trigger token',
        'triggerData'   => null,
        'isAdminBypass' => false
      ];
    }

    $submittedToken = sanitize_text_field($request->cronNotOk[3]);

    // Validate trigger token from transient
    $transientData = get_transient("bitform_trigger_transient_{$entryID}");

    if (empty($transientData)) {
      // Transient not found - will use database fallback in calling function
      return [
        'valid'         => true,
        'error'         => '',
        'triggerData'   => null,
        'isAdminBypass' => false
      ];
    }

    $triggerData = is_string($transientData) ? json_decode($transientData) : $transientData;
    // Verify token matches and belongs to this entry/log
    if (
      !isset($triggerData['trigger_token'])
      || !hash_equals($triggerData['trigger_token'], $submittedToken)
      || (int)$triggerData['entryID'] !== $entryID
      || (int)$triggerData['logID'] !== $logID
    ) {
      Log::debug_log('Invalid trigger token for entryID=' . $entryID . ', logID=' . $logID);
      return [
        'valid'         => false,
        'error'         => 'Invalid trigger token',
        'triggerData'   => null,
        'isAdminBypass' => false
      ];
    }

    // Token is valid - delete transient to prevent reuse (single-use token)
    delete_transient("bitform_trigger_transient_{$entryID}");
    Log::debug_log('Valid trigger token consumed for entryID=' . $entryID);

    return [
      'valid'         => true,
      'error'         => '',
      'triggerData'   => $triggerData,
      'isAdminBypass' => false
    ];
  }

  public static function validateFormEntryEditPermission($formId, $entryId)
  {
    if (is_user_logged_in()) {
      if (current_user_can('manage_bitform') || current_user_can('bitform_entry_edit') || current_user_can('edit_post')) {
        return true;
      }
    }
    return false;
  }

  public static function honeypotEncryptedToken($str)
  {
    $token = base64_encode(base64_encode($str));
    return $token;
  }

  public static function csrfEecrypted()
  {
    $secretKey = get_option('bf_csrf_secret');
    if (!$secretKey) {
      $secretKey = 'bf-' . time();
      update_option('bf_csrf_secret', $secretKey);
    }
    $tIdenty = base64_encode(\random_bytes(32));
    $csrf = \base64_encode(\hash_hmac('sha256', $tIdenty, $secretKey, true));
    return ['csrf' => $csrf, 't_identity' => $tIdenty];
  }

  public static function csrfDecrypted($identy, $token)
  {
    $secretKey = get_option('bf_csrf_secret');
    return \hash_equals(
      \base64_encode(\hash_hmac('sha256', $identy, $secretKey, true)),
      $token
    );
  }

  public static function checkIsIntArr($arr)
  {
    $filteredArray = array_filter($arr, 'is_numeric');
    $intArray = array_map('intval', $filteredArray);
    $result = count($arr) === count($intArray);

    return $result;
  }

  public static function getTruncatedEncryptToken($str, $length = 20)
  {
    $token = hash_hmac('sha256', $str, self::getBitformSalt());
    return substr($token, 0, $length);
  }

  public static function getEncryptedEntryId($entryId)
  {
    if (!isset(self::$encryptEntryIds[$entryId])) {
      self::$encryptEntryIds[$entryId] = self::getTruncatedEncryptToken($entryId);
    }
    return self::$encryptEntryIds[$entryId];
  }

  public static function getFullPathWithEncryptedEntryId($formId, $entryId)
  {
    $encryptDirectory = Helpers::getEncryptedEntryId($entryId);
    return BITFORMS_UPLOAD_DIR . DIRECTORY_SEPARATOR . $formId . DIRECTORY_SEPARATOR . $encryptDirectory;
  }

  public static function getWebPathWithEncryptedEntryId($formId, $entryId)
  {
    $encryptDirectory = Helpers::getEncryptedEntryId($entryId);
    return BITFORMS_UPLOAD_BASE_URL . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . $formId . DIRECTORY_SEPARATOR . $encryptDirectory;
  }

  public static function PDFPassHash($entryId)
  {
    return abs(crc32($entryId));
  }

  public static function encryptBinaryData($plaintext)
  {
    $iv = openssl_random_pseudo_bytes(16);
    $encrypted = openssl_encrypt($plaintext, 'AES-256-CBC', BITFORMS_SECRET_KEY, OPENSSL_RAW_DATA, $iv);

    return bin2hex($iv . $encrypted);
  }

  public static function decryptBinaryData($encryptedHex)
  {
    $decoded = hex2bin($encryptedHex);
    $iv = substr($decoded, 0, 16);
    $cipherText = substr($decoded, 16);

    return openssl_decrypt($cipherText, 'AES-256-CBC', BITFORMS_SECRET_KEY, OPENSSL_RAW_DATA, $iv);
  }

  /**
     * Sanitize user-provided HTML content by removing dangerous JS code
     * while allowing all valid HTML/CSS.
     *
     * @param string $html Raw HTML from user input
     * @return string Sanitized safe HTML
     */
  public static function sanitizeUserHTML(string $html): string
  {
    // Remove <script> tags entirely
    $html = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', '', $html);

    // Remove event handler attributes (like onclick, onload, etc.)
    $html = preg_replace_callback('/<[^>]+>/i', function ($matches) {
      return preg_replace('/\s*on\w+\s*=\s*"[^"]*"/i', '', $matches[0]); // on*=""
    }, $html);

    $html = preg_replace_callback('/<[^>]+>/i', function ($matches) {
      return preg_replace("/\s*on\w+\s*=\s*'[^']*'/i", '', $matches[0]); // on*=''
    }, $html);

    // Remove javascript: from href or src
    $html = preg_replace('/(href|src)\s*=\s*([\'"])\s*javascript:[^\'"]*\2/i', '', $html);

    return $html;
  }

  public static function sanitizeUrlParam($param)
  {
    if (preg_match('/\.\.?\//', $param)) {
      return new WP_Error('parameter_error', 'Invalid URL parameter');
    }

    $param = htmlspecialchars(trim($param), ENT_QUOTES, 'UTF-8');
    return sanitize_text_field($param);
  }

  public static function replaceFieldsDefaultErrorMsg($fields)
  {
    try {
      $appSettings = get_option('bitform_app_settings', (object) []);
      if (!isset($appSettings->globalMessages) || !isset($appSettings->globalMessages->err)) {
        return $fields;
      }

      $globalErrMsg = $appSettings->globalMessages->err;
      $templateCache = []; // [type_errKey] => compiled template

      foreach ($fields as $fieldKey => $field) {
        if (!isset($field->err) || !is_object($field->err)) {
          continue;
        }

        foreach ($field->err as $errKey => $errObj) {
          if (!isset($errObj->dflt)) {
            continue;
          }

          $cacheKey = $field->typ . '_' . $errKey;
          $template = null;

          // 1. Check Cache First
          if (isset($templateCache[$cacheKey])) {
            $template = $templateCache[$cacheKey];
          } else {
            // 2. Lookup from globalErrMsg
            if (isset($globalErrMsg->{$field->typ}->{$errKey})) {
              $template = $globalErrMsg->{$field->typ}->{$errKey};
            } elseif (isset($globalErrMsg->{$errKey}) && !is_object($globalErrMsg->{$errKey})) {
              $template = $globalErrMsg->{$errKey};
            }

            // 3. Cache it
            if ($template) {
              $templateCache[$cacheKey] = $template;
            }
          }

          // 4. Apply Template if Found
          if ($template) {
            $finalMsg = self::replaceShortcodeInErrorMsg($template, $field);
            // 5. Sanitize final output
            $field->err->{$errKey}->dflt = wp_kses_post($finalMsg);
          }
        }

        $fields->{$fieldKey} = $field;
      }
    } catch (Exception $e) {
      Log::debug_log('Error In Replacing Fields Default Error messages: ' . $e->getMessage());
    }
    return $fields;
  }

  //replace shortcode in error message
  public static function replaceShortcodeInErrorMsg($msg, $field)
  {
    $shortcodes = [
      '${field.label}'          => isset($field->lbl) ? $field->lbl : '',
      '${field.minimum}'        => isset($field->mn) ? $field->mn : '',
      '${field.maximum}'        => isset($field->mx) ? $field->mx : '',
      '${field.minimum_file}'   => isset($field->config->minFile) ? $field->config->minFile : '',
      '${field.maximum_file}'   => isset($field->config->maxFile) ? $field->config->maxFile : '',
      '${field.maximum_size}'   => isset($field->config->maxSize) ? $field->config->maxSize : '',
      '${field.minimum_amount}' => isset($field->config->minValue) ? $field->config->minValue : '',
      '${field.maximum_amount}' => isset($field->config->maxValue) ? $field->config->maxValue : '',
    ];
    $msg = str_replace(array_keys($shortcodes), array_values($shortcodes), $msg);
    return $msg;
  }

  public static function getDefaultGlobalMessages()
  {
    $defaultGlobalMessages = [
      'err' => [
        'req'   => '<p style="margin:0">' . __('This field is required', 'bit-form') . '</p>',
        'email' => [
          'invalid' => '<p style="margin:0">' . __('Please, enter a valid email address', 'bit-form') . '</p>',
        ],
        'url' => [
          'invalid' => '<p style="margin:0">' . __('Please, enter a valid URL', 'bit-form') . '</p>',
        ],
        'mn'     => '<p style="margin:0">' . __('Minimum ${field.minimum} is required', 'bit-form') . '</p>',
        'mx'     => '<p style="margin:0">' . __('Maximum ${field.maximum} is allowed', 'bit-form') . '</p>',
        'number' => [
          'invalid' => '<p style="margin:0">' . __('Please, enter only numbers', 'bit-form') . '</p>',
        ],
        'phone-number' => [
          'invalid' => '<p style="margin:0">' . __('Please, enter a valid phone number', 'bit-form') . '</p>',
        ],
        'check' => [
          'mn' => '<p style="margin:0">' . __('Select at least ${field.minimum} option(s)', 'bit-form') . '</p>',
          'mx' => '<p style="margin:0">' . __('Please, select no more than ${field.maximum} option(s)', 'bit-form') . '</p>',
        ],
        'select' => [
          'mn' => '<p style="margin:0">' . __('Select at least ${field.minimum} option(s)', 'bit-form') . '</p>',
          'mx' => '<p style="margin:0">' . __('Please, select no more than ${field.maximum} option(s)', 'bit-form') . '</p>',
        ],
        'image-select' => [
          'mn' => '<p style="margin:0">' . __('Select at least ${field.minimum} option(s)', 'bit-form') . '</p>',
          'mx' => '<p style="margin:0">' . __('Please, select no more than ${field.maximum} option(s)', 'bit-form') . '</p>',
        ],
        'inputMask'   => '<p style="margin:0">' . __('Input does not match the required pattern', 'bit-form') . '</p>',
        'regexr'      => '<p style="margin:0">' . __('Input does not match the required pattern', 'bit-form') . '</p>',
        'minFile'     => '<p style="margin:0">' . __('Minimum ${field.minimum_file} file(s) required', 'bit-form') . '</p>',
        'maxFile'     => '<p style="margin:0">' . __('Maximum ${field.maximum_file} file(s) allowed', 'bit-form') . '</p>',
        'maxSize'     => '<p style="margin:0">' . __('Maximum file size exceeded. (Max: ${field.maximum_size}MB)', 'bit-form') . '</p>',
        'fileType'    => '<p style="margin:0">' . __('File type is not supported', 'bit-form') . '</p>',
        'entryUnique' => '<p style="margin:0">' . __('This value is already taken. Please, choose a different one.', 'bit-form') . '</p>',
        'userUnique'  => '<p style="margin:0">' . __('This username or email is already registered. Please, use another.', 'bit-form') . '</p>',
        'otherOptReq' => '<p style="margin:0">' . __('Custom Option Required', 'bit-form') . '</p>',
        'minValue'    => '<p style="margin:0">' . __('Minimum amount of ${field.minimum_amount} is required', 'bit-form') . '</p>',
        'maxValue'    => '<p style="margin:0">' . __('Maximum amount of ${field.maximum_amount} is allowed', 'bit-form') . '</p>',
      ],
    ];

    // Convert array to object recursively
    return json_decode(json_encode($defaultGlobalMessages));
  }

  public static function getBitformSalt()
  {
    $salt = get_option('bitforms_salt');
    if (!$salt) {
      $salt = bin2hex(\random_bytes(32));
      update_option('bitforms_salt', $salt);
    }
    return $salt;
  }
}
