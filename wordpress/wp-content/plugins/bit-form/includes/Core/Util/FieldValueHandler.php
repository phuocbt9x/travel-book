<?php

namespace BitCode\BitForm\Core\Util;

use BitCode\BitForm\Admin\Form\Helpers;
use BitCode\BitForm\Core\Form\FormManager;

final class FieldValueHandler
{
  public static function replaceFieldWithValue($stringToReplaceField, $fieldValues, $formID = null)
  {
    if (empty($stringToReplaceField)) {
      return $stringToReplaceField;
    }
    if (!is_string($stringToReplaceField)) {
      $stringToReplaceField = wp_json_encode($stringToReplaceField);
    }
    $fieldValues = $formID ? self::sortValueBasedOnLayout($formID, $fieldValues) : $fieldValues;

    if ($formID) {
      $stringToReplaceField = self::replaceValueOfBf_all_data($stringToReplaceField, $fieldValues, $formID);
      $stringToReplaceField = self::replaceRepeaterFieldValue($stringToReplaceField, $fieldValues, $formID);
    }

    $stringToReplaceField = self::replaceSmartTagWithValue($stringToReplaceField);

    $fieldPattern = '/\${\w[^ ${}]*}/';

    preg_match_all($fieldPattern, $stringToReplaceField, $matchedField);
    if (empty($matchedField)) {
      return $stringToReplaceField;
    }
    $uniqueFieldsInStr = array_unique($matchedField[0]);
    foreach ($uniqueFieldsInStr as $key => $value) {
      $fieldName = substr($value, 2, strlen($value) - 3);
      $fieldValue = null;
      if (isset($fieldValues[$fieldName])) {
        $targetFieldValue = isset($fieldValues[$fieldName]['value']) ? $fieldValues[$fieldName]['value'] : $fieldValues[$fieldName];
        if ('array' === gettype($targetFieldValue) || 'object' === gettype($targetFieldValue)) {
          foreach ((array) $targetFieldValue as $singleTargetVal) {
            if (isset($fieldValue)) {
              if (is_numeric($fieldValue) && is_numeric($singleTargetVal)) {
                $fieldValue = $fieldValue + $singleTargetVal;
              } else {
                $fieldValue = "$fieldValue,  $singleTargetVal";
              }
            } else {
              $fieldValue = $singleTargetVal;
            }
          }
          // $fieldValue = wp_json_encode($targetFieldValue);
        } else {
          $fieldValue = strval($targetFieldValue);
        }
        $stringToReplaceField = str_replace($value, $fieldValue, $stringToReplaceField);
      } else {
        $stringToReplaceField = str_replace($value, '', $stringToReplaceField);
      }
    }

    // check if the string is a function like : "${_bf_calc(${b27-5}*10)}"
    // TO DO: Implement the function properly
    // if (self::isFunction($stringToReplaceField)) {
    //   $functionName = self::getFunctionName($stringToReplaceField);

    //   switch ($functionName) {
    //     case '_bf_calc':
    //       return self::getFunctionParameter($stringToReplaceField);
    //     case '_bf_count':
    //       return self::getCountValue($stringToReplaceField);
    //     default:
    //       return 0;
    //   }
    // }
    return $stringToReplaceField;
  }

  public static function replaceBackBtnWithPrevPageUrl($stringToReplaceField)
  {
    $prevPageUrl = isset($_SERVER['HTTP_REFERER']) ? esc_url_raw(wp_unslash($_SERVER['HTTP_REFERER'])) : home_url();
    preg_match_all('/\$?\{back_to_view\}/', $stringToReplaceField, $matches);
    $matched = $matches[0];

    if (empty($matched) || !$prevPageUrl) {
      return $stringToReplaceField;
    }

    foreach ($matched as $m) {
      $stringToReplaceField = str_replace($m, $prevPageUrl, $stringToReplaceField);
    }
    return $stringToReplaceField;
  }

  /**
   * Summary of getCountValue - get the count value from the function string "${_bf_count(item-1, item-2)}" => 2
   *
   * @param string $functionString
   * @return int
   */
  private static function getCountValue(string $functionString): int
  {
    $options = self::getFunctionParameter($functionString);
    $option = explode(',', $options);
    return count($option);
  }

  /**
   * Summary of getFunctionParameter - get the function parameter from the function string "${_bf_calc(2*10)}" => 2*10
   *
   * @param string $functionString
   * @return string
   */
  private static function getFunctionParameter(string $functionString): string
  {
    $regexPattern = '/\(([^)]*)\)/';
    preg_match($regexPattern, $functionString, $matches);
    return $matches[1];
  }

  /**
   * Summary of isFunction - check if the string is a function "${_bf_calc(${b27-5}*10)}" or not "${_bf_date}"
   *
   * @param string $functionString
   * @return bool true if the string is a function else false
   */
  private static function isFunction(string $functionString): bool
  {
    $regexPattern = '/\([^)]*\)/';
    return preg_match($regexPattern, $functionString);
  }

  /**
   * Summary of getFunctionName - get the function name from the function string "${_bf_calc(${b27-5}*10)}" => _bf_calc
   *
   * @param string $functionString
   * @return string
   */
  private static function getFunctionName(string $functionString): string
  {
    $regexPattern = '/\b([a-zA-Z_][a-zA-Z0-9_]*)\(/';
    preg_match($regexPattern, $functionString, $matches);
    return $matches[1];
  }

  public static function validateMailArry($emailAddresses, $fieldValues)
  {
    if (!is_array($emailAddresses)) {
      return [FieldValueHandler::replaceFieldWithValue($emailAddresses, $fieldValues)];
    }
    foreach ($emailAddresses as $key => $email) {
      if (!is_email($email)) {
        $email = FieldValueHandler::replaceFieldWithValue($email, $fieldValues);
        if (is_email($email)) {
          $emailAddresses[$key] = $email;
        }
      }
    }
    return $emailAddresses;
  }

  public static function replaceSmartTagWithValue($contentWithSmartTag)
  {
    $fieldPattern = '/(\${_[^{]*?)(?=\})}/';
    $matchPattern = preg_match_all($fieldPattern, $contentWithSmartTag, $matchedField);
    if (!$matchPattern) {
      return $contentWithSmartTag;
    }

    $ajaxRequest = false;
    // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Called from nonce-verified admin/frontend contexts.
    if (isset($_REQUEST['action']) && 'bitforms_trigger_workflow' === $_REQUEST['action']) {
      $ajaxRequest = true;
    }

    foreach (array_unique($matchedField[0]) as $value) {
      $fieldName = trim(substr($value, 2, strlen($value) - 3));

      $matches = preg_match('/\("*([^\)]+"*)\)/', $value, $matchCustomFormat);

      $customValue = '';
      if ($matches) {
        $removeQuote = ["'", '"'];
        $customValue = str_replace($removeQuote, '', $matchCustomFormat[1]);
        $fieldName = str_replace($matchCustomFormat[0], '', $fieldName);
      }

      $tagFieldValues = SmartTags::getSmartTagValue($fieldName, $ajaxRequest, $customValue);

      $contentWithSmartTag = str_replace($value, $tagFieldValues, $contentWithSmartTag);
    }
    return $contentWithSmartTag;
  }

  public static function isEmpty($val)
  {
    if (empty($val) && !in_array($val, ['0', 0, 0.0], true)) {
      return true;
    }
    return false;
  }

  public static function formatFieldValueForMail($fields, $fieldValues = [])
  {
    $formattedFldValues = $fieldValues;
    $file_upload_types = Helpers::$file_upload_types;
    $repeated_array_type_data_fields = Helpers::$repeated_array_type_data_fields;
    foreach ($fields as $fldKey => $fldData) {
      if (in_array($fldData->typ, $file_upload_types)) {
        continue;
      }
      if (array_key_exists($fldKey, $fieldValues)) {
        $value = $fieldValues[$fldKey];
        // if (is_array($value)) {
        //   $formattedFldValues[$fldKey] = htmlspecialchars(implode(', ', $value));
        // } else {
        //   $formattedFldValues[$fldKey] = htmlspecialchars($value);
        // }

        // TODO: this code are temporary commented, need to change and remove the comment

        // if (is_array($value)) {
        //   $arrValue = '';
        //   foreach ($value as $v) {
        //     if (is_array($v)) {
        //       foreach ($v as $k1 => $v1) {
        //         if (array_key_exists($k1, $repeaterFieldKey)) {
        //           $oldValue = $repeaterFieldKey[$k1];
        //           if (is_array($v1) && in_array($fields->{$k1}->typ, $repeated_array_type_data_fields)) {
        //             $newValues = '[' . implode(', ', $v1) . '] ';
        //             if (!preg_match('/\[.*\]/', $oldValue)) {
        //               $oldValue = '[' . $oldValue . '] ';
        //             }
        //           } else {
        //             $newValues = $v1;
        //           }
        //           $repeaterFieldKey[$k1] = $oldValue . ', ' . $newValues;
        //         } else {
        //           if (!empty($v1) && is_array($v1)) {
        //             $repeaterFieldKey[$k1] = htmlspecialchars(implode(', ', $v1));
        //           } else {
        //             $repeaterFieldKey[$k1] = htmlspecialchars($v1);
        //           }
        //         }
        //       }
        //     } else {
        //       $arrValue .= $v . ', ';
        //     }
        //   }
        //   $formattedFldValues[$fldKey] = htmlspecialchars(rtrim($arrValue, ', '));
        //   $arrValue = '';
        // } else {
        //   $formattedFldValues[$fldKey] = htmlspecialchars($value);
        // }
        if ('textarea' === $fldData->typ) {
          $formattedFldValues[$fldKey] = nl2br(htmlspecialchars($value));
        }
        if ('date' === $fldData->typ && !empty($value)) {
          $formattedFldValues[$fldKey] = date_i18n(get_option('date_format'), strtotime(htmlspecialchars($value)));
        }
      }
    }

    $merge_values = array_merge($fieldValues, $formattedFldValues);
    // $merge_values = array_merge($merge_values, $repeaterFieldKey);

    return $merge_values;
  }

  public static function changeHrefPathInHTMLString($html_body, $path)
  {
    if (empty($html_body) || empty($path)) {
      return $html_body;
    }

    return preg_replace_callback(
      '/<a\s+[^>]*href=[\'"]([^\'"]+)[\'"][^>]*>/i',
      function ($matches) use ($path) {
        $href = $matches[1];

        if (filter_var($href, FILTER_VALIDATE_URL)) {
          return $matches[0];
        }

        if (preg_match('/^(mailto:|tel:|javascript:|#)/i', $href)) {
          return $matches[0];
        }
        if (preg_match('/\$?\{back_to_view\}/', $matches[0])) {
          return $matches[0];
        }

        $fullPath = rtrim($path, '/') . '/' . ltrim($href, '/');

        return str_replace(
          $href,
          htmlspecialchars($fullPath, ENT_QUOTES),
          $matches[0]
        );
      },
      $html_body
    );
  }

  public static function changeImagePathInHTMLString($html_body, $path)
  {
    $allowedExtensions = ['jpg', 'jpeg', 'png', 'svg'];
    if (empty($html_body) || empty($path)) {
      return $html_body;
    }

    $allowedMimeTypes = [
      'jpg'  => ['image/jpeg', 'image/pjpeg'],
      'jpeg' => ['image/jpeg', 'image/pjpeg'],
      'png'  => ['image/png'],
      'svg'  => ['image/svg+xml']
    ];

    return preg_replace_callback(
      '/<img\s+[^>]*src=[\'"]([^\'"]*)[\'"][^>]*>/i',
      function ($matches) use ($path, $allowedExtensions, $allowedMimeTypes) {
        $src = $matches[1];

        if (filter_var($src, FILTER_VALIDATE_URL)) {
          return $matches[0];
        }

        if (!trim($src)) {
          return '';
        }

        $fullPath = rtrim($path, '/') . '/' . ltrim($src, '/');

        $extension = strtolower(pathinfo($fullPath, PATHINFO_EXTENSION));

        if (!preg_match('/^(http|https):\/\//', $fullPath)) {
          if (!file_exists($fullPath) || !isset($allowedMimeTypes[$extension])) {
            Log::debug_log([
              'status'  => 'error',
              'code'    => 'file_not_found',
              'message' => "File not found or unsupported file type: $fullPath",
            ]);
            return '';
          } else {
            Log::debug_log([
              'status'  => 'success',
              'code'    => 'file_found',
              'message' => "File Found => location: {$fullPath}"
            ]);
          }
          $mimeType = mime_content_type($fullPath);
          if (!in_array($mimeType, $allowedMimeTypes[$extension], true)) {
            Log::debug_log([
              'status'  => 'error',
              'code'    => 'unsupported_file_type',
              'message' => "Unsupported file type: $mimeType for file: $fullPath",
            ]);
            return '';
          }
        }

        return str_replace($src, htmlspecialchars($fullPath, ENT_QUOTES), $matches[0]);
      },
      $html_body
    );
  }

  public static function sortValueBasedOnLayout($formId, $fieldValues)
  {
    $formManager = FormManager::getInstance($formId);
    $layout = $formManager->getFormLayout();
    $formLayout = $formManager->getFlatenFormLayout();  // returns all layouts (lg, md, sm)
    $fieldKeyOrderbasedOnLayout = array_map(function ($fld) {
      return $fld->i;
    }, $formLayout->lg);
    $ordered = [];

    foreach ($fieldKeyOrderbasedOnLayout as $key) {
      if (array_key_exists($key, $fieldValues)) {
        $ordered[$key] = $fieldValues[$key];
      }
    }

    foreach ($fieldValues as $k=>$v) {
      if (!array_key_exists($k, $fieldKeyOrderbasedOnLayout)) {
        $ordered[$k] = $fieldValues[$k];
      }
    }
    return $ordered;
  }

  public static function replaceValueOfBf_all_data($stringToReplaceField, $fieldValues, $formId)
  {
    // $pattern = '/\$\{bf_all_data\}/'; // Corrected escaping
    $pattern = '/\$\{bf_all_data(?:\.onlyValues)?\}/'; // Corrected escaping

    preg_match_all($pattern, $stringToReplaceField, $matches);
    $matchesArray = $matches[0] ?? [];
    if (count($matchesArray) > 0) {
      $formManager = FormManager::getInstance($formId);
      $formFields = $formManager->getFields();
      $orderedFormFields = $formManager->getFieldsBasedOnLayout();  // ordered form fields based on layout(lg) order
      foreach ($matchesArray as $match) {
        switch ($match) {
          case '${bf_all_data}':
            $fieldValues = self::bindFormData($orderedFormFields, $fieldValues, $formId);
            $table = self::generateTable($fieldValues, $orderedFormFields);
            $stringToReplaceField = str_replace('${bf_all_data}', $table, $stringToReplaceField);
            break;

          case '${bf_all_data.onlyValues}':
            $fieldValues = self::bindFormData($orderedFormFields, $fieldValues, $formId, true);
            $table = self::generateTable($fieldValues, $orderedFormFields);
            $stringToReplaceField = str_replace('${bf_all_data.onlyValues}', $table, $stringToReplaceField);
            break;
          default:
            Log::debug_log([
              'status'  => 'error',
              'code'    => 'unknown_placeholder',
              'message' => "Unknown placeholder: $match",
            ]);
            break;
        }
      }
    }
    return $stringToReplaceField;
  }

  /**
   * Ensures an <img> tag with a style attribute exists in the input.
   *
   * Behavior:
   * - If the input is just an image filename (e.g., "1.png"), returns a complete <img> tag with the default style.
   * - If the input is HTML with <img> tags:
   *   - If any <img> has a style, returns the HTML as-is.
   *   - If <img> exists without style, adds the default style to the first one found.
   *   - If no <img> tag or image file is found, returns the input unchanged.
   *
   * @param string $input        Image filename or HTML string.
   * @param string $defaultStyle Optional. The CSS style to apply if missing. Default: 'max-width: 100%; height: auto;'.
   *
   * @return string Modified HTML string with styled <img> tag if needed.
   */
  private static function ensureImgWithStyle($input, $defaultStyle = 'max-width: 100%; height: auto;')
  {
    $imgTagWithStylePattern = '/<img\b[^>]*\bstyle\s*=\s*["\'][^"\']*["\'][^>]*>/i';
    $imgTagPattern = '/<img\b[^>]*>/i';
    $filePattern = '/\.(jpg|jpeg|png|gif|webp)$/i';

    if (preg_match($filePattern, trim($input)) && !preg_match('/<img\b/i', $input)) {
      return '<img src="' . htmlspecialchars(trim($input)) . '" style="' . $defaultStyle . '" />';
    }

    if (preg_match($imgTagWithStylePattern, $input)) {
      // <img> already has style, return as is
      return $input;
    } elseif (preg_match($imgTagPattern, $input, $match)) {
      // <img> without style, add style
      $updatedImg = preg_replace('/<img\b(.*?)(\/?)>/i', '<img$1 style="' . $defaultStyle . '" $2>', $match[0]);
      return str_replace($match[0], $updatedImg, $input);
    } else {
      // No <img> tag found, return input
      return $input;
    }
  }

  private static function orderRepeaterData($repeaterFldKey, $repeaterData, $formId)
  {
    if (!$formId) {
      return $repeaterData;
    }

    $formManager = FormManager::getInstance($formId);
    $nestedLayout = $formManager->getFormNestedLayout();
    $repeaterLayout = $nestedLayout->{$repeaterFldKey}->lg;
    $orderedFldKey = array_map(function ($fld) {
      return $fld->i;
    }, $repeaterLayout);

    $orderedRepeaterData = [];
    foreach ($repeaterData as $rptr) {
      $orderedFlds = [];
      foreach ($orderedFldKey as $k) {
        if (array_key_exists($k, $rptr)) {
          $orderedFlds[$k] = $rptr[$k];
        }
      }

      foreach ($rptr as $ky => $v) {
        if (!array_key_exists($ky, $orderedFldKey)) {
          $orderedFlds[$ky] = $v;
        }
      }

      $orderedRepeaterData[] = $orderedFlds;
    }
    return $orderedRepeaterData;
  }

  private static function bindFormData($formFields, $formData, $formId, $isOnlyValues = false)
  {
    $entryID = isset($formData['entry_id']) ? $formData['entry_id'] : null;
    return array_reduce(array_keys($formFields), function ($filteredData, $key) use ($formFields, $formData, $isOnlyValues, $formId) {
      $field = $formFields[$key];

      $fieldNewData = $filteredData;

      $ignoreFields = ['button', 'recaptcha', 'html', 'divider', 'spacer', 'section', 'turnstile', 'hcaptcha', 'image'];

      $arrayValueFldType = ['check', 'select', 'image-select'];

      if (in_array($field['type'], $ignoreFields)) {
        return $fieldNewData;
      }

      // Skip processing for hidden or empty fields only when $isOnlyValues is true
      if ($isOnlyValues) {
        // Check if the value is strictly an empty string or null, but allow 0
        if (!isset($formData[$key]) || '' === $formData[$key] || null === $formData[$key]) {
          return $fieldNewData;
        }

        if (isset($field['valid']['hide']) && $field['valid']['hide']) {
          return $fieldNewData;
        }
      }

      if (isset($formData[$key])) {
        if ('repeater' === $field['type']) {
          $repeater_data = is_string($formData[$key]) ? json_decode($formData[$key], true) : $formData[$key];
          // ordering repeater field according to nested repeater layout
          $repeater_data = self::orderRepeaterData($key, $repeater_data, $formId);
          if ($isOnlyValues) {
            $repeater_data = array_filter($repeater_data, function ($sub) {
              return array_filter($sub, fn ($value) => '' !== $value);
            });
          }
          $fieldNewData[$key] = $repeater_data;
        } elseif ('signature' === $field['type']) {
          if ('signature-failed.png' !== $formData[$key]) {
            $file_path = strpos($formData[$key], '/') ? $formData[$key] : $formData[$key];
            $newPath = $file_path;
            $fieldNewData[$key] = self::ensureImgWithStyle($newPath, 'max-width: 100%; height: auto;');
          }
        } elseif (in_array($field['type'], $arrayValueFldType)) {
          $v = is_string($formData[$key]) ? json_decode($formData[$key], true) : $formData[$key];
          $fieldNewData[$key] = $v && is_array($v) ? implode(', ', $v) : $formData[$key];
        } else {
          $fieldNewData[$key] = $formData[$key];
        }
      }

      return $fieldNewData;
    }, []);
  }

  private static function generateTable($fieldValues, $formFields)
  {
    if (empty($fieldValues)) {
      Log::debug_log([
        'status'     => 'error',
        'code'       => 'no_fields_found',
        'type'       => 'bf_all_data',
        'message'    => 'No fields found for bf_all_data',
        'fields'     => $fieldValues,
        'formFields' => $formFields,
      ]);
      return '<p>No data available.</p>';
    }

    $table = "<table style='font-family: arial, sans-serif; border-collapse: collapse; width: 100%;'>";

    foreach ($fieldValues as $fk => $value) {
      $value = self::decodeIfJson($value);
      $fieldName = self::getLabel($formFields, $fk) ?? $fk;
      $fieldType = $formFields[$fk]['type'];
      $table .= "<tr>
              <td style='border: 1px solid #dddddd; text-align: left; padding: 8px; font-weight: bold;'>{$fieldName}</td>
              <td style='border: 1px solid #dddddd; text-align: left; padding: 8px;'>";

      if (is_array($value)) {
        if ('repeater' === $fieldType) {
          $table .= "<table style='width: 100%; border-collapse: collapse;'>";

          $table .= '<tr>';
          foreach (array_keys($value[0]) as $subKey) {
            $subLabel = self::getLabel($formFields, $subKey) ?? $subKey;
            $table .= "<th style='border: 1px solid #dddddd; padding: 8px; background-color: #f2f2f2;'>" . $subLabel . '</th>';
          }
          $table .= '</tr>';

          foreach ($value as $row) {
            $table .= '<tr>';
            foreach ($row as $subKey => $subValue) {
              if (is_array($subValue)) {
                $subValue = self::unorderedAnchorListMarkup($subValue);

                // $subValue = implode(', ', array_map(function ($v) {
                //   if (self::isFileTypeValue($v)) {
                //     return self::anchorMarkup($v);
                //     // if (self::isImageTypeValue($v)) {
                //     //   return "<img src='{$v}' alt='{$v}' width='250'/>";
                //     // } else {
                //     //   return "<a href='{$v}' rel='noopener noreferrer' target='_blank' style='color:blue'>{$v}</a>";
                //     // }
                //   } else {
                //     return $v;
                //   }
                // }, $subValue));
              } else {
                if (self::isFileTypeValue($subValue)) {
                  if ('signature' === self::getFldType($subKey, $formFields)) {
                    if ('signature-failed.png' === $subValue) {
                      $subValue = '';
                    } else {
                      $subValue = "<img src='{$subValue}' alt='{$subValue}' width='250'/>";
                    }
                  }
                } else {
                  $subValue = $subValue;
                }
              }

              $table .= "<td style='border: 1px solid #dddddd; padding: 8px;'>" . $subValue . '</td>';
            }
            $table .= '</tr>';
          }
          $table .= '</table>';
        } elseif ('file-up' === $fieldType || 'advanced-file-up' === $fieldType) {
          if (is_array($value)) {
            $table .= self::unorderedAnchorListMarkup($value);
          }
        } elseif ('signature' === $fieldType) {
          if ('signature-failed.png' === $subValue) {
            $table .= '';
          } else {
            $table .= self::imgMarkup($value);
          }
        }
      } else {
        $table .= $value;
      }

      $table .= '</td></tr>';
    }

    $table .= '</table>';

    return $table;
  }

  private static function unorderedAnchorListMarkup($list)
  {
    $ul = "<ul style='list-style-type: none; padding: 0; margin:0'>";
    foreach ($list as $v) {
      $ul .= '<li >' . self::anchorMarkup($v) . '</li>';
    }
    $ul .= '</ul>';
    return $ul;
  }

  private static function imgMarkup($filename)
  {
    return "<img src='{$filename}' alt='{$filename}' width='250'/>";
  }

  private static function anchorMarkup($filename)
  {
    return "<a  href='{$filename}' rel='noopener noreferrer' target='_blank' style='color:blue'>{$filename}</a>";
  }

  public static function replaceRepeaterFieldValue($stringToReplaceField, $fieldValues, $formID)
  {
    if (!is_string($stringToReplaceField) || empty($stringToReplaceField)) {
      return $stringToReplaceField; // Return as-is if nothing to replace
    }

    $formManager = FormManager::getInstance($formID);
    $formFields = $formManager->getFieldsBasedOnLayout();  // ordered form fields based on layout(lg) order

    // Find all placeholders like ${field_key} example: ${b27-5}
    preg_match_all('/\$\{(b\d+-\d+)\}/', $stringToReplaceField, $matches);

    if (empty($matches[1])) {
      return $stringToReplaceField;
    }
    // Clean field data
    // $dataCleaning = self::removeEmptyValues($fieldValues);
    $flatFieldData = self::restructureRepeaterData($fieldValues, $formManager);
    // generate table for repeater fields
    foreach ($matches[1] as $fk) {
      $repeaterFieldKey = $fk;
      $fieldType = isset($formFields[$repeaterFieldKey]['type']) && !empty($formFields[$repeaterFieldKey]['type']) ? $formFields[$repeaterFieldKey]['type'] : null;
      if ('repeater' === $fieldType) {
        $repeaterMarkup = self::repeaterFieldTable($fieldValues[$repeaterFieldKey] ?? [], $formFields, $repeaterFieldKey);
        $stringToReplaceField = str_replace('${' . $fk . '}', $repeaterMarkup, $stringToReplaceField);
      } else {
        if ('signature' === $fieldType) {
          $stringToReplaceField = self::replaceImgTagForRepeatedSignature($stringToReplaceField, $flatFieldData[$repeaterFieldKey], $repeaterFieldKey);
        }

        $repeaterFieldData = self::safeFlatString($flatFieldData[$repeaterFieldKey] ?? '', $fieldType);
        $stringToReplaceField = str_replace('${' . $fk . '}', $repeaterFieldData, $stringToReplaceField);
      }
    }
    return $stringToReplaceField;
  }

  private static function replaceImgTagForRepeatedSignature($stringToReplaceField, $repeaterValue, $fldKey)
  {
    $data = self::decodeIfJson($repeaterValue);
    if (!is_string($stringToReplaceField) || empty($stringToReplaceField) || empty($fldKey)) {
      return $stringToReplaceField;
    }

    $pattern = '/<img\s+[^>]*src=[\'"]([^\'"]*' . preg_quote($fldKey, '/') . '[^\'"]*)[\'"][^>]*>/i';

    if (!preg_match($pattern, $stringToReplaceField)) {
      return $stringToReplaceField;
    }

    $values = [];
    $appendValue = function ($value) use (&$values) {
      if (is_array($value)) {
        foreach ($value as $item) {
          if (is_string($item) && '' !== trim($item) && 'signature-failed.png' !== $item) {
            $values[] = $item;
          }
        }
        return;
      }

      if (is_string($value) && '' !== trim($value) && 'signature-failed.png' !== $value) {
        $values[] = $value;
      }
    };

    $appendValue($data);

    if (empty($values)) {
      return preg_replace($pattern, '', $stringToReplaceField);
    }

    return preg_replace_callback($pattern, function ($matches) use ($values) {
      $imgTags = array_map(function ($value) use ($matches) {
        $src = htmlspecialchars($value, ENT_QUOTES);
        $alt = htmlspecialchars($value, ENT_QUOTES);

        $tag = $matches[0];
        $tag = preg_replace('/\bsrc\s*=\s*([\'"])(.*?)\1/i', 'src="' . $src . '"', $tag);

        if (preg_match('/\balt\s*=\s*([\'"])(.*?)\1/i', $tag)) {
          $tag = preg_replace('/\balt\s*=\s*([\'"])(.*?)\1/i', 'alt="' . $alt . '"', $tag);
        } else {
          $tag = preg_replace('/<img\b/i', '<img alt="' . $alt . '"', $tag, 1);
        }

        return $tag;
      }, $values);

      return implode('', $imgTags);
    }, $stringToReplaceField);
  }

  /**
   * Restructures repeater field data to maintain original structure while
   * aggregating nested repeater values into top-level indexed arrays.
   *
   * @param array $data Original field data structure
   * @return array Restructured data with aggregated arrays
   */
  public static function restructureRepeaterData(array $data, $formManagerInstance): array
  {
    $result = $data;
    // topkey === field Key  topValue === field value
    foreach ($data as $topKey => $topValue) {
      if ($formManagerInstance->isRepeaterField($topKey)) {
        $topValue = self::decodeIfJson($topValue);
        //assigning the converted value to repeater field
        $result[$topKey] = $topValue;
        // topvalue here is repeater field value;
        // entryIndex repeater field key , entry == repeater field value
        foreach ($topValue as $entryIndex => $entry) {
          if (!is_array($entry)) {
            continue;
          }
          foreach ($entry as $subKey => $subValue) {
            if (!isset($result[$subKey]) || !is_array($result[$subKey])) {
              $result[$subKey] = [];
            }
            // Handle nested arrays within entries
            $result[$subKey][$entryIndex] = $subValue;
          }
        }
      }
    }
    return $result;
  }

  /**
   * Return decoded data if incoming data is stringified and if it's a plain string (e.g "John Doe") it returns the plain string
   *
   * @param mixed $data
   * @return mixed
   */
  private static function decodeIfJson($data)
  {
    if (!is_string($data)) {
      return $data;
    }

    $decoded = json_decode($data, true);

    return (JSON_ERROR_NONE === json_last_error()) ? $decoded : $data;
  }

  /**
   * Safely converts any type of form value(Specially Repeater Field Value) to string.
   *
   * @param mixed $data
   * @param string $fldType
   * @return string
   */
  public static function safeFlatString($data, $fldType): string
  {
    $newData = self::decodeIfJson($data);
    if (is_array($newData)) {
      return implode(', ', array_map(function ($item) use ($fldType) {
        return is_array($item)
        ? '[' . implode(', ', array_map(function ($itm) use ($fldType) {
          if (in_array($fldType, ['advanced-file-up', 'file-up']) || self::isFileTypeValue($itm)) {
            return self::anchorMarkup($itm);
            // if (self::isImageTypeValue($itm)) {
            //   return "<img src='{$itm}' alt='{$itm}' width='250'/>";
            // } else {
            //   return "<a href='{$itm}' rel='noopener noreferrer' target='_blank' style='color:blue'>{$itm}</a>";
            // }
          } else {
            return $itm;
          }
        }, $item)) . ']'
        : self::safeFlatString($item, $fldType);
      }, $newData));
    }

    if (is_object($data)) {
      return method_exists($data, '__toString') ? (string) $data : (json_encode($data) ?: '');
    }

    if (is_null($data)) {
      return '';
    }

    if (self::isFileTypeValue($data)) {
      if ('signature-failed.png' === $data) {
        return '';
      }
    }

    if (in_array($fldType, ['signature', 'file-up', 'advanced-file-up'])) {
      return self::anchorMarkup($newData);
    }

    return (string) $data;
  }

  private static function removeEmptyValues($fieldData)
  {
    if (!is_array($fieldData)) {
      return $fieldData;
    }
    // Remove empty values from the array
    return array_filter($fieldData, function ($value) {
      // Check if the value is 0
      if (0 === $value) {
        return '0';
      }
      return !empty($value);
    });
  }

  /**
   * Gets the field type by field key
   *
   * @param string $fldKey
   * @param mixed $formField
   * @return string
   */
  private static function getFldType($fldKey, $formFields)
  {
    if (array_key_exists($fldKey, $formFields)) {
      return $formFields[$fldKey]['type'];
    }
  }

  /**
  * Return true is it's file type value by checking with extension
  *
  * @param string $filename
  * @return boolean
  */
  private static function isFileTypeValue($fileName)
  {
    if (!is_string($fileName)) {
      return false;
    }
    $ext = pathinfo($fileName, PATHINFO_EXTENSION);

    if ('other' !== FileHandler::getFileTypeByExtension($ext)) {
      return true;
    }
  }

  /**
  * Return true is it's image type value by checking with extension
  *
  * @param string $filename
  * @return boolean
  */
  private static function isImageTypeValue($fileName)
  {
    if (!is_string($fileName)) {
      return false;
    }
    $ext = pathinfo($fileName, PATHINFO_EXTENSION);

    if ('image' === FileHandler::getFileTypeByExtension($ext)) {
      return true;
    }
  }

  private static function repeaterFieldTable($repeaterFieldData, $formFields, $repeaterFieldKey)
  {
    $repeaterFieldData = self::decodeIfJson($repeaterFieldData);

    if (!is_array($repeaterFieldData) || !isset($repeaterFieldData[0]) || !is_array($repeaterFieldData[0])) {
      return ''; // Safely return empty if not a valid repeater structure
    }
    $table = "<table style='font-family: arial, sans-serif; border-collapse: collapse; width: 100%;'>";
    // $table .= '<tr>';
    // $table .= '<th style="border: 1px solid #dddddd; text-align: left; padding: 8px;">' . self::getLabel($formFields, $repeaterFieldKey) . '</th>';
    // $table .= '</tr>';
    // $table .= '<td style="border: 1px solid #dddddd; text-align: left; padding: 8px;">';
    // $table .= '<table style="width: 100%; border-collapse: collapse;">';

    $headers = array_keys($repeaterFieldData[0]);
    $table .= '<tr>';  // open tr (for column header)
    foreach ($headers as $fk) {
      $table .= '<th style="border: 1px solid #dddddd; padding: 8px; ">' . self::getLabel($formFields, $fk) . '</th>';
    }
    $table .= '</tr>';  // close tr (for column header)

    foreach ($repeaterFieldData as $row) {
      $table .= '<tr>';  // open tr (for table data row)
      foreach ($row as $k=>$value) {
        $fldTyp = self::getFldType($k, $formFields);
        if (is_array($value)) {
          if (in_array($fldTyp, ['advanced-file-up', 'file-up'])) {
            $newValue = self::unorderedAnchorListMarkup($value);
          } else {
            $newValue = implode(', ', $value);
          }
        } else {
          if (self::isFileTypeValue($value)) {
            $newValue = 'signature-failed.png' === $value
              ? ''
              : (self::isImageTypeValue($value)
              ? "<img src='{$value}' alt='{$value}' width='250'/>"
              : "<a href='{$value}' rel='noopener noreferrer' target='_blank' style='color:blue'>{$value}</a>");
          } else {
            $newValue = $value;
          }
        }

        $table .= '<td style="border: 1px solid #dddddd; padding: 8px;">' . $newValue . '</td>';
      }
      $table .= '</tr>';  // close tr (for table data row)
    }
    // $table .= '</table>';

    // $table .= '</td>';
    $table .= '</table>';

    return $table;
  }

  private static function getLabel($formFields, $key)
  {
    return $formFields[$key]['label'] ?? $key;
  }
}
