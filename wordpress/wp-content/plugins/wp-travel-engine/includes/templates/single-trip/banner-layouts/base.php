<?php
/**
 * Base layout for banner.
 *
 * @since 6.3.3
 */

/**
 * @var string $specific_layout Layouts other than layout-1 and layout-5.
 * @var bool $is_mobile_view
 */
$specific_layout && print( '<div class="trip-content-area">' );

if ( $is_mobile_view ) {
	wptravelengine_get_template( 'single-trip/banner-layouts/mobile-banner.php' );
} else {
	wptravelengine_get_template( 'single-trip/banner-layouts/list.php' );
}

$specific_layout && print( '</div>' );
