<?php

namespace BitCode\BitForm\Frontend\Form\View\Theme\Fields;

use BitCode\BitForm\Core\Util\FrontendHelpers;

class AdvanceDateTimeField
{
  public static function init($field, $rowID, $field_name, $form_atomic_Cls_map, $formID, $error = null, $value = null)
  {
    $inputWrapper = new ClassicInputWrapper($field, $rowID, $field_name, $form_atomic_Cls_map, $formID, $error, $value);
    $input = self::field($field, $rowID, $field_name, $form_atomic_Cls_map, $formID, $error, $value);
    return $inputWrapper->wrapper($input);
  }

  private static function field($field, $rowID, $field_name, $form_atomic_Cls_map, $formID, $error = null, $value = null)
  {
    $fieldHelpers = new ClassicFieldHelpers($field, $rowID, $form_atomic_Cls_map);
    $prefixIcn = $fieldHelpers->icon('prefixIcn', 'pre-i');
    $suffixIcn = $fieldHelpers->icon('suffixIcn', 'suf-i');
    $name = $fieldHelpers->name();
    $disabled = $fieldHelpers->disabled();
    $readonly = $fieldHelpers->readonly();
    $bfFrontendFormIds = FrontendHelpers::$bfFrontendFormIds;
    $contentCount = count($bfFrontendFormIds);
    $ph = $fieldHelpers->placeholder();
    $ac = $fieldHelpers->autocomplete();

    return sprintf(
      '<div %1$s
      class="%2$s %3$s"
    >
      <input
        %4$s
        id="%5$s-%6$s"
        class="%7$s-advanced-datetime %8$s %9$s bf-advanced-datetime-hidden-input"
        type="text"
        autocomplete="off"
        %10$s
        %11$s
        %12$s
        %13$s
        %14$s
        %15$s
      />
      %16$s
      %17$s
    </div>',
      $fieldHelpers->getCustomAttributes('inp-fld-wrp'), // 1
      $fieldHelpers->getAtomicCls('inp-fld-wrp'),        // 2
      $fieldHelpers->getCustomClasses('inp-fld-wrp'),    // 3
      $fieldHelpers->getCustomAttributes('fld'),         // 4
      $rowID,                                           // 5
      $contentCount,                                    // 6
      $rowID,                                           // 7
      $fieldHelpers->getAtomicCls('fld'),                // 8
      $fieldHelpers->getCustomClasses('fld'),            // 9
      $name,                                            // 10
      $value,                                           // 11
      $ph,                                              // 12
      $ac,                                              // 13
      $disabled,                                        // 14
      $readonly,                                        // 15
      $prefixIcn,                                       // 16
      $suffixIcn                                        // 17
    );
  }
}
