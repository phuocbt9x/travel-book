<?php

namespace WPTravelEngine\Core\Controllers;

use WPTravelEngine\Core\Cart\Item;
use WPTravelEngine\Core\Coupon;
use WPTravelEngine\Core\Tax;

class MiniCart {
	/**
	 * @var mixed
	 */
	protected $checkout;

	public function __construct( Checkout $checkout ) {
		$this->checkout = $checkout;
	}

	public function render() {
		do_action( 'wte_booking_before_minicart' );
		wte_get_template(
			'checkout/mini-cart.php',
			array(
				'checkout'  => $this->checkout,
				'mini_cart' => $this,
			)
		);
		do_action( 'wte_booking_after_minicart' );
	}

	public function title() {
		return apply_filters( 'wptravelengine_mini_cart_title', __( 'Booking Summary', 'wp-travel-engine' ) );
	}

	public function trip_date_time( $cart_item ) {
		$date_format = get_option( 'date_format', 'F j, Y' );
		$trip_date   = $cart_item->trip_date;
		$trip_time   = $cart_item->trip_time;
		if ( ! empty( $trip_time ) ) :
			$trip_date    = $cart_item->trip_time;
			$date_format .= ' \a\t ' . get_option( 'time_format', 'g:i a' );
		endif;

		return apply_filters(
			'wptravelengine_mini_cart_trip_date_time',
			wp_date( $date_format, strtotime( $trip_date ), new \DateTimeZone( 'utc' ) ),
			$cart_item,
			$trip_date,
		);
	}

	public function trip_package_name( $cart_item ) {
		$package = get_post( $cart_item->price_key );
		if ( $package ) :
			printf(
				'<span class="label">%1$s</span><span class="value">%2$s</span>',
				esc_html__( 'Package:', 'wp-travel-engine' ),
				esc_html( $package->post_title )
			);
		endif;
	}

	public function trip_pax_details( Item $cart_item ) {
		$pax_details = '';
		foreach ( $cart_item->pax as $pax_label => $pax ) :
			if ( (int) $pax <= 0 ) {
				continue;
			}

			$pax_label_display = '';
			if ( $cart_item->multi_pricing_used ?? false ) :
				$pax_label_display = wte_get_pricing_label_by_key( $cart_item->trip_id, $pax_label );
			endif;

			if ( $cart_item->category_info[ $pax_label ] ?? false ) :
				$pricing_category  = $cart_item->category_info[ $pax_label ];
				$pax_label_display = $pricing_category['label'];
			endif;

			$pax_details .= sprintf(
				'<tr>
					<td>%1$s %2$s</td>
					<td>%3$s</td>
				</tr>',
				$pax_label_display,
				(int) $pax,
				wptravelengine_the_price( $cart_item->pax_cost[ $pax_label ], false )
			);
		endforeach;

		$content = apply_filters( 'wptravelengine_mini_cart_trip_pax_details', $pax_details, $cart_item );
		echo wp_kses(
			$content,
			array(
				'table'  => array(),
				'tr'     => array(),
				'td'     => array(),
				'span'   => array(
					'class' => array(),
				),
				'del'    => array(),
				'em'     => array(),
				'strong' => array(),
				'b'      => array(),
			)
		);
	}

	public function tax_details( $cart_item, Tax $tax ) {
		global $wte_cart;

		$content = sprintf(
			'<tr class="wte-tax-calculation-tr"><td><span>%s</span></td><td><b>+&nbsp;%s</b></td></tr>',
			$tax->get_tax_label(),
			wptravelengine_the_price( $wte_cart->get_totals()['total_tax'], false )
		);

		$content = apply_filters( 'wptravelengine_mini_cart_tax_details', $content, $cart_item, $tax );

		echo wp_kses( $content, 'post' );
	}

	public function extra_services( $cart_item ) {
		$settings       = get_option( 'wp_travel_engine_settings' );
		$title          = ! empty( $settings['extra_service_title'] ) ? $settings['extra_service_title'] : __( 'Extra Services', 'wp-travel-engine' );
		$title          = apply_filters( 'wptravelengine_mini_cart_services_title', $title );
		$extra_services = '';
		if ( isset( $cart_item->trip_extras[0] ) ) :
			$extra_services .= sprintf(
				'<tr class="wte-booked-package-name">
					<td colspan="2">%1$s</td>
				</tr>',
				$title
			);
			foreach ( $cart_item->trip_extras as $trip_extra ) :
				$extra_services .= sprintf(
					'<tr>
						<td>
							<span>%1$s x %2$s</span>
						</td>
						<td>
							<b>%3$s</b>
						</td>
					</tr>',
					esc_html( $trip_extra['qty'] ),
					esc_html( $trip_extra['extra_service'] ),
					wptravelengine_the_price( (int) $trip_extra['qty'] * (float) $trip_extra['price'], false )
				);
			endforeach;
		endif;

		$content = apply_filters( 'wte_mini_cart_extra_services', $extra_services, $cart_item );

		echo wp_kses(
			$content,
			array(
				'table'  => array(),
				'tr'     => array(
					'class' => array( 'wte-booked-package-name' ),
				),
				'td'     => array(
					'colspan' => array( 2 ),
				),
				'span'   => array(
					'class' => array(),
				),
				'del'    => array(),
				'em'     => array(),
				'strong' => array(),
				'b'      => array(),
			)
		);
	}

	protected function discount_label( $discount ) {
		$discount = (object) $discount;
		$value    = $discount->type === 'percentage' ? $discount->value . '%' : wptravelengine_the_price( $discount->value, false, false );

		return apply_filters(
			'wptravelengine_mini_cart_discount_label',
			sprintf(
				__( 'Coupon Discount : %s', 'wp-travel-engine' ),
				$discount->name . "($value)",
			),
			$discount
		);
	}

	protected function discount_value( $discount ) {
		global $wte_cart;

		return apply_filters(
			'wptravelengine_mini_cart_discount_value',
			wptravelengine_the_price( $wte_cart->get_totals()['discount_total'], false ),
			$discount
		);
	}

	public function discount_details() {
		global $wte_cart;
		$discounts = $wte_cart->get_discounts();

		$content = '';
		foreach ( $discounts as $discount ) {

			$content .= sprintf(
				'<tr class="wte-coupons-discount-calculation-tr">
							<td class="wte-coupons-discount-calculation-td">%s</td>
							<td class="wte-coupons-discount-calculation-td"><b>-&nbsp;%s</b></td>
						</tr>',
				$this->discount_label( $discount ),
				$this->discount_value( $discount )
			);
		}

		$content = apply_filters( 'wptravelengine_mini_cart_discounts', $content, $discounts );
		echo wp_kses( $content, 'post' );
	}

	public function tax_summary() {
		global $wte_cart;
		if ( ! $wte_cart->tax()->is_taxable() || ! $wte_cart->tax()->is_inclusive() ) {
			return;
		}
		$settings       = get_option( 'wp_travel_engine_settings', array() );
		$tax_percentage = $settings['tax_percentage'];

		$content = apply_filters(
			'wptravelengine_mini_cart_tax_summary',
			sprintf(
				'<span class="wpte-inclusive-tax-label">%s</span>',
				// translators: %s: Tax percentage.
				sprintf( __( '(%s%% Incl. tax)', 'wp-travel-engine' ), $tax_percentage )
			),
			$tax_percentage,
		);

		echo wp_kses( $content, array( 'span' => array( 'class' => true ) ) );
	}
}
