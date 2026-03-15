<?php

namespace BitCode\BitForm\Frontend\Form\View\Theme\Fields;

class ButtonField
{
  public static function init($field, $rowID, $field_name, $form_atomic_Cls_map, $formID, $error = null, $value = null)
  {
    $inputWrapper = new ClassicInputWrapper($field, $rowID, $field_name, $form_atomic_Cls_map, $formID, $error, $value);
    $button = self::field($field, $rowID, $form_atomic_Cls_map, $formID);
    return $inputWrapper->wrapper($button, true, true);
  }

  private static function getBtnClass($btnTyp)
  {
    switch ($btnTyp) {
      case 'save-draft':
        return 'bf-trigger-form-abandonment';
      case 'next-step':
        return 'next-step-btn';
      case 'previous-step':
        return 'prev-step-btn';
    }
    return '';
  }

  private static function getBtnTyp($btnTyp)
  {
    switch ($btnTyp) {
      case 'submit':
      case 'reset':
        return $btnTyp;
      default:
        return 'button';
    }
  }

  private static function field($field, $rowID, $form_atomic_Cls_map, $formID, $error = null, $value = null)
  {
    $fieldHelpers = new ClassicFieldHelpers($field, $rowID, $form_atomic_Cls_map);
    // $helperTxt = self::helperTxt($field, $rowID, $field_name, $form_atomic_Cls_map, $formID, $error = null, $value = null);

    $btnPreIcn = $fieldHelpers->icon('btnPreIcn', 'btn-pre-i');
    $btnSufIcn = $fieldHelpers->icon('btnSufIcn', 'btn-suf-i');
    $disabled = $fieldHelpers->disabled();
    // $name = $field->btnTyp === 'submit' ? 'name="bit-form-submit-btn"' : '';
    $name = $fieldHelpers->name();
    $btnSpinner = '<span class="bf-spinner d-none"></span>';
    $btnClass = self::getBtnClass($field->btnTyp);
    $btnTyp = self::getBtnTyp($field->btnTyp);

    return sprintf(
      '<div
        %1$s
        class="%2$s %3$s"
      >
        <button
          %4$s
          class="%5$s %6$s %7$s"
          type="%8$s"
          %9$s
          %10$s
        >
          %11$s
          %12$s
          %13$s
          %14$s
        </button>
      </div>',
      $fieldHelpers->getCustomAttributes('inp-fld-wrp'),                // 1
      $fieldHelpers->getAtomicCls('inp-fld-wrp'),                        // 2
      $fieldHelpers->getCustomClasses('inp-fld-wrp'),                    // 3
      $fieldHelpers->getCustomAttributes('btn'),                         // 4
      $fieldHelpers->getAtomicCls('btn'),                                // 5
      $fieldHelpers->getCustomClasses('btn'),                            // 6
      $btnClass,                                                         // 7
      $btnTyp,                                                           // 8
      $name,                                                             // 9
      $disabled,                                                         // 10
      $btnPreIcn,                                                        // 11
      $fieldHelpers->kses_post($fieldHelpers->renderHTMR($field->txt)),  // 12
      $btnSufIcn,                                                        // 13
      $btnSpinner                                                        // 14
    );
  }

  private static function helperTxt($field, $rowID, $field_name, $form_atomic_Cls_map, $formID, $error = null, $value = null)
  {
    $fieldHelpers = new ClassicFieldHelpers($field, $rowID, $form_atomic_Cls_map);
    $hlpPreIcn = $fieldHelpers->icon('hlpPreIcn', 'hlp-txt-pre-i');
    $hlpSufIcn = $fieldHelpers->icon('hlpSufIcn', 'hlp-txt-suf-i');
    $hlpTxt = isset($field->helperTxt) ? $fieldHelpers->renderHTMR($field->helperTxt) : '';

    $helperTxt = sprintf(
      '<div
        %1$s
        class="%2$s %3$s"
      >
        %4$s
        %5$s
        %6$s
      </div>',
      $fieldHelpers->getCustomAttributes('hlp-txt'),
      $fieldHelpers->getAtomicCls('hlp-txt'),
      $fieldHelpers->getCustomClasses('hlp-txt'),
      $hlpPreIcn,
      $fieldHelpers->kses_post($hlpTxt),
      $hlpSufIcn
    );

    return  property_exists($field, 'helperTxt') ? $helperTxt : '';
  }
}
