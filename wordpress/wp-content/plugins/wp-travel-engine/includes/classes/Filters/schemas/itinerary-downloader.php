<?php
/**
 * Itinerary Downloader Schema.
 */
if ( ! defined( 'WTE_ITINERARY_DOWNLOADER_ABSPATH' ) || ! file_exists( WTE_ITINERARY_DOWNLOADER_ABSPATH ) ) {
	return array();
}

return array(
	'itinerary_downloader' => array(
		'description' => __( 'Itinerary Downloader settings', 'wp-travel-engine' ),
		'type'        => 'object',
		'properties'  => array(
			'enable'                        => array(
				'description' => __( 'Enable Itinerary Downloader', 'wp-travel-engine' ),
				'type'        => 'boolean',
			),
			'popup_form'                    => array(
				'description' => __( 'Popup Form', 'wp-travel-engine' ),
				'type'        => 'object',
				'properties'  => array(
					'enable'      => array(
						'description' => __( 'Enable Popup Form', 'wp-travel-engine' ),
						'type'        => 'boolean',
					),
					'label'       => array(
						'description' => __( 'Popup Form Label', 'wp-travel-engine' ),
						'type'        => 'string',
					),
					'description' => array(
						'description' => __( 'Popup Form Description', 'wp-travel-engine' ),
						'type'        => 'string',
					),
				),
			),
			'enable_mailchimp'              => array(
				'description' => __( 'Enable Mailchimp', 'wp-travel-engine' ),
				'type'        => 'boolean',
			),
			'mailchimp_api_key'             => array(
				'description' => __( 'Mailchimp API Key', 'wp-travel-engine' ),
				'type'        => 'string',
			),
			'download_btn_label'            => array(
				'description' => __( 'Download Button Main Label', 'wp-travel-engine' ),
				'type'        => 'string',
			),
			'download_btn_description'      => array(
				'description' => __( 'Download Button Main Description', 'wp-travel-engine' ),
				'type'        => 'string',
			),
			'enable_user_consent'           => array(
				'description' => __( 'Enable User Consent', 'wp-travel-engine' ),
				'type'        => 'boolean',
			),
			'user_consent_info'             => array(
				'description' => __( 'User Consent Info', 'wp-travel-engine' ),
				'type'        => 'string',
			),
			'enable_user_consent_mandatory' => array(
				'description' => __( 'User Consent Required or Not', 'wp-travel-engine' ),
				'type'        => 'boolean',
			),
			'reply_to_email'                => array(
				'description' => __( 'Reply To Email', 'wp-travel-engine' ),
				'type'        => 'string',
			),
			'email_subject'                 => array(
				'description' => __( 'Email Subject', 'wp-travel-engine' ),
				'type'        => 'string',
			),
			'email_content'                 => array(
				'description' => __( 'Email Content', 'wp-travel-engine' ),
				'type'        => 'string',
			),
			'pdf_content'                   => array(
				'description' => __( 'PDF Content Settings', 'wp-travel-engine' ),
				'type'        => 'object',
				'properties'  => array(
					'custom_logo'              => array(
						'description' => __( 'Company Custom Logo', 'wp-travel-engine' ),
						'type'        => 'object',
						'properties'  => array(
							'id'  => array(
								'description' => __( 'ID', 'wp-travel-engine' ),
								'type'        => 'integer',
							),
							'alt' => array(
								'description' => __( 'Alternative String', 'wp-travel-engine' ),
								'type'        => 'string',
							),
							'url' => array(
								'description' => __( 'URL', 'wp-travel-engine' ),
								'type'        => 'string',
							),
						),
					),
					'description'              => array(
						'description' => __( 'Company Description', 'wp-travel-engine' ),
						'type'        => 'string',
					),
					'email_us_label'           => array(
						'description' => __( 'Label above contact email in PDF info content page', 'wp-travel-engine' ),
						'type'        => 'string',
					),
					'email_address'            => array(
						'description' => __( 'Company Email Address', 'wp-travel-engine' ),
						'type'        => 'string',
					),
					'address'                  => array(
						'description' => __( 'Company Address Location', 'wp-travel-engine' ),
						'type'        => 'string',
					),
					'address_label'            => array(
						'description' => __( 'Company Address Location Label', 'wp-travel-engine' ),
						'type'        => 'string',
					),
					'theme_color'              => array(
						'description' => __( 'PDF Base Color Theme', 'wp-travel-engine' ),
						'type'        => 'string',
					),
					'phone_number'             => array(
						'description' => __( 'Company Phone Number', 'wp-travel-engine' ),
						'type'        => 'string',
					),
					'enable_expert_chat'       => array(
						'description' => __( 'Expert Chat Enabled or Not', 'wp-travel-engine' ),
						'type'        => 'boolean',
					),
					'expert_chat_label'        => array(
						'description' => __( 'Expert Chat Label', 'wp-travel-engine' ),
						'type'        => 'string',
					),
					'expert_email'             => array(
						'description' => __( 'Expert Email', 'wp-travel-engine' ),
						'type'        => 'string',
					),
					'expert_avatar_img'        => array(
						'description' => __( 'Expert Avatar Image', 'wp-travel-engine' ),
						'type'        => 'object',
						'properties'  => array(
							'id'  => array(
								'description' => __( 'ID', 'wp-travel-engine' ),
								'type'        => 'integer',
							),
							'alt' => array(
								'description' => __( 'Alternative String', 'wp-travel-engine' ),
								'type'        => 'string',
							),
							'url' => array(
								'description' => __( 'URL', 'wp-travel-engine' ),
								'type'        => 'string',
							),
						),
					),
					'expert_phone_number'      => array(
						'description' => __( 'Expert Phone Number', 'wp-travel-engine' ),
						'type'        => 'string',
					),
					'enable_viber_contact'     => array(
						'description' => __( 'Enable Viber Contact', 'wp-travel-engine' ),
						'type'        => 'boolean',
					),
					'enable_whatsapp_contact'  => array(
						'description' => __( 'Enable Whatsapp Contact', 'wp-travel-engine' ),
						'type'        => 'boolean',
					),
					'info_page_background_img' => array(
						'description' => __( 'Company Information Page Background Image', 'wp-travel-engine' ),
						'type'        => 'object',
						'properties'  => array(
							'id'  => array(
								'description' => __( 'ID', 'wp-travel-engine' ),
								'type'        => 'integer',
							),
							'alt' => array(
								'description' => __( 'Alternative String', 'wp-travel-engine' ),
								'type'        => 'string',
							),
							'url' => array(
								'description' => __( 'URL', 'wp-travel-engine' ),
								'type'        => 'string',
							),
						),
					),
					'include_fixed_date'       => array(
						'description' => __( 'Include Fixed Date Availability', 'wp-travel-engine' ),
						'type'        => 'boolean',
					),
				),
			),
		),
	),
);
