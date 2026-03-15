<?php

/**
 * @var WPTravelEngine\Builders\FormFields\BillingFormFields $billing_form_fields
 * @var int $lead_travellers_fields_count
 * @var bool $show_title
 * @since 6.3.0
 */
?>
<!-- Billing Details Form -->
<div class="wpte-checkout__box collapsible <?php echo esc_attr( $show_title ? 'open' : '' ); ?>">
	<?php if ( $show_title ) : ?>
		<h3 class="wpte-checkout__box-title">
			<?php echo esc_html( apply_filters( 'wpte_billings_details_title', esc_html__( 'Billing Details', 'wp-travel-engine' ) ) ); ?>
			<button type="button" class="wpte-checkout__box-toggle-button">
				<svg>
					<use xlink:href="#chevron-down"></use>
				</svg>
			</button>
		</h3>
	<?php endif; ?>
	<div class="wpte-checkout__box-content">
	<?php
	if ( wptravelengine_settings()->get( 'display_travellers_info' ) === 'yes' && wptravelengine_settings()->get( 'traveller_emergency_details_form' ) === 'on_checkout' && $payment_type !== 'due'
	&& $lead_travellers_fields_count > 0 ) :
		?>
		<div class="wpte-copy-from-lead-travelers" style="margin: 0 0 24px;">
			<input type="checkbox" id="wpte-copy-from-lead-travelers" name="wpte-copy-from-lead-travelers" value="1">
			<label for="wpte-copy-from-lead-travelers">
				<?php esc_html_e( 'Same as Lead Traveller', 'wp-travel-engine' ); ?>
			</label>
			</div>
		<?php endif; ?>
		<?php $billing_form_fields->render(); ?>
	</div>
</div>