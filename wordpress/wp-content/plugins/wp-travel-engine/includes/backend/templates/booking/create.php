<?php
/**
 * Booking Details Metabox Content.
 */

if ( ! current_user_can( 'edit_posts' ) ) {
	wp_die( __( 'Unauthorized access', 'wp-travel-engine' ) );
}

$date_picker_config = array(
	'enableTime' => true,
	'dateFormat' => 'Y-m-d H:i',
);

wptravelengine_set_template_args( array( 'is_new_booking' => true ) );
?>
<!-- Traveller Delete Confirmation Modal -->
<div id="wpte-delete-traveller-confirm-modal" class="wpte-confirm-modal-overlay">
	<div class="wpte-confirm-modal">
		<button type="button" class="wpte-button wpte-cancel wpte-close-modal">
			<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
				<path d="M18 6L6 18M6 6L18 18" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
			</svg>
		</button>
		<div class="wpte-confirm-message">
			<h5><?php esc_html_e( 'Are you sure you want to delete this traveller?', 'wp-travel-engine' ); ?></h5>
			<p><?php esc_html_e( 'This action cannot be undone. It will permanently remove this traveller from the booking.', 'wp-travel-engine' ); ?></p>
		</div>
		<div class="wpte-button-group">
			<button type="button" class="wpte-button wpte-solid wpte-traveller-delete">
				<?php esc_html_e( 'Delete Traveller', 'wp-travel-engine' ); ?>
			</button>
			<button type="button" class="wpte-button wpte-outlined wpte-cancel">
				<?php esc_html_e( 'Cancel', 'wp-travel-engine' ); ?>
			</button>
		</div>
	</div>
</div>
<!-- Back Button Confirmation Modal -->
<div id="wpte-back-button-confirm-modal" class="wpte-confirm-modal-overlay">
	<div class="wpte-confirm-modal">
		<button type="button" class="wpte-button wpte-cancel wpte-close-modal">
			<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
				<path d="M18 6L6 18M6 6L18 18" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
			</svg>
		</button>
		<div class="wpte-confirm-message">
			<h5><?php esc_html_e( 'Are you sure you want to go back to the Bookings page?', 'wp-travel-engine' ); ?></h5>
			<p><?php esc_html_e( 'The booking details created will be lost.', 'wp-travel-engine' ); ?></p>
		</div>
		<div class="wpte-button-group">
			<button type="button" class="wpte-button wpte-solid wpte-back-confirm">
				<?php esc_html_e( 'Confirm', 'wp-travel-engine' ); ?>
			</button>
			<button type="button" class="wpte-button wpte-outlined wpte-cancel">
				<?php esc_html_e( 'Cancel', 'wp-travel-engine' ); ?>
			</button>
		</div>
	</div>
</div>
<div id="wptravelengine-booking-details">
	<?php wptravelengine_get_admin_template( 'booking/partials/header.php' ); ?>
	<div class="wpte-form-container">
		<?php wptravelengine_get_admin_template( 'booking/partials/tab-title.php' ); ?>
		<div class="wpte-booking-details-layout">

			<!-- .wpte-booking-fields-area -->
			<div class="wpte-booking-fields-area">
				<?php wptravelengine_get_admin_template( 'booking/partials/booking-info.php' ); ?>
				<div class="wpte-booking-collapsible-content">
					<?php
					wptravelengine_get_admin_template( 'booking/partials/traveller-info.php' );
					wptravelengine_get_admin_template( 'booking/partials/emergency-contact.php' );
					do_action( 'wptravelengine_booking_details_edit_forms', $booking );
					do_action( 'wptravelengine_booking_details_edit_line_items', $booking );
					wptravelengine_get_admin_template( 'booking/partials/payment-details.php' );
					wptravelengine_get_admin_template( 'booking/partials/billing-details.php' );
					wptravelengine_get_admin_template( 'booking/partials/additional-field.php' );
					wptravelengine_get_admin_template( 'booking/partials/admin-notes.php' );
					?>
				</div>
				<!-- end booking-detail-fields -->
			</div>

			<div class="wpte-booking-summary-area">
				<?php wptravelengine_get_admin_template( 'booking/partials/edit/booking-summary.php' ); ?>

				<?php wptravelengine_get_admin_template( 'booking/partials/edit/booking-status.php' ); ?>
				<?php do_action( 'wptravelengine_booking_details_edit_sidebar', $booking ); ?>
			</div>
			<!-- Create nonce for booking creation -->
			<input type="hidden" name="wptravelengine_new_booking_nonce" value="<?php echo wp_create_nonce( 'wptravelengine_new_booking' ); ?>">
			<input type="hidden" name="wptravelengine_cart_version" value="<?php echo esc_attr( $cart_info->version ); ?>">
		</div>
	</div>
</div>