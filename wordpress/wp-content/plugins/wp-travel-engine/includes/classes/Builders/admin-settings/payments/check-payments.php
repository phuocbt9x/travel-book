<?php
/**
 * Payments Check Payments Tab Settings.
 *
 * @since 6.2.0
 */

return apply_filters(
	'payment_check',
	array(
		'title'  => __( 'Check Payments', 'wp-travel-engine' ),
		'order'  => 11,
		'id'     => 'payment-check',
		'fields' => array(
			array(
				'divider'    => true,
				'label'      => __( 'Title', 'wp-travel-engine' ),
				'help'       => __( 'The title which the user see during checkout.', 'wp-travel-engine' ),
				'field_type' => 'TEXT',
				'name'       => 'check_payments.title',
				'default'    => 'Check payments',
			),
			array(
				'divider'    => true,
				'label'      => __( 'Description', 'wp-travel-engine' ),
				'help'       => __( 'Payment method description.', 'wp-travel-engine' ),
				'field_type' => 'TEXTAREA',
				'name'       => 'check_payments.description',
				'default'    => 'Please send a check to Store Name, Store Street, Store Town, Store State / County, Store Postcode.',
			),
			array(
				'divider'    => true,
				'label'      => __( 'Instructions', 'wp-travel-engine' ),
				'help'       => __( 'Instructions to the user, displays on the Thank You page and email.', 'wp-travel-engine' ),
				'field_type' => 'TEXTAREA',
				'name'       => 'check_payments.instructions',
				'default'    => 'Please send a check to Store Name, Store Street, Store Town, Store State / County, Store Postcode.',
			),
		),
	)
);
