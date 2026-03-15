<?php

namespace BitCode\BitForm\Frontend\Form\View\Theme\Fields;

class PhoneNumberField
{
  public static function init($field, $rowID, $field_name, $form_atomic_Cls_map, $formID, $error = null, $value = null)
  {
    $inputWrapper = new ClassicInputWrapper($field, $rowID, $field_name, $form_atomic_Cls_map, $formID, $error, $value);
    $input = self::field($field, $rowID, $field_name, $form_atomic_Cls_map, $formID, $error, $value);
    return $inputWrapper->wrapper($input);
  }

  private static function field($field, $rowID, $field_name, $form_atomic_Cls_map, $formID, $error = null, $value = null)
  {
    $fh = new ClassicFieldHelpers($field, $rowID, $form_atomic_Cls_map);
    $img_url = BITFORMS_ASSET_URI . '/../static/countries/';
    $req = $fh->required();
    $disabled = $fh->disabled();
    $readonly = $fh->readonly();
    $name = $fh->name();
    $ph = $fh->placeholder();
    $selectedFlagImage = '';
    $dropDownBtn = '';
    $tabIndx = isset($field->disabled) ? -1 : 0;
    $selectedCountryClearable = '';
    $dpdWrap = '';
    $searchPlaceholder = '';
    $searchClearable = '';
    $optionWrap = '';
    $options = '';
    $showSelectedFlagImg = $fh->property_exists_nested($field, 'config->selectedFlagImage', true);
    $hideCountryList = $fh->property_exists_nested($field, 'config->hideCountryList', true);
    $readonlyCls = isset($field->readonly) ? 'readonly' : '';
    $disabledCls = isset($field->disabled) ? 'disabled' : '';
    $img = htmlentities("data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg'></svg>");

    $val = $fh->value();

    if ($showSelectedFlagImg) {
      $selectedFlagImage = sprintf(
        '<div class="%1$s">
          <img
            %2$s
            alt="Selected Country image"
            aria-hidden="true"
            class="%3$s %4$s"
            src="%5$s"
          />
        </div>',
        $fh->getAtomicCls('selected-country-wrp'),
        $fh->getCustomAttributes('selected-phone-img'),
        $fh->getAtomicCls('selected-country-img'),
        $fh->getCustomClasses('selected-country-img'),
        $img
      );
    }

    if (!$hideCountryList) {
      $dropDownBtn = sprintf(
        '<div class="%1$s">
          <svg
            width="15"
            height="15"
            role="img"
            title="Downarrow icon"
            viewBox="0 0 24 24"
            fill="none"
            stroke="currentColor"
            stroke-width="2"
            stroke-linecap="round"
            stroke-linejoin="round"
          >
            <polyline points="6 9 12 15 18 9" />
          </svg>
        </div>',
        $fh->getAtomicCls('dpd-down-btn')
      );
    }

    if ($fh->property_exists_nested($field, 'config->selectedCountryClearable', true)) {
      $selectedCountryClearable = sprintf(
        '<button
          %1$s
          type="button"
          title="Clear value"
          class="%2$s %3$s"
        >
          <svg
            width="12"
            height="12"
            viewBox="0 0 24 24"
            fill="none"
            stroke="currentColor"
            stroke-width="2"
            stroke-linecap="round"
            stroke-linejoin="round"
          >
            <line x1="18" y1="6" x2="6" y2="18" />
            <line x1="6" y1="6" x2="18" y2="18" />
          </svg>
        </button>',
        $fh->getCustomAttributes('input-clear-btn'),
        $fh->getAtomicCls('input-clear-btn'),
        $fh->getCustomClasses('input-clear-btn')
      );
    }

    if ($fh->property_exists_nested($field, 'config->searchPlaceholder', '', 1)) {
      $searchPlaceholder = "{$field->config->searchPlaceholder}";
    }

    if ($fh->property_exists_nested($field, 'config->searchClearable', true)) {
      $searchClearable = sprintf(
        '<button
          %1$s
          type="button"
          aria-label="Clear search"
          class="%2$s %3$s"
          tabIndex="-1"
        >
          <svg
            width="12"
            height="12"
            viewBox="0 0 24 24"
            fill="none"
            stroke="currentColor"
            stroke-width="2"
            stroke-linecap="round"
            stroke-linejoin="round"
          >
            <line x1="18" y1="6" x2="6" y2="18" />
            <line x1="6" y1="6" x2="18" y2="18" />
          </svg>
        </button>',
        $fh->getCustomAttributes('search-clear-btn'),
        $fh->getAtomicCls('search-clear-btn'),
        $fh->getCustomClasses('search-clear-btn')
      );
    }

    if (!$hideCountryList || $showSelectedFlagImg) {
      $dpdWrap = sprintf(
        '<div
          class="%1$s"
          role="combobox"
          aria-live="assertive"
          aria-labelledby="country-label-2"
          aria-expanded="false"
          tabIndex=%2$s
        >
          %3$s
          %4$s
        </div>',
        $fh->getAtomicCls('dpd-wrp'),
        $tabIndx,
        $selectedFlagImage,
        $dropDownBtn
      );
    }

    if (!$hideCountryList) {
      $optionWrap = sprintf(
        '<div
          %1$s
          class="%2$s %3$s"
        >
          <div class="%4$s">
            <div
              %5$s
              class="%6$s %7$s"
            >
              <input
                %8$s
                aria-label="Search for countries"
                type="search"
                class="%9$s %10$s"
                placeholder="%11$s"
                autoComplete="off"
                tabIndex="-1"
              />
              <svg
                %12$s
                class="%13$s %14$s"
                aria-hidden="true"
                width="22"
                height="22"
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="2"
                stroke-linecap="round"
                stroke-linejoin="round"
              >
                <circle cx="11" cy="11" r="8" />
                <line x1="21" y1="21" x2="16.65" y2="16.65" />
              </svg>
              %15$s
            </div>
            <ul
              %16$s
              class="%17$s %18$s"
              tabIndex="-1"
              role="listbox"
              aria-label="country list"
            >
              %19$s
            </ul>
          </div>
        </div>',
        $fh->getCustomAttributes('option-wrp'),
        $fh->getAtomicCls('option-wrp'),
        $fh->getCustomClasses('option-wrp'),
        $fh->getAtomicCls('option-inner-wrp'),
        $fh->getCustomAttributes('option-search-wrp'),
        $fh->getAtomicCls('option-search-wrp'),
        $fh->getCustomClasses('option-search-wrp'),
        $fh->getCustomAttributes('opt-search-input'),
        $fh->getAtomicCls('opt-search-input'),
        $fh->getCustomClasses('opt-search-icn'),
        $fh->esc_attr($searchPlaceholder),
        $fh->getCustomAttributes('opt-search-icn'),
        $fh->getAtomicCls('opt-search-icn'),
        $fh->getCustomClasses('opt-search-icn'),
        $searchClearable,
        $fh->getCustomAttributes('option-list'),
        $fh->getAtomicCls('option-list'),
        $fh->getCustomClasses('option-list'),
        $options
      );
    }

    return sprintf(
      '<div class="%1$s">
        <div
          %2$s
          class="%3$s %4$s %5$s %6$s"
        >
          <input
            %7$s
            %8$s
            type="text"
            title="Phone-number Hidden Input"
            class="%9$s d-none"
            %10$s
            %11$s
            %12$s
          />
          <div class="%13$s">
            %14$s
            <input
              %15$s
              aria-label="Phone Number"
              type="tel"
              class="%16$s %17$s"
              autoComplete="tel"
              %18$s
              tabIndex=%19$s
            />
            %20$s
          </div>
          %21$s
        </div>
      </div>',
      $fh->getAtomicCls('phone-fld-container'),
      $fh->getCustomAttributes('phone-fld-wrp'),
      $fh->getAtomicCls('phone-fld-wrp'),
      $fh->getCustomClasses('phone-fld-wrp'),
      $readonly,
      $disabled,
      $name,
      $req,
      $fh->getAtomicCls('phone-hidden-input'),
      $fh->disabled(),
      $fh->readonly(),
      $val,
      $fh->getAtomicCls('phone-inner-wrp'),
      $dpdWrap,
      $fh->getCustomAttributes('phone-number-input'),
      $fh->getAtomicCls('phone-number-input'),
      $fh->getCustomClasses('phone-number-input'),
      $ph,
      $tabIndx,
      $selectedCountryClearable,
      $optionWrap
    );
  }
}
