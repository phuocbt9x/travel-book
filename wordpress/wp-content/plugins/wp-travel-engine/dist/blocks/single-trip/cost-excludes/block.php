<?php
/**
 * Single Trip Cost Excludes Template.
 *
 * @var Attributes $attributes_parser
 * @var string $wrapper_attributes
 * @var Render $render
 * @package Wp_Travel_Engine
 * @since 5.9
 */

use WPTravelEngine\Blocks\SampleData;

global $wtetrip;

if ( $render->is_editor() ) {
	$data = SampleData::cost_excludes();
} else {

	/**
	 * Fetch the metadata associated with the trip post.
	 */
	$trip_settings = get_post_meta( $wtetrip->post->ID, 'wp_travel_engine_setting', true ) ?? false;

	if ( empty( $trip_settings['cost']['cost_excludes'] ) ) {
		return;
	}

	$data = preg_split( '/\r\n|[\r\n]/', $trip_settings['cost']['cost_excludes'] );
}

?>
	<div <?php $attributes_parser->wrapper_attributes(); ?>>
		<ul class="cost-includes-excludes-list<?php echo $attributes_parser->get( 'divider' ) ? ' wte-block-has-divider' : ''; ?>">
			<?php
			foreach ( $data as $key => $exclude ) {
				echo '<li class="list-item-has-icon"> <i class="wte-block-icon wte-block-icon__times" data-style="' . esc_attr( $attributes_parser->get( 'iconView' ) ) . '"></i>' . wp_kses_post( $exclude ) . '</li>';
			}
			?>
		</ul>
	</div>
<?php
