<?php
/**
 * Booking Tab.
 *
 * @package wp-travel-engine/includes/templates/account/tab-content/
 */
wp_enqueue_script( 'jquery-fancy-box' );
$bookings = $args['bookings'];
$booking  = 0;

global $wp, $wte_cart;
$settings                      = wptravelengine_settings()->get();
$wp_travel_engine_dashboard_id = isset( $settings['pages']['wp_travel_engine_dashboard_page'] ) ? esc_attr( $settings['pages']['wp_travel_engine_dashboard_page'] ) : wp_travel_engine_get_page_id( 'my-account' );

// Verify booking access: check if booking exists and belongs to logged-in user (by billing email).
$invalid_booking = false;
$booking_not_found = false;
if ( ! empty( $_GET['booking_id'] ) ) { // phpcs:ignore
	$booking_id = absint( $_GET['booking_id'] );
	$booking_post = get_post( $booking_id );
	
	if ( ! $booking_post || 'booking' !== $booking_post->post_type ) {
		$booking_not_found = true;
	} elseif ( is_user_logged_in() ) {
		try {
			$booking_email = ( new \WPTravelEngine\Core\Models\Post\Booking( $booking_id ) )->get_billing_email();
			$is_owner = ! empty( $booking_email ) && $booking_email === ( wp_get_current_user()->user_email ?? '' );
			// Fallback: Check if booking is in user's booking array (legacy support)
			if ( ! $is_owner && ! empty( $bookings ) && is_array( $bookings ) && in_array( $booking_id, $bookings, true ) ) {
				$is_owner = true;
			}
			$booking = $is_owner ? $booking_id : 0;
			$invalid_booking = ! $is_owner;
		} catch ( \Exception $e ) {
			error_log( sprintf( 'Booking access check failed for booking %d: %s', $booking_id, $e->getMessage() ) );
			$invalid_booking = true;
		}
	} else {
		$invalid_booking = true;
	}
}

?>
	<div class="wpte-lrf-block-wrap">
		<div class="wpte-lrf-block">
			<?php
			if ( $booking_not_found ) : ?>
				<div class="wpte-error-message wpte-booking-not-found">
					<h5 class="wpte-error-title"><?php esc_html_e( 'Booking Not Found', 'wp-travel-engine' ); ?></h5>
					<?php esc_html_e( 'We couldn\'t find a booking with that ID. Please check the URL, verify your booking reference number, or return to your dashboard.', 'wp-travel-engine' ); ?>
				</div>
			<?php elseif ( $invalid_booking ) : ?>
				<div class="wpte-error-message wpte-access-denied">
					<h5 class="wpte-error-title"><?php esc_html_e( 'Access Denied', 'wp-travel-engine' ); ?></h5>
					<?php esc_html_e( 'You do not have permission to view this booking. Please ensure you are logged into the account associated with this booking, or contact support if you believe this is an error.', 'wp-travel-engine' ); ?>
				</div>
			<?php elseif ( ! empty( $bookings ) && isset( $_GET[ 'action' ] ) && wte_clean( wp_unslash( $_GET[ 'action' ] ) ) == 'partial-payment' ) : // phpcs:ignore
				wte_get_template(
					'account/remaining-payment.php',
					array(
						'booking' => $booking,
					)
				);
			elseif ( ! empty( $bookings ) && isset( $_GET[ 'action' ] ) && wte_clean( wp_unslash( $_GET[ 'action' ] ) ) == 'booking-details' ) : // phpcs:ignore
				wte_get_template(
					'account/booking-details.php',
					array(
						'booking' => $booking,
					)
				);
			elseif ( ! empty( $bookings ) && ! isset( $_GET['action'] ) ) :
				?>
				<div class="wpte-bookings-tabmenu">
					<?php

					foreach ( $bookings_dashboard_menus as $key => $menu ) :
						?>
						<?php
						if ( $menu['menu_class'] == 'wpte-active-bookings' ) {
							$booking_menu_active_class = 'active';
						} else {
							$booking_menu_active_class = '';
						}
						?>
						<a class="wpte-booking-menu-tab <?php echo esc_attr( $menu['menu_class'] ); ?> <?php echo esc_attr( $booking_menu_active_class ); ?>"
							href="Javascript:void(0);"><?php echo esc_html( $menu['menu_title'] ); ?></a>
					<?php endforeach; ?>
				</div>
				<div class="wpte-booking-tab-main">
					<?php foreach ( $bookings_dashboard_menus as $key => $menu ) : ?>
						<?php
						if ( $menu['menu_class'] == 'wpte-active-bookings' ) {
							$booking_menu_active_class = 'active';
						} else {
							$booking_menu_active_class = '';
						}
						?>
						<div
							class="wpte-booking-tab-content wpte-<?php echo esc_attr( $key ); ?>-bookings-content <?php echo esc_attr( $menu['menu_class'] ); ?> <?php echo esc_attr( $booking_menu_active_class ); ?>">
							<?php
							if ( ! empty( $menu['menu_content_cb'] ) ) {
								$args['bookings_glance']    = $bookings_glance;
								$args['biling_glance_data'] = $biling_glance_data;
								$args['bookings']           = $bookings;
								call_user_func( $menu['menu_content_cb'], $args );
							}
							?>
						</div>
					<?php endforeach; ?>
				</div>
				<?php
			else :
				esc_html_e( 'You haven\'t booked any trip yet.', 'wp-travel-engine' );
			endif;
			?>
		</div>
	</div>
<?php
