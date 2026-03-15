<?php
/**
 * Booking Details Metabox Content.
 */
?>
<div id="wptravelengine-booking-details">
	<?php wptravelengine_get_admin_template( 'booking/legacy/partials/header.php' ); ?>
	<!-- .wpte-form-container -->
	<div class="wpte-form-container">
		<?php wptravelengine_get_admin_template( 'booking/legacy/partials/tab-title.php' ); ?>
		<div class="wpte-booking-details-layout">

			<!-- .wpte-booking-fields-area -->
			<div class="wpte-booking-fields-area">
				<?php
				wptravelengine_get_admin_template( 'booking/legacy/partials/booking-info.php' );
				?>

				<div class="wpte-booking-collapsible-content">
					<?php
					wptravelengine_get_admin_template( 'booking/legacy/partials/traveller-info.php' );
					wptravelengine_get_admin_template( 'booking/legacy/partials/emergency-contact.php' );
					wptravelengine_get_admin_template( 'booking/legacy/partials/payment-details.php' );
					wptravelengine_get_admin_template( 'booking/legacy/partials/billing-details.php' );
					wptravelengine_get_admin_template( 'booking/legacy/partials/additional-field.php' );
					wptravelengine_get_admin_template( 'booking/legacy/partials/admin-notes.php' );
					do_action( 'wptravelengine_booking_details_additional_fields', $booking );
					?>

				</div>
			</div> <!-- end .wpte-booking-fields-area -->

			<!-- .wpte-booking-summary-area -->
			<div class="wpte-booking-summary-area">
				<?php wptravelengine_get_admin_template( 'booking/legacy/partials/booking-summary.php' ); ?>
				<?php wptravelengine_get_admin_template( 'booking/legacy/partials/remaining-payment.php' ); ?>
				<?php wptravelengine_get_admin_template( 'booking/legacy/partials/purchase-receipt.php' ); ?>
				<?php do_action( 'wptravelengine_booking_details_sidebar', $booking ); ?>
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