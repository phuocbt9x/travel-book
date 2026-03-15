<?php
/**
 * Travel Insurance Tab Template
 *
 * @var \WPTravelEngine\Core\Models\Post\Booking $booking
 */

if ( 'readonly' === $template_mode && ! ( $cart_line_items['travel_insurance'] ?? false ) ) {
	return;
}

?>
<li class="wpte-tab-item">
	<a href="#" class="wpte-tab" data-target="travel-insurance"><?php echo esc_html__( 'Travel Insurance', 'wp-travel-engine' ); ?></a>
</li>