<?php
/**
 * Advanced Itinerary Schema.
 *
 * @since 6.2.0
 */

if ( ! defined( 'WTE_ZAPIER_PLUGIN_FILE' ) || ! file_exists( WTE_ZAPIER_PLUGIN_FILE ) ) {
	return array();
}

return array(
	'zapier' => array(
		'description' => __( 'Zapier Integration settings', 'wp-travel-engine' ),
		'type'        => 'object',
		'properties'  => array(
			'enable_automation_booking' => array(
				'description' => __( 'Enable Automation for Bookings', 'wp-travel-engine' ),
				'type'        => 'boolean',
			),
			'enable_automation_enquiry' => array(
				'description' => __( 'Enable Automation for Enquiries', 'wp-travel-engine' ),
				'type'        => 'boolean',
			),
			'booking_zaps'              => array(
				'description' => __( 'List of booking Zaps', 'wp-travel-engine' ),
				'type'        => 'array',
				'items'       => array(
					'type'       => 'object',
					'properties' => array(
						'name' => array(
							'description' => __( 'Zap Name', 'wp-travel-engine' ),
							'type'        => 'string',
						),
						'url'  => array(
							'description' => __( 'Zap URL', 'wp-travel-engine' ),
							'type'        => 'string',
						),
						'id'   => array(
							'description' => __( 'Zap ID', 'wp-travel-engine' ),
							'type'        => 'integer',
						),
					),
				),
			),
			'enquiry_zaps'              => array(
				'description' => __( 'List of enquiry Zaps', 'wp-travel-engine' ),
				'type'        => 'array',
				'items'       => array(
					'type'       => 'object',
					'properties' => array(
						'name' => array(
							'description' => __( 'Zap Name', 'wp-travel-engine' ),
							'type'        => 'string',
						),
						'url'  => array(
							'description' => __( 'Zap URL', 'wp-travel-engine' ),
							'type'        => 'string',
						),
						'id'   => array(
							'description' => __( 'Zap ID', 'wp-travel-engine' ),
							'type'        => 'integer',
						),
					),
				),
			),
		),
	),
);
