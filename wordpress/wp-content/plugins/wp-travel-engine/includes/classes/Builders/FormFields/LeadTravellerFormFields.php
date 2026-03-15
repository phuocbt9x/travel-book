<?php
/**
 * Lead Traveller Form Fields.
 *
 * @since 6.4.3
 */

namespace WPTravelEngine\Builders\FormFields;

use WPTravelEngine\Helpers\Countries;

use WTE_Default_Form_Fields;
use WPTravelEngine\Builders\FormFields\DefaultFormFields;

/**
 * Form field class to render lead traveller form fields.
 */
class LeadTravellerFormFields extends FormField {

	public function __construct() {
		parent::__construct( false );
	}

	public function render() {
		if ( $this->use_legacy_template ) {
			parent::render();

			return;
		}
		echo '<div class="wpte-checkout__form-row">';
		parent::render();
		echo '</div>';
	}

	/**
	 * @param array $form_data
	 *
	 * @return array
	 */
	public function with_values( array $form_data, $booking = null ): array {
		if ( $booking && $booking->get_meta( 'traveller_page_type' ) == 'old' ) {
			$this->fields = WTE_Default_Form_Fields::traveller_information();
		} else {
			$this->fields = DefaultFormFields::lead_traveller();
		}
		return array_map(
			function ( $field ) use ( $form_data ) {
				$name = preg_match( '#\[([^\[]+)]$#', $field['name'], $matches ) ? $matches[1] : $field['name'];
				if ( $name ) {
						$field['class']         = 'wpte-checkout__input';
						$field['wrapper_class'] = 'wpte-checkout__form-col';
						$field['name']          = sprintf( 'travellers[%s]', $name );

						$field['id'] = sprintf( 'travellers_%s', $name );
				}
				$field['field_label'] = isset( $field['placeholder'] ) && $field['placeholder'] !== '' ? $field['placeholder'] : $field['field_label'];
				$field['value']       = $form_data[ $name ] ?? $field['default'] ?? '';
				// Convert country code to country name to show in the traveller form.
				$countries_list = Countries::list();
				if ( $field['type'] == 'country' ) {
					if ( isset( $field['value'] ) && is_string( $field['value'] ) && isset( $countries_list[ $field['value'] ] ) ) {
						$field['value'] = $countries_list[ $field['value'] ];
					}
				}
				return $field;
			},
			$this->fields
		);
	}
}
