<?php
/**
 * Payment Gateways.
 *
 * @package WPTravelEngine\PaymentGateways
 */

namespace WPTravelEngine\PaymentGateways;

use WPTravelEngine\Abstracts\PaymentGateway;
use WPTravelEngine\PaymentGateways\StandardPaypal\Gateway as PayPalGateway;
use WPTravelEngine\Traits\Singleton;

/**
 * Payment Gateways.
 *
 * @since 6.0.0
 */
class PaymentGateways {

	use Singleton;

	/**
	 * Payment Gateways.
	 *
	 * @var PaymentGateway[]|BaseGateway[]
	 */
	protected static array $payment_gateways = array();

	/**
	 * PaymentGateways constructor.
	 */
	protected function __construct() {
		$this->register_gateways();
	}

	/**
	 * Get Payment Gateways.
	 */
	public function get_payment_gateways( $to_array = false ): array {
		$gateways = array();
		if ( $to_array ) {
			foreach ( self::$payment_gateways as $gateway ) {
				$gateways[ $gateway->get_gateway_id() ] = $gateway->get_args();
			}

			return $gateways;
		}

		return self::$payment_gateways;
	}

	/**
	 * Get Payment Gateway.
	 *
	 * @param string $gateway_id Gateway ID.
	 *
	 * @return ?PaymentGateway
	 */
	public function get_payment_gateway( string $gateway_id ): ?PaymentGateway {
		return self::$payment_gateways[ $gateway_id ] ?? null;
	}

	/**
	 * Get Active Payment Gateways.
	 *
	 * @param bool $to_array
	 *
	 * @return array
	 * @updated 6.7.0
	 */
	public function get_active_payment_gateways( bool $to_array = false ): array {
		global $wte_cart;
		$active_gateways = array();
		$currency        = wptravelengine_settings()->get( 'currency_code', 'USD' );

		foreach ( self::$payment_gateways as $gateway ) {

			if ( $wte_cart->get_booking_ref() && $wte_cart->is_curr_cart( '>', $gateway::$cart_version ) ) {
				continue;
			}

			if ( $gateway->is_active() && $gateway->is_supports_currency( $currency ) ) {
				$active_gateways[ $gateway->get_gateway_id() ] = $to_array ? $gateway->get_args() : $gateway;
			}
		}

		return $active_gateways;
	}

	/**
	 * Register Payment Gateway.
	 *
	 * @param PaymentGateway $gateway Gateway Object.
	 */
	public function register_gateway( PaymentGateway $gateway ) {
		self::$payment_gateways[ $gateway->get_gateway_id() ] = $gateway;
	}

	/**
	 * Deregister Payment Gateway.
	 *
	 * @param string $gateway_id Gateway ID.
	 */
	public function deregister_gateway( string $gateway_id ) {
		unset( self::$payment_gateways[ $gateway_id ] );
	}

	/**
	 * Register Payment Gateways.
	 */
	protected function register_gateways() {

		$payment_gateways = array(
			'booking_only'         => new BookingOnly(),
			'paypal_payment'       => new PayPalGateway(),
			'direct_bank_transfer' => new DirectBankTransfer(),
			'check_payments'       => new CheckPayment(),
		);

		$gateways = apply_filters_deprecated( 'wp_travel_engine_available_payment_gateways', array( array() ), '6.0.0' );

		foreach ( $gateways as $gateway_id => $gateway ) {
			if ( is_array( $gateway ) ) {
				$payment_gateways[ $gateway_id ] = BaseGateway::create( $gateway_id, $gateway );
			}
		}

		$payment_gateways = apply_filters( 'wptravelengine_registering_payment_gateways', $payment_gateways, $this );

		foreach ( $payment_gateways as $gateway ) {
			if ( $gateway instanceof PaymentGateway ) {
				$this->register_gateway( $gateway );
			} else {
				_doing_it_wrong( __METHOD__, 'Payment Gateway must be an instance of PaymentGateway', '6.0.0' );
			}
		}

		do_action( 'wptravelengine_payment_gateways_registered', $this );
	}
}
