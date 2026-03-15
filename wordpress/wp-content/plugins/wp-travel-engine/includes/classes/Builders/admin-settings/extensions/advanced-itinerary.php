<?php

/**
 * Extensions Advanced Itinerary Tab Settings.
 *
 * @since 6.2.0
 */
$is_advanced_itinerary_active = defined( 'WTEAD_FILE_PATH' );
$active_extensions            = apply_filters( 'wpte_get_global_extensions_tab', array() );
$file_path                    = $active_extensions['wte_advance_itinerary']['content_path'] ?? '';
if ( ! file_exists( $file_path ) ) {
	return array();
}
return apply_filters(
	'extension_advanced_itinerary',
	array(
		'is_active' => $is_advanced_itinerary_active,
		'title'     => __( 'Advanced Itinerary Builder', 'wp-travel-engine' ),
		'order'     => 5,
		'id'        => 'extension-advanced-itinerary-builder',
		'fields'    => array(
			array(
				'divider'    => true,
				'label'      => __( 'Always Show All Itinerary', 'wp-travel-engine' ),
				'help'       => __( 'Default: All hidden. Enable this option to always expand all itinerary on initial page load.', 'wp-travel-engine' ),
				'field_type' => 'SWITCH',
				'name'       => 'advanced_itinerary.enable_all_itinerary',
			),
			array(
				'field_type' => 'TITLE',
				'title'      => __( 'Sleep Mode Fields for Itinerary', 'wp-travel-engine' ),
			),
			array(
				'field_type'  => 'FIELD_HEADER',
				'label'       => __( 'Option Text<span class="required">*</span>', 'wp-travel-engine' ),
				'description' => __( 'Field option value to be displayed in Sleep Mode Select Field in trip page. This text will also be displayed in front as a sleeping mode in each itinerary.', 'wp-travel-engine' ),
			),
			array(
				'field_type' => 'SLEEP_MODE',
				'name'       => 'advanced_itinerary.sleep_mode_fields',
			),
			array(
				'field_type' => 'ALERT',
				'content'    => __( 'You can set various sleep modes on particular day\'s trip from above setting. You can add various means of accommodations such as hotel, tent, camping, homestay etc. for specific day.', 'wp-travel-engine' ),
			),
			array(
				'field_type' => 'ITINERARY_CHART',
				'name'       => 'advanced_itinerary.chart',
			),
		),
	)
);
