<?php

namespace BitCode\BitForm\Frontend\Form\View\Theme\Fields;

use BitCode\BitForm\Core\Util\FrontendHelpers;

class TextAreaField
{
  public static function init($field, $rowID, $field_name, $form_atomic_Cls_map, $formID, $error = null, $value = null)
  {
    $inputWrapper = new ClassicInputWrapper($field, $rowID, $field_name, $form_atomic_Cls_map, $formID, $error, $value);
    $input = self::field($field, $rowID, $form_atomic_Cls_map, $value);
    return $inputWrapper->wrapper($input);
  }

  private static function field($field, $rowID, $form_atomic_Cls_map, $value)
  {
    $fieldHelpers = new ClassicFieldHelpers($field, $rowID, $form_atomic_Cls_map);

    $prefixIcn = $fieldHelpers->icon('prefixIcn', 'pre-i');
    $suffixIcn = $fieldHelpers->icon('suffixIcn', 'suf-i');
    $req = $fieldHelpers->required();
    $disabled = $fieldHelpers->disabled();
    $readonly = $fieldHelpers->readonly();
    $name = $fieldHelpers->name();
    $ac = $fieldHelpers->autocomplete();
    $ph = $fieldHelpers->placeholder();
    $value = $fieldHelpers->value();
    $bfFrontendFormIds = FrontendHelpers::$bfFrontendFormIds;
    $contentCount = count($bfFrontendFormIds);

    // {...'disabled' in attr.valid && { readOnly: attr.valid.disabled }}

    return sprintf(
      '<div %1$s class="%2$s %3$s">
      <textarea
        %4$s
        id="%5$s"
        class="%6$s %7$s"
        %8$s
        %9$s
        %10$s
        %11$s
        %12$s
        %13$s
      >%14$s</textarea>
      %15$s
      %16$s
    </div>',
      $fieldHelpers->getCustomAttributes('inp-fld-wrp'),
      $fieldHelpers->getAtomicCls('inp-fld-wrp'),
      $fieldHelpers->getCustomClasses('inp-fld-wrp'),
      $fieldHelpers->getCustomAttributes('fld'),
      "{$rowID}-{$contentCount}",
      $fieldHelpers->getAtomicCls('fld'),
      $fieldHelpers->getCustomClasses('fld'),
      $req,
      $disabled,
      $readonly,
      $ph,
      $ac,
      $name,
      $fieldHelpers->esc_textarea($value),
      $prefixIcn,
      $suffixIcn
    );
  }
}
