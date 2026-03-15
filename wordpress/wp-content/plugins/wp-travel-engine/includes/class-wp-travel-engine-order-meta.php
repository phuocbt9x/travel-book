<?php

/**
 * Place order form metas.
 *
 * Responsible for creating metaboxes for order.
 *
 * @package    Wp_Travel_Engine
 * @subpackage Wp_Travel_Engine/includes
 * @author
 */

use WPTravelEngine\Builders\FormFields\EmergencyFormFields;
use WPTravelEngine\Builders\FormFields\TravellerFormFields;
use WPTravelEngine\Core\Models\Post\Booking;
use WPTravelEngine\Core\Cart\Cart;
use WPTravelEngine\Core\Cart\Item;
use WPTravelEngine\Core\Models\Post\TravelerCategories;

class Wp_Travel_Engine_Order_Meta {

	public function __construct() {
		$this->init();
	}

	function init() {
		// add_action( 'add_meta_boxes_booking', array( $this, 'wpte_booking_details_add_meta_boxes' ) );
		// add_action( 'add_meta_boxes', array( $this, 'wpte_customer_add_meta_boxes' ) );
		// add_action( 'add_meta_boxes', array( $this, 'wpte_customer_history_add_meta_boxes' ) );

		// Combined to update wp-travel-engine default meta.
		add_action( 'save_post', array( __CLASS__, 'save_post' ), 11, 3 );
	}

	public static function save_post( $post_id, $post, $update ) {

		if ( ! $post || ! in_array(
			$post->post_type,
			array(
				WP_TRAVEL_ENGINE_POST_TYPE,
				// 'booking',
				// 'customer',
				'enquiry',
			),
			! 0
		) ) {
			return;
		}

		if ( 'booking' === $post->post_type ) {
			foreach (
				array(
					'wp_travel_engine_booking_payment_status' => array(
						'default'           => 'pending',
						'sanitize_callback' => function ( $value ) {
							return sanitize_text_field( $value );
						},
					),
					'wp_travel_engine_booking_payment_gateway' => array(
						'sanitize_callback' => function ( $value ) {
							return sanitize_text_field( $value );
						},
					),
					'wp_travel_engine_booking_payment_details' => array(
						'sanitize_callback' => function ( $value ) {
							return wp_unslash( $value );
						},
					),
					'wp_travel_engine_booking_status' => array(
						'sanitize_callback' => function ( $value ) {
							return sanitize_text_field( $value );
						},
					),
				) as $meta_key => $args
			) {
				if ( isset( $_POST[ $meta_key ] ) ) { // phpcs:ignore
					$meta_value = ( isset( $args[ 'sanitize_callback' ] ) ) ? call_user_func( $args[ 'sanitize_callback' ], $_POST[ $meta_key ] ) : wte_clean( wp_unslash( $_POST[ $meta_key ] ) ); // phpcs:ignore
					update_post_meta( $post_id, $meta_key, $meta_value );
				}
			}
		}

		if ( isset( $_POST[ 'wp_travel_engine_booking_setting' ] ) ) { // phpcs:ignore
			$settings = wte_clean( wp_unslash( $_POST[ 'wp_travel_engine_booking_setting' ] ) ); // phpcs:ignore
			update_post_meta( $post_id, 'wp_travel_engine_booking_setting', $settings );
		}

		// Add additional note from new checkout page template.
		if ( isset( $_POST['wptravelengine_additional_note'] ) ) {
			$additional_note = wte_clean( wp_unslash( $_POST[ 'wptravelengine_additional_note' ] ) ); // phpcs:ignore
			update_post_meta( $post_id, 'wptravelengine_additional_note', $additional_note );
		}

		// Add new billing info to booking meta.
		if ( isset( $_POST['wptravelengine_billing_details'] ) ) {
			$billing_details = wte_clean( wp_unslash( $_POST[ 'wptravelengine_billing_details' ] ) ); // phpcs:ignore
			update_post_meta( $post_id, 'wptravelengine_billing_details', $billing_details );
		}

		// Add new travelers info to booking meta.
		if ( isset( $_POST['travelers'] ) && is_array( $_POST['travelers'] ) ) {
			$travelers_data    = $_POST['travelers'];
			$travelers_details = array();

			// Define a mapping from form keys to database keys
			$key_mapping = self::get_key_mapping();

			// Find the maximum count of any attribute to determine the number of travelers
			$max_count = 0;
			foreach ( $travelers_data as $values ) {
				if ( is_array( $values ) ) {
					$max_count = max( $max_count, count( $values ) );
				}
			}

			for ( $i = 0; $i <= $max_count; $i++ ) {
				$traveler = array();
				foreach ( $travelers_data as $key => $values ) {
					if ( is_array( $values ) && array_key_exists( $i, $values ) ) {
						// Use the mapped key if it exists, otherwise use the original key
						$normalized_key              = $key_mapping[ $key ] ?? $key;
						$traveler[ $normalized_key ] = $values[ $i ];
					}
				}
				if ( ! empty( $traveler ) ) {
					$travelers_details[] = $traveler;
				}
			}

			// Sanitize the travelers data.
			$travelers_details = wte_clean( wp_unslash( $travelers_details ) );
			update_post_meta( $post_id, 'wptravelengine_travelers_details', $travelers_details );
		}

		// Add new emergency details info to booking meta.
		if ( isset( $_POST['emergency'] ) && is_array( $_POST['emergency'] ) ) {
			$emergency_data    = $_POST['emergency'];
			$emergency_details = array();

			// Define a mapping from form keys to database keys
			$key_mapping = self::get_key_mapping();

			// Map the emergency data to the database keys
			foreach ( $emergency_data as $key => $value ) {
				$normalized_key                       = $key_mapping[ $key ] ?? $key;
				$emergency_details[ $normalized_key ] = $value[1];
			}

			// Sanitize the emergency data.
			$emergency_details = wte_clean( wp_unslash( $emergency_details ) );
			update_post_meta( $post_id, 'wptravelengine_emergency_details', $emergency_details );
		}

		// Add new billing info to booking meta.
		if ( isset( $_POST['billing_info'] ) ) {
			$billing_info = $_POST['billing_info'];

			// Sanitize the billing info.
			$billing_info = wte_clean( wp_unslash( $billing_info ) );
			update_post_meta( $post_id, 'wptravelengine_billing_details', $billing_info );
		}
	}

	/**
	 * Get key mapping for form to database fields
	 *
	 * @return array
	 */
	public static function get_key_mapping() {
		return array(
			'First Name' => 'fname',
			'Last Name'  => 'lname',
			'Email'      => 'email',
			'Phone'      => 'phone',
			'Country'    => 'country',
		);
	}

	/**
	 * Place order form metabox.
	 *
	 * @since 1.0.0
	 */
	function wpte_booking_details_add_meta_boxes() {
		add_meta_box(
			'booking_details_id',
			__( 'Booking Details', 'wp-travel-engine' ),
			array( $this, 'wp_travel_engine_booking_details_metabox_callback' ),
			'booking',
			'normal',
			'high'
		);
	}

	/**
	 * Booking details metabox callback.
	 *
	 * @return void
	 */
	public function wp_travel_engine_booking_details_metabox_callback() {
		global $post;

		$_order_trips = get_post_meta( $post->ID, 'order_trips', true );
		if ( ! empty( $booking_status ) && ( ! isset( $_order_trips ) || ! is_array( $_order_trips ) ) ) {
			include WP_TRAVEL_ENGINE_BASE_PATH . '/includes/backend/booking/booking-details.php';
		} else {
			$this->booking_details_mb_callback( $post );
		}
	}

	/**
	 *
	 * Default booking data for new booking.
	 *
	 * @since 5.4.1
	 */
	function wptravelengine_edit_booking_defaults( $_post ) {
		$booking_object = (object) get_post( $_post );

		$pricing_categories = get_terms(
			array(
				'taxonomy'   => 'trip-packages-categories',
				'hide_empty' => false,
				'orderby'    => 'term_id',
				'fields'     => 'id=>name',
			)
		);
		$pax                = array();
		foreach ( array_keys( $pricing_categories ) as $term_id ) {
			$pax[ $term_id ] = 0;
		}

		$defaults = array(
			'order_trips'  => array(
				array(
					'ID'           => 0,
					'datetime'     => date( 'Y-m-d' ),
					'cost'         => 0,
					'pax_cost'     => array(),
					'trip_extras'  => array(),
					'title'        => '',
					'partial_cost' => 0,
					'pax'          => $pax,
					'has_time'     => false,
				),
			),
			'cart_info'    => array(
				'currency'     => wptravelengine_settings()->get( 'currency_code' ),
				'subtotal'     => 0,
				'total'        => 0,
				'cart_partial' => 0,
				'discounts'    => array(),
				'tax_amount'   => 0,
			),
			'billing_info' => array(
				'fname'   => '',
				'lname'   => '',
				'email'   => '',
				'address' => '',
				'city'    => '',
				'country' => '',
			),
		);

		foreach ( $defaults as $meta_key => $meta_value ) {
			$booking_object->{$meta_key} = $meta_value;
		}

		// Payment Section.
		$postarr = new \stdClass();

		$postarr->meta_input = wp_parse_args(
			array(),
			array(
				'payment_status' => 'pending',
				'billing_info'   => $booking_object->billing_info,
				'payable'        => array(
					'currency' => wptravelengine_settings()->get( 'currency_code' ),
					'amount'   => 0,
				),
			)
		);
		$payment_id          = wp_insert_post(
			wp_parse_args(
				$postarr,
				array(
					'post_type'   => 'wte-payments',
					'post_status' => 'publish',
					'post_title'  => "Payment for booking #{$booking_object->ID}",
				)
			)
		);

		$booking_object->payments     = array( $payment_id );
		$booking_object->due_amount   = 0;
		$booking_object->manual_entry = true;

		return $booking_object;
	}

	/**
	 * New Meta box callback for booking since 5.4.1
	 *
	 * @since 5.4.1
	 */
	public function booking_details_mb_callback( $post ) {
		$booking_object = get_post( $post );

		$_order_trips = get_post_meta( $booking_object->ID, 'order_trips', true );

		if ( empty( $_order_trips ) || key( $_order_trips ) === null ) {
			$booking_object = $this->wptravelengine_edit_booking_defaults( $booking_object->ID );
		}

		$_args = array( 'booking_details' => $booking_object );

		require plugin_dir_path( WP_TRAVEL_ENGINE_FILE_PATH ) . 'includes/backend/booking/booking-parts/booking-details.php';
	}

	/**
	 * Place order form metabox.
	 *
	 * @since 1.0.0
	 */
	function wpte_customer_add_meta_boxes() {
		$screens = array( 'customer' );
		foreach ( $screens as $screen ) {
			add_meta_box(
				'customer_id',
				__( 'Customer Details', 'wp-travel-engine' ),
				array( $this, 'wp_travel_engine_customer_metabox_callback' ),
				$screen,
				'normal',
				'high'
			);
		}
	}

	// Tab for notice listing and settings
	public function wp_travel_engine_customer_metabox_callback() {
		include WP_TRAVEL_ENGINE_BASE_PATH . '/includes/backend/booking/customer.php';
	}

	/**
	 * Customer History Metabox
	 *
	 * @since 1.0.0
	 */
	function wpte_customer_history_add_meta_boxes() {
		$screens = array( 'customer' );
		foreach ( $screens as $screen ) {
			add_meta_box(
				'customer_history_id',
				__( 'Customer History', 'wp-travel-engine' ),
				array( $this, 'wp_travel_engine_customer_history_metabox_callback' ),
				$screen,
				'normal',
				'high'
			);
		}
	}

	// Tab for notice listing and settings
	public function wp_travel_engine_customer_history_metabox_callback() {
		include WP_TRAVEL_ENGINE_BASE_PATH . '/includes/backend/booking/customer-history.php';
	}
}

$obj = new Wp_Travel_Engine_Order_Meta();
