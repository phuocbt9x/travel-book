<?php
/**
 * Authorize.Net Payment Gateway Schema.
 *
 * @since 6.2.0
 */

if ( ! defined( 'WP_TRAVEL_ENGINE_AUTHORIZE_NET_FILE_PATH' ) || ! file_exists( WP_TRAVEL_ENGINE_AUTHORIZE_NET_FILE_PATH ) ) {
	return array();
}

return array(
	'authorize_net' => array(
		'description' => __( 'Authorize.Net Settings', 'wp-travel-engine' ),
		'type'        => 'object',
		'properties'  => array(
			'api_login_id'    => array(
				'description' => __( 'API Login ID', 'wp-travel-engine' ),
				'type'        => 'string',
			),
			'transaction_key' => array(
				'description' => __( 'Transaction Key', 'wp-travel-engine' ),
				'type'        => 'string',
			),
		),
	),
);
