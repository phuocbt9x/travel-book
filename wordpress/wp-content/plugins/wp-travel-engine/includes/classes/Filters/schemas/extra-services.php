<?php
/**
 * Extra Services Schema.
 */

if ( ! defined( 'WTE_EXTRA_SERVICES_FILE_PATH' ) || ! file_exists( WTE_EXTRA_SERVICES_FILE_PATH ) ) {
	return array();
}

return array(
	'extra_services' => array(
		'description' => __( 'Extra Services Settings', 'wp-travel-engine' ),
		'type'        => 'object',
		'properties'  => array(
			'title' => array(
				'description' => __( 'Extra Services Title', 'wp-travel-engine' ),
				'type'        => 'string',
			),
		),
	),
);
