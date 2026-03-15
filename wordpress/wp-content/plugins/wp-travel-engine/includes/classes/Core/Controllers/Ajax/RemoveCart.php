<?php
/**
 * Remove Trip Cart Controller.
 *
 * @package WPTravelEngine/Core/Controllers
 * @since 6.0.0
 */

namespace WPTravelEngine\Core\Controllers\Ajax;

use WPTravelEngine\Abstracts\AjaxController;

/**
 * Handles remove trip cart through ajax requests.
 */
class RemoveCart extends AjaxController {

	const NONCE_KEY    = 'nonce';
	const NONCE_ACTION = 'wte-remove-nonce';
	const ACTION       = 'wte_remove_order';

	/**
	 * Process Request.
	 * Callback function for update to cart ajax.
	 *
	 * @since    1.0.0
	 * TODO: Implement the logic properly to remove the cart if required else remove this controller.
	 */
	public function process_request() {
		return wp_send_json_error( array( 'message' => 'Cart remove is not implemented yet' ) );
	}
}
