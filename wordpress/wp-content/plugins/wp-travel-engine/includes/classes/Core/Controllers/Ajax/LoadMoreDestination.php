<?php
/**
 * Load More Destination Controller.
 *
 * @package WPTravelEngine/Core/Controllers
 * @since 6.0.0
 */

namespace WPTravelEngine\Core\Controllers\Ajax;

use WPTravelEngine\Abstracts\AjaxController;

/**
 * Handles load more destination ajax requests.
 */
class LoadMoreDestination extends AjaxController {

	const NONCE_KEY    = 'nonce';
	const NONCE_ACTION = 'wpte_ajax_load_more_destination';
	const ACTION       = 'wpte_ajax_load_more_destination';

	/**
	 * Process Request.
	 * AJAX Load More Destination
	 */
	public function process_request() {
		$post = $this->request->get_params();

		// prepare our arguments for the query
		// phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotValidated
		$args = json_decode( wte_clean( wp_unslash( $post['query'] ) ), true );
		// phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotValidated

		$post_page           = intval( $post['page'] );
		$args['paged']       = ( 0 === $post_page ) ? 2 : ( $post_page + 1 );
		$args['post_status'] = 'publish';

		$query = new \WP_Query( $args );

		ob_start();

		while ( $query->have_posts() ) :
			$query->the_post();
			$details = wte_get_trip_details( get_the_ID() );
			wptravelengine_get_template( 'content-grid.php', $details );
		endwhile;

		wp_reset_postdata();

		return wp_send_json_success(
			array(
				'data'          => ob_get_clean(),
				'current_page'  => $args['paged'],
				'remove_button' => $query->max_num_pages <= $args['paged'],
			)
		);
	}
}
