<?php
/**
 * Direct Bank Transfer Payment Gateway
 *
 * @package WPTravelEngine\PaymentGateways
 * @since 6.0.0
 */

namespace WPTravelEngine\PaymentGateways;

use WPTravelEngine\Core\Models\Post\Booking;
use WPTravelEngine\Core\Models\Post\Payment;
use WPTravelEngine\Core\Booking\BookingProcess;

/**
 * Direct Bank Transfer Payment Gateway
 *
 * @since 6.0.0
 */
class DirectBankTransfer extends BaseGateway {

	/**
	 * @inheritDoc
	 */
	public static string $cart_version = '4.0';

	/**
	 * Get gateway id.
	 *
	 * @return string
	 */
	public function get_gateway_id(): string {
		return 'direct_bank_transfer';
	}

	/**
	 * Get label
	 *
	 * @return string
	 */
	public function get_label(): string {
		return wptravelengine_settings()->get( 'bank_transfer.title', __( 'Bank Transfer', 'wp-travel-engine' ) );
	}

	/**
	 * Get public label.
	 *
	 * @return string
	 */
	public function get_public_label(): string {
		return __( 'Direct Bank Transfer', 'wp-travel-engine' );
	}

	/**
	 * Get info.
	 *
	 * @return string
	 */
	public function get_info(): string {
		return wptravelengine_settings()->get( 'bank_transfer.instruction', __( 'Please make your payment on the provided bank accounts.', 'wp-travel-engine' ) );
	}

	/**
	 * Get description.
	 *
	 * @return string
	 */
	public function get_description(): string {
		return wptravelengine_settings()->get( 'bank_transfer.description', __( 'Make your payment directly into our bank account. Please use your Order ID as the payment reference. Your order will not be shipped until the funds have cleared in our account.', 'wp-travel-engine' ) );
	}

	/**
	 * Get icon.
	 *
	 * @return string
	 */
	public function get_icon(): string {
		return '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M5 9V17M9.5 9V17M14.5 9V17M19 9V17M3 18.6L3 19.4C3 19.9601 3 20.2401 3.10899 20.454C3.20487 20.6422 3.35785 20.7951 3.54601 20.891C3.75992 21 4.03995 21 4.6 21H19.4C19.9601 21 20.2401 21 20.454 20.891C20.6422 20.7951 20.7951 20.6422 20.891 20.454C21 20.2401 21 19.9601 21 19.4V18.6C21 18.0399 21 17.7599 20.891 17.546C20.7951 17.3578 20.6422 17.2049 20.454 17.109C20.2401 17 19.9601 17 19.4 17H4.6C4.03995 17 3.75992 17 3.54601 17.109C3.35785 17.2049 3.20487 17.3578 3.10899 17.546C3 17.7599 3 18.0399 3 18.6ZM11.6529 3.07713L4.25291 4.72158C3.80585 4.82092 3.58232 4.8706 3.41546 4.9908C3.26829 5.09683 3.15273 5.2409 3.08115 5.40757C3 5.59652 3 5.82551 3 6.28347L3 7.4C3 7.96005 3 8.24008 3.10899 8.45399C3.20487 8.64215 3.35785 8.79513 3.54601 8.89101C3.75992 9 4.03995 9 4.6 9H19.4C19.9601 9 20.2401 9 20.454 8.89101C20.6422 8.79513 20.7951 8.64215 20.891 8.45399C21 8.24008 21 7.96005 21 7.4V6.28348C21 5.82551 21 5.59653 20.9188 5.40757C20.8473 5.2409 20.7317 5.09683 20.5845 4.9908C20.4177 4.8706 20.1942 4.82092 19.7471 4.72158L12.3471 3.07713C12.2176 3.04835 12.1528 3.03396 12.0874 3.02822C12.0292 3.02312 11.9708 3.02312 11.9126 3.02822C11.8472 3.03396 11.7824 3.04835 11.6529 3.07713Z" stroke="#3E4B50" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
    	</svg>';
	}

	/**
	 * Get display icon.
	 *
	 * @return string
	 */
	public function get_display_icon(): string {
		return $this->get_icon();
	}

	/**
	 * @inheritDoc
	 * @updated 6.7.0
	 */
	public function process_payment( Booking $booking, Payment $payment, $booking_instance ): void {
		if ( $booking->is_curr_cart( '<' ) ) {
			$this->process_payment_before_v4( $booking, $payment, $booking_instance );
		} else {
			$this->process_payment_in_v4( $booking, $payment, $booking_instance );
		}
	}

	/**
	 * Process payment before v4.
	 *
	 * @param Booking        $booking Booking object.
	 * @param Payment        $payment Payment object.
	 * @param BookingProcess $booking_instance Booking process object.
	 * @return void
	 */
	protected function process_payment_before_v4( Booking $booking, Payment $payment, $booking_instance ): void {
		$payable = $payment->get_meta( 'payable' );
		$amount  = array(
			'value'    => (float) $payable['amount'],
			'currency' => $payable['currency'],
		);
		$payment->set_meta( 'payment_status', 'voucher-waiting' );
		$payment->set_payment_gateway( $this->get_gateway_id() );
		$payment->set_meta( 'payment_amount', $amount );
		$payment->save();
		$paid_amount = (float) $booking->get_paid_amount();
		$due_amount  = (float) $booking->get_due_amount();
		$booking->set_meta( 'paid_amount', $paid_amount + $amount['value'] );
		$booking->set_meta( 'due_amount', max( $due_amount - $amount['value'], 0 ) );
		$booking->save();
	}

	/**
	 * Process payment in v4.
	 *
	 * @param Booking        $booking Booking object.
	 * @param Payment        $payment Payment object.
	 * @param BookingProcess $booking_instance Booking process object.
	 * @return void
	 * @since 6.7.0
	 */
	protected function process_payment_in_v4( Booking $booking, Payment $payment, $booking_instance ): void {
		global $wte_cart;
		$amount = $wte_cart->get_total_payable_amount();
		$booking->sync_payment_pending_metas( $payment->ID, $amount );
	}

	/**
	 * Print bank details.
	 *
	 * @since 6.3.3
	 */
	public function print_instruction( int $payment_id ) {
		$instruction  = $this->get_info();
		$bank_details = wptravelengine_settings()->get( 'bank_transfer.accounts', array() );
		if ( ! is_array( $bank_details ) ) {
			return;
		}

		$keys = array(
			'bank_name'      => __( 'Bank:', 'wp-travel-engine' ),
			'account_name'   => __( 'Account Name:', 'wp-travel-engine' ),
			'account_number' => __( 'Account Number:', 'wp-travel-engine' ),
			'sort_code'      => __( 'Sort Code:', 'wp-travel-engine' ),
			'iban'           => __( 'IBAN:', 'wp-travel-engine' ),
			'swift'          => __( 'BIC/SWIFT:', 'wp-travel-engine' ),
		);

		$bank_details = array_map(
			function ( $bank_detail ) use ( $keys ) {
				$_bank_detail = array();

				foreach ( $keys as $key => $label ) {
					if ( ! empty( $bank_detail[ $key ] ) ) {
						$_bank_detail[ $key ] = array(
							'label' => $label,
							'value' => $bank_detail[ $key ],
						);
					}
				}

				return $_bank_detail;
			},
			$bank_details
		);

		wptravelengine_get_template(
			'template-checkout/content-bank-transfer-instruction.php',
			compact( 'instruction', 'bank_details' )
		);
	}
}
