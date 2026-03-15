<?php

namespace BitCode\BitForm\Frontend\Form\View\Theme\Fields;

class CountryField
{
  public static function init($field, $rowID, $field_name, $form_atomic_Cls_map, $formID, $error = null, $value = null)
  {
    $inputWrapper = new ClassicInputWrapper($field, $rowID, $field_name, $form_atomic_Cls_map, $formID, $error, $value);
    $input = self::field($field, $rowID, $field_name, $form_atomic_Cls_map, $formID, $error, $value);
    return $inputWrapper->wrapper($input);
  }

  private static function field($field, $rowID, $field_name, $form_atomic_Cls_map, $formID, $error = null, $value = null)
  {
    $fieldHelpers = new ClassicFieldHelpers($field, $rowID, $form_atomic_Cls_map);
    $asset_url = BITFORMS_ASSET_URI;
    $img_url = BITFORMS_ASSET_URI . '/../static/countries/';
    $req = $fieldHelpers->required();
    $disabled = $fieldHelpers->disabled();
    $readonly = $fieldHelpers->readonly();
    $name = $fieldHelpers->name();
    $selectedFlagImage = '';
    $tabIndx = isset($field->disabled) ? -1 : 0;
    $selectedCountryClearable = '';
    $searchPlaceholder = '';
    $searchClearable = '';
    $options = '';
    $readonlyCls = isset($field->readonly) ? 'readonly' : '';
    $disabledCls = isset($field->disabled) ? 'disabled' : '';
    $val = $fieldHelpers->value();

    $selectedItm = null;
    foreach ($field->options as $opt) {
      if (isset($opt->check) && true === $opt->check) {
        $selectedItm = $opt;
        break;
      }
    }
    if ($selectedItm) {
      $img = $img_url . $selectedItm->img;
      $ph = $selectedItm->lbl;
    } else {
      $img = htmlentities("data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg'/>");
      $ph = isset($field->ph) ? $field->ph : '';
    }

    if ($fieldHelpers->property_exists_nested($field, 'config->selectedFlagImage', true)) {
      $img = sprintf(
        '<img
          %1$s
          class="%2$s %3$s"
          aria-hidden="true"
          alt="selected country flag"
          src="%4$s"
        >',
        $fieldHelpers->getCustomAttributes('selected-country-img'),
        $fieldHelpers->getAtomicCls('selected-country-img'),
        $fieldHelpers->getCustomClasses('selected-country-img'),
        $img
      );
    } else {
      $img = '';
    }

    $selectedFlagImage = sprintf(
      '<div class="%1$s">
        %2$s
        <span
          %3$s
          class="%4$s %5$s"
        >
          %6$s
        </span>
      </div>',
      $fieldHelpers->getAtomicCls('selected-country-wrp'),
      $img,
      $fieldHelpers->getCustomAttributes('selected-country-lbl'),
      $fieldHelpers->getAtomicCls('selected-country-lbl'),
      $fieldHelpers->getCustomClasses('selected-country-lbl'),
      $fieldHelpers->esc_html($ph)
    );

    if ($fieldHelpers->property_exists_nested($field, 'config->selectedCountryClearable', true)) {
      $selectedCountryClearable = sprintf(
        '<button
          %1$s
          type="button"
          title="Clear selected country value"
          class="%2$s %3$s"
        >
          <svg
            width="15"
            height="15"
            role="img"
            title="Cross icon"
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
        $fieldHelpers->getCustomAttributes('inp-clr-btn'),
        $fieldHelpers->getAtomicCls('inp-clr-btn'),
        $fieldHelpers->getCustomClasses('inp-clr-btn')
      );
    }

    if ($fieldHelpers->property_exists_nested($field, 'config->searchPlaceholder', '', 1)) {
      $searchPlaceholder = "placeholder='{$fieldHelpers->esc_attr($field->config->searchPlaceholder)}'";
    }

    if ($fieldHelpers->property_exists_nested($field, 'config->searchClearable', true)) {
      $searchClearable = sprintf(
        '<button
          %1$s
          type="button"
          title="Clear search"
          class="%2$s %3$s %4$s"
          tabIndex="-1"
        >
          <svg
            width="13"
            height="13"
            role="img"
            title="Cross icon"
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
        $fieldHelpers->getCustomAttributes('search-clear-btn'),
        $fieldHelpers->getAtomicCls('icn'),
        $fieldHelpers->getAtomicCls('search-clear-btn'),
        $fieldHelpers->getCustomClasses('search-clear-btn')
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
            title="Country Hidden Input"
            class="%9$s d-none"
            %10$s
            %11$s
            %12$s
          />
          <div
            class="%13$s"
            aria-live="assertive"
            aria-label="Select a Country"
            role="combobox"
            aria-expanded="false"
            tabIndex="%14$s"
          >
            %15$s
            <div class="%16$s">
              %17$s
              <div class="%18$s">
                <svg
                  width="15"
                  height="15"
                  viewBox="0 0 24 24"
                  title="Cross icon"
                  role="img"
                  fill="none"
                  stroke="currentColor"
                  stroke-width="2"
                  stroke-linecap="round"
                  stroke-linejoin="round"
                >
                  <polyline points="6 9 12 15 18 9" />
                </svg>
              </div>
            </div>
          </div>
          <div
            %19$s
            class="%20$s %21$s"
          >
            <div class="%22$s">
              <div class="%23$s">
                <input
                  %24$s
                  type="search"
                  class="%25$s %26$s"
                  %27$s
                  autoComplete="country-name"
                  tabIndex="-1"
                />
                <svg
                  %28$s
                  class="%29$s %30$s"
                  aria-hidden="true"
                  width="22"
                  height="22"
                  role="img"
                  title="Search icon"
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
                %31$s
              </div>
              <ul
                %32$s
                class="%33$s %34$s"
                tabIndex="-1"
                role="listbox"
                aria-label="country list"
              >
                %35$s
              </ul>
            </div>
          </div>
        </div>
      </div>',
      $fieldHelpers->getAtomicCls('country-fld-container'),    // 1
      $fieldHelpers->getCustomAttributes('country-fld-wrp'),   // 2
      $fieldHelpers->getAtomicCls('country-fld-wrp'),          // 3
      $fieldHelpers->getCustomClasses('country-fld-wrp'),      // 4
      $disabled,                                               // 5
      $readonly,                                               // 6
      $name,                                                   // 7
      $req,                                                    // 8
      $fieldHelpers->getAtomicCls('country-hidden-input'),     // 9
      $disabled,                                               // 10
      $readonly,                                               // 11
      $val,                                                    // 12
      $fieldHelpers->getAtomicCls('dpd-wrp'),                  // 13
      $tabIndx,                                                // 14
      $selectedFlagImage,                                      // 15
      $fieldHelpers->getAtomicCls('dpd-btn-wrp'),              // 16
      $selectedCountryClearable,                               // 17
      $fieldHelpers->getAtomicCls('dpd-down-btn'),             // 18
      $fieldHelpers->getCustomAttributes('option-wrp'),        // 19
      $fieldHelpers->getAtomicCls('option-wrp'),               // 20
      $fieldHelpers->getCustomClasses('option-wrp'),           // 21
      $fieldHelpers->getAtomicCls('option-inner-wrp'),         // 22
      $fieldHelpers->getAtomicCls('option-search-wrp'),        // 23
      $fieldHelpers->getCustomAttributes('opt-search-input'),  // 24
      $fieldHelpers->getAtomicCls('opt-search-input'),         // 25
      $fieldHelpers->getCustomClasses('opt-search-input'),     // 26
      $searchPlaceholder,                                      // 27
      $fieldHelpers->getCustomAttributes('opt-search-icn'),    // 28
      $fieldHelpers->getAtomicCls('opt-search-icn'),           // 29
      $fieldHelpers->getCustomClasses('opt-search-icn'),       // 30
      $searchClearable,                                        // 31
      $fieldHelpers->getCustomAttributes('option-list'),       // 32
      $fieldHelpers->getAtomicCls('option-list'),              // 33
      $fieldHelpers->getCustomClasses('option-list'),          // 34
      $options                                                 // 35
    );
  }
}
