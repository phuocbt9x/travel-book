<?php
/**
 * Payment Model.
 *
 * @package WPTravelEngine/Core/Models
 * @since 6.0.0
 */

namespace WPTravelEngine\Core\Models\Post;

use Error;
use InvalidArgumentException;
use WPTravelEngine\Abstracts\PostModel;

/**
 * Class Payment.
 * This class represents a payment to the WP Travel Engine plugin.
 *
 * @since 6.0.0
 */
class Payment extends PostModel {

	/**
	 * Post type name.
	 *
	 * @var string
	 */
	protected string $post_type = 'wte-payments';

	/**
	 * Get Payment Amount.
	 *
	 * @return float
	 */
	public function get_amount(): float {
		return (float) ( $this->get_meta( 'payment_amount' )['value'] ?? 0 );
	}

	/**
	 * Get Payment Currency.
	 *
	 * @return string
	 */
	public function get_currency(): string {
		return $this->get_meta( 'payment_amount' )['currency'] ?? $this->get_payable_currency();
	}

	/**
	 * Get Payment Status.
	 *
	 * @return string
	 */
	public function get_payment_status(): string {
		return $this->get_meta( 'payment_status' ) ?: 'pending';
	}

	/**
	 * Get Payment Gateway Response.
	 *
	 * @return string|array
	 */
	public function get_gateway_response() {
		return $this->get_meta( 'gateway_response' );
	}

	/**
	 * Get Payment Gateway.
	 *
	 * @return string
	 */
	public function get_payment_gateway(): string {
		return $this->get_meta( 'payment_gateway' ) ?? '';
	}

	/**
	 * Get Payment Source.
	 *
	 * Returns 'checkout' for payments created through checkout, 'admin' for manual/admin payments.
	 * For legacy payments without payment_source, falls back to inferring from billing_info.
	 *
	 * @return string
	 * @since 6.7.3
	 */
	public function get_payment_source(): string {
		$billing_info   = $this->get_billing_info();
		$payment_source = $this->get_meta( 'payment_source' );
		return $billing_info ? 'checkout' : ( $payment_source ?: 'admin' );
	}

	/**
	 * Get Billing Information.
	 *
	 * @return array
	 */
	public function get_billing_info(): array {
		return $this->get_meta( 'billing_info' ) ?: array();
	}

	/**
	 * Get Payable Amount.
	 *
	 * @return string
	 */
	public function get_payable_amount(): float {
		return (float) ( $this->get_meta( 'payable' )['amount'] ?? 0 );
	}

	/**
	 * Get Tax Amount.
	 *
	 * @return float
	 * @since 6.7.0
	 */
	public function get_tax_amount(): float {
		$booking_id = $this->get_meta( 'booking_id' );
		$booking    = Booking::make( $booking_id );
		$tax_amount = $booking->get_tax_amount();
		return (float) ( $tax_amount ?? 0 );
	}

	/**
	 * Get Payable Currency.
	 *
	 * @return string
	 */
	public function get_payable_currency(): string {
		return $this->get_meta( 'payable' )['currency'] ?? '';
	}

	/**
	 * Checks if payment is successful.
	 *
	 * @return bool
	 * @updated 6.7.0
	 */
	public function is_completed(): bool {
		$success_status = wptravelengine_success_payment_status();
		return isset( $success_status[ $this->get_payment_status() ] );
	}

	/**
	 * Checks if payment is failed.
	 *
	 * @return bool
	 * @since 6.7.1
	 */
	public function is_failed(): bool {
		$failed_status = wptravelengine_failed_payment_status();
		return isset( $failed_status[ $this->get_payment_status() ] );
	}

	/**
	 * Checks if payment is pending.
	 *
	 * @return bool
	 * @since 6.7.1
	 */
	public function is_pending(): bool {
		$pending_status = wptravelengine_pending_payment_status();
		return isset( $pending_status[ $this->get_payment_status() ] );
	}

	/**
	 * Update Payment Status.
	 *
	 * @return void
	 * @since 6.7.1 Added previous payment status meta.
	 */
	public function update_status( $status ) {
		$this->set_status( $status );
		$this->save();

		unset( $this->data['_prev_payment_status'] );
		unset( $this->data['payment_status'] );
	}

	/**
	 * Generates Payment Key.
	 *
	 * @return string
	 */
	public function get_payment_key(): string {
		return wptravelengine_generate_key( $this->get_id() );
	}

	/**
	 * Get Booking.
	 *
	 * @return ?Booking
	 */
	public function get_booking(): ?Booking {
		return wptravelengine_get_booking( $this->get_meta( 'booking_id' ) );
	}

	/**
	 * Set Payment Status.
	 *
	 * @param string $status Payment Status.
	 * @since 6.7.1 Added previous payment status meta.
	 */
	public function set_status( string $status ) {
		$this->set_meta( '_prev_payment_status', $this->get_payment_status() );
		$this->set_meta( 'payment_status', $status );
	}

	/**
	 * Set Payment Gateway.
	 *
	 * @param string $gateway Payment Gateway.
	 */
	public function set_payment_gateway( string $gateway ) {
		$this->set_meta( 'payment_gateway', $gateway );
	}

	/**
	 * Set Payment Gateway Response.
	 *
	 * @param string $payment_key
	 *
	 * @return ?Payment
	 * @throws InvalidArgumentException
	 */
	public static function from_payment_key( string $payment_key ): ?Payment {
		if ( empty( $payment_key ) ) {
			throw new InvalidArgumentException( 'Invalid Payment Key' );
		}

		$payment_id = get_transient( 'payment_key_' . $payment_key );

		if ( ! $payment_id ) {
			throw new InvalidArgumentException( 'Invalid Payment Key' );
		}

		return new static( $payment_id );
	}

	/**
	 * @return string
	 * @since 6.4.0
	 */
	public function get_transaction_id(): string {
		return $this->get_meta( 'transaction_id' ) ?: $this->get_meta( '_transaction_id' ) ?: '';
	}

	/**
	 * @param string $data
	 *
	 * @return void
	 * @since 6.4.0
	 */
	public function set_transaction_id( string $data ) {
		$this->set_meta( 'transaction_id', $data );
	}

	/**
	 * @return string
	 * @since 6.4.0
	 */
	public function get_transaction_date(): string {
		return $this->get_meta( 'transaction_date' );
	}

	/**
	 * @param string $data
	 *
	 * @return void
	 * @since 6.4.0
	 */
	public function set_transaction_date( string $data ) {
		$this->set_meta( 'transaction_date', $data );
	}

	/**
	 * @return array
	 * @since 6.5.2
	 */
	public function get_data(): array {
		$booking = $this->get_booking();

		$data = array(
			'id'             => $this->ID,
			'status'         => $this->get_payment_status(),
			'paid_amount'    => $this->get_amount(),
			'currency'       => $this->get_currency(),
			'payment_method' => $this->get_payment_gateway(),
		);
		if ( $booking ) {
			$data['booking_id']     = $booking->get_id();
			$data['booking_status'] = $booking->get_booking_status();
			$data['booked_trip']    = array(
				'id'              => $booking->get_trip_id(),
				'title'           => $booking->get_trip_title(),
				'url'             => get_permalink( $booking->get_trip_id() ),
				'trip_start_date' => $booking->get_order_trip()->datetime,
			);
			$data['customer']       = $booking->get_customer();
		}

		return $data;
	}

	/**
	 * Get Cart totals.
	 *
	 * @param string|null $key Optional. Specific cart total key to retrieve.
	 *
	 * @return float|array
	 * @since 6.7.0
	 */
	public function get_cart_totals( $key = null ) {
		$cart_totals = $this->get_meta( 'cart_totals' ) ?: array();
		return null === $key ? $cart_totals : ( $cart_totals[ $key ] ?? 0 );
	}

	/**
	 * Get Payment Status Label.
	 *
	 * @return string
	 * @since 6.7.3
	 */
	public function get_payment_status_label(): string {
		$all_payment_details = wptravelengine_payment_status();
		return $all_payment_details[ $this->get_payment_status() ] ?? $this->get_payment_status();
	}
}
