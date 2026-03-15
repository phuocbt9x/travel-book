<?php
/**
 * Review Title Block
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

global $wtetrip;

$review_libray = new WTE_Trip_Review_Library();
$comment_data  = $render->block->context;
$comment_id    = $comment_data['commentId'] ?? '';

$review_title = get_comment_meta( $comment_id, 'title', true );
?>
	<div <?php $attributes_parser->wrapper_attributes(); ?>>
		<span class="trip-block-review-title"><?php echo esc_html( $review_title ); ?></span>
	</div>
<?php
