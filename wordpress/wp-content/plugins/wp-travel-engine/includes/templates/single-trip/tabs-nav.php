<?php

/**
 * Trip Tabs Nav Template
 *
 * Closing "tabs-container" div is left out on purpose!.
 *
 * This template can be overridden by copying it to yourtheme/wp-travel-engine/single-trip/tabs-nav.php.
 *
 * @package Wp_Travel_Engine
 * @subpackage Wp_Travel_Engine/includes/templates
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

do_action( 'wp_travel_engine_before_trip_tabs' );

$make_tabs_sticky = wte_array_get( get_option( 'wp_travel_engine_settings' ), 'wte_sticky_tabs', 'no' ) === 'yes';

if ( ! empty( $tabs['id'] ) ) : ?>
	<div id="tabs-container"
		class="wpte-tabs-container <?php echo esc_attr( $make_tabs_sticky ? 'wpte-tabs-sticky wpte-tabs-scrollable' : '' ); ?> clearfix">
		<div class="nav-tab-wrapper">
			<?php if ( $make_tabs_sticky ) : ?>
				<div class="wpte-sticky-tab-mobile">
					<?php
					$order = 1;
					foreach ( array_values( $tabs['id'] ) as $index => $values ) :
						?>
						<div class="tab-anchor-wrapper" style="order: <?php echo esc_attr( $order ); ?>;">
							<a href="#"
								class="nav-tab nb-tab-trigger <?php echo esc_attr( $index === 0 ? 'nav-tab-active' : '' ); ?>"
								data-configuration="<?php echo esc_attr( $values ); ?>" 
								role="tab" 
								aria-selected="<?php echo esc_attr( $index === 0 ? 'true' : 'false' ); ?>">
								<?php
								if ( isset( $tabs['icon'][ $values ] ) && $tabs['icon'][ $values ] !== '' ) {
									$icon_data = isset( $tabs['icon'][ $values ]['id'] ) ? wp_get_attachment_image( $tabs['icon'][ $values ]['id'], 'thumbnail', true ) : wptravelengine_svg_by_fa_icon( $tabs['icon'][ $values ], false );
									echo '<span class="tab-icon">' . $icon_data . '</span>';
								}
								echo esc_html( $tabs['name'][ $values ] );
								?>
							</a>
						</div>
						<?php
						$order += 2;
					endforeach;
					?>
				</div>
			<?php endif; ?>
			<div class="tab-inner-wrapper">
				<?php
				$order = 1;
				foreach ( array_values( $tabs['id'] ) as $index => $values ) :
					?>
					<div class="tab-anchor-wrapper" style="order: <?php echo esc_attr( $order ); ?>;">
						<h2 class="wte-tab-title">
							<a href="#"
								class="nav-tab nb-tab-trigger <?php echo esc_attr( $index === 0 ? 'nav-tab-active' : '' ); ?>"
								data-configuration="<?php echo esc_attr( $values ); ?>" 
								role="tab" 
								aria-selected="<?php echo esc_attr( $index === 0 ? 'true' : 'false' ); ?>">
								<?php
								if ( isset( $tabs['icon'][ $values ] ) && $tabs['icon'][ $values ] !== '' ) {
									$icon_data = isset( $tabs['icon'][ $values ]['id'] ) ? wp_get_attachment_image( $tabs['icon'][ $values ]['id'], 'thumbnail', true ) : wptravelengine_svg_by_fa_icon( $tabs['icon'][ $values ], false );
									echo '<span class="tab-icon">' . $icon_data . '</span>';
								}
								echo esc_html( $tabs['name'][ $values ] );
								?>
							</a>
						</h2>
					</div>
					<?php
					$order += 2;
			endforeach;
				?>
			</div>
		</div>
		<!-- ./nav-tab-wrapper -->
	<?php
endif;
