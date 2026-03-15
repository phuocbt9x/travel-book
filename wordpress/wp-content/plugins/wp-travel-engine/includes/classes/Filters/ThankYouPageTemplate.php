<?php
/**
 * Thank You Page Template Filters.
 *
 * @since 6.3.3
 */

namespace WPTravelEngine\Filters;

use WPTravelEngine\Builders\FormFields\TravellerFormFields;
use WPTravelEngine\Builders\FormFields\BillingFormFields;
use WPTravelEngine\Builders\FormFields\EmergencyFormFields;
use WPTravelEngine\Core\Cart\Cart;
use WPTravelEngine\Core\Models\Post\Booking;
use WPTravelEngine\Core\Coupons;
use WPTravelEngine\Core\Models\Post\Payment;
use WPTravelEngine\Core\Models\Post\Trip;
use WPTravelEngine\Helpers\CartInfoParser;
use WPTravelEngine\Pages\Checkout;
use WPTravelEngine\PaymentGateways\CheckPayment;
use WPTravelEngine\PaymentGateways\DirectBankTransfer;
/**
 * Thank You Page Template Filters.
 *
 * @since 6.3.3
 */
class ThankYouPageTemplate extends CheckoutPageTemplate {

	public Booking $booking;

	public Payment $payment;

	/**
	 * @var ?Cart
	 */
	public ?Cart $cart = null;

	/**
	 * Constructor.
	 *
	 * @param Booking $booking
	 * @param Payment $payment
	 */
	public function __construct( Booking $booking, Payment $payment ) {
		$this->booking = $booking;
		$this->payment = $payment;

		$this->set_cart();

		add_action(
			'shutdown',
			function () {
				$this->cart->clear();
			}
		);
	}

	/**
	 * @return void
	 */
	public function hooks() {
		add_action( 'wptravelengine_thankyou_before_content', array( $this, 'page_header' ) );
		add_action( 'wptravelengine_thankyou_content', array( $this, 'page_content' ) );
		add_action( 'wptravelengine_thankyou_booking_details', array( $this, 'booking_details' ) );
		add_action( 'wptravelengine_thankyou_after_booking_details', array( $this, 'after_booking_details' ) );
		add_action( 'wptravelengine_thankyou_cart_summary', array( $this, 'cart_summary' ) );
		add_action( 'thankyou_template_parts_tour-details', array( $this, 'tour_details' ) );
		add_action( 'thankyou_template_parts_cart-summary', array( $this, 'cart_summary_partial' ) );
		add_action( 'thankyou_template_parts_cart-summary', array( $this, 'print_payment_details' ), 11 );
		add_action( 'wptravelengine_thankyou_booking_details_direct_bank_transfer', array( $this, 'print_bank_details' ) );
		add_action( 'wptravelengine_thankyou_booking_details_check_payments', array( $this, 'print_check_instruction' ) );
	}

	/**
	 * Print Check Instruction.
	 *
	 * @since 6.3.3
	 */
	public function print_check_instruction( $payment_id ) {
		$payment = wptravelengine_get_payment( $payment_id );
		if ( $payment ) {
			$check_payment = new CheckPayment();
			$check_payment->print_instruction( $payment_id );
		}
	}

	/**
	 * After Booking Details.
	 *
	 * @since 6.3.3
	 */
	public function after_booking_details() {
		do_action( "wptravelengine_thankyou_booking_details_{$this->payment->get_payment_gateway()}", $this->payment->get_id() );
	}

	/**
	 * Print Bank Details.
	 *
	 * @since 6.3.3
	 */
	public function print_bank_details( $payment_id ) {
		$payment = wptravelengine_get_payment( $payment_id );
		if ( $payment ) {
			$direct_bank_transfer = new DirectBankTransfer();

			$direct_bank_transfer->print_instruction( $payment_id );
		}
	}

	/**
	 * Print Payment Details.
	 *
	 * @since 6.3.3
	 */
	public function print_payment_details() {

		$payment_amount = $this->cart->get_total_payable_amount() ?: $this->payment->get_payable_amount();
		$payment_status = $this->payment->get_payment_status_label();
		$remarks        = __( 'Your booking order has been placed. Your booking will be confirmed after payment confirmation/settlement.', 'wp-travel-engine' );
		wptravelengine_get_template(
			'thank-you/content-payment-details.php',
			compact(
				'payment_amount',
				'payment_status',
				'remarks'
			)
		);
	}

	/**
	 * @return Cart
	 */
	public function get_cart(): ?Cart {
		return $this->cart;
	}

	/**
	 * @return void
	 */
	protected function set_cart() {
		if ( ! $this->cart ) {
			global $wte_cart;
			$this->cart = clone $wte_cart;

			$this->cart->set_cart_key( wptravelengine_generate_key( time() ) );

			$this->cart->version = $this->booking->get_cart_version();

			$this->cart->load( $this->booking->get_cart_info() );
		}

		return $this->cart;
	}

	/**
	 * @return mixed|null
	 * @since 6.3.5
	 */
	public function get_tour_details() {
		$cart_items = $this->cart->getItems();

		$item_details = array();

		foreach ( $cart_items as $cart_item ) {
			/** @var array $cart_item */
			$trip            = new Trip( $cart_item['trip_id'] );
			$trip_start_date = ! empty( $cart_item['trip_time'] ) ? $cart_item['trip_time'] : $cart_item['trip_date'];
			$trip_end_date   = wptravelengine_format_trip_end_datetime( $trip_start_date, $trip );
			$package_name    = $cart_item['package_name'] ?? '';
			if ( empty( $package_name ) && ! empty( $cart_item['price_key'] ) ) {
				$package_name = get_the_title( $cart_item['price_key'] );
			}
			$travelers_count = isset( $cart_item['travelers_count'] ) && $cart_item['travelers_count'] > 0 ? $cart_item['travelers_count'] : array_sum( $cart_item['pax'] ?? array() );

			if ( ! empty( $cart_item['trip_time_range'] ) ) {
				$trip_end_date = wptravelengine_format_trip_datetime( $cart_item['trip_time_range'][1] ?? '' );
			}

			$link = wptravelengine_toggled( $trip->get_meta( 'is_created_from_booking' ) ) ? '' : $trip->get_permalink();

			$item = array(
				sprintf(
					'<tr><td colspan="2">%s</td></tr>',
					sprintf(
						'<a %sclass="wpte-checkout__trip-name">%s</a>',
						$link ? 'href="' . esc_url( $link ) . '" ' : '',
						esc_html( $trip->get_title() )
					)
				),
				sprintf( '<tr><td>%s</td><td><strong>%s</strong></td></tr>', __( 'Booking ID:', 'wp-travel-engine' ), $this->booking->get_id() ),
				sprintf( '<tr><td>%s</td><td><strong>%s</strong></td></tr>', __( 'Package:', 'wp-travel-engine' ), $package_name ),
				sprintf( '<tr><td>%s</td><td><strong>%s</strong></td></tr>', __( 'Trip Code:', 'wp-travel-engine' ), $trip->get_trip_code() ),
				sprintf( '<tr><td>%s</td><td><strong>%s</strong></td></tr>', __( 'Starts on:', 'wp-travel-engine' ), wptravelengine_format_trip_datetime( $trip_start_date ) ),
				sprintf( '<tr><td>%s</td><td><strong>%s</strong></td></tr>', __( 'Ends on:', 'wp-travel-engine' ), $trip_end_date ),
				sprintf( '<tr><td>%s</td><td><strong>%s</strong></td></tr>', __( 'No. of Travellers:', 'wp-travel-engine' ), $travelers_count ),
			);

			$item_details[] = apply_filters( 'wptravelengine_checkout_page_item_' . __FUNCTION__, $item, $trip, $cart_item );
		}

		return apply_filters( 'wptravelengine_checkout_page_' . __FUNCTION__, $item_details, $cart_items, $this );
	}

	/**
	 * Tour Details.
	 *
	 * @since 6.3.3
	 */
	public function tour_details() {
		$tour_details = $this->get_tour_details();
		wptravelengine_get_template(
			'template-checkout/content-tour-details.php',
			array_merge(
				compact( 'tour_details' ),
				array(
					'content_only' => true,
				)
			)
		);
	}

	/**
	 * Print the Cart Summary.
	 *
	 * @return void
	 * @since 6.4.0
	 */
	public function print_cart_summary( $args ) {
		$template_instance                   = new Checkout( $this->cart );
		$template_instance->is_checkout_page = false;

		$cart_info = new CartInfoParser( $this->booking->get_cart_info() );

		$cart_line_items = $template_instance->get_cart_line_items();

		$deposit_amount = $cart_info->get_totals( 'partial_total' );
		$due_amount     = $cart_info->get_totals( 'due_total' );
		// Check if payment is due or partial payment.
		$is_payment_due = $this->cart->get_booking_ref();
		if ( $is_payment_due ) {
			$deposit_amount = $this->booking->get_total_paid_amount();
			$due_amount     = $this->booking->get_total_due_amount();
		}
		$is_partial_payment = in_array(
			$template_instance->cart->get_payment_type(),
			array(
				'partial',
				'due',
				'remaining_payment',
			),
			true
		);

		$show_coupon_form = wptravelengine_settings()->get( 'show_discount' ) === 'yes' && Coupons::is_coupon_available() && 'due' !== $this->cart->get_payment_type() ? 'show' : 'hide';

		$coupons = array();

		foreach ( $this->cart->get_deductible_items() as $coupon_item ) {
			if ( 'coupon' !== $coupon_item->name ) {
				continue;
			}
			$coupons[] = array(
				'label'  => $coupon_item->label,
				'amount' => $this->cart->get_totals()['total_coupon'] ?? 0,
			);
		}

		$args = array_merge(
			compact( 'cart_line_items', 'deposit_amount', 'due_amount', 'is_partial_payment', 'coupons' ),
			array(
				'show_coupon_form' => $show_coupon_form === 'show',
				'_wte_cart'        => $this->cart,
				'template_instance' => $template_instance,
			),
			$args
		);

		wptravelengine_get_template(
			'template-checkout/content-cart-summary.php',
			$args
		);
	}

	/**
	 * Cart Summary Partial.
	 *
	 * @since 6.3.3
	 */
	public function cart_summary_partial() {
		$this->print_cart_summary(
			array(
				'show_coupon_form' => false,
				'content_only'     => true,
				'show_title'       => true,
			)
		);
	}

	/**
	 * Page Header.
	 *
	 * @since 6.3.3
	 */
	public function page_header() {

		if ( ! $thankyou_message = wptravelengine_settings()->get( 'confirmation_msg', false ) ) {
			$thankyou_message = __( 'Thank you for booking the trip. Please check your email for confirmation.🎉', 'wp-travel-engine' );
		}
		wptravelengine_get_template( 'thank-you/content-page-header.php', compact( 'thankyou_message' ) );
	}

	/**
	 * Page Content.
	 *
	 * @since 6.3.3
	 */
	public function page_content() {
		wptravelengine_get_template( 'thank-you/content-thank-you.php' );
	}

	/**
	 * Booking Details.
	 *
	 * @since 6.3.3
	 */
	public function booking_details() {

		$order_trips        = $this->booking->get_meta( 'order_trips' );
		$additional_note    = $this->booking->get_meta( 'wptravelengine_additional_note' );
		$_traveller_details = $this->booking->get_meta( 'wptravelengine_travelers_details' );
		$_booking_details   = $this->booking->get_meta( 'wptravelengine_billing_details' );
		$_emergency_details = $this->booking->get_meta( 'wptravelengine_emergency_details' );

		$order_trip = reset( $order_trips );

		$trip = new Trip( $order_trip['ID'] );

		$start_datetime  = $order_trip['datetime'];
		$trip_start_date = wptravelengine_format_trip_datetime( $start_datetime );
		$trip_end_date   = wptravelengine_format_trip_end_datetime( $start_datetime, $trip );

		if ( ! empty( $order_trip['end_datetime'] ) ) {
			$trip_end_date = wptravelengine_format_trip_datetime( $order_trip['end_datetime'] );
		}

		$traveller_details = array();
		if ( is_array( $_traveller_details ) ) {
			foreach ( $_traveller_details as $traveller ) {
				$traveller_form_fields = new TravellerFormFields();
				$traveller_details[]   = $traveller_form_fields->with_values( $traveller, $this->booking );
			}
		}

		$booking_details = array();
		if ( is_array( $_booking_details ) && ! empty( $_booking_details ) ) {
			$booking_form_fields = new BillingFormFields();
			$booking_details[]   = $booking_form_fields->with_values( $_booking_details );
		}

		$emergency_details = array();
		if ( is_array( $_emergency_details ) && ! empty( $_emergency_details ) ) {
			$emergency_form_fields = new EmergencyFormFields();

			if ( isset( $_emergency_details[0] ) && is_array( $_emergency_details[0] ) ) {
				foreach ( $_emergency_details as $emergency_contact ) {
					if ( is_array( $emergency_contact ) && ! empty( $emergency_contact ) ) {
						$emergency_details[] = $emergency_form_fields->with_values( $emergency_contact );
					}
				}
			} else {
				$emergency_details[] = $emergency_form_fields->with_values( $_emergency_details );
			}
		}

		wptravelengine_get_template(
			'thank-you/content-booking-details.php',
			compact(
				'trip_start_date',
				'trip_end_date',
				'additional_note',
				'traveller_details',
				'booking_details',
				'emergency_details'
			)
		);
	}

	/**
	 * Cart Summary.
	 *
	 * @since 6.3.3
	 */
	public function cart_summary() {
		wptravelengine_get_template(
			'thank-you/content-cart-summary.php',
		);
	}
}
