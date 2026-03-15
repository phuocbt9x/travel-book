<?php

namespace BitCode\BitForm\Core\Fallback;

use BitCode\BitForm\Core\Database\FormModel;
use BitCode\BitForm\Core\Util\Log;
use BitCode\BitForm\Core\Util\Utilities;
use BitCode\BitForm\Jcof\Jcof;

class StylesFallback
{
  public function signatureFldIframeStyle()
  {
    $formModel = new FormModel();
    $forms = $formModel->get(
      ['id', 'form_content', 'builder_helper_state']
    );

    if (is_wp_error($forms)) {
      return;
    }

    $signatureIframeStyle = [
      'position'      => 'absolute',
      'top'           => 0,
      'left'          => 0,
      'width'         => '100%',
      'height'        => '100%',
      'opacity'       => 0,
      'pointer-events'=> 'none',
      'border'        => 'none',
    ];

    foreach ($forms as $form) {
      $hasStyleChanged = false;
      $formId = $form->id;
      $builderHelperState = json_decode($form->builder_helper_state);
      if (empty($builderHelperState->staticStyles)) {
        continue;
      }
      $staticStyleState = $builderHelperState->staticStyles;
      $staticStyleState = Jcof::parse($staticStyleState);

      $formContent = json_decode($form->form_content);
      $fields = $formContent->fields;

      foreach ($fields as $fldKey => $fldData) {
        if ('signature' !== $fldData->typ) {
          continue;
        }
        if (!isset($staticStyleState['staticStyles'])) {
          $staticStyleState['staticStyles'] = [];
        }
        $iframeKey = ".{$fldKey}-signature-iframe";
        $staticStyleState['staticStyles'][$iframeKey] = $signatureIframeStyle;
        $generatedStyleCSS = Utilities::styleGenerator([$iframeKey => $signatureIframeStyle]);
        Utilities::appendCSS($formId, $generatedStyleCSS);
        $hasStyleChanged = true;
      }

      if (!$hasStyleChanged) {
        continue;
      }

      $staticStyleState = Jcof::stringify($staticStyleState);
      $builderHelperState->staticStyles = $staticStyleState;
      $formModel->update(
        [
          'builder_helper_state' => wp_json_encode($builderHelperState),
        ],
        [
          'id' => $formId,
        ]
      );
    }
  }

  public function stripeFldButtonDisabled()
  {
    $stylesArray = [
      '.__fk__-stripe-btn:disabled' => [
        'background' => '#7ca5e7',
        'transform'  => 'none',
      ],
      '.__fk__-stripe-wrp .stripe-pay-btn:disabled'=> [
        'background' => '#7ca5e7',
        'transform'  => 'none',
      ]
    ];

    $this->addStyles('stripe', $stylesArray);
  }

  public function stripeFldErrorMessage()
  {
    $stylesArray = [
      '.__fk__-err-inner' => [
        'overflow'=> 'hidden',
      ],
    ];

    $this->addStyles('stripe', $stylesArray);
  }

  /**
   *  for adding fallback styles to field in form staticStyles object
   *
   * @param string $fieldType
   * @param array $stylesArray
   * @return void
   */
  private function addStyles(string $fieldType, array $stylesArray): void
  {
    $formModel = new FormModel();
    $forms = $formModel->get(
      ['id', 'form_content', 'builder_helper_state']
    );

    if (is_wp_error($forms)) {
      return;
    }
    foreach ($forms as $form) {
      $hasStyleChanged = false;
      $formId = $form->id;
      $builderHelperState = json_decode($form->builder_helper_state);
      if (empty($builderHelperState->staticStyles)) {
        continue;
      }
      $staticStyleState = $builderHelperState->staticStyles;
      $staticStyleState = Jcof::parse($staticStyleState);

      $formContent = json_decode($form->form_content);
      $fields = $formContent->fields;

      foreach ($fields as $fldKey => $fldData) {
        if ($fieldType !== $fldData->typ) {
          continue;
        }
        if (!isset($staticStyleState['staticStyles'])) {
          $staticStyleState['staticStyles'] = [];
        }

        $newStyles = $this->replaceFldKey($fldKey, $stylesArray);
        foreach ($newStyles as $newStyleKey => $newStyleValue) {
          $staticStyleState['staticStyles'][$newStyleKey] = $newStyleValue;
        }

        $generatedStyleCSS = Utilities::styleGenerator($newStyles);
        Utilities::appendCSS($formId, $generatedStyleCSS);
        $hasStyleChanged = true;
      }

      if (!$hasStyleChanged) {
        continue;
      }
      $staticStyleState = Jcof::stringify($staticStyleState);
      $builderHelperState->staticStyles = $staticStyleState;
      $formModel->update(
        [
          'builder_helper_state' => wp_json_encode($builderHelperState),
        ],
        [
          'id' => $formId,
        ]
      );
    }
  }

  /**
   *  for adding fallback styles in form staticStyles object
   *
   * @return void
   */
  public function addStaticStyleForMultiStepForm(): void
  {
    $newStylesArray = [
      '._frm-__fk__-stp-hdr-wrpr' => [
        'flex-wrap' => 'wrap',
      ],
      '@media (max-width: 576px)' => [
        '._frm-__fk__-stp-hdr-lbl' => [
          'font-size' => '12px',
        ],
        '._frm-__fk__-stp-hdr-sub-titl' => [
          'display' => 'none',
        ],
        '._frm-__fk__-stp-progress-bar' => [
          'font-size'=> '.65rem',
          'height'   => '.8rem'
        ],
      ],
    ];
    $this->overrideAllFormsStaticStyles($newStylesArray);
  }

  /**
  *  for adding fallback styles in form staticStyles object
  *
  * @return void
  */
  public function addStaticStyleForMultiStepContentFld(): void
  {
    $newStylesArray = [
      '._frm-b-stp-cntnt .btcd-fld-itm' => [
        'height' => 'fit-content',
      ]
    ];
    $this->overrideAllFormsStaticStyles($newStylesArray);
  }

  private function overrideAllFormsStaticStyles($newStylesArray)
  {
    $formModel = new FormModel();
    $forms = $formModel->get(
      ['id', 'form_content', 'builder_helper_state']
    );

    if (is_wp_error($forms)) {
      Log::debug_log('Error fetching forms for static styles override' . $forms->get_error_message());
      return;
    }
    foreach ($forms as $form) {
      $hasStyleChanged = false;
      $formId = $form->id;
      $builderHelperState = json_decode($form->builder_helper_state);
      if (!is_object($builderHelperState) || empty($builderHelperState->staticStyles)) {
        continue;
      }

      // Decode and validate form_content
      $formContent = json_decode($form->form_content);
      if (!is_object($formContent) || !isset($formContent->layout) || !is_array($formContent->layout)) {
        continue;
      }
      // Parse staticStyles safely
      try {
        $staticStyleState = Jcof::parse($builderHelperState->staticStyles);
      } catch (Exception $e) {
        Log::debug_log("Jcof::parse failed for form ID {$formId}: " . $e->getMessage());
        continue;
      }

      // Ensure staticStyles array exists
      if (!isset($staticStyleState['staticStyles']) || !is_array($staticStyleState['staticStyles'])) {
        $staticStyleState['staticStyles'] = [];
      }

      $newStyles = self::replaceKeys($newStylesArray, '__fk__', "b{$formId}");
      $staticStyleState['staticStyles'] = array_merge($staticStyleState['staticStyles'], $newStyles);

      // Convert and append CSS safely
      try {
        $generatedStyleCSS = Utilities::convertToCSS($newStyles);
        Utilities::appendCSS($formId, $generatedStyleCSS);
      } catch (Exception $e) {
        Log::debug_log("CSS generation/append failed for form ID {$formId}: " . $e->getMessage());
        continue;
      }

      // Stringify safely
      try {
        $builderHelperState->staticStyles = Jcof::stringify($staticStyleState);
      } catch (Exception $e) {
        Log::debug_log("Jcof::stringify failed for form ID {$formId}: " . $e->getMessage());
        continue;
      }

      // Save the updated builder_helper_state
      $updateResult = $formModel->update(
        [
          'builder_helper_state' => wp_json_encode($builderHelperState),
        ],
        [
          'id' => $formId,
        ]
      );

      if (false === $updateResult || is_wp_error($updateResult)) {
        Log::debug_log("Form update failed for form ID {$formId}");
      }
    }
  }

  /**
   * @method for replacing __fk__ with field key in styles array keys
   * @example: .__fk__-stripe-btn:disabled to .bk-1-stripe-btn:disabled
   *
   * @param string $fldKey
   * @param array $stylesArray
   * @return array $newStyleArray
   */
  private function replaceFldKey(string $fldKey, array $stylesArray): array
  {
    $newStyleArray = [];
    foreach ($stylesArray as $key => $value) {
      $key = str_replace('__fk__', $fldKey, $key);
      $newStyleArray[$key] = $value;
    }
    return $newStyleArray;
  }

  // public function signatureFldIframeStyle()
  // {
  //   $signatureIframeStyle = [
  //     '.__fk__-signature-iframe' => [
  //       'position'      => 'absolute',
  //       'top'           => 0,
  //       'left'          => 0,
  //       'width'         => '100%',
  //       'height'        => '100%',
  //       'opacity'       => 0,
  //       'pointer-events'=> 'none',
  //       'border'        => 'none',
  //     ]
  //   ];

  //   $this->addStyles('signature', $signatureIframeStyle);
  // }

  public static function replaceKeys(array $styles, string $search, string $replace): array
  {
    $updatedStyles = [];
    foreach ($styles as $key => $value) {
      $newKey = str_replace($search, $replace, $key);

      if (is_array($value)) {
        $value = self::replaceKeys($value, $search, $replace);
      }
      $updatedStyles[$newKey] = $value;
    }
    return $updatedStyles;
  }
}
