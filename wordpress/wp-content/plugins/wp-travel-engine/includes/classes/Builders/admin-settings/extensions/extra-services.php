<?php
/**
 * Extensions Extra Services Tab Settings.
 *
 * @since 6.2.0
 */
$is_extra_services_active = defined( 'WTE_EXTRA_SERVICES_FILE_PATH' );
$active_extensions        = apply_filters( 'wpte_get_global_extensions_tab', array() );
$file_path                = $active_extensions['wte_extra_services']['content_path'] ?? '';
if ( ! file_exists( $file_path ) ) {
	return array();
}
return apply_filters(
	'extension_extra_services',
	array(
		'is_active' => $is_extra_services_active,
		'title'     => __( 'Extra Services', 'wp-travel-engine' ),
		'order'     => 15,
		'id'        => 'extension-extra-services',
		'fields'    => array(
			array(
				'field_type' => 'ALERT',
				'content'    => sprintf( __( 'The extra services have been moved from here to separate post-type for advanced features. %1$sView Extra Services%2$s', 'wp-travel-engine' ), '<a href=" ' . get_admin_url() . 'edit.php?post_type=wte-services"  target="_blank">', '</a>' ),
			),
			array(
				'divider'     => true,
				'label'       => __( 'Extra service title', 'wp-travel-engine' ),
				'help'        => __( 'Title For the Extra Service section.', 'wp-travel-engine' ),
				'field_type'  => 'TEXT',
				'placeholder' => __( 'Enter title', 'wp-travel-engine' ),
				'name'        => 'extra_services.title',
			),
		),
	)
);
