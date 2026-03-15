<?php
/**
 * Payhere Payment Gateway Schema.
 *
 * @since 6.2.0
 */

if ( ! defined( 'WTE_PAYHERE_PAYMENTS_FILE_PATH' ) || ! file_exists( WTE_PAYHERE_PAYMENTS_FILE_PATH ) ) {
	return array();
}

return array(
	'payhere' => array(
		'description' => __( 'PayU Biz settings', 'wp-travel-engine' ),
		'type'        => 'object',
		'properties'  => array(
			'merchant_id'            => array(
				'description' => __( 'Merchant Id', 'wp-travel-engine' ),
				'type'        => 'string',
			),
			'merchant_secret'        => array(
				'description' => __( 'Merchant Secret', 'wp-travel-engine' ),
				'type'        => 'string',
			),
			'enable_onsite_checkout' => array(
				'description' => __( 'Merchant Secret', 'wp-travel-engine' ),
				'type'        => 'boolean',
			),
		),
	),
);
