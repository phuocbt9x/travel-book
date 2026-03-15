<?php
/**
 * Form Editor Extension Schema.
 *
 * @since 6.2.0
 */

if ( ! defined( 'WTE_FORM_EDITOR_PLUGIN_FILE' ) || ! file_exists( WTE_FORM_EDITOR_PLUGIN_FILE ) ) {
	return array();
}

return array(
	'form_editor' => array(
		'description' => __( 'Form Editor Settings', 'wp-travel-engine' ),
		'type'        => 'object',
		'properties'  => array(
			'recaptcha_site_key'   => array(
				'description' => __( 'Recaptcha Site Key', 'wp-travel-engine' ),
				'type'        => 'string',
			),
			'recaptcha_secret_key' => array(
				'description' => __( 'Recaptcha Secret Key', 'wp-travel-engine' ),
				'type'        => 'string',
			),
		),
	),
);
