<?php
/**
 * Static methods class for allowed HTML by context.
 *
 * @package WPTravelEngine\Helpers
 *
 * @since 6.0.0
 */

namespace WPTravelEngine\Helpers;

/**
 * This class contains all the context for allowed HTML.
 *
 * @since 6.0.0
 */
class AllowedHTML {

	/**
	 * Retrieve allowed tags.
	 *
	 * @param $context
	 * @param $allowedtags
	 *
	 * @return array
	 */
	public static function get_allowed_tags( $context, $allowedtags ): array {
		if ( method_exists( __CLASS__, $context ) ) {
			return call_user_func( array( __CLASS__, $context ) );
		}

		return $allowedtags;
	}

	/**
	 * Allowed HTML for iframe context.
	 *
	 * @return array
	 */
	public static function wte_iframe(): array {
		return array(
			'iframe' => array(
				'src'             => array(),
				'width'           => array(),
				'height'          => array(),
				'style'           => array(),
				'allowfullscreen' => array(),
				'loading'         => array(),
			),
		);
	}

	/**
	 * Allowed HTML for formats context.
	 *
	 * @return array
	 */
	public static function wte_formats(): array {
		return array(
			'a'      => array(
				'href'   => array(),
				'target' => array(),
				'class'  => array(),
				'title'  => array(),
			),
			'p'      => array( 'class' => array() ),
			'b'      => array(),
			'i'      => array(),
			'code'   => array(),
			'span'   => array(),
			'em'     => array(),
			'strong' => array(),
		);
	}

	/**
	 * Allowed HTML for plugin price context.
	 * This context contains tags allowed while displaying price.
	 *
	 * @return array
	 */
	public static function allowed_price_html(): array {
		return array(
			'span'   => array(
				'class' => array(),
			),
			'del'    => array(),
			'em'     => array(),
			'strong' => array(),
			'b'      => array(),
		);
	}
}
