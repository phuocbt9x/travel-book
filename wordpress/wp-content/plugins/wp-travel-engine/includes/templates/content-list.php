<?php

/**
 * Template part for displaying list posts
 *
 * This template can be overridden by copying it to yourtheme/wp-travel-engine/content-list.php.
 *
 * @package Wp_Travel_Engine
 * @subpackage Wp_Travel_Engine/includes/templates
 * @since @release-version //TODO: change after travel muni is live
 */
wp_enqueue_script( 'wte-popper' );
wp_enqueue_script( 'wte-tippyjs' );

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
global $post;
$settings         = get_option( 'wp_travel_engine_settings', array() );
$is_featured      = wte_is_trip_featured( get_the_ID() );
$wpte_trip_images = get_post_meta( get_the_ID(), 'wpte_gallery_id', true );

$new_trip_listing         = isset( $settings['display_new_trip_listing'] ) && $settings['display_new_trip_listing'] == 'yes';
$set_duration_type        = isset( $settings['set_duration_type'] ) && ! empty( $settings['set_duration_type'] ) ? $settings['set_duration_type'] : 'days';
$wp_travel_engine_setting = get_post_meta( get_the_ID(), 'wp_travel_engine_setting', true );

$view_mode = 'list';

$classes = 'category-trips-single wpte_new-layout';

if ( $is_featured ) {
	$classes .= ' __featured-trip';
}
?>
<div data-thumbnail="default" class="<?php echo esc_attr( $classes ); ?>" style="--span: 20;" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
	<div class="category-trips-single-inner-wrap">
		<?php
			do_action( 'wptravelengine_before_trip_archive_card' );

			wptravelengine_get_template( 'trip-card/index.php', compact( 'view_mode' ) );

			do_action( 'wptravelengine_after_trip_archive_card' );
		?>
	</div>
</div>
