<?php
/**
 * Render File for Advanced Itinerary Chart block.
 *
 * @var string $wrapper_attributes
 * @var Attributes $attributes_parser
 * @var Render $render
 * @package Wp_Travel_Engine
 * @since 5.9
 */

global $wtetrip;

if ( ! defined( 'WTEAI_VERSION' ) ) {
	?>
	<div class="notice">
		<?php echo esc_html__( 'Please activate WP Travel Engine - Advanced Itinerary addon for this block to work.', 'wp-travel-engine' ); ?>
	</div>
	<?php
} else {
	$settings        = get_option( 'wp_travel_engine_settings', array() );
	$post_meta       = get_post_meta( $wtetrip->post->ID, 'trip_itinerary_chart_data', true );
	$chart_data      = json_decode( $post_meta, true );
	$chart_data_unit = ! empty( $settings['wte_advance_itinerary']['chart']['alt_unit'] ) ? $settings['wte_advance_itinerary']['chart']['alt_unit'] : 'm';

	// Separate the extracted data into separate arrays.
	$altitude_unit_data   = ! empty( $chart_data ) ? array_column( $chart_data, 'altitude' ) : array();
	$altitude_labels_data = ! empty( $chart_data ) ? array_column( $chart_data, 'at' ) : array();
	$altitude_unit        = $attributes_parser->get( 'altitudeUnit' );
	$elevation_unit_label = $attributes_parser->get( 'altitudeUnitLabel' );
	$show_linegraph       = $attributes_parser->get( 'showLG' );
	$show_x               = $attributes_parser->get( 'showX' );
	$show_y               = $attributes_parser->get( 'showY' );
	$theme_color          = $attributes_parser->get( 'themeColor' ) ?? 'var(--primary-color)';
	$x_scale_label        = $attributes_parser->get( 'xScaleLabel' );
	$y_scale_label        = $attributes_parser->get( 'yScaleLabel' );
	$tension              = $attributes_parser->get( 'tension' );

	if ( $chart_data_unit !== $altitude_unit ) {
		$conversion_factor  = 'm' === $chart_data_unit ? 3.28084 : 0.3048;
		$altitude_unit_data = array_map(
			function( $altitude ) use ( $conversion_factor ) {
				return round( $altitude * $conversion_factor );
			},
			$altitude_unit_data
		);
	}

	$new_chart_data = array(
		'altitudeUnitData'   => $altitude_unit_data,
		'altitudeLabelsData' => $altitude_labels_data,
		'altitudeUnit'       => $altitude_unit,
		'showLG'             => $show_linegraph,
		'showX'              => $show_x,
		'showY'              => $show_y,
		'themeColor'         => $theme_color,
		'xScaleLabel'        => $x_scale_label,
		'yScaleLabel'        => $y_scale_label,
		'tension'            => $tension,
	);
	wp_localize_script(
		'editor',
		'chartData',
		apply_filters(
			'chartData',
			$new_chart_data,
			$wtetrip->post->ID
		)
	);
	wp_enqueue_script( 'editor' );
	$labels = array(
		'm'  => __( 'M.', 'wp-travel-engine' ),
		'ft' => __( 'FT.', 'wp-travel-engine' ),
	);
	?>
	<div <?php $attributes_parser->wrapper_attributes(); ?>>
		<div class="altitude-chart-container">
			<?php if ( $attributes_parser->get( 'showAltitudeUnit' ) ) { ?>
				<div class="altitude-unit-switcher">
					<label class="altitude-unit-label"><?php echo esc_html( $elevation_unit_label ); ?></label>
					<div class="altitude-unit-switches">
						<span><input type="radio" value="m" name="altitude-unit" id="altitude-unit-m" checked><label for="altitude-unit-m"><?php echo esc_html( $labels['m'] ); ?></label></span>
						<span><input type="radio" value="ft" name="altitude-unit" id="altitude-unit-ft"><label for="altitude-unit-ft"><?php echo esc_html( $labels['ft'] ); ?></label></span>
					</div>
				</div>
				<?php
			}
			if ( ! empty( $altitude_unit_data ) || ! empty( $altitude_labels_data ) ) {
				?>
				<div>
					<div id="altitude-chart-screen">
						<canvas id="altChart" class="ate-alt-chart"></canvas>
					</div>
				</div>
			<?php } ?>
		</div>
	</div>
	<?php
}
