<?php
/**
 * Trip Item class.
 *
 * @since 6.3.0
 */

namespace WPTravelEngine\Core\Cart\Items;

use WPTravelEngine\Abstracts\CartItem;

class PricingCategory extends CartItem {

	/**
	 * @inheritdoc
	 */
	public string $item_type = 'pricing_category';
}
