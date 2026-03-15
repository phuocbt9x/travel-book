<?php
/**
 * Trip Archive Display Setting
 *
 * @since 5.5.7
 */
$settings      = get_option( 'wp_travel_engine_settings', array() );
$archive_title = isset( $settings['archive']['title'] ) ? $settings['archive']['title'] : '';

?>
<!-- Hide Archive Title -->
<div class="wpte-field wpte-checkbox advance-checkbox">
	<label class="wpte-field-label" data-wte-update="wte_new_5.5.5" for="hide_term_title"><?php esc_html_e( 'Hide Archive Title', 'wp-travel-engine' ); ?></label>
	<div class="wpte-checkbox-wrap">
		<input type="hidden" name="wp_travel_engine_settings[hide_term_title]" value="no">
		<?php $hide_title = isset( $settings['hide_term_title'] ) && 'yes' === $settings['hide_term_title']; ?>
		<input type="checkbox" id="hide_term_title" class="" value="yes"
			name="wp_travel_engine_settings[hide_term_title]"
			<?php checked( $hide_title, true ); ?> />
		<label for="hide_term_title"></label>
	</div>
	<span class="wpte-tooltip"><?php esc_html_e( 'The Archive titles (Destination, Trip Types, Activities, etc) will not display if you enable this feature.', 'wp-travel-engine' ); ?></span>
</div>
<!-- Sort Trips By -->
<div class="wpte-field wpte-floated">
	<label for="wptravelengine_trip_sort_by" data-wte-update="wte_new_5.5.7" class="wpte-field-label"><?php esc_html_e( 'Sort Trips By', 'wp-travel-engine' ); ?></label>
	<select id="wptravelengine_trip_sort_by" name="wptravelengine_trip_sort_by">
	<?php
	$sorting_options = wptravelengine_get_sorting_options();
	$sort_by         = get_option( 'wptravelengine_trip_sort_by', 'latest' );
	foreach ( $sorting_options as $name => $sorting_args ) {
		if ( isset( $sorting_args['options'] ) ) {
			printf( '<optgroup label="%s">', esc_attr( $sorting_args['label'] ) );
			foreach ( $sorting_args['options'] as $_name => $sorting_option ) {
				printf( '<option value="%1$s" %3$s>%2$s</option>', esc_html( $_name ), esc_html( $sorting_option ), selected( $_name, $sort_by, false ) );
			}
			print '</optgroup>';
		} else {
			printf( '<option value="%1$s" %3$s>%2$s</option>', esc_html( $name ), esc_html( $sorting_args ), selected( $name, $sort_by, false ) );
		}
	}
	?>
	</select>
	<span class="wpte-tooltip"><?php esc_html_e( 'Choose the sorting type in which trips should be listed on archive pages.', 'wp-travel-engine' ); ?></span>
</div>
<!-- Trip View Mode -->
<?php
$list_mode = get_option( 'wptravelengine_trip_view_mode', 'list' );
?>
<div class="wpte-field wpte-floated">
	<label for="wptravelengine_trip_sort_by" data-wte-update="wte_new_5.5.7" class="wpte-field-label"><?php esc_html_e( 'Trip View Mode', 'wp-travel-engine' ); ?></label>
	<div class="wpte-field wpte-radio">
		<div class="wpte-radio-wrap">
			<input type="radio" id="view_list_mode" <?php checked( $list_mode, 'list' ); ?> value="list" name="wptravelengine_trip_view_mode">
			<label for="view_list_mode"></label>
		</div>
		<label class="wpte-field-label" for="view_list_mode"><?php esc_html_e( 'List', 'wp-travel-engine' ); ?></label>
		<div class="wpte-radio-wrap">
			<input type="radio" id="view_grid_mode" <?php checked( $list_mode, 'grid' ); ?> value="grid" name="wptravelengine_trip_view_mode">
			<label for="view_grid_mode"></label>
		</div>
		<label class="wpte-field-label" for="view_grid_mode"><?php esc_html_e( 'Grid', 'wp-travel-engine' ); ?></label>
	</div>
	<span class="wpte-tooltip"><?php esc_html_e( 'Choose the view mode: List|Grid.', 'wp-travel-engine' ); ?></span>
</div>
<!-- Show Featured Trips -->
<?php $show_featured_trips_on_top = ! isset( $settings['show_featured_trips_on_top'] ) || 'yes' === $settings['show_featured_trips_on_top']; ?>
<div class="wpte-field wpte-checkbox advance-checkbox">
	<label for="show_featured_trips_on_top" data-wte-update="wte_new_5.5.7" class="wpte-field-label"><?php esc_html_e( 'Show featured trips always on top', 'wp-travel-engine' ); ?></label>
	<div class="wpte-checkbox-wrap">
		<input type="hidden" name="wp_travel_engine_settings[show_featured_trips_on_top]" value="no">
		<input type="checkbox"
		data-onchange
		data-onchange-toggle-target="[data-number-of-featured-trips]"
		data-onchange-toggle-off-value="no"
		<?php checked( $show_featured_trips_on_top, true ); ?>
		id="show_featured_trips_on_top"
		name="wp_travel_engine_settings[show_featured_trips_on_top]"
		value="yes">
		<label for="show_featured_trips_on_top"/>
	</div>
</div>
<div class="wpte-field wpte-field-subfields" [data-number-of-featured-trips]>
	<!-- Number of Featured Trips -->
	<div class="wpte-field wpte-text wpte-floated <?php wptravelengine_hidden_class( false, $show_featured_trips_on_top ); ?>" data-number-of-featured-trips>
		<label class="wpte-field-label" for="wp_travel_engine_settings[feat_trip_num]"><?php esc_html_e( 'Number of Featured Trips', 'wp-travel-engine' ); ?></label>
		<input type="number" id="wp_travel_engine_settings[feat_trip_num]" name="wp_travel_engine_settings[feat_trip_num]" min= "0" value="<?php echo esc_attr( isset( $settings['feat_trip_num'] ) ? $settings['feat_trip_num'] : 2 ); ?>">
		<span class="wpte-tooltip"><?php esc_html_e( 'Set the number of featured trips to show in the archive pages.', 'wp-travel-engine' ); ?></span>
	</div>
</div>
<!-- Customize Archive Title -->
<div class="wpte-field wpte-floated">
	<label class="wpte-field-label" for="wp_travel_engine_settings[archive][title]">
		<?php esc_html_e( 'Customize Archive Title', 'wp-travel-engine' ); ?>
	</label>
	<input type="text" name="wp_travel_engine_settings[archive][title]" id="wp_travel_engine_settings[archive][title]"
		value="<?php esc_html_e( $archive_title, 'wp-travel-engine' ); ?>" />
	<span class="wpte-tooltip">
		<?php esc_html_e( 'Customize the Archive titles (Archives: Trips).', 'wp-travel-engine' ); ?>
	</span>
</div>
<!-- Hide Archives: Trips Title -->
<div class="wpte-field wpte-checkbox advance-checkbox">
	<label class="wpte-field-label" data-wte-update="wte_new_5.5.5" for="hide_archive_title"><?php esc_html_e( 'Hide Archives: Trips Title', 'wp-travel-engine' ); ?></label>
	<div class="wpte-checkbox-wrap">
		<input type="hidden" name="wp_travel_engine_settings['archive'][hide_archive_title]" value="no">
		<?php $hide_archive_title = isset( $settings['archive']['hide_archive_title'] ) && 'yes' === $settings['archive']['hide_archive_title']; ?>
		<input type="checkbox" id="hide_archive_title" class="" value="yes" name="wp_travel_engine_settings[archive][hide_archive_title]" <?php checked( $hide_archive_title, true ); ?>>
		<label for="hide_archive_title"></label>
	</div>
	<span class="wpte-tooltip"><?php esc_html_e( 'Enabling this feature will hide the Archive title (Archives: Trips).', 'wp-travel-engine' ); ?></span>
</div>


<!-- Collapsible Filter Panel -->
<div class="wpte-field wpte-checkbox advance-checkbox">
	<label class="wpte-field-label" data-wte-update="wte_new_6.0.0" for="collapsible_filter_panel"><?php esc_html_e( 'Enable Advance Search Panel', 'wp-travel-engine' ); ?></label>
	<div class="wpte-checkbox-wrap">
		<input type="hidden" name="wp_travel_engine_settings['archive'][collapsible_filter_panel]" value="no">
		<?php $collapsible_filter_panel = isset( $settings['archive']['collapsible_filter_panel'] ) && 'yes' === $settings['archive']['collapsible_filter_panel']; ?>
		<input type="checkbox" id="collapsible_filter_panel" class="" value="yes" name="wp_travel_engine_settings[archive][collapsible_filter_panel]" <?php checked( $collapsible_filter_panel, true ); ?>>
		<label for="collapsible_filter_panel"></label>
	</div>
	<span class="wpte-tooltip"><?php esc_html_e( 'Enable advance search panel for smaller devices in archive pages.', 'wp-travel-engine' ); ?></span>
</div>

<!-- search-filter-option -->

<div class="wpte-field wpte-checkbox advance-checkbox" id="filterOptionsContainer">
	<label class="wpte-field-label" data-wte-update="wte_new_5.5.5"
		for="search_filter_option"><?php esc_html_e( 'Toggle Criteria Filter Display', 'wp-travel-engine' ); ?></label>
	<div class="wpte-checkbox-wrap">
		<input type="hidden" name="wp_travel_engine_settings[search_filter_option]" value="no">
		<?php $search_filter_option = 'yes' === ( $settings['search_filter_option'] ?? 'yes' ); ?>
		<input type="checkbox" id="search_filter_option" class="" value="yes"
			name="wp_travel_engine_settings[search_filter_option]" <?php checked( $search_filter_option, true ); ?>>
		<label for="search_filter_option"></label>
	</div>
	<span
		class="wpte-tooltip"><?php esc_html_e( 'Enabling this feature will display each filter option as a requirement on the archive page, allowing users to toggle the visibility of criteria filters.', 'wp-travel-engine' ); ?></span>
</div>
