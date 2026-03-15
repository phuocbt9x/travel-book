<?php
/**
 * Weather Forecast Schema.
 */

if ( ! defined( 'WTE_WEATHER_FORECAST_BASE_PATH' ) || ! file_exists( WTE_WEATHER_FORECAST_BASE_PATH ) ) {
	return array();
}

return array(
	'weather_forecast' => array(
		'description' => __( 'Weather Forecast Settings', 'wp-travel-engine' ),
		'type'        => 'object',
		'properties'  => array(
			'api_key' => array(
				'description' => __( 'API Key', 'wp-travel-engine' ),
				'type'        => 'string',
			),
		),
	),
);
