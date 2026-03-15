<?php
/**
 *
 * @since 6.3.3
 */

defined( 'ABSPATH' ) || exit;

/**
 * @var string $banner_layout
 * @var array $list_images List of image sizes.
 * @var bool $show_image_gallery
 * @var bool $show_video_gallery
 * @var bool $full_width_banner Is full width banner enabled?
 */
$fullwidth_class = $full_width_banner && 'banner-layout-1' === $banner_layout ? ' banner-layout-full' : '';
?>
<div class="wpte-gallery-wrapper <?php echo esc_attr( $banner_layout ); ?>">
	<div class="wpte-multi-banner-layout<?php echo esc_attr( $fullwidth_class ); ?>">
		<?php
		/**
		 * Use this filter to generate markup for images.
		 *
		 * @param $list_images List of attachment IDs.
		 */
		$list_images = apply_filters( 'wptravelengine_trip_dynamic_banner_list_images', $list_images, $banner_layout, $show_image_gallery, $show_video_gallery );
		foreach ( $list_images as $image ) {
			if ( is_numeric( $image ) ) {
				continue;
			}
			echo wp_kses_post( $image );
		}
		?>
	</div>
	<?php
	if ( $show_image_gallery || $show_video_gallery ) {
		wptravelengine_get_template(
			'single-trip/banner-layouts/list-gallery.php',
			compact( 'show_image_gallery', 'show_video_gallery' )
		);
	}
	?>
</div>

