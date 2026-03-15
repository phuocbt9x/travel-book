<?php
/**
 * Extensions Partial Payment Tab Settings.
 *
 * @since 6.2.0
 */
$is_partial_payment_active = defined( 'WP_TRAVEL_ENGINE_PARTIAL_PAYMENT_FILE_PATH' );
$active_extensions         = apply_filters( 'wpte_get_global_extensions_tab', array() );
$file_path                 = $active_extensions['wte_partial_payment']['content_path'] ?? '';
if ( ! file_exists( $file_path ) ) {
	return array();
}
return apply_filters(
	'extension_partial_payment',
	array(
		'is_active' => $is_partial_payment_active,
		'title'     => __( 'Partial Payment', 'wp-travel-engine' ),
		'order'     => 45,
		'id'        => 'extension-partial-payment',
		'fields'    => array(
			array(
				'divider'    => true,
				'label'      => __( 'Apply Partial Payment', 'wp-travel-engine' ),
				'help'       => __( 'Check this if you want to enable partial payments for trip bookings.', 'wp-travel-engine' ),
				'field_type' => 'SWITCH',
				'name'       => 'partial_payment.enable',
			),
			array(
				'condition'  => 'partial_payment.enable === true',
				'field_type' => 'GROUP',
				'fields'     => array(
					array(
						'divider'    => true,
						'label'      => __( 'Accept Partial Payment In', 'wp-travel-engine' ),
						'help'       => __( 'Accept Partial Payment in Amount or Percentage.', 'wp-travel-engine' ),
						'field_type' => 'SELECT_BUTTON',
						'options'    => array(
							array(
								'label' => __( 'Percentage', 'wp-travel-engine' ),
								'value' => 'percent',
							),
							array(
								'label' => __( 'Amount', 'wp-travel-engine' ),
								'value' => 'amount',
							),
						),
						'default'    => 'percent',
						'name'       => 'partial_payment.payment_type',
					),
					array(
						'condition'  => 'partial_payment.payment_type === percent',
						'divider'    => true,
						'label'      => __( 'Partial Payment in Percent', 'wp-travel-engine' ),
						'help'       => __( 'Enter partial payment amount (in percentage of total trip cost) to be paid while booking the trip (without % sign).', 'wp-travel-engine' ),
						'field_type' => 'NUMBER',
						'min'        => 0,
						'name'       => 'partial_payment.payment_percent',
					),
					array(
						'condition'   => 'partial_payment.payment_type === amount',
						'divider'     => true,
						'label'       => __( 'Partial Payment in Amount', 'wp-travel-engine' ),
						'help'        => __( 'Enter partial payment amount (in figures) to be paid while booking the trip (without $ sign).', 'wp-travel-engine' ),
						'description' => __( 'This amount will be calculated on a per-traveler basis.', 'wp-travel-engine' ),
						'field_type'  => 'NUMBER',
						'min'         => 0,
						'name'        => 'partial_payment.payment_amount',
					),
					array(
						'label'      => __( 'Full Payment', 'wp-travel-engine' ),
						'help'       => __( 'Enable this feature to display both full payment and partial payment options on the checkout page.', 'wp-travel-engine' ),
						'field_type' => 'SWITCH',
						'name'       => 'partial_payment.enable_full_payment',
					),
				),
			),
		),
	)
);
