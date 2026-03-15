<?php
/**
 * Shortcode TripTax.
 *
 * @package WPTravelEngine
 * @subpackage WPTravelEngine\Core\Shortcodes
 * @since 6.0.0
 */

namespace WPTravelEngine\Core\Shortcodes;

use WPTravelEngine\Abstracts\Shortcode;
use WPTravelEngine\Core\Controllers\Shortcodes\TripTaxController;

/**
 * Class TripTax.
 *
 * Responsible for creating shortcodes for trip taxonomies and maintain it.
 *
 * @since 6.0.0
 */
class TripTax extends Shortcode {
	/**
	 * Shortcode tag.
	 *
	 * @var string
	 */

	const TAG = 'wte_trip_tax';

	/**
	 * Returns the default attributes for the TripTax shortcode.
	 *
	 * This function returns an array of default attributes for the TripTax shortcode.
	 *
	 * @return array The array of default attributes.
	 */
	protected function default_attributes(): array {
		return array(
			'activities'  => '',
			'destination' => '',
			'trip_types'  => '',
			'layout'      => 'grid',
			'postsnumber' => get_option( 'posts_per_page' ),
		);
	}

	/**
	 * Retrieves the TripTax shortcode output.
	 *
	 * This function generates the HTML output for the TripTax shortcode and provide information related to TripTax of user.
	 *
	 * @param array $atts Shortcode attributes.
	 * @return string The generated HTML output.
	 */
	public function output( $atts ): string {
		ob_start();
		$triptax = new TripTaxController();
		return $triptax->view( $this->parse_attributes( $atts ) );
	}
}
