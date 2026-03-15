<?php
/**
 * Check Coupon Code Controller.
 *
 * @package @WPTravelEngine/Core/Controllers
 * @since 6.0.0
 */

namespace WPTravelEngine\Core\Controllers\Ajax;

use WPTravelEngine\Abstracts\AjaxController;
use WPTravelEngine\Modules\CouponCode;

/**
 * Handles ajax request to check coupon.
 */
class CheckCoupon extends AjaxController {

	const NONCE_KEY    = 'nonce';
	const NONCE_ACTION = 'wp_travel_engine_check_coupon_code';
	const ACTION       = 'wp_travel_engine_check_coupon_code';

	/**
	 * Process Request.
	 * The coupon is set to default.
	 */
	protected function process_request() {
		$check_coupon_id   = $this->request->get_param( 'coupon_id' );
		$check_coupon_code = $this->request->get_param( 'coupon_code' );
		// phpcs:disable
		if ( empty( $check_coupon_code ) ) {
			\wp_send_json_error(
				new \WP_Error( 'WTE_INVALID_REQUEST', __( 'Coupon Code is required.', 'wp-travel-engine' ) )
			);
			die;
		}

		$post_id   = intval( sanitize_text_field( wte_clean( wp_unslash( $check_coupon_id ) ) ) );
		$coupon_id = CouponCode::coupon_id_by_code( wte_clean( wp_unslash( $check_coupon_code ) ) );

		if ( ! $coupon_id || $post_id === $coupon_id ) {
			wp_send_json_success(
				array(
					'status'      => 'valid',
					'coupon_code' => wte_clean( wp_unslash( $check_coupon_code ) ),
				)
			);
			die;
		}
		// phpcs:enable

		\wp_send_json_error(
			new \WP_Error( 'INVALID_COUPON_CODE', __( 'Invalid Coupon code.', 'wp-travel-engine' ) )
		);
		die;
	}
}
