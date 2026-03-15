<?php

/**
 * Global Trip Highlights.
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
	$global_highlights = SampleData::global_highlights();
} else {
	$settings          = get_option( 'wp_travel_engine_settings', array() );
	$global_highlights = isset( $settings['trip_highlights'] ) && is_array( $settings['trip_highlights'] ) ? $settings['trip_highlights'] : array();
}

if ( count( $global_highlights ) > 0 ) :
	?>
	<div <?php $attributes_parser->wrapper_attributes(); ?>>
		<div class="wpte-bf-content">
			<ul class="<?php echo $attributes_parser->get( 'divider' ) ? 'wte-block-has-divider' : ''; ?>">
				<?php
				foreach ( $global_highlights as $highlight ) {
					$highlight = (object) $highlight;
					?>
					<li class="list-item-has-icon">
						<i class="wte-block-icon wte-block-icon__check" data-style="<?php echo esc_attr( $attributes_parser->get( 'iconView' ) ); ?>"></i>
						<?php
						echo esc_html( $highlight->highlight );
						if ( ! empty( $highlight->help ) ) {
							echo '<span class="wpte-custom-tooltip" data-title="' . esc_attr( $highlight->help ) . '"><i></i></span>';
						}
						?>
					</li>
					<?php
				}
				?>
			</ul>
		</div>
	</div>
<?php endif; ?>
