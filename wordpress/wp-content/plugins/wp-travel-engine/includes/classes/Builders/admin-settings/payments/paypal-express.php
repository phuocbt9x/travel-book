<?php
/**
 * PayPal Express Settings.
 *
 * @since 6.2.0
 */
use WPTravelEngine\PaymentGateways\PaymentGateways;
$is_paypal_express_active = PaymentGateways::instance()->get_payment_gateway( 'paypalexpress_enable' );
if ( ! $is_paypal_express_active ) {
	return array();
}
$payment_methods = array(
	array(
		'label' => __( 'Credit or debit cards', 'wp-travel-engine' ),
		'value' => 'card',
	),
	array(
		'label' => __( 'Venmo', 'wp-travel-engine' ),
		'value' => 'venmo',
	),
	array(
		'label' => __( 'SEPA-Lastschrift', 'wp-travel-engine' ),
		'value' => 'sepa',
	),
	array(
		'label' => __( 'Bancontact', 'wp-travel-engine' ),
		'value' => 'bancontact',
	),
	array(
		'label' => __( 'EPS', 'wp-travel-engine' ),
		'value' => 'eps',
	),
	array(
		'label' => __( 'GIROPAY', 'wp-travel-engine' ),
		'value' => 'giropay',
	),
	array(
		'label' => __( 'IDEAL', 'wp-travel-engine' ),
		'value' => 'ideal',
	),
	array(
		'label' => __( 'MyBank', 'wp-travel-engine' ),
		'value' => 'mybank',
	),
	array(
		'label' => __( 'P24', 'wp-travel-engine' ),
		'value' => 'p24',
	),
	array(
		'label' => __( 'Sofort', 'wp-travel-engine' ),
		'value' => 'sofort',
	),
);
return apply_filters(
	'payment_paypal_express',
	array(
		'title'     => __( 'PayPal Express', 'wp-travel-engine' ),
		'order'     => 55,
		'id'        => 'payment-paypal-express',
		'is_active' => $is_paypal_express_active,
		'fields'    => array(
			array(
				'field_type' => 'DEBUG_MODE',
				'name'       => 'debug_mode',
			),
			array(
				'divider'     => true,
				'label'       => __( 'Client ID', 'wp-travel-engine' ),
				'description' => __( 'Enter a valid Client ID from PayPal-Express account. All payments will go to this account.', 'wp-travel-engine' ),
				'field_type'  => 'TEXT',
				'name'        => 'paypal_express.client_id',
			),
			array(
				'divider'     => true,
				'label'       => __( 'Client Secret', 'wp-travel-engine' ),
				'description' => __( 'Enter a valid Secret Key from PayPal-Express account.', 'wp-travel-engine' ),
				'field_type'  => 'TEXT',
				'name'        => 'paypal_express.client_secret',
			),
			array(
				'label'       => __( 'Disable Funding', 'wp-travel-engine' ),
				'description' => __( 'Default: Credit/debit cards are disabled. Funding sources to disallow from showing in the Smart Payment Buttons.', 'wp-travel-engine' ),
				'field_type'  => 'SELECT',
				'name'        => 'paypal_express.disable_funding',
				'options'     => $payment_methods,
				'isMultiple'  => true,
			),
		),
	)
);
