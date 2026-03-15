<?php
/**
 * Payment Woocomerce Tab Settings.
 *
 * @since 6.2.0
 */
$is_wte_woocommerce_active = defined( 'WPTRAVELENGINE_WC_PAYMENTS_FILE__' );
$is_woocomerce_active      = defined( 'WC_PLUGIN_FILE' );
$active_extensions         = apply_filters( 'wpte_settings_get_global_tabs', array() );
$file_path                 = $active_extensions['wpte-payment']['sub_tabs']['woocommerce']['content_path'] ?? '';
if ( ! file_exists( $file_path ) ) {
	return array();
}
return apply_filters(
	'payment_woocommerce',
	array(
		'is_active' => ( $is_wte_woocommerce_active && $is_woocomerce_active ) ? true : false,
		'title'     => __( 'WooCommerce', 'wp-travel-engine' ),
		'order'     => 75,
		'id'        => 'payment-woocommerce',
		'fields'    => array(
			array(
				'field_type' => 'DEBUG_MODE',
				'name'       => 'debug_mode',
			),
			array(
				'divider'     => true,
				'label'       => __( 'Use WooCommerce Payment Gateway for Payments', 'wp-travel-engine' ),
				'description' => __( 'Enabling this option will allow users to use WooCommerce Checkout and Payment Gateways to purchase/book trips.<br><strong>Note: </strong>This will also disable the default behavior of WP Travel Engine of Trip Booking.', 'wp-travel-engine' ),
				'field_type'  => 'SWITCH',
				'name'        => 'enable_woocommerce_gateway',
				'default'     => '',
			),
			array(
				'field_type' => 'ALERT',
				'content'    => sprintf(
					__( 'If you have enabled tax for WP Travel Engine, You must configure tax settings for WooCommerce. %1$sView Documentation%2$s about setting up taxes.', 'wp-travel-engine' ),
					'<a href="https://woocommerce.com/document/setting-up-taxes-in-woocommerce/" target="_blank">',
					'</a>'
				),
			),
		),
	)
);
