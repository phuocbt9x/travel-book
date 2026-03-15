<?php

namespace BitCode\BitForm\Frontend\Form\View\Theme\Fields;

class CurrencyField
{
  public static function init($field, $rowID, $field_name, $form_atomic_Cls_map, $formID, $error = null, $value = '')
  {
    $inputWrapper = new ClassicInputWrapper($field, $rowID, $field_name, $form_atomic_Cls_map, $formID, $error, $value);
    $input = self::field($field, $rowID, $field_name, $form_atomic_Cls_map, $formID, $error, $value);
    return $inputWrapper->wrapper($input);
  }

  private static function field($field, $rowID, $field_name, $form_atomic_Cls_map, $formID, $error = null, $value = '')
  {
    $fh = new ClassicFieldHelpers($field, $rowID, $form_atomic_Cls_map);
    $img_url = BITFORMS_ASSET_URI . '/../static/currencies/';

    $req = $fh->required();
    $disabled = $fh->disabled();
    $readonly = $fh->readonly();
    $name = $fh->name();
    $ph = $fh->placeholder();
    $selectedFlagImage = '';
    $tabIndx = isset($field->disabled) ? -1 : 0;
    $selectedCurrencyClearable = '';
    $searchPlaceholder = '';
    $searchClearable = '';
    $options = '';
    $readonlyCls = isset($field->readonly) ? 'readonly' : '';
    $disabledCls = isset($field->disabled) ? 'disabled' : '';
    $value = is_string($value) ? $value : '';
    $numValue = preg_replace('/[^0-9.-]/', '', $value);

    $img = htmlentities("data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg'/>");

    if ($fh->property_exists_nested($field, 'config->selectedFlagImage', true)) {
      $selectedFlagImage = sprintf(
        '<div class="%1$s">
          <img
            %2$s
            class="%3$s %4$s"
            aria-hidden="true"
            alt="selected country flag"
            src="%5$s"
          />
        </div>',
        $fh->getAtomicCls('selected-currency-wrp'),
        $fh->getCustomAttributes('selected-currency-img'),
        $fh->getAtomicCls('selected-currency-img'),
        $fh->getCustomClasses('selected-currency-img'),
        $img
      );
    }

    if ($fh->property_exists_nested($field, 'config->selectedCurrencyClearable', true)) {
      $selectedCurrencyClearable = sprintf(
        '<button
          %1$s
          type="button"
          title="Clear selected currency value"
          class="%2$s %3$s"
        >
          <svg
            width="12"
            height="12"
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
        $fh->getCustomAttributes('input-clear-btn'),
        $fh->getAtomicCls('input-clear-btn'),
        $fh->getCustomClasses('input-clear-btn')
      );
    }

    if ($fh->property_exists_nested($field, 'config->searchPlaceholder', '', 1)) {
      $searchPlaceholder = "placeholder='{$fh->esc_attr($field->config->searchPlaceholder)}'";
    }

    if ($fh->property_exists_nested($field, 'config->searchClearable', true)) {
      $searchClearable = sprintf(
        '<button
          %1$s
          type="button"
          title="Clear search"
          class="%2$s %3$s"
          tabIndex="-1"
        >
          <svg
            width="12"
            height="12"
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
        $fh->getCustomAttributes('search-clear-btn'),
        $fh->getAtomicCls('search-clear-btn'),
        $fh->getCustomClasses('search-clear-btn')
      );
    }

    // Container wrapper
    $html = sprintf(
      '<div class="%1$s">
        <div
          %2$s
          class="%3$s %4$s %5$s %6$s"
        >',
      $fh->getAtomicCls('currency-fld-container'),
      $fh->getCustomAttributes('currency-fld-wrp'),
      $fh->getAtomicCls('currency-fld-wrp'),
      $fh->getCustomClasses('currency-fld-wrp'),
      $disabled,
      $readonly
    );

    // Hidden input
    $html .= sprintf(
      '<input
        %1$s
        %2$s
        %3$s
        type="text"
        title="Currency Hidden Input"
        class="%4$s d-none"
        %5$s
        %6$s
        value="%7$s"
      />',
      $fh->getCustomAttributes('currency-hidden-input'),
      $name,
      $req,
      $fh->getAtomicCls('currency-hidden-input'),
      $disabled,
      $readonly,
      $fh->esc_attr($value)
    );

    // Currency inner wrapper with dropdown
    $html .= sprintf(
      '<div class="%1$s">
        <div
          %2$s
          class="%3$s %4$s"
          role="combobox"
          aria-controls="currency-dropdown"
          aria-live="assertive"
          aria-labelledby="currency-label-2"
          aria-expanded="false"
          tabIndex="%5$s"
        >
          %6$s
          <div class="%7$s">
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
          </div>
        </div>',
      $fh->getAtomicCls('currency-inner-wrp'),
      $fh->getCustomAttributes('dpd-wrp'),
      $fh->getAtomicCls('dpd-wrp'),
      $fh->getCustomClasses('dpd-wrp'),
      $tabIndx,
      $selectedFlagImage,
      $fh->getAtomicCls('dpd-down-btn')
    );

    // Currency amount input
    $html .= sprintf(
      '<input
        %1$s
        aria-label="Currency Input"
        type="text"
        class="%2$s %3$s"
        %4$s
        tabIndex="%5$s"
        data-num-value="%6$s"
      />
      %7$s
    </div>',
      $fh->getCustomAttributes('currency-amount-input'),
      $fh->getAtomicCls('currency-amount-input'),
      $fh->getCustomClasses('currency-amount-input'),
      $ph,
      $tabIndx,
      $numValue,
      $selectedCurrencyClearable
    );

    // Option wrapper with search
    $html .= sprintf(
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
              type="search"
              class="%9$s %10$s"
              %11$s
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
          </div>',
      $fh->getCustomAttributes('option-wrp'),        // 1
      $fh->getAtomicCls('option-wrp'),               // 2
      $fh->getCustomClasses('option-wrp'),           // 3
      $fh->getAtomicCls('option-inner-wrp'),         // 4
      $fh->getCustomAttributes('option-search-wrp'), // 5
      $fh->getAtomicCls('option-search-wrp'),        // 6
      $fh->getCustomClasses('option-search-wrp'),    // 7
      $fh->getCustomAttributes('opt-search-input'),  // 8
      $fh->getAtomicCls('opt-search-input'),         // 9
      $fh->getCustomClasses('opt-search-input'),     // 10
      $searchPlaceholder,                            // 11
      $fh->getCustomAttributes('opt-search-icn'),    // 12
      $fh->getAtomicCls('opt-search-icn'),           // 13
      $fh->getCustomClasses('opt-search-icn'),       // 14
      $searchClearable                               // 15
    );

    // Option list and closing tags
    $html .= sprintf(
      '<ul
        %1$s
        class="%2$s %3$s"
        tabIndex="-1"
        role="listbox"
        aria-label="currency list"
      >
        %4$s
      </ul>
          </div>
        </div>
      </div>
    </div>',
      $fh->getCustomAttributes('option-list'),
      $fh->getAtomicCls('option-list'),
      $fh->getCustomClasses('option-list'),
      $options
    );

    return $html;
  }
}
