<?php

namespace BitCode\BitForm\Frontend\Form\View\Conversational\Fields;

use BitCode\BitForm\Core\Util\FrontendHelpers;

class RatingField
{
  public static function init($field, $rowID, $field_name, $form_atomic_Cls_map, $formID, $error = null, $value = null)
  {
    $inputWrapper = new ConversationalInputWrapper($field, $rowID, $field_name, $form_atomic_Cls_map, $formID, $error, $value);
    $input = self::field($field, $rowID, $form_atomic_Cls_map, $formID, $value);
    return $inputWrapper->wrapper($input);
  }

  private static function field($field, $rowID, $form_atomic_Cls_map, $formID,  $value)
  {
    $fieldHelpers = new ConversationalFieldHelpers($formID, $field, $rowID, $form_atomic_Cls_map);

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
        // sprintf args order:
        // 1: label class, 2: rowID, 3: contentCount, 4: key, 5: label attrs
        // 6: input class, 7: name attr, 8: value, 9: label text, 10: checked, 11: required, 12: input attrs
        // 13: img class, 14: img src, 15: img attrs
        $ratingOption .= sprintf(
          '<label
          class="%1$s"
          for="%2$s-%3$s-rating-%4$s"
          %5$s
          data-indx="%4$s"
        >
               <input
                 type="radio"
                 class="%6$s"
                 %7$s
                 value="%8$s"
                 aria-label="%9$s"
                 id="%2$s-%3$s-rating-%4$s"
                 %10$s
                 %11$s
                 %12$s
               />
               <img
                 class="%13$s"
                 src="%14$s"
                 alt="%9$s"
                 aria-label="%9$s"
                 %15$s
               />
         </label>
',
          $fieldHelpers->getConversationalMultiCls('rating-lbl') . ' ' . $fieldHelpers->getCustomClasses('rating-lbl'),
          $rowID,
          $contentCount,
          $key,
          $fieldHelpers->getCustomAttributes('rating-lbl'),
          $fieldHelpers->getConversationalMultiCls('rating-input') . ' ' . $fieldHelpers->getCustomClasses('rating-input'),
          $name,
          $val,
          $lbl,
          $checked,
          $req,
          $fieldHelpers->getCustomAttributes('rating-input'),
          $fieldHelpers->getConversationalMultiCls('rating-img') . ' ' . $fieldHelpers->getCustomClasses('rating-img') . ' ' . $checkedCls,
          $img,
          $fieldHelpers->getCustomAttributes('rating-img')
        );
      }
    }

    return sprintf(
      '<div
      %1$s
      class="%2$s"
      >
           <div
             %3$s
             class="%4$s"
             tabindex="0"
           >
             %5$s      
           </div>
           <span
             class="%6$s"
             %7$s
           >
           </span>
    </div>',
      $fieldHelpers->getCustomAttributes('inp-fld-wrp'),
      $fieldHelpers->getConversationalMultiCls('inp-fld-wrp') . ' ' . $fieldHelpers->getCustomClasses('inp-fld-wrp'),
      $fieldHelpers->getCustomAttributes('rating-wrp'),
      $fieldHelpers->getConversationalMultiCls('rating-wrp') . ' ' . $fieldHelpers->getCustomClasses('rating-wrp'),
      $ratingOption,
      $fieldHelpers->getConversationalMultiCls('rating-msg') . ' ' . $fieldHelpers->getCustomClasses('rating-msg'),
      $fieldHelpers->getCustomAttributes('rating-msg')
    );
  }
}
