<?php
/**
 * Checkout Template.
 * @package Wp_Travel_Engine
 * @since 6.3.0
 */
use WPTravelEngine\Assets;

Assets::instance()
->enqueue_script( 'trip-checkout' )
->enqueue_style( 'trip-checkout' )
->dequeue_script( 'wp-travel-engine' )
->dequeue_style( 'wp-travel-engine' );


global $wptravelengine_template_args, $wte_cart;
$checkout_page = new \WPTravelEngine\Pages\Checkout( $wte_cart );
$tour_details = $checkout_page->get_tour_details();
$cart_line_items = $checkout_page->get_cart_line_items();

wptravelengine_get_template( 'template-checkout.php', wptravelengine_get_checkout_template_args( [ 'tour_details' => $tour_details, 'cart_line_items' => $cart_line_items ] ) );
?>
