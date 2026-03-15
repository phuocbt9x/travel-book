<?php
/**
 * Class Item
 *
 * @package WPTravelEngine\Core\Cart
 * @since 6.0.0
 */

namespace WPTravelEngine\Core\Cart;

use WPTravelEngine\Core\Cart\Items\PricingCategory;
use WP_REST_Request;
use WPTravelEngine\Core\Coupon;
use WPTravelEngine\Core\Models\Post\Booking;
use WPTravelEngine\Core\Models\Post\Trip;
use WPTravelEngine\Core\Models\Post\TripPackage;
use WPTravelEngine\Core\PartialPayment;
use WPTravelEngine\Interfaces\CartItem;
use WPTravelEngine\Interfaces\CartItem as CartItemInterface;
use WPTravelEngine\Abstracts\CartItem as AbstractCartItem;

#[AllowDynamicProperties]
/**
 * Class Item.
 */
class Item {

	/**
	 * @var mixed
	 */
	protected $trip_price;

	/**
	 * @var mixed
	 */
	protected $trip_price_partial;

	/**
	 * @var mixed
	 */
	protected $pax;

	/**
	 * @var mixed
	 */
	protected $price_key;

	/**
	 * @var mixed
	 */
	protected $trip_date;

	/**
	 * @var mixed
	 */
	protected $trip_time;

	/**
	 * @var mixed
	 */
	public $trip_id;

	/**
	 * @var array
	 */
	protected array $attrs;

	/**
	 * Item ID.
	 *
	 * @var string Item ID.
	 */
	protected string $id;

	/**
	 * @var mixed
	 */
	protected $datetime;

	/**
	 * If this item is loaded from booking.
	 *
	 * @var bool
	 */
	protected bool $is_order_item = false;

	/**
	 * Cart Charges and Fees.
	 *
	 * @since 6.3.0
	 * @var array
	 */
	protected array $additional_line_items = array();

	/**
	 * Default totals for the item.
	 *
	 * @var array Totals for different types.
	 */
	protected array $default_totals = array(
		'total'    => 0,
		'subtotal' => 0,
		'discount' => 0,
		'tax'      => array(
			'subtotal' => 0,
			'total'    => 0,
			'discount' => 0,
		),
		'partial'  => 0,
	);

	/**
	 * Holds Calculated totals for the item.
	 *
	 * @var mixed
	 */
	protected array $totals = array();
	/**
	 * @var true
	 */
	protected bool $calculated_totals = false;

	/**
	 * Cart.
	 *
	 * @var Cart
	 */
	public Cart $cart;

	/**
	 * @param array $attrs Attributes.
	 * @param Cart  $cart Cart.
	 */
	public function __construct( Cart $cart, array $attrs = array() ) {

		$this->attrs = $attrs;
		$this->cart  = $cart;

		$this->trip_id   = $this->attrs['trip_id'] ?? 0;
		$this->price_key = $this->attrs['price_key'] ?? '';
		$this->pax       = $this->attrs['pax'] ?? array();

		$this->id = $this->generate_id();

		$this->calculate_totals();
	}

	/**
	 * Add attributes.
	 *
	 * @param array $attrs Attributes.
	 *
	 * @since 6.3.3
	 */
	public function add_attributes( array $attrs ) {
		$this->attrs = array_merge( $this->attrs, $attrs );
	}

	/**
	 * Remove additional line items.
	 *
	 * @param string $item_type
	 *
	 * @since 6.3.3
	 */
	public function remove_additional_line_items( string $item_type ) {
		if ( isset( $this->additional_line_items[ $item_type ] ) ) {
			$this->additional_line_items[ $item_type ] = array();
		}
	}

	/**
	 * Get attributes.
	 *
	 * @return array
	 * @since 6.3.3
	 */
	public function get_attributes(): array {
		return $this->attrs;
	}

	/**
	 * Set item properties from booking.
	 *
	 * @param $order_item
	 * @param Booking $booking Booking Object.
	 * @param Cart    $cart Cart Object.
	 *
	 * @return Item
	 */
	public static function from_order_item( $order_item, Booking $booking, Cart $cart ): Item {
		$order_item['trip_id']    = $order_item['ID'];
		$order_item['trip_price'] = $order_item['cost'] ?? 0;

		if ( isset( $order_item['_cart_item_object'] ) ) {
			$order_item = $order_item['_cart_item_object'];
		}

		$cart_data = $booking->get_cart_info();

		$item = new static( $cart, $order_item );

		// $item->add_line_items_from_order_item( $order_item );

		$item->add_line_items_from_order_item( $cart_data );

		return $item;
	}

	/**
	 * Set Item properties from Request.
	 *
	 * @param WP_REST_Request $request
	 * @param Cart            $cart
	 *
	 * @return Item
	 */
	public static function from_request( WP_REST_Request $request, Cart $cart ): Item {
		$cart_data = (object) $request->get_json_params();

		$package = new TripPackage( $cart_data->packageID, Trip::make( $cart_data->tripID ) );

		$trip_price = 0;
		$travelers  = array();

		if ( isset( $cart_data->{'travelers'} ) && is_array( $cart_data->{'travelers'} ) ) {
			$package_travelers    = $package->get_traveler_categories();
			$cart_pricing_options = $cart_data->{'travelers'};

			foreach ( $package_travelers as $package_traveler ) {
				$pax = $cart_pricing_options[ $package_traveler->id ] ?? 0;
				if ( $pax < 1 ) {
					continue;
				}
				if ( isset( $cart_pricing_options[ $package_traveler->id ] ) ) {
					$applicable_price = isset( $package_traveler->has_sale ) && $package_traveler->has_sale ? $package_traveler->sale_price : $package_traveler->price;

					$enable_group_discount = (bool) ( $package_traveler->enabled_group_discount ?? false );
					$group_pricing         = $package->get_group_pricing()[ $package_traveler->id ] ?? array();

					$travelers['pax'][ $package_traveler->id ] = $pax;

					if ( $enable_group_discount && ! empty( $group_pricing ) ) {
						foreach ( $group_pricing as $pricing ) {
							if ( $pricing['from'] <= $pax && ( empty( $pricing['to'] ) || ( $pricing['to'] >= $pax ) ) ) {
								$applicable_price = $pricing['price'];
								break;
							}
						}
					}

					$travelers['info'][ $package_traveler->id ] = array(
						'label'                => $package_traveler->label,
						'price'                => $package_traveler->price,
						'enabledSale'          => $package_traveler->has_sale ?? false,
						'salePrice'            => $package_traveler->sale_price,
						'pricingType'          => $package_traveler->pricing_type,
						'minPax'               => $package_traveler->min_pax,
						'maxPax'               => $package_traveler->max_pax,
						'groupPricing'         => $group_pricing,
						'enabledGroupDiscount' => $enable_group_discount,
					);

					$travelers['cost'][ $package_traveler->id ] = $package_traveler->pricing_type === 'per-group' ? $applicable_price : (float) $applicable_price * $pax;

					$trip_price += $travelers['cost'][ $package_traveler->id ];
				}
			}
		}

		$tax   = $cart->tax();
		$attrs = apply_filters(
			'wp_travel_engine_cart_attributes',
			array(
				'trip_id'               => $cart_data->{'tripID'},
				'trip_price'            => $trip_price,
				'trip_date'             => $cart_data->{'tripDate'},
				'trip_time'             => $cart_data->{'tripTime'} ?? '',
				'trip_time_range'       => $cart_data->{'timeRange'} ?? array(),
				'price_key'             => $cart_data->{'packageID'},
				// 'pricing_options'    => $cart_data->{'pricingOptions'},
				'pax'                   => $travelers['pax'] ?? array(),
				'pax_labels'            => array(),
				'category_info'         => $travelers['info'],
				'pax_cost'              => $travelers['cost'],
				'multi_pricing_used'    => ! 0,
				'trip_extras'           => ! empty( $cart_data->{'extraServices'} ) ? (array) $cart_data->{'extraServices'} : array(),
				// Tax Percentage.
				'datetime'              => $cart_data->{'tripDate'},
				'tax_amount'            => $tax->get_tax_percentage(),
				'subtotal_reservations' => $cart_data->{'subtotalReservations'} ?? array(),
				'travelers'             => $cart_data->{'travelers'},
			)
		);

		$item = new static( $cart, $attrs );

		$item->add_add_line_items();

		return $item;
	}

	/**
	 * Add additional line items.
	 *
	 * @param CartItemInterface $item
	 *
	 * @since 6.3.0
	 */
	public function add_additional_line_items( CartItemInterface $item ) {
		$this->additional_line_items[ $item->item_type ][] = $item;
	}

	/**
	 * Set additional line items.
	 *
	 * @param array $items
	 *
	 * @since 6.3.0
	 */
	public function set_additional_line_items( array $items ) {
		$this->additional_line_items = $items;
	}

	/**
	 * Get cart items.
	 *
	 * @return CartItem[]
	 * @since 6.3.0
	 */
	public function get_additional_line_items(): array {
		return apply_filters( 'wptravelengine_cart_' . __FUNCTION__, $this->additional_line_items, $this );
	}

	/**
	 * Get totals.
	 *
	 * @return float|array
	 * @since 6.3.0
	 */
	public function get_totals( ?string $key = null ) {
		if ( $key ) {
			return $this->totals[ $key ] ?? 0;
		}

		return $this->totals;
	}

	/**
	 * Calculate totals.
	 *
	 * @return void
	 * @updated 6.7.0
	 */
	public function calculate_totals() {
		$this->totals = array(
			'subtotal'      => 0,
			'total'         => 0,
			'total_partial' => 0,
		);

		do_action( 'wptravelengine_before_cart_item_calculate_totals', $this );

		/* @var CartItem $item */
		foreach ( $this->additional_line_items as $item ) {
			if ( $item instanceof CartItem ) {
				$item = array( $item );
			}
			foreach ( $item as $_item ) {
				$item_subtotal = $_item->get_totals( 'subtotal' );

				$this->totals['subtotal'] += $item_subtotal;

				$discounted_subtotal = $this->apply_discounts( $item_subtotal, $_item );

				if ( $this->cart->is_curr_cart( '<' ) ) {
					$this->totals['total'] += $this->apply_fees( $discounted_subtotal, $_item );
				} else {
					$this->totals['total'] += $item_subtotal;
				}
			}
		}

		$this->totals['total_partial'] += $this->calculate_partial();

		/**
		 * Round all totals to 2 decimal places.
		 *
		 * @since 6.6.9
		 */
		foreach ( $this->totals as $key => $value ) {
			$this->totals[ $key ] = round( $value, 2 );
		}

		$this->totals = apply_filters( 'wptravelengine_after_cart_item_calculate_totals', $this->totals, $this );

		$this->calculated_totals = true;
	}

	/**
	 * Apply adjustments.
	 *
	 * @param array  $adjustment_items
	 * @param float  $subtotal
	 * @param string $type
	 *
	 * @return float
	 */
	protected function apply_adjustments( array $adjustment_items, float $subtotal, CartItem $line_item, string $type = 'fee' ): float {
		$_subtotal = $subtotal;

		foreach ( $adjustment_items as $item ) {
			if ( ! empty( $item->applies_to ) && ! in_array( $line_item->item_type, $item->applies_to ) ) {
				continue;
			}
			$deduct_value = $item->apply_to_actual_subtotal ? $item->apply( $subtotal, $this ) : $item->apply( $_subtotal, $this );

			if ( ! isset( $this->totals[ "total_{$item->name}" ] ) ) {
				$this->totals[ "total_{$item->name}" ] = 0;
			}
			$this->totals[ "total_{$item->name}" ] += $deduct_value;

			if ( $type === 'discount' ) {
				$_subtotal = $_subtotal - $deduct_value;
			} else {
				$_subtotal = $_subtotal + $deduct_value;
			}
		}

		return $_subtotal;
	}

	/**
	 * Apply discounts.
	 *
	 * @param float $subtotal
	 *
	 * @return float
	 */
	protected function apply_discounts( float $subtotal, CartItem $item ): float {
		return $this->apply_adjustments( $this->cart->get_deductible_items(), $subtotal, $item, 'discount' );
	}

	/**
	 * Apply fees.
	 *
	 * @param float             $subtotal
	 * @param CartItemInterface $item
	 *
	 * @return float
	 */
	protected function apply_fees( float $subtotal, CartItem $item ): float {
		return $this->apply_adjustments( $this->cart->get_fees(), $subtotal, $item );
	}

	/**
	 * Calculate totals.
	 *
	 * @return void
	 */
	// public function calculate_totals() {
	// $this->totals = $this->default_totals;
	//
	// $this->totals[ 'subtotal' ] = $this->calculate_subtotal();
	// $this->totals[ 'discount' ] = $this->calculate_discount();
	// $this->totals[ 'tax' ]      = $this->calculate_tax();
	// $this->totals[ 'total' ]    = $this->calculate_total();
	// $this->totals[ 'partial' ]  = $this->calculate_partial();
	//
	// $this->totals = apply_filters( 'wptravelengine_cart_item_calculate_totals', $this->totals, $this );
	// }

	/**
	 * Calculate subtotal.
	 *
	 * @return float
	 */
	public function calculate_subtotal(): float {
		return $this->price() + $this->trip_extras_totals();
	}

	/**
	 * Calculate total.
	 *
	 * @return float
	 */
	public function calculate_total(): float {
		return $this->totals['subtotal'] - $this->totals['discount'] + ( $this->totals['tax']['total'] ?? 0 );
	}

	/**
	 * Calculate Tax for the Trip.
	 *
	 * @return array
	 */
	public function calculate_tax(): array {

		$tax = $this->cart->tax();

		$totals = array();
		if ( $tax->is_taxable() && $tax->is_exclusive() ) {
			$totals['subtotal'] = $tax->get_tax_amount( $this->totals['subtotal'] );
			$totals['discount'] = $tax->get_tax_amount( $this->totals['discount'] );
			$totals['total']    = $tax->get_tax_amount( $this->totals['subtotal'] - $this->totals['discount'] );
		}

		return $totals;
	}

	/**
	 * Generates Item ID.
	 *
	 * @return string
	 */
	protected function generate_id(): string {
		$trip_time = $this->attrs['trip_time'] ?? '';
		$trip_date = $this->attrs['trip_date'] ?? '';
		if ( ! empty( $trip_time ) ) {
			$suffix = ( new \DateTime( $trip_time ) )->format( 'Y-m-d_H-i' );
		} else {
			$suffix = ( new \DateTime( $trip_date ) )->format( 'Y-m-d_H-i' );
		}

		$cart_item_id = "cart_{$this->trip_id}";

		if ( ! empty( $this->price_key ) ) {
			$cart_item_id .= '_' . $this->price_key;
		}

		$cart_item_id .= "_{$suffix}";

		return apply_filters( 'wp_travel_engine_filter_cart_item_id', $cart_item_id, $this->trip_id );
	}

	/**
	 * Get Item ID.
	 *
	 * @return string
	 */
	public function id(): string {
		return $this->id;
	}

	/**
	 * Get Item data.
	 *
	 * @return array
	 */
	public function data(): array {
		$data = $this->attrs;

		$data['trip_id'] = $this->trip_id;
		if ( ! is_array( $this->pax ) ) {
			unset( $data['trip_price'] );
			unset( $data['trip_price_partial'] );
		}
		$data['id'] = $this->id();

		$line_items = array();
		foreach ( $this->get_additional_line_items() as $type => $items ) {
			foreach ( $items as $item ) {
				$line_items[ $type ][] = $item->data();
			}
		}

		$data['line_items'] = $line_items;

		return $data;
	}

	/**
	 * Get a sum of trip price.
	 *
	 * @return float
	 */
	public function price(): float {
		return (float) $this->trip_price;
	}

	/**
	 * Calculates trip extras totals.
	 *
	 * @return float
	 */
	public function trip_extras_totals(): float {
		$totals = 0;
		if ( ! empty( $this->attrs['trip_extras'] ) && is_array( $this->attrs['trip_extras'] ) ) :
			foreach ( $this->attrs['trip_extras'] as $extra ) :
				$totals += (float) ( $extra['price'] * $extra['qty'] );
			endforeach;
		endif;

		return (float) $totals;
	}

	/**
	 * @param $string bool Whether to return string value or array.
	 *
	 * @return array|string
	 */
	public function down_payment_settings( bool $string = false ) {

		$trip_id         = $this->trip_id;
		$partial_payment = array();
		$global_settings = get_option( 'wp_travel_engine_settings', true );
		$trip_settings   = get_post_meta( $trip_id, 'wp_travel_engine_setting', true );

		$valid_partial_types = apply_filters( 'wptravelengine_partial_value_types', array( 'amount', 'percent' ) );

		$type = $global_settings['partial_payment_option'] ?? 'invalid';

		if ( ! $trip_id || ! wp_travel_engine_is_trip_partially_payable( $trip_id ) || ! in_array( $type, $valid_partial_types, true ) ) {
			return $partial_payment;
		}

		$trip_full_payment   = ( $trip_settings['trip_full_payment_enabled'] ?? 'yes' ) === 'yes';
		$global_full_payment = ( $global_settings['full_payment_enable'] ?? 'yes' ) === 'yes';

		$value = (float) $global_settings[ "partial_payment_{$type}" ] ?? 0;
		if ( ! empty( $trip_settings[ "partial_payment_{$type}" ] ) ) {
			$value = (float) $trip_settings[ "partial_payment_{$type}" ];
		}

		$trip_full_payment = $global_full_payment;

		/**
		 * Send more data to disable full payment.
		 *
		 * @since 5.7.1
		 */
		$partial_payment = compact( 'trip_full_payment', 'global_full_payment', 'type', 'value' );

		$string_value = $type === 'percent' ? $value . '%' : $value;

		return $string ? $string_value : $partial_payment;
	}

	/**
	 * Calculates down payment with provided total.
	 *
	 * @return float
	 */
	public function calculate_partial(): float {
		return PartialPayment::instance()->apply_to_cart_item( $this );
	}

	/**
	 * Calculate the discount for the item.
	 *
	 * @return float
	 */
	public function calculate_discount(): float {
		$discounts = $this->cart->get_discounts();

		$discount_total = 0;
		foreach ( $discounts as $discount ) {
			$discount_total += Coupon::calculate_value( $this->totals['subtotal'], $discount['type'], $discount['value'] );
		}

		return $discount_total;
	}

	/**
	 * Get the item cart.
	 *
	 * @return Cart|null
	 * @since 6.2.3
	 */
	public function cart() {
		return $this->cart ?? null;
	}

	/**
	 * Calculate the subtotal for the item.
	 *
	 * @return float
	 */
	public function subtotal(): float {
		return $this->totals['subtotal'];
	}

	/**
	 * Get Discount Amount if applicable.
	 *
	 * @return float
	 */
	public function discount(): float {
		return $this->totals['discount'];
	}

	/**
	 * Calculate Tax for the Trip.
	 *
	 * @return array
	 */
	public function tax(): array {
		return $this->totals['tax'];
	}

	/**
	 * Total sum of cart item.
	 * This includes trip price and extras services.
	 *
	 * @return float
	 */
	public function total(): float {
		return $this->totals['total'];
	}

	/**
	 * Partial amount that can be paid as down payment.
	 * Calculated on item total.
	 *
	 * @return float
	 * @since 6.0.0
	 */
	public function partial_total(): float {
		return $this->totals['partial'];
	}

	/**
	 * @return float
	 */
	public function due_total(): float {
		return $this->calculate_total() - $this->calculate_partial();
	}

	/**
	 * Get Item attribute.
	 *
	 * @param string $name Attribute name.
	 *
	 * @return mixed
	 */
	public function __get( string $name ) {
		return $this->attrs[ $name ] ?? null;
	}

	/**
	 * Add additional line items.
	 *
	 * @return void
	 * @since 6.3.0
	 */
	public function add_add_line_items() {
		$item    = $this;
		$package = new TripPackage( $this->price_key, new Trip( $this->trip_id ) );

		if ( isset( $this->pax ) && is_array( $this->pax ) ) {
			$package_travelers    = $package->get_traveler_categories();
			$cart_pricing_options = $this->pax;

			foreach ( $package_travelers as $package_traveler ) {
				if ( isset( $cart_pricing_options[ $package_traveler->id ] ) ) {
					$pax = (int) ( $cart_pricing_options[ $package_traveler->id ] ?? 0 );

					$applicable_price = isset( $package_traveler->has_sale ) && $package_traveler->has_sale ? $package_traveler->sale_price : $package_traveler->price;

					$enable_group_discount = (bool) ( $package_traveler->enabled_group_discount ?? false );
					$group_pricing         = $package->get_group_pricing()[ $package_traveler->id ] ?? array();

					$travelers['pax'][ $package_traveler->id ] = $pax;

					if ( $enable_group_discount && ! empty( $group_pricing ) ) {
						foreach ( $group_pricing as $pricing ) {
							if ( $pricing['from'] <= $pax && ( empty( $pricing['to'] ) || ( $pricing['to'] >= $pax ) ) ) {
								$applicable_price = $pricing['price'];
								break;
							}
						}
					}

					$price = $package_traveler->pricing_type === 'per-group' && $pax > 0 ? $applicable_price / $pax : $applicable_price;

					$item->add_additional_line_items(
						new PricingCategory(
							$this->cart,
							array(
								'label'       => $package_traveler->label,
								'quantity'    => $pax,
								'price'       => apply_filters(
									'wptravelengine_package_traveler_price',
									$price,
									compact(
										'item',
										'package_traveler'
									)
								),
								'pricingType' => $package_traveler->pricing_type,
							)
						)
					);
				}
			}
		}

		// if ( $this->attrs[ 'additional_line_items' ] ?? false ) {
		// foreach ( $this->attrs[ 'additional_line_items' ] as $class_name => $additional_line_items ) {
		// foreach ( $additional_line_items as $additional_line_item ) {
		// if ( class_exists( $class_name ) ) {
		// $this->add_additional_line_items(
		// new $class_name( $this->cart, $additional_line_item )
		// );
		// }
		// }
		// }
		// }
	}

	/**
	 * Add line items from order item.
	 *
	 * @param $order_item
	 *
	 * @since 6.3.3
	 */
	public function add_line_items_from_order_item( $order_item ) {

		$pricing_categories = $order_item['items'][0]['line_items']['pricing_category'] ?? array();

		foreach ( $pricing_categories as $pricing_category ) {
			$this->add_additional_line_items(
				new PricingCategory(
					$this->cart,
					array(
						'label'    => $pricing_category['label'],
						'quantity' => $pricing_category['quantity'],
						'price'    => $pricing_category['price'],
						'total'    => $pricing_category['total'],
					)
				)
			);
		}

		// TODO: Commented out code if for package travelers, once finalized above code works fine need to remove this.
		// if ( isset( $this->pax ) && is_array( $this->pax ) ) {
		// $cart_pricing_options = $this->pax;

		// $package_travelers = $order_item[ 'category_info' ];

		// foreach ( $package_travelers as $pricing_category_id => $package_traveler ) {
		// $package_traveler[ 'id' ] = $pricing_category_id;
		// $package_traveler         = (object) $package_traveler;

		// if ( isset( $cart_pricing_options[ $package_traveler->id ] ) ) {
		// $pax = (int) ( $cart_pricing_options[ $package_traveler->id ] ?? 0 );

		// $applicable_price = isset( $package_traveler->enabledSale ) && $package_traveler->enabledSale ? $package_traveler->salePrice : $package_traveler->price;

		// $enable_group_discount = (bool) ( $package_traveler->enabledGroupDiscount ?? false );
		// $group_pricing         = $package_traveler->groupPricing ?? [];

		// $travelers[ 'pax' ][ $package_traveler->id ] = $pax;

		// if ( $enable_group_discount && ! empty( $group_pricing ) ) {
		// foreach ( $group_pricing as $pricing ) {
		// if ( $pricing[ 'from' ] <= $pax && ( empty( $pricing[ 'to' ] ) || ( $pricing[ 'to' ] >= $pax ) ) ) {
		// $applicable_price = $pricing[ 'price' ];
		// break;
		// }
		// }
		// }

		// $price = $package_traveler->pricingType === 'per-group' && $pax > 0 ? $applicable_price / $pax : $applicable_price;

		// $total = $package_traveler->total ?? 0;

		// $item = $this;
		// $this->add_additional_line_items(
		// new PricingCategory( $this->cart, array(
		// 'label'    => $package_traveler->label,
		// 'quantity' => $pax,
		// 'price'    => apply_filters( 'wptravelengine_package_traveler_price', $price, compact(
		// 'item',
		// 'package_traveler'
		// )
		// ),
		// 'total'    => $total,
		// ) )
		// );
		// }
		// }
		// }
	}
}
