<?php
/**
 * User Dashboard Tab Settings.
 *
 * @since 6.2.0
 */

return apply_filters(
	'user_dashboard_settings',
	array(
		'title'  => __( 'User Dashboard', 'wp-travel-engine' ),
		'order'  => 5,
		'id'     => 'dashboard-user-settings',
		'fields' => array(
			array(
				'field_type' => 'ALERT',
				/* translators: %s: admin url linking to general settings page */
				'content'    => sprintf(
					__( 'You can set your User Dashboard page from %s.', 'wp-travel-engine' ),
					'<a target="_blank" href="' . esc_url( admin_url( 'edit.php?post_type=booking&page=class-wp-travel-engine-admin.php#general_pages' ) ) . '" rel="nofollow">here</a>'
				),
				'status'     => 'info',
			),
			array(
				'divider'    => true,
				'label'      => __( 'Create Account Automatically', 'wp-travel-engine' ),
				'help'       => __( 'It automatically creates user account (username and password) when booking a trip and sends the details to the customer.', 'wp-travel-engine' ),
				'field_type' => 'SWITCH',
				'name'       => 'generate_user_account',
			),
			array(
				'condition'  => 'generate_user_account === false',
				'field_type' => 'GROUP',
				'fields'     => array(
					array(
						'divider'    => true,
						'label'      => __( 'Require Registration for Booking', 'wp-travel-engine' ),
						'help'       => __( 'Customers must sign in or create an account to complete trip bookings.', 'wp-travel-engine' ),
						'field_type' => 'SWITCH',
						'name'       => 'enable_booking_registration',
					),
					array(
						'divider'    => true,
						'label'      => __( 'Customer Registration', 'wp-travel-engine' ),
						'help'       => __( 'Enable to let customers create new accounts on my account page.', 'wp-travel-engine' ),
						'field_type' => 'SWITCH',
						'name'       => 'enable_account_registration',
					),
				),
			),
			array(
				'label'      => __( 'Login Page Label', 'wp-travel-engine' ),
				'field_type' => 'TEXT',
				'default'    => __( 'Log into Your Account', 'wp-travel-engine' ),
				'name'       => 'login_page_label',
				'divider'    => true,
			),
			array(
				'label'      => __( 'Forgot Page Label', 'wp-travel-engine' ),
				'field_type' => 'TEXT',
				'default'    => __( 'Reset Your Password', 'wp-travel-engine' ),
				'name'       => 'forgot_page_label',
				'divider'    => true,
			),
			array(
				'label'      => __( 'Forgot Page Description', 'wp-travel-engine' ),
				'field_type' => 'TEXTAREA',
				'default'    => __( 'If an account with that email exist, we’ll send you a link to reset your password. Please check your inbox including spam/junk folder.', 'wp-travel-engine' ),
				'name'       => 'forgot_page_description',
				'divider'    => true,
			),
			array(
				'label'      => __( 'Set Password Page Label', 'wp-travel-engine' ),
				'field_type' => 'TEXT',
				'default'    => __( 'Set New Password', 'wp-travel-engine' ),
				'name'       => 'set_password_page_label',
				'divider'    => true,
			),
		),
	),
);
