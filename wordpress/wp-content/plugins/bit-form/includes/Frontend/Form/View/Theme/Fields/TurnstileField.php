<?php

namespace BitCode\BitForm\Frontend\Form\View\Theme\Fields;

use BitCode\BitForm\Core\Integration\IntegrationHandler;

class TurnstileField
{
  public static function init($field, $rowID, $field_name, $form_atomic_Cls_map, $formID, $error = null, $value = null)
  {
    $integrationHandler = new IntegrationHandler(0);
    $allFormIntegrations = $integrationHandler->getAllIntegration('app', 'turnstileCaptcha');
    if (is_wp_error($allFormIntegrations)) {
      return '';
    }
    $siteKey = json_decode($allFormIntegrations[0]->integration_details)->siteKey;
    return self::field($field, $rowID, $field_name, $form_atomic_Cls_map, $formID, $siteKey, $error = null, $value = null);
  }

  private static function field($field, $rowID, $field_name, $form_atomic_Cls_map, $formID, $siteKey, $error = null, $value = null)
  {
    $fieldHelpers = new ClassicFieldHelpers($field, $rowID, $form_atomic_Cls_map);
    $theme = $field->config->theme;
    $size = $field->config->size;
    $language = $field->config->language;
    $appearance = $field->config->appearance;

    $interactiveCallback = '';
    $dNone = '';
    $script = '';

    $fldKeyWithContentId = $fieldHelpers->getFieldKeyWithContentCount();
    $fldKeyExceptHyphen = str_replace('-', '', $fldKeyWithContentId);

    $beforeInteractiveCallback = '';
    $afterInteractiveCallback = '';

    if ('interaction-only' === $appearance) {
      $beforeExcFuncName = 'bfBeforeInteractiveCallback' . $fldKeyExceptHyphen;
      $afterExcFuncName = 'bfAfterInteractiveCallback' . $fldKeyExceptHyphen;
      $dNone = 'd-none';
      $beforeInteractiveCallback = 'data-before-interactive-callback="' . $beforeExcFuncName . '"';
      $afterInteractiveCallback = 'data-after-interactive-callback="' . $afterExcFuncName . '"';
      $script = sprintf(
        '<script>
          function %1$s() {
            const bfTurnstileFldSelector = document.querySelector(\'.%2$s-turnstile-wrp\');
            if (bfTurnstileFldSelector) bfTurnstileFldSelector.parentElement.classList.remove(\'d-none\');
          }
          function %3$s() {
            const bfTurnstileFldSelector = document.querySelector(\'.%2$s-turnstile-wrp\');
            if (bfTurnstileFldSelector) bfTurnstileFldSelector.parentElement.classList.add(\'d-none\');
          }
        </script>',
        $beforeExcFuncName,
        $fldKeyWithContentId,
        $afterExcFuncName
      );
    }

    return sprintf(
      '%1$s
      <div
        %2$s
        class="%3$s %4$s %5$s"
      >
        <div class="%6$s-turnstile-wrp">
          <div
            class="cf-turnstile"
            %7$s
            %8$s
            data-appearance="%9$s"
            data-language="%10$s"
            data-theme="%11$s"
            data-size="%12$s"
            data-sitekey="%13$s"
          >
          </div>
        </div>
      </div>',
      $script,
      $fieldHelpers->getCustomAttributes('fld-wrp'),
      $fieldHelpers->getAtomicCls('fld-wrp'),
      $fieldHelpers->getCustomClasses('fld-wrp'),
      $dNone,
      $fldKeyWithContentId,
      $beforeInteractiveCallback,
      $afterInteractiveCallback,
      $appearance,
      $language,
      $theme,
      $size,
      $fieldHelpers->esc_attr($siteKey)
    );
  }
}
