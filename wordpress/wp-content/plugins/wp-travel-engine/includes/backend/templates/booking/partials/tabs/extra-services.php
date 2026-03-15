<?php
/**
 * Extra Services Tab Template
 *
 * @var \WPTravelEngine\Core\Models\Post\Booking $booking
 */

if ( 'readonly' === $template_mode && ! ( $cart_line_items['extra_service'] ?? false ) ) {
	return;
}

?>
<li class="wpte-tab-item">
	<a href="#" class="wpte-tab" data-target="extra-services"><?php echo esc_html__( 'Extra Services', 'wp-travel-engine' ); ?></a>
</li>