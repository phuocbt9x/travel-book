<?php
/**
 * ShortCode TripMap.
 *
 * @package    WPTravelEngine
 * @subpackage WPTravelEngine\Core\Shortcodes
 */

namespace WPTravelEngine\Core\Shortcodes;

use WPTravelEngine\Abstracts\Shortcode;
use WPTravelEngine\Core\Controllers\Shortcodes\TripMapController;

/**
 * Class Trip Map.
 *
 * Responsible for creating shortcodes for trip map displaying and maintaining it.
 *
 * @since 6.0.0
 */
class TripMap extends Shortcode {
	/**
	 * Shortcode tag.
	 *
	 * @var string
	 */
	const TAG = 'wte_trip_map';

	/**
	 * Retrieves the default attributes for the video gallery shortcode.
	 *
	 * @return array The default attributes.
	 */
	protected function default_attributes(): array {
		global $post;
		return array(
			'id'   => $post->ID ?? 0,
			'show' => 'both',
		);
	}

	/**
	 * Retrieves the trip map shortcode output.
	 *
	 * This function generates the HTML output for the trip map shortcode based on the provided attributes.
	 *
	 * @param array $atts The shortcode attribute (show).
	 * @return string The generated HTML output.
	 */
	public function output( $atts ): string {
		$tripmap = new TripMapController();
		return $tripmap->view( $this->parse_attributes( $atts ) );
	}
}
