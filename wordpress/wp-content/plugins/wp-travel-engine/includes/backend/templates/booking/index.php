<?php
/**
 * Booking Details Metabox Content.
 */

wptravelengine_set_template_args( array( 'is_new_booking' => false ) );
?>

<div id="wptravelengine-booking-details">
	<?php wptravelengine_get_admin_template( 'booking/partials/header.php' ); ?>
	<!-- .wpte-form-container -->
	<div class="wpte-form-container">
		<?php wptravelengine_get_admin_template( 'booking/partials/tab-title.php' ); ?>
		<div class="wpte-booking-details-layout">

			<!-- .wpte-booking-fields-area -->
			<div class="wpte-booking-fields-area">
				<?php
				wptravelengine_get_admin_template( 'booking/partials/booking-info.php' );
				?>

				<div class="wpte-booking-collapsible-content">
					<?php
					wptravelengine_get_admin_template( 'booking/partials/traveller-info.php' );
					wptravelengine_get_admin_template( 'booking/partials/emergency-contact.php' );
					do_action( 'wptravelengine_booking_details_forms', $booking );
					do_action( 'wptravelengine_booking_details_line_items', $booking );
					// discounts
					do_action( 'wptravelengine_booking_details_discounts', $booking );
					wptravelengine_get_admin_template( 'booking/partials/payment-details.php' );
					wptravelengine_get_admin_template( 'booking/partials/billing-details.php' );
					wptravelengine_get_admin_template( 'booking/partials/additional-field.php' );
					wptravelengine_get_admin_template( 'booking/partials/admin-notes.php' );
					do_action( 'wptravelengine_booking_details_additional_field', $booking );
					?>

				</div>
			</div> <!-- end .wpte-booking-fields-area -->

			<!-- .wpte-booking-summary-area -->
			<div class="wpte-booking-summary-area">
				<?php wptravelengine_get_admin_template( 'booking/partials/booking-summary.php' ); ?>
				<?php wptravelengine_get_admin_template( 'booking/partials/remaining-payment.php' ); ?>
				<?php wptravelengine_get_admin_template( 'booking/partials/purchase-receipt.php' ); ?>
				<?php do_action( 'wptravelengine_booking_details_sidebar', $booking ); ?>
				<?php
				// If no action is registered, show the commission template from the core plugin.
				if ( ! has_action( 'wptravelengine_booking_details_sidebar' ) ) {
					wptravelengine_get_admin_template( 'booking/partials/commission.php' );
				}
				?>
			</div> <!-- end .wpte-booking-summary-area -->
		</div>
	</div> <!-- end .wpte-form-container -->

	<?php
	/**
	 * Hooks for Addons.
	 */
	do_action( 'wp_travel_engine_booking_screen_after_personal_details', $booking->ID );
	?>
</div>