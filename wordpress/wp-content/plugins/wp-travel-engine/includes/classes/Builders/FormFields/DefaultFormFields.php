<?php

namespace WPTravelEngine\Builders\FormFields;

/**
 * Default Form Fields.
 *
 * @since 6.3.0
 */
use WPTravelEngine\Core\Models\Post\Booking as BookingModel;
class DefaultFormFields extends \WTE_Default_Form_Fields {

	public static function billing( string $mode = 'edit' ): array {
		$fields = array(
			'booking_first_name' => array(
				'type'           => 'text',
				'wrapper_class'  => 'wp-travel-engine-billing-details-field-wrap',
				'field_label'    => __( 'First Name', 'wp-travel-engine' ),
				'label_class'    => 'wpte-bf-label',
				'name'           => 'wp_travel_engine_booking_setting[place_order][booking][fname]',
				'id'             => 'wp_travel_engine_booking_setting[place_order][booking][fname]',
				'validations'    => array(
					'required'  => true,
					'maxlength' => '50',
					'type'      => 'alphanum',
				),
				'attributes'     => array(
					'data-msg'                      => __( 'Please enter your first name', 'wp-travel-engine' ),
					'data-parsley-required-message' => __( 'Please enter your first name', 'wp-travel-engine' ),
				),
				'priority'       => 10,
				'default_field'  => true,
				'required_field' => true,
			),
			'booking_last_name'  => array(
				'type'           => 'text',
				'wrapper_class'  => 'wp-travel-engine-billing-details-field-wrap',
				'field_label'    => __( 'Last Name', 'wp-travel-engine' ),
				'label_class'    => 'wpte-bf-label',
				'name'           => 'wp_travel_engine_booking_setting[place_order][booking][lname]',
				'id'             => 'wp_travel_engine_booking_setting[place_order][booking][lname]',
				'validations'    => array(
					'required'  => true,
					'maxlength' => '50',
					'type'      => 'alphanum',
				),
				'attributes'     => array(
					'data-msg'                      => __( 'Please enter your last name', 'wp-travel-engine' ),
					'data-parsley-required-message' => __( 'Please enter your last name', 'wp-travel-engine' ),
				),
				'priority'       => 20,
				'default_field'  => true,
				'required_field' => true,
			),
			'booking_email'      => array(
				'type'           => 'email',
				'wrapper_class'  => 'wp-travel-engine-billing-details-field-wrap',
				'field_label'    => __( 'Email', 'wp-travel-engine' ),
				'label_class'    => 'wpte-bf-label',
				'name'           => 'wp_travel_engine_booking_setting[place_order][booking][email]',
				'id'             => 'wp_travel_engine_booking_setting[place_order][booking][email]',
				'validations'    => array(
					'required' => true,
				),
				'attributes'     => array(
					'data-msg'                      => __( 'Please enter a valid email address', 'wp-travel-engine' ),
					'data-parsley-required-message' => __( 'Please enter a valid email address', 'wp-travel-engine' ),
				),
				'priority'       => 30,
				'default_field'  => true,
				'required_field' => true,
			),
			'booking_address'    => array(
				'type'          => 'text',
				'wrapper_class' => 'wp-travel-engine-billing-details-field-wrap',
				'field_label'   => __( 'Address', 'wp-travel-engine' ),
				'label_class'   => 'wpte-bf-label',
				'name'          => 'wp_travel_engine_booking_setting[place_order][booking][address]',
				'id'            => 'wp_travel_engine_booking_setting[place_order][booking][address]',
				'validations'   => array(
					'required'  => true,
					'maxlength' => '60',
					'type'      => 'alphanum',
				),
				'attributes'    => array(
					'data-msg'                      => __( 'Please enter your address details', 'wp-travel-engine' ),
					'data-parsley-required-message' => __( 'Please enter your address details', 'wp-travel-engine' ),
				),
				'priority'      => 40,
				'default_field' => true,
			),
			'booking_city'       => array(
				'type'          => 'text',
				'wrapper_class' => 'wp-travel-engine-billing-details-field-wrap',
				'field_label'   => __( 'City', 'wp-travel-engine' ),
				'label_class'   => 'wpte-bf-label',
				'name'          => 'wp_travel_engine_booking_setting[place_order][booking][city]',
				'id'            => 'wp_travel_engine_booking_setting[place_order][booking][city]',
				'validations'   => array(
					'required' => true,
				),
				'attributes'    => array(
					'data-msg'                      => __( 'Please enter your city name', 'wp-travel-engine' ),
					'data-parsley-required-message' => __( 'Please enter your city name', 'wp-travel-engine' ),
				),
				'priority'      => 50,
				'default_field' => true,
			),
			'booking_country'    => array(
				'type'          => 'country_dropdown',
				'field_label'   => __( 'Country', 'wp-travel-engine' ),
				'label_class'   => 'wpte-bf-label',
				'wrapper_class' => 'wp-travel-engine-billing-details-field-wrap',
				'name'          => 'wp_travel_engine_booking_setting[place_order][booking][country]',
				'id'            => 'wp_travel_engine_booking_setting[place_order][booking][country]',
				'validations'   => array(
					'required' => true,
				),
				'attributes'    => array(
					'data-msg'                      => __( 'Please choose your country from the list', 'wp-travel-engine' ),
					'data-parsley-required-message' => __( 'Please choose your country from the list', 'wp-travel-engine' ),
				),
				'priority'      => 60,
				'default_field' => true,
			),
		);

		return static::by_mode( apply_filters( 'wp_travel_engine_booking_fields_display', $fields ), $mode );
	}

	/**
	 * @param string $mode
	 *
	 * @return array
	 * @since 6.4.0
	 * @updated 6.7.0
	 */
	public static function payments( string $mode = 'edit' ): array {
		$booking = BookingModel::for( get_the_ID(), get_post() );

		if ( $booking->is_curr_cart() ) {
			return static::payment_in_v4( $mode );
		}

		return static::payment_before_v4( $mode );
	}

	/**
	 * Payment form fields before v4.
	 *
	 * @param string $mode Mode.
	 * @return array
	 */
	private static function payment_before_v4( string $mode ): array {
		$fields = apply_filters(
			'wptravelengine_payments_form_fields',
			array(
				'id'               => array(
					'type'          => 'text',
					'wrapper_class' => 'row-repeater name-holder',
					'field_label'   => __( 'Payment ID', 'wp-travel-engine' ),
					'name'          => 'payments[id][]',
					'id'            => 'payment_id',
					'class'         => 'input',
					'attributes'    => array( 'readonly' => 'readonly' ),
					'placeholder'   => __( 'Payment ID', 'wp-travel-engine' ),
				),
				'status'           => array(
					'type'          => 'select',
					'wrapper_class' => 'row-repeater',
					'field_label'   => __( 'Status', 'wp-travel-engine' ),
					'name'          => 'payments[status][]',
					'id'            => 'payments_status',
					'class'         => 'input',
					'options'       => array_reduce(
						array_keys( wptravelengine_payment_status() ),
						function ( $carry, $key ) {
							$skip_pairs = array(
								'cancel'           => 'cancelled',
								'complete'         => 'completed',
								'voucher-awaiting' => 'voucher-waiting',
							);

							if ( in_array( $key, array_keys( $skip_pairs ) ) && isset( $carry[ $skip_pairs[ $key ] ] ) ) {
								return $carry;
							}

							$carry[ $key ] = wptravelengine_payment_status()[ $key ];
							return $carry;
						},
						array()
					),
				),
				'gateway'          => array(
					'type'          => 'text',
					'wrapper_class' => 'row-repeater',
					'field_label'   => __( 'Payment Gateway', 'wp-travel-engine' ),
					'name'          => 'payments[gateway][]',
					'id'            => 'payments_gateway',
					'class'         => 'input',
				),
				'amount'           => array(
					'type'          => 'number',
					'wrapper_class' => 'row-repeater',
					'field_label'   => __( 'Amount', 'wp-travel-engine' ),
					'name'          => 'payments[amount][]',
					'id'            => 'payments_amount',
					'class'         => 'input',
					'attributes'    => array( 'step' => 'any' ),
				),
				'currency'         => array(
					'type'          => 'currency_picker',
					'wrapper_class' => 'row-repeater',
					'field_label'   => __( 'Currency', 'wp-travel-engine' ),
					'name'          => 'payments[currency][]',
					'id'            => 'payments_currency',
					'class'         => 'input',
				),
				'transaction_id'   => array(
					'type'          => 'text',
					'wrapper_class' => 'row-repeater',
					'field_label'   => __( 'Transaction ID', 'wp-travel-engine' ),
					'name'          => 'payments[transaction_id][]',
					'id'            => 'payments_transaction_id',
					'class'         => 'input',
				),
				// 'transaction_date' => array(
				// 'type'          => 'datepicker',
				// 'wrapper_class' => 'row-repeater',
				// 'field_label'   => __( 'Transaction Date', 'wp-travel-engine' ),
				// 'name'          => 'payments[transaction_date][]',
				// 'id'            => 'payments_transaction_date',
				// 'class'         => 'input',
				// ),
				'gateway_response' => array(
					'type'          => 'textarea',
					'wrapper_class' => 'row-repeater',
					'field_label'   => __( 'Gateway Response', 'wp-travel-engine' ),
					'name'          => 'payments[gateway_response][]',
					'id'            => 'payments_gateway_response',
					'class'         => 'input',
					'attributes'    => array( 'readonly' => 'readonly' ),
				),
			)
		);

		return static::by_mode( $fields, $mode );
	}

	/**
	 * Payment form fields in v4.
	 *
	 * @param string $mode Mode.
	 * @return array
	 * @since 6.7.0
	 */
	private static function payment_in_v4( string $mode ): array {
		$payment_gateways = wp_travel_engine_get_sorted_payment_gateways();
		foreach ( $payment_gateways as $key => $payment_gateway ) {
			$payment_gateways_options[ $key ] = $payment_gateway['label'];
		}
		$payment_gateways_options = array( '' => __( 'Choose a Payment Gateway', 'wp-travel-engine' ) ) + $payment_gateways_options;
		$payment_gateways_options = apply_filters( 'wptravelengine_payments_form_fields_options', $payment_gateways_options );

		$fields = apply_filters(
			'wptravelengine_payments_form_fields',
			array(
				'id'               => array(
					'type'          => 'text',
					'wrapper_class' => 'row-repeater name-holder',
					'field_label'   => __( 'Payment ID', 'wp-travel-engine' ),
					'name'          => 'payments[id][]',
					'id'            => 'payments_id',
					'class'         => 'input',
					'attributes'    => array( 'readonly' => 'readonly' ),
					'placeholder'   => __( 'Payment ID', 'wp-travel-engine' ),
					'order'         => 1,
				),
				'status'           => array(
					'type'          => 'select',
					'wrapper_class' => 'row-repeater',
					'field_label'   => __( 'Payment Status', 'wp-travel-engine' ),
					'name'          => 'payments[status][]',
					'id'            => 'payments_status',
					'class'         => 'input',
					'options'       => apply_filters(
						'wptravelengine_payments_form_fields_status_options',
						array(
							'__skip_none_option__' => true,
							'completed' => __( 'Completed', 'wp-travel-engine' ),
							'pending'   => __( 'Pending', 'wp-travel-engine' ),
							'failed'    => __( 'Failed', 'wp-travel-engine' ),
							'refunded'  => __( 'Refunded', 'wp-travel-engine' ),
						)
					),
					'order'         => 2,
				),
				'gateway'          => array(
					'type'          => 'select',
					'wrapper_class' => 'row-repeater',
					'field_label'   => __( 'Payment Gateway', 'wp-travel-engine' ),
					'name'          => 'payments[gateway][]',
					'id'            => 'payments_gateway',
					'class'         => 'input',
					'order'         => 3,
					'options'       => $payment_gateways_options,
				),
				'deposit'          => array(
					'type'          => 'number',
					'wrapper_class' => 'row-repeater',
					'field_label'   => __( 'Deposit Amount', 'wp-travel-engine' ),
					'name'          => 'payments[deposit][]',
					'id'            => 'payments_deposit',
					'class'         => 'input',
					'attributes'    => array(
						'step'          => 'any',
						'data-key'      => 'deposit',
						'show_prefix'   => true,
						'wrapper_class' => 'wpte-amount-wrap',
						'prefix_class'  => 'wpte-amount-currency',
					),
					'order'         => 4,
				),
				'amount'           => array(
					'type'          => 'number',
					'wrapper_class' => 'row-repeater',
					'field_label'   => __( 'Paid Amount', 'wp-travel-engine' ),
					'name'          => 'payments[amount][]',
					'id'            => 'payments_amount',
					'class'         => 'input',
					'order'         => 6,
					'attributes'    => array(
						'step'          => 'any',
						'data-key'      => 'amount',
						'show_prefix'   => true,
						'wrapper_class' => 'wpte-amount-wrap',
						'prefix_class'  => 'wpte-amount-currency',
						'readonly'      => 'readonly',
					),
				),
				'payable'          => array(
					'type'          => 'number',
					'wrapper_class' => 'row-repeater',
					'field_label'   => __( 'Payable Amount', 'wp-travel-engine' ),
					'name'          => 'payments[payable][]',
					'id'            => 'payments_payable',
					'class'         => 'input',
					'attributes'    => array(
						'readonly'      => 'readonly',
						'show_prefix'   => true,
						'wrapper_class' => 'wpte-amount-wrap',
						'prefix_class'  => 'wpte-amount-currency',
					),
					'order'         => 6,
				),
				'date'             => array(
					'type'          => 'text',
					'wrapper_class' => 'row-repeater name-holder',
					'field_label'   => __( 'Date', 'wp-travel-engine' ),
					'name'          => 'payments[date][]',
					'id'            => 'payments_date',
					'class'         => 'input',
					'disabled'      => true,
					'attributes'    => array( 'readonly' => 'readonly' ),
					'order'         => 7,
				),
				'transaction_id'   => array(
					'type'          => 'text',
					'wrapper_class' => 'row-repeater',
					'field_label'   => __( 'Transaction ID', 'wp-travel-engine' ),
					'name'          => 'payments[transaction_id][]',
					'id'            => 'payments_transaction_id',
					'class'         => 'input',
					'order'         => 9,
					'attributes'    => array( 'readonly' => 'readonly' ),
				),
				'gateway_response' => array(
					'type'          => 'textarea',
					'wrapper_class' => 'row-repeater',
					'field_label'   => __( 'Gateway Response', 'wp-travel-engine' ),
					'name'          => 'payments[gateway_response][]',
					'id'            => 'payments_gateway_response',
					'class'         => 'input',
					'attributes'    => array( 'readonly' => 'readonly' ),
					'order'         => 9,
				),
			)
		);

		return static::by_mode( $fields, $mode );
	}

	/**
	 * @param string $mode
	 *
	 * @return array
	 * @since 6.4.0
	 */
	public static function traveller( string $mode = 'edit' ): array {
		return static::traveller_form_fields( $mode );
	}

	/**
	 * @param string $mode
	 *
	 * @since 6.4.3
	 * @return array
	 */
	public static function lead_traveller( string $mode = 'edit' ): array {
		return static::lead_traveller_form_fields( $mode );
	}

	/**
	 * @param string $mode
	 *
	 * @return array
	 * @since 6.4.0
	 */
	public static function emergency( string $mode ): array {
		return static::emergency_form_fields( $mode );
	}

	/**
	 * @param array  $fields
	 * @param string $mode
	 *
	 * @return array
	 * @since 6.4.0
	 */
	public static function by_mode( array $fields, string $mode ): array {
		return array_map(
			function ( $field ) use ( $mode ) {
				$_field = $field;
				if ( $field['context'][ $mode ] ?? false ) {
						$_field = array_merge( $field, $_field );
				}

				if ( 'readonly' === $mode ) {
					$_field['attributes']['readonly'] = 'readonly';
				}
				if ( 'disabled' === $mode ) {
					$_field['attributes']['disabled'] = 'disabled';
				}
				if ( ( $_field['type'] === 'select' || $_field['type'] === 'country_dropdown' || $_field['type'] === 'textarea' || $_field['type'] === 'currency-picker' || $_field['type'] === 'checkbox' ) && $mode !== 'edit' ) {
					$_field['attributes']['disabled'] = 'disabled';
				}

				return $_field;
			},
			$fields
		);
	}

	/**
	 * Traveller Information form fields.
	 *
	 * @param string $mode
	 *
	 * @return array
	 */
	public static function traveller_form_fields( string $mode = 'edit' ): array {

		$fields = apply_filters(
			'wp_travel_engine_traveller_info_fields_display',
			array(
				'traveller_first_name' => array(
					'type'          => 'text',
					'wrapper_class' => 'wpte-checkout__form-col',
					'class'         => 'wpte-checkout__input',
					'field_label'   => __( 'First Name', 'wp-travel-engine' ),
					'name'          => 'wp_travel_engine_placeorder_setting[place_order][travelers][fname]',
					'id'            => 'wp_travel_engine_placeorder_setting[place_order][travelers][fname]',
					'validations'   => array(
						'required'  => true,
						'maxlength' => '50',
						'type'      => 'alphanum',
					),
					'priority'      => 20,
					'default_field' => true,
				),

				'traveller_last_name'  => array(
					'type'          => 'text',
					'wrapper_class' => 'wpte-checkout__form-col',
					'class'         => 'wpte-checkout__input',
					'field_label'   => __( 'Last Name', 'wp-travel-engine' ),
					'name'          => 'wp_travel_engine_placeorder_setting[place_order][travelers][lname]',
					'id'            => 'wp_travel_engine_placeorder_setting[place_order][travelers][lname]',
					'validations'   => array(
						'required'  => true,
						'maxlength' => '50',
						'type'      => 'alphanum',
					),
					'priority'      => 30,
					'default_field' => true,
				),

				'traveller_email'      => array(
					'type'          => 'email',
					'wrapper_class' => 'wpte-checkout__form-col',
					'class'         => 'wpte-checkout__input',
					'field_label'   => __( 'Email', 'wp-travel-engine' ),
					'name'          => 'wp_travel_engine_placeorder_setting[place_order][travelers][email]',
					'id'            => 'wp_travel_engine_placeorder_setting[place_order][travelers][email]',
					'validations'   => array(
						'required' => true,
					),
					'priority'      => 50,
					'default_field' => true,
				),
				'traveller_phone'      => array(
					'type'          => 'tel',
					'wrapper_class' => 'wpte-checkout__form-col',
					'field_label'   => __( 'Phone', 'wp-travel-engine' ),
					'name'          => 'wp_travel_engine_placeorder_setting[place_order][travelers][phone]',
					'id'            => 'wp_travel_engine_placeorder_setting[place_order][travelers][phone]',
					'validations'   => array(
						'required'  => true,
						'maxlength' => '50',
						'type'      => 'alphanum',
					),
					'priority'      => 100,
					'default_field' => true,
					'class'         => 'wpte-checkout__input',
				),

				'traveller_country'    => array(
					'type'          => 'country_dropdown',
					'field_label'   => __( 'Country', 'wp-travel-engine' ),
					'wrapper_class' => 'wpte-checkout__form-col',
					'name'          => 'wp_travel_engine_placeorder_setting[place_order][travelers][country]',
					'id'            => 'wp_travel_engine_placeorder_setting[place_order][travelers][country]',
					'validations'   => array(
						'required' => true,
					),
					'attributes'    => array(
						'data-field-type' => 'country-selector',
					),
					'priority'      => 80,
					'default_field' => true,
					'class'         => 'wpte-checkout__input',
					'context'       => array(
						'readonly' => array(
							'attributes' => array(
								'data-field-type' => 'country-selector',
								'disabled'        => 'disabled',
							),
						),
					),
				),
			)
		);

		return static::by_mode( $fields, $mode );
	}

	/**
	 * Lead Traveller Information form fields.
	 *
	 * @param string $mode
	 *
	 * @since 6.4.3
	 * @return array
	 */
	public static function lead_traveller_form_fields( string $mode = 'edit' ): array {

		$fields = apply_filters(
			'wp_travel_engine_lead_traveller_info_fields_display',
			array(
				'lead_traveller_first_name' => array(
					'type'          => 'text',
					'wrapper_class' => 'wpte-checkout__form-col',
					'class'         => 'wpte-checkout__input',
					'field_label'   => __( 'First Name', 'wp-travel-engine' ),
					'name'          => 'wp_travel_engine_placeorder_setting[place_order][travelers][fname]',
					'id'            => 'wp_travel_engine_placeorder_setting[place_order][travelers][fname]',
					'validations'   => array(
						'required'  => true,
						'maxlength' => '50',
						'type'      => 'alphanum',
					),
					'priority'      => 20,
					'default_field' => true,
				),

				'lead_traveller_last_name'  => array(
					'type'          => 'text',
					'wrapper_class' => 'wpte-checkout__form-col',
					'field_label'   => __( 'Last Name', 'wp-travel-engine' ),
					'name'          => 'wp_travel_engine_placeorder_setting[place_order][travelers][lname]',
					'id'            => 'wp_travel_engine_placeorder_setting[place_order][travelers][lname]',
					'validations'   => array(
						'required'  => true,
						'maxlength' => '50',
						'type'      => 'alphanum',
					),
					'default'       => '',
					'priority'      => 30,
					'default_field' => true,
				),

				'lead_traveller_email'      => array(
					'type'          => 'email',
					'wrapper_class' => 'wpte-checkout__form-col',
					'class'         => 'input',
					'field_label'   => __( 'Email', 'wp-travel-engine' ),
					'name'          => 'wp_travel_engine_placeorder_setting[place_order][travelers][email]',
					'id'            => 'wp_travel_engine_placeorder_setting[place_order][travelers][email]',
					'validations'   => array(
						'required' => true,
					),
					'default'       => '',
					'priority'      => 50,
					'default_field' => true,
				),

				'lead_traveller_phone'      => array(
					'type'          => 'tel',
					'wrapper_class' => 'wpte-checkout__form-col',
					'field_label'   => __( 'Phone', 'wp-travel-engine' ),
					'name'          => 'wp_travel_engine_placeorder_setting[place_order][travelers][phone]',
					'id'            => 'wp_travel_engine_placeorder_setting[place_order][travelers][phone]',
					'validations'   => array(
						'required'  => true,
						'maxlength' => '50',
						'type'      => 'alphanum',
					),
					'default'       => '',
					'priority'      => 100,
					'default_field' => true,
				),

				'lead_traveller_country'    => array(
					'type'          => 'country_dropdown',
					'field_label'   => __( 'Country', 'wp-travel-engine' ),
					'wrapper_class' => 'wpte-checkout__form-col',
					'name'          => 'wp_travel_engine_placeorder_setting[place_order][travelers][country]',
					'class'         => 'wc-enhanced-select',
					'id'            => 'wp_travel_engine_placeorder_setting[place_order][travelers][country]',
					'validations'   => array(
						'required' => true,
					),
					'default'       => '',
					'priority'      => 80,
					'default_field' => true,
				),
			)
		);

		return static::by_mode( $fields, $mode );
	}

	/**
	 * @return array[]
	 */
	public static function emergency_form_fields( string $mode = 'edit' ): array {
		return static::emergency_contact( $mode );
	}

	/**
	 * Emergency Information form fields.
	 *
	 * @return array[]
	 */
	public static function emergency_contact( string $mode = 'edit' ): array {
		$fields = apply_filters(
			'wp_travel_engine_emergency_contact_fields_display',
			array(
				'traveller_emergency_first_name' => array(
					'type'          => 'text',
					'wrapper_class' => 'wpte-checkout__form-col',
					'class'         => 'wpte-checkout__input',
					'field_label'   => __( 'First Name', 'wp-travel-engine' ),
					'name'          => 'wp_travel_engine_placeorder_setting[place_order][relation][fname]',
					'id'            => 'wp_travel_engine_placeorder_setting[place_order][relation][fname]',
					'validations'   => array(
						'required'  => true,
						'maxlength' => '50',
						'type'      => 'alphanum',
					),
					'priority'      => 140,
					'default_field' => true,
				),

				'traveller_emergency_last_name'  => array(
					'type'          => 'text',
					'wrapper_class' => 'wpte-checkout__form-col',
					'class'         => 'wpte-checkout__input',
					'field_label'   => __( 'Last Name', 'wp-travel-engine' ),
					'name'          => 'wp_travel_engine_placeorder_setting[place_order][relation][lname]',
					'id'            => 'wp_travel_engine_placeorder_setting[place_order][relation][lname]',
					'validations'   => array(
						'required'  => true,
						'maxlength' => '50',
						'type'      => 'alphanum',
					),
					'priority'      => 150,
					'default_field' => true,
				),

				'traveller_emergency_phone'      => array(
					'type'          => 'tel',
					'wrapper_class' => 'wpte-checkout__form-col',
					'class'         => 'wpte-checkout__input',
					'field_label'   => __( 'Phone', 'wp-travel-engine' ),
					'name'          => 'wp_travel_engine_placeorder_setting[place_order][relation][phone]',
					'id'            => 'wp_travel_engine_placeorder_setting[place_order][relation][phone]',
					'validations'   => array(
						'required'  => true,
						'maxlength' => '50',
						'type'      => 'alphanum',
					),
					'priority'      => 160,
					'default_field' => true,
				),

				'traveller_emergency_country'    => array(
					'type'          => 'country_dropdown',
					'field_label'   => __( 'Country', 'wp-travel-engine' ),
					'wrapper_class' => 'wpte-checkout__form-col',
					'name'          => 'wp_travel_engine_placeorder_setting[place_order][relation][country]',
					'class'         => 'wpte-checkout__input',
					'id'            => 'wp_travel_engine_placeorder_setting[place_order][relation][country]',
					'validations'   => array(
						'required' => true,
					),
					'priority'      => 80,
					'default_field' => true,
				),

				'traveller_emergency_relation'   => array(
					'type'          => 'text',
					'wrapper_class' => 'wpte-checkout__form-col',
					'class'         => 'wpte-checkout__input',
					'field_label'   => __( 'Relationship', 'wp-travel-engine' ),
					'name'          => 'wp_travel_engine_placeorder_setting[place_order][relation][relation]',
					'id'            => 'wp_travel_engine_placeorder_setting[place_order][relation][relation]',
					'validations'   => array(
						'required'  => true,
						'maxlength' => '50',
						'type'      => 'alphanum',
					),
					'priority'      => 170,
					'default_field' => true,
				),
			)
		);

		return static::by_mode( $fields, $mode );
	}

	/**
	 * Additional Note
	 */
	public static function additional_note() {
		return array(
			'traveller_additional_note' => array(
				'type'          => 'textarea',
				'wrapper_class' => 'wpte-checkout__box-content',
				'class'         => 'wpte-checkout__input',
				'field_label'   => __( 'Add any specific requests or extra details here...', 'wp-travel-engine' ),
				'name'          => 'wptravelengine_additional_note',
				'id'            => 'wptravelengine_additional_note',
				'validations'   => array(
					'required' => false,
				),
				'priority'      => 20,
				'default_field' => true,
			),

		);
	}

	/**
	 * Privacy form fields.
	 */
	public static function privacy_form_fields() {
		$options = get_option( 'wp_travel_engine_settings', array() );

		$privacy_policy_form_field = array();
		$default_label             = __( 'Check the box to confirm you\'ve read and agree to our <a href="%1$s" id="terms-and-conditions" target="_blank"> Terms and Conditions</a> and <a href="%2$s" id="privacy-policy" target="_blank">Privacy Policy</a>.', 'wp-travel-engine' );
		$checkbox_options          = array(
			'0' => sprintf(
				! empty( $options['privacy_policy_msg'] ) ?
					$options['privacy_policy_msg'] . ' <a href="%1$s" id="terms-and-conditions" target="_blank">' . __( 'Terms and Conditions', 'wp-travel-engine' ) . '</a>' . __( ' and', 'wp-travel-engine' ) . '  <a href="%2$s" id="privacy-policy" target="_blank">' . __( 'Privacy Policy', 'wp-travel-engine' ) . '</a>.' :
					$default_label,
				esc_url( get_permalink( $options['pages']['wp_travel_engine_terms_and_conditions'] ?? '' ) ),
				esc_url( get_privacy_policy_url() )
			),
		);
		if ( function_exists( 'get_privacy_policy_url' ) ) {
			$privacy_policy_form_field['privacy_policy_info'] = array(
				'type'              => 'checkbox',
				'options'           => $checkbox_options,
				'name'              => 'wp_travel_engine_booking_setting[terms_conditions]',
				'wrapper_class'     => 'wpte-checkout__form-control',
				'id'                => 'wp_travel_engine_booking_setting[terms_conditions]',
				'default'           => '',
				'validations'       => array(
					'required' => true,
				),
				'option_attributes' => array(
					'required'                      => true,
					'data-msg'                      => __( 'Please make sure to check the privacy policy checkbox', 'wp-travel-engine' ),
					'data-parsley-required-message' => __( 'Please make sure to check the privacy policy checkbox', 'wp-travel-engine' ),
				),
				'priority'          => 70,
			);

		}

		return apply_filters( 'wte_booking_privacy_fields', $privacy_policy_form_field );
	}


	/**
	 * Order Trip form fields.
	 *
	 * @return array
	 * @since 6.4.0
	 */
	public static function order_trip_form_fields( string $mode = 'edit' ): array {
		$booking = BookingModel::for( get_the_ID(), get_post() );

		if ( $booking->is_curr_cart() ) {
			$fields = apply_filters(
				'wptravelengine_order_trip_fields_display',
				array(
					'booked_trip'         => array(
						'type'          => 'select',
						'wrapper_class' => 'row-repeater name-holder',
						'field_label'   => __( 'Booked Trip', 'wp-travel-engine' ),
						'name'          => 'order_trip[id]',
						'id'            => 'order_trip_booked_trip',
					),
					'custom_trip'         => array(
						'type'          => 'text',
						'wrapper_class' => 'row-repeater',
						'field_label'   => __( 'Custom Trip', 'wp-travel-engine' ),
						'name'          => 'order_trip[custom_trip]',
						'id'            => 'order_trip_custom_trip',
						'class'         => 'input',
					),
					'booked_date'         => array(
						'type'          => 'text',
						'wrapper_class' => 'row-repeater name-holder',
						'field_label'   => __( 'Booked Date', 'wp-travel-engine' ),
						'name'          => 'order_trip[booked_date]',
						'id'            => 'order_trip_booked_date',
						'class'         => 'input',
						'disabled'      => true,
					),
					'start_date'          => array(
						'type'          => 'text',
						'wrapper_class' => 'row-repeater',
						'field_label'   => __( 'Start Date', 'wp-travel-engine' ),
						'name'          => 'order_trip[start_date]',
						'id'            => 'order_trip_start_date',
						'class'         => 'wpte-date-picker',
						'attributes'    => array(
							'data-options' => array(
								'enableTime' => true,
								'dateFormat' => 'Y-m-d H:i',
							),
						),
					),
					'end_date'            => array(
						'type'          => 'text',
						'wrapper_class' => 'row-repeater',
						'field_label'   => __( 'End Date', 'wp-travel-engine' ),
						'name'          => 'order_trip[end_date]',
						'id'            => 'order_trip_end_date',
						'class'         => 'wpte-date-picker',
						'attributes'    => array(
							'data-options' => array(
								'enableTime' => true,
								'dateFormat' => 'Y-m-d H:i',
							),
						),
					),
					'trip_code'           => array(
						'type'          => 'text',
						'wrapper_class' => 'row-repeater',
						'field_label'   => __( 'Trip Code', 'wp-travel-engine' ),
						'name'          => 'order_trip[trip_code]',
						'id'            => 'order_trip_trip_code',
						'attributes'    => array( 'readonly' => 'readonly' ),
					),
					'number_of_travelers' => array(
						'type'          => 'number',
						'wrapper_class' => 'row-repeater',
						'field_label'   => __( 'Number of Travelers', 'wp-travel-engine' ),
						'name'          => 'order_trip[number_of_travelers]',
						'id'            => 'order_trip_number_of_travelers',
					),
					'package_name'        => array(
						'type'          => 'select',
						'wrapper_class' => 'row-repeater',
						'field_label'   => __( 'Package Name', 'wp-travel-engine' ),
						'name'          => 'order_trip[package_id]',
						'id'            => 'order_trip_package_id',
						'class'         => 'input',
					),
				)
			);
		} else {
			$fields = apply_filters(
				'wptravelengine_order_trip_fields_display',
				array(
					'booked_trip'         => array(
						'type'          => 'select',
						'wrapper_class' => 'row-repeater name-holder',
						'field_label'   => __( 'Booked Trip', 'wp-travel-engine' ),
						'name'          => 'order_trip[id]',
						'id'            => 'order_trip_booked_trip',
					),
					'booked_date'         => array(
						'type'          => 'text',
						'wrapper_class' => 'row-repeater name-holder',
						'field_label'   => __( 'Booked Date', 'wp-travel-engine' ),
						'name'          => 'order_trip[booked_date]',
						'id'            => 'order_trip_booked_date',
						'class'         => 'input',
						'disabled'      => true,
					),
					'start_date'          => array(
						'type'          => 'text',
						'wrapper_class' => 'row-repeater',
						'field_label'   => __( 'Start Date', 'wp-travel-engine' ),
						'name'          => 'order_trip[start_date]',
						'id'            => 'order_trip_start_date',
						'class'         => 'wpte-date-picker',
						'attributes'    => array(
							'data-options' => array(
								'enableTime' => true,
								'dateFormat' => 'Y-m-d H:i',
							),
						),
					),
					'end_date'            => array(
						'type'          => 'text',
						'wrapper_class' => 'row-repeater',
						'field_label'   => __( 'End Date', 'wp-travel-engine' ),
						'name'          => 'order_trip[end_date]',
						'id'            => 'order_trip_end_date',
						'class'         => 'wpte-date-picker',
						'attributes'    => array(
							'data-options' => array(
								'enableTime' => true,
								'dateFormat' => 'Y-m-d H:i',
							),
						),
					),
					'trip_code'           => array(
						'type'          => 'text',
						'wrapper_class' => 'row-repeater',
						'field_label'   => __( 'Trip Code', 'wp-travel-engine' ),
						'name'          => 'order_trip[trip_code]',
						'id'            => 'order_trip_trip_code',
						'attributes'    => array( 'readonly' => 'readonly' ),
					),
					'number_of_travelers' => array(
						'type'          => 'number',
						'wrapper_class' => 'row-repeater',
						'field_label'   => __( 'Number of Travelers', 'wp-travel-engine' ),
						'name'          => 'order_trip[number_of_travelers]',
						'id'            => 'order_trip_number_of_travelers',
					),
					'package_name'        => array(
						'type'          => 'select',
						'wrapper_class' => 'row-repeater',
						'field_label'   => __( 'Package Name', 'wp-travel-engine' ),
						'name'          => 'order_trip[package_id]',
						'id'            => 'order_trip_package_id',
						'class'         => 'input',
					),
				)
			);
		}

		return static::by_mode( $fields, $mode );
	}
}
