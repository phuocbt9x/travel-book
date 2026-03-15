<?php
/**
 * Class Checkout
 *
 * This class is responsible for validating checkout data.
 *
 * @package WPTravelEngine\Validator
 * @since 5.8.0
 */

namespace WPTravelEngine\Validator;

/**
 * Class Checkout
 *
 * This class is responsible for validating checkout data.
 */
class Checkout extends Validator {

	/**
	 * Validates the input data.
	 *
	 * @param mixed $data The input data.
	 *
	 * @return $this
	 */
	public function validate( $data ): Checkout {
		$this->raw_data = $data;

		$checkout_fields = apply_filters( 'wp_travel_engine_booking_fields_display', \WTE_Default_Form_Fields::booking() );

		$booking_data = $data['wp_travel_engine_booking_setting']['place_order']['booking'] ?? $data['billing'] ?? array();

		$field_name_mapping = array(
			'booking_first_name' => 'fname',
			'booking_last_name'  => 'lname',
			'booking_email'      => 'email',
			'booking_phone'      => 'phone',
			'booking_address'    => 'address',
			'booking_city'       => 'city',
			'booking_country'    => 'country',
		);

		foreach ( $checkout_fields as $field_key => $field ) {
			$field['required_field'] = $field['validations']['required'] ?? $field['required_field'] ?? false;

			if ( method_exists( $this, $field_key ) ) {
				$value = $booking_data[ $field_name_mapping[ $field_key ] ] ?? $data[ $field['name'] ] ?? '';
				if ( empty( $value ) && ! in_array( $field['required_field'], array( 'false', false ) ) ) {
					$this->errors[ $field_key ] = self::REQUIRED;
					continue;
				}

				$this->$field_key( $value, $field );
			} else {
				$this->default( $data, $field );
			}
		}

		return $this;
	}

	/**
	 * Sets the first name for a booking.
	 *
	 * Validates the given first name and stores it in the data array if it passes validation.
	 *
	 * @param string $value The first name to be set for the booking.
	 *
	 * @return void
	 */
	protected function booking_first_name( string $value ) {

		// if ( ! $this->validate_name( $value ) ) {
		// $this->errors[ 'booking_first_name' ] = static::ILLEGAL_CHARACTERS;
		//
		// return;
		// }

		$this->data['booking']['fname'] = sanitize_text_field( $value );
		// For new checkout page, use of new functionality to set billing data.
		if ( isset( $_POST['billing'] ) ) {
			$this->data['billing']['fname'] = sanitize_text_field( $value );
		}
	}

	/**
	 * Sets the last name for a booking.
	 *
	 * Validates the given last name and stores it in the data array if it passes validation.
	 *
	 * @param string $value The last name to be set for the booking.
	 *
	 * @return void
	 */
	protected function booking_last_name( string $value ) {
		$this->data['booking']['lname'] = sanitize_text_field( $value );
		// For new checkout page, use of new functionality to set billing data.
		if ( isset( $_POST['billing'] ) ) {
			$this->data['billing']['lname'] = sanitize_text_field( $value );
		}
	}

	/**
	 * Sets the email address for a booking.
	 *
	 * Validates the given email address and stores it in the data array if it passes validation.
	 *
	 * @param string $value The email address to be set for the booking.
	 *
	 * @return void
	 */
	protected function booking_email( string $value ) {
		if ( ! $this->validate_email( $value ) ) {

			$this->errors['booking_email'] = static::INVALID_VALUE;

			return;

		}
		$this->data['booking']['email'] = sanitize_email( $value );
		// For new checkout page, use of new functionality to set billing data.
		if ( isset( $_POST['billing'] ) ) {
			$this->data['billing']['email'] = sanitize_email( $value );
		}
	}

	/**
	 * Sets the value of the phone number for the booking.
	 *
	 * @param mixed $value The phone number value to be set for the booking.
	 *
	 * @return void
	 */
	protected function booking_phone( $value ) {
		$this->data['booking']['phone'] = $this->sanitize_phone( $value );
		// For new checkout page, use of new functionality to set billing data.
		if ( isset( $_POST['billing'] ) ) {
			$this->data['billing']['phone'] = $this->sanitize_phone( $value );
		}
	}

	/**
	 * Sets the address for the booking.
	 *
	 * @param string $value The address value to be sanitized and stored.
	 *
	 * @return void
	 */
	protected function booking_address( string $value ) {
		$this->data['booking']['address'] = sanitize_text_field( $value );
		// For new checkout page, use of new functionality to set billing data.
		if ( isset( $_POST['billing'] ) ) {
			$this->data['billing']['address'] = sanitize_text_field( $value );
		}
	}

	/**
	 * Sets the city for the booking.
	 *
	 * @param string $value The city value to be checked for illegal characters and stored.
	 *
	 * @return void
	 */
	protected function booking_city( string $value ) {
		// if ( ! $this->validate_name( $value ) ) {
		// $this->errors[ 'booking_city' ] = self::ILLEGAL_CHARACTERS;
		//
		// return;
		// }

		$this->data['booking']['city'] = sanitize_text_field( $value );
		// For new checkout page, use of new functionality to set billing data.
		if ( isset( $_POST['billing'] ) ) {
			$this->data['billing']['city'] = sanitize_text_field( $value );
		}
	}

	/**
	 * Sets the country for the booking.
	 *
	 * @param string $value The country value to be sanitized and stored.
	 *
	 * @return void
	 */
	protected function booking_country( string $value ) {

		$value = $this->sanitize_country( $value );
		if ( empty( $value ) ) {
			$this->errors['booking_country'] = static::INVALID_VALUE;
			return;
		}
		$this->data['booking']['country'] = $value;
		// For new checkout page, use of new functionality to set billing data.
		if ( isset( $_POST['billing'] ) ) {
			$this->data['billing']['country'] = $value;
		}
	}

	/**
	 * Sets the default value for a field in the booking data.
	 *
	 * If the field value is empty and the field is marked as required,
	 * an error is added to the errors array. Otherwise, the field value
	 * is sanitized and stored in the booking data.
	 *
	 * @param array $data The input data array.
	 * @param array $settings The field settings array.
	 *
	 * @return void
	 */
	protected function default( array $data, array $settings ) {
		$name = $settings['name'];

		if ( $settings['type'] === 'file' ) {
			if ( ! isset( $_FILES[ $name ] ) || ! is_uploaded_file( $_FILES[ $name ]['tmp_name'] ) ) {
				if ( $settings['required_field'] !== 'false' ) {
					$this->errors[ $name ] = self::REQUIRED;
				}
				return;
			}
			$data[ $name ] = $this->upload_file( $_FILES[ $name ] );
			if ( isset( $data['billing'] ) ) {
				$data['billing'][ $name ] = $data[ $name ];
			}
		}

		if ( isset( $data['billing'] ) && isset( $data['billing'][ $name ] ) && ! empty( $data['billing'][ $name ] ) ) {
			$this->set_default_value( $data, $settings );
			return;
		} elseif ( empty( $data[ $name ] ) && $settings['required_field'] !== 'false' ) {
				$this->errors[ $name ] = self::REQUIRED;
				return;
		}

		$value                          = $data[ $name ] ?? '';
		$this->data['booking'][ $name ] = is_array( $value ) ? array_map( 'sanitize_text_field', $value ) : sanitize_text_field( $value );
	}

	/**
	 * Sets the default value for a field in the booking data.
	 *
	 * @param array $data The input data array.
	 * @param array $settings The field settings array.
	 *
	 * @return void
	 */
	public function set_default_value( array $data, array $settings ) {

		$name = $settings['name'];

		if ( isset( $data['billing'] ) && isset( $data['billing'][ $name ] ) && '' == $data['billing'][ $name ] ) {
			$this->errors[ $name ] = self::REQUIRED;
			return;
		} elseif ( isset( $data['emergency'] ) && isset( $data['emergency'][ $name ] ) && '' == $data['emergency'][ $name ] ) {
			$this->errors[ $name ] = self::REQUIRED;
			return;
		} elseif ( isset( $data['travellers'] ) && isset( $data['travellers'][ $name ] ) && '' == $data['travellers'][ $name ] ) {
			$this->errors[ $name ] = self::REQUIRED;
			return;
		}
		if ( isset( $data['billing'] ) ) {
			$value                          = $data['billing'][ $name ] ?? '';
			$this->data['billing'][ $name ] = is_array( $value ) ? array_map( 'sanitize_text_field', $value ) : sanitize_text_field( $value );
		}
	}


	/**
	 * Handles file upload.
	 *
	 * @param array $file The file data array.
	 *
	 * @return string The uploaded file URL.
	 */
	private function upload_file( array $file ) {

		if ( ! function_exists( 'wp_handle_upload' ) ) {
			require_once ABSPATH . 'wp-admin/includes/file.php';
		}

		$upload_data = wp_handle_upload( $file, array( 'test_form' => false ) );

		if ( isset( $upload_data['error'] ) ) {
			$this->errors['file_upload_error'] = $upload_data['error'];
			return '';
		}

		return $upload_data['url'] ?? '';
	}
}
