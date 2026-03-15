<?php
/**
 * File Downloads Schema.
 */

if ( ! defined( 'WTEFD_FILE_PATH' ) || ! file_exists( WTEFD_FILE_PATH ) ) {
	return array();
}

return array(
	'file_downloads' => array(
		'description' => __( 'File Downloads Settings', 'wp-travel-engine' ),
		'type'        => 'object',
		'properties'  => array(
			'header_text'              => array(
				'description' => __( 'Header Text', 'wp-travel-engine' ),
				'type'        => 'string',
			),
			'header_description'       => array(
				'description' => __( 'Header Description', 'wp-travel-engine' ),
				'type'        => 'string',
			),
			'show_global_files_only'   => array(
				'description' => __( 'Display Global Files Only or Not', 'wp-travel-engine' ),
				'type'        => 'boolean',
			),
			'show_global_files_on_top' => array(
				'description' => __( 'Display Global Files on Top', 'wp-travel-engine' ),
				'type'        => 'boolean',
			),
			'view_in_new_tab'          => array(
				'description' => __( 'View Files in New Tab', 'wp-travel-engine' ),
				'type'        => 'boolean',
			),
			'enable_download'          => array(
				'description' => __( 'Enable One Click Download', 'wp-travel-engine' ),
				'type'        => 'boolean',
			),
			'files'                    => array(
				'description' => __( 'Files', 'wp-travel-engine' ),
				'type'        => 'array',
				'items'       => array(
					'type'       => 'object',
					'properties' => array(
						'id'    => array(
							'description' => __( 'File downloads id.', 'wp-travel-engine' ),
							'type'        => 'integer',
						),
						'type'  => array(
							'description' => __( 'File downloads type.', 'wp-travel-engine' ),
							'type'        => 'string',
						),
						'title' => array(
							'description' => __( 'File downloads title.', 'wp-travel-engine' ),
							'type'        => 'string',
						),
						'url'   => array(
							'description' => __( 'File downloads url.', 'wp-travel-engine' ),
							'type'        => 'string',
						),
					),
				),
			),
		),
	),
);
