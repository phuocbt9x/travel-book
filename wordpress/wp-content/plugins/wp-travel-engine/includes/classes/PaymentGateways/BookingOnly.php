<?php
/**
 * Booking Only Payment Gateway
 *
 * @package WPTravelEngine\PaymentGateways
 * @since 6.0.0
 */

namespace WPTravelEngine\PaymentGateways;

use WPTravelEngine\Core\Models\Post\Booking;
use WPTravelEngine\Core\Models\Post\Payment;
use WPTravelEngine\Core\Booking\BookingProcess;

/**
 * Booking Only Payment Gateway
 *
 * @since 6.0.0
 */
class BookingOnly extends BaseGateway {

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
		return 'booking_only';
	}

	/**
	 * Get label
	 *
	 * @return string
	 */
	public function get_label(): string {
		return __( 'Book Now Pay Later', 'wp-travel-engine' );
	}

	/**
	 * Get public label.
	 *
	 * @return string
	 */
	public function get_public_label(): string {
		return __( 'Book Now Pay Later', 'wp-travel-engine' );
	}

	/**
	 * Get Description.
	 */
	public function get_description(): string {
		return __( 'If checked, no payment gateways will be used in checkout. The booking process will be completed and booking will be saved without payment.', 'wp-travel-engine' );
	}

	/**
	 * Get icon.
	 *
	 * @return string
	 */
	public function get_icon(): string {
		return '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
        <g>
            <path d="M5.84714 21.682C4.483 21.6857 3.16072 21.211 2.11039 20.3406C1.06005 19.4701 0.348125 18.259 0.0984849 16.9178C-0.151155 15.5767 0.0772878 14.1905 0.744068 13.0005C1.41085 11.8104 2.47376 10.8917 3.74787 10.4043V3.98748C3.74966 3.45935 3.96088 2.95349 4.33517 2.5809C4.70947 2.2083 5.21628 1.99939 5.74441 2H20.9785C21.5066 1.99938 22.0134 2.20829 22.3877 2.58088C22.762 2.95348 22.9733 3.45934 22.975 3.98748V7.22516C23.2666 7.29445 23.5263 7.45994 23.7123 7.69493C23.8982 7.92992 23.9996 8.22071 24 8.52037V11.404C23.9996 11.7037 23.8982 11.9945 23.7123 12.2295C23.5263 12.4645 23.2666 12.63 22.975 12.6992V15.9369C22.9733 16.4651 22.762 16.9709 22.3877 17.3435C22.0134 17.7161 21.5066 17.925 20.9785 17.9244H11.2623C10.8419 19.0224 10.1003 19.9682 9.13431 20.6384C8.16835 21.3087 7.02284 21.6724 5.84714 21.682ZM5.83292 11.626C0.37313 11.7854 0.0920723 19.8376 5.83329 20.0636C11.3888 19.8447 11.3725 11.8871 5.83292 11.626ZM11.6401 16.3059H20.9784C21.0775 16.3071 21.173 16.2689 21.2439 16.1997C21.3148 16.1305 21.3553 16.0359 21.3566 15.9369V12.7387C20.7097 12.74 19.993 12.7337 19.3845 12.7379C18.7171 12.7387 18.0717 12.4991 17.5665 12.0631C17.0612 11.6271 16.7298 11.0237 16.633 10.3633C16.5361 9.70298 16.6803 9.02985 17.0391 8.46707C17.3978 7.9043 17.9472 7.48952 18.5868 7.29862C19.5045 7.17447 20.4318 7.1369 21.3566 7.18641V3.98748C21.3554 3.88841 21.3148 3.79388 21.2439 3.72468C21.173 3.65548 21.0775 3.61727 20.9785 3.61844H5.74441C5.64534 3.61727 5.54986 3.65549 5.47895 3.72469C5.40805 3.79389 5.36752 3.88841 5.36627 3.98748V10.0187C6.20498 9.9661 7.04526 10.0928 7.83117 10.3904C8.61708 10.6879 9.33058 11.1495 9.92416 11.7443C10.5177 12.3392 10.9778 13.0537 11.2737 13.8402C11.5695 14.6268 11.6944 15.4673 11.6401 16.3059ZM18.765 10.9591C19.0574 11.251 21.8785 11.0694 22.3816 11.1163V8.80805C22.1899 8.82234 19.089 8.76343 19.0139 8.85861C18.8074 8.93309 18.6257 9.06359 18.4891 9.2355C18.3525 9.40741 18.2665 9.61393 18.2407 9.83196C18.2148 10.05 18.2502 10.2709 18.3428 10.47C18.4354 10.669 18.5816 10.8384 18.765 10.9591ZM5.81435 16.6584H4.25716C4.04453 16.6554 3.84162 16.5688 3.69231 16.4173C3.54301 16.2659 3.45931 16.0618 3.45931 15.8491C3.45931 15.6365 3.54301 15.4324 3.69231 15.2809C3.84162 15.1295 4.04453 15.0429 4.25716 15.0399H5.0051V13.4136C5.00811 13.201 5.09469 12.9981 5.24612 12.8487C5.39755 12.6994 5.60166 12.6157 5.81432 12.6157C6.02698 12.6157 6.23109 12.6994 6.38252 12.8487C6.53395 12.9981 6.62053 13.201 6.62354 13.4136V15.8491C6.62357 15.9554 6.60266 16.0606 6.56201 16.1588C6.52135 16.257 6.46175 16.3462 6.38661 16.4214C6.31146 16.4965 6.22225 16.5561 6.12406 16.5968C6.02587 16.6375 5.92063 16.6584 5.81435 16.6584Z" fill="#3E4B50" />
        </g>
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
	public function process_payment( Booking $booking, Payment $payment, BookingProcess $booking_instance ): void {
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
		$cart_info = $booking->get_cart_info();
		if ( $cart_info['total'] <= 0 && $cart_info['subtotal'] > 0 ) { // Maybe 100% discount coupon applied.
			$payment->set_status( 'completed' );
			$payment->save();

			$booking->update_status( 'booked' );
		}
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
		$cart_info = $booking->get_cart_info();
		$amount    = $wte_cart->get_total_payable_amount();
		if ( $cart_info['total'] == 0 ) {
			$booking->sync_payment_success_metas( $payment->ID, $amount );
		} else {
			$booking->sync_payment_pending_metas( $payment->ID, $amount );
		}
	}
}
