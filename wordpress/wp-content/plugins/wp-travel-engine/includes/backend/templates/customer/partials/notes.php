<?php
/**
 * Customer Notes.
 *
 * @var string $notes
 */
?>

<div class="wpte-form-section" data-target-id="notes">
	<?php
		wp_nonce_field( 'wptravelengine_customer_save_nonce_action', 'wptravelengine_customer_save_nonce' );
	?>
	<div class="wpte-accordion">
		<div class="wpte-accordion-content">
			<h3 class="wpte-accordion-title"><?php esc_html_e( 'Notes', 'wp-travel-engine' ); ?></h3>
			<div class="wpte-field">
				<textarea name="billing[notes]"><?php echo esc_textarea( $notes ); ?></textarea>
			</div>
		</div>
	</div>
</div>
