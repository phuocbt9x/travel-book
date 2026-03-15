<?php
/**
 * Booking Notes.
 *
 * @since 6.4.0
 */

/**
 * @var Booking $booking
 */

use WPTravelEngine\Core\Models\Post\Booking;

if ( ( ! $admin_note = $booking->get_admin_note() ) && 'edit' !== $template_mode ) {
	return;
}
?>
<div class="wpte-form-section wpte-static-info" data-target-id="notes">
	<div class="wpte-accordion">
		<div class="wpte-accordion-content">
			<div class="wpte-fields-grid" data-columns="1">
				<div class="wpte-field">
					<label for="admin_notes"><?php echo __( 'Your Notes', 'wp-travel-engine' ); ?></label>
					<?php if ( 'edit' === $template_mode ) : ?>
						<textarea name="admin_notes"
									id="admin_notes"><?php echo esc_textarea( $admin_note ); ?></textarea>
					<?php else : ?>
						<p><?php echo esc_textarea( $admin_note ); ?></p>
					<?php endif ?>
				</div>
			</div>
		</div>
	</div>
</div>
