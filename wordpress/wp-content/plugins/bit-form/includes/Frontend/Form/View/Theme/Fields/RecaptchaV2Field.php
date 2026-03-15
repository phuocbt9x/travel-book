<?php

namespace BitCode\BitForm\Frontend\Form\View\Theme\Fields;

use BitCode\BitForm\Core\Integration\IntegrationHandler;

class RecaptchaV2Field
{
  public static function init($field, $rowID, $field_name, $form_atomic_Cls_map, $formID, $error = null, $value = null)
  {
    $integrationHandler = new IntegrationHandler(0);
    $allFormIntegrations = $integrationHandler->getAllIntegration('app', 'gReCaptcha');
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

    return sprintf(
      '<div
        %1$s
        class="%2$s %3$s"
      >
        <div class="%4$s-recaptcha-wrp">
          <div
            class="g-recaptcha"
            data-theme="%5$s"
            data-size="%6$s"
            data-sitekey="%7$s"
          >
          </div>
        </div>
      </div>',
      $fieldHelpers->getCustomAttributes('fld-wrp'),
      $fieldHelpers->getAtomicCls('fld-wrp'),
      $fieldHelpers->getCustomClasses('fld-wrp'),
      $rowID,
      $theme,
      $size,
      $fieldHelpers->esc_attr($siteKey)
    );
  }
}
