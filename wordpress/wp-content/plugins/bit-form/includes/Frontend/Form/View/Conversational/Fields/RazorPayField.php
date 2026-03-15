<?php

namespace BitCode\BitForm\Frontend\Form\View\Conversational\Fields;

class RazorPayField
{
  public static function init($field, $rowID, $field_name, $form_atomic_Cls_map, $formID, $error = null, $value = null)
  {
    $inputWrapper = new ConversationalInputWrapper($field, $rowID, $field_name, $form_atomic_Cls_map, $formID, $error, $value);
    $input = self::field($field, $rowID, $field_name, $form_atomic_Cls_map, $formID, $error, $value);
    return $inputWrapper->wrapper($input, true);
  }

  private static function field($field, $rowID, $field_name, $form_atomic_Cls_map, $formID, $error = null, $value = null)
  {
    $fieldHelpers = new ConversationalFieldHelpers($formID, $field, $rowID, $form_atomic_Cls_map);
    $subTitl = '';

    if ($fieldHelpers->property_exists_nested($field, 'subTitl', true)) {
      $subTitl = sprintf(
        '<span
             %1$s
            class="%2$s"
         >
           Secured by Razorpay
        </span>',
        $fieldHelpers->getCustomAttributes('razorpay-btn-sub-title'),
        $fieldHelpers->getConversationalCls('razorpay-btn-sub-title') . ' ' . $fieldHelpers->getCustomClasses('razorpay-btn-sub-title')
      );
    }

    return sprintf(
      '<div class="bf-form">
           <div class="%1$s">
                <button
                  %2$s
                  type="button"
                  class="%3$s"
                >
                  <svg width="18" height="20" viewBox="0 0 18 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M7.077 6.476l-.988 3.569 5.65-3.589-3.695 13.54 3.752.004 5.457-20L7.077 6.476z" fill="#fff" />
                    <path d="M1.455 14.308L0 20h7.202L10.149 8.42l-8.694 5.887z" fill="#fff" />
                  </svg>
                  <div
                    %4$s
                    class="%5$s"
                  >
                    <span
                      %6$s
                      class="%7$s"
                    >
                      %8$s
                    </span>
                    %9$s
                  </div>
                </button>
            </div>
      </div>',
      $fieldHelpers->getConversationalCls('razorpay-wrp'),
      $fieldHelpers->getCustomAttributes('razorpay-btn'),
      $fieldHelpers->getConversationalCls('razorpay-btn') . ' ' . $fieldHelpers->getCustomClasses('razorpay-btn'),
      $fieldHelpers->getCustomAttributes('razorpay-btn-text'),
      $fieldHelpers->getConversationalCls('razorpay-btn-text') . ' ' . $fieldHelpers->getCustomClasses('razorpay-btn-text'),
      $fieldHelpers->getCustomAttributes('razorpay-btn-title'),
      $fieldHelpers->getConversationalCls('razorpay-btn-title') . ' ' . $fieldHelpers->getCustomClasses('razorpay-btn-title'),
      $fieldHelpers->esc_html($fieldHelpers->renderHTMR($field->btnTxt)),
      $subTitl
    );
  }
}
