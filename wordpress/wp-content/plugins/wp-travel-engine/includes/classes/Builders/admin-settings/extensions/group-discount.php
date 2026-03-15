<?php
/**
 * Extensions Group Discount Tab Settings.
 *
 * @since 6.2.0
 */
$is_group_discount_active = defined( 'WP_TRAVEL_ENGINE_GROUP_DISCOUNT_FILE_PATH' );
$active_extensions        = apply_filters( 'wpte_get_global_extensions_tab', array() );
$file_path                = $active_extensions['wte_gd']['content_path'] ?? '';
if ( ! file_exists( $file_path ) ) {
	return array();
}
return apply_filters(
	'extension_group_discount',
	array(
		'is_active' => $is_group_discount_active,
		'title'     => __( 'Group Discount', 'wp-travel-engine' ),
		'order'     => 35,
		'id'        => 'extension-group-discount',
		'fields'    => array(
			array(
				'divider'     => true,
				'label'       => __( 'Apply Group Discount', 'wp-travel-engine' ),
				'description' => __( 'Check this if you want to enable group discount option on trips.', 'wp-travel-engine' ),
				'field_type'  => 'SWITCH',
				'name'        => 'group_discount.enable',
			),
			array(
				'condition'  => 'group_discount.enable === true',
				'field_type' => 'GROUP',
				'fields'     => array(
					array(
						'divider'     => true,
						'label'       => __( 'Group Discount Info', 'wp-travel-engine' ),
						'description' => __( 'Group discount available text message for archives.', 'wp-travel-engine' ),
						'field_type'  => 'TEXT',
						'name'        => 'group_discount.info',
					),
					array(
						'divider'     => true,
						'label'       => __( 'Discount Guide Title', 'wp-travel-engine' ),
						'description' => __( 'Enter the text for the title of group discount toggle in booking form.', 'wp-travel-engine' ),
						'field_type'  => 'TEXT',
						'name'        => 'group_discount.guide_title',
					),
					array(
						'label'       => __( 'Discount Guide Open Title', 'wp-travel-engine' ),
						'description' => __( 'Enter the text for the title of group discount toggle when toggle is opened in booking form.', 'wp-travel-engine' ),
						'field_type'  => 'TEXT',
						'name'        => 'group_discount.guide_open_title',
					),
				),
			),
		),
	)
);
