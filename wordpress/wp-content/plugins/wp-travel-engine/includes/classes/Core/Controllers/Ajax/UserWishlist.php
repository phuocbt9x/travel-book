<?php
/**
 * User Wishlist Controller.
 *
 * @package WPTravelEngine/Core/Controllers
 * @since 6.0.0
 */

namespace WPTravelEngine\Core\Controllers\Ajax;

use WPTravelEngine\Abstracts\AjaxController;

/**
 * Handles the user wishlist ajax request.
 */
class UserWishlist extends AjaxController {

	const NONCE_KEY    = '_nonce';
	const NONCE_ACTION = 'wp_xhr';
	const ACTION       = 'wte_user_wishlist';

	/**
	 * Process Request.
	 * Update user wishlist.
	 *
	 * @return void
	 */
	public function process_request(): void {
		$request        = $this->request->get_params();
		$request_method = sanitize_text_field( wp_unslash( $_SERVER['REQUEST_METHOD'] ?? 'GET' ) );
		$user_wishlists = array_values( (array) wptravelengine_user_wishlists() );
		$message        = __( 'Wishlist fetched successfully.', 'wp-travel-engine' );

		$args = array(
			'post_type'   => WP_TRAVEL_ENGINE_POST_TYPE,
			'post_status' => 'publish',
			'post__in'    => $user_wishlists,
			'orderby'     => 'post__in',
			'paged'       => intval( $request['paged'] ?? 1 ),
		);

		if ( 'GET' === $request_method ) {
			list( $markup, $pagination, $max_page ) = $this->get_wishlist_markup( $args );
			wp_send_json_success( compact( 'message', 'markup', 'pagination', 'max_page' ) );
			wp_die();
		}

		if ( 'POST' === $request_method ) {
			$user_wishlists[] = (int) $request['wishlist'];
			$message          = __( 'Trip is added to wishlists.', 'wp-travel-engine' );
		} elseif ( 'DELETE' === $request_method ) {
			if ( 'all' === $request['wishlist'] ) {
				$user_wishlists = array();
			} else {
				$user_wishlists = array_diff( $user_wishlists, explode( ',', $request['wishlist'] ) );
			}
			$message = __( 'Trip is removed from wishlists.', 'wp-travel-engine' );
		}

		if ( is_user_logged_in() ) {
			$user_id = get_current_user_id();
			update_user_meta( $user_id, 'wptravelengine_wishlists', $user_wishlists );
		} else {
			WTE()->session->set( 'user_wishlists', $user_wishlists );
		}

		$args['post__in']                       = $user_wishlists;
		list( $markup, $pagination, $max_page ) = $this->get_wishlist_markup( $args );

		wp_send_json_success(
			array(
				'message'        => $message,
				'markup'         => $markup,
				'max_page'       => $max_page,
				'pagination'     => $pagination,
				'user_wishlists' => $user_wishlists,
				'refresh'        => 'all' === $request['wishlist'] || empty( $user_wishlists ),
				'partials'       => array(
					/* Translators: %d is the number of items in the wishlist. */
					'[data-wptravelengine-wishlist-count]' => ! empty( $user_wishlists ) ? sprintf( _n( '<strong>%d</strong> item in the wishlist', '<strong>%d</strong> items in the wishlist', count( $user_wishlists ), 'wp-travel-engine' ), count( $user_wishlists ) ) : '',
				),
			)
		);
		wp_die();
	}

	/**
	 * Get the user wishlist markup.
	 *
	 * @param array $args The query arguments.
	 *
	 * @return array
	 */
	private function get_wishlist_markup( $args ): array {
		$query = new \WP_Query( $args );

		ob_start();
		while ( $query->have_posts() ) :
			$query->the_post();
			$details                   = wte_get_trip_details( get_the_ID() );
			$details['user_wishlists'] = $args['post__in'];
			wptravelengine_get_template( 'content-grid.php', $details );
		endwhile;
		$markup = ob_get_clean();

		$original_query      = $GLOBALS['wp_query'];
		$GLOBALS['wp_query'] = $query;

		$pagination = '';
		$max_page   = $query->max_num_pages;
		if ( $max_page > 1 ) {
			ob_start();
			the_posts_pagination(
				array(
					'prev_text'          => esc_html__( 'Previous', 'wp-travel-engine' ),
					'next_text'          => esc_html__( 'Next', 'wp-travel-engine' ),
					'before_page_number' => '<span class="meta-nav screen-reader-text">' . esc_html__( 'Page', 'wp-travel-engine' ) . ' </span>',
				)
			);
			$pagination = ob_get_clean();
		}

		$GLOBALS['wp_query'] = $original_query;

		wp_reset_postdata();

		return array( $markup, $pagination, $max_page );
	}
}
