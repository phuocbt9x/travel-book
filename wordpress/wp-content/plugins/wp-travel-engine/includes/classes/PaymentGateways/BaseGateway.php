<?php
/**
 * Base Payment Gateway.
 *
 * @package WPTravelEngine\PaymentGateways
 */

namespace WPTravelEngine\PaymentGateways;

use WPTravelEngine\Abstracts\PaymentGateway;
use WPTravelEngine\Core\Booking\BookingProcess;
use WPTravelEngine\Core\Models\Post\Booking;
use WPTravelEngine\Core\Models\Post\Payment;

/**
 * Base Payment Gateway.
 *
 * This is a base gateway class to support for all existing gateways.
 * All the payment gateways should extend PaymentGateway class instead depending on this class.
 *
 * @since 6.0.0
 */
class BaseGateway extends PaymentGateway {

	/**
	 * @var mixed
	 */
	protected $gateway_id = '';

	/**
	 * Legacy Args.
	 *
	 * @var array $args
	 */
	protected array $args;

	/**
	 * Get icon.
	 *
	 * @return string
	 */
	public function get_icon(): string {
		return $this->args['icon_url'] ?? '';
	}


	/**
	 * Get display icon.
	 *
	 * @return string
	 */
	public function get_display_icon(): string {
		return $this->args['display_icon'] ?? '';
	}

	/**
	 * Public info shows at the time of checkout.
	 *
	 * @return string
	 */
	public function get_info(): string {
		return $this->args['info_text'] ?? '';
	}

	/**
	 * @inerhitDoc
	 */
	public function process_payment( Booking $booking, Payment $payment, BookingProcess $booking_instance ): void {
		/**
		 * Recommended for WTE Payment Addons.
		 *
		 * @since 4.3.0
		 */
		do_action(
			"wte_payment_gateway_{$this->gateway_id}",
			$payment->get_id(),
			$booking_instance->get_payment_type(),
			$this->get_gateway_id(),
		);
	}

	/**
	 * @inheritDoc
	 */
	public function get_label(): string {
		return $this->args['label'] ?? '';
	}

	/**
	 * @inheritDoc
	 */
	public function get_public_label(): string {
		return $this->args['public_label'] ?? '';
	}

	/**
	 * @inheritDoc
	 */
	public function get_gateway_id(): string {
		return $this->gateway_id;
	}

	/**
	 * Create Instance of Gateway.
	 *
	 * @return $this
	 */
	public static function create( $gateway_id, $args = array() ): BaseGateway {
		$instance             = new static();
		$instance->gateway_id = $gateway_id;
		$instance->args       = $args;

		return $instance;
	}

	/**
	 * @return bool
	 */
	public function enabled_payment_debug() {
		return defined( 'WP_TRAVEL_ENGINE_PAYMENT_DEBUG' ) && WP_TRAVEL_ENGINE_PAYMENT_DEBUG;
	}

	/**
	 * @param Payment $payment
	 * @param string  $callback_type success|cancel|notification
	 * @param array   $query_args
	 *
	 * @return string
	 */
	public function get_callback_url( Payment $payment, string $callback_type = 'success', array $query_args = array() ): string {
		$payment_key = $payment->get_payment_key();

		set_transient( "payment_key_$payment_key", $payment->get_id(), 60 * 10 );

		$base_url = home_url( '/' );
		if ( $this->enabled_payment_debug() ) {
			$base_url = defined( 'WPTRAVELENGINE_TUNNEL_URL' ) ? WPTRAVELENGINE_TUNNEL_URL : $base_url;
		}

		$url = add_query_arg( array_merge( compact( 'payment_key', 'callback_type' ), $query_args ), $base_url );

		return apply_filters( 'wptravelengine_payment_gateway_callback_url', $url, $payment, $callback_type );
	}

	/**
	 * Get gateway args.
	 *
	 * @return array
	 */
	public function get_args(): array {
		return array(
			'label'        => $this->get_label(),
			'input_class'  => '',
			'public_label' => $this->get_public_label(),
			'gateway_id'   => $this->get_gateway_id(),
			'icon_url'     => $this->get_icon(),
			'info_text'    => $this->get_info(),
			'description'  => $this->get_description(),
			'display_icon' => $this->get_display_icon(),
		);
	}

	/**
	 * Handle cancel request.
	 *
	 * @return void
	 */
	public function handle_cancel_request( Booking $booking, Payment $payment ) {
		$payment->update_status( 'canceled' );
		delete_transient( "payment_key_{$payment->get_payment_key()}" );
	}
}
