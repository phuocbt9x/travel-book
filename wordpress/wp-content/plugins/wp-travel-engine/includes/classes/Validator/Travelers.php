<?php
/**
 * Class Travellers
 *
 * This class is responsible for validating travellers data.
 *
 * @package WPTravelEngine\Validator
 * @since 5.8.0
 */

namespace WPTravelEngine\Validator;

use WPTravelEngine\Builders\FormFields\DefaultFormFields as TravelersFields;
/**
 * Class Travellers
 *
 * This class is responsible for validating travellers data.
 */
class Travelers extends Validator {
	/**
	 * Validates the input data.
	 *
	 * @param mixed $data The input data.
	 *
	 * @return void
	 */
	public function validate( $data ) {
		$this->raw_data = $data;

		$traveler_fields = apply_filters( 'wp_travel_engine_traveller_info_fields_display', \WTE_Default_Form_Fields::traveller_information() );

		$traveler_data = $data['wp_travel_engine_placeorder_setting']['place_order']['travelers'] ?? array();

		$field_name_mapping = array(
			'traveler_title'           => 'title',
			'traveler_first_name'      => 'fname',
			'traveler_last_name'       => 'lname',
			'traveler_passport_number' => 'passport',
			'traveler_email'           => 'email',
			'traveler_address'         => 'address',
			'traveler_city'            => 'country',
			'traveler_postcode'        => 'postcode',
			'traveler_phone'           => 'phone',
			'traveler_dob'             => 'dob',
		);

		foreach ( $traveler_fields as $field_key => $field ) {
			$field['required_field'] = $field['required_field'] ?? $field['validations']['required'] ?? false;

			if ( method_exists( $this, $field_key ) ) {
				$value = $traveler_data[ $field_name_mapping[ $field_key ] ] ?? '';
				if ( empty( $value ) && $field['required_field'] ) {
					$this->errors[ $field_key ] = self::REQUIRED;
					continue;
				}

				$this->$field_key( $value, $field );
			} else {
				$this->default( $data, $field );
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
	protected function traveler_title( string $value ) {
		$this->data['travelers']['title'] = sanitize_text_field( $value );
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
	protected function traveler_first_name( string $value ) {

		if ( ! $this->validate_name( $value ) ) {
			$this->errors['traveler_first_name'] = static::ILLEGAL_CHARACTERS;

			return;
		}

		$this->data['travelers']['fname'] = sanitize_text_field( $value );
	}

	/**
	 * Sets the last name for a traveler.
	 *
	 * Validates the given last name and stores it in the data array if it passes validation.
	 *
	 * @param string $value The last name to be set for the traveler.
	 *
	 * @return void
	 */
	protected function traveler_last_name( string $value ) {

		if ( ! $this->validate_name( $value ) ) {
			$this->errors['traveler_last_name'] = self::ILLEGAL_CHARACTERS;

			return;
		}

		$this->data['travelers']['lname'] = sanitize_text_field( $value );
	}

	/**
	 * Sets the value of the phone number for the traveler.
	 *
	 * @param mixed $value The phone number value to be set for the traveler.
	 *
	 * @return void
	 */
	protected function traveler_passport_number( $value ) {
		$this->data['travelers']['passport'] = sanitize_text_field( $value );
	}

	/**
	 * Sets the email address for a traveler.
	 *
	 * Validates the given email address and stores it in the data array if it passes validation.
	 *
	 * @param string $value The email address to be set for the traveler.
	 *
	 * @return void
	 */
	protected function traveler_email( string $value ) {
		if ( ! $this->validate_email( $value ) ) {
			$this->errors['traveler_email'] = static::INVALID_VALUE;

			return;
		}

		$this->data['travelers']['email'] = sanitize_email( $value );
	}

	/**
	 * Sets the address for the traveler.
	 *
	 * @param string $value The address value to be sanitized and stored.
	 *
	 * @return void
	 */
	protected function traveler_address( string $value ) {
		$this->data['travelers']['address'] = sanitize_text_field( $value );
	}

	/**
	 * Sets the city for the traveler.
	 *
	 * @param string $value The city value to be checked for illegal characters and stored.
	 *
	 * @return void
	 */
	protected function traveler_city( string $value ) {
		if ( ! $this->validate_name( $value ) ) {
			$this->errors['traveler_city'] = self::ILLEGAL_CHARACTERS;

			return;
		}
		$this->data['travelers']['city'] = sanitize_text_field( $value );
	}

	/**
	 * Sets the postcode for the traveler.
	 *
	 * @param string $value The postcode value to be checked.
	 *
	 * @return void
	 */
	protected function traveler_postcode( string $value ) {
		$this->data['travelers']['postcode'] = sanitize_text_field( $value );
	}

	/**
	 * Sets the value of the phone number for the traveler.
	 *
	 * @param mixed $value The phone number value to be set for the traveler.
	 *
	 * @return void
	 */
	protected function traveler_phone( $value ) {
		$this->data['travelers']['phone'] = $this->sanitize_phone( $value );
	}

	/**
	 * Sets the value of the date of birth for the traveler.
	 *
	 * @param mixed $value The date of birth to be set for the traveler.
	 *
	 * @return void
	 */
	protected function traveler_dob( $value ) {
		$this->data['travelers']['dob'] = sanitize_text_field( $value );
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
	protected function default( array $data, array $settings ) {
		$name = $settings['name'];

		if ( empty( $data[ $name ] ) && ( $settings['required_field'] !== 'false' ) ) {
			$this->errors[ $name ] = self::REQUIRED;

			return;
		}
		$this->data['travelers'][ $settings['name'] ] = sanitize_text_field( $data[ $settings['name'] ] ?? '' );
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
	protected function fname( string $value, $key ) {
		if ( ! $this->validate_name( $value ) ) {
			$this->errors[ $key ] = static::ILLEGAL_CHARACTERS;

			return;
		}

		$this->data['travelers'][ $key ]['fname'] = sanitize_text_field( $value );
	}

	/**
	 * Sets the last name for a traveler.
	 *
	 * Validates the given last name and stores it in the data array if it passes validation.
	 *
	 * @param string $value The last name to be set for the traveler.
	 *
	 * @return void
	 */
	protected function lname( string $value, $key ) {

		if ( ! $this->validate_name( $value ) ) {
			$this->errors[ $key ] = self::ILLEGAL_CHARACTERS;

			return;
		}

		$this->data['travelers'][ $key ]['lname'] = sanitize_text_field( $value );
	}

	/**
	 * Sets the email address for a traveler.
	 *
	 * Validates the given email address and stores it in the data array if it passes validation.
	 *
	 * @param string $value The email address to be set for the traveler.
	 *
	 * @return void
	 */
	protected function email( string $value, $key ) {
		if ( ! $this->validate_email( $value ) ) {
			$this->errors[ $key ] = static::INVALID_VALUE;

			return;
		}

		$this->data['travelers'][ $key ]['email'] = sanitize_email( $value );
	}


	/**
	 * Sets the value of the phone number for the traveler.
	 *
	 * @param mixed $value The phone number value to be set for the traveler.
	 *
	 * @return void
	 */
	protected function phone( $value, $key ) {
		$this->data['travelers'][ $key ]['phone'] = $this->sanitize_phone( $value );
	}

	/**
	 * Sets the city for the traveler.
	 *
	 * @param string $value The city value to be checked for illegal characters and stored.
	 *
	 * @return void
	 */
	protected function country( string $value, $key ) {
		$value = $this->sanitize_country( $value );
		if ( empty( $value ) ) {
			$this->errors[ $key ] = static::INVALID_VALUE;
			return;
		}
		$this->data['travelers'][ $key ]['country'] = $value;
	}

	public function validate_data( $data ) {

		$this->raw_data = $data;

		$field_name_mapping = array(
			'traveller_first_name' => 'fname',
			'traveller_last_name'  => 'lname',
			'traveller_email'      => 'email',
			'traveller_phone'      => 'phone',
			'traveller_country'    => 'country',
		);

		$traveler_fields = apply_filters( 'wp_travel_engine_traveller_info_fields_display', TravelersFields::traveller_form_fields() );

		$lead_traveler_fields = apply_filters( 'wp_travel_engine_lead_traveller_info_fields_display', TravelersFields::lead_traveller_form_fields() );

		$this->process_traveler_fields( $lead_traveler_fields, $data, $field_name_mapping );
		$this->process_traveler_fields( $traveler_fields, $data, $field_name_mapping );
	}

	/**
	 * Process the traveler fields and set the data to travelers array.
	 *
	 * @param array $traveler_fields Traveler fields.
	 * @param array $data Data.
	 * @param array $field_name_mapping Field name mapping.
	 * @since 6.4.3
	 */
	protected function process_traveler_fields( array $traveler_fields, array $data, array $field_name_mapping ) {
		if ( ! empty( $traveler_fields ) ) {
			foreach ( $traveler_fields as $field_key => $field ) {
				foreach ( $data as $key => $value ) {
					$mapped_key = $field_name_mapping[ $field_key ] ?? $field['name'] ?? '';

					// For exisiting naming convention of travelers fields.
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

					// Get the value of the mapped key.
					$mapped_value = $value[ $mapped_key ] ?? $value[ $field_key ] ?? '';

					// Check if the field is required.
					$is_required = $field['validations']['required'] ?? false;

					// Check if the method exists.
					if ( method_exists( $this, $mapped_key ) ) {
						$value = $value[ $mapped_key ] ?? '';
						if ( empty( $value ) && $is_required ) {
							$this->errors[ $mapped_key ] = self::REQUIRED;
							continue;
						}
						$this->$mapped_key( $value, $key );
					} elseif ( empty( $value[ $mapped_key ] ) && $is_required ) {
							$this->errors[ $mapped_key ] = self::REQUIRED;
							continue;
					}
					$this->data['travelers'][ $key ][ $mapped_key ] = is_array( $mapped_value ) ? array_map( 'sanitize_text_field', $mapped_value ) : sanitize_text_field( $mapped_value );
				}
			}
		}
	}
}
