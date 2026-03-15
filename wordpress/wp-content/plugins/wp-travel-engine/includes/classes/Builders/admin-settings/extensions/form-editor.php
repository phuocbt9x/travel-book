<?php
/**
 * Extensions Form Editor Tab Settings.
 *
 * @since 6.2.0
 */
$is_form_editor_active = defined( 'WTE_FORM_EDITOR_PLUGIN_FILE' );
$active_extensions     = apply_filters( 'wpte_get_global_extensions_tab', array() );
$file_path             = $active_extensions['wte_form_editor']['content_path'] ?? '';
if ( ! file_exists( $file_path ) ) {
	return array();
}
return apply_filters(
	'extension_form_editor',
	array(
		'is_active' => $is_form_editor_active,
		'title'     => __( 'Form Editor', 'wp-travel-engine' ),
		'order'     => 30,
		'id'        => 'extension-form-editor',
		'fields'    => array(
			array(
				'divider'    => true,
				'label'      => __( 'Google reCaptcha site key', 'wp-travel-engine' ),
				'help'       => __( 'Enter google reCaptcha site key.', 'wp-travel-engine' ),
				'field_type' => 'TEXT',
				'name'       => 'form_editor.recaptcha_site_key',
			),
			array(
				'label'      => __( 'Google reCaptcha secret key', 'wp-travel-engine' ),
				'help'       => __( 'Enter google reCaptcha secret key.', 'wp-travel-engine' ),
				'field_type' => 'TEXT',
				'name'       => 'form_editor.recaptcha_secret_key',
			),
		),
	)
);
