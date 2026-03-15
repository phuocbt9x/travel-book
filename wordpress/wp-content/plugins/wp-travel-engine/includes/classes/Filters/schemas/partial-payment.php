<?php
/**
 * Partial Payment Addon Schema.
 *
 * @since 6.2.0
 */

if ( ! defined( 'WP_TRAVEL_ENGINE_PARTIAL_PAYMENT_FILE_PATH' ) || ! file_exists( WP_TRAVEL_ENGINE_PARTIAL_PAYMENT_FILE_PATH ) ) {
	return array();
}

return array(
	'partial_payment' => array(
		'description' => __( 'Partial Payment', 'wp-travel-engine' ),
		'type'        => 'object',
		'properties'  => array(
			'enable'              => array(
				'description' => __( 'Partial Payment Enabled or Not', 'wp-travel-engine' ),
				'type'        => 'boolean',
			),
			'payment_type'        => array(
				'description' => __( 'Partial Payment Type', 'wp-travel-engine' ),
				'type'        => 'string',
			),
			'payment_percent'     => array(
				'description' => __( 'Payment Percentage', 'wp-travel-engine' ),
				'type'        => 'string',
			),
			'payment_amount'      => array(
				'description' => __( 'Payment Amount', 'wp-travel-engine' ),
				'type'        => 'string',
			),
			'enable_full_payment' => array(
				'description' => __( 'Full Payment Enabled or Not', 'wp-travel-engine' ),
				'type'        => 'boolean',
			),
		),
	),
);
