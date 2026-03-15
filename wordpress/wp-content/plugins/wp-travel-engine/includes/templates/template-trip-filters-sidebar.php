<?php

use WPTravelEngine\Modules\TripSearch;

/**
 * Archive Filters Sidebar.
 *
 * @since __addonmigration__
 */
// wp_enqueue_style('wte-nouislider');
$filters     = TripSearch::get_filters_sections();
$settings    = get_option( 'wp_travel_engine_settings', array() );
$trip_search = $settings['trip_search'] ?? array();
?>
<div class='advanced-search-wrapper' id="wte__trip-search-filters" data-filter-nonce="<?php echo esc_attr( wp_create_nonce( 'wte_show_ajax_result' ) ); ?>">
	<button id="wte-filterbar-close-btn" class="wte-filterbar-close-btn" type="button">
		<svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
			<path d="M9.87992 8.00009L15.6133 2.28008C15.8643 2.02901 16.0054 1.68849 16.0054 1.33342C16.0054 0.978349 15.8643 0.637823 15.6133 0.386751C15.3622 0.13568 15.0217 -0.00537109 14.6666 -0.00537109C14.3115 -0.00537109 13.971 0.13568 13.7199 0.386751L7.99992 6.12009L2.27992 0.386751C2.02885 0.13568 1.68832 -0.0053711 1.33325 -0.00537109C0.978183 -0.00537109 0.637657 0.13568 0.386585 0.386751C0.135514 0.637823 -0.00553703 0.978349 -0.00553703 1.33342C-0.00553704 1.68849 0.135514 2.02901 0.386585 2.28008L6.11992 8.00009L0.386585 13.7201C0.261614 13.844 0.162422 13.9915 0.0947304 14.154C0.0270388 14.3165 -0.0078125 14.4907 -0.0078125 14.6668C-0.0078125 14.8428 0.0270388 15.017 0.0947304 15.1795C0.162422 15.342 0.261614 15.4895 0.386585 15.6134C0.510536 15.7384 0.658004 15.8376 0.820483 15.9053C0.982962 15.973 1.15724 16.0078 1.33325 16.0078C1.50927 16.0078 1.68354 15.973 1.84602 15.9053C2.0085 15.8376 2.15597 15.7384 2.27992 15.6134L7.99992 9.88009L13.7199 15.6134C13.8439 15.7384 13.9913 15.8376 14.1538 15.9053C14.3163 15.973 14.4906 16.0078 14.6666 16.0078C14.8426 16.0078 15.0169 15.973 15.1794 15.9053C15.3418 15.8376 15.4893 15.7384 15.6133 15.6134C15.7382 15.4895 15.8374 15.342 15.9051 15.1795C15.9728 15.017 16.0077 14.8428 16.0077 14.6668C16.0077 14.4907 15.9728 14.3165 15.9051 14.154C15.8374 13.9915 15.7382 13.844 15.6133 13.7201L9.87992 8.00009Z" fill="currentColor" />
		</svg>
	</button>
	<div class="sidebar">
		<div class="advanced-search-header">
			<h2><?php echo esc_html( apply_filters( 'wte_advanced_filterby_title', __( 'Filter By', 'wp-travel-engine' ) ) ); ?></h2>
			<button class="clear-search-criteria" id="reset-trip-search-criteria" style="display: none;"><?php esc_html_e( 'Clear all', 'wp-travel-engine' ); ?></button>
		</div>
		<?php
		if ( is_array( $filters ) ) {
			foreach ( $filters as $filter ) {
				if ( ! empty( $trip_search['apply_in_search_page'] ?? '1' ) || ( $filter['show'] ?? true ) ) {
					call_user_func( $filter['render'], $filter );
				}
			}
		}

		if ( ! wptravelengine_toggled( $trip_search['dates'] ?? false ) ) {
			do_action( 'wte_departure_date_dropdown', true );
		}
		?>
	</div>
</div>