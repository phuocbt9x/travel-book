<?php
/**
 * Checkout V2 Shortcode.
 *
 * @since 6.3.0
 */

namespace WPTravelEngine\Core\Shortcodes;

use WPTravelEngine\Assets;
use WPTravelEngine\Core\Coupons;
use WPTravelEngine\Core\Models\Post\Coupons as CouponsModel;
use WPTravelEngine\Core\Models\Post\Booking;
use WPTravelEngine\Core\Models\Post\Payment;

class CheckoutV2 extends Checkout {

	/**
	 * Default attributes for the shortcode.
	 *
	 * @return array
	 */
	protected function default_attributes(): array {
		global $wte_cart;

		$wptravelengine_settings = get_option( 'wp_travel_engine_settings', array() );
		$checkout_page_template  = $wptravelengine_settings['checkout_page_template'] ?? '1.0';
		$display_header_footer   = $wptravelengine_settings['display_header_footer'] ?? 'no';
		$show_travellers_info    = $wptravelengine_settings['display_travellers_info'] ?? 'yes';
		$show_emergency_contact  = $wptravelengine_settings['display_emergency_contact'] ?? '';
		$traveller_details_form  = $wptravelengine_settings['traveller_emergency_details_form'] ?? 'on_checkout';
		$display_billing_details = $wptravelengine_settings['display_billing_details'] ?? 'yes';
		$show_additional_note    = $wptravelengine_settings['show_additional_note'] ?? 'yes';
		$show_coupon_form        = $wptravelengine_settings['show_discount'] ?? 'yes';
		$is_payment_due          = $wte_cart->get_booking_ref() ?? false;
		return array(
			'version'               => $checkout_page_template,
			'header'                => $checkout_page_template == '2.0' && $display_header_footer == 'yes' ? 'default' : 'none',
			'footer'                => $checkout_page_template == '2.0' && $display_header_footer == 'yes' ? 'default' : 'none',
			'checkout-steps'        => 'show',
			'tour-details'          => 'show',
			'tour-details-title'    => 'show',
			'cart-summary'          => 'show',
			'cart-summary-title'    => 'show',
			'lead-travellers'       => $is_payment_due ? 'hide' : ( $show_travellers_info == 'yes' && $traveller_details_form == 'on_checkout' ? 'show' : 'hide' ),
			'lead-travellers-title' => 'show',
			'travellers'            => $is_payment_due ? 'hide' : ( $show_travellers_info == 'yes' && $traveller_details_form == 'on_checkout' ? 'show' : 'hide' ),
			'travellers-title'      => 'show',
			'emergency'             => $is_payment_due ? 'hide' : ( $show_emergency_contact == 'yes' && $traveller_details_form == 'on_checkout' ? 'show' : 'hide' ),
			'emergency-title'       => 'show',
			'billing'               => $display_billing_details == 'yes' ? 'show' : 'hide',
			'billing-title'         => 'show',
			'additional_note'       => $show_additional_note == 'yes' ? 'show' : 'hide',
			'additional-note-title' => 'show',
			'payment'               => 'show',
			'payment-title'         => 'show',
			'coupon_form'           => $show_coupon_form == 'yes' && Coupons::is_coupon_available() && 'due' !== $wte_cart->get_payment_type() ? 'show' : 'hide',
			'footer_copyright'      => $wptravelengine_settings['footer_copyright'] ?? '',
		);
	}

	/**
	 * Place order form shortcode callback function.
	 *
	 * @param array $atts The shortcode attributes.
	 *
	 * @return string
	 * @since 1.0
	 */
	public function output( $atts ): string {
		global $wte_cart;

		$wptravelengine_settings   = get_option( 'wp_travel_engine_settings', array() );
		$generate_user_account     = $wptravelengine_settings['generate_user_account'] ?? 'yes';
		$require_login_to_checkout = $wptravelengine_settings['enable_checkout_customer_registration'] ?? 'no';

		if ( 'no' === $generate_user_account && 'yes' === $require_login_to_checkout && ! is_user_logged_in() ) {
			ob_start();

			Assets::instance()
			->enqueue_style( 'my-account' )
			->enqueue_script( 'my-account' );

			wte_get_template( 'account/form-login.php' );

			return ob_get_clean();
		}

		$checkout_page_template = $wptravelengine_settings['checkout_page_template'] ?? '1.0';
		// Simplified conditional check for outputting based on version
		if ( $atts['version'] === '1.0' || ( $atts['version'] !== '2.0' && $checkout_page_template == '1.0' ) ) {
			return parent::output( $atts );
		}

		Assets::instance()
				->enqueue_script( 'trip-checkout' )
				->enqueue_style( 'trip-checkout' )
				->enqueue_script( 'parsley' )
				->enqueue_script( 'wptravelengine-validatejs' )
				->dequeue_script( 'wp-travel-engine' )
				->dequeue_style( 'wp-travel-engine' );

		ob_start();

		$cart_items = $wte_cart->getItems();

		if ( ! empty( $cart_items ) ) {
			$booking_ref = $wte_cart->get_booking_ref();
			if ( $booking_ref ) {
				$booking = wptravelengine_get_booking( $booking_ref );

				if ( ! $booking ) {
					echo __( 'This booking reference is invalid.', 'wp-travel-engine' );
					return ob_get_clean();
				}

				$due_amount = $booking->get_total_due_amount();

				if ( $wte_cart->is_curr_cart( '<' ) ) {
					// Check for customized reservation with full payment.
					$is_customized_reservation = $booking->get_meta( '_user_edited' );
					if ( $is_customized_reservation ) {
						$payments       = $booking->get_payment_detail();
						$payment_amount = 0;

						foreach ( $payments as $payment ) {
							$payment_id      = Payment::make( $payment );
							$payment_amount += $payment_id->get_amount();
						}

						$cart_version = $wte_cart->version;

						if ( $payment_amount > $due_amount && $cart_version < '4.0' ) {
							echo __(
								'Thank you! Your payment has been received in full. No further action is required.',
								'wp-travel-engine'
							);
							return ob_get_clean();
						}
					}
				} elseif ( round( $due_amount, 2 ) <= 0 ) {
					echo __(
						'Thank you! Your payment has been received in full. No further action is required.',
						'wp-travel-engine'
					);
					return ob_get_clean();
				}
			}
			$template_args = array();

			$atts = apply_filters( 'wptravelengine_checkoutv2_shortcode_attributes', $atts, $this );

			$form_sections = array(
				'travellers'      => 'content-travellers-details',
				'emergency'       => 'content-emergency-details',
				'billing'         => 'content-billing-details',
				'additional_note' => 'content-checkout-note',
				'payment'         => 'content-payments',
			);

			$template_args['form_sections'] = apply_filters( 'wptravelengine_checkoutv2_form_templates', $form_sections );
			unset( $form_sections );

			if ( is_array( $template_args['form_sections'] ) ) {
				foreach ( array_keys( $template_args['form_sections'] ) as $section ) {
					if ( 'hide' === ( $atts[ $section ] ?? 'show' ) ) {
						unset( $template_args['form_sections'][ $section ] );
					}
				}
			}
			$show_coupon_form = $wptravelengine_settings['show_discount'] ?? 'yes';
			if ( 'yes' === $show_coupon_form ) {
				$this->maybe_apply_discount( $wte_cart );
			}

			wptravelengine_get_template(
				'template-checkout/content-checkout.php',
				wptravelengine_get_checkout_template_args(
					array(
						'attributes'     => $atts,
						'deposit_amount' => $wte_cart->get_totals()['partial_total'],
						'due_amount'     => $wte_cart->get_totals()['due_total'],
					)
				)
			);

		} else {
			echo __(
				'Sorry, you may not have selected the number of travellers for this trip. Please select number of travellers and confirm your booking. Thank you.',
				'wp-travel-engine'
			);
		}

		return ob_get_clean();
	}

	/**
	 * Apply discount to the cart from the URL.
	 *
	 * @since 6.5.2
	 * @param \WPTravelEngine\Core\Cart\Cart $wte_cart The cart object.
	 */
	protected function maybe_apply_discount( $wte_cart ) {
		if ( ! isset( $_GET['discount'] ) || $wte_cart->has_discounts() ) {
			return;
		}

		$code     = $_GET['discount'];
		$trip_ids = $wte_cart->get_cart_trip_ids();
		if ( empty( $trip_ids ) ) {
			return;
		}

		$coupon = CouponsModel::by_code( $code );
		if ( ! $coupon || $coupon->is_valid() ) {
			return;
		}

		$trip_id = is_array( $trip_ids ) ? array_shift( $trip_ids ) : 0;
		if ( ! $trip_id || $coupon->is_valid( $trip_id ) ) {
			return;
		}

		$limit = $coupon->get_coupon_limit_number();
		if ( (bool) $limit && ( (int) $limit <= $coupon->get_coupon_usage_count() ) ) {
			return;
		}

		$type  = $coupon->get_coupon_type();
		$value = $coupon->get_coupon_value();

		$wte_cart->add_discount_values( 'coupon', $code, $type, $value );
	}
}
