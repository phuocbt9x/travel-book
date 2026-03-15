<?php
/**
 * HBL Payment Gateway Schema.
 *
 * @since 6.2.0
 */

if ( ! defined( 'WP_TRAVEL_ENGINE_HBL_FILE_PATH' ) || ! file_exists( WP_TRAVEL_ENGINE_HBL_FILE_PATH ) ) {
	return array();
}

return array(
	'hbl' => array(
		'description' => __( 'HBL Payment', 'wp-travel-engine' ),
		'type'        => 'object',
		'properties'  => array(
			'office_id'             => array(
				'description' => __( 'Office Id', 'wp-travel-engine' ),
				'type'        => 'string',
			),
			'api_key'               => array(
				'description' => __( 'Api Key', 'wp-travel-engine' ),
				'type'        => 'string',
			),
			'encryption_key_id'     => array(
				'description' => __( 'Encryption Key Id', 'wp-travel-engine' ),
				'type'        => 'string',
			),
			'merchant_private_keys' => array(
				'description' => __( 'Merchant Private Key', 'wp-travel-engine' ),
				'type'        => 'object',
				'properties'  => array(
					'signing_key'    => array(
						'description' => __( 'Signing Key', 'wp-travel-engine' ),
						'type'        => 'string',
					),
					'decryption_key' => array(
						'description' => __( 'Decryption Key', 'wp-travel-engine' ),
						'type'        => 'string',
					),
				),
			),
			'paco_public_keys'      => array(
				'description' => __( 'Paco Public Key', 'wp-travel-engine' ),
				'type'        => 'object',
				'properties'  => array(
					'signing_key'    => array(
						'description' => __( 'Signing Key', 'wp-travel-engine' ),
						'type'        => 'string',
					),
					'encryption_key' => array(
						'description' => __( 'Encryption Key', 'wp-travel-engine' ),
						'type'        => 'string',
					),
				),
			),
			'notification_urls'     => array(
				'description' => __( 'Notification Urls', 'wp-travel-engine' ),
				'type'        => 'object',
				'properties'  => array(
					'confirmation_url' => array(
						'description' => __( 'Confirmation Url', 'wp-travel-engine' ),
						'type'        => 'string',
					),
					'cancellation_url' => array(
						'description' => __( 'Cancellation Url', 'wp-travel-engine' ),
						'type'        => 'string',
					),
					'failure_url'      => array(
						'description' => __( 'Failure Url', 'wp-travel-engine' ),
						'type'        => 'string',
					),
					'notify_url'       => array(
						'description' => __( 'Notification Url', 'wp-travel-engine' ),
						'type'        => 'string',
					),
				),
			),
		),
	),
);
