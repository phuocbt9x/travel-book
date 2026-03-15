<?php
/**
 * Checkout.
 *
 * @since 6.2.0
 */

return apply_filters(
	'checkout_settings',
	array(
		'title'  => __( 'Checkout', 'wp-travel-engine' ),
		'order'  => 20,
		'id'     => 'checkout_settings',
		'fields' => array(
			array(
				'label'       => __( 'Booking Confirmation Message', 'wp-travel-engine' ),
				'description' => '',
				'field_type'  => 'TEXTAREA',
				'default'     => 'Thank you for booking the trip. Please check your email for confirmation. Below is your booking detail:',
				'name'        => 'booking_confirmation_msg',
				'divider'     => true,
			),
			array(
				'label'       => __( 'Template for Checkout Page', 'wp-travel-engine' ),
				'description' => sprintf( __( 'To use the New template, create a new page and assign the "WP Travel Engine - Checkout" template in the Page Settings. For detailed instructions, refer to our documentation. <a href="%s" target="_blank">Click here</a> to know more about new template.', 'wp-travel-engine' ), 'https://docs.wptravelengine.com/article/checkout-settings/?utm_source=free_plugin&utm_medium=dashboard&utm_campaign=docs' ),
				'field_type'  => 'SELECT_BUTTON',
				'options'     => array(
					array(
						'label' => __( 'Default', 'wp-travel-engine' ),
						'value' => '1.0',
					),
					array(
						'label' => __( 'New', 'wp-travel-engine' ),
						'value' => '2.0',
					),
				),
				'name'        => 'checkout_page_template',
				'isNew'       => WP_TRAVEL_ENGINE_VERSION === '6.3.0',
			),
			array(
				'condition'  => 'checkout_page_template === 1.0',
				'field_type' => 'GROUP',
				'fields'     => array(
					array(
						'condition'   => 'checkout_page_template === 1.0',
						'label'       => __( "Show Traveller's Information", 'wp-travel-engine' ),
						'description' => __( 'If checked, information of all the travellers will be compulsory. After checkout, information of each of the travellers will be asked to fill up.', 'wp-travel-engine' ),
						'field_type'  => 'SWITCH',
						'name'        => 'enable_travellers_info',
						'divider'     => true,
					),
					array(
						'condition'   => 'checkout_page_template === 1.0 && enable_travellers_info === true',
						'label'       => __( 'Show Emergency Contact Details', 'wp-travel-engine' ),
						'description' => __( 'If unchecked, Emergency Contact Details of the travellers will be disabled from the Travellers Information Form.', 'wp-travel-engine' ),
						'field_type'  => 'SWITCH',
						'name'        => 'enable_emergency_contact',

					),
				),
			),
			array(
				'condition'  => 'checkout_page_template === 2.0',
				'field_type' => 'GROUP',
				'fields'     => array(
					array(
						'label'       => __( 'Show Traveller Information Form', 'wp-travel-engine' ),
						'description' => __( 'When enabled, providing information for all travellers will be mandatory.', 'wp-travel-engine' ),
						'field_type'  => 'SWITCH',
						'name'        => 'display_travellers_info',
						'condition'   => 'checkout_page_template === 2.0',
						'divider'     => true,
						'isNew'       => WP_TRAVEL_ENGINE_VERSION === '6.3.0',
					),
					array(
						'label'       => __( 'Show Emergency Contact Form', 'wp-travel-engine' ),
						'description' => __( 'Enable this option to include a section for emergency contact details in the Traveller Information form.', 'wp-travel-engine' ),
						'field_type'  => 'SWITCH',
						'name'        => 'display_emergency_contact',
						'condition'   => 'display_travellers_info === true',
						'divider'     => true,
						'isNew'       => WP_TRAVEL_ENGINE_VERSION === '6.3.0',
					),
					array(
						'label'      => __( 'Display Traveller and Emergency Details', 'wp-travel-engine' ),
						'field_type' => 'SELECT_BUTTON',
						'name'       => 'traveller_emergency_details_form',
						'condition'  => 'display_travellers_info === true',
						'options'    => array(
							array(
								'label' => __( 'On Checkout', 'wp-travel-engine' ),
								'value' => 'on_checkout',
							),
							array(
								'label' => __( 'After Checkout', 'wp-travel-engine' ),
								'value' => 'after_checkout',
							),
						),
						'divider'    => true,
						'isNew'      => WP_TRAVEL_ENGINE_VERSION === '6.3.0',
					),
					array(
						'label'       => __( 'Collect Traveller Information', 'wp-travel-engine' ),
						'description' => __( 'Choose whether to collect information for all travellers or only the main traveller during checkout.', 'wp-travel-engine' ),
						'field_type'  => 'SELECT_BUTTON',
						'name'        => 'travellers_details_type',
						'condition'   => 'display_travellers_info === true && traveller_emergency_details_form === on_checkout',
						'options'     => array(
							array(
								'label' => __( 'All Travellers', 'wp-travel-engine' ),
								'value' => 'all',
							),
							array(
								'label' => __( 'Only Lead Traveller', 'wp-travel-engine' ),
								'value' => 'only_lead',
							),
						),
						'divider'     => true,
						'isNew'       => WP_TRAVEL_ENGINE_VERSION === '6.3.0',
					),
					array(
						'label'       => __( 'Show Additional Note', 'wp-travel-engine' ),
						'description' => __( 'Enable this option to display an additional notes section on the checkout page.', 'wp-travel-engine' ),
						'field_type'  => 'SWITCH',
						'name'        => 'show_additional_note',
						'condition'   => 'checkout_page_template === 2.0',
						'divider'     => true,
						'isNew'       => WP_TRAVEL_ENGINE_VERSION === '6.3.0',
					),
					array(
						'label'       => __( 'Show Coupon Code Field', 'wp-travel-engine' ),
						'description' => __( 'Enable this option to allow customers to apply a coupon code during checkout if the trip has one.', 'wp-travel-engine' ),
						'field_type'  => 'SWITCH',
						'name'        => 'show_discount',
						'condition'   => 'checkout_page_template === 2.0',
						'divider'     => true,
						'isNew'       => WP_TRAVEL_ENGINE_VERSION === '6.3.0',
					),
					array(
						'label'       => __( 'Privacy Policy Notice', 'wp-travel-engine' ),
						'description' => __( 'Enter the privacy policy message to display on the checkout page.', 'wp-travel-engine' ),
						'field_type'  => 'TEXTAREA',
						'name'        => 'privacy_policy_msg',
						'divider'     => true,
						'isNew'       => WP_TRAVEL_ENGINE_VERSION === '6.3.0',
					),
					array(
						'label'       => __( 'Footer Copyright', 'wp-travel-engine' ),
						'description' => __( 'Enter the copyright text to display in the footer. Available tags: %1$current_year%, %2$site_name%.', 'wp-travel-engine' ),
						'condition'   => 'checkout_page_template === 2.0',
						'field_type'  => 'TEXTAREA',
						'name'        => 'footer_copyright',
						'isNew'       => WP_TRAVEL_ENGINE_VERSION === '6.3.0',
					),
				),
			),
		),
	)
);
