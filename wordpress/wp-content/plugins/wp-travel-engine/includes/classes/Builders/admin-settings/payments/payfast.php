<?php
/**
 * Payment PayFast Payment Tab Settings.
 *
 * @since 6.2.0
 */
use WPTravelEngine\PaymentGateways\PaymentGateways;
$currency_code     = wp_travel_engine_get_currency_code();
$is_payfast_active = PaymentGateways::instance()->get_payment_gateway( 'payfast_enable' );
if ( ! $is_payfast_active ) {
	return array();
}
return apply_filters(
	'payment_payfast',
	array(
		'is_active' => $is_payfast_active,
		'title'     => __( 'PayFast Payment', 'wp-travel-engine' ),
		'order'     => 45,
		'id'        => 'payment-payfast',
		'fields'    => array(
			array(
				'field_type' => 'DEBUG_MODE',
				'name'       => 'debug_mode',
			),
			array(
				'visibility' => $currency_code !== 'ZAR',
				'divider'    => true,
				'field_type' => 'ALERT',
				'content'    => __( 'Note: Please choose South African rand (R) in Settings Tab ( Miscellaneous > Currency Settings > Payment Currency ) for this gateway.', 'wp-travel-engine' ),
			),
			array(
				'divider'    => true,
				'label'      => __( 'Merchant Key', 'wp-travel-engine' ),
				'help'       => __( 'Enter a valid Merchant key from PayFast account. All payments will go to this account.', 'wp-travel-engine' ),
				'field_type' => 'TEXT',
				'name'       => 'payfast.merchant_key',
				'default'    => '',
			),
			array(
				'divider'    => true,
				'label'      => __( 'Merchant ID', 'wp-travel-engine' ),
				'help'       => __( 'Enter a valid Merchant ID from PayFast account.', 'wp-travel-engine' ),
				'field_type' => 'TEXT',
				'name'       => 'payfast.merchant_id',
				'default'    => '',
			),
		),
	)
);
