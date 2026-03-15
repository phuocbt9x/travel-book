<?php

namespace BitCode\BitForm\Frontend\Form\View\Theme\Fields;

class RazorPayField
{
  public static function init($field, $rowID, $field_name, $form_atomic_Cls_map, $formID, $error = null, $value = null)
  {
    $inputWrapper = new ClassicInputWrapper($field, $rowID, $field_name, $form_atomic_Cls_map, $formID, $error, $value);
    $input = self::field($field, $rowID, $field_name, $form_atomic_Cls_map, $formID, $error, $value);
    return $inputWrapper->wrapper($input, true);
  }

  private static function field($field, $rowID, $field_name, $form_atomic_Cls_map, $formID, $error = null, $value = null)
  {
    $fieldHelpers = new ClassicFieldHelpers($field, $rowID, $form_atomic_Cls_map);
    $subTitl = '';

    if ($fieldHelpers->property_exists_nested($field, 'subTitl', true)) {
      $subTitl = sprintf(
        '<span
          %1$s
          class="%2$s %3$s"
        >
          Secured by Razorpay
        </span>',
        $fieldHelpers->getCustomAttributes('razorpay-btn-sub-title'),
        $fieldHelpers->getAtomicCls('razorpay-btn-sub-title'),
        $fieldHelpers->getCustomClasses('razorpay-btn-sub-title')
      );
    }

    return sprintf(
      '<div class="bf-form">
        <div class="%1$s">
          <button
            %2$s
            type="button"
            class="%3$s %4$s"
          >
            <svg width="18" height="20" viewBox="0 0 18 20" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path d="M7.077 6.476l-.988 3.569 5.65-3.589-3.695 13.54 3.752.004 5.457-20L7.077 6.476z" fill="#fff" />
              <path d="M1.455 14.308L0 20h7.202L10.149 8.42l-8.694 5.887z" fill="#fff" />
            </svg>
            <div
              %5$s
              class="%6$s %7$s"
            >
              <span
                %8$s
                class="%9$s %10$s"
              >
                %11$s
              </span>
              %12$s
            </div>
          </button>
        </div>
      </div>',
      $fieldHelpers->getAtomicCls('razorpay-wrp'),
      $fieldHelpers->getCustomAttributes('razorpay-btn'),
      $fieldHelpers->getAtomicCls('razorpay-btn'),
      $fieldHelpers->getCustomClasses('razorpay-btn'),
      $fieldHelpers->getCustomAttributes('razorpay-btn-text'),
      $fieldHelpers->getAtomicCls('razorpay-btn-text'),
      $fieldHelpers->getCustomClasses('razorpay-btn-text'),
      $fieldHelpers->getCustomAttributes('razorpay-btn-title'),
      $fieldHelpers->getAtomicCls('razorpay-btn-title'),
      $fieldHelpers->getCustomClasses('razorpay-btn-title'),
      $fieldHelpers->esc_html($fieldHelpers->renderHTMR($field->btnTxt)),
      $subTitl
    );
  }
}
