<?php
/**
 * Tax fee item.
 *
 * @since 6.3.0
 */

namespace WPTravelEngine\Core\Cart\Adjustments;

use WPTravelEngine\Abstracts\CartAdjustment;
use WPTravelEngine\Core\Cart\Cart;
use WPTravelEngine\Core\Cart\Item;
use WPTravelEngine\Core\Tax;

class TaxAdjustment extends BaseCartAdjustment {

	/**
	 * Tax instance.
	 *
	 * @var Tax
	 */
	protected Tax $tax;

	public function __construct( Cart $cart, array $args = array() ) {
		$this->tax = new Tax();

		$args = wp_parse_args(
			$args,
			array(
				'name'       => 'tax',
				'label'      => $this->tax->get_tax_label(),
				'percentage' => $this->tax->get_tax_percentage(),
				'item_type'  => 'fee',
			)
		);
		parent::__construct( $cart, $args );
		$this->tax->set_tax_percentage( $this->percentage );
	}

	/**
	 * @inheritDoc
	 */
	public function apply( $total, Item $cart_item ): float {
		return $this->tax->get_tax_amount( $total );
	}
}
