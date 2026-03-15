<?php
/**
 * Authorize Net Settings.
 *
 * @since 6.2.0
 */
use WPTravelEngine\PaymentGateways\PaymentGateways;
$is_authorize_net_active = PaymentGateways::instance()->get_payment_gateway( 'authorize-net-payment' );
if ( ! $is_authorize_net_active ) {
	return array();
}
return apply_filters(
	'payment_authorize_net',
	array(
		'title'     => __( 'Authorize.net', 'wp-travel-engine' ),
		'order'     => 30,
		'id'        => 'payment-authorize-net',
		'is_active' => $is_authorize_net_active,
		'fields'    => array(
			array(
				'field_type' => 'DEBUG_MODE',
				'name'       => 'debug_mode',
			),
			array(
				'divider'    => true,
				'label'      => __( 'API Login ID', 'wp-travel-engine' ),
				'field_type' => 'TEXT',
				'name'       => 'authorize_net.api_login_id',
			),
			array(
				'divider'    => true,
				'label'      => __( 'Transaction Key', 'wp-travel-engine' ),
				'field_type' => 'TEXT',
				'name'       => 'authorize_net.transaction_key',
				'type'       => 'password',
			),
			array(
				'field_type' => 'ALERT',
				'content'    => sprintf( __( 'Create Developer Account <a href="%s" target="__blank">Here</a> to get API Credentials.', 'wp-travel-engine' ), 'https://developer.authorize.net/hello_world/sandbox.html' ),
			),
		),
	)
);
