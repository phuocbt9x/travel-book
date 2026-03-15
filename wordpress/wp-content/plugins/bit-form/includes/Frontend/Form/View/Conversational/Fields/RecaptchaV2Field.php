<?php

namespace BitCode\BitForm\Frontend\Form\View\Conversational\Fields;

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
    $fieldHelpers = new ConversationalFieldHelpers($formID, $field, $rowID, $form_atomic_Cls_map);
    $theme = $field->config->theme;
    $size = $field->config->size;

    return sprintf(
      '<div
        %1$s
        class="%2$s"
      >
        <div class="%3$s-recaptcha-wrp">
          <div
            class="g-recaptcha"
            data-theme="%4$s"
            data-size="%5$s"
            data-sitekey="%6$s"
          >
          </div>
        </div>
      </div>',
      $fieldHelpers->getCustomAttributes('fld-wrp'),
      $fieldHelpers->getConversationalCls('fld-wrp') . ' ' . $fieldHelpers->getCustomClasses('fld-wrp'),
      $rowID,
      $theme,
      $size,
      $fieldHelpers->esc_attr($siteKey)
    );
  }
}
