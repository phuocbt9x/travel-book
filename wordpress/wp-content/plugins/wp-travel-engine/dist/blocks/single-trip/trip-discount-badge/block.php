<?php
/**
 * Render File for Discount block.
 *
 * @var Render $render
 * @var string $wrapper_attributes
 * @var Attributes $attributes_parser
 * @package Wp_Travel_Engine
 * @since 5.9
 */

global $wtetrip;

$discount = '';

if ( $render->is_editor() ) {
	$discount = '20%';
} else {
	if ( $wtetrip->has_sale && isset( $wtetrip->sale_percentage ) ) {
		$discount = (float) $wtetrip->sale_percentage . '%';
	}
}

if ( $discount !== '' ) :
	?>
	<div <?php $attributes_parser->wrapper_attributes(); ?>>
        <span class="wpte-bf-discount-tag">
			<?php
			echo wp_kses(
				str_replace( '%discount_percentage%', "{$discount}", $attributes_parser->get( 'discountLabel' ) ),
				array( 'span' => array( 'class' => array() ) )
			);
			?>
		</span>
	</div>
<?php endif; ?>
