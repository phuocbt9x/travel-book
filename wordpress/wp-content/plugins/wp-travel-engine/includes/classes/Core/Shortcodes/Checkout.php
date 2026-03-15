<?php

namespace WPTravelEngine\Core\Shortcodes;

use WPTravelEngine\Abstracts\Shortcode;
use WPTravelEngine\Core\Controllers\Checkout as CheckoutController;

/**
 * Place order form.
 *
 * Responsible for creating shortcodes for place order form and mainatain it.
 *
 * @package    Wp_Travel_Engine
 * @subpackage Wp_Travel_Engine/includes
 * @author
 */
use WPTravelEngine\Assets;

class Checkout extends Shortcode {
	/**
	 * Shortcode tag.
	 *
	 * @var string
	 */
	const TAG = 'WP_TRAVEL_ENGINE_PLACE_ORDER';

	/**
	 * Place order form shortcode callback function.
	 *
	 * @return string
	 * @since 1.0
	 */
	public function output( $atts ): string {
		global $wte_cart;

		if ( is_admin() ) {
			return '';
		}

		ob_start();

		// Check if login is required for checkout.
		$settings = wptravelengine_settings()->get();

		$generate_user_account     = $settings['generate_user_account'] ?? 'yes';
		$require_login_to_checkout = $settings['enable_checkout_customer_registration'] ?? 'no';

		if ( 'no' === $generate_user_account && 'yes' === $require_login_to_checkout && ! is_user_logged_in() ) {

			Assets::instance()
			->enqueue_style( 'my-account' )
			->enqueue_script( 'my-account' );

			wte_get_template( 'account/form-login.php' );

			return ob_get_clean();
		}

		if ( defined( 'WTE_USE_OLD_BOOKING_PROCESS' ) && WTE_USE_OLD_BOOKING_PROCESS ) :
			wp_die( new \WP_Error( 'WTE_FUNCTIONALITY_NOT_AVAILABLE', __( 'Old Booking Process functionality has been removed since WP Travel Engine 5.3.1.', 'wp-travel-engine' ) ) );
		elseif ( ! empty( $wte_cart->getItems() ) && is_array( $wte_cart->getItems() ) ) :
			wp_enqueue_script( 'parsley' );
			$checkout = new CheckoutController( $wte_cart );
			wte_get_template( 'template-checkout-new.php', compact( 'checkout' ) );
		else :
			return __( 'Sorry, you may not have selected the number of travellers for the trip. Please select number of travellers and confirm your booking. Thank you.', 'wp-travel-engine' );

		endif;

		return ob_get_clean();
	}
}
