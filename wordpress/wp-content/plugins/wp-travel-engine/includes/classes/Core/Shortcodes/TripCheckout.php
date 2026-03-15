<?php
/**
 * Trip Checkout Shortcode.
 *
 * @since 6.3.0
 */

namespace WPTravelEngine\Core\Shortcodes;

use WPTravelEngine\Abstracts\Shortcode;
use WPTravelEngine\Assets;
use WPTravelEngine\Core\Coupons;
use WPTravelEngine\Core\Models\Post\Booking;
use WPTravelEngine\Core\Models\Post\Payment;

class TripCheckout extends Shortcode {
	const TAG = 'WPTRAVELENGINE_CHECKOUT';

	protected function default_attributes(): array {
		return array(
			'content'          => array(), // Comma-separated list of sections to show
			'title'            => 'show', // Show/hide title,
			'content-only'     => 'no', // Show/hide title,
			'show_coupon_form' => 'show',
		);
	}

	public function output( $atts ): string {
		global $wte_cart;
		if ( isset( $atts['content'] ) ) {
			Assets::instance()
					->enqueue_script( 'trip-checkout' )
					->enqueue_style( 'trip-checkout' )
					->enqueue_script( 'parsley' )
					->enqueue_script( 'wptravelengine-validatejs' )
					->dequeue_script( 'wp-travel-engine' )
					->dequeue_style( 'wp-travel-engine' );
			ob_start();
			printf( '<div %s>', esc_attr( "data-{$atts['content']}" ) );
			do_action(
				"checkout_template_parts_{$atts['content']}",
				array(
					'show_title'       => $atts['title'] === 'show',
					'content_only'     => $atts['content-only'] === 'yes',
					'show_coupon_form' => $atts['show_coupon_form'] === 'show' && Coupons::is_coupon_available() && $wte_cart->get_payment_type() !== 'due',
				)
			);
			echo '</div>';

			return ob_get_clean();
		}

		// Enqueue necessary assets
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
				$booking    = Booking::make( $booking_ref );
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
			// Parse the 'content' attribute or use defaults
			$content_to_show = ! empty( $atts['content'] )
				? array_map( 'trim', explode( ',', $atts['content'] ) )
				: array();
			// Set default attributes and form sections
			$attributes    = array( 'checkout-steps' => 'hide' );
			$form_sections = array(
				'travellers'      => 'content-travellers-details',
				'emergency'       => 'content-emergency-details',
				'billing'         => 'content-billing-details',
				'additional_note' => 'content-checkout-note',
				'payment'         => 'content-payments',
			);
			if ( ! empty( $content_to_show ) ) {
				// Adjust sections based on provided 'content' attribute
				$form_sections              = in_array( 'form', $content_to_show, true ) ? $form_sections : array();
				$attributes['cart-summary'] = in_array( 'cart-summary', $content_to_show, true ) ? 'show' : 'hide';
				$attributes['tour-details'] = in_array( 'tour-details', $content_to_show, true ) ? 'show' : 'hide';
			} else {
				// Default behavior when 'content' is empty
				$attributes['cart-summary'] = 'show';
				$attributes['tour-details'] = 'show';
			}
			// Render the template with attributes and sections
			wptravelengine_get_template(
				'template-checkout/content-checkout.php',
				wptravelengine_get_checkout_template_args(
					array(
						'form_sections' => $form_sections,
						'attributes'    => $attributes,
					)
				)
			);
		} else {
			// No cart items available
			echo __(
				'Sorry, you may not have selected the number of travellers for this trip. Please select the number of travellers and confirm your booking. Thank you.',
				'wp-travel-engine'
			);
		}

		return ob_get_clean();
	}
}
