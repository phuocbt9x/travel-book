<?php
/**
 * Advanced Itinerary Schema.
 *
 * @since 6.2.0
 */

if ( ! defined( 'WTEAD_FILE_PATH' ) || ! file_exists( WTEAD_FILE_PATH ) ) {
	return array();
}

return array(
	'advanced_itinerary' => array(
		'description' => __( 'Advanced Itinerary settings', 'wp-travel-engine' ),
		'type'        => 'object',
		'properties'  => array(
			'enable_all_itinerary' => array(
				'description' => __( 'Enable All Itinerary', 'wp-travel-engine' ),
				'type'        => 'boolean',
			),
			'sleep_mode_fields'    => array(
				'description' => __( 'Sleep Mode Fields', 'wp-travel-engine' ),
				'type'        => 'array',
				'items'       => array(
					'type' => 'string',
				),
			),
			'chart'                => array(
				'description' => __( 'Chart', 'wp-travel-engine' ),
				'type'        => 'object',
				'properties'  => array(
					'enable'            => array(
						'description' => __( 'Enable', 'wp-travel-engine' ),
						'type'        => 'boolean',
					),
					'elevation_unit'    => array(
						'description' => __( 'Elevation Unit', 'wp-travel-engine' ),
						'type'        => 'string',
					),
					'enable_x_axis'     => array(
						'description' => __( 'Enable X Axis', 'wp-travel-engine' ),
						'type'        => 'boolean',
					),
					'enable_y_axis'     => array(
						'description' => __( 'Enable Y Axis', 'wp-travel-engine' ),
						'type'        => 'boolean',
					),
					'enable_line_graph' => array(
						'description' => __( 'Enable Line Graph', 'wp-travel-engine' ),
						'type'        => 'boolean',
					),
					'color'             => array(
						'description' => __( 'Color', 'wp-travel-engine' ),
						'type'        => 'string',
					),
					'background_image'  => array(
						'description' => __( 'Background Image', 'wp-travel-engine' ),
						'type'        => 'object',
						'properties'  => array(
							'id'  => array(
								'description' => __( 'ID', 'wp-travel-engine' ),
								'type'        => 'integer',
							),
							'alt' => array(
								'description' => __( 'Alternative String', 'wp-travel-engine' ),
								'type'        => 'string',
							),
							'url' => array(
								'description' => __( 'URL', 'wp-travel-engine' ),
								'type'        => 'string',
							),
						),
					),
				),
			),
		),
	),
);
