<?php
/**
 * Trip Search Result Template.
 * @package Wp_Travel_Engine
 * @since 5.9
 */
use WPTravelEngine\Modules\TripSearch;
TripSearch::enqueue_scripts();

do_action( 'wp_travel_engine_trip_archive_wrap' );