<?php

namespace BitCode\BitForm\Frontend\Form\View\Theme\Fields;

use BitCode\BitForm\Admin\Form\Helpers;

class ImageField
{
  public static function init($field, $rowID, $field_name, $form_atomic_Cls_map, $formID, $error = null, $value = null)
  {
    $fieldHelpers = new ClassicFieldHelpers($field, $rowID, $form_atomic_Cls_map);
    $imgHeight = intval(Helpers::property_exists_nested($field, 'height', '', 1) ? $field->height : 100);
    $imgWidth = intval(Helpers::property_exists_nested($field, 'width', '', 1) ? $field->width : 40);

    $imgSrc = Helpers::property_exists_nested($field, 'bg_img', '', 1) ? $field->bg_img : "https://fakeimg.pl/{$imgWidth}x{$imgHeight}";
    $alt = Helpers::property_exists_nested($field, 'alt', '', 1) ? $field->alt : '';

    $img = sprintf(
      '<img
        %1$s
        class="%2$s %3$s"
        src="%4$s"
        alt="%5$s"
        width="%6$s"
        height="%7$s"
      />',
      $fieldHelpers->getCustomAttributes('img'),
      $fieldHelpers->getAtomicCls('img'),
      $fieldHelpers->getCustomClasses('img'),
      $fieldHelpers->esc_url($imgSrc),
      $fieldHelpers->esc_attr($alt),
      $fieldHelpers->esc_attr($imgWidth),
      $fieldHelpers->esc_attr($imgHeight)
    );

    return sprintf(
      '<div
        %1$s
        class="%2$s %3$s"
      >
        %4$s
      </div>',
      $fieldHelpers->getCustomAttributes('fld-wrp'),
      $fieldHelpers->getAtomicCls('fld-wrp'),
      $fieldHelpers->getCustomClasses('fld-wrp'),
      $img
    );
  }
}
