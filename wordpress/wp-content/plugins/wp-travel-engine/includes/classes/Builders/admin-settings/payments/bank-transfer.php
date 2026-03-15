<?php
/**
 * Payment Direct Bank Transfer Tab Settings.
 *
 * @since 6.2.0
 */

return apply_filters(
	'payment_direct_bank_transfer',
	array(
		'title'  => __( 'Direct bank transfer', 'wp-travel-engine' ),
		'order'  => 12,
		'id'     => 'payment-bank-transfer',
		'fields' => array(
			array(
				'divider'    => true,
				'label'      => __( 'Title', 'wp-travel-engine' ),
				'help'       => __( 'The title which the user see during checkout.', 'wp-travel-engine' ),
				'field_type' => 'TEXT',
				'name'       => 'direct_bank_transfer.title',
				'default'    => 'Bank Transfer',
			),
			array(
				'divider'    => true,
				'label'      => __( 'Description', 'wp-travel-engine' ),
				'help'       => __( 'Payment method description.', 'wp-travel-engine' ),
				'field_type' => 'TEXTAREA',
				'name'       => 'direct_bank_transfer.description',
				'default'    => 'Make your payment directly into our bank account. Please use your Order ID as the payment reference. Your order will not be shipped until the funds have cleared in our account.',
			),
			array(
				'divider'    => true,
				'label'      => __( 'Instructions', 'wp-travel-engine' ),
				'help'       => __( 'Instructions to the user, displays on the thankyou page and email.', 'wp-travel-engine' ),
				'field_type' => 'TEXTAREA',
				'name'       => 'direct_bank_transfer.instructions',
				'default'    => 'Please make your payment on the provided bank accounts.',
			),
			array(
				'field_type' => 'TITLE',
				'title'      => __( 'Account Details', 'wp-travel-engine' ),
			),
			array(
				'field_type' => 'ACCOUNT_DETAILS',
				'name'       => 'direct_bank_transfer.account_details',
			),
		),
	)
);
