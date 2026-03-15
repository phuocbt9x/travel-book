<?php
/**
 *
 * @since 5.5.3
 */

namespace WPTravelEngine\Core;

use WPTravelEngine\Core\Models\Settings\PluginSettings;

/**
 * Class Settings
 */
class Settings extends PluginSettings {

	/**
	 * Constructor.
	 */
	public function __construct() {
		_deprecated_class( __CLASS__, '6.0.0', 'WPTravelEngine\Core\Models\Settings\PluginSettings' );
		parent::__construct();
	}
}
