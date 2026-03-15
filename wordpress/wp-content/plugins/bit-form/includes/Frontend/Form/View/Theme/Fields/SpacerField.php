<?php

namespace BitCode\BitForm\Frontend\Form\View\Theme\Fields;

class SpacerField
{
  public static function init($field, $rowID, $field_name, $form_atomic_Cls_map, $formID, $error = null, $value = null)
  {
    $fieldHelpers = new ClassicFieldHelpers($field, $rowID, $form_atomic_Cls_map);

    return sprintf(
      '<div
      %1$s
      class="%2$s %3$s"
    >
    </div>',
      $fieldHelpers->getCustomAttributes('fld-wrp'),
      $fieldHelpers->getAtomicCls('fld-wrp'),
      $fieldHelpers->getCustomClasses('fld-wrp')
    );
  }
}
