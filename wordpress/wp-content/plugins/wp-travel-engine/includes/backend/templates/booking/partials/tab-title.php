<?php
/**
 * @var array $remaining_payment
 * @var \WPTravelEngine\Core\Models\Post\Booking $booking
 */

?>

<div class="wpte-tabs-container">
	<!-- .wpte-tabs -->
	<ul class="wpte-tabs horizontal">
		<li class="wpte-tab-item is-active">
			<a href="#" class="wpte-tab"
				data-target="trip-details"><?php echo __( 'Trip Details', 'wp-travel-engine' ); ?></a>
		</li>
		<?php if ( $booking->get_travelers() || 'edit' === $template_mode ) : ?>
			<li class="wpte-tab-item">
				<a href="#" class="wpte-tab"
					data-target="travellers"><?php echo __( 'Travellers', 'wp-travel-engine' ); ?></a>
			</li>
		<?php endif; ?>
		<?php if ( $booking->get_emergency_contacts() || 'edit' === $template_mode ) : ?>
			<li class="wpte-tab-item">
				<a href="#" class="wpte-tab"
					data-target="emergency-contact"><?php echo __( 'Emergency Contact', 'wp-travel-engine' ); ?></a>
			</li>
			<?php
		endif;
		do_action( 'wptravelengine_booking_details_tabs', $booking );
		?>
		<?php if ( $booking->get_payments() || 'edit' === $template_mode ) : ?>
			<li class="wpte-tab-item">
				<a href="#" class="wpte-tab" data-target="payments"><?php echo __( 'Payment', 'wp-travel-engine' ); ?></a>
			</li>
		<?php endif; ?>
		<?php if ( $booking->get_billing_info() || 'edit' === $template_mode ) : ?>
			<li class="wpte-tab-item">
				<a href="#" class="wpte-tab" data-target="billing"><?php echo __( 'Billing', 'wp-travel-engine' ); ?></a>
			</li>
		<?php endif; ?>
		<?php if ( $booking->get_customer_note() ) : ?>
			<li class="wpte-tab-item">
				<a href="#" class="wpte-tab"
					data-target="additional-notes"><?php echo __( 'Additional Notes', 'wp-travel-engine' ); ?></a>
			</li>
		<?php endif; ?>
		<?php if ( $booking->get_admin_note() || 'edit' === $template_mode ) : ?>
			<li class="wpte-tab-item">
				<a href="#" class="wpte-tab" data-target="notes"><?php echo __( 'Your Notes', 'wp-travel-engine' ); ?></a>
			</li>
		<?php endif; ?>
	</ul> <!-- end .wpte-tabs -->

	<div class="wpte-button-group">
			<?php if ( 'edit' === $template_mode || apply_filters( 'wptravelengine_booking_details_save_button', false, $booking ) ) : ?>
			<div class="wpte-button-group">
				<button style="display:block;" id="wpte-booking-submit-button" type="submit"
					class="wpte-button wpte-solid"><?php echo __( 'Save', 'wp-travel-engine' ); ?></button>
			</div>
		<?php else : ?>
			<a id="wpte-booking-edit-button" type="button" href="<?php echo esc_url( admin_url( "post.php?post={$booking->get_id()}&action=edit&wptravelengine_action=edit" ) ); ?>" class="wpte-button wpte-outlined"><?php echo __( 'Edit', 'wp-travel-engine' ); ?></a>
		<?php endif ?>
	</div>
</div>