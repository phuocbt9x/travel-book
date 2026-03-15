<?php
/**
 * Shortcode Trip.
 *
 * @package WPTravelEngine
 * @subpackage WPTravelEngine\Core\Shortcodes
 * @since 6.0.0
 */

namespace WPTravelEngine\Core\Shortcodes;

/**
 * Class Trip.
 *
 * Responsible for creating shortcodes for trip and maintain it.
 *
 * @since 6.0.0
 */
class TripsList {

	/**
	 * Shortcode tag.
	 *
	 * @var string
	 */
	const TAG = 'wte_trip';

	/**
	 * Render the shortcode.
	 *
	 * @param array $attr Shortcode attributes.
	 */
	public function render( array $attr ) {
		$attr = shortcode_atts(
			array(
				'ids'         => '',
				'layout'      => 'grid',
				'postsnumber' => get_option( 'posts_per_page' ),
			),
			$attr,
			'wte_trip'
		);

		if ( ! in_array( $attr['layout'], array( 'grid', 'list' ) ) ) {
			return '<h1>' . sprintf( __( 'Layout not found: %s', 'wp-travel-engine' ), $attr['layout'] ) . '</h1>';
		}

		if ( ! empty( $attr['ids'] ) ) {
			$ids         = explode( ',', $attr['ids'] );
			$attr['ids'] = $ids;
		}

		ob_start();

		do_action( 'wte_trip_content_action', $attr );

		return ob_get_clean();
	}
}
