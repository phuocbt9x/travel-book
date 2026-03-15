<?php
/**
 * Public Button Action AJAX Controller.
 *
 * @package WPTravelEngine/Core/Controllers/Ajax
 * @since 6.7.6
 */

namespace WPTravelEngine\Core\Controllers\Ajax;

use WPTravelEngine\Abstracts\AjaxController;

/**
 * AJAX controller for public button field actions.
 * Allows non-logged-in users to access.
 */
class PublicButtonAction extends AjaxController {

	/**
	 * Nonce key for AJAX request.
	 *
	 * @var string
	 */
	const NONCE_KEY = '_nonce';

	/**
	 * Nonce action for verification.
	 *
	 * @var string
	 */
	const NONCE_ACTION = 'wte_public_button_action_nonce';

	/**
	 * AJAX action name.
	 *
	 * @var string
	 */
	const ACTION = 'wte_public_button_action';

	/**
	 * Allow non-privileged users to access this action.
	 * Set to true for public actions.
	 *
	 * @var bool
	 */
	const ALLOW_NOPRIV = true;

	/**
	 * Process the AJAX request.
	 * This method is called after nonce verification.
	 *
	 * Add-ons should hook into the action hooks to handle button logic.
	 *
	 * @return void
	 */
	protected function process_request() {
		do_action( 'wptravelengine_public_button_action', $this->request );
	}
}
