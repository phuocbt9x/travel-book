<?php

namespace BitCode\BitForm\Frontend\Form\View\Theme\Fields;

class DividerField
{
  public static function init($field, $rowID, $field_name, $form_atomic_Cls_map, $formID, $error = null, $value = null)
  {
    $fieldHelpers = new ClassicFieldHelpers($field, $rowID, $form_atomic_Cls_map);

    return sprintf(
      '<div
        %1$s
        class="%2$s %3$s"
      >
        <div
          %4$s
          class="%5$s %6$s"
        ></div>
      </div>',
      $fieldHelpers->getCustomAttributes('fld-wrp'),  // 1
      $fieldHelpers->getAtomicCls('fld-wrp'),         // 2
      $fieldHelpers->getCustomClasses('fld-wrp'),     // 3
      $fieldHelpers->getCustomAttributes('divider'),  // 4
      $fieldHelpers->getAtomicCls('divider'),         // 5
      $fieldHelpers->getCustomClasses('divider')      // 6
    );
  }
}
