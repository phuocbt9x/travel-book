<?php
/**
 * @var \WPTravelEngine\Core\Models\Post\Trip $trip_instance
 */

$actual_price = $trip_instance->has_sale() ? $trip_instance->get_sale_price() : $trip_instance->get_price();
$saved_price  = $trip_instance->has_sale() ? $trip_instance->get_price() - $trip_instance->get_sale_price() : 0;

?>
<span class="price-holder">
	<?php if ( $trip_instance->has_sale() ) : ?>
		<span class="regular-price">
			<?php esc_html_e( 'From ', 'wp-travel-engine' ); ?>
			<span class="striked-price"><?php \wte_the_formated_price( $trip_instance->get_price() ); ?></span>
		</span>
	<?php endif; ?>
	<?php if ( 0.00 === floatval( $actual_price ) ) : ?>
		<span class="actual-price wpte-is-free"><?php esc_html_e( 'Free', 'wp-travel-engine' ); ?></span>
	<?php else : ?>
		<span class="actual-price"><?php \wte_the_formated_price( $actual_price ); ?></span>
	<?php endif; ?>
	<?php if ( $saved_price > 0 ) { ?>
		<span class="saved-price">
			<?php esc_html_e( 'You save ', 'wp-travel-engine' ); ?>
			<?php \wte_the_formated_price( $saved_price ); ?>
		</span>
	<?php } ?>
</span>
