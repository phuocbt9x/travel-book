<?php
/**
 * Admin Featured Term Controller.
 *
 * @package WPTravelEngine/Core/Controllers
 * @since 6.0.0
 */

namespace WPTravelEngine\Core\Controllers\Ajax;

use WPTravelEngine\Abstracts\AjaxController;

/**
 * Handles admin featured term ajax request.
 */
class FeaturedTerm extends AjaxController {

	const NONCE_KEY    = 'nonce';
	const NONCE_ACTION = 'wp_travel_engine_featured_term';
	const ACTION       = 'wp_travel_engine_featured_term';

	/**
	 * Process Request.
	 * */
	protected function process_request() {
		$post_id         = intval( $this->request->get_param( 'post_id' ) );
		$featured_status = esc_attr( get_term_meta( $post_id, 'wte_trip_tax_featured', true ) );
		$new_status      = 'yes' === $featured_status ? 'no' : 'yes';
		update_term_meta( $post_id, 'wte_trip_tax_featured', $new_status );
		echo wp_json_encode(
			array(
				'ID'         => $post_id,
				'new_status' => $new_status,
			)
		);
		die();
	}
}
