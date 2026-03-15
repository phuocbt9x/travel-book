<?php

use WPTravelEngine\Core\Cart\Cart;

/**
 * Helper functions for cart.
 *
 * @package WPTravelEngine
 * @since 6.3.0
 */

/**
 * @param array $args
 *
 * @return Cart
 * @since 6.3.0
 */
function wptravelengine_update_cart( array $args ): Cart {
	global $wte_cart;

	$mappings = array(
		'full'              => 'full',
		'due'               => 'due',
		'partial'           => 'partial',
		'full_payment'      => 'full',
		'remaining_payment' => 'due',
	);

	/**
	 * @since 6.7.1
	 * @description Filter for the cart update payment mode mappings.
	 * @param array $mappings The payment mode mappings.
	 * @param array $args The arguments for the filter.
	 * @return array
	 */
	$mappings = apply_filters( 'wptravelengine_cart_update_payment_mode_mappings', $mappings, $args );

	$payment_type = $args['payment_type'] ?? false;
	if ( ! empty( $args['payment_gateway'] ) ) {
		$wte_cart->set_payment_gateway( $args['payment_gateway'] );
	}

	if ( $payment_type && isset( $mappings[ $payment_type ] ) ) {
		$wte_cart->set_payment_type( $mappings[ $payment_type ] );
	}
	$wte_cart->update_cart();
	$wte_cart->calculate_totals();

	return $wte_cart;
}

/**
 * Get zero decimal currencies.
 *
 * @return array
 * @since 6.3.0
 */
function wptravelengine_cart_zero_decimal_currencies(): array {
	return apply_filters(
		'wptravelengine_cart_zero_decimal_currencies',
		array_merge(
			array( 'BIF', 'CLP', 'DJF', 'GNF', 'JPY', 'KMF', 'KRW', 'MGA' ),
			array( 'PYG', 'RWF', 'VND', 'VUV', 'XAF', 'XOF', 'XPF' )
		)
	);
}
