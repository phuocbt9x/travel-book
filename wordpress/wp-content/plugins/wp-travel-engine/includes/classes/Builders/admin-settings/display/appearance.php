<?php

/**
 * Appearance Display Settings.
 *
 * @since 6.6.1
 */

return apply_filters(
	'display-appearance',
	array(
		'title'  => __( 'Appearance', 'wp-travel-engine' ),
		'order'  => 5,
		'id'     => 'display_appearance',
		'fields' => array(
			array(
				'field_type' => 'TITLE',
				'title'      => __( 'Color Settings', 'wp-travel-engine' ),
			),
			array(
				'field_type'  => 'COLOR_PICKER',
				'name'        => 'appearance.primary_color',
				'label'       => __( 'Primary Color', 'wp-travel-engine' ),
				'enableReset' => true,
			),
			array(
				'field_type'  => 'COLOR_PICKER',
				'name'        => 'appearance.discount_color',
				'label'       => __( 'Discount Ribbon Color', 'wp-travel-engine' ),
				'enableReset' => true,
			),
			array(
				'field_type'  => 'COLOR_PICKER',
				'name'        => 'appearance.featured_color',
				'label'       => __( 'Featured Ribbon Color', 'wp-travel-engine' ),
				'enableReset' => true,
			),
			array(
				'field_type'  => 'COLOR_PICKER',
				'name'        => 'appearance.icon_color',
				'label'       => __( 'Icon Color', 'wp-travel-engine' ),
				'enableReset' => true,
			),
		),
	),
);
