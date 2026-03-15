<?php
/**
 * Shortcode Trip.
 *
 * @package WPTravelEngine
 * @subpackage WPTravelEngine\Core\Shortcodes
 * @since 6.0.0
 */

namespace WPTravelEngine\Core\Shortcodes;

use WPTravelEngine\Abstracts\Shortcode;
use WPTravelEngine\Core\Controllers\Shortcodes\ConfirmationController;

/**
 * Class Conformation.
 *
 * Responsible for creating shortcodes for trip conformaion and maintain it.
 *
 * @since 6.0.0
 */
class Confirmation extends Shortcode {

	/**
	 * Shortcode tag.
	 *
	 * @var string
	 */
	const TAG = 'WP_TRAVEL_ENGINE_BOOK_CONFIRMATION';

	/**
	 *  Retrieves Confirmation shortcode output.
	 *
	 * This function returns an array of default attributes for the Confirmation shortcode.
	 *
	 * @param array $atts Shortcode attributes.
	 * @return string The generated HTML output.
	 */
	public function output( $atts ): string {
		$confirmation = new ConfirmationController();
		return $confirmation->view( $this->parse_attributes( $atts ) );
	}
}
