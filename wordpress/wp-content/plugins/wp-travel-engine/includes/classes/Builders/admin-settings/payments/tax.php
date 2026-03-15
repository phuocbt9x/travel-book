<?php
/**
 * Payment Tax Tab Settings.
 *
 * @since 6.2.0
 */

return apply_filters(
	'payment_tax',
	array(
		'title'  => __( 'Tax Settings', 'wp-travel-engine' ),
		'order'  => 9,
		'id'     => 'payment-tax',
		'fields' => array(
			array(
				'label'      => __( 'Apply Tax', 'wp-travel-engine' ),
				'help'       => __( 'Check this option to enable tax option for trips.', 'wp-travel-engine' ),
				'field_type' => 'SWITCH',
				'name'       => 'tax.enable',
				'default'    => '',
			),
			array(
				'condition'  => 'tax.enable === true',
				'field_type' => 'GROUP',
				'fields'     => array(
					array(
						'divider'     => true,
						'label'       => __( 'Custom Label', 'wp-travel-engine' ),
						'description' => __( 'This option allows you to use custom label for Tax. `%s` will be replaced with percentage number and `%%` wil be replaced with `%`.', 'wp-travel-engine' ),
						'field_type'  => 'TEXT',
						'name'        => 'tax.custom_label',
						'default'     => 'Tax (%s%%)',
						'style'       => array( 'width' => '50%' ),
					),
					array(
						'divider'    => true,
						'label'      => __( 'Trip Prices', 'wp-travel-engine' ),
						'help'       => __( 'This option will affect how you enter trip prices.', 'wp-travel-engine' ),
						'field_type' => 'SELECT_BUTTON',
						'name'       => 'tax.type',
						'options'    => array(
							array(
								'label' => __( 'Inclusive of tax', 'wp-travel-engine' ),
								'value' => 'inclusive',
							),
							array(
								'label' => __( 'Exclusive of tax', 'wp-travel-engine' ),
								'value' => 'exclusive',
							),
						),
						'default'    => 'exclusive',
					),
					array(
						'divider'    => true,
						'label'      => __( 'Percentage', 'wp-travel-engine' ),
						'help'       => __( 'Trip Tax percentage added to trip price.', 'wp-travel-engine' ),
						'field_type' => 'NUMBER',
						'default'    => '13',
						'name'       => 'tax.percentage',
						'rules'      => array(
							'required' => 'Tax percentage is required',
							'min'      => array(
								'value'   => 0,
								'message' => 'Tax percentage must be greater than 0',
							),
						),
						'style'      => array( 'width' => '50%' ),
						'suffix'     => array(
							'type'    => 'field',
							'field'   => array(
								'defaultValue' => __( '%', 'wp-travel-engine' ),
								'type'         => 'TEXT',
								'readOnly'     => true,
							),
							'variant' => 'solid',
						),
					),
				),
			),
		),
	),
);
