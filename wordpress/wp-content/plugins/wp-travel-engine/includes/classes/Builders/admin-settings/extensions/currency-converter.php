<?php

/**
 * Extensions Currency Converter Tab Settings.
 *
 * @since 6.2.0
 */
$is_currency_converter_active = defined( 'WTE_CURRENCY_CONVERTER_ABSPATH' );
$active_extensions            = apply_filters( 'wpte_get_global_extensions_tab', array() );
$file_path                    = $active_extensions['wte_currency_converter']['content_path'] ?? '';
if ( ! file_exists( $file_path ) ) {
	return array();
}

$helper_functions = \Wte_Currency_Converter_Helper_Functions::get_instance();

// Get currency codes and country names.
$currency_codes = $helper_functions->get_currency_codes();
$currencies     = array();
foreach ( $currency_codes as $key => $value ) {
	$currencies[] = array(
		'label' => $value,
		'value' => $key,
	);
}

$currencies = array_values( $currencies );

return apply_filters(
	'extension_currency_converter',
	array(
		'is_active' => $is_currency_converter_active,
		'title'     => __( 'Currency Converter', 'wp-travel-engine' ),
		'order'     => 10,
		'id'        => 'extension-currency-converter',
		'fields'    => array(
			array(
				'divider'    => true,
				'label'      => __( 'Apply Currency Converter', 'wp-travel-engine' ),
				'help'       => __( 'Check this if you want to enable currency converter functionality in the frontend.', 'wp-travel-engine' ),
				'field_type' => 'SWITCH',
				'name'       => 'currency_converter.enable',
			),
			array(
				'divider'    => true,
				'label'      => __( 'Apply Sticky Currency Switcher', 'wp-travel-engine' ),
				'help'       => __( 'Check this if you want to enable sticky currency converter dropdown in the frontend.', 'wp-travel-engine' ),
				'field_type' => 'SWITCH',
				'name'       => 'currency_converter.sticky_enable',
			),
			array(
				'divider'    => true,
				'label'      => __( 'Show Currency Converter Before Booking Calender', 'wp-travel-engine' ),
				'help'       => __( 'Check this if you want to display currency converter dropdown before the booking calender on trip page.', 'wp-travel-engine' ),
				'field_type' => 'SWITCH',
				'name'       => 'currency_converter.show_before_bkg',
			),
			array(
				'divider'    => true,
				'label'      => __( 'Currency Converter Title:', 'wp-travel-engine' ),
				'help'       => __( 'Title for currency converter.', 'wp-travel-engine' ),
				'field_type' => 'TEXT',
				'default'    => 'Currency Converter',
				'name'       => 'currency_converter.title',
			),
			array(
				'divider'     => true,
				'label'       => __( 'API Key:', 'wp-travel-engine' ),
				'description' => sprintf(
					__( 'Get an %1$sFree API Key%2$s or %3$sPremium API Key%4$s', 'wp-travel-engine' ),
					'<a href="https://currencyfreaks.com/" target="_blank" rel="nofollow">',
					'</a>',
					'<a href="https://www.currencyconverterapi.com/pricing" target="_blank" rel="nofollow">',
					'</a>'
				),
				'field_type'  => 'TEXT',
				'default'     => 'Currency Converter',
				'name'        => 'currency_converter.api_key',
			),
			array(
				'divider'    => true,
				'label'      => __( 'License Type', 'wp-travel-engine' ),
				'help'       => __( 'Choose either license type is free or premium.', 'wp-travel-engine' ),
				'field_type' => 'SELECT_BUTTON',
				'options'    => array(
					array(
						'label' => __( 'Free', 'wp-travel-engine' ),
						'value' => 'free',
					),
					array(
						'label' => __( 'Premium', 'wp-travel-engine' ),
						'value' => 'premium',
					),
				),
				'default'    => 'free',
				'name'       => 'currency_converter.key_type',
			),
			array(
				'title'      => __( 'Currencies', 'wp-travel-engine' ),
				'field_type' => 'TITLE',
			),
			array(
				'divider'    => true,
				'label'      => __( 'Geo Locate', 'wp-travel-engine' ),
				'help'       => __( 'Check this option to enable geo locate.', 'wp-travel-engine' ),
				'field_type' => 'SWITCH',
				'name'       => 'currency_converter.geo_locate',
			),
			array(
				'divider'    => true,
				'label'      => __( 'Auto update rate', 'wp-travel-engine' ),
				'help'       => __( 'Check this option to enable auto rate update', 'wp-travel-engine' ),
				'field_type' => 'SWITCH',
				'name'       => 'currency_converter.auto_update',
			),
			array(
				'condition'  => 'currency_converter.api_key !== EMPTY',
				'title'      => __( 'Add Currency', 'wp-travel-engine' ),
				'field_type' => 'TITLE',
			),
			array(
				'condition'  => 'currency_converter.api_key !== EMPTY',
				'field_type' => 'CURRENCY_CONVERTER',
				'options'    => $currencies,
				'name'       => 'currency_converter.currency_rate',
			),

		),
	)
);
