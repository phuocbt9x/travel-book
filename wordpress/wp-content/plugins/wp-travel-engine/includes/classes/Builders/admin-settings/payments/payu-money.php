<?php
/**
 * Payment PayU Money Bolt Tab Settings.
 *
 * @since 6.2.0
 */
use WPTravelEngine\PaymentGateways\PaymentGateways;
$is_payu_money_active = PaymentGateways::instance()->get_payment_gateway( 'payu_money_enable' );
if ( ! $is_payu_money_active ) {
	return array();
}
return apply_filters(
	'payment_payu_money',
	array(
		'is_active' => $is_payu_money_active,
		'title'     => __( 'PayU Money Bolt', 'wp-travel-engine' ),
		'order'     => 65,
		'id'        => 'payment-payu-money',
		'fields'    => array(
			array(
				'field_type' => 'DEBUG_MODE',
				'name'       => 'debug_mode',
			),
			array(
				'divider'    => true,
				'label'      => __( 'Merchant Key', 'wp-travel-engine' ),
				'help'       => __( 'Enter a valid Merchant Key. All payments will go to this account.', 'wp-travel-engine' ),
				'field_type' => 'TEXT',
				'name'       => 'payu_money.merchant_key',
				'default'    => '',
			),
			array(
				'label'      => __( 'Merchant Salt', 'wp-travel-engine' ),
				'help'       => __( 'Enter a valid Merchant Salt.', 'wp-travel-engine' ),
				'field_type' => 'TEXT',
				'name'       => 'payu_money.merchant_salt',
				'default'    => '',
			),
		),
	)
);
