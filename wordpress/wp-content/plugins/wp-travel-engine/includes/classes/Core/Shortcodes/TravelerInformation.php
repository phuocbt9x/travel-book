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

/**
 * Class Conformation.
 *
 * Responsible for creating shortcodes for trip conformation and maintaining it.
 *
 * @since 6.0.0
 */
class TravelerInformation extends Shortcode {

	/**
	 * Shortcode tag.
	 *
	 * @var string
	 */
	const TAG = 'WP_TRAVEL_ENGINE_BOOK_CONFIRMATION';

	/**
	 * @return string
	 */
	public function output(): string {
		if ( is_admin() ) {
			return '';
		}

		ob_start();
		wte_get_template( 'traveller-information/template-traveler-info.php' );

		return ob_get_clean();
	}
}
