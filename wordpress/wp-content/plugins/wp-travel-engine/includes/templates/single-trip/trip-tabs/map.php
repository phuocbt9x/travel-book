<?php
/**
 * Map Template
 *
 * This template can be overridden by copying it to yourtheme/wp-travel-engine/single-trip/trip-tabs/map.php.
 *
 * @package Wp_Travel_Engine
 * @subpackage Wp_Travel_Engine/includes/templates
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

do_action( 'wte_before_map_content' );
echo "<div class='post-data'>";

/**
 * Hook - Display tab content title, left for themes.
 */
do_action( 'wte_map_tab_title' );
echo "<div class='content'>" . do_shortcode( '[wte_trip_map id=' . $post_id . ']' ) . '</div>';
echo '</div>';

do_action( 'wte_after_map_content' );
