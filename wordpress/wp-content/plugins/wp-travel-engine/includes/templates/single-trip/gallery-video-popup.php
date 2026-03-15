<?php
/**
 * Gallery popup video template.
 *
 * @package WP_Travel_Engine
 */

// Enqueue necessary scripts and styles.
wp_enqueue_script( 'jquery-fancy-box' );
wp_enqueue_style( 'jquery-fancy-box' );

// Display title if provided.
if ( ! empty( $args['title'] ) ) :
	?>
	<h3><?php echo esc_html( $args['title'] ); ?></h3>
	<?php
endif;

// Set default label if not provided.
$label = 'Video' === $args['label'] ? __( 'Video', 'wp-travel-engine' ) : esc_html( $args['label'] );
if ( ! empty( $args['gallery'] ) ) :
	$unique_id = uniqid();
	foreach ( $args['gallery'] as $key => $gallery_item ) :
		$video_id  = $gallery_item['id'];
		$video_url = 'youtube' === $gallery_item['type'] ? '//www.youtube.com/watch?v=' . $video_id : '//vimeo.com/' . $video_id;
		$slides[]  = array( 'src' => $video_url );
	endforeach;
	?>
	<span class="wp-travel-engine-vid-gal-popup">
		<a
			data-galtarget="#wte-video-gallary-popup-<?php echo esc_attr( $args['trip_id'] . $unique_id ); ?>"
			data-variable="<?php echo esc_attr( 'wtevideoGallery' . $unique_id ); ?>"
			href="#wte-video-gallary-popup-<?php echo esc_attr( $args['trip_id'] . $unique_id ); ?>"
			data-items="<?php echo esc_js( wp_json_encode( $slides ) ); ?>"
			class="wte-trip-vidgal-popup-trigger">
			<?php echo esc_html( $label ); ?>
		</a>
	</span>
	<?php
endif;
