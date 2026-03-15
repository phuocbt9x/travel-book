<?php
/**
 * Render File for Booking Button  block.
 *
 * @var Render $render
 * @var string $wrapper_attributes
 * @var Attributes $attributes_parser
 * @package Wp_Travel_Engine
 * @since 5.9
 */

use WPTravelEngine\Core\Models\Post\TravelerCategories;
use WPTravelEngine\Core\Models\Post\Trip;
use WPTravelEngine\Core\Models\Post\TripPackage;
use WPTravelEngine\Core\Models\Settings\PluginSettings;

global $wtetrip;
global $post;

/**
 * @var Trip $trip_model
 */
$trip_model      = Trip::make( $wtetrip->post->ID );
$default_package = $trip_model->default_package();
if ( ! $default_package ) {
	return;
}
if ( $default_package instanceof TripPackage ) {
	$traveler_categories     = TravelerCategories::make( $trip_model, $default_package );
	$single_pricing_category = $traveler_categories->is_single_pricing_category();
	$settings                = new PluginSettings();
	$hide_booking_form       = $settings->get( 'booking', 'no' );
	$form_layout             = $settings->get( 'pricing_section_layout', 'layout-1' );
	$class_based_on_layout   = $form_layout ? ' wpte-form-' . $form_layout : ' wpte-form-layout-1';
	$class_based_on_layout   = ( $form_layout === 'layout-2' && $single_pricing_category ) ? ' wpte-form-layout-2 wpte-default-form' : $class_based_on_layout;
	$wrapper_classes         = apply_filters( 'wpte_bf_outer_wrapper_classes', '' );
}

if ( 'yes' === $hide_booking_form ) {
	return;
}

$global_settings = wptravelengine_settings();

$trip_booking_data = wptravelengine_trip_booking_modal_data( $wtetrip->post->ID );

?>
<div <?php echo esc_attr( $attributes_parser->wrapper_attributes() ); ?>>
	<div class="wpte-bf-btn-wrap">
		<button type="button"
				data-trip-booking="<?php echo esc_attr( wp_json_encode( $trip_booking_data ) ); ?>"
				class="wpte-bf-btn wte-book-now"><?php echo esc_html__( 'Check Availability', 'wp-travel-engine' ); ?></button>
	</div>
</div>
