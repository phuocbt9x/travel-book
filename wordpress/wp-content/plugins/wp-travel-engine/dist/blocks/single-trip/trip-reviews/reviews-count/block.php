<?php
/**
 * Trip Reviews Count Block.
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

$review_libray = new WTE_Trip_Review_Library();

if ( $render->is_editor() ) {
	$rating_count = SampleData::reviews_count();
} else {
	$comment_datas = $review_libray->pull_comment_data( $wtetrip->post->ID );

	if ( empty( $comment_datas ) ) {
		return;
	}
	$rating_count = isset( $comment_datas['i'] ) ? absint( $comment_datas['i'] ) : '';
}

?>
	<div <?php $attributes_parser->wrapper_attributes(); ?>>
		<div class="trip-rating-count-container">
			<div class="trip-rating-count">
				<span class="trip-rating-count-prefix">
					<?php
					echo wp_kses(
						str_replace( '%rating_count%', "<span class='trip-rating-count-number'>{$rating_count}</span>", $attributes_parser->get( 'ratingCount' ) ),
						array(
							'span' => array(
								'class' => array(),
							),
						)
					)
					?>
				</span>
			</div>
		</div>
	</div>
<?php
