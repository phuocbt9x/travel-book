<?php
/**
 * Extensions Trip Weather Forecast Tab Settings.
 *
 * @since 6.2.0
 */
$is_weather_forecast_active = defined( 'WTE_WEATHER_FORECAST_BASE_PATH' );
$active_extensions          = apply_filters( 'wpte_get_global_extensions_tab', array() );
$file_path                  = $active_extensions['wte_weather_forecast']['content_path'] ?? '';
if ( ! file_exists( $file_path ) ) {
	return array();
}
return apply_filters(
	'extension_weather_foreacast',
	array(
		'is_active' => $is_weather_forecast_active,
		'title'     => __( 'Trip Weather Forecast', 'wp-travel-engine' ),
		'order'     => 70,
		'id'        => 'extension-trip-weather-forecast',
		'fields'    => array(
			array(
				'field_type' => 'ALERT',
				'content'    => sprintf(
					__( 'We have integrated a new weather API offered by the weather API service. Kindly generate a fresh API Key from %1$sthis source%2$s, where both free and premium API Keys are available for use.', 'wp-travel-engine' ),
					'<a href="https://www.weatherapi.com/" target="_blank" rel="nofollow">',
					'</a>'
				),
			),
			array(
				'divider'     => true,
				'label'       => __( 'API Key:', 'wp-travel-engine' ),
				'description' => sprintf(
					__( 'Get a Free API Key %1$sfrom here%2$s.', 'wp-travel-engine' ),
					'<a href="https://www.weatherapi.com/pricing.aspx" target="_blank" rel="nofollow">',
					'</a>'
				),
				'field_type'  => 'TEXT',
				'name'        => 'weather_forecast.api_key',
			),
		),
	)
);
