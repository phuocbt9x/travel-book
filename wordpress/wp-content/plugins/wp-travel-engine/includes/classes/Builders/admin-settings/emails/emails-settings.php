<?php
/**
 * Admin Email Settings.
 *
 * @since 6.5.0
 */

return apply_filters(
	'emails-settings',
	array(
		'title'  => __( 'Settings', 'wp-travel-engine' ),
		'order'  => 2,
		'id'     => 'emails_settings',
		'icon'   => 'email',
		'fields' => apply_filters(
			'emails-settings-fields',
			array(
				array(
					'label'       => __( 'Enquiry Notification Emails', 'wp-travel-engine' ),
					'description' => __( 'Enter the email address(es) to receive notifications whenever an enquiry is made. Separate multiple addresses with a comma (,) without spaces.', 'wp-travel-engine' ),
					'field_type'  => 'TEXT',
					'name'        => 'email_settings.enquiry_emails',
					'multiple'    => true,
				),
				array(
					'label'       => __( 'Admin Notification Emails', 'wp-travel-engine' ),
					'description' => __( 'Enter the email address(es) that should receive a notification anytime a sale is made, separated by comma (,) and no spaces.', 'wp-travel-engine' ),
					'field_type'  => 'TEXT',
					'name'        => 'email_settings.sale_emails',
					'divider'     => true,
					'multiple'    => true,
				),
				array(
					'field_type' => 'TITLE',
					'title'      => __( 'Sender Details', 'wp-travel-engine' ),
				),
				array(
					'label'       => __( 'From Name', 'wp-travel-engine' ),
					'field_type'  => 'TEXT',
					'description' => __( 'Enter the name the purchase receipts are sent from. This should probably be your site or shop name.', 'wp-travel-engine' ),
					'name'        => 'email_settings.from_name',
				),
				array(
					'label'       => __( 'From Email', 'wp-travel-engine' ),
					'field_type'  => 'TEXT',
					'description' => __( 'Enter the email address from which the purchase receipts will be sent. This will act as as the from address.', 'wp-travel-engine' ),
					'name'        => 'email_settings.from',
				),
				array(
					'divider'     => true,
					'label'       => __( 'Reply To', 'wp-travel-engine' ),
					'field_type'  => 'TEXT',
					'description' => __( 'Enter the email address to which replies to the purchase receipts will be sent.', 'wp-travel-engine' ),
					'name'        => 'email_settings.reply_to',
				),
				array(
					'field_type' => 'TITLE',
					'title'      => __( 'Branding', 'wp-travel-engine' ),
				),
				array(
					'label'       => __( 'Header Image/Logo', 'wp-travel-engine' ),
					'field_type'  => 'GALLERY',
					'fileTypes'   => array( 'image' ),
					'buttonLabel' => __( 'Upload Logo', 'wp-travel-engine' ),
					'description' => __( 'Upload a logo to be used in the purchase receipts.', 'wp-travel-engine' ),
					'name'        => 'email_settings.logo',
				),
				array(
					'divider'     => true,
					'label'       => __( 'Footer Text ', 'wp-travel-engine' ),
					'field_type'  => 'TEXT',
					'description' => __( 'Enter the footer text for your emails.', 'wp-travel-engine' ),
					'name'        => 'email_settings.footer',
				),
				array(
					'field_type' => 'TITLE',
					'title'      => __( 'Test Emails', 'wp-travel-engine' ),
				),
				array(
					'field_type' => 'ALERT',
					'content'    => sprintf(
						__( '<strong>NOTE: </strong>After sending the test email and receiving a success message, please check your inbox. If your server is properly configured for email sending, you will receive the test email. However, if something seems amiss or the email does not arrive, please refer to the <a href="%s">Email FAQ page</a> for troubleshooting assistance.', 'wp-travel-engine' ),
						'https://docs.wptravelengine.com/article/email-troubleshooting/'
					),
					'status'     => 'info',
				),
				array(
					'label'      => __( 'Send Test Email', 'wp-travel-engine' ),
					'field_type' => 'TEST_EMAIL',
					'_nonce'     => wp_create_nonce( 'wptravelengine_test_email_nonce' ),
					'default'    => wp_get_current_user()->user_email,
				),
			)
		),
	),
);
