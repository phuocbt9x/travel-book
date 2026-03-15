<?php
/**
 * Extensions WeTravel Tab Settings.
 *
 * @since 6.2.0
 */
$is_we_travel_active = defined( 'WTE_AFFILIATE_BOOKING_FILE_PATH' );
$active_extensions   = apply_filters( 'wpte_get_global_extensions_tab', array() );
$file_path           = $active_extensions['wte_wetravel']['content_path'] ?? '';
if ( ! file_exists( $file_path ) ) {
	return array();
}
return apply_filters(
	'extension_we_travel',
	array(
		'is_active' => $is_we_travel_active,
		'title'     => __( 'WeTravel', 'wp-travel-engine' ),
		'order'     => 65,
		'id'        => 'extension-we-travel',
		'fields'    => array(
			array(
				'divider'      => true,
				'label'        => __( 'Book Now Label', 'wp-travel-engine' ),
				'help'         => __( 'Label for Book Now button.', 'wp-travel-engine' ),
				'field_type'   => 'TEXT',
				'defaultValue' => 'Book Now',
				'name'         => 'wetravel.book_now_label',
			),
		),
	)
);
