<?php

/**
 * Render File for Duration Block.
 *
 * @var string $wrapper_attributes
 * @var Attributes $attributes_parser
 * @var Render $render
 * @package Wp_Travel_Engine
 * @since 5.9
 */

use WPTravelEngine\Blocks\SampleData;

global $wtetrip;

if ( $render->is_editor() ) {
	$duration_data = SampleData::duration();
	$duration = $duration_data[ 'duration' ];
	$duration_unit = $duration_data[ 'duration_unit' ];
} else {

	$post_meta = get_post_meta( $wtetrip->post->ID, 'wp_travel_engine_setting', true );

	$duration      = $post_meta[ 'trip_duration' ] ?? '';
	$duration_unit = $post_meta[ 'trip_duration_unit' ] ?? 'days';

}

if ( ! is_numeric( $duration ) || ! in_array( $duration_unit, [ 'days', 'hours' ] ) ) {
	return;
}
?>
	<div <?php $attributes_parser->wrapper_attributes(); ?>>
		<div class="wte-duration-container" style="display: flex;">
			<div class="wte-title-duration">
				<span class="duration">
					<?php echo esc_html( ! empty( $duration ) ? number_format_i18n( $duration ) : 0 ); ?>
				</span>
				<span class="days">
					<?php
					if ( 'days' === $duration_unit ) {
						echo esc_html( _nx( 'Day', 'Days', $duration, 'days', 'wp-travel-engine' ) );
					} else if ( 'hours' === $duration_unit ) {
						echo esc_html( _nx( 'Hour', 'Hours', $duration, 'hours', 'wp-travel-engine' ) );
					}
					?>
				</span>
			</div>
		</div>
	</div>
<?php
