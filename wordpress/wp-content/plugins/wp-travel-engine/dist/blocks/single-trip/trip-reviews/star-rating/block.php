<?php
/**
 * Trip Reviews Block.
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
	$average_rating = SampleData::star_ratings();
} else {
	$comment_datas = $review_libray->pull_comment_data( $wtetrip->post->ID );

	if ( empty( $comment_datas ) ) {
		return;
	}
	$average_rating = $comment_datas['aggregate'] ?? '';
}

$scale_text = array(
	__( 'Terrible', 'wp-travel-engine' ),
	__( 'Poor', 'wp-travel-engine' ),
	__( 'Average', 'wp-travel-engine' ),
	__( 'Very Good', 'wp-travel-engine' ),
	__( 'Excellent', 'wp-travel-engine' ),
);

$rating_index = max( 0, min( 4, intval( $average_rating ) - 1 ) );
// Display the corresponding scale text
$scale_rating = $scale_text[ $rating_index ];

?>
	<div <?php $attributes_parser->wrapper_attributes(); ?> >
		<div class="trip-rating-stars-container">
			<div class="trip-rating-stars">
				<div class="trip-rating-stars-placeholder-group">
					<?php
					for ( $i = 0; $i < 5; $i ++ ) {
						?>
						<svg width="19" height="18" viewBox="0 0 19 18" fill="none" xmlns="http://www.w3.org/2000/svg">
							<path
								d="M8.47612 1.09395C8.84128 0.344864 9.90872 0.344863 10.2739 1.09395L12.2726 5.19412C12.4172 5.49069 12.6987 5.69686 13.0251 5.74516L17.5169 6.40996C18.3329 6.53074 18.66 7.53192 18.0727 8.11117L14.8082 11.3308C14.5759 11.5599 14.47 11.888 14.5245 12.2097L15.2925 16.7441C15.4312 17.5628 14.5694 18.184 13.8365 17.7938L9.84502 15.6683C9.5512 15.5118 9.1988 15.5118 8.90498 15.6683L4.91349 17.7938C4.1806 18.184 3.31885 17.5628 3.45751 16.7441L4.22555 12.2097C4.28004 11.888 4.17412 11.5599 3.94178 11.3308L0.677323 8.11117C0.0899991 7.53192 0.417086 6.53074 1.23311 6.40996L5.72489 5.74516C6.05126 5.69686 6.3328 5.49069 6.47737 5.19412L8.47612 1.09395Z"
								fill="currentColor" />
						</svg>
						<?php
					}
					?>
				</div>
				<div class="trip-rating-stars-rated-group" style="width: <?php echo (int) $average_rating / 5 * 100; ?>%">
					<?php
					for ( $i = 0; $i < 5; $i ++ ) {
						?>
						<svg width="19" height="18" viewBox="0 0 19 18" fill="none" xmlns="http://www.w3.org/2000/svg">
							<path
								d="M8.47612 1.09395C8.84128 0.344864 9.90872 0.344863 10.2739 1.09395L12.2726 5.19412C12.4172 5.49069 12.6987 5.69686 13.0251 5.74516L17.5169 6.40996C18.3329 6.53074 18.66 7.53192 18.0727 8.11117L14.8082 11.3308C14.5759 11.5599 14.47 11.888 14.5245 12.2097L15.2925 16.7441C15.4312 17.5628 14.5694 18.184 13.8365 17.7938L9.84502 15.6683C9.5512 15.5118 9.1988 15.5118 8.90498 15.6683L4.91349 17.7938C4.1806 18.184 3.31885 17.5628 3.45751 16.7441L4.22555 12.2097C4.28004 11.888 4.17412 11.5599 3.94178 11.3308L0.677323 8.11117C0.0899991 7.53192 0.417086 6.53074 1.23311 6.40996L5.72489 5.74516C6.05126 5.69686 6.3328 5.49069 6.47737 5.19412L8.47612 1.09395Z"
								fill="currentColor" />
						</svg>
						<?php
					}
					?>
				</div>

				<?php if ( $attributes_parser->get( 'enableScaleText' ) ) : ?>
					<span class="trip-rating-scale-text <?php echo esc_attr( $attributes_parser->get( 'scaleTextPosition' ) ); ?>">
						<?php echo esc_html( $scale_rating ); ?>
					</span>
				<?php endif; ?>
			</div>
		</div>
	</div>
<?php
