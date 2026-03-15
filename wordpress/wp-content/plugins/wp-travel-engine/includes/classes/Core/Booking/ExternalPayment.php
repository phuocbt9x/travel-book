<?php
/**
 * Handle External Payment Request.
 *
 * @since 6.4.0
 */

namespace WPTravelEngine\Core\Booking;

use WPTravelEngine\Core\Controllers\Ajax\AddToCart;
use WPTravelEngine\Core\Models\Post\Payment;
use WPTravelEngine\Helpers\Functions;
use WPTravelEngine\Utilities\RequestParser;

/**
 *
 * @since 6.4.0.
 */
class ExternalPayment {

	public function __construct( RequestParser $request ) {
		$key = $request->get_param( '_payment_key' );

		$result = array(
			'redirect' => home_url( '/404' ),
		);

		if ( $data = get_transient( '_payment_key_' . $key ) ) {
			$data = json_decode( $data, true );

			$booking_id = $data['booking_id'] ?? false;

			if ( $booking_id ) {
				$request = Functions::create_request( 'POST' );

				$request->set_body(
					wp_json_encode(
						array(
							'_nonce'       => wp_create_nonce( 'wte_add_trip_to_cart' ),
							'cart_version' => '2.0',
							'booking_id'   => $booking_id,
						)
					)
				);

				$result = AddToCart::create( $request )->add_to_cart();
			}
		}

		if ( ! headers_sent() ) {
			if ( isset( $result['redirect'] ) ) {
				wp_safe_redirect( $result['redirect'] );
				exit;
			}
		}
	}

	public static function is_request(): bool {
		if ( isset( $_REQUEST['_payment_key'] ) ) {
			return true;
		}

		return false;
	}
}
