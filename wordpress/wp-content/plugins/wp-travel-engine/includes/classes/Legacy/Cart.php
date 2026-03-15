<?php
/**
 * This is legacy class of WP Travel Engine Core Cart.
 *
 * @package WP Travel Engine
 */

namespace WPTravelEngine\Legacy;

// Exit if accessed directly.
use WP_REST_Request;
use WPTravelEngine\Core\Cart\Item;
use WPTravelEngine\Abstracts\CartAdjustment;
use WPTravelEngine\Core\Cart\Adjustments\CouponAdjustment;
use WPTravelEngine\Core\Coupon;
use WPTravelEngine\Core\Tax;
use WPTravelEngine\Filters\AddCartItems;
use WPTravelEngine\Filters\CheckoutPageTemplate;
use WPTravelEngine\Interfaces\CartItem;
use WPTravelEngine\Interfaces\CartItem as CartItemInterface;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * WP Travel Engine Cart Shortcode Class.
 */
class Cart {

	public string $version = '3.0';

	public array $cart_info = array();

	/**
	 * Session key to hold cart data.
	 *
	 * @var string
	 */
	protected string $cart_id = 'wpte_trip_cart';

	/**
	 * Unique Cart Key for dirty cart.
	 *
	 * @var string
	 */
	protected string $cart_key = '';

	/**
	 * Limit of item in cart.
	 *
	 * @var integer
	 */
	protected int $item_limit = 0;

	/**
	 * Limit of quantity per item.
	 *
	 * @var integer
	 */
	protected int $quantity_limit = 99;

	/**
	 * Holds the Item objects in the cart.
	 *
	 * @var Item[] $items An array of Item objects.
	 */
	protected array $items = array();

	/**
	 * Cart Charges and Fees.
	 *
	 * @since 6.3.0
	 * @var array
	 */
	protected array $additional_line_items = array();

	/**
	 * Cart Deductible Items.
	 *
	 * @since 6.3.0
	 * @var array
	 */
	protected array $deductible_items = array();

	/**
	 * Cart Fees.
	 *
	 * @var array
	 * @since 6.3.0
	 */
	protected array $fees = array();

	/**
	 * Cart Discounts.
	 *
	 * @var array
	 */
	protected array $discounts = array();

	/**
	 * Cart item attributes.
	 *
	 * @var array
	 */
	protected array $attributes = array();

	/**
	 * Cart errors.
	 *
	 * @var array
	 */
	protected array $errors = array();

	/**
	 * @var Tax $tax
	 */
	protected Tax $tax;

	/**
	 * @var string $payment_type Payment type. full|due|partial.
	 */
	protected string $payment_type = 'full';

	/**
	 * @var mixed|null
	 */
	protected $booking_ref = null;

	/**
	 * @var float[] $default_totals Default total values.
	 */
	protected array $default_totals = array(
		'subtotal'       => 0,
		'subtotal_tax'   => 0,
		'discount_total' => 0,
		'discount_tax'   => 0,
		'total'          => 0,
		'total_tax'      => 0,
		'partial_total'  => 0,
		'due_total'      => 0,
	);

	/**
	 * @var array $totals Total values of the cart.
	 */
	protected array $totals = array();

	/**
	 * @var string
	 */
	public string $payment_gateway = '';

	/**
	 * @var bool
	 */
	protected bool $static_cart = false;

	/**
	 * Initialize shopping cart.
	 *
	 * @return void
	 */
	public function __construct() {
		$cart_items_hooks = new AddCartItems();
		$cart_items_hooks->hooks();

		$checkout_page_template = new CheckoutPageTemplate();
		$checkout_page_template->hooks();

		$this->tax = new Tax();
		// Read cart data on load.
		add_action( 'init', array( $this, 'read_cart_onload' ), 11 );
	}

	/**
	 * Get cart items.
	 *
	 * @return CartAdjustment[]
	 * @since 6.3.0
	 */
	public function get_deductible_items(): array {
		$items = $this->deductible_items;
		usort(
			$items,
			function ( $a, $b ) {
				return $a->order - $b->order;
			}
		);

		return apply_filters( 'wptravelengine_cart_' . __FUNCTION__, $items, $this );
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
	 * Get Fees items.
	 *
	 * @return CartAdjustment[]
	 * @since 6.3.0
	 */
	public function get_fees(): array {
		$fees = $this->fees;
		usort(
			$fees,
			function ( $a, $b ) {
				return $a->order - $b->order;
			}
		);

		return apply_filters( 'wptravelengine_cart_' . __FUNCTION__, $fees, $this );
	}

	/**
	 * @param array $fees
	 *
	 * @return void
	 * @since 6.4.0
	 */
	public function set_fees( array $fees ): void {
		$this->fees = $fees;
	}

	/**
	 * Add fee item.
	 *
	 * @param CartAdjustment $item
	 *
	 * @return void
	 * @since 6.3.0
	 */
	public function add_fee( CartAdjustment $item ) {
		$this->maybe_remove_item( $item->name, 'fee' );

		if ( $item->order < 0 ) {
			$this->fees[] = $item;
		} else {
			$this->fees[ $item->order ] = $item;
		}
	}

	/**
	 * Add additional line items.
	 *
	 * @param CartAdjustment $item
	 *
	 * @since 6.3.0
	 */
	public function add_deductible_items( CartAdjustment $item ) {
		$this->maybe_remove_item( $item->name, 'deductible' );

		if ( $item->order < 0 ) {
			$this->deductible_items[] = $item;
		} else {
			$this->deductible_items[ $item->order ] = $item;
		}
	}

	/**
	 * Maybe remove item from cart.
	 * If item is already in cart, remove it.
	 *
	 * @param string $name
	 * @param string $type
	 *
	 * @return void
	 * @since 6.7.0
	 */
	private function maybe_remove_item( string $name, string $type ) {
		switch ( $type ) {
			case 'fee':
				$items = &$this->fees;
				break;
			case 'deductible':
				$items = &$this->deductible_items;
				break;
			default:
				return;
		}

		foreach ( $items as $key => $item ) {
			if ( $item->name === $name ) {
				unset( $items[ $key ] );
				break;
			}
		}
	}

	/**
	 * Get cart items.
	 *
	 * @return CartItem[]
	 * @since 6.4.0
	 */
	public function get_cart_items(): array {
		return $this->items;
	}

	/**
	 * Add additional line items.
	 *
	 * @param CartItemInterface $item
	 *
	 * @since 6.3.0
	 */
	public function add_additional_line_items( CartItemInterface $item ) {
		$this->additional_line_items[] = $item;
	}

	/**
	 * Get cart id.
	 *
	 * @return string
	 */
	public function get_cart_id(): string {
		return $this->cart_id;
	}

	/**
	 * Get cart key.
	 *
	 * @return string
	 * @since 6.0.0
	 */
	public function get_cart_key(): string {
		return $this->cart_key;
	}

	/**
	 * Output of cart shotcode.
	 *
	 * @since 2.2.3
	 */
	public static function output() {
		$wte = \wte_functions();
		wte_get_template( 'content-cart.php' );
	}

	/**
	 * @param mixed ...$args
	 *
	 * @return bool
	 */
	public function add( ...$args ): bool {

		if ( $args[0] instanceof WP_REST_Request ) {
			$request = $args[0];
		} else {
			$trip_id = $args[0];
			$attrs   = $args[1] ?? array();
			$request = $args[2] ?? null;
		}

		if ( ! $request instanceof WP_REST_Request ) {
			return false;
		}

		$cart_items = array();

		$this->cart_key = wptravelengine_generate_key( time() );

		$this->set_booking_ref();
		$this->set_payment_type( 'full' );

		if ( ! is_null( $request->get_param( 'booking_id' ) ) ) {
			$booking = wptravelengine_get_booking( $request->get_param( 'booking_id' ) );

			if ( ! $booking || 'publish' !== $booking->post->post_status ) {
				return false;
			}

			$cart_info = $booking->get_cart_info();

			$this->version = $cart_info['version'] ?? '2.0';

			$this->discounts = $cart_info['discounts'] ?? array();

			$order_items = $booking->get_order_items();
			foreach ( $order_items as $order_item ) {
				$cart_items[] = Item::from_order_item( $order_item, $booking, $this );
			}

			$this->set_payment_type( 'due' );
			$this->set_booking_ref( $booking->get_id() );

			WTE()->session->set( "__cart_{$this->cart_key}", array( 'booking_id' => $booking->get_id() ) );
		} else {
			$item       = Item::from_request( $request, $this );
			$attributes = $item->data();
			do_action( 'wte_before_add_to_cart', $item->trip_id, $attributes );

			$cart_items[] = $item;

			$this->set_payment_gateway( (string) wptravelengine_settings()->get( 'default_gateway', 'booking_only' ) );
		}

		foreach ( $cart_items as $item ) {
			$attributes = $item->data();
			do_action( 'wte_before_add_to_cart', $item->trip_id, $attributes );
			do_action( 'wptravelengine_before_add_item_to_cart', $item, $attributes, $this );
			$this->items[ $item->id() ] = $item;
		}

		/**
		 * @since 6.4.0
		 */
		do_action( 'wptravelengine_after_items_added_to_cart', $this->items, $this );

		$this->calculate_totals();

		$this->write();

		/**
		 * TODO: Should deprecate this, this doesn't work if multi cart is implemented.
		 */
		do_action( 'wte_after_add_to_cart', $cart_items[0]->trip_id, $cart_items[0]->data() );

		return true;
	}

	/**
	 * Write changes to cart session.
	 */
	protected function write() {

		if ( $this->static_cart ) {
			return;
		}

		$cart_attributes_session_name = $this->cart_id . '_attributes';
		$items                        = array();

		foreach ( $this->items as $id => $item ) :
			if ( ! $id ) {
				continue;
			}
			$items[ $id ] = $item->data();
		endforeach;

		$cart['id']              = $this->cart_key;
		$cart['cart_items']      = $items;
		$cart['discounts']       = $this->discounts;
		$cart['attributes']      = $this->attributes;
		$cart['payment_type']    = $this->payment_type;
		$cart['payment_gateway'] = $this->payment_gateway ?? 'booking_only';
		$cart['booking_ref']     = $this->booking_ref;
		$cart['tax']             = $this->tax->get_tax_percentage();
		$cart['version']         = $this->version;

		WTE()->session->set_json( $this->cart_id, array_merge( $cart, $this->data() ) );
	}

	/**
	 * Set Totals for current Cart.
	 *
	 * @return void
	 * @updated 6.3.0
	 */
	public function calculate_totals() {
		// $this->reset_totals();
		// $this->fees             = array();
		// $this->deductible_items = array();

		// do_action( 'wptravelengine_before_calculate_totals', $this );

		$totals = $this->totals;

		foreach ( $this->items as $item ) {
			$item->calculate_totals();
			$item_subtotal       = $item->get_totals( 'subtotal' );
			$totals['subtotal'] += $item_subtotal;

			foreach ( $this->get_deductible_items() as $deductible_item ) {
				if ( ! isset( $totals[ "total_{$deductible_item->name}" ] ) ) {
					$totals[ "total_{$deductible_item->name}" ] = 0;
				}
				$totals[ "total_{$deductible_item->name}" ] += $item->get_totals( "total_{$deductible_item->name}" );
			}

			foreach ( $this->get_fees() as $fee ) {
				if ( ! isset( $totals[ "total_{$fee->name}" ] ) ) {
					$totals[ "total_{$fee->name}" ] = 0;
				}
				$totals[ "total_{$fee->name}" ] += $item->get_totals( "total_{$fee->name}" );
			}

			$totals['partial_total'] += $item->get_totals( 'total_partial' );

			$totals['total'] += $item->get_totals( 'total' );

		}

		$totals['due_total'] = $totals['total'] - $totals['partial_total'];

		/**
		 * Round all totals to 2 decimal places.
		 *
		 * @since 6.6.9
		 */

		foreach ( $totals as $key => $value ) {
			$totals[ $key ] = round( $value, 2 );
		}

		$this->totals = apply_filters( 'wptravelengine_cart_calculate_totals', $totals, $this );

		// do_action( 'wptravelengine_after_calculate_totals', $this );
	}

	/**
	 * Reset cart totals to the defaults.
	 */
	protected function reset_totals() {
		$this->totals = $this->default_totals;
	}

	/**
	 * Loads line items to the cart.
	 *
	 * @return void
	 * @since 6.4.0
	 */
	protected function load_line_items( $items ) {
		foreach ( $items as $item_key => $item_data ) {
			$cart_item = new Item( $this, $item_data );
			if ( isset( $item_data['line_items'] ) ) {
				foreach ( $item_data['line_items'] as $line_items ) {
					foreach ( $line_items as $line_item ) {
						$class = $line_item['_class_name'] ?? null;
						if ( class_exists( $class ) ) {
							$cart_item->add_additional_line_items( new $class( $this, $line_item ) );
							$this->items[ $item_key ] = $cart_item;
						}
					}
				}
			}
		}
	}

	/**
	 * Loads deductible items to the cart.
	 *
	 * @return void
	 * @since 6.4.0
	 */
	protected function load_deductible_items( $items ) {
		foreach ( $items as $item ) {
			$class = $item['_class_name'] ?? null;
			if ( class_exists( $class ) ) {
				$this->add_deductible_items( new $class( $this, $item ) );
			}
		}
	}

	/**
	 * Loads fee items to the cart.
	 *
	 * @return void
	 * @since 6.4.0
	 */
	protected function load_fees( $items ) {
		foreach ( $items as $item ) {
			$class = $item['_class_name'] ?? null;
			if ( class_exists( $class ) ) {
				$this->add_fee( new $class( $this, $item ) );
			}
		}
	}

	/**
	 * Loads Cart.
	 *
	 * @return void
	 * @since 6.4.0
	 */
	public function load( $data ) {

		$this->totals = $data['totals'] ?? $this->default_totals;

		if ( ! empty( $data['items'] ) ) {
			$this->load_line_items( $data['items'] );
		}

		if ( ! empty( $data['deductible_items'] ) ) {
			$this->load_deductible_items( $data['deductible_items'] );
		}

		if ( ! empty( $data['fees'] ) ) {
			$this->load_fees( $data['fees'] );
		}

		if ( ! empty( $data['payment_type'] ) ) {
			$this->set_payment_type( $data['payment_type'] );
		}

		$this->set_attributes( $data['attributes'] ?? array() );
	}

	/**
	 * Read items from cart session.
	 *
	 * @return void
	 */
	protected function read() {

		$_cart = WTE()->session->get( $this->cart_id );

		if ( ! $_cart ) {
			$this->reset_totals();

			return;
		}

		$this->load( $_cart );

		$this->set_payment_type( $_cart['payment_type'] ?? 'full_payment' );
		$this->set_payment_gateway( $_cart['payment_gateway'] ?? 'booking_only' );
		$this->booking_ref = $_cart['booking_ref'] ?? null;
		$this->discounts   = $_cart['discounts'] ?? array();
		$this->attributes  = $_cart['attributes'] ?? array();
		$this->cart_key    = $_cart['id'] ?? '';
		$this->version     = $_cart['version'] ?? $this->version;

		$this->calculate_totals();
	}

	/**
	 * Set Payment Type.
	 *
	 * @param $payment_type
	 *
	 * @return void
	 * @since 6.0.0
	 */
	public function set_payment_type( $payment_type ) {
		$this->payment_type = $payment_type;
	}

	/**
	 * Get Payment Type.
	 *
	 * @return void
	 * @since 6.3.0
	 */
	public function set_payment_gateway( $payment_gateway ) {
		$this->payment_gateway = $payment_gateway;
	}

	/**
	 * Set Booking Reference.
	 *
	 * @param int|null $booking_id Currently processing booking ID.
	 *
	 * @return void
	 */
	public function set_booking_ref( ?int $booking_id = null ) {
		$this->booking_ref = $booking_id;
	}

	/**
	 * Set Cart Key.
	 *
	 * @param string|null $cart_key Currently processing cart key.
	 *
	 * @return void
	 */
	public function set_cart_key( ?string $cart_key = null ) {
		$this->cart_key = $cart_key;
	}

	/**
	 * @return void
	 */
	public function update_cart() {
		$this->write();
	}

	/**
	 * Update item quantity.
	 *
	 * @param int   $cart_item_id ID of target item.
	 * @param int   $qty Quantity.
	 * @param array $attr Attributes of item.
	 *
	 * @return boolean
	 */
	public function update( $cart_item_id, $pax, $trip_extras = false, $attr = array() ) {

		if ( is_array( $pax ) ) {

			if ( empty( $pax ) ) {

				return $this->remove( $cart_item_id );

			}
		}

		if ( isset( $this->items[ $cart_item_id ] ) ) {

			if ( is_array( $pax ) ) {

				$trip_id    = $this->items[ $cart_item_id ]['trip_id'];
				$trip_price = $this->items[ $cart_item_id ]['trip_price'];
				$cart_trip  = $this->items[ $cart_item_id ]['trip'];

				$trip_price         = 0;
				$trip_price_partial = 0;

				$this->items[ $cart_item_id ]['trip_price']         = $trip_price;
				$this->items[ $cart_item_id ]['trip_price_partial'] = $trip_price_partial;
			}

			$this->write();

			return true;
		}

		return false;
	}

	/**
	 * Add Discount Values
	 */
	public function add_discount_values( $discount_id, $discount_name, $discount_type, $discount_value ) {

		$this->discounts[ $discount_id ]['name']  = $discount_name;
		$this->discounts[ $discount_id ]['type']  = $discount_type;
		$this->discounts[ $discount_id ]['value'] = $discount_value;

		$coupon = Coupon::by_code( $discount_name );
		if ( $coupon instanceof Coupon ) {
			$this->add_deductible_items(
				new CouponAdjustment(
					$this,
					array(
						'name'            => 'coupon',
						'label'           => sprintf(
							__( 'Coupon: %1$s (%2$s)', 'wp-travel-engine' ),
							$coupon->code(),
							$coupon->type() === 'percentage' ? $coupon->value() . '%' : '',
							// wptravelengine_the_price( $coupon->value(), false, false ),
						),
						'adjustment_type' => $coupon->type(),
						'percentage'      => $coupon->value(),
					)
				)
			);
		}

		$this->write();

		return true;
	}

	/**
	 * Check if cart has discounts.
	 *
	 * @return boolean
	 * @since 5.7.4
	 */
	public function has_discounts(): bool {
		return ! empty( $this->discounts );
	}

	/**
	 * Get discounts
	 */
	public function get_discounts() {
		return $this->discounts;
	}


	/**
	 * Return cart items for legacy support.
	 *
	 * @return array
	 * @since 5.7.4
	 */
	protected function get_formated_items(): array {
		$formated_items = array();
		foreach ( $this->items as $key => $item ) {
			$formated_items[ $key ] = $item->data();
		}

		return $formated_items;
	}

	/**
	 * Get a list of items in cart.
	 *
	 * @return Item[] An array of items in the cart.
	 * @since 5.7.4 Adds $return_item_objects parameter.
	 */
	public function getItems( $return_item_objects = false ) {
		return $return_item_objects ? $this->items : $this->get_formated_items();
	}

	/**
	 * Set items in the cart.
	 *
	 * @param array $items Items to set.
	 *
	 * @return void
	 * @since 6.3.3
	 */
	public function setItems( array $items ) {
		$this->items = $items;
	}

	/**
	 * Empty cart message.
	 *
	 * @return void
	 */
	public function cart_empty_message() {
		$url = get_post_type_archive_link( 'trip' );
		printf(
			esc_html__( 'Your cart is empty please %1$s click here %2$s to add trips.', 'wp-travel-engine' ),
			'<a href="' . esc_url( $url ) . '">',
			'</a>'
		);
	}

	/**
	 * Clear all items in the cart.
	 */
	public function clear() {

		$this->items        = array();
		$this->attributes   = array();
		$this->discounts    = array();
		$this->booking_ref  = null;
		$this->payment_type = 'full';
		$this->tax          = new Tax();
		$this->discount_clear();
		$this->deductible_items = array();
		$this->fees             = array();
		$this->reset_totals();

		$this->write();
	}

	/**
	 * Get all attributes.
	 *
	 * @access public
	 * @return mixed Attributes
	 * @since 3.0.5
	 */
	public function get_attributes() {
		return $this->attributes;
	}

	/**
	 * Set all attributes.
	 *
	 * @param mixed $attributes Atributes
	 *
	 * @return void
	 * @since 3.0.5
	 * @access public
	 */
	public function set_attributes( $attributes ) {
		$this->attributes = $attributes;
		// $this->write();
	}

	/**
	 * Get a single attribute value.
	 *
	 * @param string $key Attribute key.
	 *
	 * @return mixed|string Attribute value.
	 */
	public function get_attribute( $key ) {
		if ( ! isset( $this->attributes[ $key ] ) ) {
			return false;
		}

		return $this->attributes[ $key ];
	}

	/**
	 * Set a single attribute value.
	 *
	 * @param string $key Attribute key.
	 * @param mixed  $value Attribute value.
	 *
	 * @return void
	 */
	public function set_attribute( $key, $value ) {
		$this->attributes[ $key ] = $value;
		$this->write();
	}


	/**
	 * Read cart items while load.
	 *
	 * @return void
	 */
	public function read_cart_onload() {
		if ( ! ( defined( 'DOING_CRON' ) && DOING_CRON ) ) {
			$this->read();
		}
	}

	/**
	 * Remove item from cart.
	 *
	 * @param integer $id ID of targeted item.
	 */
	public function remove( $id ) {

		unset( $this->items[ $id ] );

		unset( $this->attributes[ $id ] );

		$this->write();
	}

	/**
	 * Apply tax to cart totals.
	 *
	 * @param float $totals Cart totals.
	 *
	 * @return float
	 * @since 5.7.4
	 */
	protected function apply_tax( float $totals ): float {
		if ( $this->tax->is_taxable() && $this->tax->is_exclusive() ) {
			$totals = $totals + $this->tax->get_tax_amount( (float) $totals );
		}

		return $totals;
	}

	/**
	 * @param float $total
	 *
	 * @return float
	 */
	protected function calculate_discount( float $total ) {
		$totals = 0;
		if ( ! empty( $this->discounts ) ) {
			foreach ( $this->discounts as $discount ) :
				$discount_value = $discount['value'];
				switch ( $discount['type'] ) {
					case 'fixed':
						if ( $total > $totals ) {
							$totals += $discount_value;
						}
						break;
					case 'percentage':
						$discount_amount = ( $total * $discount_value ) / 100;
						if ( $total > $totals ) {
							$totals += $discount_amount;
						}
						break;
				}
				break; // TODO: Should look in case of multiple discount feature applied.
			endforeach;
		}

		return $totals;
	}

	/**
	 * @param $totals
	 *
	 * @return float
	 * @since 5.7.4
	 */
	public function apply_discounts( $totals ) {
		return $totals - $this->calculate_discount( $totals );
	}

	/**
	 * @return float
	 * @since 5.7.4
	 */
	public function get_extra_services_totals(): float {
		return (float) array_reduce(
			$this->items,
			function ( $carry, $item ) {
				return $carry + $item->trip_extras_totals();
			},
			0
		);
	}

	/**
	 * Get the total values of the shopping cart.
	 *
	 * @return array An array containing the following total values.
	 * @since 6.0.0
	 */
	public function get_totals(): array {
		$totals = empty( $this->totals ) ? $this->default_totals : $this->totals;

		return apply_filters( 'wptravelengine_cart_totals', $totals, $this );
	}

	/**
	 * @return float
	 * @since 5.7.4
	 */
	public function get_subtotal(): float {
		return apply_filters( 'wptravelengine_cart_sub_total', $this->totals['subtotal'] );
	}

	/**
	 * @return float
	 * @since 5.7.4
	 */
	public function get_discount_amount(): float {
		return apply_filters( 'wptravelengine_cart_discount_amount', round( $this->calculate_discount( $this->get_subtotal() ), 2 ) );
	}

	/**
	 * Get Discount Total.
	 *
	 * @return float
	 * @since 6.0.0
	 */
	public function get_discount_total(): float {
		return apply_filters( 'wptravelengine_cart_discount_total', $this->totals['discount_total'] );
	}

	/**
	 * @return float
	 * @since 5.7.4
	 */
	public function get_total_partial(): float {
		return $this->totals['partial_total'];
	}

	/**
	 * @return float
	 * @since 5.7.4
	 */
	public function get_due_total(): float {
		return $this->totals['due_total'];
	}

	/**
	 * Get the cart total value.
	 *
	 * @param bool $with_discount
	 * @param bool $with_tax
	 *
	 * @return float
	 * @since 6.0.0
	 */
	public function get_cart_total(): float {
		return $this->totals['total'] ?? 0;
	}

	/**
	 * Get the total values of the shopping cart.
	 *
	 * @param bool $with_discount (Optional) Whether to include discounts in the total. Default is true.
	 *
	 * @return array|float The total value of the cart, or an array if the method call is deprecated.
	 * @deprecated 6.0.0 Use WPTravelEngine\Core\Cart\Cart::get_totals() instead.
	 */
	public function get_total( bool $with_discount = true ): array {
		_deprecated_function( __METHOD__, '6.0.0', 'WPTravelEngine\Core\Cart\Cart::get_totals' );

		/**
		 * Represents the total value of the shopping cart.
		 */
		$cart_total = $this->totals['total'];

		/**
		 * Represents the amount of discount applied to a purchase.
		 */
		$discount_amount = $this->totals['discount_total'];

		/**
		 * Calculate the total cost of trip extras.
		 */
		$trip_extras_total = $this->get_extra_services_totals();

		$total_with_discount = $cart_total - $discount_amount;
		/**
		 * The total variable represents the sum of all values.
		 */
		$total = $this->totals['total'];

		/**
		 * Represents the partials of the cart total.
		 *
		 * @var array $cart_total_partial An array of partial values that make up the cart total.
		 */
		$cart_total_partial = $total_partial = $this->totals['partial_total'];

		/**
		 * Represents the amount of tax to be applied.
		 *
		 * @var float $tax_amount The value of the tax amount.
		 */
		$tax_amount = $this->totals['total_tax'];

		$sub_total = $this->totals['subtotal'];

		$cart_totals = compact( 'cart_total', 'sub_total', 'discount_amount', 'total', 'trip_extras_total', 'cart_total_partial', 'total_partial', 'tax_amount' );

		return apply_filters( 'wp_travel_engine_cart_get_total_fields', $cart_totals );
	}

	/**
	 * Return cart trip id.
	 *
	 * @return  string[]  trip id.
	 *
	 * @since   2.2.6
	 */
	public function get_cart_trip_ids(): array {
		return array_column( $this->getItems(), 'trip_id' );
	}

	/**
	 * Return Coupon Name.
	 *
	 * @return  String Singular Coupon Name id.
	 *
	 * @since
	 */
	public function get_cart_coupon_name() {
		$coupon_array  = array_column( $this->discounts, 'name' );
		$coupon_return = isset( $coupon_array[0] ) && ! empty( $coupon_array[0] ) ? esc_attr( $coupon_array[0] ) : '';

		return $coupon_return;
	}

	public function get_cart_coupon_type() {
		$coupon_array  = array_column( $this->discounts, 'type' );
		$coupon_return = isset( $coupon_array[0] ) && ! empty( $coupon_array[0] ) ? esc_attr( $coupon_array[0] ) : '';

		return $coupon_return;
	}

	public function get_cart_coupon_value() {
		$coupon_array  = array_column( $this->discounts, 'value' );
		$coupon_return = isset( $coupon_array[0] ) && ! empty( $coupon_array[0] ) ? esc_attr( $coupon_array[0] ) : '';

		return $coupon_return;
	}

	public function discount_clear() {
		$this->discounts        = array();
		$this->deductible_items = array();
		$this->write();
	}

	/**
	 * Get the tax object.
	 *
	 * @return Tax The tax value.
	 */
	public function tax(): Tax {
		return $this->tax;
	}

	/**
	 * Provides a payment type to distinguish between initial checkout or checkout for remaining amount.
	 *
	 * @return string
	 * @since 6.0.0
	 */
	public function get_payment_type(): string {
		return $this->payment_type;
	}

	/**
	 * Holds booking reference in case of due payment.
	 *
	 * @return mixed|null
	 * @since 6.0.0
	 */
	public function get_booking_ref() {
		return $this->booking_ref;
	}

	/**
	 * Are current cart items are loaded from booking?
	 *
	 * @return bool
	 * @since 6.0.0
	 */
	public function is_loaded_from_booking(): bool {
		return ! is_null( $this->booking_ref );
	}

	/**
	 * @return array
	 * @since 6.4.0
	 */
	public function data(): array {
		$data = array(
			'currency'         => wptravelengine_settings()->get( 'currency_code', 'USD' ),
			'totals'           => $this->get_totals(),
			'deductible_items' => array_map(
				function ( $item ) {
					return array_merge(
						$item->data(),
						array( '_class_name' => get_class( $item ) )
					);
				},
				$this->get_deductible_items()
			),
			'fees'             => array_map(
				function ( $item ) {
					return array_merge(
						$item->data(),
						array( '_class_name' => get_class( $item ) )
					);
				},
				$this->get_fees()
			),
			'version'          => $this->version,
		);
		foreach ( $this->getItems( true ) as $item ) {
			$data['items'][] = $item->data() ?? array();
		}

		return $data;
	}

	/**
	 * @since 6.4.0
	 */
	public function __clone() {
		$this->static_cart = true;
	}
}
