<?php
/**
 * Render File for Fixed Starting Date Select Block.
 *
 * @var string $wrapper_attributes
 * @var Attributes $attributes_parser
 * @var Render $render
 * @package Wp_Travel_Engine
 * @since 5.9
 */

use WPTravelEngine\Blocks\SampleData;

global $wtetrip;
$sorted_fsd = array();
if ( $render->is_editor() ) {
	if ( defined( 'WTE_FIXED_DEPARTURE_VERSION' ) && version_compare( WTE_FIXED_DEPARTURE_VERSION, '2.4.0', '<' ) )  {
		$dates_data = SampleData::fsd();
		$months_arr = array_unique(
			array_map(
				function ( $fsd ) {
					return gmdate( 'Y-m', strtotime( $fsd[ 'start_date' ] ) );
				},
				$dates_data
			)
		);
	}
} else {
    $trip_id = $_GET[ 'post' ] ?? $post->ID;
    $trip_settings     = get_post_meta( $trip_id, 'wp_travel_engine_setting', true );
    $trip_duration_unit = isset( $trip_settings['trip_duration_unit'] ) ? $trip_settings['trip_duration_unit'] : 'days';
    if ( isset( $trip_id ) ) {
		$post_type = get_post_type( $trip_id );
	}
    if ( ! $trip_id ) {
        return;
    }
	$months_arr = array();
    if ( defined( 'WTE_FIXED_DEPARTURE_VERSION' ) ) {
		if ( version_compare( WTE_FIXED_DEPARTURE_VERSION, '2.4.0', '<' ) )  {
			$sorted_fsd    = call_user_func(
			    array( new WTE_Fixed_Starting_Dates_Shortcodes(), 'generate_fsds' ),
			    $trip_id,
			    array(
			        'year'  => '',
			        'month' => '',
			    )
			);
			$months_arr = array_unique(
				array_map(
					function ( $fsd ) {
						return gmdate( 'Y-m', strtotime( $fsd[ 'start_date' ] ) );
					},
					$sorted_fsd
				)
			);
			?>
			<div <?php echo esc_attr( $attributes_parser->wrapper_attributes() ); ?>>
				<div class="wte-fsd-list-header">
					<div class="wte-user-input">
						<select style="display: none;" class="fsd-date-select wpte-enhanced-select" name="date-select" data-placeholder="<?= esc_attr_e('Choose a date&hellip;', 'wp-travel-engine') ?>">
							<option value=" "><?= esc_html_e('Choose a date...', 'wp-travel-engine') ?></option>
							<?php foreach ( $months_arr as $key => $val ): ?>
								<option data-month="<?= esc_attr(date_i18n('m', strtotime($val))) ?>"
										value="<?= esc_attr($val) ?>"><?= esc_html(date_i18n('F, Y', strtotime($val))) ?></option>
							<?php endforeach; ?>
						</select>
					</div>
				</div>
			</div>
			<?php
		}
    }
}
?>

