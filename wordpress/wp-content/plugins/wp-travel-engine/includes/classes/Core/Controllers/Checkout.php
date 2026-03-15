<?php

namespace WPTravelEngine\Core\Controllers;

use WPTravelEngine\Core\Cart\Cart;
use WPTravelEngine\Core\Cart\Item;
use WPTravelEngine\Core\Models\Post\Customer;

class Checkout {

	protected Cart $cart;
	/**
	 * @var mixed|null
	 */
	protected $form_fields = null;

	public function __construct( Cart $cart ) {
		$this->cart = $cart;
	}

	protected function add_class_to_form_fields( $field ) {
		$field['wrapper_class'] = 'wpte-bf-field wpte-cf-' . $field['type'];

		return $field;
	}

	/**
	 * @return array
	 * @since 6.0.0
	 * @TODO: Update this get customer info from bookingRef if available.
	 */
	protected function get_customer_details(): array {
		global $wte_cart;

		$user = wp_get_current_user();

		return array(
			'email' => $user->user_email,
			'fname' => $user->first_name,
			'lname' => $user->last_name,
		);
	}

	protected function form_fields(): Checkout {
		if ( ! is_null( $this->form_fields ) ) {
			return $this;
		}
		$checkout_fields = apply_filters( 'wp_travel_engine_booking_fields_display', \WTE_Default_Form_Fields::booking() );

		$this->form_fields = array_map( array( $this, 'add_class_to_form_fields' ), $checkout_fields );

		$customer_details = $this->get_customer_details();

		if ( $customer_id = Customer::is_exists( $customer_details['email'] ) ) {
			$customer         = Customer::make( $customer_id );
			$customer_details = $customer->get_customer_details();
		}
		$this->form_fields = array_map(
			function ( $field ) use ( $customer_details ) {

				$name = $field['name'];
				if ( strpos( $field['name'], '[' ) ) {
					if ( preg_match( '#\[([^\]]+)\]$#', $field['name'], $matches ) ) {
						$name = $matches[1] ?? '';
					}
				}

				if ( isset( $customer_details[ $name ] ) ) {
					$field['default'] = $customer_details[ $name ];
				}

				return $field;
			},
			$this->form_fields
		);

		return $this;
	}

	public function has_form_fields(): bool {
		return ! empty( $this->form_fields()->form_fields );
	}

	public function render_form_fields() {
		wptravelengine_render_form_fields( $this->form_fields );
	}

	protected function privacy_from_fields(): array {
		$options = get_option( 'wp_travel_engine_settings', array() );

		$privacy_policy_form_field = array();
		if ( function_exists( 'get_privacy_policy_url' ) ) {
			$privacy_policy_form_field['privacy_policy_info'] = array(
				'type'              => 'checkbox',
				'options'           => array(
					'0' => sprintf(
						__( 'Check the box to confirm you\'ve read and agree to our <a href="%1$s" id="terms-and-conditions" target="_blank"> Terms and Conditions</a> and <a href="%2$s" id="privacy-policy" target="_blank">Privacy Policy</a>.', 'wp-travel-engine' ),
						esc_url( get_permalink( $options['pages']['wp_travel_engine_terms_and_conditions'] ?? '' ) ),
						esc_url( get_privacy_policy_url() )
					),
				),
				'name'              => 'wp_travel_engine_booking_setting[terms_conditions]',
				'wrapper_class'     => 'wp-travel-engine-terms',
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

	public function render_privacy_form_fields() {
		wptravelengine_render_form_fields( $this->privacy_from_fields() );
	}

	public function get_active_payments(): array {
		global $wte_cart;
		$gateways = wp_travel_engine_get_active_payment_gateways();
		if ( $wte_cart->get_payment_type() === 'due' ) {
			unset( $gateways['booking_only'] );
		}

		return $gateways;
	}

	public function template_mini_cart() {
		$mini_cart = new MiniCart( $this );
		$mini_cart->render();
	}

	public function submit_button() {
		$_attributes = array(
			'type'                => 'submit',
			'disabled'            => 'disabled',
			'data-checkout-label' => __( 'Pay', 'wp-travel-engine' ),
			'name'                => 'wp_travel_engine_nw_bkg_submit',
			'value'               => wte_default_labels( 'checkout.submitButtonText' ),
		);

		$attributes = apply_filters( 'wptravelengine_checkout_submit_button_attributes', $_attributes );

		$attributes = array_map(
			function ( $key, $value ) {
				return sprintf( '%s="%s"', $key, $value );
			},
			array_keys( $attributes ),
			$attributes
		);

		$attributes = implode( ' ', $attributes );

		echo apply_filters( 'wptravelengine_checkout_submit_button', "<div class=\"wpte-bf-field wpte-bf-submit\"><input {$attributes}></div>", $_attributes );
	}

	public function full_payment_label( $full_payment, $settings ) {
		// translators: %s: Full payment Amount/Percentage.
		$label = apply_filters( 'wte_checkout_full_pay_label', __( 'Full payment(%s)', 'wp-travel-engine' ) );
		$value = ( $settings['type'] ?? '' ) === 'amount' ? wptravelengine_the_price( $full_payment, false, false ) : '100%';

		/**
		 * Filters the full payment label.
		 *
		 * @todo to remove below code once placeholder is passed from theme.
		 */
		if ( strpos( $label, '%s' ) !== false ) {
			// If the label contains a placeholder, replace it with the value.
			return wp_kses_post( sprintf( $label, $value ) );
		} else {
			// If the label doesn't contain a placeholder, just return it as is.
			return wp_kses_post( $label );
		}
	}

	/**
	 * Get the down payment label.
	 *
	 * @param $settings
	 *
	 * @return string
	 */
	public function down_payment_label( $settings ): string {
		$is_amount = ( $settings['type'] ?? '' ) === 'amount';
		$value     = $settings['value'] ?? 0;
		$label     = $is_amount ? wptravelengine_the_price( $value, false, false ) : "{$value}%";

		// translators: %s: Down payment Amount/Percentage.down_payment_label
		$format = apply_filters( 'wte_checkout_down_pay_label', __( 'Down payment(%s)', 'wp-travel-engine' ), $settings );
		if ( strpos( $format, '%s' ) !== false ) {
			return sprintf( $format, $label );
		} else {
			return $format;
		}
	}
}
