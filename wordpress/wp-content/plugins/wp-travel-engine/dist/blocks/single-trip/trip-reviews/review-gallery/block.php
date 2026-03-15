<?php
/**
 * Review Gallery Block
 *
 * @package Wp_Travel_Engine
 * @since 5.9
 * @var Render $render
 * @var Attributes $attributes_parser
 * @var string $wrapper_attributes
 */

if ( ! defined( 'WTE_TRIP_REVIEW_VERSION' ) ) {
	return;
}

$comment_data   = $render->block->context;
$comment_id     = $comment_data['commentId'] ?? '';
$gallery_images = get_comment_meta( $comment_id, 'gallery_images', true );

if ( isset( $gallery_images ) && ! is_object( $gallery_images ) && ! empty( $gallery_images ) ) {
	?>
	<div <?php $attributes_parser->wrapper_attributes(); ?> >
		<figure class="trip-block-review-gallery">
			<?php
			foreach ( $gallery_images as $key => $id ) {
				$image_thumbnail = wp_get_attachment_image( $id, 'thumbnail' );
				$image_full      = wp_get_attachment_image_url( $id, 'large' );
				if ( ! empty( $image_thumbnail ) ) {
					?>
					<figure class="trip-block-review-image">
						<a class="trip-block-review-image-link" href="<?php echo esc_url( $image_full ); ?>" data-fancybox="review-gallery" >
							<?php echo $image_thumbnail; ?> <!-- phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -->
						</a>
					</figure>
					<?php
				}
			}
			?>
		</figure>
	</div>
	<?php
}

