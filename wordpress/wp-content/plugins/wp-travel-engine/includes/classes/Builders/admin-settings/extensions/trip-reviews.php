<?php
/**
 * Extensions Trip Reviews Tab Settings.
 *
 * @since 6.2.0
 */
$is_trip_reviews_active = defined( 'WTE_TRIP_REVIEW_FILE_PATH' );
$active_extensions      = apply_filters( 'wpte_get_global_extensions_tab', array() );
$file_path              = $active_extensions['wte_trip_reviews']['content_path'] ?? '';
if ( ! file_exists( $file_path ) ) {
	return array();
}
return apply_filters(
	'extension_trip_reviews',
	array(
		'is_active' => $is_trip_reviews_active,
		'title'     => __( 'Trip Reviews', 'wp-travel-engine' ),
		'order'     => 50,
		'id'        => 'extension-trip-reviews',
		'fields'    => array(
			array(
				'divider'    => true,
				'label'      => __( 'Show Trip Reviews', 'wp-travel-engine' ),
				'help'       => __( 'Enable the switch to show whole trip reviews section on your trip page.', 'wp-travel-engine' ),
				'field_type' => 'SWITCH',
				'default'    => '',
				'name'       => 'trip_reviews.enable',
			),
			array(
				'divider'    => true,
				'label'      => __( 'Show Trip Review Form', 'wp-travel-engine' ),
				'help'       => __( 'Enable the switch to show trip review form section on your trip page.', 'wp-travel-engine' ),
				'field_type' => 'SWITCH',
				'default'    => '',
				'name'       => 'trip_reviews.enable_from',
			),
			array(
				'field_type' => 'TITLE',
				'title'      => __( 'Review Labels', 'wp-travel-engine' ),
			),
			array(
				'divider'    => true,
				'label'      => __( 'Trip Review Label', 'wp-travel-engine' ),
				'help'       => __( 'Default Label: Overall Trip Rating. This label is displayed before the post specific trip reviews.', 'wp-travel-engine' ),
				'field_type' => 'TEXT',
				'default'    => 'Overall Trip Rating:',
				'name'       => 'trip_reviews.label',
			),
			array(
				'divider'    => true,
				'label'      => __( 'Company Trip Label', 'wp-travel-engine' ),
				'help'       => __( 'Default Label: Overall Company Rating. This label is displayed before the all trip\'s review listing rather than individual post reviews.', 'wp-travel-engine' ),
				'field_type' => 'TEXT',
				'default'    => 'Overall Company Rating:',
				'name'       => 'trip_reviews.rating_label',
			),
			array(
				'divider'    => true,
				'label'      => __( 'Reviewed Tour Label', 'wp-travel-engine' ),
				'help'       => __( 'Default Label: Reviewed Tour. This label is displayed before the trip post link on singular review i.e. quick link to trip post on which review was posted.', 'wp-travel-engine' ),
				'field_type' => 'TEXT',
				'default'    => 'Reviewed Tour:',
				'name'       => 'trip_reviews.reviewed_tour_label',
			),
			array(
				'divider'    => true,
				'label'      => __( 'Excellent Review Label', 'wp-travel-engine' ),
				'field_type' => 'TEXT',
				'default'    => 'Excellent',
				'name'       => 'trip_reviews.excellent_label',
			),
			array(
				'divider'    => true,
				'label'      => __( 'Very Good Review Label', 'wp-travel-engine' ),
				'field_type' => 'TEXT',
				'default'    => 'Very Good',
				'name'       => 'trip_reviews.very_good_label',
			),
			array(
				'divider'    => true,
				'label'      => __( 'Average Review Label', 'wp-travel-engine' ),
				'field_type' => 'TEXT',
				'default'    => 'Average',
				'name'       => 'trip_reviews.average_label',
			),
			array(
				'divider'    => true,
				'label'      => __( 'Poor Review Label', 'wp-travel-engine' ),
				'field_type' => 'TEXT',
				'default'    => 'Poor',
				'name'       => 'trip_reviews.poor_label',

			),
			array(
				'divider'    => true,
				'label'      => __( 'Terrible Review Label', 'wp-travel-engine' ),
				'field_type' => 'TEXT',
				'default'    => 'Terrible',
				'name'       => 'trip_reviews.terrible_label',
			),
			array(
				'field_type' => 'TITLE',
				'title'      => __( 'Emoticons Setting', 'wp-travel-engine' ),
			),
			array(
				'divider'    => true,
				'label'      => __( 'Show Trip Review Emoticons', 'wp-travel-engine' ),
				'help'       => __( 'Default: Hidden. If unchecked, emoticons will be shown for average review and overall overage review section.', 'wp-travel-engine' ),
				'field_type' => 'SWITCH',
				'name'       => 'trip_reviews.enable_emoticons',
			),
			array(
				'field_type' => 'TITLE',
				'title'      => __( 'Show/Show Fields', 'wp-travel-engine' ),
			),
			array(
				'divider'    => true,
				'label'      => __( 'Show Experience Date Field', 'wp-travel-engine' ),
				'help'       => __( 'Default: Shown. If unchecked, Experience Date field will be hidden from form and won\'t be shown in review section.', 'wp-travel-engine' ),
				'field_type' => 'SWITCH',
				'name'       => 'trip_reviews.enable_expericence_date',
			),
			array(
				'divider'    => true,
				'label'      => __( 'Show Gallery Image', 'wp-travel-engine' ),
				'help'       => __( 'Default: Shown. If unchecked, Gallery Image field will be hidden from form and won\'t be shown in review section', 'wp-travel-engine' ),
				'field_type' => 'SWITCH',
				'default'    => '',
				'name'       => 'trip_reviews.enable_gallery_images',
			),
			array(
				'divider'    => true,
				'label'      => __( 'Show Reviewed Tour', 'wp-travel-engine' ),
				'help'       => __( 'Default: Shown. If unchecked, Reviewed Tour Link won\'t be shown in review section.', 'wp-travel-engine' ),
				'field_type' => 'SWITCH',
				'name'       => 'trip_reviews.enable_reviewed_tour',
			),
			array(
				'divider'    => true,
				'label'      => __( 'Show Client Location', 'wp-travel-engine' ),
				'help'       => __( 'Default: Shown. If unchecked, Client Location field will be hidden from form and won\'t be shown in review section.', 'wp-travel-engine' ),
				'field_type' => 'SWITCH',
				'name'       => 'trip_reviews.enable_client_location',
			),

		),
	)
);
