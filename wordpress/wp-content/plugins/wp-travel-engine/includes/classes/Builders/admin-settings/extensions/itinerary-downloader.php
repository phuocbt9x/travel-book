<?php
/**
 * Extensions Itinerary Downloader Tab Settings.
 *
 * @since 6.2.0
 */
$is_itinerary_downloader = defined( 'WTE_ITINERARY_DOWNLOADER_ABSPATH' );
$active_extensions       = apply_filters( 'wpte_get_global_extensions_tab', array() );
$file_path               = $active_extensions['wte_itinerary_downloader']['content_path'] ?? '';
if ( ! file_exists( $file_path ) ) {
	return array();
}
/**
 * @todo: Make fields default values dynamic
 */

return apply_filters(
	'extension_itinerary_downloader',
	array(
		'is_active' => $is_itinerary_downloader,
		'title'     => __( 'Itinerary Downloader', 'wp-travel-engine' ),
		'order'     => 40,
		'id'        => 'extension-itinerary-downloader',
		'fields'    => array(
			array(
				'field_type' => 'TITLE',
				'title'      => __( 'Main Settings', 'wp-travel-engine' ),
			),
			array(
				'divider'     => true,
				'label'       => __( 'Itinerary Downloader', 'wp-travel-engine' ),
				'description' => __( 'Global Enable/Disable for File Downloader', 'wp-travel-engine' ),
				'field_type'  => 'SWITCH',
				'name'        => 'itinerary_downloader.enable',
				'default'     => '',
			),
			array(
				'divider'     => true,
				'label'       => __( 'Popup Email Form', 'wp-travel-engine' ),
				'description' => __( 'Check this if you want to enable Email Form. If enabled, this will overwrite default action of button (Direct Download on click) and change it to Popup Email Form', 'wp-travel-engine' ),
				'field_type'  => 'SWITCH',
				'name'        => 'itinerary_downloader.popup_form.enable',
				'default'     => '',
			),
			array(
				'divider'     => true,
				'label'       => __( 'Apply MailChimp', 'wp-travel-engine' ),
				'description' => __( 'Check this if you want to enable mailchimp', 'wp-travel-engine' ),
				'field_type'  => 'SWITCH',
				'name'        => 'itinerary_downloader.enable_mailchimp',
				'default'     => '',
			),
			array(
				'divider'     => true,
				'label'       => __( 'Mailchimp API Key', 'wp-travel-engine' ),
				'description' => sprintf( __( 'Get an %1$sAPI Key%2$s', 'wp-travel-engine' ), '<a href="//admin.mailchimp.com/account/api/" target="_blank">', '</a>' ),
				'field_type'  => 'TEXT',
				'name'        => 'itinerary_downloader.mailchimp_api_key',
				'default'     => '',
			),
			array(
				'field_type' => 'ALERT',
				'content'    => sprintf( __( '<strong>Note: [wte_itinerary_downloader]</strong> - Usable shortcode to display download button/link.', 'wp-travel-engine' ), '<a href="//admin.mailchimp.com/account/api/" target="_blank">', '</a>' ),
			),
			array(
				'field_type' => 'TITLE',
				'title'      => __( 'Label Text Settings', 'wp-travel-engine' ),
			),
			array(
				'divider'     => true,
				'label'       => __( 'Download Button Main Label', 'wp-travel-engine' ),
				'description' => __( 'Label above download button', 'wp-travel-engine' ),
				'field_type'  => 'TEXT',
				'name'        => 'itinerary_downloader.download_btn_label',
				'default'     => 'Want to read it later?',
			),
			array(
				'divider'     => true,
				'label'       => __( 'Download Button Main Description', 'wp-travel-engine' ),
				'description' => __( 'Description above download button', 'wp-travel-engine' ),
				'field_type'  => 'TEXTAREA',
				'name'        => 'itinerary_downloader.download_btn_description',
				'default'     => 'Download this tour\'s PDF brochure and start your planning offline.',
			),
			array(
				'divider'     => true,
				'label'       => __( 'Popup Form Main Label', 'wp-travel-engine' ),
				'description' => __( 'Label for popup email form', 'wp-travel-engine' ),
				'field_type'  => 'TEXT',
				'name'        => 'itinerary_downloader.popup_form.label',
				'default'     => 'Almost there!',
			),
			array(
				'divider'     => true,
				'label'       => __( 'Popup Form Main Description', 'wp-travel-engine' ),
				'description' => __( 'Please enter your email address and click the button below to get itinerary sent directly to your email address.', 'wp-travel-engine' ),
				'field_type'  => 'TEXTAREA',
				'name'        => 'itinerary_downloader.popup_form.description',
				'default'     => 'Enter your email address to get the download link.',
			),
			array(
				'field_type' => 'TITLE',
				'title'      => __( 'User Consent Settings', 'wp-travel-engine' ),
			),
			array(
				'divider'     => true,
				'label'       => __( 'User Consent Checkbox', 'wp-travel-engine' ),
				'description' => __( 'Check this if you want to User Consent for Email Form. If enabled, this will display the checkbox that notify user that email will also be stored in mailchimp.', 'wp-travel-engine' ),
				'field_type'  => 'SWITCH',
				'name'        => 'itinerary_downloader.enable_user_consent',
				'default'     => '',
			),
			array(
				'divider'    => true,
				'label'      => __( 'User Consent Info', 'wp-travel-engine' ),
				'field_type' => 'TEXTAREA',
				'name'       => 'itinerary_downloader.user_consent_info',
				'default'    => 'After signing up for the newsletter, you will occasionally receive mail regarding offers, releases & notices. We will not sell or distribute your email address to a third party at any time.',
			),
			array(
				'divider'     => true,
				'label'       => __( 'Make User Consent Checkbox Required', 'wp-travel-engine' ),
				'description' => __( 'Check this, if you want to make User Consent checkbox required in order to continue.', 'wp-travel-engine' ),
				'field_type'  => 'SWITCH',
				'name'        => 'itinerary_downloader.enable_user_consent_mandatory',
				'default'     => '',
			),
			array(
				'field_type' => 'TITLE',
				'title'      => __( 'Email Settings', 'wp-travel-engine' ),
			),
			array(
				'divider'    => true,
				'label'      => __( 'Reply To Email', 'wp-travel-engine' ),
				'field_type' => 'TEXT',
				'name'       => 'itinerary_downloader.reply_to_email',
				'default'    => '',
			),
			array(
				'divider'    => true,
				'label'      => __( 'Email Subject', 'wp-travel-engine' ),
				'field_type' => 'TEXT',
				'name'       => 'itinerary_downloader.email_subject',
				'default'    => 'Please Find The Itinerary PDF Attached',
			),
			array(
				'divider'    => true,
				'label'      => __( 'Email Body Message', 'wp-travel-engine' ),
				'field_type' => 'TEXTAREA',
				'name'       => 'itinerary_downloader.email_content',
				'default'    => 'Hello, Please find the requested PDF of #trip attached. Thanks & Regards',
			),
			array(
				'field_type' => 'TITLE',
				'title'      => __( 'PDF Content Settings', 'wp-travel-engine' ),
			),
			array(
				'divider'     => true,
				'label'       => __( 'Company Custom Logo', 'wp-travel-engine' ),
				'description' => __( 'PDF Content Custom Logo. If empty, plugin will search for logo in customizer.', 'wp-travel-engine' ),
				'field_type'  => 'GALLERY',
				'name'        => 'itinerary_downloader.pdf_content.custom_logo',
			),
			array(
				'divider'     => true,
				'label'       => __( 'Short Company Description', 'wp-travel-engine' ),
				'description' => __( 'Company Summary text. Please insert short description about the company here. 40 words 240 characters or less for best result.', 'wp-travel-engine' ),
				'field_type'  => 'TEXTAREA',
				'name'        => 'itinerary_downloader.pdf_content.description',
				'default'     => '',
			),
			array(
				'divider'     => true,
				'label'       => __( 'Email Us - Label', 'wp-travel-engine' ),
				'description' => __( 'Default: Quick Questions? Email Us. Label above contact email in PDF Info Content page i.e. last page of PDF', 'wp-travel-engine' ),
				'field_type'  => 'TEXT',
				'name'        => 'itinerary_downloader.pdf_content.email_us_label',
				'default'     => 'Quick Questions? Email Us',
			),
			array(
				'divider'     => true,
				'label'       => __( 'Company Email Address', 'wp-travel-engine' ),
				'description' => __( 'Company Email Address that will be displayed in the pdf footer, last content page.', 'wp-travel-engine' ),
				'field_type'  => 'TEXT',
				'name'        => 'itinerary_downloader.pdf_content.email_address',
				'default'     => '',
			),
			array(
				'divider'     => true,
				'label'       => __( 'Company Location Address Label', 'wp-travel-engine' ),
				'description' => __( 'Default: Address. Label above contact address in PDF Info Content page i.e. last page of PDF', 'wp-travel-engine' ),
				'field_type'  => 'TEXT',
				'name'        => 'itinerary_downloader.pdf_content.address_label',
				'default'     => 'Address',
			),
			array(
				'divider'     => true,
				'label'       => __( 'Company Location Address', 'wp-travel-engine' ),
				'description' => __( 'Company address will be displayed in pdf footer.', 'wp-travel-engine' ),
				'field_type'  => 'TEXT',
				'name'        => 'itinerary_downloader.pdf_content.address',
				'default'     => '',
			),
			array(
				'divider'     => true,
				'label'       => __( 'PDF Base Color Theme', 'wp-travel-engine' ),
				'description' => __( 'Base Color scheme the pdf file.', 'wp-travel-engine' ),
				'field_type'  => 'COLOR_PICKER',
				'name'        => 'itinerary_downloader.pdf_content.theme_color',
			),
			array(
				'divider'     => true,
				'label'       => __( 'Company Telephone No.', 'wp-travel-engine' ),
				'description' => __( 'company telephone address will be displayed in pdf footer.', 'wp-travel-engine' ),
				'field_type'  => 'TEXT',
				'name'        => 'itinerary_downloader.pdf_content.phone_number',
			),
			array(
				'divider'     => true,
				'label'       => __( 'Talk To Expert - section in PDF', 'wp-travel-engine' ),
				'description' => __( 'Check this, if you want to enable - Talk To Expert - section', 'wp-travel-engine' ),
				'field_type'  => 'SWITCH',
				'name'        => 'itinerary_downloader.pdf_content.enable_expert_chat',
				'default'     => '',
			),
			array(
				'divider'     => true,
				'label'       => __( 'Talk to Company Expert label', 'wp-travel-engine' ),
				'description' => __( 'Default: Talk to an Expert. Please add your label text here.', 'wp-travel-engine' ),
				'field_type'  => 'TEXT',
				'name'        => 'itinerary_downloader.pdf_content.expert_chat_label',
				'default'     => 'Talk To an Expert',
			),
			array(
				'divider'     => true,
				'label'       => __( 'Expert Email', 'wp-travel-engine' ),
				'description' => __( 'Please add your expert personnel email here.', 'wp-travel-engine' ),
				'field_type'  => 'TEXT',
				'name'        => 'itinerary_downloader.pdf_content.expert_email',
				'default'     => '',
			),
			array(
				'divider'     => true,
				'label'       => __( 'Expert Avatar Image', 'wp-travel-engine' ),
				'description' => __( 'Please add your expert avatar image here.', 'wp-travel-engine' ),
				'field_type'  => 'GALLERY',
				'name'        => 'itinerary_downloader.pdf_content.expert_avatar_img',
			),
			array(
				'divider'     => true,
				'label'       => __( 'Expert Telephone No.', 'wp-travel-engine' ),
				'description' => __( 'Please add your expert personnel telephone address here.', 'wp-travel-engine' ),
				'field_type'  => 'TEXT',
				'name'        => 'itinerary_downloader.pdf_content.expert_phone_number',
				'default'     => '',
			),
			array(
				'divider'     => true,
				'label'       => __( 'Viber contact Available', 'wp-travel-engine' ),
				'description' => __( 'Check this if you have Viber enabled for the company & user can add above telephone number in Viber to contact the Company/Expert.', 'wp-travel-engine' ),
				'field_type'  => 'SWITCH',
				'name'        => 'itinerary_downloader.pdf_content.enable_viber_contact',
				'default'     => '',
			),
			array(
				'divider'     => true,
				'label'       => __( 'Whatsapp contact Available', 'wp-travel-engine' ),
				'description' => __( 'Check this if you have Whatsapp enabled for the company & user can add above telephone number in Whatsapp to contact the Company/Expert.', 'wp-travel-engine' ),
				'field_type'  => 'SWITCH',
				'name'        => 'itinerary_downloader.pdf_content.enable_whatsapp_contact',
				'default'     => '',
			),
			array(
				'divider'     => true,
				'label'       => __( 'Company Info Page Background Image', 'wp-travel-engine' ),
				'description' => __( 'Upload PDF Info Content page background image ( i.e. the last page of pdf)', 'wp-travel-engine' ),
				'field_type'  => 'GALLERY',
				'name'        => 'itinerary_downloader.pdf_content.info_page_background_img',
			),
			array(
				'field_type' => 'TITLE',
				'title'      => __( 'Include/Exclude particular PDF content', 'wp-travel-engine' ),
			),
			array(
				'label'       => __( 'Availability - to PDF', 'wp-travel-engine' ),
				'description' => __( 'Check this if you want to also add - Trip Fixed Date Availability - Section into PDF', 'wp-travel-engine' ),
				'field_type'  => 'SWITCH',
				'name'        => 'itinerary_downloader.pdf_content.include_fixed_date',
				'default'     => '',
			),
		),
	)
);
