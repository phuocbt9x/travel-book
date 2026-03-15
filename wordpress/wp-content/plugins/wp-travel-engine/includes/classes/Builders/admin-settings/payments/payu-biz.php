<?php
/**
 * Payment PayU Biz Payment Tab Settings.
 *
 * @since 6.2.0
 */
use WPTravelEngine\PaymentGateways\PaymentGateways;
$is_payu_biz_active = PaymentGateways::instance()->get_payment_gateway( 'payu_enable' );
if ( ! $is_payu_biz_active ) {
	return array();
}
return apply_filters(
	'payment_payu_biz',
	array(
		'is_active' => $is_payu_biz_active,
		'title'     => __( 'PayU Biz', 'wp-travel-engine' ),
		'order'     => 60,
		'id'        => 'payment-payu-biz',
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
				'name'       => 'payu_biz.merchant_key',
			),
			array(
				'label'      => __( 'Merchant Salt', 'wp-travel-engine' ),
				'help'       => __( 'Enter a valid Merchant Salt.', 'wp-travel-engine' ),
				'field_type' => 'TEXT',
				'name'       => 'payu_biz.merchant_salt',
			),
		),
	)
);
