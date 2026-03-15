<?php
/**
 *
 * @since 6.4.0
 */

namespace WPTravelEngine\Builders\FormFields;

use WPTravelEngine\Abstracts\BookingEditFormFields;
use WPTravelEngine\Helpers\Countries;
use WTE_Default_Form_Fields;
/**
 * Form field class to render billing form fields.
 *
 * @since 6.4.0
 */
class EmergencyEditFormFields extends BookingEditFormFields {

	/**
	 * Emergency contact count
	 *
	 * @var int
	 */
	protected $count;

	public function __construct( array $defaults = array(), string $mode = 'edit', $booking = null ) {
		parent::__construct( $defaults, $mode );
		$this->count  = $defaults['index'] ?? ( $defaults['total_count'] ?? 0 ) + 1;
		static::$mode = $mode;
		$this->init( $this->map_fields( static::structure( $mode, $booking ) ) );
	}

	public static function create( ...$args ): EmergencyEditFormFields {
		return new static( ...$args );
	}

	protected function map_field( $field ) {
		$name = null;

		$field = parent::map_field( $field );

		// Extract the name using regex patterns.
		if ( preg_match( '#\[([^\[]+)]$#', $field['name'], $matches ) ) {
			$name = $matches[1];
		} elseif ( preg_match( '/^[^\s]+$/', $field['name'], $matches ) ) {
			$name = $matches[0];
		}

		// If a name was found, set field attributes.
		if ( $name ) {
			$field['name']                    = sprintf( 'emergency_contacts[%s][]', $name );
			$field['id']                      = sprintf( 'emergency_contacts_%s', $name );
			$field['field_label']             = isset( $field['placeholder'] ) && $field['placeholder'] !== '' ? $field['placeholder'] : ( $field['field_label'] ?? '' );
			$field['default']                 = $this->defaults[ $name ] ?? $field['default'] ?? '';
			$field['validations']['required'] = false;
			// Convert country code to country name to show in the emergency form.
			$countries_list = Countries::list();
			if ( $field['type'] == 'country' ) {
				foreach ( $countries_list as $key => $value ) {
					if ( $field['default'] === $value ) {
						$field['default'] = $key;
					}
				}
			}
		}

		// Convert datepicker to text input to fix styling issue and to enable time selection.
		if ( $field['type'] === 'datepicker' ) {
			$field['type']       = 'text';
			$field['class']      = 'wpte-date-picker';
			$field['attributes'] = array(
				'data-options' => array(
					'enableTime' => true,
					'dateFormat' => 'Y-m-d H:i',
				),
			);
		}

		if ( static::$mode !== 'edit' && ! ( $field['skip_disabled'] ?? false ) ) {
			$field['option_attributes'] = array(
				'disabled' => 'disabled',
			);
			$field['attributes']        = array(
				'disabled' => 'disabled',
			);
		}

		if ( $field['type'] == 'checkbox' || $field['type'] == 'radio' && isset( $this->count ) ) {
			$field['name'] = sprintf( 'emergency_contacts[%s][%s]', $name, $this->count );
		}
		return $field;
	}

	/**
	 * Structure the form fields.
	 *
	 * @param string $mode
	 * @param object $booking
	 * @return array
	 */
	static function structure( string $mode = 'edit', $booking = null ): array {
		if ( $booking && $booking->get_meta( 'traveller_page_type' ) == 'old' ) {
			return WTE_Default_Form_Fields::emergency_contact();
		} else {
			return DefaultFormFields::emergency( $mode );
		}
	}
}
