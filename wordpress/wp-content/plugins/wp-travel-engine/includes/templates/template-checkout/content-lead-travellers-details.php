<?php
/**
 * @var WPTravelEngine\Builders\FormFields\FormField $lead_travellers_form_fields
 * @var bool $show_title
 * @since 6.4.3
 */
if ( 'hide' === ( $args['attributes']['lead-travellers'] ?? '' ) ) {
	return;
}
// Lead Traveller's Details Form
if ( isset( $lead_travellers_form_fields ) && isset( $lead_travellers_form_fields[0]->fields ) && empty( $lead_travellers_form_fields[0]->fields ) ) {
	return;
}
?>
<div class="wpte-checkout__box collapsible <?php echo $show_title ? 'open' : ''; ?>">
	<?php if ( $show_title ) : ?>
		<h3 class="wpte-checkout__box-title">
			<?php echo __( 'Lead Traveller Details', 'wp-travel-engine' ); ?>
			<button type="button" class="wpte-checkout__box-toggle-button">
				<svg>
					<use xlink:href="#chevron-down"></use>
				</svg>
			</button>
		</h3>
	<?php endif; ?>
	<div class="wpte-checkout__box-content">
		<?php
		foreach ( $lead_travellers_form_fields as $lead_travellers_form_field ) {
			$lead_travellers_form_field->render();
		}
		?>
	</div>
</div>
