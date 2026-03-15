<?php
/**
 * Load More Controller.
 *
 * @package WPTravelEngine/Core/Controllers
 * @since 6.0.0
 */

namespace WPTravelEngine\Core\Controllers\Ajax;

use WPTravelEngine\Abstracts\AjaxController;

/**
 * Handles load more ajax requests.
 */
class LoadMore extends AjaxController {

	const NONCE_KEY    = 'nonce';
	const NONCE_ACTION = 'wpte-be-load-more-nonce';
	const ACTION       = 'wpte_ajax_load_more';

	/**
	 * Process Request.
	 * AJAX Load More.
	 */
	public function process_request() {
		// Prepare our arguments for the query.
		$args                = wte_clean( json_decode( wp_unslash( $this->request->get_param( 'query' ) ), true ) );
		$args                = wte_clean( $args );
		$args['paged']       = wte_clean( wp_unslash( $this->request->get_param( 'page' ) ) ) + 1; // We need next page to be loaded.
		$args['post_status'] = 'publish';

		$query = new \WP_Query( $args );
		ob_start();

		while ( $query->have_posts() ) :
			$query->the_post();
			$details = wte_get_trip_details( get_the_ID() );
			wptravelengine_get_template( 'content-grid.php', $details );
		endwhile;
		wp_reset_postdata();

		$output = ob_get_contents();
		ob_end_clean();
		echo $output; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		wp_reset_query();
		exit();
	}
}
