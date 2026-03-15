<?php
/**
 * Checkout content tour details
 *
 * @var array $tour_details Tour Details.
 * @var bool $show_title Show title.
 * @var bool $content_only Show title.
 * @since 6.3.0
 */
?>
<!-- Tour Details -->
<?php if ( ! $content_only ) : ?>
<div class="wpte-checkout__box collapsible open">
	<?php if ( $show_title ) : ?>
		<h5 class="wpte-checkout__form-title toggler-wrap">
			<?php echo __( 'Tour Details', 'wp-travel-engine' ); ?>
			<button type="button" class="wpte-checkout__box-toggle-button">
				<svg>
					<use xlink:href="#chevron-down"></use>
				</svg>
			</button>
		</h5>
	<?php endif; ?>
	<div class="wpte-checkout__box-content">
		<?php endif; // $tour_details_content_only ?>
		<?php foreach ( $tour_details as $cart_item ) : ?>
			<div class="wpte-checkout__tour-details">
				<div class="wpte-checkout__table-wrap">
					<table>
						<?php echo wp_kses_post( implode( '', $cart_item ) ); ?>
					</table>
				</div>
			</div>
		<?php endforeach; ?>
		<?php if ( ! $content_only ) : ?>
	</div>
</div>
<?php endif; // $tour_details_content_only ?>
