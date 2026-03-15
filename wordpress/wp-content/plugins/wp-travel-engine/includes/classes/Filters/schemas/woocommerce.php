<?php
/**
 * Woocommerce Payment Extenstion Schmea.
 *
 * @since 6.2.0
 */

$active_extensions = apply_filters( 'wpte_settings_get_global_tabs', array() );
$file_path         = $active_extensions['wpte-payment']['sub_tabs']['woocommerce']['content_path'] ?? '';
if ( ! file_exists( $file_path ) ) {
	return array();
}

return array(
	'enable_woocommerce_gateway' => array(
		'description' => __( 'Use WooCommerce Payment Gateway for Payments', 'wp-travel-engine' ),
		'type'        => 'boolean',
	),
);
