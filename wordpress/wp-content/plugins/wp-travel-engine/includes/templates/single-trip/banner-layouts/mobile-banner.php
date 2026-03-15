<?php
/**
 * Mobile Banner Layout.
 *
 * This template is also used for desktop banner layout 1.
 *
 * @since 6.3.3
 */
/**
 * @var string $banner_layout
 * @var bool $full_width_banner
 */
$fullwidth_class = $full_width_banner && 'banner-layout-1' === $banner_layout ? ' banner-layout-full' : '';
$image_size      = 'banner-layout-1' === $banner_layout ? 'full' : 'large'; // If it is desktop banner layout 1 then we need to show full size image.
?>
<div class="wpte-gallery-wrapper <?php echo esc_attr( $banner_layout ); ?>">
	<div class="wpte-multi-banner-layout<?php echo esc_attr( $fullwidth_class ); ?>">
		<div class="wpte-trip-feat-img">
			<?php
			the_post_thumbnail( $image_size );
			?>
		</div>
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