<?php
/**
 * Experience Date Block
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

$experience_date = get_comment_meta( $comment_id, 'experience_date', true );
$converted_date  = date_i18n( 'F d, Y', strtotime( $experience_date ) );

?>
	<div <?php $attributes_parser->wrapper_attributes(); ?>>
		<span
			class="trip-block-review-experience-date"><?php echo esc_html( "Date of Experience: {$converted_date}" ); ?></span>
	</div>
<?php
