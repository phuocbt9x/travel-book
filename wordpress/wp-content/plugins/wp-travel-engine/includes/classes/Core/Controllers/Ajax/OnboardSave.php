<?php
/**
 * Onboard Save Controller.
 *
 * @package @WPTravelEngine/Core/Controllers
 * @since 6.0.0
 */

namespace WPTravelEngine\Core\Controllers\Ajax;

use WPTravelEngine\Abstracts\AjaxController;
use WPTravelEngine\Core\Models\Settings\PluginSettings;
use WPTravelEngine\Core\Models\Settings\Options;

/**
 * Handles onboard save ajax functionalities.
 */
class OnboardSave extends AjaxController {

	const NONCE_KEY    = '_nonce';
	const NONCE_ACTION = 'wpte_onboard_save_function';
	const ACTION       = 'wpte_onboard_save_function';
	const ALLOW_NOPRIV = false;

	/**
	 * Process Request.
	 * Save & continue button callback
	 *
	 * @return void
	 */
	protected function process_request() {
		// phpcs:disable
		if ( 'wpte_onboard_save_function' === ( $this->request->get_param('action') ?? null ) ) {

			$settings 					= $this->request->get_param('wp_travel_engine_settings');
			$setting_to_save 			= wte_clean( wp_unslash( $settings ?? array() ) );
			$wp_travel_engine_settings  = ( new PluginSettings() )->get();
			
			if ( isset( $setting_to_save ) && is_array( $setting_to_save ) ) {
				foreach ( $setting_to_save as $key => $value ) {
					if ( is_array( $value ) ) {
						$wp_travel_engine_settings[$key] = array_diff( $wp_travel_engine_settings[$key], array_values( $value ) ) + $value;
					} else {
						$wp_travel_engine_settings[$key] = $value;
					}
				}
			}

			Options::update( 'wp_travel_engine_settings', $wp_travel_engine_settings );

			$message_array = array( 'message' => __( 'Settings Saved Sucessfully', 'wp-travel-engine' ) );

			$currency_code = wte_clean( wp_unslash( $settings['currency_code'] ?? '' ) );
			if ( ! empty( $currency_code ) ) {
				$additional_message = array(
					'additional_message' => 'yes',
					'currency_code'      => $currency_code,
				);
				$message_array      = array_merge( $message_array, $additional_message );
			}

			wp_send_json_success( $message_array );
		} else {
			wp_send_json_error( array( 'message' => __( 'Unauthorized Access. Aborting.', 'wp-travel-engine' ) ) );
		}
		wp_die();
		// phpcs:enable
	}
}
