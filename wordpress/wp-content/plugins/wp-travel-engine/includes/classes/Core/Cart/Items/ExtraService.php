<?php
/**
 * Extra service cart item class.
 *
 * @since 6.3.0
 */

namespace WPTravelEngine\Core\Cart\Items;

use WPTravelEngine\Abstracts\CartItem;

class ExtraService extends CartItem {

	/**
	 * @inheritdoc
	 */
	public string $item_type = 'extra_service';
}
