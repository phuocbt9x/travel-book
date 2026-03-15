<?php

namespace BitCode\BitForm\Frontend\Form\View\Theme\Fields;

use BitCode\BitForm\Core\Util\FrontendHelpers;

class RadioBoxField
{
  public static function init($field, $rowID, $field_name, $form_atomic_Cls_map, $formID, $error = null, $value = null)
  {
    $inputWrapper = new ClassicInputWrapper($field, $rowID, $field_name, $form_atomic_Cls_map, $formID, $error, $value);
    $input = self::field($field, $rowID, $field_name, $form_atomic_Cls_map, $formID, $error, $value);
    return $inputWrapper->wrapper($input);
  }

  private static function field($field, $rowID, $field_name, $form_atomic_Cls_map, $formID, $error, $value)
  {
    $fieldHelpers = new ClassicFieldHelpers($field, $rowID, $form_atomic_Cls_map);
    $req = $fieldHelpers->required();
    $name = $fieldHelpers->name();
    $value = '';
    $radioBoxOptions = '';
    $bfFrontendFormIds = FrontendHelpers::$bfFrontendFormIds;
    $contentCount = count($bfFrontendFormIds);
    if (property_exists($field, 'opt') && count($field->opt) > 0) {
      $defaultValue = isset($field->val) ? $field->val : '';
      foreach ($field->opt as $key => $opt) {
        $value = isset($opt->val) ? $opt->val : $opt->lbl;
        $check = '';
        $req = '';
        $lbl = $fieldHelpers->renderHTMR($opt->lbl);
        $disabled = '';
        if ($fieldHelpers->property_exists_nested($opt, 'check', true)) {
          $check = "checked='{$opt->check}'";
        } else {
          if ($value === $defaultValue) {
            $check = "checked='checked'";
          }
        }
        if ($fieldHelpers->property_exists_nested($opt, 'req', true)) {
          $req = "required='{$opt->req}'";
        }
        if ($fieldHelpers->property_exists_nested($field, 'valid->disabled', true)
        || $fieldHelpers->property_exists_nested($opt, 'disabled', true)) {
          $disabled = "disabled='disabled'";
        }

        $radioBoxOptions .= sprintf(
          '<div
            %1$s
            class="%2$s %3$s"
          >
            <input
              %4$s
              id="%5$s-%6$s-chk-%7$s"
              type="radio"
              class="%8$s %9$s"
              value="%10$s"
              %11$s
              %12$s
              %13$s
              %14$s
            />
            <label
              %15$s
              data-cl
              for="%5$s-%6$s-chk-%7$s"
              class="%16$s %17$s"
            >
              <span
                %18$s
                data-bx
                class="%19$s %20$s"
              >
                <svg
                  width="12"
                  height="10"
                  viewBox="0 0 12 10"
                  class="%21$s %22$s"
                >
                  <use
                    data-ck-icn
                    href=#%5$s-ck-svg
                    class="%23$s %24$s"
                  />
                </svg>
              </span>
              <span
                %25$s
                class="%26$s %27$s"
              >
                %28$s
              </span>
            </label>
          </div>',
          $fieldHelpers->getCustomAttributes('cw'),
          $fieldHelpers->getAtomicCls('cw'),
          $fieldHelpers->getCustomClasses('cw'),
          $fieldHelpers->getCustomAttributes('ci'),
          $rowID,
          $contentCount,
          $key,
          $fieldHelpers->getAtomicCls('ci'),
          $fieldHelpers->getCustomClasses('ci'),
          $fieldHelpers->esc_attr($value),
          $check,
          $name,
          $req,
          $disabled,
          $fieldHelpers->getCustomAttributes('cl'),
          $fieldHelpers->getAtomicCls('cl'),
          $fieldHelpers->getCustomClasses('cl'),
          $fieldHelpers->getCustomAttributes('bx'),
          $fieldHelpers->getAtomicCls('bx'),
          $fieldHelpers->getCustomClasses('bx'),
          $fieldHelpers->getAtomicCls('svgwrp'),
          $fieldHelpers->getCustomClasses('svgwrp'),
          $fieldHelpers->getAtomicCls('ck-icn'),
          $fieldHelpers->getCustomClasses('ck-icn'),
          $fieldHelpers->getCustomAttributes('ct'),
          $fieldHelpers->getAtomicCls('ct'),
          $fieldHelpers->getCustomClasses('ct'),
          $fieldHelpers->kses_post($lbl)
        );
      }
    }

    //Other Option
    $otherOptLbl = !empty($field->otherOptLbl) ? $field->otherOptLbl : __('Other...', 'bit-form');
    $optCount = property_exists($field, 'opt') ? count($field->opt) : 0;
    $inputPh = isset($field->otherInpPh) ? $field->otherInpPh : $otherOptLbl;
    $inpReq = isset($field->valid->otherOptReq) ? ($field->valid->otherOptReq ? 'required' : '') : '';
    if (property_exists($field, 'addOtherOpt') && $field->addOtherOpt) {
      $check = $check ?? '';
      $radioBoxOptions .= sprintf(
        '      <div
        %1$s
        class="%2$s %3$s"
      >
        <input
          %4$s
          id="%5$s-%6$s-chk-%7$s"
          data-oopt="%5$s"
          type="radio"
          class="%8$s %9$s"
          value=""
          %10$s
          %11$s
        />
        <label
          %12$s
          data-cl
          for="%5$s-%6$s-chk-%7$s"
          class="%13$s %14$s"
        >
          <span
            %15$s
            data-bx
            class="%16$s %17$s %18$s"
          >
            <svg
              width="12"
              height="10"
              viewBox="0 0 12 10"
              class="%19$s %20$s"
            >
              <use
                data-ck-icn
                href=#%5$s-ck-svg
                class="%21$s %22$s"
              />
            </svg>
          </span>
          <span
            %23$s
            class="%24$s %25$s"
          >
            %26$s
          </span>
        </label>
        <div data-oinp-wrp class="%27$s">
          <input
            data-bf-other-inp="%5$s-chk-%7$s"
            type="text"
            class="%28$s"
            %29$s
            placeholder="%30$s"
          >
        </div>
      </div>',
        $fieldHelpers->getCustomAttributes('cw'),
        $fieldHelpers->getAtomicCls('cw'),
        $fieldHelpers->getCustomClasses('cw'),
        $fieldHelpers->getCustomAttributes('ci'),
        $rowID,
        $contentCount,
        $optCount,
        $fieldHelpers->getAtomicCls('ci'),
        $fieldHelpers->getCustomClasses('ci'),
        $check,
        $name,
        $fieldHelpers->getCustomAttributes('cl'),
        $fieldHelpers->getAtomicCls('cl'),
        $fieldHelpers->getCustomClasses('cl'),
        $fieldHelpers->getCustomAttributes('rdo'),
        $fieldHelpers->getAtomicCls('bx'),
        $fieldHelpers->getAtomicCls('rdo'),
        $fieldHelpers->getCustomClasses('rdo'),
        $fieldHelpers->getAtomicCls('svgwrp'),
        $fieldHelpers->getCustomClasses('svgwrp'),
        $fieldHelpers->getAtomicCls('ck-icn'),
        $fieldHelpers->getCustomClasses('ck-icn'),
        $fieldHelpers->getCustomAttributes('ct'),
        $fieldHelpers->getAtomicCls('ct'),
        $fieldHelpers->getCustomClasses('ct'),
        $otherOptLbl,
        $fieldHelpers->getAtomicCls('other-inp-wrp'),
        $fieldHelpers->getAtomicCls('other-inp'),
        $inpReq,
        $fieldHelpers->esc_attr($inputPh)
      );
    }

    return sprintf(
      '<div
        %1$s
        class="%2$s %3$s"
      >
      %4$s
      </div>',
      $fieldHelpers->getCustomAttributes('cc'),
      $fieldHelpers->getAtomicCls('cc'),
      $fieldHelpers->getCustomClasses('cc'),
      $radioBoxOptions
    );
  }
}
