<?php
/**
 * Payment Stripe Payment Tab Settings.
 *
 * @since 6.2.0
 */
use WPTravelEngine\PaymentGateways\PaymentGateways;
$is_stripe_checkout_active = PaymentGateways::instance()->get_payment_gateway( 'stripe_payment' );
if ( ! $is_stripe_checkout_active ) {
	return array();
}
return apply_filters(
	'payment_stripe',
	array(
		'is_active' => $is_stripe_checkout_active,
		'title'     => __( 'Stripe Payment', 'wp-travel-engine' ),
		'order'     => 70,
		'id'        => 'payment-stripe',
		'fields'    => array(
			array(
				'field_type' => 'DEBUG_MODE',
				'name'       => 'debug_mode',
			),
			array(
				'divider'    => true,
				'label'      => __( 'Stripe Secret Key', 'wp-travel-engine' ),
				'help'       => __( 'Enter a valid Stripe Secret Key. All payments will go to this account.', 'wp-travel-engine' ),
				'field_type' => 'TEXT',
				'name'       => 'stripe.secret_key',
			),
			array(
				'divider'    => true,
				'label'      => __( 'Stripe Publishable Key', 'wp-travel-engine' ),
				'help'       => __( 'Enter a valid Stripe Publishable Key.', 'wp-travel-engine' ),
				'field_type' => 'TEXT',
				'name'       => 'stripe.publishable_key',
			),
			array(
				'divider'    => true,
				'label'      => __( 'Stripe Pay Button Label', 'wp-travel-engine' ),
				'help'       => __( 'Enter the label for the pay button for stripe.', 'wp-travel-engine' ),
				'field_type' => 'TEXT',
				'name'       => 'stripe.pay_btn_label',
			),
			array(
				'label'      => __( 'Show Postal Code', 'wp-travel-engine' ),
				'help'       => __( 'Check this option to show Postal Code.', 'wp-travel-engine' ),
				'field_type' => 'SWITCH',
				'name'       => 'stripe.enable_postal_code',
			),
		),
	)
);
