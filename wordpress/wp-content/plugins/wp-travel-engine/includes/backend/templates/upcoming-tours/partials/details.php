<?php

/**
 * Upcoming Tours Details
 *
 * @since 6.4.3
 *
 * @var array $trip_details
 * @var array $bookings
 */
?>

<div class="wpte-upcoming-tours-details">
	<div class="wpte-upcoming-tours-details-header">
		<span class="wpte-upcoming-tours-details-title"><?php _e( 'Tour Details', 'wp-travel-engine' ); ?></span>
		<span class="wpte-upcoming-tours-details-close-btn">
			<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
				<path d="M18 6L6 18" stroke="#859094" stroke-width="1.33333" stroke-linecap="round" stroke-linejoin="round" />
				<path d="M6 6L18 18" stroke="#859094" stroke-width="1.33333" stroke-linecap="round" stroke-linejoin="round" />
			</svg>
		</span>
	</div>
	<div class="wpte-upcoming-tours-details-content">
		<div class="wpte-upcoming-tours-details-title-area">
			<div class="wpte-upcoming-tours-details-content-image">
			<?php if ( ! empty( $trip_details['image'] ) ) : ?>
				<img src="<?php echo esc_url( $trip_details['image'] ); ?>" alt="<?php echo esc_attr( $trip_details['title'] ); ?>">
				<?php endif; ?>
			</div>
			<h2 class="wpte-trip-title"><?php echo esc_html( $trip_details['title'] ); ?></h2>
		</div>
		<div class="wpte-upcoming-tours-details-content-info">
			<div class="wpte-trip-meta">
				<div class="wpte-meta-item">
					<span class="wpte-meta-label"><?php _e( 'Duration:', 'wp-travel-engine' ); ?></span>
					<span class="wpte-meta-value"><?php echo esc_html( $trip_details['duration'] ); ?></span>
				</div>
				<div class="wpte-meta-item">
					<span class="wpte-meta-label"><?php _e( 'Starts on:', 'wp-travel-engine' ); ?></span>
					<span class="wpte-meta-value"><?php echo esc_html( $trip_details['start_date'] ); ?></span>
				</div>
				<div class="wpte-meta-item">
					<span class="wpte-meta-label"><?php _e( 'Ends on:', 'wp-travel-engine' ); ?></span>
					<span class="wpte-meta-value"><?php echo esc_html( $trip_details['end_date'] ); ?></span>
				</div>
				<div class="wpte-meta-item">
					<span class="wpte-meta-label"><?php _e( 'No. of Travellers:', 'wp-travel-engine' ); ?></span>
					<span class="wpte-meta-value"><?php echo esc_html( $trip_details['travellers'] ); ?></span>
				</div>
			</div>

			<div class="wpte-traveller-details">
				<h3><?php _e( 'Traveller(s) Details:', 'wp-travel-engine' ); ?></h3>
				<div class="wpte-traveller-list">
					<?php foreach ( $bookings as $booking ) : ?>
						<div class="wpte-traveller-item">
							<span class="wpte-traveller-name"><?php echo esc_html( $booking['billing_info'] ); ?></span>
							<span class="wpte-traveller-count"><?php echo esc_html( $booking['travellers'] ); ?> <?php _e( 'Travellers', 'wp-travel-engine' ); ?></span>
							<a href="<?php echo esc_url( get_edit_post_link( $booking['id'], 'display' ) ); ?>" target="_blank" class="wpte-details-link"><?php _e( 'Details', 'wp-travel-engine' ); ?></a>
						</div>
					<?php endforeach; ?>
				</div>
			</div>
			<div class="wpte-trip-total">
				<span class="wpte-total-label"><?php _e( 'Total', 'wp-travel-engine' ); ?></span>
				<span class="wpte-total-amount"><?php echo esc_html( $trip_details['total'] ); ?></span>
			</div>
		</div>
	</div>
</div>