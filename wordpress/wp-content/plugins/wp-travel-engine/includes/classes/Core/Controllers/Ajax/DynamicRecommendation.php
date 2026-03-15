<?php
/**
 * Dynamic Recommendation Controller.
 *
 * @package WPTravelEngine/Core/Controllers
 * @since 6.0.0
 */

namespace WPTravelEngine\Core\Controllers\Ajax;

use WPTravelEngine\Abstracts\AjaxController;

/**
 * Handles the on board dynamic recommendation ajax request.
 */
class DynamicRecommendation extends AjaxController {

	const NONCE_KEY    = '_nonce';
	const NONCE_ACTION = 'wpte_onboard_save_function';
	const ACTION       = 'wte_onboard_dynamic_recommendation';

	/**
	 * Process Request.
	 * Output for recommentation payment gateways.
	 */
	public function process_request() {
		// phpcs:ignore
		$currency_code = wte_clean( wp_unslash( $this->request->get_param('currency_code') ?? 'USD' ) );
		$addons_data   = get_transient( 'wp_travel_engine_onboard_addons_list' );
		if ( ! $addons_data ) {
			$addons_data = wp_safe_remote_get( WP_TRAVEL_ENGINE_STORE_URL . '/edd-api/v2/products/?category=payment-gateways&number=-1' );
			if ( is_wp_error( $addons_data ) ) {
				return;
			}
			$addons_data = wp_remote_retrieve_body( $addons_data );
			set_transient( 'wp_travel_engine_onboard_addons_list', $addons_data, 128 * HOUR_IN_SECONDS );
		}

		if ( ! empty( $addons_data ) ) {
			$addons_data = json_decode( $addons_data );
			$addons_data = $addons_data->products;
		}
		if ( $addons_data ) {
			?>
			<div class="wpte-field wpte-block-link wpte-floated">
				<?php
				foreach ( $addons_data as $product ) {
					$prod_info           = isset( $product ) && ! empty( $product ) ? $product->info : '';
					$wte_object          = ! empty( $product->wte ) ? $product->wte : '';
					$suported_currencies = is_object( $wte_object ) && ! empty( $wte_object ) ? $wte_object->supported_currencies : array();
					if ( in_array( $currency_code, $suported_currencies, true ) ) {
						$link = \WP_TRAVEL_ENGINE_STORE_URL . "plugins/{$product->info->slug}";
						?>
						<a href="<?php echo esc_url( $link ); ?>" title="<?php echo esc_html( $prod_info->title ); ?>" target="_blank">
							<img src="<?php echo esc_url( $prod_info->thumbnail ); ?>" class="attachment-showcase wp-post-image" alt="<?php echo esc_html( $prod_info->title ); ?>">
						</a>
						<?php
					}
				}
				?>
			</div>
			<?php
		}
		die();
	}
}
