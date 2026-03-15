<?php
/**
 * General Performance Settings.
 *
 * @since 6.0.5
 */

$optimized_loading = wptravelengine_settings()->get( 'enable_optimize_loading', false );
$lazy_loading      = wptravelengine_settings()->get( 'enable_lazy_loading', false );
$map_lazy_loading  = wptravelengine_settings()->get( 'enable_map_lazy_loading', false );
$img_lazy_loading  = wptravelengine_settings()->get( 'enable_img_lazy_loading', false );
?>
<!-- Enable Optimized Loading for better performance. -->
<div class="wpte-field wpte-checkbox advance-checkbox">
	<label class="wpte-field-label" style="padding-right:50px" data-wte-update="wte_new_6.0.5" data-tag="beta" for="wp_travel_engine_settings[enable_optimize_loading]"><?php _e( 'Optimized Loading', 'wp-travel-engine' ); ?></label>
	<div class="wpte-checkbox-wrap">
		<input type="hidden" name="wp_travel_engine_settings[enable_optimize_loading]" value="no" >
		<input type="checkbox" id="wp_travel_engine_settings[enable_optimize_loading]" name="wp_travel_engine_settings[enable_optimize_loading]" value="yes" <?php checked( $optimized_loading, 'yes' ); ?>>
		<label for="wp_travel_engine_settings[enable_optimize_loading]" class="checkbox-label"></label>
	</div>
	<span class="wpte-tooltip"><?php esc_html_e( 'This feature adds conditional loading of the assets to improve the loading speed.', 'wp-travel-engine' ); ?> </span>
</div>
<!-- Enable Lazy Loading for better performance. -->
<div class="wpte-field wpte-checkbox advance-checkbox">
	<label class="wpte-field-label" style="padding-right:50px" data-wte-update="wte_new_6.0.5" data-tag="beta" for="wp_travel_engine_settings[enable_lazy_loading]"><?php _e( 'Lazy Loading', 'wp-travel-engine' ); ?></label>
	<div class="wpte-checkbox-wrap">
		<input type="hidden" name="wp_travel_engine_settings[enable_lazy_loading]" value="no" >
		<input type="checkbox" id="wp_travel_engine_settings[enable_lazy_loading]"
		name="wp_travel_engine_settings[enable_lazy_loading]" value="yes"
		data-onchange
		data-onchange-toggle-target="[data-enable-lazy-loading]"
		data-onchange-toggle-off-value="no"
		<?php checked( $lazy_loading, 'yes' ); ?>>
		<label for="wp_travel_engine_settings[enable_lazy_loading]" class="checkbox-label"></label>
	</div>
	<span class="wpte-tooltip"><?php esc_html_e( 'This experimental feature will lazy load the different media on your single trip to improve the performance and load your site faster.', 'wp-travel-engine' ); ?> </span>
</div>
<div class="wpte-field-subfields<?php echo 'yes' === $lazy_loading ? '' : ' hidden'; ?>" data-enable-lazy-loading>
<!-- Enable Map Lazy Loading -->
<div class="wpte-field wpte-checkbox advance-checkbox">
	<label class="wpte-field-label" for="wp_travel_engine_settings[enable_map_lazy_loading]"><?php _e( 'Map Lazy Loading', 'wp-travel-engine' ); ?></label>
	<div class="wpte-checkbox-wrap">
		<input type="hidden" name="wp_travel_engine_settings[enable_map_lazy_loading]" value="no" >
		<input type="checkbox" id="wp_travel_engine_settings[enable_map_lazy_loading]"
		name="wp_travel_engine_settings[enable_map_lazy_loading]" value="yes"
		<?php checked( $map_lazy_loading, 'yes' ); ?>>
		<label for="wp_travel_engine_settings[enable_map_lazy_loading]" class="checkbox-label"></label>
	</div>
	<span class="wpte-tooltip"><?php esc_html_e( 'This feature delays the loading of Map iframe until the user interacts on your site.', 'wp-travel-engine' ); ?> </span>
</div>
<!-- Enable Images Lazy Loading for better performance. -->
<div class="wpte-field wpte-checkbox advance-checkbox">
	<label class="wpte-field-label" for="wp_travel_engine_settings[enable_img_lazy_loading]"><?php _e( 'Image Lazy Loading', 'wp-travel-engine' ); ?></label>
	<div class="wpte-checkbox-wrap">
		<input type="hidden" name="wp_travel_engine_settings[enable_img_lazy_loading]" value="no" >
		<input type="checkbox" id="wp_travel_engine_settings[enable_img_lazy_loading]"
		name="wp_travel_engine_settings[enable_img_lazy_loading]" value="yes"
		data-onchange
		data-onchange-toggle-target="[data-enable-img-lazy-loading]"
		data-onchange-toggle-off-value="no"
		<?php checked( $img_lazy_loading, 'yes' ); ?>>
		<label for="wp_travel_engine_settings[enable_img_lazy_loading]" class="checkbox-label"></label>
	</div>
	<span class="wpte-tooltip"><?php esc_html_e( 'This feature will lazy load your images to boost the performance of your site to load faster.', 'wp-travel-engine' ); ?> </span>
</div>
</div>

