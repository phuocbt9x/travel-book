<?php
/**
 * PayU Money Payment Gateway Schema.
 *
 * @since 6.2.0
 */

if ( ! defined( 'WTE_PAYU_MONEY_BOLT_FILE_PATH' ) || ! file_exists( WTE_PAYU_MONEY_BOLT_FILE_PATH ) ) {
	return array();
}

return array(
	'payu_money' => array(
		'description' => __( 'PayU Biz settings', 'wp-travel-engine' ),
		'type'        => 'object',
		'properties'  => array(
			'merchant_key'  => array(
				'description' => __( 'Merchant Key', 'wp-travel-engine' ),
				'type'        => 'string',
			),
			'merchant_salt' => array(
				'description' => __( 'Merchant Salt', 'wp-travel-engine' ),
				'type'        => 'string',
			),
		),
	),
);
