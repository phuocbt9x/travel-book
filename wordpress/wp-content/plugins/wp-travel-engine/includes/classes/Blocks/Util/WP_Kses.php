<?php
/**
 * WP Kses.
 */

namespace WPTravelEngine\Blocks\Util;

class WP_Kses {

	public function __construct() {
		add_filter( 'wp_kses_allowed_html', array( $this, 'allowed_html' ), 10, 2 );
	}

	/**
	 * @return array
	 * @since 6.3.4
	 */
	public function wptravelengine_post() {
		global $allowedposttags;

		$allowedposttags['iframe'] = array(
			'src'             => true,
			'width'           => true,
			'height'          => true,
			'frameborder'     => true,
			'loading'         => true,
			'style'           => true,
			'allowfullscreen' => true,
			'referrerpolicy'  => true,
		);

		return $allowedposttags;
	}

	public function allowed_html( $allowed_tags, $context ) {

		if ( method_exists( $this, $context ) ) {
			return $this->$context( $allowed_tags );
		}

		return $allowed_tags;
	}

	/**
	 * Allowed HTML for svg.
	 *
	 * @return array[]
	 */
	public function svg(): array {
		return array(
			'svg'      => array(
				'class'           => true,
				'aria-hidden'     => true,
				'aria-labelledby' => true,
				'role'            => true,
				'xmlns'           => true,
				'width'           => true,
				'height'          => true,
				'viewbox'         => true, // <= Must be lower case!
				'fill'            => true,
			),
			'g'        => array( 'fill' => true ),
			'title'    => array( 'title' => true ),
			'path'     => array(
				'd'               => true,
				'fill'            => true,
				'stroke'          => true,
				'stroke-width'    => true,
				'stroke-linecap'  => true,
				'stroke-linejoin' => true,
			),
			'circle'   => array(
				'cx'     => true,
				'cy'     => true,
				'r'      => true,
				'fill'   => true,
				'stroke' => true,
			),
			'polygon'  => array(
				'points' => true,
				'fill'   => true,
			),
			'rect'     => array(
				'x'      => true,
				'y'      => true,
				'width'  => true,
				'height' => true,
				'fill'   => true,
			),
			'line'     => array(
				'x1'           => true,
				'y1'           => true,
				'x2'           => true,
				'y2'           => true,
				'stroke'       => true,
				'stroke-width' => true,
			),
			'ellipse'  => array(
				'cx'     => true,
				'cy'     => true,
				'rx'     => true,
				'ry'     => true,
				'fill'   => true,
				'stroke' => true,
			),
			'defs'     => array(),
			'clippath' => array( 'id' => true ),
			'use'      => array( 'xlink:href' => true ),
		);
	}

	/**
	 * Allowed HTML for image.
	 *
	 * @return array[]
	 */
	public function img(): array {
		return array(
			'img' => array(
				'width'         => true,
				'height'        => true,
				'decoding'      => true,
				'fetchpriority' => true,
				'id'            => true,
				'alt'           => true,
				'src'           => true,
				'srcset'        => true,
				'class'         => true,
				'loading'       => true,
				'itemprop'      => true,
			),
		);
	}
}
