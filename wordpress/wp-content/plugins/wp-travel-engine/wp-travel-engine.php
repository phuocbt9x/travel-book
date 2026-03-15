<?php
/**
 * The plugin bootstrap file
 *
 * WordPress reads this file to generate the plugin information in the plugin
 * admin area. This file also includes all the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://wptravelengine.com/
 * @since             1.0.0
 * @package           WP_Travel_Engine
 *
 * @wordpress-plugin
 * Plugin Name:       WP Travel Engine - Travel and Tour Booking Plugin
 * Plugin URI:        https://wordpress.org/plugins/wp-travel-engine/
 * Description:       WP Travel Engine is a free travel booking WordPress plugin to create travel and tour packages for tour operators and travel agencies. It is a complete travel management system and includes plenty of useful features. You can create your travel booking website using WP Travel Engine in less than 5 minutes.
 * Version:           6.7.7
 * Author:            WP Travel Engine
 * Author URI:        https://wptravelengine.com/
 * License:           GPLv3
 * License URI:       https://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain:       wp-travel-engine
 * Domain Path:       /languages
 * Requires at least: 5.8
 * Requires PHP: 7.4
 * Tested up to: 6.9
 */

defined( 'ABSPATH' ) || exit;

const WP_TRAVEL_ENGINE_FILE_PATH = __FILE__;
const WP_TRAVEL_ENGINE_VERSION   = '6.7.7';

/**
 * Load plugin updater file
 */
if ( ! version_compare( PHP_VERSION, '7.4', '>=' ) ) {
	add_action(
		'admin_notices',
		function () {
			echo wp_kses(
				sprintf(
					'<div class="wte-admin-notice error">%1$s</div>',
					__( "The PHP version doesn't meet requirement of WP Travel Engine, plugin is currently NOT RUNNING.", 'wp-travel-engine' )
				),
				array( 'div' => array( 'class' => array() ) )
			);
		}
	);
} elseif ( ! version_compare( get_bloginfo( 'version' ), '5.8', '>=' ) ) {
	add_action(
		'admin_notices',
		function () {
			echo wp_kses(
				sprintf(
					'<div class="wte-admin-notice error">%1$s</div>',
					__( 'The WordPress version is earlier than the minimum requirement to run WP Travel Engine, the plugin is NOT RUNNING.', 'wp-travel-engine' )
				),
				array( 'div' => array( 'class' => array() ) )
			);
		}
	);
} elseif ( ! class_exists( '\WPTravelEngine\Plugin', false ) ) {
	require plugin_dir_path( __FILE__ ) . 'vendor/autoload.php';
	require_once __DIR__ . '/includes/helpers/functions.php';
	require_once __DIR__ . '/includes/class-wte-session.php';
	/**
	* Engine starts.
	*/
	WPTravelEngine();
}
