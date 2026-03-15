<?php
/**
 * Abstract Payment Gateway.
 *
 * @package WPTravelEngine\Abstracts
 * @since 6.0.0
 */

namespace WPTravelEngine\Abstracts;

use WPTravelEngine\Core\Booking\BookingProcess;
use WPTravelEngine\Core\Models\Post\Booking;
use WPTravelEngine\Core\Models\Post\Payment;
use WPTravelEngine\Core\Models\Settings\PluginSettings;

abstract class PaymentGateway {

	/**
	 * Get label.
	 *
	 * @return string
	 */
	abstract public function get_label(): string;

	/**
	 * Get public label.
	 *
	 * @return string
	 */
	abstract public function get_public_label(): string;

	/**
	 * Get gateway id.
	 *
	 * @return string
	 */
	abstract public function get_gateway_id(): string;

	/**
	 * Cart version to be used for processing payment.
	 *
	 * @since 6.7.0
	 */
	public static string $cart_version = '3.0';


	/**
	 * Process Payment.
	 *
	 * @param Booking        $booking Booking.
	 * @param Payment        $payment Payment.
	 * @param BookingProcess $booking_instance Booking Process.
	 *
	 * @return void
	 */
	public function process_payment( Booking $booking, Payment $payment, BookingProcess $booking_instance ) {
		_doing_it_wrong( __METHOD__, __( 'This method should be overridden in the child class.', 'wp-travel-engine' ), '6.0.0' );
	}

	/**
	 * Process Payment V2.
	 * This method has higher priority than process_payment.
	 *
	 * @param Booking        $booking Booking.
	 * @param Payment        $payment Payment.
	 * @param BookingProcess $booking_instance Booking Process.
	 *
	 * @return void
	 * @since 6.7.0
	 */
	public function process_payment_v2( Booking $booking, Payment $payment, BookingProcess $booking_instance ) {
		// Process the payment according to cart version 4.0.
		// If process_payment_v2 is not implemented, then process the payment according to cart version 3.0.
		$this->process_payment( $booking, $payment, $booking_instance );
	}

	/**
	 * Get icon.
	 *
	 * @return string
	 */
	public function get_icon(): string {
		return '';
	}

	/**
	 * Public icon shows at the time of checkout.
	 *
	 * @return string
	 */
	public function get_display_icon(): string {
		return '';
	}

	/**
	 * Public info shows at the time of checkout.
	 *
	 * @return string
	 */
	public function get_info(): string {
		return '';
	}

	/**
	 * Gateway Description.
	 *
	 * @return string
	 */
	public function get_description(): string {
		return '';
	}

	/**
	 * Get gateway args.
	 *
	 * @return array
	 */
	public function get_args(): array {
		return array();
	}

	/**
	 * Check if gateway is active.
	 *
	 * @return bool
	 */
	public function is_active(): bool {
		return PluginSettings::make()->get( $this->get_gateway_id() ) == '1';
	}

	/**
	 * Check if gateway is enabled.
	 *
	 * @param string $currency
	 *
	 * @return bool
	 */
	public function is_supports_currency( string $currency ): bool {
		return true;
	}

	/**
	 * If Test or debug mode is enabled.
	 *
	 * @return bool
	 */
	public function is_test_mode(): bool {
		return ( defined( 'WP_TRAVEL_ENGINE_PAYMENT_DEBUG' ) && WP_TRAVEL_ENGINE_PAYMENT_DEBUG ) || ! wptravelengine_settings()->is( "{$this->get_gateway_id()}_test_mode", 'no' );
	}

	/**
	 * Compare cart version with the provided version.
	 *
	 * @param string $op Operator to compare.
	 * @param string $ver Version to compare with. Default is '4.0'.
	 *
	 * @return bool
	 * @since 6.7.0
	 */
	public static function is_curr_cart( string $op = '==', string $ver = '4.0' ): bool {
		return version_compare( static::$cart_version, $ver, $op );
	}
}
