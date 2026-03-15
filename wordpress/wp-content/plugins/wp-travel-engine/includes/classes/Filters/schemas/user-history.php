<?php
/**
 * User History Schmea.
 *
 * @since 6.2.0
 */

if ( ! defined( 'WTE_USER_HISTORY_FILE_PATH' ) || ! file_exists( WTE_USER_HISTORY_FILE_PATH ) ) {
	return array();
}

return array(
	'user_history' => array(
		'description' => __( 'We Travel Settings', 'wp-travel-engine' ),
		'type'        => 'object',
		'properties'  => array(
			'enable_tracking'       => array(
				'description' => __( 'Enable User History Tracking', 'wp-travel-engine' ),
				'type'        => 'boolean',
			),
			'enable_cookie_consent' => array(
				'description' => __( 'Enable Cookie Consent Message', 'wp-travel-engine' ),
				'type'        => 'boolean',
			),
			'cookie_position'       => array(
				'description' => __( 'Cookie Consent Message Position', 'wp-travel-engine' ),
				'type'        => 'string',
			),
			'cookie_layout'         => array(
				'description' => __( 'Cookie Consent Message layout', 'wp-travel-engine' ),
				'type'        => 'string',
			),
			'banner_bg_color'       => array(
				'description' => __( 'Cookie Consent banner background Color', 'wp-travel-engine' ),
				'type'        => 'string',
			),
			'banner_btn_color'      => array(
				'description' => __( 'Cookie Consent banner button Color', 'wp-travel-engine' ),
				'type'        => 'string',
			),
			'banner_text_color'     => array(
				'description' => __( 'Cookie Consent banner text Color', 'wp-travel-engine' ),
				'type'        => 'string',
			),
			'banner_btn_text_color' => array(
				'description' => __( 'Cookie consent banner button text color', 'wp-travel-engine' ),
				'type'        => 'string',
			),
			'learn_more_link'       => array(
				'description' => __( 'Cookie Consent Learn More Link', 'wp-travel-engine' ),
				'type'        => 'string',
			),
			'cookie_custom_message' => array(
				'description' => __( 'Cookie Consent Custom Message', 'wp-travel-engine' ),
				'type'        => 'string',
			),
			'dismiss_button_text'   => array(
				'description' => __( 'Dismiss button text', 'wp-travel-engine' ),
				'type'        => 'string',
			),
			'policy_link_text'      => array(
				'description' => __( 'Policy link text', 'wp-travel-engine' ),
				'type'        => 'string',
			),
		),
	),
);
