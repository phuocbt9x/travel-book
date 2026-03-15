<?php
/**
 * Trip gallery template.
 *
 * This template can be overridden by copying it to yourtheme/wp-travel-engine/single-trip/gallery.php.
 *
 * @package Wp_Travel_Engine
 * @subpackage Wp_Travel_Engine/includes/templates
 * @since @release-version
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
global $post;

$wptravelengine_settings = get_option( 'wp_travel_engine_settings', array() );
$banner_layout           = $wptravelengine_settings['trip_banner_layout'] ?? 'banner-default';

if ( ! ( 'banner-default' === $banner_layout || 'banner-layout-6' === $banner_layout || $related_query ) ) {
	do_action( 'wptravelengine_trip_dynamic_banner', $post->ID );
	return;
}

echo '<div class="wpte-gallery-wrapper__multi-banners">';
wptravelengine_get_template( 'single-trip/main-gallery.php' );
echo '</div>';
