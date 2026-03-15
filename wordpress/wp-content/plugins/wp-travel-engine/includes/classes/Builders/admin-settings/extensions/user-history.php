<?php
/**
 * Extensions User History Tab Settings.
 *
 * @since 6.2.0
 */

$is_user_history_active = defined( 'WTE_USER_HISTORY_FILE_PATH' );
$active_extensions      = apply_filters( 'wpte_get_global_extensions_tab', array() );
$file_path              = $active_extensions['wte_user_history']['content_path'] ?? '';
if ( ! file_exists( $file_path ) ) {
	return array();
}

foreach ( array(
	'block'    => 'Block',
	'classic'  => 'Classic',
	'edgeless' => 'Edgeless',
	'wire'     => 'Wire',
) as $cookie_layout => $cookie_layout_label
) {
	$cookie_image_layouts[] = array(
		'label' => $cookie_layout_label,
		'value' => $cookie_layout,
		'image' => esc_url( WTE_USER_HISTORY_URL . '/assets/admin/images/cc-layout-' . esc_attr( $cookie_layout ) . '.jpg' ),
	);
}

return apply_filters(
	'extension_user_history',
	array(
		'is_active' => $is_user_history_active,
		'title'     => __( 'User History', 'wp-travel-engine' ),
		'order'     => 60,
		'id'        => 'extension-user-history',
		'fields'    => array(
			array(
				'divider'      => true,
				'label'        => __( 'User history tracking', 'wp-travel-engine' ),
				'help'         => __( 'Check this if you want to enable track user browsing history and data to display on bookings and enquiries.', 'wp-travel-engine' ),
				'field_type'   => 'SWITCH',
				'name'         => 'user_history.enable_tracking',
				'defaultValue' => '',
			),
			array(
				'divider'      => true,
				'label'        => __( 'Cookie consent message', 'wp-travel-engine' ),
				'help'         => __( 'Check this if you want to show cookie collection information on the website frontend.', 'wp-travel-engine' ),
				'field_type'   => 'SWITCH',
				'name'         => 'user_history.enable_cookie_consent',
				'defaultValue' => '',
			),
			array(
				'divider'      => true,
				'label'        => __( 'Cookie consent message position', 'wp-travel-engine' ),
				'help'         => __( 'This lets you choose the position of the cookie consent popup-up message.', 'wp-travel-engine' ),
				'field_type'   => 'SELECT',
				'name'         => 'user_history.cookie_position',
				'options'      => array(
					array(
						'label' => __( 'Top', 'wp-travel-engine' ),
						'value' => 'top',
					),
					array(
						'label' => __( 'Bottom', 'wp-travel-engine' ),
						'value' => 'bottom',
					),
					array(
						'label' => __( 'Bottom-Right', 'wp-travel-engine' ),
						'value' => 'bottom-right',
					),
					array(
						'label' => __( 'Bottom-Left', 'wp-travel-engine' ),
						'value' => 'bottom-left',
					),
				),
				'defaultValue' => 'top',
			),
			array(
				'divider'      => true,
				'label'        => __( 'Cookie consent message layout', 'wp-travel-engine' ),
				'help'         => __( 'This lets you choose the layout of the cookie consent popup-up message.', 'wp-travel-engine' ),
				'field_type'   => 'IMAGE_SELECTOR',
				'options'      => $cookie_image_layouts,
				'defaultValue' => 'block',
				'name'         => 'user_history.cookie_layout',
			),
			array(
				'divider'      => true,
				'label'        => __( 'Cookie consent banner background color', 'wp-travel-engine' ),
				'field_type'   => 'COLOR_PICKER',
				'name'         => 'user_history.banner_bg_color',
				'defaultValue' => '#000000',
				'description'  => __( 'Banner background color.', 'wp-travel-engine' ),
			),
			array(
				'divider'      => true,
				'label'        => __( 'Cookie consent banner button color', 'wp-travel-engine' ),
				'field_type'   => 'COLOR_PICKER',
				'name'         => 'user_history.banner_btn_color',
				'defaultValue' => '#f1d600',
			),
			array(
				'divider'      => true,
				'label'        => __( 'Cookie consent banner content text color', 'wp-travel-engine' ),
				'field_type'   => 'COLOR_PICKER',
				'name'         => 'user_history.banner_text_color',
				'defaultValue' => '#ffffff',
			),
			array(
				'divider'      => true,
				'label'        => __( 'Cookie consent banner button text color', 'wp-travel-engine' ),
				'field_type'   => 'COLOR_PICKER',
				'name'         => 'user_history.banner_btn_text_color',
				'defaultValue' => '#000000',
				'description'  => __( 'Banner button text color.', 'wp-travel-engine' ),
			),
			array(
				'divider'      => true,
				'label'        => __( 'Cookie consent learn more link URL', 'wp-travel-engine' ),
				'field_type'   => 'TEXT',
				'help'         => __( 'learn more link URL.', 'wp-travel-engine' ),
				'defaultValue' => '',
				'name'         => 'user_history.learn_more_link',
			),
			array(
				'divider'      => true,
				'label'        => __( 'Cookie consent custom message', 'wp-travel-engine' ),
				'field_type'   => 'TEXTAREA',
				'help'         => __( 'custom message', 'wp-travel-engine' ),
				'defaultValue' => 'This website uses cookies to ensure you get the best experience on our website.',
				'name'         => 'user_history.cookie_custom_message',
			),
			array(
				'divider'      => true,
				'label'        => __( 'Dismiss button text', 'wp-travel-engine' ),
				'field_type'   => 'TEXT',
				'help'         => __( 'Dismiss button text', 'wp-travel-engine' ),
				'defaultValue' => 'Got it!',
				'name'         => 'user_history.dismiss_button_text',
			),
			array(
				'divider'      => true,
				'label'        => __( 'Policy link text', 'wp-travel-engine' ),
				'field_type'   => 'TEXT',
				'help'         => __( 'Policy link text', 'wp-travel-engine' ),
				'defaultValue' => 'Learn more',
				'name'         => 'user_history.policy_link_text',
			),
		),
	)
);
