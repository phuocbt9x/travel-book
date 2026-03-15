<?php
/**
 * Payment Paypal Standard Tab Settings.
 *
 * @since 6.2.0
 */

return apply_filters(
	'payment_paypal_standard',
	array(
		'title'  => __( 'PayPal Standard', 'wp-travel-engine' ),
		'order'  => 13,
		'id'     => 'payment-paypal-standard',
		'fields' => array(
			array(
				'field_type' => 'DEBUG_MODE',
				'name'       => 'debug_mode',
			),
			array(
				'label'      => __( 'PayPal ID', 'wp-travel-engine' ),
				'help'       => __( 'Enter a valid Merchant account ID (strongly recommend) or PayPal account email address. All payments will go to this account.', 'wp-travel-engine' ),
				'field_type' => 'TEXT',
				'name'       => 'paypal.paypal_id',
			),
		),
	)
);
