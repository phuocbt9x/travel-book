<?php
/**
 * HBL Payment Settings.
 *
 * @since 6.2.0
 */
use WPTravelEngine\PaymentGateways\PaymentGateways;
$is_hbl_active = PaymentGateways::instance()->get_payment_gateway( 'hbl_enable' );
if ( ! $is_hbl_active ) {
	return array();
}
return apply_filters(
	'payment_hbl',
	array(
		'title'     => __( 'HBL Payment', 'wp-travel-engine' ),
		'order'     => 35,
		'id'        => 'payment-hbl',
		'is_active' => $is_hbl_active,
		'fields'    => array(
			array(
				'field_type' => 'DEBUG_MODE',
				'name'       => 'debug_mode',
			),
			array(
				'divider'      => true,
				'label'        => __( 'Office ID', 'wp-travel-engine' ),
				'field_type'   => 'TEXT',
				'description'  => __( 'Get office ID from the Bank.', 'wp-travel-engine' ),
				'name'         => 'hbl.office_id',
				'defaultValue' => 'DEMOOFFICE',
			),
			array(
				'divider'     => true,
				'label'       => __( 'API Key', 'wp-travel-engine' ),
				'field_type'  => 'TEXT',
				'description' => __( 'Get office API Key from the bank.', 'wp-travel-engine' ),
				'name'        => 'hbl.api_key',
			),
			array(
				'label'       => __( 'Encryption Key ID', 'wp-travel-engine' ),
				'description' => __( 'Get Encryption Key ID from the bank.', 'wp-travel-engine' ),
				'field_type'  => 'TEXT',
				'name'        => 'hbl.encryption_key_id',
				'divider'     => true,
			),
			array(
				'field_type' => 'TITLE',
				'title'      => __( 'Encryption Keys', 'wp-travel-engine' ),
			),
			array(
				'label'       => __( 'Merchant Signing Private Key', 'wp-travel-engine' ),
				'description' => __( 'Merchant Signing Private Key is used to cryptographically sign and create the request JWS.', 'wp-travel-engine' ),
				'field_type'  => 'TEXTAREA',
				'name'        => 'hbl.merchant_private_keys.signing_key',
				'divider'     => true,
			),
			array(
				'label'       => __( 'Merchant Decryption Private Key', 'wp-travel-engine' ),
				'description' => __( 'Merchant Decryption Private Key used to cryptographically decrypt the response JWE.', 'wp-travel-engine' ),
				'field_type'  => 'TEXTAREA',
				'name'        => 'hbl.merchant_private_keys.decryption_key',
				'divider'     => true,
			),
			array(
				'label'       => __( 'PACO Signing Public Key', 'wp-travel-engine' ),
				'description' => __( 'PACO Encryption Public Key is used to cryptographically encrypt and create the request JWE.', 'wp-travel-engine' ),
				'field_type'  => 'TEXTAREA',
				'name'        => 'hbl.paco_public_keys.signing_key',
				'divider'     => true,
			),
			array(
				'label'       => __( 'PACO Encryption Public Key', 'wp-travel-engine' ),
				'description' => __( 'PACO Encryption Public Key is used to cryptographically encrypt and create the request JWE.', 'wp-travel-engine' ),
				'field_type'  => 'TEXTAREA',
				'name'        => 'hbl.paco_public_keys.encryption_key',
				'divider'     => true,
			),
			array(
				'field_type' => 'TITLE',
				'title'      => __( 'Notification URLs', 'wp-travel-engine' ),
			),
			array(
				'label'        => __( 'Confirmation URL', 'wp-travel-engine' ),
				'description'  => __( 'URL to redirect customer back to Merchant website after success.', 'wp-travel-engine' ),
				'field_type'   => 'TEXT',
				'name'         => 'hbl.notification_urls.confirmation_url',
				'divider'      => true,
				'defaultValue' => 'http://travelengine.test?_gateway=hbl_enable&_action=wtep_success',
			),
			array(
				'label'        => __( 'Cancellation URL', 'wp-travel-engine' ),
				'description'  => __( 'Redirect URL if customer cancel the transaction.', 'wp-travel-engine' ),
				'field_type'   => 'TEXT',
				'name'         => 'hbl.notification_urls.cancellation_url',
				'divider'      => true,
				'defaultValue' => 'http://travelengine.test?_gateway=hbl_enable&_action=wtep_cancel',
			),
			array(
				'label'        => __( 'Failed URL', 'wp-travel-engine' ),
				'description'  => __( 'Redirect URL if transaction is failed.', 'wp-travel-engine' ),
				'field_type'   => 'TEXT',
				'name'         => 'hbl.notification_urls.failure_url',
				'divider'      => true,
				'defaultValue' => 'http://travelengine.test?_gateway=hbl_enable&_action=wtep_fail',
			),
			array(
				'label'        => __( 'Notification URL', 'wp-travel-engine' ),
				'description'  => __( 'Payment Gateway Notification URL.', 'wp-travel-engine' ),
				'field_type'   => 'TEXT',
				'name'         => 'hbl.notification_urls.notify_url',
				'defaultValue' => 'http://travelengine.test?_gateway=hbl_enable&_action=wtep_ipn',
			),
		),
	)
);
