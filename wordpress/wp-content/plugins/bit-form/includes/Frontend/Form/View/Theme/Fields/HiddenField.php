<?php

namespace BitCode\BitForm\Frontend\Form\View\Theme\Fields;

use BitCode\BitForm\Core\Util\FrontendHelpers;

class HiddenField
{
  public static function init($field, $rowID, $field_name, $form_atomic_Cls_map, $formID, $error = null, $value = null)
  {
    $inputWrapper = new ClassicInputWrapper($field, $rowID, $field_name, $form_atomic_Cls_map, $formID, $error, $value);
    $input = self::field($field, $rowID, $form_atomic_Cls_map, $value);
    return $inputWrapper->wrapper($input, true, true);
  }

  private static function field($field, $rowID, $form_atomic_Cls_map, $value)
  {
    $fieldHelpers = new ClassicFieldHelpers($field, $rowID, $form_atomic_Cls_map);

    $sugg = '';
    $req = $fieldHelpers->required();
    $disabled = $fieldHelpers->disabled();
    $readonly = $fieldHelpers->readonly();
    $inputMode = '';
    $name = $fieldHelpers->name();
    $ac = $fieldHelpers->autocomplete();
    $ph = $fieldHelpers->placeholder();
    $value = $fieldHelpers->value();
    $bfFrontendFormIds = FrontendHelpers::$bfFrontendFormIds;
    $contentCount = count($bfFrontendFormIds);

    $onClickAttr = self::onClickAttr($field, $rowID);

    return sprintf(
      '<div
        %1$s
        class="%2$s %3$s"
      >
        <input
          %4$s
          id="%5$s-%6$s"
          class="%7$s %8$s"
          type="%9$s"
          %10$s
          %11$s
          %12$s
          %13$s
          %14$s
          %15$s
          %16$s
          %17$s
          %18$s
        />
      </div>
      %19$s',
      $fieldHelpers->getCustomAttributes('inp-fld-wrp'),
      $fieldHelpers->getAtomicCls('inp-fld-wrp'),
      $fieldHelpers->getCustomClasses('inp-fld-wrp'),
      $fieldHelpers->getCustomAttributes('fld'),
      $rowID,
      $contentCount,
      $fieldHelpers->getAtomicCls('fld'),
      $fieldHelpers->getCustomClasses('fld'),
      $field->typ,
      $req,
      $disabled,
      $readonly,
      $ph,
      $ac,
      $inputMode,
      $name,
      $value,
      $onClickAttr,
      $sugg
    );
  }

  private static function onClickAttr($field, $rowID)
  {
    $onClickAttr = '';
    $dateType = ['date', 'datetime-local', 'month', 'time', 'week'];
    //field type check
    if (in_array($field->typ, $dateType)) {
      $onClickAttr = "onclick='this.showPicker();'";
    }
    return $onClickAttr;
  }
}
