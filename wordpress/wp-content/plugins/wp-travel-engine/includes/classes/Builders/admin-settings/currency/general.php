<?php
/**
 * Currency Settings.
 *
 * @since 6.2.0
 */

use WPTravelEngine\Helpers\Functions as Helper;

$currencies       = Helper::get_currencies();
$payment_currency = array_map(
	function ( $key, $name ) {
		return array(
			'label' => $name . ' ( ' . html_entity_decode( Helper::currency_symbol_by_code( $key ), ENT_QUOTES | ENT_HTML5, 'UTF-8' ) . ' )',
			'value' => $key,
		);
	},
	array_keys( $currencies ),
	$currencies
);

$currency_options = array(
	array(
		'label' => __( 'Currency Symbol ( e.g. $ )', 'wp-travel-engine' ),
		'value' => 'symbol',
	),
	array(
		'label' => __( 'Currency Code ( e.g. USD )', 'wp-travel-engine' ),
		'value' => 'code',
	),
);

return apply_filters(
	'currency_genral_settings',
	array(
		'title'  => __( 'General', 'wp-travel-engine' ),
		'order'  => 5,
		'id'     => 'currency-general',
		'fields' => array(
			array(
				'label'       => __( 'Payment Currency', 'wp-travel-engine' ),
				'description' => __( 'Choose the base currency for the trips pricing.', 'wp-travel-engine' ),
				'field_type'  => 'SELECT',
				'name'        => 'currency_code',
				'divider'     => true,
				'options'     => $payment_currency,
			),
			array(
				'label'       => __( 'Display Currency Symbol or Code', 'wp-travel-engine' ),
				'description' => __( 'Display Currency Symbol or Code in Trip Listing Templates.', 'wp-travel-engine' ),
				'field_type'  => 'SELECT',
				'name'        => 'currency_symbol',
				'options'     => $currency_options,
				'divider'     => true,
			),
			array(
				'label'       => __( 'Amount Display Format', 'wp-travel-engine' ),
				'description' => __( 'Amount Display format. Available tags: <code>%CURRENCY_CODE%</code>, <code>%CURRENCY_SYMBOL%</code>, <code>%AMOUNT%</code>, <code>%FORMATED_AMOUNT%</code>', 'wp-travel-engine' ),
				'field_type'  => 'TEXT',
				'name'        => 'amount_format',
				'divider'     => true,
			),
			array(
				'label'       => __( 'Decimal Separator', 'wp-travel-engine' ),
				'description' => __( 'Symbol to use for decimal separator in Trip Price.', 'wp-travel-engine' ),
				'field_type'  => 'TEXT',
				'name'        => 'decimal_separator',
				'divider'     => true,
			),
			array(
				'label'       => __( 'Thousands Separator', 'wp-travel-engine' ),
				'description' => __( 'Symbol to use for thousands separator in Trip Price.', 'wp-travel-engine' ),
				'field_type'  => 'TEXT',
				'name'        => 'thousands_separator',
				'divider'     => true,
			),
		),
	),
);
