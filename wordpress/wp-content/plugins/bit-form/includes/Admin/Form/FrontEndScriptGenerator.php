<?php

namespace BitCode\BitForm\Admin\Form;

if (!defined('ABSPATH')) {
  exit;
}

use BitCode\BitForm\Core\Util\FileHandler;
use BitCode\BitForm\Core\Util\Log;
use BitCode\BitForm\Core\Util\Utilities;
use BitCode\BitForm\Core\WorkFlow\WorkFlowHandler;
use BitCode\BitFormPro\Admin\FormSettings\FormAbandonment;
use WP_Error;

class FrontEndScriptGenerator
{
  private $_scriptAddedFlags;
  private $_fields;
  private $_formContents;
  private $_jsFilesNeeded;
  private $_loadedScriptsList;
  private $_validationJsFilesNeeded;

  public function __construct()
  {
    $this->_scriptAddedFlags = [];
    $this->_fields = [];
    $this->_jsFilesNeeded = [];
    $this->_loadedScriptsList = [];
    $this->_validationJsFilesNeeded = ScriptFilePriorityManager::validationAndOtherScriptFile();
  }

  private static function generateBfGlobalObjJS($contentIds = [])
  {
    $contentidArray = wp_json_encode($contentIds);
    return '    if(!window.bf_globals){ window.bf_globals = {};}
    ' . $contentidArray . '.forEach(function(contentId){
      const form = document.getElementById(contentId);
      if(!form){ 
        delete window.bf_globals[contentId];
        return;
       }
      if(!window.bf_globals[contentId]){
        window.bf_globals[contentId] = {inits: {}, contentId: contentId};
      }else{ 
        window.bf_globals[contentId].inits = {};
        window.bf_globals[contentId].contentId = contentId;
      }
      
    });';
  }

  private static function isValidationNeeded($fldData)
  {
    $validationsToCheck = ['valid->req', 'valid->regexr', 'mn', 'mx',  'opt', 'err->invalid->show'];
    foreach ($validationsToCheck as $property) {
      $needValidation = Helpers::property_exists_nested($fldData, $property);
      if ($needValidation) {
        return true;
      }
    }
    return false;
  }

  public function generateJsFile($formContents, $fields, $contentIds, $postId, $formIDs = null, $preview = 'classic')
  {
    add_option('bitforms_frontend_js_generating', true);
    $this->_formContents = $formContents;
    $this->_fields = $fields;

    $this->appendJs(self::generateBfGlobalObjJS($contentIds), $postId, $preview, 'w');
    $this->_jsFilesNeeded = ScriptFilePriorityManager::jsFile();

    // helper js files for whole form
    foreach ($this->_jsFilesNeeded['helperScript'] as $jsFile) {
      $this->addScriptInLoadedScriptsList($jsFile);
    }

    // for add validation js files
    $this->jsValidationNeededFile();

    // js files for all fields
    $this->jsFieldsNeededFile();

    // for frontend helper js files
    $this->jsFrontendScript();

    // for hidden Field when cache plugin on
    $this->jsHiddenFieldScript();

    // for recaptcha v3 (form-level setting)
    $this->jsRecaptchaV3Scripts();

    // for form abandonment scripts
    $this->jsFormAbandonmentScripts();

    // multi step form scripts
    $this->multiStepFormScripts();

    // conversational form scripts
    $this->conversationalFormScripts();

    //⚠👆 every script file generate method call before this method
    $this->appendJs($this->getScript(), $postId, $preview);
    $this->appendJs($this->generateFieldConfigsJs($contentIds), $postId, $preview);
    // init all js events and functions at last
    $this->appendJs(self::getFileFromAssetsJs('bitform-init.min.js'), $postId, $preview);
    if (defined('ELEMENTOR_PRO_VERSION')) {
      $this->appendJs(self::getFileFromAssetsJs('bitform-elementor.min.js'), $postId, $preview);
    }

    // for js file minification
    $this->appendJs(self::getCustomJsCodes($contentIds), $postId, $preview);
    delete_option('bitforms_frontend_js_generating');
    return true;
  }

  private function multiStepFormScripts()
  {
    foreach ($this->_formContents as $formContent) {
      $layout = $formContent->layout;
      if (is_array($layout) && count($layout) > 1) {
        $files = ScriptFilePriorityManager::multiStepFiles();
        foreach ($files as $file) {
          $this->addScriptInLoadedScriptsList($file);
        }
      }
    }
  }

  private function conversationalFormScripts()
  {
    foreach ($this->_formContents as $formContent) {
      if (!empty($formContent->formInfo->conversationalSettings) && $formContent->formInfo->conversationalSettings->enable) {
        $files = ScriptFilePriorityManager::conversationalFormFiles();
        foreach ($files as $file) {
          $this->addScriptInLoadedScriptsList($file);
        }
      }
    }
  }

  private function jsFormAbandonmentScripts()
  {
    if (!Utilities::isPro() || !class_exists('\BitCode\BitFormPro\Admin\FormSettings\FormAbandonment')) {
      return;
    }
    //  check if any of the form has form abandonment enabled
    $allFiles = [];
    foreach ($this->_formContents as $formContent) {
      $formAbandonmentSettings = FormAbandonment::getFormAbandonmentSettings($formContent->formId);
      if (!empty($formAbandonmentSettings->saveFormDraft)) {
        $neededFiles = ScriptFilePriorityManager::formAbandonmentNeededFiles('autoSave');
        $allFiles = array_merge($allFiles, $neededFiles);
        break;
      }

      $workflowHandler = new WorkFlowHandler($formContent->formId);
      $allWorkflows = $workflowHandler->getAllworkFlow();
      foreach ($allWorkflows as $workflow) {
        foreach ($workflow['conditions'] as $cond) {
          if (!empty($cond->actions) && !empty($cond->actions->fields)) {
            foreach ($cond->actions->fields as $fldAction) {
              if (empty($fldAction->field) || empty($fldAction->action)) {
                continue;
              }
              if ('_bf_form' === $fldAction->field && 'save_draft' === $fldAction->action) {
                $neededFiles = ScriptFilePriorityManager::formAbandonmentNeededFiles();
                $allFiles = array_merge($allFiles, $neededFiles);
              }
            }
          }
        }
      }
    }
    if (isset($this->_fields['button'])) {
      foreach ($this->_fields['button'] as $button) {
        if ('save-draft' === $button['field']->btnTyp) {
          $neededFiles = ScriptFilePriorityManager::formAbandonmentNeededFiles();
          $allFiles = array_merge($allFiles, $neededFiles);
          break;
        }
      }
    }
    //
    if (empty($allFiles)) {
      return;
    }
    foreach ($allFiles as $jsFile) {
      $this->addScriptInLoadedScriptsList($jsFile);
    }
  }

  private function appendJs($script, $postId, $previewMode, $mode = 'a')
  {
    $fileName = '';
    $path = '';

    if ('conversational' === $previewMode) {
      $fileName = "bitform-conversational-{$postId}.js";
      $path = 'form-scripts';
    } elseif ('preview' === $previewMode) {
      $fileName = "preview-{$postId}.js";
      $path = 'form-scripts';
    } else {
      $fileName = "bitform-js-$postId.js";
      $path = "form-scripts/{$postId}";
    }
    Helpers::saveFile($path, $fileName,  Helpers::minifyJs($script), $mode);
  }

  private function getScript()
  {
    usort($this->_loadedScriptsList, function ($a, $b) {
      return $a['priority'] - $b['priority'];
    });

    $script = '';
    foreach ($this->_loadedScriptsList as $scriptFileArr) {
      $fileNam = $scriptFileArr['filename'];
      $fileContent = self::getFileFromAssetsJs($fileNam);
      if ($fileContent) {
        $script .= $fileContent;
      }
    }
    return $script;
  }

  private function jsValidationNeededFile()
  {
    foreach ($this->_fields as $flds) {
      foreach ($flds as $fld) {
        $fldData = $fld['field'];
        // for validation js files
        if (self::isValidationNeeded($fldData)) {
          $validation = $this->_validationJsFilesNeeded['validation'];
          $this->addScriptInLoadedScriptsList($validation);
        }
        // for required
        if (Helpers::property_exists_nested($fldData, 'valid->req')) {
          $fileArr = $this->_validationJsFilesNeeded['requiredFldValidation'];
          $this->addScriptInLoadedScriptsList($fileArr);
        }
        // for regex
        if (Helpers::property_exists_nested($fldData, 'valid->regexr')) {
          $patternFile = $this->_validationJsFilesNeeded['generateBackslashPattern'];
          $rgx = $this->_validationJsFilesNeeded['regexPatternValidation'];
          $this->addScriptInLoadedScriptsList($patternFile);
          $this->addScriptInLoadedScriptsList($rgx);
        }

        $validationScriptFileMapping = ScriptFilePriorityManager::validationScriptFileMapping($fldData->typ);
        if ($validationScriptFileMapping) {
          foreach ($validationScriptFileMapping as $key => $value) {
            $paths = $value['paths'] ?? [];
            $hasMatchingPath = false;
            foreach ($paths as $path) {
              if (Helpers::property_exists_nested($fldData, $path)) {
                $hasMatchingPath = true;
                break; // one match is enough
              }
            }

            if (!$hasMatchingPath) {
              continue;
            }

            // Add the main script once
            if (!empty($this->_validationJsFilesNeeded[$key])) {
              $this->addScriptInLoadedScriptsList($this->_validationJsFilesNeeded[$key]);
            }

            // Add dependencies once each
            foreach (($value['dependencies'] ?? []) as $dep) {
              if (!empty($this->_validationJsFilesNeeded[$dep])) {
                $this->addScriptInLoadedScriptsList($this->_validationJsFilesNeeded[$dep]);
              }
            }
          }
        }
      }
    }
  }

  private function jsFieldsNeededFile()
  {
    foreach ($this->_fields as $typ => $flds) {
      if (!array_key_exists($typ, $this->_jsFilesNeeded)) {
        continue;
      }
      $fldScriptArr = $this->_jsFilesNeeded[$typ];

      foreach ($flds as $fld) {
        if ('advanced-file-up' === $typ) {
          $configs = $fld['field']->config;
          foreach ($configs as $configKey => $config) {
            if ($configs->$configKey) {
              $filepondPlugin = ScriptFilePriorityManager::filePondPlugins($configKey);
              if ($filepondPlugin) {
                $this->addScriptInLoadedScriptsList($filepondPlugin);
              }
            }
          }
        }
        foreach ($fldScriptArr as $scriptFile) {
          if (isset($scriptFile['path']) && !Helpers::property_exists_nested($fld['field'], $scriptFile['path'], true)) {
            continue;
          }
          $this->addScriptInLoadedScriptsList($scriptFile);
        }
      }
    }
  }

  private static function getCustomJsCodes($contentIds = [])
  {
    $script = 'let bfContentId = "", bfSlNo = "1", bfVars= "";';
    foreach ($contentIds as $contentId) {
      $contentIdArr = explode('_', $contentId);
      $jsCode = self::getCustomCodes($contentIdArr[1])['JavaScript'];
      $bfSlNo = array_key_exists(3, $contentIdArr) ? $contentIdArr[3] : '1';
      if (!empty($jsCode)) {
        $script .= " if(bfSelect('#{$contentId}')){ bfContentId = '{$contentId}'; bfSlNo = '{$bfSlNo}'; bfVars = window.bf_globals.{$contentId}.smartTags; {$jsCode}}";
      }
    }

    return $script;
  }

  private static function getFileFromAssetsJs($fileName)
  {
    $sourceJsFile = BITFORMS_PLUGIN_DIR_PATH . 'assets' . DIRECTORY_SEPARATOR . $fileName;
    if (file_exists($sourceJsFile)) {
      return file_get_contents($sourceJsFile);
    }
    Log::debug_log('file not found: ' . $fileName);
    return false;
  }

  public static function saveCssFile($formId, $atomicCssText)
  {
    $path = 'form-styles';
    $fileName = "bitform-$formId.css";
    if (!isset($formId) || '' === $formId) {
      return new WP_Error('missing_form_id', __('Error Occurred, Please Reload', 'bit-form'));
    }
    return Helpers::saveFile($path, $fileName, $atomicCssText, 'w');
  }

  public static function customCodeFile($formId, $customCodes)
  {
    // for js file
    $path = 'form-scripts';
    $fileName = "bitform-custom-$formId.js";
    $filteredJs = Helpers::removeJsSingleLineComments($customCodes->JavaScript);
    self::customCodeFileSaveOrDelete($filteredJs, $path, $fileName);

    // for css file
    $path = 'form-styles';
    $fileName = "bitform-custom-$formId.css";
    self::customCodeFileSaveOrDelete($customCodes->CSS, $path, $fileName);

    return true;
  }

  public static function customCodeFileSaveOrDelete($script, $path, $fileName)
  {
    if ($script) {
      Helpers::saveFile($path, $fileName, $script, 'w');
    } else {
      $uploadPath = "$path/$fileName";
      $uploadFilePath = Helpers::generatePathDirOrFile($uploadPath);
      FileHandler::deleteIsFileExists($uploadFilePath);
    }
    return true;
  }

  public static function getCustomCodes($formId)
  {
    $customCodes = ['JavaScript' => '', 'CSS' => ''];
    $customJsPath = Helpers::generatePathDirOrFile("form-scripts/bitform-custom-$formId.js");
    $customCodes['JavaScript'] = Helpers::fileRead($customJsPath);

    $customCSSPath = Helpers::generatePathDirOrFile("form-styles/bitform-custom-$formId.css");
    $customCodes['CSS'] = Helpers::fileRead($customCSSPath);

    return $customCodes;
  }

  private $fldContainersByType = [
    'select'            => '.__$fk__-dpd-fld-wrp',
    'country'           => '.__$fk__-country-fld-wrp',
    'currency'          => '.__$fk__-currency-fld-wrp',
    'phone-number'      => '.__$fk__-phone-fld-wrp',
    'file-up'           => '.__$fk__-file-up-wrpr',
    'advanced-file-up'  => '#filepond-__$fk__-container',
    'paypal'            => '.__$fk__-paypal-wrp',
    'razorpay'          => '.__$fk__-razorpay-wrp',
    'recaptcha'         => '.__$fk__-recaptcha-wrp',
    'stripe'            => '.__$fk__-stripe-fld',
    'mollie'            => '.__$fk__-mollie-wrp',
    'repeater'          => '.__$fk__-rpt-fld-wrp',
    'signature'         => '.__$fk__-inp-fld-wrp',
    'rating'            => '.__$fk__-inp-fld-wrp',
    'hcaptcha'          => '.__$fk__-h-captcha-wrp',
    'advanced-datetime' => '.__$fk__-advanced-datetime',
  ];

  private function generateFieldConfigsJs()
  {
    $customFlds = ['select', 'country', 'currency', 'phone-number', 'file-up', 'advanced-file-up', 'paypal', 'razorpay', 'stripe', 'mollie', 'recaptcha', 'repeater', 'signature', 'rating', 'turnstile', 'hcaptcha', 'advanced-datetime'];
    $allFieldTypes = array_keys($this->_fields);
    $customFldsInForms = array_intersect($allFieldTypes, $customFlds);
    $formContents = $this->_formContents;
    $recaptchaV3Enabled = false;
    foreach ($formContents as $content) {
      if (isset($content->additional->enabled->recaptchav3) && $content->additional->enabled->recaptchav3) {
        $recaptchaV3Enabled = true;
        break;
      }
    }
    if (empty($customFldsInForms) && !$recaptchaV3Enabled) {
      return '';
    }

    $containers = [];
    foreach ($customFldsInForms as $customFldTyp) {
      if (isset($this->fldContainersByType[$customFldTyp])) {
        $containers[$customFldTyp] = $this->fldContainersByType[$customFldTyp];
      }
    }

    $customFldConfigPaths = [];

    foreach ($customFldsInForms as $customFldTyp) {
      $allConfs = ScriptFilePriorityManager::getAllFldConfs();
      if (isset($allConfs[$customFldTyp])) {
        $customFldConfigPaths[$customFldTyp] = $allConfs[$customFldTyp];
      } else {
        $customFldConfigPaths[$customFldTyp] = (object) [];
      }
    }

    $customFldConfigPaths = wp_json_encode($customFldConfigPaths);
    $containers = wp_json_encode($containers);

    $script = '    const customFldConfigPaths = ' . $customFldConfigPaths . ';
    const fldContainers = ' . $containers . ';
    
    function initAllCustomFlds (formContentId = null) {
      const allContendIds = formContentId ? [formContentId] : Object.keys(bf_globals);
      allContendIds.forEach((contentId) => {
        const contentData = bf_globals[contentId];
        const flds = bf_globals[contentId]?.fields || {};
        const fldKeys = Object.keys(flds).reverse();
        fldKeys.forEach((fldKey) => {
          const fldData = flds[fldKey];
          const fldType = fldData.typ;
          if(fldType === \'paypal\') {
            initPaypalFld(contentId, fldKey, fldData, fldType);
          } else if(fldType === \'razorpay\') {
            initRazorpayFld(contentId, fldKey, fldType);
          } else if(fldType === \'recaptcha\') {
            initRecaptchaFld(contentId, fldKey, fldType);
          } else if(fldType === \'turnstile\') {
            initTurnstileFld(contentId, fldKey);
          } else if(fldType === \'hcaptcha\') {
            initHCaptchaFld(contentId, fldKey, fldType);
          } else if(fldType === \'stripe\' || fldType === \'mollie\') {
            initStripeFld(contentId, fldKey, fldType);
          } else if (customFldConfigPaths[fldType]) {
            contentData.inits[fldKey] = getFldInstance(contentId, fldKey, fldType);
          }
        });
        if(contentData.gRecaptchaVersion === \'v3\' && contentData.gRecaptchaSiteKey){
          initRecaptchaV3Fld(contentId, contentData);
        }
      });
    };
    function getFldInstance(contentId, fldKey, fldTyp, nestedSelector = \'\') {
      const fldClass = this[\'bit_\'+fldTyp.replace(/-/g, \'_\')+\'_field\'];
      const selector = \'#form-\'+contentId+\' \'+nestedSelector+fldContainers[fldTyp].replace("__$fk__", fldKey);
      if(!fldClass || !bfSelect(selector)) return;
      return new fldClass(selector, getFldConf(contentId, fldKey, fldTyp));
    };
    function getFldConf(contentId, fieldKey, fldTyp) {
      const fldData = bf_globals[contentId].fields[fieldKey];
      const fldConfPaths = Object.entries(customFldConfigPaths[fldTyp]);
      let fldConf = {};
      const { formId } = bf_globals[contentId];
      if (!("config" in customFldConfigPaths[fldTyp]) && "config" in fldData) fldConf = fldData.config;
      const varData = { contentId, fieldKey, formId };
      fldConfPaths.forEach(([ confPath, fldPath ]) => {
        let value = "";
        if (fldPath.var) value = varData[fldPath.var];
        if (!value && fldPath.path) {
          if(Array.isArray(fldPath.path)) {
            fldPath.path.forEach((path) => {
              if(!value) value = getDataFromNestedPath(fldData, path);
            });
          } else {
            value = getDataFromNestedPath(fldData, fldPath.path);
          }
        }
        if (!value && fldPath.val) {
          value = fldPath.val;
          if(typeof value === \'string\') {
            Object.entries(varData).forEach(([key, val]) => {
              value = value.replace("__$"+key+"__", val);
            });
          }
        }
        fldConf = setDataToNestedPath(fldConf, confPath, value);
      });
      return fldConf;
    };
    function getDataFromNestedPath(data, key) {
      const keys = key.split("->");
      const lastKey = keys.pop();
      let current = {...data};
      for (const k of keys) {
        if (!(k in current)) return null;
        current = current[k];
      }
      return current[lastKey] || null;
    }
    function setDataToNestedPath(data, key, value) {
      const keys = key.split("->");
      const lastKey = keys.pop();
      let current = {...data};
      keys.forEach((k) => {
        if (!current[k]) current[k] = {};
        current = current[k];
      });
      current[lastKey] = value;
      return current;
    }';

    return $script;
  }

  private function addScriptInLoadedScriptsList($fileArr)
  {
    $fileName = $fileArr['filename'];
    if (!in_array($fileName, $this->_scriptAddedFlags)) {
      $this->_loadedScriptsList[] = $fileArr;
      $this->_scriptAddedFlags[] = $fileName;
    }
    return true;
  }

  private function jsFrontendScript()
  {
    foreach ($this->_formContents as $formContent) {
      if (Helpers::property_exists_nested($formContent, 'workFlowExist->oninput', true)) {
        $fileArr = ScriptFilePriorityManager::validationAndOtherScriptFile()['conditionalLogic'];
        $this->addScriptInLoadedScriptsList($fileArr);
        $fileArr = ScriptFilePriorityManager::validationAndOtherScriptFile()['resetPlaceholders'];
        $this->addScriptInLoadedScriptsList($fileArr);
        $fileArr = ScriptFilePriorityManager::validationAndOtherScriptFile()['bfResetDefaultValue'];
        $this->addScriptInLoadedScriptsList($fileArr);
        $fileArr = ScriptFilePriorityManager::validationAndOtherScriptFile()['validateFocusLost'];
        $this->addScriptInLoadedScriptsList($fileArr);
        $fileArr = ScriptFilePriorityManager::frontendScriptFile()['observeElm'];
        $this->addScriptInLoadedScriptsList($fileArr);
      }
      if (Helpers::property_exists_nested($formContent, 'additional->enabled->validateFocusLost', true)) {
        $fileArr = ScriptFilePriorityManager::validationAndOtherScriptFile()['validateFocusLost'];
        $this->addScriptInLoadedScriptsList($fileArr);
      }
    }
  }

  private function jsHiddenFieldScript()
  {
    $appConfig = get_option('bitform_app_config');
    if (Helpers::property_exists_nested($appConfig, 'cache_plugin', true)) {
      $fileArr = ScriptFilePriorityManager::frontendScriptFile()['hidden-token-field'];
      $this->addScriptInLoadedScriptsList($fileArr);
      return;
    }
  }

  private function jsRecaptchaV3Scripts()
  {
    foreach ($this->_formContents as $content) {
      if (isset($content->additional->enabled->recaptchav3) && $content->additional->enabled->recaptchav3) {
        $this->addScriptInLoadedScriptsList(['priority' => 302, 'filename' => 'scriptLoader.min.js']);
        $this->addScriptInLoadedScriptsList(['priority' => 303, 'filename' => 'initRecaptchaV3Fld.min.js']);
        return;
      }
    }
  }

  public function dd($data, $exit = false)
  {
    echo '+++++++++++++';
    echo '<pre>';
    var_dump($data); // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_var_dump
    echo '</pre>';
    echo '+++++++++++++';
    $exit ? exit : '';
  }
}
