<?php
/**
 * Accommodation Tab Template
 *
 * @var \WPTravelEngine\Core\Models\Post\Booking $booking
 */

if ( 'readonly' === $template_mode && ! ( $cart_line_items['accommodation'] ?? false ) ) {
	return;
}

?>
<li class="wpte-tab-item">
	<a href="#" class="wpte-tab" data-target="accommodation"><?php echo esc_html__( 'Accommodation', 'wp-travel-engine' ); ?></a>
</li>