<?php
/**
 * Paypal Express Payment Gateway Schema.
 *
 * @since 6.2.0
 */

if ( ! defined( 'WP_TRAVEL_ENGINE_PAYPAL_EXPRESS_FILE_PATH' ) || ! file_exists( WP_TRAVEL_ENGINE_PAYPAL_EXPRESS_FILE_PATH ) ) {
	return array();
}

return array(
	'paypal_express' => array(
		'description' => __( 'Paypal Express Settings', 'wp-travel-engine' ),
		'type'        => 'object',
		'properties'  => array(
			'client_id'       => array(
				'description' => __( 'Client ID', 'wp-travel-engine' ),
				'type'        => 'string',
			),
			'client_secret'   => array(
				'description' => __( 'Client Secret', 'wp-travel-engine' ),
				'type'        => 'string',
			),
			'disable_funding' => array(
				'description' => __( 'Disable Funding', 'wp-travel-engine' ),
				'type'        => 'array',
				'items'       => array(
					'type' => 'string',
				),
			),
		),
	),
);
