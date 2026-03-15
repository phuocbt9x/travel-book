<?php

namespace BitCode\BitForm\Frontend\Form\View\Theme\Fields;

use BitCode\BitForm\Core\Util\FieldValueHandler;

class TitleField
{
  public static function init($field, $rowID, $field_name, $form_atomic_Cls_map, $formID, $error = null, $value = null)
  {
    return self::field($field, $rowID, $field_name, $form_atomic_Cls_map, $formID, $error, $value);
  }

  private static function titleGenerator($tag, $text, $cls, $preIcn, $sufIcn, $fk, $field, $form_atomic_Cls_map)
  {
    $text = FieldValueHandler::replaceSmartTagWithValue($text);
    $fieldHelpers = new ClassicFieldHelpers($field, $fk, $form_atomic_Cls_map);

    return sprintf(
      '      <%1$s class="%2$s %3$s">
      %4$s
      %5$s
      %6$s
      </%1$s>',
      $tag,
      $fieldHelpers->getAtomicCls($cls),
      $fieldHelpers->getCustomClasses($cls),
      $preIcn,
      $fieldHelpers->kses_post($text),
      $sufIcn
    );
  }

  private static function iconImg($field, $object, $element, $fieldHelpers, $alt, $fk)
  {
    return sprintf(
      '<img 
        %1$s
        class="%2$s %3$s"
        src="%4$s" 
        alt="%5$s" 
      />',
      $fieldHelpers->getCustomAttributes($element),
      $fieldHelpers->getAtomicCls($element),
      $fieldHelpers->getCustomClasses($element),
      $fieldHelpers->esc_url($field->$object),
      $fieldHelpers->esc_attr($alt)
    );
  }

  private static function field($field, $rowID, $field_name, $form_atomic_Cls_map, $formID, $error = null, $value = null)
  {
    $fieldHelpers = new ClassicFieldHelpers($field, $rowID, $form_atomic_Cls_map);

    $logo = $fieldHelpers->icon('logo', 'logo');
    $titleHide = '';
    $subtitleHide = '';

    $titlePreIcn = '';
    $titleSufIcn = '';
    $subTitlPreIcn = '';
    $subTitlSufIcn = '';

    if (property_exists($field, 'titlePreIcn')) {
      $titlePreIcn = self::iconImg($field, 'titlePreIcn', 'title-pre-i', $fieldHelpers, 'Title Prefix Icon', $rowID);
    }

    if (property_exists($field, 'titleSufIcn')) {
      $titleSufIcn = self::iconImg($field, 'titleSufIcn', 'title-suf-i', $fieldHelpers, 'Title Suffix Icon', $rowID);
    }

    if (property_exists($field, 'subTitlPreIcn')) {
      $subTitlPreIcn = self::iconImg($field, 'subTitlPreIcn', 'sub-titl-pre-i', $fieldHelpers, 'Subtitle Prefix Icon', $rowID);
    }

    if (property_exists($field, 'subTitlSufIcn')) {
      $subTitlSufIcn = self::iconImg($field, 'subTitlSufIcn', 'sub-titl-suf-i', $fieldHelpers, 'Subtitle Suffix Icon', $rowID);
    }

    if (property_exists($field, 'titleHide') && !$field->titleHide) {
      $titleHide = self::titleGenerator($field->titleTag, $field->title, 'title', $titlePreIcn, $titleSufIcn, $rowID, $field, $form_atomic_Cls_map);
    }

    if (property_exists($field, 'subtitleHide') && !$field->subtitleHide) {
      $subtitleHide = self::titleGenerator($field->subTitleTag, $field->subtitle, 'sub-titl', $subTitlPreIcn, $subTitlSufIcn, $rowID, $field, $form_atomic_Cls_map);
    }

    return sprintf(
      '<div 
      %1$s
      class="%2$s %3$s"
    >
     %4$s
      <div
        %5$s
        class="%6$s %7$s"
      >
        %8$s
        %9$s
      </div>
    </div>',
      $fieldHelpers->getCustomAttributes('fld-wrp'),
      $fieldHelpers->getAtomicCls('fld-wrp'),
      $fieldHelpers->getCustomClasses('fld-wrp'),
      $logo,
      $fieldHelpers->getCustomAttributes('titl-wrp'),
      $fieldHelpers->getAtomicCls('titl-wrp'),
      $fieldHelpers->getCustomClasses('titl-wrp'),
      $titleHide,
      $subtitleHide
    );
  }
}
