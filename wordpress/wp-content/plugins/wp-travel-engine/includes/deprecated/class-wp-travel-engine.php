<?php
/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @since      1.0.0
 *
 * @package    Wp_Travel_Engine
 * @subpackage Wp_Travel_Engine/includes
 */

use WPTravelEngine\Traits;
use WPTravelEngine\Plugin;

defined( 'ABSPATH' ) || exit;

/**
 * Main Class.
 *
 * @deprecated 6.0.0
 */
final class Wp_Travel_Engine {

	use Traits\Singleton;

	public function __construct() {
		wptravelengine_deprecated_class( __CLASS__, '6.0.0', Plugin::class );
	}
}

Wp_Travel_Engine::instance();
