<?php
/**
 * Shortcode SearchForm.
 *
 * @package WPTravelEngine
 * @subpackage WPTravelEngine\Core\Shortcodes
 * @since 6.0.0
 */

namespace WPTravelEngine\Core\Shortcodes;

use WPTravelEngine\Core\Controllers\Ajax\TripsHtml;
use WPTravelEngine\Modules\TripSearch;

/**
 * Class SearchForm.
 *
 * Responsible for creating shortcodes for Search form and maintain it.
 *
 * @since 6.0.0
 */
class SearchForm {

	/**
	 * Shortcode tag.
	 *
	 * @var string
	 */
	const TAG = 'Wte_Advanced_Search_Form';
	/**
	 * Render the shortcode.
	 */
	public function render() {
		$cost_range     = new TripsHtml();
		$duration_range = new TripsHtml();

		return TripSearch::search_form();
	}
}
