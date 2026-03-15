<?php
/**
 * Field types array.
 *
 * @package WPTravelEngine
 * @since 6.0.0
 */

use WPTravelEngine\Utilities\FormBuilder\Fields;

return array(
	'text'             => array(
		'field_label' => __( 'Text', 'wp-travel-engine' ),
		'field_class' => Fields\Text::class,
	),
	'email'            => array(
		'field_label' => __( 'Email', 'wp-travel-engine' ),
		'field_class' => Fields\Email::class,
	),
	'number'           => array(
		'field_label' => __( 'Number', 'wp-travel-engine' ),
		'field_class' => Fields\Number::class,
	),
	'hidden'           => array(
		'field_label' => __( 'Hidden', 'wp-travel-engine' ),
		'field_class' => Fields\Hidden::class,
	),
	'select'           => array(
		'field_label' => __( 'Select', 'wp-travel-engine' ),
		'field_class' => Fields\Select::class,
	),
	'textarea'         => array(
		'field_label' => __( 'Textarea', 'wp-travel-engine' ),
		'field_class' => Fields\Textarea::class,
	),
	'datepicker'       => array(
		'field_label' => __( 'Date', 'wp-travel-engine' ),
		'field_class' => Fields\DatePicker::class,
	),
	'radio'            => array(
		'field_label' => __( 'Radio', 'wp-travel-engine' ),
		'field_class' => Fields\Radio::class,
	),
	'checkbox'         => array(
		'field_label' => __( 'Checkbox', 'wp-travel-engine' ),
		'field_class' => Fields\Checkbox::class,
	),
	'text_info'        => array(
		'field_label' => __( 'Text Info', 'wp-travel-engine' ),
		'field_class' => Fields\TextInfo::class,
	),
	'heading'          => array(
		'field_label' => __( 'Heading', 'wp-travel-engine' ),
		'field_class' => Fields\Heading::class,
	),
	'range'            => array(
		'field_label' => __( 'Range', 'wp-travel-engine' ),
		'field_class' => 'WP_Travel_Engine_Form_Field_Range',
	),
	'date_range'       => array(
		'field_label' => __( 'Date Range', 'wp-travel-engine' ),
		'field_class' => 'WP_Travel_Engine_Form_Field_Date_Range',
	),
	'file'             => array(
		'field_label' => __( 'File', 'wp-travel-engine' ),
		'field_class' => Fields\File::class,
	),
	'country_dropdown' => array(
		'field_label' => __( 'Country Dropdown', 'wp-travel-engine' ),
		'field_class' => Fields\CountrySelector::class,
	),
	'tel'              => array(
		'field_label' => __( 'Tel', 'wp-travel-engine' ),
		'field_class' => Fields\Phone::class,
	),
	'trips_list'       => array(
		'field_label' => __( 'Trips List', 'wp-travel-engine' ),
		'field_class' => Fields\TripSelector::class,
	),
	'currency_picker'  => array(
		'field_label' => __( 'Currency Picker', 'wp-travel-engine' ),
		'field_class' => Fields\CurrencyPicker::class,
	),
);
