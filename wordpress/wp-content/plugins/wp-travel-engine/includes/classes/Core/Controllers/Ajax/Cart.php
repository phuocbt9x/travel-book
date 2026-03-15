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
use WPTravelEngine\Core\Controllers\Checkout;

/**
 * Handles cart related requests.
 */
class Cart extends AjaxController {

	const NONCE_KEY    = '_nonce';
	const NONCE_ACTION = 'wp_xhr';
	const ACTION       = 'wptravelengine_cart';

	/**
	 * Process Request.
	 */
	protected function process_request() {

		$cart_action = $this->request->get_param( 'cart_action' );

		switch ( $cart_action ) {
			case 'update_payment_type':
				$this->change_payment_type();
				break;
			default:
				wp_send_json_error( new WP_Error( 'INVALID_CART_ACTION', __( 'Invalid cart action.', 'wp-travel-engine' ) ) );
		}
	}

	/**
	 * Change a payment type.
	 */
	protected function change_payment_type() {
		global $wte_cart;

		$payment_type    = $this->request->get_param( 'data' )['payment_type'] ?? 'full_payment';
		$payment_gateway = $this->request->get_param( 'data' )['payment_gateway'] ?? 'booking_only';

		wptravelengine_update_cart( compact( 'payment_type', 'payment_gateway' ) );

		$checkout = new Checkout( $wte_cart );
		ob_start();
		$checkout->template_mini_cart();
		$mini_cart_contents = ob_get_clean();
		wp_send_json_success(
			array(
				'message'   => __( 'Payment type updated successfully.', 'wp-travel-engine' ),
				'fragments' => array(
					'.wpte-bf-book-summary' => $mini_cart_contents,
				),
			)
		);
	}
}
