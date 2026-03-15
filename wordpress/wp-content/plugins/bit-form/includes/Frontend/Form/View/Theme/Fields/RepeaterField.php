<?php

namespace BitCode\BitForm\Frontend\Form\View\Theme\Fields;

use BitCode\BitForm\Frontend\Form\View\Theme\ThemeBase;

class RepeaterField
{
  public static function init($field, $rowID, $field_name, $form_atomic_Cls_map, $formID, $formViewerInstance,  $nestedLayout, $error = null, $value = null)
  {
    $inputWrapper = new ClassicInputWrapper($field, $rowID, $field_name, $form_atomic_Cls_map, $formID, $error, $value);
    $input = self::field($field, $rowID, $field_name, $formID, $formViewerInstance, $nestedLayout, $form_atomic_Cls_map, $value);
    return $inputWrapper->wrapper($input);
  }

  private static function field($field, $rowID, $field_name, $formID, $formViewerInstance, $nestedLayout, $form_atomic_Cls_map, $value)
  {
    $fieldHelpers = new ClassicFieldHelpers($field, $rowID, $form_atomic_Cls_map);

    $addBtnMarkup = '';
    $addToEndBtnMarkup = '';

    if (isset($field->addBtn->show) && $field->addBtn->show) {
      $addBtnPreIcn = $fieldHelpers->icon('addBtnPreIcn', 'rpt-add-btn-pre-i');
      $addBtnSufIcn = $fieldHelpers->icon('addBtnSufIcn', 'rpt-add-btn-suf-i');

      $addBtnMarkup = sprintf(
        '<button
          %1$s
          class="%2$s %3$s"
          type="%4$s"
          data-parent-field-name="%5$s"
        >
          %6$s
          %7$s
          %8$s
        </button>',
        $fieldHelpers->getCustomAttributes('rpt-add-btn'),
        $fieldHelpers->getAtomicCls('rpt-add-btn'),
        $fieldHelpers->getCustomClasses('rpt-add-btn'),
        $field->addBtn->btnTyp,
        $field->fieldName,
        $addBtnPreIcn,
        $fieldHelpers->kses_post($fieldHelpers->renderHTMR($field->addBtn->txt)),
        $addBtnSufIcn
      );
    };

    if (isset($field->addToEndBtn->show) && $field->addToEndBtn->show) {
      $addToEndBtnPreIcn = $fieldHelpers->icon('addToEndBtnPreIcn', 'add-to-end-btn-pre-i');
      $addToEndBtnSufIcn = $fieldHelpers->icon('addToEndBtnSufIcn', 'add-to-end-btn-suf-i');

      $addToEndBtnMarkup = sprintf(
        '<div 
          %1$s
          class="%2$s %3$s"
        >       
          <button
            %4$s
            class="%5$s %6$s"
            type="%7$s"
            data-parent-field-name="%8$s"
          >
            %9$s
            %10$s
            %11$s
          </button>
        </div>',
        $fieldHelpers->getCustomAttributes('add-to-end-btn-wrp'),
        $fieldHelpers->getAtomicCls('add-to-end-btn-wrp'),
        $fieldHelpers->getCustomClasses('add-to-end-btn-wrp'),
        $fieldHelpers->getCustomAttributes('add-to-end-btn'),
        $fieldHelpers->getAtomicCls('add-to-end-btn'),
        $fieldHelpers->getCustomClasses('add-to-end-btn'),
        $field->addToEndBtn->btnTyp,
        $field->fieldName,
        $addToEndBtnPreIcn,
        $fieldHelpers->kses_post($fieldHelpers->renderHTMR($field->addToEndBtn->txt)),
        $addToEndBtnSufIcn
      );
    };

    $removeBtnPreIcn = $fieldHelpers->icon('removeBtnPreIcn', 'rpt-rmv-btn-pre-i');
    $removeBtnSufIcn = $fieldHelpers->icon('removeBtnSufIcn', 'rpt-rmv-btn-suf-i');

    $themeBase = new ThemeBase();
    $fieldHtml = '';
    if (isset($nestedLayout->lg)) {
      foreach ($nestedLayout->lg as $row) {
        $fieldHtml .= $themeBase->inputWrapper($formViewerInstance, $row->i);
      }
    }

    $repeatableWrap = sprintf(
      '<div 
        %1$s
        class="%2$s %3$s"
      >
        <div 
          %4$s
          class="%5$s %6$s"
        >   
          <div class="_frm-b%7$s repeater-grid">
            %8$s
          </div>
        </div>
        <div 
          %9$s
          class="%10$s %11$s"
        >
          %12$s
          <button
            %13$s
            class="%14$s %15$s"
            type="%16$s"
            data-parent-field-name="%17$s"
          >
            %18$s
            %19$s
            %20$s
          </button>
        </div>
      </div>',
      $fieldHelpers->getCustomAttributes('rpt-wrp'),
      $fieldHelpers->getAtomicCls('rpt-wrp'),
      $fieldHelpers->getCustomClasses('rpt-wrp'),
      $fieldHelpers->getCustomAttributes('rpt-grid-wrp'),
      $fieldHelpers->getAtomicCls('rpt-grid-wrp'),
      $fieldHelpers->getCustomClasses('rpt-grid-wrp'),
      $formID,
      $fieldHtml,
      $fieldHelpers->getCustomAttributes('pair-btn-wrp'),
      $fieldHelpers->getAtomicCls('pair-btn-wrp'),
      $fieldHelpers->getCustomClasses('pair-btn-wrp'),
      $addBtnMarkup,
      $fieldHelpers->getCustomAttributes('rpt-rmv-btn'),
      $fieldHelpers->getAtomicCls('rpt-rmv-btn'),
      $fieldHelpers->getCustomClasses('rpt-rmv-btn'),
      $field->removeBtn->btnTyp,
      $field->fieldName,
      $removeBtnPreIcn,
      $fieldHelpers->renderHTMR($field->removeBtn->txt),
      $removeBtnSufIcn
    );

    $defaultRow = isset($field->defaultRow) ? intval($field->defaultRow) : 1;
    if (isset($field->maxRow) && $defaultRow > intval($field->maxRow)) {
      $defaultRow = intval($field->maxRow);
    }

    if (isset($field->minRow) && $defaultRow < intval($field->minRow)) {
      $defaultRow = intval($field->minRow);
    }
    $repeatedRow = $repeatableWrap;
    while ($defaultRow > 1) {
      $repeatedRow .= $repeatableWrap;
      $defaultRow--;
    }

    return sprintf(
      '<div 
      %1$s
      class="%2$s %3$s"
    >
      <div 
        %4$s
        class="%5$s %6$s"
      >
        %7$s
        %8$s
        <input
          type="text"
          class="d-none"
          title="Rpeater Index Hidden Input"
          name="%9$s"
          value=""
        />
      </div>
    </div>',
      $fieldHelpers->getCustomAttributes('inp-fld-wrp'),
      $fieldHelpers->getAtomicCls('inp-fld-wrp'),
      $fieldHelpers->getCustomClasses('inp-fld-wrp'),
      $fieldHelpers->getCustomAttributes('rpt-fld-wrp'),
      $fieldHelpers->getAtomicCls('rpt-fld-wrp'),
      $fieldHelpers->getCustomClasses('rpt-fld-wrp'),
      $repeatedRow,
      $addToEndBtnMarkup,
      $fieldHelpers->esc_attr($field->fieldName . '-repeat-index')
    );
  }
}
