<?php
/**
 * Blossom Recipe Maker SEO Functions
 *
 * @package    WP Travel Engine
 *
 * @since       1.0.2
 * @deprecated  1119-schema-issue
 */

use WPTravelEngine\Core\SEO;

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Wp_Travel_Engine_SEO extends SEO {
	/**
	 * Constructor.
	 */
	public function __construct() {
		_deprecated_class( __CLASS__, '6.4.1', 'WPTravelEngine\Core\SEO' );
		parent::__construct();
	}
}
