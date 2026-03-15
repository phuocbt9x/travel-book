<?php
/**
 * Single Trip Cost Includes Template.
 *
 * @var string $wrapper_attributes
 * @var Render $render
 * @var Attributes $attributes_parser
 * @package Wp_Travel_Engine
 * @since 5.9
 */

use WPTravelEngine\Blocks\SampleData;

global $wtetrip;

if ( $render->is_editor() ) {
	$data = SampleData::cost_includes();
} else {
	/**
	 * Fetch the metadata associated with the trip post.
	 */
	$trip_settings = get_post_meta( $wtetrip->post->ID, 'wp_travel_engine_setting', true ) ?? false;

	if ( empty( $trip_settings['cost']['cost_includes'] ) ) {
		return;
	}

	$data = preg_split( '/\r\n|[\r\n]/', $trip_settings['cost']['cost_includes'] );
}
?>
	<div <?php $attributes_parser->wrapper_attributes(); ?>>
		<ul class="cost-includes-excludes-list<?php echo $attributes_parser->get( 'divider' ) ? ' wte-block-has-divider' : ''; ?>">
			<?php
			foreach ( $data as $key => $include ) {
				echo '<li class="list-item-has-icon"> <i class="wte-block-icon wte-block-icon__check" data-style="' . esc_attr( $attributes_parser->get( 'iconView' ) ) . '"></i>' . wp_kses_post( $include ) . '</li>';
			}
			?>
		</ul>
	</div>
<?php
