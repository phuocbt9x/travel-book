<?php
/**
 * @var Booking $booking
 * @since 6.4.0
 */

use WPTravelEngine\Core\Models\Post\Booking;

?>
<div class="wpte-field">
	<label for="booking_status"><?php echo __( 'Booking Status', 'wp-travel-engine' ); ?></label>
	<select name="wp_travel_engine_booking_status" id="booking_status">
		<?php foreach ( wp_travel_engine_get_booking_status() as $value => $label ) : ?>
			<option value="<?php echo esc_attr( $value ); ?>" name="booking_status"
				<?php selected( $value, $booking->get_booking_status() ); ?>>
				<?php echo esc_html( $label['text'] ); ?>
			</option>
		<?php endforeach; ?>
	</select>
</div>
