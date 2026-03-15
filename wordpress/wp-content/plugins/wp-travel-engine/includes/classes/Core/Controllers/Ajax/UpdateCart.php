<?php
/**
 * Update Trip Cart Controller.
 *
 * @package WPTravelEngine/Core/Controllers
 * @since 6.0.0
 */

namespace WPTravelEngine\Core\Controllers\Ajax;

use WPTravelEngine\Abstracts\AjaxController;

/**
 * Handles update trip cart through ajax requests.
 */
class UpdateCart extends AjaxController {

	const NONCE_KEY    = 'nonce';
	const NONCE_ACTION = 'update_cart_action_nonce';
	const ACTION       = 'wte_update_cart';

	/**
	 * Process Request.
	 * Callback function for update to cart ajax.
	 *
	 * @since    1.0.0
	 * TODO: Implement the logic properly to update the cart if required else remove this controller.
	 */
	public function process_request() {
		return wp_send_json_error( array( 'message' => 'Cart update is not implemented yet' ) );
	}
}
