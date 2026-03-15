<?php
/**
 * Shortcode Cart.
 *
 * @package WPTravelEngine
 * @subpackage WPTravelEngine\Core\Shortcodes
 * @since 6.0.0
 */

namespace WPTravelEngine\Core\Shortcodes;

use WPTravelEngine\Abstracts\Shortcode;

/**
 * Class Cart.
 *
 * Responsible for creating shortcodes for Cart and maintain it.
 *
 * @since 6.0.0
 */
class Cart extends Shortcode {

	/**
	 * Shortcode tag.
	 *
	 * @var string
	 */
	const TAG = 'wp_travel_engine_cart';

	/**
	 * Render the shortcode.
	 */
	public function output(): string {

		$wrapper = array(
			'class'  => 'wp-travel',
			'before' => null,
			'after'  => null,
		);

		ob_start();

		// @codingStandardsIgnoreStart
		echo empty( $wrapper[ 'before' ] ) ? '<div class="' . esc_attr( $wrapper[ 'class' ] ) . '">' : wp_kses_post( $wrapper[ 'before' ] );
		wptravelengine_get_template( 'content-cart.php' );
		echo empty( $wrapper[ 'after' ] ) ? '</div>' : wp_kses_post( $wrapper[ 'after' ] );

		// @codingStandardsIgnoreEnd

		return ob_get_clean();
	}
}
