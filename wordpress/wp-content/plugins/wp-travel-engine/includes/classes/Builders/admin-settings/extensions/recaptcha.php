<?php
/**
 * Extensions reCAPTCHA Tab Settings.
 *
 * @since 6.7.0
 */

$recaptcha_addons    = apply_filters( 'wptravelengine_recaptcha_addons', array() );
$is_recaptcha_needed = ! empty( $recaptcha_addons );

if ( empty( $recaptcha_addons ) ) {
	return array();
}

return apply_filters(
	'extension_recaptcha_settings',
	array(
		'is_active' => $is_recaptcha_needed,
		'title'     => __( 'reCAPTCHA', 'wp-travel-engine' ),
		'order'     => 5,
		'id'        => 'extension-recaptcha',
		'fields'    => array(
			array(
				'field_type' => 'ALERT',
				'content'    => __( 'Configure Google reCAPTCHA to protect your forms from spam and automated submissions. Get your keys from <a href="https://www.google.com/recaptcha/admin" target="_blank">Google reCAPTCHA Admin</a>.', 'wp-travel-engine' ),
			),
			array(
				'label'      => __( 'reCAPTCHA Version', 'wp-travel-engine' ),
				'help'       => __( 'Choose which version of reCAPTCHA to use. v2 shows a checkbox, v3 works invisibly in the background.', 'wp-travel-engine' ),
				'field_type' => 'SELECT_BUTTON',
				'options'    => array(
					array(
						'label' => __( 'v2', 'wp-travel-engine' ),
						'value' => 'v2',
					),
					array(
						'label' => __( 'v3', 'wp-travel-engine' ),
						'value' => 'v3',
					),
				),
				'name'       => 'recaptcha.version',
				'default'    => 'v2',
			),
			array(
				'field_type' => 'GROUP',
				'condition'  => 'recaptcha.version === v2',
				'fields'     => array(
					array(
						'field_type' => 'ALERT',
						'content'    => __( 'Configure Google reCAPTCHA v2 with "I\'m not a robot" Checkbox type. <strong>Important:</strong> Use only v2 keys in this section.', 'wp-travel-engine' ),
					),
					array(
						'divider'    => true,
						'label'      => __( 'Site Key', 'wp-travel-engine' ),
						'help'       => __( 'Enter your Google reCAPTCHA v2 "I\'m not a robot" Checkbox site key.', 'wp-travel-engine' ),
						'field_type' => 'PASSWORD',
						'name'       => 'recaptcha.v2.site_key',
					),
					array(
						'label'      => __( 'Secret Key', 'wp-travel-engine' ),
						'help'       => __( 'Enter your Google reCAPTCHA v2 "I\'m not a robot" Checkbox secret key.', 'wp-travel-engine' ),
						'field_type' => 'PASSWORD',
						'name'       => 'recaptcha.v2.secret_key',
					),
				),
			),
			array(
				'field_type' => 'GROUP',
				'condition'  => 'recaptcha.version === v3',
				'fields'     => array(
					array(
						'field_type' => 'ALERT',
						'content'    => __( 'Configure Google reCAPTCHA v3 which works invisibly in the background. <strong>Important:</strong> Use only v3 keys in this section.', 'wp-travel-engine' ),
					),
					array(
						'divider'    => true,
						'label'      => __( 'Site Key', 'wp-travel-engine' ),
						'help'       => __( 'Enter your Google reCAPTCHA v3 site key.', 'wp-travel-engine' ),
						'field_type' => 'PASSWORD',
						'name'       => 'recaptcha.v3.site_key',
					),
					array(
						'label'      => __( 'Secret Key', 'wp-travel-engine' ),
						'help'       => __( 'Enter your Google reCAPTCHA v3 secret key.', 'wp-travel-engine' ),
						'field_type' => 'PASSWORD',
						'name'       => 'recaptcha.v3.secret_key',
					),
				),
			),
		),
	)
);
