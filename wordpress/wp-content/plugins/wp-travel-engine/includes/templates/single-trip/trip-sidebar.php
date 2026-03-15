<?php
/**
 * WTE Trip Sidebar
 *
 * This template can be overridden by copying it to yourtheme/wp-travel-engine/single-trip/trip-sidebar.php.
 *
 * @package Wp_Travel_Engine
 * @subpackage Wp_Travel_Engine/includes/templates
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * wp_travel_engine_trip_before_secondary_content hook.
 *
 * @hooked trip_secondary_content_start - 5 (secondary content open)
 */
do_action( 'wp_travel_engine_trip_before_secondary_content' );

/**
 * wp_travel_engine_trip_secondary_wrap hook.
 *
 * @hooked trip_secondary_wrap_start - 5 (secondary wrapper open)
 */
do_action( 'wp_travel_engine_trip_secondary_wrap' );

/**
 * wp_travel_engine_trip_price hook.
 *
 * @hooked display_trip_price - 5 (shows trip price)
 */
do_action( 'wp_travel_engine_trip_price' );

/**
 * wp_travel_engine_trip_facts hook.
 *
 * @hooked display_trip_facts - 5 (shows trip facts)
 */
do_action( 'wp_travel_engine_trip_facts' );

/**
 * wp_travel_engine_trip_gallery hook.
 *
 * @hooked wp_travel_engine_trip_gallery - 10 (shows trip gallery)
 */
do_action( 'wp_travel_engine_trip_gallery' );

/**
 * wp_travel_engine_trip_map hook.
 *
 * @hooked wp_travel_engine_trip_map - 10 (shows trip map)
 */
do_action( 'wp_travel_engine_trip_map' );

/**
 * wp_travel_engine_wte_sidebar.
 *
 * @hooked wte_widget_sidebar - 10 (shows trip map)
 */
do_action( 'wp_travel_engine_wte_sidebar' );

/**
 * wp_travel_engine_trip_secondary_wrap hook.
 *
 * @hooked trip_secondary_wrap_end - 5 (secondary wrapper close)
 */
do_action( 'wp_travel_engine_trip_after_secondary_content' );
