<?php
/**
 * Rated Star Block.
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

$rated_star = get_comment_meta( $comment_id, 'stars', true );

?>
	<div <?php $attributes_parser->wrapper_attributes(); ?> >
		<div class="trip-rating-stars-container">
			<div class="trip-rating-stars">
				<div class="trip-rating-stars-placeholder-group">
					<?php
					for ( $i = 0; $i < 5; $i++ ) {
						?>
							<svg width="19" height="18" viewBox="0 0 19 18" fill="none" xmlns="http://www.w3.org/2000/svg">
							<path d="M8.47612 1.09395C8.84128 0.344864 9.90872 0.344863 10.2739 1.09395L12.2726 5.19412C12.4172 5.49069 12.6987 5.69686 13.0251 5.74516L17.5169 6.40996C18.3329 6.53074 18.66 7.53192 18.0727 8.11117L14.8082 11.3308C14.5759 11.5599 14.47 11.888 14.5245 12.2097L15.2925 16.7441C15.4312 17.5628 14.5694 18.184 13.8365 17.7938L9.84502 15.6683C9.5512 15.5118 9.1988 15.5118 8.90498 15.6683L4.91349 17.7938C4.1806 18.184 3.31885 17.5628 3.45751 16.7441L4.22555 12.2097C4.28004 11.888 4.17412 11.5599 3.94178 11.3308L0.677323 8.11117C0.0899991 7.53192 0.417086 6.53074 1.23311 6.40996L5.72489 5.74516C6.05126 5.69686 6.3328 5.49069 6.47737 5.19412L8.47612 1.09395Z" fill="currentColor"/>
							</svg>
						<?php
					}
					?>
				</div>
				<div class="trip-rating-stars-rated-group" style="width: <?php echo (int) $rated_star / 5 * 100; ?>%">
					<?php
					for ( $i = 0; $i < 5; $i++ ) {
						?>
							<svg width="19" height="18" viewBox="0 0 19 18" fill="none" xmlns="http://www.w3.org/2000/svg">
							<path d="M8.47612 1.09395C8.84128 0.344864 9.90872 0.344863 10.2739 1.09395L12.2726 5.19412C12.4172 5.49069 12.6987 5.69686 13.0251 5.74516L17.5169 6.40996C18.3329 6.53074 18.66 7.53192 18.0727 8.11117L14.8082 11.3308C14.5759 11.5599 14.47 11.888 14.5245 12.2097L15.2925 16.7441C15.4312 17.5628 14.5694 18.184 13.8365 17.7938L9.84502 15.6683C9.5512 15.5118 9.1988 15.5118 8.90498 15.6683L4.91349 17.7938C4.1806 18.184 3.31885 17.5628 3.45751 16.7441L4.22555 12.2097C4.28004 11.888 4.17412 11.5599 3.94178 11.3308L0.677323 8.11117C0.0899991 7.53192 0.417086 6.53074 1.23311 6.40996L5.72489 5.74516C6.05126 5.69686 6.3328 5.49069 6.47737 5.19412L8.47612 1.09395Z" fill="currentColor"/>
							</svg>
						<?php
					}
					?>
				</div>
			</div>
		</div>
	</div>
<?php
