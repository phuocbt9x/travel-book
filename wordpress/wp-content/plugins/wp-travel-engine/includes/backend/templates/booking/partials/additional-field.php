<?php
/**
 * @since 6.4.0
 *
 * @var \WPTravelEngine\Core\Models\Post\Booking $booking
 */

if ( ( ! $customer_note = $booking->get_customer_note() ) && 'edit' !== $template_mode || empty( $customer_note ) ) {
	return;
}
?>
<div class="wpte-form-section wpte-static-info" data-target-id="additional-notes">
	<div class="wpte-accordion">
		<div class="wpte-accordion-content">
			<div class="wpte-fields-grid" data-columns="1">
				<div class="wpte-field">
					<label for="admin_notes"><?php echo __( 'Additional Notes:', 'wp-travel-engine' ); ?></label>
					<?php if ( 'edit' === $template_mode ) : ?>
						<textarea name="admin_notes" id="admin_notes"><?php echo esc_textarea( $customer_note ); ?></textarea>
					<?php else : ?>
						<p><?php echo esc_html( $customer_note ); ?></p>
					<?php endif ?>
				</div>
			</div>
		</div>
	</div>
</div>