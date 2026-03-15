<?php

namespace BitCode\BitForm\Frontend\Form\View\Theme\Fields;

use BitCode\BitForm\Core\Util\FrontendHelpers;

class GDPRAgreementField
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

    // SVG symbol for checkbox tick
    $svgSymbol = sprintf(
      '<svg class="%1$s">
        <symbol id="%2$s-ck-svg" viewBox="0 0 12 10">
          <polyline
            class="%3$s"
            points="1.5 6 4.5 9 10.5 1"
          ></polyline>
        </symbol>
      </svg>',
      $fieldHelpers->getAtomicCls('cks'),
      $rowID,
      $fieldHelpers->getAtomicCls('ck-svgline')
    );

    // Checkbox input element
    $inputHtml = sprintf(
      '<input
        id="%1$s-%2$s-gdpr"
        type="checkbox"
        class="%3$s"
        %4$s
        %5$s
        %6$s
        %7$s
        %8$s
        %9$s
      />',
      $rowID,
      $contentCount,
      $fieldHelpers->getAtomicCls('ci'),
      $disabled,
      $readonly,
      $req,
      $name,
      $checked,
      $value
    );

    // Checkbox box with SVG icon
    $checkBoxHtml = sprintf(
      '<span
        %1$s
        data-bx
        class="%2$s %2$s"
      >
        <svg
          width="12"
          height="10"
          viewBox="0 0 12 10"
          class="%3$s"
        >
          <use
            data-ck-icn
            href="#%4$s-ck-svg"
            class="%5$s"
          />
        </svg>
      </span>',
      $fieldHelpers->getCustomAttributes('bx'),
      $fieldHelpers->getAtomicCls('bx'),
      $fieldHelpers->getAtomicCls('svgwrp'),
      $rowID,
      $fieldHelpers->getAtomicCls('ck-icn')
    );

    // Label text span
    $labelTextHtml = sprintf(
      '<span
        %1$s
        class="%2$s %3$s"
      >
        %4$s
      </span>',
      $fieldHelpers->getCustomAttributes('ct'),
      $fieldHelpers->getAtomicCls('ct'),
      $fieldHelpers->getCustomClasses('ct'),
      $fieldHelpers->kses_post($fieldHelpers->renderHTMR($lbl))
    );

    // Label wrapper
    $labelHtml = sprintf(
      '<label
        %1$s
        data-cl
        for="%2$s-%3$s-gdpr"
        class="%4$s"
      >
        %5$s
        %6$s
      </label>',
      $fieldHelpers->getCustomAttributes('cl'),
      $rowID,
      $contentCount,
      $fieldHelpers->getAtomicCls('cl'),
      $checkBoxHtml,
      $labelTextHtml
    );

    // Checkbox wrapper div
    $checkboxWrapperHtml = sprintf(
      '<div
        %1$s
        class="%2$s %3$s"
      >
        %4$s
        %5$s
      </div>',
      $fieldHelpers->getCustomAttributes('cw'),
      $fieldHelpers->getAtomicCls('cw'),
      $fieldHelpers->getCustomClasses('cw'),
      $inputHtml,
      $labelHtml
    );

    // Final container
    return sprintf(
      '<div
        %1$s
        class="%2$s %3$s"
      >
        %4$s
        %5$s
      </div>',
      $fieldHelpers->getCustomAttributes('cc'),
      $fieldHelpers->getAtomicCls('cc'),
      $fieldHelpers->getCustomClasses('cc'),
      $svgSymbol,
      $checkboxWrapperHtml
    );
  }
}
