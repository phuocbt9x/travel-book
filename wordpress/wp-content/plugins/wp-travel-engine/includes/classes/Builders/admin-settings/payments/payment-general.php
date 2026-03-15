<?php
/**
 * Payment General Tab Settings.
 *
 * @since 6.2.0
 */
$payment_gateways_sorted = wp_travel_engine_get_sorted_payment_gateways();
$payment_gateway         = array();
foreach ( $payment_gateways_sorted as $key => $value ) {
	$payment_gateway[] = array(
		'label' => $value['label'],
		'value' => $key,
		'icon'  => $value['icon_url'] ?? '',
	);
}
return apply_filters(
	'payment_general_settings',
	array(
		'title'  => __( 'General', 'wp-travel-engine' ),
		'order'  => 5,
		'id'     => 'payment-general',
		'fields' => array(
			array(
				'field_type' => 'DEBUG_MODE',
				'name'       => 'debug_mode',
			),
			array(
				'divider'    => true,
				'label'      => __( 'Debug Mode', 'wp-travel-engine' ),
				'help'       => __( 'Check this option to enable debug mode for all active payment gateways. Enabling this option will use sandbox accounts( if available ) on the checkout page.', 'wp-travel-engine' ),
				'field_type' => 'SWITCH',
				'name'       => 'debug_mode',
			),
			array(
				'divider'    => true,
				'label'      => __( 'Default Gateway', 'wp-travel-engine' ),
				'help'       => __( 'Choose the default payment gateway. The chosen gateway will be selected by default on the checkout page.', 'wp-travel-engine' ),
				'field_type' => 'SELECT',
				'name'       => 'default_payment_gateway',
				'options'    => $payment_gateway,
			),
			array(
				'divider'     => true,
				'label'       => __( 'Payment Gateways', 'wp-travel-engine' ),
				'help'        => __( 'Check the payment gateways to enable on the checkout page. You can configure each payment gateway settings by switching to the Payment gateway settings tab.', 'wp-travel-engine' ),
				'description' => __( 'Check the payment gateways to enable on the checkout page. You can configure each payment gateway settings by switching to the Payment gateway settings tab.', 'wp-travel-engine' ),
				'field_type'  => 'PAYMENT_GATEWAYS',
				'name'        => 'payment_gateways',
				'options'     => $payment_gateway,
			),
			array(
				'field_type' => 'ALERT',
				'content'    => sprintf( __( 'Need more payment gateways to receive payment from customers? We support several payment gateways to empower travel agencies to sell travel packages. %s', 'wp-travel-engine' ), sprintf( '<a target="_blank" href="https://wptravelengine.com/plugins/category/payment-gateways/?utm_source=free_plugin&utm_medium=pro_addon&utm_campaign=upgrade_to_pro" rel="nofollow">%s</a>', __( 'See all the supported payment gateways here.', 'wp-travel-engine' ) ) ),
				'status'     => 'upgrade',
			),
		),
	)
);
