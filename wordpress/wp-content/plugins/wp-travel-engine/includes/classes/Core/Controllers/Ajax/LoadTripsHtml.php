<?php
/**
 * Load Trip Html Controller.
 *
 * @package @WPTravelEngine/Core/Controllers
 * @since 6.0.0
 */

namespace WPTravelEngine\Core\Controllers\Ajax;

use WPTravelEngine\Abstracts\AjaxController;
use WPTravelEngine\Core\Models\Settings\Options;

/**
 * Loads the trips html.
 */
class LoadTripsHtml extends AjaxController {

	const NONCE_KEY    = 'nonce';
	const NONCE_ACTION = 'wte_show_ajax_result_load';
	const ACTION       = 'wte_show_ajax_result_load';

	/**
	 * Process Request.
	 * Load Trips Html.
	 */
	protected function process_request() {
		$post           = $this->request->get_params();
		$posts_per_page = ( new Options() )->get( 'posts_per_page', 10 );

		// phpcs:disable
		$args                = json_decode( wp_unslash( $post['query'] ), true );
		$args['paged']       = wte_clean( wp_unslash( $post['page'] ) ) + 1; // we need next page to be loaded
		$args['post_status'] = 'publish';

		$query = new \WP_Query( $args );
		ob_start();

		$user_wishlists = wptravelengine_user_wishlists();
		$template_name	= wptravelengine_get_template_by_view_mode( $view_mode );

		// phpcs:enable
		while ( $query->have_posts() ) :
			$query->the_post();
			$details                   = \wte_get_trip_details( get_the_ID() );
			$details['user_wishlists'] = $user_wishlists;
			wptravelengine_get_template( $template_name, $details );
		endwhile;

		wp_reset_postdata();

		wp_send_json_success(
			array(
				'data' => ob_get_clean(),
			)
		);
		exit();
	}
}
