<?php
/**
 *
 * Handles Booking Post-Type.
 *
 * @since 5.5.2
 */

namespace WPTravelEngine\Core\Trip;

use WPTravelEngine\Core\Models\Post\Booking as BookingModel;

/**
 *
 * @deprecated 6.0.0
 */
class Booking extends BookingModel {

	/**
	 * @inheritDoc
	 */
	public function __construct( $booking_id = null ) {
		wptravelengine_deprecated_class( __CLASS__, '6.0.0', 'WPTravelEngine\Core\Models\Post\Booking' );
		if ( ! is_null( $booking_id ) ) {
			parent::__construct( $booking_id );
		}
	}

	protected static $instance = null;

	public function insert_post() {
		return \wp_insert_post(
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

	public function create() {
		$this->ID   = $this->insert_post();
		$this->post = get_post( $this->ID );
	}

	public function get_booking_object(): \WP_Post {
		return $this->post;
	}

	public function update_booking_meta( $meta_key, $data, $booking_id = null ) {

		$instance = is_null( $this->ID ) ? new self( $booking_id ) : $this;
		$instance->update_meta( $meta_key, $data );

		return $instance;
	}

	public function update_booking_status( $status, $update ) {
		_deprecated_function( __METHOD__, '6.0.0', 'WPTravelEngine\Core\Models\Post\Booking::update_status' );
		$this->update_status( $status );
	}

	public function update_cart_info( $data, $update = false ) {
		$_data = array();

		if ( $update ) {
			$current_value = get_post_meta( $this->ID, 'cart_info', true );
			if ( is_array( $current_value ) ) {
				$_data = wp_parse_args( $data, $current_value );
			}
		}

		return $this->update_booking_meta( 'cart_info', $_data );
	}

	public function update_billing_info( $data, $update = false ) {

		if ( $update ) {
			$current_value = get_post_meta( $this->ID, 'billing_info', true );
			if ( is_array( $current_value ) ) {
				$_data = wp_parse_args( $data, $current_value );
			}
		}

		return $this->update_booking_meta( 'billing_info', $data );
	}

	public function update_order_items( $data, $update = false ) {
		$_data = array();
		if ( $update ) {
			$current_order_trips = get_post_meta( $this->ID, 'order_trips', true );

			foreach ( array_keys( $current_order_trips ) as $cart_id ) {
				if ( ! isset( $data[ $cart_id ] ) ) {
					$_data[ $cart_id ] = $current_order_trips[ $cart_id ];
					continue;
				}
				$cart_data = $data[ $cart_id ];

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
		} else {
			$_data = $data;
		}

		return $this->update_booking_meta( 'order_trips', $_data );
	}

	public function update_legacy_order_meta( $data ) {
		return $this->update_booking_meta( 'wp_travel_engine_booking_setting', $data );
	}

	public function update_payments( $data ) {
		return $this->update_booking_meta( 'payments', $data );
	}
}
