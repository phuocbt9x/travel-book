<?php
/**
 * Booking Inventory.
 *
 * @since 5.5.3
 */
namespace WPTravelEngine\Core;

use WPTravelEngine\Core\Booking\Inventory;

/**
 * @deprecated 6.0.0
 */
class Booking_Inventory extends Inventory {

	public function __construct() {
		_deprecated_class( __CLASS__, '6.0.0', 'WPTravelEngine\Core\Booking\Inventory' );
	}

	/**
	 *
	 * @since 6.0.0
	 */
	public function get_trip_inventory( $trip_id ) {
		$original_trip_id = self::get_original_trip_id( $trip_id );

		$inventory  = $this->get_booking_inventory_record( $original_trip_id );
		$_inventory = array();
		if ( is_array( $inventory ) ) {
			foreach ( $inventory as $cart_id => $pax ) {
				$datetime                 = self::get_date_from_cart_key( $cart_id );
				$timestamp                = $datetime->getTimestamp();
				$_inventory[ $timestamp ] = array(
					'booked'  => array_sum( $pax ),
					'datestr' => $timestamp,
				);
				$pattern                  = '/cart_\d+_(\d+)_\d{4}-\d{2}-\d{2}_\d{2}-\d{2}/';
				$package                  = 0;
				if ( preg_match( $pattern, $cart_id, $matches ) ) {
					$package = $matches[1];
				}

				// Added package specific data to fix multiple package with same date issue.
				$_inventory[ $timestamp + $package ] = array(
					'booked'  => array_sum( $pax ),
					'datestr' => $timestamp + $package,
				);
			}
		}

		$old_booking_record = get_post_meta( $original_trip_id, 'wte_fsd_booked_seats', true );
		if ( is_array( $old_booking_record ) ) {
			$_inventory = array_column( array_merge( $old_booking_record, $_inventory ), null, 'datestr' );
		}

		return $_inventory;
	}
}
