<?php
/**
 * Group Discount Addon Schema.
 *
 * @since 6.2.0
 */

if ( ! defined( 'WP_TRAVEL_ENGINE_GROUP_DISCOUNT_FILE_PATH' ) || ! file_exists( WP_TRAVEL_ENGINE_GROUP_DISCOUNT_FILE_PATH ) ) {
	return array();
}

return array(
	'group_discount' => array(
		'description' => __( 'Group Discount', 'wp-travel-engine' ),
		'type'        => 'object',
		'properties'  => array(
			'enable'           => array(
				'description' => __( 'Group Discount Enabled or Not', 'wp-travel-engine' ),
				'type'        => 'boolean',
			),
			'info'             => array(
				'description' => __( 'Group Discount Information', 'wp-travel-engine' ),
				'type'        => 'string',
			),
			'guide_title'      => array(
				'description' => __( 'Group Discount Guide Title', 'wp-travel-engine' ),
				'type'        => 'string',
			),
			'guide_open_title' => array(
				'description' => __( 'Group Discount Guide Open Title', 'wp-travel-engine' ),
				'type'        => 'string',
			),
		),
	),

);
