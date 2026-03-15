<?php
/**
 * Shortcode TripCode.
 *
 * @package WPTravelEngine
 * @subpackage WPTravelEngine\Core\Shortcodes
 * @since 6.0.0
 */

namespace WPTravelEngine\Core\Shortcodes;

use WPTravelEngine\Core\Models\Post\Trip;
/**
 * Class TripCode.
 *
 * Responsible for creating shortcodes for trip and maintain it.
 *
 * @since 6.0.0
 */
class TripCode {

	/**
	 * Shortcode tag.
	 *
	 * @var string
	 */
	const TAG = 'wte_trip_code';

	/**
	 * Render the shortcode.
	 *
	 * @param array $atts Shortcode attributes.
	 */
	public function render( $atts ) {
		if ( is_admin() ) {
			return;
		}

		global $post;
		$post_id = is_object( $post ) && isset( $post->ID ) ? $post->ID : false;

		$atts = shortcode_atts(
			array(
				'id' => $post_id,
			),
			$atts,
			'wte_trip_code'
		);

		$trip_model = new Trip( $post_id );
		$trip_code  = $trip_model->get_trip_code();

		if ( $trip_code ) {
			return sprintf(
				'<span class="wpte-trip-code">%s: %s</span>',
				esc_html__( 'Trip Code', 'wp-travel-engine' ),
				esc_html( $trip_code )
			);
		}
	}
}
