<?php
/**
 * Stripe Payment Gateway Schmea.
 *
 * @since 6.2.0
 */

if ( ! defined( 'WTE_STRIPE_GATEWAY_FILE_PATH' ) || ! file_exists( WTE_STRIPE_GATEWAY_FILE_PATH ) ) {
	return array();
}

return array(
	'stripe' => array(
		'description' => __( 'Stripe Settings', 'wp-travel-engine' ),
		'type'        => 'object',
		'properties'  => array(
			'secret_key'         => array(
				'description' => __( 'Secret Key', 'wp-travel-engine' ),
				'type'        => 'string',
			),
			'publishable_key'    => array(
				'description' => __( 'Publishable Key', 'wp-travel-engine' ),
				'type'        => 'string',
			),
			'pay_btn_label'      => array(
				'description' => __( 'Pay Button Label', 'wp-travel-engine' ),
				'type'        => 'string',
			),
			'enable_postal_code' => array(
				'description' => __( 'Enable Postal Code', 'wp-travel-engine' ),
				'type'        => 'boolean',
			),
		),
	),
);
