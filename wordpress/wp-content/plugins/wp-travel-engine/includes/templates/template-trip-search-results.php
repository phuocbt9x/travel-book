<?php
/**
 * Trip Results Template.
 */
use WPTravelEngine\Modules\TripSearch;

TripSearch::enqueue_assets();
get_header();

if ( 'builder' === get_post_meta( get_the_ID(), '_elementor_edit_mode', true ) ) :
	?>
		<div class="wp-travel-engine-archive-outer-wrap collapsible-filter-panel">
			<?php if ( 'travel-agency' !== get_option( 'template', '' ) ) : ?>
				<div class="page-header">
					<?php echo apply_filters( 'wte-trip-search-page-title', sprintf( '<h1 class="page-title">%1$s</h1>', get_the_title() ) ); ?>
					<div class="page-content">
						<?php
						the_content();
						?>
					</div>
				</div>
			<?php endif; ?>
		</div>
	<?php
else :
	do_action( 'wp_travel_engine_trip_archive_wrap' );
endif;

get_footer();
