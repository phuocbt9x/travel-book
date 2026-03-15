<?php
/**
 * Plugin Name:     CW Pattern Import
 * Plugin URI:      PLUGIN SITE HERE
 * Description:     PLUGIN DESCRIPTION HERE
 * Author:          YOUR NAME HERE
 * Author URI:      YOUR SITE HERE
 * Text Domain:     cw-pattern-import
 * Domain Path:     /languages
 * Version:         1.0.0
 *
 * @package         Cw_Pattern_Import
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once plugin_dir_path( __FILE__ ) . 'class-import-patterns.php';