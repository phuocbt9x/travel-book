<?php
/**
 * Trip Card Aside
 *
 * @var WPTravelEngine\Core\Models\Post\Trip $trip_instance
 */

global $post;
$set_duration_type ??= 'both';
$duration_label      = wptravelengine_get_trip_duration_arr( $trip_instance ?? $post, $set_duration_type );

?>
<div class="wpte-trip-price-wrapper">
	<?php if ( ! empty( $duration_label ) ) : ?>
		<div class="wpte-trip-duration">
			<?php echo esc_html__( 'Duration', 'wp-travel-engine' ); ?>
			<?php
			if ( false !== $trip_duration ) {
				?>
					<span class="wpte-trip-duration-value">
					<?php wptravelengine_get_template( 'components/content-trip-card-duration.php', array( 'is_booking_detail' => true ) ); ?>
					</span>
					<?php
			}
			?>
		</div>
	<?php endif; ?>
	<?php
	if ( ! empty( $trip_instance->get_price() ) ) {
		wptravelengine_get_template( 'trip-card/components/card-price.php' );
	}
	?>
</div>
<?php $fsds = apply_filters( 'trip_card_fixed_departure_dates', $trip_id ); ?>
<div class="wpte-button-group">
	<a href="<?php echo esc_url( get_the_permalink() ); ?>" class="wpte-button">
		<?php echo esc_html__( 'View Details', 'wp-travel-engine' ); ?>
	</a>
	<?php do_action( 'wp_travel_engine_download_pdf_button' ); ?>
	<?php if ( ! $has_date ) : ?>
		<a href="#" class="wpte-button wpte-button-disabled" disabled>
			<?php echo esc_html__( 'Sold Out', 'wp-travel-engine' ); ?>
		</a>
	<?php endif; ?>
</div>
<?php

wptravelengine_get_template( 'trip-card/components/card-fsd-details.php', compact( 'fsds' ) );