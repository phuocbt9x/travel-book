<?php
/**
 * WP Travel Engine Archive Hooks
 *
 * This class defines all hooks for archive page of the trip.
 *
 * @package    Wp_Travel_Engine
 * @subpackage Wp_Travel_Engine/includes
 * @since      1.0.0
 * @author     WP Travel Engine <https://wptravelengine.com/>
 */

use WPTravelEngine\Modules\TripSearch;

/**
 * Class Wp_Travel_Engine_Archive_Hooks
 *
 * @since 1.0.0
 */
class Wp_Travel_Engine_Archive_Hooks {

	/**
	 * Current custom query.
	 *
	 * @var ?WP_Query
	 * @access public
	 * @static
	 * @since 6.6.0
	 */
	public static $query = null;

	/**
	 * Query args for trip filters.
	 *
	 * @var array
	 * @since 6.7.3
	 */
	public static array $query_args = array();

	/**
	 * Featured trips post IDs.
	 *
	 * @var int[]
	 * @since 6.7.3
	 */
	protected static array $featured_trip_ids = array();

	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		add_action( 'wp_travel_engine_trip_archive_outer_wrapper', array( $this, 'wp_travel_engine_trip_archive_wrapper' ) );
		add_action( 'wp_travel_engine_trip_archive_wrap', array( $this, 'wp_travel_engine_trip_archive_wrap' ) );
		add_action( 'wp_travel_engine_trip_archive_outer_wrapper_close', array( $this, 'wp_travel_engine_trip_archive_outer_wrapper_close' ) );
		add_action( 'wp_travel_engine_header_filters', array( $this, 'wp_travel_engine_header_filters_template' ) );
		add_action( 'wp_travel_engine_archive_header_block', array( $this, 'wp_travel_engine_archive_header_block' ) );
		add_action( 'wp_travel_engine_featured_trips_sticky', array( $this, 'wte_featured_trips_sticky' ), 10, 1 );
		add_action( 'pre_get_posts', array( $this, 'archive_pre_get_posts' ), 11 );
	}

	/**
	 * Get archive view mode.
	 *
	 * @since 1.0.0
	 * @return string
	 * @updated 6.6.8 Migrated the code to wp_travel_engine_get_archive_view_mode() function due to security reason.
	 */
	public static function archive_view_mode() {
		return wp_travel_engine_get_archive_view_mode();
	}

	/**
	 * Get archive sort by option.
	 *
	 * @since 5.5.7
	 * @return string
	 */
	public static function archive_sort_by() {
		if ( ! empty( $_GET['wte_orderby'] ) ) {
			return sanitize_text_field( wp_unslash( $_GET['wte_orderby'] ) );
		}
		return get_option( 'wptravelengine_trip_sort_by', 'latest' );
	}

	/**
	 * Get query arguments based on sort option.
	 *
	 * @since 5.5.7
	 * @param string $sort_by Sort option.
	 * @return array
	 */
	public static function get_query_args_by_sort( $sort_by ) {
		$sort_args = array();
		switch ( $sort_by ) {
			case 'latest':
				$sort_args['order']   = 'DESC';
				$sort_args['orderby'] = 'date';
				break;
			// case 'dates':
			// $sort_args['order']   = 'ASC';
			// $sort_args['orderby'] = 'date';
			// break;
			case 'rating':
				$sort_args['order']   = 'DESC';
				$sort_args['orderby'] = 'comment_count';
				break;
			case 'price':
				$sort_args['meta_key'] = '_s_price';
				$sort_args['order']    = 'ASC';
				$sort_args['orderby']  = 'meta_value_num';
				break;
			case 'price-desc':
				$sort_args['meta_key'] = '_s_price';
				$sort_args['order']    = 'DESC';
				$sort_args['orderby']  = 'meta_value_num';
				break;
			case 'days':
				$sort_args['meta_key'] = '_s_duration';
				$sort_args['order']    = 'ASC';
				$sort_args['orderby']  = 'meta_value_num';
				break;
			case 'days-desc':
				$sort_args['meta_key'] = '_s_duration';
				$sort_args['order']    = 'DESC';
				$sort_args['orderby']  = 'meta_value_num';
				break;
			case 'name':
				$sort_args['order']   = 'ASC';
				$sort_args['orderby'] = 'title';
				break;
			case 'name-desc':
				$sort_args['order']   = 'DESC';
				$sort_args['orderby'] = 'title';
				break;
		}

		return $sort_args;
	}

	/**
	 * Check if featured trips should be shown on top.
	 *
	 * @since 5.5.7
	 * @return bool
	 */
	public function show_featured_trips_on_top() {
		$settings = get_option( 'wp_travel_engine_settings', true );
		return ! isset( $settings['show_featured_trips_on_top'] ) || 'yes' === $settings['show_featured_trips_on_top'];
	}

	/**
	 * Filter query on archive page to sort and list trips properly.
	 *
	 * @param WP_Query $query The WP_Query instance.
	 *
	 * @return void
	 * @since 5.5.7
	 * @updated 6.7.0
	 * @since 6.7.3 Added featured trips processing.
	 */
	public function archive_pre_get_posts( $query ) {

		$post_not_in = $query->get( 'post__not_in', array() );

		if ( ! is_admin() && $query->is_main_query() ) {
			if ( $query->is_post_type_archive( WP_TRAVEL_ENGINE_POST_TYPE ) || $query->is_tax ) {
				if ( $query->is_tax ) {
					$queried_object = $query->get_queried_object();
					$taxonomies     = wptravelengine_get_trip_taxonomies();
					if ( ! isset( $taxonomies[ $queried_object->taxonomy ?? '' ] ) ) {
						return;
					}
				}
			} else {
				return;
			}

			$sort_by   = self::archive_sort_by();
			$sort_args = self::get_query_args_by_sort( $sort_by );
			if ( isset( $sort_args['order'] ) ) {
				$query->set( 'order', $sort_args['order'] );
			}
			if ( isset( $sort_args['meta_key'] ) ) {
				$query->set( 'meta_key', $sort_args['meta_key'] );
			}
			if ( isset( $sort_args['orderby'] ) ) {
				$query->set( 'orderby', $sort_args['orderby'] );
			}

			self::$query_args = $query->query_vars;

			$query->set( 'wpte_trip_search', true );
		}

		if ( $query->get( 'wpte_trip_search' ) && wptravelengine_toggled( wptravelengine_settings()->get( 'show_featured_trips_on_top' ) ) ) {
			$this->process_featured_trips();
			$post_not_in = array_merge( $post_not_in, self::$featured_trip_ids );
		}

		$custom_trips = get_option( 'wptravelengine_custom_trips', array() );

		$query->set( 'post__not_in', array_unique( array_merge( $post_not_in, $custom_trips ) ) );
	}

	/**
	 * Process featured trips.
	 *
	 * @since 6.7.3
	 * @return void
	 */
	private function process_featured_trips() {
		static $filter_added = false;

		if ( $filter_added ) {
			return;
		}

		global $wp_query;

		self::$query ??= $wp_query;

		$trips_array   = wte_get_featured_trips_array( true );
		$feat_trip_num = (int) wptravelengine_settings()->get( 'feat_trip_num', 2 );

		if ( empty( $trips_array ) || empty( $feat_trip_num ) || empty( self::$query_args ) ) {
			return;
		}

		$args = array_merge(
			self::$query_args,
			array(
				'post__in'            => $trips_array,
				'paged'               => 1,
				'posts_per_page'      => $feat_trip_num,
				'wpte_trip_search'    => false,
				'wpte_featured_query' => true,
			)
		);

		$featured_query = new \WP_Query( $args );
		wp_reset_postdata();

		if ( empty( $featured_query->posts ) ) {
			return;
		}

		self::$featured_trip_ids = wp_list_pluck( $featured_query->posts, 'ID' );

		add_filter(
			'the_posts',
			function ( $posts, $query ) use ( $featured_query ) {
				if ( ! $query->get( 'wpte_trip_search' ) || intval( $query->get( 'paged' ) ) > 1 ) {
					return $posts;
				}
				$merged_posts      = array_merge( $featured_query->posts, $posts );
				$query->post_count = count( $merged_posts );
				return $merged_posts;
			},
			10,
			2
		);

		$filter_added = true;
	}

	/**
	 * Get featured trips IDs.
	 *
	 * @param bool $get_all Get all featured trips.
	 * @since 5.5.7
	 * @return array
	 * @since 6.7.3 Added $get_all parameter to get all featured trips.
	 */
	public static function get_featured_trips_ids( bool $get_all = false ) {
		global $wpdb;

		$featured_trips = wp_cache_get( 'featured_trip_ids_' . (int) $get_all, 'wptravelengine' );

		if ( (bool) $featured_trips ) {
			return $featured_trips;
		}

		$prepare    = "SELECT `post_id` FROM {$wpdb->postmeta} WHERE `meta_key` = %s AND `meta_value` = %s";
		$meta_value = array( 'wp_travel_engine_featured_trip', 'yes' );

		if ( ! $get_all ) {
			$prepare     .= ' LIMIT %d';
			$meta_value[] = (int) wptravelengine_settings()->get( 'feat_trip_num', 2 );
		}

		$prepared_statement = $wpdb->prepare( $prepare, ...$meta_value );

		$results = $wpdb->get_col( $prepared_statement );

		wp_cache_add( 'featured_trip_ids_' . (int) $get_all, $results, 'wptravelengine' );

		$args = array(
			'post_type'   => 'trip',
			'numberposts' => -1,
		);

		return apply_filters( 'wp_travel_engine_feat_trips_array', $results, $args );
	}

	/**
	 * Featured Trips sticky section for WP Travel Engine Archives.
	 *
	 * @since 1.0.0
	 * @param string $view_mode View mode.
	 * @return void
	 */
	public function wte_featured_trips_sticky( $view_mode ) {
		global $wp_query;

		self::$query ??= $wp_query;

		if ( ! self::$query->is_post_type_archive() || ! $this->show_featured_trips_on_top() ) {
			return;
		}

		$trips_array = wte_get_featured_trips_array();
		if ( empty( $trips_array ) ) {
			return;
		}

		$args = array(
			'post_type' => 'trip',
			'post__in'  => $trips_array,
		);

		$featured_query = new \WP_Query( $args );

		$user_wishlists = wptravelengine_user_wishlists();
		$template_name  = wptravelengine_get_template_by_view_mode( $view_mode );

		while ( $featured_query->have_posts() ) :
			$featured_query->the_post();
			$details                   = wte_get_trip_details( get_the_ID() );
			$details['user_wishlists'] = $user_wishlists;
			wptravelengine_get_template( $template_name, $details );
		endwhile;

		wp_reset_postdata();
	}

	/**
	 * Get archive filters sub options.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public static function archive_filters_sub_options() {
		$view_mode       = wp_travel_engine_get_archive_view_mode();
		$orderby         = self::archive_sort_by();
		$sorting_options = wptravelengine_get_sorting_options();
		$orderby_label   = 'Latest';

		foreach ( $sorting_options as $key => $val ) {
			if ( $key === $orderby && ! is_array( $val ) ) {
				$orderby_label = $val;
			} elseif ( is_array( $val ) && isset( $val['options'][ $orderby ] ) ) {
				$orderby_label = strpos( $orderby, 'name' ) === false
					? $val['label'] . ' - ' . $val['options'][ $orderby ]
					: $val['options'][ $orderby ];
			}
		}

		$current_url = '';
		$protocol    = ( ! empty( $_SERVER['HTTPS'] ) && $_SERVER['HTTPS'] === 'on' ) ? 'https://' : 'http://';
		if ( ! empty( $_SERVER['HTTP_HOST'] ) ) {
			$current_url .= esc_url_raw( wp_unslash( $protocol . $_SERVER['HTTP_HOST'] ) );
		}
		if ( ! empty( $_SERVER['REQUEST_URI'] ) ) {
			$current_url .= esc_url_raw( wp_unslash( $_SERVER['REQUEST_URI'] ) );
		}

		$search_value = $_GET['s'] ?? $_GET['search'] ?? '';

		?>
		<div class="wpte-toolbar-container">
			<div class="wp-travel-toolbar trip-content-area">
				<div class="wpte-trip-search-input">
					<input type="search" name="wte_search" id="wte_search" placeholder="<?php esc_html_e( 'Search', 'wp-travel-engine' ); ?>"<?php echo empty( $search_value ) ? '' : ' value="' . esc_attr( $search_value ) . '"'; ?>>
				</div>

				<div class="wte-filterby-dropdown wte-ordering">
					<span><?php esc_html_e( 'Sort', 'wp-travel-engine' ); ?></span>
					<div class="wpte-trip__adv-field wpte__select-field">
						<span class="wpte__input" name="wte_orderby" data-label="<?php echo esc_attr( $orderby ); ?>">(<?php echo esc_html( $orderby_label ); ?>)</span>
						<div class="wpte__select-options">
							<ul>
								<?php foreach ( $sorting_options as $id => $name ) : ?>
									<?php if ( is_array( $name ) ) : ?>
										<li>
											<ul>
												<li class="wpte__select-options__label"><?php echo esc_html( $name['label'] ); ?></li>
												<?php foreach ( $name['options'] as $key => $label ) : ?>
													<li data-value="<?php echo esc_attr( $key ); ?>" data-label="<?php echo esc_attr( strpos( $key, 'name' ) === false ? $name['label'] . ' - ' . $label : $label ); ?>" <?php echo ( $key === $orderby ) ? 'data-selected' : ''; ?>>
														<span><?php echo esc_html( $label ); ?></span>
													</li>
												<?php endforeach; ?>
											</ul>
										</li>
									<?php else : ?>
										<li data-value="<?php echo esc_attr( $id ); ?>" data-label="<?php echo esc_attr( $name ); ?>" <?php echo ( $id === $orderby ) ? 'class="selected" data-selected' : ''; ?>>
											<span><?php echo esc_html( $name ); ?></span>
										</li>
									<?php endif; ?>
								<?php endforeach; ?>
							</ul>
						</div>
					</div>
					<?php wte_query_string_form_fields( null, array( 'wte_orderby', 'submit', 'paged' ) ); ?>
				</div>

				<div class="wp-travel-engine-toolbar wte-view-modes">
					<span><?php esc_html_e( 'View by :', 'wp-travel-engine' ); ?></span>
					<ul class="wte-view-mode-selection-lists">
						<li class="wte-view-mode-selection <?php echo ( $view_mode === 'grid' ) ? 'active' : ''; ?>" data-mode="grid"><span></span></li>
						<li class="wte-view-mode-selection <?php echo ( $view_mode === 'list' ) ? 'active' : ''; ?>" data-mode="list"><span></span></li>
					</ul>
				</div>

				<?php if ( wptravelengine_toggled( get_option( 'wptravelengine_show_trip_search_sidebar', 'yes' ) ) ) : ?>
					<div class="wp-travel-engine-toolbar wte-filterbar-toggle">
						<button id="wte-filterbar-toggle-btn" class="wte-filterbar-toggle-btn">
							<span class="wte-filterbar-toggle-btn-text"><?php esc_html_e( 'Apply Filters', 'wp-travel-engine' ); ?></span>
							<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
								<path d="M2.5 4.95296H10.4897C10.6046 5.50493 10.9062 6.00047 11.3436 6.3562C11.781 6.71193 12.3276 6.90613 12.8915 6.90613C13.4553 6.90613 14.0019 6.71193 14.4393 6.3562C14.8767 6.00047 15.1783 5.50493 15.2932 4.95296H17.5C17.6326 4.95296 17.7598 4.90028 17.8536 4.80651C17.9473 4.71274 18 4.58557 18 4.45296C18 4.32035 17.9473 4.19317 17.8536 4.0994C17.7598 4.00564 17.6326 3.95296 17.5 3.95296H15.2932C15.1783 3.40098 14.8767 2.90544 14.4393 2.54972C14.0019 2.19399 13.4553 1.99979 12.8915 1.99979C12.3276 1.99979 11.781 2.19399 11.3436 2.54972C10.9062 2.90544 10.6046 3.40098 10.4897 3.95296H2.5C2.36739 3.95296 2.24021 4.00564 2.14645 4.0994C2.05268 4.19317 2 4.32035 2 4.45296C2 4.58557 2.05268 4.71274 2.14645 4.80651C2.24021 4.90028 2.36739 4.95296 2.5 4.95296ZM12.8914 2.99983C13.1788 2.99983 13.4598 3.08506 13.6988 3.24473C13.9377 3.4044 14.124 3.63135 14.2339 3.89687C14.3439 4.16239 14.3727 4.45457 14.3166 4.73645C14.2606 5.01833 14.1222 5.27725 13.919 5.48047C13.7157 5.68369 13.4568 5.82209 13.1749 5.87816C12.893 5.93423 12.6009 5.90545 12.3354 5.79547C12.0698 5.68549 11.8429 5.49924 11.6832 5.26027C11.5235 5.0213 11.4383 4.74036 11.4383 4.45296C11.4387 4.0677 11.592 3.69834 11.8644 3.42592C12.1368 3.1535 12.5062 3.00026 12.8914 2.99983ZM17.5 9.49983H9.73103C9.6161 8.94786 9.31454 8.45232 8.87712 8.09659C8.43969 7.74086 7.89308 7.54666 7.32927 7.54666C6.76545 7.54666 6.21884 7.74086 5.78142 8.09659C5.34399 8.45232 5.04244 8.94786 4.9275 9.49983H2.5C2.36739 9.49983 2.24021 9.55251 2.14645 9.64628C2.05268 9.74005 2 9.86722 2 9.99983C2 10.1324 2.05268 10.2596 2.14645 10.3534C2.24021 10.4472 2.36739 10.4998 2.5 10.4998H4.9275C5.0423 11.0519 5.3438 11.5476 5.78124 11.9034C6.21868 12.2593 6.76536 12.4535 7.32925 12.4535C7.89314 12.4535 8.43982 12.2593 8.87726 11.9034C9.3147 11.5476 9.6162 11.0519 9.731 10.4998H17.5C17.6326 10.4998 17.7598 10.4472 17.8536 10.3534C17.9473 10.2596 18 10.1324 18 9.99983C18 9.86722 17.9473 9.74005 17.8536 9.64628C17.7598 9.55251 17.6326 9.49983 17.5 9.49983ZM7.32925 11.453C7.04185 11.453 6.7609 11.3677 6.52194 11.2081C6.28297 11.0484 6.09672 10.8214 5.98674 10.5559C5.87675 10.2904 5.84798 9.99822 5.90405 9.71634C5.96012 9.43446 6.09851 9.17554 6.30174 8.97232C6.50496 8.76909 6.76388 8.6307 7.04576 8.57463C7.32764 8.51856 7.61981 8.54734 7.88534 8.65732C8.15086 8.7673 8.37781 8.95355 8.53748 9.19252C8.69715 9.43148 8.78237 9.71243 8.78237 9.99983C8.78194 10.3851 8.62871 10.7545 8.35629 11.0269C8.08387 11.2993 7.71451 11.4525 7.32925 11.453ZM17.5 15.0467H13.0057C12.8908 14.4947 12.5893 13.9992 12.1518 13.6435C11.7144 13.2877 11.1678 13.0935 10.604 13.0935C10.0402 13.0935 9.49356 13.2877 9.05613 13.6435C8.61871 13.9992 8.31715 14.4947 8.20222 15.0467H2.5C2.36739 15.0467 2.24021 15.0994 2.14645 15.1932C2.05268 15.2869 2 15.4141 2 15.5467C2 15.6793 2.05268 15.8065 2.14645 15.9003C2.24021 15.994 2.36739 16.0467 2.5 16.0467H8.20222C8.31715 16.5987 8.61871 17.0942 9.05613 17.4499C9.49356 17.8057 10.0402 17.9999 10.604 17.9999C11.1678 17.9999 11.7144 17.8057 12.1518 17.4499C12.5893 17.0942 12.8908 16.5987 13.0057 16.0467H17.5C17.6326 16.0467 17.7598 15.994 17.8536 15.9003C17.9473 15.8065 18 15.6793 18 15.5467C18 15.4141 17.9473 15.2869 17.8536 15.1932C17.7598 15.0994 17.6326 15.0467 17.5 15.0467ZM10.604 16.9998C10.3166 16.9998 10.0357 16.9146 9.7967 16.755C9.55774 16.5953 9.37149 16.3684 9.2615 16.1029C9.1515 15.8374 9.1227 15.5452 9.17874 15.2634C9.23477 14.9815 9.37313 14.7226 9.57631 14.5193C9.77949 14.3161 10.0384 14.1777 10.3202 14.1216C10.6021 14.0654 10.8942 14.0942 11.1598 14.2041C11.4253 14.314 11.6523 14.5002 11.812 14.7391C11.9717 14.978 12.057 15.2589 12.0571 15.5463V15.5471C12.0565 15.9323 11.9033 16.3015 11.6309 16.5739C11.3584 16.8462 10.9892 16.9994 10.604 16.9998Z" fill="currentColor"/>
							</svg>
						</button>
					</div>
				<?php endif; ?>

				<?php
					global $wp_query;
					self::$query    ??= $wp_query;
					$foundpostss      = '<div class="wte-filter-foundposts">';
					$show_found_posts = ! empty( array_diff( array_keys( $_GET ), array( 'view_mode', 'wte_orderby' ) ) );
				if ( $show_found_posts && self::$query ) {
					$foundpostss .= sprintf(
						_nx( '%1$s Trip Found', '%1$s Trips Found', self::$query->found_posts, 'number of trips', 'wp-travel-engine' ),
						'<strong>' . number_format_i18n( self::$query->found_posts ) . '</strong>'
					);
				}
					$foundpostss .= '</div>';
					echo $foundpostss;
				?>
			</div>
		</div>
		<?php
	}

	/**
	 * Header filter section for WP Travel Engine Archives.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function wp_travel_engine_header_filters_template() {
		self::archive_filters_sub_options();
	}

	/**
	 * Hook for the header block ( contains title and description ).
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function wp_travel_engine_archive_header_block() {
		$page_header = apply_filters( 'wte_trip_archive_description_page_header', true );

		$queried_object = get_queried_object();

		$page_title = '';
		if ( $queried_object instanceof \WP_Term ) {
			$page_title = $queried_object->name;
		} elseif ( $queried_object instanceof \WP_Post_Type ) {
			$page_title = $queried_object->label;
		}

		if ( $page_header && ! empty( $page_title ) ) {
			?>
			<header class="page-header">
				<?php
				$settings           = get_option( 'wp_travel_engine_settings', array() );
				$show_archive_title = apply_filters( 'wte_trip_archive_title', false );
				$show_archive_title = ! isset( $settings['hide_term_title'] ) || 'yes' !== $settings['hide_term_title'];
				$archive_title_type = $settings['archive']['title_type'] ?? 'default';
				if ( $show_archive_title && $archive_title_type === 'default' ) {
					echo '<h1 class="page-title" itemprop="name">' . esc_html( $page_title ) . '</h1>';
				} elseif ( $show_archive_title && $archive_title_type === 'custom' ) {
					echo '<h1 class="page-title" itemprop="name">' . get_the_archive_title() . '</h1>';
				}
				$taxonomies = array( 'trip_types', 'destination', 'activities' );
				if ( is_tax( $taxonomies ) ) {
					$image_id       = get_term_meta( get_queried_object()->term_id, 'category-image-id', true );
					$wte_global     = get_option( 'wp_travel_engine_settings', true );
					$show_tax_image = isset( $image_id ) && '' != $image_id
						&& isset( $wte_global['tax_images'] ) ? true : false;
					if ( $show_tax_image ) {
						$tax_banner_size = apply_filters( 'wp_travel_engine_template_banner_size', 'full' );
						echo wp_get_attachment_image( $image_id, $tax_banner_size );
					}
				}

				if ( '' !== get_the_archive_description() && ! is_post_type_archive( WP_TRAVEL_ENGINE_POST_TYPE ) ) {
					the_archive_description( '<div class="taxonomy-description" itemprop="description">', '</div>' );
				}
				?>
			</header><!-- .page-header -->
			<?php
		}
	}

	/**
	 * Main wrap of the archive.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function wp_travel_engine_trip_archive_wrapper() {
		?>
		<div id="wte-crumbs">
			<?php
			do_action( 'wp_travel_engine_breadcrumb_holder' );
			?>
		</div>
		<div id="wp-travel-trip-wrapper" class="trip-content-area" itemscope itemtype="https://schema.org/ItemList">
			<?php
			$header_block = apply_filters( 'wp_travel_engine_archive_header_block_display', true );
			if ( $header_block ) {
				do_action( 'wp_travel_engine_archive_header_block' );
			}
			?>
			<div class="wp-travel-inner-wrapper">
			<?php
	}

	/**
	 * Inner wrap of the archive.
	 *
	 * @since 1.0.0
	 * @return void
	 * @updated 6.6.0
	 */
	public function wp_travel_engine_trip_archive_wrap() {
		if ( ! wp_script_is( 'wte-trip-search', 'enqueued' ) ) {
			TripSearch::enqueue_scripts();
		}

		if ( ! wp_style_is( 'wpte-trip-archive', 'enqueued' ) ) {
			TripSearch::enqueue_styles();
		}

		global $wp_query;
		if ( is_tax( get_object_taxonomies( 'trip', 'names' ) ) ) {
			self::$query = $wp_query;
		} else {
			self::$query = new \WP_Query( TripSearch::get_query_args() );
		}

		$show_sidebar = wptravelengine_toggled( get_option( 'wptravelengine_show_trip_search_sidebar', 'yes' ) );
		?>
		<div class="wp-travel-engine-archive-outer-wrap collapsible-filter-panel">
			<?php
			/**
			 * wp_travel_engine_archive_sidebar hook
			 *
			 * @hooked wte_advanced_search_archive_sidebar - Trip Search addon
			 */
			if ( $show_sidebar ) {
				do_action( 'wp_travel_engine_archive_sidebar' );
			}
			?>
			<div class="wp-travel-engine-archive-repeater-wrap">
				<?php
				/**
				 * Hook - wp_travel_engine_header_filters
				 * Hook for the new archive filters on trip archive page.
				 *
				 * @hooked - wp_travel_engine_header_filters_template.
				 */
				do_action( 'wp_travel_engine_header_filters' );
				?>
				<div class="wte-category-outer-wrap is-loading" data-filter-nonce="<?php echo esc_attr( wp_create_nonce( 'wte_show_ajax_result' ) ); ?>">
					<?php
					$j          = 1;
					$view_mode  = wp_travel_engine_get_archive_view_mode();
					$classes    = apply_filters( 'wte_advanced_search_trip_results_grid_classes', class_exists( 'Wte_Advanced_Search' ) ? ( $show_sidebar ? 'wte-col-2 category-grid' : 'wte-col-3 category-grid' ) : 'wte-col-3 category-grid' );
					$view_class = 'grid' === $view_mode ? $classes : 'category-list';

					echo '<div class="category-main-wrap ' . esc_attr( $view_class ) . '">';

					if ( ! self::$query->have_posts() ) {
						echo apply_filters( 'no_result_found_message', __( 'No results found!', 'wp-travel-engine' ) );
					}

					$user_wishlists = wptravelengine_user_wishlists();
					$template_name  = wptravelengine_get_template_by_view_mode( $view_mode );

					while ( self::$query->have_posts() ) {
						self::$query->the_post();
						$details = wte_get_trip_details( get_the_ID() );
						if ( $details ) {
							$details['j']              = $j;
							$details['user_wishlists'] = $user_wishlists;
							wptravelengine_get_template( $template_name, $details );
							++$j;
						}
					}
					echo '</div>';
					wp_reset_postdata();

					// $show_load_more = !empty( $_GET['wte_orderby'] ?? '' ) && self::$query->found_posts > get_option( 'posts_per_page', 10 );
					$show_load_more = ( get_option( 'wptravelengine_archive_display_mode', 'pagination' ) === 'load_more' ) && self::$query->found_posts > get_option( 'posts_per_page', 10 );

					if ( $show_load_more ) {
						echo '<div data-id="' . esc_attr( self::$query->found_posts ) . '" class="wte-search-load-more"><button data-current-page="' . esc_attr( get_query_var( 'page' ) ?: 1 ) . '" data-max-page="' . esc_attr( self::$query->max_num_pages ) . '" class="load-more-search">' . esc_html__( 'Load More', 'wp-travel-engine' ) . '</button></div>';
					}
					?>
				</div>
				<?php if ( ! $show_load_more ) : ?>
					<div class="trip-pagination" data-max-page="<?php echo esc_attr( self::$query->max_num_pages ); ?>" <?php echo $show_load_more ? 'style="display: none;"' : ''; ?>>
						<?php
							$original_query = $wp_query;
							$wp_query       = self::$query;
							the_posts_pagination(
								array(
									'prev_text'          => esc_html__( 'Previous', 'wp-travel-engine' ),
									'next_text'          => esc_html__( 'Next', 'wp-travel-engine' ),
									'before_page_number' => '<span class="meta-nav screen-reader-text">' . esc_html__( 'Page', 'wp-travel-engine' ) . ' </span>',
								)
							);
							$wp_query = $original_query;
						?>
					</div>
				<?php endif; ?>
			</div>
			<div id="loader" style="display: none">
				<div class="table">
					<div class="table-grid">
						<div class="table-cell">
							<?php wptravelengine_svg_by_fa_icon( 'fas fa-spinner', true, array( 'fa-spin' ) ); ?>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php
	}

	/**
	 * Outer wrap of the archive.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function wp_travel_engine_trip_archive_outer_wrapper_close() {
		?>
		</div><!-- wp-travel-inner-wrapper -->
	</div><!-- .wp-travel-trip-wrapper -->
		<?php
	}
}

new Wp_Travel_Engine_Archive_Hooks();
