<?php
/**
 * Extensions Fixed Starting Dates Tab Settings.
 *
 * @since 6.2.0
 */
$is_fsd_active     = defined( 'WTE_FIXED_DEPARTURE_FILE_PATH' );
$active_extensions = apply_filters( 'wpte_get_global_extensions_tab', array() );
$file_path         = $active_extensions['wte_fsd']['content_path'] ?? '';
if ( ! file_exists( $file_path ) ) {
	return array();
}
return apply_filters(
	'extension_fixed_starting_dates',
	array(
		'is_active' => $is_fsd_active,
		'title'     => __( 'Fixed Starting Dates', 'wp-travel-engine' ),
		'order'     => 25,
		'id'        => 'extension-fixed-starting-dates',
		'fields'    => array(
			array(
				'field_type' => 'ALERT',
				'content'    => __( 'Need to list all the Fixed Starting Dates? You can use this shortcode <strong> [WTE_TRIPS_FIXED_STARTING_DATES] </strong> on a page/post/tab to display Fixed Starting Dates from all of your trips sorted by Months.', 'wp-travel-engine' ),
			),
			array(
				'divider'    => true,
				'label'      => __( 'Show Fixed Trip Starts Dates section', 'wp-travel-engine' ),
				'help'       => __( 'Check this if you want to enable fixed trip starting dates section between featured image/slider and trip content sections.', 'wp-travel-engine' ),
				'field_type' => 'SWITCH',
				'name'       => 'fsd.enable',
			),
			array(
				'divider'    => true,
				'label'      => __( 'Fixed Starting Dates Section Title', 'wp-travel-engine' ),
				'help'       => __( 'Title for Fixed Starting Dates of the trip.', 'wp-travel-engine' ),
				'field_type' => 'TEXT',
				'name'       => 'fsd.section_title',
			),
			array(
				'divider'    => true,
				'label'      => __( 'Show dates layout from trip cards', 'wp-travel-engine' ),
				'help'       => __( 'Check this if you want to show the availability section from the trip cards in homepage and archive pages.', 'wp-travel-engine' ),
				'field_type' => 'SWITCH',
				'name'       => 'fsd.show_dates_layout',
			),
			array(
				'divider'    => true,
				'label'      => __( 'Show FSD Column: Availability', 'wp-travel-engine' ),
				'help'       => __( 'Check this if you want to show the availability column from the Fixed Starting Dates.', 'wp-travel-engine' ),
				'field_type' => 'SWITCH',
				'name'       => 'fsd.show_availability',
			),
			array(
				'divider'    => true,
				'label'      => __( 'Show FSD Column: Price', 'wp-travel-engine' ),
				'help'       => __( 'Check this if you want to show the price column from the Fixed Starting Dates.', 'wp-travel-engine' ),
				'field_type' => 'SWITCH',
				'name'       => 'fsd.show_price',
			),
			array(
				'divider'    => true,
				'label'      => __( 'Show FSD Column: Space left', 'wp-travel-engine' ),
				'help'       => __( 'Check this if you want to show the Space Left column from the Fixed Starting Dates.', 'wp-travel-engine' ),
				'field_type' => 'SWITCH',
				'name'       => 'fsd.show_space_left',
			),
			// array(
			// 'title'      => __( 'Select The Dates Layout', 'wp-travel-engine' ),
			// 'field_type' => 'TITLE',
			// ),
			// array(
			// 'divider'     => true,
			// 'description' => __( 'Choose a dates list or months layout to display in taxonomy pages.', 'wp-travel-engine' ),
			// 'field_type'  => 'IMAGE_SELECTOR',
			// 'options'     => array(
			// array(
			// 'label' => __( '1. Show dates list', 'wp-travel-engine' ),
			// 'value' => 'dates_list',
			// 'image' => WP_TRAVEL_ENGINE_URL . '/assets/images/dates-list.png',
			// ),
			// array(
			// 'label' => __( '2. Show months list', 'wp-travel-engine' ),
			// 'value' => 'months_list',
			// 'image' => WP_TRAVEL_ENGINE_URL . '/assets/images/months-list.png',
			// ),
			// ),
			// 'default'     => 'dates_list',
			// 'name'        => 'fsd.date_layout',
			// ),
			array(
				'divider'    => true,
				'label'      => __( 'Number of Trip Dates', 'wp-travel-engine' ),
				'help'       => __( 'Use this option to set number of trip fixed starting dates to show in the homepage sections.', 'wp-travel-engine' ),
				'field_type' => 'NUMBER',
				'default'    => 3,
				'name'       => 'fsd.number_of_dates',
			),
			array(
				'divider'    => true,
				'label'      => __( 'Pagination Number', 'wp-travel-engine' ),
				'help'       => __( 'Use this option to set number of trip fixed starting dates to show per page in the date listings through shortcode and tabs.', 'wp-travel-engine' ),
				'field_type' => 'NUMBER',
				'default'    => 10,
				'name'       => 'fsd.number_of_pagination',
			),
			array(
				'title'       => __( 'Trip Visibility Settings', 'wp-travel-engine' ),
				'description' => __( 'Display trips on the archive page according to specific conditions.', 'wp-travel-engine' ),
				'field_type'  => 'TITLE',
				'divider'     => true,
			),
			array(
				'label'      => __( 'Show trips with fixed starting dates', 'wp-travel-engine' ),
				'help'       => __( 'Enable this feature to show only trips that have fixed starting dates.', 'wp-travel-engine' ),
				'field_type' => 'SWITCH',
				'name'       => 'fsd.show_without_fsd',
				'divider'    => true,
			),
			array(
				'label'      => __( 'Show trips with available dates', 'wp-travel-engine' ),
				'help'       => __( 'Enable this feature to show any trips with available dates extending beyond a specified number of days.', 'wp-travel-engine' ),
				'field_type' => 'SWITCH',
				'name'       => 'fsd.show_with_available_dates',
				'divider'    => true,
			),
			array(
				'condition'  => 'fsd.show_with_available_dates === false',
				'field_type' => 'GROUP',
				'fields'     => array(
					array(
						'label'      => __( 'Number of days', 'wp-travel-engine' ),
						'help'       => __( '1 = Today, 2 = Tomorrow, and so on.', 'wp-travel-engine' ),
						'field_type' => 'NUMBER',
						'default'    => 3,
						'name'       => 'fsd.number_of_days',
					),
				),
			),
		),
	)
);
