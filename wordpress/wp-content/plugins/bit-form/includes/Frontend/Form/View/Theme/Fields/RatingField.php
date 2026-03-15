<?php

namespace BitCode\BitForm\Frontend\Form\View\Theme\Fields;

use BitCode\BitForm\Core\Util\FrontendHelpers;

class RatingField
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

    $ratingOption = '';

    $checkedIndex = '';

    if (isset($field->opt)) {
      $defaultValue = isset($field->val) ? $field->val : '';
      foreach ($field->opt as $key => $opt) {
        if ($defaultValue && $defaultValue === $opt->val) {
          $checkedIndex = $key;
        } elseif (isset($opt->check) && true === $opt->check) {
          $checkedIndex = $key;
        }
      }

      $checkedCls = null;

      foreach ($field->opt as $key => $opt) {
        $val = $opt->val;
        $img = $opt->img;
        $lbl = $opt->lbl;
        $checked = '';

        // if ($defaultValue && $opt->val === $defaultValue) {
        //   $checked = "checked='checked'";
        // } elseif (isset($opt->check) && true === $opt->check) {
        //   $checked = "checked='{$opt->check}'";
        // } else {
        //   $checked = '';
        // }

        if ($defaultValue) {
          if ($opt->val === $defaultValue) {
            $checked = "checked='1'";
          }
        } else {
          if (isset($opt->check) && true === $opt->check) {
            $checked = "checked='{$opt->check}'";
          } else {
            $checked = '';
          }
        }

        if (!empty($checkedIndex) && $key <= $checkedIndex) {
          $checkedCls = $rowID . '-rating-selected';
        } else {
          $checkedCls = null;
        }
        $ratingOption .= sprintf(
          '        <label
            class="%1$s %2$s"
            for="%3$s-%4$s-rating-%5$s"
            %6$s
            data-indx="%5$s"
          >
            <input
              type="radio"
              class="%7$s %8$s"
              %9$s
              value=%10$s
              aria-label="%11$s"
              id="%3$s-%4$s-rating-%5$s"
              %12$s
              %13$s
              %14$s
            />
            <img
              class="%15$s %16$s %17$s"
              src="%18$s"
              alt="%11$s"
              aria-label="%11$s"
              %19$s
            />
          </label>',
          $fieldHelpers->getAtomicCls('rating-lbl'),
          $fieldHelpers->getCustomClasses('rating-lbl'),
          $rowID,
          $contentCount,
          $key,
          $fieldHelpers->getCustomAttributes('rating-lbl'),
          $fieldHelpers->getAtomicCls('rating-input'),
          $fieldHelpers->getCustomClasses('rating-input'),
          $name,
          $val,
          $lbl,
          $checked,
          $req,
          $fieldHelpers->getCustomAttributes('rating-input'),
          $fieldHelpers->getAtomicCls('rating-img'),
          $fieldHelpers->getCustomClasses('rating-img'),
          $checkedCls,
          $img,
          $fieldHelpers->getCustomAttributes('rating-img')
        );
      }
    }

    return sprintf(
      '<div
        %1$s
        class="%2$s %3$s"
      >
        <div
          %4$s
          class="%5$s %6$s"
          tabindex="0"
        >
%7$s
        </div>
        <span
          class="%8$s %9$s"
          %10$s
        >
        </span>
      </div>',
      $fieldHelpers->getCustomAttributes('inp-fld-wrp'),
      $fieldHelpers->getAtomicCls('inp-fld-wrp'),
      $fieldHelpers->getCustomClasses('inp-fld-wrp'),
      $fieldHelpers->getCustomAttributes('rating-wrp'),
      $fieldHelpers->getAtomicCls('rating-wrp'),
      $fieldHelpers->getCustomClasses('rating-wrp'),
      $ratingOption,
      $fieldHelpers->getAtomicCls('rating-msg'),
      $fieldHelpers->getCustomClasses('rating-msg'),
      $fieldHelpers->getCustomAttributes('rating-msg')
    );
  }
}
