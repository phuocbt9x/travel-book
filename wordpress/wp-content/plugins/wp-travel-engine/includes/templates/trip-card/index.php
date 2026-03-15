<?php

use WPTravelEngine\Modules\TripSearch;
/**
 * @var \WPTravelEngine\Core\Models\Post\Trip $trip_instance
 */

if ( ! isset( $trip_instance ) ) {
	global $post;
	if ( ! $related_query ) {
		TripSearch::enqueue_assets();
	}
	$all_args                   = wte_get_trip_details( $post->ID );
	$all_args['user_wishlists'] = wptravelengine_user_wishlists();
	foreach ( $all_args as $key => $value ) {
		wptravelengine_set_template_args( array( $key => $value ) );
	}
}

?>
<?php wptravelengine_get_template( 'trip-card/card-image.php' ); ?>
<div class="category-trip-content-wrap">
	<div class="category-trip-detail-wrap">
		<?php wptravelengine_get_template( 'trip-card/card-body.php' ); ?>
	</div>
	<div class="category-trip-budget">
		<?php wptravelengine_get_template( 'trip-card/card-aside.php' ); ?>
	</div>
</div>
<?php wptravelengine_get_template( 'trip-card/card-footer.php' ); ?>