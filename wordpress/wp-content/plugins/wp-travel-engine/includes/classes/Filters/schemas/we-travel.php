<?php
/**
 * WE Travel Schmea.
 *
 * @since 6.2.0
 */

if ( ! defined( 'WTE_AFFILIATE_BOOKING_FILE_PATH' ) || ! file_exists( WTE_AFFILIATE_BOOKING_FILE_PATH ) ) {
	return array();
}

return array(
	'wetravel' => array(
		'description' => __( 'We Travel Settings', 'wp-travel-engine' ),
		'type'        => 'object',
		'properties'  => array(
			'book_now_label' => array(
				'description' => __( 'Book Now Label', 'wp-travel-engine' ),
				'type'        => 'string',
			),
		),
	),
);
