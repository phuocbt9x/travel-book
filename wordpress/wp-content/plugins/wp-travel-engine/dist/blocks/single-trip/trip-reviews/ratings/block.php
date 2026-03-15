<?php
/**
 * Trip Ratings Block.
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

use WPTravelEngine\Blocks\SampleData;

global $wtetrip;

$review_libray = new \WTE_Trip_Review_Library();

if ( $render->is_editor() ) {
	$rating = SampleData::ratings();
} else {
	$comment_datas = $review_libray->pull_comment_data( $wtetrip->post->ID );

	if ( empty( $comment_datas ) ) {
		return;
	}
	$rating = $comment_datas['aggregate'] ?? '';
}

?>
	<div <?php $attributes_parser->wrapper_attributes(); ?>>
		<div class="trip-rating-container">
			<span class="trip-rating-point"><?php echo number_format( (float) $rating, 1 ); ?></span>
		</div>
	</div>
<?php
