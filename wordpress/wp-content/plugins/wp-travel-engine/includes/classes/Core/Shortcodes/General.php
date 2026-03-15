<?php
/**
 * General Shortcode for WP Travel Engine.
 *
 * @since 6.1.3
 */

namespace WPTravelEngine\Core\Shortcodes;

use WPTravelEngine\Abstracts\Shortcode;
use WPTravelEngine\Core\Controllers\Checkout as CheckoutController;

/**
 * Place order form.
 *
 * Responsible for creating shortcodes for place order form and mainatain it.
 *
 * @package    Wp_Travel_Engine
 * @subpackage Wp_Travel_Engine/includes
 * @author
 */
class General extends Shortcode {
	/**
	 * Shortcode tag.
	 *
	 * @var string
	 */
	const TAG = 'WPTRAVELENGINE';

	/**
	 * Default attributes for the shortcode.
	 *
	 * @return array
	 */
	protected function default_attributes(): array {
		return array(
			'template' => '',
		);
	}

	/**
	 * Place order form shortcode callback function.
	 *
	 * @param $atts
	 *
	 * @return string
	 * @since 1.0
	 */
	public function output( $atts ): string {
		ob_start();
		if ( isset( $atts['template'] ) ) {
			$template = $atts['template'];
			wte_get_template(
				$template,
				$atts
			);
		}

		return ob_get_clean();
	}
}
