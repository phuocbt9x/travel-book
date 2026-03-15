<?php
/**
 * Admin notice controller.
 *
 * @package WPTravelEngine/Core/Controllers
 * @since 6.5.5
 */

namespace WPTravelEngine\Core\Controllers\Ajax;

use WP_Error;
use WPTravelEngine\Abstracts\AjaxController;

/**
 * Handles admin notice ajax request.
 */
class AdminNotice extends AjaxController {

	const NONCE_KEY    = 'nonce';
	const NONCE_ACTION = '_wptravelengine_notice_dismiss';
	const ACTION       = 'wptravelengine_notice_dismiss';
	const ALLOW_NOPRIV = false;

	/**
	 * Process Request.
	 *
	 * @since 6.7.2 Made compatible for local notice too.
	 */
	protected function process_request() {
		$last_updated = $this->request->get_param( 'last_updated' );
		if ( $last_updated ) {
			$type = $this->request->get_param( 'type' );
			if ( 'server' === $type ) {
				update_option( 'wptravelengine_notice_dismissed_at', $last_updated );
			} else {
				update_option( 'wptravelengine_local_notice_dismissed_at', $last_updated );
			}
		}
	}
}
