<?php
/**
 * List gallery layout for banner.
 *
 * @since 6.3.3
 */

/**
 * @var string $banner_layout
 * @var array $list_images List of image sizes.
 * @var bool $show_image_gallery
 * @var bool $show_video_gallery
 * @var int $trip_id
 */

do_action( 'wptravelengine_trip_carousel', $list_images, $trip_id );
