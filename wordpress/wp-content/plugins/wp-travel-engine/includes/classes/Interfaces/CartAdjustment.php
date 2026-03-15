<?php
/**
 * Cart Fee Interface.
 *
 * @since 6.3.0
 */

namespace WPTravelEngine\Interfaces;

use WPTravelEngine\Core\Cart\Item;

interface CartAdjustment {

	/**
	 * Apply the adjustment.
	 *
	 * @param float $total The total to apply the adjustment to.
	 * @param Item  $cart_item
	 *
	 * @return float The adjusted total.
	 */
	public function apply( float $total, Item $cart_item ): float;
}
