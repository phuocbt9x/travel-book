<?php
/**
 *
 * @since 6.4.0
 */

namespace WPTravelEngine\Builders\FormFields;

use WPTravelEngine\Abstracts\BookingEditFormFields;
use WPTravelEngine\Core\Models\Post\Booking;
use WPTravelEngine\Helpers\Countries;
class BillingEditFormFields extends BookingEditFormFields {

	protected ?Booking $booking = null;

	public function __construct( array $defaults = array(), string $mode = 'edit' ) {
		parent::__construct( $defaults, $mode );
		static::$mode = $mode;
		$this->init( $this->map_fields( static::structure( $mode ) ) );
	}

	protected function map_field( $field ) {

		global $current_screen;
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
			$field['name']        = sprintf( 'billing[%s]', $name );
			$field['id']          = sprintf( 'billing_%s', $name );
			$field['field_label'] = isset( $field['placeholder'] ) && $field['placeholder'] !== '' ? $field['placeholder'] : ( $field['field_label'] ?? '' );
			$field['default']     = $this->defaults[ $name ] ?? $field['default'] ?? '';

			$field['validations']['required'] = false;
			// Convert country code to country name to show in the billing form.
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

		if ( $field['type'] == 'file' && ! empty( $field['default'] ) ) {
			$filename = wp_basename( $field['default'] );
			$full_url = $field['default'];

			$field['type']       = 'text';
			$field['value']      = $filename;
			$field['default']    = $filename;
			$field['attributes'] = array(
				'style'         => 'border:none; background:none; cursor:pointer; color:blue; text-decoration:underline;',
				'readonly'      => 'readonly',
				'data-full-url' => $full_url,
			);

			if ( static::$mode === 'edit' ) {
				$field['attributes']['onclick'] = sprintf(
					"var a = document.createElement('a'); a.href='%s'; a.download='%s'; a.click();",
					esc_js( $filename ),
					esc_js( $filename )
				);
			} else {
				$field['attributes']['disabled'] = 'disabled';
				$field['attributes']['style']   .= ' color: #666; cursor: not-allowed;';
			}
		}

		if ( static::$mode !== 'edit' && ! ( $field['skip_disabled'] ?? false ) ) {
			$field['option_attributes'] = array(
				'disabled' => 'disabled',
			);
			$field['attributes']        = array(
				'disabled' => 'disabled',
			);
		}

		if ( 'booking' === $current_screen->id && $current_screen->action == 'add' && 'edit' == static::$mode ) :
			if ( $field['type'] == 'file' && empty( $field['default'] ) ) {
				return;
			}
			return $field;
		endif;

		return $field;
	}

	public static function create( ...$args ): BillingEditFormFields {
		return new static( ...$args );
	}

	public static function structure( string $mode = 'edit' ): array {
		return DefaultFormFields::billing( $mode );
	}
}
