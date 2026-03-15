<?php
/**
 * Midtrans Payment Settings.
 *
 * @since 6.2.0
 */
use WPTravelEngine\PaymentGateways\PaymentGateways;
$is_midtrans_active = PaymentGateways::instance()->get_payment_gateway( 'midtrans_enable' );
if ( ! $is_midtrans_active ) {
	return array();
}
return apply_filters(
	'payment_midtrans',
	array(
		'title'     => __( 'Midtrans', 'wp-travel-engine' ),
		'order'     => 40,
		'id'        => 'payment-midtrans',
		'is_active' => $is_midtrans_active,
		'fields'    => array(
			array(
				'field_type' => 'DEBUG_MODE',
				'name'       => 'debug_mode',
			),
			array(
				'divider'    => true,
				'label'      => __( 'Payment Notification URL', 'wp-travel-engine' ),
				'field_type' => 'COPY_CODE',
				'readOnly'   => true,
				'code'       => home_url(),
			),
			array(
				'divider'    => true,
				'label'      => __( 'Recurring Notification URL', 'wp-travel-engine' ),
				'field_type' => 'COPY_CODE',
				'readOnly'   => true,
				'code'       => home_url(),
			),
			array(
				'label'      => __( 'Finish Redirect URL', 'wp-travel-engine' ),
				'field_type' => 'COPY_CODE',
				'name'       => 'finish_redirect_url',
				'readOnly'   => true,
				'divider'    => true,
				'code'       => wp_travel_engine_get_booking_confirm_url(),
			),
			array(
				'label'      => __( 'Unfinish Redirect URL', 'wp-travel-engine' ),
				'field_type' => 'COPY_CODE',
				'name'       => 'unfinish_redirect_url',
				'readOnly'   => true,
				'divider'    => true,
				'code'       => home_url(),
			),
			array(
				'label'      => __( 'Error Redirect URL', 'wp-travel-engine' ),
				'field_type' => 'COPY_CODE',
				'name'       => 'error_redirect_url',
				'readOnly'   => true,
				'divider'    => true,
				'code'       => home_url(),
			),
			array(
				'label'       => __( '3DS Secure', 'wp-travel-engine' ),
				'description' => __( 'Check this option to enable 3DS Secure', 'wp-travel-engine' ),
				'field_type'  => 'SWITCH',
				'name'        => 'midtrans.enable_3Ds_secure',
				'divider'     => true,
			),
			array(
				'label'       => __( 'Save Card', 'wp-travel-engine' ),
				'description' => __( 'Check this option to enable Save Card', 'wp-travel-engine' ),
				'field_type'  => 'SWITCH',
				'name'        => 'midtrans.enable_save_card',
				'divider'     => true,
			),
			array(
				'label'       => __( 'Merchant ID', 'wp-travel-engine' ),
				'description' => __( 'Enter a valid Merchant key from Midtrans account. All payments will go to this account', 'wp-travel-engine' ),
				'field_type'  => 'TEXT',
				'name'        => 'midtrans.merchant_id',
				'divider'     => true,
			),
			array(
				'label'       => __( 'Client Key', 'wp-travel-engine' ),
				'description' => __( 'Enter a valid Client Key from Midtrans account.', 'wp-travel-engine' ),
				'field_type'  => 'TEXT',
				'name'        => 'midtrans.client_key',
				'divider'     => true,
			),
			array(
				'label'       => __( 'Server Key', 'wp-travel-engine' ),
				'description' => __( 'Enter a valid Server Key from Midtrans account.', 'wp-travel-engine' ),
				'field_type'  => 'TEXT',
				'name'        => 'midtrans.server_key',
			),
		),
	)
);
