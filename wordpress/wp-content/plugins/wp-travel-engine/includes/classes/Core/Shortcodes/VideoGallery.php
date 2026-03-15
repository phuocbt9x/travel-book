<?php
/**
 * ShortCode VideoGallery.
 *
 * @package    WPTravelEngine
 * @subpackage WPTravelEngine\Core\Shortcodes
 */

namespace WPTravelEngine\Core\Shortcodes;

use WPTravelEngine\Abstracts\Shortcode;
use WPTravelEngine\Core\Controllers\Shortcodes\VideoGalleryController;


/**
 * Class Video Gallery.
 *
 * Responsible for creating shortcodes for trip video gallery displaying and maintaining it.
 *
 * @since 6.0.0
 */
class VideoGallery extends Shortcode {
	/**
	 * Shortcode tag.
	 *
	 * @var string
	 */
	const TAG = 'wte_video_gallery';

	/**
	 * Retrieves the default attributes for the video gallery shortcode.
	 *
	 * @return array The default attributes.
	 */
	protected function default_attributes(): array {
		global $post;
		return array(
			'title'   => false,
			'trip_id' => $post->ID ?? 0,
			'type'    => 'popup',
			'label'   => esc_html__( 'Video Gallery', 'wp-travel-engine' ),
		);
	}

	/**
	 * Retrieves the video gallery shortcode output.
	 *
	 * This function generates the HTML output for the trip video gallery shortcode based on the provided attributes.
	 *
	 * @param array $atts The shortcode attributes ( trip_id, title, type, label ).
	 * @return string The generated HTML output from videogallerycontroller view method.
	 */
	public function output( $atts ): string {
		wp_enqueue_style( 'jquery-fancy-box' );
		wp_enqueue_script( 'jquery-fancy-box' );
		$videogallery = new VideoGalleryController();
		return $videogallery->view( $atts );
	}
}
