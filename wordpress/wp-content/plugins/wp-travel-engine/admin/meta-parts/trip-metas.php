<?php
/**
 * Trip Metas Tabs HTML
 *
 * @package WP_Travel_Engine
 */

$global_settings = wptravelengine_settings()->get();

$all_pricing_categories = \WPTravelEngine\Packages\get_packages_pricing_categories();
foreach ( $all_pricing_categories as $pricing_category ) {
	$pricing_categories[] = array(
		'id'    => (int) $pricing_category->term_id,
		'value' => (int) $pricing_category->term_id,
		'label' => (string) $pricing_category->name,
	);
}

$global_trip_facts = wptravelengine_get_trip_facts_options();
$trip_facts        = array(
	array(
		'label' => __( 'Select Trip Fact', 'wp-travel-engine' ),
		'value' => '',
	),
);
foreach ( $global_trip_facts['field_id'] ?? array() as $key => $label ) {
	if ( ( $global_trip_facts['enabled'][ $key ] ?? 'no' ) === 'no' ) {
		continue;
	}
	$trip_facts[] = array(
		'id'          => (int) $key ?? 0,
		'label'       => (string) $label ?? '',
		'type'        => (string) $global_trip_facts['field_type'][ $key ] ?? '',
		'placeholder' => (string) $global_trip_facts['input_placeholder'][ $key ] ?? '',
		'options'     => (array) explode( ',', $global_trip_facts['select_options'][ $key ] ?? '' ),
	);
}

$sleep_mode_options = array(
	array(
		'label' => __( 'Select Sleep Mode', 'wp-travel-engine' ),
		'value' => '',
	),
);
foreach ( $global_settings['wte_advance_itinerary']['itinerary_sleep_mode_fields'] ?? array() as $sleep_modes ) {
	$sleep_mode_options[] = array(
		'label' => $sleep_modes['field_text'] ?? '',
		'value' => $sleep_modes['field_text'] ?? '',
	);
}

$trip_meta_tabs = array(
	'wpte-general'         => array(
		'tab_label'         => esc_html__( 'General', 'wp-travel-engine' ),
		'tab_heading'       => esc_html__( 'General', 'wp-travel-engine' ),
		'content_path'      => plugin_dir_path( __FILE__ ) . '/trip-tabs/general.php',
		'callback_function' => 'wpte_edit_trip_tab_general',
		'content_key'       => 'wpte-general',
		'current'           => true,
		'content_loaded'    => true,
		'priority'          => 10,
		'icon'              => 'info',
		'fields'            => array(
			array(
				'label'   => __( 'Trip Code', 'wp-travel-engine' ),
				'divider' => true,
				'field'   => array(
					'name'        => 'trip_code',
					'type'        => 'TEXT',
					'placeholder' => __( 'Enter Trip Code', 'wp-travel-engine' ),
				),
			),
			array(
				'description' => __( 'Enter the duration ( number ) for the trip and choose desired unit.', 'wp-travel-engine' ),
				'label'       => __( 'Duration', 'wp-travel-engine' ),
				'field'       => array(
					'type' => 'GROUP',
				),
				'sub_fields'  => array(
					array(
						'field' => array(
							'name'       => 'duration.period',
							'type'       => 'NUMBER',
							'attributes' => array(
								'style' => array( 'width' => '80px' ),
							),
							'suffix'     => array(
								'type'    => 'field',
								'variant' => 'solid',
								'field'   => array(
									'type'    => 'SELECT',
									'name'    => 'duration.unit',
									'options' => array(
										array(
											'label' => __( 'Days', 'wp-travel-engine' ),
											'value' => 'days',
										),
										array(
											'label' => __( 'Hours', 'wp-travel-engine' ),
											'value' => 'hours',
										),
									),
								),
							),
						),
					),
					array(
						'condition' => 'duration.unit == days',
						'field'     => array(
							'name'       => 'duration.nights',
							'type'       => 'NUMBER',
							'min'        => 0,
							'attributes' => array(
								'min'   => array(
									'value'   => 0,
									'message' => __( 'Minimum value must be greater than 0', 'wp-travel-engine' ),
								),
								'style' => array( 'width' => '80px' ),
							),
							'suffix'     => array(
								'type'    => 'field',
								'field'   => array(
									'defaultValue' => __( 'Night(s)', 'wp-travel-engine' ),
									'type'         => 'TEXT',
									'readOnly'     => true,
								),
								'variant' => 'solid',
							),
						),
					),
				),
			),
			array(
				'visibility' => ! wptravelengine_is_addon_active( 'activity-tour' ),
				'field'      => array(
					'type'    => 'ALERT',
					'content' => __( 'Do you offer activity-based or single-day tours? The Activity Tour Booking extension makes it easy to create and showcase these experiences with detailed itineraries, real-time availability, and seamless booking. <a href="https://wptravelengine.com/plugins/activity-tour-booking/" target="_blank"><strong>Get Activity Tour extension now!</strong></a>', 'wp-travel-engine' ),
					'status'  => 'upgrade',
				),
			),
			array(
				'field' => array(
					'type' => 'DIVIDER',
				),
			),
			array(
				'label'       => __( 'Enable Cut-off Time', 'wp-travel-engine' ),
				'description' => __( 'The cut-off time will be the time before which bookings are allowed for the trip.', 'wp-travel-engine' ),
				'field'       => array(
					'name' => 'cut_off_time.enable',
					'type' => 'SWITCH',
				),
			),
			array(
				'condition'   => 'cut_off_time.enable == true',
				'label'       => true,
				'description' => __( 'Enter trip cut-off value in number of days. If you set your cutoff time to 1 day, the product cannot be booked with a start date today. If 2 days, the product cannot be booked with a start date today and tomorrow etc.', 'wp-travel-engine' ),
				'field'       => array(
					'name'        => 'cut_off_time.period',
					'type'        => 'NUMBER',
					'placeholder' => __( 'Enter Cut-off Time', 'wp-travel-engine' ),
					'min'         => 0,
					'attributes'  => array(
						'style' => array( 'maxWidth' => '100px' ),
					),
					'suffix'      => array(
						'type'    => 'field',
						'variant' => 'solid',
						'field'   => array(
							'type'    => 'SELECT',
							'name'    => 'cut_off_time.unit',
							'options' => array(
								array(
									'label' => __( 'Days', 'wp-travel-engine' ),
									'value' => 'days',
								),
								array(
									'label' => __( 'Hours', 'wp-travel-engine' ),
									'value' => 'hours',
								),
							),
						),
					),
				),
			),
			array(
				'field' => array(
					'type' => 'DIVIDER',
				),
			),
			array(
				'label' => __( 'Set Minimum And Maximum Age', 'wp-travel-engine' ),
				'field' => array(
					'name' => 'age_limit.enable',
					'type' => 'SWITCH',
				),
			),
			array(
				'condition'  => 'age_limit.enable == true',
				'label'      => true,
				'cols'       => 2,
				'field'      => array(
					'type' => 'GROUP',
				),
				'sub_fields' => array(
					array(
						'label' => 'Minimum Age',
						'field' => array(
							'name'       => 'age_limit.min',
							'type'       => 'NUMBER',
							'min'        => 0,
							'direction'  => 'vertical',
							'attributes' => array(
								'min' => array(
									'value'   => 0,
									'message' => __( 'Minimum value must be greater than 0', 'wp-travel-engine' ),
								),
							),
						),
					),
					array(
						'label' => 'Maximum Age',
						'field' => array(
							'name'       => 'age_limit.max',
							'type'       => 'NUMBER',
							'min'        => 0,
							'direction'  => 'vertical',
							'attributes' => array(
								'min' => array(
									'value'   => 0,
									'message' => __( 'Minimum value must be greater than 0', 'wp-travel-engine' ),
								),
							),
						),
					),
				),
			),
			array(
				'field' => array(
					'type' => 'DIVIDER',
				),
			),
			array(
				'visibility' => version_compare( WP_TRAVEL_ENGINE_VERSION, '7.5.0', '<' ),
				'field'      => array(
					'type'    => 'ALERT',
					'content' => sprintf( __( 'WP Travel Engine with the Fixed Starting Dates (FSD) addon lets you manage seats by trip, package, and departure date. This ensures accurate availability, prevents overbooking, and gives you full control over capacity. Learn more in the <a href="%s" target="_blank"><strong>documentation →</strong></a>.', 'wp-travel-engine' ), 'https://docs.wptravelengine.com/article/seat-allocation/' ),
					'status'  => 'notice',
				),
			),
			array(
				'label' => __( 'Minimum Travellers Per Booking', 'wp-travel-engine' ),
				'field' => array(
					'name'       => 'participants.min',
					'type'       => 'NUMBER',
					'attributes' => array(
						'min'   => array(
							'value'   => 0,
							'message' => __( 'Minimum value must be greater than or equal to 0', 'wp-travel-engine' ),
						),
						'style' => array(
							'max-width' => '90px',
						),
					),
				),
			),
			array(
				'label' => __( 'Total Travellers Seats', 'wp-travel-engine' ),
				'field' => array(
					'name'       => 'participants.max',
					'type'       => 'NUMBER',
					'attributes' => array(
						'min'   => array(
							'value'   => 1,
							'message' => __( 'Maximum value must be greater than 1', 'wp-travel-engine' ),
						),
						'style' => array(
							'max-width' => '90px',
						),
					),
				),
			),
			array(
				'field' => array(
					'type'      => 'SEAT_VALIDATION_ALERT',
					'key_names' => array(
						'participants.min',
						'participants.max',
					),
					'status'    => 'tip',
				),
			),
			array(
				'field' => array(
					'type' => 'DIVIDER',
				),
			),
		),
	),
	'wpte-pricing'         => array(
		'tab_label'         => esc_html__( 'Date & Price', 'wp-travel-engine' ),
		'tab_heading'       => esc_html__( 'Date & Price', 'wp-travel-engine' ),
		'content_path'      => plugin_dir_path( __FILE__ ) . '/trip-tabs/pricing.php',
		'callback_function' => 'wpte_edit_trip_tab_pricing',
		'content_key'       => 'wpte-pricing',
		'current'           => false,
		'content_loaded'    => false,
		'priority'          => 40,
		'icon'              => 'calendarcheck',
		'fields'            => array(
			array(
				'divider' => true,
				'field'   => array(
					'type'                 => 'PACKAGES',
					'name'                 => 'packages',
					'currency_code'        => $global_settings['currency_code'] ?? '',
					'isGroupDisountActive' => wptravelengine_is_addon_active( 'group-discount' ),
					'isFSDActive'          => wptravelengine_is_addon_active( 'fixed-starting-dates' ),
					'pricingCategories'    => $pricing_categories,
				),
			),
		),
	),
	'wpte-overview'        => array(
		'tab_label'         => esc_html__( 'Overview', 'wp-travel-engine' ),
		'tab_heading'       => esc_html__( 'Overview', 'wp-travel-engine' ),
		'content_path'      => plugin_dir_path( __FILE__ ) . '/trip-tabs/overview.php',
		'callback_function' => 'wpte_edit_trip_tab_overview',
		'content_key'       => 'wpte-overview',
		'current'           => false,
		'content_loaded'    => true,
		'priority'          => 20,
		'icon'              => 'filesearch',
		'fields'            => array(
			array(
				'label'   => __( 'Section Title', 'wp-travel-engine' ),
				'divider' => true,
				'field'   => array(
					'name'        => 'overview_title',
					'type'        => 'TEXT',
					'placeholder' => __( 'Overview', 'wp-travel-engine' ),
				),
			),
			array(
				'label'   => __( 'Trip Description', 'wp-travel-engine' ),
				'divider' => true,
				'field'   => array(
					'name'        => 'overview',
					'type'        => 'EDITOR',
					'placeholder' => __( 'Overview', 'wp-travel-engine' ),
				),
			),
			array(
				'field' => array(
					'type'  => 'TITLE',
					'title' => __( 'Trip Highlights', 'wp-travel-engine' ),
				),
			),
			array(
				'label'   => __( 'Section Title', 'wp-travel-engine' ),
				'divider' => true,
				'field'   => array(
					'name'        => 'highlights_title',
					'type'        => 'TEXT',
					'placeholder' => __( 'Highlights', 'wp-travel-engine' ),
				),
			),
			array(
				'field' => array(
					'type' => 'HIGHLIGHTS',
					'name' => 'highlights',
				),
			),
		),
	),
	'wpte-itinerary'       => array(
		'tab_label'         => esc_html__( 'Itinerary', 'wp-travel-engine' ),
		'tab_heading'       => esc_html__( 'Itinerary', 'wp-travel-engine' ),
		'content_path'      => apply_filters( 'wte_trip_itinerary_setting_path', WP_TRAVEL_ENGINE_BASE_PATH . '/admin/meta-parts/trip-tabs/itinerary.php' ),
		'callback_function' => 'wpte_edit_trip_tab_itinerary',
		'content_key'       => 'wpte-itinerary',
		'current'           => false,
		'content_loaded'    => false,
		'priority'          => 30,
		'icon'              => 'route',
		'fields'            => array(
			array(
				'label'       => __( 'Section Title', 'wp-travel-engine' ),
				'description' => __( 'Enter title for the trip itinerary section tab content.', 'wp-travel-engine' ),
				'divider'     => true,
				'field'       => array(
					'name'        => 'itinerary_title',
					'type'        => 'TEXT',
					'placeholder' => __( 'Itinerary', 'wp-travel-engine' ),
				),
			),
			array(
				'label'       => __( 'Itinerary Description', 'wp-travel-engine' ),
				'description' => __( 'Enter description for the trip itinerary section tab content.', 'wp-travel-engine' ),
				'divider'     => true,
				'field'       => array(
					'name'        => 'itinerary_description',
					'type'        => 'EDITOR',
					'placeholder' => __( 'Itinerary Description', 'wp-travel-engine' ),
				),
			),
			array(
				'visibility' => ! wptravelengine_is_addon_active( 'advanced-itinerary' ),
				'divider'    => true,
				'field'      => array(
					'type'    => 'ALERT',
					'content' => __( '<strong>NOTE:</strong> Need additional itinerary fields or require rich text editing for the itinerary? Advanced Itinerary Builder extension provides a rich text editor, sleeping mode, meals, ability to add photos to each day and more. <a href="https://wptravelengine.com/plugins/advanced-itinerary-builder/?utm_source=free_plugin&utm_medium=pro_addon&utm_campaign=upgrade_to_pro" target="_blank">Get Advanced Itinerary Builder extension now</a>', 'wp-travel-engine' ),
					'status'  => 'upgrade',
				),
			),
			array(
				'visibility' => wptravelengine_is_addon_active( 'advanced-itinerary' ),
				'divider'    => true,
				'field'      => array(
					'type'    => 'ALERT',
					'content' => __( '<p>You can add, edit and delete sleep modes via <strong>WP Travel Engine > Settings > Extensions > Advanced Itinerary Builder.</strong></p>', 'wp-travel-engine' ),
					'status'  => 'notice',
				),
			),
			array(
				'divider' => true,
				'field'   => array(
					'name'             => 'itineraries',
					'type'             => 'ITINERARIES',
					'isAdvancedActive' => wptravelengine_is_addon_active( 'advanced-itinerary' ),
					'sleepModeOptions' => $sleep_mode_options,
				),
			),
			...apply_filters(
				'wptravelengine_tripedit:extensions:fields',
				array(),
				'itinerary-downloader'
			),
		),
	),
	'wpte-include-exclude' => array(
		'tab_label'         => __( 'Includes/Excludes', 'wp-travel-engine' ),
		'tab_heading'       => __( 'Includes/Excludes', 'wp-travel-engine' ),
		'content_path'      => plugin_dir_path( __FILE__ ) . '/trip-tabs/includes-excludes.php',
		'callback_function' => 'wpte_edit_trip_tab_include_exclude',
		'content_key'       => 'wpte-include-exclude',
		'current'           => false,
		'content_loaded'    => false,
		'priority'          => 50,
		'icon'              => 'flag',
		'fields'            => array(
			array(
				'label'   => __( 'Section Title', 'wp-travel-engine' ),
				'divider' => true,
				'field'   => array(
					'name'        => 'cost_title',
					'type'        => 'TEXT',
					'placeholder' => __( 'Includes/Excludes', 'wp-travel-engine' ),
				),
			),
			array(
				'field' => array(
					'type'  => 'TITLE',
					'title' => __( 'Cost Includes', 'wp-travel-engine' ),
				),
			),
			array(
				'label' => __( 'Cost Includes Title', 'wp-travel-engine' ),
				'field' => array(
					'name'        => 'cost_includes_title',
					'type'        => 'TEXT',
					'placeholder' => __( 'Cost Includes', 'wp-travel-engine' ),
				),
			),
			array(
				'label'   => __( 'List Of Services', 'wp-travel-engine' ),
				'divider' => true,
				'field'   => array(
					'name'        => 'cost_includes',
					'type'        => 'TEXTAREA',
					'placeholder' => __( 'List of services that are included.', 'wp-travel-engine' ),
					'split'       => true,
					'rows'        => '5',
				),
			),
			array(
				'field' => array(
					'type'  => 'TITLE',
					'title' => __( 'Cost Excludes', 'wp-travel-engine' ),
				),
			),
			array(
				'label'   => __( 'Cost Excludes Title', 'wp-travel-engine' ),
				'divider' => true,
				'field'   => array(
					'name'        => 'cost_excludes_title',
					'type'        => 'TEXT',
					'placeholder' => __( 'Cost Excludes', 'wp-travel-engine' ),
				),
			),
			array(
				'label' => __( 'List Of Services', 'wp-travel-engine' ),
				'field' => array(
					'name'        => 'cost_excludes',
					'type'        => 'TEXTAREA',
					'placeholder' => __( 'List of services that are excluded.', 'wp-travel-engine' ),
					'split'       => true,
					'rows'        => '5',
				),
			),
		),
	),
	'wpte-facts'           => array(
		'tab_label'         => esc_html__( 'Trip Info', 'wp-travel-engine' ),
		'tab_heading'       => esc_html__( 'Trip Info', 'wp-travel-engine' ),
		'content_path'      => plugin_dir_path( __FILE__ ) . '/trip-tabs/trip-facts.php',
		'callback_function' => 'wpte_edit_trip_tab_facts',
		'content_key'       => 'wpte-facts',
		'current'           => false,
		'content_loaded'    => false,
		'priority'          => 21,
		'icon'              => 'map',
		'fields'            => array(
			array(
				'label'   => __( 'Section Title', 'wp-travel-engine' ),
				'divider' => true,
				'field'   => array(
					'name'        => 'trip_info_title',
					'type'        => 'TEXT',
					'placeholder' => __( 'Trip Info', 'wp-travel-engine' ),
				),
			),
			array(
				'label'       => __( 'Trip Info Selection', 'wp-travel-engine' ),
				'description' => __( 'Select the trip fact title and click on add fact button to enter trip fact data.', 'wp-travel-engine' ),
				'field'       => array(
					'name'    => 'trip_info',
					'type'    => 'TRIP_FACTS',
					'options' => $trip_facts,
				),
			),
		),
	),
	'wpte-gallery'         => array(
		'tab_label'         => esc_html__( 'Gallery', 'wp-travel-engine' ),
		'tab_heading'       => esc_html__( 'Gallery', 'wp-travel-engine' ),
		'content_path'      => plugin_dir_path( __FILE__ ) . '/trip-tabs/gallery.php',
		'callback_function' => 'wpte_edit_trip_tab_gallery',
		'content_key'       => 'wpte-gallery',
		'current'           => false,
		'content_loaded'    => false,
		'priority'          => 61,
		'icon'              => 'image',
		'fields'            => array(
			array(
				'label' => __( 'Enable Image Gallery', 'wp-travel-engine' ),
				'field' => array(
					'name' => 'gallery_enable',
					'type' => 'SWITCH',
				),
			),
			array(
				'label'       => true,
				'description' => __( 'Max. file size 5MB Supports: JPG,PNG,WebP images', 'wp-travel-engine' ),
				'condition'   => 'gallery_enable == true',
				'field'       => array(
					'name'        => 'gallery',
					'type'        => 'IMAGE_GALLERY',
					'isMultiple'  => true,
					'fileTypes'   => array( 'image/jpeg', 'image/png', 'image/webp' ),
					'className'   => 'wpte-media-uploader-field',
					'buttonLabel' => __( 'Add Image', 'wp-travel-engine' ),
				),
			),
			array(
				'field' => array(
					'type' => 'DIVIDER',
				),
			),
			array(
				'label' => __( 'Enable Video Gallery', 'wp-travel-engine' ),
				'field' => array(
					'name' => 'video_gallery_enable',
					'type' => 'SWITCH',
				),
			),
			array(
				'label'     => true,
				'condition' => 'video_gallery_enable == true',
				'field'     => array(
					'name' => 'video_gallery',
					'type' => 'VIDEO_GALLERY',
				),
			),
		),
	),
	'wpte-map'             => array(
		'tab_label'         => esc_html__( 'Map', 'wp-travel-engine' ),
		'tab_heading'       => esc_html__( 'Map', 'wp-travel-engine' ),
		'content_path'      => plugin_dir_path( __FILE__ ) . '/trip-tabs/map.php',
		'callback_function' => 'wpte_edit_trip_tab_map',
		'content_key'       => 'wpte-map',
		'current'           => false,
		'content_loaded'    => false,
		'priority'          => 60,
		'icon'              => 'marker',
		'fields'            => array(
			array(
				'label'   => __( 'Section Title', 'wp-travel-engine' ),
				'divider' => true,
				'field'   => array(
					'name'        => 'map_title',
					'type'        => 'TEXT',
					'placeholder' => __( 'Enter map title', 'wp-travel-engine' ),
				),
			),
			array(
				'label'   => __( 'Map Image', 'wp-travel-engine' ),
				'divider' => true,
				'field'   => array(
					'name'        => 'trip_map.images[0]',
					'type'        => 'IMAGE_GALLERY',
					'icon'        => 'upload',
					'fileTypes'   => array( 'image' ),
					'buttonLabel' => __( 'Upload Image', 'wp-travel-engine' ),
				),
			),
			array(
				'label'   => __( 'Map iframe Code', 'wp-travel-engine' ),
				'divider' => true,
				'field'   => array(
					'name' => 'trip_map.iframe',
					'type' => 'TEXTAREA',
					'rows' => 5,
				),
			),
		),
	),
	'wpte-faqs'            => array(
		'tab_label'         => esc_html__( 'FAQs', 'wp-travel-engine' ),
		'tab_heading'       => esc_html__( 'FAQs', 'wp-travel-engine' ),
		'content_path'      => plugin_dir_path( __FILE__ ) . '/trip-tabs/faqs.php',
		'callback_function' => 'wpte_edit_trip_tab_faqs',
		'content_key'       => 'wpte-faqs',
		'current'           => false,
		'content_loaded'    => false,
		'priority'          => 70,
		'icon'              => 'message',
		'fields'            => array(
			array(
				'label'   => __( 'Section Title', 'wp-travel-engine' ),
				'divider' => true,
				'field'   => array(
					'name'        => 'faqs_title',
					'type'        => 'TEXT',
					'placeholder' => __( 'Enter FAQs Title', 'wp-travel-engine' ),
				),
			),
			array(
				'field' => array(
					'name' => 'faqs',
					'type' => 'FAQS',
				),
			),
		),
	),
	'wte-exta-services'    => array(
		'tab_label'         => esc_html__( 'Extra Services', 'wp-travel-engine' ),
		'tab_heading'       => esc_html__( 'Extra Services', 'wp-travel-engine' ),
		'content_path'      => plugin_dir_path( __FILE__ ) . '/trip-tabs/extra-services.php',
		'callback_function' => 'wpte_edit_trip_tab_extra_services_upsell',
		'content_key'       => 'wte-exta-services',
		'current'           => false,
		'content_loaded'    => false,
		'priority'          => 125,
		'icon'              => 'grid',
		'fields'            => array(
			...apply_filters(
				'wptravelengine_tripedit:extensions:fields',
				array(),
				'extra-services'
			),
		),
	),
	'wte-trip-shortcodes'  => array(
		'tab_label'    => esc_html__( 'Shortcodes', 'wp-travel-engine' ),
		'tab_heading'  => esc_html__( 'Shortcodes', 'wp-travel-engine' ),
		'content_path' => plugin_dir_path( __FILE__ ) . '/trip-tabs/shortcodes.php',
		'content_key'  => 'wte-trip-shortcodes',
		'icon'         => 'code',
		'priority'     => 100,
		'fields'       => array(
			...apply_filters(
				'wptravelengine_tripedit:extensions:fields',
				array(),
				'shortcodes'
			),
		),
	),
);

$trip_meta_tabs = wpte_add_custom_tabs_to_trip_meta( $trip_meta_tabs );

// Apply filter hooks.
$trip_meta_tabs = apply_filters( 'wp_travel_engine_admin_trip_meta_tabs', $trip_meta_tabs );

if ( wptravelengine_is_addon_active( 'fixed-starting-dates' ) ) {
	foreach ( $trip_meta_tabs['wpte-general']['fields'] as $key => $field ) {
		if ( 'fsd' === ( $field['extension'] ?? '' ) && 'max_travellers_per_day' === $field['field']['name'] ) {
			unset( $trip_meta_tabs['wpte-general']['fields'][ $key ] );
			unset( $trip_meta_tabs['wpte-general']['fields'][ $key + 1 ] );
			break;
		}
	}
	$trip_meta_tabs['wpte-general']['fields'] = array_values( $trip_meta_tabs['wpte-general']['fields'] );
}

$trip_meta_tabs = wp_travel_engine_sort_array_by_priority( $trip_meta_tabs );

$handle_def_tabs = function ( &$tab_fields ) use ( &$trip_meta_tabs ) {
	$this_key = $tab_fields['content_key'] ?? $tab_fields['id'];

	switch ( $this_key ) :
		case 'wpte-overview':
			$tab_fields['tab_label'] = $tab_fields['tab_heading'] = __( 'Overview & Info', 'wp-travel-engine' );

			$sub1 = array(
				'id'       => 'overview',
				'title'    => __( 'Overview', 'wp-travel-engine' ),
				'fields'   => $tab_fields['fields'],
				'priority' => 5,
			);

			$sub2 = array(
				'id'       => 'trip-info',
				'title'    => __( 'Trip Info', 'wp-travel-engine' ),
				'fields'   => $trip_meta_tabs['wpte-facts']['fields'] ?? array(),
				'priority' => 10,
			);

			$_tabs = array( $sub1, $sub2 );
			break;
		case 'wpte-pricing':
			$sub1 = array(
				'id'       => 'packages',
				'title'    => __( 'Packages', 'wp-travel-engine' ),
				'fields'   => $tab_fields['fields'],
				'priority' => 5,
			);

			$sub2 = array(
				'id'       => 'dates-settings',
				'title'    => __( 'Date Settings', 'wp-travel-engine' ),
				'fields'   => apply_filters(
					'wptravelengine_tripedit:extensions:fields',
					array(),
					'fixed-starting-dates'
				),
				'priority' => 10,
			);

			$sub3 = array(
				'id'       => 'partial-payment',
				'title'    => __( 'Partial Payments', 'wp-travel-engine' ),
				'fields'   => apply_filters(
					'wptravelengine_tripedit:extensions:fields',
					array(),
					'partial-payment'
				),
				'priority' => 15,
			);

			$_tabs = array( $sub1, $sub2, $sub3 );
			break;
		case 'wpte-map':
			$tab_fields['tab_label'] = $tab_fields['tab_heading'] = __( 'Map & Gallery', 'wp-travel-engine' );

			$sub1 = array(
				'id'       => 'map',
				'title'    => __( 'Map', 'wp-travel-engine' ),
				'fields'   => $tab_fields['fields'],
				'priority' => 10,
			);

			$sub2 = array(
				'id'       => 'gallery',
				'title'    => __( 'Gallery', 'wp-travel-engine' ),
				'fields'   => $trip_meta_tabs['wpte-gallery']['fields'] ?? array(),
				'priority' => 5,
			);

			$_tabs = array( $sub1, $sub2 );
			break;
		case 'wpte-gallery':
		case 'wpte-facts':
			unset( $trip_meta_tabs[ $this_key ] );
			return;
		default:
			return;
	endswitch;

	$tabs = apply_filters( 'wptravelengine_tripedit:field:subtabs', $_tabs, $this_key );

	$tab_fields['fields'] = array(
		array(
			'field' => array(
				'type' => 'TAB',
				'tabs' => wp_travel_engine_sort_array_by_priority( $tabs ),
			),
		),
	);
};

$def_keys = array(
	'wpte-general'         => true,
	'wpte-pricing'         => true,
	'wpte-overview'        => true,
	'wpte-itinerary'       => true,
	'wpte-include-exclude' => true,
	'wpte-facts'           => true,
	'wpte-map'             => true,
	'wpte-gallery'         => true,
	'wpte-faqs'            => true,
	'wpte-custom-tabs'     => true,
);

$def_increases_revenue = array(
	'wpte-extra-services'             => 1,
	'wte-exta-services'               => 1,
	'wptravelengine-accommodation'    => 2,
	'wptravelengine-travel-insurance' => 3,
	'wptravelengine-pickup-points'    => 4,
);

$increases_revenue_tabs = array();
$advanced_settings_tabs = array();
foreach ( $trip_meta_tabs as $key => &$tab_fields ) :
	if ( ! isset( $tab_fields['fields'] ) ) :
		continue;
	endif;

	if ( isset( $def_keys[ $key ] ) ) :
		$handle_def_tabs( $tab_fields );
	else :
		if ( isset( $tab_fields['content_key'] ) ) {
			$tab_fields['id'] = $tab_fields['content_key'];
		}

		if ( isset( $tab_fields['tab_heading'] ) ) {
			$tab_fields['heading'] = $tab_fields['tab_heading'];
		}

		if ( isset( $tab_fields['tab_label'] ) ) {
			$tab_fields['label'] = $tab_fields['tab_label'];
		}

		$tab_fields['title'] ??= $tab_fields['label'] ?? $tab_fields['heading'] ?? $tab_fields['id'] ?? $key;

		if ( ( $tab_fields['increases_revenue'] ?? false ) || ( $def_increases_revenue[ $key ] ?? false ) ) :
			$tab_fields['priority']   = $tab_fields['order'] ?? $def_increases_revenue[ $key ];
			$increases_revenue_tabs[] = $tab_fields;
		else :
			$advanced_settings_tabs[] = $tab_fields;
		endif;

		unset( $trip_meta_tabs[ $key ] );
	endif;
endforeach;

unset( $handle_def_tabs );

if ( ! empty( $increases_revenue_tabs ) ) {
	$trip_meta_tabs['increase-revenue'] = array(
		'tab_label'   => esc_html__( 'Increase Revenue', 'wp-travel-engine' ),
		'tab_heading' => esc_html__( 'Increase Revenue', 'wp-travel-engine' ),
		'content_key' => 'increase-revenue',
		'icon'        => 'line-chart-up',
		'separated'   => true,
		'as'          => 'title',
	);

	$increases_revenue_tabs = array_column( wp_travel_engine_sort_array_by_priority( $increases_revenue_tabs ), null, 'content_key' );
	$trip_meta_tabs         = array_merge( $trip_meta_tabs, $increases_revenue_tabs );
}

$advanced_settings_tabs = array_merge(
	$advanced_settings_tabs,
	array(
		array(
			'id'       => 'file-downloads',
			'title'    => __( 'File Downloads', 'wp-travel-engine' ),
			'fields'   => apply_filters(
				'wptravelengine_tripedit:extensions:fields',
				array(),
				'file-downloads'
			),
			'priority' => 25,
		),
		array(
			'id'       => 'custom-booking-link',
			'title'    => __( 'Custom Booking Link', 'wp-travel-engine' ),
			'fields'   => apply_filters(
				'wptravelengine_tripedit:extensions:fields',
				array(),
				'custom-booking-link'
			),
			'priority' => 30,
		),
	)
);

$trip_meta_tabs['advanced-settings'] = array(
	'tab_label'   => esc_html__( 'Advanced Settings', 'wp-travel-engine' ),
	'tab_heading' => esc_html__( 'Advanced Settings', 'wp-travel-engine' ),
	'content_key' => 'advanced-settings',
	'priority'    => 125,
	'icon'        => 'settings',
	'separated'   => true,
	'fields'      => array(
		array(
			'field' => array(
				'type' => 'TAB',
				'tabs' => wp_travel_engine_sort_array_by_priority( $advanced_settings_tabs ),
			),
		),
	),
);

$admin_tabs_ui = wptravelengine_tabs_ui();

$tab_args = array(
	'id'          => 'wptravelengine-edit-trip',
	'class'       => 'wptravelengine-edit-trip',
	'content_key' => 'wpte_edit_trip_tabs',
);

// Load Tabs.
$admin_tabs_ui->init( $tab_args )->single_trip_metabox_template( $trip_meta_tabs );
