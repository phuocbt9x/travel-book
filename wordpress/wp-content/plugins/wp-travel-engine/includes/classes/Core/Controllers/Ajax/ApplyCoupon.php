<?php
/**
 * Apply Coupon Code Controller.
 *
 * @package @WPTravelEngine/Core/Controllers
 * @since 6.0.0
 */

namespace WPTravelEngine\Core\Controllers\Ajax;

use WPTravelEngine\Abstracts\AjaxController;
use WPTravelEngine\Core\Models\Post\Coupons;

/**
 * Handles ajax request to apply coupon.
 */
class ApplyCoupon extends AjaxController {

	const NONCE_KEY    = '_nonce';
	const NONCE_ACTION = 'wte_session_cart_apply_coupon';
	const ACTION       = 'wte_session_cart_apply_coupon';

	/**
	 * Process Request.
	 * Coupon code is applied.
	 */
	public function process_request() {
		global $wte_cart;
		$apply_coupon_code = $this->request->get_param( 'CouponCode' );
		$apply_trip_ids    = $this->request->get_param( 'trip_ids' ) ?? wp_json_encode( $wte_cart->get_cart_trip_ids() );
		if ( empty( $apply_coupon_code ) ) { // phpcs:ignore
			\wp_send_json_error(
				new \WP_Error( 'WTE_INVALID_REQUEST', __( 'Coupon Code is required.', 'wp-travel-engine' ) )
			);
			die;
		}

		if ( empty( $apply_trip_ids ) ) { // phpcs:ignore

			\wp_send_json_error(
				new \WP_Error( 'WTE_INVALID_REQUEST', __( 'Coupon cannot be applied. No Trips to apply Coupon', 'wp-travel-engine' ) )
			);
			die;
		}

		$coupon_instance = Coupons::by_code( esc_html( $apply_coupon_code ) );

		if ( ! $coupon_instance ) {
			\wp_send_json_error(
				new \WP_Error( 'WTE_COUPON_NOT_EXIST', __( 'Coupon does not exist or has been removed.', 'wp-travel-engine' ) )
			);
			die;
		}

		if ( $coupon_instance->is_valid() ) {
			\wp_send_json_error(
				new \WP_Error( 'WTE_COUPON_INVALID', __( 'Coupon is either inactive or has expired. Coupon Code could not be applied.', 'wp-travel-engine' ) )
			);
			die;
		}

		$trip_ids = wte_clean( wp_unslash( json_decode( wte_clean( wp_unslash( $apply_trip_ids ) ) ) ) ); // phpcs:ignore

		$trip_id = is_array( $trip_ids ) ? array_shift( $trip_ids ) : 0;

		if ( ! $trip_id || $coupon_instance->is_valid( $trip_id ) ) {
			\wp_send_json_error(
				new \WP_Error( 'WTE_COUPON_INVALID', __( 'Coupon Code could not be applied to the selected Trip.', 'wp-travel-engine' ) )
			);
			die;
		}

		$coupon_limit_number = $coupon_instance->get_coupon_limit_number();

		if ( (bool) $coupon_limit_number && ( + $coupon_limit_number <= $coupon_instance->get_coupon_usage_count() ) ) {
			\wp_send_json_error(
				new \WP_Error( 'WTE_COUPON_INVALID', sprintf( __( 'Coupon "%1$s" has expired. Maximum no. of coupon usage exceeded.', 'wp-travel-engine' ), sanitize_text_field( wp_unslash( $apply_coupon_code ) ) ) ) // phpcs:ignore
			);
			die;
		}

		$discount_type  = $coupon_instance->get_coupon_type();
		$discount_value = $coupon_instance->get_coupon_value();

		$cart_total = $wte_cart->get_subtotal();

		$discounted_total = 0;
		if ( 'fixed' === $discount_type ) {
			if ( $discount_value > $cart_total ) {
				\wp_send_json_error(
					new \WP_Error( 'WTE_COUPON_AMOUNT_EXCEED', sprintf( __( 'Coupon "%1$s" cannot be applied for this trip.', 'wp-travel-engine' ), sanitize_text_field( wp_unslash( $apply_coupon_code ) ) ) ) // phpcs:ignore
				);
				die;
			}
			$discounted_total = $cart_total - $discount_value;
		}

		if ( 'percentage' === $discount_type ) {
			$discounted_total = round( $cart_total * ( 100 - $discount_value ) / 100, 2 );
		}

		$wte_cart->add_discount_values( 'coupon', wte_clean( wp_unslash( $apply_coupon_code ) ), $discount_type, $discount_value ); // phpcs:ignore

		if ( wp_travel_engine_is_trip_partially_payable( $trip_id ) ) {
			$new_dicounted_cost = $discounted_total - $wte_cart->get_total_partial();
		} else {
			$new_dicounted_cost = $discounted_total;
		}

		/**
		 * Store form data in session
		 *
		 * @since 6.5.5
		 */
		if ( $this->request->get_param( 'formData' ) ) {
			$form_data = stripslashes( $this->request->get_param( 'formData' ) );

			$form_data = json_decode( $form_data, true );

			WTE()->session->set( 'billing_form_data', $form_data['billing'] ?? array() );
			WTE()->session->set( 'travellers_form_data', $form_data['travellers'] ?? array() );
			WTE()->session->set( 'emergency_form_data', $form_data['emergency'] ?? array() );
			WTE()->session->set( 'additional_note', $this->request->get_param( 'wptravelengine_additional_note' ) );
		}

		wp_send_json_success(
			array(
				'dis_type'            => $discount_type,
				'new_discounted_cost' => round( $new_dicounted_cost, 2 ),
				'new_cost'            => $discounted_total,
				'coupon_code'         => wte_clean( wp_unslash( $apply_coupon_code ) ),
				// phpcs:ignore
				'discount_percent'    => ( 'percentage' === $discount_type ) ? $discount_value : 0,
				'discount_amt'        => ( 'percentage' === $discount_type ) ? $cart_total * + $discount_value / 100 : $discount_value,
				'unit'                => ( 'percentage' === $discount_type ) ? '%' : \wte_currency_code(),
				'type'                => 'success',
				'message'             => sprintf( __( 'Coupon "%1$s" applied successfully.', 'wp-travel-engine' ), wte_clean( wp_unslash( $apply_coupon_code ) ) ),
				// phpcs:ignore
			)
		);
		die;
	}
}
