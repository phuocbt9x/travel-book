<?php
/**
 * Save Global Setting Controller.
 *
 * @package WPTravelEngine/Core/Controllers
 * @since 6.0.0
 */

namespace WPTravelEngine\Core\Controllers\Ajax;

use WPTravelEngine\Abstracts\AjaxController;
use WPTravelEngine\Core\Models\Settings\PluginSettings;
use WPTravelEngine\Core\Models\Settings\Options;
use WPTravelEngine\Core\Models\Settings\StaticStrings;

/**
 * Saves global settings.
 */
class SaveGlobalSettings extends AjaxController {

	const NONCE_KEY    = 'nonce';
	const NONCE_ACTION = 'wpte_global_tabs_save_data';
	const ACTION       = 'wpte_global_tabs_save_data';
	const ALLOW_NOPRIV = false;

	/**
	 * Process request.
	 */
	public function process_request() {

		$posted_data = self::get_sanitized_posted_data( $this->request->get_body_params() );

		$plugin_settings = new PluginSettings();

		$new_settings = $this->request->get_param( 'wp_travel_engine_settings' );
		if ( $new_settings ) {
			foreach ( $new_settings as $key => $value ) {
				// TODO: Validate data type and schema.
				$plugin_settings->set( $key, $value );
			}

			if ( isset( $new_settings['pages']['search'] ) ) {
				Options::update( 'wp_travel_engine_search_page_id', (int) $new_settings['pages']['search'] );
			}

			$plugin_settings->save();
		}

		if ( $value = $this->request->get_param( 'wptravelengine_trip_sort_by' ) ) {
			Options::update( 'wptravelengine_trip_sort_by', sanitize_text_field( wp_unslash( $value ) ) );
		}

		if ( $value = $this->request->get_param( 'wptravelengine_trip_view_mode' ) ) {
			if ( in_array( $value, array( 'list', 'grid' ), true ) ) {
				Options::update( 'wptravelengine_trip_view_mode', sanitize_text_field( wp_unslash( $value ) ) );
			}
		}

		// Save Custom Translation Strings.
		$new_strings = $this->request->get_param( 'wptravelengine_custom_strings' );
		if ( ! is_null( $new_strings ) ) {
			$static_strings = new StaticStrings();
			$static_strings->update( $new_strings );
		}

		if ( isset( $posted_data['wp_travel_engine_settings'] ) ) {

			$active_tab = $posted_data['tab'];

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
			 * Hook for addons global settings.
			 */
			do_action( 'wpte_after_save_global_settings_data', $this->request->get_params() );

			wp_send_json_success( array( 'message' => 'Settings Saved Successfully.' ) );

		}
	}

	/**
	 * Retrieves sanitized posted data.
	 *
	 * @param array $posted_data Posted data.
	 */
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

		return wte_input_clean( $posted_data, $special_fields );
	}
}
