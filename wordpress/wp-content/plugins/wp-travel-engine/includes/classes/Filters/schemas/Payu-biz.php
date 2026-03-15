<?php
/**
 * PayU Biz Payment Gateway Schema.
 *
 * @since 6.2.0
 */

if ( ! defined( 'WP_TRAVEL_ENGINE_PAYU_FILE_PATH' ) || ! file_exists( WP_TRAVEL_ENGINE_PAYU_FILE_PATH ) ) {
	return array();
}

return array(
	'payu_biz' => array(
		'description' => __( 'PayU Biz settings', 'wp-travel-engine' ),
		'type'        => 'object',
		'properties'  => array(
			'merchant_key'  => array(
				'description' => __( 'Merchant Key', 'wp-travel-engine' ),
				'type'        => 'string',
			),
			'merchant_salt' => array(
				'description' => __( 'Merchant Salt', 'wp-travel-engine' ),
				'type'        => 'string',
			),
		),
	),
);
