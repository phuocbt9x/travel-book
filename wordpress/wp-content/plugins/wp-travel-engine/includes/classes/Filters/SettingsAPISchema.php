<?php
/**
 * Settings API Schema
 *
 * @package WPTravelEngine
 * @since 6.2.0
 */

namespace WPTravelEngine\Filters;

use stdClass;
use WP_REST_Request;
use WPTravelEngine\Core\Controllers\RestAPI\V2\Settings;
use WPTravelEngine\Core\Models\Settings\PluginSettings;
use WPTravelEngine\Core\Models\Settings\Options;

/**
 * Settings API Schema
 *
 * @since 6.2.0
 */
class SettingsAPISchema {


	/**
	 * Plugin settings object.
	 *
	 * @var PluginSettings
	 */
	protected PluginSettings $plugin_settings;

	public function hooks() {
		add_filter( 'wptravelengine_settings_api_schema', array( $this, 'addons_settings_api_schema' ), 10, 2 );
		add_filter( 'wptravelengine_rest_prepare_settings', array( $this, 'prepare_addons_settings' ), 10, 3 );
		add_action( 'wptravelengine_api_update_settings', array( $this, 'update_addons_settings' ), 10, 2 );
	}

	/**
	 * Prepares image data for the given image id.
	 *
	 * @param numeric $id Image id.
	 *
	 * @return array|stdClass
	 */
	private function prepare_image_data( $id ) {

		if ( ! wp_get_attachment_metadata( $id ) || ! wp_attachment_is_image( $id ) ) {
			return new stdClass();
		}

		$id  = (int) $id;
		$alt = get_post_meta( $id, '_wp_attachment_image_alt', true );
		$url = wp_get_attachment_image_url( $id, 'full' );

		return compact( 'id', 'alt', 'url' );
	}

	/**
	 * Addons Settings API Schema
	 *
	 * @param array    $schema Settings API schema.
	 * @param Settings $settings_controller Settings controller instance.
	 *
	 * @return array
	 */
	public function addons_settings_api_schema( array $schema, Settings $settings_controller ): array {
		$schema_directory = __DIR__ . '/schemas';

		$directory_iterator = new \DirectoryIterator( $schema_directory );

		$additional_schema = array();
		foreach ( $directory_iterator as $file_info ) {
			if ( $file_info->isFile() ) {
				$schema_file = $file_info->getPathname();
				$schema_name = $file_info->getBasename( '.php' );

				$additional_schema[] = require $schema_file;
			}
		}

		$additional_schema = array_reduce( $additional_schema, 'array_merge', array() );

		return array_merge( $schema, $additional_schema );
	}

	/**
	 * Prepare weather forcast extension settings.
	 *
	 * @param WP_REST_Request $request Request object.
	 *
	 * @return array
	 */
	protected function prepare_weather_forecast_settings( WP_REST_Request $request ): array {

		if ( ! defined( 'WTE_WEATHER_FORECAST_BASE_PATH' ) || ! file_exists( WTE_WEATHER_FORECAST_BASE_PATH ) ) {
			return array();
		}

		$settings = array();

		$settings['weather_forecast'] = array(
			'api_key' => (string) $this->plugin_settings->get( 'weather_forecast.api_key', '' ),
		);

		return $settings;
	}

	/**
	 * Prepare advance itinerary extension settings.
	 *
	 * @param WP_REST_Request $request Request object.
	 *
	 * @return array
	 */
	protected function prepare_advance_itinerary_settings( WP_REST_Request $request ): array {

		if ( ! defined( 'WTEAD_FILE_PATH' ) || ! file_exists( WTEAD_FILE_PATH ) ) {
			return array();
		}

		$chart_data = $this->plugin_settings->get( 'wte_advance_itinerary.chart', array() );

		$chart_bg_img = new stdClass();
		if ( ! empty( $chart_data['bg'] ?? '' ) ) {
			$chart_bg_img = $this->prepare_image_data( $chart_data['bg'] );
		}

		$settings['advanced_itinerary'] = array(
			'enable_all_itinerary' => wptravelengine_toggled( $this->plugin_settings->get( 'wte_advance_itinerary.enable_expand_all' ) ),
			'sleep_mode_fields'    => array_column( $this->plugin_settings->get( 'wte_advance_itinerary.itinerary_sleep_mode_fields', array() ), 'field_text' ),
			'chart'                => array(
				'enable'            => wptravelengine_replace( $this->plugin_settings->get( 'wte_advance_itinerary.chart.show', 'yes' ), 'yes', true, false ),
				'elevation_unit'    => (string) $this->plugin_settings->get( 'wte_advance_itinerary.chart.alt_unit', 'm' ),
				'enable_x_axis'     => wptravelengine_toggled( $chart_data['options']['scales.xAxes.display'] ?? false ),
				'enable_y_axis'     => wptravelengine_toggled( $chart_data['options']['scales.yAxes.display'] ?? false ),
				'enable_line_graph' => wptravelengine_toggled( $chart_data['data']['datasets.data.fill'] ?? false ),
				'color'             => (string) $this->plugin_settings->get( 'wte_advance_itinerary.chart.data.color', '#147dfe' ),
				'background_image'  => $chart_bg_img,
			),
		);
		return $settings;
	}

	/**
	 * Prepare Group Discount Extension Tabs Settings.
	 *
	 * @param WP_REST_Request $request Request object.
	 *
	 * @return array
	 */
	protected function prepare_group_discount_settings( WP_REST_Request $request ): array {

		if ( ! defined( 'WP_TRAVEL_ENGINE_GROUP_DISCOUNT_FILE_PATH' ) || ! file_exists( WP_TRAVEL_ENGINE_GROUP_DISCOUNT_FILE_PATH ) ) {
			return array();
		}

		$settings = array();

		$settings['group_discount'] = array(
			'enable'           => wptravelengine_toggled( $this->plugin_settings->get( 'group.discount', '1' ) ),
			'info'             => (string) $this->plugin_settings->get( 'group.discount_availability', '' ),
			'guide_title'      => (string) $this->plugin_settings->get( 'group.discount_guide_title', '' ),
			'guide_open_title' => (string) $this->plugin_settings->get( 'group.discount_guide_open_title', '' ),
		);

		return $settings;
	}

	/**
	 * Prepare Extra Services Extension Tabs Settings.
	 *
	 * @param WP_REST_Request $request Request object.
	 *
	 * @return array
	 */
	protected function prepare_extra_services_settings( WP_REST_Request $request ): array {

		if ( ! defined( 'WTE_EXTRA_SERVICES_FILE_PATH' ) || ! file_exists( WTE_EXTRA_SERVICES_FILE_PATH ) ) {
			return array();
		}

		$settings = array();

		$settings['extra_services'] = array(
			'title' => (string) $this->plugin_settings->get( 'extra_service_title', __( 'Extra Services', 'wp-travel-engine' ) ),
		);

		return $settings;
	}

	/**
	 * Prepare File Downloads Extension Tabs Settings.
	 *
	 * @param WP_REST_Request $request Request object.
	 *
	 * @return array
	 */
	protected function prepare_file_downloads_settings( WP_REST_Request $request ): array {

		if ( ! defined( 'WTEFD_FILE_PATH' ) || ! file_exists( WTEFD_FILE_PATH ) ) {
			return array();
		}

		$settings = array();

		$files = $this->plugin_settings->get( 'file_downloads.wte_files_downloadable', array() );

		$file_downloads = array();
		foreach ( $files ?? array() as $file ) {
			$file_downloads[] = array(
				'id'    => (int) $file['id'],
				'type'  => (string) get_post_mime_type( $file['id'] ),
				'title' => (string) $file['title'],
				'url'   => (string) $file['url'],
			);
		}

		$settings['file_downloads'] = array(
			'header_text'              => (string) $this->plugin_settings->get( 'file_downloads.wte_file_download_label', '' ),
			'header_description'       => (string) $this->plugin_settings->get( 'file_downloads.wte_file_download_description', '' ),
			'show_global_files_only'   => wptravelengine_toggled( $this->plugin_settings->get( 'file_downloads.wte_file_download_global_check', '1' ) ),
			'show_global_files_on_top' => wptravelengine_toggled( $this->plugin_settings->get( 'file_downloads.wte_file_download_force_global' ) ),
			'view_in_new_tab'          => wptravelengine_toggled( $this->plugin_settings->get( 'file_downloads.wte_file_download_new_tab' ) ),
			'enable_download'          => wptravelengine_toggled( $this->plugin_settings->get( 'file_downloads.wte_file_download_click_download', '1' ) ),
			'files'                    => $file_downloads,
		);

		return $settings;
	}

	/**
	 * Prepare Itinerary Downloader Extension Tabs Settings.
	 *
	 * @param WP_REST_Request $request Request object.
	 *
	 * @return array
	 */
	protected function prepare_itinerary_downloader_settings( WP_REST_Request $request ): array {

		if ( ! defined( 'WTE_ITINERARY_DOWNLOADER_ABSPATH' ) || ! file_exists( WTE_ITINERARY_DOWNLOADER_ABSPATH ) ) {
			return array();
		}

		$temp_image  = $this->plugin_settings->get( 'itinerary_downloader.pdf_content_logo', '' );
		$custom_logo = new stdClass();
		if ( ! empty( $temp_image ) ) {
			$custom_logo = $this->prepare_image_data( $temp_image );
		}

		$temp_image        = $this->plugin_settings->get( 'itinerary_downloader.pdf_content_expert_img', '' );
		$expert_avatar_img = new stdClass();
		if ( ! empty( $temp_image ) ) {
			$expert_avatar_img = $this->prepare_image_data( $temp_image );
		}

		$temp_image               = $this->plugin_settings->get( 'itinerary_downloader.pdf_company_info_last_page_bg_image', '' );
		$info_page_background_img = new stdClass();
		if ( ! empty( $temp_image ) ) {
			$info_page_background_img = $this->prepare_image_data( $temp_image );
		}

		return array(
			'itinerary_downloader' => array(
				'enable'                        => wptravelengine_toggled( $this->plugin_settings->get( 'itinerary_downloader.enable', 'on' ) ),
				'popup_form'                    => array(
					'enable'      => wptravelengine_toggled( $this->plugin_settings->get( 'itinerary_downloader.enable_email_form' ) ),
					'label'       => (string) $this->plugin_settings->get( 'itinerary_downloader.popup_form_main_label', '' ),
					'description' => (string) $this->plugin_settings->get( 'itinerary_downloader.popup_form_main_description', '' ),
				),
				'enable_mailchimp'              => wptravelengine_toggled( $this->plugin_settings->get( 'itinerary_downloader.mailchimp_enabled' ) ),
				'mailchimp_api_key'             => (string) $this->plugin_settings->get( 'itinerary_downloader.mailchimp_api_key', '' ),
				'download_btn_label'            => (string) $this->plugin_settings->get( 'itinerary_downloader.download_button_main_label', __( 'Want to read it later ?', 'wp-travel-engine' ) ),
				'download_btn_description'      => (string) $this->plugin_settings->get( 'itinerary_downloader.download_button_main_description', __( 'Download this tour\'s PDF brochure and start your planning offline.', 'wp-travel-engine' ) ),
				'enable_user_consent'           => wptravelengine_toggled( $this->plugin_settings->get( 'itinerary_downloader.user_consent_enabled' ) ),
				'user_consent_info'             => (string) $this->plugin_settings->get( 'itinerary_downloader.user_consent_info', __( 'After signing up for the newsletter, you will occasionally receive mail regarding offers, releases & notices. We will not sell or distribute your email address to a third party at any time.', 'wp-travel-engine' ) ),
				'enable_user_consent_mandatory' => wptravelengine_toggled( $this->plugin_settings->get( 'itinerary_downloader.user_consent_always_required', 'off' ) ),
				'reply_to_email'                => (string) $this->plugin_settings->get( 'itinerary_downloader.replyto_emailaddress', Options::get( 'admin_email' ) ),
				'email_subject'                 => (string) $this->plugin_settings->get( 'itinerary_downloader.email_subject_text', __( 'Please Find The Itinerary PDF Attached', 'wp-travel-engine' ) ),
				'email_content'                 => (string) $this->plugin_settings->get( 'itinerary_downloader.email_body_message', __( 'Hello, Please find the requested PDF of #trip attached. Thanks & Regards', 'wp-travel-engine' ) ),
				'pdf_content'                   => array(
					'custom_logo'              => $custom_logo,
					'description'              => (string) $this->plugin_settings->get( 'itinerary_downloader.pdf_company_summary', '' ),
					'email_us_label'           => (string) $this->plugin_settings->get( 'itinerary_downloader.email_contact_us_label', __( 'Quick Questions ? Email Us', 'wp-travel-engine' ) ),
					'email_address'            => (string) $this->plugin_settings->get( 'itinerary_downloader.pdf_content_email', Options::get( 'admin_email' ) ),
					'address'                  => (string) $this->plugin_settings->get( 'itinerary_downloader.pdf_company_address', '' ),
					'address_label'            => (string) $this->plugin_settings->get( 'itinerary_downloader.company_address_label', __( 'Address', 'wp-travel-engine' ) ),
					'theme_color'              => (string) $this->plugin_settings->get( 'itinerary_downloader.pdf_base_theme_color', '' ),
					'phone_number'             => (string) $this->plugin_settings->get( 'itinerary_downloader.pdf_company_telephone_address', '' ),
					'enable_expert_chat'       => wptravelengine_toggled( $this->plugin_settings->get( 'itinerary_downloader.enable_talk_to_expert_section' ) ),
					'expert_chat_label'        => (string) $this->plugin_settings->get( 'itinerary_downloader.pdf_talk_to_expert_text', __( 'Talk To an Expert', 'wp-travel-engine' ) ),
					'expert_email'             => (string) $this->plugin_settings->get( 'itinerary_downloader.pdf_expert_email', Options::get( 'admin_email' ) ),
					'expert_avatar_img'        => $expert_avatar_img,
					'expert_phone_number'      => (string) $this->plugin_settings->get( 'itinerary_downloader.pdf_expert_telephone_address', '' ),
					'enable_viber_contact'     => wptravelengine_toggled( $this->plugin_settings->get( 'itinerary_downloader.viber_available_for_contact' ) ),
					'enable_whatsapp_contact'  => wptravelengine_toggled( $this->plugin_settings->get( 'itinerary_downloader.whattsapp_available_for_contact' ) ),
					'info_page_background_img' => $info_page_background_img,
					'include_fixed_date'       => wptravelengine_toggled( $this->plugin_settings->get( 'itinerary_downloader.add_availability_into_pdf' ) ),
				),
			),
		);
	}

	/**
	 * Prepare Trip Reviews Extension Tabs Settings.
	 *
	 * @param WP_REST_Request $request Request object.
	 *
	 * @return array
	 */
	protected function prepare_trip_reviews_settings( WP_REST_Request $request ): array {

		if ( ! defined( 'WTE_TRIP_REVIEW_FILE_PATH' ) || ! file_exists( WTE_TRIP_REVIEW_FILE_PATH ) ) {
			return array();
		}

		return array(
			'trip_reviews' => array(
				'enable'                  => ! wptravelengine_toggled( $this->plugin_settings->get( 'trip_reviews.hide' ) ),
				'enable_from'             => ! wptravelengine_toggled( $this->plugin_settings->get( 'trip_reviews.hide_form' ) ),
				'label'                   => (string) $this->plugin_settings->get( 'trip_reviews.summary_label', 'Overall Trip Rating:' ),
				'rating_label'            => (string) $this->plugin_settings->get( 'trip_reviews.company_summary_label', 'Overall Company Rating:' ),
				'reviewed_tour_label'     => (string) $this->plugin_settings->get( 'trip_reviews.reviewed_tour_text', 'Reviewed Tour:' ),
				'excellent_label'         => (string) $this->plugin_settings->get( 'trip_reviews.excellent_label', 'Excellent' ),
				'very_good_label'         => (string) $this->plugin_settings->get( 'trip_reviews.vgood_label', 'Very Good' ),
				'average_label'           => (string) $this->plugin_settings->get( 'trip_reviews.average_label', 'Average' ),
				'poor_label'              => (string) $this->plugin_settings->get( 'trip_reviews.poor_label', 'Poor' ),
				'terrible_label'          => (string) $this->plugin_settings->get( 'trip_reviews.terrible_label', 'Terrible' ),
				'enable_emoticons'        => wptravelengine_toggled( $this->plugin_settings->get( 'trip_reviews.show_emoticons' ) ),
				'enable_expericence_date' => ! wptravelengine_toggled( $this->plugin_settings->get( 'trip_reviews.hide_experience_date_field' ) ),
				'enable_gallery_images'   => ! wptravelengine_toggled( $this->plugin_settings->get( 'trip_reviews.hide_image_upload_field' ) ),
				'enable_reviewed_tour'    => ! wptravelengine_toggled( $this->plugin_settings->get( 'trip_reviews.hide_reviewed_tour_field' ) ),
				'enable_client_location'  => ! wptravelengine_toggled( $this->plugin_settings->get( 'trip_reviews.hide_client_location_field' ) ),
			),
		);
	}

	/**
	 * Prepare Fixed Starting Dates Extension Tabs Settings.
	 *
	 * @param WP_REST_Request $request Request object.
	 *
	 * @return array
	 */
	protected function prepare_fixed_starting_settings( WP_REST_Request $request ): array {

		if ( ! defined( 'WTE_FIXED_DEPARTURE_FILE_PATH' ) || ! file_exists( WTE_FIXED_DEPARTURE_FILE_PATH ) ) {
			return array();
		}

		$settings = array();

		$settings['fsd'] = array(
			'enable'                    => wptravelengine_toggled( $this->plugin_settings->get( 'departure.section', '1' ) ),
			'section_title'             => (string) $this->plugin_settings->get( 'departure.section_title', '' ),
			'show_dates_layout'         => ! wptravelengine_toggled( $this->plugin_settings->get( 'departure.hide_availability_section', '' ) ),
			'show_availability'         => ! wptravelengine_toggled( $this->plugin_settings->get( 'departure.hide_availability_column', '' ) ),
			'show_price'                => ! wptravelengine_toggled( $this->plugin_settings->get( 'departure.hide_price_column', '' ) ),
			'show_space_left'           => ! wptravelengine_toggled( $this->plugin_settings->get( 'departure.hide_space_left_column', '' ) ),
			// 'date_layout'               => (string) $this->plugin_settings->get( 'fsd_dates_layout', 'dates_list' ),
			'number_of_dates'           => (string) $this->plugin_settings->get( 'trip_dates.number', '3' ),
			'number_of_pagination'      => (string) $this->plugin_settings->get( 'trip_dates.pagination_number', '10' ),
			'show_without_fsd'          => ! wptravelengine_toggled( $this->plugin_settings->get( 'hide_trips_without_dates', 'no' ) ),
			'show_with_available_dates' => ! wptravelengine_toggled( $this->plugin_settings->get( 'hide_trips_without_dates_beyond', 'no' ) ),
			'number_of_days'            => (string) $this->plugin_settings->get( 'number_of_days_to_hide_trips', '' ),
		);

		return $settings;
	}

	/**
	 * Prepare Partial Payment Extension Tabs Settings.
	 *
	 * @param WP_REST_Request $request Request object.
	 *
	 * @return array
	 */
	protected function prepare_partial_payment_settings( WP_REST_Request $request ): array {

		if ( ! defined( 'WP_TRAVEL_ENGINE_PARTIAL_PAYMENT_FILE_PATH' ) || ! file_exists( WP_TRAVEL_ENGINE_PARTIAL_PAYMENT_FILE_PATH ) ) {
			return array();
		}

		$settings = array();

		$settings['partial_payment'] = array(
			'enable'              => wptravelengine_toggled( $this->plugin_settings->get( 'partial_payment_enable', 'no' ) ),
			'payment_type'        => (string) $this->plugin_settings->get( 'partial_payment_option', 'percent' ),
			'payment_percent'     => (string) $this->plugin_settings->get( 'partial_payment_percent', '' ),
			'payment_amount'      => (string) $this->plugin_settings->get( 'partial_payment_amount', '' ),
			'enable_full_payment' => wptravelengine_toggled( $this->plugin_settings->get( 'full_payment_enable', 'yes' ) ),
		);

		return $settings;
	}

	/**
	 * Prepare Form Editor Extension Tabs Settings.
	 *
	 * @param WP_REST_Request $request Request object.
	 *
	 * @return array
	 */
	protected function prepare_form_editor_settings( WP_REST_Request $request ): array {

		if ( ! defined( 'WTE_FORM_EDITOR_PLUGIN_FILE' ) || ! file_exists( WTE_FORM_EDITOR_PLUGIN_FILE ) ) {
			return array();
		}

		$settings = array();

		$settings['form_editor'] = array(
			'recaptcha_site_key'   => (string) $this->plugin_settings->get( 'form_editor.grecaptcha_site_key', '' ),
			'recaptcha_secret_key' => (string) $this->plugin_settings->get( 'form_editor.recaptcha_v2_secret_key', '' ),
		);

		return $settings;
	}

	/**
	 * Prepare Currency Converter Extension Settings.
	 *
	 * @param WP_REST_Request $request Request object.
	 *
	 * @return array
	 * @since 6.2.0
	 */
	protected function prepare_currency_converter_settings( WP_REST_Request $request ): array {

		if ( ! defined( 'WTE_CURRENCY_CONVERTER_ABSPATH' ) || ! file_exists( WTE_CURRENCY_CONVERTER_ABSPATH ) ) {
			return array();
		}

		$settings = $this->plugin_settings->get( 'currency_converter', array() );

		$code          = $settings['code'] ?? array();
		$rate          = $settings['rate'] ?? array();
		$currency_rate = array_map(
			function ( $key, $value ) use ( $rate ) {
				return array(
					'id'   => $key,
					'code' => $value,
					'rate' => $rate[ $key ] ?? '',
				);
			},
			array_keys( $code ),
			$code
		);

		return array(
			'currency_converter' => array(
				'enable'          => wptravelengine_toggled( $this->plugin_settings->get( 'currency_converter.enable', 'yes' ) ),
				'sticky_enable'   => wptravelengine_toggled( $this->plugin_settings->get( 'currency_converter.sticky_enable', 'no' ) ),
				'show_before_bkg' => wptravelengine_toggled( $this->plugin_settings->get( 'currency_converter.show_before_bkg', 'yes' ) ),
				'title'           => (string) ( $this->plugin_settings->get( 'currency_converter.title', 'Currency Converter' ) ),
				'api_key'         => (string) ( $this->plugin_settings->get( 'currency_converter.api_key', '' ) ),
				'key_type'        => (string) ( $this->plugin_settings->get( 'currency_converter.key_type', 'free' ) ),
				'geo_locate'      => wptravelengine_toggled( $this->plugin_settings->get( 'currency_converter.geo_locate', '0' ) ),
				'auto_update'     => wptravelengine_toggled( $this->plugin_settings->get( 'currency_converter.auto_update', '0' ) ),
				'currency_rate'   => $currency_rate,
			),
		);
	}

	/**
	 * Prepare Zapier Extension Settings.
	 *
	 * @param WP_REST_Request $request Request object.
	 *
	 * @return array
	 * @since 6.2.0
	 */
	protected function prepare_zapier_settings( WP_REST_Request $request ): array {

		if ( ! defined( 'WTE_ZAPIER_PLUGIN_FILE' ) || ! file_exists( WTE_ZAPIER_PLUGIN_FILE ) ) {
			return array();
		}

		$booking_zaps     = $this->plugin_settings->get( 'zapier.booking_zaps', array() );
		$new_booking_zaps = $this->process_zaps( $booking_zaps );

		$enquiry_zaps     = $this->plugin_settings->get( 'zapier.enquiry_zaps', array() );
		$new_enquiry_zaps = $this->process_zaps( $enquiry_zaps );

		return array(
			'zapier' => array(
				'enable_automation_booking' => wptravelengine_toggled( $this->plugin_settings->get( 'zapier.enable_automation_booking', 'no' ) ),
				'enable_automation_enquiry' => wptravelengine_toggled( $this->plugin_settings->get( 'zapier.enable_automation_enquiry', 'no' ) ),
				'booking_zaps'              => $new_booking_zaps,
				'enquiry_zaps'              => $new_enquiry_zaps,
			),
		);
	}

	/**
	 * Prepare We Travel Extension Settings.
	 *
	 * @param WP_REST_Request $request Request object.
	 *
	 * @return array
	 * @since 6.2.0
	 */
	protected function prepare_wetravel_settings( WP_REST_Request $request ): array {

		if ( ! defined( 'WTE_AFFILIATE_BOOKING_FILE_PATH' ) || ! file_exists( WTE_AFFILIATE_BOOKING_FILE_PATH ) ) {
			return array();
		}

		$settings = array();

		$wte_wetravel_settings = Options::get( 'wte_wetravel_settings', array( 'book_now_label' => 'Book Now' ) );

		$settings['wetravel'] = array(
			'book_now_label' => $wte_wetravel_settings['book_now_label'],
		);

		return $settings;
	}

	/**
	 * Prepare User History Extension Settings.
	 *
	 * @param WP_REST_Request $request Request object.
	 *
	 * @return array
	 * @since 6.2.0
	 */
	protected function prepare_user_history_settings( WP_REST_Request $request ): array {

		if ( ! defined( 'WTE_USER_HISTORY_FILE_PATH' ) || ! file_exists( WTE_USER_HISTORY_FILE_PATH ) ) {
			return array();
		}

		$settings = array();

		$settings['user_history'] = array(
			'enable_tracking'       => ! wptravelengine_toggled( $this->plugin_settings->get( 'user_history.disable' ) ),
			'enable_cookie_consent' => wptravelengine_toggled( $this->plugin_settings->get( 'user_history.cookie_consent.enable' ) ),
			'cookie_position'       => (string) $this->plugin_settings->get( 'user_history.cookie_consent.position', 'top' ),
			'cookie_layout'         => (string) $this->plugin_settings->get( 'user_history.cookie_consent.layout', '' ),
			'banner_bg_color'       => (string) $this->plugin_settings->get( 'user_history.cookie_consent.banner_bg', '#000000' ),
			'banner_btn_color'      => (string) $this->plugin_settings->get( 'user_history.cookie_consent.button_bg', '#f1d600' ),
			'banner_text_color'     => (string) $this->plugin_settings->get( 'user_history.cookie_consent.content_text_col', '#ffffff' ),
			'banner_btn_text_color' => (string) $this->plugin_settings->get( 'user_history.cookie_consent.button_text_col', '#000000' ),
			'learn_more_link'       => (string) $this->plugin_settings->get( 'user_history.cookie_consent.learn_more_link', '' ),
			'cookie_custom_message' => (string) $this->plugin_settings->get( 'user_history.cookie_consent.consent_message', 'This website uses cookies to ensure you get the best experience on our website.' ),
			'dismiss_button_text'   => (string) $this->plugin_settings->get( 'user_history.cookie_consent.dismiss_button_text', 'Got it!' ),
			'policy_link_text'      => (string) $this->plugin_settings->get( 'user_history.cookie_consent.policy_link_text', 'Learn more' ),
		);

		return $settings;
	}

	/**
	 * Process zaps data.
	 *
	 * @param array $zaps Zaps data.
	 *
	 * @return array
	 * @since 6.2.0
	 */
	private function process_zaps( array $zaps ): array {
		$new_zaps = array();
		foreach ( $zaps as $key => $value ) {
			$new_zaps[] = array(
				'id'   => $key,
				'name' => sanitize_text_field( $value['name'] ),
				'url'  => esc_url_raw( $value['url'] ),
			);
		}
		return $new_zaps;
	}

	/**
	 * Prepares addon Settings.
	 *
	 * @param array           $data Settings data.
	 * @param WP_REST_Request $request Request object.
	 * @param Settings        $settings_controller Settings controller instance.
	 *
	 * @return array
	 */
	public function prepare_addons_settings( array $data, WP_REST_Request $request, Settings $settings_controller ): array {

		$this->plugin_settings = $settings_controller->plugin_settings;

		return array_merge(
			$data,
			$this->prepare_weather_forecast_settings( $request ),
			$this->prepare_group_discount_settings( $request ),
			$this->prepare_advance_itinerary_settings( $request ),
			$this->prepare_extra_services_settings( $request ),
			$this->prepare_file_downloads_settings( $request ),
			$this->prepare_itinerary_downloader_settings( $request ),
			$this->prepare_trip_reviews_settings( $request ),
			$this->prepare_fixed_starting_settings( $request ),
			$this->prepare_partial_payment_settings( $request ),
			$this->prepare_form_editor_settings( $request ),
			$this->prepare_currency_converter_settings( $request ),
			$this->prepare_zapier_settings( $request ),
			$this->prepare_wetravel_settings( $request ),
			$this->prepare_user_history_settings( $request ),
		);
	}

	/**
	 * Process Trip Weather Forecast Settings of Extension Tab.
	 *
	 * @param WP_REST_Request $request Request object.
	 *
	 * @return void
	 */
	protected function set_weather_forcast_details( WP_REST_Request $request ) {

		if ( isset( $request['weather_forecast']['api_key'] ) ) {
			$this->plugin_settings->set( 'weather_forecast.api_key', $request['weather_forecast']['api_key'] );
		}
	}

	/**
	 * Process Advanced Itinerary Settings of Extension Tab.
	 *
	 * @param WP_REST_Request $request Request object.
	 *
	 * @return void
	 */
	protected function set_advanced_itinerary_details( WP_REST_Request $request ) {

		$advanced_itinerary = $request['advanced_itinerary'] ?? array();

		if ( empty( $advanced_itinerary ) ) {
			return;
		}

		if ( isset( $advanced_itinerary['enable_all_itinerary'] ) ) {
			$this->plugin_settings->set( 'wte_advance_itinerary.enable_expand_all', wptravelengine_replace( $advanced_itinerary['enable_all_itinerary'], true, '1' ) );
		}

		if ( isset( $advanced_itinerary['sleep_mode_fields'] ) ) {
			$this->plugin_settings->set( 'wte_advance_itinerary.itinerary_sleep_mode_fields', array_map( fn( $val ) => array( 'field_text' => $val ), $advanced_itinerary['sleep_mode_fields'] ) );
		}

		if ( isset( $advanced_itinerary['chart']['enable'] ) ) {
			$this->plugin_settings->set( 'wte_advance_itinerary.chart.show', wptravelengine_replace( $advanced_itinerary['chart']['enable'], true, 'yes', '1' ) );
		}

		if ( isset( $advanced_itinerary['chart']['elevation_unit'] ) ) {
			$this->plugin_settings->set( 'wte_advance_itinerary.chart.alt_unit', $advanced_itinerary['chart']['elevation_unit'] );
		}

		$chart_options = $this->plugin_settings->get( 'wte_advance_itinerary.chart.options', array() );
		if ( isset( $advanced_itinerary['chart']['enable_x_axis'] ) ) {
			$chart_options['scales.xAxes.display'] = wptravelengine_replace( $advanced_itinerary['chart']['enable_x_axis'], true, '1' );
		}

		if ( isset( $advanced_itinerary['chart']['enable_y_axis'] ) ) {
			$chart_options['scales.yAxes.display'] = wptravelengine_replace( $advanced_itinerary['chart']['enable_y_axis'], true, '1' );
		}

		if ( isset( $advanced_itinerary['chart']['enable_x_axis'] ) || isset( $advanced_itinerary['chart']['enable_y_axis'] ) ) {
			$this->plugin_settings->set( 'wte_advance_itinerary.chart.options', $chart_options );
		}

		$chart_data = $this->plugin_settings->get( 'wte_advance_itinerary.chart.data', array() );
		if ( isset( $advanced_itinerary['chart']['enable_line_graph'] ) ) {
			$chart_data['datasets.data.fill'] = wptravelengine_replace( $advanced_itinerary['chart']['enable_line_graph'], true, '1' );
		}

		if ( isset( $advanced_itinerary['chart']['color'] ) ) {
			$chart_data['color'] = $advanced_itinerary['chart']['color'];
		}

		if ( isset( $advanced_itinerary['chart']['enable_line_graph'] ) || isset( $advanced_itinerary['chart']['color'] ) ) {
			$this->plugin_settings->set( 'wte_advance_itinerary.chart.data', $chart_data );
		}

		if ( isset( $advanced_itinerary['chart']['background_image'] ) ) {
			$this->plugin_settings->set( 'wte_advance_itinerary.chart.bg', $advanced_itinerary['chart']['background_image']['id'] ?? null );
		}
	}

	/**
	 * Process Group Discount Settings of Extension Tab.
	 *
	 * @param WP_REST_Request $request Request object.
	 *
	 * @return void
	 */
	protected function set_group_discount_details( WP_REST_Request $request ) {

		if ( ! isset( $request['group_discount'] ) ) {
			return;
		}

		$group_discount = $request['group_discount'] ?? array();

		if ( isset( $group_discount['enable'] ) ) {
			$this->plugin_settings->set( 'group.discount', wptravelengine_replace( $group_discount['enable'], true, '1' ) );
		}

		if ( isset( $group_discount['info'] ) ) {
			$this->plugin_settings->set( 'group.discount_availability', $group_discount['info'] );
		}

		if ( isset( $group_discount['guide_title'] ) ) {
			$this->plugin_settings->set( 'group.discount_guide_title', $group_discount['guide_title'] );
		}

		if ( isset( $group_discount['guide_open_title'] ) ) {
			$this->plugin_settings->set( 'group.discount_guide_open_title', $group_discount['guide_open_title'] );
		}
	}

	/**
	 * Process Extra Services Settings of Extension Tab.
	 *
	 * @param WP_REST_Request $request Request object.
	 *
	 * @return void
	 */
	protected function set_extra_services_details( WP_REST_Request $request ) {

		if ( isset( $request['extra_services']['title'] ) ) {
			$this->plugin_settings->set( 'extra_service_title', $request['extra_services']['title'] );
		}
	}

	/**
	 * Process File Downloads Settings of Extension Tab.
	 *
	 * @param WP_REST_Request $request Request object.
	 *
	 * @return void
	 */
	protected function set_file_downloads_details( WP_REST_Request $request ) {

		if ( ! isset( $request['file_downloads'] ) ) {
			return;
		}

		if ( isset( $request['file_downloads']['header_text'] ) ) {
			$this->plugin_settings->set( 'file_downloads.wte_file_download_label', $request['file_downloads']['header_text'] );
		}

		if ( isset( $request['file_downloads']['header_description'] ) ) {
			$this->plugin_settings->set( 'file_downloads.wte_file_download_description', $request['file_downloads']['header_description'] );
		}

		if ( isset( $request['file_downloads']['show_global_files_only'] ) ) {
			$this->plugin_settings->set( 'file_downloads.wte_file_download_global_check', wptravelengine_replace( $request['file_downloads']['show_global_files_only'], true, '1' ) );
		}

		if ( isset( $request['file_downloads']['show_global_files_on_top'] ) ) {
			$this->plugin_settings->set( 'file_downloads.wte_file_download_force_global', wptravelengine_replace( $request['file_downloads']['show_global_files_on_top'], true, '1' ) );
		}

		if ( isset( $request['file_downloads']['view_in_new_tab'] ) ) {
			$this->plugin_settings->set( 'file_downloads.wte_file_download_new_tab', wptravelengine_replace( $request['file_downloads']['view_in_new_tab'], true, '1' ) );
		}

		if ( isset( $request['file_downloads']['enable_download'] ) ) {
			$this->plugin_settings->set( 'file_downloads.wte_file_download_click_download', wptravelengine_replace( $request['file_downloads']['enable_download'], true, '1' ) );
		}

		if ( isset( $request['file_downloads']['files'] ) ) {
			$file_downloads = array();
			foreach ( $request['file_downloads']['files'] ?? array() as $key => $value ) {
				if ( in_array( $value['id'] ?? '', array_column( $file_downloads, 'id' ) ) ) {
					continue;
				}
				unset( $value['type'] );
				$file_downloads[ ++$key ] = $value;
			}
			$this->plugin_settings->set( 'file_downloads.file_downloadable_max_count', count( $request['file_downloads']['files'] ) );
			$this->plugin_settings->set( 'file_downloads.wte_files_downloadable', $file_downloads );
		}
	}

	/**
	 * Process Itinerary Downloader Settings of Extension Tab.
	 *
	 * @param WP_REST_Request $request Request object.
	 *
	 * @return void
	 */
	protected function set_itinerary_downloader_details( WP_REST_Request $request ) {

		if ( ! isset( $request['itinerary_downloader'] ) ) {
			return;
		}

		$itinerary_downloader = $request['itinerary_downloader'];

		if ( isset( $itinerary_downloader['enable'] ) ) {
			$this->plugin_settings->set( 'itinerary_downloader.enable', wptravelengine_replace( $itinerary_downloader['enable'], true, 'on' ) );
		}

		if ( isset( $itinerary_downloader['popup_form'] ) ) {

			$popup_form = $itinerary_downloader['popup_form'];

			if ( isset( $popup_form['enable'] ) ) {
				$this->plugin_settings->set( 'itinerary_downloader.enable_email_form', wptravelengine_replace( $popup_form['enable'], true, 'on' ) );
			}
			if ( isset( $popup_form['label'] ) ) {
				$this->plugin_settings->set( 'itinerary_downloader.popup_form_main_label', $popup_form['label'] );
			}
			if ( isset( $popup_form['description'] ) ) {
				$this->plugin_settings->set( 'itinerary_downloader.popup_form_main_description', $popup_form['description'] );
			}
		}

		if ( isset( $itinerary_downloader['enable_mailchimp'] ) ) {
			$this->plugin_settings->set( 'itinerary_downloader.mailchimp_enabled', wptravelengine_replace( $itinerary_downloader['enable_mailchimp'], true, 'on' ) );
		}

		if ( isset( $itinerary_downloader['mailchimp_api_key'] ) ) {
			$this->plugin_settings->set( 'itinerary_downloader.mailchimp_api_key', $itinerary_downloader['mailchimp_api_key'] );
		}

		if ( isset( $itinerary_downloader['download_btn_label'] ) ) {
			$this->plugin_settings->set( 'itinerary_downloader.download_button_main_label', $itinerary_downloader['download_btn_label'] );
		}

		if ( isset( $itinerary_downloader['download_btn_description'] ) ) {
			$this->plugin_settings->set( 'itinerary_downloader.download_button_main_description', $itinerary_downloader['download_btn_description'] );
		}

		if ( isset( $itinerary_downloader['enable_user_consent'] ) ) {
			$this->plugin_settings->set( 'itinerary_downloader.user_consent_enabled', wptravelengine_replace( $itinerary_downloader['enable_user_consent'], true, 'on' ) );
		}

		if ( isset( $itinerary_downloader['user_consent_info'] ) ) {
			$this->plugin_settings->set( 'itinerary_downloader.user_consent_info', $itinerary_downloader['user_consent_info'] );
		}

		if ( isset( $itinerary_downloader['enable_user_consent_mandatory'] ) ) {
			$this->plugin_settings->set( 'itinerary_downloader.user_consent_always_required', wptravelengine_replace( $itinerary_downloader['enable_user_consent_mandatory'], true, 'on' ) );
		}

		if ( isset( $itinerary_downloader['reply_to_email'] ) ) {
			$this->plugin_settings->set( 'itinerary_downloader.replyto_emailaddress', $itinerary_downloader['reply_to_email'] );
		}

		if ( isset( $itinerary_downloader['email_subject'] ) ) {
			$this->plugin_settings->set( 'itinerary_downloader.email_subject_text', $itinerary_downloader['email_subject'] );
		}

		if ( isset( $itinerary_downloader['email_content'] ) ) {
			$this->plugin_settings->set( 'itinerary_downloader.email_body_message', $itinerary_downloader['email_content'] );
		}

		if ( ! isset( $itinerary_downloader['pdf_content'] ) ) {
			return;
		}

		$pdf_content = $itinerary_downloader['pdf_content'];

		if ( isset( $pdf_content['custom_logo'] ) ) {
			$this->plugin_settings->set( 'itinerary_downloader.pdf_content_logo', $pdf_content['custom_logo']['id'] ?? '' );
		}

		if ( isset( $pdf_content['description'] ) ) {
			$this->plugin_settings->set( 'itinerary_downloader.pdf_company_summary', $pdf_content['description'] );
		}

		if ( isset( $pdf_content['email_us_label'] ) ) {
			$this->plugin_settings->set( 'itinerary_downloader.email_contact_us_label', $pdf_content['email_us_label'] );
		}

		if ( isset( $pdf_content['email_address'] ) ) {
			$this->plugin_settings->set( 'itinerary_downloader.pdf_content_email', $pdf_content['email_address'] );
		}

		if ( isset( $pdf_content['address'] ) ) {
			$this->plugin_settings->set( 'itinerary_downloader.pdf_company_address', $pdf_content['address'] );
		}

		if ( isset( $pdf_content['address_label'] ) ) {
			$this->plugin_settings->set( 'itinerary_downloader.company_address_label', $pdf_content['address_label'] );
		}

		if ( isset( $pdf_content['theme_color'] ) ) {
			$this->plugin_settings->set( 'itinerary_downloader.pdf_base_theme_color', $pdf_content['theme_color'] );
		}

		if ( isset( $pdf_content['phone_number'] ) ) {
			$this->plugin_settings->set( 'itinerary_downloader.pdf_company_telephone_address', $pdf_content['phone_number'] );
		}

		if ( isset( $pdf_content['enable_expert_chat'] ) ) {
			$this->plugin_settings->set( 'itinerary_downloader.enable_talk_to_expert_section', wptravelengine_replace( $pdf_content['enable_expert_chat'], true, 'on' ) );
		}

		if ( isset( $pdf_content['expert_chat_label'] ) ) {
			$this->plugin_settings->set( 'itinerary_downloader.pdf_talk_to_expert_text', $pdf_content['expert_chat_label'] );
		}

		if ( isset( $pdf_content['expert_email'] ) ) {
			$this->plugin_settings->set( 'itinerary_downloader.pdf_expert_email', $pdf_content['expert_email'] );
		}

		if ( isset( $pdf_content['expert_avatar_img'] ) ) {
			$this->plugin_settings->set( 'itinerary_downloader.pdf_content_expert_img', $pdf_content['expert_avatar_img']['id'] ?? '' );
		}

		if ( isset( $pdf_content['expert_phone_number'] ) ) {
			$this->plugin_settings->set( 'itinerary_downloader.pdf_expert_telephone_address', $pdf_content['expert_phone_number'] );
		}

		if ( isset( $pdf_content['enable_viber_contact'] ) ) {
			$this->plugin_settings->set( 'itinerary_downloader.viber_available_for_contact', wptravelengine_replace( $pdf_content['enable_viber_contact'], true, 'on' ) );
		}

		if ( isset( $pdf_content['enable_whatsapp_contact'] ) ) {
			$this->plugin_settings->set( 'itinerary_downloader.whattsapp_available_for_contact', wptravelengine_replace( $pdf_content['enable_whatsapp_contact'], true, 'on' ) );
		}

		if ( isset( $pdf_content['info_page_background_img'] ) ) {
			$this->plugin_settings->set( 'itinerary_downloader.pdf_company_info_last_page_bg_image', $pdf_content['info_page_background_img']['id'] ?? '' );
		}

		if ( isset( $pdf_content['include_fixed_date'] ) ) {
			$this->plugin_settings->set( 'itinerary_downloader.add_availability_into_pdf', wptravelengine_replace( $pdf_content['include_fixed_date'], true, 'on' ) );
		}
	}

	/**
	 * Process Trip Reviews Settings of Extension Tab.
	 *
	 * @param WP_REST_Request $request Request object.
	 *
	 * @return void
	 */
	protected function set_trip_reviews_details( WP_REST_Request $request ) {

		if ( ! isset( $request['trip_reviews'] ) ) {
			return;
		}

		$trip_reviews = $request['trip_reviews'];

		if ( isset( $trip_reviews['enable'] ) ) {
			$this->plugin_settings->set( 'trip_reviews.hide', wptravelengine_replace( $trip_reviews['enable'], false, '1' ) );
		}

		if ( isset( $trip_reviews['enable_from'] ) ) {
			$this->plugin_settings->set( 'trip_reviews.hide_form', wptravelengine_replace( $trip_reviews['enable_from'], false, '1' ) );
		}

		if ( isset( $trip_reviews['label'] ) ) {
			$this->plugin_settings->set( 'trip_reviews.summary_label', $trip_reviews['label'] );
		}

		if ( isset( $trip_reviews['rating_label'] ) ) {
			$this->plugin_settings->set( 'trip_reviews.company_summary_label', $trip_reviews['rating_label'] );
		}

		if ( isset( $trip_reviews['reviewed_tour_label'] ) ) {
			$this->plugin_settings->set( 'trip_reviews.reviewed_tour_text', $trip_reviews['reviewed_tour_label'] );
		}

		if ( isset( $trip_reviews['excellent_label'] ) ) {
			$this->plugin_settings->set( 'trip_reviews.excellent_label', $trip_reviews['excellent_label'] );
		}

		if ( isset( $trip_reviews['very_good_label'] ) ) {
			$this->plugin_settings->set( 'trip_reviews.vgood_label', $trip_reviews['very_good_label'] );
		}

		if ( isset( $trip_reviews['average_label'] ) ) {
			$this->plugin_settings->set( 'trip_reviews.average_label', $trip_reviews['average_label'] );
		}

		if ( isset( $trip_reviews['poor_label'] ) ) {
			$this->plugin_settings->set( 'trip_reviews.poor_label', $trip_reviews['poor_label'] );
		}

		if ( isset( $trip_reviews['terrible_label'] ) ) {
			$this->plugin_settings->set( 'trip_reviews.terrible_label', $trip_reviews['terrible_label'] );
		}

		if ( isset( $trip_reviews['enable_emoticons'] ) ) {
			$this->plugin_settings->set( 'trip_reviews.show_emoticons', wptravelengine_replace( $trip_reviews['enable_emoticons'], true, '1' ) );
		}

		if ( isset( $trip_reviews['enable_expericence_date'] ) ) {
			$this->plugin_settings->set( 'trip_reviews.hide_experience_date_field', wptravelengine_replace( $trip_reviews['enable_expericence_date'], false, '1' ) );
		}

		if ( isset( $trip_reviews['enable_gallery_images'] ) ) {
			$this->plugin_settings->set( 'trip_reviews.hide_image_upload_field', wptravelengine_replace( $trip_reviews['enable_gallery_images'], false, '1' ) );
		}

		if ( isset( $trip_reviews['enable_reviewed_tour'] ) ) {
			$this->plugin_settings->set( 'trip_reviews.hide_reviewed_tour_field', wptravelengine_replace( $trip_reviews['enable_reviewed_tour'], false, '1' ) );
		}

		if ( isset( $trip_reviews['enable_client_location'] ) ) {
			$this->plugin_settings->set( 'trip_reviews.hide_client_location_field', wptravelengine_replace( $trip_reviews['enable_client_location'], false, '1' ) );
		}
	}

	/**
	 * Process Fixed Starting Dates of Extension Tab.
	 *
	 * @param WP_REST_Request $request Request object.
	 *
	 * @return void
	 */
	protected function set_fixed_starting_dates_details( WP_REST_Request $request ) {

		if ( ! isset( $request['fsd'] ) ) {
			return;
		}

		$fsd = $request['fsd'] ?? array();

		if ( isset( $fsd['enable'] ) ) {
			$this->plugin_settings->set( 'departure.section', wptravelengine_replace( $fsd['enable'], true, 'yes' ) );
		}

		if ( isset( $fsd['section_title'] ) ) {
			$this->plugin_settings->set( 'departure.section_title', $fsd['section_title'] );
		}

		if ( isset( $fsd['show_dates_layout'] ) ) {
			$this->plugin_settings->set( 'departure.hide_availability_section', wptravelengine_replace( $fsd['show_dates_layout'], false, 'yes', '' ) );
		}

		if ( isset( $fsd['show_availability'] ) ) {
			$this->plugin_settings->set( 'departure.hide_availability_column', wptravelengine_replace( $fsd['show_availability'], false, 'yes', '' ) );
		}

		if ( isset( $fsd['show_price'] ) ) {
			$this->plugin_settings->set( 'departure.hide_price_column', wptravelengine_replace( $fsd['show_price'], false, 'yes', '' ) );
		}

		if ( isset( $fsd['show_space_left'] ) ) {
			$this->plugin_settings->set( 'departure.hide_space_left_column', wptravelengine_replace( $fsd['show_space_left'], false, 'yes', '' ) );
		}

		// if ( isset( $fsd['date_layout'] ) ) {
		// $this->plugin_settings->set( 'fsd_dates_layout', $fsd['date_layout'] );
		// }

		if ( isset( $fsd['number_of_dates'] ) ) {
			$this->plugin_settings->set( 'trip_dates.number', $fsd['number_of_dates'] );
		}

		if ( isset( $fsd['number_of_pagination'] ) ) {
			$this->plugin_settings->set( 'trip_dates.pagination_number', $fsd['number_of_pagination'] );
		}

		if ( isset( $fsd['show_without_fsd'] ) ) {
			$this->plugin_settings->set( 'hide_trips_without_dates', wptravelengine_replace( $fsd['show_without_fsd'], false, 'yes', 'no' ) );
		}

		if ( isset( $fsd['show_with_available_dates'] ) ) {
			$this->plugin_settings->set( 'hide_trips_without_dates_beyond', wptravelengine_replace( $fsd['show_with_available_dates'], false, 'yes', 'no' ) );
		}

		if ( isset( $fsd['number_of_days'] ) ) {
			$this->plugin_settings->set( 'number_of_days_to_hide_trips', $fsd['number_of_days'] );
		}
	}

	/**
	 * Process Partial Payment Settings of Extension Tab.
	 *
	 * @param WP_REST_Request $request Request object.
	 *
	 * @return void
	 */
	protected function set_partial_payment_details( WP_REST_Request $request ) {

		if ( ! isset( $request['partial_payment'] ) ) {
			return;
		}

		$partial_payment = $request['partial_payment'] ?? array();

		if ( isset( $partial_payment['enable'] ) ) {
			$this->plugin_settings->set( 'partial_payment_enable', wptravelengine_replace( $partial_payment['enable'], true, 'yes', 'no' ) );
		}

		if ( isset( $partial_payment['payment_type'] ) ) {
			$this->plugin_settings->set( 'partial_payment_option', $partial_payment['payment_type'] );
		}

		if ( isset( $partial_payment['payment_percent'] ) ) {
			$this->plugin_settings->set( 'partial_payment_percent', $partial_payment['payment_percent'] );
		}

		if ( isset( $partial_payment['payment_amount'] ) ) {
			$this->plugin_settings->set( 'partial_payment_amount', $partial_payment['payment_amount'] );
		}
		if ( isset( $partial_payment['enable_full_payment'] ) ) {
			$this->plugin_settings->set( 'full_payment_enable', wptravelengine_replace( $partial_payment['enable_full_payment'], true, 'yes', 'no' ) );
		}
	}

	/**
	 * Process Form Editor Settings of Extension Tab.
	 *
	 * @param WP_REST_Request $request Request object.
	 *
	 * @return void
	 */
	protected function set_form_editor_details( WP_REST_Request $request ) {

		if ( ! isset( $request['form_editor'] ) ) {
			return;
		}

		$form_editor = $request['form_editor'] ?? array();

		if ( isset( $form_editor['recaptcha_site_key'] ) ) {
			$this->plugin_settings->set( 'form_editor.grecaptcha_site_key', $form_editor['recaptcha_site_key'] );
		}

		if ( isset( $form_editor['recaptcha_secret_key'] ) ) {
			$this->plugin_settings->set( 'form_editor.recaptcha_v2_secret_key', $form_editor['recaptcha_secret_key'] );
		}
	}

	/**
	 * Process Currency Converter Settings of Extension Tab.
	 *
	 * @param WP_REST_Request $request Request object.
	 *
	 * @return void
	 */
	protected function set_currency_converter_details( WP_REST_Request $request ) {
		if ( ! isset( $request['currency_converter'] ) ) {
			return;
		}

		if ( isset( $request['currency_converter']['enable'] ) ) {
			$this->plugin_settings->set( 'currency_converter.enable', wptravelengine_replace( $request['currency_converter']['enable'], true, 'yes', '0' ) );
		}

		if ( isset( $request['currency_converter']['sticky_enable'] ) ) {
			$this->plugin_settings->set( 'currency_converter.sticky_enable', wptravelengine_replace( $request['currency_converter']['sticky_enable'], true, 'yes', '0' ) );
		}

		if ( isset( $request['currency_converter']['show_before_bkg'] ) ) {
			$this->plugin_settings->set( 'currency_converter.show_before_bkg', wptravelengine_replace( $request['currency_converter']['show_before_bkg'], true, 'yes', '0' ) );
		}

		if ( isset( $request['currency_converter']['title'] ) ) {
			$this->plugin_settings->set( 'currency_converter.title', $request['currency_converter']['title'] );
		}

		if ( isset( $request['currency_converter']['api_key'] ) ) {
			$this->plugin_settings->set( 'currency_converter.api_key', $request['currency_converter']['api_key'] );
		}

		if ( isset( $request['currency_converter']['key_type'] ) ) {
			$this->plugin_settings->set( 'currency_converter.key_type', $request['currency_converter']['key_type'] );
		}

		if ( isset( $request['currency_converter']['geo_locate'] ) ) {
			$this->plugin_settings->set( 'currency_converter.geo_locate', wptravelengine_replace( $request['currency_converter']['geo_locate'], true, 'yes', '0' ) );
		}

		if ( isset( $request['currency_converter']['auto_update'] ) ) {
			$this->plugin_settings->set( 'currency_converter.auto_update', wptravelengine_replace( $request['currency_converter']['auto_update'], true, 'yes', '0' ) );
		}

		if ( isset( $request['currency_converter']['currency_rate'] ) ) {
			$this->plugin_settings->set( 'currency_converter.currency_rate', $request['currency_converter']['currency_rate'] );
			$codes = array();
			$rates = array();

			foreach ( $request['currency_converter']['currency_rate'] as $key => $code ) {
				$codes[] = $code['code'];
			}

			foreach ( $request['currency_converter']['currency_rate'] as $key => $rate ) {
				$rates[] = $rate['rate'];
			}

			$this->plugin_settings->set( 'currency_converter.code', $codes );
			$this->plugin_settings->set( 'currency_converter.rate', $rates );
		}
	}

	/**
	 * Process Zapier Settings of Extension Tab.
	 *
	 * @param WP_REST_Request $request Request object.
	 *
	 * @return void
	 */
	protected function set_zapier_details( WP_REST_Request $request ) {
		if ( ! isset( $request['zapier'] ) ) {
			return;
		}

		if ( isset( $request['zapier']['enable_automation_booking'] ) ) {
			$this->plugin_settings->set( 'zapier.enable_automation_booking', wptravelengine_replace( $request['zapier']['enable_automation_booking'], true, 'yes', 'no' ) );
		}

		if ( isset( $request['zapier']['enable_automation_enquiry'] ) ) {
			$this->plugin_settings->set( 'zapier.enable_automation_enquiry', wptravelengine_replace( $request['zapier']['enable_automation_enquiry'], true, 'yes', 'no' ) );
		}

		if ( isset( $request['zapier']['booking_zaps'] ) && is_array( $request['zapier']['booking_zaps'] ) ) {
			$booking_zaps = array();
			foreach ( $request['zapier']['booking_zaps'] as $zap => $value ) {
				if ( isset( $value['name'] ) && isset( $value['url'] ) ) {
					$booking_zaps[ $zap + 1 ] = array(
						'name' => sanitize_text_field( $value['name'] ),
						'url'  => esc_url_raw( $value['url'] ),
					);
				}
			}
			$this->plugin_settings->set( 'zapier.booking_zaps', $booking_zaps );
		}

		if ( isset( $request['zapier']['enquiry_zaps'] ) && is_array( $request['zapier']['enquiry_zaps'] ) ) {
			$enquiry_zaps = array();
			foreach ( $request['zapier']['enquiry_zaps'] as $zap => $value ) {
				if ( isset( $value['name'] ) && isset( $value['url'] ) ) {
					$enquiry_zaps[ $zap + 1 ] = array(
						'name' => sanitize_text_field( $value['name'] ),
						'url'  => esc_url_raw( $value['url'] ),
					);
				}
			}
			$this->plugin_settings->set( 'zapier.enquiry_zaps', $enquiry_zaps );
		}
	}

	/**
	 * Process We Travel Settings of Extension Tab.
	 *
	 * @param WP_REST_Request $request Request object.
	 *
	 * @return void
	 */
	protected function set_we_travel_details( WP_REST_Request $request ) {

		if ( isset( $request['wetravel']['book_now_label'] ) ) {

			$settings = array(
				'book_now_label' => $request['wetravel']['book_now_label'],
			);

			Options::update( 'wte_wetravel_settings', $settings );
		}
	}

	/**
	 * Process User History Settings of Extension Tab.
	 *
	 * @param WP_REST_Request $request Request object.
	 *
	 * @return void
	 */
	protected function set_user_history_details( WP_REST_Request $request ) {

		if ( ! isset( $request['user_history'] ) ) {
			return;
		}

		$user_history = $request['user_history'] ?? array();

		if ( isset( $user_history['enable_tracking'] ) ) {
			$this->plugin_settings->set( 'user_history.disable', wptravelengine_replace( $user_history['enable_tracking'], false, '1' ) );
		}

		if ( isset( $user_history['enable_cookie_consent'] ) ) {
			$this->plugin_settings->set( 'user_history.cookie_consent.enable', wptravelengine_replace( $user_history['enable_cookie_consent'], true, '1' ) );
		}

		if ( isset( $user_history['cookie_position'] ) ) {
			$this->plugin_settings->set( 'user_history.cookie_consent.position', $user_history['cookie_position'] );
		}
		if ( isset( $user_history['cookie_layout'] ) ) {
			$this->plugin_settings->set( 'user_history.cookie_consent.layout', $user_history['cookie_layout'] );
		}
		if ( isset( $user_history['banner_bg_color'] ) ) {
			$this->plugin_settings->set( 'user_history.cookie_consent.banner_bg', $user_history['banner_bg_color'] );
		}
		if ( isset( $user_history['banner_btn_color'] ) ) {
			$this->plugin_settings->set( 'user_history.cookie_consent.button_bg', $user_history['banner_btn_color'] );
		}
		if ( isset( $user_history['banner_text_color'] ) ) {
			$this->plugin_settings->set( 'user_history.cookie_consent.content_text_col', $user_history['banner_text_color'] );
		}
		if ( isset( $user_history['banner_btn_text_color'] ) ) {
			$this->plugin_settings->set( 'user_history.cookie_consent.button_text_col', $user_history['banner_btn_text_color'] );
		}
		if ( isset( $user_history['learn_more_link'] ) ) {
			$this->plugin_settings->set( 'user_history.cookie_consent.learn_more_link', $user_history['learn_more_link'] );
		}
		if ( isset( $user_history['cookie_custom_message'] ) ) {
			$this->plugin_settings->set( 'user_history.cookie_consent.consent_message', $user_history['cookie_custom_message'] );
		}
		if ( isset( $user_history['dismiss_button_text'] ) ) {
			$this->plugin_settings->set( 'user_history.cookie_consent.dismiss_button_text', $user_history['dismiss_button_text'] );
		}
		if ( isset( $user_history['policy_link_text'] ) ) {
			$this->plugin_settings->set( 'user_history.cookie_consent.policy_link_text', $user_history['policy_link_text'] );
		}
	}

	/**
	 * Update Addons Settings.
	 *
	 * @param WP_REST_Request $request Request object.
	 * @param Settings        $settings_controller Settings controller instance.
	 *
	 * @return void
	 */
	public function update_addons_settings( WP_REST_Request $request, Settings $settings_controller ) {

		$this->plugin_settings = $settings_controller->plugin_settings;

		$this->set_weather_forcast_details( $request );
		$this->set_advanced_itinerary_details( $request );
		$this->set_group_discount_details( $request );
		$this->set_extra_services_details( $request );
		$this->set_file_downloads_details( $request );
		$this->set_itinerary_downloader_details( $request );
		$this->set_trip_reviews_details( $request );
		$this->set_fixed_starting_dates_details( $request );
		$this->set_partial_payment_details( $request );
		$this->set_form_editor_details( $request );
		$this->set_currency_converter_details( $request );
		$this->set_zapier_details( $request );
		$this->set_we_travel_details( $request );
		$this->set_user_history_details( $request );
	}
}
