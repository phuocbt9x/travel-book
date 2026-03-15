<?php
/**
 * Add Cart Items Filter
 *
 * @since 6.3.0
 */

namespace WPTravelEngine\Filters;

use WPTravelEngine\Core\Cart\Items\ExtraService;
use WPTravelEngine\Core\Cart\Adjustments\TaxAdjustment;
use WPTravelEngine\Core\Cart\Cart;
use WPTravelEngine\Core\Cart\Adjustments\CouponAdjustment;
use WPTravelEngine\Core\Cart\Item;
use WPTravelEngine\Core\Coupon;
use WPTravelEngine\Core\Models\Post\Trip;
use WPTravelEngine\Core\Models\Post\Booking;
use WPTravelEngine\Helpers\CartInfoParser;
use WPTravelEngine\Abstracts\CartAdjustment;
use WPTravelEngine\Core\Cart\Item as CartItem;
use WPTravelEngine\Helpers\BookedItem;
use WPTravelEngine\Core\Models\Post\Payment;
class AddCartItems {

	/**
	 * Initializes hooks for template inclusion and excerpt modification.
	 */
	public function hooks() {
		add_action( 'wptravelengine_before_add_item_to_cart', array( $this, 'add_extra_services' ), 10, 3 );
		add_action( 'wptravelengine_after_items_added_to_cart', array( $this, 'add_tax' ), 10, 2 );
		add_action( 'wptravelengine_after_items_added_to_cart', array( $this, 'add_deductible_items' ), 5, 3 );
		add_filter( 'wptravelengine_cart_calculate_totals', array( $this, 'modify_totals' ), 10, 2 );
	}

	/**
	 * Modify totals.
	 *
	 * @param array $totals
	 * @param Cart  $cart
	 * @since 6.4.0
	 * @return array
	 */
	public function modify_totals( $totals, $cart ) {
		$booking_ref = $cart->get_booking_ref();

		if ( ! $cart->is_curr_cart() && $booking_ref !== null ) {
			$booking   = Booking::make( $booking_ref );
			$cart_info = new CartInfoParser( $booking->get_cart_info() );
			$totals    = $cart_info->get_totals();

			$payments = $booking->get_payment_detail();
			if ( is_array( $payments ) && ! empty( $payments ) ) {
				$payment_gateways = array_map(
					function ( $payment ) {
						return Payment::make( $payment )->get_meta( 'payment_gateway' );
					},
					$payments
				);

				$offline_gateways = array( 'booking_only' );

				$has_offline_payment = ! empty( array_intersect( $payment_gateways, $offline_gateways ) );
				$has_online_payment  = count( array_diff( $payment_gateways, $offline_gateways ) ) > 0;

				if ( $has_offline_payment && ! $has_online_payment ) {
					$total_paid = $booking->get_meta( 'total_paid_amount' );
					$total_due  = $booking->get_meta( 'total_due_amount' );

					if ( empty( $total_paid ) || empty( $total_due ) ) {
						$totals['partial_total'] = 0;
						$totals['due_total']     = $totals['total'];
					}
				}
			}

			$cart->set_payment_type( 'due' );
		}
		return $totals;
	}

	/**
	 * Add tax as a fee to the cart.
	 */
	public function add_tax( array $items, Cart $cart ) {

		if ( $cart->get_booking_ref() !== null ) {
			$booking_id = $cart->get_booking_ref();
			$booking    = Booking::make( $booking_id );
			$cart_info  = new CartInfoParser( $booking->get_cart_info() );
			$fees       = $cart_info->get_fees();

			if ( ! empty( $fees ) ) {
				foreach ( $fees as $fee ) {
					if ( 'tax' === ( $fee['name'] ?? '' ) && class_exists( $fee['_class_name'] ) ) {
						$cart->add_fee(
							new $fee['_class_name']( $cart, $fee )
						);
					}
				}
			}
		} elseif ( $cart->tax()->is_taxable() && $cart->tax()->is_exclusive() ) {
			$cart->add_fee(
				new TaxAdjustment( $cart, array( 'order' => 10 ) )
			);
		}
	}


	/**
	 * Add deductable items to the cart.
	 *
	 * @param array $items
	 * @param Cart  $cart
	 * @since 6.4.0
	 * @return void
	 */
	public function add_deductible_items( array $items, Cart $cart ) {
		if ( $cart->get_booking_ref() !== null ) {
			$booking_id       = $cart->get_booking_ref();
			$booking          = Booking::make( $booking_id );
			$cart_info        = new CartInfoParser( $booking->get_cart_info() );
			$deductible_items = $cart_info->get_deductible_items();

			foreach ( $deductible_items as $discount ) {
				if ( class_exists( $discount['_class_name'] ) ) {
					$cart->add_deductible_items( new $discount['_class_name']( $cart, $discount ) );
				}
			}
		}
	}


	/**
	 * Add cart items from order items.
	 *
	 * @param Cart $cart
	 *
	 * @return void
	 * @since 6.4.0
	 */
	public function add_extra_services( Item $item, $cart_attributes, $cart ) {

		if ( ! ( $trip_extras = $item->subtotal_reservations['extraServices'] ?? null ) ) {
			return;
		}

		$trip           = new Trip( $item->trip_id );
		$extra_services = $trip->get_services();
		if ( $cart->get_booking_ref() !== null ) {
			$booking_id           = $cart->get_booking_ref();
			$booking              = Booking::make( $booking_id );
			$cart_info            = new CartInfoParser( $booking->get_cart_info() );
			$extra_services_items = $cart_info->get_item()->get_line_items()['extra_service'];
		}

		if ( isset( $extra_services_items ) && ! empty( $extra_services_items ) ) {
			foreach ( $extra_services_items as $trip_extra ) {
				$item->add_additional_line_items(
					new ExtraService(
						$cart,
						array(
							'label'    => $trip_extra['label'],
							'quantity' => $trip_extra['quantity'],
							'price'    => $trip_extra['price'],
							'total'    => $trip_extra['total'],
						)
					)
				);
			}
		} else {
			foreach ( $trip_extras as $trip_extra ) {

				foreach ( $extra_services as $service ) {
					$key = array_search( $trip_extra['id'], array_column( $service['options'], 'key' ) );
					if ( $key !== false ) {
						$label = (string) ( $service['options'][ $key ]['label'] ?? ( $service['title'] ?? '' ) );
						$price = (float) ( $service['options'][ $key ]['price'] ?? 0.0 );
						break;
					}
				}

				if ( ! isset( $label ) || ! isset( $price ) ) {
					continue;
				}

				$item->add_additional_line_items(
					new ExtraService(
						$cart,
						array(
							'label'    => $label,
							'quantity' => $trip_extra['quantity'] ?? 0,
							'price'    => $price,
						)
					)
				);
			}
		}
	}
}
