<?php
/**
 * Admin Featured Trip Controller.
 *
 * @package WPTravelEngine/Core/Controllers
 * @since 6.0.0
 */

namespace WPTravelEngine\Core\Controllers\Ajax;

use WPTravelEngine\Abstracts\AjaxController;
use WPTravelEngine\Core\Models\Post\Trip;

/**
 * Handles admin featured trip ajax request.
 */
class FeaturedTrip extends AjaxController {

	const NONCE_KEY    = 'nonce';
	const NONCE_ACTION = 'wp_travel_engine_featured_trip';
	const ACTION       = 'wp_travel_engine_featured_trip';

	/**
	 * Process Request.
	 * Ajax for adding featured trip meta
	 * */
	protected function process_request() {
		$post_id         = intval( $this->request->get_param( 'post_id' ) );
		$featured_status = esc_attr( ( new Trip( $post_id ) )->get_meta( 'wp_travel_engine_featured_trip' ) );
		$new_status      = 'yes' === $featured_status ? 'no' : 'yes';
		update_post_meta( $post_id, 'wp_travel_engine_featured_trip', $new_status );
		echo wp_json_encode(
			array(
				'ID'         => $post_id,
				'new_status' => $new_status,
			)
		);
		die();
	}
}
