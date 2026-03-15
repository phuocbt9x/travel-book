<?php

namespace BitCode\BitForm\Frontend\Form\View\Theme\Fields;

use BitCode\BitForm\Core\Util\FrontendHelpers;

class DecisionBoxField
{
  public static function init($field, $rowID, $field_name, $form_atomic_Cls_map, $formID, $error = null, $value = null)
  {
    $inputWrapper = new ClassicInputWrapper($field, $rowID, $field_name, $form_atomic_Cls_map, $formID, $error, $value);
    $input = self::field($field, $rowID, $field_name, $form_atomic_Cls_map, $formID, $error, $value);
    return $inputWrapper->wrapper($input, true);
  }

  private static function field($field, $rowID, $field_name, $form_atomic_Cls_map, $formID, $error = null, $value = null)
  {
    $fieldHelpers = new ClassicFieldHelpers($field, $rowID, $form_atomic_Cls_map);

    $suggestions = '';
    $req = $fieldHelpers->required();
    $disabled = '';
    $readonly = '';
    $name = $fieldHelpers->name();
    $value = '';
    $bfFrontendFormIds = FrontendHelpers::$bfFrontendFormIds;
    $contentCount = count($bfFrontendFormIds);
    if ($fieldHelpers->property_exists_nested($field, 'msg->checked')) {
      $value = "value='{$field->msg->checked}'";
    }
    $checked = '';

    if ($fieldHelpers->property_exists_nested($field, 'valid->disabled', true)) {
      $disabled = 'disabled';
    }

    if ($fieldHelpers->property_exists_nested($field, 'valid->checked', true)) {
      $checked = 'checked';
    }

    if ($fieldHelpers->property_exists_nested($field, 'valid->readonly', true)) {
      $readonly = 'readonly';
    }

    $lbl = '';
    if (isset($field->lbl) && !empty($field->lbl)) {
      $lbl = $field->lbl;
    } elseif ($fieldHelpers->property_exists_nested($field, 'info->lbl') && !empty($field->info->lbl)) {
      $lbl = $field->info->lbl;
    }

    return sprintf(
      '<div
        %1$s
        class="%2$s %3$s"
      >
        <svg class="%4$s">
          <symbol id="%5$s-ck-svg" viewBox="0 0 12 10">
            <polyline
              class="%6$s"
              points="1.5 6 4.5 9 10.5 1"
            ></polyline>
          </symbol>
        </svg>

        <div
          %7$s
          class="%8$s %9$s"
        >
          <input
            id="%10$s-%11$s-decision"
            type="checkbox"
            class="%12$s"
            %13$s
            %14$s
            %15$s
            %16$s
            %17$s
            %18$s
          />
          <label
            %19$s
            data-cl
            for="%20$s-%21$s-decision"
            class="%22$s"
          >
            <span
              %23$s
              data-bx
              class="%24$s %25$s"
            >
              <svg width="12" height="10" viewBox="0 0 12 10" class="%26$s">
                <use data-ck-icn href="#%27$s-ck-svg" class="%28$s" />
              </svg>
            </span>
            <span
              %29$s
              class="%30$s %31$s"
            >
              %32$s
            </span>
          </label>
        </div>
      </div>',
      $fieldHelpers->getCustomAttributes('cc'),                   // 1
      $fieldHelpers->getAtomicCls('cc'),                          // 2
      $fieldHelpers->getCustomClasses('cc'),                      // 3
      $fieldHelpers->getAtomicCls('cks'),                         // 4
      $rowID,                                                     // 5
      $fieldHelpers->getAtomicCls('ck-svgline'),                  // 6
      $fieldHelpers->getCustomAttributes('cw'),                   // 7
      $fieldHelpers->getAtomicCls('cw'),                          // 8
      $fieldHelpers->getCustomClasses('cw'),                      // 9
      $rowID,                                                     // 10
      $contentCount,                                              // 11
      $fieldHelpers->getAtomicCls('ci'),                          // 12
      $disabled,                                                  // 13
      $readonly,                                                  // 14
      $req,                                                       // 15
      $name,                                                      // 16
      $checked,                                                   // 17
      $value,                                                     // 18
      $fieldHelpers->getCustomAttributes('cl'),                   // 19
      $rowID,                                                     // 20
      $contentCount,                                              // 21
      $fieldHelpers->getAtomicCls('cl'),                          // 22
      $fieldHelpers->getCustomAttributes('bx'),                   // 23
      $fieldHelpers->getAtomicCls('bx'),                          // 24
      $fieldHelpers->getAtomicCls('bx'),                          // 25
      $fieldHelpers->getAtomicCls('svgwrp'),                      // 26
      $rowID,                                                     // 27
      $fieldHelpers->getAtomicCls('ck-icn'),                      // 28
      $fieldHelpers->getCustomAttributes('ct'),                   // 29
      $fieldHelpers->getAtomicCls('ct'),                          // 30
      $fieldHelpers->getCustomClasses('ct'),                      // 31
      $fieldHelpers->kses_post($fieldHelpers->renderHTMR($lbl))   // 32
    );
  }
}
