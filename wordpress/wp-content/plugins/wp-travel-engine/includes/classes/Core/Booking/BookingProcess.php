<?php
/**
 * This file contains the BookingProcess class.
 *
 * @package WPTravelEngine\Core\Controllers
 * @since 6.0.0
 */

namespace WPTravelEngine\Core\Booking;

use WPTravelEngine\Abstracts\PaymentGateway;
use WPTravelEngine\PaymentGateways\BaseGateway;
use WPTravelEngine\Core\Cart\Cart;
use WPTravelEngine\Filters\Events;
use WPTravelEngine\Helpers\CartInfoParser;
use WPTravelEngine\Core\Models\Post\Booking;
use WPTravelEngine\Core\Models\Post\Customer;
use WPTravelEngine\Core\Models\Post\Payment;
use WPTravelEngine\Core\Models\Post\Trip;
use WPTravelEngine\Modules\CouponCode;
use WPTravelEngine\PaymentGateways\PaymentGateways;
use WPTravelEngine\Utilities\RequestParser;
use WPTravelEngine\Validator\Checkout as CheckoutValidator;
use WPTravelEngine\Validator\Travelers as TravelersValidator;
use WPTravelEngine\Validator\Emergency as EmergencyValidator;

class BookingProcess {

	/**
	 * Nonce key.
	 *
	 * @var string
	 */
	const NONCE_KEY = 'wp_travel_engine_new_booking_process_nonce';

	/**
	 * Nonce action.
	 *
	 * @var string
	 */
	const NONCE_ACTION = 'wp_travel_engine_new_booking_process_nonce_action';

	/**
	 * @var RequestParser
	 */
	protected RequestParser $request;

	/**
	 * @var Cart
	 */
	protected Cart $cart;

	/**
	 * Current Booking Payment Method.
	 *
	 * @var string
	 */
	protected string $payment_method;

	/**
	 * Payment Model.
	 *
	 * @var Payment
	 */
	protected Payment $payment;

	/**
	 * Booking Model.
	 *
	 * @var Booking
	 */
	protected Booking $booking;

	/**
	 * Customer Model.
	 *
	 * @var Customer
	 */
	protected Customer $customer;

	/**
	 * @var CheckoutValidator
	 */
	protected CheckoutValidator $validator;

	/**
	 * Billing Info.
	 *
	 * @var array
	 */
	protected array $billing_info;

	/**
	 * @var string full_payment|partial|due
	 */
	protected string $payment_type;

	/**
	 * @since 6.5.2
	 */
	protected bool $is_new_booking = true;

	/**
	 * @var bool
	 * @since 6.5.2
	 */
	protected bool $is_new_customer;

	/**
	 * BookingProcess constructor.
	 *
	 * @param RequestParser $request Request parser.
	 * @param Cart          $cart Cart.
	 */
	public function __construct( RequestParser $request, Cart $cart ) {
		if ( empty( $cart->getItems( true ) ) ) {
			return;
		}

		$this->request = $request;
		$this->cart    = $cart;

		if ( $this->request->get_param( 'action' ) === 'editpost' ) {
			return;
		}

		$this->validator = new CheckoutValidator();
		$is_valid_form   = $this->validate_form_data();
		WTE()->session->set( 'checkout_billing_info', $this->validator->sanitized() );
		if ( $is_valid_form ) {

			$this->booking = $this->process_booking();

			$this->payment_method = apply_filters(
				'wptravelengine_checkout_payment_method',
				$this->request->get_param( 'wpte_checkout_paymnet_method' ) ?? 'booking_only',
				$this->booking->get_id()
			);

			if ( $ref = $this->request->get_param( "wte_pg_{$this->payment_method}_ref" ) ) {
				$this->booking->set_meta( "wte_pg_{$this->payment_method}_ref", (string) $ref )
								->set_meta( 'wp_travel_engine_booking_payment_method', $this->payment_method )
								->set_meta( 'wp_travel_engine_booking_payment_gateway', $this->payment_method );
			}

			// Add additional note from the new checkout page template.
			if ( $additional_note = $this->request->get_param( 'wptravelengine_additional_note' ) ) {
				// Set additional note to session.
				WTE()->session->set( 'additional_note', $additional_note );

				// Sanitize additional note.
				$additional_note = sanitize_text_field( $additional_note );

				// Set additional note to booking meta.
				$this->booking->set_meta( 'wptravelengine_additional_note', $additional_note ?? '' );
			}

			// Add new billing info to booking meta.
			if ( $this->request->get_param( 'billing' ) ) {
				// Set billing form data to session.
				WTE()->session->set( 'billing_form_data', $this->request->get_param( 'billing' ) );

				// Get validated billing data.
				$sanitized_billing = $this->validator->sanitized() ?? array();

				// Set billing details to booking meta.
				$this->booking->set_meta( 'wptravelengine_billing_details', $sanitized_billing['billing'] ?? array() );
			}

			// Gather sanitized lead traveller from request (so process_customer can set it in one place).
			$lead_traveller = array();
			if ( $this->request->get_param( 'travellers' ) ) {
				// Set travelers form data to session.
				WTE()->session->set( 'travellers_form_data', $this->request->get_param( 'travellers' ) );

				// Validate travelers data.
				$travelers = new TravelersValidator();
				$travelers->validate_data( $this->request->get_param( 'travellers' ) );

				// Sanitize travelers data.
				$sanitized_travellers = $travelers->sanitized() ?? array();

				// Set travelers details to booking meta.
				$this->booking->set_meta( 'wptravelengine_travelers_details', $sanitized_travellers['travelers'] ?? array() );

				$lead_traveller = $sanitized_travellers['travelers'][0] ?? array();
			}

			// Create/fetch customer and set lead traveller details in one place. Lead traveller is already sanitized via TravelersValidator::sanitized().
			$this->customer = $this->process_customer( $lead_traveller );

			// Add new emergency contacts info to booking meta.
			if ( $this->request->get_param( 'emergency' ) ) {
				// Set emergency form data to session.
				WTE()->session->set( 'emergency_form_data', $this->request->get_param( 'emergency' ) );

				// Validate emergency data.
				$emergency = new EmergencyValidator();
				$emergency->validate( $this->request->get_param( 'emergency' ) );

				// Sanitize emergency data.
				$sanitized_emergency = $emergency->sanitized() ?? array();

				// Set emergency details to booking meta.
				$this->booking->set_meta( 'wptravelengine_emergency_details', $sanitized_emergency['emergency'] ?? array() );
			}

			if ( $this->payment_method === 'booking_only' ) {
				$this->payment_type = 'full_payment';
			} else {
				$payment_type       = $this->request->get_param( 'wp_travel_engine_payment_mode' ) ?? 'full_payment';
				$this->payment_type = 'remaining_payment' === $payment_type ? 'due' : $payment_type;
			}

			$this->update_coupon_usage();

			$this->customer->update_customer_bookings( $this->booking->get_id() );

			$this->payment = $this->create_payment();

			$this->booking->add_payment( $this->payment->get_id() );

			$this->booking->save();

			// TODO: This needs to be $due = (float) $this->booking->get_due_amount(); only and should be handled properly in Core/Cart Class.
			if ( (float) $this->booking->get_due_amount() >= 1 ) {
				$this->booking->set_payment_transient();
			}

			if ( $this->is_new_booking ) {
				Events::booking_created( $this->booking );
			} else {
				Events::booking_updated( $this->booking );
			}

			do_action( 'wptravelengine_after_booking_created', $this->booking->get_id() );

			$this->customer->update_customer_meta( $this->booking->get_id() );
			$this->customer->save();

			if ( $this->is_new_customer ) {
				Events::customer_created( $this->customer );
			}

			$this->payment->save();

			$this->update_session();
			$this->payment_gateway_process();

			$this->send_notification_emails();

			if ( in_array( $this->payment_type, array( 'due' ) ) ) {
				do_action( 'wp_travel_engine_after_remaining_payment_process_completed', $this->booking->get_id() );
			} else {
				/**
				 * Hook to handle a payment process.
				 *
				 * @since 2.2.8
				 * @TODO: Remove on later update.
				 */
				do_action( 'wp_travel_engine_after_booking_process_completed', $this->booking->get_id() );
				do_action( 'wp_travel_engine_booking_completed_with_post_parameter', $request->get_params() ); // phpcs:ignore
			}

			// Redirect if not redirected till this point.
			$this->maybe_redirect();
		} else {
			wp_send_json_error( $this->validator->get_errors() );
		}
	}

	/**
	 * Redirect to thank you or travelers information page.
	 *
	 * @return void
	 */
	protected function maybe_redirect() {
		$booking_ref = $this->cart->get_booking_ref();
		$payment_key = $this->payment->get_payment_key();

		/**
		 * If booking reference and payment key are set, redirect to thank you page.
		 *
		 * @since 6.5.0
		 */
		if ( $booking_ref && $payment_key ) {
			wptravelengine_redirect_to_thank_you_page( $booking_ref, $payment_key );
		}

		$this->cart->clear();
		// Redirect if not redirected till this point.
		if ( apply_filters( 'wptravelengine_redirect_after_booking', true ) ) {
			$redirect_url = self::completion_redirect_url( $this->payment );
			wp_safe_redirect( $redirect_url );
			exit;
		}
	}

	/**
	 * Get completion redirect url.
	 *
	 * @param Payment $payment Payment.
	 * @return string
	 * @since 6.7.1
	 */
	public static function completion_redirect_url( Payment $payment ) {
		$get_key      = $payment->get_payment_key();
		$redirect_url = wp_travel_engine_get_booking_confirm_url();

		set_transient( 'payment_key_' . $get_key, $payment->get_id(), 24 * HOUR_IN_SECONDS );

		return add_query_arg( array( 'payment_key' => $get_key ), $redirect_url );
	}

	/**
	 * Triggers Payment Gateway.
	 *
	 * @return void
	 */
	protected function payment_gateway_process() {
		global $wte_cart;
		set_transient( "payment_key_{$this->payment->get_payment_key()}", $this->payment->get_id(), 60 * 10 );

		$payment_gateway = PaymentGateways::instance()->get_payment_gateway( $this->payment_method );

		if ( $payment_gateway instanceof BaseGateway ) {
			/**
			 * Added to support Cart Version 4.0
			 *
			 * @since 6.7.0
			 */
			if ( $wte_cart->is_curr_cart( '>=' ) && $payment_gateway::is_curr_cart( '>=' ) && method_exists( $payment_gateway, 'process_payment_v2' ) ) {
				$payment_gateway->process_payment_v2( $this->booking, $this->payment, $this );
			} else {
				$payment_gateway->process_payment( $this->booking, $this->payment, $this );
			}
		} else {
			/**
			 * Recommended for WTE Payment Addons.
			 *
			 * @since 4.3.0
			 */
			do_action(
				"wte_payment_gateway_{$this->payment_method}",
				$this->payment->get_id(),
				$this->payment_type,
				$this->payment_method
			);
		}
	}

	/**
	 * Process Booking - create booking and update metas.
	 *
	 * @return Booking
	 */
	protected function process_booking(): Booking {
		if ( $booking_id = $this->get_booking_ref() ) {
			$booking              = new Booking( $booking_id );
			$this->is_new_booking = false;
		} else {
			$booking              = $this->create_booking();
			$this->is_new_booking = true;

			$this->set_booking_ref( $booking );
			/**
			 * @action_hook wte_created_user_booking
			 *
			 * @since 2.2.0
			 */
			do_action( 'wte_after_booking_created', $booking->get_id() );
		}

		$this->billing_info = $this->set_billing_info( $booking, $this->validator->sanitized()['booking'] );

		$this->set_order_items( $booking );

		$booking_title = implode(
			' ',
			array(
				$this->billing_info['fname'] ?? '',
				$this->billing_info['lname'] ?? '',
				"#{$booking->get_id()}",
			)
		);

		$booking->update_post(
			array(
				'post_title'  => $booking_title,
				'post_status' => 'publish',
			)
		);

		// Sets booking meta for wp_travel_engine_booking_settings meta_key to retrieve data in booking details page and analytics.
		$items  = $this->cart->getItems( true );
		$_items = array();

		foreach ( $items as $cart_item ) {
			$data                 = $cart_item->data();
			$trip                 = new Trip( $data['trip_id'] ?? null );
			$cart_pricing_options = (array) $cart_item->pricing_options;
			$package              = get_post( $cart_item->price_key ?? null );

			$_items['place_order'] = array(
				'traveler'     => esc_attr( array_sum( $data['pax'] ?? array() ) ),
				'cost'         => esc_attr( $data['trip_price'] ?? '' ),
				'due'          => esc_attr( $this->cart->get_cart_total() - (float) ( $booking->get_meta( 'paid_amount' ) ?? 0 ) ),
				'tid'          => esc_attr( $trip->get_id() ?? '' ),
				'tname'        => esc_attr( $trip->get_title() ?? '' ),
				'datetime'     => ! empty( $data['trip_date'] ) ? $data['trip_date'] : '',
				'datewithtime' => ! empty( $data['trip_time'] ) ? $data['trip_time'] : '',
				'booking'      => $this->billing_info,
				'tax'          => esc_attr( $data['tax'] ?? '' ),
				'tduration'    => esc_attr( ( $trip->get_trip_duration() ?? '' ) . ' ' . ( $trip->get_trip_duration_unit() ?? '' ) ),
				'tenddate'     => wptravelengine_format_trip_end_datetime(
					! empty( $data['trip_time'] ) ? $data['trip_time'] : ( ! empty( $data['trip_date'] ) ? $data['trip_date'] : '' ),
					$trip,
					'Y-m-d\TH:i'
				),
				'trip_package' => esc_attr( $package->post_title ?? '' ),
			);

			if ( isset( $cart_item->trip_extras ) ) {
				$cart_extra_services                     = $cart_item->trip_extras;
				$_items['place_order']['extra_services'] = $cart_extra_services;
			}
		}

		return $booking->set_meta( 'paid_amount', (float) $booking->get_meta( 'paid_amount' ) )
						->set_meta( 'due_amount', $this->cart->get_cart_total() - (float) $booking->get_meta( 'paid_amount' ) ?? 0 )
						->set_cart_info(
							apply_filters(
								'wptravelengine_before_setting_cart_info',
								array_merge(
									array(
										'currency'     => wptravelengine_settings()->get( 'currency_code', 'USD' ),
										'subtotal'     => $this->cart->get_subtotal(),
										'totals'       => $this->cart->get_totals(),
										'total'        => $this->cart->get_cart_total(),
										'cart_partial' => $this->cart->get_total_partial(),
										'discounts'    => $this->cart->get_discounts(),
										'tax_amount'   => $this->cart->tax()->is_taxable() && $this->cart->tax()->is_exclusive() ? $this->cart->tax()->get_tax_percentage() : 0,
										'payment_type' => $this->cart->get_payment_type(), // @since 6.4.0 For Thank You page
									),
									$this->cart->data(),
								),
								$this->cart
							)
						)
						->set_meta( 'trip_datetime', ! empty( $data['trip_time'] ) ? $data['trip_time'] : $data['trip_date'] )
						->set_meta( 'wp_travel_engine_booking_setting', $_items )->save();
	}

	/**
	 * Process customer insert and update customer meta.
	 *
	 * @param array $lead_traveller Optional. Sanitized lead traveller data (index 0) to store on customer for future checkouts.
	 * @return Customer
	 */
	protected function process_customer( array $lead_traveller = array() ): Customer {
		$email = sanitize_email( $this->billing_info['email'] ?? '' );

		if ( $customer_id = Customer::is_exists( $email ) ) {
			try {
				$customer_model = new Customer( $customer_id );
			} catch ( \Exception $e ) {
				$this->is_new_customer = true;
				$customer_model        = Customer::create_post(
					array(
						'post_status' => 'publish',
						'post_type'   => 'customer',
						'post_title'  => $email,
					)
				);
			}
			$this->is_new_customer = false;
		} else {
			$this->is_new_customer = true;
			$customer_model        = Customer::create_post(
				array(
					'post_status' => 'publish',
					'post_type'   => 'customer',
					'post_title'  => $email,
				)
			);
		}

		if ( ! empty( $lead_traveller ) ) {
			$customer_model->set_my_meta( 'wptravelengine_traveller_details.0', $lead_traveller );
		}

		$customer_model->maybe_register_as_user();

		return $customer_model;
	}

	/**
	 * Process Payment.
	 *
	 * @return Payment
	 * @updated 6.7.0
	 */
	protected function create_payment(): Payment {

		$cart_info = new CartInfoParser( $this->booking->get_cart_info() );

		$cart_totals = $cart_info->get_totals();

		$payable_amount = 0;
		if ( $this->cart->is_curr_cart( '<' ) ) {
			$amounts        = array(
				'partial'      => $cart_totals['partial_total'],
				'full_payment' => $cart_totals['total'],
				'due'          => $cart_totals['due_total'],
			);
			$payable_amount = $amounts[ $this->payment_type ];
		} else {
			$payable_amount = $cart_totals['payable_now'];
		}

		if ( $payment_id = $this->get_payment_ref() ) {
			$payment_model = wptravelengine_get_payment( $payment_id );
		} else {
			$last_payment = $this->booking->get_last_payment();
			if ( $last_payment && 'booking_only' === $last_payment->get_payment_gateway() && 'pending' === $last_payment->get_payment_status() ) {
				$payment_model  = $last_payment;
				$payable_amount = $last_payment->get_payable_amount();
			} else {
				$payment_model = Payment::create_post(
					array(
						'post_type'   => 'wte-payments',
						'post_status' => 'publish',
						'post_title'  => 'Payment',
					)
				);
			}
			$this->set_payment_ref( $payment_model );
		}

		if ( $this->cart->is_curr_cart( '>=' ) ) {
			$payment_model->set_meta( 'cart_totals', $cart_totals );
		}

		$payment_model->set_meta(
			'payable',
			array(
				'currency' => wptravelengine_settings()->get( 'currency_code', 'USD' ),
				'amount'   => $payable_amount,
			)
		);

		$payment_model->set_meta( 'booking_id', $this->booking->get_id() )
						->set_meta( 'payment_gateway', $this->payment_method )
						->set_meta( 'payment_status', 'pending' )
						->set_meta( 'billing_info', $this->billing_info )
						->set_meta( 'payment_source', 'checkout' )
						->set_meta( 'is_due_payment', $this->payment_type === 'due' ? 'yes' : 'no' );

		$payment_types = apply_filters(
			'wte_payment_modes_and_titles',
			array(
				'full_payment' => 'Payment for booking #%s',
				'partial'      => 'Partial payment of booking #%s',
				'due'          => 'Due payment of booking #%s',
				'installment'  => 'Payment of booking #%s - #1',
			)
		);

		$payment_model->update_post(
			array(
				'post_title'  => sprintf( $payment_types[ $this->payment_type ] ?? 'Payment for booking #%s', $this->booking->get_id() ),
				'post_status' => 'publish',
			)
		);

		return $payment_model;
	}

	/**
	 * Update Coupon Usage.
	 *
	 * @return void
	 */
	protected function update_coupon_usage() {
		$discounts = $this->cart->get_discounts();
		if ( is_array( $discounts ) ) {
			foreach ( $discounts as $discount ) {
				$coupon_id = CouponCode::coupon_id_by_code( $discount['name'] );
				if ( $coupon_id ) {
					CouponCode::update_usage_count( $coupon_id );
				}
			}
		}
	}

	/**
	 * Set order items.
	 *
	 * @param Booking $booking Booking.
	 *
	 * @return void
	 */
	public function set_order_items( Booking $booking ): void {
		$items = $this->cart->getItems( true );

		$_items = array();
		/* @var Item $item */
		foreach ( $items as $cart_item ) {
			$data         = $cart_item->data();
			$trip         = new Trip( $data['trip_id'] );
			$cart_item_id = $cart_item->id();

			$_items[ $cart_item_id ] = array(
				'ID'                 => $trip->get_id(),
				'title'              => $trip->get_title(),
				'cost'               => $data['trip_price'],
				'pax'                => $data['pax'],
				'pax_cost'           => $data['pax_cost'],
				// 'partial_cost'       => $data[ 'trip_price_partial' ], // TODO
				'multi_pricing_used' => $data['multi_pricing_used'],
				'trip_extras'        => $data['trip_extras'],
				'datetime'           => ! empty( $data['trip_time'] ) ? $data['trip_time'] : $data['trip_date'],
				'_prev_cart_key'     => $cart_item_id,
				'has_time'           => ! empty( $data['trip_time'] ),
				'_cart_item_object'  => $data,
				'package_name'       => $data['package_name'] ?? get_the_title( $data['price_key'] ),
			);

			if ( isset( $data['trip_time_range'][1] ) ) {
				$_items[ $cart_item_id ]['end_datetime'] = $data['trip_time_range'][1];
			}
		}

		$booking->set_order_items( $_items );
		$booking->save();
		$booking->maybe_update_inventory();
	}

	/**
	 * Set billing info.
	 *
	 * @param Booking $booking Booking model.
	 * @param array   $form_data Form Data.
	 *
	 * @return array Billing Info.
	 */
	protected function set_billing_info( Booking $booking, $form_data ): array {
		$billing_info = array();
		foreach ( $form_data as $key => $value ) {
			$keys = explode( '.', $key );

			$billing_info[ array_pop( $keys ) ] = $value;
		}

		$booking->set_billing_info( $billing_info );

		return $billing_info;
	}

	/**
	 * Create a new booking.
	 *
	 * @return Booking
	 */
	protected function create_booking(): Booking {
		return Booking::create_post(
			array(
				'post_status' => 'publish',
				'post_type'   => 'booking',
				'post_title'  => 'booking',
				'meta_input'  => array(
					'wp_travel_engine_booking_payment_status' => 'pending',
					'wp_travel_engine_booking_payment_method' => __( 'N/A', 'wp-travel-engine' ),
					'billing_info'                    => array(),
					'cart_info'                       => array(
						'cart_total'   => 0,
						'cart_partial' => 0,
						'due'          => 0,
					),
					'payments'                        => array(),
					'paid_amount'                     => 0,
					'due_amount'                      => 0,
					'wp_travel_engine_booking_status' => 'pending',
				),
			)
		);
	}

	/**
	 * Validate the booking request.
	 *
	 * @return bool
	 */
	public function validate_form_data(): bool {

		$result = $this->validator->validate( $this->request->get_body_params() );

		/**
		 * If WooCommerce integration is active, let it continue the process ignoring the form validation errors since it is handled by WooCommerce.
		 *
		 * @since 6.5.7
		 */
		$enable_woocommerce_gateway = wptravelengine_settings()->get( 'use_woocommerce_payment_gateway', false );
		if ( defined( 'WPTRAVELENGINE_WC_PAYMENTS_FILE__' ) && $enable_woocommerce_gateway ) {
			return true;
		}

		return ! $result->has_errors();
	}

	/**
	 * Get Payment Type.
	 *
	 * @return string
	 */
	public function get_payment_type(): string {
		return $this->payment_type;
	}

	/**
	 * Process Gateway Callback.
	 *
	 * @return void
	 */
	public static function process_gateway_callback() {
		if ( isset( $_REQUEST['action'], $_REQUEST['_gateway'] ) ) {
			do_action( 'wte_callback_for_' . $_REQUEST['_gateway'] . '_' . $_REQUEST['action'] );
		}

		if ( isset( $_REQUEST['payment_key'], $_REQUEST['callback_type'] ) ) {

			$payment_key = sanitize_text_field( $_REQUEST['payment_key'] );
			$payment_id  = get_transient( "payment_key_{$payment_key}" );

			if ( $payment_id ) {

				/* @var Payment $payment */
				$payment = Payment::make( $payment_id );

				$payment_gateway = $payment->get_payment_gateway();

				$instance = PaymentGateways::instance()->get_payment_gateway( $payment_gateway );

				$callback_type = sanitize_text_field( $_REQUEST['callback_type'] );
				$booking       = Booking::make( $payment->get_meta( 'booking_id' ) );
				if ( method_exists( $instance, "handle_{$callback_type}_request" ) ) {
					$instance->{"handle_{$callback_type}_request"}( $booking, $payment );
				}
			}
		}
	}

	/**
	 * Check if the request is a gateway callback.
	 *
	 * @return bool
	 */
	public static function is_gateway_callback(): bool {
		return isset( $_REQUEST['action'], $_REQUEST['_gateway'] ) || isset( $_REQUEST['payment_key'], $_REQUEST['callback_type'] ) || isset( $_REQUEST['_action'], $_REQUEST['_token'] );
	}

	/**
	 * Check if the request is traveler information save request.
	 *
	 * @return bool
	 */
	public static function is_traveler_information_save_request(): bool {
		return check_ajax_referer( 'wp_travel_engine_final_confirmation_nonce', 'nonce', false );
	}

	/**
	 * Get Payment Method.
	 *
	 * @return string
	 */
	public function get_payment_method(): string {
		return $this->payment_method;
	}

	/**
	 * Check if the request is a due payment request.
	 *
	 * @return bool
	 */
	public static function is_due_payment_request(): bool {
		return check_ajax_referer( 'nonce_checkout_partial_payment_remaining_action', 'nonce_checkout_partial_payment_remaining_field', false ) !== false;
	}

	/**
	 * Check if the request is a booking request.
	 *
	 * @return bool
	 */
	public static function is_booking_request(): bool {
		if ( isset( $_REQUEST[ static::NONCE_KEY ] ) ) {
			if ( check_ajax_referer( static::NONCE_ACTION, static::NONCE_KEY, false ) !== false ) {
				return true;
			}
			wp_send_json_error( new \WP_Error( 'invalid_nonce', __( 'Nonce validation failed.', 'wp-travel-engine' ) ), 403 );
		}

		return false;
	}

	/**
	 * Send Booking Emails.
	 *
	 * @return void
	 */
	public function send_notification_emails() {
		if ( wptravelengine_settings()->get( 'email.disable_booking_notification', 'no' ) !== '1' ) {
			wptravelengine_send_booking_emails( $this->payment, 'order', 'admin' );
		}
		wptravelengine_send_booking_emails( $this->payment, 'order', 'customer' );
	}

	/**
	 * Update Session with current booking and payment ID for a further process.
	 *
	 * @return void
	 */
	protected function update_session() {
		WTE()->session->set( 'temp_tf_direction', "{$this->booking->ID}|{$this->payment->ID}|{$this->payment_method}" );
	}

	/**
	 * Update Booking State.
	 *
	 * @param string $key Key.
	 * @param mixed  $value Value.
	 *
	 * @return void
	 */
	protected function update_booking_state( string $key, $value ) {
		$booking_state         = WTE()->session->get( '__cart_' . $this->cart->get_cart_key() ) ?? array();
		$booking_state[ $key ] = $value;
		WTE()->session->set( "__cart_{$this->cart->get_cart_key()}", $booking_state );
	}

	/**
	 * Get Booking Reference.
	 *
	 * @return int|false
	 */
	public function get_booking_ref() {
		return WTE()->session->get( '__cart_' . $this->cart->get_cart_key() )['booking_id'] ?? false;
	}

	/**
	 * Get Payment Reference.
	 *
	 * @return int|false
	 */
	public function get_payment_ref() {
		return WTE()->session->get( '__cart_' . $this->cart->get_cart_key() )['payment_id'] ?? false;
	}

	/**
	 * Set Booking Reference.
	 *
	 * @return void
	 */
	public function set_booking_ref( Booking $booking ) {
		$this->update_booking_state( 'booking_id', $booking->get_id() );
	}

	/**
	 * Set Payment Reference.
	 *
	 * @param Payment $payment Payment Object.
	 *
	 * @return void
	 */
	public function set_payment_ref( Payment $payment ) {
		$this->update_booking_state( 'payment_id', $payment->get_id() );
	}

	/**
	 * Initialize legacy booking hooks.
	 *
	 * TODO:Should be handled properly.
	 *
	 * @return void
	 */
	public static function initialize_legacy_booking_hooks() {
		add_action(
			'wte_after_thankyou_booking_details_direct_bank_transfer',
			function ( $payment_id ) {
				$settings     = get_option( 'wp_travel_engine_settings', array() );
				$instructions = $settings['bank_transfer']['instruction'] ?? '';
				?>
				<div class="wte-bank-transfer-instructions">
					<?php echo wp_kses_post( $instructions ); ?>
				</div>
				<h4 class="bank-details"><?php echo esc_html__( 'Bank Details:', 'wp-travel-engine' ); ?></h4>
				<?php
				$bank_details = isset( $settings['bank_transfer']['accounts'] ) && is_array( $settings['bank_transfer']['accounts'] ) ? $settings['bank_transfer']['accounts'] : array();
				foreach ( $bank_details as $bank_detail ) :
					$details = array(
						'bank_name'      => array(
							'label' => __( 'Bank:', 'wp-travel-engine' ),
							'value' => $bank_detail['bank_name'],
						),
						'account_name'   => array(
							'label' => __( 'Account Name:', 'wp-travel-engine' ),
							'value' => $bank_detail['account_name'],
						),
						'account_number' => array(
							'label' => __( 'Account Number:', 'wp-travel-engine' ),
							'value' => $bank_detail['account_number'],
						),
						'sort_code'      => array(
							'label' => __( 'Sort Code:', 'wp-travel-engine' ),
							'value' => $bank_detail['sort_code'],
						),
						'iban'           => array(
							'label' => __( 'IBAN:', 'wp-travel-engine' ),
							'value' => $bank_detail['iban'],
						),
						'swift'          => array(
							'label' => __( 'BIC/SWIFT:', 'wp-travel-engine' ),
							'value' => $bank_detail['swift'],
						),
					);
					?>
					<div class="detail-container">
						<?php
						foreach ( $details as $detail ) :
							?>
							<div class="detail-item">
								<strong class="item-label"><?php echo esc_html( $detail['label'] ); ?></strong>
								<span class="value"><?php echo esc_html( $detail['value'] ); ?></span>
							</div>
						<?php endforeach; ?>
					</div>
					<?php
				endforeach;
			}
		);
		add_action(
			'wte_after_thankyou_booking_details_check_payments',
			function () {
				$settings     = get_option( 'wp_travel_engine_settings', array() );
				$instructions = wte_array_get( $settings, 'check_payment.instruction', '' );
				?>
				<div class="wte-bank-transfer-instructions">
					<?php echo wp_kses_post( $instructions ); ?>
				</div>
				<?php
			}
		);

		add_action(
			'wte_booking_cleanup',
			function ( $payment_id, $action = '' ) {
				global $wte_cart;
				$wte_cart->clear();
				if ( $action ) {
					delete_transient( "wte_token_{$action}" );
				}
			},
			11,
			2
		);

		add_action(
			'wte_redirect_after_payment_success',
			array(
				\WPTravelEngine\Core\Booking::class,
				'redirect',
			),
			90
		);
		add_action( 'wte_redirect_after_payment_error', array( \WPTravelEngine\Core\Booking::class, 'error' ), 90 );
	}
}
