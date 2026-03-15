<?php

/**
 * Extensions File Downloads Tab Settings.
 *
 * @since 6.2.0
 */
$is_file_downloads_active = defined( 'WTEFD_FILE_PATH' );
$active_extensions        = apply_filters( 'wpte_get_global_extensions_tab', array() );
$file_path                = $active_extensions['wte_file_downloads']['content_path'] ?? '';
if ( ! file_exists( $file_path ) ) {
	return array();
}
return apply_filters(
	'extension_file_downloads',
	array(
		'is_active' => $is_file_downloads_active,
		'title'     => __( 'File Downloads', 'wp-travel-engine' ),
		'order'     => 20,
		'id'        => 'extension-file-downloads',
		'fields'    => array(
			array(
				'divider'     => true,
				'label'       => __( 'File Download Header Text', 'wp-travel-engine' ),
				'field_type'  => 'TEXT',
				'placeholder' => __( 'Header Text', 'wp-travel-engine' ),
				'name'        => 'file_downloads.header_text',
			),
			array(
				'divider'     => true,
				'label'       => __( 'File Download Header Description', 'wp-travel-engine' ),
				'field_type'  => 'TEXTAREA',
				'placeholder' => __( 'Header Description', 'wp-travel-engine' ),
				'name'        => 'file_downloads.header_description',
			),
			array(
				'divider'    => true,
				'label'      => __( 'List Global or Trip Specific Files', 'wp-travel-engine' ),
				'help'       => __( 'If checked, global files will be displayed by default if plugin cannot find trip specific files. If unchecked, only file listed in specific trip will be displayed. Else, nothing will be shown.', 'wp-travel-engine' ),
				'field_type' => 'SWITCH',
				'name'       => 'file_downloads.show_global_files_only',
			),
			array(
				'divider'    => true,
				'label'      => __( 'Always List Global File', 'wp-travel-engine' ),
				'help'       => __( 'If checked, global file(s) will always be listed. Then only, trip specific file(s) will be listed. Repeated files will be ommitted.', 'wp-travel-engine' ),
				'field_type' => 'SWITCH',
				'name'       => 'file_downloads.show_global_files_on_top',
			),
			array(
				'divider'    => true,
				'label'      => __( 'View File in New Tab', 'wp-travel-engine' ),
				'help'       => __( 'If checked, files like image, pdf, txt will be loaded in new tab. Files like doc,docx are downloaded by default.', 'wp-travel-engine' ),
				'field_type' => 'SWITCH',
				'name'       => 'file_downloads.view_in_new_tab',
			),
			array(
				'divider'    => true,
				'label'      => __( 'One Click Download', 'wp-travel-engine' ),
				'help'       => __( 'If checked, file(s) will always be downloaded on click.', 'wp-travel-engine' ),
				'field_type' => 'SWITCH',
				'name'       => 'file_downloads.enable_download',
			),
			array(
				'field_type' => 'ALERT',
				'content'    => __( '<strong>Note : [trip_file_downloads] </strong>- Usable global shortcode to list and display downloadable files.', 'wp-travel-engine' ),
			),
			array(
				'field_type' => 'TITLE',
				'title'      => __( 'File Uploader', 'wp-travel-engine' ),
			),
			array(
				'field_type'  => 'FIELD_HEADER',
				'description' => __( 'You can upload and set the global files from this setting. You can also change/add the file(s) from each individual trip page as well.', 'wp-travel-engine' ),
			),
			array(
				'field_type'          => 'FILE_UPLOADER',
				'fileTypes'           => array(),
				'className'           => 'wpte-media-uploader-field',
				'isMultiple'          => true,
				'uploaderDescription' => __( 'Max. file size 5MB Supports: JPG, PNG, WebP images', 'wp-travel-engine' ),
				'name'                => 'file_downloads.files',
			),
		),
	)
);
