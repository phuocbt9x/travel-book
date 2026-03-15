<?php

namespace WPTravelEngine\Core;

use WPTravelEngine\Helpers\Functions as HelperFunctions;
use WPTravelEngine\Utilities\RequestParser;

/**
 * Basic functions for the plugin.
 *
 * Maintain a list of functions that are used in the plugin for basic purposes
 *
 * @package    Wp_Travel_Engine
 * @subpackage Wp_Travel_Engine/includes
 * @author
 */

/**
 * class \WPTravelEngine\Core\Functions
 * Utility functions.
 *
 * @deprecated 6.0.0
 */
class Functions extends HelperFunctions {
}

\wte_functions()->init();

\class_alias( 'WPTravelEngine\Core\Functions', '\Wp_Travel_Engine_Functions' );
