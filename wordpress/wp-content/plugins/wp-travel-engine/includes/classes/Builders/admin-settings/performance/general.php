<?php
/**
 * Performance General Tab Settings.
 *
 * @since 6.2.0
 */

return apply_filters(
	'performance_general_settings',
	array(
		'title'  => __( 'General', 'wp-travel-engine' ),
		'order'  => 5,
		'id'     => 'performance-general',
		'fields' => array(
			array(
				'divider'    => true,
				'label'      => __( 'Optimized Loading<span class="beta-tag"></span>', 'wp-travel-engine' ),
				'help'       => __( 'This feature adds conditional loading of the assets to improve the loading speed.', 'wp-travel-engine' ),
				'field_type' => 'SWITCH',
				'name'       => 'enable_optimized_loading',
				'isBeta'     => true,
			),
			array(
				'divider'    => true,
				'label'      => __( 'Lazy Loading<span class="beta-tag"></span>', 'wp-travel-engine' ),
				'help'       => __( 'This experimental feature will lazy load the different media on your single trip to improve the performance and load your site faster.', 'wp-travel-engine' ),
				'field_type' => 'SWITCH',
				'name'       => 'lazy_loading.enable',
				'isBeta'     => true,
			),
			array(
				'condition'  => 'lazy_loading.enable == true',
				'field_type' => 'GROUP',
				'fields'     => array(
					array(
						'divider'    => true,
						'label'      => __( 'Map Lazy Loading', 'wp-travel-engine' ),
						'help'       => __( 'This feature delays the loading of Map iframe until the user interacts on your site.', 'wp-travel-engine' ),
						'field_type' => 'SWITCH',
						'name'       => 'lazy_loading.enable_map',
					),
					array(
						'label'      => __( 'Image Lazy Loading', 'wp-travel-engine' ),
						'help'       => __( 'This feature will lazy load your images to boost the performance of your site to load faster.', 'wp-travel-engine' ),
						'field_type' => 'SWITCH',
						'name'       => 'lazy_loading.enable_image',
					),
				),
			),
		),
	)
);
