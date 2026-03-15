<?php
/**
 * Fixed Starting Date Add On Schema.
 *
 * @since 6.2.0
 */

if ( ! defined( 'WTE_FIXED_DEPARTURE_FILE_PATH' ) || ! file_exists( WTE_FIXED_DEPARTURE_FILE_PATH ) ) {
	return array();
}

return array(
	'fsd' => array(
		'description' => __( 'Fixed Starting Dates', 'wp-travel-engine' ),
		'type'        => 'object',
		'properties'  => array(
			'enable'                    => array(
				'description' => __( 'Fixed Starting Dates Enabled or Not', 'wp-travel-engine' ),
				'type'        => 'boolean',
			),
			'section_title'             => array(
				'description' => __( 'Fixed Starting Dates Section Title', 'wp-travel-engine' ),
				'type'        => 'string',
			),
			'show_dates_layout'         => array(
				'description' => __( 'Show Dates Layout', 'wp-travel-engine' ),
				'type'        => 'boolean',
			),
			'show_availability'         => array(
				'description' => __( 'Show Availability', 'wp-travel-engine' ),
				'type'        => 'boolean',
			),
			'show_price'                => array(
				'description' => __( 'Show Price', 'wp-travel-engine' ),
				'type'        => 'boolean',
			),
			'show_space_left'           => array(
				'description' => __( 'Show Space Left', 'wp-travel-engine' ),
				'type'        => 'boolean',
			),
			'date_layout'               => array(
				'description' => __( 'Date Layout', 'wp-travel-engine' ),
				'type'        => 'string',
			),
			'number_of_dates'           => array(
				'description' => __( 'Number of Dates', 'wp-travel-engine' ),
				'type'        => 'string',
			),
			'number_of_pagination'      => array(
				'description' => __( 'Number of Pagination', 'wp-travel-engine' ),
				'type'        => 'string',
			),
			'show_without_fsd'          => array(
				'description' => __( 'Show Trip Without FSD', 'wp-travel-engine' ),
				'type'        => 'boolean',
			),
			'show_with_available_dates' => array(
				'description' => __( 'Show Trip With Available dates', 'wp-travel-engine' ),
				'type'        => 'boolean',
			),
			'number_of_days'            => array(
				'description' => __( 'Number of days', 'wp-travel-engine' ),
				'type'        => 'string',
			),
		),
	),
);
