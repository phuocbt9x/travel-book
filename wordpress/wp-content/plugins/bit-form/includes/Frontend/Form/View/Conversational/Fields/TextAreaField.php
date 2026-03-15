<?php

namespace BitCode\BitForm\Frontend\Form\View\Conversational\Fields;

use BitCode\BitForm\Core\Util\FrontendHelpers;

class TextAreaField
{
  public static function init($field, $rowID, $field_name, $form_atomic_Cls_map, $formID, $error = null, $value = null)
  {
    $inputWrapper = new ConversationalInputWrapper($field, $rowID, $field_name, $form_atomic_Cls_map, $formID, $error, $value);
    $input = self::field($formID, $field, $rowID, $form_atomic_Cls_map, $value);
    return $inputWrapper->wrapper($input);
  }

  private static function field($formID, $field, $rowID, $form_atomic_Cls_map, $value)
  {
    $fieldHelpers = new ConversationalFieldHelpers($formID, $field, $rowID, $form_atomic_Cls_map);

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
    $inputWrapperCustomAttributes = $fieldHelpers->getCustomAttributes('inp-fld-wrp');
    $inputWrapperClass = $fieldHelpers->getConversationalCls('inp-fld-wrp') . ' ' . $fieldHelpers->getCustomClasses('inp-fld-wrp');
    $fieldCustomAttributes = $fieldHelpers->getCustomAttributes('fld');
    $fieldClass = $fieldHelpers->getConversationalCls('focus-elm') . ' ' . $fieldHelpers->getConversationalMultiCls('fld') . ' ' . $fieldHelpers->getCustomClasses('fld');
    $id = "{$rowID}-{$contentCount}";
    $escapedValue = $fieldHelpers->esc_textarea($value);

    return '<div
      ' . $inputWrapperCustomAttributes . '
      class="' . $inputWrapperClass . '"
    >
      <textarea
        ' . $fieldCustomAttributes . '
        id="' . $id . '"
        class="' . $fieldClass . '"
        ' . $req . '
        ' . $disabled . '
        ' . $readonly . '
        ' . $ph . '
        ' . $ac . '
        ' . $name . '
      >' . $escapedValue . '</textarea>
      ' . $prefixIcn . '
      ' . $suffixIcn . '
    </div>';
  }
}
