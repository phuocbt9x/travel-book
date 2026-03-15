<?php
/**
 * Global Trips Settings.
 *
 * @since v6.6.4
 */

return apply_filters(
	'global',
	array(
		'title'  => __( 'Trip Settings', 'wp-travel-engine' ),
		'order'  => 20,
		'id'     => 'trip-settings',
		'fields' => array(
			array(
				'field_type' => 'TAB',
				'tabs'       => array(
					array(
						'title'  => __( 'Trip Tabs', 'wp-travel-engine' ),
						'id'     => 'trip-tabs',
						'fields' => array(
							array(
								'field_type' => 'TRIP_TABS',
								'name'       => 'trip_tabs',
							),
						),
					),
					array(
						'title'  => __( 'Trip Info', 'wp-travel-engine' ),
						'id'     => 'trip-info',
						'fields' => array(
							array(
								'field_type' => 'TRIP_INFO_TABS',
								'name'       => 'trip_info',
							),
						),
					),
					array(
						'title'  => __( 'Highlights', 'wp-travel-engine' ),
						'id'     => 'highlights',
						'fields' => array(
							array(
								'field_type' => 'HIGHLIGHTS',
								'name'       => 'highlights',
							),
						),
					),
					array(
						'title'  => __( 'Pricing Type', 'wp-travel-engine' ),
						'id'     => 'pricing-type',
						'fields' => array(
							array(
								'field_type' => 'PRICING_TYPE',
								'name'       => 'pricing_type',
							),
						),
					),
				),
			),
		),
	)
);
