<?php
/**
 * Class Emergency
 *
 * This class is responsible for validating emergency data.
 *
 * @package WPTravelEngine\Validator
 * @since 6.3.0
 */

namespace WPTravelEngine\Validator;

use WPTravelEngine\Builders\FormFields\DefaultFormFields as EmergencyFields;
/**
 * Class Emergency
 *
 * This class is responsible for validating emergency data.
 */
class Emergency extends Validator {
	/**
	 * Validates the input data.
	 *
	 * @param mixed $data The input data.
	 *
	 * @return void
	 */
	public function validate( $data ) {

		$this->raw_data = $data;

		$emergency_fields = apply_filters( 'wp_travel_engine_emergency_contact_fields_display', EmergencyFields::emergency_contact() );

		$field_name_mapping = array(
			'traveller_emergency_first_name' => 'fname',
			'traveller_emergency_last_name'  => 'lname',
			'traveller_emergency_country'    => 'country',
			'traveller_emergency_phone'      => 'phone',
			'traveller_emergency_relation'   => 'relation',
		);

		foreach ( $emergency_fields as $field_key => $field ) {
			$is_required = $field['validations']['required'] ?? false;
			$mapped_key  = $field_name_mapping[ $field_key ] ?? $field['name'] ?? '';

			// For exisiting naming convention of emergency fields.
			$parts = explode( ']', $mapped_key );
			$parts = array_map(
				function ( $part ) {
					return trim( $part, '[] ' );
				},
				$parts
			);

			// Remove empty elements from the array.
			$parts = array_filter( $parts );

			// Get the last non-empty element.
			$mapped_key = end( $parts );

			$mapped_value = $data[ $mapped_key ] ?? '';

			// Check if the method exists for the field key.
			if ( method_exists( $this, $field_key ) ) {
				$value = $data[ $field_name_mapping[ $field_key ] ] ?? '';
				if ( empty( $value ) && $is_required ) {
					$this->errors[ $mapped_key ] = self::REQUIRED;
					continue;
				}

				$this->$field_key( $mapped_value, $mapped_key );
			} else {
				$this->default( $data, $mapped_key, $is_required );
			}
		}
	}

	/**
	 * Sets the title for a traveler.
	 *
	 * Validates the given title and stores it in the data array if it passes validation.
	 *
	 * @param string $value The title to be set for the traveler.
	 *
	 * @return void
	 */
	protected function traveller_emergency_first_name( string $value, $key ) {
		if ( ! $this->validate_name( $value ) ) {
			$this->errors[ $key ] = static::ILLEGAL_CHARACTERS;

			return;
		}

		$this->data['emergency']['fname'] = sanitize_text_field( $value );
	}

	/**
	 * Sets the first name for a traveler.
	 *
	 * Validates the given first name and stores it in the data array if it passes validation.
	 *
	 * @param string $value The first name to be set for the traveler.
	 *
	 * @return void
	 */
	protected function traveller_emergency_last_name( string $value, $key ) {

		if ( ! $this->validate_name( $value ) ) {
			$this->errors[ $key ] = static::ILLEGAL_CHARACTERS;

			return;
		}

		$this->data['emergency']['lname'] = sanitize_text_field( $value );
	}


	/**
	 * Sets the value of the phone number for the traveler.
	 *
	 * @param mixed $value The phone number value to be set for the traveler.
	 *
	 * @return void
	 */
	protected function traveller_emergency_phone( $value, $key ) {
		$this->data['emergency']['phone'] = $this->sanitize_phone( $value );
	}

	/**
	 * Sets the city for the traveler.
	 *
	 * @param string $value The city value to be checked for illegal characters and stored.
	 *
	 * @return void
	 */
	protected function traveller_emergency_country( string $value, $key ) {
		$value = $this->sanitize_country( $value );
		if ( empty( $value ) ) {
			$this->errors[ $key ] = static::INVALID_VALUE;
			return;
		}
		$this->data['emergency']['country'] = $value;
	}

	/**
	 * Sets the first name for a traveler.
	 *
	 * Validates the given first name and stores it in the data array if it passes validation.
	 *
	 * @param string $value The first name to be set for the traveler.
	 *
	 * @return void
	 */
	protected function traveller_emergency_relation( string $value, $key ) {

		if ( ! $this->validate_name( $value ) ) {
			$this->errors[ $key ] = static::ILLEGAL_CHARACTERS;

			return;
		}

		$this->data['emergency']['relation'] = sanitize_text_field( $value );
	}

	/**
	 * Sets the default value for a field in the travelers data.
	 *
	 * If the field value is empty and the field is marked as required,
	 * an error is added to the errors array. Otherwise, the field value
	 * is sanitized and stored in the traveler data.
	 *
	 * @param array $data The input data array.
	 * @param array $settings The field settings array.
	 *
	 * @return void
	 */
	protected function default( array $data, $mapped_key, $is_required ) {

		if ( empty( $data[ $mapped_key ] ) && $is_required ) {
			$this->errors[ $mapped_key ] = self::REQUIRED;

			return;
		}
		$this->data['emergency'][ $mapped_key ] = is_array( $data[ $mapped_key ] ) ? array_map( 'sanitize_text_field', $data[ $mapped_key ] ) : sanitize_text_field( $data[ $mapped_key ] ?? '' );
	}
}
