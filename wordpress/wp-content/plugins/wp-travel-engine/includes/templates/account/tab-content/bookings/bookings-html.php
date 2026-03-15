<?php

/**
 * Renders Upcoming Trips & Bookings History Tab.
 *
 * @since 6.0.0
 * @package wp-travel-engine/includes/templates/account/tab-content/bookings
 */
$map = array(
	__( 'Partially Paid', 'wp-travel-engine' ) => 'partially-paid',
	__( 'Pending', 'wp-travel-engine' )        => 'pending',
	__( 'Paid', 'wp-travel-engine' )           => 'paid',
);

?>
<div class="wpte-booked-trip-wrap">
	<div class="wpte-booked-trip-image">
		<?php
		if ( has_post_thumbnail( $booking_instance->get_trip_id() ) ) {
			echo get_the_post_thumbnail( $booking_instance->get_trip_id() );
		} else {
			?>
			<img alt="<?php the_title(); ?>" itemprop="image" src="<?php echo esc_url( WP_TRAVEL_ENGINE_IMG_URL . '/public/css/images/single-trip-featured-img.jpg' ); ?>" alt="">
			<?php
		}
		?>
	</div>
	<div class="wpte-booked-trip-content">
		<div class="wpte-booked-trip-description-left">
			<div class="wpte-booked-trip-title">
				<?php echo esc_html( $booking_instance->get_trip_title() ); ?>
			</div>
			<div class="wpte-booked-trip-descriptions">
				<ul>
					<li>
						<span class="lrf-td-title"><?php esc_html_e( 'Departure:', 'wp-travel-engine' ); ?></span>
						<span class="lrf-td-desc"><?php echo esc_html( date_i18n( get_option( 'date_format' ), strtotime( $trip_date ) ) ); ?></span>
					</li>
					<li>
						<span class="lrf-td-title"><?php esc_html_e( 'Payment Status:', 'wp-travel-engine' ); ?></span>
						<span class="lrf-td-desc wpte-<?php echo esc_attr( $map[ $payment_status ] ?? 'paid' ); ?>"><?php echo esc_html( $payment_status ); ?></span>
					</li>
					<li>
						<span class="lrf-td-title"><?php printf( __( 'Total Amount:%s', 'wp-travel-engine' ), $booking_instance->is_curr_cart() ? __( '<br>(excl. extra fees)', 'wp-travel-engine' ) : '' ); ?></span>
						<span
							class="lrf-td-desc"><?php wptravelengine_the_price( $booking_instance->get_total() ?? 0, true, compact( 'currency_code' ) ); ?></span>
					</li>
					<li>
						<span class="lrf-td-title"><?php esc_html_e( 'Amount Paid:', 'wp-travel-engine' ); ?></span>
						<span
							class="lrf-td-desc"><?php wptravelengine_the_price( $total_paid, true, compact( 'currency_code' ) ); ?></span>
					</li>
					<li>
						<span class="lrf-td-title"><?php esc_html_e( 'Amount Due:', 'wp-travel-engine' ); ?></span>
						<span
							class="lrf-td-desc"><?php wptravelengine_the_price( $due, true, compact( 'currency_code' ) ); ?></span>
					</li>
				</ul>
			</div>
		</div>
		<div class="wpte-booked-trip-buttons-right">
			<a class="wpte-lrf-btn-transparent wpte-detail-btn" href="<?php echo esc_url( get_the_permalink() . '?action=booking-details&booking_id=' . $booking_instance->ID . '"' ); ?>"><?php esc_html_e( 'View Details', 'wp-travel-engine' ); ?></a>
		</div>
	</div>
	<?php
		$payment_link = $booking_instance->get_due_payment_link();
	if ( $show_pay_now_btn && 'history' !== $type && ! empty( $payment_link ) ) {
		?>
		<div class="wpte-ud-message wpte-warning">
		<?php if ( $due > 0 && $total_paid > 0 ) { ?>
				<p><?php printf( esc_html__( 'Due %1$s needs to be paid.', 'wp-travel-engine' ), '<strong>' . wptravelengine_the_price( $due, false, compact( 'currency_code' ) ) . '</strong>' ); ?></p>
			<?php } ?>
			<a href="<?php echo esc_url( $payment_link ); ?>"><?php esc_html_e( 'Pay Now', 'wp-travel-engine' ); ?></a>
		<?php if ( $total_paid == 0 ) { ?>
				<p><?php esc_html_e( 'to confirm your booking.', 'wp-travel-engine' ); ?></p>
			<?php } ?>
		</div>
	<?php } ?>
</div>