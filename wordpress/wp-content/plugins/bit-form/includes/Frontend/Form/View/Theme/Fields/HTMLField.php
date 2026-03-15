<?php

namespace BitCode\BitForm\Frontend\Form\View\Theme\Fields;

use BitCode\BitForm\Core\Util\FieldValueHandler;

class HTMLField
{
  public static function init($field, $rowID, $field_name, $form_atomic_Cls_map, $formID, $error = null, $value = null)
  {
    $fieldHelpers = new ClassicFieldHelpers($field, $rowID, $form_atomic_Cls_map);

    $content = '';
    if (isset($field->content) && !empty($field->content)) {
      $content = $field->content;
    } elseif ($fieldHelpers->property_exists_nested($field, 'info->content') && !empty($field->info->content)) {
      $content = $field->info->content;
    }

    $content = FieldValueHandler::replaceSmartTagWithValue($content);

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
      $fieldHelpers->renderHTMR($content)
    );
  }
}
