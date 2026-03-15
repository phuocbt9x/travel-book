<?php
/**
 * @var int $trip_id
 */

if ( ! defined( 'WTE_TRIP_REVIEW_VERSION' ) || ! function_exists( 'wptravelengine_reviews_get_trip_reviews' ) ) {
	return '';
}

$trip_reviews = wptravelengine_reviews_get_trip_reviews( $trip_id );

if ( ! isset( $trip_reviews['average'] ) || $trip_reviews['average'] <= 0 ) {
	return '';
}

// phpcs:disable
?>
<div class="wpte-trip-review-wrap">
    <span class="wpte-average-review-count"><?php echo esc_html( round( $trip_reviews['average'], 1 ) ); ?></span>
    <span class="wpte-review-star">
        <svg width="16" height="15" viewBox="0 0 16 15" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M6.63791 0.952996C7.17404 -0.209312 8.82596 -0.209311 9.36209 0.952996L10.4697 3.35428C10.6882 3.82799 11.1371 4.15416 11.6552 4.21558L14.2812 4.52694C15.5523 4.67764 16.0628 6.24872 15.123 7.11778L13.1815 8.91321C12.7985 9.2674 12.627 9.79515 12.7287 10.3068L13.2441 12.9005C13.4935 14.156 12.1571 15.127 11.0402 14.5018L8.73266 13.2101C8.27745 12.9553 7.72255 12.9553 7.26734 13.2101L4.95983 14.5018C3.84291 15.127 2.50647 14.156 2.75593 12.9005L3.27129 10.3068C3.37296 9.79515 3.20149 9.2674 2.81848 8.91321L0.876994 7.11778C-0.0627561 6.24872 0.44772 4.67764 1.71881 4.52694L4.34484 4.21558C4.86288 4.15416 5.31181 3.82799 5.53031 3.35428L6.63791 0.952996Z" fill="#FBB040" />
        </svg>
    </span>
    <span class="wpte-total-review-count"><?php printf( esc_html( _n( '(%d Review)', '(%d Reviews)', $trip_reviews['count'], 'wp-travel-engine' ) ), (float) $trip_reviews['count'] ); ?></span>
</div>
