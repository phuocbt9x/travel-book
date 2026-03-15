<?php
/**
 * Class Coupons.
 *
 * This class handles overall functionality of coupons.
 *
 * @since 5.8.3
 */

namespace WPTravelEngine\Core;

/**
 * WP Travel Engine Coupons Class.
 */
class Coupons {

	/**
	 * Checks available coupons in specific trip.
	 *
	 * @return boolean
	 * @since 6.7.1 Refined the query to improve performance.
	 */
	public static function is_coupon_available() {
		global $wte_cart;
		$trip_id = $wte_cart->get_cart_trip_ids()[0] ?? '';

		$args = array(
			'post_type'   => 'wte-coupon',
			'post_status' => 'publish',
			'numberposts' => -1,
			'meta_query'  => array(
				array(
					'key'     => 'wp_travel_engine_coupon_metas',
					'compare' => 'EXISTS',
				),
			),
		);

		$coupons = get_posts( $args );

		$valid_coupon_exists = false;
		$today               = wp_date( 'Y-m-d' );

		foreach ( $coupons as $coupon ) {

			$meta = get_post_meta( $coupon->ID, 'wp_travel_engine_coupon_metas', true );

			if ( ! is_array( $meta ) ) {
				continue;
			}

			// Usage count
			$usage_count = (int) get_post_meta( $coupon->ID, 'wp_travel_engine_coupon_usage_count', true );

			// Coupon limit
			$limit = isset( $meta['restriction']['coupon_limit_number'] ) ? (int) $meta['restriction']['coupon_limit_number'] : '';

			if ( $limit !== '' && $limit !== 0 && $usage_count >= $limit ) {
				continue;
			}

			// Expiry date
			$expiry = $meta['general']['coupon_expiry_date'] ?? '';
			if ( $expiry && $expiry < $today ) {
				continue;
			}

			// Start date
			$start = $meta['general']['coupon_start_date'] ?? '';
			if ( $start && $start > $today ) {
				continue;
			}

			// Trip restriction
			$restricted_trips = $meta['restriction']['restricted_trips'] ?? array();

			if ( ! empty( $restricted_trips ) && ! in_array( (string) $trip_id, array_map( 'strval', (array) $restricted_trips ), true ) ) {
				continue;
			}

			$valid_coupon_exists = true;
			break;
		}

		return $valid_coupon_exists;
	}
}
