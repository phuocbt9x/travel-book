<?php
/**
 * @var BillingEditFormFields $billing_edit_form_fields
 */

use WPTravelEngine\Builders\FormFields\BillingEditFormFields;

if ( ! $billing_edit_form_fields ) {
	return;
}
?>

<div class="wpte-form-section" data-target-id="billing">
	<div class="wpte-accordion">
		<div class="wpte-accordion-header">
			<h3 class="wpte-accordion-title"><?php echo __( 'Billing Details', 'wp-travel-engine' ); ?></h3>
			<button type="button" class="wpte-accordion-toggle">
				<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
					<path d="M5 7.5L10 12.5L15 7.5" stroke="currentColor" stroke-width="1.66667" stroke-linecap="round"
							stroke-linejoin="round" />
				</svg>
			</button>
		</div>
		<div class="wpte-accordion-content">
			<div class="wpte-fields-grid" data-columns="2">
				<?php $billing_edit_form_fields->render(); ?>
			</div>
		</div>

	</div>
</div>
