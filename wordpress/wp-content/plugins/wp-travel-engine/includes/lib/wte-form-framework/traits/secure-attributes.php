<?php
/**
 * Secure Attributes Trait
 *
 * Prevents XSS by validating attribute names and escaping values.
 *
 * @package WPTravelEngine
 * @since 6.7.6
 */

/**
 * Trait Secure_Attributes_Trait
 */
trait Secure_Attributes {

	/**
	 * Render safe attributes with validation and escaping.
	 *
	 * @param array $attributes Attributes array.
	 * @param array $skip       Optional. Attributes to skip.
	 * @return string
	 */
	protected function render_safe_attributes( $attributes, $skip = array() ) {
		if ( empty( $attributes ) ) {
			return '';
		}

		$output = '';
		foreach ( $attributes as $name => $value ) {
			if ( in_array( $name, $skip, true ) ) {
				continue;
			}

			// Block dangerous attributes (event handlers, etc.).
			if ( $this->is_dangerous_attribute( $name ) ) {
				continue;
			}

			// Special handling for data-options.
			if ( 'data-options' === $name ) {
				$output .= sprintf(
					' %s="%s"',
					esc_attr( $name ),
					htmlspecialchars( wp_json_encode( $value, JSON_HEX_QUOT ), ENT_QUOTES, 'UTF-8' )
				);
				continue;
			}

			$output .= sprintf( ' %s="%s"', esc_attr( $name ), esc_attr( $value ) );
		}

		return $output;
	}

	/**
	 * Check if attribute is dangerous.
	 *
	 * @param string $name Attribute name.
	 * @return bool
	 */
	protected function is_dangerous_attribute( $name ) {
		// Block event handlers (onclick, onload, etc.).
		if ( preg_match( '/^on/i', $name ) ) {
			return true;
		}

		// Block dangerous attributes.
		$dangerous = array( 'srcdoc', 'formaction' );
		return in_array( strtolower( $name ), $dangerous, true );
	}

	/**
	 * Render validation attributes.
	 *
	 * @param array $validations Validation rules.
	 * @return string
	 */
	protected function render_validation_attributes( $validations ) {
		if ( empty( $validations ) ) {
			return '';
		}

		$output = '';
		foreach ( $validations as $key => $value ) {
			// Only allow safe validation names.
			if ( ! preg_match( '/^[a-z0-9\-]+$/i', $key ) ) {
				continue;
			}

			$attr_name = 'data-parsley-' . strtolower( $key );
			$output   .= sprintf( ' %s="%s"', esc_attr( $attr_name ), esc_attr( $value ) );
		}

		return $output;
	}
}
