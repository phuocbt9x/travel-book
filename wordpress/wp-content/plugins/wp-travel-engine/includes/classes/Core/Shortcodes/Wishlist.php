<?php
/**
 * ShortCode Wishlist.
 *
 * @package    WPTravelEngine
 * @subpackage WPTravelEngine\Core\Shortcodes
 */

namespace WPTravelEngine\Core\Shortcodes;

use WPTravelEngine\Abstracts\Shortcode;
use WPTravelEngine\Core\Controllers\Shortcodes\WishlistController;

/**
 * Class Wishlist.
 *
 * Responsible for creating shortcodes for Wishlist displaying and maintaining it.
 *
 * @since 6.0.0
 */
class Wishlist extends Shortcode {
	/**
	 * Shortcode tag.
	 *
	 * @var string
	 */
	const TAG = 'WP_TRAVEL_ENGINE_WISHLIST';

	/**
	 * Retrieves the Wishlist shortcode output.
	 *
	 * This function generates the HTML output for the wishlist shortcode and provide information related to wishlist of user.
	 *
	 * @param array $atts The shortcode attributes.
	 * @return string The generated HTML output from wishlistcontroller view method.
	 */
	public function output( $atts ): string {
		wp_enqueue_style( 'trip-wishlist' );
		wp_enqueue_script( 'trip-wishlist' );
		$wishlist = new WishlistController();
		return $wishlist->view();
	}
}
