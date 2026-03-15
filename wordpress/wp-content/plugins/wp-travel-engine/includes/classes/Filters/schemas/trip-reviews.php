<?php
/**
 * Trip Reviews Schema.
 *
 * @since 6.2.0
 */

if ( ! defined( 'WTE_TRIP_REVIEW_FILE_PATH' ) || ! file_exists( WTE_TRIP_REVIEW_FILE_PATH ) ) {
	return array();
}

return array(
	'trip_reviews' => array(
		'description' => __( 'Trip review settings', 'wp-travel-engine' ),
		'type'        => 'object',
		'properties'  => array(
			'enable'                  => array(
				'description' => __( 'Show trip reviews list section in trip page.', 'wp-travel-engine' ),
				'type'        => 'boolean',
			),
			'enable_from'             => array(
				'description' => __( 'Show trip review form section in trip page.', 'wp-travel-engine' ),
				'type'        => 'boolean',
			),
			'label'                   => array(
				'description' => __( 'Trip review label.', 'wp-travel-engine' ),
				'type'        => 'string',
			),
			'rating_label'            => array(
				'description' => __( 'Trip review rating label.', 'wp-travel-engine' ),
				'type'        => 'string',
			),
			'reviewed_tour_label'     => array(
				'description' => __( 'Trip review reviewed tour label.', 'wp-travel-engine' ),
				'type'        => 'string',
			),
			'excellent_label'         => array(
				'description' => __( 'Trip review excellent label.', 'wp-travel-engine' ),
				'type'        => 'string',
			),
			'very_good_label'         => array(
				'description' => __( 'Trip review very good label.', 'wp-travel-engine' ),
				'type'        => 'string',
			),
			'average_label'           => array(
				'description' => __( 'Trip review average label.', 'wp-travel-engine' ),
				'type'        => 'string',
			),
			'poor_label'              => array(
				'description' => __( 'Trip review poor label.', 'wp-travel-engine' ),
				'type'        => 'string',
			),
			'terrible_label'          => array(
				'description' => __( 'Trip review terrible label.', 'wp-travel-engine' ),
				'type'        => 'string',
			),
			'enable_emoticons'        => array(
				'description' => __( 'Enable emoticons in trip review form.', 'wp-travel-engine' ),
				'type'        => 'boolean',
			),
			'enable_expericence_date' => array(
				'description' => __( 'Show experience date in trip review form.', 'wp-travel-engine' ),
				'type'        => 'boolean',
			),
			'enable_gallery_images'   => array(
				'description' => __( 'Show gallery images in trip review form.', 'wp-travel-engine' ),
				'type'        => 'boolean',
			),
			'enable_reviewed_tour'    => array(
				'description' => __( 'Show reviewed tour in trip review form.', 'wp-travel-engine' ),
				'type'        => 'boolean',
			),
			'enable_client_location'  => array(
				'description' => __( 'Show client location in trip review form.', 'wp-travel-engine' ),
				'type'        => 'boolean',
			),
		),
	),
);
