<?php
/**
 * Filter Trip Html Controller.
 *
 * @package WPTravelEngine\Core\Controllers
 * @since 6.0.0
 */

namespace WPTravelEngine\Core\Controllers\Ajax;

use WPTravelEngine\Abstracts\AjaxController;
use WPTravelEngine\Modules\TripSearch;

/**
 * Filters the trips html.
 *
 * @since 6.0.0
 */
class FilterTripsHtml extends AjaxController {

	/**
	 * @inheritDoc
	 */
	const NONCE_KEY = 'nonce';

	/**
	 * @inheritDoc
	 */
	const NONCE_ACTION = 'wte_show_ajax_result';

	/**
	 * @inheritDoc
	 */
	const ACTION = 'wte_show_ajax_result';

	/**
	 * Post data from the request.
	 *
	 * @var array
	 */
	protected static array $post_data;

	/**
	 * WordPress query object.
	 *
	 * @var \WP_Query
	 */
	protected \WP_Query $query;

	/**
	 * Process Request: Filters Trips HTML.
	 *
	 * @return void
	 * @updated 6.6.0
	 */
	protected function process_request() {
		self::$post_data = $this->request->get_params();
		$this->query     = \Wp_Travel_Engine_Archive_Hooks::$query = new \WP_Query( TripSearch::get_query_args( true ) );

		if ( ! $this->query->have_posts() ) {
			return wp_send_json_success(
				array(
					'foundposts' => apply_filters( 'no_result_found_message', __( 'No results found!', 'wp-travel-engine' ) ),
					'data'       => '',
				)
			);
		}

		$is_load_more   = wptravelengine_toggled( self::$post_data['is_load_more'] ?? false );
		$posts_per_page = get_option( 'posts_per_page', 10 );
		$view_mode      = wp_travel_engine_get_archive_view_mode();
		$has_more_posts = $this->query->found_posts > $posts_per_page;
		$_show_more_    = get_option( 'wptravelengine_archive_display_mode', 'pagination' ) === 'load_more';
		$show_load_more = $_show_more_ && $has_more_posts;

		ob_start();

		if ( $is_load_more ) {
			$this->render_posts( $view_mode );
		} else {
			$show_sidebar = wptravelengine_toggled( get_option( 'wptravelengine_show_trip_search_sidebar', 'yes' ) );
			echo '<div class="category-main-wrap ' . esc_attr( $view_mode === 'grid' ? ( $show_sidebar ? 'col-2 category-grid' : 'col-3 category-grid' ) : 'category-list' ) . '">';
			$this->render_posts( $view_mode );
			echo '</div>';
			if ( $show_load_more ) {
				echo '<div data-id="' . esc_attr( $this->query->found_posts ) . '" class="wte-search-load-more"><button data-current-page="' . esc_attr( get_query_var( 'paged' ) ?: 1 ) . '" data-max-page="' . esc_attr( $this->query->max_num_pages ) . '" class="load-more-search">' . esc_html__( 'Load More', 'wp-travel-engine' ) . '</button></div>';
			}
		}

		$foundposts = sprintf(
			_nx( '%1$s Trip Found', '%1$s Trips Found', $this->query->found_posts, 'number of trips', 'wp-travel-engine' ),
			'<strong>' . number_format_i18n( $this->query->found_posts ) . '</strong>'
		);

		return wp_send_json_success(
			array(
				'foundposts'   => $foundposts,
				'data'         => ob_get_clean(),
				'max_page'     => $this->query->max_num_pages,
				'current_page' => self::$post_data['paged'] ?? 1,
				'pagination'   => ( ! $_show_more_ && $has_more_posts ) ? $this->get_pagination() : '',
			)
		);
	}

	/**
	 * Render Posts HTML.
	 *
	 * @param string $view_mode The view mode (grid/list).
	 *
	 * @return void
	 * @since 6.6.0
	 */
	private function render_posts( $view_mode ): void {
		$user_wishlists = wptravelengine_user_wishlists();
		$template_name  = wptravelengine_get_template_by_view_mode( $view_mode );

		while ( $this->query->have_posts() ) :
			$this->query->the_post();
			$details                   = wte_get_trip_details( get_the_ID() );
			$details['user_wishlists'] = $user_wishlists;

			wptravelengine_get_template( $template_name, $details );
		endwhile;

		wp_reset_postdata();
	}

	/**
	 * Get my pagination.
	 *
	 * @return void
	 * @since 6.6.0
	 */
	private function get_pagination(): string {
		global $wp_query;
		$original_query = $wp_query;
		$wp_query       = $this->query;
		$pagination     = get_the_posts_pagination(
			array(
				'prev_text'          => esc_html__( 'Previous', 'wp-travel-engine' ),
				'next_text'          => esc_html__( 'Next', 'wp-travel-engine' ),
				'before_page_number' => '<span class="meta-nav screen-reader-text">' . esc_html__( 'Page', 'wp-travel-engine' ) . ' </span>',
			)
		);
		$wp_query       = $original_query;

		return $pagination;
	}
}
