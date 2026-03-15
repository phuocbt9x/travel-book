<?php

namespace BitCode\BitForm\Frontend\Form\View\Theme\Fields;

use BitCode\BitForm\Core\Util\FrontendHelpers;

class TextField
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
    $sugg = '';
    $req = $fieldHelpers->required();
    $disabled = $fieldHelpers->disabled();
    $readonly = $fieldHelpers->readonly();
    $inputMode = '';
    $name = $fieldHelpers->name();
    $ac = $fieldHelpers->autocomplete();
    $mx = '';
    $mn = '';
    $ph = $fieldHelpers->placeholder();
    $value = $fieldHelpers->value();
    $list = '';
    $bfFrontendFormIds = FrontendHelpers::$bfFrontendFormIds;
    $contentCount = count($bfFrontendFormIds);

    $onClickAttr = self::onClickAttr($field, $rowID);

    if (property_exists($field, 'suggestions') && count($field->suggestions) > 0) {
      $list = "list='{$rowID}-{$contentCount}-datalist'";
      $sugg .= "<datalist id='{$rowID}-{$contentCount}-datalist'>";
      foreach ($field->suggestions as $suggestion) {
        $val = (isset($suggestion->val) && !empty($suggestion->val)) ? $suggestion->val : $suggestion->lbl;
        $sugg .= "<option value='{$fieldHelpers->esc_attr($val)}'>{$fieldHelpers->esc_html($suggestion->lbl)}</option>";
      }
      $sugg .= '</datalist>';
    }

    if (property_exists($field, 'mn') && '' !== $field->mn) {
      $mn = "min='{$fieldHelpers->esc_attr($field->mn)}'";
    }

    if (property_exists($field, 'mx') && '' !== $field->mx) {
      $mx = "max='{$fieldHelpers->esc_attr($field->mx)}'";
    }

    if (property_exists($field, 'inputMode') && '' !== $field->inputMode) {
      $inputMode = "inputMode='{$fieldHelpers->esc_attr($field->inputMode)}'";
    }

    return sprintf(
      '<div %1$s class="%2$s %3$s">
      <input
        %4$s
        id="%5$s"
        %6$s
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
        %19$s
        %20$s
      />
      %21$s
      %22$s
    </div>
    %23$s',
      $fieldHelpers->getCustomAttributes('inp-fld-wrp'),
      $fieldHelpers->getAtomicCls('inp-fld-wrp'),
      $fieldHelpers->getCustomClasses('inp-fld-wrp'),
      $fieldHelpers->getCustomAttributes('fld'),
      "{$rowID}-{$contentCount}",
      $list,
      $fieldHelpers->getAtomicCls('fld'),
      $fieldHelpers->getCustomClasses('fld'),
      $field->typ,
      $req,
      $disabled,
      $readonly,
      $ph,
      $mn,
      $mx,
      $ac,
      $inputMode,
      $name,
      $value,
      $onClickAttr,
      $prefixIcn,
      $suffixIcn,
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
