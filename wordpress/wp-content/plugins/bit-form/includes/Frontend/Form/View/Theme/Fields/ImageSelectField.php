<?php

namespace BitCode\BitForm\Frontend\Form\View\Theme\Fields;

use BitCode\BitForm\Core\Util\FrontendHelpers;

class ImageSelectField
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

    $name = $fieldHelpers->name();
    $req = $fieldHelpers->required();

    $bfFrontendFormIds = FrontendHelpers::$bfFrontendFormIds;
    $contentCount = count($bfFrontendFormIds);

    $imageOption = '';
    if (!empty($field->val)) {
      $value = $field->val;
    }
    if (!empty($value) && false !== strpos($value, BITFORMS_BF_SEPARATOR)) {
      $defaultValues = explode(BITFORMS_BF_SEPARATOR, $value);
    } else {
      $defaultValues = isset($value) ? explode(',', $value) : [];
    }

    if (isset($field->opt)) {
      $checkedImg = $field->tickImgSrc;
      foreach ($field->opt as $key => $opt) {
        $val = isset($opt->val) ? $opt->val : $opt->lbl;
        $img = $opt->img;
        $lbl = $fieldHelpers->renderHTMR($opt->lbl);
        $checked = '';
        $inpType = $field->inpType;
        $imgAlt = $fieldHelpers->esc_html($lbl);
        $disabled = '';

        if ($fieldHelpers->property_exists_nested($opt, 'check', true)) {
          $checked = 'checked';
        } elseif (in_array($val, $defaultValues)) {
          $checked = 'checked';
        }

        if ($fieldHelpers->property_exists_nested($field, 'valid->disabled', true)
        || $fieldHelpers->property_exists_nested($opt, 'disabled', true)) {
          $disabled = 'disabled';
        }

        if (isset($field->mx) && !empty($field->valid->disableOnMax) && !$checked && ((int) $field->mx <= count($defaultValues))) {
          $disabled = 'disabled';
        }

        $optLblHide = '';

        if (!$field->optLblHide) {
          $optLblHide = sprintf(
            '<div
              class="%1$s %2$s"
              %3$s
            >
              <span
                class="%4$s %5$s"
                %6$s
              >
                %7$s
              </span>
            </div>',
            $fieldHelpers->getAtomicCls('tc'),
            $fieldHelpers->getCustomClasses('tc'),
            $fieldHelpers->getCustomAttributes('tc'),
            $fieldHelpers->getAtomicCls('img-title'),
            $fieldHelpers->getCustomClasses('img-title'),
            $fieldHelpers->getCustomAttributes('img-title'),
            $fieldHelpers->kses_post($lbl)
          );
        }

        // Input element
        $inputHtml = sprintf(
          '<input
            class="%1$s %2$s"
            type="%3$s"
            id="%4$s-%5$s-img-wrp-%6$s"
            %7$s
            value="%8$s"
            %9$s
            %10$s
            %11$s
            %12$s
          />',
          $fieldHelpers->getAtomicCls('img-inp'),
          $fieldHelpers->getCustomClasses('img-inp'),
          $inpType,
          $rowID,
          $contentCount,
          $key,
          $name,
          $fieldHelpers->esc_attr($val),
          $checked,
          $req,
          $disabled,
          $fieldHelpers->getCustomAttributes('img-inp')
        );

        // Check box with tick image
        $checkBoxHtml = sprintf(
          '<span
            class="%1$s %2$s"
            %3$s
          >
            <img
              src="%4$s"
              alt=""
              class="%5$s %6$s"
              %7$s
            />
          </span>',
          $fieldHelpers->getAtomicCls('check-box'),
          $fieldHelpers->getCustomClasses('check-box'),
          $fieldHelpers->getCustomAttributes('check-box'),
          $checkedImg,
          $fieldHelpers->getAtomicCls('check-img'),
          $fieldHelpers->getCustomClasses('check-img'),
          $fieldHelpers->getCustomAttributes('check-img')
        );

        // Image card wrapper with selectable image
        $imgCardHtml = sprintf(
          '<span
            class="%1$s %2$s"
            %3$s
          >
            <img
              src="%4$s"
              alt="%5$s"
              aria-label="%5$s"
              class="%6$s %7$s"
              %8$s
            />
            %9$s
          </span>',
          $fieldHelpers->getAtomicCls('img-card-wrp'),
          $fieldHelpers->getCustomClasses('img-card-wrp'),
          $fieldHelpers->getCustomAttributes('img-card-wrp'),
          $img,
          $imgAlt,
          $fieldHelpers->getAtomicCls('select-img'),
          $fieldHelpers->getCustomClasses('select-img'),
          $fieldHelpers->getCustomAttributes('select-img'),
          $optLblHide
        );

        // Label wrapper
        $labelHtml = sprintf(
          '<label
            for="%1$s-%2$s-img-wrp-%3$s"
            class="%4$s %5$s"
            %6$s
          >
            %7$s
            %8$s
          </label>',
          $rowID,
          $contentCount,
          $key,
          $fieldHelpers->getAtomicCls('img-wrp'),
          $fieldHelpers->getCustomClasses('img-wrp'),
          $fieldHelpers->getCustomAttributes('img-wrp'),
          $checkBoxHtml,
          $imgCardHtml
        );

        // Full option item
        $imageOption .= sprintf(
          '<div
            class="%1$s %2$s"
            %3$s
          >
            %4$s
            %5$s
          </div>',
          $fieldHelpers->getAtomicCls('inp-opt'),
          $fieldHelpers->getCustomClasses('inp-opt'),
          $fieldHelpers->getCustomAttributes('inp-opt'),
          $inputHtml,
          $labelHtml
        );
      }
    }

    return sprintf(
      '<div
        class="%1$s %2$s"
        %3$s
      >
        <div
          class="%4$s %5$s"
          %6$s
        >
          %7$s
        </div>
      </div>',
      $fieldHelpers->getAtomicCls('inp-fld-wrp'),
      $fieldHelpers->getCustomClasses('inp-fld-wrp'),
      $fieldHelpers->getCustomAttributes('inp-fld-wrp'),
      $fieldHelpers->getAtomicCls('ic'),
      $fieldHelpers->getCustomClasses('ic'),
      $fieldHelpers->getCustomAttributes('ic'),
      $imageOption
    );
  }
}
