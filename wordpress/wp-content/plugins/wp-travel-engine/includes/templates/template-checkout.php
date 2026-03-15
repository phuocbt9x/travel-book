<?php
/**
 * Checkout V2 Template.
 *
 * @since 6.1.3
 */
global $wte_cart;

wptravelengine_get_template( 'header-checkout.php' );

if ( count( $wte_cart->getItems() ) > 0 ) {
	wptravelengine_get_template( 'template-checkout/content-checkout.php' );
} else {
	wptravelengine_get_template( 'template-checkout/content-cart-empty.php' );
}

wptravelengine_get_template( 'footer-checkout.php' );
