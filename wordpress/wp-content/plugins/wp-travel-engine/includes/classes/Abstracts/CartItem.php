<?php
/**
 * Cart Item Abstract Class
 *
 * @since 6.3.0
 */

namespace WPTravelEngine\Abstracts;

use WP_REST_Request;
use WPTravelEngine\Core\Cart\Cart;
use WPTravelEngine\Core\Cart\Item;
use WPTravelEngine\Core\Models\Post\Booking;
use WPTravelEngine\Interfaces\CartItem as CartItemInterface;
use WPTravelEngine\Interfaces\Tax as TaxInterface;
use WPTravelEngine\Core\Models\Post\Trip;

abstract class CartItem implements CartItemInterface {

	public Trip $trip;

	public Cart $cart;

	/**
	 * Item type.
	 *
	 * @var string
	 */
	public string $item_type;

	public static string $type;

	public string $label;

	public int $quantity;

	public float $price;

	public float $total;

	public TaxInterface $tax;

	protected bool $calculated_totals = false;

	protected array $totals;

	protected array $args;

	public function __construct( Cart $cart, $args = array() ) {
		$this->cart = $cart;
		$this->tax  = $cart->tax();

		$this->args = wp_parse_args(
			$args,
			array(
				'label'    => '',
				'quantity' => 0,
				'price'    => 0,
			)
		);

		$this->label    = (string) $this->args['label'];
		$this->quantity = (int) $this->args['quantity'];
		$this->price    = (float) $this->args['price'];
		$this->total    = isset( $this->args['total'] ) && $this->args['total'] > 0 ? $this->args['total'] : ( $this->args['total'] = $this->quantity * $this->price );
	}

	/**
	 * Get item subtotal.
	 *
	 * @return float
	 */
	public function get_subtotal(): float {
		return $this->get_totals( 'subtotal' );
	}

	/**
	 * Get item total.
	 *
	 * @return float
	 */
	public function get_total(): float {
		return $this->get_totals( 'total' );
	}

	/**
	 * Calculate totals.
	 *
	 * @return void
	 */
	protected function calculate_totals() {
		$subtotal = $this->calculate_subtotal();

		$this->totals = array(
			'subtotal' => $subtotal,
		);

		// $this->totals[ 'total' ] = $this->apply_fees( $this->apply_discounts( $subtotal ) );

		$this->calculated_totals = true;
	}

	/**
	 * Apply adjustments.
	 *
	 * @param array $adjustment_items
	 * @param float $subtotal
	 * @param string $type
	 *
	 * @return float
	 */
	// protected function apply_adjustments( array $adjustment_items, float $subtotal, string $type = 'fee' ): float {
	// $_subtotal = $subtotal;
	// foreach ( $adjustment_items as $item ) {
	// if ( ! empty( $item->applies_to ) && ! in_array( $this->item_type, $item->applies_to ) ) {
	// continue;
	// }
	// $deduct_value = $item->apply_to_actual_subtotal ? $item->apply( $subtotal ) : $item->apply( $_subtotal );
	//
	// $this->totals[ "total_{$item->name}" ] = $deduct_value;
	//
	// if ( $type === 'discount' ) {
	// $_subtotal = $_subtotal - $deduct_value;
	// } else {
	// $_subtotal = $_subtotal + $deduct_value;
	// }
	// }
	//
	// return $_subtotal;
	// }

	/**
	 * Apply discounts.
	 *
	 * @param float $subtotal
	 *
	 * @return float
	 */
	// protected function apply_discounts( float $subtotal ): float {
	// return $this->apply_adjustments( $this->cart->get_deductible_items(), $subtotal, 'discount' );
	// }

	/**
	 * Apply fees.
	 *
	 * @param float $subtotal
	 *
	 * @return float
	 */
	// protected function apply_fees( float $subtotal ): float {
	// return $this->apply_adjustments( $this->cart->get_fees(), $subtotal );
	// }

	public function calculate_subtotal(): float {
		return (float) $this->total && $this->total > 0 ? (float) $this->total : (float) $this->price * $this->quantity;
	}

	/**
	 * Get totals.
	 *
	 * @return float|array
	 */
	public function get_totals( ?string $key = null ) {
		if ( ! $this->calculated_totals ) {
			$this->calculate_totals();
		}
		if ( $key ) {
			return $this->totals[ $key ] ?? 0;
		}

		return $this->totals;
	}

	/**
	 * Get data.
	 *
	 * @return array
	 * @since 6.3.0
	 */
	public function data(): array {
		return array_merge(
			$this->args,
			array( '_class_name' => get_class( $this ) )
		);
	}

	/**
	 * Render.
	 *
	 * @return string
	 * @since 6.3.5
	 */
	public function render(): string {
		return sprintf(
			'<tr><td>%s</td><td><strong>%s</strong></td></tr>',
			sprintf( '%s: %s x %s', $this->label, $this->quantity, wptravelengine_the_price( $this->price, false ) ),
			wptravelengine_the_price( $this->get_subtotal(), false )
		);
	}
}
