<?php
/**
 * Single Trip Map Template
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
	$wte_map = SampleData::map();
} else {
	$wp_travel_engine_setting = get_post_meta( $wtetrip->post->ID, 'wp_travel_engine_setting', true );
	$wte_map = $wp_travel_engine_setting[ 'map' ] ?? array();
	if ( isset( $wte_map[ 'image_url' ] ) ) {
		$attachment_id = $wte_map[ 'image_url' ];
		$src           = wp_get_attachment_image_src( $attachment_id, 'full' ) ?? array();
	}
	$wte_map[ 'image_url' ] = $src[ 0 ] ?? '';
}

if (
	( empty( $wte_map[ 'iframe' ] ) && empty( $wte_map[ 'image_url' ] ) )
	|| ! in_array( $attributes_parser->get( 'map' ), [ 'Iframe', 'Image', 'Both', ] ) ) {
	return;
}

?>
	<div <?php $attributes_parser->wrapper_attributes(); ?>>
		<?php
		if ( in_array( $attributes_parser->get( 'map' ), [ 'Iframe', 'Both' ] ) ) {
			?>
			<div class="trip-map iframe" style="width:100%">
				<?php echo wptravelengine_esc_iframe( $wte_map[ 'iframe' ] ); ?> <!-- phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped Escaping has been added on function wptravelengine_esc_iframe.-->
			</div>
			<?php
		} else {
			?>
			<div class="trip-map image" style="width: 100%">
				<img src="<?php echo esc_url( $wte_map[ 'image_url' ] ); ?>"
					 alt="<?php echo esc_attr__( 'Trip Map Image', 'wp-travel-engine' ); ?>" />
			</div>
			<?php
		}
		?>
	</div>
<?php
