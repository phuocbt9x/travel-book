<?php

namespace BitCode\BitForm\Frontend\Form\View\Conversational\Fields;

use BitCode\BitForm\Core\Util\FrontendHelpers;

class HtmlSelectField
{
  public static function init($field, $rowID, $field_name, $form_atomic_Cls_map, $formID, $error = null, $value = null)
  {
    $inputWrapper = new ConversationalInputWrapper($field, $rowID, $field_name, $form_atomic_Cls_map, $formID, $error, $value);
    $input = self::field($field, $rowID, $form_atomic_Cls_map, $formID, $value);
    return $inputWrapper->wrapper($input);
  }

  private static function checkSelected($opt, $val)
  {
    $selected = '';
    if (isset($opt->val) && $opt->val === $val) {
      $selected = 'selected';
    } elseif (isset($opt->lbl) && $opt->lbl === $val) {
      $selected = 'selected';
    } elseif (property_exists($opt, 'check')) {
      $selected = 'selected';
    }
    return $selected;
  }

  private static function field($field, $rowID, $form_atomic_Cls_map, $formID, $val = null)
  {
    $fieldHelpers = new ConversationalFieldHelpers($formID, $field, $rowID, $form_atomic_Cls_map);
    $disabled = $fieldHelpers->disabled();
    $readonly = $fieldHelpers->readonly();
    $name = $fieldHelpers->name();
    $value = '';
    if ($val) {
      $value = $val;
    } elseif (isset($field->val)) {
      $value = $field->val;
    }

    $readonlyCls = $readonly ? 'readonly' : '';
    $phHide = '';
    $optionsHTML = '';
    $bfFrontendFormIds = FrontendHelpers::$bfFrontendFormIds;
    $contentCount = count($bfFrontendFormIds);
    if ($fieldHelpers->property_exists_nested($field, 'phHide', true)) {
      $phHide = sprintf(
        '<option
          %1$s
          class="%2$s"
          value
        >
          %3$s
        </option>',
        $fieldHelpers->getCustomAttributes('slct-optn'),
        $fieldHelpers->getConversationalCls('slct-optn') . ' ' . $fieldHelpers->getCustomClasses('slct-optn'),
        $fieldHelpers->kses_post($field->ph)
      );
    }

    if (property_exists($field, 'opt')) {
      foreach ($field->opt as $opt) {
        $disabled = property_exists($opt, 'disabled') ? 'disabled' : '';
        if (property_exists($opt, 'type')) {
          $optionsHTML .= sprintf(
            '<optgroup
              %1$s
              class="%2$s"
              label="%3$s"
              %4$s
            >',
            $fieldHelpers->getCustomAttributes('slct-opt-grp'),
            $fieldHelpers->getConversationalCls('slct-opt-grp') . ' ' . $fieldHelpers->getCustomClasses('slct-opt-grp'),
            $fieldHelpers->esc_attr($opt->title),
            $disabled
          );
          foreach ($opt->childs as $child) {
            $val = isset($child->val) ? $child->val : $child->lbl;
            $selected = self::checkSelected($child, $value);
            $disabled = property_exists($child, 'disabled') ? 'disabled' : '';
            $optionsHTML .= sprintf(
              '<option
                %1$s
                class="%2$s"
                value="%3$s"
                %4$s
                %5$s
              >
                %6$s
              </option>',
              $fieldHelpers->getCustomAttributes('slct-optn'),
              $fieldHelpers->getConversationalCls('slct-optn') . ' ' . $fieldHelpers->getCustomClasses('slct-optn'),
              $fieldHelpers->esc_attr($val),
              $selected,
              $disabled,
              $fieldHelpers->kses_post($child->lbl)
            );
          }
          $optionsHTML .= '</optgroup>';
        } else {
          $selected = self::checkSelected($opt, $value);
          $val = isset($opt->val) ? $opt->val : $opt->lbl;
          $optionsHTML .= sprintf(
            '<option
              %1$s
              class="%2$s"
              value="%3$s"
              %4$s
              %5$s
            >
              %6$s
            </option>',
            $fieldHelpers->getCustomAttributes('slct-optn'),
            $fieldHelpers->getConversationalCls('slct-optn') . ' ' . $fieldHelpers->getCustomClasses('slct-optn'),
            $fieldHelpers->esc_attr($val),
            $selected,
            $disabled,
            $fieldHelpers->kses_post($opt->lbl)
          );
        }
      }
    }

    return sprintf(
      '<div
      %1$s
      class="%2$s"
      >
          <select
            %3$s
            id="%4$s-%5$s"
            class="%6$s %7$s"
            %8$s
            %9$s
            %10$s
            value="%11$s"
          >
             %12$s
             %13$s
          </select>
      </div>',
      $fieldHelpers->getCustomAttributes('inp-fld-wrp'),
      $fieldHelpers->getConversationalCls('inp-fld-wrp') . ' ' . $fieldHelpers->getCustomClasses('inp-fld-wrp'),
      $fieldHelpers->getCustomAttributes('fld'),
      $rowID,
      $contentCount,
      $fieldHelpers->getConversationalCls('fld') . ' ' . $fieldHelpers->getCustomClasses('fld'),
      $readonlyCls,
      $readonly,
      $disabled,
      $name,
      $fieldHelpers->esc_attr($value),
      $phHide,
      $optionsHTML
    );
  }
}
