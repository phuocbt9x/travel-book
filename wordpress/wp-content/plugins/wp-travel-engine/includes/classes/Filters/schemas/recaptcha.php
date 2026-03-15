<?php
/**
 * reCAPTCHA Extension Schema.
 *
 * @since 6.7.0
 */

return array(
	'recaptcha' => array(
		'description' => __( 'reCAPTCHA Settings', 'wp-travel-engine' ),
		'type'        => 'object',
		'properties'  => array(
			'version' => array(
				'description' => __( 'reCAPTCHA Version (v2 or v3)', 'wp-travel-engine' ),
				'type'        => 'string',
				'enum'        => array( 'v2', 'v3' ),
				'default'     => 'v2',
			),
			'v2'      => array(
				'description' => __( 'reCAPTCHA v2 Settings', 'wp-travel-engine' ),
				'type'        => 'object',
				'properties'  => array(
					'site_key'   => array(
						'description' => __( 'reCAPTCHA v2 Site Key', 'wp-travel-engine' ),
						'type'        => 'string',
					),
					'secret_key' => array(
						'description' => __( 'reCAPTCHA v2 Secret Key', 'wp-travel-engine' ),
						'type'        => 'string',
					),
				),
			),
			'v3'      => array(
				'description' => __( 'reCAPTCHA v3 Settings', 'wp-travel-engine' ),
				'type'        => 'object',
				'properties'  => array(
					'site_key'   => array(
						'description' => __( 'reCAPTCHA v3 Site Key', 'wp-travel-engine' ),
						'type'        => 'string',
					),
					'secret_key' => array(
						'description' => __( 'reCAPTCHA v3 Secret Key', 'wp-travel-engine' ),
						'type'        => 'string',
					),
				),
			),
		),
	),
);
