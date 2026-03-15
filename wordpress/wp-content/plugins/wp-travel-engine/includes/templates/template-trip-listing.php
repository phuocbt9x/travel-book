<?php
/**
 * The template for displaying trips trip listing page
 *
 * @package Wp_Travel_Engine
 * @subpackage Wp_Travel_Engine/includes/templates
 * @since 1.0.0
 */
use WPTravelEngine\Modules\TripSearch;
TripSearch::enqueue_assets();

get_header();
$active_theme = get_option( 'template', '' );
?>
	<div id="wte-crumbs">
		<?php
		do_action( 'wp_travel_engine_breadcrumb_holder' );
		?>
	</div>
<?php
$wte_trip_tax_post_args = array(
	'post_type'      => 'trip',
	'posts_per_page' => - 1,
	'order'          => apply_filters( 'wpte_trip_listing_order', 'DESC' ),
	'orderby'        => apply_filters( 'wpte_trip_listing_order_by', 'date' ),
	'_trip_sort'     => get_option( 'wptravelengine_trip_sort_by', 'latest' ),
);

$wte_orderby = array(
	'latest'     => array(
		'order'   => 'DESC',
		'orderby' => 'date',
	),
	'rating'     => array(
		'order'   => 'DESC',
		'orderby' => 'comment_count',
	),
	'price'      => array(
		'meta_key' => 'wp_travel_engine_setting_trip_actual_price',
		'order'    => 'ASC',
		'orderby'  => 'meta_value_num',
	),
	'price-desc' => array(
		'meta_key' => 'wp_travel_engine_setting_trip_actual_price',
		'order'    => 'DESC',
		'orderby'  => 'meta_value_num',
	),
	'days'       => array(
		'meta_key' => 'wp_travel_engine_setting_trip_duration',
		'order'    => 'DESC',
		'orderby'  => 'meta_value_num',
	),
	'days-desc'  => array(
		'meta_key' => 'wp_travel_engine_setting_trip_duration',
		'order'    => 'DESC',
		'orderby'  => 'meta_value_num',
	),
	'name'       => array(
		'order'   => 'ASC',
		'orderby' => 'title',
	),
	'name-desc'  => array(
		'order'   => 'DESC',
		'orderby' => 'title',
	),
);

// Use of existing function to get sort by and sort args.
$sort_by   = Wp_Travel_Engine_Archive_Hooks::archive_sort_by();
$sort_args = Wp_Travel_Engine_Archive_Hooks::get_query_args_by_sort( $sort_by );

if ( isset( $sort_args['order'] ) ) {
	$wte_trip_tax_post_args['order'] = $sort_args['order'];
}
if ( isset( $sort_args['meta_key'] ) ) {
	$wte_trip_tax_post_args['meta_key'] = $sort_args['meta_key'];
}
if ( isset( $sort_args['orderby'] ) ) {
	$wte_trip_tax_post_args['orderby'] = $sort_args['orderby'];
}

if ( ! empty( $_GET['wte_orderby'] ) && isset( $wte_orderby[ $_GET['wte_orderby'] ] ) ) {
	$get_wte_order_by = $wte_orderby[ wte_clean( wp_unslash( $_GET['wte_orderby'] ) ) ];
	$wte_trip_tax_post_args;
	if ( isset( $get_wte_order_by['meta_key'] ) ) {
		$wte_trip_tax_post_args['meta_key'] = $get_wte_order_by['meta_key'];
	}
	$wte_trip_tax_post_args['order']   = $get_wte_order_by['order'];
	$wte_trip_tax_post_args['orderby'] = $get_wte_order_by['orderby'];
}

$options = get_option( 'wp_travel_engine_settings', array() );
if ( isset( $options['reorder']['flag'] ) ) {
	$wte_trip_tax_post_args['order']   = 'ASC';
	$wte_trip_tax_post_args['orderby'] = 'menu_order';
}

if ( defined( 'WTE_FIXED_DEPARTURE_VERSION' ) && in_array(
	'dates',
	array(
		$_GET['wte_orderby'] ?? '',
		$wte_trip_tax_post_args['_trip_sort'],
	)
) ) {
	$post__in            = WTE_Fixed_Starting_Dates_Functions::get_indexed_trip_by_dates();
	$trips_without_dates = array();

	foreach ( $post__in as $trip_id => $timestamp ) {
		if ( is_numeric( $timestamp ) ) {
			continue;
		}
		unset( $post__in[ $trip_id ] );
		if ( ! isset( $global_settings['hide_trips_without_dates'] ) || 'yes' !== $global_settings['hide_trips_without_dates'] ) {
			$trips_without_dates[ $trip_id ] = $timestamp;
		}
	}
	uksort(
		$post__in,
		function ( $key1, $key2 ) use ( $post__in ) {
			$value1 = $post__in[ $key1 ];
			$value2 = $post__in[ $key2 ];

			if ( $value1 == $value2 ) {
				return 0;
			}

			return ( $value1 < $value2 ) ? - 1 : 1;
		}
	);
	$wte_trip_tax_post_args['orderby']  = 'post__in';
	$wte_trip_tax_post_args['post__in'] = array_merge( array_keys( $trips_without_dates ), array_keys( $post__in ) );
}

\Wp_Travel_Engine_Archive_Hooks::$query = $wte_trip_tax_post_qry = new WP_Query( $wte_trip_tax_post_args );
global $post;
if ( $wte_trip_tax_post_qry->have_posts() ) :
	$show_sidebar = wptravelengine_toggled( get_option( 'wptravelengine_show_trip_search_sidebar', 'yes' ) );
	?>

	<div id="wp-travel-trip-wrapper" class="trip-content-area container" itemscope itemtype="https://schema.org/ItemList">
		<?php if ( 'travel-agency' !== $active_theme ) : ?>
			<div class="page-header">
				<?php the_title( '<h1 class="page-title">', '</h1>' ); ?>
				<div class="page-feat-image">
					<?php
					$image_id               = get_post_thumbnail_id( $post->ID );
					$activities_banner_size = apply_filters( 'wp_travel_engine_template_banner_size', 'full' );
					echo wp_get_attachment_image( $image_id, $activities_banner_size );
					?>
				</div>
				<div class="page-content">
					<p>
						<?php
						$content = apply_filters( 'the_content', $post->post_content );
						echo wp_kses_post( $content );
						?>
					</p>
				</div>
			</div>
		<?php endif; ?>
		<div class="wp-travel-inner-wrapper">
			<div class="wp-travel-engine-archive-outer-wrap">
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
					<div class="wte-category-outer-wrap">
						<?php
						$j         = 1;
						$view_mode = wp_travel_engine_get_archive_view_mode();
						if ( 'grid' === $view_mode ) {
							$show_sidebar = wptravelengine_toggled( get_option( 'wptravelengine_show_trip_search_sidebar', 'yes' ) );
							$view_class   = class_exists( 'Wte_Advanced_Search' ) ? ( $show_sidebar ? 'wte-col-2 category-grid' : 'wte-col-3 category-grid' ) : 'wte-col-3 category-grid';
						} else {
							$view_class = 'category-list';
						}
						echo '<div class="category-main-wrap ' . esc_attr( $view_class ) . '">';
						$user_wishlists = wptravelengine_user_wishlists();
						$template_name  = wptravelengine_get_template_by_view_mode( $view_mode );

						while ( $wte_trip_tax_post_qry->have_posts() ) :
							$wte_trip_tax_post_qry->the_post();
							$details                   = wte_get_trip_details( get_the_ID() );
							$details['j']              = $j;
							$details['user_wishlists'] = $user_wishlists;

							wptravelengine_get_template( $template_name, $details );
							++$j;
						endwhile;
						wp_reset_postdata();
						echo '</div>';
						?>
					</div>
					<div id="loader" style="display: none">
						<div class="table">
							<div class="table-grid">
								<div class="table-cell">
									<i class="fa fa-spinner fa-spin" aria-hidden="true"></i>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php
endif;
get_footer();
