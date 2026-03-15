<?php
/**
 * Single Trip Highlights Template
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
	$data = SampleData::highlights();
} else {
	$post_settings   = get_post_meta( $wtetrip->post->ID, 'wp_travel_engine_setting', true );
	$trip_highlights = $post_settings[ 'trip_highlights' ] ?? array();

	if ( ! is_array( $trip_highlights ) || empty( $trip_highlights ) ) {
		return;
	}

	$data = array_column( $trip_highlights, 'highlight_text' );

}

?>
    <div <?php $attributes_parser->wrapper_attributes(); ?> >
        <div class="highlights">
            <ul class="wpte-trip-highlights<?php echo $attributes_parser->get('divider') ? ' wte-block-has-divider' : ''; ?>">
				<?php foreach ( $data as $highlight ) { ?>
                    <li class="list-item-has-icon">
						<i class="wte-block-icon wte-block-icon__check" data-style="<?php echo esc_attr( $attributes_parser->get('iconView') ); ?>"></i>
						<?php echo esc_html( $highlight ); ?>
					</li>
				<?php } ?>
            </ul>
        </div>
</div>
<?php
