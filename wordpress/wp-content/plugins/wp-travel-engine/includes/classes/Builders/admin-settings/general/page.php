<?php
/**
 * Page Settings Tab Settings.
 *
 * @since 6.2.0
 */

$wptravelengine_pages = array();

foreach ( get_pages() as $page ) {
	$wptravelengine_pages[] = array(
		'label' => $page->post_title,
		'value' => $page->ID,
	);
}

return apply_filters(
	'general_pages',
	array(
		'title'  => __( 'Pages', 'wp-travel-engine' ),
		'order'  => 5,
		'id'     => 'general_pages',
		'fields' => array(
			array(
				'label'       => __( 'Checkout Page', 'wp-travel-engine' ),
				'description' => __( 'This is the checkout page where buyers will complete their order. The <strong>[WP_TRAVEL_ENGINE_PLACE_ORDER]</strong> shortcode must be on this page.', 'wp-travel-engine' ),
				'field_type'  => 'SELECT',
				'options'     => $wptravelengine_pages,
				'name'        => 'checkout_page',
				'divider'     => true,
			),
			array(
				'label'       => __( 'Terms & Conditions Page', 'wp-travel-engine' ),
				'description' => __( 'This is the terms and conditions page where trip bookers will see the terms and conditions for booking.', 'wp-travel-engine' ),
				'field_type'  => 'SELECT',
				'options'     => $wptravelengine_pages,
				'name'        => 'terms_and_conditions',
				'divider'     => true,
			),
			array(
				'label'       => __( 'Thank You Page', 'wp-travel-engine' ),
				'description' => __( 'This is the thank you page where trip bookers will get the payment confirmation message. The <strong>[WP_TRAVEL_ENGINE_THANK_YOU]</strong> shortcode must be on this page.', 'wp-travel-engine' ),
				'field_type'  => 'SELECT',
				'options'     => $wptravelengine_pages,
				'name'        => 'thank_you_page',
				'divider'     => true,
			),
			array(
				'label'       => __( 'Confirmation Page', 'wp-travel-engine' ),
				'description' => __( 'This is the confirmation page where trip bookers will fill the full form of the travellers. The <strong>[WP_TRAVEL_ENGINE_BOOK_CONFIRMATION]</strong> shortcode must be on this page.', 'wp-travel-engine' ),
				'field_type'  => 'SELECT',
				'options'     => $wptravelengine_pages,
				'name'        => 'confirmation_page',
				'divider'     => true,
			),
			array(
				'label'       => __( 'User Dashboard Page', 'wp-travel-engine' ),
				'description' => __( 'This is the dashboard page that lets your users login and interact with bookings from the frontend. The <strong>[wp_travel_engine_dashboard]</strong> shortcode must be on this page.', 'wp-travel-engine' ),
				'field_type'  => 'SELECT',
				'options'     => $wptravelengine_pages,
				'name'        => 'dashboard_page',
				'divider'     => true,
			),
			array(
				'label'      => __( 'Enquiry Thank You Page', 'wp-travel-engine' ),
				'help'       => __( 'This is the thank you page where users will be redirected after a successful enquiry.', 'wp-travel-engine' ),
				'field_type' => 'SELECT',
				'options'    => $wptravelengine_pages,
				'name'       => 'enquiry_thank_you_page',
				'divider'    => true,
			),
			array(
				'label'       => __( 'Wishlist Page', 'wp-travel-engine' ),
				'description' => __( 'This is the wishlist page where users can check out the trips they have wishlisted. The <strong>[WP_TRAVEL_ENGINE_WISHLIST]</strong> shortcode must be on this page.', 'wp-travel-engine' ),
				'field_type'  => 'SELECT',
				'options'     => $wptravelengine_pages,
				'name'        => 'wishlist_page',
				'divider'     => true,
			),
			array(
				'label'      => __( 'Trip Search Results Page', 'wp-travel-engine' ),
				'help'       => __( 'This is the trip search results page with search filters.', 'wp-travel-engine' ),
				'field_type' => 'SELECT',
				'options'    => $wptravelengine_pages,
				'name'       => 'search_page',
			),
		),
	)
);
