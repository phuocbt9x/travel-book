<?php
/**
 * Shortcode Abstract Class
 *
 * @since 6.0.0
 * @package WPTravelEngine
 */

namespace WPTravelEngine\Abstracts;

/**
 * Shortcode Abstract Class
 * This class contains shortcodes attributes parse and other maintainance functions.
 */
abstract class Shortcode {
	/**
	 * Render the shortcode .
	 *
	 * @param mixed $atts The shortcode attributes.
	 *
	 * @return string The generated HTML output.
	 */
	public function render( $atts ): string {
		return $this->output( $this->parse_attributes( $atts ) ) ?? '';
	}
	/**
	 * Get the default attributes for the shortcode.
	 *
	 * @return array The default attributes.
	 */
	protected function default_attributes(): array {
		return array();
	}
	/**
	 * Get the default attributes for the shortcode.
	 *
	 * @param array $atts The shortcode attributes.
	 * @return array The default attributes.
	 */
	protected function parse_attributes( $atts ) {
		return shortcode_atts(
			$this->default_attributes(),
			$atts,
			static::TAG
		);
	}
}
