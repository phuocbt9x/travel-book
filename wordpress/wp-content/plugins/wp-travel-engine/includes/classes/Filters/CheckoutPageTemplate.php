<?php
/**
 * Checkout Page Template Filters.
 *
 * @since 6.3.0
 */

namespace WPTravelEngine\Filters;

use WPTravelEngine\Abstracts\BookingProcessPageTemplate;
use WPTravelEngine\Builders\FormFields\BillingFormFields;
use WPTravelEngine\Builders\FormFields\EmergencyFormFields;
use WPTravelEngine\Builders\FormFields\PrivacyPolicyFields;
use WPTravelEngine\Builders\FormFields\TravellersFormFields;
use WPTravelEngine\Builders\FormFields\LeadTravellersFormFields;
use WPTravelEngine\Core\Coupons;
use WPTravelEngine\Core\Models\Settings\PluginSettings;
use WPTravelEngine\Pages\Checkout;

/**
 * Checkout Page Template Filters.
 *
 * @since 6.3.0
 */
class CheckoutPageTemplate extends BookingProcessPageTemplate {

	public function hooks() {
		add_action(
			'wptravelengine_cart_before_pricing_category_line_items',
			array( $this, 'print_pricing_category_line_items_title' )
		);

		add_action(
			'wptravelengine_cart_before_extra_service_line_items',
			array( $this, 'print_extra_service_line_items_title' )
		);

		add_action( 'wptravelengine_checkout_form_submit_button', array( $this, 'print_checkout_form_button' ) );

		add_filter(
			'wptravelengine_checkout_paypal_payment_button',
			array( $this, 'print_paypal_checkout_button' ),
			10,
			2
		);

		add_filter(
			'wptravelengine_checkout_stripe_payment_button',
			array( $this, 'print_stripe_payment_button' ),
			10,
			2
		);

		add_filter(
			'wptravelengine_checkout_paypalexpress_enable_button',
			array( $this, 'print_paypalexpress_checkout_button' ),
			10,
			2
		);

		add_filter(
			'wptravelengine_checkout_authorize-net-payment_button',
			array(
				$this,
				'print_authorize_net_payment_button',
			),
			10,
			2
		);

		add_filter(
			'wptravelengine_checkout_payu_money_enable_button',
			array(
				$this,
				'print_payu_money_enable_button',
			),
			10,
			2
		);

		add_action( 'wptravelengine_stripe_payment_payment_cc', array( $this, 'print_stripe_payment_cc' ) );

		$checkout_templates = array(
			'wptravelengine_checkout_payment_modes'      => 'print_payment_modes',
			'wptravelengine_checkout_payment_methods'    => 'print_payment_methods',
			'checkout_template_parts_tour-details'       => 'print_tour_details',
			'checkout_template_parts_cart-summary'       => 'print_cart_summary',
			'checkout_template_parts_payments'           => 'print_payments_methods',
			'checkout_template_parts_lead-travellers-details' => 'print_lead_travellers_details',
			'checkout_template_parts_travellers-details' => 'print_travellers_details',
			'checkout_template_parts_billing-details'    => 'print_billing_details',
			'checkout_template_parts_checkout-note'      => 'print_checkout_note',
			'checkout_template_parts_emergency-details'  => 'print_emergency_details',
			'checkout_template_parts_checkout-form'      => 'print_checkout_form',
		);

		foreach ( $checkout_templates as $template_part => $callback ) {
			add_action(
				$template_part,
				function ( $args ) use ( $callback ) {
					$args = ! is_array( $args ) ? array() : $args;

					call_user_func(
						array( $this, $callback ),
						wp_parse_args(
							$args,
							array(
								'show_title'   => true,
								'content_only' => false,
							)
						)
					);
				}
			);
		}

		do_action( 'wptravelengine_checkout_page_template_filters', $this );
	}

	/**
	 * Print the Checkout Form.
	 *
	 * @return void
	 */
	public function print_checkout_form() {
		wp_dequeue_script( 'Wte-PayPal-Express' );
		global $wte_cart;

		$template_instance = Checkout::instance( $wte_cart );
		$args              = array(
			'billing_form_fields'         => new BillingFormFields(),
			'lead_travellers_form_fields' => new LeadTravellersFormFields(),
			'travellers_form_fields'      => new TravellersFormFields(),
			'emergency_contact_fields'    => new EmergencyFormFields(),
			'note_form_fields'            => wptravelengine_form_field( false )->init( $template_instance->get_note_form_fields() ),
			'privacy_policy_fields'       => new PrivacyPolicyFields(),
		);
		?>
		<form class="wpte-checkout__content" method="POST" id="wptravelengine-checkout__form"
				enctype="multipart/form-data">
			<input type="hidden" name="action" value="wp_travel_engine_new_booking_process_action">
			<?php
			wp_nonce_field( 'wp_travel_engine_new_booking_process_nonce_action', 'wp_travel_engine_new_booking_process_nonce' );
			wptravelengine_get_template( 'template-checkout/content-checkout-form.php', $args );
			?>
		</form>
		<?php
	}

	/**
	 * Print the Lead Travellers Details.
	 *
	 * @param array $args Arguments.
	 * @since 6.4.3
	 * @return void
	 */
	public function print_lead_travellers_details( array $args ) {
		global $wte_cart;
		if ( ! isset( $lead_travellers_form_fields ) ) {
			$lead_travellers_form_fields = array();
			foreach ( $wte_cart->getItems( true ) as $cart_item ) {
				$lead_travellers_form_fields[] = new LeadTravellersFormFields( array() );
			}
		}
		$args = array_merge( compact( 'lead_travellers_form_fields' ), $args );
		wptravelengine_get_template( 'template-checkout/content-lead-travellers-details.php', $args );
	}

	/**
	 * Print the Travellers Details.
	 *
	 * @return void
	 */
	public function print_travellers_details( array $args ) {
		global $wte_cart;
		if ( ! isset( $travellers_form_fields ) ) {
			$travellers_form_fields = array();
			foreach ( $wte_cart->getItems( true ) as $cart_item ) {
				$travellers_form_fields[] = new TravellersFormFields(
					array(
						'number_of_travellers'      => array_sum( $cart_item->travelers ?? $cart_item->pax ),
						'number_of_lead_travellers' => 1,
					)
				);
			}
		}
		$args = array_merge( compact( 'travellers_form_fields' ), $args );
		wptravelengine_get_template( 'template-checkout/content-travellers-details.php', $args );
	}

	/**
	 * Print the Billing Details.
	 *
	 * @return void
	 */
	public function print_billing_details() {
		global $wte_cart;
		if ( ! isset( $billing_form_fields ) ) {
			$billing_form_fields = new BillingFormFields( array( 'booking_ref' => $wte_cart->get_booking_ref() ) );
		}

		$lead_travellers_form_fields = array();
		if ( isset( $lead_travellers_form_fields ) ) {
			foreach ( $wte_cart->getItems( true ) as $cart_item ) {
				$lead_travellers_form_fields[] = new LeadTravellersFormFields( array() );
			}
		}
		$lead_travellers_fields_count = isset( $lead_travellers_form_fields ) && isset( $lead_travellers_form_fields[0]->fields ) ? count( $lead_travellers_form_fields[0]->fields ) : 0;
		$args                         = compact( 'billing_form_fields', 'lead_travellers_fields_count' );
		wptravelengine_get_template( 'template-checkout/content-billing-details.php', $args );
	}

	/**
	 * Print the Checkout Note.
	 *
	 * @return void
	 */
	public function print_checkout_note( $args ) {
		$args = array_merge(
			array(
				'note_form_fields' => wptravelengine_form_field( false )->init( Checkout::instance()->get_note_form_fields() ),
			),
			$args
		);
		wptravelengine_get_template( 'template-checkout/content-checkout-note.php', $args );
	}

	/**
	 * Print the Emergency Details.
	 *
	 * @return void
	 */
	public function print_emergency_details() {
		if ( ! isset( $emergency_contact_fields ) ) {
			$emergency_contact_fields = new EmergencyFormFields();
		}
		$args = compact( 'emergency_contact_fields' );

		wptravelengine_get_template( 'template-checkout/content-emergency-details.php', $args );
	}

	/**
	 * Print the Payment Methods.
	 *
	 * @return void
	 */
	public function print_payments_methods() {
		global $wte_cart;

		$privacy_policy_fields = new PrivacyPolicyFields();
		$payment_methods       = Checkout::instance( $wte_cart )->get_active_payment_methods();

		$payment_methods = $wte_cart->get_totals()['total'] <= 0 ? array() : $payment_methods;

		wptravelengine_get_template(
			'template-checkout/content-payments.php',
			compact( 'privacy_policy_fields', 'payment_methods' )
		);
	}

	/**
	 * Print the Cart Summary.
	 *
	 * @param array $args Arguments to be passed in cart summary template.
	 *
	 * @return void
	 */
	public function print_cart_summary( $args ) {
		global $wte_cart;
		/**  @var \WPTravelEngine\Pages\Checkout $template_instance */
		$template_instance  = Checkout::instance( $wte_cart );
		$cart_line_items    = $template_instance->get_cart_line_items();
		$deposit_amount     = $template_instance->cart->get_total_partial();
		$due_amount         = $template_instance->cart->get_due_total();
		$is_partial_payment = in_array(
			$template_instance->cart->get_payment_type(),
			array(
				'partial',
				'due',
				'remaining_payment',
			),
			true
		);
		$show_coupon_form   = wptravelengine_settings()->get( 'show_discount' ) === 'yes' && Coupons::is_coupon_available() && 'due' !== $wte_cart->get_payment_type() ? 'show' : 'hide';

		$coupons = array();

		foreach ( $wte_cart->get_deductible_items() as $coupon_item ) {
			if ( 'coupon' !== $coupon_item->name ) {
				continue;
			}
			$coupons[] = array(
				'label'  => $coupon_item->label,
				'amount' => $wte_cart->get_totals()['total_coupon'] ?? 0,
			);
		}

		$args = array_merge(
			compact( 'cart_line_items', 'deposit_amount', 'due_amount', 'is_partial_payment', 'coupons' ),
			array( 'show_coupon_form' => $show_coupon_form === 'show' ),
			$args
		);
		wptravelengine_get_template(
			'template-checkout/content-cart-summary.php',
			$args
		);
	}

	/**
	 * Print the Tour Details.
	 *
	 * @return void
	 */
	public function print_tour_details( array $args ) {
		global $wte_cart;
		$tour_details = Checkout::instance( $wte_cart )->get_tour_details();
		wptravelengine_get_template(
			'template-checkout/content-tour-details.php',
			array_merge( compact( 'tour_details' ), $args )
		);
	}

	/**
	 * Print the Payment Methods.
	 *
	 * @return void
	 */
	public function print_payment_methods( array $args ) {
		global $wptravelengine_template_args, $wte_cart;
		if ( ! isset( $wptravelengine_template_args['payment_methods'] ) ) {
			$payment_methods = Checkout::instance( $wte_cart )->get_active_payment_methods();
			$payment_methods = $wte_cart->get_totals()['total'] <= 0 ? array() : $payment_methods;
		} else {
			$payment_methods = $wptravelengine_template_args['payment_methods'];
		}
		if ( count( $payment_methods ) < 1 ) {
			return;
		}
		if ( 'due' === $wte_cart->get_payment_type() ) {
			unset( $payment_methods['booking_only'] );
		}

		/**
		 * @since 6.7.1
		 * @description Filter for the payment methods.
		 * @param array $payment_methods The payment methods.
		 * @param array $args The arguments for the filter.
		 * @return array
		 */
		$payment_methods = apply_filters( 'wptravelengine_filter_checkout_payment_methods', $payment_methods, $args );

		wptravelengine_get_template(
			'template-checkout/content-payment-methods.php',
			array_merge( compact( 'payment_methods', 'wte_cart' ), $args )
		);
	}

	/**
	 * Print the Checkout Form Button.
	 *
	 * @return void
	 */
	public function print_checkout_form_button() {
		global $wte_cart;

		$payment_gateway = $wte_cart->payment_gateway ?? 'default';
		ob_start();
		?>
		<button type="submit" class="wpte-checkout__form-submit-button">
			<?php echo __( 'Confirm Booking', 'wp-travel-engine' ); ?>
		</button>
		<?php
		$button = apply_filters( "wptravelengine_checkout_{$payment_gateway}_button", ob_get_clean(), $wte_cart );
		echo wp_kses_post( $button );
	}

	/**
	 * Print the Payment Options.
	 *
	 * @return void
	 */
	public function print_payment_modes() {
		global $wte_cart;

		if ( ! wp_travel_engine_is_cart_partially_payable() || ( $wte_cart->get_totals()['total'] <= 0 ) ) {
			return;
		}

		if ( 'booking_only' === $wte_cart->payment_gateway ) {
			return;
		}

		$instance             = Checkout::instance( $wte_cart );
		$full_payment_enabled = $instance->is_full_payment_enabled();
		$payment_mode         = $instance->get_payment_type() ?? 'partial';
		// Get Booking Ref.
		$booking_ref = $instance->cart->get_booking_ref();
		if ( $booking_ref ) {
			$booking = wptravelengine_get_booking( $booking_ref );
			if ( $booking && $wte_cart->is_curr_cart() && ! $booking->get_last_payment() ) {
				return;
			}
			$payment_mode = 'due';
		}

		$down_payment_amount = $instance->cart->get_totals()['partial_total'];
		$full_payment_amount = $instance->cart->get_totals()['total'];

		switch ( $payment_mode ) {
			case 'due':
			case 'remaining_payment':
				global $wte_cart;
				$due_payment_amount = $wte_cart->get_due_total();
				wptravelengine_get_template(
					'template-checkout/content-payment-mode-due.php',
					compact( 'payment_mode', 'due_payment_amount' )
				);
				break;
			case 'partial':
			case 'full':
			case 'full_payment':
			default:
				wptravelengine_get_template(
					'template-checkout/content-payment-modes.php',
					compact( 'payment_mode', 'full_payment_enabled', 'down_payment_amount', 'full_payment_amount' )
				);
				break;
		}
	}

	/**
	 * Print the Stripe Payment CC.
	 *
	 * @return void
	 */
	public function print_stripe_payment_cc() {
		?>
		<div class="wpte-checkout__payment-cc-placeholder">
			<div id="stripe-payment-element"></div>
			<!--			<div id="stripe-payment-card-element" data-parsley-excluded="true">-->
			<!--				<div class="strip-element stripe-card-number-wrapper">-->
			<!--					<div id="stripe-card-number"></div>-->
			<!--					<fieldset>-->
			<!--						<legend>-->
			<?php // echo __( 'Card Number', 'wp-travel-engine' ) ?><!--</legend>-->
			<!--					</fieldset>-->
			<!--				</div>-->
			<!--				<div class="strip-element stripe-card-expiry-wrapper">-->
			<!--					<div id="stripe-card-expiry"></div>-->
			<!--					<fieldset>-->
			<!--						<legend>--><?php // echo __( 'Expiry', 'wp-travel-engine' ) ?><!--</legend>-->
			<!--					</fieldset>-->
			<!--				</div>-->
			<!--				<div class="strip-element stripe-card-cvc-wrapper">-->
			<!--					<div id="stripe-card-cvc"></div>-->
			<!--					<fieldset>-->
			<!--						<legend>--><?php // echo __( 'CVC', 'wp-travel-engine' ) ?><!--</legend>-->
			<!--					</fieldset>-->
			<!--				</div>-->
			<!--			</div>-->
			<div id="stripe-card-errors" role="alert"></div>
		</div>
		<?php
	}

	/**
	 * Print the PayPal Checkout Button.
	 *
	 * @return string
	 */
	public function print_paypal_checkout_button(): string {
		ob_start();
		?>
		<button type="submit" class="wpte-checkout__form-submit-button">
			<?php echo __( 'Pay &amp; Confirm Booking', 'wp-travel-engine' ); ?>
		</button>
		<?php
		return ob_get_clean();
	}

	/**
	 * Print the Stripe Checkout Button.
	 *
	 * @return string
	 * @since 6.7.1 Added support for stripe button label filter.
	 */
	public function print_stripe_payment_button(): string {
		$btn_label = wptravelengine_settings()->get(
			'stripe_btn_label'
		);
		return sprintf(
			'<button id="wte-stripe-payment-button" class="wpte-checkout__form-submit-button">%s</button>',
			empty( $btn_label ) ? __( 'Pay & Confirm Booking', 'wp-travel-engine' ) : $btn_label
		);
	}

	/**
	 * Print the Authorize Net Checkout Button.
	 *
	 * @return string
	 */
	public function print_authorize_net_payment_button(): string {

		ob_start();
		wp_enqueue_script( 'wte-authorizenet-checkout' );

		do_action( 'wte_booking_after_submit_button' );
		$button                  = ob_get_clean();
		$wptravelengine_settings = get_option( 'wp_travel_engine_settings' );
		$is_debug                = isset( $wptravelengine_settings['payment_debug'] ) && 'yes' === $wptravelengine_settings['payment_debug'];
		$acceptui_url            = $is_debug ? 'https://jstest.authorize.net/v3/AcceptUI.js' : 'https://js.authorize.net/v3/AcceptUI.js';
		?>
		<input type="hidden" name="dataValue" id="dataValue" />
		<input type="hidden" name="dataDescriptor" id="dataDescriptor" />
		<?php
		$button = str_replace( 'class="AcceptUI"', 'class="wpte-checkout__form-submit-button AcceptUI wte-authorize-net-payment-button" data-acceptui-url="' . esc_url( $acceptui_url ) . '"', $button );

		return $button;
	}

	/**
	 * Print the PayPal Express Checkout Button.
	 *
	 * @return string
	 */
	public function print_paypalexpress_checkout_button(): string {
		global $wte_cart;

		$amount = $wte_cart->get_total_payable_amount();

		$data = array(
			'currency_code' => PluginSettings::make()->get( 'currency_code' ),
			'amount'        => $amount,
		);

		return sprintf(
			'<div id="wte-paypal-express-payment-button" class="wte-paypal-express-payment-button" data-payment="%s"></div>',
			esc_attr( wp_json_encode( $data ) )
		);
	}

	/**
	 * Print the PayU Money Enable Button.
	 *
	 * @return string
	 */
	public function print_payu_money_enable_button(): string {
		global $wte_cart;

		$amount = $wte_cart->get_total_payable_amount();

		// Prepare data array.
		$data = array(
			'amount' => round( $amount, 2 ),
		);

		// Return the button HTML directly.
		return sprintf(
			'<button type="submit" class="wpte-checkout__form-submit-button" id="wte-payu-money-enable-button" data-payment="%s">%s</button>',
			esc_attr( wp_json_encode( $data ) ),
			esc_html__( 'Pay & Confirm Booking', 'wp-travel-engine' )
		);
	}

	/**
	 * Print Pricing Category Title.
	 *
	 * @return void
	 */
	public function print_pricing_category_line_items_title() {
		?>
		<tr>
			<td><strong><?php _e( 'Traveller(s):', 'wp-travel-engine' ); ?></strong></td>
			<td></td>
		</tr>
		<?php
	}

	/**
	 * Print Extra Service Title.
	 *
	 * @return void
	 */
	public function print_extra_service_line_items_title() {
		$settings = get_option( 'wp_travel_engine_settings' );
		$title    = ! empty( $settings['extra_service_title'] ) ? $settings['extra_service_title'] : __( 'Extra Services', 'wp-travel-engine' );
		$title    = apply_filters( 'wptravelengine_mini_cart_services_title', $title );
		?>
		<tr>
			<td><strong><?php echo esc_html( $title ); ?></strong></td>
			<td></td>
		</tr>
		<?php
	}
}
