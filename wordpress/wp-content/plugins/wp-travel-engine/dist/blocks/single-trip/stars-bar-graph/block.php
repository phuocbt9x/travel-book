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

use WPTravelEngine\Blocks\Icons;
use WPTravelEngine\Blocks\SampleData;

global $wtetrip;

$obj = new Wte_Trip_Review_Init();

if ( $render->is_editor() ) {
	$progress_bars = SampleData::star_bars();
} else {
	$comment_datas = $obj->pull_comment_data( $wtetrip->post->ID );

	if ( empty( $comment_datas ) ) {
		return;
	}
	$progress_bars = array(
		'very-happy' => array(
			'percent' => $comment_datas['five_stars_percent'] ?? '',
			'emoji'   => Icons::very_happy(),
			'text'    => __( 'Excellent', 'wp-travel-engine' ),
		),
		'happy'      => array(
			'percent' => $comment_datas['four_stars_percent'] ?? '',
			'emoji'   => Icons::happy(),
			'text'    => __( 'Very Good', 'wp-travel-engine' ),
		),
		'neutral'    => array(
			'percent' => $comment_datas['three_stars_percent'] ?? '',
			'emoji'   => Icons::confused(),
			'text'    => __( 'Average', 'wp-travel-engine' ),
		),
		'sad'        => array(
			'percent' => $comment_datas['two_stars_percent'] ?? '',
			'emoji'   => Icons::sad(),
			'text'    => __( 'Poor', 'wp-travel-engine' ),
		),
		'angry'      => array(
			'percent' => $comment_datas['one_stars_percent'] ?? '',
			'emoji'   => Icons::angry(),
			'text'    => __( 'Terrible', 'wp-travel-engine' ),
		),
	);
}

?>
	<div <?php $attributes_parser->wrapper_attributes(); ?>>
		<div class="trip-stars-bar-graph-container">
			<div class="trip-stars-bar-graph">
				<?php
				foreach ( $progress_bars as $key => $value ) {
					$progress_percent = $progress_bars[ $key ]['percent'];
					$icon             = $progress_bars[ $key ]['emoji'];
					$scale_text       = $progress_bars[ $key ]['text'];
					?>
					<div class="trip-stars-bar <?php echo esc_attr( "progress-bar-$key" ); ?>"
						style="--progress-percent:<?php echo esc_attr( round( $progress_percent ) ); ?>%;">
						<?php
						if ( $attributes_parser->get( 'showText' ) ) {
							?>
							<span class="trip-stars-bar-text">
								<?php echo esc_html( $scale_text ); ?>
							</span>
							<?php
						}
						if ( $attributes_parser->get( 'showIcon' ) ) {
							?>
							<span class="trip-stars-bar-image">
								<?php echo wp_kses( $icon, 'svg' ); ?>
							</span>
							<?php
						}
						?>
						<span class="trip-stars-bar-progress-bar"></span>
						<?php if ( $attributes_parser->get( 'showPercent' ) ) : ?>
							<span
								class="trip-stars-bar-progress-percent"><?php echo esc_html( round( $progress_percent ) ) . '%'; ?>
							</span>
						<?php endif; ?>
					</div>
					<?php
				}
				?>
			</div>
		</div>
	</div>
<?php
