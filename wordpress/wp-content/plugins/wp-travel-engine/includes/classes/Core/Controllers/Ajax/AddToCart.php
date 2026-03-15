<?php
/**
 * Add to cart controller.
 *
 * @package WPTravelEngine/Core/Controllers
 * @since 6.0.0
 */

namespace WPTravelEngine\Core\Controllers\Ajax;

use WP_Error;
use WPTravelEngine\Abstracts\AjaxController;

/**
 * Handles add to cart ajax request.
 */
class AddToCart extends AjaxController {

	const NONCE_KEY    = '_nonce';
	const NONCE_ACTION = 'wte_add_trip_to_cart';
	const ACTION       = 'wte_add_trip_to_cart';

	/**
	 * Process Request.
	 */
	protected function process_request() {

		/**
		 * Maybe using a new cart.
		 */
		if ( $this->request->get_param( 'cart_version' ) ) { // phpcs:ignore
			$result = $this->add_to_cart();
			if ( is_wp_error( $result ) ) {
				wp_send_json_error( new WP_Error( 'ADD_TO_CART_ERROR', __( 'Invalid data structure.', 'wp-travel-engine' ) ) );
			} else {
				wp_send_json_success( $result );
			}
		}

		$post = $this->request->get_body_params();
		// phpcs:disable
		if ( ! isset( $post[ 'trip-id' ] ) || is_null( get_post( $post[ 'trip-id' ] ) ) ) {
			wp_send_json_error( new \WP_Error( 'ADD_TO_CART_ERROR', __( 'Invalid trip ID.', 'wp-travel-engine' ) ) );
			die;
		}

		global $wte_cart;

		$allow_multiple_cart_items = apply_filters( 'wp_travel_engine_allow_multiple_cart_items', false );

		if ( ! $allow_multiple_cart_items ) {
			$wte_cart->clear();
		}

		$posted_data = wte_clean( wp_unslash( $post ) );
		// phpcs:enable
		$trip_id            = $posted_data['trip-id'];
		$travelers          = $posted_data['travelers'] ?? 1;
		$travelers_cost     = $posted_data['travelers-cost'] ?? 0;
		$child_travelers    = $posted_data['child-travelers'] ?? 0;
		$child_cost         = $posted_data['child-travelers-cost'] ?? 0;
		$trip_price         = $posted_data['trip-cost'] ?? 0;
		$price_key          = '';
		$trip_price_partial = 0;

		// Additional cart params.
		$attrs['trip_date'] = $posted_data['trip-date'] ?? '';

		$attrs['trip_time']   = $posted_data['trip-time'] ?? '';
		$attrs['trip_extras'] = $posted_data['extra_service'] ?? array();

		$pax      = array();
		$pax_cost = array();

		if ( ! empty( $posted_data['pricing_options'] ) ) :

			foreach ( $posted_data['pricing_options'] as $key => $option ) :

				$pax[ $key ]      = $option['pax'];
				$pax_cost[ $key ] = $option['cost'];

			endforeach;

			// Multi-pricing flag.
			$attrs['multi_pricing_used'] = true;

		else :

			$pax = array(
				'adult' => $travelers,
				'child' => $child_travelers,
			);

			$pax_cost = array(
				'adult' => $travelers_cost,
				'child' => $child_cost,
			);

		endif;

		$attrs['pax']      = $pax;
		$attrs['pax_cost'] = $pax_cost;

		$attrs = apply_filters( 'wp_travel_engine_cart_attributes', $attrs );

		$partial_payment_data = wp_travel_engine_get_trip_partial_payment_data( $trip_id );
		if ( ! empty( $partial_payment_data ) ) :

			if ( 'amount' === $partial_payment_data['type'] ) :

				$trip_price_partial = $partial_payment_data['value'];

			elseif ( 'percentage' === $partial_payment_data['type'] ) :

				$partial            = 100 - (float) $partial_payment_data['value'];
				$trip_price_partial = ( $trip_price ) - ( $partial / 100 ) * $trip_price;

			endif;

		endif;

		// combine additional parameters to attributes insted more params.
		$attrs['trip_price']         = $trip_price;
		$attrs['trip_price_partial'] = $trip_price_partial;
		$attrs['pax']                = $pax;
		$attrs['price_key']          = $price_key;

		/**
		 * Action with data.
		 */
		do_action_deprecated(
			'wp_travel_engine_before_trip_add_to_cart',
			array(
				$trip_id,
				$trip_price,
				$trip_price_partial,
				$pax,
				$price_key,
				$attrs,
			),
			'4.3.0',
			'wte_before_add_to_cart',
			__( 'deprecated because of more params.', 'wp-travel-engine' )
		);
		do_action( 'wte_before_add_to_cart', $trip_id, $attrs );

		// Get any errors/ notices added.
		$wte_errors = WTE()->notices->get( 'error' );

		// If any errors found bail. Ftrip-cost.
		if ( $wte_errors ) :
			wp_send_json_error( $wte_errors );
		endif;

		// Add to cart.
		$wte_cart->add( $trip_id, $attrs );

		/**
		 * Action after trip added to the cart.
		 *
		 * @since 3.0.7
		 */
		do_action_deprecated(
			'wp_travel_engine_after_trip_add_to_cart',
			array(
				$trip_id,
				$trip_price,
				$trip_price_partial,
				$pax,
				$price_key,
				$attrs,
			),
			'4.3.0',
			'wte_after_add_to_cart',
			__( 'deprecated because of more params.', 'wp-travel-engine' )
		);

		do_action( 'wte_after_add_to_cart', $trip_id, $attrs );

		// send success notification.
		wp_send_json_success(
			array(
				'message' => __( 'Trip added to cart successfully', 'wp-travel-engine' ),
			)
		);

		die;
	}

	/**
	 * Add to cart.
	 *
	 * @return WP_Error|array
	 * @since 5.0.0
	 */
	public function add_to_cart() {

		$cart_data = $this->request->get_json_params();

		if ( is_null( $cart_data ) ) {
			return new WP_Error( 'ADD_TO_CART_ERROR', __( 'Invalid data structure.', 'wp-travel-engine' ) );
		}

		$cart_data = (object) $cart_data;

		global $wte_cart;

		if ( empty( $cart_data->booking_id ) && empty( $cart_data->{'tripID'} ) ) {
			return new WP_Error( 'ADD_TO_CART_ERROR', __( 'Invalid Trip ID.', 'wp-travel-engine' ) );
		}

		if ( ! apply_filters( 'wp_travel_engine_allow_multiple_cart_items', false ) ) {
			$wte_cart->clear();
		}

		$wte_cart->add( $this->request );

		do_action( 'wptravelengine_after_add_to_cart', $wte_cart );

		return array(
			'code'     => 'ADD_TO_CART_SUCCESS',
			'message'  => __( 'Trip added to cart successfully.', 'wp-travel-engine' ),
			'items'    => $wte_cart->getItems(),
			'redirect' => add_query_arg( 'wte_id', time(), wptravelengine_get_checkout_url() ),
		);
	}
}
