<?php
/**
 * Trip Archive Template.
 * @package Wp_Travel_Engine
 * @since 5.9
 */
use WPTravelEngine\Modules\TripSearch;
TripSearch::enqueue_scripts();

wp_enqueue_script( 'wp-travel-engine' );
wp_enqueue_style( 'wp-travel-engine' );
// Main wrapper
do_action( 'wp_travel_engine_trip_archive_outer_wrapper' );

// Loop starts
do_action( 'wp_travel_engine_trip_archive_loop_start' );

// Inner wrapper
do_action( 'wp_travel_engine_trip_archive_wrap' );

// Loop ends
do_action( 'wp_travel_engine_trip_archive_loop_end' );

// Pagination for archive
do_action( 'wp_travel_engine_trip_archive_pagination' );

// Main wrapper close
do_action( 'wp_travel_engine_trip_archive_outer_wrapper_close' );

/* Omit closing PHP tag at the end of PHP files to avoid "headers already sent" issues. */
