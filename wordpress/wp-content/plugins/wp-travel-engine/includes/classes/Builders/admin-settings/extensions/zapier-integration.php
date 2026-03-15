<?php
/**
 * Extensions Zapier Integration Settings.
 *
 * @since 6.2.0
 */
$is_zapier_active  = defined( 'WTE_ZAPIER_PLUGIN_FILE' );
$active_extensions = apply_filters( 'wpte_get_global_extensions_tab', array() );
$file_path         = $active_extensions['wte_zapier_addon']['content_path'] ?? '';
if ( ! file_exists( $file_path ) ) {
	return array();
}
return apply_filters(
	'extension_zapier_integration',
	array(
		'is_active' => $is_zapier_active,
		'title'     => __( 'Zapier Integration', 'wp-travel-engine' ),
		'order'     => 75,
		'id'        => 'extension-zapier-integration',
		'fields'    => array(
			array(
				'divider'    => true,
				'label'      => __( 'Automation for Bookings', 'wp-travel-engine' ),
				'help'       => __( 'Check this option to automatically trigger booking Zaps when new booking is created.', 'wp-travel-engine' ),
				'field_type' => 'SWITCH',
				'name'       => 'zapier.enable_automation_booking',
			),
			array(
				'divider'    => true,
				'label'      => __( 'Automation for Enquiries', 'wp-travel-engine' ),
				'help'       => __( 'Check this option to automatically trigger enquiry Zaps when new enquiry is created.', 'wp-travel-engine' ),
				'field_type' => 'SWITCH',
				'name'       => 'zapier.enable_automation_enquiry',
			),
			array(
				'field_type' => 'TITLE',
				'title'      => __( 'Booking Zapier Webhooks', 'wp-travel-engine' ),
			),
			array(
				'field_type'  => 'FIELD_HEADER',
				'description' => __( 'Enter the name and webhook URLs for zaps used for bookings.', 'wp-travel-engine' ),
			),
			array(
				'divider'    => true,
				'help'       => __( 'Enter the name and webhook URLs for zaps used for bookings.', 'wp-travel-engine' ),
				'field_type' => 'WEBHOOK',
				'name'       => 'zapier.booking_zaps',
				'_nonce'     => wp_create_nonce( 'wte_zapier_trigger_hook_test' ),
				'hook_type'  => 'booking',
			),
			array(
				'field_type' => 'TITLE',
				'title'      => __( 'Enquiry Zapier Webhooks', 'wp-travel-engine' ),
			),
			array(
				'field_type'  => 'FIELD_HEADER',
				'description' => __( 'Enter the name and webhook URLs for zaps used for enquiries.', 'wp-travel-engine' ),
			),
			array(
				'help'       => __( 'Enter the name and webhook URLs for zaps used for enquiries.', 'wp-travel-engine' ),
				'field_type' => 'WEBHOOK',
				'name'       => 'zapier.enquiry_zaps',
				'_nonce'     => wp_create_nonce( 'wte_zapier_trigger_hook_test' ),
				'hook_type'  => 'enquiry',
			),
		),
	)
);
