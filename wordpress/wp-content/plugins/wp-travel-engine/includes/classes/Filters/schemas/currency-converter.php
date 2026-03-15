<?php
/**
 * Currency Converter Extension Schema.
 *
 * @since 6.2.0
 */

if ( ! defined( 'WTE_CURRENCY_CONVERTER_ABSPATH' ) || ! file_exists( WTE_CURRENCY_CONVERTER_ABSPATH ) ) {
	return array();
}

return array(
	'currency_converter' => array(
		'description' => __( 'Currency Converter Settings', 'wp-travel-engine' ),
		'type'        => 'object',
		'properties'  => array(
			'enable'          => array(
				'description' => __( 'Enable Currency Converter', 'wp-travel-engine' ),
				'type'        => 'boolean',
			),
			'sticky_enable'   => array(
				'description' => __( 'Enable Sticky Currency Switcher', 'wp-travel-engine' ),
				'type'        => 'boolean',
			),
			'show_before_bkg' => array(
				'type'        => 'boolean',
				'description' => 'Show Currency Converter Before Booking Calendar',
			),
			'title'           => array(
				'type'        => 'string',
				'description' => 'Title for currency converter',
			),
			'api_key'         => array(
				'type'        => 'string',
				'description' => 'API Key for currency converter',
			),
			'key_type'        => array(
				'type'        => 'string',
				'description' => 'License Type',
			),
			'geo_locate'      => array(
				'type'        => 'boolean',
				'description' => 'Enable Geo Locate',
			),
			'auto_update'     => array(
				'type'        => 'boolean',
				'description' => 'Enable Auto Update Rate',
			),
			'currency_rate'   => array(
				'type'        => 'array',
				'description' => 'Currency Rate',
				'items'       => array(
					'type'       => 'object',
					'properties' => array(
						'id'   => array(
							'type'        => 'integer',
							'description' => 'Currency ID',
						),
						'code' => array(
							'type'        => 'string',
							'description' => 'Currency Code',
						),
						'rate' => array(
							'type'        => 'float',
							'description' => 'Currency Rate',
						),
					),
				),
			),
		),
	),
);
