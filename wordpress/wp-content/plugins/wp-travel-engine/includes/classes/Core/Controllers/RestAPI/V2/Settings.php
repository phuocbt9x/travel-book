<?php

/**
 * WP Travel Engine Settings API.
 *
 * @package WP Travel Engine
 * @since 6.2.0
 */

namespace WPTravelEngine\Core\Controllers\RestAPI\V2;

use WP_Error;
use WP_REST_Request;
use WP_REST_Response;
use WP_REST_Server;
use WPTravelEngine\Core\Models\Settings\Options;
use WPTravelEngine\Core\Models\Settings\PluginSettings;
use WPTravelEngine\Core\Models\Settings\StaticStrings;
use WPTravelEngine\Helpers\Translators;
use WPTravelEngine\PaymentGateways\PaymentGateways;
use WPTravelEngine\Utilities\ArrayUtility;

/**
 * Settings API class.
 *
 * @since 6.2.0
 */
class Settings {

	/**
	 * REST API Namespace.
	 */
	protected string $namespace;

	/**
	 * Plugin settings.
	 *
	 * @var PluginSettings
	 */
	public PluginSettings $plugin_settings;

	/**
	 * Error object.
	 *
	 * @var WP_Error
	 */
	protected WP_Error $errors;

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->plugin_settings = new PluginSettings();
		$this->namespace       = 'wptravelengine/v2';
	}

	/**
	 * Register routes.
	 */
	public function register_routes() {

		register_rest_route(
			$this->namespace,
			'/schema',
			array(
				'methods'             => WP_REST_Server::READABLE,
				'callback'            => array( $this, 'get_schema' ),
				'permission_callback' => array( $this, 'get_permission' ),
			)
		);

		register_rest_route(
			$this->namespace,
			'/settings',
			array(
				'methods'             => WP_REST_Server::READABLE,
				'callback'            => array( $this, 'get_settings' ),
				'permission_callback' => array( $this, 'get_permission' ),
			)
		);

		register_rest_route(
			$this->namespace,
			'/settings',
			array(
				'methods'             => WP_REST_Server::CREATABLE,
				'callback'            => array( $this, 'update_settings' ),
				'permission_callback' => array( $this, 'get_permission' ),
			)
		);
	}

	/**
	 * Checks for permissions.
	 *
	 * @return bool
	 * @return bool
	 * @since 6.2.0
	 */
	public function get_permission(): bool {
		return current_user_can( 'manage_options' );
	}

	/**
	 * Prepare Page Settings.
	 *
	 * @param WP_REST_Request $request Request object.
	 *
	 * @return array
	 * @since 6.2.0
	 */
	protected function prepare_page_settings( WP_REST_Request $request ): array {

		$pages = array(
			'checkout_page'          => 'pages.wp_travel_engine_place_order',
			'terms_and_conditions'   => 'pages.wp_travel_engine_terms_and_conditions',
			'thank_you_page'         => 'pages.wp_travel_engine_thank_you',
			'confirmation_page'      => 'pages.wp_travel_engine_confirmation_page',
			'dashboard_page'         => 'pages.wp_travel_engine_dashboard_page',
			'enquiry_thank_you_page' => 'pages.enquiry',
			'wishlist_page'          => 'pages.wp_travel_engine_wishlist',
			'search_page'            => 'pages.search',
		);

		return array_map(
			function ( $key ) use ( $request ) {
				return (int) $this->plugin_settings->get( $key );
			},
			$pages
		);
	}

	/**
	 * Prepare Trip Tabs.
	 *
	 * @param WP_REST_Request $request Request object.
	 *
	 * @return array
	 * @since 6.2.0
	 */
	protected function prepare_trip_tabs( WP_REST_Request $request ): array {

		$trip_tabs = $this->plugin_settings->get( 'trip_tabs' );

		$_trip_tabs = array();
		foreach ( $trip_tabs['id'] ?? array() as $id ) {
			$id           = (int) $id;
			$_trip_tabs[] = array(
				'id'        => (int) $trip_tabs['id'][ $id ],
				'name'      => (string) $trip_tabs['name'][ $id ],
				'field'     => (string) $trip_tabs['field'][ $id ],
				'icon'      => (array) ( $trip_tabs['icon'][ $id ] ?? array_fill_keys( array( 'icon', 'view_box', 'path' ), '' ) ),
				'enable'    => wptravelengine_toggled( $trip_tabs['enable'][ $id ] ?? false ),
				// @TODO: This is not safe condition check.
				'trashable' => $id !== 1 && $trip_tabs['field'][ $id ] == 'wp_editor',
			);
		}

		return array( 'trip_tabs' => $_trip_tabs );
	}

	/**
	 * Prepare Trip Facts.
	 *
	 * @param WP_REST_Request $request Request object.
	 *
	 * @return array
	 * @since 6.2.0
	 */
	protected function prepare_trip_infos( WP_REST_Request $request ): array {

		$default_trip_info = $this->plugin_settings->get( 'default_trip_facts', array() );

		$trip_info = array();
		$i         = 0;
		foreach ( $default_trip_info as $key => $info ) {
			$trip_info[] = array(
				'id'          => (string) $key,
				'name'        => (string) $info['field_id'],
				'placeholder' => (string) $info['input_placeholder'],
				'type'        => (string) $info['field_type'],
				'icon'        => (array) $info['field_icon'],
				'options'     => array(),
				'enable'      => wptravelengine_toggled( $info['enabled'] ?? 'no' ),
				'trashable'   => false,
			);
		}

		$additional_trip_info = $this->plugin_settings->get( 'trip_facts', array() );
		foreach ( $additional_trip_info['fid'] ?? array() as $info_id ) {
			$trip_info[] = array(
				'id'          => (string) $info_id,
				'name'        => (string) $additional_trip_info['field_id'][ $info_id ] ?? '',
				'placeholder' => (string) $additional_trip_info['input_placeholder'][ $info_id ] ?? '',
				'type'        => (string) $additional_trip_info['field_type'][ $info_id ] ?? '',
				'icon'        => (array) $additional_trip_info['field_icon'][ $info_id ] ?? array(),
				'options'     => isset( $additional_trip_info['select_options'][ $info_id ] ) ? explode( ',', $additional_trip_info['select_options'][ $info_id ] ) : array(),
				'enable'      => wptravelengine_toggled( $additional_trip_info['enabled'][ $info_id ] ?? 'yes' ),
				'trashable'   => true,
			);
		}

		return compact( 'trip_info' );
	}

	/**
	 * Prepare Trip Settings.
	 *
	 * @param WP_REST_Request $request Request object.
	 *
	 * @return array
	 * @since 6.2.0
	 */
	protected function prepare_trip_settings( WP_REST_Request $request ): array {
		$trip_highlights = array_filter( (array) $this->plugin_settings->get( 'trip_highlights' ) );

		$highlights   = array_map(
			function ( $highlight ) {
				return array(
					'title'       => (string) $highlight['highlight'],
					'description' => (string) $highlight['help'],
				);
			},
			$trip_highlights
		);
		$pricing_type = array_values( wptravelengine_get_pricing_type( true ) );

		return compact( 'highlights', 'pricing_type' );
	}

	/**
	 * Prepare Emails Configuration.
	 *
	 * @param WP_REST_Request $request Request object.
	 *
	 * @return array
	 * @since 6.5.0
	 */
	protected function prepare_emails_configuration( WP_REST_Request $request ): array {

		$temp_admin_email_notifi_tabs = $this->plugin_settings->get(
			'admin_email_notify_tabs',
			array(
				'booking_confirmation' => array(
					'id'                 => 'booking_confirmation',
					'name'               => __( 'Booking Confirmation', 'wp-travel-engine' ),
					'subject'            => (string) $this->plugin_settings->get( 'email.booking_notification_subject_admin' ),
					'content'            => (string) $this->plugin_settings->get( 'email.booking_notification_template_admin' ),
					'enabled'            => wptravelengine_replace( ! wptravelengine_toggled( $this->plugin_settings->get( 'email.disable_booking_notification' ) ), true, 'yes', 'no' ),
					'is_default'         => 'yes',
					'notify_description' => __( 'This email is sent to the admin when a booking is confirmed.', 'wp-travel-engine' ),
				),
				'payment_confirmation' => array(
					'id'         => 'payment_confirmation',
					'name'       => __( 'Payment Confirmation', 'wp-travel-engine' ),
					'subject'    => (string) $this->plugin_settings->get( 'email.sale_subject' ),
					'content'    => (string) $this->plugin_settings->get( 'email.sales_wpeditor' ),
					'enabled'    => 'yes',
					'is_default' => 'yes',
				),
			)
		);

		$admin_email_notifi_tabs = array();
		foreach ( $temp_admin_email_notifi_tabs as $key => $value ) {
			$value['addon']          ??= 'wptravelengine';
			$value['enabled']          = wptravelengine_toggled( $value['enabled'] );
			$value['is_default']       = wptravelengine_toggled( $value['is_default'] );
			$value['show']             = wptravelengine_is_addon_active( $value['addon'] );
			$admin_email_notifi_tabs[] = $value;
		}

		$temp_customer_email_notifi_tabs = $this->plugin_settings->get(
			'customer_email_notify_tabs',
			array(
				'booking_confirmation' => array(
					'id'         => 'booking_confirmation',
					'name'       => __( 'Booking Confirmation', 'wp-travel-engine' ),
					'subject'    => (string) $this->plugin_settings->get( 'email.booking_notification_subject_customer' ),
					'content'    => (string) $this->plugin_settings->get( 'email.booking_notification_template_customer' ),
					'enabled'    => wptravelengine_replace( wptravelengine_toggled( $this->plugin_settings->get( 'email.enable_cust_notif', 'yes' ) ), true, 'yes', 'no' ),
					'is_default' => 'yes',
				),
				'payment_confirmation' => array(
					'id'         => 'payment_confirmation',
					'name'       => __( 'Payment Confirmation', 'wp-travel-engine' ),
					'subject'    => (string) $this->plugin_settings->get( 'email.subject' ),
					'content'    => (string) $this->plugin_settings->get( 'email.purchase_wpeditor' ),
					'enabled'    => 'yes',
					'is_default' => 'yes',
				),
				'account_registration' => array(
					'id'         => 'account_registration',
					'name'       => __( 'Account Registration', 'wp-travel-engine' ),
					'subject'    => 'Your account has been created on {sitename}',
					'content'    => wte_get_template_html( 'template-emails/customer/account-registration.php' ),
					'enabled'    => 'yes',
					'is_default' => 'yes',
				),
				'forgot_password'      => array(
					'id'         => 'forgot_password',
					'name'       => __( 'Forgot Password', 'wp-travel-engine' ),
					'subject'    => 'Reset Your Password – {sitename}',
					'content'    => wte_get_template_html( 'template-emails/customer/forgot-password.php' ),
					'enabled'    => 'yes',
					'is_default' => 'yes',
				),
				'enquiry'              => array(
					'id'         => 'enquiry',
					'name'       => __( 'Enquiry', 'wp-travel-engine' ),
					'subject'    => (string) $this->plugin_settings->get( 'email.enquiry_subject', 'Enquiry received' ),
					'content'    => wte_get_template_html( 'template-emails/enquiry.php' ),
					'enabled'    => wptravelengine_replace( wptravelengine_toggled( $this->plugin_settings->get( 'email.cust_notif', '1' ) ), true, '1', '0' ),
					'is_default' => 'yes',
				),
			)
		);

		$customer_email_notifi_tabs = array();
		foreach ( $temp_customer_email_notifi_tabs as $key => $value ) {
			$value['addon']             ??= 'wptravelengine';
			$value['enabled']             = wptravelengine_toggled( $value['enabled'] );
			$value['is_default']          = wptravelengine_toggled( $value['is_default'] );
			$value['show']                = wptravelengine_is_addon_active( $value['addon'] );
			$customer_email_notifi_tabs[] = $value;
		}

		$admin_email_notifi_tabs = array_map(
			function ( $value ) use ( $admin_email_notifi_tabs ) {
				switch ( $value['id'] ) {
					case 'booking_confirmation':
						$value['notify_description'] = __( 'This email is to notify the admin that a new booking has been placed as soon as a customer completes the checkout process.', 'wp-travel-engine' );
						break;
					case 'payment_confirmation':
						$value['notify_description'] = __( 'This email is to notify the admin that a payment has been successfully received and confirmed for a booking.', 'wp-travel-engine' );
						break;
				}
				return $value;
			},
			$admin_email_notifi_tabs
		);

		$customer_email_notifi_tabs = array_map(
			function ( $value ) use ( $customer_email_notifi_tabs ) {
				switch ( $value['id'] ) {
					case 'booking_confirmation':
						$value['notify_description'] = __( 'This email is sent to customers after they complete checkout, confirming that their trip has been successfully booked.', 'wp-travel-engine' );
						break;
					case 'payment_confirmation':
						$value['notify_description'] = __( 'This email is sent to customers after they complete payment for the booking.', 'wp-travel-engine' );
						break;
					case 'account_registration':
						$value['notify_description'] = __( 'This email is sent to customers when their account is successfully created on your website.', 'wp-travel-engine' );
						break;
					case 'forgot_password':
						$value['notify_description'] = __( 'This email is sent to customers when they request a password reset.', 'wp-travel-engine' );
						break;
					case 'enquiry':
						$value['notify_description'] = __( 'This email is sent to customers after they submit an enquiry form on your website confirming that their enquiry has been received.', 'wp-travel-engine' );
						break;
				}
				return $value;
			},
			$customer_email_notifi_tabs
		);

		return array(
			'email_notification' => array(
				'admin'    => $admin_email_notifi_tabs,
				'customer' => $customer_email_notifi_tabs,
			),
		);
	}

	/**
	 * Prepare Emails Settings.
	 *
	 * @param WP_REST_Request $request Request object.
	 *
	 * @return array
	 * @since 6.5.0
	 */
	protected function prepare_emails_settings( WP_REST_Request $request ): array {

		$settings = array();

		$settings['email_settings'] = array(
			'enquiry_emails' => (array) explode( ',', $this->plugin_settings->get( 'email.enquiry_emailaddress', Options::get( 'admin_email' ) ) ),
			'sale_emails'    => (array) explode( ',', $this->plugin_settings->get( 'email.emails', Options::get( 'admin_email' ) ) ),
			'from_name'      => (string) $this->plugin_settings->get( 'email.name', get_bloginfo( 'name' ) ),
			'from'           => (string) $this->plugin_settings->get( 'email.from', Options::get( 'admin_email' ) ),
			'reply_to'       => (string) $this->plugin_settings->get( 'email.reply_to', Options::get( 'admin_email' ) ),
			'logo'           => array(
				'id'  => (string) $this->plugin_settings->get( 'email.logo.id', Options::get( 'site_icon' ) ),
				'url' => (string) $this->plugin_settings->get( 'email.logo.url', wp_get_attachment_image_url( Options::get( 'site_icon' ) ) ? wp_get_attachment_image_url( Options::get( 'site_icon' ) ) : '' ),
			),
			'footer'         => (string) $this->plugin_settings->get( 'email.footer', 'Copyright © ' . date( 'Y' ) . ' | ' . get_bloginfo( 'name' ) . '. All rights reserved.' ),
		);

		return $settings;
	}

	/**
	 * Prepare Admin Emails Configuration.
	 *
	 * @param WP_REST_Request $request Request object.
	 *
	 * @return array
	 * @since 6.2.0
	 */
	protected function prepare_admin_emails_configuration( WP_REST_Request $request ): array {

		$settings = array();

		$settings['admin_email'] = array(
			'email_addresses' => (array) explode( ',', $this->plugin_settings->get( 'email.emails', '' ) ),
			'enable'          => ! wptravelengine_toggled( $this->plugin_settings->get( 'email.disable_notif' ) ),
		);

		$settings['admin_booking_notification'] = array(
			'subject'  => (string) $this->plugin_settings->get( 'email.booking_notification_subject_admin' ),
			'template' => (string) $this->plugin_settings->get( 'email.booking_notification_template_admin' ),
			'enable'   => ! wptravelengine_toggled( $this->plugin_settings->get( 'email.disable_booking_notification' ) ),
		);

		$settings['admin_payment_notification'] = array(
			'subject'  => (string) $this->plugin_settings->get( 'email.sale_subject' ),
			'template' => (string) $this->plugin_settings->get( 'email.sales_wpeditor' ),
		);

		return $settings;
	}

	/**
	 * Prepare Settings.
	 *
	 * @param WP_REST_Request $request Request object.
	 *
	 * @return array
	 * @since 6.2.0
	 */
	protected function prepare_customer_emails_configuration( WP_REST_Request $request ): array {
		$settings                             = array();
		$settings['customer_receipt_details'] = array(
			'admin_name'          => (string) $this->plugin_settings->get( 'email.name', get_bloginfo( 'name' ) ),
			'admin_email_address' => (string) $this->plugin_settings->get( 'email.from', Options::get( 'admin_email' ) ),
		);

		$settings['customer_booking_notification'] = array(
			'subject'  => (string) $this->plugin_settings->get( 'email.booking_notification_subject_customer' ),
			'template' => (string) $this->plugin_settings->get( 'email.booking_notification_template_customer' ),
			'enable'   => wptravelengine_toggled( $this->plugin_settings->get( 'email.enable_cust_notif', 'yes' ) ),
		);

		$settings['customer_purchase_notification'] = array(
			'subject'  => (string) $this->plugin_settings->get( 'email.subject' ),
			'template' => (string) $this->plugin_settings->get( 'email.purchase_wpeditor' ),
		);

		return $settings;
	}

	/**
	 * Prepare Enquiry Form Configuration.
	 *
	 * @param WP_REST_Request $request Request object.
	 *
	 * @return array
	 * @since 6.2.0
	 */
	protected function prepare_enquiry_form_configuration( WP_REST_Request $request ): array {

		$enquiry_form = array(
			'email_addresses' => (array) explode( ',', $this->plugin_settings->get( 'email.enquiry_emailaddress', Options::get( 'admin_email' ) ) ),
			'email_subject'   => (string) $this->plugin_settings->get( 'query_subject', 'Enquiry received' ),
			'notify_customer' => wptravelengine_toggled( $this->plugin_settings->get( 'email.cust_notif' ) ),
			'powered_by_link' => ! wptravelengine_toggled( $this->plugin_settings->get( 'hide_powered_by' ) ),
		);

		return compact( 'enquiry_form' );
	}

	/**
	 * Prepare Trip Card of Display Tabs.
	 *
	 * @param WP_REST_Request $request Request object.
	 *
	 * @return array
	 * @since 6.2.0
	 */
	protected function prepare_trip_card_details( WP_REST_Request $request ): array {

		$settings                    = array();
		$settings['card_new_layout'] = array(
			// 'enable'                  => wptravelengine_toggled( $this->plugin_settings->get( 'display_new_trip_listing' ) ),
			'enable_slider'           => wptravelengine_toggled( $this->plugin_settings->get( 'display_slider_layout', '1' ) ),
			'enable_featured_tag'     => wptravelengine_toggled( $this->plugin_settings->get( 'show_featured_tag', '1' ) ),
			'enable_wishlist'         => wptravelengine_toggled( $this->plugin_settings->get( 'show_wishlist', '1' ) ),
			'enable_map'              => wptravelengine_toggled( $this->plugin_settings->get( 'show_map_on_card', '1' ) ),
			'enable_excerpt'          => wptravelengine_toggled( $this->plugin_settings->get( 'show_excerpt', '1' ) ),
			'enable_difficulty'       => wptravelengine_toggled( $this->plugin_settings->get( 'show_difficulty_tax', '1' ) ),
			'enable_tags'             => wptravelengine_toggled( $this->plugin_settings->get( 'show_trips_tag', '1' ) ),
			'enable_fsd'              => wptravelengine_toggled( $this->plugin_settings->get( 'show_date_layout', '1' ) ),
			'enable_available_months' => wptravelengine_toggled( $this->plugin_settings->get( 'show_available_months', '1' ) ),
			'enable_available_dates'  => wptravelengine_toggled( $this->plugin_settings->get( 'show_available_dates', '1' ) ?? false ),
		);

		$settings['trip_duration_label_on_card'] = (string) $this->plugin_settings->get( 'set_duration_type', 'days' );

		return $settings;
	}

	/**
	 * Prepare Single Trip of Display Tab.
	 *
	 * @param WP_REST_Request $request Request object.
	 *
	 * @return array
	 * @since 6.2.0
	 */
	protected function prepare_single_trip_details( WP_REST_Request $request ): array {

		$settings = array();

		$settings['show_modal_warning'] = wptravelengine_toggled( $this->plugin_settings->get( 'show_booking_modal_warning', 'yes' ) );

		$settings['modal_warning_message'] = (string) $this->plugin_settings->get( 'booking_modal_warning_message', '' );

		$settings['trip_banner_layout'] = $this->plugin_settings->get( 'trip_banner_layout', 'banner-default' );

		$settings['display_banner_fullwidth'] = wptravelengine_toggled( $this->plugin_settings->get( 'display_banner_fullwidth', 'no' ) );

		$settings['enable_booking_form'] = ! wptravelengine_toggled( $this->plugin_settings->get( 'booking' ) );

		$settings['pricing_section_layout'] = $this->plugin_settings->get( 'pricing_section_layout', 'layout-1' );

		$settings['enable_compact_layout'] = wptravelengine_toggled( $this->plugin_settings->get( 'enable_compact_layout' ) );

		$settings['inquiry_form']         = array(
			'enable'    => wptravelengine_toggled( $this->plugin_settings->get( 'show_enquiry_info', 'yes' ) ),
			'link_type' => (string) $this->plugin_settings->get( 'enquiry_form_link', 'default' ),
		);
		$settings['inquiry_form']['link'] = $settings['inquiry_form']['link_type'] === 'custom' ? $this->plugin_settings->get( 'custom_enquiry_link', '' ) : '';

		$settings['whatsapp'] = array(
			'enable' => wptravelengine_toggled( $this->plugin_settings->get( 'show_whatsapp_icon' ) ),
			'number' => (string) $this->plugin_settings->get( 'whatsapp_number', '' ),
		);

		$settings['enable_tabs_sticky'] = wptravelengine_toggled( $this->plugin_settings->get( 'wte_sticky_tabs' ) );

		$settings['enable_booking_widget_sticky'] = wptravelengine_toggled( $this->plugin_settings->get( 'wte_sticky_booking_widget' ) );

		$settings['related_trips'] = array(
			'enable'  => wptravelengine_toggled( $this->plugin_settings->get( 'show_related_trips', 'yes' ) ),
			'title'   => (string) $this->plugin_settings->get( 'related_trips_section_title', 'Related trips you might interested in' ),
			'number'  => (int) $this->plugin_settings->get( 'no_of_related_trips', 3 ),
			'show_by' => (string) $this->plugin_settings->get( 'related_trip_show_by', 'activities' ),
		);

		$settings['pricing_widget_enquiry_message'] = (string) $this->plugin_settings->get( 'pricing_widget_enquiry_message', '' );

		$settings['related_trip_new_layout'] = array(
			'enable'                  => wptravelengine_toggled( $this->plugin_settings->get( 'related_display_new_trip_listing' ) ),
			'enable_slider'           => wptravelengine_toggled( $this->plugin_settings->get( 'show_related_trip_carousel', '1' ) ),
			'enable_featured_tag'     => wptravelengine_toggled( $this->plugin_settings->get( 'show_related_featured_tag', '1' ) ),
			'enable_wishlist'         => wptravelengine_toggled( $this->plugin_settings->get( 'show_related_wishlist', '1' ) ),
			'enable_map'              => wptravelengine_toggled( $this->plugin_settings->get( 'show_related_map', '1' ) ),
			'enable_excerpt'          => wptravelengine_toggled( $this->plugin_settings->get( 'show_related_excerpt', '1' ) ),
			'enable_difficulty'       => wptravelengine_toggled( $this->plugin_settings->get( 'show_related_difficulty_tax', '1' ) ),
			'enable_tags'             => wptravelengine_toggled( $this->plugin_settings->get( 'show_related_trip_tags', '1' ) ),
			'enable_fsd'              => wptravelengine_toggled( $this->plugin_settings->get( 'show_related_date_layout', '1' ) ),
			'enable_available_months' => wptravelengine_toggled( $this->plugin_settings->get( 'show_related_available_months', '1' ) ),
			'enable_available_dates'  => wptravelengine_toggled( $this->plugin_settings->get( 'show_related_available_dates', '1' ) ),
		);

		$settings['enable_trip_info'] = wptravelengine_toggled( $this->plugin_settings->get( 'show_trip_facts', 'no' ) );

		$settings['enable_trip_info_on_sidebar'] = wptravelengine_toggled( $this->plugin_settings->get( 'show_trip_facts_sidebar' ) );

		$settings['enable_trip_info_on_main_content'] = wptravelengine_toggled( $this->plugin_settings->get( 'show_trip_facts_content_area' ) );

		$settings['enable_image_autoplay'] = wptravelengine_toggled( $this->plugin_settings->get( 'gallery_autoplay' ) );

		$settings['trip_duration_format'] = (string) $this->plugin_settings->get( 'trip_duration_format', 'days' );

		$settings['show_discounts_type'] = (string) $this->plugin_settings->get( 'show_discounts_type', 'percentage' );

		$settings['enable_featured_image'] = ! wptravelengine_toggled( $this->plugin_settings->get( 'feat_img' ) );

		$settings['enable_image_in_gallery'] = wptravelengine_toggled( $this->plugin_settings->get( 'show_featured_image_in_gallery', 'yes' ) );

		$settings['enable_fse'] = wptravelengine_toggled( $this->plugin_settings->get( 'enable_fse_template' ) );

		$settings['enquiry_enable'] = ! wptravelengine_toggled( $this->plugin_settings->get( 'enquiry' ) );

		$settings['enquiry_custom_form'] = array(
			'shortcode' => (string) $this->plugin_settings->get( 'enquiry_shortcode' ),
			'enable'    => wptravelengine_toggled( $this->plugin_settings->get( 'custom_enquiry' ) ),
		);

		return $settings;
	}

	/**
	 * Prepare Archive Details of Display Tab.
	 *
	 * @param WP_REST_Request $request Request object.
	 *
	 * @return array
	 * @since 6.2.0
	 */
	protected function prepare_archive_details( WP_REST_Request $request ): array {

		$settings = array();

		$settings['enable_archive_title'] = ! wptravelengine_toggled( $this->plugin_settings->get( 'hide_term_title' ) );

		$settings['sort_trips_by'] = (string) Options::get( 'wptravelengine_trip_sort_by', 'latest' );

		$settings['trip_view_mode'] = (string) Options::get( 'wptravelengine_trip_view_mode', 'list' );

		$settings['featured_trips'] = array(
			'enable' => wptravelengine_toggled( $this->plugin_settings->get( 'show_featured_trips_on_top', 'yes' ) ),
			'number' => (int) $this->plugin_settings->get( 'feat_trip_num', 2 ),
		);

		$settings['archives'] = array(
			'title'        => (string) $this->plugin_settings->get( 'archive.title', '' ),
			'enable_title' => ! wptravelengine_toggled( $this->plugin_settings->get( 'archive.hide_archive_title', 'no' ) ),
			'title_type'   => (string) $this->plugin_settings->get( 'archive.title_type', 'default' ),
			// 'enable_advance_search' => wptravelengine_toggled( $this->plugin_settings->get( 'archive.collapsible_filter_panel', 'no' ) ),
		);

		$settings['show_sidebar'] = (bool) wptravelengine_toggled( Options::get( 'wptravelengine_show_trip_search_sidebar', 'yes' ) );

		$settings['display_mode'] = (string) Options::get( 'wptravelengine_archive_display_mode', 'pagination' );

		// $settings[ 'enable_criteria_filter' ] = wptravelengine_toggled( $this->plugin_settings->get( 'search_filter_option', 'yes' ) );

		return $settings;
	}

	/**
	 * Prepare Appearance Details of Display Tab.
	 *
	 * @param WP_REST_Request $request Request object.
	 *
	 * @return array
	 * @since 6.6.1
	 */
	protected function prepare_appearance_details( WP_REST_Request $request ): array {
		$settings = array();

		$appearance = Options::get( 'wptravelengine_appearance', array() );

		$settings['appearance'] = array(
			'primary_color'     => (string) ( $appearance['primary_color'] ?? '' ),
			'primary_color_rgb' => (string) ( $appearance['primary_color_rgb'] ?? '' ),
			'discount_color'    => (string) ( $appearance['discount_color'] ?? '' ),
			'featured_color'    => (string) ( $appearance['featured_color'] ?? '' ),
			'icon_color'        => (string) ( $appearance['icon_color'] ?? '' ),
		);

		return $settings;
	}

	/**
	 * Prepare Checkout Details of Display Tab.
	 *
	 * @param WP_REST_Request $request Request object.
	 *
	 * @return array
	 * @since 6.2.0
	 */
	protected function prepare_checkout_details( WP_REST_Request $request ): array {
		$settings                             = array();
		$settings['enable_travellers_info']   = ! wptravelengine_toggled( $this->plugin_settings->get( 'travelers_information', 'yes' ) );
		$settings['enable_emergency_contact'] = ! wptravelengine_toggled( $this->plugin_settings->get( 'emergency', null ) );
		$settings['booking_confirmation_msg'] = (string) $this->plugin_settings->get( 'confirmation_msg', 'Thank you for booking the trip. Please check your email for confirmation. Below is your booking detail:' );
		$settings['gdpr_msg']                 = (string) $this->plugin_settings->get( 'gdpr_msg', 'By contacting us, you agree to our' );
		// New setting for checkout page template.

		// Determine template version based on user status
		$is_existing_user = version_compare(
			get_option( 'wptravelengine_since', false ),
			WP_TRAVEL_ENGINE_VERSION,
			'<'
		);

		// Set checkout settings
		$settings['checkout_page_template'] = (string) $this->plugin_settings->get(
			'checkout_page_template',
			$is_existing_user ? '1.0' : '2.0'
		);

		$settings['display_header_footer']            = wptravelengine_toggled( $this->plugin_settings->get( 'display_header_footer', 'no' ) );
		$settings['display_emergency_contact']        = wptravelengine_toggled( $this->plugin_settings->get( 'display_emergency_contact', 'no' ) );
		$settings['display_travellers_info']          = wptravelengine_toggled( $this->plugin_settings->get( 'display_travellers_info', 'no' ) );
		$settings['traveller_emergency_details_form'] = (string) $this->plugin_settings->get( 'traveller_emergency_details_form', 'on_checkout' );
		$settings['travellers_details_type']          = (string) $this->plugin_settings->get( 'travellers_details_type', 'all' );
		$settings['display_billing_details']          = wptravelengine_toggled( $this->plugin_settings->get( 'display_billing_details', 'yes' ) );
		$settings['show_additional_note']             = wptravelengine_toggled( $this->plugin_settings->get( 'show_additional_note', 'no' ) );
		$settings['show_discount']                    = wptravelengine_toggled( $this->plugin_settings->get( 'show_discount', 'yes' ) );
		$settings['privacy_policy_msg']               = (string) $this->plugin_settings->get( 'privacy_policy_msg', __( 'Check the box to confirm you\'ve read and agree to our', 'wp-travel-engine' ) );
		$settings['footer_copyright']                 = (string) $this->plugin_settings->get( 'footer_copyright', sprintf( 'Copyright © %s %d. All Rights Reserved.', get_bloginfo( 'name' ), date( 'Y' ) ) );

		return $settings;
	}

	/**
	 * Prepare Taxonomy Details of Display Tab.
	 *
	 * @param WP_REST_Request $request Request object.
	 *
	 * @return array
	 * @since 6.2.0
	 */
	protected function prepare_taxonomy_details( WP_REST_Request $request ): array {

		return array(
			'taxonomy' => array(
				'enable_image'          => wptravelengine_toggled( $this->plugin_settings->get( 'tax_images' ) ),
				'enable_children_terms' => wptravelengine_toggled( $this->plugin_settings->get( 'show_taxonomy_children', 'no' ) ),
			),
		);
	}

	/**
	 * Prepare
	 */


	/**
	 * Prepare Display Tabs Settings.
	 *
	 * @param WP_REST_Request $request Request object.
	 *
	 * @return array
	 * @since 6.2.0
	 */
	protected function prepare_display_tabs( WP_REST_Request $request ): array {

		return array_reduce(
			array(
				$this->prepare_trip_card_details( $request ),
				$this->prepare_single_trip_details( $request ),
				$this->prepare_archive_details( $request ),
				$this->prepare_appearance_details( $request ),
				$this->prepare_checkout_details( $request ),
				$this->prepare_taxonomy_details( $request ),
				array(
					'custom_strings' => array_map(
						fn ( $string ) => array(
							'initial_label'  => (string) $string['initial_label'],
							'modified_label' => (string) $string['modified_label'],
						),
						array_values( Options::get( 'wptravelengine_custom_strings', array() ) )
					),
				),
			),
			fn ( $carry, $item ) => array_merge( $carry, $item ),
			array()
		);
	}

	/**
	 * Prepare Currency Tab Settings.
	 *
	 * @param WP_REST_Request $request Request object.
	 *
	 * @return array
	 * @since 6.2.0
	 */
	protected function prepare_currency_settings( WP_REST_Request $request ): array {

		$settings                        = array();
		$settings['currency_code']       = $this->plugin_settings->get( 'currency_code', 'USD' );
		$settings['currency_symbol']     = $this->plugin_settings->get( 'currency_option', 'symbol' );
		$settings['amount_format']       = $this->plugin_settings->get( 'amount_display_format', '%CURRENCY_SYMBOL%%FORMATED_AMOUNT%' );
		$settings['decimal_digits']      = $this->plugin_settings->get( 'decimal_digits', 0 );
		$settings['decimal_separator']   = $this->plugin_settings->get( 'decimal_separator', '.' );
		$settings['thousands_separator'] = $this->plugin_settings->get( 'thousands_separator', '' );

		return $settings;
	}

	/**
	 * Prepare Payment Tab Settings.
	 *
	 * @param WP_REST_Request $request Request object.
	 *
	 * @return array
	 * @since 6.2.0
	 */
	public function prepare_payment_settings( WP_REST_Request $request ): array {

		$plugin_settings = $this->plugin_settings;

		$settings = array();

		$settings['debug_mode'] = wptravelengine_toggled( $plugin_settings->get( 'payment_debug', 'no' ) );

		$settings['default_payment_gateway'] = (string) $plugin_settings->get( 'default_gateway', 'booking_only' );

		$payment_gateways = PaymentGateways::instance()->get_payment_gateways( true );

		$settings['payment_gateways'][] = array(
			'id'     => 'booking_only',
			'name'   => __( 'Book Now Pay Later', 'wp-travel-engine' ),
			'enable' => wptravelengine_toggled( $plugin_settings->get( 'booking_only' ) ),
			'icon'   => '',
		);
		$settings['booking_only']       = new \stdClass();

		$settings['payment_gateways'][] = array(
			'id'     => 'paypal_payment',
			'name'   => __( 'PayPal Standard', 'wp-travel-engine' ),
			'enable' => wptravelengine_toggled( $plugin_settings->get( 'paypal_payment' ) ),
			'icon'   => '',
		);
		$settings['paypal']             = array(
			'paypal_id' => (string) $plugin_settings->get( 'paypal_id' ),
		);

		$settings['payment_gateways'][]   = array(
			'id'     => 'direct_bank_transfer',
			'name'   => __( 'Direct Bank Transfer', 'wp-travel-engine' ),
			'enable' => wptravelengine_toggled( $plugin_settings->get( 'direct_bank_transfer' ) ),
			'icon'   => '',
		);
		$settings['direct_bank_transfer'] = array(
			'title'           => (string) $plugin_settings->get( 'bank_transfer.title', 'Bank Transfer' ),
			'description'     => (string) $plugin_settings->get( 'bank_transfer.description', 'Make your payment directly into our bank account. Please use your Order ID as the payment reference. Your order will not be shipped until the funds have cleared in our account.' ),
			'instructions'    => (string) $plugin_settings->get( 'bank_transfer.instruction', 'Please make your payment on the provided bank accounts.' ),
			'account_details' => array_values( (array) $plugin_settings->get( 'bank_transfer.accounts', array() ) ),
		);

		$settings['payment_gateways'][] = array(
			'id'     => 'check_payments',
			'name'   => __( 'Check Payments', 'wp-travel-engine' ),
			'enable' => wptravelengine_toggled( $plugin_settings->get( 'check_payments' ) ),
			'icon'   => '',
		);
		$settings['check_payments']     = array(
			'title'        => (string) $plugin_settings->get( 'check_payment.title', 'Check payments' ),
			'description'  => (string) $plugin_settings->get( 'check_payment.description', 'Please send a check to Store Name, Store Street, Store Town, Store State / County, Store Postcode.' ),
			'instructions' => (string) $plugin_settings->get( 'check_payment.instruction', 'Please send a check to Store Name, Store Street, Store Town, Store State / County, Store Postcode.' ),
		);

		if ( isset( $payment_gateways['hbl_enable'] ) ) {
			$settings['payment_gateways'][] = array(
				'id'     => 'hbl_enable',
				'name'   => __( 'HBL Payments', 'wp-travel-engine' ),
				'enable' => wptravelengine_toggled( $plugin_settings->get( 'hbl_enable' ) ),
				'icon'   => WP_TRAVEL_ENGINE_FILE_URL . 'assets/images/paymentgateways/hbl.png',
			);
			$notification_url_base          = home_url();
			$settings['hbl']                = array(
				'office_id'             => (string) $plugin_settings->get( 'hbl_settings.office_id', 'DEMOOFFICE' ),
				'api_key'               => (string) $plugin_settings->get( 'hbl_settings.api_key' ),
				'encryption_key_id'     => (string) $plugin_settings->get( 'hbl_settings.key_id' ),
				'merchant_private_keys' => array(
					'signing_key'    => (string) $plugin_settings->get( 'hbl_settings.merchant_signing_private_key' ),
					'decryption_key' => (string) $plugin_settings->get( 'hbl_settings.merchant_decryption_private_key' ),
				),
				'paco_public_keys'      => array(
					'signing_key'    => (string) $plugin_settings->get( 'hbl_settings.paco_signing_public_key' ),
					'encryption_key' => (string) $plugin_settings->get( 'hbl_settings.paco_encryption_public_key' ),
				),
				'notification_urls'     => array(
					'confirmation_url' => (string) $plugin_settings->get( 'hbl_settings.confirmation_url', $notification_url_base . '?_gateway=hbl_enable&_action=wtep_success' ),
					'cancellation_url' => (string) $plugin_settings->get( 'hbl_settings.cancel_url', $notification_url_base . '?_gateway=hbl_enable&_action=wtep_cancel' ),
					'failure_url'      => (string) $plugin_settings->get( 'hbl_settings.failed_url', $notification_url_base . '?_gateway=hbl_enable&_action=wtep_fail' ),
					'notify_url'       => (string) $plugin_settings->get( 'hbl_settings.backend_url', $notification_url_base . '?_gateway=hbl_enable&_action=wtep_ipn' ),
				),
			);
		}

		if ( isset( $payment_gateways['payfast_enable'] ) ) {
			$settings['payment_gateways'][] = array(
				'id'     => 'payfast_enable',
				'name'   => 'Payfast',
				'enable' => wptravelengine_toggled( $plugin_settings->get( 'payfast_enable' ) ),
				'icon'   => WP_TRAVEL_ENGINE_FILE_URL . 'assets/images/paymentgateways/payfast.png',
			);
			$settings['payfast']            = array(
				'merchant_id'  => (string) $plugin_settings->get( 'payfast_id' ),
				'merchant_key' => (string) $plugin_settings->get( 'payfast_merchant_key' ),
			);
		}

		if ( isset( $payment_gateways['stripe_payment'] ) ) {
			$settings['payment_gateways'][] = array(
				'id'     => 'stripe_payment',
				'name'   => 'Stripe',
				'enable' => wptravelengine_toggled( $plugin_settings->get( 'stripe_payment' ) ),
				'icon'   => WP_TRAVEL_ENGINE_FILE_URL . 'assets/images/paymentgateways/stripe.png',
			);
			$settings['stripe']             = array(
				'secret_key'         => (string) $plugin_settings->get( 'stripe_secret' ),
				'publishable_key'    => (string) $plugin_settings->get( 'stripe_publishable' ),
				'pay_btn_label'      => (string) $plugin_settings->get( 'stripe_btn_label' ),
				'enable_postal_code' => ! wptravelengine_toggled( $plugin_settings->get( 'stripe_hide_postal_code', 'yes' ) ),
			);
		}

		if ( isset( $payment_gateways['paypalexpress_enable'] ) ) {
			$settings['payment_gateways'][] = array(
				'id'     => 'paypalexpress_enable',
				'name'   => __( 'PayPal Express', 'wp-travel-engine' ),
				'enable' => wptravelengine_toggled( $plugin_settings->get( 'paypalexpress_enable' ) ),
				'icon'   => WP_TRAVEL_ENGINE_FILE_URL . 'assets/images/paymentgateways/paypalexpress.png',
			);
			$settings['paypal_express']     = array(
				'client_id'       => (string) $plugin_settings->get( 'paypalexpress_client_id' ),
				'client_secret'   => (string) $plugin_settings->get( 'paypalexpress_secret' ),
				'disable_funding' => (array) $plugin_settings->get( 'paypalexpress_payment_method', array( 'card' ) ),
			);
		}

		if ( isset( $payment_gateways['authorize-net-payment'] ) ) {
			$settings['payment_gateways'][] = array(
				'id'     => 'authorize-net-payment',
				'name'   => __( 'Authorize.Net', 'wp-travel-engine' ),
				'enable' => wptravelengine_toggled( $plugin_settings->get( 'authorize-net-payment' ) ),
				'icon'   => WP_TRAVEL_ENGINE_FILE_URL . 'assets/images/paymentgateways/authorize.png',
			);
			$settings['authorize_net']      = array(
				'api_login_id'    => (string) $plugin_settings->get( 'authorizenet.api_login_id' ),
				'transaction_key' => (string) $plugin_settings->get( 'authorizenet.transaction_key' ),
			);
		}

		if ( isset( $payment_gateways['midtrans_enable'] ) ) {
			$settings['payment_gateways'][] = array(
				'id'     => 'midtrans_enable',
				'name'   => 'Midtrans',
				'enable' => wptravelengine_toggled( $plugin_settings->get( 'midtrans_enable' ) ),
				'icon'   => WP_TRAVEL_ENGINE_FILE_URL . 'assets/images/paymentgateways/midtrans.png',
			);
			$settings['midtrans']           = array(
				'enable_3Ds_secure' => wptravelengine_toggled( $plugin_settings->get( 'midtrans.3ds_enabled', '0' ) ),
				'enable_save_card'  => wptravelengine_toggled( $plugin_settings->get( 'midtrans.save_card_enabled', '0' ) ),
				'merchant_id'       => (string) $plugin_settings->get( 'midtrans.merchant_id' ),
				'client_key'        => (string) $plugin_settings->get( 'midtrans.client_key' ),
				'server_key'        => (string) $plugin_settings->get( 'midtrans.server_key' ),
			);
		}

		if ( isset( $payment_gateways['payhere_payment'] ) ) {
			$settings['payment_gateways'][] = array(
				'id'     => 'payhere_payment',
				'name'   => 'Pay Here',
				'enable' => wptravelengine_toggled( $plugin_settings->get( 'payhere_payment' ) ),
				'icon'   => WP_TRAVEL_ENGINE_FILE_URL . 'assets/images/paymentgateways/payhere.png',
			);
			$settings['payhere']            = array(
				'merchant_id'            => (string) $plugin_settings->get( 'payhere_merchant_id' ),
				'merchant_secret'        => (string) $plugin_settings->get( 'payhere_merchant_secret' ),
				'enable_onsite_checkout' => wptravelengine_toggled( $plugin_settings->get( 'payhere_enable_onsite', 'no' ) ),
			);
		}

		if ( isset( $payment_gateways['payu_money_enable'] ) ) {
			$settings['payment_gateways'][] = array(
				'id'     => 'payu_money_enable',
				'name'   => 'PayU Money',
				'enable' => wptravelengine_toggled( $plugin_settings->get( 'payu_money_enable' ) ),
				'icon'   => WP_TRAVEL_ENGINE_FILE_URL . 'assets/images/paymentgateways/payumoney.png',
			);
			$settings['payu_money']         = array(
				'merchant_key'  => (string) $plugin_settings->get( 'payu_money_merchant_id' ),
				'merchant_salt' => (string) $plugin_settings->get( 'payu_money_salt' ),
			);
		}

		if ( isset( $payment_gateways['payu_enable'] ) ) {
			$settings['payment_gateways'][] = array(
				'id'     => 'payu_enable',
				'name'   => 'PayU Biz',
				'enable' => wptravelengine_toggled( $plugin_settings->get( 'payu_enable' ) ),
				'icon'   => WP_TRAVEL_ENGINE_FILE_URL . 'assets/images/paymentgateways/payubiz.png',
			);
			$settings['payu_biz']           = array(
				'merchant_key'  => (string) $plugin_settings->get( 'payu_merchant_id' ),
				'merchant_salt' => (string) $plugin_settings->get( 'payu_salt' ),
			);
		}

		$settings['payment_gateways'] = apply_filters( 'wptravelengine_rest_payment_gateways', $settings['payment_gateways'], $plugin_settings );

		$active_extensions = apply_filters( 'wpte_settings_get_global_tabs', array() );
		$file_path         = $active_extensions['wpte-payment']['sub_tabs']['woocommerce']['content_path'] ?? '';
		if ( file_exists( $file_path ) ) {
			$settings['enable_woocommerce_gateway'] = wptravelengine_toggled( $plugin_settings->get( 'use_woocommerce_payment_gateway', 'no' ) );
		}

		$payments_order = Options::get( 'wptravelengine_payment_gateways', array() );

		if ( ! empty( $payments_order ) ) {
			$payments_order = array_column( $payments_order, 'id' );
			usort(
				$settings['payment_gateways'],
				function ( $a, $b ) use ( $payments_order ) {
					$posA = array_search( $a['id'], $payments_order );
					$posB = array_search( $b['id'], $payments_order );

					return $posA <=> $posB;
				}
			);
		}

		$settings['tax'] = array(
			'enable'       => wptravelengine_toggled( $plugin_settings->get( 'tax_enable', 'no' ) ),
			'custom_label' => (string) $plugin_settings->get( 'tax_label', 'Tax (%s%%)' ),
			'type'         => (string) $plugin_settings->get( 'tax_type_option', 'exclusive' ),
			'percentage'   => (float) $plugin_settings->get( 'tax_percentage', '13' ),
		);

		return $settings;
	}

	/**
	 * Prepare Dashboard Settings.
	 *
	 * @param WP_REST_Request $request Request object.
	 *
	 * @return array
	 * @since 6.2.0
	 */
	protected function prepare_dashboard_settings( WP_REST_Request $request ): array {

		$plugin_settings = $this->plugin_settings;

		$settings = array();

		$settings['generate_user_account'] = wptravelengine_toggled( $plugin_settings->get( 'generate_user_account', 'yes' ) );

		$settings['enable_booking_registration'] = wptravelengine_toggled( $plugin_settings->get( 'enable_checkout_customer_registration', 'no' ) );

		$settings['enable_account_registration'] = ! wptravelengine_toggled( $plugin_settings->get( 'disable_my_account_customer_registration', 'yes' ) );

		$settings['login_page_label']        = $plugin_settings->get( 'login_page_label', 'Log into Your Account' );
		$settings['forgot_page_label']       = $plugin_settings->get( 'forgot_page_label', 'Reset Your Password' );
		$settings['forgot_page_description'] = $plugin_settings->get( 'forgot_page_description', 'If an account with that email exist, we\'ll send you a link to reset your password. Please check your inbox including spam/junk folder.' );
		$settings['set_password_page_label'] = $plugin_settings->get( 'set_password_page_label', 'Set New Password' );

		$settings['social_login'] = array(
			'enable' => wptravelengine_toggled( $plugin_settings->get( 'enable_social_login', 'no' ) ),
		);

		$settings['social_login']['providers']['facebook'] = array(
			'enable'     => wptravelengine_toggled( $plugin_settings->get( 'enable_facebook_login', 'no' ) ),
			'app_id'     => (string) $plugin_settings->get( 'facebook_client_id' ),
			'app_secret' => (string) $plugin_settings->get( 'facebook_client_secret' ),
		);

		$settings['social_login']['providers']['google'] = array(
			'enable'     => wptravelengine_toggled( $plugin_settings->get( 'enable_google_login', 'no' ) ),
			'app_id'     => (string) $plugin_settings->get( 'google_client_id' ),
			'app_secret' => (string) $plugin_settings->get( 'google_client_secret' ),
		);

		$settings['social_login']['providers']['linkedIn'] = array(
			'enable'     => wptravelengine_toggled( $plugin_settings->get( 'enable_linkedin_login', 'no' ) ),
			'app_id'     => (string) $plugin_settings->get( 'linkedin_client_id' ),
			'app_secret' => (string) $plugin_settings->get( 'linkedin_client_secret' ),
		);

		return $settings;
	}

	/**
	 * Prepare Performance Settings.
	 *
	 * @param WP_REST_Request $request Request object.
	 *
	 * @return array
	 * @since 6.2.0
	 */
	protected function prepare_performace_settings( WP_REST_Request $request ): array {

		$settings = array();

		$settings['enable_optimized_loading'] = wptravelengine_toggled( $this->plugin_settings->get( 'enable_optimize_loading', 'no' ) );

		$settings['lazy_loading'] = array(
			'enable'       => wptravelengine_toggled( $this->plugin_settings->get( 'enable_lazy_loading', 'no' ) ),
			'enable_map'   => wptravelengine_toggled( $this->plugin_settings->get( 'enable_map_lazy_loading', 'no' ) ),
			'enable_image' => wptravelengine_toggled( $this->plugin_settings->get( 'enable_img_lazy_loading', 'no' ) ),
		);

		return $settings;
	}

	/**
	 * Prepare Trip Search Settings.
	 *
	 * @param WP_REST_Request $request Request object.
	 *
	 * @return array
	 */
	protected function prepare_trip_search_settings( WP_REST_Request $request ): array {

		$settings = array();

		$settings['trip_search'] = array(
			'enable_destination'       => ! wptravelengine_toggled( $this->plugin_settings->get( 'trip_search.destination', '0' ) ),
			'enable_activities'        => ! wptravelengine_toggled( $this->plugin_settings->get( 'trip_search.activities', '0' ) ),
			'enable_trip_types'        => ! wptravelengine_toggled( $this->plugin_settings->get( 'trip_search.trip_types', '0' ) ),
			'enable_trip_tags'         => ! wptravelengine_toggled( $this->plugin_settings->get( 'trip_search.trip_tag', '0' ) ),
			'enable_difficulties'      => ! wptravelengine_toggled( $this->plugin_settings->get( 'trip_search.difficulty', '0' ) ),
			'enable_duration'          => ! wptravelengine_toggled( $this->plugin_settings->get( 'trip_search.duration', '0' ) ),
			'enable_budget'            => ! wptravelengine_toggled( $this->plugin_settings->get( 'trip_search.budget', '0' ) ),
			'enable_fsd'               => ! wptravelengine_toggled( $this->plugin_settings->get( 'trip_search.dates', '0' ) ),
			'enable_filter_by_section' => ! wptravelengine_toggled( $this->plugin_settings->get( 'trip_search.apply_in_search_page', '0' ) ),
		);

		return $settings;
	}

	/**
	 * Prepare reCAPTCHA Settings.
	 *
	 * @param WP_REST_Request $request Request object.
	 *
	 * @since 6.7.0
	 * @return array
	 */
	protected function prepare_recaptcha_settings( WP_REST_Request $request ): array {
		$settings = array();

		$settings['recaptcha'] = array(
			'version' => (string) $this->plugin_settings->get( 'recaptcha.version' ) ?: 'v2',
			'v2'      => array(
				'site_key'   => (string) $this->plugin_settings->get( 'recaptcha.v2.site_key' ),
				'secret_key' => (string) $this->plugin_settings->get( 'recaptcha.v2.secret_key' ),
			),
			'v3'      => array(
				'site_key'   => (string) $this->plugin_settings->get( 'recaptcha.v3.site_key' ),
				'secret_key' => (string) $this->plugin_settings->get( 'recaptcha.v3.secret_key' ),
			),
		);

		return $settings;
	}

	/**
	 * Get settings.
	 *
	 * @param WP_REST_Request $request Request object.
	 *
	 * @return WP_REST_Response|WP_Error
	 */
	public function get_settings( $request ) {
		$settings = array_reduce(
			array(
				$this->prepare_page_settings( $request ),
				$this->prepare_trip_tabs( $request ),
				$this->prepare_trip_infos( $request ),
				$this->prepare_trip_settings( $request ),
				// $this->prepare_admin_emails_configuration( $request ),
				// $this->prepare_customer_emails_configuration( $request ),
				$this->prepare_emails_configuration( $request ),
				$this->prepare_emails_settings( $request ),
				$this->prepare_enquiry_form_configuration( $request ),
				$this->prepare_display_tabs( $request ),
				$this->prepare_currency_settings( $request ),
				$this->prepare_payment_settings( $request ),
				$this->prepare_dashboard_settings( $request ),
				$this->prepare_performace_settings( $request ),
				$this->prepare_trip_search_settings( $request ),
				$this->prepare_recaptcha_settings( $request ),
			),
			fn ( $carry, $item ) => array_merge( $carry, $item ),
			array()
		);

		$settings = apply_filters( 'wptravelengine_rest_prepare_settings', $settings, $request, $this );

		if ( ! Translators::is_wpml_multilingual_active() ) {
			$this->plugin_settings->save();
		} else {
			Translators::register_wpml_admin_strings();
		}

		return $settings;
	}

	/**
	 * Process Trip Tabs.
	 *
	 * @param WP_REST_Request $request Request object.
	 *
	 * @return void
	 */
	protected function set_trip_tabs( WP_REST_Request $request ) {
		if ( isset( $request['trip_tabs'] ) ) {
			$trip_tabs = array(
				'id'     => array_column( $request['trip_tabs'], 'id', 'id' ),
				'name'   => array_column( $request['trip_tabs'], 'name', 'id' ),
				'field'  => array_column( $request['trip_tabs'], 'field', 'id' ),
				'icon'   => array_column( $request['trip_tabs'], 'icon', 'id' ),
				'enable' => array_column( $request['trip_tabs'], 'enable', 'id' ),
			);

			$trip_tabs['enable'] = wptravelengine_replace( $trip_tabs['enable'], true, 'yes', 'no' );

			$this->plugin_settings->set( 'trip_tabs', $trip_tabs );
		}
	}

	/**
	 * Process Trip Settings.
	 *
	 * @param WP_REST_Request $request Request object.
	 *
	 * @return void
	 */
	protected function set_trip_settings( WP_REST_Request $request ) {
		if ( isset( $request['pricing_type'] ) ) {
			$pricing_types = array();
			foreach ( $request['pricing_type'] as $pricing_type ) {
				if ( empty( $pricing_type['label'] ) ) {
					$this->set_bad_request( 'invalid_pricing_type', 'Pricing Type is required' );
				}
				$id                   = 'per-' . str_replace( ' ', '-', strtolower( $pricing_type['label'] ) );
				$pricing_types[ $id ] = array(
					'label'       => (string) $pricing_type['label'],
					'description' => (string) $pricing_type['description'],
				);
			}

			Options::update( 'wptravelengine_pricing_type', $pricing_types );
		}

		if ( isset( $request['highlights'] ) ) {
			$highlights = array_map(
				function ( $highlight ) {
					return array(
						'highlight' => $highlight['title'],
						'help'      => $highlight['description'],
					);
				},
				$request['highlights']
			);

			$this->plugin_settings->set( 'trip_highlights', $highlights );
		}
	}

	/**
	 * Process Trip Facts.
	 *
	 * @param WP_REST_Request $request Request object.
	 *
	 * @return void
	 * @since 6.2.0
	 */
	protected function set_pages( WP_REST_Request $request ) {

		$plugin_settings = $this->plugin_settings;

		if ( isset( $request['checkout_page'] ) ) {
			$plugin_settings->set( 'pages.wp_travel_engine_place_order', $request['checkout_page'] );
		}

		if ( isset( $request['terms_and_conditions'] ) ) {
			$plugin_settings->set( 'pages.wp_travel_engine_terms_and_conditions', $request['terms_and_conditions'] );
		}

		if ( isset( $request['thank_you_page'] ) ) {
			$plugin_settings->set( 'pages.wp_travel_engine_thank_you', $request['thank_you_page'] );
		}

		if ( isset( $request['confirmation_page'] ) ) {
			$plugin_settings->set( 'pages.wp_travel_engine_confirmation_page', $request['confirmation_page'] );
		}

		if ( isset( $request['dashboard_page'] ) ) {
			$plugin_settings->set( 'pages.wp_travel_engine_dashboard_page', $request['dashboard_page'] );
		}

		if ( isset( $request['enquiry_thank_you_page'] ) ) {
			$plugin_settings->set( 'pages.enquiry', $request['enquiry_thank_you_page'] );
		}

		if ( isset( $request['wishlist_page'] ) ) {
			$plugin_settings->set( 'pages.wp_travel_engine_wishlist', $request['wishlist_page'] );
		}

		if ( isset( $request['search_page'] ) ) {
			Options::update( 'wp_travel_engine_search_page_id', $request['search_page'] );
			$plugin_settings->set( 'pages.search', $request['search_page'] );
		}
	}

	/**
	 * Process Trip Facts.
	 *
	 * @param WP_REST_Request $request Request object.
	 *
	 * @return void
	 * @since 6.2.0
	 */
	protected function set_trip_infos( WP_REST_Request $request ) {

		$plugin_settings = $this->plugin_settings;

		if ( isset( $request['trip_info'] ) ) {

			foreach ( $request['trip_info'] as $trip_info ) {

				if ( ! isset( $trip_info['id'] ) ) {
					continue;
				}

				if ( $trip_info['trashable'] ) {
					$trashable_trip_info['fid'][ $trip_info['id'] ]               = $trip_info['id'] ?? '';
					$trashable_trip_info['field_id'][ $trip_info['id'] ]          = $trip_info['name'] ?? '';
					$trashable_trip_info['input_placeholder'][ $trip_info['id'] ] = $trip_info['placeholder'] ?? '';
					$trashable_trip_info['field_type'][ $trip_info['id'] ]        = $trip_info['type'] ?? '';
					$trashable_trip_info['field_icon'][ $trip_info['id'] ]        = $trip_info['icon'] ?? '';
					$trashable_trip_info['enabled'][ $trip_info['id'] ]           = wptravelengine_replace( $trip_info['enable'] ?? false, true, 'yes', 'no' );
					$trashable_trip_info['select_options'][ $trip_info['id'] ]    = $trip_info['options'] ? implode( ',', $trip_info['options'] ) : null;
				} else {
					$default_trip_info[ $trip_info['id'] ]['fid']               = $trip_info['id'] ?? '';
					$default_trip_info[ $trip_info['id'] ]['field_id']          = $trip_info['name'] ?? '';
					$default_trip_info[ $trip_info['id'] ]['input_placeholder'] = $trip_info['placeholder'] ?? '';
					$default_trip_info[ $trip_info['id'] ]['field_type']        = $trip_info['type'] ?? '';
					$default_trip_info[ $trip_info['id'] ]['field_icon']        = $trip_info['icon'] ?? '';
					$default_trip_info[ $trip_info['id'] ]['enabled']           = wptravelengine_replace( $trip_info['enable'] ?? false, true, 'yes', 'no' );
				}
			}

			$plugin_settings->set( 'trip_facts', $trashable_trip_info ?? array() );
			$plugin_settings->set( 'default_trip_facts', $default_trip_info ?? array() );
		}
	}

	/**
	 * Process Admin Tab Settings.s
	 *
	 * @param WP_REST_Request $request Request object.
	 *
	 * @return void
	 * @since 6.2.0
	 */
	protected function set_admin_tabs( WP_REST_Request $request ) {

		$plugin_settings = $this->plugin_settings;

		if ( isset( $request['admin_email']['email_addresses'] ) ) {
			$plugin_settings->set( 'email.emails', implode( ',', $request['admin_email']['email_addresses'] ) );
		}

		if ( isset( $request['admin_email']['enable'] ) ) {
			$plugin_settings->set( 'email.disable_notif', wptravelengine_replace( $request['admin_email']['enable'], false, '1' ) );
		}

		if ( isset( $request['admin_booking_notification']['subject'] ) ) {
			$plugin_settings->set( 'email.booking_notification_subject_admin', $request['admin_booking_notification']['subject'] );
		}

		if ( isset( $request['admin_booking_notification']['template'] ) ) {
			$plugin_settings->set( 'email.booking_notification_template_admin', $request['admin_booking_notification']['template'] );
		}

		if ( isset( $request['admin_booking_notification']['enable'] ) ) {
			$plugin_settings->set( 'email.disable_booking_notification', wptravelengine_replace( $request['admin_booking_notification']['enable'], false, '1' ) );
		}

		if ( isset( $request['admin_payment_notification']['subject'] ) ) {
			$plugin_settings->set( 'email.sale_subject', $request['admin_payment_notification']['subject'] );
		}

		if ( isset( $request['admin_payment_notification']['template'] ) ) {
			$plugin_settings->set( 'email.sales_wpeditor', $request['admin_payment_notification']['template'] );
		}

		if ( isset( $request['customer_receipt_details']['admin_name'] ) ) {
			$plugin_settings->set( 'email.name', $request['customer_receipt_details']['admin_name'] );
		}

		if ( isset( $request['customer_receipt_details']['admin_email_address'] ) ) {
			$plugin_settings->set( 'email.from', $request['customer_receipt_details']['admin_email_address'] );
		}

		if ( isset( $request['customer_booking_notification']['subject'] ) ) {
			$plugin_settings->set( 'email.booking_notification_subject_customer', $request['customer_booking_notification']['subject'] );
		}

		if ( isset( $request['customer_booking_notification']['template'] ) ) {
			$plugin_settings->set( 'email.booking_notification_template_customer', $request['customer_booking_notification']['template'] );
		}

		if ( isset( $request['customer_booking_notification']['enable'] ) ) {
			$plugin_settings->set( 'email.enable_cust_notif', wptravelengine_replace( $request['customer_booking_notification']['enable'], true, 'yes', 'no' ) );
		}

		if ( isset( $request['customer_purchase_notification']['subject'] ) ) {
			$plugin_settings->set( 'email.subject', $request['customer_purchase_notification']['subject'] );
		}

		if ( isset( $request['customer_purchase_notification']['template'] ) ) {
			$plugin_settings->set( 'email.purchase_wpeditor', $request['customer_purchase_notification']['template'] );
		}

		if ( ! isset( $request['enquiry_form'] ) ) {
			return;
		}

		if ( isset( $request['enquiry_form']['email_addresses'] ) ) {
			$plugin_settings->set( 'email.enquiry_emailaddress', implode( ',', $request['enquiry_form']['email_addresses'] ) );
		}

		if ( isset( $request['enquiry_form']['email_subject'] ) ) {
			$plugin_settings->set( 'query_subject', $request['enquiry_form']['email_subject'] );
		}

		if ( isset( $request['enquiry_form']['notify_customer'] ) ) {
			$plugin_settings->set( 'email.cust_notif', wptravelengine_replace( $request['enquiry_form']['notify_customer'], true, '1' ) );
		}

		if ( isset( $request['enquiry_form']['powered_by_link'] ) ) {
			$plugin_settings->set( 'hide_powered_by', wptravelengine_replace( $request['enquiry_form']['powered_by_link'], false, 'yes', 'no' ) );
		}
	}

	/**
	 * Process Email Notification Details.
	 *
	 * @param WP_REST_Request $request Request object.
	 *
	 * @return void
	 * @since 6.2.0
	 */
	protected function set_email_notification_details( WP_REST_Request $request ) {

		$plugin_settings = $this->plugin_settings;

		if ( ! isset( $request['email_notification'] ) ) {
			return;
		}

		if ( isset( $request['email_notification']['admin'] ) ) :

			$admin_value = array();
			$admin       = $request['email_notification']['admin'];

			foreach ( $admin as $value ) :
				unset( $value['show'] );
				switch ( $value['id'] ) :
					case 'booking_confirmation':
						$plugin_settings->set( 'email.disable_booking_notification', wptravelengine_replace( $value['enabled'], false, '1' ) );
						$plugin_settings->set( 'email.booking_notification_subject_admin', $value['subject'] );
						$plugin_settings->set( 'email.booking_notification_template_admin', $value['content'] );
						break;

					case 'payment_confirmation':
						$plugin_settings->set( 'email.sale_subject', $value['subject'] );
						$plugin_settings->set( 'email.sales_wpeditor', $value['content'] );
						break;

				endswitch;

				$value['enabled']    = wptravelengine_replace( $value['enabled'], true, 'yes', 'no' );
				$value['is_default'] = wptravelengine_replace( $value['is_default'], true, 'yes', 'no' );

				$admin_value[ $value['id'] ] = $value;

			endforeach;

			$plugin_settings->set( 'admin_email_notify_tabs', $admin_value );

		endif;

		if ( isset( $request['email_notification']['customer'] ) ) :

			$customer_value = array();
			$customer       = $request['email_notification']['customer'];

			foreach ( $customer as $value ) :
				unset( $value['show'] );
				switch ( $value['id'] ) :
					case 'booking_confirmation':
						$plugin_settings->set( 'email.enable_cust_notif', wptravelengine_replace( $value['enabled'], true, 'yes', 'no' ) );
						$plugin_settings->set( 'email.booking_notification_subject_customer', $value['subject'] );
						$plugin_settings->set( 'email.booking_notification_template_customer', $value['content'] );
						break;

					case 'payment_confirmation':
						$plugin_settings->set( 'email.subject', $value['subject'] );
						$plugin_settings->set( 'email.purchase_wpeditor', $value['content'] );
						break;

					case 'enquiry':
						$plugin_settings->set( 'email.enquiry_subject', $value['subject'] );
						$plugin_settings->set( 'email.cust_notif', wptravelengine_replace( $value['enabled'], true, '1' ) );
						break;

				endswitch;

				$value['enabled']    = wptravelengine_replace( $value['enabled'], true, 'yes', 'no' );
				$value['is_default'] = wptravelengine_replace( $value['is_default'], true, 'yes', 'no' );

				$customer_value[ $value['id'] ] = $value;

			endforeach;

			$plugin_settings->set( 'customer_email_notify_tabs', $customer_value );

		endif;
	}

	/**
	 * Process Email Settings.
	 *
	 * @param WP_REST_Request $request Request object.
	 *
	 * @return void
	 * @since 6.5.0
	 */
	protected function set_email_settings( WP_REST_Request $request ) {
		$plugin_settings = $this->plugin_settings;

		if ( isset( $request['email_settings'] ) ) {

			if ( isset( $request['email_settings']['enquiry_emails'] ) ) {
				$plugin_settings->set( 'email.enquiry_emailaddress', implode( ',', $request['email_settings']['enquiry_emails'] ) );
			}

			if ( isset( $request['email_settings']['sale_emails'] ) ) {
				$plugin_settings->set( 'email.emails', implode( ',', $request['email_settings']['sale_emails'] ) );
			}

			if ( isset( $request['email_settings']['from_name'] ) ) {
				$plugin_settings->set( 'email.name', $request['email_settings']['from_name'] );
			}

			if ( isset( $request['email_settings']['from'] ) ) {
				if ( is_email( $request['email_settings']['from'] ) ) {
					$plugin_settings->set( 'email.from', $request['email_settings']['from'] );
				} else {
					$this->set_bad_request( 'invalid_parameter', __( 'Invalid email address', 'wp-travel-engine' ) );
					return;
				}
			}

			if ( isset( $request['email_settings']['reply_to'] ) ) {
				if ( is_email( $request['email_settings']['reply_to'] ) ) {
					$plugin_settings->set( 'email.reply_to', $request['email_settings']['reply_to'] );
				} else {
					$this->set_bad_request( 'invalid_parameter', __( 'Invalid email address', 'wp-travel-engine' ) );
					return;
				}
				$plugin_settings->set( 'email.reply_to', $request['email_settings']['reply_to'] );
			}

			if ( isset( $request['email_settings']['logo'] ) ) {
				if ( ! empty( $request['email_settings']['logo'] ) ) {
					$plugin_settings->set( 'email.logo', $request['email_settings']['logo'] );
				} else {
					$plugin_settings->set( 'email.logo.id', null );
					$plugin_settings->set( 'email.logo.url', '' );
				}
			}

			if ( isset( $request['email_settings']['footer'] ) ) {
				$plugin_settings->set( 'email.footer', $request['email_settings']['footer'] );
			}
		}
	}

	/**
	 * Process Appearance Settings.
	 *
	 * @param WP_REST_Request $request Request object.
	 *
	 * @return void
	 * @since 6.6.1
	 */
	protected function set_appearance_settings( WP_REST_Request $request ) {
		$appearance = ArrayUtility::make( Options::get( 'wptravelengine_appearance' ) );

		if ( isset( $request['appearance']['primary_color'] ) ) {
			$appearance->set( 'primary_color', sanitize_hex_color( $request['appearance']['primary_color'] ) );
			$appearance->set( 'primary_color_rgb', wptravelengine_hex_to_rgb( $request['appearance']['primary_color'] ) );
		}

		if ( isset( $request['appearance']['discount_color'] ) ) {
			$appearance->set( 'discount_color', sanitize_hex_color( $request['appearance']['discount_color'] ) );
		}

		if ( isset( $request['appearance']['featured_color'] ) ) {
			$appearance->set( 'featured_color', sanitize_hex_color( $request['appearance']['featured_color'] ) );
		}

		if ( isset( $request['appearance']['icon_color'] ) ) {
			$appearance->set( 'icon_color', sanitize_hex_color( $request['appearance']['icon_color'] ) );
		}

		Options::update( 'wptravelengine_appearance', $appearance->value() );
	}

	/**
	 * Process Trip Card of Display Tab.
	 *
	 * @param WP_REST_Request $request Request object.
	 *
	 * @return void
	 * @since 6.2.0
	 */
	protected function set_trip_card( WP_REST_Request $request ) {

		$plugin_settings = $this->plugin_settings;

		if ( isset( $request['trip_duration_label_on_card'] ) ) {
			$plugin_settings->set( 'set_duration_type', $request['trip_duration_label_on_card'] );
		}

		if ( ! isset( $request['card_new_layout'] ) ) {
			return;
		}

		// if ( isset( $request[ 'card_new_layout' ][ 'enable' ] ) ) {
		// $plugin_settings->set( 'display_new_trip_listing', wptravelengine_replace( $request[ 'card_new_layout' ][ 'enable' ], true, 'yes', 'no' ) );
		// }

		if ( isset( $request['card_new_layout']['enable_slider'] ) ) {
			$plugin_settings->set( 'display_slider_layout', wptravelengine_replace( $request['card_new_layout']['enable_slider'], true, '1', '0' ) );
		}

		if ( isset( $request['card_new_layout']['enable_featured_tag'] ) ) {
			$plugin_settings->set( 'show_featured_tag', wptravelengine_replace( $request['card_new_layout']['enable_featured_tag'], true, '1', '0' ) );
		}

		if ( isset( $request['card_new_layout']['enable_wishlist'] ) ) {
			$plugin_settings->set( 'show_wishlist', wptravelengine_replace( $request['card_new_layout']['enable_wishlist'], true, '1', '0' ) );
		}

		if ( isset( $request['card_new_layout']['enable_map'] ) ) {
			$plugin_settings->set( 'show_map_on_card', wptravelengine_replace( $request['card_new_layout']['enable_map'], true, '1', '0' ) );
		}

		if ( isset( $request['card_new_layout']['enable_excerpt'] ) ) {
			$plugin_settings->set( 'show_excerpt', wptravelengine_replace( $request['card_new_layout']['enable_excerpt'], true, '1', '0' ) );
		}

		if ( isset( $request['card_new_layout']['enable_difficulty'] ) ) {
			$plugin_settings->set( 'show_difficulty_tax', wptravelengine_replace( $request['card_new_layout']['enable_difficulty'], true, '1', '0' ) );
		}

		if ( isset( $request['card_new_layout']['enable_tags'] ) ) {
			$plugin_settings->set( 'show_trips_tag', wptravelengine_replace( $request['card_new_layout']['enable_tags'], true, '1', '0' ) );
		}

		if ( isset( $request['card_new_layout']['enable_fsd'] ) ) {
			$plugin_settings->set( 'show_date_layout', wptravelengine_replace( $request['card_new_layout']['enable_fsd'], true, '1', '0' ) );
		}

		if ( isset( $request['card_new_layout']['enable_available_months'] ) ) {
			$plugin_settings->set( 'show_available_months', wptravelengine_replace( $request['card_new_layout']['enable_available_months'], true, '1', '0' ) );
		}

		if ( isset( $request['card_new_layout']['enable_available_dates'] ) ) {
			$plugin_settings->set( 'show_available_dates', wptravelengine_replace( $request['card_new_layout']['enable_available_dates'], true, '1', '0' ) );
		}
	}

	/**
	 * Process Single Trip of Display Tab.
	 *
	 * @param WP_REST_Request $request Request object.
	 *
	 * @return void
	 * @since 6.2.0
	 */
	protected function set_single_trip( WP_REST_Request $request ) {

		$plugin_settings = $this->plugin_settings;

		if ( isset( $request['show_modal_warning'] ) ) {
			$plugin_settings->set( 'show_booking_modal_warning', wptravelengine_replace( $request['show_modal_warning'], true, 'yes', 'no' ) );
		}

		if ( isset( $request['modal_warning_message'] ) ) {
			$plugin_settings->set( 'booking_modal_warning_message', $request['modal_warning_message'] );
		}

		if ( isset( $request['trip_banner_layout'] ) ) {
			$plugin_settings->set( 'trip_banner_layout', $request['trip_banner_layout'] );
		}

		if ( isset( $request['display_banner_fullwidth'] ) ) {
			$plugin_settings->set( 'display_banner_fullwidth', wptravelengine_replace( $request['display_banner_fullwidth'], true, 'yes', 'no' ) );
		}

		if ( isset( $request['enable_booking_form'] ) ) {
			$plugin_settings->set( 'booking', wptravelengine_replace( $request['enable_booking_form'], false, '1', '' ) );
		}

		if ( isset( $request['pricing_section_layout'] ) ) {
			$plugin_settings->set( 'pricing_section_layout', $request['pricing_section_layout'] );
		}

		if ( isset( $request['enable_compact_layout'] ) ) {
			$plugin_settings->set( 'enable_compact_layout', wptravelengine_replace( $request['enable_compact_layout'], true, 'yes', 'no' ) );
		}

		if ( isset( $request['inquiry_form']['enable'] ) ) {
			$plugin_settings->set( 'show_enquiry_info', wptravelengine_replace( $request['inquiry_form']['enable'], true, 'yes', 'no' ) );
		}

		if ( isset( $request['inquiry_form']['link_type'] ) ) {
			$plugin_settings->set( 'enquiry_form_link', $request['inquiry_form']['link_type'] );
		}

		if ( isset( $request['inquiry_form']['link'] ) ) {
			$plugin_settings->set( 'custom_enquiry_link', $request['inquiry_form']['link'] );
		}

		if ( isset( $request['whatsapp']['enable'] ) ) {
			$plugin_settings->set( 'show_whatsapp_icon', wptravelengine_replace( $request['whatsapp']['enable'], true, 'yes', 'no' ) );
		}

		if ( isset( $request['whatsapp']['number'] ) ) {
			$plugin_settings->set( 'whatsapp_number', $request['whatsapp']['number'] );
		}

		if ( isset( $request['enable_tabs_sticky'] ) ) {
			$plugin_settings->set( 'wte_sticky_tabs', wptravelengine_replace( $request['enable_tabs_sticky'], true, 'yes', 'no' ) );
		}

		if ( isset( $request['enable_booking_widget_sticky'] ) ) {
			$plugin_settings->set( 'wte_sticky_booking_widget', wptravelengine_replace( $request['enable_booking_widget_sticky'], true, 'yes', 'no' ) );
		}

		if ( isset( $request['related_trips']['enable'] ) ) {
			$plugin_settings->set( 'show_related_trips', wptravelengine_replace( $request['related_trips']['enable'], true, 'yes', 'no' ) );
		}

		if ( isset( $request['related_trips']['title'] ) ) {
			$plugin_settings->set( 'related_trips_section_title', $request['related_trips']['title'] );
		}

		if ( isset( $request['related_trips']['number'] ) ) {
			$plugin_settings->set( 'no_of_related_trips', $request['related_trips']['number'] );
		}

		if ( isset( $request['related_trips']['show_by'] ) ) {
			$plugin_settings->set( 'related_trip_show_by', $request['related_trips']['show_by'] );
		}

		if ( isset( $request['pricing_widget_enquiry_message'] ) ) {
			$plugin_settings->set( 'pricing_widget_enquiry_message', $request['pricing_widget_enquiry_message'] );
		}

		if ( isset( $request['related_trip_new_layout']['enable'] ) ) {
			$plugin_settings->set( 'related_display_new_trip_listing', wptravelengine_replace( $request['related_trip_new_layout']['enable'], true, 'yes', 'no' ) );
		}

		if ( isset( $request['related_trip_new_layout']['enable_slider'] ) ) {
			$plugin_settings->set( 'show_related_trip_carousel', wptravelengine_replace( $request['related_trip_new_layout']['enable_slider'], true, '1', '0' ) );
		}

		if ( isset( $request['related_trip_new_layout']['enable_featured_tag'] ) ) {
			$plugin_settings->set( 'show_related_featured_tag', wptravelengine_replace( $request['related_trip_new_layout']['enable_featured_tag'], true, '1', '0' ) );
		}

		if ( isset( $request['related_trip_new_layout']['enable_wishlist'] ) ) {
			$plugin_settings->set( 'show_related_wishlist', wptravelengine_replace( $request['related_trip_new_layout']['enable_wishlist'], true, '1', '0' ) );
		}

		if ( isset( $request['related_trip_new_layout']['enable_map'] ) ) {
			$plugin_settings->set( 'show_related_map', wptravelengine_replace( $request['related_trip_new_layout']['enable_map'], true, '1', '0' ) );
		}

		if ( isset( $request['related_trip_new_layout']['enable_excerpt'] ) ) {
			$plugin_settings->set( 'show_related_excerpt', wptravelengine_replace( $request['related_trip_new_layout']['enable_excerpt'], true, '1', '0' ) );
		}

		if ( isset( $request['related_trip_new_layout']['enable_difficulty'] ) ) {
			$plugin_settings->set( 'show_related_difficulty_tax', wptravelengine_replace( $request['related_trip_new_layout']['enable_difficulty'], true, '1', '0' ) );
		}

		if ( isset( $request['related_trip_new_layout']['enable_tags'] ) ) {
			$plugin_settings->set( 'show_related_trip_tags', wptravelengine_replace( $request['related_trip_new_layout']['enable_tags'], true, '1', '0' ) );
		}

		if ( isset( $request['related_trip_new_layout']['enable_fsd'] ) ) {
			$plugin_settings->set( 'show_related_date_layout', wptravelengine_replace( $request['related_trip_new_layout']['enable_fsd'], true, '1', '0' ) );
		}

		if ( isset( $request['related_trip_new_layout']['enable_available_months'] ) ) {
			$plugin_settings->set( 'show_related_available_months', wptravelengine_replace( $request['related_trip_new_layout']['enable_available_months'], true, '1', '0' ) );
		}

		if ( isset( $request['related_trip_new_layout']['enable_available_dates'] ) ) {
			$plugin_settings->set( 'show_related_available_dates', wptravelengine_replace( $request['related_trip_new_layout']['enable_available_dates'], true, '1', '0' ) );
		}

		if ( isset( $request['enable_trip_info'] ) ) {
			$plugin_settings->set( 'show_trip_facts', wptravelengine_replace( $request['enable_trip_info'], true, 'yes', 'no' ) );
		}

		if ( isset( $request['enable_trip_info_on_sidebar'] ) ) {
			$plugin_settings->set( 'show_trip_facts_sidebar', wptravelengine_replace( $request['enable_trip_info_on_sidebar'], true, 'yes', 'no' ) );
		}

		if ( isset( $request['enable_trip_info_on_main_content'] ) ) {
			$plugin_settings->set( 'show_trip_facts_content_area', wptravelengine_replace( $request['enable_trip_info_on_main_content'], true, 'yes', 'no' ) );
		}

		if ( isset( $request['enable_image_autoplay'] ) ) {
			$plugin_settings->set( 'gallery_autoplay', wptravelengine_replace( $request['enable_image_autoplay'], true, 'yes', 'no' ) );
		}

		if ( isset( $request['trip_duration_format'] ) ) {
			$plugin_settings->set( 'trip_duration_format', $request['trip_duration_format'] );
		}

		if ( isset( $request['show_discounts_type'] ) ) {
			$plugin_settings->set( 'show_discounts_type', $request['show_discounts_type'] );
		}

		if ( isset( $request['enable_featured_image'] ) ) {
			$plugin_settings->set( 'feat_img', wptravelengine_replace( $request['enable_featured_image'], false, 'yes', 'no' ) );
		}

		if ( isset( $request['enable_image_in_gallery'] ) ) {
			$plugin_settings->set( 'show_featured_image_in_gallery', wptravelengine_replace( $request['enable_image_in_gallery'], true, 'yes', 'no' ) );
		}

		if ( isset( $request['enable_fse'] ) ) {
			$plugin_settings->set( 'enable_fse_template', wptravelengine_replace( $request['enable_fse'], true, 'yes', 'no' ) );
		}

		if ( isset( $request['enquiry_enable'] ) ) {
			$plugin_settings->set( 'enquiry', wptravelengine_replace( $request['enquiry_enable'], false, '1', '' ) );
		}

		if ( isset( $request['enquiry_custom_form']['shortcode'] ) ) {
			$plugin_settings->set( 'enquiry_shortcode', $request['enquiry_custom_form']['shortcode'] );
		}

		if ( isset( $request['enquiry_custom_form']['enable'] ) ) {
			$plugin_settings->set( 'custom_enquiry', wptravelengine_replace( $request['enquiry_custom_form']['enable'], true, 'yes', '' ) );
		}
	}

	/**
	 * Process Trip Archive of Display Tab.
	 *
	 * @param WP_REST_Request $request Request object.
	 *
	 * @return void
	 * @since 6.2.0
	 */
	protected function set_trip_archive( WP_REST_Request $request ) {
		$plugin_settings = $this->plugin_settings;

		if ( isset( $request['enable_archive_title'] ) ) {
			$plugin_settings->set( 'hide_term_title', wptravelengine_replace( $request['enable_archive_title'], false, 'yes', 'no' ) );
		}

		if ( isset( $request['sort_trips_by'] ) ) {
			Options::update( 'wptravelengine_trip_sort_by', $request['sort_trips_by'] );
		}

		if ( isset( $request['trip_view_mode'] ) ) {
			Options::update( 'wptravelengine_trip_view_mode', $request['trip_view_mode'] );
		}

		if ( isset( $request['show_sidebar'] ) ) {
			Options::update( 'wptravelengine_show_trip_search_sidebar', wptravelengine_replace( $request['show_sidebar'], true, 'yes', 'no' ) );
		}

		if ( isset( $request['display_mode'] ) ) {
			Options::update( 'wptravelengine_archive_display_mode', $request['display_mode'] );
		}

		if ( isset( $request['featured_trips']['enable'] ) ) {
			$plugin_settings->set( 'show_featured_trips_on_top', wptravelengine_replace( $request['featured_trips']['enable'], true, 'yes', 'no' ) );
		}

		if ( isset( $request['featured_trips']['number'] ) ) {
			$plugin_settings->set( 'feat_trip_num', $request['featured_trips']['number'] );
		}

		if ( isset( $request['archives'] ) ) {
			$archives                       = $plugin_settings->get( 'archive', array() );
			$archives['title']              = $request['archives']['title'] ?? $archives['title'] ?? '';
			$archives['hide_archive_title'] = isset( $request['archives']['enable_title'] ) ? wptravelengine_replace( $request['archives']['enable_title'], false, 'yes', 'no' ) : ( $archives['hide_archive_title'] ?? 'no' );
			$archives['title_type']         = $request['archives']['title_type'] ?? $archives['title_type'] ?? 'default';
			// $archives[ 'collapsible_filter_panel' ] = isset( $request[ 'archives' ][ 'enable_advance_search' ] ) ? wptravelengine_replace( $request[ 'archives' ][ 'enable_advance_search' ], true, 'yes', 'no' ) : ( $archives[ 'collapsible_filter_panel' ] ?? 'no' );
			$plugin_settings->set( 'archive', $archives );
			unset( $archives );
		}

		// if ( isset( $request[ 'enable_criteria_filter' ] ) ) {
		// $plugin_settings->set( 'search_filter_option', wptravelengine_replace( $request[ 'enable_criteria_filter' ], true, 'yes', 'no' ) );
		// }
	}

	/**
	 * Process Checkout of Display Tab.
	 *
	 * @param WP_REST_Request $request Request object.
	 *
	 * @return void
	 * @since 6.2.0
	 */
	protected function set_checkout( WP_REST_Request $request ) {
		$plugin_settings = $this->plugin_settings;

		if ( isset( $request['enable_travellers_info'] ) ) {
			$plugin_settings->set( 'travelers_information', wptravelengine_replace( $request['enable_travellers_info'], true, 'no', 'yes' ) );
		}

		if ( isset( $request['enable_emergency_contact'] ) ) {
			$plugin_settings->set( 'emergency', wptravelengine_replace( $request['enable_emergency_contact'], false, '1', null ) );
		}

		if ( isset( $request['booking_confirmation_msg'] ) ) {
			$plugin_settings->set( 'confirmation_msg', $request['booking_confirmation_msg'] );
		}

		if ( isset( $request['gdpr_msg'] ) ) {
			$plugin_settings->set( 'gdpr_msg', $request['gdpr_msg'] );
		}

		if ( isset( $request['checkout_page_template'] ) ) {
			$plugin_settings->set( 'checkout_page_template', $request['checkout_page_template'] );
		}

		if ( isset( $request['display_header_footer'] ) ) {
			$plugin_settings->set( 'display_header_footer', wptravelengine_replace( $request['display_header_footer'], true, 'yes', 'no' ) );
		}

		if ( isset( $request['display_travellers_info'] ) ) {
			$plugin_settings->set( 'display_travellers_info', wptravelengine_replace( $request['display_travellers_info'], true, 'yes', 'no' ) );
		}

		if ( isset( $request['display_emergency_contact'] ) ) {
			$plugin_settings->set( 'display_emergency_contact', wptravelengine_replace( $request['display_emergency_contact'], true, 'yes', 'no' ) );
		}

		if ( isset( $request['traveller_emergency_details_form'] ) ) {
			$plugin_settings->set( 'traveller_emergency_details_form', $request['traveller_emergency_details_form'] );
		}

		if ( isset( $request['travellers_details_type'] ) ) {
			$plugin_settings->set( 'travellers_details_type', $request['travellers_details_type'] );
		}

		if ( isset( $request['display_billing_details'] ) ) {
			$plugin_settings->set( 'display_billing_details', wptravelengine_replace( $request['display_billing_details'], true, 'yes', 'no' ) );
		}

		if ( isset( $request['show_additional_note'] ) ) {
			$plugin_settings->set( 'show_additional_note', wptravelengine_replace( $request['show_additional_note'], true, 'yes', 'no' ) );
		}

		if ( isset( $request['show_discount'] ) ) {
			$plugin_settings->set( 'show_discount', wptravelengine_replace( $request['show_discount'], true, 'yes', 'no' ) );
		}

		if ( isset( $request['privacy_policy_msg'] ) ) {
			$plugin_settings->set( 'privacy_policy_msg', $request['privacy_policy_msg'] );
		}

		if ( isset( $request['footer_copyright'] ) ) {
			$plugin_settings->set( 'footer_copyright', $request['footer_copyright'] );
		}
	}

	/**
	 * Process Taxonomy of Display Tab.
	 *
	 * @param WP_REST_Request $request Request object.
	 *
	 * @return void
	 * @since 6.2.0
	 */
	protected function set_taxonomy( WP_REST_Request $request ) {

		if ( ! isset( $request['taxonomy'] ) ) {
			return;
		}

		$plugin_settings = $this->plugin_settings;

		if ( isset( $request['taxonomy']['enable_image'] ) ) {
			$plugin_settings->set( 'tax_images', wptravelengine_replace( $request['taxonomy']['enable_image'], true, '1' ) );
		}

		if ( isset( $request['taxonomy']['enable_children_terms'] ) ) {
			$plugin_settings->set( 'show_taxonomy_children', wptravelengine_replace( $request['taxonomy']['enable_children_terms'], true, 'yes', 'no' ) );
		}
	}

	/**
	 * Process Display Tab Settings.
	 *
	 * @param WP_REST_Request $request Request object.
	 *
	 * @return void
	 * @since 6.2.0
	 */
	protected function set_display_tabs( WP_REST_Request $request ) {

		$this->set_trip_card( $request );
		$this->set_single_trip( $request );
		$this->set_trip_archive( $request );
		$this->set_checkout( $request );
		$this->set_taxonomy( $request );

		if ( isset( $request['custom_strings'] ) ) {
			$static_string = new StaticStrings();
			$static_string->update( $request['custom_strings'] );
		}
	}

	/**
	 * Process currency settings.
	 *
	 * @param WP_REST_Request $request Request object.
	 *
	 * @return void
	 * @since 6.2.0
	 */
	protected function set_currency_details( WP_REST_Request $request ) {

		$plugin_settings = $this->plugin_settings;

		if ( isset( $request['currency_code'] ) ) {
			$plugin_settings->set( 'currency_code', $request['currency_code'] );
		}

		if ( isset( $request['currency_symbol'] ) ) {
			$plugin_settings->set( 'currency_option', $request['currency_symbol'] );
		}

		if ( isset( $request['amount_format'] ) ) {
			$plugin_settings->set( 'amount_display_format', $request['amount_format'] );
		}

		if ( isset( $request['decimal_digits'] ) ) {
			$plugin_settings->set( 'decimal_digits', $request['decimal_digits'] );
		}

		if ( isset( $request['decimal_separator'] ) ) {
			$plugin_settings->set( 'decimal_separator', $request['decimal_separator'] );
		}

		if ( isset( $request['thousands_separator'] ) ) {
			$plugin_settings->set( 'thousands_separator', $request['thousands_separator'] );
		}
	}

	/**
	 * Prepare payment gateway settings.
	 *
	 * @param WP_REST_Request $request Request object.
	 *
	 * @return void
	 * @since 6.2.0
	 */
	protected function set_payment_gateway( WP_REST_Request $request ) {

		$plugin_settings = $this->plugin_settings;

		if ( isset( $request['debug_mode'] ) ) {
			$plugin_settings->set( 'payment_debug', wptravelengine_replace( $request['debug_mode'], true, 'yes', 'no' ) );
		}

		if ( isset( $request['default_payment_gateway'] ) ) {
			$plugin_settings->set( 'default_gateway', $request['default_payment_gateway'] );
		}

		if ( isset( $request['payment_gateways'] ) ) {

			$payment_gateways = array_column( $request['payment_gateways'], 'enable', 'id' );

			Options::update( 'wptravelengine_payment_gateways', $request['payment_gateways'] );

			foreach ( $payment_gateways as $id => $enable ) {
				$plugin_settings->set( $id, wptravelengine_replace( $enable, true, '1' ) );
			}
		}

		if ( isset( $request['paypal']['paypal_id'] ) ) {
			$plugin_settings->set( 'paypal_id', $request['paypal']['paypal_id'] );
		}

		if ( isset( $request['direct_bank_transfer'] ) ) {
			if ( isset( $request['direct_bank_transfer']['title'] ) ) {
				$plugin_settings->set( 'bank_transfer.title', $request['direct_bank_transfer']['title'] );
			}

			if ( isset( $request['direct_bank_transfer']['description'] ) ) {
				$plugin_settings->set( 'bank_transfer.description', $request['direct_bank_transfer']['description'] );
			}

			if ( isset( $request['direct_bank_transfer']['instructions'] ) ) {
				$plugin_settings->set( 'bank_transfer.instruction', $request['direct_bank_transfer']['instructions'] );
			}

			if ( isset( $request['direct_bank_transfer']['account_details'] ) ) {
				$plugin_settings->set( 'bank_transfer.accounts', $request['direct_bank_transfer']['account_details'] );
			}
		}

		if ( isset( $request['check_payments'] ) ) {
			if ( isset( $request['check_payments']['title'] ) ) {
				$plugin_settings->set( 'check_payment.title', $request['check_payments']['title'] );
			}

			if ( isset( $request['check_payments']['description'] ) ) {
				$plugin_settings->set( 'check_payment.description', $request['check_payments']['description'] );
			}

			if ( isset( $request['check_payments']['instructions'] ) ) {
				$plugin_settings->set( 'check_payment.instruction', $request['check_payments']['instructions'] );
			}
		}

		if ( isset( $request['hbl'] ) ) {
			if ( isset( $request['hbl']['office_id'] ) ) {
				$plugin_settings->set( 'hbl_settings.office_id', $request['hbl']['office_id'] );
			}

			if ( isset( $request['hbl']['api_key'] ) ) {
				$plugin_settings->set( 'hbl_settings.api_key', $request['hbl']['api_key'] );
			}

			if ( isset( $request['hbl']['encryption_key_id'] ) ) {
				$plugin_settings->set( 'hbl_settings.key_id', $request['hbl']['encryption_key_id'] );
			}

			if ( isset( $request['hbl']['merchant_private_keys']['signing_key'] ) ) {
				$plugin_settings->set( 'hbl_settings.merchant_signing_private_key', $request['hbl']['merchant_private_keys']['signing_key'] );
			}

			if ( isset( $request['hbl']['merchant_private_keys']['decryption_key'] ) ) {
				$plugin_settings->set( 'hbl_settings.merchant_decryption_private_key', $request['hbl']['merchant_private_keys']['decryption_key'] );
			}

			if ( isset( $request['hbl']['paco_public_keys']['signing_key'] ) ) {
				$plugin_settings->set( 'hbl_settings.paco_signing_public_key', $request['hbl']['paco_public_keys']['signing_key'] );
			}

			if ( isset( $request['hbl']['paco_public_keys']['encryption_key'] ) ) {
				$plugin_settings->set( 'hbl_settings.paco_encryption_public_key', $request['hbl']['paco_public_keys']['encryption_key'] );
			}

			if ( isset( $request['hbl']['notification_urls']['confirmation_url'] ) ) {
				$plugin_settings->set( 'hbl_settings.confirmation_url', $request['hbl']['notification_urls']['confirmation_url'] );
			}

			if ( isset( $request['hbl']['notification_urls']['cancellation_url'] ) ) {
				$plugin_settings->set( 'hbl_settings.cancel_url', $request['hbl']['notification_urls']['cancellation_url'] );
			}

			if ( isset( $request['hbl']['notification_urls']['failure_url'] ) ) {
				$plugin_settings->set( 'hbl_settings.failed_url', $request['hbl']['notification_urls']['failure_url'] );
			}

			if ( isset( $request['hbl']['notification_urls']['notify_url'] ) ) {
				$plugin_settings->set( 'hbl_settings.backend_url', $request['hbl']['notification_urls']['notify_url'] );
			}
		}

		if ( isset( $request['payfast'] ) ) {
			if ( isset( $request['payfast']['merchant_id'] ) ) {
				$plugin_settings->set( 'payfast_id', $request['payfast']['merchant_id'] );
			}

			if ( isset( $request['payfast']['merchant_key'] ) ) {
				$plugin_settings->set( 'payfast_merchant_key', $request['payfast']['merchant_key'] );
			}
		}

		if ( isset( $request['stripe'] ) ) {
			if ( isset( $request['stripe']['secret_key'] ) ) {
				$plugin_settings->set( 'stripe_secret', $request['stripe']['secret_key'] );
			}

			if ( isset( $request['stripe']['publishable_key'] ) ) {
				$plugin_settings->set( 'stripe_publishable', $request['stripe']['publishable_key'] );
			}

			if ( isset( $request['stripe']['pay_btn_label'] ) ) {
				$plugin_settings->set( 'stripe_btn_label', $request['stripe']['pay_btn_label'] );
			}

			if ( isset( $request['stripe']['enable_postal_code'] ) ) {
				$plugin_settings->set( 'stripe_hide_postal_code', wptravelengine_replace( $request['stripe']['enable_postal_code'], true, 'no', 'yes' ) );
			}
		}

		if ( isset( $request['paypal_express'] ) ) {
			if ( isset( $request['paypal_express']['client_id'] ) ) {
				$plugin_settings->set( 'paypalexpress_client_id', $request['paypal_express']['client_id'] );
			}

			if ( isset( $request['paypal_express']['client_secret'] ) ) {
				$plugin_settings->set( 'paypalexpress_secret', $request['paypal_express']['client_secret'] );
			}

			if ( isset( $request['paypal_express']['disable_funding'] ) ) {
				$plugin_settings->set( 'paypalexpress_payment_method', $request['paypal_express']['disable_funding'] );
			}
		}

		if ( isset( $request['authorize_net'] ) ) {
			if ( isset( $request['authorize_net']['api_login_id'] ) ) {
				$plugin_settings->set( 'authorizenet.api_login_id', $request['authorize_net']['api_login_id'] );
			}

			if ( isset( $request['authorize_net']['transaction_key'] ) ) {
				$plugin_settings->set( 'authorizenet.transaction_key', $request['authorize_net']['transaction_key'] );
			}
		}

		if ( isset( $request['midtrans'] ) ) {
			if ( isset( $request['midtrans']['merchant_id'] ) ) {
				$plugin_settings->set( 'midtrans.merchant_id', $request['midtrans']['merchant_id'] );
			}

			if ( isset( $request['midtrans']['client_key'] ) ) {
				$plugin_settings->set( 'midtrans.client_key', $request['midtrans']['client_key'] );
			}

			if ( isset( $request['midtrans']['server_key'] ) ) {
				$plugin_settings->set( 'midtrans.server_key', $request['midtrans']['server_key'] );
			}

			if ( isset( $request['midtrans']['enable_3Ds_secure'] ) ) {
				$plugin_settings->set( 'midtrans.3ds_enabled', wptravelengine_replace( $request['midtrans']['enable_3Ds_secure'], true, '1', '0' ) );
			}

			if ( isset( $request['midtrans']['enable_save_card'] ) ) {
				$plugin_settings->set( 'midtrans.save_card_enabled', wptravelengine_replace( $request['midtrans']['enable_save_card'], true, '1', '0' ) );
			}
		}

		if ( isset( $request['payhere'] ) ) {
			if ( isset( $request['payhere']['merchant_id'] ) ) {
				$plugin_settings->set( 'payhere_merchant_id', $request['payhere']['merchant_id'] );
			}

			if ( isset( $request['payhere']['merchant_secret'] ) ) {
				$plugin_settings->set( 'payhere_merchant_secret', $request['payhere']['merchant_secret'] );
			}

			if ( isset( $request['payhere']['enable_onsite_checkout'] ) ) {
				$plugin_settings->set( 'payhere_enable_onsite', wptravelengine_replace( $request['payhere']['enable_onsite_checkout'], true, '1', 'no' ) );
			}
		}

		if ( isset( $request['payu_money'] ) ) {
			if ( isset( $request['payu_money']['merchant_key'] ) ) {
				$plugin_settings->set( 'payu_money_merchant_id', $request['payu_money']['merchant_key'] );
			}

			if ( isset( $request['payu_money']['merchant_salt'] ) ) {
				$plugin_settings->set( 'payu_money_salt', $request['payu_money']['merchant_salt'] );
			}
		}

		if ( isset( $request['payu_biz'] ) ) {
			if ( isset( $request['payu_biz']['merchant_key'] ) ) {
				$plugin_settings->set( 'payu_merchant_id', $request['payu_biz']['merchant_key'] );
			}

			if ( isset( $request['payu_biz']['merchant_salt'] ) ) {
				$plugin_settings->set( 'payu_salt', $request['payu_biz']['merchant_salt'] );
			}
		}

		if ( isset( $request['enable_woocommerce_gateway'] ) ) {
			$plugin_settings->set( 'use_woocommerce_payment_gateway', wptravelengine_replace( $request['enable_woocommerce_gateway'], true, 'yes', 'no' ) );
		}

		if ( ! isset( $request['tax'] ) ) {
			return;
		}

		if ( isset( $request['tax']['enable'] ) ) {
			$plugin_settings->set( 'tax_enable', wptravelengine_replace( $request['tax']['enable'], true, 'yes', 'no' ) );
		}

		if ( isset( $request['tax']['custom_label'] ) ) {
			$plugin_settings->set( 'tax_label', $request['tax']['custom_label'] );
		}

		if ( isset( $request['tax']['type'] ) ) {
			$plugin_settings->set( 'tax_type_option', $request['tax']['type'] );
		}

		if ( isset( $request['tax']['percentage'] ) ) {
			$plugin_settings->set( 'tax_percentage', $request['tax']['percentage'] );
		}
	}

	/**
	 * Process Dashboard Settings.
	 *
	 * @param WP_REST_Request $request Request object.
	 *
	 * @return void
	 * @since 6.2.0
	 */
	protected function set_dashboard_settings( WP_REST_Request $request ) {
		$plugin_settings = $this->plugin_settings;

		if ( isset( $request['generate_user_account'] ) ) {
			$plugin_settings->set( 'generate_user_account', wptravelengine_replace( $request['generate_user_account'], true, 'yes', 'no' ) );
		}

		if ( isset( $request['enable_booking_registration'] ) ) {
			$plugin_settings->set( 'enable_checkout_customer_registration', wptravelengine_replace( $request['enable_booking_registration'], true, 'yes', 'no' ) );
		}

		if ( isset( $request['enable_account_registration'] ) ) {
			$plugin_settings->set( 'disable_my_account_customer_registration', wptravelengine_replace( $request['enable_account_registration'], false, 'yes', 'no' ) );
		}

		if ( isset( $request['login_page_label'] ) ) {
			$plugin_settings->set( 'login_page_label', $request['login_page_label'] );
		}

		if ( isset( $request['forgot_page_label'] ) ) {
			$plugin_settings->set( 'forgot_page_label', $request['forgot_page_label'] );
		}

		if ( isset( $request['forgot_page_description'] ) ) {
			$plugin_settings->set( 'forgot_page_description', $request['forgot_page_description'] );
		}

		if ( isset( $request['set_password_page_label'] ) ) {
			$plugin_settings->set( 'set_password_page_label', $request['set_password_page_label'] );
		}

		if ( ! isset( $request['social_login'] ) ) {
			return;
		}

		if ( isset( $request['social_login']['enable'] ) ) {
			$plugin_settings->set( 'enable_social_login', wptravelengine_replace( $request['social_login']['enable'], true, 'yes', 'no' ) );
		}

		if ( isset( $request['social_login']['providers']['facebook']['enable'] ) ) {
			$plugin_settings->set( 'enable_facebook_login', wptravelengine_replace( $request['social_login']['providers']['facebook']['enable'], true, 'yes', 'no' ) );
		}

		if ( isset( $request['social_login']['providers']['facebook']['app_id'] ) ) {
			$plugin_settings->set( 'facebook_client_id', $request['social_login']['providers']['facebook']['app_id'] );
		}

		if ( isset( $request['social_login']['providers']['facebook']['app_secret'] ) ) {
			$plugin_settings->set( 'facebook_client_secret', $request['social_login']['providers']['facebook']['app_secret'] );
		}

		if ( isset( $request['social_login']['providers']['google']['enable'] ) ) {
			$plugin_settings->set( 'enable_google_login', wptravelengine_replace( $request['social_login']['providers']['google']['enable'], true, 'yes', 'no' ) );
		}

		if ( isset( $request['social_login']['providers']['google']['app_id'] ) ) {
			$plugin_settings->set( 'google_client_id', $request['social_login']['providers']['google']['app_id'] );
		}

		if ( isset( $request['social_login']['providers']['google']['app_secret'] ) ) {
			$plugin_settings->set( 'google_client_secret', $request['social_login']['providers']['google']['app_secret'] );
		}

		if ( isset( $request['social_login']['providers']['linkedIn']['enable'] ) ) {
			$plugin_settings->set( 'enable_linkedin_login', wptravelengine_replace( $request['social_login']['providers']['linkedIn']['enable'], true, 'yes', 'no' ) );
		}

		if ( isset( $request['social_login']['providers']['linkedIn']['app_id'] ) ) {
			$plugin_settings->set( 'linkedin_client_id', $request['social_login']['providers']['linkedIn']['app_id'] );
		}

		if ( isset( $request['social_login']['providers']['linkedIn']['app_secret'] ) ) {
			$plugin_settings->set( 'linkedin_client_secret', $request['social_login']['providers']['linkedIn']['app_secret'] );
		}
	}

	/**
	 * Process Performance Settings.
	 *
	 * @param WP_REST_Request $request Request object.
	 *
	 * @return void
	 * @since 6.2.0
	 */
	protected function set_performance_settings( WP_REST_Request $request ) {

		$plugin_settings = $this->plugin_settings;

		if ( isset( $request['enable_optimized_loading'] ) ) {
			$plugin_settings->set( 'enable_optimize_loading', wptravelengine_replace( $request['enable_optimized_loading'], true, 'yes', 'no' ) );
		}

		if ( ! isset( $request['lazy_loading'] ) ) {
			return;
		}

		if ( isset( $request['lazy_loading']['enable'] ) ) {
			$plugin_settings->set( 'enable_lazy_loading', wptravelengine_replace( $request['lazy_loading']['enable'], true, 'yes', 'no' ) );
		}

		if ( isset( $request['lazy_loading']['enable_map'] ) ) {
			$plugin_settings->set( 'enable_map_lazy_loading', wptravelengine_replace( $request['lazy_loading']['enable_map'], true, 'yes', 'no' ) );
		}

		if ( isset( $request['lazy_loading']['enable_image'] ) ) {
			$plugin_settings->set( 'enable_img_lazy_loading', wptravelengine_replace( $request['lazy_loading']['enable_image'], true, 'yes', 'no' ) );
		}
	}

	/**
	 * Process Trip Search Settings.
	 *
	 * @param WP_REST_Request $request Request object.
	 *
	 * @return void
	 */
	protected function set_trip_search_settings( WP_REST_Request $request ) {

		if ( ! isset( $request['trip_search'] ) ) {
			return;
		}

		$plugin_settings = $this->plugin_settings;

		if ( isset( $request['trip_search']['enable_destination'] ) ) {
			$plugin_settings->set( 'trip_search.destination', wptravelengine_replace( $request['trip_search']['enable_destination'], false, '1', '0' ) );
		}

		if ( isset( $request['trip_search']['enable_activities'] ) ) {
			$plugin_settings->set( 'trip_search.activities', wptravelengine_replace( $request['trip_search']['enable_activities'], false, '1', '0' ) );
		}

		if ( isset( $request['trip_search']['enable_trip_types'] ) ) {
			$plugin_settings->set( 'trip_search.trip_types', wptravelengine_replace( $request['trip_search']['enable_trip_types'], false, '1', '0' ) );
		}

		if ( isset( $request['trip_search']['enable_trip_tags'] ) ) {
			$plugin_settings->set( 'trip_search.trip_tag', wptravelengine_replace( $request['trip_search']['enable_trip_tags'], false, '1', '0' ) );
		}

		if ( isset( $request['trip_search']['enable_difficulties'] ) ) {
			$plugin_settings->set( 'trip_search.difficulty', wptravelengine_replace( $request['trip_search']['enable_difficulties'], false, '1', '0' ) );
		}

		if ( isset( $request['trip_search']['enable_duration'] ) ) {
			$plugin_settings->set( 'trip_search.duration', wptravelengine_replace( $request['trip_search']['enable_duration'], false, '1', '0' ) );
		}

		if ( isset( $request['trip_search']['enable_budget'] ) ) {
			$plugin_settings->set( 'trip_search.budget', wptravelengine_replace( $request['trip_search']['enable_budget'], false, '1', '0' ) );
		}

		if ( isset( $request['trip_search']['enable_fsd'] ) ) {
			$plugin_settings->set( 'trip_search.dates', wptravelengine_replace( $request['trip_search']['enable_fsd'], false, '1', '0' ) );
		}

		if ( isset( $request['trip_search']['enable_filter_by_section'] ) ) {
			$plugin_settings->set( 'trip_search.apply_in_search_page', wptravelengine_replace( $request['trip_search']['enable_filter_by_section'], false, '1', '0' ) );
		}
	}

	/**
	 * Process reCAPTCHA Settings.
	 *
	 * @param WP_REST_Request $request Request object.
	 *
	 * @since 6.7.0
	 * @return void
	 */
	protected function set_recaptcha_settings( WP_REST_Request $request ) {
		$plugin_settings = $this->plugin_settings;

		// Handle version selection
		if ( isset( $request['recaptcha']['version'] ) ) {
			$version = sanitize_text_field( $request['recaptcha']['version'] );
			if ( in_array( $version, array( 'v2', 'v3' ), true ) ) {
				$plugin_settings->set( 'recaptcha.version', $version );
			}
		}

		// Handle v2 settings
		if ( isset( $request['recaptcha']['v2']['site_key'] ) ) {
			$plugin_settings->set( 'recaptcha.v2.site_key', $request['recaptcha']['v2']['site_key'] );
		}

		if ( isset( $request['recaptcha']['v2']['secret_key'] ) ) {
			$plugin_settings->set( 'recaptcha.v2.secret_key', $request['recaptcha']['v2']['secret_key'] );
		}

		// Handle v3 settings
		if ( isset( $request['recaptcha']['v3']['site_key'] ) ) {
			$plugin_settings->set( 'recaptcha.v3.site_key', $request['recaptcha']['v3']['site_key'] );
		}

		if ( isset( $request['recaptcha']['v3']['secret_key'] ) ) {
			$plugin_settings->set( 'recaptcha.v3.secret_key', $request['recaptcha']['v3']['secret_key'] );
		}
	}
	/**
	 * Update settings.
	 *
	 * @param WP_REST_Request $request Request object.
	 *
	 * @since 6.2.0
	 */
	public function update_settings( WP_REST_Request $request ) {

		$this->is_valid_request( $request );

		if ( isset( $this->errors ) ) {
			return $this->errors;
		}

		$req_params = $request->get_json_params();

		foreach ( $this->sanitize_params_recursive( $req_params ) as $key => $value ) {
			$request->set_param( $key, $value );
		}

		$this->set_pages( $request );
		$this->set_trip_tabs( $request );
		$this->set_trip_settings( $request );
		$this->set_trip_infos( $request );
		// $this->set_admin_tabs( $request );
		$this->set_email_notification_details( $request );
		$this->set_email_settings( $request );
		$this->set_appearance_settings( $request );
		$this->set_display_tabs( $request );
		$this->set_currency_details( $request );
		$this->set_payment_gateway( $request );
		$this->set_dashboard_settings( $request );
		$this->set_performance_settings( $request );
		$this->set_trip_search_settings( $request );
		$this->set_recaptcha_settings( $request );
		$plugin_settings = $this->plugin_settings;

		do_action( 'wptravelengine_api_update_settings', $request, $this );

		if ( isset( $this->errors ) ) {
			return $this->errors;
		}

		if ( ! Translators::is_wpml_multilingual_active() ) {
			$plugin_settings->save();

			/**
			 * Placed here for backward compatibility.
			 *
			 * @deprecated 6.6.6
			 */
			do_action_deprecated(
				'wpte_after_save_global_settings_data',
				array( array( 'wp_travel_engine_settings' => $plugin_settings->get() ) ),
				'6.6.6',
				'wptravelengine_api_update_settings'
			);

		} else {
			Translators::save_wpml_translation( $plugin_settings->get(), '[wp_travel_engine_settings]' );
		}

		return $this->get_settings( $request );
	}

	/**
	 * Verifies request parameters and their types
	 * are in accordance with the schema's requirements.
	 *
	 * @param WP_REST_Request $request Request object.
	 *
	 * @return void
	 * @since 6.2.0
	 */
	public function is_valid_request( $request ) {

		if ( empty( $request->get_json_params() ) ) {
			$this->set_bad_request( 'invalid_request', 'Invalid request' );
			return;
		}

		$filter_recursive = function ( &$data, $schema ) use ( &$filter_recursive ) {

			foreach ( $data as $key => &$value ) {

				if ( ! isset( $schema[ $key ] ) || ( isset( $schema[ $key ]['enum'] ) && ! in_array( $value, $schema[ $key ]['enum'] ) ) ) {
					$this->set_bad_request( 'invalid_parameter', 'Invalid parameter: ' . $key );
					break;
				}

				if ( is_array( $value ) && ! empty( $value ) ) {

					$current_items = $schema[ $key ]['items'] ?? $schema['items'] ?? null;
					$current_props = $schema[ $key ]['properties'] ?? $schema['properties'] ?? null;

					if ( $current_items ) {
						// Checks if all items have same keys.
						if ( count(
							array_unique(
								array_map(
									function ( $item ) {
										$keys = array_keys( (array) $item );
										sort( $keys );

										return $keys;
									},
									$value
								),
								SORT_REGULAR
							)
						) === 1 ) {

							if ( isset( $current_items['type'] ) ) {

								$current_type = (array) $current_items['type'];
								array_push( $current_type, ( array_keys( $value ) !== $value ) ? 'array' : 'object' );

								foreach ( $value as $item ) {
									if ( ! in_array( gettype( $item ), $current_type ) ) {
										$this->set_bad_request( 'invalid_parameter', 'Invalid parameter: ' . $key );
										break;
									}
								}
							} else {

								$current_items = $current_items['properties'] ?? $current_items;

								foreach ( $value as $item ) {
									$filter_recursive( $item, $current_items );
								}
							}
						} else {
							$this->set_bad_request( 'invalid_parameter', 'Invalid parameter: ' . $key );
							break;
						}
					} elseif ( $current_props ) {
						$filter_recursive( $value, $current_props );
					} elseif ( isset( $schema[ $key ]['type'] ) ) {

						$schema[ $key ]['type'] = (array) $schema[ $key ]['type'];
						array_push( $schema[ $key ]['type'], ( array_keys( $value ) !== $value ) ? 'array' : 'object' );

						if ( ! in_array( gettype( $value ), $schema[ $key ]['type'], false ) ) {
							$this->set_bad_request( 'invalid_parameter', 'Invalid parameter: ' . $key );
							break;
						}
					} else {
						$this->set_bad_request( 'invalid_parameter', 'Invalid parameter: ' . $key );
						break;
					}
				} elseif ( isset( $schema[ $key ]['type'] ) ) {

					$schema[ $key ]['type'] = (array) $schema[ $key ]['type'];

					switch ( true ) {
						case is_array( $value ):
							$schema[ $key ]['type'] = array_merge( $schema[ $key ]['type'], array( 'array' ) );
							$schema[ $key ]['type'] = array_merge( $schema[ $key ]['type'], ( array_keys( $value ) !== $value ) ? array( 'array' ) : array( 'object' ) );
							// array_push( $schema[ $key ]['type'], ( array_keys( $value ) !== $value ) ? 'array' : 'object' );
							break;
						case is_numeric( $value ):
							if ( in_array( 'integer', $schema[ $key ]['type'], true ) ) {
								$value = (int) $value;
							} elseif ( in_array( 'float', $schema[ $key ]['type'], true ) ) {
								$value = (float) $value;
								array_push( $schema[ $key ]['type'], 'double' );
							}
							break;
					}

					if ( ! in_array( gettype( $value ), $schema[ $key ]['type'], false ) ) {
						$this->set_bad_request( 'invalid_parameter', 'Invalid parameter: ' . $key );
					}
				}
			}
		};

		$req_params = $request->get_json_params();
		$filter_recursive( $req_params, $this->get_schema() );

		return;
	}

	/**
	 * Sanitize Request Params.
	 *
	 * @param WP_REST_Request $request Request object.
	 *
	 * @return mixed
	 * @since 6.2.0
	 * @updated 6.2.3
	 */
	private function sanitize_params_recursive( $params ) {
		$sanitized_params = array();

		foreach ( $params as $key => $value ) {
			if ( is_array( $value ) ) {
				$sanitized_params[ $key ] = $this->sanitize_params_recursive( $value );
			} elseif ( is_int( $value ) ) {
				$sanitized_params[ $key ] = intval( $value );
			} elseif ( is_float( $value ) ) {
				$sanitized_params[ $key ] = floatval( $value );
			} elseif ( is_bool( $value ) ) {
				$sanitized_params[ $key ] = filter_var( $value, FILTER_VALIDATE_BOOLEAN );
			} elseif ( is_email( $value ) ) {
				$sanitized_params[ $key ] = sanitize_email( $value );
			} elseif ( filter_var( $value, FILTER_VALIDATE_URL ) ) {
				$sanitized_params[ $key ] = esc_url_raw( $value );
			} else {
				$sanitized_params[ $key ] = $value;
			}
		}

		return $sanitized_params;
	}

	/**
	 * Adds Bad Request '400 status' error to the error object.
	 *
	 * @param string $error_code Error code.
	 * @param string $error_message Error message to be displayed.
	 * @param string $error_param Error parameter.
	 *
	 * @return void
	 */
	public function set_bad_request( string $error_code = '', string $error_message = '', string $error_param = '' ): void {
		if ( ! isset( $this->errors ) ) {
			$this->errors = new WP_Error();
		}

		$this->errors->add(
			$error_code,
			$error_message ?? 'Bad Request.',
			array(
				'status'  => 400,
				'param'   => $error_param,
				'details' => strip_tags( $error_message ),
			)
		);
	}

	/**
	 * Has errors.
	 *
	 * @return bool
	 * @since 6.7.1
	 */
	public function has_errors(): bool {
		return isset( $this->errors ) && $this->errors->has_errors();
	}

	/**
	 * Returns Setting schema.
	 *
	 * @return array
	 * @since 6.2.0
	 */
	public function get_schema(): array {
		$_schema = array(
			'checkout_page'                    => array(
				'description' => __( 'Checkout Page', 'wp-travel-engine' ),
				'type'        => 'string',
			),
			'terms_and_conditions'             => array(
				'description' => __( 'Terms and Conditions', 'wp-travel-engine' ),
				'type'        => 'string',
			),
			'thank_you_page'                   => array(
				'description' => __( 'Thank You Page', 'wp-travel-engine' ),
				'type'        => 'string',
			),
			'confirmation_page'                => array(
				'description' => __( 'Confirmation Page', 'wp-travel-engine' ),
				'type'        => 'string',
			),
			'dashboard_page'                   => array(
				'description' => __( 'Dashboard Page', 'wp-travel-engine' ),
				'type'        => 'string',
			),
			'enquiry_thank_you_page'           => array(
				'description' => __( 'Enquiry Thank You Page', 'wp-travel-engine' ),
				'type'        => 'string',
			),
			'wishlist_page'                    => array(
				'description' => __( 'Wishlist Page', 'wp-travel-engine' ),
				'type'        => 'string',
			),
			'search_page'                      => array(
				'description' => __( 'Search Page', 'wp-travel-engine' ),
				'type'        => 'string',
			),
			'trip_tabs'                        => array(
				'description' => __( 'Trip Tabs', 'wp-travel-engine' ),
				'type'        => 'array',
				'items'       => array(
					'type'       => 'object',
					'properties' => array(
						'id'        => array(
							'description' => __( 'Tab ID', 'wp-travel-engine' ),
							'type'        => 'integer',
						),
						'name'      => array(
							'description' => __( 'Tab Name', 'wp-travel-engine' ),
							'type'        => 'string',
						),
						'field'     => array(
							'description' => __( 'Tab Field', 'wp-travel-engine' ),
							'type'        => 'string',
						),
						'icon'      => array(
							'description' => __( 'Tab Icon', 'wp-travel-engine' ),
							'type'        => 'object',
							'properties'  => array(
								'icon'     => array(
									'description' => __( 'Icon Name', 'wp-travel-engine' ),
									'type'        => 'string',
								),
								'view_box' => array(
									'description' => __( 'Icon View Box', 'wp-travel-engine' ),
									'type'        => 'string',
								),
								'path'     => array(
									'description' => __( 'Icon Path', 'wp-travel-engine' ),
									'type'        => 'string',
								),
							),
						),
						'enable'    => array(
							'description' => __( 'Tab Enabled or Not', 'wp-travel-engine' ),
							'type'        => 'boolean',
						),
						'trashable' => array(
							'description' => __( 'Tab Trashable or Not', 'wp-travel-engine' ),
							'type'        => 'boolean',
						),
					),
				),
			),
			'trip_info'                        => array(
				'description' => __( 'Trip Infos', 'wp-travel-engine' ),
				'type'        => 'array',
				'items'       => array(
					'type'       => 'object',
					'properties' => array(
						'id'          => array(
							'description' => __( 'Fact ID', 'wp-travel-engine' ),
							'type'        => 'string',
						),
						'name'        => array(
							'description' => __( 'Fact Name', 'wp-travel-engine' ),
							'type'        => 'string',
						),
						'placeholder' => array(
							'description' => __( 'Fact Placeholder', 'wp-travel-engine' ),
							'type'        => 'string',
						),
						'type'        => array(
							'description' => __( 'Fact Type', 'wp-travel-engine' ),
							'type'        => 'string',
						),
						'icon'        => array(
							'description' => __( 'Fact Icon', 'wp-travel-engine' ),
							'type'        => 'object',
							'properties'  => array(
								'icon'     => array(
									'description' => __( 'Icon Name', 'wp-travel-engine' ),
									'type'        => 'string',
								),
								'view_box' => array(
									'description' => __( 'Icon View Box', 'wp-travel-engine' ),
									'type'        => 'string',
								),
								'path'     => array(
									'description' => __( 'Icon Path', 'wp-travel-engine' ),
									'type'        => 'string',
								),
							),
						),
						'enable'      => array(
							'description' => __( 'Fact Enabled or Not', 'wp-travel-engine' ),
							'type'        => 'boolean',
							'NULL',
							'enum'        => array( true, false, 'NULL' ),
						),
						'options'     => array(
							'description' => __( 'Fact Options', 'wp-travel-engine' ),
							'type'        => 'array',
							'items'       => array(
								'type' => 'string',
							),
						),
						'trashable'   => array(
							'description' => __( 'Fact Trashable or Not', 'wp-travel-engine' ),
							'type'        => 'boolean',
						),
					),
				),
			),
			'highlights'                       => array(
				'description' => __( 'Highlights', 'wp-travel-engine' ),
				'type'        => 'array',
				'items'       => array(
					'type'       => 'object',
					'properties' => array(
						'title'       => array(
							'description' => __( 'Highlight Title', 'wp-travel-engine' ),
							'type'        => 'string',
						),
						'description' => array(
							'description' => __( 'Highlight Description', 'wp-travel-engine' ),
							'type'        => 'string',
						),
					),
				),
			),
			'pricing_type'                     => array(
				'description' => __( 'Pricing Type', 'wp-travel-engine' ),
				'type'        => 'array',
				'items'       => array(
					'type'       => 'object',
					'properties' => array(
						'label'       => array(
							'description' => __( 'Pricing Type', 'wp-travel-engine' ),
							'type'        => 'string',
						),
						'description' => array(
							'description' => __( 'Pricing Type Description', 'wp-travel-engine' ),
							'type'        => 'string',
						),
					),
				),
			),
			'email_notification'               => array(
				'description' => __( 'Email Notification', 'wp-travel-engine' ),
				'type'        => 'object',
				'properties'  => array(
					'admin'    => array(
						'description' => __( 'Admin Email Notification', 'wp-travel-engine' ),
						'type'        => 'object',
					),
					'customer' => array(
						'description' => __( 'Customer Email Notification', 'wp-travel-engine' ),
						'type'        => 'object',
					),
				),
			),
			'email_settings'                   => array(
				'description' => __( 'Email Settings', 'wp-travel-engine' ),
				'type'        => 'object',
				'properties'  => array(
					'enquiry_emails' => array(
						'description' => __( 'Enquiry Emails', 'wp-travel-engine' ),
						'type'        => 'array',
						'items'       => array(
							'type' => 'string',
						),
					),
					'sale_emails'    => array(
						'description' => __( 'Sale Emails', 'wp-travel-engine' ),
						'type'        => 'array',
						'items'       => array(
							'type' => 'string',
						),
					),
					'from_name'      => array(
						'description' => __( 'From Name', 'wp-travel-engine' ),
						'type'        => 'string',
					),
					'from'           => array(
						'description' => __( 'From Email', 'wp-travel-engine' ),
						'type'        => 'string',
					),
					'reply_to'       => array(
						'description' => __( 'Reply To Email', 'wp-travel-engine' ),
						'type'        => 'string',
					),
					'logo'           => array(
						'description' => __( 'Header Logo', 'wp-travel-engine' ),
						'type'        => 'object',
						'properties'  => array(
							'id'  => array(
								'description' => __( 'Attachment ID', 'wp-travel-engine' ),
								'type'        => 'integer',
							),
							'url' => array(
								'description' => __( 'Logo URL', 'wp-travel-engine' ),
								'type'        => 'string',
							),
							'alt' => array(
								'description' => __( 'Logo Alt Text', 'wp-travel-engine' ),
								'type'        => 'string',
							),
						),
					),
					'footer'         => array(
						'description' => __( 'Footer Text', 'wp-travel-engine' ),
						'type'        => 'string',
					),
				),
			),
			'appearance'                       => array(
				'description' => __( 'Appearance Settings', 'wp-travel-engine' ),
				'type'        => 'object',
				'properties'  => array(
					'primary_color'     => array(
						'description' => __( 'Primary Color', 'wp-travel-engine' ),
						'type'        => 'string',
					),
					'primary_color_rgb' => array(
						'description' => __( 'Primary Color RGB', 'wp-travel-engine' ),
						'type'        => 'string',
					),
					'discount_color'    => array(
						'description' => __( 'Discount Color', 'wp-travel-engine' ),
						'type'        => 'string',
					),
					'featured_color'    => array(
						'description' => __( 'Featured Color', 'wp-travel-engine' ),
						'type'        => 'string',
					),
					'icon_color'        => array(
						'description' => __( 'Icon Color', 'wp-travel-engine' ),
						'type'        => 'string',
					),
				),
			),
			// 'admin_email'                      => array(
			// 'description' => __( 'Admin Email', 'wp-travel-engine' ),
			// 'type'        => 'object',
			// 'properties'  => array(
			// 'email_addresses' => array(
			// 'description' => __( 'Admin Email Addresses', 'wp-travel-engine' ),
			// 'type'        => 'array',
			// 'items'       => array(
			// 'type' => 'string',
			// ),
			// ),
			// 'enable'          => array(
			// 'description' => __( 'Admin Email Notification Enabled or Not', 'wp-travel-engine' ),
			// 'type'        => 'boolean',
			// ),
			// ),
			// ),
			// 'admin_booking_notification'       => array(
			// 'description' => __( 'Admin Booking Email Notification', 'wp-travel-engine' ),
			// 'type'        => 'object',
			// 'properties'  => array(
			// 'subject'  => array(
			// 'description' => __( 'Admin Booking Email Subject', 'wp-travel-engine' ),
			// 'type'        => 'string',
			// ),
			// 'template' => array(
			// 'description' => __( 'Admin Booking Email Template', 'wp-travel-engine' ),
			// 'type'        => 'string',
			// ),
			// 'enable'   => array(
			// 'description' => __( 'Admin Booking Email Enabled or Not', 'wp-travel-engine' ),
			// 'type'        => 'boolean',
			// ),
			// ),
			// ),
			// 'admin_payment_notification'       => array(
			// 'description' => __( 'Admin Payment Email Notification', 'wp-travel-engine' ),
			// 'type'        => 'object',
			// 'properties'  => array(
			// 'subject'  => array(
			// 'description' => __( 'Admin Payment Email Subject', 'wp-travel-engine' ),
			// 'type'        => 'string',
			// ),
			// 'template' => array(
			// 'description' => __( 'Admin Payment Email Template', 'wp-travel-engine' ),
			// 'type'        => 'string',
			// ),
			// ),
			// ),
			// 'customer_receipt_details'         => array(
			// 'description' => __( 'Customer Receipt Details', 'wp-travel-engine' ),
			// 'type'        => 'object',
			// 'properties'  => array(
			// 'admin_name'          => array(
			// 'description' => __( 'Admin Name For Customer Receipt', 'wp-travel-engine' ),
			// 'type'        => 'string',
			// ),
			// 'admin_email_address' => array(
			// 'description' => __( 'Admin Email Address For Customer Receipt', 'wp-travel-engine' ),
			// 'type'        => 'string',
			// ),
			// ),
			// ),
			// 'customer_booking_notification'    => array(
			// 'description' => __( 'Customer Email Notification', 'wp-travel-engine' ),
			// 'type'        => 'object',
			// 'properties'  => array(
			// 'subject'  => array(
			// 'description' => __( 'Customer Email Subject', 'wp-travel-engine' ),
			// 'type'        => 'string',
			// ),
			// 'template' => array(
			// 'description' => __( 'Customer Email Template', 'wp-travel-engine' ),
			// 'type'        => 'string',
			// ),
			// 'enable'   => array(
			// 'description' => __( 'Customer Email Enabled or Not', 'wp-travel-engine' ),
			// 'type'        => 'boolean',
			// ),
			// ),
			// ),
			// 'customer_purchase_notification'   => array(
			// 'description' => __( 'Customer Purchase Email Notification', 'wp-travel-engine' ),
			// 'type'        => 'object',
			// 'properties'  => array(
			// 'subject'  => array(
			// 'description' => __( 'Customer Purchase Email Subject', 'wp-travel-engine' ),
			// 'type'        => 'string',
			// ),
			// 'template' => array(
			// 'description' => __( 'Customer Purchase Email Template', 'wp-travel-engine' ),
			// 'type'        => 'string',
			// ),
			// ),
			// ),
			'enquiry_enable'                   => array(
				'description' => __( 'Enquiry Form Enabled or Not', 'wp-travel-engine' ),
				'type'        => 'boolean',
			),
			'enquiry_custom_form'              => array(
				'description' => __( 'Custom Enquiry Form Enabled', 'wp-travel-engine' ),
				'type'        => 'object',
				'properties'  => array(
					'shortcode' => array(
						'description' => __( 'Custom Enquiry Form Shortcode', 'wp-travel-engine' ),
						'type'        => 'string',
					),
					'enable'    => array(
						'description' => __( 'Enquiry Form Enabled or Not', 'wp-travel-engine' ),
						'type'        => 'boolean',
					),
				),
			),
			'enquiry_form'                     => array(
				'description' => __( 'Enquiry Email', 'wp-travel-engine' ),
				'type'        => 'object',
				'properties'  => array(
					'email_addresses' => array(
						'description' => __( 'Enquiry Email Addresses', 'wp-travel-engine' ),
						'type'        => 'array',
						'items'       => array(
							'type' => 'string',
						),
					),
					'email_subject'   => array(
						'description' => __( 'Email Subject For Enquiry', 'wp-travel-engine' ),
						'type'        => 'string',
					),
					'notify_customer' => array(
						'description' => __( 'Customer Enquiry Notification Enabled', 'wp-travel-engine' ),
						'type'        => 'boolean',
					),
					// 'custom_form'     => array(
					// 'description' => __( 'Custom Enquiry Form Enabled', 'wp-travel-engine' ),
					// 'type'        => 'object',
					// 'properties'  => array(
					// 'shortcode' => array(
					// 'description' => __( 'Custom Enquiry Form Shortcode', 'wp-travel-engine' ),
					// 'type'        => 'string',
					// ),
					// 'enable'    => array(
					// 'description' => __( 'Enquiry Form Enabled or Not', 'wp-travel-engine' ),
					// 'type'        => 'boolean',
					// ),
					// ),
					// ),
					// 'enable'          => array(
					// 'description' => __( 'Enquiry Form Enabled or Not', 'wp-travel-engine' ),
					// 'type'        => 'boolean',
					// ),
					'powered_by_link' => array(
						'description' => __( 'Powered By Link Enabled or Not', 'wp-travel-engine' ),
						'type'        => 'boolean',
					),
				),
			),
			'card_new_layout'                  => array(
				'description' => __( 'New Trip Layout', 'wp-travel-engine' ),
				'type'        => 'object',
				'properties'  => array(
					'enable'                  => array(
						'description' => __( 'New Trip Layout Enabled or Not', 'wp-travel-engine' ),
						'type'        => 'boolean',
					),
					'enable_slider'           => array(
						'description' => __( 'Display Slider or Not', 'wp-travel-engine' ),
						'type'        => 'boolean',
					),
					'enable_featured_tag'     => array(
						'description' => __( 'Display Featured or Not', 'wp-travel-engine' ),
						'type'        => 'boolean',
					),
					'enable_wishlist'         => array(
						'description' => __( 'Display Wishlist or Not', 'wp-travel-engine' ),
						'type'        => 'boolean',
					),
					'enable_map'              => array(
						'description' => __( 'Display Map or Not', 'wp-travel-engine' ),
						'type'        => 'boolean',
					),
					'enable_excerpt'          => array(
						'description' => __( 'Display Trip Archive Excerpt or Not', 'wp-travel-engine' ),
						'type'        => 'boolean',
					),
					'enable_difficulty'       => array(
						'description' => __( 'Display Difficulty or Not', 'wp-travel-engine' ),
						'type'        => 'boolean',
					),
					'enable_tags'             => array(
						'description' => __( 'Display Tags or Not', 'wp-travel-engine' ),
						'type'        => 'boolean',
					),
					'enable_fsd'              => array(
						'description' => __( 'Display Next Departure Dates or Not', 'wp-travel-engine' ),
						'type'        => 'boolean',
					),
					'enable_available_months' => array(
						'description' => __( 'Display Available Months or Not', 'wp-travel-engine' ),
						'type'        => 'boolean',
					),
					'enable_available_dates'  => array(
						'description' => __( 'Display Available Dates or Not', 'wp-travel-engine' ),
						'type'        => 'boolean',
					),
				),
			),
			'trip_duration_label_on_card'      => array(
				'description' => __( 'Trip Duration Label', 'wp-travel-engine' ),
				'type'        => 'string',
				'enum'        => array( 'days', 'nights', 'both' ),
			),
			'show_modal_warning'               => array(
				'description' => __( 'Show Booking Modal Warning', 'wp-travel-engine' ),
				'type'        => 'boolean',
			),
			'modal_warning_message'            => array(
				'description' => __( 'Booking Modal Warning Message', 'wp-travel-engine' ),
				'type'        => 'string',
			),
			'trip_banner_layout'               => array(
				'description' => __( 'Trip Banner Layout', 'wp-travel-engine' ),
				'type'        => 'string',
			),
			'display_banner_fullwidth'         => array(
				'description' => __( 'Display Banner in Full Width', 'wp-travel-engine' ),
				'type'        => 'boolean',
			),
			'enable_booking_form'              => array(
				'description' => __( 'Booking Form Enabled or Not', 'wp-travel-engine' ),
				'type'        => 'boolean',
			),
			'pricing_section_layout'           => array(
				'description' => __( 'Pricing Section Layout', 'wp-travel-engine' ),
				'type'        => 'string',
			),
			'enable_compact_layout'            => array(
				'description' => __( 'Compact Layout Enabled or Not', 'wp-travel-engine' ),
				'type'        => 'boolean',
			),
			'inquiry_form'                     => array(
				'description' => __( 'Inquiry Info Form', 'wp-travel-engine' ),
				'type'        => 'object',
				'properties'  => array(
					'enable'    => array(
						'description' => __( 'Inquiry Info Form Enabled or Not', 'wp-travel-engine' ),
						'type'        => 'boolean',
					),
					'link_type' => array(
						'description' => __( 'Inquiry Info Form Link Type', 'wp-travel-engine' ),
						'type'        => 'string',
						'enum'        => array( 'default', 'custom' ),
					),
					'link'      => array(
						'description' => __( 'Inquiry Info Form Link', 'wp-travel-engine' ),
						'type'        => 'string',
					),
				),
			),
			'whatsapp'                         => array(
				'description' => __( 'WhatsApp', 'wp-travel-engine' ),
				'type'        => 'object',
				'properties'  => array(
					'enable' => array(
						'description' => __( 'WhatsApp Enabled or Not', 'wp-travel-engine' ),
						'type'        => 'boolean',
					),
					'number' => array(
						'description' => __( 'WhatsApp Number', 'wp-travel-engine' ),
						'type'        => 'string',
					),
				),
			),
			'enable_tabs_sticky'               => array(
				'description' => __( 'Tabs Sticky and Scrollable Enabled or Not', 'wp-travel-engine' ),
				'type'        => 'boolean',
			),
			'enable_booking_widget_sticky'     => array(
				'description' => __( 'Booking Widget Sticky and Scrollable Enabled or Not', 'wp-travel-engine' ),
				'type'        => 'boolean',
			),
			'related_trips'                    => array(
				'description' => __( 'Related Trips', 'wp-travel-engine' ),
				'type'        => 'object',
				'properties'  => array(
					'enable'  => array(
						'description' => __( 'Related Trips Enabled or Not', 'wp-travel-engine' ),
						'type'        => 'boolean',
					),
					'title'   => array(
						'description' => __( 'Related Trips Title', 'wp-travel-engine' ),
						'type'        => 'string',
					),
					'number'  => array(
						'description' => __( 'Number of Related Trips', 'wp-travel-engine' ),
						'type'        => 'integer',
					),
					'show_by' => array(
						'description' => __( 'Show Related Trips By', 'wp-travel-engine' ),
						'type'        => 'string',
					),
				),
			),
			'pricing_widget_enquiry_message'   => array(
				'description' => __( 'Pricing Widget Enquiry Message', 'wp-travel-engine' ),
				'type'        => 'string',
			),
			'related_trip_new_layout'          => array(
				'description' => __( 'New Trip Layout', 'wp-travel-engine' ),
				'type'        => 'object',
				'properties'  => array(
					'enable'                  => array(
						'description' => __( 'New Trip Layout Enabled or Not', 'wp-travel-engine' ),
						'type'        => 'boolean',
					),
					'enable_slider'           => array(
						'description' => __( 'Display Slider or Not', 'wp-travel-engine' ),
						'type'        => 'boolean',
					),
					'enable_featured_tag'     => array(
						'description' => __( 'Display Featured or Not', 'wp-travel-engine' ),
						'type'        => 'boolean',
					),
					'enable_wishlist'         => array(
						'description' => __( 'Display Wishlist or Not', 'wp-travel-engine' ),
						'type'        => 'boolean',
					),
					'enable_map'              => array(
						'description' => __( 'Display Map or Not', 'wp-travel-engine' ),
						'type'        => 'boolean',
					),
					'enable_excerpt'          => array(
						'description' => __( 'Display Related Trip Excerpt or Not', 'wp-travel-engine' ),
						'type'        => 'boolean',
					),
					'enable_difficulty'       => array(
						'description' => __( 'Display Difficulty or Not', 'wp-travel-engine' ),
						'type'        => 'boolean',
					),
					'enable_tags'             => array(
						'description' => __( 'Display Tags or Not', 'wp-travel-engine' ),
						'type'        => 'boolean',
					),
					'enable_fsd'              => array(
						'description' => __( 'Display Next Departure Dates or Not', 'wp-travel-engine' ),
						'type'        => 'boolean',
					),
					'enable_available_months' => array(
						'description' => __( 'Display Available Months or Not', 'wp-travel-engine' ),
						'type'        => 'boolean',
					),
					'enable_available_dates'  => array(
						'description' => __( 'Display Available Dates or Not', 'wp-travel-engine' ),
						'type'        => 'boolean',
					),
				),
			),
			'enable_trip_info'                 => array(
				'description' => __( 'Trip Info Enabled or Not', 'wp-travel-engine' ),
				'type'        => 'boolean',
			),
			'enable_trip_info_on_sidebar'      => array(
				'description' => __( 'Trip Info on Sidebar Enabled or Not', 'wp-travel-engine' ),
				'type'        => 'boolean',
			),
			'enable_trip_info_on_main_content' => array(
				'description' => __( 'Trip Info on Main Content Area Enabled or Not', 'wp-travel-engine' ),
				'type'        => 'boolean',
			),
			'enable_image_autoplay'            => array(
				'description' => __( 'Image Gallery Autoplay Enabled or Not', 'wp-travel-engine' ),
				'type'        => 'boolean',
			),
			'trip_duration_format'             => array(
				'description' => __( 'Trip Duration Format', 'wp-travel-engine' ),
				'type'        => 'string',
				'enum'        => array( 'days', 'days_and_nights' ),
			),
			'show_discounts_type'              => array(
				'description' => __( 'Show Discounts Type', 'wp-travel-engine' ),
				'type'        => 'string',
				'enum'        => array( 'percentage', 'fixed_amount' ),
			),
			'enable_image_in_gallery'          => array(
				'description' => __( 'Featured Image in Gallery Enabled or Not', 'wp-travel-engine' ),
				'type'        => 'boolean',
			),
			'enable_archive_title'             => array(
				'description' => __( 'Archive Title Enabled or Not', 'wp-travel-engine' ),
				'type'        => 'boolean',
			),
			'sort_trips_by'                    => array(
				'description' => __( 'Sort Trips By', 'wp-travel-engine' ),
				'type'        => 'string',
			),
			'trip_view_mode'                   => array(
				'description' => __( 'Trip View Mode', 'wp-travel-engine' ),
				'type'        => 'string',
				'enum'        => array( 'grid', 'list' ),
			),
			'featured_trips'                   => array(
				'description' => __( 'Featured Trips', 'wp-travel-engine' ),
				'type'        => 'object',
				'properties'  => array(
					'enable' => array(
						'description' => __( 'Featured Trips Enabled or Not', 'wp-travel-engine' ),
						'type'        => 'boolean',
					),
					'number' => array(
						'description' => __( 'Number of Featured Trips', 'wp-travel-engine' ),
						'type'        => 'integer',
					),
				),
			),
			'show_sidebar'                     => array(
				'description' => __( 'Show Sidebar', 'wp-travel-engine' ),
				'type'        => 'boolean',
			),
			'display_mode'                     => array(
				'description' => __( 'Display Mode', 'wp-travel-engine' ),
				'type'        => 'string',
				'enum'        => array( 'pagination', 'load_more' ),
			),
			'archives'                         => array(
				'description' => __( 'Customize Archives', 'wp-travel-engine' ),
				'type'        => 'object',
				'properties'  => array(
					'title'        => array(
						'description' => __( 'Archive Title', 'wp-travel-engine' ),
						'type'        => 'string',
					),
					'enable_title' => array(
						'description' => __( 'Archive title Enabled or Not', 'wp-travel-engine' ),
						'type'        => 'boolean',
					),
					'title_type'   => array(
						'description' => __( 'Archive Title Type', 'wp-travel-engine' ),
						'type'        => 'string',
						'enum'        => array( 'default', 'custom' ),
					),
					// 'enable_advance_search' => array(
					// 'description' => __( 'Advance Search Panel Enabled or Not', 'wp-travel-engine' ),
					// 'type'        => 'boolean',
					// ),
				),
			),
			// 'enable_criteria_filter'           => array(
			// 'description' => __( 'Criteria Filter Enabled or Not', 'wp-travel-engine' ),
			// 'type'        => 'boolean',
			// ),
			'custom_strings'                   => array(
				'description' => __( 'Custom Strings', 'wp-travel-engine' ),
				'type'        => 'array',
				'items'       => array(
					'type'       => 'object',
					'properties' => array(
						'initial_label'  => array(
							'description' => __( 'Initial Label', 'wp-travel-engine' ),
							'type'        => 'string',
						),
						'modified_label' => array(
							'description' => __( 'Modified Label', 'wp-travel-engine' ),
							'type'        => 'string',
						),
					),
				),
			),
			'currency_code'                    => array(
				'description' => __( 'Currency Code', 'wp-travel-engine' ),
				'type'        => 'string',
			),
			'currency_symbol'                  => array(
				'description' => __( 'Currency Symbol', 'wp-travel-engine' ),
				'type'        => 'string',
			),
			'amount_format'                    => array(
				'description' => __( 'Amount Format', 'wp-travel-engine' ),
				'type'        => 'string',
			),
			'decimal_digits'                   => array(
				'description' => __( 'Decimal Digits', 'wp-travel-engine' ),
				'type'        => 'integer',
			),
			'decimal_separator'                => array(
				'description' => __( 'Decimal Seperator', 'wp-travel-engine' ),
				'type'        => 'string',
			),
			'thousands_separator'              => array(
				'description' => __( 'Thousands Seperator', 'wp-travel-engine' ),
				'type'        => 'string',
			),
			'enable_emergency_contact'         => array(
				'description' => __( 'Emergency Contact Details Enabled or Not', 'wp-travel-engine' ),
				'type'        => 'boolean',
			),
			'enable_featured_image'            => array(
				'description' => __( 'Trip Featured Image Enabled or Not', 'wp-travel-engine' ),
				'type'        => 'boolean',
			),
			'enable_travellers_info'           => array(
				'description' => __( 'Travellers Information Enabled or Not', 'wp-travel-engine' ),
				'type'        => 'boolean',
			),
			'enable_multi_price_list'          => array(
				'description' => __( 'Multiple Price List Enabled or Not', 'wp-travel-engine' ),
				'type'        => 'boolean',
			),
			'taxonomy'                         => array(
				'description' => __( 'Taxonomy', 'wp-travel-engine' ),
				'type'        => 'object',
				'properties'  => array(
					'enable_image'          => array(
						'description' => __( 'Taxonomy Image Enabled or Not', 'wp-travel-engine' ),
						'type'        => 'boolean',
					),
					'enable_children_terms' => array(
						'description' => __( 'Taxonomy Children Terms Enabled or Not', 'wp-travel-engine' ),
						'type'        => 'boolean',
					),
				),
			),
			'enable_fse'                       => array(
				'description' => __( 'FSE Template Enabled or Not', 'wp-travel-engine' ),
				'type'        => 'boolean',
			),
			'booking_btn_label'                => array(
				'description' => __( 'Booking Button Label', 'wp-travel-engine' ),
				'type'        => 'string',
			),
			// 'per_person_format'  => [
			// 'description'   => __( 'Per Person Format', 'wp-travel-engine' ),
			// 'type'          => 'string',
			// ],
			'booking_confirmation_msg'         => array(
				'description' => __( 'Booking Confirmation Message', 'wp-travel-engine' ),
				'type'        => 'string',
			),
			'gdpr_msg'                         => array(
				'description' => __( 'GDPR Message', 'wp-travel-engine' ),
				'type'        => 'string',
			),
			'checkout_page_template'           => array(
				'description' => __( 'Checkout Page Layout', 'wp-travel-engine' ),
				'type'        => 'string',
				'enum'        => array( '1.0', '2.0' ),
			),
			'display_header_footer'            => array(
				'description' => __( 'Show Theme Header and Footer', 'wp-travel-engine' ),
				'type'        => 'boolean',
			),
			'display_travellers_info'          => array(
				'description' => __( 'Show Travellers Information', 'wp-travel-engine' ),
				'type'        => 'boolean',
			),
			'display_emergency_contact'        => array(
				'description' => __( 'Show Emergency Contact Details', 'wp-travel-engine' ),
				'type'        => 'boolean',
			),
			'traveller_emergency_details_form' => array(
				'description' => __( 'Traveller and Emergency Details Form', 'wp-travel-engine' ),
				'type'        => 'string',
				'enum'        => array( 'on_checkout', 'after_checkout' ),
			),
			'travellers_details_type'          => array(
				'description' => __( 'Travellers Details Type', 'wp-travel-engine' ),
				'type'        => 'string',
				'enum'        => array( 'all', 'only_lead' ),
			),
			'display_billing_details'          => array(
				'description' => __( 'Display Billing Details', 'wp-travel-engine' ),
				'type'        => 'boolean',
			),
			'show_additional_note'             => array(
				'description' => __( 'Show Additional Note', 'wp-travel-engine' ),
				'type'        => 'boolean',
			),
			'show_discount'                    => array(
				'description' => __( 'Show Discount', 'wp-travel-engine' ),
				'type'        => 'boolean',
			),
			'privacy_policy_msg'               => array(
				'description' => __( 'Privacy Policy Message', 'wp-travel-engine' ),
				'type'        => 'string',
			),
			'footer_copyright'                 => array(
				'description' => __( 'Footer Copyright', 'wp-travel-engine' ),
				'type'        => 'string',
			),
			'debug_mode'                       => array(
				'description' => __( 'Debug Mode', 'wp-travel-engine' ),
				'type'        => 'boolean',
			),
			'default_payment_gateway'          => array(
				'description' => __( 'Default Payment Gateway', 'wp-travel-engine' ),
				'type'        => 'string',
			),
			'payment_gateways'                 => array(
				'description' => __( 'Payment Gateways', 'wp-travel-engine' ),
				'type'        => 'array',
				'items'       => array(
					'type'       => 'object',
					'properties' => array(
						'id'     => array(
							'description' => __( 'Gateway ID', 'wp-travel-engine' ),
							'type'        => 'string',
						),
						'title'  => array(
							'description' => __( 'Gateway Title', 'wp-travel-engine' ),
							'type'        => 'string',
						),
						'enable' => array(
							'description' => __( 'Gateway Enabled or Not', 'wp-travel-engine' ),
							'type'        => 'boolean',
						),
					),
				),
			),
			'paypal'                           => array(
				'description' => __( 'Paypal Standard Payment Gateway', 'wp-travel-engine' ),
				'type'        => 'object',
				'properties'  => array(
					'paypal_id' => array(
						'description' => __( 'Paypal Id', 'wp-travel-engine' ),
						'type'        => 'string',
					),
				),
			),
			'direct_bank_transfer'             => array(
				'description' => __( 'Direct Bank Transfer Payment Gateway', 'wp-travel-engine' ),
				'type'        => 'object',
				'properties'  => array(
					'title'           => array(
						'description' => __( 'Direct Bank Transfer Title', 'wp-travel-engine' ),
						'type'        => 'string',
					),
					'description'     => array(
						'description' => __( 'Direct Bank Transfer Description', 'wp-travel-engine' ),
						'type'        => 'string',
					),
					'instructions'    => array(
						'description' => __( 'Direct Bank Transfer Instructions', 'wp-travel-engine' ),
						'type'        => 'string',
					),
					'account_details' => array(
						'description' => __( 'Account Details', 'wp-travel-engine' ),
						'type'        => 'array',
						'items'       => array(
							'type'       => 'object',
							'properties' => array(
								'account_name'   => array(
									'description' => __( 'Account Name', 'wp-travel-engine' ),
									'type'        => 'string',
								),
								'account_number' => array(
									'description' => __( 'Account Number', 'wp-travel-engine' ),
									'type'        => 'string',
								),
								'bank_name'      => array(
									'description' => __( 'Bank Name', 'wp-travel-engine' ),
									'type'        => 'string',
								),
								'short_code'     => array(
									'description' => __( 'Short Code', 'wp-travel-engine' ),
									'type'        => 'string',
								),
								'iban'           => array(
									'description' => __( 'IBAN', 'wp-travel-engine' ),
									'type'        => 'string',
								),
								'swift'          => array(
									'description' => __( 'SWIFT', 'wp-travel-engine' ),
									'type'        => 'string',
								),
							),
						),
					),
				),
			),
			'check_payments'                   => array(
				'description' => __( 'Check Payments Payment Gateway', 'wp-travel-engine' ),
				'type'        => 'object',
				'properties'  => array(
					'title'        => array(
						'description' => __( 'Check Payments Title', 'wp-travel-engine' ),
						'type'        => 'string',
					),
					'description'  => array(
						'description' => __( 'Check Payments Description', 'wp-travel-engine' ),
						'type'        => 'string',
					),
					'instructions' => array(
						'description' => __( 'Check Payments Instructions', 'wp-travel-engine' ),
						'type'        => 'string',
					),
				),
			),
			'tax'                              => array(
				'description' => __( 'Tax', 'wp-travel-engine' ),
				'type'        => 'object',
				'properties'  => array(
					'enable'       => array(
						'description' => __( 'Tax Enabled or Not', 'wp-travel-engine' ),
						'type'        => 'boolean',
					),
					'custom_label' => array(
						'description' => __( 'Tax Custom Label', 'wp-travel-engine' ),
						'type'        => 'string',
					),
					'type'         => array(
						'description' => __( 'Type of Tax Inclusion', 'wp-travel-engine' ),
						'type'        => 'string',
						'enum'        => array( 'inclusive', 'exclusive' ),
					),
					'percentage'   => array(
						'description' => __( 'Tax Percentage', 'wp-travel-engine' ),
						'type'        => 'float',
					),
				),
			),
			'generate_user_account'            => array(
				'description' => __( 'Generate User Account Automatically or Not', 'wp-travel-engine' ),
				'type'        => 'boolean',
			),
			'enable_account_registration'      => array(
				'description' => __( 'Whether to prevent customers from creating new accounts on the account page.', 'wp-travel-engine' ),
				'type'        => 'boolean',
			),
			'enable_booking_registration'      => array(
				'description' => __( 'Enable Registration for Booking', 'wp-travel-engine' ),
				'type'        => 'boolean',
			),
			'login_page_label'                 => array(
				'description' => __( 'Login Page Label', 'wp-travel-engine' ),
				'type'        => 'string',
			),
			'forgot_page_label'                => array(
				'description' => __( 'Forgot Page Label', 'wp-travel-engine' ),
				'type'        => 'string',
			),
			'forgot_page_description'          => array(
				'description' => __( 'Forgot Page Description', 'wp-travel-engine' ),
				'type'        => 'string',
			),
			'set_password_page_label'          => array(
				'description' => __( 'Set Password Page Label', 'wp-travel-engine' ),
				'type'        => 'string',
			),
			'generate'                         => array(
				'description' => __( 'User Account', 'wp-travel-engine' ),
				'type'        => 'object',
				'properties'  => array(
					'user_name'       => array(
						'description' => __( 'Generate Username from Customer Email or Not', 'wp-travel-engine' ),
						'type'        => 'boolean',
					),
					'secure_password' => array(
						'description' => __( 'Generate Secure Password or Not', 'wp-travel-engine' ),
						'type'        => 'boolean',
					),
				),
			),
			'social_login'                     => array(
				'description' => __( 'Social Login', 'wp-travel-engine' ),
				'type'        => 'object',
				'properties'  => array(
					'enable'    => array(
						'description' => __( 'Social Login Enabled or Not', 'wp-travel-engine' ),
						'type'        => 'boolean',
					),
					'providers' => array(
						'type' => 'object',
					),
				),
			),
			'trip_search'                      => array(
				'description' => __( 'Trip Search', 'wp-travel-engine' ),
				'type'        => 'object',
				'properties'  => array(
					'enable_destination'       => array(
						'description' => __( 'Destination Search Enabled or Not', 'wp-travel-engine' ),
						'type'        => 'boolean',
					),
					'enable_activities'        => array(
						'description' => __( 'Activities Search Enabled or Not', 'wp-travel-engine' ),
						'type'        => 'boolean',
					),
					'enable_trip_types'        => array(
						'description' => __( 'Trip Types Search Enabled or Not', 'wp-travel-engine' ),
						'type'        => 'boolean',
					),
					'enable_trip_tags'         => array(
						'description' => __( 'Trip Tags Search Enabled or Not', 'wp-travel-engine' ),
						'type'        => 'boolean',
					),
					'enable_difficulties'      => array(
						'description' => __( 'Difficulties Search Enabled or Not', 'wp-travel-engine' ),
						'type'        => 'boolean',
					),
					'enable_duration'          => array(
						'description' => __( 'Duration Search Enabled or Not', 'wp-travel-engine' ),
						'type'        => 'boolean',
					),
					'enable_budget'            => array(
						'description' => __( 'Budget Search Enabled or Not', 'wp-travel-engine' ),
						'type'        => 'boolean',
					),
					'enable_fsd'               => array(
						'description' => __( 'Fixed Starting Date Search Enabled or Not', 'wp-travel-engine' ),
						'type'        => 'boolean',
					),
					'enable_filter_by_section' => array(
						'description' => __( 'Filter By Section Enabled or Not', 'wp-travel-engine' ),
						'type'        => 'boolean',
					),
				),
			),
			'enable_optimized_loading'         => array(
				'description' => __( 'Optimized Loading Enabled or Not', 'wp-travel-engine' ),
				'type'        => 'boolean',
			),
			'lazy_loading'                     => array(
				'description' => __( 'Lazy Loading', 'wp-travel-engine' ),
				'type'        => 'object',
				'properties'  => array(
					'enable'       => array(
						'description' => __( 'Lazy Loading Enabled or Not', 'wp-travel-engine' ),
						'type'        => 'boolean',
					),
					'enable_map'   => array(
						'description' => __( 'Map Lazy Loading Enabled or Not', 'wp-travel-engine' ),
						'type'        => 'boolean',
					),
					'enable_image' => array(
						'description' => __( 'Image Lazy Loading Enabled or Not', 'wp-travel-engine' ),
						'type'        => 'boolean',
					),
				),
			),
		);

		return apply_filters( 'wptravelengine_settings_api_schema', $_schema, $this );
	}
}
