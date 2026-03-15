<?php

/**
 * Trip Archive Display Settings.
 *
 * @since 6.2.0
 */
$site_url           = get_site_url();
$trip_page_url      = $site_url . '/trip/';
$difficulty_tax_url = $site_url . '/wp-admin/edit-tags.php?taxonomy=difficulty&post_type=trip';
$tag_tax_url        = $site_url . '/wp-admin/edit-tags.php?taxonomy=trip_tag&post_type=trip';
$is_fsd_active      = defined( 'WTE_FIXED_DEPARTURE_FILE_PATH' );
$sorting_options    = wptravelengine_get_sorting_options();
$sort_trip_by       = array();

foreach ( $sorting_options as $key => $value ) {
	if ( is_array( $value ) && isset( $value['options'] ) ) {
		$group = array(
			'label'   => $value['label'],
			'options' => array(),
		);
		foreach ( $value['options'] as $k => $v ) {
			$group['options'][] = array(
				'label' => $v,
				'value' => $k,
			);
		}
		$sort_trip_by[] = $group;
	} else {
		$sort_trip_by[] = array(
			'label' => $value,
			'value' => $key,
		);
	}
}

return apply_filters(
	'display-trip-archive',
	array(
		'title'  => __( 'Trip Archive', 'wp-travel-engine' ),
		'order'  => 15,
		'id'     => 'display-trip-archive',
		'fields' => array(
			array(
				'field_type' => 'TAB',
				'tabs'       => array(
					array(
						'title'  => __( 'Trip Archive', 'wp-travel-engine' ),
						'id'     => 'trip-archive-page',
						'fields' => apply_filters(
							'trip_archive_page_settings_tab',
							array(
								array(
									'field_type' => 'ALERT',
									'content'    => sprintf( __( 'The settings you configure here will apply to the Trip Archive page, all Trip Taxonomy pages (such as destinations, activities, or trip types), and the Search Results page.', 'wp-travel-engine' ), $trip_page_url ),
									'status'     => 'info',
								),
								array(
									'field_type' => 'DIVIDER',
								),
								array(
									'label'       => __( 'Show Archive Title', 'wp-travel-engine' ),
									'description' => __( 'The Archive titles (Destination, Trip Types, Activities, etc) will display if you enable this feature.', 'wp-travel-engine' ),
									'field_type'  => 'SWITCH',
									'name'        => 'enable_archive_title',
								),
								array(
									'field_type' => 'GROUP',
									'condition'  => 'enable_archive_title === true',
									'fields'     => array(
										array(
											'label'      => __( 'Title Type', 'wp-travel-engine' ),
											'help'       => __( 'Choose whether to display default page title or custom title.', 'wp-travel-engine' ),
											'field_type' => 'SELECT_BUTTON',
											'options'    => array(
												array(
													'label' => __( 'Default', 'wp-travel-engine' ),
													'value' => 'default',
												),
												array(
													'label' => __( 'Custom', 'wp-travel-engine' ),
													'value' => 'custom',
												),
											),
											'name'       => 'archives.title_type',
										),
										array(
											'label'       => __( 'Customize Archive Title', 'wp-travel-engine' ),
											'description' => __( 'Customize the Archive titles (Archives: Trips).', 'wp-travel-engine' ),
											'condition'   => 'archives.title_type === custom',
											'field_type'  => 'TEXT',
											'name'        => 'archives.title',
										),
									),
								),
								array(
									'field_type' => 'DIVIDER',
								),
								array(
									'label'       => __( 'Sort Trips By', 'wp-travel-engine' ),
									'description' => __( 'Choose the sorting type in which trips should be listed on archive pages.', 'wp-travel-engine' ),
									'field_type'  => 'SELECT',
									'options'     => $sort_trip_by,
									'divider'     => true,
									'name'        => 'sort_trips_by',
								),
								array(
									'label'       => __( 'Trip View Mode', 'wp-travel-engine' ),
									'description' => __( 'Choose the view mode: List|Grid.', 'wp-travel-engine' ),
									'field_type'  => 'SELECT_BUTTON',
									'options'     => array(
										array(
											'label' => __( 'List', 'wp-travel-engine' ),
											'value' => 'list',
										),
										array(
											'label' => __( 'Grid', 'wp-travel-engine' ),
											'value' => 'grid',
										),
									),
									'divider'     => true,
									'name'        => 'trip_view_mode',
								),
								array(
									'label'       => __( 'Show featured trips on top', 'wp-travel-engine' ),
									'description' => '',
									'field_type'  => 'SWITCH',
									'name'        => 'featured_trips.enable',
								),
								array(
									'field_type' => 'GROUP',
									'condition'  => 'featured_trips.enable === true',
									'fields'     => array(
										array(
											'label'       => __( 'Number of Featured Trips', 'wp-travel-engine' ),
											'description' => __( 'Set the number of featured trips to show in the archive pages.', 'wp-travel-engine' ),
											'field_type'  => 'NUMBER',
											'default'     => '2',
											'min'         => 0,
											'name'        => 'featured_trips.number',
										),
									),
								),
								array(
									'field_type' => 'DIVIDER',
								),
								array(
									'label'      => __( 'Show Sidebar Filters', 'wp-travel-engine' ),
									'field_type' => 'SWITCH',
									'name'       => 'show_sidebar',
									'isNew'      => version_compare( WP_TRAVEL_ENGINE_VERSION, '6.6.5', '<' ),
									'divider'    => true,
								),
								array(
									'label'      => __( 'Display', 'wp-travel-engine' ),
									'field_type' => 'SELECT_BUTTON',
									'isNew'      => version_compare( WP_TRAVEL_ENGINE_VERSION, '6.6.5', '<' ),
									'name'       => 'display_mode',
									'divider'    => true,
									'options'    => array(
										array(
											'label' => __( 'Pagination', 'wp-travel-engine' ),
											'value' => 'pagination',
										),
										array(
											'label' => __( 'Load More', 'wp-travel-engine' ),
											'value' => 'load_more',
										),
									),
								),

							// array(
							// 'label'       => __('Show Archives: Trips Title', 'wp-travel-engine'),
							// 'description' => __('Enabling this feature will show the Archive title (Archives: Trips).', 'wp-travel-engine'),
							// 'field_type'  => 'SWITCH',
							// 'name'        => 'archives.enable_title',
							// 'divider'     => true,
							// ),
							// array(
							// 'label'       => __( 'Advance Search Panel', 'wp-travel-engine' ),
							// 'description' => __( 'Enable advance search panel for smaller devices in archive pages.', 'wp-travel-engine' ),
							// 'field_type'  => 'SWITCH',
							// 'name'        => 'archives.enable_advance_search',
							// 'divider'     => true,
							// ),
							// array(
							// 'label'       => __( 'Toggle Criteria Filter Display', 'wp-travel-engine' ),
							// 'description' => __( 'Enabling this feature will display each filter option as a requirement on the archive page, allowing users to toggle the visibility of criteria filters.', 'wp-travel-engine' ),
							// 'field_type'  => 'SWITCH',
							// 'name'        => 'enable_criteria_filter',
							// ),
							)
						),
					),
					array(
						'title'  => __( 'Trip Card Display', 'wp-travel-engine' ),
						'id'     => 'trip-card-listing',
						'fields' => apply_filters(
							'trip_archive_card_settings_tab',
							array(
								array(
									'field_type' => 'ALERT',
									'content'    => __( 'The settings you configure here will apply to the Trip Archive page, all Trip Taxonomy pages (such as destinations, activities, or trip types), and the Search Results page.', 'wp-travel-engine' ),
									'type'       => 'info',
								),
								array(
									'field_type' => 'DIVIDER',
								),
								// array(
								// 'label'       => __( 'New Trip Layout', 'wp-travel-engine' ),
								// 'description' => __( 'Enable to display new design in trip listing page.', 'wp-travel-engine' ),
								// 'field_type'  => 'SWITCH',
								// 'name'        => 'card_new_layout.enable',
								// ),
								array(
									'divider'     => true,
									'label'       => __( 'Show Slider', 'wp-travel-engine' ),
									'description' => '',
									'field_type'  => 'SWITCH',
									'name'        => 'card_new_layout.enable_slider',
								),
								array(
									'divider'    => true,
									'label'      => __( 'Show Featured Tag on Card', 'wp-travel-engine' ),
									'field_type' => 'SWITCH',
									'name'       => 'card_new_layout.enable_featured_tag',
								),
								array(
									'divider'     => true,
									'label'       => __( 'Show Wishlist', 'wp-travel-engine' ),
									'description' => '',
									'field_type'  => 'SWITCH',
									'name'        => 'card_new_layout.enable_wishlist',
								),
								array(
									'divider'     => true,
									'label'       => __( 'Show Map', 'wp-travel-engine' ),
									'description' => '',
									'field_type'  => 'SWITCH',
									'name'        => 'card_new_layout.enable_map',
								),
								array(
									'label'      => __( 'Show Excerpt', 'wp-travel-engine' ),
									'field_type' => 'SWITCH',
									'name'       => 'card_new_layout.enable_excerpt',
									'divider'    => true,
								),
								array(
									'divider'     => true,
									'label'       => __( 'Show Difficulty', 'wp-travel-engine' ),
									'description' => sprintf( __( 'Click <a href="%s">here</a> to add difficulty level.', 'wp-travel-engine' ), $difficulty_tax_url ),
									'field_type'  => 'SWITCH',
									'name'        => 'card_new_layout.enable_difficulty',
								),
								array(
									'divider'     => true,
									'label'       => __( 'Show Tag', 'wp-travel-engine' ),
									'description' => sprintf( __( 'Click <a href="%s">here</a> to add a tag.', 'wp-travel-engine' ), $tag_tax_url ),
									'field_type'  => 'SWITCH',
									'name'        => 'card_new_layout.enable_tags',
								),
								array(
									'divider'    => true,
									'label'      => __( 'Show Next Departure Dates', 'wp-travel-engine' ),
									'field_type' => 'SWITCH',
									'name'       => 'card_new_layout.enable_fsd',
								),
								array(
									'label'      => __( 'Show Available Months', 'wp-travel-engine' ),
									'field_type' => 'SWITCH',
									'name'       => 'card_new_layout.enable_available_months',
								),
								array(
									'field_type' => 'GROUP',
									'visibility' => $is_fsd_active === true,
									'condition'  => 'card_new_layout.enable_available_months === true',
									'fields'     => array(
										array(
											'label'       => __( 'Show Available Dates', 'wp-travel-engine' ),
											'description' => __( 'Enable to show available dates on hover.', 'wp-travel-engine' ),
											'field_type'  => 'SWITCH',
											'name'        => 'card_new_layout.enable_available_dates',
										),
									),
								),
								array(
									'field_type' => 'DIVIDER',
								),
								array(
									'label'       => __( 'Trip Duration', 'wp-travel-engine' ),
									'description' => __( 'Choose how the trip duration should be displayed, not applicable for hourly trips.', 'wp-travel-engine' ),
									'field_type'  => 'SELECT_BUTTON',
									'name'        => 'trip_duration_label_on_card',
									'default'     => 'days',
									'options'     => array(
										array(
											'value' => 'both',
											'label' => __( 'Days and Nights', 'wp-travel-engine' ),
										),
										array(
											'value' => 'days',
											'label' => __( 'Days', 'wp-travel-engine' ),
										),
										array(
											'value' => 'nights',
											'label' => __( 'Nights', 'wp-travel-engine' ),
										),
									),
								),
							)
						),
					),
					array(
						'title'  => __( 'Trip Taxonomy', 'wp-travel-engine' ),
						'id'     => 'trip-taxonomy',
						'fields' => apply_filters(
							'trip_archive_taxonomy_settings_tab',
							array(
								array(
									'field_type' => 'ALERT',
									'content'    => __( 'Taxonomy settings you configure here will apply to Destination, Activities and Trip Types listing pages.', 'wp-travel-engine' ),
									'type'       => 'info',
								),
								array(
									'field_type' => 'DIVIDER',
								),
								array(
									'label'       => __( 'Show Taxonomy Image', 'wp-travel-engine' ),
									'description' => __( 'Enable to show taxonomy image in the taxonomy page.', 'wp-travel-engine' ),
									'field_type'  => 'SWITCH',
									'name'        => 'taxonomy.enable_image',
									'divider'     => true,
								),
								array(
									'label'       => __( 'Show Taxonomy Children Terms', 'wp-travel-engine' ),
									'description' => __( 'If checked, the terms with parent will be shown on the taxonomy archive page. This term children are not displayed in default.', 'wp-travel-engine' ),
									'field_type'  => 'SWITCH',
									'name'        => 'taxonomy.enable_children_terms',
									'divider'     => true,
								),
							)
						),
					),
				),
			),
		),
	),
);
