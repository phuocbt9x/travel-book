<?php

use WPTravelEngine\Core\Controllers\Checkout;
use WPTravelEngine\Registers\ShortcodeRegistry;
use WPTravelEngine\Core\Shortcodes\Checkout as CheckoutShortcode;

/**
 * Place order form.
 *
 * Responsible for creating shortcodes for place order form and mainatain it.
 *
 * @package    Wp_Travel_Engine
 * @subpackage Wp_Travel_Engine/includes
 * @author
 */
class Wp_Travel_Engine_Place_Order {

	/**
	 * Initialize the place order form shortcode.
	 *
	 * @since 1.0.0
	 */
	function init() {
		add_action( 'init', array( $this, 'place_order_form_validate' ) );
	}

	/**
	 * Place order form shortcode callback function.
	 *
	 * @since 1.0
	 */
	function wp_travel_engine_place_order_shortcodes_callback() {
		global $post;

		global $wte_cart;

		if ( is_admin() ) {
			return;
		}

		ob_start();

		// Check if login is required for checkout.
		$settings = wptravelengine_settings()->get();

		$generate_user_account     = $settings['generate_user_account'] ?? 'yes';
		$require_login_to_checkout = isset( $settings['enable_checkout_customer_registration'] ) ? $settings['enable_checkout_customer_registration'] : 'no';

		if ( 'no' === $generate_user_account && 'yes' === $require_login_to_checkout && ! is_user_logged_in() ) {
			return wte_get_template( 'account/form-login.php' );
		}

		if ( defined( 'WTE_USE_OLD_BOOKING_PROCESS' ) && WTE_USE_OLD_BOOKING_PROCESS ) :
			wp_die( new WP_Error( 'WTE_FUNCTIONALITY_NOT_AVAILABLE', esc_html__( 'Old Booking Process functionality has been removed since WP Travel Engine 5.3.1.', 'wp-travel-engine' ) ) );
		elseif ( ! empty( $wte_cart->getItems() ) && is_array( $wte_cart->getItems() ) ) :
				wp_enqueue_script( 'parsley' );
				$checkout = new Checkout( $wte_cart );
				wte_get_template( 'template-checkout-new.php', compact( 'checkout' ) );
		else :
				return __( 'Sorry, you may not have selected the number of travellers for the trip. Please select number of travellers and confirm your booking. Thank you.', 'wp-travel-engine' );

		endif;

			$data = ob_get_clean();

			return $data;
	}

	/**
	 * Place order form validation function.
	 *
	 * @since 1.0.0
	 */
	function place_order_form_validate() {
	}
}
