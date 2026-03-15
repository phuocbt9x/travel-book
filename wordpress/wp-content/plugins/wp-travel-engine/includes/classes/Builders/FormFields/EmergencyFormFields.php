<?php
/**
 * Emergency Form Fields.
 *
 * @since 6.3.0
 */

namespace WPTravelEngine\Builders\FormFields;

use WTE_Default_Form_Fields;
use WPTravelEngine\Helpers\Countries;
use WPTravelEngine\Builders\FormFields\DefaultFormFields;

/**
 * Form field class to render emergency form fields.
 *
 * @since 6.3.0
 */
class EmergencyFormFields extends FormField {

	/**
	 * @var array
	 */
	public $fields;

	public function __construct() {
		parent::__construct( false );

		$fields = DefaultFormFields::emergency_form_fields();

		$this->init( $fields );
	}

	public function render() {
		$this->fields = $this->map_fields( $this->fields );
		if ( empty( $this->fields ) ) {
			return;
		}
		?>
		<div class="wpte-checkout__form-section">
			<div class="wpte-checkout__form-row">
				<?php parent::render(); ?>
			</div>
		</div>
		<?php
	}

	protected function map_fields( $fields ) {
		$form_data = WTE()->session->get( 'emergency_form_data' );
		if ( ! $form_data ) {
			$form_data = array();
		}

		return array_map(
			function ( $field ) use ( $form_data ) {
				$name = preg_match( '#\[([^\[]+)]$#', $field['name'], $matches ) ? $matches[1] : $field['name'];
				if ( $name ) {
						$field['class']         = 'wpte-checkout__input';
						$field['wrapper_class'] = 'wpte-checkout__form-col';
						$field['name']          = sprintf( 'emergency[%s]', $name );

						$field['id'] = sprintf( 'emergency_%s', $name );

				}
				$field['field_label'] = isset( $field['placeholder'] ) && $field['placeholder'] !== '' ? $field['placeholder'] : $field['field_label'];
				$field['default']     = $form_data[ $name ] ?? $field['default'] ?? '';

				return $field;
			},
			$fields
		);
	}

	/**
	 * Function to map fields with values.
	 *
	 * @param array $form_data
	 *
	 * @return array
	 * @since 6.4.0
	 */
	public function with_values( $form_data, $booking = null ): array {
		if ( $booking && $booking->get_meta( 'traveller_page_type' ) == 'old' ) {
			$this->fields = WTE_Default_Form_Fields::emergency_contact();
		} else {
			$this->fields = DefaultFormFields::emergency( 'readonly' );
		}

		return array_map(
			function ( $field ) use ( $form_data ) {
				$name = preg_match( '#\[([^\[]+)]$#', $field['name'], $matches ) ? $matches[1] : $field['name'];
				if ( $name ) {
						$field['class']         = 'wpte-checkout__input';
						$field['wrapper_class'] = 'wpte-checkout__form-col';
						$field['name']          = sprintf( 'emergency[%s]', $name );

						$field['id'] = sprintf( 'emergency_%s', $name );

				}
				$field['field_label'] = isset( $field['placeholder'] ) && $field['placeholder'] !== '' ? $field['placeholder'] : $field['field_label'];
				$field['value']       = $form_data[ $name ] ?? $field['default'] ?? '';
				// Convert country code to country name to show in the emergency form.
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
