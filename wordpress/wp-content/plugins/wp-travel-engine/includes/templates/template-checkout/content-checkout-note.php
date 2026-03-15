<?php
/**
 * @var WPTravelEngine\Builders\FormFields\FormField $note_form_fields
 * @var bool $show_title
 * @since 6.3.0
 */
if ( 'hide' === ( $args['attributes']['additional_note'] ?? '' ) ) {
	return;
}
?>
<!-- Additional Note -->
<div class="wpte-checkout__box collapsible <?php echo $show_title ? 'open' : ''; ?>">
	<?php if ( $show_title ) : ?>
		<h3 class="wpte-checkout__box-title">
			<?php echo __( 'Additional Notes', 'wp-travel-engine' ); ?>
			<button type="button" class="wpte-checkout__box-toggle-button">
				<svg>
					<use xlink:href="#chevron-down"></use>
				</svg>
			</button>
		</h3>
		<?php
	endif;
	$note_form_fields->render();
	?>
</div>
