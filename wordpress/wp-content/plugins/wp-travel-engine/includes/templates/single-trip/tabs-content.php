<?php
/**
 * Trip Tabs Content Template
 *
 * This template can be overridden by copying it to yourtheme/wp-travel-engine/single-trip/tabs-content.php.
 *
 * @package Wp_Travel_Engine
 * @subpackage Wp_Travel_Engine/includes/templates
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

// Filter to control tabs open behaviour.
$show_all_tabs = apply_filters( 'wte_single_trip_show_all_tabs', false );

if ( isset( $tabs['id'] ) ) : ?>
	<div class="tab-content delay_me" >
		<?php
		$order = 2;
		foreach ( array_values( $tabs['id'] ) as $index => $id ) :
			$field = $tabs['field'][ $id ];
			$icon  = isset( $tabs['icon'][ $id ] ) ? $tabs['icon'][ $id ] : '';
			/**
			 * @hook - wte_single_before_trip_tab_{field_name}
			 * Dynamic hooks before Tab wrapper - for themes to hook content into.
			 */
			do_action( "wte_single_before_trip_tab_{$field}" );
			?>
			<div id="nb-<?php echo esc_attr( $id ); ?>-configurations" class="nb-<?php echo esc_attr( $id ); ?>-configurations nb-configurations"
					style="order: <?php echo esc_attr( $order ); ?>;<?php echo ( 0 !== $index && ! $show_all_tabs ) ? 'display:none;' : ''; ?>" >
					<?php do_action( "wte_single_trip_tab_content_{$field}", $id, $field, $tabs['name'][ $id ], $icon ); ?>
			</div>
			<?php
			/**
			 * @hook - wte_single_after_trip_tab_{field_name}
			 * Dynamic hooks after Tab wrapper - for themes to hook content into.
			 */
			do_action( "wte_single_after_trip_tab_{$field}" );
			$order += 2;
		endforeach;
		?>
	</div>
	<!-- ./tab-content -->
</div>
<!-- /#tabs-container -->
	<?php
endif;

do_action( 'wp_travel_engine_after_trip_tabs' );
