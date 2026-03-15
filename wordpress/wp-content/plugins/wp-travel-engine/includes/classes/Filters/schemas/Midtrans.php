<?php
/**
 * Midtrans Payment Gateway Schema.
 *
 * @since 6.2.0
 */

if ( ! defined( 'WTE_MIDTRANS_ABSPATH' ) || ! file_exists( WTE_MIDTRANS_ABSPATH ) ) {
	return array();
}

return array(
	'midtrans' => array(
		'description' => __( 'Midtrans Settings', 'wp-travel-engine' ),
		'type'        => 'object',
		'properties'  => array(
			'enable_3Ds_secure' => array(
				'description' => __( 'Enable 3D Secure', 'wp-travel-engine' ),
				'type'        => 'boolean',
			),
			'enable_save_card'  => array(
				'description' => __( 'Enable Save Card', 'wp-travel-engine' ),
				'type'        => 'boolean',
			),
			'merchant_id'       => array(
				'description' => __( 'Merchant ID', 'wp-travel-engine' ),
				'type'        => 'string',
			),
			'client_key'        => array(
				'description' => __( 'Client Key', 'wp-travel-engine' ),
				'type'        => 'string',
			),
			'server_key'        => array(
				'description' => __( 'Server Key', 'wp-travel-engine' ),
				'type'        => 'string',
			),
		),
	),
);
