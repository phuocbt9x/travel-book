<?php
/**
 *
 * @since 6.4.0
 */

namespace WPTravelEngine\Builders\FormFields;

use WPTravelEngine\Abstracts\BookingEditFormFields;
use WPTravelEngine\Core\Models\Post\Booking;
use WPTravelEngine\Core\Models\Post\Payment;
use WPTravelEngine\Traits\Singleton;
use WPTravelEngine\Helpers\Functions;
use WPTravelEngine\Utilities\ArrayUtility;

/**
 * Form field class to render billing form fields.
 *
 * @since 6.4.0
 */
class PaymentEditFormFields extends BookingEditFormFields {

	protected ?Payment $payment      = null;
	private const DATEPICKER_OPTIONS = array(
		'dateFormat' => 'Y-m-d H:i:S',
		'enableTime' => true,
		'time_24hr'  => true,
		'allowInput' => true,
	);

	/**
	 * Enable editing for non-checkout payment fields.
	 *
	 * For payments whose source is not 'checkout' (typically created/edited manually
	 * in admin), this method:
	 * - Removes readonly attributes from gateway_response and transaction_id.
	 * - Enables the date field and attaches the Flatpickr date picker with
	 *   datetime support.
	 *
	 * @param array &$fields Form fields array (passed by reference).
	 *
	 * @since 6.7.3
	 * @return void
	 */
	private function apply_non_checkout_editability( array &$fields ): void {
		// Gateway response.
		if ( isset( $fields['gateway_response']['attributes']['readonly'] ) ) {
			unset( $fields['gateway_response']['attributes']['readonly'] );
		}

		// Transaction ID.
		if ( isset( $fields['transaction_id']['attributes']['readonly'] ) ) {
			unset( $fields['transaction_id']['attributes']['readonly'] );
		}

		// Date.
		if ( isset( $fields['date'] ) ) {
			if ( isset( $fields['date']['disabled'] ) ) {
				unset( $fields['date']['disabled'] );
			}
			if ( isset( $fields['date']['attributes']['readonly'] ) ) {
				unset( $fields['date']['attributes']['readonly'] );
			}
			$fields['date']['class']                      = ( $fields['date']['class'] ?? 'input' ) . ' wpte-date-picker';
			$fields['date']['attributes']['data-options'] = wp_json_encode( self::DATEPICKER_OPTIONS );
		}
	}

	/**
	 * Constructor
	 *
	 * @updated 6.7.0
	 */
	public function __construct( array $defaults = array(), string $mode = 'edit', array $labels = array() ) {
		$this->use_legacy_template( true );
		parent::__construct( $defaults, $mode );
		static::$mode = $mode;

		// Get the fields and sort them by order
		$fields = static::structure( $mode );

		$booking = Booking::for( get_the_ID() );

		if ( $booking->is_curr_cart() ) {
			if ( ( $defaults['amount'] ?? 1 ) > 0 ) {
				unset( $fields['payable'] );
			}

			if ( 'edit' !== $mode ) {
				$diff_fields = array_diff_key( $labels, $fields );
				foreach ( $diff_fields as $slug => $label ) {
					$fields[ $slug ] = $this->get_default_structure( $slug, $label );
				}

				foreach ( $defaults['button_details'] ?? array() as $key => $btn ) {
					$id            = $btn['id'] ?? "btn_{$key}";
					$fields[ $id ] = wp_parse_args(
						$btn,
						array(
							'id'            => $id,
							'name'          => '',
							'type'          => 'button',
							'button_type'   => 'button',
							'button_text'   => __( 'Capture', 'wp-travel-engine' ),
							'class'         => 'wpte-button wpte-outlined wpte-button-full',
							'wrapper_class' => 'wpte-field',
							'order'         => $btn['order'] ?? 100,
							'skip_disabled' => true,
							'allow_nopriv'  => false,
						)
					);
				}
			} else {
				$labels['tax']               = wptravelengine_get_tax_label();
				$fields['tax']               = $this->get_default_structure( 'tax', $labels['tax'] );
				$fields['tax']['behaviours'] = array(
					'type' => 'fee',
				);
				$fields['tax']['attributes'] = array(
					'step' => 'any',
				);

				$payment_source = $defaults['payment_source'] ?? '';
				$is_editable    = 'checkout' !== $payment_source;
				/**
				 * Filter whether payment fields should be editable.
				 *
				 * @param bool   $is_editable   Whether fields should be editable. Default: true for non-checkout payments.
				 * @param int    $payment_id   Payment ID (0 for new payments).
				 * @param string $payment_source Payment source ('checkout', 'admin', or empty string).
				 */
				$is_editable = apply_filters(
					'wptravelengine_payment_field_editable',
					$is_editable,
					$defaults['id'] ?? 0,
					$payment_source
				);
				if ( $is_editable ) {
					$this->apply_non_checkout_editability( $fields );
				}
			}

			$fees = array();
			foreach ( $fields as $slug => &$field ) {
				$behav_ = $field['behaviours'] ?? array();
				if ( 'fee' === ( $behav_['type'] ?? '' ) ) {
					if ( 'edit' !== $mode && ! isset( $labels[ $slug ] ) ) {
						unset( $fields[ $slug ] );
						continue;
					}
					$field['type']                   = 'number';
					$field['attributes']['data-key'] = $slug;
					$field['id']                     = 'payments_' . $slug;
					if ( isset( $defaults['id'] ) && 'edit' === $mode ) {
						$field['attributes']['min']  = '0';
						$field['attributes']['step'] = 'any';
					}
					$fees[] = array(
						'label' => $field['field_label'],
					);
				}

				if ( $field['type'] === 'number' ) {
					$field['attributes']['show_prefix']   = true;
					$field['attributes']['wrapper_class'] = 'wpte-amount-wrap';
					$field['attributes']['prefix_class']  = 'wpte-amount-currency';
				}
			}

			global $wte_cart;

			if ( $wte_cart && ! empty( $fees ) ) {
				$excl                             = $wte_cart->get_exclusion_label( $fees );
				$fields['deposit']['field_label'] = $fields['deposit']['field_label'] . ( $excl ? sprintf( ' (excl. %s)', $excl ) : '' );
			}
		} else {
			$fields = array_diff_key( $fields, $labels );
		}

		$sorted_fields = $this->sort_fields_by_order( $fields );
		$this->init( $this->map_fields( $sorted_fields ) );
	}

	/**
	 * Sort fields by their order property.
	 *
	 * @param array $fields
	 * @return array
	 * @updated 6.7.0
	 */
	protected function sort_fields_by_order( array $fields ): array {
		// Sort fields by order property
		uasort(
			$fields,
			function ( $a, $b ) {
				$order_a = $a['order'] ?? 999;
				$order_b = $b['order'] ?? 999;
				return $order_a <=> $order_b;
			}
		);

		return $fields;
	}

	/**
	 * Create.
	 *
	 * @return PaymentEditFormFields
	 */
	public static function create( ...$args ): PaymentEditFormFields {
		return new static( ...$args );
	}

	/**
	 * Map field.
	 *
	 * @param array $field Field.
	 *
	 * @return array
	 * @updated 6.7.0
	 */
	protected function map_field( $field ): array {
		$booking      = Booking::for( get_the_ID(), get_post() );
		$is_curr_cart = $booking->is_curr_cart();
		if ( $is_curr_cart ) {
			return $this->map_template_in_v4( $field );
		} else {
			return $this->map_template_before_v4( $field );
		}
	}

	/**
	 * Map Template before v4.
	 *
	 * @param array $field
	 *
	 * @return array
	 */
	private function map_template_before_v4( array $field ): array {
		$name = null;

		$field = parent::map_field( $field );
		if ( preg_match( '#\[([^\]]+)\]\[\]$#', $field['name'], $matches ) ) {
			$name = $matches[1];
		} elseif ( preg_match( '#\[[^\]]+\]\[([^\]]+)\]$#', $field['name'], $matches ) ) {
			$name = $matches[1];
		}

		if ( $name ) {
			$field['name'] = sprintf( 'payments[%s][]', $name );
			$field['id']   = sprintf( 'payments_%s', $name );
		}
		$field['field_label'] = isset( $field['placeholder'] ) && $field['placeholder'] !== '' ? $field['placeholder'] : $field['field_label'];
		$field['default']     = $this->defaults[ $name ] ?? $field['default'] ?? '';

		if ( static::$mode !== 'edit' ) {
			$field['option_attributes'] = array(
				'disabled' => 'disabled',
			);
			$field['attributes']        = array_merge(
				$field['attributes'] ?? array(),
				array(
					'disabled' => 'disabled',
				)
			);
		}

		$field['wrapper_class'] = apply_filters( 'wptravelengine_payment_edit_form_fields_wrapper_class', 'wpte-field', $field );

		return $field;
	}

	/**
	 * Map Template in v4.
	 *
	 * @param array $field
	 *
	 * @return array
	 * @since 6.7.0
	 */
	private function map_template_in_v4( array $field ): array {
		$field = parent::map_field( $field );

		$name = str_replace( 'payments_', '', $field['id'] );

		$field['field_label'] = isset( $field['placeholder'] ) && $field['placeholder'] !== '' ? $field['placeholder'] : ( $field['field_label'] ?? '' );
		$field['default']     = $this->defaults[ $name ] ?? $field['default'] ?? '';

		// Set dynamic prefix if currency is available and field explicitly requests it.
		if ( isset( $field['attributes']['show_prefix'] ) && $field['attributes']['show_prefix'] ) {
			$currency_code = $this->defaults['currency'] ?? '';

			// Fallback to settings currency if not in defaults (for new bookings)
			if ( empty( $currency_code ) ) {
				$currency_code = wptravelengine_settings()->get( 'currency_code', 'USD' );
			}

			if ( ! empty( $currency_code ) ) {
				$currency_symbol = Functions::currency_symbol_by_code( $currency_code );

				$prefix_value = $currency_code . ' ' . $currency_symbol;

				$field['attributes']['prefix'] = $prefix_value;
			}

			unset( $field['attributes']['show_prefix'] );
		}

		if ( static::$mode !== 'edit' && ! ( $field['skip_disabled'] ?? false ) ) {
			$field['option_attributes'] = array(
				'disabled' => 'disabled',
			);
			$field['attributes']        = array_merge(
				$field['attributes'] ?? array(),
				array(
					'disabled' => 'disabled',
				)
			);
		}

		$field['wrapper_class'] = apply_filters( 'wptravelengine_payment_edit_form_fields_wrapper_class', 'wpte-field', $field );

		return $field;
	}

	public static function structure( string $mode = 'edit' ): array {
		return DefaultFormFields::payments( $mode );
	}

	/**
	 * Get default structure.
	 *
	 * @param string $slug
	 * @param string $label
	 * @return array
	 * @since 6.7.0
	 */
	private function get_default_structure( string $slug, string $label ): array {
		return array(
			'type'          => 'text',
			'wrapper_class' => 'row-repeater',
			'field_label'   => $label,
			'name'          => 'payments[' . $slug . '][]',
			'id'            => 'payments_' . $slug,
			'class'         => 'input',
			'attributes'    => array(
				'data-key'      => $slug,
				'show_prefix'   => true,
				'wrapper_class' => 'wpte-amount-wrap',
				'prefix_class'  => 'wpte-amount-currency',
			),
			'order'         => 5,
		);
	}

	/**
	 * Get extra fields.
	 *
	 * @return array
	 * @since 6.7.0
	 */
	public function get_my_fields(): array {
		return $this->fields;
	}

	/**
	 * Update fields.
	 *
	 * @param mixed $key
	 * @param mixed $value
	 *
	 * @return void
	 * @since 6.7.0
	 */
	public function update_fields( $key, $value ): void {
		$arr = ArrayUtility::make( $this->fields );
		$arr->set( $key, $value );
		$this->fields = $arr->value();
	}
}
