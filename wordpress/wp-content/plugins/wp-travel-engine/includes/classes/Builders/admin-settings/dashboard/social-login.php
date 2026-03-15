<?php

/**
 * Social Login Tab Settings.
 *
 * @since 6.2.0
 */

return apply_filters(
	'social_login_settings',
	array(
		'title'  => __( 'Social Login', 'wp-travel-engine' ),
		'order'  => 10,
		'id'     => 'dashboard-social-login',
		'fields' => array(
			array(
				'divider'    => true,
				'field_type' => 'ALERT',
				'content'    => sprintf( __( 'Note: Please go through <a href="%s" target="__blank">this</a> doc to learn how to get client id and secret for each of the social login integration', 'wp-travel-engine' ), 'https://docs.wptravelengine.com/docs/social-login' ),
				'status'     => 'notice',
			),
			array(
				'divider'    => true,
				'label'      => __( 'Display Social Login', 'wp-travel-engine' ),
				'field_type' => 'SWITCH',
				'name'       => 'social_login.enable',
			),
			array(
				'divider'    => true,
				'condition'  => 'social_login.enable === true',
				'label'      => __( 'Facebook', 'wp-travel-engine' ),
				'label_icon' => '<svg width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
								<circle cx="20" cy="20" r="20" fill="#1778F2" fill-opacity="0.08"/>
								<path d="M30 20C30 14.48 25.52 10 20 10C14.48 10 10 14.48 10 20C10 24.84 13.44 28.87 18 29.8V23H16V20H18V17.5C18 15.57 19.57 14 21.5 14H24V17H22C21.45 17 21 17.45 21 18V20H24V23H21V29.95C26.05 29.45 30 25.19 30 20Z" fill="#1778F2"/>
								</svg>',
				'field_type' => 'SWITCH',
				'name'       => 'social_login.providers.facebook.enable',
				'showValue'  => true,
			),
			array(
				'condition'  => 'social_login.providers.facebook.enable === true && social_login.enable === true',
				'field_type' => 'GROUP',
				'fields'     => array(
					array(
						'label'       => __( 'Client ID', 'wp-travel-engine' ),
						'placeholder' => __( 'Facebook Client Id', 'wp-travel-engine' ),
						'description' => __( 'Please enter a valid client id for Facebook.', 'wp-travel-engine' ),
						'field_type'  => 'TEXT',
						'name'        => 'social_login.providers.facebook.app_id',
					),
					array(
						'label'       => __( 'Client Secret', 'wp-travel-engine' ),
						'placeholder' => __( 'Facebook Client Secret', 'wp-travel-engine' ),
						'description' => __( 'Please enter a valid client secret for Facebook.', 'wp-travel-engine' ),
						'field_type'  => 'TEXT',
						'name'        => 'social_login.providers.facebook.app_secret',
					),
				),
			),

			array(
				'divider'    => true,
				'condition'  => 'social_login.enable === true',
				'label'      => __( 'Google', 'wp-travel-engine' ),
				'label_icon' => '<svg width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
								<circle cx="20" cy="20" r="20" fill="#FBBC04" fill-opacity="0.1"/>
								<g clip-path="url(#clip0_964_10278)">
								<path d="M31.7663 20.2765C31.7663 19.4608 31.7001 18.6406 31.559 17.8381H20.2402V22.4591H26.722C26.453 23.9495 25.5888 25.2679 24.3233 26.1056V29.104H28.1903C30.4611 27.014 31.7663 23.9274 31.7663 20.2765Z" fill="#4285F4"/>
								<path d="M20.2401 32.0008C23.4766 32.0008 26.2059 30.9382 28.1945 29.1039L24.3276 26.1055C23.2517 26.8375 21.8627 27.252 20.2445 27.252C17.1139 27.252 14.4595 25.1399 13.507 22.3003H9.5166V25.3912C11.5537 29.4434 15.7029 32.0008 20.2401 32.0008Z" fill="#34A853"/>
								<path d="M13.5028 22.3002C13.0001 20.8099 13.0001 19.196 13.5028 17.7057V14.6147H9.51674C7.81473 18.0055 7.81473 22.0004 9.51674 25.3912L13.5028 22.3002Z" fill="#FBBC04"/>
								<path d="M20.2401 12.7497C21.9509 12.7232 23.6044 13.367 24.8434 14.5487L28.2695 11.1226C26.1001 9.0855 23.2208 7.96553 20.2401 8.00081C15.7029 8.00081 11.5537 10.5582 9.5166 14.6148L13.5026 17.7058C14.4506 14.8617 17.1095 12.7497 20.2401 12.7497Z" fill="#EA4335"/>
								</g>
								<defs>
								<clipPath id="clip0_964_10278">
								<rect width="24" height="24" fill="white" transform="translate(8 8)"/>
								</clipPath>
								</defs>
								</svg>',
				'field_type' => 'SWITCH',
				'name'       => 'social_login.providers.google.enable',
				'showValue'  => true,
			),
			array(
				'condition'  => 'social_login.providers.google.enable === true && social_login.enable === true',
				'field_type' => 'GROUP',
				'fields'     => array(
					array(
						'label'       => __( 'Client ID', 'wp-travel-engine' ),
						'placeholder' => __( 'Google Client Id', 'wp-travel-engine' ),
						'description' => __( 'Please enter a valid client id for Google.', 'wp-travel-engine' ),
						'field_type'  => 'TEXT',
						'name'        => 'social_login.providers.google.app_id',
					),
					array(
						'label'       => __( 'Client Secret', 'wp-travel-engine' ),
						'placeholder' => __( 'Google Client Secret', 'wp-travel-engine' ),
						'description' => __( 'Please enter a valid client secret for Google.', 'wp-travel-engine' ),
						'field_type'  => 'TEXT',
						'name'        => 'social_login.providers.google.app_secret',
					),
				),
			),
			array(
				'divider'    => true,
				'condition'  => 'social_login.enable === true',
				'label'      => __( 'LinkedIn', 'wp-travel-engine' ),
				'label_icon' => '<svg width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
								<circle cx="20" cy="20" r="20" fill="#0A66C2" fill-opacity="0.1"/>
								<path d="M26.335 26.339H23.67V22.162C23.67 21.166 23.65 19.884 22.28 19.884C20.891 19.884 20.679 20.968 20.679 22.089V26.339H18.013V17.75H20.573V18.92H20.608C20.966 18.246 21.836 17.533 23.136 17.533C25.836 17.533 26.336 19.311 26.336 21.624V26.339H26.335ZM15.003 16.575C14.7996 16.5753 14.5981 16.5354 14.4101 16.4576C14.2221 16.3798 14.0513 16.2657 13.9075 16.1218C13.7636 15.9779 13.6497 15.8071 13.572 15.619C13.4944 15.431 13.4546 15.2294 13.455 15.026C13.4552 14.7198 13.5462 14.4206 13.7164 14.1661C13.8867 13.9117 14.1286 13.7134 14.4115 13.5965C14.6945 13.4795 15.0057 13.449 15.306 13.5089C15.6062 13.5689 15.882 13.7165 16.0983 13.9331C16.3147 14.1497 16.4619 14.4257 16.5215 14.726C16.581 15.0263 16.5501 15.3375 16.4328 15.6203C16.3154 15.9031 16.1169 16.1447 15.8622 16.3147C15.6075 16.4846 15.3092 16.5752 15.003 16.575ZM16.339 26.339H13.666V17.75H16.34V26.339H16.339ZM27.67 11H12.329C11.593 11 11 11.58 11 12.297V27.703C11 28.42 11.594 29 12.328 29H27.666C28.4 29 29 28.42 29 27.703V12.297C29 11.58 28.4 11 27.666 11H27.67Z" fill="#0A66C2"/>
								</svg>',
				'field_type' => 'SWITCH',
				'name'       => 'social_login.providers.linkedIn.enable',
				'showValue'  => true,
			),
			array(
				'condition'  => 'social_login.providers.linkedIn.enable === true && social_login.enable === true',
				'field_type' => 'GROUP',
				'fields'     => array(
					array(
						'label'       => __( 'Client ID', 'wp-travel-engine' ),
						'placeholder' => __( 'LinkedIn Client Id', 'wp-travel-engine' ),
						'description' => __( 'Please enter a valid client id for LinkedIn.', 'wp-travel-engine' ),
						'field_type'  => 'TEXT',
						'name'        => 'social_login.providers.linkedIn.app_id',
					),
					array(
						'label'       => __( 'Client Secret', 'wp-travel-engine' ),
						'placeholder' => __( 'LinkedIn Client Secret', 'wp-travel-engine' ),
						'description' => __( 'Please enter a valid client secret for LinkedIn.', 'wp-travel-engine' ),
						'field_type'  => 'TEXT',
						'name'        => 'social_login.providers.linkedIn.app_secret',
					),
				),
			),
		),
	)
);
