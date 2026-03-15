<?php
/**
 * ShortCode Enquiry.
 *
 * @package    WPTravelEngine
 * @subpackage WPTravelEngine\Core\Shortcodes
 */

namespace WPTravelEngine\Core\Shortcodes;

use WPTravelEngine\Abstracts\Shortcode;
use WPTravelEngine\Core\Controllers\Shortcodes\EnquiryController;

/**
 * Class Enquiry.
 *
 * Responsible for creating shortcodes for Enquiry form displaying and maintaining it.
 *
 * @since 6.0.0
 */
class Enquiry extends Shortcode {
	/**
	 * Shortcode tag.
	 *
	 * @var string
	 */
	const TAG = 'WP_TRAVEL_ENGINE_TRIP_ENQUIRY_FORM';

	/**
	 * Retrieves the default attributes for the video gallery shortcode.
	 *
	 * @return array The default attributes.
	 */
	protected function default_attributes(): array {
		return array(
			'shortcode' => true,
		);
	}

	/**
	 * Retrieves the Enquiry shortcode output.
	 *
	 * This function ob_get_clean output for the enquiry form.
	 *
	 * @param array $atts The shortcode attributes.
	 * @return string The generated HTML output from wpte_enquiry_form function.
	 */
	public function output( $atts = array() ): string {
		$eqnuiry = new EnquiryController();
		return $eqnuiry->view( $atts );
	}
}
