<?php

/**
 * Template for related trips.
 *
 * @since __next_version__
 */
$section_title          ??= __( 'Related trips you might interested in', 'wp-travel-engine' );
$settings                 = wptravelengine_settings()->get();
$related_new_trip_listing = wptravelengine_toggled( $settings['related_display_new_trip_listing'] ?? false );

?>
<div class="wte-related-trips-wrapper">
	<h2 class="wte-related-trips__heading"><?php echo esc_html( $section_title ); ?></h2>
	<div class="wte-related-trips category-grid wte-col-3" style="--gap: 20px;">
		<?php
		$user_wishlists = wptravelengine_user_wishlists();
		while ( $related_trips->have_posts() ) {
			$related_trips->the_post();
			$details                   = wte_get_trip_details( get_the_ID() );
			$details['related_query']  = true;
			$details['user_wishlists'] = $user_wishlists;

			if ( $related_new_trip_listing ) {
				wptravelengine_get_template( 'content-related-trip.php', $details );
			} else {
				wptravelengine_get_template( 'content-related-trip-default.php', $details );
			}
		}
		wp_reset_postdata();
		?>
	</div>
</div>