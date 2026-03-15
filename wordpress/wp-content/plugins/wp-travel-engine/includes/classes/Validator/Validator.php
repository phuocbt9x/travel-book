<?php
/**
 * Validator Class
 *
 * This class provides methods for data validation and sanitization.
 *
 * @since 5.8.0
 * @package     WPTravelEngine\Validator
 * @category    Utility
 */

namespace WPTravelEngine\Validator;

use WPTravelEngine\Helpers\Functions;

/**
 * Validator Class
 *
 * This class provides methods for data validation and sanitization.
 *
 * @package     WPTravelEngine\Validator
 * @category    Utility
 */
class Validator {


	const REQUIRED = 'required';

	const ILLEGAL_CHARACTERS = 'illegal_characters';

	const INVALID_VALUE = 'invalid_value';

	/**
	 * @var array $raw_data An empty array to store raw data
	 */
	protected array $raw_data = array();

	/**
	 * @var array $errors An empty array to store errors
	 */
	protected array $errors = array();

	/**
	 * @var array $data An empty array to store data
	 */
	protected array $data = array();

	/**
	 * Check if there are errors.
	 *
	 * This method checks whether the `errors` property of the current object is empty or not.
	 *
	 * @return bool Returns true if there are errors, false otherwise.
	 */
	public function has_errors(): bool {
		return ! empty( $this->errors );
	}

	/**
	 * Retrieve the errors stored in the object.
	 *
	 * @return array The array of errors.
	 */
	public function get_errors(): array {
		return $this->errors;
	}

	/**
	 * Sanitize a phone number by removing all non-digit characters.
	 *
	 * @param string $value The phone number to be sanitized.
	 *
	 * @return string The sanitized phone number.
	 */
	public function sanitize_phone( string $value ): string {
		return preg_replace( '/[^0-9]/', '', $value );
	}

	/**
	 * Sanitize the country data to ensure it is a valid country.
	 *
	 * @param string $value The country data to be sanitized.
	 *
	 * @return string The sanitized country data. If the country is valid, it will be returned as is, otherwise an empty string will be returned.
	 */
	public function sanitize_country( string $value ): string {
		$countries = Functions::get_countries();
		$countries = array_merge( array_keys( $countries ), array_values( $countries ) );

		if ( in_array( $value, $countries ) ) {
			return $value;
		}

		return '';
	}

	/**
	 * Validate the given name value.
	 *
	 * @param string $value The name value to be validated.
	 *
	 * @return string True if the name value is valid, false otherwise.
	 */
	public function validate_name( string $value ): string {
		return sanitize_text_field( $value );
	}

	public function validate_email( string $value ): bool {
		return is_email( $value );
	}

	/**
	 * Retrieve the sanitized data stored in the object.
	 *
	 * @return array The array of sanitized data.
	 */
	public function sanitized(): array {
		return $this->data;
	}
}
