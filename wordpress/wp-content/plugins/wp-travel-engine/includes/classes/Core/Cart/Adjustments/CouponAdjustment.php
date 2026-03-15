<?php
/**
 * Discount Adjustment class.
 *
 * @since 6.3.0
 */

namespace WPTravelEngine\Core\Cart\Adjustments;

use WPTravelEngine\Abstracts\CartAdjustment;
use WPTravelEngine\Core\Cart\Cart;
use WPTravelEngine\Core\Cart\Item;
use WPTravelEngine\Core\Coupon;
use WPTravelEngine\Core\Tax;

class CouponAdjustment extends BaseCartAdjustment {

	/**
	 * Apply the adjustment.
	 *
	 * @param float $total
	 * @param Item  $cart_item
	 *
	 * @return float
	 */
	public function apply( float $total, Item $cart_item ): float {

		if ( 'percentage' === $this->adjustment_type ) {
			return $total * $this->percentage / 100;
		}

		$total_line_items = array_reduce(
			$cart_item->get_additional_line_items(),
			function ( $carry, $items ) {
				return $carry + count( $items );
			},
			0
		);

		return $total_line_items > 0 ? (float) ( $this->percentage / $total_line_items ) : 0;
	}
}
