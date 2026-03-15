<?php

namespace BitCode\BitForm\Frontend\Form\View\Theme\Fields;

class AdvanceFileUpField
{
  public static function init($field, $rowID, $field_name, $form_atomic_Cls_map, $formID, $error = null, $value = null)
  {
    $inputWrapper = new ClassicInputWrapper($field, $rowID, $field_name, $form_atomic_Cls_map, $formID, $error, $value);
    $input = self::field($field, $rowID, $field_name, $form_atomic_Cls_map, $formID, $error, $value);
    return $inputWrapper->wrapper($input);
  }

  private static function field($field, $rowID, $field_name, $form_atomic_Cls_map, $formID, $error = null, $value = null)
  {
    $fh = new ClassicFieldHelpers($field, $rowID, $form_atomic_Cls_map);
    $name = $fh->name();
    $readonlyCls = isset($field->readonly) ? 'readonly' : '';
    $disabledCls = isset($field->disabled) ? 'disabled' : '';

    return sprintf(
      '<input
        hidden
        id="%1$s"
        type="file"
        class="filepond"
        %2$s
        %3$s
        %4$s
      />
      <div
        id="filepond-%5$s-container"
        class="%6$s %7$s %8$s"
      ></div>',
      $rowID,
      $name,
      $fh->readonly(),
      $fh->disabled(),
      $rowID,
      $fh->getAtomicCls("filepond-{$rowID}-container"),
      $readonlyCls,
      $disabledCls
    );
  }
}
