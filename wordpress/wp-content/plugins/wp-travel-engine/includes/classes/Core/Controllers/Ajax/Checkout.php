<?php
/**
 * Add to cart controller.
 *
 * @package WPTravelEngine/Core/Controllers
 * @since 6.0.0
 */

namespace WPTravelEngine\Core\Controllers\Ajax;

use Stripe\Exception\ApiErrorException;
use WP_Error;
use WPTravelEngine\Abstracts\AjaxController;
use WPTravelEngine\Helpers\Functions;
use WPTravelEngine\Utilities\RequestParser;
use WTE_PayU_Money_Admin;

/**
 * Handles cart related requests.
 */
class Checkout extends AjaxController {
	const NONCE_KEY    = '_nonce';
	const NONCE_ACTION = 'wp_xhr';
	const ACTION       = 'wptravelengine_page_checkout';

	/**
	 * @inheritDoc
	 */
	protected function process_request() {

		$cart_action = $this->request->get_param( '_action' );

		do_action( "wptravelengine_page_checkout_{$cart_action}", $this->request );

		switch ( $cart_action ) {
			case 'update_cart':
				wptravelengine_update_cart(
					array(
						'payment_type'    => $this->request->get_param( 'payment_mode' ),
						'payment_gateway' => $this->request->get_param( 'payment_method' ),
					)
				);

				if ( $formData = $this->request->get_param( 'formData' ) ) {
					$request = new RequestParser( 'POST' );
					foreach ( $formData as $key => $value ) {
						$request->set_param( $key, $value );
					}
					wptravelengine_cache_checkout_form_data( $request );
				}

				ob_start();
				do_action( 'wptravelengine_checkout_form_submit_button' );
				$submit_button = ob_get_clean();

				ob_start();
				do_action( 'checkout_template_parts_cart-summary' );
				$cart_summary = ob_get_clean();

				ob_start();
				do_action( 'wptravelengine_checkout_payment_modes' );
				$payment_modes = ob_get_clean();

				/**
				 * @since 6.7.1
				 * @description This is the action for the payment methods fragments update.
				 * @return void
				 */
				ob_start();
				do_action( 'wptravelengine_checkout_payment_methods' );
				$payment_methods = ob_get_clean();

				global $wte_cart;

				wp_send_json(
					apply_filters(
						"wptravelengine_page_checkout_{$cart_action}_response",
						array(
							'success'     => true,
							'message'     => __( 'Cart updated successfully.', 'wp-travel-engine' ),
							'cart'        => $wte_cart,
							'cart_totals' => $wte_cart->get_totals(),
							'fragments'   => array(
								'[data-checkout-form-submit]' => $submit_button,
								'[data-cart-summary]' => $cart_summary,
								'[data-checkout-payment-modes]' => $payment_modes,
								'[data-checkout-payment-methods-details]' => $payment_methods,
							),
						)
					)
				);
				break;
			case 'stripe_create_session':
				$this->stripe_create_session();
				break;

			case 'stripe_create_payment_intent':
				$this->stripe_create_payment_intent();
				break;

			case 'midtrans_snap_token':
				$this->midtrans_snap_token();
				break;

			case 'payu_money_bolt_generate_hash':
				$payu_money_admin = new WTE_PayU_Money_Admin();
				$payu_money_admin->generate_hash();
				break;

			default:
				wp_send_json_error( new WP_Error( 'INVALID_CART_ACTION', __( 'Invalid cart action.', 'wp-travel-engine' ) ) );
		}
	}

	/**
	 */
	public function stripe_create_session() {
		require_once WTE_STRIPE_GATEWAY_BASE_PATH . '/includes/stripe-php/init.php';
		@ini_set( 'display_errors', 0 );

		// Set your secret key. Remember to switch to your live secret key in production.
		// See your keys here: https://dashboard.stripe.com/apikeys
		$stripe = new \Stripe\StripeClient(
			array(
				'api_key'        => wptravelengine_settings()->get( 'stripe_secret' ),
				'stripe_version' => '2024-12-18.acacia; custom_checkout_beta=v1;',
			)
		);

		global $wte_cart;

		$line_items = array();

		$zero_decimal_currencies = wptravelengine_cart_zero_decimal_currencies();

		foreach ( $wte_cart->getItems( true ) as $cart_item ) {
			$unit_amount = $cart_item->get_totals( 'total' );
			$currency    = wptravelengine_settings()->get( 'currency_code' );
			if ( ! in_array( $currency, $zero_decimal_currencies ) ) {
				$unit_amount = round( $unit_amount, 2 ) * 100;
			} else {
				$unit_amount = (int) round( $unit_amount );
			}

			$line_items[] = array(
				'price_data' => array(
					'currency'     => wptravelengine_settings()->get( 'currency_code' ),
					'product_data' => array( 'name' => get_the_title( $cart_item->trip_id ) ),
					'unit_amount'  => $unit_amount,
				),
				'quantity'   => 1,
			);
		}
		try {
			$response = $stripe->checkout->sessions->create(
				array(
					'line_items' => $line_items,
					'mode'       => 'payment',
					'ui_mode'    => 'custom',
					'return_url' => get_bloginfo( 'url' ),
				)
			);

			wp_send_json(
				array(
					'success' => true,
					'data'    => $response,
				)
			);
		} catch ( ApiErrorException $e ) {
			wp_send_json(
				array(
					'success' => false,
					'error'   => $e->getMessage(),
				)
			);
		}
	}

	public function stripe_create_payment_intent() {
		require_once WTE_STRIPE_GATEWAY_BASE_PATH . '/includes/stripe-php/init.php';
		@ini_set( 'display_errors', 0 );

		// Set your secret key. Remember to switch to your live secret key in production.
		// See your keys here: https://dashboard.stripe.com/apikeys
		\Stripe\Stripe::setApiKey( wptravelengine_settings()->get( 'stripe_secret' ) );

		global $wte_cart;

		if ( 'partial' == $wte_cart->get_payment_type() ) {
			$order_amount = $wte_cart->get_totals()['partial_total'];
		} elseif ( 'due' == $wte_cart->get_payment_type() ) {
			$order_amount = $wte_cart->get_totals()['due_total'];
		} else {
			$order_amount = $wte_cart->get_totals()['total'];
		}

		$currency = wptravelengine_settings()->get( 'currency_code' );

		if ( ! in_array( $currency, wptravelengine_cart_zero_decimal_currencies() ) ) {
			$order_amount = round( $order_amount, 2 ) * 100;
		} else {
			$order_amount = (int) round( $order_amount );
		}

		try {
			$payment_intent = \Stripe\PaymentIntent::create(
				array(
					'amount'   => $order_amount,
					'currency' => $currency,
					'metadata' => array( 'integration_check' => 'accept_a_payment' ),
				)
			);

			wp_send_json(
				array(
					'success' => true,
					'data'    => array(
						'client_secret' => $payment_intent->client_secret,
						'id'            => $payment_intent->id,
						'return_url'    => get_bloginfo( 'url' ),
						'amount'        => $order_amount,
						'currency'      => $currency,
						'payment_mode'  => $wte_cart->get_payment_type(),
					),
				)
			);
		} catch ( ApiErrorException $e ) {
			wp_send_json(
				array(
					'success' => false,
					'error'   => $e->getMessage(),
				)
			);
		}
	}

	/**
	 * @param \WPTravelEngine\Core\Cart\Cart $cart
	 *
	 * @return float
	 */
	public function get_payable_amount( \WPTravelEngine\Core\Cart\Cart $cart ): float {
		if ( 'partial' == $cart->get_payment_type() ) {
			$order_amount = $cart->get_totals()['partial_total'];
		} elseif ( in_array( $cart->get_payment_type(), array( 'due', 'remaining_payment' ) ) ) {
			$order_amount = $cart->get_totals()['due_total'];
		} else {
			$order_amount = $cart->get_totals()['total'];
		}

		return (int) round( $order_amount, 2 );
	}

	/**
	 * @return void
	 */
	public function midtrans_snap_token() {
		global $wte_cart;

		$amount = $wte_cart->get_total_payable_amount();

		$snap_token = \Wte_Midtrans_Helper::get_instance()->get_snap_token(
			array(
				'transaction_details' => array(
					'order_id'     => rand(),
					'gross_amount' => round( $amount ),
				),
				'custom_field1'       => 'midtrans',
				'customer_details'    => array(
					'first_name' => $this->request->get_param( 'billing[fname]' ),
					'last_name'  => $this->request->get_param( 'billing[lname]' ),
					'email'      => $this->request->get_param( 'billing[email]' ),
				),
				'credit_card'         => array(
					'save_card' => \Wte_Midtrans_Helper::get_instance()->is_save_card_enabled(),
				),
			)
		);

		wp_send_json(
			array(
				'success' => true,
				'data'    => compact( 'snap_token' ),
			)
		);
	}
}
