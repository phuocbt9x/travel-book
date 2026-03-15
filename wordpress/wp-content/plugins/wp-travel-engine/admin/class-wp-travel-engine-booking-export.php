<?php
/**
 * WP Travel Engine - Export Booking Data
 *
 * @package WP_Travel_Engine
 */

use WPTravelEngine\Core\Models\Post\Booking as BookingModel;
use WPTravelEngine\Helpers\CartInfoParser;
use WPTravelEngine\Helpers\Countries;

/**
 * WP Travel Engine Booking Export
 *
 * @since 5.7.4
 */
class WP_Travel_Engine_Booking_Export {

	/**
	 * Safely unserialize data with restricted classes.
	 * Tries JSON first, then falls back to unserialize with security restrictions.
	 *
	 * @param mixed $data Data that might be serialized or JSON.
	 * @return mixed Unserialized data or original data if not serialized.
	 * @since 6.7.0
	 */
	private static function safe_unserialize( $data ) {
		if ( ! is_string( $data ) || empty( $data ) ) {
			return $data;
		}

		$json_data = json_decode( $data, true );
		if ( json_last_error() === JSON_ERROR_NONE ) {
			return $json_data;
		}
		if ( is_serialized( $data ) ) {
			return @unserialize( $data, array( 'allowed_classes' => false ) );
		}

		return $data;
	}

	/**
	 * Initialize export procedure.
	 *
	 * @return void
	 */
	public function init() {
		if ( ! isset( $_REQUEST['booking_export_submit'] ) || ! wp_verify_nonce( $_REQUEST['booking_export_nonce'], 'booking_export_nonce_action' ) ) {
			return;
		}

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'You do not have sufficient permissions to perform this action.', 'wp-travel-engine' ) );
		}

		$date_range                    = sanitize_text_field( $_REQUEST['wte_booking_range'] ?? '' );
		$dates                         = array_pad( explode( ' to ', $date_range ), 2, '' );
		list( $start_date, $end_date ) = $dates;
		$booking_status                = sanitize_text_field( $_REQUEST['wptravelengine_booking_status'] ?? 'all' );
		$trip_id                       = sanitize_text_field( $_REQUEST['wptravelengine_trip_id'] ?? 'all' );
		$filter_ids                    = $trip_id == 'all' ? array() : wptravelengine_get_booking_ids( (int) $trip_id );
		$queries_data                  = self::export_query( $start_date, $end_date, $booking_status, $filter_ids );
		self::data_export( $queries_data );
		exit;
	}

	/**
	 * Query to retrieve data based on start date and end date.
	 *
	 * @param string $start_date Start Date.
	 * @param string $end_date End Date.
	 * @param string $booking_status Booking Status.
	 * @param array  $filter_ids Booking IDs.
	 *
	 * @since 5.7.4
	 *
	 * @modified_since 6.3.5 - Trip Name and booking status filter added.
	 */
	public function export_query( $start_date, $end_date, $booking_status, $filter_ids ) {
		global $wpdb;

		$meta_keys = array(
			'wp_travel_engine_booking_status',
			'order_trips',
			'billing_info',
			'wp_travel_engine_booking_payment_gateway',
			'_wte_wc_order_id',
			'payments',
		);

		$post_status = array( 'publish', 'draft' );

		$sql = "
		    SELECT
		        p.ID AS BookingID,
		        (
		         SELECT pm1.meta_value
		         FROM $wpdb->postmeta pm1
		         WHERE pm1.post_id = p.ID AND pm1.meta_key = %s
		         LIMIT 1
		        ) AS BookingStatus,

		        (
		         SELECT pm2.meta_value
		         FROM $wpdb->postmeta pm2
		         WHERE pm2.post_id = p.ID AND pm2.meta_key = %s
		         LIMIT 1
		        ) AS placeorder,

		        (
		         SELECT pm3.meta_value
		         FROM $wpdb->postmeta pm3
		         WHERE pm3.post_id = p.ID AND pm3.meta_key = %s
		         LIMIT 1
		        ) AS billinginfo,

		        (
		         SELECT pm4.meta_value
		         FROM $wpdb->postmeta pm4
		         WHERE pm4.post_id = p.ID AND pm4.meta_key = %s
		         LIMIT 1
		        ) AS PaymentGateway,

		        (
		         SELECT pm5.meta_value
		         FROM $wpdb->postmeta pm5
		         WHERE pm5.post_id = p.ID AND pm5.meta_key = %s
		         LIMIT 1
		        ) AS wc_id,

		        p.post_date AS BookingDate,

				(
					SELECT pm6.meta_value
					FROM $wpdb->postmeta pm6
					WHERE pm6.post_id = p.ID AND pm6.meta_key = %s
					LIMIT 1
				) AS payments

		    FROM
		        $wpdb->postmeta pm
		    INNER JOIN
		        $wpdb->posts p ON pm.post_id = p.ID

		    WHERE
		        pm.meta_key IN ('" . implode( "', '", $meta_keys ) . "')
		    AND
		        p.post_type = %s
		    AND
		        p.post_status IN ('" . implode( "', '", $post_status ) . "')
		";

		$meta_keys[] = 'booking';
		if ( ! empty( $start_date ) && ! empty( $end_date ) ) {
			$sql        .= ' AND DATE(p.post_date) >= %s AND DATE(p.post_date) <= %s';
			$meta_keys[] = $start_date;
			$meta_keys[] = $end_date;
		} elseif ( ! empty( $start_date ) ) {
			$sql        .= ' AND DATE(p.post_date) = %s';
			$meta_keys[] = $start_date;
		}

		if ( ( 'all' !== $booking_status ) ) {
			$sql        .= " AND 
						EXISTS (
							SELECT 1
							FROM $wpdb->postmeta pm_status
							WHERE pm_status.post_id = p.ID
							AND pm_status.meta_key = 'wp_travel_engine_booking_status'
							AND pm_status.meta_value = %s
						)";
			$meta_keys[] = $booking_status;
		}

		if ( ! empty( $filter_ids ) ) {
			$filter_ids   = array_map( 'absint', $filter_ids );
			$placeholders = implode( ',', array_fill( 0, count( $filter_ids ), '%d' ) );
			$sql         .= " AND p.ID IN ($placeholders)";
			$meta_keys    = array_merge( $meta_keys, $filter_ids );
		}
		$sql .= ' GROUP BY BookingID, BookingDate, BookingStatus ORDER BY BookingID DESC';
		// Spread $meta_keys so each placeholder is bound (required for wpdb::prepare).
		return $wpdb->get_results( $wpdb->prepare( $sql, ...$meta_keys ) );
	}

	/**
	 * Sanitize CSV value to prevent CSV injection.
	 * Prevents values starting with =, +, -, @, or tab from being interpreted as formulas.
	 *
	 * @param mixed $value Value to sanitize.
	 * @return mixed Sanitized value (preserves numeric types).
	 * @since 6.7.4
	 */
	private static function sanitize_csv_value( $value ) {
		if ( is_numeric( $value ) || $value === null || $value === '' ) {
			return $value;
		}
		$string_value = (string) $value;
		if ( ! empty( $string_value ) && in_array( substr( $string_value, 0, 1 ), array( '=', '+', '-', '@', "\t" ), true ) ) {
			return "\t" . $string_value;
		}
		return $string_value;
	}

	/**
	 * Convert country code to country name.
	 *
	 * @param mixed  $value         Field value (could be country code).
	 * @param string $field_name    Field name to check if it's a country field.
	 * @param array  $countries_list List of countries (code => name).
	 * @return mixed Converted country name or original value.
	 * @since 6.7.4
	 */
	private static function convert_country_codes( $value, $field_name, $countries_list ) {
		if ( ( $field_name === 'country' || $field_name === 'country_code' ) && ! empty( $value ) && isset( $countries_list[ $value ] ) ) {
			return $countries_list[ $value ];
		}
		return $value;
	}

	/**
	 * Collect billing field names from a booking.
	 *
	 * @param int $booking_id Booking ID.
	 * @return array Array of billing field names.
	 * @since 6.7.4
	 */
	private static function collect_billing_fields( $booking_id ) {
		$billing_fields  = array();
		$billing_details = get_post_meta( $booking_id, 'wptravelengine_billing_details', true );
		if ( ! empty( $billing_details ) ) {
			$billing_data = self::safe_unserialize( $billing_details );
			if ( is_array( $billing_data ) ) {
				foreach ( $billing_data as $field => $value ) {
					if ( ! in_array( $field, $billing_fields ) ) {
						$billing_fields[] = $field;
					}
				}
			}
		}
		return $billing_fields;
	}

	/**
	 * Collect traveler field names and determine max travelers from a booking.
	 *
	 * @param int $booking_id Booking ID.
	 * @return array Array with 'lead_traveler_fields', 'regular_traveler_fields', and 'max_travelers'.
	 * @since 6.7.4
	 */
	private static function collect_traveler_fields( $booking_id ) {
		$lead_traveler_fields    = array();
		$regular_traveler_fields = array();
		$max_travelers           = 0;

		$traveler_details = get_post_meta( $booking_id, 'wptravelengine_travelers_details', true );
		if ( ! empty( $traveler_details ) ) {
			$travelers = self::safe_unserialize( $traveler_details );
			if ( is_array( $travelers ) ) {
				$max_travelers = count( $travelers );
				foreach ( $travelers as $index => $traveler ) {
					if ( is_array( $traveler ) ) {
						foreach ( $traveler as $field => $value ) {
							$is_lead_field = (
								preg_match( '/^lead[-_\s]/i', $field ) ||
								preg_match( '/lead[-_\s]traveller/i', $field )
							);

							if ( $index === 0 && $is_lead_field ) {
								if ( ! in_array( $field, $lead_traveler_fields ) ) {
									$lead_traveler_fields[] = $field;
								}
							} elseif ( ! $is_lead_field ) {
								if ( ! in_array( $field, $regular_traveler_fields ) ) {
									$regular_traveler_fields[] = $field;
								}
							}
						}
					}
				}
			}
		}

		return array(
			'lead_traveler_fields'    => $lead_traveler_fields,
			'regular_traveler_fields' => $regular_traveler_fields,
			'max_travelers'           => $max_travelers,
		);
	}

	/**
	 * Collect emergency contact field names and determine max contacts from a booking.
	 *
	 * @param int $booking_id Booking ID.
	 * @return array Array with 'emergency_fields' and 'max_emergency_contacts'.
	 * @since 6.7.4
	 */
	private static function collect_emergency_contact_fields( $booking_id ) {
		$emergency_fields       = array();
		$max_emergency_contacts = 0;

		$emergency_details = get_post_meta( $booking_id, 'wptravelengine_emergency_details', true );
		if ( ! empty( $emergency_details ) ) {
			$emergency_data = self::safe_unserialize( $emergency_details );
			if ( is_array( $emergency_data ) ) {
				if ( isset( $emergency_data['fname'] ) || isset( $emergency_data['first_name'] ) ) {
					$max_emergency_contacts = 1;
					foreach ( $emergency_data as $field => $value ) {
						if ( ! in_array( $field, $emergency_fields ) ) {
							$emergency_fields[] = $field;
						}
					}
				} else {
					$max_emergency_contacts = count( $emergency_data );
					foreach ( $emergency_data as $contact ) {
						if ( is_array( $contact ) ) {
							foreach ( $contact as $field => $value ) {
								if ( ! in_array( $field, $emergency_fields ) ) {
									$emergency_fields[] = $field;
								}
							}
						}
					}
				}
			}
		}

		return array(
			'emergency_fields'       => $emergency_fields,
			'max_emergency_contacts' => $max_emergency_contacts,
		);
	}

	/**
	 * Collect payment field names, check cart version, and payment-related flags from a booking.
	 *
	 * @param int $booking_id Booking ID.
	 * @return array Array with 'payment_fields', 'max_payments', 'has_cart_v4', 'has_cart_less_than_v4', 'has_discount'.
	 * @since 6.7.4
	 */
	private static function collect_payment_fields_and_flags( $booking_id ) {
		$payment_fields        = array();
		$max_payments          = 0;
		$has_cart_v4           = false;
		$has_cart_less_than_v4 = false;
		$has_discount          = false;

		try {
			$booking = BookingModel::make( $booking_id );
			if ( $booking ) {
				// Check if this booking has cart version >= 4
				if ( $booking->is_curr_cart( '>=', '4.0' ) ) {
					$has_cart_v4 = true;
				} else {
					$has_cart_less_than_v4 = true;
				}

				$payments = $booking->get_payments();
				if ( is_array( $payments ) && ! empty( $payments ) ) {
					$max_payments = count( $payments );

					// Collect deposit (same pattern as payment edit form - separate from fees)
					if ( ! in_array( 'deposit', $payment_fields ) ) {
						$payment_fields[] = 'deposit';
					}

					// Collect fee fields using same pattern as payment edit form (get_fees())
					$fees = $booking->get_fees();
					foreach ( $fees as $fee ) {
						$slug = $fee['name'];
						if ( ! in_array( $slug, $payment_fields ) ) {
							$payment_fields[] = $slug;
						}
					}
				}

				// Check for discount/coupon information
				try {
					$cart_info        = new CartInfoParser( $booking->get_cart_info() ?? array() );
					$deductible_items = $cart_info->get_deductible_items();
					if ( ! empty( $deductible_items ) ) {
						foreach ( $deductible_items as $item ) {
							if ( isset( $item['_class_name'] ) && $item['_class_name'] === 'WPTravelEngine\Core\Cart\Adjustments\CouponAdjustment' ) {
								$has_discount = true;
								break;
							}
						}
					}
				} catch ( \Exception $e ) {
					error_log( 'Error in collect_payment_fields_and_flags (cart_info parsing): ' . $e->getMessage() );
				}
			}
		} catch ( \Exception $e ) {
			error_log( 'Error in collect_payment_fields_and_flags: ' . $e->getMessage() );
			// Try fallback: check payments meta directly
			$payments_meta = get_post_meta( $booking_id, 'payments', true );
			if ( ! empty( $payments_meta ) && is_array( $payments_meta ) ) {
				$max_payments = count( $payments_meta );
			}
		}

		return array(
			'payment_fields'        => $payment_fields,
			'max_payments'          => $max_payments,
			'has_cart_v4'           => $has_cart_v4,
			'has_cart_less_than_v4' => $has_cart_less_than_v4,
			'has_discount'          => $has_discount,
		);
	}

	/**
	 * Check if booking has accommodation, extra services, pickup points, or travel insurance.
	 *
	 * @param int $booking_id Booking ID.
	 * @return array Array with 'has_accommodation', 'has_extra_services', 'has_pickup_points', 'has_travel_insurance'.
	 * @since 6.7.4
	 */
	private static function check_optional_line_items( $booking_id ) {
		$has_accommodation    = false;
		$has_extra_services   = false;
		$has_pickup_points    = false;
		$has_travel_insurance = false;

		// Check for accommodation, extra services, pickup points, and travel insurance
		$cart_info = get_post_meta( $booking_id, 'cart_info', true );
		if ( ! empty( $cart_info ) && is_array( $cart_info ) && isset( $cart_info['items'][0]['line_items'] ) ) {
			$line_items = $cart_info['items'][0]['line_items'];

			// Check accommodation
			if ( isset( $line_items['accommodation'] ) && is_array( $line_items['accommodation'] ) && ! empty( $line_items['accommodation'] ) ) {
				$has_accommodation = true;
			}

			// Check extra services (check both 'extra_service' and 'extra_services' keys)
			if ( ( isset( $line_items['extra_service'] ) && is_array( $line_items['extra_service'] ) && ! empty( $line_items['extra_service'] ) ) ||
				( isset( $line_items['extra_services'] ) && is_array( $line_items['extra_services'] ) && ! empty( $line_items['extra_services'] ) ) ) {
				$has_extra_services = true;
			}

			// Check pickup points from line items
			if ( isset( $line_items['pickup_point'] ) && is_array( $line_items['pickup_point'] ) && ! empty( $line_items['pickup_point'] ) ) {
				$has_pickup_points = true;
			}

			// Check travel insurance from line items
			if ( isset( $line_items['travel_insurance'] ) && is_array( $line_items['travel_insurance'] ) && ! empty( $line_items['travel_insurance'] ) ) {
				$has_travel_insurance = true;
			}
		}

		if ( ! $has_travel_insurance ) {
			$travel_insurance_meta = get_post_meta( $booking_id, 'wptravelengine_travel_insurance', true );
			if ( empty( $travel_insurance_meta ) || ! is_array( $travel_insurance_meta ) ) {
				$travel_insurance_meta = get_post_meta( $booking_id, 'travel_insurance', true );
			}
			if ( ! empty( $travel_insurance_meta ) && is_array( $travel_insurance_meta ) ) {
				if ( isset( $travel_insurance_meta['travel_insurance_plans'] ) && ! empty( $travel_insurance_meta['travel_insurance_plans'] ) ) {
					$has_travel_insurance = true;
				} elseif ( isset( $travel_insurance_meta['follow_up_question'] ) && ! empty( $travel_insurance_meta['follow_up_question'] ) ) {
					$has_travel_insurance = true;
				} elseif ( isset( $travel_insurance_meta['travel_insurance_affiliate_link'] ) && ! empty( $travel_insurance_meta['travel_insurance_affiliate_link'] ) ) {
					$has_travel_insurance = true;
				}
			}
		}

		return array(
			'has_accommodation'    => $has_accommodation,
			'has_extra_services'   => $has_extra_services,
			'has_pickup_points'    => $has_pickup_points,
			'has_travel_insurance' => $has_travel_insurance,
		);
	}

	/**
	 * Collect all field definitions from bookings.
	 *
	 * @param array $queries_data Queries Data.
	 * @return array Field definitions including billing_fields, traveler_fields, emergency_fields, payment_fields, max counts, and flags.
	 * @since 6.7.4
	 */
	private static function collect_field_definitions( $queries_data ) {
		$lead_traveler_fields    = array();
		$regular_traveler_fields = array();
		$emergency_fields        = array();
		$max_travelers           = 0;
		$max_emergency_contacts  = 0;
		$max_payments            = 0;
		$payment_fields          = array();
		$billing_fields          = array();
		$has_discount            = false;
		$has_deposit             = false;
		$has_tax                 = false;
		$has_cart_v4             = false;
		$has_cart_less_than_v4   = false;
		$has_accommodation       = false;
		$has_extra_services      = false;
		$has_pickup_points       = false;
		$has_travel_insurance    = false;

		foreach ( $queries_data as $data ) {
			// Collect billing fields
			$booking_billing_fields = self::collect_billing_fields( $data->BookingID );
			foreach ( $booking_billing_fields as $field ) {
				if ( ! in_array( $field, $billing_fields ) ) {
					$billing_fields[] = $field;
				}
			}

			// Collect traveler fields
			$traveler_data = self::collect_traveler_fields( $data->BookingID );
			$max_travelers = max( $max_travelers, $traveler_data['max_travelers'] );
			foreach ( $traveler_data['lead_traveler_fields'] as $field ) {
				if ( ! in_array( $field, $lead_traveler_fields ) ) {
					$lead_traveler_fields[] = $field;
				}
			}
			foreach ( $traveler_data['regular_traveler_fields'] as $field ) {
				if ( ! in_array( $field, $regular_traveler_fields ) ) {
					$regular_traveler_fields[] = $field;
				}
			}

			// Collect emergency contact fields
			$emergency_data         = self::collect_emergency_contact_fields( $data->BookingID );
			$max_emergency_contacts = max( $max_emergency_contacts, $emergency_data['max_emergency_contacts'] );
			foreach ( $emergency_data['emergency_fields'] as $field ) {
				if ( ! in_array( $field, $emergency_fields ) ) {
					$emergency_fields[] = $field;
				}
			}

			// Collect payment fields and flags
			$payment_data = self::collect_payment_fields_and_flags( $data->BookingID );
			$max_payments = max( $max_payments, $payment_data['max_payments'] );
			foreach ( $payment_data['payment_fields'] as $field ) {
				if ( ! in_array( $field, $payment_fields ) ) {
					$payment_fields[] = $field;
				}
			}
			if ( $payment_data['has_cart_v4'] ) {
				$has_cart_v4 = true;
			}
			if ( $payment_data['has_cart_less_than_v4'] ) {
				$has_cart_less_than_v4 = true;
			}
			if ( $payment_data['has_discount'] ) {
				$has_discount = true;
			}

			// Check optional line items
			$optional_items = self::check_optional_line_items( $data->BookingID );
			if ( $optional_items['has_accommodation'] ) {
				$has_accommodation = true;
			}
			if ( $optional_items['has_extra_services'] ) {
				$has_extra_services = true;
			}
			if ( $optional_items['has_pickup_points'] ) {
				$has_pickup_points = true;
			}
			if ( $optional_items['has_travel_insurance'] ) {
				$has_travel_insurance = true;
			}
		}

		return array(
			'billing_fields'          => $billing_fields,
			'lead_traveler_fields'    => $lead_traveler_fields,
			'regular_traveler_fields' => $regular_traveler_fields,
			'emergency_fields'        => $emergency_fields,
			'payment_fields'          => $payment_fields,
			'max_travelers'           => $max_travelers,
			'max_emergency_contacts'  => $max_emergency_contacts,
			'max_payments'            => $max_payments,
			'has_discount'            => $has_discount,
			'has_deposit'             => $has_deposit,
			'has_tax'                 => $has_tax,
			'has_cart_v4'             => $has_cart_v4,
			'has_cart_less_than_v4'   => $has_cart_less_than_v4,
			'has_accommodation'       => $has_accommodation,
			'has_extra_services'      => $has_extra_services,
			'has_pickup_points'       => $has_pickup_points,
			'has_travel_insurance'    => $has_travel_insurance,
		);
	}

	/**
	 * Verify which payment fields have actual values across all bookings.
	 *
	 * @param array $queries_data Queries Data.
	 * @return array Array of payment field keys that have actual values.
	 * @since 6.7.4
	 */
	private static function verify_payment_fields( $queries_data ) {
		$payment_fields_with_actual_values = array();

		foreach ( $queries_data as $data ) {
			try {
				$booking = BookingModel::make( $data->BookingID );
				if ( $booking ) {
					$payments = $booking->get_payments();
					if ( is_array( $payments ) && ! empty( $payments ) ) {
						// Get payments data using same pattern as payment edit form
						$cart_info    = new CartInfoParser( $booking->get_cart_info() ?? array() );
						$payment_data = $cart_info->is_curr_cart_ver( '>=' ) ? $booking->get_payments_data( false )['payments'] ?? array() : array();

						// Verify deposit (same pattern as payment edit form - separate from fees)
						foreach ( $payments as $payment ) {
							$payment_id    = isset( $payment->ID ) ? $payment->ID : ( method_exists( $payment, 'get_id' ) ? $payment->get_id() : 0 );
							$_payment_data = $payment_data[ $payment_id ] ?? array();

							// Verify deposit
							if ( isset( $_payment_data['deposit'] ) ) {
								$deposit_value = $_payment_data['deposit'];
								if ( $deposit_value !== null && $deposit_value !== '' && $deposit_value !== false ) {
									if ( is_numeric( $deposit_value ) && (float) $deposit_value != 0 ) {
										if ( ! in_array( 'deposit', $payment_fields_with_actual_values ) ) {
											$payment_fields_with_actual_values[] = 'deposit';
										}
									} elseif ( is_string( $deposit_value ) && ! empty( trim( $deposit_value ) ) && trim( $deposit_value ) !== 'null' && trim( $deposit_value ) !== 'NULL' ) {
										if ( ! in_array( 'deposit', $payment_fields_with_actual_values ) ) {
											$payment_fields_with_actual_values[] = 'deposit';
										}
									}
								}
							}
						}

						// Get fee fields using same pattern as payment edit form (get_fees())
						$fees      = $booking->get_fees();
						$fee_slugs = array();
						foreach ( $fees as $fee ) {
							$fee_slugs[] = $fee['name'];
						}

						foreach ( $payments as $payment ) {
							$payment_id    = isset( $payment->ID ) ? $payment->ID : ( method_exists( $payment, 'get_id' ) ? $payment->get_id() : 0 );
							$_payment_data = $payment_data[ $payment_id ] ?? array();

							// Verify fee fields (same pattern as payment edit form)
							foreach ( $fee_slugs as $slug ) {
								if ( isset( $_payment_data[ $slug ] ) ) {
									$value = $_payment_data[ $slug ];
									if ( $value !== null && $value !== '' && $value !== false ) {
										if ( is_string( $value ) ) {
											$trimmed_value = trim( $value );
											// Skip only empty strings and null strings
											if ( empty( $trimmed_value ) || $trimmed_value === 'null' || $trimmed_value === 'NULL' ) {
												continue;
											}
											if ( ! in_array( $slug, $payment_fields_with_actual_values ) ) {
												$payment_fields_with_actual_values[] = $slug;
											}
										} elseif ( is_array( $value ) && ! empty( $value ) ) {
											if ( ! in_array( $slug, $payment_fields_with_actual_values ) ) {
												$payment_fields_with_actual_values[] = $slug;
											}
										} elseif ( is_numeric( $value ) ) {
											// Allow zero values
											if ( ! in_array( $slug, $payment_fields_with_actual_values ) ) {
												$payment_fields_with_actual_values[] = $slug;
											}
										}
									}
								}
							}
						}
					}
				}
			} catch ( \Exception $e ) {
				// Log the error for debugging
				error_log( 'Error in verify_payment_fields (booking ID ' . $data->BookingID . '): ' . $e->getMessage() );
			}
		}

		// Remove duplicates and re-index
		return array_values( array_unique( $payment_fields_with_actual_values ) );
	}

	/**
	 * Verify which billing fields have actual values across all bookings.
	 *
	 * @param array $queries_data Queries Data.
	 * @return array Array of billing field names that have actual values.
	 * @since 6.7.4
	 */
	private static function verify_billing_fields( $queries_data ) {
		$billing_fields_with_values = array();

		foreach ( $queries_data as $data ) {
			$billing_details = get_post_meta( $data->BookingID, 'wptravelengine_billing_details', true );
			if ( ! empty( $billing_details ) ) {
				$billing_data = self::safe_unserialize( $billing_details );
				if ( is_array( $billing_data ) ) {
					foreach ( $billing_data as $field => $value ) {
						// Skip if already verified
						if ( in_array( $field, $billing_fields_with_values ) ) {
							continue;
						}

						// Check if value is non-empty
						if ( $value === null || $value === '' || $value === false ) {
							continue;
						}

						// Skip empty arrays
						if ( is_array( $value ) && empty( $value ) ) {
							continue;
						}

						// Skip whitespace-only strings and null strings
						if ( is_string( $value ) ) {
							$trimmed_value = trim( $value );
							if ( empty( $trimmed_value ) || $trimmed_value === 'null' || $trimmed_value === 'NULL' ) {
								continue;
							}
						}

						// If we get here, the field has a value (including zero values)
						$billing_fields_with_values[] = $field;
					}
				}
			}
		}

		// Remove duplicates and re-index
		return array_values( array_unique( $billing_fields_with_values ) );
	}

	/**
	 * Verify which traveler fields have actual values across all bookings.
	 *
	 * @param array $queries_data Queries Data.
	 * @return array Array with 'lead_traveler_fields' and 'regular_traveler_fields' that have actual values.
	 * @since 6.7.4
	 */
	private static function verify_traveler_fields( $queries_data ) {
		$lead_traveler_fields_with_values    = array();
		$regular_traveler_fields_with_values = array();

		foreach ( $queries_data as $data ) {
			$traveler_details = get_post_meta( $data->BookingID, 'wptravelengine_travelers_details', true );
			if ( ! empty( $traveler_details ) ) {
				$travelers = self::safe_unserialize( $traveler_details );
				if ( is_array( $travelers ) ) {
					foreach ( $travelers as $index => $traveler ) {
						if ( is_array( $traveler ) ) {
							foreach ( $traveler as $field => $value ) {
								// Check if value is non-empty
								if ( $value === null || $value === '' || $value === false ) {
									continue;
								}

								// Skip empty arrays
								if ( is_array( $value ) && empty( $value ) ) {
									continue;
								}

								// Skip whitespace-only strings and null strings
								if ( is_string( $value ) ) {
									$trimmed_value = trim( $value );
									if ( empty( $trimmed_value ) || $trimmed_value === 'null' || $trimmed_value === 'NULL' ) {
										continue;
									}
								}

								// Determine if this is a lead field
								$is_lead_field = (
									preg_match( '/^lead[-_\s]/i', $field ) ||
									preg_match( '/lead[-_\s]traveller/i', $field )
								);

								if ( $index === 0 && $is_lead_field ) {
									if ( ! in_array( $field, $lead_traveler_fields_with_values ) ) {
										$lead_traveler_fields_with_values[] = $field;
									}
								} elseif ( ! $is_lead_field ) {
									if ( ! in_array( $field, $regular_traveler_fields_with_values ) ) {
										$regular_traveler_fields_with_values[] = $field;
									}
								}
							}
						}
					}
				}
			}
		}

		// Always include Pricing Category from traveller details (lead: pricing_category, others: traveller_pricing_category).
		if ( ! in_array( 'pricing_category', $lead_traveler_fields_with_values, true ) ) {
			$lead_traveler_fields_with_values[] = 'pricing_category';
		}
		if ( ! in_array( 'traveller_pricing_category', $regular_traveler_fields_with_values, true ) ) {
			$regular_traveler_fields_with_values[] = 'traveller_pricing_category';
		}

		return array(
			'lead_traveler_fields'    => array_values( array_unique( $lead_traveler_fields_with_values ) ),
			'regular_traveler_fields' => array_values( array_unique( $regular_traveler_fields_with_values ) ),
		);
	}

	/**
	 * Verify which emergency contact fields have actual values across all bookings.
	 *
	 * @param array $queries_data Queries Data.
	 * @return array Array of emergency contact field names that have actual values.
	 * @since 6.7.4
	 */
	private static function verify_emergency_contact_fields( $queries_data ) {
		$emergency_fields_with_values = array();

		foreach ( $queries_data as $data ) {
			$emergency_details = get_post_meta( $data->BookingID, 'wptravelengine_emergency_details', true );
			if ( ! empty( $emergency_details ) ) {
				$emergency_data = self::safe_unserialize( $emergency_details );
				if ( is_array( $emergency_data ) ) {
					// Handle single contact format
					if ( isset( $emergency_data['fname'] ) || isset( $emergency_data['first_name'] ) ) {
						$emergency_data = array( $emergency_data );
					}

					foreach ( $emergency_data as $contact ) {
						if ( is_array( $contact ) ) {
							foreach ( $contact as $field => $value ) {
								// Skip if already verified
								if ( in_array( $field, $emergency_fields_with_values ) ) {
									continue;
								}

								// Check if value is non-empty
								if ( $value === null || $value === '' || $value === false ) {
									continue;
								}

								// Skip empty arrays
								if ( is_array( $value ) && empty( $value ) ) {
									continue;
								}

								// Skip whitespace-only strings and null strings
								if ( is_string( $value ) ) {
									$trimmed_value = trim( $value );
									if ( empty( $trimmed_value ) || $trimmed_value === 'null' || $trimmed_value === 'NULL' ) {
										continue;
									}
								}

								// If we get here, the field has a value (including zero values)
								$emergency_fields_with_values[] = $field;
							}
						}
					}
				}
			}
		}

		// Remove duplicates and re-index
		return array_values( array_unique( $emergency_fields_with_values ) );
	}

	/**
	 * Build CSV headers array.
	 *
	 * @param array $field_definitions Field definitions from collect_field_definitions().
	 * @param array $payment_fields_with_actual_values Payment fields that have actual values.
	 * @param array $billing_fields_with_values Billing fields that have actual values.
	 * @param array $traveler_fields_with_values Traveler fields that have actual values (with 'lead_traveler_fields' and 'regular_traveler_fields' keys).
	 * @param array $emergency_fields_with_values Emergency contact fields that have actual values.
	 * @return array Header array for CSV.
	 * @since 6.7.4
	 */
	private static function build_csv_headers( $field_definitions, $payment_fields_with_actual_values, $billing_fields_with_values, $traveler_fields_with_values, $emergency_fields_with_values ) {
		$header = array(
			__( 'Booking ID', 'wp-travel-engine' ),
			__( 'Booking Status', 'wp-travel-engine' ),
			__( 'Trip Name', 'wp-travel-engine' ),
		);

		$header[] = __( 'Total Cost', 'wp-travel-engine' );
		$header[] = __( 'Total Paid', 'wp-travel-engine' );

		$header = array_merge(
			$header,
			array(
				__( 'Payment Gateway', 'wp-travel-engine' ),
				__( 'No. of Travellers', 'wp-travel-engine' ),
				__( 'Booking Date', 'wp-travel-engine' ),
				__( 'Trip Date', 'wp-travel-engine' ),
				__( 'Trip End Date', 'wp-travel-engine' ),
				__( 'Package Name', 'wp-travel-engine' ),
			)
		);

		// Conditionally add accommodation, extra services, pickup points, and travel insurance headers
		if ( $field_definitions['has_accommodation'] ) {
			$header[] = __( 'Accommodation', 'wp-travel-engine' );
		}
		if ( $field_definitions['has_extra_services'] ) {
			$header[] = __( 'Extra Services', 'wp-travel-engine' );
		}
		if ( $field_definitions['has_pickup_points'] ) {
			$header[] = __( 'Pickup Points', 'wp-travel-engine' );
		}
		if ( $field_definitions['has_travel_insurance'] ) {
			$header[] = __( 'Travel Insurance', 'wp-travel-engine' );
		}

		// Add billing headers (only fields with actual values)
		foreach ( $billing_fields_with_values as $field ) {
			$field_label = ucwords( str_replace( '_', ' ', $field ) );
			$header[]    = sprintf( __( 'Billing - %s', 'wp-travel-engine' ), $field_label );
		}

		// Add payment headers
		for ( $i = 1; $i <= $field_definitions['max_payments']; $i++ ) {
			$header[] = sprintf( __( 'Payment %d - ID', 'wp-travel-engine' ), $i );
			$header[] = sprintf( __( 'Payment %d - Status', 'wp-travel-engine' ), $i );
			$header[] = sprintf( __( 'Payment %d - Gateway', 'wp-travel-engine' ), $i );
			$header[] = sprintf( __( 'Payment %d - Date', 'wp-travel-engine' ), $i );
			$header[] = sprintf( __( 'Payment %d - Paid Amount', 'wp-travel-engine' ), $i );
			$header[] = sprintf( __( 'Payment %d - Transaction ID', 'wp-travel-engine' ), $i );
			$header[] = sprintf( __( 'Payment %d - Currency', 'wp-travel-engine' ), $i );

			// Add dynamic payment fields (tax, deposit, booking_fee, etc.)
			foreach ( $payment_fields_with_actual_values as $field_key ) {
				$field_label = ucwords( str_replace( array( '_', 'total_' ), array( ' ', '' ), $field_key ) );
				$header[]    = sprintf( __( 'Payment %1$d - %2$s', 'wp-travel-engine' ), $i, $field_label );
			}
		}

		// Add booking summary totals headers
		if ( $field_definitions['has_deposit'] ) {
			$header[] = __( 'Total Deposit Amount', 'wp-travel-engine' );
		}
		if ( $field_definitions['has_tax'] ) {
			$header[] = __( 'Total Tax', 'wp-travel-engine' );
		}

		// Add discount information headers
		if ( $field_definitions['has_discount'] ) {
			$header[] = __( 'Discount Code', 'wp-travel-engine' );
			$header[] = __( 'Discount Percentage', 'wp-travel-engine' );
			$header[] = __( 'Discount Amount', 'wp-travel-engine' );
		}

		// Helper function for traveler field matching (using verified fields)
		$has_corresponding_lead_field = function ( $regular_field ) use ( $traveler_fields_with_values ) {
			$regular_base = preg_replace( '/^(traveller[-_\s]*)/i', '', strtolower( $regular_field ) );
			$regular_base = preg_replace( '/[-_\s]+/', ' ', trim( $regular_base ) );

			foreach ( $traveler_fields_with_values['lead_traveler_fields'] as $lead_field ) {
				$lead_base = preg_replace( '/^(lead[-_\s]*)?(traveller[-_\s]*)?/i', '', strtolower( $lead_field ) );
				$lead_base = preg_replace( '/[-_\s]+/', ' ', trim( $lead_base ) );

				if ( $regular_base === $lead_base ) {
					return true;
				}
			}
			return false;
		};

		// Add headers for each traveler (only fields with actual values).
		// Use at least 1 traveler slot when Pricing Category is in the fields (display from cart until saved in meta).
		$has_pricing_category    = in_array( 'pricing_category', $traveler_fields_with_values['lead_traveler_fields'], true )
			|| in_array( 'traveller_pricing_category', $traveler_fields_with_values['regular_traveler_fields'], true );
		$effective_max_travelers = $has_pricing_category ? max( 1, (int) $field_definitions['max_travelers'] ) : (int) $field_definitions['max_travelers'];
		$pricing_category_label  = __( 'Pricing Category', 'wp-travel-engine' );
		for ( $i = 1; $i <= $effective_max_travelers; $i++ ) {
			if ( $i === 1 ) {
				foreach ( $traveler_fields_with_values['lead_traveler_fields'] as $field ) {
					$field_label = ( $field === 'pricing_category' ) ? $pricing_category_label : ucwords( str_replace( '_', ' ', $field ) );
					$header[]    = sprintf( __( 'Traveler %1$d - %2$s', 'wp-travel-engine' ), $i, $field_label );
				}
				foreach ( $traveler_fields_with_values['regular_traveler_fields'] as $field ) {
					if ( ! $has_corresponding_lead_field( $field ) ) {
						$field_label = ( $field === 'traveller_pricing_category' ) ? $pricing_category_label : ucwords( str_replace( '_', ' ', $field ) );
						$header[]    = sprintf( __( 'Traveler %1$d - %2$s', 'wp-travel-engine' ), $i, $field_label );
					}
				}
			} else {
				$pricing_category_field = null;
				$other_fields           = array();
				foreach ( $traveler_fields_with_values['regular_traveler_fields'] as $field ) {
					if ( $field === 'traveller_pricing_category' ) {
						$pricing_category_field = $field;
					} else {
						$other_fields[] = $field;
					}
				}
				// Add pricing category first if it exists
				if ( $pricing_category_field !== null ) {
					$header[] = sprintf( __( 'Traveler %1$d - %2$s', 'wp-travel-engine' ), $i, $pricing_category_label );
				}
				foreach ( $other_fields as $field ) {
					$field_label = ucwords( str_replace( '_', ' ', $field ) );
					$header[]    = sprintf( __( 'Traveler %1$d - %2$s', 'wp-travel-engine' ), $i, $field_label );
				}
			}
		}

		// Add headers for each emergency contact (only fields with actual values)
		for ( $i = 1; $i <= $field_definitions['max_emergency_contacts']; $i++ ) {
			foreach ( $emergency_fields_with_values as $field ) {
				$field_label = ucwords( str_replace( '_', ' ', $field ) );
				$header[]    = sprintf( __( 'Emergency Contact %1$d - %2$s', 'wp-travel-engine' ), $i, $field_label );
			}
		}

		// Add additional notes header
		$header[] = __( 'Additional Notes', 'wp-travel-engine' );

		// Add admin notes header
		$header[] = __( 'Admin Notes', 'wp-travel-engine' );

		return $header;
	}

	/**
	 * Get billing row data for a booking.
	 *
	 * @param int   $booking_id Booking ID.
	 * @param array $billing_fields_with_values Billing field names that have actual values.
	 * @param array $countries_list Countries list for code conversion.
	 * @return array Billing field values in order.
	 * @since 6.7.4
	 */
	private static function get_billing_row_data( $booking_id, $billing_fields_with_values, $countries_list ) {
		$billing_row_data = array();
		$billing_details  = get_post_meta( $booking_id, 'wptravelengine_billing_details', true );
		$billing_data     = ! empty( $billing_details ) ? self::safe_unserialize( $billing_details ) : array();

		foreach ( $billing_fields_with_values as $field ) {
			if ( isset( $billing_data[ $field ] ) ) {
				$value = $billing_data[ $field ];

				// Convert country code to country name
				$value = self::convert_country_codes( $value, $field, $countries_list );

				if ( is_array( $value ) && ! empty( $value ) ) {
					// Handle array values
					$clean_values       = array_map(
						function ( $item ) use ( $countries_list, $field ) {
							$item = self::convert_country_codes( $item, $field, $countries_list );
							return is_array( $item ) ? implode( ', ', $item ) : strval( $item );
						},
						$value
					);
					$billing_row_data[] = implode( ', ', $clean_values );
				} else {
					$billing_row_data[] = $value;
				}
			} else {
				$billing_row_data[] = '';
			}
		}

		return $billing_row_data;
	}

	/**
	 * Derive pricing category labels by traveler index from cart line items (same as booking details page display).
	 * Used when pricing category is not yet saved in traveler meta (only after edit).
	 *
	 * @param int $booking_id Booking ID.
	 * @return array Indexed array of category labels (key = traveler index 0-based).
	 * @since 6.7.4
	 */
	private static function get_pricing_categories_from_cart( $booking_id ) {
		$by_index  = array();
		$cart_info = get_post_meta( $booking_id, 'cart_info', true );
		if ( empty( $cart_info ) || ! is_array( $cart_info ) || empty( $cart_info['items'][0]['line_items']['pricing_category'] ) ) {
			return $by_index;
		}
		$items = $cart_info['items'][0]['line_items']['pricing_category'];
		if ( ! is_array( $items ) ) {
			return $by_index;
		}
		$index = 0;
		// Support both: list of items (label, quantity, category_id) and keyed by category_id (quantity[0], price[0]).
		foreach ( $items as $key => $item ) {
			if ( ! is_array( $item ) ) {
				continue;
			}
			$label  = isset( $item['label'] ) ? trim( (string) $item['label'] ) : '';
			$cat_id = isset( $item['category_id'] ) ? $item['category_id'] : ( is_numeric( $key ) ? (int) $key : 0 );
			if ( $label === '' && $cat_id && is_numeric( $cat_id ) ) {
				$term = get_term( (int) $cat_id, 'trip-packages-categories' );
				if ( $term && ! is_wp_error( $term ) && isset( $term->name ) ) {
					$label = $term->name;
				}
			}
			if ( $label === '' && $cat_id ) {
				$label = 'Category ' . $cat_id;
			}
			$qty = isset( $item['quantity'] ) ? max( 0, (int) $item['quantity'] ) : null;
			if ( $qty === null && isset( $item['quantity'][0] ) ) {
				$qty = max( 0, (int) $item['quantity'][0] );
			}
			if ( $qty === null ) {
				$qty = 1;
			}
			for ( $n = 0; $n < $qty; $n++ ) {
				$by_index[ $index ] = $label;
				++$index;
			}
		}
		return $by_index;
	}

	/**
	 * Resolve pricing category field value: if value is a term ID, return the term name.
	 *
	 * @param mixed  $value Raw value (term ID or name).
	 * @param string $field Field key (pricing_category or traveller_pricing_category).
	 * @return mixed Resolved value (term name when applicable).
	 * @since 6.7.4
	 */
	private static function resolve_pricing_category_value( $value, $field ) {
		if ( ( $field !== 'pricing_category' && $field !== 'traveller_pricing_category' ) || $value === '' || $value === null ) {
			return $value;
		}
		if ( is_array( $value ) ) {
			return $value;
		}
		$value = is_string( $value ) ? trim( $value ) : $value;
		if ( is_numeric( $value ) && (int) $value > 0 ) {
			$term = get_term( (int) $value, 'trip-packages-categories' );
			if ( $term && ! is_wp_error( $term ) && isset( $term->name ) ) {
				return $term->name;
			}
		}
		return $value;
	}

	/**
	 * Get traveler row data for a booking.
	 *
	 * @param int   $booking_id Booking ID.
	 * @param array $traveler_fields_with_values Traveler fields that have actual values (with 'lead_traveler_fields' and 'regular_traveler_fields' keys).
	 * @param int   $max_travelers Maximum number of travelers.
	 * @param array $countries_list Countries list for code conversion.
	 * @return array Traveler field values for all travelers.
	 * @since 6.7.4
	 */
	private static function get_traveler_row_data( $booking_id, $traveler_fields_with_values, $max_travelers, $countries_list ) {
		$traveler_row_data = array();
		$traveler_details  = get_post_meta( $booking_id, 'wptravelengine_travelers_details', true );
		$travelers         = ! empty( $traveler_details ) ? self::safe_unserialize( $traveler_details ) : array();

		// Derive pricing category from cart line items when not in meta (same as booking details page display).
		$pricing_from_cart = self::get_pricing_categories_from_cart( $booking_id );

		$lead_traveler_fields    = $traveler_fields_with_values['lead_traveler_fields'];
		$regular_traveler_fields = $traveler_fields_with_values['regular_traveler_fields'];

		$has_corresponding_lead_field = function ( $regular_field ) use ( $lead_traveler_fields ) {
			$regular_base = preg_replace( '/^(traveller[-_\s]*)/i', '', strtolower( $regular_field ) );
			$regular_base = preg_replace( '/[-_\s]+/', ' ', trim( $regular_base ) );

			foreach ( $lead_traveler_fields as $lead_field ) {
				$lead_base = preg_replace( '/^(lead[-_\s]*)?(traveller[-_\s]*)?/i', '', strtolower( $lead_field ) );
				$lead_base = preg_replace( '/[-_\s]+/', ' ', trim( $lead_base ) );

				if ( $regular_base === $lead_base ) {
					return true;
				}
			}
			return false;
		};

		for ( $i = 0; $i < $max_travelers; $i++ ) {
			if ( isset( $travelers[ $i ] ) && is_array( $travelers[ $i ] ) ) {
				$traveler = $travelers[ $i ];

				if ( $i === 0 ) {
					// Lead traveler fields
					foreach ( $lead_traveler_fields as $field ) {
						$value = isset( $traveler[ $field ] ) ? $traveler[ $field ] : '';
						// For pricing_category: prioritize cart_info (source of truth) over meta, since cart reflects actual booking
						if ( $field === 'pricing_category' && isset( $pricing_from_cart[ $i ] ) && $pricing_from_cart[ $i ] !== '' ) {
							$value = $pricing_from_cart[ $i ];
						} elseif ( $field === 'pricing_category' ) {
							// Fallback: use meta if cart doesn't have it
							$meta_value = trim( (string) $value );
							if ( $meta_value === '' || $meta_value === '0' || $meta_value === 'null' || $meta_value === 'NULL' ) {
								$value = '';
							}
						}
						$value               = self::convert_country_codes( $value, $field, $countries_list );
						$value               = self::resolve_pricing_category_value( $value, $field );
						$traveler_row_data[] = is_array( $value ) ? implode( ', ', $value ) : $value;
					}
					// Regular traveler fields that don't have corresponding lead fields
					foreach ( $regular_traveler_fields as $field ) {
						if ( ! $has_corresponding_lead_field( $field ) ) {
							$value = isset( $traveler[ $field ] ) ? $traveler[ $field ] : '';
							// For traveller_pricing_category: prioritize cart_info (source of truth) over meta
							if ( $field === 'traveller_pricing_category' && isset( $pricing_from_cart[ $i ] ) && $pricing_from_cart[ $i ] !== '' ) {
								$value = $pricing_from_cart[ $i ];
							} elseif ( $field === 'traveller_pricing_category' ) {
								// Fallback: use meta if cart doesn't have it
								$meta_value = trim( (string) $value );
								if ( $meta_value === '' || $meta_value === '0' || $meta_value === 'null' || $meta_value === 'NULL' ) {
									$value = '';
								}
							}
							$value               = self::convert_country_codes( $value, $field, $countries_list );
							$value               = self::resolve_pricing_category_value( $value, $field );
							$traveler_row_data[] = is_array( $value ) ? implode( ', ', $value ) : $value;
						}
					}
				} else {
					// Regular traveler fields - put pricing category first (like traveller 1)
					$pricing_category_field = null;
					$other_fields           = array();
					foreach ( $regular_traveler_fields as $field ) {
						if ( $field === 'traveller_pricing_category' ) {
							$pricing_category_field = $field;
						} else {
							$other_fields[] = $field;
						}
					}
					// Process pricing category first if it exists
					if ( $pricing_category_field !== null ) {
						$value = isset( $traveler[ $pricing_category_field ] ) ? $traveler[ $pricing_category_field ] : '';
						// For traveller_pricing_category: prioritize cart_info (source of truth) over meta
						if ( isset( $pricing_from_cart[ $i ] ) && $pricing_from_cart[ $i ] !== '' ) {
							$value = $pricing_from_cart[ $i ];
						} else {
							// Fallback: use meta if cart doesn't have it
							$meta_value = trim( (string) $value );
							if ( $meta_value === '' || $meta_value === '0' || $meta_value === 'null' || $meta_value === 'NULL' ) {
								$value = '';
							}
						}
						$value               = self::convert_country_codes( $value, $pricing_category_field, $countries_list );
						$value               = self::resolve_pricing_category_value( $value, $pricing_category_field );
						$traveler_row_data[] = is_array( $value ) ? implode( ', ', $value ) : $value;
					}
					// Then process other fields
					foreach ( $other_fields as $field ) {
						$value               = isset( $traveler[ $field ] ) ? $traveler[ $field ] : '';
						$value               = self::convert_country_codes( $value, $field, $countries_list );
						$value               = self::resolve_pricing_category_value( $value, $field );
						$traveler_row_data[] = is_array( $value ) ? implode( ', ', $value ) : $value;
					}
				}
			} else {
				// No traveler meta at this index: output empty values, but use pricing_from_cart when available (same as booking page display)
				if ( $i === 0 ) {
					foreach ( $lead_traveler_fields as $field ) {
						$value               = ( $field === 'pricing_category' && isset( $pricing_from_cart[ $i ] ) ) ? $pricing_from_cart[ $i ] : '';
						$traveler_row_data[] = $value;
					}
					foreach ( $regular_traveler_fields as $field ) {
						if ( ! $has_corresponding_lead_field( $field ) ) {
							$value               = ( $field === 'traveller_pricing_category' && isset( $pricing_from_cart[ $i ] ) ) ? $pricing_from_cart[ $i ] : '';
							$traveler_row_data[] = $value;
						}
					}
				} else {
					// For traveller 2+, put pricing category first (like traveller 1)
					$pricing_category_field = null;
					$other_fields           = array();
					foreach ( $regular_traveler_fields as $field ) {
						if ( $field === 'traveller_pricing_category' ) {
							$pricing_category_field = $field;
						} else {
							$other_fields[] = $field;
						}
					}
					// Add pricing category first if it exists
					if ( $pricing_category_field !== null ) {
						$value               = ( isset( $pricing_from_cart[ $i ] ) ) ? $pricing_from_cart[ $i ] : '';
						$traveler_row_data[] = $value;
					}
					// Then add other fields
					foreach ( $other_fields as $field ) {
						$traveler_row_data[] = '';
					}
				}
			}
		}

		return $traveler_row_data;
	}

	/**
	 * Get emergency contact row data for a booking.
	 *
	 * @param int   $booking_id Booking ID.
	 * @param array $emergency_fields_with_values Emergency contact field names that have actual values.
	 * @param int   $max_emergency_contacts Maximum number of emergency contacts.
	 * @param array $countries_list Countries list for code conversion.
	 * @return array Emergency contact field values for all contacts.
	 * @since 6.7.4
	 */
	private static function get_emergency_contact_row_data( $booking_id, $emergency_fields_with_values, $max_emergency_contacts, $countries_list ) {
		$emergency_row_data = array();
		$emergency_details  = get_post_meta( $booking_id, 'wptravelengine_emergency_details', true );
		$emergency_data     = ! empty( $emergency_details ) ? self::safe_unserialize( $emergency_details ) : array();

		// Handle single contact format
		if ( is_array( $emergency_data ) && ( isset( $emergency_data['fname'] ) || isset( $emergency_data['first_name'] ) ) ) {
			$emergency_data = array( $emergency_data );
		}

		for ( $i = 0; $i < $max_emergency_contacts; $i++ ) {
			if ( isset( $emergency_data[ $i ] ) && is_array( $emergency_data[ $i ] ) ) {
				$contact = $emergency_data[ $i ];
				foreach ( $emergency_fields_with_values as $field ) {
					$value                = isset( $contact[ $field ] ) ? $contact[ $field ] : '';
					$value                = self::convert_country_codes( $value, $field, $countries_list );
					$emergency_row_data[] = is_array( $value ) ? implode( ', ', $value ) : $value;
				}
			} else {
				// Empty values for missing contacts
				foreach ( $emergency_fields_with_values as $field ) {
					$emergency_row_data[] = '';
				}
			}
		}

		return $emergency_row_data;
	}

	/**
	 * Get booking totals (Total Cost and Total Paid).
	 *
	 * @param BookingModel $booking Booking model instance.
	 * @param bool         $is_cart_v4 Whether booking uses cart version 4.
	 * @return array Array with 'total_cost' and 'total_paid' keys.
	 * @since 6.7.4
	 */
	private static function get_booking_totals( $booking, $is_cart_v4 ) {
		$total_cost = 0;
		$total_paid = 0;

		if ( ! $is_cart_v4 ) {
			try {
				$cart_info  = new CartInfoParser( $booking->get_cart_info() ?? array() );
				$total_cost = $cart_info->get_totals( 'total' ) ?? 0;
				$total_paid = $booking->get_total_paid_amount() ?? 0;
			} catch ( \Exception $e ) {
				// Log the error for debugging
				error_log( 'Error in get_booking_totals (booking ID ' . $booking->get_id() . '): ' . $e->getMessage() );
				// Fallback for legacy bookings
				$cart_info_meta = get_post_meta( $booking->get_id(), 'cart_info', true );
				if ( ! empty( $cart_info_meta ) && is_array( $cart_info_meta ) && isset( $cart_info_meta['totals']['total'] ) ) {
					$total_cost = $cart_info_meta['totals']['total'];
				}
				$total_paid_meta = get_post_meta( $booking->get_id(), 'total_paid_amount', true );
				if ( $total_paid_meta !== '' && $total_paid_meta !== null && is_numeric( $total_paid_meta ) ) {
					$total_paid = (float) $total_paid_meta;
				} else {
					$paid_amount_meta = get_post_meta( $booking->get_id(), 'paid_amount', true );
					if ( ! empty( $paid_amount_meta ) && is_numeric( $paid_amount_meta ) ) {
						$total_paid = (float) $paid_amount_meta;
					}
				}
			}
		}

		return array(
			'total_cost' => $total_cost,
			'total_paid' => $total_paid,
		);
	}

	/**
	 * Get payment row data for a specific payment index.
	 *
	 * @param array $payment Payment data array.
	 * @param array $payment_fields_with_actual_values Payment fields that have actual values.
	 * @return array Payment row data.
	 * @since 6.7.4
	 */
	private static function get_payment_row_data( $payment, $payment_fields_with_actual_values ) {
		$payment_row = array();

		// Add basic fields first
		$payment_row[] = isset( $payment['id'] ) ? ( is_object( $payment['id'] ) ? wp_json_encode( $payment['id'] ) : (string) $payment['id'] ) : '';
		$payment_row[] = isset( $payment['status'] ) ? ( is_object( $payment['status'] ) ? wp_json_encode( $payment['status'] ) : (string) $payment['status'] ) : '';
		$payment_row[] = isset( $payment['gateway'] ) ? ( is_object( $payment['gateway'] ) ? wp_json_encode( $payment['gateway'] ) : (string) $payment['gateway'] ) : '';
		$payment_row[] = isset( $payment['date'] ) ? ( is_object( $payment['date'] ) ? wp_json_encode( $payment['date'] ) : (string) $payment['date'] ) : '';
		$payment_row[] = isset( $payment['paid_amount'] ) ? ( is_object( $payment['paid_amount'] ) ? wp_json_encode( $payment['paid_amount'] ) : ( is_numeric( $payment['paid_amount'] ) ? $payment['paid_amount'] : (string) $payment['paid_amount'] ) ) : '';
		$payment_row[] = isset( $payment['transaction_id'] ) ? ( is_object( $payment['transaction_id'] ) ? wp_json_encode( $payment['transaction_id'] ) : (string) $payment['transaction_id'] ) : '';
		$payment_row[] = isset( $payment['currency'] ) ? ( is_object( $payment['currency'] ) ? wp_json_encode( $payment['currency'] ) : (string) $payment['currency'] ) : '';

		// Add dynamic payment fields in the same order as headers
		foreach ( $payment_fields_with_actual_values as $field_key ) {
			$value = '';
			if ( isset( $payment[ $field_key ] ) ) {
				$value = $payment[ $field_key ];
			} elseif ( strpos( $field_key, 'total_' ) === 0 ) {
				$total_prefix_length = 6; // Length of 'total_' prefix
				$base_key            = substr( $field_key, $total_prefix_length );
				if ( isset( $payment[ $base_key ] ) ) {
					$value = $payment[ $base_key ];
				}
			}
			// Convert objects to strings
			if ( is_object( $value ) ) {
				$value = wp_json_encode( $value );
			} elseif ( is_array( $value ) ) {
				$value = wp_json_encode( $value );
			}
			$payment_row[] = is_numeric( $value ) ? $value : (string) $value;
		}

		return $payment_row;
	}

	/**
	 * Get booking summary data (totals and discount).
	 *
	 * @param int   $booking_id Booking ID.
	 * @param bool  $has_discount Whether booking has discount.
	 * @param array $payment_data_array Payment data array.
	 * @param bool  $has_deposit Whether booking has deposit.
	 * @param bool  $has_tax Whether booking has tax.
	 * @return array Summary data with totals and discount info.
	 * @since 6.7.4
	 */
	private static function get_booking_summary_data( $booking_id, $has_discount, $payment_data_array, $has_deposit, $has_tax ) {
		$summary_data = array();

		// Calculate totals from booking cart_info instead of payment_data_array
		$total_paid_sum = 0;
		$total_deposit  = 0;
		$total_tax      = 0;

		try {
			$booking = BookingModel::make( $booking_id );
			if ( $booking ) {
				$cart_info = $booking->get_cart_info();

				// Total paid: sum from Payment objects (paid_amount from payment_data_array)
				foreach ( $payment_data_array as $payment ) {
					if ( isset( $payment['paid_amount'] ) && is_numeric( $payment['paid_amount'] ) ) {
						$total_paid_sum += (float) $payment['paid_amount'];
					}
				}

				// Total tax: from booking cart_info
				$total_tax = (float) ( $booking->get_tax_amount() ?? 0 );

				// Total deposit: from cart_info partial_total
				if ( is_array( $cart_info ) && isset( $cart_info['totals']['partial_total'] ) ) {
					$total_deposit = (float) $cart_info['totals']['partial_total'];
				}
			}
		} catch ( \Exception $e ) {
			error_log( 'Error in get_booking_summary_data (totals calculation, booking ID ' . $booking_id . '): ' . $e->getMessage() );
			// Fallback: calculate from payment_data_array if booking model fails
			foreach ( $payment_data_array as $payment ) {
				if ( isset( $payment['paid_amount'] ) && is_numeric( $payment['paid_amount'] ) ) {
					$total_paid_sum += (float) $payment['paid_amount'];
				}
			}
		}

		// Add booking summary totals
		if ( $has_deposit ) {
			$summary_data['total_deposit'] = $total_deposit;
		}
		if ( $has_tax ) {
			$summary_data['total_tax'] = $total_tax;
		}
		$summary_data['total_paid'] = $total_paid_sum;

		// Extract discount information
		$discount_code       = '';
		$discount_percentage = '';
		$discount_amount     = '';

		if ( $has_discount ) {
			try {
				$booking = BookingModel::make( $booking_id );
				if ( $booking ) {
					$cart_info        = new CartInfoParser( $booking->get_cart_info() ?? array() );
					$deductible_items = $cart_info->get_deductible_items();
					if ( ! empty( $deductible_items ) ) {
						foreach ( $deductible_items as $item ) {
							if ( isset( $item['_class_name'] ) && $item['_class_name'] === 'WPTravelEngine\Core\Cart\Adjustments\CouponAdjustment' ) {
								// Extract discount code
								$discount_code = preg_replace( '/\s*\([^)]*\)/', '', $item['label'] ?? '' );
								$discount_code = is_object( $discount_code ) ? '' : (string) $discount_code;

								$discount_percentage = isset( $item['percentage'] ) ? $item['percentage'] : '';
								if ( is_object( $discount_percentage ) ) {
									$discount_percentage = '';
								} elseif ( is_numeric( $discount_percentage ) ) {
									$discount_percentage = (string) $discount_percentage;
								} else {
									$discount_percentage = (string) $discount_percentage;
								}

								$discount_amount = $cart_info->get_totals( 'total_' . $item['name'] ) ?? 0;
								if ( is_object( $discount_amount ) ) {
									$discount_amount = '';
								} elseif ( is_numeric( $discount_amount ) ) {
									$discount_amount = abs( (float) $discount_amount );
								} else {
									$discount_amount = '';
								}
								break;
							}
						}
					}
				}
			} catch ( \Exception $e ) {
				// Log the error for debugging
				error_log( 'Error in get_booking_summary_data (discount extraction, booking ID ' . $booking_id . '): ' . $e->getMessage() );
			}
		}

		if ( $has_discount ) {
			$summary_data['discount_code']       = $discount_code;
			$summary_data['discount_percentage'] = $discount_percentage;
			$summary_data['discount_amount']     = $discount_amount;
		}

		return $summary_data;
	}

	/**
	 * Importing data to csv format..
	 *
	 * @param array $queries_data Queries Data.
	 *
	 * @since 6.7.4
	 */
	public function data_export( $queries_data ) {
		if ( ! is_array( $queries_data ) ) {
			return;
		}

		header( 'Content-Type: text/csv' );
		header( 'Content-Disposition: attachment; filename="wptravelengine-booking-export.csv"' );
		header( 'Pragma: no-cache' );
		header( 'Expires: 0' );

		$file = fopen( 'php://output', 'w' );
		if ( ! $file ) {
			return;
		}

		static $countries_list = null;
		if ( $countries_list === null ) {
			$countries_list = Countries::list();
		}
		$field_definitions                 = self::collect_field_definitions( $queries_data );
		$payment_fields_with_actual_values = self::verify_payment_fields( $queries_data );
		$billing_fields_with_values        = self::verify_billing_fields( $queries_data );
		$traveler_fields_with_values       = self::verify_traveler_fields( $queries_data );
		$emergency_fields_with_values      = self::verify_emergency_contact_fields( $queries_data );

		$header = self::build_csv_headers( $field_definitions, $payment_fields_with_actual_values, $billing_fields_with_values, $traveler_fields_with_values, $emergency_fields_with_values );
		fputcsv( $file, $header );

		// Pre-load post meta for all bookings to avoid N+1 queries.
		$booking_ids = array_unique(
			array_map(
				function ( $row ) {
					return (int) $row->BookingID;
				},
				$queries_data
			)
		);
		if ( ! empty( $booking_ids ) ) {
			update_meta_cache( 'post', $booking_ids );
		}

		$export_errors = array();
		foreach ( $queries_data as $data ) {
			try {
				$row_data = self::process_booking_row( $data, $field_definitions, $payment_fields_with_actual_values, $billing_fields_with_values, $traveler_fields_with_values, $emergency_fields_with_values, $countries_list );
				$row_data = array_map( array( __CLASS__, 'sanitize_csv_value' ), $row_data );
				fputcsv( $file, $row_data );
			} catch ( \Exception $e ) {
				$export_errors[] = array(
					'booking_id' => $data->BookingID,
					'message'    => $e->getMessage(),
				);
				error_log( 'WP Travel Engine export: booking ID ' . $data->BookingID . ' failed: ' . $e->getMessage() );
			}
		}
		if ( ! empty( $export_errors ) ) {
			error_log( 'WP Travel Engine export: ' . count( $export_errors ) . ' booking(s) could not be exported. IDs: ' . implode( ', ', wp_list_pluck( $export_errors, 'booking_id' ) ) );
		}

		fclose( $file );
	}

	/**
	 * Process a single booking row and return CSV row data.
	 *
	 * @param object $data Booking data object.
	 * @param array  $field_definitions Field definitions.
	 * @param array  $payment_fields_with_actual_values Payment fields with values.
	 * @param array  $billing_fields_with_values Billing fields with values.
	 * @param array  $traveler_fields_with_values Traveler fields with values.
	 * @param array  $emergency_fields_with_values Emergency contact fields with values.
	 * @param array  $countries_list Countries list.
	 * @return array Row data array.
	 * @since 6.7.4
	 */
	private static function process_booking_row( $data, $field_definitions, $payment_fields_with_actual_values, $billing_fields_with_values, $traveler_fields_with_values, $emergency_fields_with_values, $countries_list ) {
		$tripname         = '';
		$traveler         = '';
		$tripstartingdate = '';
		$tripendingdate   = '';
		$trip_id          = 0;
		$paymentgateway   = '';
		try {
			$booking_date = new DateTime( $data->BookingDate );
		} catch ( \Exception $e ) {
			error_log( 'Error in process_booking_row (DateTime): ' . $e->getMessage() );
			$booking_date = new DateTime();
		}
		$placeorder_package_name = '';
		if ( isset( $data->placeorder ) ) {
			$unserializedOrderData = self::safe_unserialize( $data->placeorder );

			if ( is_array( $unserializedOrderData ) && ! empty( $unserializedOrderData ) ) {
				$first_key               = array_key_first( $unserializedOrderData );
				$first_trip_data         = $unserializedOrderData[ $first_key ];
				$unserializedOrderData   = (object) $first_trip_data;
				$trip_id                 = is_numeric( $first_key ) ? (int) $first_key : 0;
				$tripname                = isset( $unserializedOrderData->title ) ? $unserializedOrderData->title : '';
				$tripstartingdate        = isset( $unserializedOrderData->datetime ) ? $unserializedOrderData->datetime : '';
				$placeorder_package_name = isset( $unserializedOrderData->trip_package ) ? $unserializedOrderData->trip_package : '';
			}
		}
		$cart_info = get_post_meta( $data->BookingID, 'cart_info', true );

		if ( ! $trip_id && ! empty( $cart_info ) && is_array( $cart_info ) && isset( $cart_info['items'][0]['trip_id'] ) && is_numeric( $cart_info['items'][0]['trip_id'] ) ) {
			$trip_id = (int) $cart_info['items'][0]['trip_id'];
		}

		$trip_end_has_time = ( strpos( $tripstartingdate, 'T' ) !== false || preg_match( '/\s\d{1,2}:\d{2}/', $tripstartingdate ) );
		$trip_end_format   = $trip_end_has_time ? 'Y-m-d H:i' : 'Y-m-d';

		if ( ! empty( $cart_info ) && is_array( $cart_info ) && isset( $cart_info['items'][0]['end_date'] ) && $cart_info['items'][0]['end_date'] !== '' ) {
			$end_raw        = $cart_info['items'][0]['end_date'];
			$end_ts         = strtotime( $end_raw );
			$end_has_time   = ( strpos( $end_raw, 'T' ) !== false || preg_match( '/\s\d{1,2}:\d{2}/', $end_raw ) );
			$tripendingdate = $end_ts ? wp_date( $end_has_time ? 'Y-m-d H:i' : 'Y-m-d', $end_ts ) : '';
		} elseif ( $trip_id && $tripstartingdate !== '' && function_exists( 'wptravelengine_get_trip' ) && function_exists( 'wptravelengine_format_trip_end_datetime' ) ) {
			$trip = wptravelengine_get_trip( $trip_id );
			if ( $trip ) {
				$tripendingdate = wptravelengine_format_trip_end_datetime( $tripstartingdate, $trip, $trip_end_format );
			}
		}

		// Package name: check multiple sources in order of preference (cart_info, placeorder, order_trips, booking meta)
		$package_name = '';
		if ( ! empty( $cart_info ) && is_array( $cart_info ) && isset( $cart_info['items'][0]['trip_package'] ) && $cart_info['items'][0]['trip_package'] !== '' ) {
			$package_name = is_string( $cart_info['items'][0]['trip_package'] ) ? $cart_info['items'][0]['trip_package'] : '';
		}
		if ( $package_name === '' && $placeorder_package_name !== '' ) {
			$package_name = $placeorder_package_name;
		}
		if ( $package_name === '' ) {
			$order_trips = get_post_meta( $data->BookingID, 'order_trips', true );
			if ( ! empty( $order_trips ) && is_array( $order_trips ) ) {
				$first_order_trip = reset( $order_trips );
				if ( is_array( $first_order_trip ) && isset( $first_order_trip['package_name'] ) && $first_order_trip['package_name'] !== '' ) {
					$package_name = $first_order_trip['package_name'];
				}
			}
		}
		if ( $package_name === '' ) {
			$package_meta = get_post_meta( $data->BookingID, 'package_name', true );
			if ( $package_meta !== '' && $package_meta !== null ) {
				if ( is_numeric( $package_meta ) && $trip_id && function_exists( 'wptravelengine_get_trip' ) && class_exists( 'WPTravelEngine\Core\Models\Post\TripPackages' ) ) {
					$trip_for_pkg = wptravelengine_get_trip( $trip_id );
					if ( $trip_for_pkg ) {
						$trip_packages = new \WPTravelEngine\Core\Models\Post\TripPackages( $trip_for_pkg );
						foreach ( $trip_packages as $pkg ) {
							if ( (string) $package_meta === (string) $pkg->ID ) {
								$package_name = isset( $pkg->post->post_title ) ? $pkg->post->post_title : '';
								break;
							}
						}
					}
				}
				if ( $package_name === '' ) {
					$package_name = (string) $package_meta;
				}
			}
		}

		$traveler = '';
		if ( ! empty( $cart_info ) && is_array( $cart_info ) && isset( $cart_info['items'][0] ) ) {
			$cart_item = $cart_info['items'][0];
			if ( isset( $cart_item['travelers_count'] ) && is_numeric( $cart_item['travelers_count'] ) && $cart_item['travelers_count'] > 0 ) {
				$traveler = (int) $cart_item['travelers_count'];
			} elseif ( isset( $cart_item['travelers'] ) && is_array( $cart_item['travelers'] ) ) {
				$traveler = array_sum( $cart_item['travelers'] );
			} elseif ( isset( $cart_item['pax'] ) && is_array( $cart_item['pax'] ) ) {
				$traveler = array_sum( $cart_item['pax'] );
			}
		}
		if ( isset( $data->PaymentGateway ) ) {
			$paymentgateway = $data->PaymentGateway != 'N/A' ? $data->PaymentGateway : '';
		}

		if ( empty( $data->PaymentGateway ) && ! empty( $data->payments ) ) {
			$payment_ids = self::safe_unserialize( $data->payments );
			if ( is_array( $payment_ids ) && ! empty( $payment_ids ) ) {
				$latest_payment_id = end( $payment_ids );
				$payment_gateway   = get_post_meta( $latest_payment_id, 'payment_gateway', true );
				$paymentgateway    = $payment_gateway ?: '';
			} else {
				$paymentgateway = '';
			}
		}

		if ( isset( $data->wc_id ) && $data->wc_id != '(NULL)' ) {
			$paymentgateway = 'woocommerce';
		}

		$accommodation_data    = '';
		$extra_services_data   = '';
		$pickup_points_data    = '';
		$travel_insurance_data = '';
		if ( ! empty( $cart_info ) && is_array( $cart_info ) && isset( $cart_info['items'][0]['line_items'] ) ) {
			$line_items = $cart_info['items'][0]['line_items'];
			if ( isset( $line_items['accommodation'] ) && is_array( $line_items['accommodation'] ) ) {
				$accommodation_labels = array();
				foreach ( $line_items['accommodation'] as $item ) {
					if ( isset( $item['label'] ) ) {
						$label    = html_entity_decode( $item['label'], ENT_QUOTES | ENT_HTML5, 'UTF-8' );
						$quantity = isset( $item['quantity'] ) ? (float) $item['quantity'] : 1;
						$price    = isset( $item['price'] ) ? (float) $item['price'] : ( isset( $item['total'] ) ? (float) $item['total'] : 0 );
						$total    = isset( $item['total'] ) ? (float) $item['total'] : ( $quantity * $price );

						if ( $quantity > 1 ) {
							$label .= ' (x' . $quantity . ')';
						}
						if ( $total > 0 ) {
							$label .= ' - ' . number_format( $total, 2 );
						}
						$accommodation_labels[] = $label;
					}
				}
				$accommodation_data = implode( ', ', $accommodation_labels );
			}
			$extra_services_items = array();
			if ( isset( $line_items['extra_services'] ) && is_array( $line_items['extra_services'] ) ) {
				$extra_services_items = $line_items['extra_services'];
			} elseif ( isset( $line_items['extra_service'] ) && is_array( $line_items['extra_service'] ) ) {
				$extra_services_items = $line_items['extra_service'];
			}

			if ( ! empty( $extra_services_items ) ) {
				// Trip ID from cart_info (booking model is instantiated later in this method).
				$extra_services_trip_id = ( ! empty( $cart_info ) && is_array( $cart_info ) && isset( $cart_info['items'][0]['trip_id'] ) )
					? (int) $cart_info['items'][0]['trip_id']
					: 0;
				$trip_extra_services    = array();
				if ( $extra_services_trip_id ) {
					$trip_settings       = get_post_meta( $extra_services_trip_id, 'wp_travel_engine_setting', true );
					$trip_extra_services = $trip_settings['trip_extra_services'] ?? array();
				}
				$get_extra_service_type = function ( $line_item_label, $services ) {
					foreach ( (array) $services as $service ) {
						if ( isset( $service['options'] ) && is_array( $service['options'] ) ) {
							foreach ( $service['options'] as $option ) {
								if ( $option === $line_item_label ) {
									return $service['type'] ?? 'Default';
								}
							}
						}
					}
					return 'Default';
				};
				$extra_service_labels   = array();
				foreach ( $extra_services_items as $item ) {
					if ( isset( $item['label'] ) ) {
						$label           = html_entity_decode( $item['label'], ENT_QUOTES | ENT_HTML5, 'UTF-8' );
						$quantity        = isset( $item['quantity'] ) ? (float) $item['quantity'] : 1;
						$price           = isset( $item['price'] ) ? (float) $item['price'] : ( isset( $item['total'] ) ? (float) $item['total'] : 0 );
						$total           = isset( $item['total'] ) ? (float) $item['total'] : ( $quantity * $price );
						$service_type    = isset( $item['type'] ) ? $item['type'] : $get_extra_service_type( $item['label'], $trip_extra_services );
						$formatted_label = $label . ' [' . $service_type . ']';
						if ( $quantity > 1 ) {
							$formatted_label .= ' (x' . $quantity . ')';
						}
						if ( $total > 0 ) {
							$formatted_label .= ' - ' . number_format( $total, 2 );
						}
						$extra_service_labels[] = $formatted_label;
					}
				}
				$extra_services_data = implode( ', ', $extra_service_labels );
			}
			if ( isset( $line_items['pickup_point'] ) && is_array( $line_items['pickup_point'] ) ) {
				$pickup_point_labels = array();
				foreach ( $line_items['pickup_point'] as $item ) {
					if ( isset( $item['label'] ) ) {
						$label    = html_entity_decode( $item['label'], ENT_QUOTES | ENT_HTML5, 'UTF-8' );
						$quantity = isset( $item['quantity'] ) ? (float) $item['quantity'] : 1;
						$price    = isset( $item['price'] ) ? (float) $item['price'] : ( isset( $item['total'] ) ? (float) $item['total'] : 0 );
						$total    = isset( $item['total'] ) ? (float) $item['total'] : ( $quantity * $price );

						if ( $quantity > 1 ) {
							$label .= ' (x' . $quantity . ')';
						}
						if ( $total > 0 ) {
							$label .= ' - ' . number_format( $total, 2 );
						}
						$pickup_point_labels[] = $label;
					}
				}
				$pickup_points_data = implode( ', ', $pickup_point_labels );
			}
			if ( isset( $line_items['travel_insurance'] ) && is_array( $line_items['travel_insurance'] ) && ! empty( $line_items['travel_insurance'] ) ) {
				$travel_insurance_labels = array();
				foreach ( $line_items['travel_insurance'] as $item ) {
					if ( isset( $item['label'] ) ) {
						$label    = html_entity_decode( $item['label'], ENT_QUOTES | ENT_HTML5, 'UTF-8' );
						$quantity = isset( $item['quantity'] ) ? (float) $item['quantity'] : 1;
						$price    = isset( $item['price'] ) ? (float) $item['price'] : ( isset( $item['total'] ) ? (float) $item['total'] : 0 );
						$total    = isset( $item['total'] ) ? (float) $item['total'] : ( $quantity * $price );

						if ( $quantity > 1 ) {
							$label .= ' (x' . $quantity . ')';
						}
						if ( $total > 0 ) {
							$label .= ' - ' . number_format( $total, 2 );
						}
						$travel_insurance_labels[] = $label;
					}
				}
				$travel_insurance_data = implode( ', ', $travel_insurance_labels );
			}
		}
		if ( empty( $travel_insurance_data ) ) {
			$travel_insurance_meta = get_post_meta( $data->BookingID, 'wptravelengine_travel_insurance', true );
			if ( empty( $travel_insurance_meta ) || ! is_array( $travel_insurance_meta ) ) {
				$travel_insurance_meta = get_post_meta( $data->BookingID, 'travel_insurance', true );
			}
			if ( ! empty( $travel_insurance_meta ) && is_array( $travel_insurance_meta ) ) {
				if ( isset( $travel_insurance_meta['travel_insurance_plans'] ) && ! empty( $travel_insurance_meta['travel_insurance_plans'] ) ) {
					$travel_insurance_data = html_entity_decode( $travel_insurance_meta['travel_insurance_plans'], ENT_QUOTES | ENT_HTML5, 'UTF-8' );
				} elseif ( isset( $travel_insurance_meta['follow_up_question'] ) ) {
					if ( strtolower( $travel_insurance_meta['follow_up_question'] ) === 'no' ) {
						$travel_insurance_data = __( 'Declined', 'wp-travel-engine' );
						if ( isset( $travel_insurance_meta['follow_up_answer'] ) && ! empty( $travel_insurance_meta['follow_up_answer'] ) ) {
							$travel_insurance_data .= ' (' . html_entity_decode( $travel_insurance_meta['follow_up_answer'], ENT_QUOTES | ENT_HTML5, 'UTF-8' ) . ')';
						}
					} elseif ( strtolower( $travel_insurance_meta['follow_up_question'] ) === 'yes' ) {
						if ( isset( $travel_insurance_meta['travel_insurance_affiliate_link'] ) && ! empty( $travel_insurance_meta['travel_insurance_affiliate_link'] ) ) {
							$affiliate_label       = html_entity_decode( $travel_insurance_meta['travel_insurance_affiliate_label'] ?? __( 'External Insurance', 'wp-travel-engine' ), ENT_QUOTES | ENT_HTML5, 'UTF-8' );
							$affiliate_link        = $travel_insurance_meta['travel_insurance_affiliate_link'];
							$travel_insurance_data = $affiliate_label . ' (' . $affiliate_link . ')';
						} else {
							$travel_insurance_data = __( 'Yes', 'wp-travel-engine' );
						}
					}
				} elseif ( isset( $travel_insurance_meta['travel_insurance_affiliate_link'] ) && ! empty( $travel_insurance_meta['travel_insurance_affiliate_link'] ) ) {
					$affiliate_label       = html_entity_decode( $travel_insurance_meta['travel_insurance_affiliate_label'] ?? __( 'External Insurance', 'wp-travel-engine' ), ENT_QUOTES | ENT_HTML5, 'UTF-8' );
					$affiliate_link        = $travel_insurance_meta['travel_insurance_affiliate_link'];
					$travel_insurance_data = $affiliate_label . ' (' . $affiliate_link . ')';
				}
			}
		}

		// Get booking model once and reuse it
		$booking    = null;
		$is_cart_v4 = false;
		try {
			$booking = BookingModel::make( $data->BookingID );
			if ( $booking ) {
				$is_cart_v4 = $booking->is_curr_cart( '>=', '4.0' );
			}
		} catch ( \Exception $e ) {
			error_log( 'Error in process_booking_row (BookingModel, ID ' . $data->BookingID . '): ' . $e->getMessage() );
		}
		$totals = array(
			'total_cost' => 0,
			'total_paid' => 0,
		);
		if ( ! $is_cart_v4 && $booking ) {
			try {
				$totals = self::get_booking_totals( $booking, $is_cart_v4 );
			} catch ( \Exception $e ) {
				error_log( 'Error in process_booking_row (get_booking_totals, ID ' . $data->BookingID . '): ' . $e->getMessage() );
			}
		}
		$row_data = array(
			$data->BookingID,
			$data->BookingStatus,
			$tripname,
		);

		if ( $is_cart_v4 ) {
			$row_data[] = $booking && is_numeric( $booking->get_total() ) ? (float) $booking->get_total() : '';
			$row_data[] = $booking && is_numeric( $booking->get_total_paid_amount() ) ? (float) $booking->get_total_paid_amount() : '';
		} else {
			$row_data[] = $totals['total_cost'];
			$row_data[] = $totals['total_paid'];
		}

		$row_data = array_merge(
			$row_data,
			array(
				$paymentgateway,
				$traveler,
				$booking_date->format( 'Y-m-d' ),
				$tripstartingdate,
				$tripendingdate,
				$package_name,
			)
		);
		if ( $field_definitions['has_accommodation'] ) {
			$row_data[] = $accommodation_data;
		}
		if ( $field_definitions['has_extra_services'] ) {
			$row_data[] = $extra_services_data;
		}
		if ( $field_definitions['has_pickup_points'] ) {
			$row_data[] = $pickup_points_data;
		}
		if ( $field_definitions['has_travel_insurance'] ) {
			$row_data[] = $travel_insurance_data;
		}
		$billing_row_data   = self::get_billing_row_data( $data->BookingID, $billing_fields_with_values, $countries_list );
		$row_data           = array_merge( $row_data, $billing_row_data );
		$payment_data_array = array();
		try {
			if ( ! $booking ) {
				$booking = BookingModel::make( $data->BookingID );
			}
			if ( $booking ) {
				// Get payments data using same pattern as payment edit form fields
				$cart_info    = new CartInfoParser( $booking->get_cart_info() ?? array() );
				$payment_data = $cart_info->is_curr_cart_ver( '>=' ) ? $booking->get_payments_data( false )['payments'] ?? array() : array();

				$all_payments = $booking->get_payments();
				if ( is_array( $all_payments ) && ! empty( $all_payments ) ) {
					// Sort payments by ID
					usort(
						$all_payments,
						function ( $a, $b ) {
							$a_id = isset( $a->ID ) ? $a->ID : ( method_exists( $a, 'get_id' ) ? $a->get_id() : 0 );
							$b_id = isset( $b->ID ) ? $b->ID : ( method_exists( $b, 'get_id' ) ? $b->get_id() : 0 );
							return $a_id - $b_id;
						}
					);

					foreach ( $all_payments as $payment ) {
						$payment_id      = isset( $payment->ID ) ? $payment->ID : ( method_exists( $payment, 'get_id' ) ? $payment->get_id() : 0 );
						$payment_status  = $payment->get_payment_status();
						$payment_gateway = $payment->get_payment_gateway();
						$paid_amount     = $payment->get_amount();
						$transaction_id  = $payment->get_transaction_id();
						$currency        = $payment->get_currency() ?: $payment->get_payable_currency();

						// Get payment date
						$payment_date = $payment->get_transaction_date();
						if ( empty( $payment_date ) ) {
							if ( isset( $payment->post ) && isset( $payment->post->post_date ) ) {
								$payment_date = $payment->post->post_date;
							} elseif ( method_exists( $payment, 'get_post' ) ) {
								$post_obj = $payment->get_post();
								if ( $post_obj && isset( $post_obj->post_date ) ) {
									$payment_date = $post_obj->post_date;
								}
							}
						}

						// Get payment data using same pattern as payment edit form fields
						$_payment_data = $payment_data[ $payment_id ] ?? array();

						// Build payment row with basic fields (same pattern as payment edit form)
						$payment_row = array(
							'id'             => $payment_id,
							'status'         => ! empty( $payment_status ) ? ucfirst( $payment_status ) : '',
							'gateway'        => ! empty( $payment_gateway ) ? $payment_gateway : '',
							'date'           => ! empty( $payment_date ) ? $payment_date : '',
							'paid_amount'    => $paid_amount > 0 ? $paid_amount : 0,
							'transaction_id' => ! empty( $transaction_id ) ? $transaction_id : '',
							'currency'       => ! empty( $currency ) ? $currency : '',
						);

						// Add deposit (same pattern as payment edit form - separate from fees)
						if ( isset( $_payment_data['deposit'] ) ) {
							$deposit_value = $_payment_data['deposit'];
							if ( $deposit_value !== null && $deposit_value !== '' && $deposit_value !== false ) {
								$payment_row['deposit'] = is_numeric( $deposit_value ) ? (float) $deposit_value : $deposit_value;
							}
						}

						// Add fee fields using same pattern as payment edit form (get_fees())
						$fees = $booking->get_fees();
						foreach ( $fees as $fee ) {
							$slug = $fee['name'];
							// Only include fields that are in the verified fields list
							if ( ! in_array( $slug, $payment_fields_with_actual_values ) ) {
								continue;
							}
							// Get value from payment_data (same pattern as payment edit form)
							$value = $_payment_data[ $slug ] ?? 0;
							if ( $value !== null && $value !== '' && $value !== false ) {
								$payment_row[ $slug ] = is_numeric( $value ) ? (float) $value : $value;
							}
						}

						$payment_data_array[] = $payment_row;
					}
				}
			}
		} catch ( \Exception $e ) {
			error_log( 'Error in process_booking_row (payments, ID ' . $data->BookingID . '): ' . $e->getMessage() );
		}
		$basic_payment_fields_count = 7;
		for ( $i = 0; $i < $field_definitions['max_payments']; $i++ ) {
			if ( isset( $payment_data_array[ $i ] ) ) {
				$payment_row_data = self::get_payment_row_data( $payment_data_array[ $i ], $payment_fields_with_actual_values );
				$row_data         = array_merge( $row_data, $payment_row_data );
			} else {
				$empty_count = $basic_payment_fields_count + count( $payment_fields_with_actual_values );
				for ( $j = 0; $j < $empty_count; $j++ ) {
					$row_data[] = '';
				}
			}
		}
		$summary_data = self::get_booking_summary_data(
			$data->BookingID,
			$field_definitions['has_discount'],
			$payment_data_array,
			$field_definitions['has_deposit'],
			$field_definitions['has_tax']
		);
		if ( $field_definitions['has_deposit'] ) {
			$row_data[] = $summary_data['total_deposit'];
		}
		if ( $field_definitions['has_tax'] ) {
			$row_data[] = $summary_data['total_tax'];
		}
		if ( $field_definitions['has_discount'] ) {
			$row_data[] = $summary_data['discount_code'];
			$row_data[] = $summary_data['discount_percentage'];
			$row_data[] = $summary_data['discount_amount'];
		}
		$has_pricing_category    = in_array( 'pricing_category', $traveler_fields_with_values['lead_traveler_fields'], true )
			|| in_array( 'traveller_pricing_category', $traveler_fields_with_values['regular_traveler_fields'], true );
		$effective_max_travelers = $has_pricing_category ? max( 1, (int) $field_definitions['max_travelers'] ) : (int) $field_definitions['max_travelers'];
		$traveler_row_data       = self::get_traveler_row_data( $data->BookingID, $traveler_fields_with_values, $effective_max_travelers, $countries_list );
		$row_data                = array_merge( $row_data, $traveler_row_data );
		$emergency_row_data      = self::get_emergency_contact_row_data( $data->BookingID, $emergency_fields_with_values, $field_definitions['max_emergency_contacts'], $countries_list );
		$row_data                = array_merge( $row_data, $emergency_row_data );
		$additional_notes        = '';
		$additional_note_meta    = get_post_meta( $data->BookingID, 'wptravelengine_additional_note', true );
		if ( ! empty( $additional_note_meta ) ) {
			$additional_notes = $additional_note_meta;
		} else {
			$booking_setting = get_post_meta( $data->BookingID, 'wp_travel_engine_booking_setting', true );
			if ( is_array( $booking_setting ) && isset( $booking_setting['additional_notes'] ) && ! empty( $booking_setting['additional_notes'] ) ) {
				$additional_notes = $booking_setting['additional_notes'];
			}
		}
		$row_data[]  = $additional_notes;
		$admin_notes = get_post_meta( $data->BookingID, 'wptravelengine_admin_notes', true );
		$row_data[]  = ! empty( $admin_notes ) ? $admin_notes : '';

		return $row_data;
	}
}
