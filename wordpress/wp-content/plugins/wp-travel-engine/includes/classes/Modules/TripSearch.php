<?php

namespace WPTravelEngine\Modules;

use WPTravelEngine\Assets;

/**
 * Trip Search Module Class
 *
 * Handles trip search functionality including filters, search forms, and results display.
 * Provides methods for price range filtering, duration filtering, and taxonomy-based filtering.
 *
 * @since __addonmigration__
 * @package WPTravelEngine\Modules
 */
class TripSearch {

	/**
	 * Constructor for the TripSearch class.
	 * Initializes the module by including required files and setting up hooks.
	 */
	public function __construct() {
		$this->includes();
		$this->init_hooks();
	}

	/**
	 * Includes required dependency files for the search module.
	 *
	 * @access private
	 */
	private function includes() {
		require_once __DIR__ . '/trip-search/backward-compatibility.php';
	}

	/**
	 * Initializes WordPress hooks for the search functionality.
	 * Sets up actions and filters for both admin and public interfaces.
	 *
	 * @access private
	 */
	private function init_hooks() {

		add_action(
			'wp',
			function () {
				if ( is_post_type_archive( WP_TRAVEL_ENGINE_POST_TYPE ) ) {
					self::enqueue_assets();
					return;
				}

				global $post;

				if ( ! $post instanceof \WP_Post || ! is_singular() ) {
					return;
				}

				if ( has_shortcode( $post->post_content, 'Wte_Advanced_Search_Form' ) ) {
					self::enqueue_trip_search_scripts();
				}

				if (
				has_shortcode( $post->post_content, 'WTE_Trip_Search' ) ||
				has_block( 'wptravelenginepagesblocks/archive-trip', $post ) ||
				has_block( 'wptravelenginepagesblocks/trip-search', $post ) ||
				has_block( 'wptravelengine/trip-search', $post )
				) {
					self::enqueue_styles();
					self::enqueue_trip_search_scripts();
				}
			}
		);

		add_action( 'init', array( $this, 'init' ) );

		/**
		 * Admin Hooks.
		 */
		// add_action( 'wte_advanced_search_page', array( __CLASS__, 'search_page' ) );
		// add_action( 'wp_travel_engine_search_fields', array( __CLASS__, 'search_fields' ) );
		add_action( 'wpte_get_global_extensions_tab', array( self::class, 'settings' ) );

		/**
		 * Add settings to choose Search Page.
		 * Settings>General>Page Settings
		 */
		add_filter( 'wpte_global_page_options', array( self::class, 'choose_page' ) );

		/**
		 * Public hooks
		 */
		add_filter(
			'body_class',
			function ( $classes ) {
				if ( self::is_search_page() ) {
					$classes[] = 'trip-search-result';
				}

				return $classes;
			}
		);

		add_action( 'template_redirect', array( self::class, 'template_redirect' ) );

		add_action( 'wp_travel_engine_archive_sidebar', array( self::class, 'archive_filter_sidebar' ) );

		// Search Filter forms callback.
		add_filter(
			'wptravelengine_search_filter_price',
			function () {
				return array( self::class, 'search_filter_price' );
			}
		);

		add_filter(
			'wptravelengine_search_filter_duration',
			function () {
				return array( self::class, 'search_filter_duration' );
			}
		);

		// Loading Elementor Widgets Trip Search Assets.
		add_filter(
			'wte_register_block_types',
			function ( $val, $args ) {
				self::enqueue_trip_search_scripts();
				return $val;
			},
			10,
			2
		);
	}

	/**
	 * Enqueue trip search widgets dropdown scripts.
	 */
	public static function enqueue_trip_search_scripts() {
		Assets::instance()->enqueue_script( 'wptravelengine-trip-search-widgets-dropdown' )->enqueue_script( 'wptravelengine-trip-search-widgets-slider' );
	}

	/**
	 * Renders the duration filter in the search interface.
	 *
	 * @param array $args Filter arguments including labels and settings
	 * @static
	 */
	public static function search_filter_duration( $args ) {
		// wp_enqueue_script( 'jquery-ui-slider' );
		wp_enqueue_script( 'wptravelengine-trip-search-widgets-slider' );
		$range = (array) self::get_duration_range( true );
		$id    = wte_uniqid();
		?>
		<div data-value-format="duration" class="wpte-trip__adv-field wpte__select-field"
			data-range-slider="#<?php echo esc_attr( $id ); ?>"
			data-range="<?php echo esc_attr( implode( ',', $range ) ); ?>" data-min="<?php echo esc_attr( $range['min_value'] ); ?>" data-max="<?php echo esc_attr( $range['max_value'] ); ?>"
			data-suffix="<?php esc_attr_e( 'Days', 'wp-travel-engine' ); ?>">
			<?php
			self::search_filter_icon( $args, 'duration' );
			?>
			<input type="text" data-value class="wpte__input" placeholder="<?php echo esc_attr( $args['label'] ); ?>" id="wte-duration-filter-input" />
			<input type="hidden" data-value-min class="wpte__input-min" name="min-duration">
			<input type="hidden" data-value-max class="wpte__input-max" name="max-duration">
			<div class="wpte__select-options">
				<div id="<?php echo esc_attr( $id ); ?>"></div>
				<div class="wpte-slider-values">
					<span data-value-min-display></span>
					<span data-value-max-display></span>
				</div>
			</div>
		</div>
		<?php
	}

	/**
	 * Renders the price filter in the search interface.
	 *
	 * @param array $args Filter arguments including labels and settings
	 * @static
	 */
	public static function search_filter_price( $args ) {
		// wp_enqueue_script( 'jquery-ui-slider' );
		// wp_enqueue_script( 'wte-nouislider' );
		// wp_enqueue_style( 'wte-nouislider' );
		wp_enqueue_script( 'wptravelengine-trip-search-widgets-slider' );
		$range = (array) self::get_price_range( true );
		$id    = wte_uniqid();
		?>
		<div class="wpte-trip__adv-field wpte__select-field" data-range-slider="#<?php echo esc_attr( $id ); ?>"
			data-range="<?php echo esc_attr( implode( ',', $range ) ); ?>" data-min="<?php echo esc_attr( $range['min_value'] ); ?>" data-max="<?php echo esc_attr( $range['max_value'] ); ?>"
			data-value-format="price"
			>
			<?php
			self::search_filter_icon( $args, 'price' );
			?>

			<input type="text" class="wpte__input" data-value placeholder="<?php echo esc_attr( $args['label'] ); ?>" id="wte-price-filter-input" />
			<input type="hidden" data-value-min class="wpte__input-min" name="min-cost" />
			<input type="hidden" data-value-max class="wpte__input-max" name="max-cost" />
			<div class="wpte__select-options">
				<div id="<?php echo esc_attr( $id ); ?>"></div>
				<div class="wpte-slider-values">
					<span data-value-min-display></span>
					<span data-value-max-display></span>
				</div>
			</div>
		</div>
		<?php
	}

	/**
	 * Get the data to be localized.
	 *
	 * @return array
	 */
	public static function get_localized_data() {

		$_req = wptravelengine_sanitize_params_recursive( $_REQUEST );

		$settings       = get_option( 'wp_travel_engine_settings', array() );
		$price_range    = self::get_price_range( true );
		$duration_range = self::get_duration_range( true );
		$post_type      = isset( $_req['post_type'] ) ? sanitize_key( $_req['post_type'] ) : '';
		$is_search_page = ( is_post_type_archive( 'trip' ) && 'trip' !== $post_type ) || is_page( 'trip-search-result' );
		$show_sidebar   = wptravelengine_toggled( get_option( 'wptravelengine_show_trip_search_sidebar', 'yes' ) );

		$selected_min_cost     = intval( $_req['mincost'] ?? $price_range['min_value'] );
		$selected_max_cost     = intval( $_req['maxcost'] ?? $price_range['max_value'] );
		$selected_min_duration = intval( $_req['mindur'] ?? $duration_range['min_value'] );
		$selected_max_duration = intval( $_req['maxdur'] ?? $duration_range['max_value'] );

		$filter_data = array();
		if ( ! $show_sidebar ) {
			$filter_data = array_merge(
				array(
					'mincost' => $selected_min_cost,
					'maxcost' => $selected_max_cost,
					'mindur'  => $selected_min_duration,
					'maxdur'  => $selected_max_duration,
				),
				self::get_sidebar_filter_data()
			);

			if ( isset( $_req['trip-chosen-date'] ) ) {
				$filter_data['date'] = $_req['trip-chosen-date'];
			}
		}

		return array(
			'ajax_url'               => admin_url( 'admin-ajax.php' ),
			'destination_nonce'      => wp_create_nonce( 'wpte_ajax_load_more_destination' ),
			'is_search'              => $is_search_page,
			'is_tax'                 => ! $is_search_page && is_tax( get_object_taxonomies( 'trip', 'names' ) ),
			'min_cost'               => (int) $price_range['min_value'],
			'max_cost'               => (int) $price_range['max_value'],
			'min_duration'           => (int) $duration_range['min_value'],
			'max_duration'           => (int) $duration_range['max_value'],
			'selected_min_cost'      => $selected_min_cost,
			'selected_max_cost'      => $selected_max_cost,
			'selected_min_duration'  => $selected_min_duration,
			'selected_max_duration'  => $selected_max_duration,
			'cur_symbol'             => wp_travel_engine_get_currency_code(),
			'days_text'              => __( 'Days', 'wp-travel-engine' ),
			'is_load_more'           => 'load_more' === get_option( 'wptravelengine_archive_display_mode', 'pagination' ),
			'default_view_mode'      => apply_filters( 'wp_travel_engine_default_archive_view_mode', get_option( 'wptravelengine_trip_view_mode', 'list' ) ),
			'default_orderby'        => get_option( 'wptravelengine_trip_sort_by', 'latest' ),
			'showFeaturedTripsOnTop' => wptravelengine_toggled( $settings['show_featured_trips_on_top'] ?? false ),
			'noOfFeaturedTrips'      => (int) ( $settings['feat_trip_num'] ?? 2 ),
			'showOptionFilter'       => wptravelengine_toggled( $settings['search_filter_option'] ?? false ),
			'showSidebar'            => $show_sidebar,
			'sidebarFilterData'      => $filter_data,
		);
	}

	/**
	 * Returns array of sidebar filter data for hidden sidebar.
	 *
	 * @access private
	 * @return array
	 * @since 6.2.2
	 */
	private static function get_sidebar_filter_data() {

		$data              = array();
		$recursive_checker = null;
		$recursive_checker = function ( $terms, $children = false ) use ( &$data, &$recursive_checker ) {
			if ( is_array( $terms ) && count( $terms ) > 0 ) {
				$queried_term = get_queried_object();

				foreach ( $terms as $term ) {
					if ( isset( $term->parent ) && $term->parent && ! $children ) {
						continue;
					}

					if ( ! isset( $term->taxonomy ) || ! isset( $term->slug ) || ! isset( $term->name ) ) {
						continue;
					}

					$get_terms        = wte_array_get( $_GET, $term->taxonomy, array() );
					$get_terms        = is_string( $get_terms ) ? explode( ',', $get_terms ) : ( $get_terms ?: array() );
					$is_queried_term  = ( $queried_term->taxonomy ?? '' ) === $term->taxonomy && ( $queried_term->slug ?? '' ) === $term->slug;
					$is_in_url_params = in_array( $term->slug, $get_terms, true );

					if ( checked( true, $is_queried_term || $is_in_url_params, false ) ) {
						$data[ 'result[' . $term->taxonomy . '][]' ] = $term->slug;
					}

					if ( is_array( $term->children ) && count( $term->children ) > 0 ) {
						$_children = array();
						foreach ( $term->children as $term_child ) {
							if ( ! isset( $terms[ $term_child ] ) ) {
								continue;
							}
							$_children[ $term_child ] = $terms[ $term_child ];
						}
						$recursive_checker( $_children, true ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					}
				}
			}
		};

		$sidebar_filters = array_merge(
			array(
				'destination',
				'activities',
				'trip_tag',
				'difficulty',
				'trip_types',
			),
			array_column( get_option( 'wte_custom_filters', array() ), 'slug' )
		);

		foreach ( $sidebar_filters as $taxonomy ) {
			$terms = \wte_get_terms_by_id( $taxonomy );
			$recursive_checker( $terms );
		}

		return $data;
	}

	/**
	 * Enqueues the required styles and scripts for the search functionality.
	 */
	public static function enqueue_assets() {
		self::enqueue_styles();
		self::enqueue_scripts();
	}

	/**
	 * Enqueues required scripts for the search functionality.
	 * Registers and localizes the trip search scripts with necessary data.
	 */
	public static function enqueue_scripts() {
		Assets::instance()->enqueue_script( 'wte-trip-search' );
	}

	/**
	 * Enqueues required styles for the search functionality.
	 */
	public static function enqueue_styles() {
		Assets::instance()->enqueue_style( 'wpte-trip-archive' );
	}

	/**
	 * Loads and returns HTML for trip search results via AJAX.
	 * Handles pagination and view mode (grid/list) for search results.
	 *
	 * @static
	 */
	public static function load_trips_html() {
		// phpcs:disable
		$args                  = json_decode( wp_unslash( $_POST[ 'query' ] ), true );
		$args[ 'paged' ]       = wte_clean( wp_unslash( $_POST[ 'page' ] ) ) + 1; // we need next page to be loaded
		$args[ 'post_status' ] = 'publish';

		$query = new \WP_Query( $args );
		ob_start();

		$view_mode  = wte_clean( wp_unslash( $_POST[ 'mode' ] ) );
		$show_sidebar = wptravelengine_toggled( get_option( 'wptravelengine_show_trip_search_sidebar', 'yes' ) );
		$view_class = 'grid' === $view_mode ? ( $show_sidebar ? 'col-2 category-grid' : 'col-3 category-grid' ) : 'category-list';

		$user_wishlists = wptravelengine_user_wishlists();
		$template_name	= wptravelengine_get_template_by_view_mode( $view_mode );

		// phpcs:enable
		while ( $query->have_posts() ) :
			$query->the_post();
			$details                   = \wte_get_trip_details( get_the_ID() );
			$details['user_wishlists'] = $user_wishlists;
			wptravelengine_get_template( $template_name, $details );
		endwhile;
		\wp_reset_postdata();

		$html = ob_get_clean();
		wp_send_json_success(
			array(
				'data' => $html,
			)
		);
		exit();
	}

	/**
	 * Filters trips based on search criteria and returns filtered HTML results.
	 *
	 * @param array $post_data Posted search filter data
	 * @static
	 * @return string|void JSON response with filtered trips HTML
	 */
	public static function filter_trips_html( $post_data ) {

		$view_mode = ! empty( $post_data[ 'mode' ] ) ? wte_clean( wp_unslash( $post_data[ 'mode' ] ) ) : wp_travel_engine_get_archive_view_mode(); // phpcs:ignore

		if ( ! in_array( $view_mode, array( 'grid', 'list' ) ) ) {
			return '<h1>' . sprintf( __( 'Layout not found: %s', 'wp-travel-engine' ), $view_mode ) . '</h1>';
		}

		$query_args = self::get_query_args( true );

		$query = new \WP_query( $query_args );

		if ( ! $query->have_posts() ) {
			return wp_send_json_success(
				array(
					'foundposts' => apply_filters( 'no_result_found_message', __( 'No results found!', 'wp-travel-engine' ) ),
					'data'       => '',
				)
			);
		}

		$show_sidebar = wptravelengine_toggled( get_option( 'wptravelengine_show_trip_search_sidebar', 'yes' ) );
		$view_class   = 'grid' === $view_mode ? ( $show_sidebar ? 'wte-col-2 category-grid' : 'wte-col-3 category-grid' ) : 'category-list';

		ob_start();

		echo '<div class="category-main-wrap ' . esc_attr( $view_class ) . '">';

		$user_wishlists = wptravelengine_user_wishlists();
		$template_name  = wptravelengine_get_template_by_view_mode( $view_mode );

		while ( $query->have_posts() ) :
			$query->the_post();
			$details                   = wte_get_trip_details( get_the_ID() );
			$details['user_wishlists'] = $user_wishlists;

			wptravelengine_get_template( $template_name, $details );
		endwhile;
		wp_reset_postdata();
		echo '</div>';

		$default_posts_per_page = get_option( 'posts_per_page', 10 );
		$paged                  = ( get_query_var( 'paged' ) ) ? absint( get_query_var( 'paged' ) ) : 1;
		if ( $query->found_posts > $default_posts_per_page ) {
			echo "<span data-id='" . esc_attr( $query->found_posts ) . "' class='wte-search-load-more'><a data-query-vars='" . wp_json_encode( $query->query_vars ) . "' data-current-page='" . esc_attr( $paged ) . "' data-max-page='" . esc_attr( $query->max_num_pages ) . "' href='#' class='load-more-search' data-nonce='" . wp_create_nonce( 'wte_show_ajax_result_load' ) . "'>" . __( 'Load More', 'wp-travel-engine' ) . '</a></span>';
		}

		$foundposts = sprintf( _nx( '%s Trip Found', '%s Trips found', $query->found_posts, 'number of trips', 'wp-travel-engine' ), '<strong>' . number_format_i18n( $query->found_posts ) . '</strong>' );

		return wp_send_json_success(
			array(
				'foundposts' => $foundposts,
				'data'       => ob_get_clean(),
			)
		);
		exit;
	}

	/**
	 * Gets query arguments for trip filtering based on search parameters.
	 *
	 * @param boolean $ajax_request Whether the request is an AJAX request
	 *
	 * @static
	 * @return array Query arguments for WP_Query
	 * @updated 6.6.0
	 */
	public static function get_query_args( $ajax_request = false ) {

		$def_args = array();

		if ( ! $ajax_request ) {
			$def_args = array(
				'mode' => wp_travel_engine_get_archive_view_mode(),
				'sort' => \Wp_Travel_Engine_Archive_Hooks::archive_sort_by(),
			);
		}

		$post_data  = wp_parse_args( $_REQUEST, $def_args );
		$query_args = array(
			'post_type'        => WP_TRAVEL_ENGINE_POST_TYPE,
			'post_status'      => 'publish',
			'posts_per_page'   => get_option( 'posts_per_page', 10 ),
			'paged'            => absint( ! empty( $post_data['paged'] ?? '' ) ? $post_data['paged'] : ( get_query_var( 'paged' ) ?: 1 ) ),
			'wpte_trip_search' => true,
		);

		$categories = apply_filters(
			'wte_filter_categories',
			array(
				'trip_types'  => array(
					'taxonomy'         => 'trip_types',
					'field'            => 'slug',
					'include_children' => true,
				),
				'cat'         => array(
					'taxonomy'         => 'trip_types',
					'field'            => 'slug',
					'include_children' => true,
				),
				'budget'      => array(
					'taxonomy'         => 'budget',
					'field'            => 'slug',
					'include_children' => false,
				),
				'activities'  => array(
					'taxonomy'         => 'activities',
					'field'            => 'slug',
					'include_children' => true,
				),
				'destination' => array(
					'taxonomy'         => 'destination',
					'field'            => 'slug',
					'include_children' => true,
				),
				'trip_tag'    => array(
					'taxonomy' => 'trip_tag',
					'field'    => 'slug',
				),
				'difficulty'  => array(
					'taxonomy' => 'difficulty',
					'field'    => 'slug',
				),
			)
		);

		// phpcs:disable
		$tax_query = array();
		foreach ( $categories as $cat => $term_args ) {
			$category = ( $post_data['result'][ $cat ] ?? '-1' ) != '-1' ? $post_data['result'][ $cat ] : ( $post_data[$cat] ?? '' ); // phpcs:ignore
			if ( ! empty( $category ) ) {
				$term_args['terms'] = is_string( $category ) && (strpos( $category, ',' ) !== false) ? explode( ',', $category ) : $category;
				$tax_query[]        = $term_args;
			}
		}

		if ( ! empty( $tax_query ) ) {
			$query_args['tax_query'] = $tax_query; // phpcs:ignore
			$query_args['tax_query']['relation'] = 'AND';
		}

		$meta_query = array();
		// Check Price.
		$cost_range = self::get_price_range( true );
		$min_cost   = max( -1, floatval( $post_data['mincost'] ?? $post_data['min-cost'] ?? $cost_range['min_value'] ) );
		$max_cost   = max( -1, floatval( $post_data['maxcost'] ?? $post_data['max-cost'] ?? $cost_range['max_value'] ) );

		if ( $max_cost > -1 ) {
			$meta_query[] = array(
				'key'     => apply_filters( 'wpte_advance_search_price_filter', '_s_price' ),
				'value'   => array( $min_cost, $max_cost ),
				'compare' => 'BETWEEN',
				'type'    => 'numeric',
			);
		}

		// Check Duration.
		$range        = self::get_duration_range( true );
		$min_duration = max( -1, floatval( $post_data['mindur'] ?? $post_data['min-duration'] ?? $range['min_value'] ) );
		$max_duration = max( -1, floatval( $post_data['maxdur'] ?? $post_data['max-duration'] ?? $range['max_value'] ) );

		if ( $max_duration > -1 ) {
			$meta_query[] = array(
				'key'     => '_s_duration',
				'value'   => array( $min_duration * 24, $max_duration * 24 ),
				'compare' => 'BETWEEN',
				'type'    => 'numeric',
			);
		}

		$date = $post_data['date'] ?? $post_data['trip-date-select'] ?? '';
		if ( ! empty( $date ) ) {
			$date = wte_clean( wp_unslash( $date ) );

			try {
				$min_date = new \DateTime( $date . '-01' );
				$date     = $min_date->format( 'ym' );
			} catch ( \Exception $e ) {
				$date = str_replace( '-', '', $date );
			}
			$meta_query[] = array(
				'key'     => 'trip_available_months',
				'value'   => $date,
				'compare' => 'LIKE',
			);
		}

		if ( ! empty( $meta_query ) ) {
			$query_args['meta_query'] = $meta_query; // phpcs:ignore
			$query_args['meta_query']['relation'] = 'AND'; // phpcs:ignore
		}

		if ( ! empty( $post_data['sort'] ?? '' ) ) {
			$order_by   = wte_clean( wp_unslash( $post_data['sort'] ) );
			$sort_args  = wte_advanced_search_get_order_args( $order_by ); // phpcs:ignore
			$query_args = array_merge( $query_args, $sort_args );
		}

		if ( ! empty( $post_data['search'] ?? $post_data['s'] ?? '' ) ) {
			$query_args['s'] = $post_data['search'] ?? $post_data['s'];
		}
		// phpcs:enable

		\Wp_Travel_Engine_Archive_Hooks::$query_args = $query_args;

		return apply_filters( 'query_args_for_trip_filters', $query_args );
	}

	/**
	 * Retrieves the duration range.
	 *
	 * @param boolean $new_format Whether to return the range in the new format
	 *
	 * @return object The duration range.
	 */
	public static function get_duration_range( $new_format = false ) {
		$duration = self::get_range( 'wpte_duration_range' );
		return $new_format ? array(
			'min_value' => $duration->min_value,
			'max_value' => $duration->max_value,
		) : $duration;
	}

	/**
	 * Retrieves the price range.
	 *
	 * @param boolean $new_format Whether to return the range in the new format
	 *
	 * @return object The price range.
	 */
	public static function get_price_range( $new_format = false ) {
		$price = self::get_range( 'wpte_price_range' );
		return $new_format ? array(
			'min_value' => $price->min_value,
			'max_value' => $price->max_value,
		) : $price;
	}

	/**
	 * Retrieves the duration or price range.
	 *
	 * @param string $range_type The type of range to retrieve ('wte_duration_range' or 'wte_price_range').
	 * @return object The range object.
	 */
	private static function get_range( $range_type ): object {

		$range = wp_cache_get( $range_type, 'options' );

		if ( $range ) {
			return $range;
		}

		global $wpdb;

		$meta_key = 'wpte_duration_range' === $range_type ? '_s_duration' : '_s_price';
		$query    = $wpdb->prepare(
			"SELECT MIN(`pm`.`meta_value` * 1) AS `min_value`, MAX(`pm`.`meta_value` * 1) AS `max_value`
			FROM `{$wpdb->postmeta}` AS `pm`
			INNER JOIN `{$wpdb->posts}` AS `p` ON `pm`.`post_id` = `p`.`ID`
			WHERE `pm`.`meta_key` = %s AND `p`.`post_status` = %s",
			$meta_key,
			'publish'
		);
		$results 	= $wpdb->get_row( $query ); // phpcs:ignore
		$range    = (object) array(
			'min_value' => 0,
			'max_value' => 0,
		);
		if ( ! empty( $results ) ) {
			$range = $results;
			if ( 'wpte_duration_range' === $range_type ) {
				$range->min_value    = $range->min = floor( (int) $range->min_value / 24 );
				$range->max_value    = ceil( (int) $range->max_value / 24 );
				$range->min_duration = $range->min_value;
				$range->max_duration = $range->max_value;
			} else {
				$range->min_price = $range->min_value;
				$range->max_price = $range->max_value;
			}
		}

		wp_cache_add( $range_type, $range, 'options' );

		return (object) $range;
	}

	/**
	 * Handles template redirection for search results page.
	 *
	 * @param string $template The template path
	 * @static
	 * @return mixed The template path
	 */
	public static function template_redirect( $template ) {
		global $post;
		if ( is_null( $post ) ) {
			return $template;
		}

		if ( self::is_search_page() && ! has_shortcode( $post->post_content, 'WTE_Trip_Search' ) ) {
			$wp_travel_engine_settings = get_option( 'wp_travel_engine_settings', array() );
			$is_enabled_fse_template   = $wp_travel_engine_settings['enable_fse_template'] ?? 'no';
			if ( current_theme_supports( 'wptravelengine-templates' ) || ( wp_is_block_theme() && $is_enabled_fse_template == 'yes' ) ) {
				return $template;
			}
			// wp_enqueue_script( 'wp-travel-engine' );
			// wp_enqueue_style( 'wp-travel-engine' );
			\wptravelengine_get_template( 'template-trip-search-results.php' );
			exit;
		}
	}

	/**
	 * Gets the available filter sections for trip search.
	 *
	 * @static
	 * @return array Array of filter sections with their configurations
	 */
	public static function get_filters_sections() {
		$settings = get_option( 'wp_travel_engine_settings', array() );

		return apply_filters(
			'trip_filters_sections',
			array(
				'destinations' => array(
					'label'  => __( 'Destination', 'wp-travel-engine' ),
					'show'   => empty( $settings['trip_search']['destination'] ),
					'render' => array( __CLASS__, 'filter_destinations_render' ),
				),
				'price'        => array(
					'label'  => __( 'Price', 'wp-travel-engine' ),
					'show'   => empty( $settings['trip_search']['budget'] ),
					'render' => array( __CLASS__, 'filter_price_render' ),
				),
				'duration'     => array(
					'label'  => __( 'Duration', 'wp-travel-engine' ),
					'show'   => empty( $settings['trip_search']['duration'] ),
					'render' => array( __CLASS__, 'filter_duration_render' ),
				),
				'activities'   => array(
					'label'  => __( 'Activities', 'wp-travel-engine' ),
					'show'   => empty( $settings['trip_search']['activities'] ),
					'render' => array( __CLASS__, 'filter_activities_render' ),
				),
				'trip_types'   => array(
					'label'  => __( 'Trip Types', 'wp-travel-engine' ),
					'show'   => empty( $settings['trip_search']['trip_types'] ),
					'render' => array( __CLASS__, 'filter_trip_types_render' ),
				),
				'trip_tag'     => array(
					'label'  => __( 'Tags', 'wp-travel-engine' ),
					'show'   => empty( $settings['trip_search']['trip_tag'] ),
					'render' => array( __CLASS__, 'filter_trip_tag_render' ),
				),
				'difficulty'   => array(
					'label'  => __( 'Difficulties', 'wp-travel-engine' ),
					'show'   => empty( $settings['trip_search']['difficulty'] ),
					'render' => array( __CLASS__, 'filter_difficulty_render' ),
				),
			)
		);
	}

	/**
	 * Renders the taxonomy filter HTML for trip search.
	 *
	 * @param array   $terms    Array of taxonomy terms to display
	 * @param boolean $children Whether to show child terms
	 * @static
	 */
	public static function taxonomy_filter_html( $terms, $children = false ) {

		if ( is_array( $terms ) && count( $terms ) > 0 ) {

			if ( ! $children ) {
				uasort(
					$terms,
					function ( $a, $b ) {
						return strcasecmp( $a->name, $b->name );
					}
				);
			}

			printf( '<ul class="%1$s">', $children ? 'children' : 'wte-search-terms-list' );
			// $list_count  = [];
			$queried_term = get_queried_object();
			$list_count   = 0;
			foreach ( $terms as $term ) {
				if ( isset( $term->parent ) && $term->parent && ! $children ) {
					continue;
				}

				if ( ! isset( $term->taxonomy ) || ! isset( $term->slug ) || ! isset( $term->name ) ) {
					continue;
				}

				++$list_count;

				$get_terms        = wte_array_get( $_GET, $term->taxonomy, array() );
				$get_terms        = is_string( $get_terms ) ? explode( ',', $get_terms ) : ( $get_terms ?: array() );
				$is_queried_term  = ( $queried_term->taxonomy ?? '' ) === $term->taxonomy && ( $queried_term->slug ?? '' ) === $term->slug;
				$is_in_url_params = in_array( $term->slug, $get_terms, true );

				ob_start();
				printf( '<li class="%1$s" %2$s>', $children ? 'has-children' : '', ( $list_count > 4 && ! $children ) ? 'style="display: none;"' : '' );
				printf(
					'<label>'
					. '<input type="checkbox" %1$s value="%2$s" name="%3$s" class="%3$s wte-filter-item"/>'
					. '<span>%4$s</span>'
					. '</label>',
					checked( true, $is_queried_term || $is_in_url_params, false ), // phpcs:ignore
					esc_attr( $term->slug ),
					esc_attr( $term->taxonomy ),
					esc_html( $term->name )
				);

				if ( apply_filters( 'wte_advanced_search_filters_show_tax_count', true ) ) {
					printf( '<span class="count">%1$s</span>', $term->count );
				}
				if ( is_array( $term->children ) && count( $term->children ) > 0 ) {
					$_children = array();
					foreach ( $term->children as $term_child ) {
						if ( ! isset( $terms[ $term_child ] ) ) {
							continue;
						}
						$_children[ $term_child ] = $terms[ $term_child ];
						// $list_count++;
					}
					call_user_func( array( __CLASS__, __FUNCTION__ ), $_children, true );
				}

				echo '</li>';

				echo ob_get_clean();
			}

			echo '</ul>';

			if ( ! $children && $list_count > 4 ) {
				printf(
					'<div class="wte-terms-show-btns"><button class="show-more-btn">%1$s</button><button class="show-less-btn" style="display: none;">%2$s</button></div>',
					esc_html__( 'Show More', 'wp-travel-engine' ),
					esc_html__( 'Show Less', 'wp-travel-engine' )
				);
			}
		}
	}

	/**
	 * Renders the filter section for a specific taxonomy.
	 *
	 * @param string $taxonomy The taxonomy to render filters for
	 * @param array  $filter   Filter configuration array
	 * @static
	 */
	public static function filter_taxonomies_render( $taxonomy, $filter ) {
		if ( empty( $filter['label'] ) ) {
			return;
		}

		$categories = get_categories( "taxonomy={$taxonomy}" );
		if ( empty( $categories ) ) {
			return;
		}

		$terms = \wte_get_terms_by_id( $taxonomy );
		if ( empty( $terms ) ) {
			return;
		}

		?>
		<div class='advanced-search-field search-trip-type wte-list-opn'>
			<h3 class='filter-section-title trip-type'><?php echo esc_html( $filter['label'] ); ?></h3>
			<div class="filter-section-content">
				<?php self::taxonomy_filter_html( $terms ); ?>
			</div>
		</div>
		<?php
	}

	/**
	 * Renders the destinations filter section.
	 *
	 * @param array $filter Filter configuration array
	 * @static
	 */
	public static function filter_destinations_render( $filter ) {
		self::filter_taxonomies_render( 'destination', $filter );
	}

	/**
	 * Renders the activities filter section.
	 *
	 * @param array $filter Filter configuration array
	 * @static
	 */
	public static function filter_activities_render( $filter ) {
		self::filter_taxonomies_render( 'activities', $filter );
	}

	/**
	 * Renders the trip tag filter section.
	 *
	 * @param array $filter Filter configuration array
	 * @static
	 * @since 5.5.7
	 */
	public static function filter_trip_tag_render( $filter ) {
		self::filter_taxonomies_render( 'trip_tag', $filter );
	}

	/**
	 * Renders the difficulty filter section.
	 *
	 * @param array $filter Filter configuration array
	 * @static
	 * @since 5.5.7
	 */
	public static function filter_difficulty_render( $filter ) {
		self::filter_taxonomies_render( 'difficulty', $filter );
	}

	/**
	 * Renders the duration filter section.
	 *
	 * @param array $filter Filter configuration array
	 * @static
	 */
	public static function filter_duration_render( $filter ) {
		$duration_range = self::get_duration_range( true );
		$min_duration   = intval( ! empty( $_GET['min-duration'] ?? '' ) ? $_GET['min-duration'] : $duration_range['min_value'] );
		$max_duration   = intval( ! empty( $_GET['max-duration'] ?? '' ) ? $_GET['max-duration'] : $duration_range['max_value'] );
		?>
		<div class="advanced-search-field search-duration search-trip-type"
			data-value-format="duration"
			data-suffix="<?php echo esc_attr__( 'Days', 'wp-travel-engine' ); ?>"
			data-min="<?php echo esc_attr( $duration_range['min_value'] ); ?>"
			data-max="<?php echo esc_attr( $duration_range['max_value'] ); ?>"
			data-range="<?php echo esc_attr( $min_duration . ',' . $max_duration ); ?>"
			data-range-slider="#duration-slider-range">
			<h3 class="filter-section-title"><?php echo esc_html( $filter['label'] ); ?></h3>
			<div class="filter-section-content">
				<div id="duration-slider-range" data-min-key="mindur" data-max-key="maxdur"></div>
				<div class="wpte-slider-values">
					<span id="min-duration" class="min-duration" name="min-duration" data-value-min-display>
						<?php printf( esc_html__( '%1$s Days', 'wp-travel-engine' ), esc_html( round( $min_duration ) ) ); ?>
					</span>
					<span class="max-duration" id="max-duration" name="max-duration" data-value-max-display>
						<?php printf( esc_html__( '%1$s Days', 'wp-travel-engine' ), esc_html( round( $max_duration ) ) ); ?>
					</span>
				</div>
			</div>
		</div>
		<?php
	}

	/**
	 * Renders the price filter section.
	 *
	 * @param array $filter Filter configuration array
	 * @static
	 */
	public static function filter_price_render( $filter ) {
		$price_range = self::get_price_range( true );
		$min_cost    = floatval( ! empty( $_GET['min-cost'] ?? '' ) ? $_GET['min-cost'] : $price_range['min_value'] );
		$max_cost    = floatval( ! empty( $_GET['max-cost'] ?? '' ) ? $_GET['max-cost'] : $price_range['max_value'] );

		print( '<div class="advanced-search-field search-cost search-trip-type" data-max="' . esc_attr( $price_range['max_value'] ) . '" data-min="' . esc_attr( $price_range['min_value'] ) . '" data-range="' . esc_attr( $min_cost ) . ',' . esc_attr( $max_cost ) . '" data-value-format="price" data-range-slider="#cost-slider-range">'
				. '<h3 class="filter-section-title">' . esc_html( $filter['label'] ) . '</h3>'
				. '<div class="filter-section-content">'
				. '<div id="cost-slider-range" data-min-key="mincost" data-max-key="maxcost"></div>'
				. '<div class="wpte-slider-values"><span class="min-cost" data-value-min-display>'
				. wp_kses( \wte_get_formated_price( $min_cost ), 'allowed_price_html' )
				. '</span><span class="max-cost" data-value-max-display>'
				. wp_kses( \wte_get_formated_price( $max_cost ), 'allowed_price_html' )
				. '</span></div>'
				. '</div>'
				. '</div>'
		);
	}

	/**
	 * Renders the trip types filter section.
	 *
	 * @param array $filter Filter configuration array
	 * @static
	 */
	public static function filter_trip_types_render( $filter ) {
		self::filter_taxonomies_render( 'trip_types', $filter );
	}

	/**
	 * Shows filters sidebar on Archive Page.
	 */
	public static function archive_filter_sidebar() {
		// \wp_enqueue_style( 'wte-trip-search' );
		// \wp_enqueue_script( 'wte-trip-search' );
		self::enqueue_assets();
		\wptravelengine_get_template( 'template-trip-filters-sidebar.php' );
	}

	/**
	 * Initializes the search functionality and registers shortcodes.
	 *
	 * @since 5.5.7
	 */
	public function init() {
		add_shortcode( 'Wte_Advanced_Search_Form', array( self::class, 'search_form' ) );
		add_shortcode( 'WTE_Trip_Search', array( $this, 'search_results' ) );
	}

	/**
	 * Updates the trip infos in each update.
	 *
	 * @return void
	 * @since 6.6.9
	 */
	public static function update_metas_for_trip_search() {

		$posts = get_posts(
			array(
				'post_type'      => WP_TRAVEL_ENGINE_POST_TYPE,
				'post_status'    => 'publish',
				'posts_per_page' => -1,
			)
		);

		foreach ( $posts as $post ) {

			$trip = wptravelengine_get_trip( $post );

			if ( ! $trip || ! $trip->has_package() ) {
				continue;
			}

			$price         = $trip->has_sale() ? $trip->get_sale_price() : $trip->get_price();
			$duration      = (int) $trip->get_trip_duration();
			$duration_unit = $trip->get_trip_duration_unit();

			$available_months = array();
			foreach ( $trip->packages as $package ) {
				$package_dates    = $package->get_meta( 'package-dates' ) ?: array();
				$_a_m             = array_values(
					array_unique(
						call_user_func_array(
							'array_merge',
							array_map(
								function ( $string ) use ( $package, $package_dates ) {
									return ( new \WPTravelEngine\Helpers\PackageDateParser(
										$package,
										$package_dates[ $string ]
									) )->get_unique_dates( false, array(), 'ym' );
								},
								array_keys( $package_dates )
							)
						)
					)
				);
				$available_months = array_merge( $available_months, $_a_m );
			}
			if ( $available_months ) {
				$trip->set_meta( 'trip_available_months', implode( ',', array_unique( $available_months ) ) );
			}

			$dur = 'days' === $duration_unit ? ( $duration * 24 ) : $duration;

			$trip->set_meta( '_s_price', $price )
				->set_meta( '_s_has_sale', wptravelengine_replace( $trip->has_sale(), true, 'yes', 'no' ) )
				->set_meta( '_s_min_pax', $trip->get_minimum_participants() )
				->set_meta( '_s_max_pax', $trip->get_maximum_participants() )
				->set_meta( '_s_duration', $dur )
				->set_meta( 'wp_travel_engine_setting_trip_duration', $dur )
				->set_meta( 'wp_travel_engine_setting_trip_price', $price )
				->set_meta( 'wp_travel_engine_setting_trip_actual_price', $trip->get_price() )
				->save();

			if ( $trip->get_meta( 'max_travellers_per_day' ) ) {
				$trip->delete_meta( 'max_travellers_per_day' );
			}

			do_action( 'wptravelengine_update_trip_metas', $trip );
		}
	}

	/**
	 * Renders the search results HTML.
	 *
	 * @return string The search results HTML
	 */
	public function search_results() {
		ob_start();
		wptravelengine_get_template( 'content-trip-search-results.php' );
		return ob_get_clean();
	}

	/**
	 * Renders the search form HTML.
	 *
	 * @static
	 * @return string The search form HTML
	 */
	public static function search_form( $atts ) {
		$is_rest_route = defined( 'REST_REQUEST' ) && REST_REQUEST;

		if ( ( is_admin() && ! $is_rest_route ) || ( ! is_admin() && $is_rest_route ) ) {
			return '';
		}

		$atts = shortcode_atts(
			array(
				'direction' => 'horizontal',
			),
			$atts,
			'Wte_Advanced_Search_Form'
		);

		if ( ! wp_script_is( 'wptravelengine-trip-search-widgets-slider', 'enqueued' ) ) {
			self::enqueue_trip_search_scripts();
		}

		ob_start();
		wptravelengine_get_template( 'template-trip-search-form.php', array( 'direction' => $atts['direction'] ) );
		return ob_get_clean();
	}

	/**
	 * Checks if current page is the search results page.
	 *
	 * @static
	 * @return boolean True if current page is search page, false otherwise
	 */
	public static function is_search_page() {
		global $post;

		if ( ! is_object( $post ) ) {
			return false;
		}

		$options = get_option( 'wp_travel_engine_settings', array() );

		return isset( $options['pages']['search'] ) && ( (int) $post->ID === (int) $options['pages']['search'] );
	}

	/**
	 * Renders the search page content.
	 *
	 * @static
	 */
	public static function search_page() {
		// 404 do_action not found
	}

	/**
	 * Renders the search fields HTML.
	 *
	 * @static
	 */
	public static function search_fields() {
	}

	/**
	 * Adds trip search settings to the global settings array.
	 *
	 * @param array $settings The global settings array
	 * @static
	 * @return array Modified settings array with trip search settings
	 */
	public static function settings( $settings ) {
		$settings['wte_trip_search'] = array(
			'label'        => __( 'Trip Search', 'wp-travel-engine' ),
			'content_path' => plugin_dir_path( __FILE__ ) . 'trip-search/views/admin-settings.php',
			'current'      => true,
			'has_updates'  => 'wte_note_5.5.7',
		);

		return $settings;
	}

	/**
	 * Adds search page selection to the page settings.
	 *
	 * @param array $pages The page settings array
	 * @static
	 * @return array Modified page settings array with search page option
	 */
	public static function choose_page( $pages ) {
		$options = get_option( 'wp_travel_engine_settings', array() );
		$search  = isset( $options['pages']['search'] ) ? esc_attr( $options['pages']['search'] ) : wptravelengine_get_page_by_title( 'Trip Search Result' )->ID;

		$_pages = get_pages();
		$_pages = array_column( $_pages, 'post_title', 'ID' );

		$pages['wte-search-page'] = array(
			'label'         => __( 'Trip Search Results Page', 'wp-travel-engine' ),
			'label_class'   => 'wpte-field-label',
			'wrapper_class' => 'wpte-field wpte-select wpte-floated',
			'field_label'   => __( 'Trip Search Results Page', 'wp-travel-engine' ),
			'type'          => 'select',
			'options'       => $_pages,
			'class'         => 'wpte-enhanced-select',
			'name'          => 'wp_travel_engine_settings[pages][search]',
			'default'       => $search,
			'selected'      => $search,
			'tooltip'       => __( 'This is the trip search results page with search filters.', 'wp-travel-engine' ),
		);

		return $pages;
	}

	/**
	 * Gets the search page ID from settings.
	 *
	 * @static
	 * @return int The page ID for the search results page, -1 if not found
	 */
	public static function get_page_id() {
		$page_id = get_option( 'wp_travel_engine_search_page_id', false );

		if ( ! $page_id ) {
			$settings = get_option( 'wp_travel_engine_settings', array() ); // Not used wp_travel_engine_get_settings due to infinite loop.
			$page_id  = isset( $settings['pages']['search'] ) ? $settings['pages']['search'] : - 1;
			update_option( 'wp_travel_engine_search_page_id', $page_id );
		}

		$page_id = apply_filters( 'wp_travel_engine_get_search_page_id', $page_id );

		return $page_id ? absint( $page_id ) : - 1;
	}

	/**
	 * Renders the search filter icon based on the filter type.
	 *
	 * @param array  $args Arguments for the icon rendering
	 * @param string $tag  The type of filter ('duration', 'price', or 'date')
	 * @static
	 * @since 5.7.1
	 */
	public static function search_filter_icon( $args, $tag ) {
		?>
		<span class="icon">
		<?php
		if ( class_exists( 'Elementor\Icons_Manager' ) && isset( $args['icon'] ) && isset( $args['icon']['value'] ) && $args['icon']['value'] != '' ) {
			\Elementor\Icons_Manager::render_icon( $args['icon'] );
		} else {
			switch ( $tag ) {
				case 'duration':
					?>
					<svg width="15" height="15" viewBox="0 0 15 15" fill="none" xmlns="http://www.w3.org/2000/svg">
						<path
							d="M7.4375 0.125C5.99123 0.125 4.57743 0.553871 3.3749 1.35738C2.17236 2.16089 1.2351 3.30294 0.681634 4.63913C0.128168 5.97531 -0.0166435 7.44561 0.265511 8.8641C0.547665 10.2826 1.24411 11.5855 2.26678 12.6082C3.28946 13.6309 4.59242 14.3273 6.0109 14.6095C7.42939 14.8916 8.89969 14.7468 10.2359 14.1934C11.5721 13.6399 12.7141 12.7026 13.5176 11.5001C14.3211 10.2976 14.75 8.88377 14.75 7.4375C14.75 6.47721 14.5609 5.52632 14.1934 4.63913C13.8259 3.75193 13.2872 2.94581 12.6082 2.26678C11.9292 1.58775 11.1231 1.04912 10.2359 0.681631C9.34868 0.314143 8.39779 0.125 7.4375 0.125ZM7.4375 13.2875C6.28048 13.2875 5.14944 12.9444 4.18742 12.3016C3.22539 11.6588 2.47558 10.7451 2.03281 9.6762C1.59004 8.60725 1.47419 7.43101 1.69991 6.29622C1.92563 5.16143 2.48279 4.11906 3.30093 3.30092C4.11907 2.48279 5.16144 1.92563 6.29622 1.69991C7.43101 1.47418 8.60725 1.59003 9.6762 2.0328C10.7451 2.47558 11.6588 3.22539 12.3016 4.18741C12.9444 5.14944 13.2875 6.28048 13.2875 7.4375C13.2875 8.98901 12.6712 10.477 11.5741 11.5741C10.477 12.6712 8.98902 13.2875 7.4375 13.2875ZM9.70438 7.89819L8.16875 7.01337V3.78125C8.16875 3.58731 8.09171 3.40131 7.95457 3.26418C7.81744 3.12704 7.63144 3.05 7.4375 3.05C7.24356 3.05 7.05757 3.12704 6.92043 3.26418C6.78329 3.40131 6.70625 3.58731 6.70625 3.78125V7.4375C6.70625 7.4375 6.70625 7.496 6.70625 7.52525C6.71058 7.57563 6.72293 7.625 6.74282 7.6715C6.75787 7.71488 6.77748 7.75655 6.80131 7.79581C6.82132 7.83737 6.84585 7.87661 6.87444 7.91281L6.99144 8.00787L7.05725 8.07369L8.9585 9.17056C9.06995 9.23373 9.19603 9.26651 9.32413 9.26562C9.48604 9.26676 9.64375 9.21412 9.77252 9.11596C9.90129 9.01781 9.99385 8.8797 10.0357 8.72328C10.0775 8.56686 10.0662 8.40098 10.0036 8.25166C9.94101 8.10233 9.83062 7.97801 9.68975 7.89819H9.70438Z"
							fill="currentColor" />
					</svg>
					<?php
					break;
				case 'price':
					?>
					<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
						<path
							d="M6 11V15M18 9V13M17 4C19.4487 4 20.7731 4.37476 21.4321 4.66544C21.5199 4.70415 21.5638 4.72351 21.6904 4.84437C21.7663 4.91682 21.9049 5.12939 21.9405 5.22809C22 5.39274 22 5.48274 22 5.66274V16.4111C22 17.3199 22 17.7743 21.8637 18.0079C21.7251 18.2454 21.5914 18.3559 21.3319 18.4472C21.0769 18.5369 20.562 18.438 19.5322 18.2401C18.8114 18.1017 17.9565 18 17 18C14 18 11 20 7 20C4.55129 20 3.22687 19.6252 2.56788 19.3346C2.48012 19.2958 2.43624 19.2765 2.3096 19.1556C2.23369 19.0832 2.09512 18.8706 2.05947 18.7719C2 18.6073 2 18.5173 2 18.3373L2 7.58885C2 6.68009 2 6.2257 2.13628 5.99214C2.2749 5.75456 2.40859 5.64412 2.66806 5.55281C2.92314 5.46305 3.43803 5.56198 4.46783 5.75985C5.18862 5.89834 6.04348 6 7 6C10 6 13 4 17 4ZM14.5 12C14.5 13.3807 13.3807 14.5 12 14.5C10.6193 14.5 9.5 13.3807 9.5 12C9.5 10.6193 10.6193 9.5 12 9.5C13.3807 9.5 14.5 10.6193 14.5 12Z"
							stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
					</svg>
					<?php
					break;
				case 'date':
					?>
					<svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg"><path
							d="M8 9.5C8.14834 9.5 8.29334 9.45601 8.41668 9.3736C8.54002 9.29119 8.63614 9.17406 8.69291 9.03701C8.74968 8.89997 8.76453 8.74917 8.73559 8.60368C8.70665 8.4582 8.63522 8.32456 8.53033 8.21967C8.42544 8.11478 8.2918 8.04335 8.14632 8.01441C8.00083 7.98547 7.85003 8.00032 7.71299 8.05709C7.57594 8.11386 7.45881 8.20999 7.3764 8.33332C7.29399 8.45666 7.25 8.60166 7.25 8.75C7.25 8.94891 7.32902 9.13968 7.46967 9.28033C7.61032 9.42098 7.80109 9.5 8 9.5ZM11.75 9.5C11.8983 9.5 12.0433 9.45601 12.1667 9.3736C12.29 9.29119 12.3861 9.17406 12.4429 9.03701C12.4997 8.89997 12.5145 8.74917 12.4856 8.60368C12.4566 8.4582 12.3852 8.32456 12.2803 8.21967C12.1754 8.11478 12.0418 8.04335 11.8963 8.01441C11.7508 7.98547 11.6 8.00032 11.463 8.05709C11.3259 8.11386 11.2088 8.20999 11.1264 8.33332C11.044 8.45666 11 8.60166 11 8.75C11 8.94891 11.079 9.13968 11.2197 9.28033C11.3603 9.42098 11.5511 9.5 11.75 9.5ZM8 12.5C8.14834 12.5 8.29334 12.456 8.41668 12.3736C8.54002 12.2912 8.63614 12.1741 8.69291 12.037C8.74968 11.9 8.76453 11.7492 8.73559 11.6037C8.70665 11.4582 8.63522 11.3246 8.53033 11.2197C8.42544 11.1148 8.2918 11.0434 8.14632 11.0144C8.00083 10.9855 7.85003 11.0003 7.71299 11.0571C7.57594 11.1139 7.45881 11.21 7.3764 11.3333C7.29399 11.4567 7.25 11.6017 7.25 11.75C7.25 11.9489 7.32902 12.1397 7.46967 12.2803C7.61032 12.421 7.80109 12.5 8 12.5ZM11.75 12.5C11.8983 12.5 12.0433 12.456 12.1667 12.3736C12.29 12.2912 12.3861 12.1741 12.4429 12.037C12.4997 11.9 12.5145 11.7492 12.4856 11.6037C12.4566 11.4582 12.3852 11.3246 12.2803 11.2197C12.1754 11.1148 12.0418 11.0434 11.8963 11.0144C11.7508 10.9855 11.6 11.0003 11.463 11.0571C11.3259 11.1139 11.2088 11.21 11.1264 11.3333C11.044 11.4567 11 11.6017 11 11.75C11 11.9489 11.079 12.1397 11.2197 12.2803C11.3603 12.421 11.5511 12.5 11.75 12.5ZM4.25 9.5C4.39834 9.5 4.54334 9.45601 4.66668 9.3736C4.79001 9.29119 4.88614 9.17406 4.94291 9.03701C4.99968 8.89997 5.01453 8.74917 4.98559 8.60368C4.95665 8.4582 4.88522 8.32456 4.78033 8.21967C4.67544 8.11478 4.5418 8.04335 4.39632 8.01441C4.25083 7.98547 4.10003 8.00032 3.96299 8.05709C3.82594 8.11386 3.70881 8.20999 3.6264 8.33332C3.54399 8.45666 3.5 8.60166 3.5 8.75C3.5 8.94891 3.57902 9.13968 3.71967 9.28033C3.86032 9.42098 4.05109 9.5 4.25 9.5ZM13.25 2H12.5V1.25C12.5 1.05109 12.421 0.860322 12.2803 0.71967C12.1397 0.579018 11.9489 0.5 11.75 0.5C11.5511 0.5 11.3603 0.579018 11.2197 0.71967C11.079 0.860322 11 1.05109 11 1.25V2H5V1.25C5 1.05109 4.92098 0.860322 4.78033 0.71967C4.63968 0.579018 4.44891 0.5 4.25 0.5C4.05109 0.5 3.86032 0.579018 3.71967 0.71967C3.57902 0.860322 3.5 1.05109 3.5 1.25V2H2.75C2.15326 2 1.58097 2.23705 1.15901 2.65901C0.737053 3.08097 0.5 3.65326 0.5 4.25V13.25C0.5 13.8467 0.737053 14.419 1.15901 14.841C1.58097 15.2629 2.15326 15.5 2.75 15.5H13.25C13.8467 15.5 14.419 15.2629 14.841 14.841C15.2629 14.419 15.5 13.8467 15.5 13.25V4.25C15.5 3.65326 15.2629 3.08097 14.841 2.65901C14.419 2.23705 13.8467 2 13.25 2ZM14 13.25C14 13.4489 13.921 13.6397 13.7803 13.7803C13.6397 13.921 13.4489 14 13.25 14H2.75C2.55109 14 2.36032 13.921 2.21967 13.7803C2.07902 13.6397 2 13.4489 2 13.25V6.5H14V13.25ZM14 5H2V4.25C2 4.05109 2.07902 3.86032 2.21967 3.71967C2.36032 3.57902 2.55109 3.5 2.75 3.5H13.25C13.4489 3.5 13.6397 3.57902 13.7803 3.71967C13.921 3.86032 14 4.05109 14 4.25V5ZM4.25 12.5C4.39834 12.5 4.54334 12.456 4.66668 12.3736C4.79001 12.2912 4.88614 12.1741 4.94291 12.037C4.99968 11.9 5.01453 11.7492 4.98559 11.6037C4.95665 11.4582 4.88522 11.3246 4.78033 11.2197C4.67544 11.1148 4.5418 11.0434 4.39632 11.0144C4.25083 10.9855 4.10003 11.0003 3.96299 11.0571C3.82594 11.1139 3.70881 11.21 3.6264 11.3333C3.54399 11.4567 3.5 11.6017 3.5 11.75C3.5 11.9489 3.57902 12.1397 3.71967 12.2803C3.86032 12.421 4.05109 12.5 4.25 12.5Z"
							fill="currentColor" /></svg>
					<?php
					break;
				default:
					break;
			}
		}
		?>
		</span>
		<?php
	}
}
