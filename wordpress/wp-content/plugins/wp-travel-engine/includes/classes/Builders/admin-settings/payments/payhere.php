<?php
/**
 * Payment PayHere Payment Tab Settings.
 *
 * @since 6.2.0
 */
use WPTravelEngine\PaymentGateways\PaymentGateways;
$is_payhere_active = PaymentGateways::instance()->get_payment_gateway( 'payhere_payment' );
if ( ! $is_payhere_active ) {
	return array();
}
return apply_filters(
	'payment_payhere',
	array(
		'is_active' => $is_payhere_active,
		'title'     => __( 'PayHere Payment', 'wp-travel-engine' ),
		'order'     => 50,
		'id'        => 'payment-payhere',
		'fields'    => array(
			array(
				'field_type' => 'DEBUG_MODE',
				'name'       => 'debug_mode',
			),
			array(
				'divider'    => true,
				'label'      => __( 'Payhere Merchant ID', 'wp-travel-engine' ),
				'help'       => __( 'Payhere Merchant ID for your payhere account. All payments will go to this account.', 'wp-travel-engine' ),
				'field_type' => 'TEXT',
				'name'       => 'payhere.merchant_id',
			),
			array(
				'divider'    => true,
				'label'      => __( 'Payhere Merchant Secret', 'wp-travel-engine' ),
				'help'       => __( 'Payhere Merchant Secret for your payhere account. All payments will go to this account.', 'wp-travel-engine' ),
				'field_type' => 'TEXT',
				'name'       => 'payhere.merchant_secret',
			),
			array(
				'divider'    => true,
				'label'      => __( 'Onsite checkout', 'wp-travel-engine' ),
				'help'       => __( 'Check to enable onsite checkout while paying with PayHere.', 'wp-travel-engine' ),
				'field_type' => 'SWITCH',
				'name'       => 'payhere.enable_onsite_checkout',
			),
		),
	)
);
