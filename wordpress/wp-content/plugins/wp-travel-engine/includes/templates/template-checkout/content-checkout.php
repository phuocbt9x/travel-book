<?php
/**
 * Checkout V2 content Template.
 *
 * @since 6.3.0
 */

/**
 * @var object $booking_checkout
 * @var array $form_instances
 * @var array $attributes Checkout UI Attributes.
 * @var array $tour_details Tour Details.
 * @var array $booking_summary Booking Summary.
 * @var array $form_sections Form Sections.
 */

if ( 'show' === $attributes['checkout-steps'] ?? 'show' ) {
	wptravelengine_get_template( 'template-checkout/content-checkout-steps.php' );
}
global $post;
$shortcode_present = has_shortcode( $post->post_content, 'WP_TRAVEL_ENGINE_PLACE_ORDER' );
if ( ! $shortcode_present ) :
	?>
<main class="wpte-checkout__main">
	<div class="wpte-checkout__container">
		<h1 class="wpte-checkout__page-title"><?php echo __( 'Checkout', 'wp-travel-engine' ); ?></h1>
		<?php endif; ?>
		<div class="wpte-checkout__page-layout">
			<div class="wpte-checkout__sidebar">
				<?php
				do_action( 'checkout_template_parts_tour-details' );
				?>
				<div class="wpte-checkout__box wpte-checkout__booking-summary-box">
					<div class="wpte-checkout__box-content" data-cart-summary>
						<?php do_action( 'checkout_template_parts_cart-summary' ); ?>
					</div>
				</div>
			</div>
			<?php do_action( 'checkout_template_parts_checkout-form' ); ?>
		</div>
		<?php
		if ( ! $shortcode_present ) :
			?>
	</div>
</main>
<?php endif; ?>
<?php wptravelengine_get_template( 'template-checkout/content-sprite-svg.php' ); ?>
