<?php
/**
 * Booking Model.
 *
 * @package WPTravelEngine/Core/Models
 * @since 6.0.0
 */

namespace WPTravelEngine\Core\Models\Post;

use InvalidArgumentException;
use stdClass;
use WP_POST;
use WPTravelEngine\Abstracts\PostModel;
use WPTravelEngine\Core\Booking\Inventory;
use WPTravelEngine\Helpers\CartInfoParser;
use WPTravelEngine\Helpers\Functions;
use WPTravelEngine\Utilities\ArrayUtility;
use WPTravelEngine\Utilities\PaymentCalculator;
use WPTravelEngine\Filters\Events;

/**
 * Class Booking.
 * This class represents a trip booking to the WP Travel Engine plugin.
 *
 * @since 6.0.0
 */
#[\AllowDynamicProperties]
class Booking extends PostModel {

	/**
	 * Post type name.
	 *
	 * @var string
	 */
	protected string $post_type = 'booking';

	/**
	 * @var null|Payment[] $payments Payments made for this booking.
	 */
	protected ?array $payments = null;

	/**
	 * Indicates if the booking is trashed.
	 *
	 * @var bool
	 */
	protected $trashed = false;

	/**
	 * Payments data.
	 *
	 * @var array
	 * @since 6.7.0
	 */
	public array $payments_data = array();

	/**
	 * Retrieves booking status.
	 *
	 * @return string Booking status
	 */
	public function get_booking_status(): string {
		$status = $this->get_meta( 'wp_travel_engine_booking_status' );

		return ! $status ? $this->post->post_status : $status;
	}

	/**
	 * Retrieves order trip.
	 *
	 * @return object|null Order trip.
	 */
	public function get_order_trip() {
		$order_trips = $this->get_meta( 'order_trips' ) ?? array();

		if ( ! is_array( $order_trips ) || empty( $order_trips ) ) {
			return null;
		}

		$order_trip_object = new \stdClass();

		$order_trip_object->cart_id = key( $order_trips );

		foreach ( current( $order_trips ) as $key => $value ) {
			$order_trip_object->$key = $value;
		}

		return $order_trip_object;
	}

	/**
	 * Get Booked Trip ID.
	 *
	 * @return int Booked Trip ID
	 */
	public function get_trip_id() {
		$order_trips = $this->get_order_items();

		return $order_trips['trip_id'] ?? $order_trips[0]['ID'] ?? $this->get_cart_info( 'items' )[0]['trip_id'] ?? 0;
	}

	/**
	 * Get Booked Trip Title.
	 *
	 * @return string Booked Trip Title
	 */
	public function get_trip_title() {
		$trip_id = $this->get_trip_id();

		return get_the_title( $trip_id ) ?? '';
	}

	/**
	 * Get Trip Cost.
	 *
	 * @return float Trip Cost
	 */
	public function get_trip_cost() {
		$order_trips = $this->get_order_items();

		return $order_trips['cost'] ?? 0;
	}

	/**
	 * Get Trip Partial Cost.
	 *
	 * @return float Trip Partial Cost
	 */
	public function get_partial_cost() {
		$order_trips = $this->get_order_items();

		return $order_trips['partial_cost'] ?? 0;
	}

	/**
	 * Get Trip DateTime.
	 *
	 * @return string Trip DateTime
	 */
	public function get_trip_datetime() {
		$order_trips = $this->get_order_items();

		return $order_trips['datetime'] ?? $order_trips[0]['datetime'] ?? gmdate( 'Y-m-d' );
	}

	/**
	 * Get Trip Pax.
	 *
	 * @return array Trip Pax
	 */
	public function get_trip_pax() {
		$order_trips = $this->get_order_items();

		return $order_trips['pax'] ?? $order_trips[0]['pax'] ?? array();
	}

	/**
	 * Get Trip Pax Cost.
	 *
	 * @return array Trip Pax Cost
	 */
	public function get_trip_pax_cost() {
		$order_trips = $this->get_order_items();

		return $order_trips['pax_cost'] ?? array();
	}

	/**
	 * Get Trip Extras.
	 *
	 * @return array Trip Extras
	 */
	public function get_trip_extras() {
		$order_trips = $this->get_order_items();

		return $order_trips['trip_extras'] ?? array();
	}

	/**
	 * Get Trip Package name.
	 * Modified function since enhancement/booking-details since it was not working.
	 *
	 * @return string Trip Package name
	 */
	public function get_trip_package_name() {
		$order_trips = $this->get_order_items();

		if ( is_array( $order_trips ) && ! empty( $order_trips ) ) {
			foreach ( $order_trips as $order_trip ) {
				if ( isset( $order_trip['package_name'] ) && ! empty( $order_trip['package_name'] ) ) {
					return $order_trip['package_name'];
				}
			}
		}

		return '';
	}


	/**
	 * Get Trip has time.
	 *
	 * @return bool Trip has time
	 */
	public function get_trip_has_time() {
		$order_trips = $this->get_order_items();

		return $order_trips['has_time'] ?? false;
	}

	/**
	 * Retrieves due amount.
	 *
	 * @return float Due Amount.
	 */
	public function get_due_amount(): float {
		$amount = floatval( $this->get_meta( 'due_amount' ) ?: 0 );

		return (float) number_format( $amount, 2, '.', '' );
	}

	/**
	 * Retrieves paid amount.
	 *
	 * @return float Paid Amount
	 */
	public function get_paid_amount(): float {
		$amount = $this->get_meta( 'paid_amount' ) ?? 0;

		return (float) number_format( ! $amount ? 0 : $amount, 2, '.', '' );
	}

	/**
	 * Retrieves booking cart info.
	 *
	 * @return mixed Booking cart info
	 */
	public function get_cart_info( $key = null ) {
		$cart_info = $this->get_meta( 'cart_info' ) ?? array();

		if ( ! is_null( $key ) ) {
			return $cart_info[ $key ] ?? null;
		}

		if ( (bool) $cart_info ) {
			if ( ! isset( $cart_info['items'] ) ) {
				$cart_info['items'] = $this->get_order_items();
			}
		}

		return ! $cart_info ? array() : $cart_info;
	}

	/**
	 * Retrieves booking cart info - Currency.
	 *
	 * @return string Currency
	 */
	public function get_currency() {
		$cart_info = $this->get_cart_info();

		return $cart_info['currency'] ?? '';
	}

	/**
	 * Retrieves booking cart info - Subtotal.
	 *
	 * @return float Subtotal
	 */
	public function get_subtotal() {
		$cart_info = $this->get_cart_info();

		return $cart_info['totals']['subtotal'] ?? 0;
	}

	/**
	 * Retrieves booking cart info - Total.
	 *
	 * @return float Total
	 */
	public function get_total(): float {
		$cart_info = $this->get_cart_info();

		return (float) ( $cart_info['totals']['total'] ?? 0 );
	}

	/**
	 * Retrieves booking cart info - Cart Partial.
	 *
	 * @return float Cart Partial
	 */
	public function get_cart_partial(): float {
		$cart_info = $this->get_cart_info();

		return (float) ( $cart_info['totals']['partial_total'] ?? 0 );
	}

	/**
	 * Retrieves booking cart info - Discounts.
	 *
	 * @return array Discounts
	 */
	public function get_discounts() {
		$cart_info = $this->get_cart_info();

		return $cart_info['discounts'] ?? array();
	}

	/**
	 * Retrieves booking cart info - Tax Amount.
	 *
	 * @return float Tax Amount
	 */
	public function get_tax_amount() {
		$cart_info = $this->get_cart_info();

		return $cart_info['tax_amount'] ?? 0;
	}

	/**
	 * Retrieves payment details.
	 *
	 * @return array payment details
	 */
	public function get_payment_detail() {
		return $this->get_meta( 'payments' ) ?? array();
	}

	/**
	 * Retrives Payment Details - Payment Status.
	 *
	 * @return string Payment Status
	 */
	public function get_payment_status() {
		return $this->get_meta( 'wp_travel_engine_booking_payment_status' );
	}

	/**
	 * Retrives Payment Details - Payment Gateway.
	 *
	 * @return string Payment Gateway
	 */
	public function get_payment_gateway() {
		return $this->get_meta( 'wp_travel_engine_booking_payment_gateway' );
	}

	/**
	 * Retrives Payment Details - Payment Method.
	 *
	 * @return string Payment Method
	 */
	public function get_payment_method() {
		return $this->get_meta( 'wp_travel_engine_booking_payment_method' );
	}

	/**
	 * Retries Billing Info Data.
	 *
	 * @return string|array Billing Info Data
	 */
	public function get_billing_info( ?string $key = null ) {
		if ( $this->has_meta( 'wptravelengine_billing_details' ) ) {
			$billing_info = $this->get_meta( 'wptravelengine_billing_details' );
		} else {
			$billing_info = $this->get_meta( 'billing_info' ) ?? array();
		}

		if ( ! is_null( $key ) ) {
			return $billing_info[ $key ] ?? '';
		}

		return ! $billing_info ? array() : $billing_info;
	}

	/**
	 * Get Billing Info - First Name.
	 *
	 * @return string First Name
	 */
	public function get_billing_fname(): string {
		return $this->get_billing_info( 'fname' );
	}

	/**
	 * Get Billing Info - Last Name.
	 *
	 * @return string Last Name
	 */
	public function get_billing_lname(): string {
		return $this->get_billing_info( 'lname' );
	}

	/**
	 * Get Billing Info - Email.
	 *
	 * @return string Email
	 */
	public function get_billing_email(): string {
		return $this->get_billing_info( 'email' );
	}

	/**
	 * Get Billing Info - Address.
	 *
	 * @return string Address
	 */
	public function get_billing_address(): string {
		return $this->get_billing_info( 'address' );
	}

	/**
	 * Get Billing Info - City.
	 *
	 * @return string City
	 */
	public function get_billing_city(): string {
		return $this->get_billing_info( 'city' );
	}

	/**
	 * Get Billing Info - Country.
	 *
	 * @return string Country.
	 */
	public function get_billing_country(): string {
		return $this->get_billing_info( 'country' );
	}

	/**
	 * Get Order Items.
	 *
	 * @return array
	 */
	public function get_order_items(): array {
		$order_trips = $this->get_meta( 'order_trips' );

		return is_array( $order_trips ) ? array_values( $order_trips ) : array();
	}

	/**
	 * Get Payments Object.
	 *
	 * @return Payment[]
	 */
	public function get_payments(): array {
		$payments = $this->get_meta( 'payments' ) ?: array();

		if ( count( $payments ) > 0 ) {
			$this->payments = array_map(
				function ( $payment ) {
					return wptravelengine_get_payment( $payment );
				},
				$payments
			);
		}

		return array_filter( $this->payments ?? array() );
	}

	/**
	 * @param int $payment_id
	 *
	 * @return Booking
	 */
	public function add_payment( int $payment_id ): Booking {
		$payments = $this->get_meta( 'payments' );

		if ( ! is_array( $payments ) ) {
			$payments = array();
		}

		$payments[] = $payment_id;

		$this->set_meta( 'payments', array_unique( $payments ) );

		return $this;
	}

	/**
	 * Retrieves Additional Fields Data.
	 *
	 * @return array Additional Fields Data.
	 */
	public function get_additional_fields(): array {
		return $this->get_meta( 'additional_fields' ) ?? array();
	}

	/**
	 * Retrieves Traveler Info Data - Travelers.
	 *
	 * @return array Travelers
	 * @since 6.4.0 Retrieves travelers info from wptravelengine_travelers_details and in particular format.
	 */
	public function get_travelers(): array {
		if ( $this->has_meta( 'wptravelengine_travelers_details' ) ) {
			return $this->get_meta( 'wptravelengine_travelers_details' ) ?? array();
		}

		// Check for legacy format.
		$traveler_info = $this->get_meta( 'wp_travel_engine_placeorder_setting' );

		if ( ! empty( $traveler_info['place_order']['travelers'] ) ) {
			return ArrayUtility::normalize( $traveler_info['place_order']['travelers'], 'fname' );
		}

		return array();
	}

	/**
	 * Retrieves Traveler Info Data - Emergency Contact Details.
	 *
	 * @return array Emergency Contact Details
	 */
	public function get_emergency_contacts(): array {
		if ( $this->has_meta( 'wptravelengine_emergency_details' ) ) {
			$emergency_contacts = $this->get_meta( 'wptravelengine_emergency_details' );
			if ( ! isset( $emergency_contacts[0] ) ) {
				$emergency_contacts = array( $emergency_contacts );
			}

			return $emergency_contacts;
		}

		// Check for legacy format first.
		$emergency_info = $this->get_meta( 'wp_travel_engine_placeorder_setting' );

		if ( ! empty( $emergency_info['place_order']['relation'] ) ) {
			return ArrayUtility::normalize( $emergency_info['place_order']['relation'] );
		}

		return array();
	}

	/**
	 * Set Billing Info.
	 *
	 * @return void
	 */
	public function set_billing_info( array $billing_info ) {
		$this->set_meta( 'billing_info', $billing_info );
	}

	/**
	 * Set Order Items.
	 *
	 * @return void
	 */
	public function set_order_items( array $items ) {
		$this->set_meta( 'order_trips', $items );
	}

	/**
	 * Set Cart Information.
	 *
	 * @return $this
	 */
	public function set_cart_info( array $data ): Booking {
		return $this->set_meta( 'cart_info', wp_slash( $data ) );
	}

	/**
	 * @param $status
	 *
	 * @return void
	 * @since 6.4.0
	 */
	public function set_status( $status ) {
		$this->set_meta( '_prev_booking_status', $this->get_booking_status() );
		$this->set_meta( 'wp_travel_engine_booking_status', $status );
	}

	/**
	 * Update Booking Status.
	 *
	 * @return $this
	 */
	public function update_status( $status ): Booking {
		$this->update_meta( '_prev_booking_status', $this->get_booking_status() );
		$this->update_meta( 'wp_travel_engine_booking_status', $status );

		return $this;
	}

	/**
	 * Update Paid Amount.
	 * If parameter `$update` is false will replace the current meta-value.
	 *
	 * @return $this
	 */
	public function update_paid_amount( $amount, bool $update = true ): Booking {
		$previous_amount = $this->get_paid_amount();

		$amount = $update ? $previous_amount + $amount : $amount;
		$this->update_meta( 'paid_amount', $amount );

		return $this;
	}

	/**
	 * Update Due Amount.
	 *
	 * @return $this
	 */
	public function update_due_amount( $amount, bool $update = true ): Booking {
		$previous_amount = $this->get_due_amount();

		$amount = $update ? max( $previous_amount - $amount, 0 ) : $amount;
		$this->update_meta( 'due_amount', $amount );

		return $this;
	}

	/**
	 * Last Payment.
	 *
	 * @return  false|Payment
	 */
	public function get_last_payment() {
		$payments = $this->get_payments();

		return end( $payments );
	}

	/**
	 * Save Booking.
	 *
	 * @param int     $post_id Post ID.
	 * @param WP_POST $post Post Object.
	 * @param bool    $update Update Flag.
	 */
	public static function save_post_booking( int $post_id, WP_Post $post, bool $update = false ) {

		// Backward Compatibility with Old Checkout.
		$request = Functions::create_request( 'POST' );
		$booking = new static( $post );

		// Billing Info.
		if ( $billing_info = $request->get_param( 'billing_info' ) ) {
			$current_billing_info = $booking->get_billing_info();
			if ( is_array( $billing_info ) ) {
				foreach ( $billing_info as $key => $value ) {
					$current_billing_info[ $key ] = sanitize_text_field( wp_unslash( $value ) );
				}
				$booking->set_billing_info( $current_billing_info );
			}
		}

		// Sets Traveler's Information.
		$traveler_info = $request->get_param( 'wp_travel_engine_placeorder_setting' )['place_order'] ?? false;
		if ( ! $traveler_info ) {
			$traveler_info = $request->get_param( 'wp_travel_engine_booking_setting' )['place_order'] ?? false;
		}

		if ( is_array( $traveler_info ) && ! empty( $traveler_info ) ) {
			$travelers = array();
			if ( isset( $traveler_info['travelers'] ) && is_array( $traveler_info['travelers'] ) ) {
				foreach ( $traveler_info['travelers'] as $key => $value ) {
					$travelers['travelers'][ $key ] = array_map( 'sanitize_text_field', wp_unslash( $value ) );
				}
			}
			if ( isset( $traveler_info['relation'] ) && is_array( $traveler_info['relation'] ) ) {
				foreach ( $traveler_info['relation'] as $key => $value ) {
					$travelers['relation'][ $key ] = array_map( 'sanitize_text_field', wp_unslash( $value ) );
				}
			}

			// Backward Compatibility with Old Travelers Information Page.
			$travelers_detail   = $traveler_info['travelers'] ?? array();
			$emergency_contacts = $traveler_info['relation'] ?? array();

			if ( ! empty( $travelers_detail ) ) {
				$travelers_detail = static::sanitize_data_array( $travelers_detail );
				$booking->set_meta( 'wptravelengine_travelers_details', $travelers_detail );
			}
			if ( ! empty( $emergency_contacts ) ) {
				$emergency_contacts = static::sanitize_data_array( $emergency_contacts );
				$booking->set_meta( 'wptravelengine_emergency_details', $emergency_contacts );
			}

			$booking->set_meta(
				'wp_travel_engine_placeorder_setting',
				array( 'place_order' => $travelers )
			);
		}

		// Order Trips.
		if ( $order_trips = $request->get_param( 'order_trips' ) ) {
			$current_order_trips = $booking->get_meta( 'order_trips' );
			$_data               = array();
			foreach ( array_keys( $current_order_trips ) as $cart_id ) {
				if ( ! isset( $order_trips[ $cart_id ] ) ) {
					$_data[ $cart_id ] = $current_order_trips[ $cart_id ];
					continue;
				}
				$cart_data = $order_trips[ $cart_id ];

				if ( isset( $cart_data['ID'] ) ) {
					$_data[ $cart_id ]['ID']    = sanitize_text_field( $cart_data['ID'] );
					$_data[ $cart_id ]['title'] = get_the_title( $_data[ $cart_id ]['ID'] );
				}
				if ( isset( $cart_data['datetime'] ) ) {
					$_data[ $cart_id ]['datetime'] = sanitize_text_field( $cart_data['datetime'] );
				}

				if ( isset( $cart_data['end_datetime'] ) ) {
					$_data[ $cart_id ]['end_datetime'] = sanitize_text_field( $cart_data['end_datetime'] );
				}

				if ( isset( $cart_data['pax'] ) ) {
					$_data[ $cart_id ]['pax'] = array_map( 'absint', $cart_data['pax'] );
				}

				if ( isset( $cart_data['pax_cost'] ) ) {
					foreach ( $cart_data['pax_cost'] as $_id => $pax_cost ) {
						if ( ! isset( $_data[ $cart_id ]['pax'][ $_id ] ) ) {
							continue;
						}
						$pax_count                             = (int) $_data[ $cart_id ]['pax'][ $_id ];
						$_data[ $cart_id ]['pax_cost'][ $_id ] = $pax_count * (float) $pax_cost;
					}
				}

				if ( isset( $cart_data['cost'] ) ) {
					$_data[ $cart_id ]['cost'] = sanitize_text_field( $cart_data['cost'] );
				}

				$_data[ $cart_id ] = wp_parse_args( $_data[ $cart_id ], $current_order_trips[ $cart_id ] );
			}

			$booking->set_order_items( $_data );
		}

		if ( $booking_status = $request->get_param( 'wp_travel_engine_booking_status' ) ) {
			$booking->set_meta( 'wp_travel_engine_booking_status', sanitize_text_field( $booking_status ) );
		}

		// Sets Paid amount.
		if ( is_numeric( $paid_amount = $request->get_param( 'paid_amount' ) ) ) {
			$booking->set_meta( 'paid_amount', $paid_amount );
		}

		// Sets due amount.
		if ( is_numeric( $due_amount = $request->get_param( 'due_amount' ) ) ) {
			$booking->set_meta( 'due_amount', $due_amount );
		}

		$booking->save();
		$booking->maybe_update_inventory();

		if ( $update ) {
			/**
			 * @param array $data Booking Data.
			 * @param Booking $booking Booking Object.
			 *
			 * @since 6.5.2
			 */
			do_action( 'wptravelengine.booking.updated', $booking->get_data(), $booking );
		}
	}


	/**
	 * Sanitize Data Array.
	 *
	 * @param array $form_data
	 *
	 * @return array
	 * @since 6.5.0
	 */
	public static function sanitize_data_array( array $form_data ): array {
		$sanitized = array();
		foreach ( $form_data as $key => $value ) {
			if ( is_array( $value ) && ! empty( $value ) ) {
				foreach ( array_values( $value ) as $i => $data ) {
					$sanitized[ $i ][ $key ] = is_array( $data )
						? array_map( 'sanitize_text_field', wp_unslash( $data ) )
						: sanitize_text_field( $data );
				}
			}
		}

		return $sanitized;
	}

	/**
	 * Handle post when trashing if post-type is booking.
	 *
	 * @param int $post_id Post ID.
	 *
	 * @return void
	 */
	public static function trashing_booking( int $post_id ): void {
		try {
			$booking = new static( $post_id );

			$booking->set_meta( '_prev_booking_status', $booking->get_booking_status() );
			$booking->update_status( 'canceled' );

			$booking->trashed = true;
			$booking->maybe_update_inventory();
		} catch ( \Exception $e ) {
			// Do nothing.
		}
	}

	/**
	 * Handle post when untrashing if post-type is booking.
	 *
	 * @param int $post_id Post ID.
	 *
	 * @return void
	 */
	public static function untrashing_booking( int $post_id ): void {
		try {
			$booking = new static( $post_id );

			$booking->update_status( $booking->get_meta( '_prev_booking_status' ) );
			$booking->untrashed = true;

			$booking->maybe_update_inventory();
		} catch ( \Exception $e ) {
			// Do nothing.
		}
	}

	/**
	 * Save Traveler's Information from the POST Request.
	 *
	 * @return void
	 */
	public static function save_travellers_information( $booking_id ) {

		if ( $booking_id ) {
			do_action( 'wp_travel_engine_before_traveller_information_save', $booking_id );
			static::save_post_booking( $booking_id, get_post( $booking_id ), true );
			do_action( 'wp_travel_engine_after_traveller_information_save', $booking_id );
			WTE()->session->delete( 'temp_tf_direction' );
		}
	}

	/**
	 * Maybe Update Inventory.
	 *
	 * @return void
	 */
	public function maybe_update_inventory(): void {
		$order_trips        = $this->get_meta( 'order_trips' );
		$cart_data          = $this->get_cart_info();
		$pricing_line_items = $cart_data['items'][0]['line_items']['pricing_category'] ?? array();

		if ( is_array( $order_trips ) ) {
			foreach ( $order_trips as $cart_id => $order_trip ) {
				$inventory = new Inventory( $order_trip['ID'] );
				$pax       = 0;

				if ( $this->trashed === true || 'canceled' === $this->get_booking_status() || 'refunded' === $this->get_booking_status() ) {
					$inventory->update_pax( $cart_id, 0, $order_trip['ID'], $this->ID );
					continue;
				}
				if ( is_array( $order_trip['pax'] ) ) {
					$pax = array_sum( $order_trip['pax'] );
				}
				if ( isset( $pricing_line_items ) && ! empty( $pricing_line_items ) && is_array( $pricing_line_items ) ) {
					$pax = array_sum( array_column( $pricing_line_items, 'quantity' ) );
				}

				$records = $inventory->get_inventory_record();
				if ( isset( $records[ $cart_id ][ $this->ID ] ) ) {
					$recorded_pax = $records[ $cart_id ][ $this->ID ];
					if ( $recorded_pax !== $pax ) {
						$inventory->update_pax( $cart_id, $pax, $order_trip['ID'], $this->ID );
					}
				} else {
					$inventory->update_pax( $cart_id, $pax, $order_trip['ID'], $this->ID );
				}
			}
		}
	}

	/**
	 * Get Booking by Payment ID.
	 *
	 * @param int|Payment $payment Payment ID or Payment Modal object.
	 *
	 * @return Booking|null
	 * @throws InvalidArgumentException If invalid Booking ID of Payment.
	 */
	public static function from_payment( $payment ): ?Booking {

		if ( $payment instanceof Payment ) {
			$payment = $payment->get_id();
		}

		$booking_id = get_post_meta( $payment, 'booking_id', true );

		if ( ! $booking_id ) {
			throw new InvalidArgumentException( 'Invalid Booking ID of Payment' );
		}

		return new static( $booking_id );
	}

	/**
	 * @return bool
	 * @since 6.4.0
	 */
	public function has_due_payment(): bool {
		$payments    = $this->get_payment_detail();
		$paid_amount = 0;
		$due_amount  = $this->get_total_due_amount();
		if ( $due_amount && is_numeric( $due_amount ) ) {
			return $due_amount > 1;
		}

		if ( is_array( $payments ) && count( $payments ) > 0 ) {
			foreach ( $payments as $payment ) {
				$payment      = Payment::make( $payment );
				$paid_amount += $payment->get_amount();
			}
		}
		$due_amount = $this->get_total() - $paid_amount;

		return $due_amount > 1;
	}

	/**
	 * Get Total Paid Amount.
	 *
	 * @return float
	 * @since 6.4.0
	 */
	public function get_total_paid_amount(): float {
		$paid_amount = $this->get_meta( 'total_paid_amount' );
		// Check if the value exists and is numeric, even if it's 0
		if ( $paid_amount !== null && $paid_amount !== false && is_numeric( $paid_amount ) ) {
			return (float) $paid_amount;
		}
		$payments    = get_post_meta( $this->ID, 'payments', true );
		$paid_amount = 0;
		if ( is_array( $payments ) && count( $payments ) > 0 ) {
			foreach ( $payments as $payment ) {
				$payment      = Payment::make( $payment );
				$paid_amount += $payment->get_amount();
			}
		}

		return $paid_amount;
	}

	/**
	 * Get Total Due Amount.
	 *
	 * @return float
	 * @since 6.4.0
	 */
	public function get_total_due_amount(): float {
		$due_amount = $this->get_meta( 'total_due_amount' );
		if ( $due_amount !== null && $due_amount !== false && is_numeric( $due_amount ) ) {
			return (float) $due_amount;
		}
		$paid_amount = $this->get_total_paid_amount();

		return $this->get_total() - $paid_amount;
	}

	/**
	 * Set Total Due Amount.
	 *
	 * @param float $amount
	 *
	 * @return void
	 * @since 6.4.0
	 */
	public function set_total_due_amount( float $amount ): void {
		$this->update_meta( 'total_due_amount', floatval( $amount ) );
	}

	/**
	 * Set Total Paid Amount.
	 *
	 * @param float $amount
	 *
	 * @return void
	 * @since 6.4.0
	 */
	public function set_total_paid_amount( float $amount ): void {
		$this->update_meta( 'total_paid_amount', $amount );
	}

	/**
	 * Get Remaining Payment Link.
	 *
	 * @return string URL for remaining payment or empty string if payment is not pending
	 */
	public function get_due_payment_link(): string {
		$payment_key  = wptravelengine_generate_key( $this->get_id() );
		$payment_link = '';

		$this->set_payment_transient( $payment_key );

		$is_partial_payment = false;
		foreach ( $this->get_payments() as $payment ) {
			if ( $payment->get_payment_gateway() === 'booking_only' ) {
				$is_partial_payment = true;
				break;
			}
		}

		if ( $this->has_due_payment() && ( wp_travel_engine_is_trip_partially_payable( $this->get_trip_id() ) || $is_partial_payment ) ) {
			$payment_link = add_query_arg(
				array(
					'_payment_key' => $payment_key,
				),
				home_url()
			);
		}

		return apply_filters( 'wptravelengine_due_payment_link', $payment_link, $this->get_id() );
	}

	/**
	 * Set Payment Link.
	 *
	 * @param ?string $payment_key Payment Link
	 * @return void
	 * @since 6.6.10
	 */
	public function set_payment_transient( $payment_key = null ): void {
		$payment_key ??= wptravelengine_generate_key( $this->get_id() );

		// Set expiration to 365 days for remaining payment links so customers can complete payment at any time
		set_transient(
			"_payment_key_{$payment_key}",
			wp_json_encode(
				array(
					'action'     => 'remaining_payment',
					'booking_id' => $this->get_id(),
				)
			),
			365 * DAY_IN_SECONDS
		);
	}

	/**
	 * Get customer note.
	 *
	 * @return string Additional Details
	 */
	public function get_customer_note(): string {
		return $this->get_meta( 'wptravelengine_additional_note' ) ?? '';
	}

	/**
	 * Get Admin Notes.
	 *
	 * @return string Admin Notes
	 */
	public function get_admin_note(): string {
		return $this->get_meta( 'wptravelengine_admin_notes' ) ?? '';
	}

	/**
	 * Set Traveller Details.
	 *
	 * @param array $data
	 */
	public function set_traveller_details( array $data ) {
		$this->set_meta( 'wptravelengine_travelers_details', $data );
	}

	/**
	 * Set Emergency Contact Details.
	 *
	 * @param array $data
	 */
	public function set_emergency_contact_details( array $data ) {
		$this->set_meta( 'wptravelengine_emergency_details', $data );
	}

	/**
	 * Set Cart Pricing.
	 *
	 * @param array $cart_pricing Cart Pricing
	 */
	public function set_cart_pricing( $cart_pricing ) {
		$this->set_meta( 'wptravelengine_cart_pricing', $cart_pricing );
		$this->save();
	}

	/**
	 * @return object
	 */
	public function get_customer(): object {
		$email_address = $this->get_billing_email();
		$_customer     = new stdClass();

		if ( $customer_id = Customer::is_exists( $email_address ) ) {
			$customer  = new Customer( $customer_id );
			$_customer = $customer->get_data();
		} else {
			$_customer->id         = 0;
			$_customer->first_name = '';
			$_customer->last_name  = '';
			$_customer->email      = $email_address;
			$_customer->phone      = $this->get_billing_info( 'phone' );
		}

		return (object) $_customer;
	}

	/**
	 * Set Additional Details.
	 *
	 * @param string $additional_details Additional Details
	 */
	public function set_additional_details( $additional_details ) {
		$this->set_meta( 'wptravelengine_additional_note', $additional_details );
		$this->save();
	}

	/**
	 * Set Notes.
	 *
	 * @param string $notes Notes
	 */
	public function set_notes( $notes ) {
		$this->set_meta( 'wptravelengine_admin_notes', $notes );
		$this->save();
	}

	/**
	 * @return array
	 * @since 6.5.2
	 */
	public function get_data(): array {

		$cart_info        = $this->get_cart_info();
		$cart_info_parser = new CartInfoParser( $cart_info );

		$booked_trips = array_map(
			function ( $item ) {
				return array(
					'id'                   => $item->get_trip_id(),
					'title'                => $item->get_trip_title(),
					'url'                  => get_permalink( $item->get_trip_id() ),
					'trip_start_date'      => $item->get_trip_date(),
					'trip_end_date'        => $item->get_end_date(),
					'number_of_travellers' => $item->travelers_count(),
					'line_items'           => array_map(
						function ( $line_items ) {
							return array_map(
								function ( $_line_item ) {
									$label    = '';
									$quantity = 0;
									$price    = 0;
									$total    = 0;
									extract( $_line_item );

									return compact( 'label', 'quantity', 'price', 'total' );
								},
								$line_items
							);
						},
						$item->get_line_items()
					),
				);
			},
			$cart_info_parser->get_items()
		);

		return array(
			'id'             => $this->ID,
			'booking_status' => $this->get_booking_status(),
			'booked_date'    => $this->post->post_date_gmt,
			'total_amount'   => $cart_info_parser->get_totals( 'total' ),
			'paid_amount'    => $this->get_paid_amount(),
			'due_amount'     => $this->get_due_amount(),
			'currency'       => $cart_info_parser->get_currency(),
			'booked_trips'   => $booked_trips,
			'customer'       => $this->get_customer(),
			'payments'       => array_map(
				function ( $payment ) {
					return array(
						'id'              => $payment->ID,
						'amount'          => $payment->get_amount(),
						'date'            => $payment->get_transaction_date(),
						'status'          => $payment->get_payment_status(),
						'payment_gateway' => $payment->get_payment_gateway(),
					);
				},
				$this->get_payments()
			),
		);
	}

	/**
	 * Set Payment Gateway Response.
	 *
	 * @param string $key
	 *
	 * @return ?Booking
	 * @throws InvalidArgumentException
	 */
	public static function from_payment_key( string $key ): ?Booking {
		if ( empty( $key ) ) {
			throw new InvalidArgumentException( 'Invalid Payment Key' );
		}

		$payment_id = get_transient( 'payment_key_' . $key );

		if ( ! $payment_id ) {
			throw new InvalidArgumentException( 'Invalid Payment Key' );
		}

		return new static( $payment_id );
	}

	/**
	 * Ensure payment key is stored for this booking.
	 * This allows payment links to work independently without requiring the booking details page.
	 *
	 * @return string The payment key
	 * @since 6.7.0
	 */
	public function ensure_payment_key(): string {
		$payment_key = wptravelengine_generate_key( $this->get_id() );

		// Store payment key as post meta for efficient lookups
		$this->update_meta( '_payment_key', $payment_key );

		return $payment_key;
	}

	/**
	 * Compare current cart version with the given version.
	 *
	 * @param string $op Operator to compare. Default is '=='.
	 * @param string $version Version to compare with. Default is '4.0'.
	 * @return bool
	 * @since 6.7.0
	 */
	public function is_curr_cart( string $op = '==', string $version = '4.0' ): bool {
		global $current_screen;
		if ( $current_screen && $current_screen->id === 'booking' && $current_screen->action === 'add' && $current_screen->base === 'post' ) {
			return true;
		}
		return version_compare( $this->get_cart_version(), $version, $op );
	}

	/**
	 * Retrieves booking cart info - Version.
	 *
	 * @return string Version
	 * @since 6.7.0
	 */
	public function get_cart_version(): string {
		return (string) ( $this->get_cart_info( 'version' ) ?? '3.0' );
	}

	/**
	 * Get fees data.
	 *
	 * @return array Fees data.
	 * @since 6.7.0
	 */
	public function get_fees(): array {
		return $this->get_cart_info( 'fees' ) ?? array();
	}

	/**
	 * Get Payment by ID.
	 *
	 * @param int $payment_id The payment ID.
	 *
	 * @return ?Payment
	 * @since 6.7.0
	 */
	public function get_payment_by_id( int $payment_id ) {
		foreach ( $this->get_payments() as $payment ) {
			if ( $payment->ID === $payment_id ) {
				return $payment;
			}
		}
		return null;
	}

	/**
	 * Get fees by type.
	 *
	 * @return array
	 * @since 6.7.0
	 */
	public function get_fee_types(): array {
		$fees         = $this->get_fees();
		$fees_by_type = array();

		foreach ( $fees as $fee ) {
			$apply_tax = $fee['apply_tax'] ?? true;
			if ( 'tax' === $fee['name'] ) {
				$fees_by_type['tax'] = $fee;
			} elseif ( $apply_tax ) {
				$fees_by_type['tax_inclusive'][] = $fee;
			} else {
				$fees_by_type['tax_exclusive'][] = $fee;
			}
		}

		return $fees_by_type;
	}

	/**
	 * Get payments data.
	 *
	 * @param bool $success Whether to include successful payments only. Default true.
	 *
	 * @return array Payments data.
	 * {
	 *  'totals' => array<mixed>,
	 *  'payments' => array<mixed>,
	 * }
	 * @since 6.7.0
	 * @since 6.7.1 Skips calculation for failed payments.
	 */
	public function get_payments_data( bool $success = true ): array {

		$key = $success ? 'success' : 'not_success';

		if ( isset( $this->payments_data[ $key ] ) ) {
			return $this->payments_data[ $key ];
		}

		$totals    = array(
			'total_deposit'   => '0',
			'total_paid'      => '0',
			'total_exclusive' => '0',
			'total_discount'  => '0',
			'due_exclusive'   => '0',
			'payable'         => '0',
			'extra_charges'   => '0',
		);
		$payments  = array();
		$fee_types = $this->get_fee_types();

		$calculator = PaymentCalculator::for( $this->get_currency() );

		$discounts = $this->get_discounts();

		foreach ( $this->get_payments() as $payment ) {
			$cart_totals = $payment->get_cart_totals();

			if ( ! $cart_totals || $payment->is_failed() || ( $success && ! $payment->is_completed() ) ) {
				continue;
			}

			foreach ( $discounts as $_key => $_ ) {
				if ( isset( $cart_totals[ 'total_' . $_key ] ) ) {
					$totals['total_discount'] = $calculator->add( $totals['total_discount'], (string) $cart_totals[ 'total_' . $_key ] );
				}
			}

			$p_id = $payment->ID;

			foreach ( $fee_types['tax_inclusive'] ?? array() as $fee ) {
				$payments[ $p_id ][ $fee['name'] ]                = (string) ( $cart_totals[ 'total_' . $fee['name'] ] ?? '0.00' );
				$totals['tax_inclusive'][ $fee['name'] ]['label'] = $fee['label'];
				$totals['tax_inclusive'][ $fee['name'] ]['value'] = $calculator->add(
					$totals['tax_inclusive'][ $fee['name'] ]['value'] ?? '0.00',
					$payments[ $p_id ][ $fee['name'] ]
				);
			}

			foreach ( $fee_types['tax_exclusive'] ?? array() as $fee ) {
				$payments[ $p_id ][ $fee['name'] ]                = (string) ( $cart_totals[ 'total_' . $fee['name'] ] ?? '0.00' );
				$totals['tax_exclusive'][ $fee['name'] ]['label'] = $fee['label'];
				$totals['tax_exclusive'][ $fee['name'] ]['value'] = $calculator->add(
					$totals['tax_exclusive'][ $fee['name'] ]['value'] ?? '0.00',
					$payments[ $p_id ][ $fee['name'] ]
				);
			}

			if ( isset( $fee_types['tax'], $cart_totals['total_tax'] ) ) {
				$payments[ $p_id ]['tax'] = (string) ( $cart_totals['total_tax'] ?? '0.00' );
				$totals['tax']            = array(
					'label' => $fee_types['tax']['label'],
					'value' => $calculator->add(
						$totals['tax']['value'] ?? '0.00',
						$payments[ $p_id ]['tax']
					),
				);
			}

			$payments[ $p_id ]['total']         = (string) $payment->get_amount();
			$payments[ $p_id ]['deposit']       = (string) ( $cart_totals['deposit'] ?? '0.00' );
			$payments[ $p_id ]['payable']       = (string) ( $cart_totals['payable_now'] ?? '0.00' );
			$payments[ $p_id ]['extra_charges'] = (string) ( $cart_totals['total_extra_charges'] ?? '0.00' );

			$totals['total_paid']    = $calculator->add(
				$totals['total_paid'] ?? '0.00',
				$payments[ $p_id ]['total']
			);
			$totals['total_deposit'] = $calculator->add(
				$totals['total_deposit'] ?? '0.00',
				$payments[ $p_id ]['deposit']
			);
			$totals['payable']       = $calculator->add(
				$totals['payable'] ?? '0.00',
				$payments[ $p_id ]['payable']
			);
			$totals['extra_charges'] = $calculator->add(
				$totals['extra_charges'] ?? '0.00',
				$payments[ $p_id ]['extra_charges']
			);
		}

		$totals['total_exclusive'] = (string) $this->get_total();
		$totals['due_exclusive']   = $calculator->subtract(
			$totals['total_exclusive'],
			$totals['total_deposit']
		);
		$totals['subtotal']        = $calculator->add( $totals['total_exclusive'], $totals['total_discount'] );

		$this->payments_data[ $key ] = compact( 'totals', 'payments' );

		return $this->payments_data[ $key ];
	}

	/**
	 * Syncs metas to the Payment and Booking posts.
	 *
	 * @param int   $payment_id The payment ID.
	 * @param float $paid_amount The paid amount received from the payment gateway response.
	 * @param array $args The arguments for payment, booking metadata to sync and whether to send booking emails.
	 * {
	 *  'payment_metadata'    => array<mixed>,
	 *  'booking_metadata'    => array<mixed>,
	 *  'send_booking_emails' => bool,
	 *  'send_payment_emails' => bool,
	 * }
	 * @return void
	 * @since 6.7.0
	 * @since 6.7.1 Added support for send_booking_emails, send_payment_emails arguments and payment event trigger.
	 */
	public function sync_payment_success_metas( int $payment_id, float $paid_amount, array $args = array() ): void {
		$args = wp_parse_args(
			$args,
			array(
				'payment_metadata'    => array(),
				'booking_metadata'    => array(),
				'send_booking_emails' => false,
				'send_payment_emails' => false,
			)
		);

		/** @var PaymentCalculator $calculator */
		$calculator = PaymentCalculator::for( $this->get_currency() );

		$_payment            = null;
		$total_paid_amount   = (string) $paid_amount;
		$total_extra_charges = '0.00';

		$payments = $this->get_payments();

		foreach ( $payments as $payment ) {
			if ( $payment->ID === $payment_id ) {
				$_payment = $payment;
			} elseif ( ! $payment->is_completed() ) {
				continue;
			} else {
				$total_paid_amount = $calculator->add( $total_paid_amount, (string) $payment->get_amount() );
			}
			$total_extra_charges = $calculator->add( $total_extra_charges, (string) $payment->get_cart_totals( 'total_extra_charges' ) );
		}

		if ( ! ( $_payment instanceof Payment ) ) {
			throw new InvalidArgumentException( 'Payment ID #' . $payment_id . ' is not found in the booking #' . $this->get_id() . '.' );
		}

		$args['payment_metadata'] = is_array( $args['payment_metadata'] ) ? $args['payment_metadata'] : array();
		$args['booking_metadata'] = is_array( $args['booking_metadata'] ) ? $args['booking_metadata'] : array();

		$payable_amount = $calculator->subtract(
			(string) $_payment->get_payable_amount(),
			(string) $paid_amount
		);

		$previous_payment_status = $_payment->get_payment_status();

		$_payment_metas = array(
			'payable'              => array(
				'amount'   => $payable_amount,
				'currency' => $this->get_currency(),
			),
			'payment_amount'       => array(
				'value'    => $paid_amount,
				'currency' => $this->get_currency(),
			),
			'_prev_payment_status' => $previous_payment_status,
			'payment_status'       => 'completed',
		);

		$_payment->sync_metas( array_merge( $args['payment_metadata'], $_payment_metas ) );

		$total_due_amount = $calculator->subtract(
			$calculator->add( (string) $this->get_total(), (string) $total_extra_charges ),
			(string) $total_paid_amount
		);

		$_booking_metas = array(
			'total_paid_amount'                       => $total_paid_amount,
			'total_due_amount'                        => $total_due_amount,
			'_prev_booking_status'                    => $this->get_booking_status(),
			'wp_travel_engine_booking_payment_status' => 'completed',
			'wp_travel_engine_booking_status'         => 'booked',
		);

		$this->sync_metas( array_merge( $args['booking_metadata'], $_booking_metas ) );

		if ( $args['send_booking_emails'] ) {
			wptravelengine_send_booking_emails( $payment_id, 'order', 'all' );
		}

		if ( $args['send_payment_emails'] ) {
			wptravelengine_send_booking_emails( $payment_id, 'order_confirmation', 'all' );
		}

		if ( 'completed' !== $previous_payment_status ) {
			Events::add_event( 'wptravelengine.booking.payment.completed', $_payment->get_id(), $_payment->get_post_type() );
		}
	}

	/**
	 * Syncs metas to the Payment and Booking posts.
	 *
	 * @param int   $payment_id The payment ID.
	 * @param float $paid_amount The paid amount received from the payment gateway response.
	 * @param array $args The arguments with payment and booking IDs and its respective metadata to sync.
	 * {
	 *  'payment_metadata' => array<mixed>,
	 *  'booking_metadata' => array<mixed>,
	 * }
	 * @return void
	 * @since 6.7.0
	 * @since 6.7.1 Added support for payment event trigger and _prev_payment_status updation.
	 */
	public function sync_payment_pending_metas( int $payment_id, float $amount, array $args = array() ): void {
		$_payment = $this->get_payment_by_id( $payment_id );

		if ( ! ( $_payment instanceof Payment ) ) {
			throw new InvalidArgumentException( 'Payment ID #' . $payment_id . ' is not found in the booking #' . $this->get_id() . '.' );
		}

		$args = wp_parse_args(
			$args,
			array(
				'payment_metadata' => array(),
				'booking_metadata' => array(),
			)
		);

		$previous_payment_status = $_payment->get_payment_status();

		$_payment_metas = array(
			'payable'              => array(
				'currency' => $this->get_currency(),
				'amount'   => $amount,
			),
			'payment_status'       => 'pending',
			'_prev_payment_status' => $previous_payment_status,
		);

		$_payment->sync_metas( array_merge( $args['payment_metadata'], $_payment_metas ) );

		$_booking_metas = array(
			'_prev_booking_status'                    => $this->get_booking_status(),
			'wp_travel_engine_booking_payment_status' => 'pending',
			'wp_travel_engine_booking_status'         => 'pending',
		);

		$this->sync_metas( array_merge( $args['booking_metadata'], $_booking_metas ) );

		if ( 'pending' !== $previous_payment_status ) {
			Events::add_event( 'wptravelengine.booking.payment.pending', $_payment->get_id(), $_payment->get_post_type() );
		}
	}

	/**
	 * Syncs metas to the Payment and Booking posts.
	 *
	 * @param int   $payment_id The payment ID.
	 * @param float $paid_amount The paid amount received from the payment gateway response.
	 * @param array $args The arguments with payment and booking IDs and its respective metadata to sync.
	 * {
	 *  'payment_metadata' => array<mixed>,
	 *  'booking_metadata' => array<mixed>,
	 * }
	 * @return void
	 * @since 6.7.0
	 * @since 6.7.1 Added support for payment event trigger and _prev_payment_status updation.
	 */
	public function sync_payment_failed_metas( int $payment_id, float $amount, array $args = array() ): void {

		$_payment = $this->get_payment_by_id( $payment_id );

		if ( ! ( $_payment instanceof Payment ) ) {
			throw new InvalidArgumentException( 'Payment ID #' . $payment_id . ' is not found in the booking #' . $this->get_id() . '.' );
		}

		$args = wp_parse_args(
			$args,
			array(
				'payment_metadata' => array(),
				'booking_metadata' => array(),
			)
		);

		$previous_payment_status = $_payment->get_payment_status();

		$_payment_metas = array(
			'payable'              => array(
				'currency' => $this->get_currency(),
				'amount'   => $amount,
			),
			'payment_status'       => 'failed',
			'_prev_payment_status' => $previous_payment_status,
		);

		$_payment->sync_metas( array_merge( $args['payment_metadata'], $_payment_metas ) );

		$_booking_metas = array(
			'_prev_booking_status'                    => $this->get_booking_status(),
			'wp_travel_engine_booking_payment_status' => 'failed',
			'wp_travel_engine_booking_status'         => 'canceled',
		);

		$this->sync_metas( array_merge( $args['booking_metadata'], $_booking_metas ) );

		if ( 'failed' !== $previous_payment_status ) {
			Events::add_event( 'wptravelengine.booking.payment.failed', $_payment->get_id(), $_payment->get_post_type() );
		}
	}
}
