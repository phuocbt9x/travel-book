<?php

/**
 * @var Render $render
 * @var string $wrapper_attributes
 * @var Attributes $attributes_parser
 * @package Wp_Travel_Engine
 * @since 5.9
 */

use WPTravelEngine\Blocks\SampleData;

global $wtetrip;

if ( $render->is_editor() ) {
	$trip_facts = SampleData::trip_facts();
} else {

	$global_trip_facts = wptravelengine_get_trip_facts_options();
	$trip_settings     = get_post_meta( $wtetrip->post->ID, 'wp_travel_engine_setting', true );
	$_trip_facts       = isset( $trip_settings['trip_facts'] ) && is_array( $trip_settings['trip_facts'] ) ? $trip_settings['trip_facts'] : array();

	if ( empty( $_trip_facts ) ) {
		return;
	}

	$trip_facts = array();
	if ( ! empty( $_trip_facts['field_type'] ) && is_array( $_trip_facts ) ) {
		foreach ( $_trip_facts['field_type'] as $key => $value ) {
			$trip_facts[] = array(
				'title'   => $global_trip_facts['field_id'][ $key ] ?? '',
				'icon'    => $global_trip_facts['field_icon'][ $key ] ?? '',
				'content' => isset( $_trip_facts[ $key ][ $key ] ) ? $_trip_facts[ $key ][ $key ] : '',
			);
		}
	}
}

?>
<div <?php $attributes_parser->wrapper_attributes(); ?>>
	<div class="secondary-trip-info">
		<ul class="trip-facts-value wte-columns-<?php echo esc_attr( $attributes_parser->get( 'columns' ) ); ?>">
			<?php
			foreach ( $trip_facts as $trip_fact ) :
				?>
				<li class="vertical-align-<?php echo esc_attr( $attributes_parser->get( 'verticalAlignment' ) ); ?> icon-<?php echo esc_attr( $attributes_parser->get( 'iconPosition' ) ); ?> align-<?php echo esc_attr( $attributes_parser->get( 'alignment' ) ); ?>">
					<?php if ( $attributes_parser->get( 'showIcon' ) ) : ?>
						<span class="icon-holder">
							<?php wptravelengine_svg_by_fa_icon( $trip_fact['icon'] ); ?>
						</span>
					<?php endif; ?>

					<div class="trip-facts-text">
						<label>
							<?php echo esc_html( $trip_fact['title'] ); ?>
						</label>
						<div class="value">
							<?php echo wp_kses( $trip_fact['content'], array( 'a' => array( 'href' => array() ) ) ); ?>
						</div>
					</div>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>
</div>
