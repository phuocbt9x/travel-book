<?php

use WPTravelEngine\Helpers\Functions;

/**
 * Settings section of the plugin.
 *
 * Maintain a list of functions that are used for settings purposes of the plugin
 *
 * @package    WP Travel Engine
 * @subpackage WP_Travel_Engine/includes
 * @author    codewing
 */
class Wp_Travel_Engine_Settings {

	public function __construct() {

		if ( ! class_exists( '\WP_Travel_Engine_Form_Field' ) ) {
			include_once plugin_dir_path( WP_TRAVEL_ENGINE_FILE_PATH ) . 'includes/lib/wte-form-framework/class-wte-form-field.php';
		}

		$tabs = $this->get_global_settings_tabs();

		foreach ( $tabs as $tab_name => $tab_args ) {
			$tab_name = str_replace( 'wpte-', '', $tab_name );
			add_filter( 'wp_travel_engine_settings__properties', array( __CLASS__, "get_{$tab_name}_fields_schema" ) );
		}
	}

	public static function init_form_fields( $fields ) {
		if ( ! class_exists( '\WP_Travel_Engine_Form_Field' ) ) {
			include_once plugin_dir_path( WP_TRAVEL_ENGINE_FILE_PATH ) . 'includes/lib/wte-form-framework/class-wte-form-field.php';
		}

		$wte_form_field_instance = new \WP_Travel_Engine_Form_Field_Admin();

		$wte_form_field_instance->init( $fields );

		return $wte_form_field_instance;
	}

	public static function get_general_fields_schema( $properties ) {
		$properties['pages'] = array(
			'type'       => 'object',
			'properties' => array(
				'wp_travel_engine_place_order'          => array(
					'type' => 'string',
				),
				'wp_travel_engine_terms_and_conditions' => array(
					'type' => 'string',
				),
				'wp_travel_engine_thank_you'            => array(
					'type' => 'string',
				),
				'wp_travel_engine_confirmation_page'    => array(
					'type' => 'string',
				),
				'wp_travel_engine_dashboard_page'       => array(
					'type' => 'string',
				),
				'enquiry'                               => array(
					'type' => 'string',
				),
				'search'                                => array(
					'type' => 'string',
				),
				'wp_travel_engine_wishlist'             => array(
					'type' => 'string',
				),
			),
		);

		return $properties;
	}

	public static function get_emails_fields_schema( $properties ) {
		$properties['email'] = array(
			'type'       => 'object',
			'properties' => array(
				'emails'                              => array(
					'type' => 'string',
				),
				'disable_notif'                       => array(
					'type' => 'boolean',
				),
				'cust_notif'                          => array(
					'type' => 'boolean',
				),
				'booking_notification_subject_admin'  => array(
					'type' => 'string',
				),
				'booking_notification_template_admin' => array(
					'type' => 'string',
				),
				'sale_subject'                        => array(
					'type' => 'string',
				),
				'sales_wpeditor'                      => array(
					'type' => 'string',
				),
				'name'                                => array(
					'type' => 'string',
				),
			),
		);

		return $properties;
	}

	public static function get_miscellaneous_fields_schema( $properties ) {
		$properties['currency_code']         = array( 'type' => 'string' );
		$properties['currency_option']       = array( 'type' => 'string' );
		$properties['amount_display_format'] = array( 'type' => 'string' );
		$properties['decimal_digits']        = array( 'type' => 'number' );
		$properties['decimal_separator']     = array( 'type' => 'string' );
		$properties['thousands_separator']   = array( 'type' => 'string' );

		return $properties;
	}

	public static function get_payment_fields_schema( $properties ) {
		$properties['payment_debug']        = array( 'type' => 'boolean' );
		$properties['default_gateway']      = array( 'type' => 'string' );
		$properties['default_gateway']      = array( 'type' => 'string' );
		$properties['booking_only']         = array( 'type' => 'boolean' );
		$properties['paypal_payment']       = array( 'type' => 'boolean' );
		$properties['paypal_payment']       = array( 'type' => 'boolean' );
		$properties['direct_bank_transfer'] = array( 'type' => 'boolean' );
		$properties['check_payments']       = array( 'type' => 'boolean' );

		return $properties;
	}

	public static function get_extensions_fields_schema( $properties ) {
		$properties['trip_search'] = array(
			'type'       => 'object',
			'properties' => array(
				'destination'          => array(
					'type' => 'boolean',
				),
				'activities'           => array(
					'type' => 'boolean',
				),
				'duration'             => array(
					'type' => 'boolean',
				),
				'budget'               => array(
					'type' => 'boolean',
				),
				'apply_in_search_page' => array(
					'type' => 'boolean',
				),
			),
		);

		return $properties;
	}

	public static function get_dashboard_fields_schema( $properties ) {
		$properties['enable_checkout_customer_registration']    = array( 'type' => 'boolean' );
		$properties['disable_my_account_customer_registration'] = array( 'type' => 'boolean' );
		$properties['generate_username_from_email']             = array( 'type' => 'boolean' );
		$properties['generate_user_password']                   = array( 'type' => 'boolean' );

		return $properties;
	}

	public static function get_default_option_schema() {
		$properties = apply_filters( 'wp_travel_engine_settings__properties', array() );

		return array(
			'type'       => 'object',
			'properties' => $properties,
		);
	}

	public static function get_page_settings_fields() {
		$options = get_option( 'wp_travel_engine_settings', array() );

		$pages = get_pages();

		$pages         = array_column( $pages, 'post_title', 'ID' );
		$wishlist_page = wptravelengine_get_page_by_title( 'Wishlist' );
		if ( isset( $wishlist_page ) ) {
			$wishlist_page_id = $wishlist_page->ID;
		} else {
			$wishlist_page_id = '';
		}
		$page_settings_fields = array(
			'wte-checkout-page'     => array(
				'label'         => __( 'Checkout Page', 'wp-travel-engine' ),
				'label_class'   => 'wpte-field-label',
				'wrapper_class' => 'wpte-field wpte-select wpte-floated',
				'field_label'   => __( 'Checkout Page', 'wp-travel-engine' ),
				'type'          => 'select',
				'options'       => $pages,
				'class'         => 'wpte-enhanced-select',
				'name'          => 'wp_travel_engine_settings[pages][wp_travel_engine_place_order]',
				'selected'      => isset( $options['pages']['wp_travel_engine_place_order'] ) ? esc_attr( $options['pages']['wp_travel_engine_place_order'] ) : '',
				'default'       => isset( $options['pages']['wp_travel_engine_place_order'] ) ? esc_attr( $options['pages']['wp_travel_engine_place_order'] ) : '',
				'tooltip'       => __( 'This is the checkout page where buyers will complete their order. The <b>[WP_TRAVEL_ENGINE_PLACE_ORDER]</b> shortcode must be on this page.', 'wp-travel-engine' ),
			),
			'wte-terms-page'        => array(
				'label'         => __( 'Terms and Conditions', 'wp-travel-engine' ),
				'label_class'   => 'wpte-field-label',
				'wrapper_class' => 'wpte-field wpte-select wpte-floated',
				'field_label'   => __( 'Terms and Conditions', 'wp-travel-engine' ),
				'type'          => 'select',
				'options'       => $pages,
				'class'         => 'wpte-enhanced-select',
				'name'          => 'wp_travel_engine_settings[pages][wp_travel_engine_terms_and_conditions]',
				'selected'      => isset( $options['pages']['wp_travel_engine_terms_and_conditions'] ) ? esc_attr( $options['pages']['wp_travel_engine_terms_and_conditions'] ) : '',
				'default'       => isset( $options['pages']['wp_travel_engine_terms_and_conditions'] ) ? esc_attr( $options['pages']['wp_travel_engine_terms_and_conditions'] ) : '',
				'tooltip'       => __( 'This is the terms and conditions page where trip bookers will see the terms and conditions for booking.', 'wp-travel-engine' ),
			),
			'wte-thankyou-page'     => array(
				'label'         => __( 'Thank You Page', 'wp-travel-engine' ),
				'label_class'   => 'wpte-field-label',
				'wrapper_class' => 'wpte-field wpte-select wpte-floated',
				'field_label'   => __( 'Thank You Page', 'wp-travel-engine' ),
				'type'          => 'select',
				'options'       => $pages,
				'class'         => 'wpte-enhanced-select',
				'name'          => 'wp_travel_engine_settings[pages][wp_travel_engine_thank_you]',
				'selected'      => isset( $options['pages']['wp_travel_engine_thank_you'] ) ? esc_attr( $options['pages']['wp_travel_engine_thank_you'] ) : '',
				'default'       => isset( $options['pages']['wp_travel_engine_thank_you'] ) ? esc_attr( $options['pages']['wp_travel_engine_thank_you'] ) : '',
				'tooltip'       => __( 'This is the thank you page where trip bookers will get the payment confirmation message. The <b>[WP_TRAVEL_ENGINE_THANK_YOU]</b> shortcode must be on this page.', 'wp-travel-engine' ),
			),
			'wte-confirmation-page' => array(
				'label'         => __( 'Confirmation Page', 'wp-travel-engine' ),
				'label_class'   => 'wpte-field-label',
				'wrapper_class' => 'wpte-field wpte-select wpte-floated',
				'field_label'   => __( 'Confirmation Page', 'wp-travel-engine' ),
				'type'          => 'select',
				'options'       => $pages,
				'class'         => 'wpte-enhanced-select',
				'name'          => 'wp_travel_engine_settings[pages][wp_travel_engine_confirmation_page]',
				'selected'      => isset( $options['pages']['wp_travel_engine_confirmation_page'] ) ? esc_attr( $options['pages']['wp_travel_engine_confirmation_page'] ) : '',
				'default'       => isset( $options['pages']['wp_travel_engine_confirmation_page'] ) ? esc_attr( $options['pages']['wp_travel_engine_confirmation_page'] ) : '',
				'tooltip'       => __( 'This is the confirmation page where trip bookers will fill the full form of the travellers. The <b>[WP_TRAVEL_ENGINE_BOOK_CONFIRMATION]</b> shortcode must be on this page.', 'wp-travel-engine' ),
			),
			'wte-dashboard-page'    => array(
				'label'         => __( 'User Dashboard Page', 'wp-travel-engine' ),
				'label_class'   => 'wpte-field-label',
				'wrapper_class' => 'wpte-field wpte-select wpte-floated',
				'field_label'   => __( 'User Dashboard Page', 'wp-travel-engine' ),
				'type'          => 'select',
				'options'       => $pages,
				'class'         => 'wpte-enhanced-select',
				'name'          => 'wp_travel_engine_settings[pages][wp_travel_engine_dashboard_page]',
				'selected'      => isset( $options['pages']['wp_travel_engine_dashboard_page'] ) ? esc_attr( $options['pages']['wp_travel_engine_dashboard_page'] ) : wp_travel_engine_get_page_id( 'my-account' ),
				'default'       => isset( $options['pages']['wp_travel_engine_dashboard_page'] ) ? esc_attr( $options['pages']['wp_travel_engine_dashboard_page'] ) : wp_travel_engine_get_page_id( 'my-account' ),
				'tooltip'       => __( 'This is the dasbhboard page that lets your users to login and interact to bookings from frontend. The <b>[wp_travel_engine_dashboard]</b> shortcode must be on this page.', 'wp-travel-engine' ),
			),
			'wte-enquiry-thank-you' => array(
				'label'         => __( 'Enquiry Thank You Page', 'wp-travel-engine' ),
				'label_class'   => 'wpte-field-label',
				'wrapper_class' => 'wpte-field wpte-select wpte-floated',
				'field_label'   => __( 'Enquiry Thank You Page', 'wp-travel-engine' ),
				'type'          => 'select',
				'options'       => $pages,
				'class'         => 'wpte-enhanced-select',
				'name'          => 'wp_travel_engine_settings[pages][enquiry]',
				'selected'      => isset( $options['pages']['enquiry'] ) ? esc_attr( $options['pages']['enquiry'] ) : '',
				'default'       => isset( $options['pages']['enquiry'] ) ? esc_attr( $options['pages']['enquiry'] ) : '',
				'tooltip'       => __( 'This is the thankyou page where user will be redirected after successful enquiry.', 'wp-travel-engine' ),
			),
			'wte-wishlist-page'     => array(
				'label'         => __( 'Wishlist Page', 'wp-travel-engine' ),
				'label_class'   => 'wpte-field-label',
				'wrapper_class' => 'wpte-field wpte-select wpte-floated',
				'field_label'   => __( 'Wishlist Page', 'wp-travel-engine' ),
				'type'          => 'select',
				'options'       => $pages,
				'class'         => 'wpte-enhanced-select',
				'name'          => 'wp_travel_engine_settings[pages][wp_travel_engine_wishlist]',
				'selected'      => isset( $options['pages']['wp_travel_engine_wishlist'] ) ? esc_attr( $options['pages']['wp_travel_engine_wishlist'] ) : $wishlist_page_id,
				'default'       => isset( $options['pages']['wp_travel_engine_wishlist'] ) ? esc_attr( $options['pages']['wp_travel_engine_wishlist'] ) : $wishlist_page_id,
				'tooltip'       => __( 'This is the wishlist page where user can check out the trips they have wishlisted. The <b>[WP_TRAVEL_ENGINE_WISHLIST]</b> shortcode must be on this page.', 'wp-travel-engine' ),
			),
		);

		return apply_filters( 'wpte_global_page_options', $page_settings_fields );
	}

	public static function get_booking_notifications_fields() {
		$wp_travel_engine_settings = get_option( 'wp_travel_engine_settings', array() );
		$fields                    = array(
			'sale_notification_emails'           => array(
				'field_label'   => __( 'Sale Notification Emails', 'wp-travel-engine' ),
				'wrapper_class' => 'wpte-field wpte-textarea wpte-floated',
				'type'          => 'text',
				'name'          => 'wp_travel_engine_settings[email][emails]',
				'default'       => wte_array_get( $wp_travel_engine_settings, 'email.emails', get_option( 'admin_email' ) ),
				'tooltip'       => __( 'Enter the email address(es) that should receive a notification anytime a sale is made, separated by comma (,) and no spaces.', 'wp-travel-engine' ),
			),
			'disable_admin_notification'         => array(
				'field_label'   => __( 'Disable Admin Notification', 'wp-travel-engine' ),
				'wrapper_class' => 'wpte-field wpte-checkbox advance-checkbox',
				'type'          => 'checkbox',
				'default'       => isset( $wp_travel_engine_settings['email']['disable_notif'] ) ? $wp_travel_engine_settings['email']['disable_notif'] : '',
				'id'            => 'disable-admin-notification',
				'name'          => 'wp_travel_engine_settings[email][disable_notif]',
				'tooltip'       => __( 'Turn this on if you do not want to receive sales notification emails.', 'wp-travel-engine' ),
			),
			// 'enable_customer_notification'       => array(
			// 'field_label'   => __( 'Enable Customer Enquiry Notification', 'wp-travel-engine' ),
			// 'wrapper_class' => 'wpte-field wpte-checkbox advance-checkbox',
			// 'type'          => 'checkbox',
			// 'default'       => isset( $wp_travel_engine_settings['email']['cust_notif'] ) ? $wp_travel_engine_settings['email']['cust_notif'] : '',
			// 'id'            => 'enable-customer-enquiry-notification',
			// 'name'          => 'wp_travel_engine_settings[email][cust_notif]',
			// 'tooltip'       => __( 'Turn this on if you want to send enquiry notification emails to customer as well.', 'wp-travel-engine' ),
			// ),
			'disable_admin_booking_notification' => array(
				'field_label'   => __( 'Disable Booking Notification', 'wp-travel-engine' ),
				'wrapper_class' => 'wpte-field wpte-checkbox advance-checkbox',
				'type'          => 'checkbox',
				'default'       => $wp_travel_engine_settings['email']['disable_booking_notification'] ?? '',
				'id'            => 'disable-booking-notification',
				'name'          => 'wp_travel_engine_settings[email][disable_booking_notification]',
				'tooltip'       => __( 'Check this box to receive only payment notification emails and not booking notifications.', 'wp-travel-engine' ),
			),
			'booking_notification_subject_admin' => array(
				'field_label'   => __( 'Booking Notification Subject', 'wp-travel-engine' ),
				'wrapper_class' => 'wpte-field wpte-text wpte-floated',
				'type'          => 'text',
				'name'          => 'wp_travel_engine_settings[email][booking_notification_subject_admin]',
				'default'       => \WTE_Booking_Emails::get_subject( 'order', 'admin' ),
				'tooltip'       => __( 'Enter the booking subject for the booking notification email. Available Tags: {booking_id}, {payment_id}, {sitename}, {name}, {fullname}', 'wp-travel-engine' ),
			),
		);

		return apply_filters( 'wte_booking_notifications_fields', $fields );
	}

	public static function get_currency_fields() {
		$wp_travel_engine_settings = wptravelengine_settings()->get();
		$currencies                = Functions::get_currencies();
		$code                      = 'USD';
		if ( ! empty( $wp_travel_engine_settings['currency_code'] ) ) {
			$code = $wp_travel_engine_settings['currency_code'];
		}
		$options = array( '' => __( 'Choose a currency&hellip;', 'wp-travel-engine' ) );
		foreach ( $currencies as $key => $name ) {
			$options[ $key ] = $name . ' (' . Functions::currency_symbol_by_code( $key ) . ')';
		}

		$currency_options = array(
			'symbol' => 'Currency Symbol ( e.g. $ )',
			'code'   => 'Currency Code ( e.g. USD )',
		);
		$currency_option  = isset( $wp_travel_engine_settings['currency_option'] ) ? esc_attr( $wp_travel_engine_settings['currency_option'] ) : 'symbol';

		$amount_display_format = wte_array_get( $wp_travel_engine_settings, 'amount_display_format', '%CURRENCY_SYMBOL%%FORMATED_AMOUNT%' );
		$thousands_separator   = isset( $wp_travel_engine_settings['thousands_separator'] ) && $wp_travel_engine_settings['thousands_separator'] != '' ? ( $wp_travel_engine_settings['thousands_separator'] ) : '';
		$decimal_separator     = wte_array_get( $wp_travel_engine_settings, 'decimal_separator', '.' );
		$decimal_digits        = wte_array_get( $wp_travel_engine_settings, 'decimal_digits', '0' );

		$fields = array(
			'currency_code'         => array(
				'type'          => 'select',
				'wrapper_class' => 'wpte-field wpte-select wpte-floated',
				'class'         => 'wpte-enhanced-select',
				'field_label'   => __( 'Payment Currency', 'wp-travel-engine' ),
				'name'          => 'wp_travel_engine_settings[currency_code]',
				'default'       => $code,
				'options'       => $options,
				'tooltip'       => __( 'Choose the base currency for the trips pricing.', 'wp-travel-engine' ),
			),
			'currency_option'       => array(
				'type'          => 'select',
				'wrapper_class' => 'wpte-field wpte-select wpte-floated',
				'class'         => 'wpte-enhanced-select',
				'field_label'   => __( 'Display Currency Symbol or Code', 'wp-travel-engine' ),
				'name'          => 'wp_travel_engine_settings[currency_option]',
				'default'       => $currency_option,
				'options'       => $currency_options,
				'tooltip'       => __( 'Display Currency Symbol or Code in Trip Listing Templates.', 'wp-travel-engine' ),
			),
			'amount_display_format' => array(
				'type'          => 'text',
				'wrapper_class' => 'wpte-field wpte-floated',
				'field_label'   => __( 'Amount Display Format', 'wp-travel-engine' ),
				'name'          => 'wp_travel_engine_settings[amount_display_format]',
				'default'       => $amount_display_format,
				'tooltip'       => sprintf(
					__( 'Amount Display format. Available tags: %s', 'wp-travel-engine' ),
					'<code>%CURRENCY_CODE%</code>, <code>%CURRENCY_SYMBOL%</code>, <code>%AMOUNT%</code>, <code>%FORMATED_AMOUNT%</code>'
				),
			),
			'decimal_digits'        => array(
				'type'          => 'number',
				'wrapper_class' => 'wpte-field wpte-floated',
				'field_label'   => __( 'Decimal digits.', 'wp-travel-engine' ),
				'name'          => 'wp_travel_engine_settings[decimal_digits]',
				'default'       => $decimal_digits,
				'tooltip'       => __( 'Number of Decimal digits.', 'wp-travel-engine' ),
			),
			'decimal_separator'     => array(
				'type'          => 'text',
				'wrapper_class' => 'wpte-field wpte-floated',
				'field_label'   => __( 'Decimal Separator', 'wp-travel-engine' ),
				'name'          => 'wp_travel_engine_settings[decimal_separator]',
				'default'       => $decimal_separator,
				'tooltip'       => __( 'Symbol to use for decimal separator in Trip Price.', 'wp-travel-engine' ),
			),
			'thousands_separator'   => array(
				'type'          => 'text',
				'wrapper_class' => 'wpte-field wpte-floated',
				'field_label'   => __( 'Thousands Separator', 'wp-travel-engine' ),
				'name'          => 'wp_travel_engine_settings[thousands_separator]',
				'default'       => $thousands_separator,
				'tooltip'       => __( 'Symbol to use for thousands separator in Trip Price.', 'wp-travel-engine' ),
			),

		);

		return $fields;
	}

	public static function get_settings() {
		$default_settings = array();
		$settings         = (array) get_option( 'wp_travel_engine_settings', array() );

		return array_merge( $default_settings, $settings );
	}

	public static function get_global_settings_schema() {
	}

	public static function get_sanitized_posted_data( $posted_data ) {
		$special_fields = array(
			'type'       => 'array',
			'properties' => array(
				'wp_travel_engine_settings' => array(
					'type'       => 'array',
					'properties' => array(
						'trip_facts'       => array(
							'type'       => 'array',
							'properties' => array(
								'select_options' => array(
									'type'  => 'array',
									'items' => array(
										'type' => 'string',
										'sanitize_callback' => 'sanitize_textarea_field',
									),
								),
							),
						),
						'confirmation_msg' => array(
							'type'              => 'string',
							'sanitize_callback' => 'wp_kses_post',
						),
						'gdpr_msg'         => array(
							'type'              => 'string',
							'sanitize_callback' => 'wp_kses_post',
						),
						'email'            => array(
							'type'       => 'array',
							'properties' => array(
								'booking_notification_template_admin' => array(
									'type'              => 'string',
									'sanitize_callback' => 'wp_kses_post',
								),
								'sales_wpeditor'    => array(
									'type'              => 'string',
									'sanitize_callback' => 'wp_kses_post',
								),
								'booking_notification_template_customer' => array(
									'type'              => 'string',
									'sanitize_callback' => 'wp_kses_post',
								),
								'purchase_wpeditor' => array(
									'type'              => 'string',
									'sanitize_callback' => 'wp_kses_post',
								),
							),
						),
						'bank_transfer'    => array(
							'type'       => 'array',
							'properties' => array(
								'description' => array(
									'type'              => 'string',
									'sanitize_callback' => 'wp_kses_post',
								),
								'instruction' => array(
									'type'              => 'string',
									'sanitize_callback' => 'sanitize_textarea_field',
								),
							),
						),
						'check_payment'    => array(
							'type'       => 'array',
							'properties' => array(
								'description' => array(
									'type'              => 'string',
									'sanitize_callback' => 'sanitize_textarea_field',
								),
								'instruction' => array(
									'type'              => 'string',
									'sanitize_callback' => 'sanitize_textarea_field',
								),
							),
						),
					),
				),
			),
		);

		$sanitized_data = wte_input_clean( $posted_data, $special_fields );

		return $sanitized_data;
	}

	/**
	 * Save Settings.
	 *
	 * @since 5.3.1
	 */
	public static function save_settings() {

		if ( isset( $_POST['nonce'] ) ) {
			if ( ! wp_verify_nonce( wte_clean( wp_unslash( $_POST['nonce'] ) ), 'wpte_global_tabs_save_data' ) ) {
				wp_send_json_error( array( 'message' => __( 'Security Error! Nonce verification failed', 'wp-travel-engine' ) ) );
				die;
			}
		}

		$global_settings_saved = self::get_settings();

		$posted_data = self::get_sanitized_posted_data( $_POST );

		if ( isset( $posted_data['wp_travel_engine_settings'] ) ) {
			$global_settings_to_save = (array) $posted_data['wp_travel_engine_settings'];

			// Merge data.
			$global_settings_merged_with_saved = array_merge( $global_settings_saved, $global_settings_to_save );

			$global_checkboxes_array = array(
				'wpte-emails' => array(
					'email' => 'disable_notif',
					'email' => 'cust_notif',
				),
			);

			$global_settings_checkboxes = apply_filters( 'wp_travel_engine_global_settings_checkboxes', $global_checkboxes_array );

			$active_tab = $posted_data['tab'];

			if ( isset( $global_settings_checkboxes[ $active_tab ] ) ) {
				foreach ( $global_settings_checkboxes[ $active_tab ] as $key => $checkbox ) {
					if ( isset( $global_settings_merged_with_saved[ $key ][ $checkbox ] ) && ! isset( $global_settings_to_save[ $key ][ $checkbox ] ) ) {
						unset( $global_settings_merged_with_saved[ $key ][ $checkbox ] );
					}
				}
			}

			$add_checkbox = array(
				'wpte-miscellaneous' => array(
					'booking',
					'enquiry',
					'emergency',
					'feat_img',
					'travelers_information',
					'tax_images',
					'show_multiple_pricing_list_disp',
					'show_excerpt',
				),
				'wpte-payment'       => array(
					'payment_debug',
				),
				'wpte-dashboard'     => array(
					'enable_checkout_customer_registration',
					'disable_my_account_customer_registration',
					'generate_username_from_email',
					'generate_user_password',
				),
			);

			$add_checkbox = apply_filters( 'wpte_global_add_checkboxes', $add_checkbox );

			if ( isset( $add_checkbox[ $active_tab ] ) ) {
				foreach ( $add_checkbox[ $active_tab ] as $checkbox ) {
					if ( isset( $global_settings_merged_with_saved[ $checkbox ] ) && ! isset( $global_settings_to_save[ $checkbox ] ) ) {
						unset( $global_settings_merged_with_saved[ $checkbox ] );
					}
				}
			}

			if ( 'wpte-payment' === $active_tab ) {
				// Payment checkboxes.
				$payment_gateways = wp_travel_engine_get_available_payment_gateways();

				foreach ( $payment_gateways as $key => $gateway ) {
					if ( isset( $global_settings_merged_with_saved[ $key ] ) && ! isset( $global_settings_to_save[ $key ] ) ) {
						unset( $global_settings_merged_with_saved[ $key ] );
					}
				}
			}

			/**
			 * Save Trip Sort By.
			 *
			 * @since 5.5.7
			 */
			if ( isset( $_REQUEST['wptravelengine_trip_sort_by'] ) ) {
				update_option( 'wptravelengine_trip_sort_by', sanitize_text_field( wp_unslash( $_REQUEST['wptravelengine_trip_sort_by'] ) ) );
			}
			if ( isset( $_REQUEST['wptravelengine_trip_view_mode'] ) ) {
				update_option( 'wptravelengine_trip_view_mode', sanitize_text_field( wp_unslash( $_REQUEST['wptravelengine_trip_view_mode'] ) ) );
			}

			update_option( 'wp_travel_engine_settings', wp_unslash( $global_settings_merged_with_saved ) );

			if ( isset( $posted_data['wp_travel_engine_settings']['pages']['search'] ) ) {
				update_option( 'wp_travel_engine_search_page_id', $posted_data['wp_travel_engine_settings']['pages']['search'] );
			}

			/**
			 * Hook for addons global settings.
			 */
			do_action( 'wpte_after_save_global_settings_data', $posted_data );

			wp_send_json_success( array( 'message' => 'Settings Saved Successfully.' ) );

		}
	}

	/**
	 * Settings Tabs.
	 *
	 * @since    1.0.0
	 */
	public function get_global_settings_tabs() {

		$global_tabs = wp_cache_get( 'wptravelengine_settings_tabs', 'wptravelengine' );

		if ( $global_tabs ) {
			return $global_tabs;
		}

		// Global Tabs.
		$global_tabs = array(
			'wpte-general'       => array(
				'label'    => __( 'General', 'wp-travel-engine' ),
				'sub_tabs' => array(
					'page_settings' => array(
						'label'        => __( 'Page Settings', 'wp-travel-engine' ),
						'content_path' => plugin_dir_path( __FILE__ ) . 'backend/settings/general/page-settings.php',
						'current'      => true,
						'schema'       => array(
							'pages' => array(
								'type'       => 'object',
								'properties' => array(
									'wp_travel_engine_place_order' => array(
										'type' => 'string',
									),
									'wp_travel_engine_terms_and_conditions' => array(
										'type' => 'string',
									),
									'wp_travel_engine_thank_you' => array(
										'type' => 'string',
									),
									'wp_travel_engine_confirmation_page' => array(
										'type' => 'string',
									),
									'wp_travel_engine_dashboard_page' => array(
										'type' => 'string',
									),
									'enquiry' => array(
										'type' => 'string',
									),
									'search'  => array(
										'type' => 'string',
									),
									'wp_travel_engine_wishlist' => array(
										'type' => 'string',
									),
								),
							),
						),
						'fields'       => self::get_page_settings_fields(),
					),
					'trip_tabs'     => array(
						'label'        => __( 'Trip Tabs Settings', 'wp-travel-engine' ),
						'content_path' => plugin_dir_path( __FILE__ ) . 'backend/settings/general/trip-tabs.php',
						'current'      => false,
						// 'fields'      => self::get_trip_tabs_fields(),
					),
					'trip_info'     => array(
						'label'        => __( 'Trip Info', 'wp-travel-engine' ),
						'content_path' => plugin_dir_path( __FILE__ ) . 'backend/settings/general/trip-info.php',
						'current'      => false,
					),
				),
				'current'  => true,
				'order'    => 1,
			),
			'wpte-emails'        => array(
				'label'    => __( 'Emails', 'wp-travel-engine' ),
				'sub_tabs' => array(
					'booking_notifications' => array(
						'label'        => __( 'Admin Email Templates', 'wp-travel-engine' ),
						'content_path' => plugin_dir_path( __FILE__ ) . 'backend/settings/emails/booking-notifications.php',
						'current'      => false,
						'fields'       => self::get_booking_notifications_fields(),
					),
					'purchase_receipt'      => array(
						'label'        => __( 'Customer Email Templates', 'wp-travel-engine' ),
						'content_path' => plugin_dir_path( __FILE__ ) . 'backend/settings/emails/purchase-receipt.php',
						'current'      => true,
					),
					'enquiry_emails'        => array(
						'label'        => __( 'Enquiry Emails', 'wp-travel-engine' ),
						'content_path' => plugin_dir_path( __FILE__ ) . 'backend/settings/emails/enquiry-emails.php',
						'current'      => false,
					),
					'test_email'            => array(
						'label'        => __( 'Test Email', 'wp-travel-engine' ),
						'content_path' => plugin_dir_path( __FILE__ ) . 'backend/settings/emails/test-email.php',
						'current'      => false,
					),
				),
				'order'    => 2,
			),
			'wpte-miscellaneous' => array(
				'label'       => __( 'Miscellaneous', 'wp-travel-engine' ),
				'sub_tabs'    => array(
					'currency'  => array(
						'label'        => __( 'Currency Settings', 'wp-travel-engine' ),
						'content_path' => plugin_dir_path( __FILE__ ) . 'backend/settings/misc/currency.php',
						'current'      => true,
						'fields'       => array(
							'',
						),
					),
					'show-hide' => array(
						'label'        => __( 'Display Settings', 'wp-travel-engine' ),
						'content_path' => plugin_dir_path( __FILE__ ) . 'backend/settings/misc/show-hide.php',
						'current'      => true,
						'has_updates'  => 'wte_note_5.5.7',
					),
					'misc'      => array(
						'label'        => __( 'Miscellaneous Settings', 'wp-travel-engine' ),
						'content_path' => plugin_dir_path( __FILE__ ) . 'backend/settings/misc/miscellaneous.php',
						'current'      => true,
					),
				),
				'has_updates' => 'wte_note_5.5.7',
				'order'       => 3,
			),
			'wpte-payment'       => array(
				'label'       => __( 'Payments', 'wp-travel-engine' ),
				'order'       => 5,
				'sub_tabs'    => array(
					'payment-general' => array(
						'label'        => __( 'Payment General Settings', 'wp-travel-engine' ),
						'content_path' => plugin_dir_path( __FILE__ ) . 'backend/settings/payments/general.php',
						'current'      => true,
					),
					'paypal-standard' => array(
						'label'        => __( 'PayPal Standard', 'wp-travel-engine' ),
						'content_path' => plugin_dir_path( __FILE__ ) . 'backend/settings/payments/paypal-standard.php',
						'current'      => false,
					),
					'bacs-payment'    => array(
						'label'        => __( 'Direct bank transfer', 'wp-travel-engine' ),
						'content_path' => plugin_dir_path( __FILE__ ) . 'backend/settings/payments/bacs-payment.php',
						'current'      => false,
					),
					'check-payment'   => array(
						'label'        => __( 'Check Payments', 'wp-travel-engine' ),
						'content_path' => plugin_dir_path( __FILE__ ) . 'backend/settings/payments/check-payment.php',
						'current'      => false,
					),
					'tax-setting'     => array(
						'label'        => __( 'Tax Settings', 'wp-travel-engine' ),
						'content_path' => plugin_dir_path( __FILE__ ) . 'backend/settings/payments/tax-settings.php',
						'current'      => false,
						'has_updates'  => 'wte_new_5.5.0',
					),
				),
				'has_updates' => 'wte_note_5.5.0',
				'order'       => 4,
			),
			'wpte-dashboard'     => array(
				'label'       => __( 'Dashboard', 'wp-travel-engine' ),
				'order'       => 6,
				'sub_tabs'    => array(
					'user-dashboard' => array(
						'label'        => __( 'User Dashboard Settings', 'wp-travel-engine' ),
						'content_path' => plugin_dir_path( __FILE__ ) . 'backend/settings/dashboard/general.php',
						'current'      => true,
					),
					'social-login'   => array(
						'label'        => __( 'Social Login Settings', 'wp-travel-engine' ),
						'content_path' => plugin_dir_path( __FILE__ ) . 'backend/settings/dashboard/socialintegration.php',
						'current'      => true,
						'has_updates'  => 'wte_new_5.5.0',
					),
				),
				'has_updates' => 'wte_note_5.5.0',
				'order'       => 5,
			),
			'wpte-extensions'    => array(
				'label'       => __( 'Extensions', 'wp-travel-engine' ),
				'sub_tabs'    => $this->get_extensions_tabs(),
				'order'       => 7,
				'has_updates' => 'wte_note_5.5.7',
			),
			'wpte-performance'   => array(
				'label'    => __( 'Performance', 'wp-travel-engine' ),
				'sub_tabs' => array(
					'general' => array(
						'label'        => __( 'General', 'wp-travel-engine' ),
						'content_path' => plugin_dir_path( __FILE__ ) . 'backend/settings/performance/general.php',
						'current'      => true,
					),
				),
				'order'    => 8,
			),
		);

		$settings_directory = plugin_dir_path( WP_TRAVEL_ENGINE_FILE_PATH ) . 'includes/backend/settings';
		$settings_dirs      = scandir( $settings_directory );

		if ( is_array( $settings_dirs ) ) {
			foreach ( $settings_dirs as $dir ) {
				if ( in_array( $dir, array( '.', '..' ) ) ) {
					continue;
				}
				$directory = $settings_directory . '/' . $dir;
				if ( is_dir( $directory ) && file_exists( $directory . '/index.php' ) ) {
					$content_args           = include $directory . '/index.php';
					$tab_id                 = 'wpte-' . $dir;
					$global_tabs[ $tab_id ] = $content_args;
				}
			}

			$menu_order = array_column( $global_tabs, 'order' );
			array_multisort( $menu_order, SORT_NUMERIC, $global_tabs );
		}

		$global_tabs = apply_filters( 'wpte_settings_get_global_tabs', $global_tabs );

		wp_cache_set( 'wptravelengine_settings_tabs', $global_tabs, 'wptravelengine' );

		return $global_tabs;
	}

	/**
	 * Settings panel of the plugin.
	 *
	 * @since    1.0.0
	 */
	public function wp_travel_engine_backend_settings() {
		?>
		<div id="wptravelengine-global-settings-app"></div>
		<?php
	}

	/**
	 * Get extensions settings tabs
	 */
	public function get_extensions_tabs() {
		return apply_filters( 'wpte_get_global_extensions_tab', array() );
	}
}
