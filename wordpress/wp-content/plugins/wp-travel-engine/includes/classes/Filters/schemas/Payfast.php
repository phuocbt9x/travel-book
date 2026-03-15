<?php
/**
 * Payfast Payment Gateway Schema.
 *
 * @since 6.2.0
 */

if ( ! defined( 'WP_TRAVEL_ENGINE_PAYFAST_FILE_PATH' ) || ! file_exists( WP_TRAVEL_ENGINE_PAYFAST_FILE_PATH ) ) {
	return array();
}

return array(
	'payfast' => array(
		'description' => __( 'PayU Biz settings', 'wp-travel-engine' ),
		'type'        => 'object',
		'properties'  => array(
			'merchant_id'  => array(
				'description' => __( 'Merchant Id', 'wp-travel-engine' ),
				'type'        => 'string',
			),
			'merchant_key' => array(
				'description' => __( 'Merchant Key', 'wp-travel-engine' ),
				'type'        => 'string',
			),
		),
	),
);
