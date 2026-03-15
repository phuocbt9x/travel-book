<?php
/**
 * ResetCoupon Code Controller.
 *
 * @package @WPTravelEngine/Core/Controllers
 * @since 6.0.0
 */

namespace WPTravelEngine\Core\Controllers\Ajax;

use WPTravelEngine\Abstracts\AjaxController;

/**
 * Handles reset coupon ajax request.
 */
class ResetCoupon extends AjaxController {

	const NONCE_KEY    = '_nonce';
	const NONCE_ACTION = 'wte_session_cart_reset_coupon';
	const ACTION       = 'wte_session_cart_reset_coupon';

	/**
	 * Process Request.
	 */
	protected function process_request() {
		global $wte_cart;
		$wte_cart->discount_clear();

		wp_send_json_success(
			array(
				'default_cost' => wptravelengine_the_price( $wte_cart->get_subtotal(), false, false ),
				'message'      => __(
					'Applied Coupons reset successfully.',
					'wp-travel-engine'
				),
			)
		);
	}
}
