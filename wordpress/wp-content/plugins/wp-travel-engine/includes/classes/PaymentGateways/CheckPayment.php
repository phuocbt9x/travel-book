<?php
/**
 * Check Payment.
 *
 * @package WPTravelEngine\PaymentGateways
 * @since 6.0.0
 */

namespace WPTravelEngine\PaymentGateways;

use WPTravelEngine\Core\Models\Post\Booking;
use WPTravelEngine\Core\Booking\BookingProcess;
use WPTravelEngine\Core\Models\Post\Payment;

/**
 * Check Payment Gateway
 *
 * @since 6.0.0
 */
class CheckPayment extends BaseGateway {

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
		return 'check_payments';
	}

	/**
	 * Get label
	 *
	 * @return string
	 */
	public function get_label(): string {
		return wptravelengine_settings()->get( 'check_payment.title', __( 'Check Payments', 'wp-travel-engine' ) );
	}

	/**
	 * Get public label.
	 *
	 * @return string
	 */
	public function get_public_label(): string {
		return __( 'Check Payments', 'wp-travel-engine' );
	}

	/**
	 * Public info shows at the time of checkout.
	 *
	 * @return string
	 */
	public function get_info(): string {
		return wptravelengine_settings()->get( 'check_payment.instruction', __( 'Please send a check to Store Name, Store Street, Store Town, Store State / County, Store Postcode.', 'wp-travel-engine' ) );
	}

	/**
	 * Get description.
	 *
	 * @return string
	 */
	public function get_description(): string {
		return wptravelengine_settings()->get( 'check_payment.description', __( 'Please send a check to Store Name, Store Street, Store Town, Store State / County, Store Postcode.', 'wp-travel-engine' ) );
	}

	/**
	 * Get icon.
	 *
	 * @return string
	 */
	public function get_icon(): string {
		return '<svg width="38" height="24" viewBox="0 0 38 24" fill="none" xmlns="http://www.w3.org/2000/svg">
        <g>
            <path fill-rule="evenodd" clip-rule="evenodd" d="M0.188965 20.0206V3.22215C0.200316 1.4416 1.65003 0 3.43221 0C5.0003 0 6.30897 1.11406 6.61057 2.59459H37.162C37.5204 2.59459 37.8107 2.88487 37.8107 3.24324V20.1081C37.8107 20.4665 37.5204 20.7568 37.162 20.7568H35.2161V23.3514C35.2161 23.7097 34.9258 24 34.5674 24H4.28705C2.0638 24 0.231143 22.2276 0.188965 20.0206ZM6.0266 20.7568C6.00551 20.7568 5.98443 20.7552 5.96497 20.7536H5.96335C5.93903 20.7503 5.9147 20.7471 5.89038 20.7422L5.86281 20.7357C5.84659 20.7325 5.82876 20.726 5.81416 20.7211C5.6893 20.679 5.58389 20.5995 5.50605 20.4957L5.49308 20.4779L5.48173 20.4617C5.47686 20.4519 5.47038 20.4422 5.46389 20.4325L5.45254 20.4114L5.44281 20.3903L5.4347 20.3709L5.42497 20.3498L5.41362 20.3206L5.40551 20.2946C5.40389 20.2914 5.40389 20.2914 5.40227 20.2881C5.39903 20.2784 5.3974 20.2654 5.39254 20.2541C5.39254 20.2492 5.39092 20.2444 5.3893 20.2395C5.38605 20.2233 5.38281 20.2038 5.38119 20.1876L5.37957 20.1844C5.37632 20.1584 5.37632 20.1341 5.37632 20.1082V20.0579C5.3747 20.0319 5.37308 20.006 5.37308 19.98C5.30659 18.9649 4.46173 18.1622 3.43037 18.1622C2.3893 18.1622 1.52009 18.9779 1.48443 20.019C1.52659 21.5141 2.77687 22.7028 4.28497 22.7028H33.9169V20.7568H6.0248L6.0266 20.7568ZM36.5133 3.89193V19.4595H6.67545V3.89193H36.5133ZM29.571 15.7523C28.898 16.3232 28.3499 16.5388 27.8991 16.461C27.7126 16.4302 27.5521 16.3475 27.4175 16.2307C27.8521 15.8123 28.2396 15.3761 28.4537 14.9934C28.7018 14.5491 28.7472 14.1339 28.6369 13.808C28.5186 13.461 28.2218 13.1269 27.5667 12.9874C27.0899 12.8869 26.704 12.9663 26.4007 13.1447C26.0926 13.3231 25.8607 13.6085 25.7197 13.9718C25.5056 14.5263 25.5251 15.2901 25.7699 15.9826C25.0872 16.5518 24.4678 16.9799 24.4678 16.9799C24.1726 17.1842 24.1013 17.5896 24.304 17.8831C24.5083 18.1783 24.9137 18.2496 25.2072 18.0469C25.2072 18.0469 25.7748 17.6447 26.4494 17.0901C26.871 17.515 27.4321 17.7842 28.1132 17.7728C28.6418 17.7631 29.2661 17.5734 29.9651 17.0869C30.4094 17.4826 31.1407 17.9594 32.0148 17.8718C32.6229 17.8101 33.3267 17.4955 34.037 16.6296C34.264 16.3539 34.2234 15.9437 33.9478 15.7166C33.6705 15.4896 33.2618 15.5318 33.0348 15.8074C32.6197 16.3134 32.2418 16.5469 31.8867 16.5826C31.5948 16.6118 31.3386 16.5015 31.1359 16.3718C30.8083 16.1658 30.5943 15.895 30.5554 15.8415C30.1759 15.3355 29.6229 15.7183 29.5727 15.7539L29.571 15.7523ZM5.37815 3.24308C5.37815 2.16958 4.50571 1.29713 3.43221 1.29713C2.36357 1.29713 1.49436 2.16144 1.48626 3.22846V17.4713C2.02465 17.0886 2.69439 16.8665 3.42249 16.8649H3.43222C4.05331 16.8649 4.63545 17.04 5.12842 17.3432L5.13653 17.3465C5.16248 17.3627 5.1868 17.3789 5.21275 17.3951L5.24032 17.413C5.25653 17.4227 5.2695 17.434 5.28572 17.4438C5.28897 17.447 5.29545 17.4503 5.2987 17.4535C5.31978 17.4681 5.33924 17.4827 5.36032 17.4973C5.3668 17.5021 5.37329 17.507 5.37978 17.5119V3.24161L5.37815 3.24308ZM29.571 15.7523C29.5613 15.7605 29.5694 15.754 29.6034 15.7248C29.5937 15.7345 29.5807 15.7426 29.571 15.7523ZM9.91849 15.2431H15.7561C16.1145 15.2431 16.4048 14.9529 16.4048 14.5945C16.4048 14.2361 16.1145 13.9458 15.7561 13.9458H9.91849C9.56011 13.9458 9.26984 14.2361 9.26984 14.5945C9.26984 14.9529 9.56011 15.2431 9.91849 15.2431ZM27.3475 14.2685C27.3313 14.2637 27.3135 14.2588 27.2973 14.2556C27.1983 14.2345 27.1156 14.228 27.0524 14.2653C26.9908 14.301 26.9583 14.3691 26.9308 14.442C26.8724 14.5912 26.8529 14.7712 26.8643 14.9593C26.9178 14.901 26.9697 14.8426 27.0167 14.7842C27.1497 14.6253 27.2794 14.3983 27.3475 14.2685ZM9.91849 12.6485H21.5942C21.9525 12.6485 22.2428 12.3583 22.2428 11.9999C22.2428 11.6415 21.9525 11.3512 21.5942 11.3512H9.91849C9.56011 11.3512 9.26984 11.6415 9.26984 11.9999C9.26984 12.3583 9.56011 12.6485 9.91849 12.6485ZM9.91849 10.0539H33.2698C33.6282 10.0539 33.9185 9.76368 33.9185 9.4053C33.9185 9.04692 33.6282 8.75665 33.2698 8.75665H9.91849C9.56011 8.75665 9.26984 9.04692 9.26984 9.4053C9.26984 9.76368 9.56011 10.0539 9.91849 10.0539ZM9.91849 7.45936H33.2698C33.6282 7.45936 33.9185 7.16908 33.9185 6.81071C33.9185 6.45233 33.6282 6.16206 33.2698 6.16206H9.91849C9.56011 6.16206 9.26984 6.45233 9.26984 6.81071C9.26984 7.16908 9.56011 7.45936 9.91849 7.45936Z" fill="#3E4B50" />
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
	protected function process_payment_before_v4( Booking $booking, Payment $payment, BookingProcess $booking_instance ): void {
		update_post_meta( $booking->get_id(), 'wp_travel_engine_booking_payment_gateway', __( 'Check Payment', 'wp-travel-engine' ) );
		update_post_meta( $booking->get_id(), 'wp_travel_engine_booking_payment_status', 'check-waiting' );
		$payment->set_status( 'check-waiting' );
		$payment->set_payment_gateway( 'check_payments' );
		$payable = $payment->get_meta( 'payable' );
		$amount  = array(
			'value'    => (float) $payable['amount'],
			'currency' => $payable['currency'],
		);
		$payment->set_meta( 'payment_status', 'check-waiting' );
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
	protected function process_payment_in_v4( Booking $booking, Payment $payment, BookingProcess $booking_instance ): void {
		global $wte_cart;
		$amount = $wte_cart->get_total_payable_amount();
		$booking->sync_payment_pending_metas( $payment->ID, $amount );
	}

	/**
	 *
	 * @since 6.3.3
	 */
	public function print_instruction() {
		$instruction = $this->get_info();
		wptravelengine_get_template( 'template-checkout/content-check-instruction.php', compact( 'instruction' ) );
	}
}
