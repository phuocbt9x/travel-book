<?php
/**
 * Optimizer class
 *
 * @package WPTravelEngine\Optimizer
 * @since 6.0.5
 */

namespace WPTravelEngine\Optimizer;

/**
 * Class Optimizer.
 * The class that handles the optimization of the WP Travel Engine plugin.
 *
 * @since 6.0.5
 */
class Optimizer {

	/**
	 * Registers hooks for the optimizer.
	 */
	public function hooks() {
		add_action( 'template_redirect', array( __CLASS__, 'start' ) );
		add_filter( 'wptravelengine_output_buffer_template_redirect', array( __CLASS__, 'add_lazyload_attributes' ) );
	}

	/**
	 * Initializes the output buffering for processing HTML content.
	 */
	public static function start() {
		if ( ! is_embed() && ! is_feed() && ! is_preview() && is_singular( WP_TRAVEL_ENGINE_POST_TYPE ) ) {
			ob_start(
				function ( $html ) {
					return apply_filters( 'wptravelengine_output_buffer_template_redirect', $html );
				}
			);
		}
	}

	/**
	 * Processes the HTML to add lazy loading attributes to image tags.
	 *
	 * @param string $html The HTML content.
	 *
	 * @return string Modified HTML content with lazy loading attributes.
	 */
	public static function add_lazyload_attributes( string $html ): string {
		// Check if the current page is a WP Travel Engine single post and lazy loading is enabled.
		if ( ! wptravelengine_toggled( wptravelengine_settings()->get( 'enable_lazy_loading' ) ) || ! wptravelengine_toggled( wptravelengine_settings()->get( 'enable_img_lazy_loading' ) ) ) {
			return $html;
		}

		return preg_replace_callback(
			'/<img\s+([^>]+)>/i',
			function ( $matches ) {

				$img_tag = $matches[0];

				// Add 'lazy' class if not present.
				if ( strpos( $img_tag, 'class=' ) !== false ) {
					$img_tag = preg_replace( '/class="([^"]*)"/i', 'class="$1 lazy"', $img_tag );
				} else {
					$img_tag = str_replace( '<img', '<img class="lazy"', $img_tag );
				}

				// Replace src with data-src.
				return preg_replace( '/src="([^"]*)"/i', 'src="" data-src="$1"', $img_tag );
			},
			$html
		);
	}
}
