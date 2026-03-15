<?php

namespace BitCode\BitForm\Frontend\Form\View\Conversational\Fields;

class CountryField
{
  public static function init($field, $rowID, $field_name, $form_atomic_Cls_map, $formID, $error = null, $value = null)
  {
    $inputWrapper = new ConversationalInputWrapper($field, $rowID, $field_name, $form_atomic_Cls_map, $formID, $error, $value);
    $input = self::field($field, $rowID, $field_name, $form_atomic_Cls_map, $formID, $error, $value);
    return $inputWrapper->wrapper($input);
  }

  private static function field($field, $rowID, $field_name, $form_atomic_Cls_map, $formID, $error = null, $value = null)
  {
    $fieldHelpers = new ConversationalFieldHelpers($formID, $field, $rowID, $form_atomic_Cls_map);
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
        >
',
        $fieldHelpers->getCustomAttributes('selected-country-img'),
        $fieldHelpers->getConversationalMultiCls('selected-country-img'),
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
      </div>
',
      $fieldHelpers->getConversationalMultiCls('selected-country-wrp'),
      $img,
      $fieldHelpers->getCustomAttributes('selected-country-lbl'),
      $fieldHelpers->getConversationalMultiCls('selected-country-lbl'),
      $fieldHelpers->getCustomClasses('selected-country-lbl'),
      $fieldHelpers->esc_html($ph)
    );

    if ($fieldHelpers->property_exists_nested($field, 'config->selectedCountryClearable', true)) {
      $selectedCountryClearable = sprintf(
        '      <button
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
      </button>
',
        $fieldHelpers->getCustomAttributes('inp-clr-btn'),
        $fieldHelpers->getConversationalMultiCls('inp-clr-btn'),
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
        </button>
',
        $fieldHelpers->getCustomAttributes('search-clear-btn'),
        $fieldHelpers->getConversationalMultiCls('icn'),
        $fieldHelpers->getConversationalMultiCls('search-clear-btn'),
        $fieldHelpers->getCustomClasses('search-clear-btn')
      );
    }

    return '    <div class="' . $fieldHelpers->getConversationalMultiCls('country-fld-container') . '">
      <div 
        ' . $fieldHelpers->getCustomAttributes('country-fld-wrp') . '
        class="' . $fieldHelpers->getConversationalMultiCls('country-fld-wrp') . ' ' . $fieldHelpers->getCustomClasses('country-fld-wrp') . ' ' . $disabled . ' ' . $readonly . '"
      >
        <input
          ' . $name . '
          ' . $req . '
          type="text"
          title="Country Hidden Input"
          class="' . $fieldHelpers->getConversationalMultiCls('country-hidden-input') . ' d-none"
          ' . $disabled . '
          ' . $readonly . '
          ' . $val . '
        />
        <div
          class="' . $fieldHelpers->getConversationalMultiCls('dpd-wrp') . '"
          aria-live="assertive"
          aria-label="Select a Country"
          role="combobox"
          aria-expanded="false"
          tabIndex=' . $tabIndx . '
        >
          ' . $selectedFlagImage . '
    
          <div class="' . $fieldHelpers->getConversationalMultiCls('dpd-btn-wrp') . '">
          ' . $selectedCountryClearable . '
            <div class="' . $fieldHelpers->getConversationalMultiCls('dpd-down-btn') . '">
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
          ' . $fieldHelpers->getCustomAttributes('option-wrp') . '
          class="' . $fieldHelpers->getConversationalMultiCls('option-wrp') . ' ' . $fieldHelpers->getCustomClasses('option-wrp') . '"
        >
          <div class="' . $fieldHelpers->getConversationalMultiCls('option-inner-wrp') . '">
            <div class="' . $fieldHelpers->getConversationalMultiCls('option-search-wrp') . '">
              <input
                ' . $fieldHelpers->getCustomAttributes('opt-search-input') . '
                type="search"
                class="' . $fieldHelpers->getConversationalMultiCls('opt-search-input') . ' ' . $fieldHelpers->getCustomClasses('opt-search-input') . '"
                ' . $searchPlaceholder . '
                autoComplete="country-name"
                tabIndex="-1"
              />
              <svg
                ' . $fieldHelpers->getCustomAttributes('opt-search-icn') . '
                class="' . $fieldHelpers->getConversationalMultiCls('icn') . ' ' . $fieldHelpers->getConversationalMultiCls('opt-search-icn') . ' ' . $fieldHelpers->getCustomClasses('opt-search-icn') . '"
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
             ' . $searchClearable . '
            </div>
            <ul
              ' . $fieldHelpers->getCustomAttributes('option-list') . '
              class="' . $fieldHelpers->getConversationalMultiCls('option-list') . ' ' . $fieldHelpers->getCustomClasses('option-list') . '"
              tabIndex="-1"
              role="listbox"
              aria-label="country list"
            >
              ' . $options . '
            </ul>
          </div>
        </div>
      </div>
    </div>';
  }
}
