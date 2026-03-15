<?php
/**
 * Shortcode Trip.
 *
 * @package WPTravelEngine
 * @subpackage WPTravelEngine\Core\Shortcodes
 * @since 6.0.0
 */

namespace WPTravelEngine\Core\Shortcodes;

use WPTravelEngine\Core\Models\Settings\PluginSettings;
use WPTravelEngine\Core\Models\Post\Trip;
/**
 * Class TripInfo.
 *
 * Responsible for creating shortcodes for trip Information/facts and maintain it.
 *
 * @since 6.0.0
 */
class TripInfo {

	/**
	 * Shortcode tag.
	 *
	 * @var string
	 */
	const TAG = 'Trip_Info_Shortcode';

	/**
	 * Render the shortcode.
	 *
	 * @param array $atts Shortcode attributes.
	 */
	public function render( $atts ) {
		ob_start();
		$atts = shortcode_atts(
			array(
				'id' => '',
			),
			$atts,
			'trip_facts_shortcode'
		);
		global $post;
		$trip          = Trip::make( $post );
		$settings      = new \WPTravelEngine\Core\Models\Settings\PluginSettings();
		$trip_facts    = $trip->get_trip_facts();
		$section_title = $trip->get_tab_section_title( 'trip_facts' );

		require_once dirname( WP_TRAVEL_ENGINE_FILE_PATH ) . '/includes/frontend/trip-meta/trip-meta-parts/trip-facts.php';

		return ob_get_clean();
	}
}
