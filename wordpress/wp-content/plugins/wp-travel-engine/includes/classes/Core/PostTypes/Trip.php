<?php
/**
 * Post Type Trip.
 *
 * @package WPTravelEngine/Core/PostTypes
 * @since 6.0.0
 */

namespace WPTravelEngine\Core\PostTypes;

use WPTravelEngine\Abstracts\PostType;

/**
 * Class Trip
 * This class represents a trip to the WP Travel Engine plugin.
 *
 * @since 6.0.0
 */
#[\AllowDynamicProperties]
class Trip extends PostType {

	/**
	 * Post type name.
	 *
	 * @var string
	 */
	protected string $post_type = 'trip';

	/**
	 * Retrieve the labels for the custom post type.
	 *
	 * Returns an array of labels for the custom post type.
	 * This function provides the label, for trip custom post type.
	 *
	 * @return array Array of labels for the custom post type.
	 */
	public function get_labels() {
		return array(
			'name'               => _x( 'Trips', 'post type general name', 'wp-travel-engine' ),
			'singular_name'      => _x( 'Trip', 'post type singular name', 'wp-travel-engine' ),
			'menu_name'          => _x( 'Trips', 'admin menu', 'wp-travel-engine' ),
			'name_admin_bar'     => _x( 'Trip', 'add new on admin bar', 'wp-travel-engine' ),
			'add_new'            => _x( 'Add New', 'Trip', 'wp-travel-engine' ),
			'add_new_item'       => esc_html__( 'Add New Trip', 'wp-travel-engine' ),
			'new_item'           => esc_html__( 'New Trip', 'wp-travel-engine' ),
			'edit_item'          => esc_html__( 'Edit Trip', 'wp-travel-engine' ),
			'view_item'          => esc_html__( 'View Trip', 'wp-travel-engine' ),
			'all_items'          => esc_html__( 'Trips', 'wp-travel-engine' ),
			'search_items'       => esc_html__( 'Search Trips', 'wp-travel-engine' ),
			'parent_item_colon'  => esc_html__( 'Parent Trips:', 'wp-travel-engine' ),
			'not_found'          => esc_html__( 'No Trips found.', 'wp-travel-engine' ),
			'not_found_in_trash' => esc_html__( 'No Trips found in Trash.', 'wp-travel-engine' ),
		);
	}

	/**
	 * Retrieves the icon for the Trip post type.
	 *
	 * @return string The base64-encoded SVG icon.
	 */
	public function get_icon(): string {
		return 'data:image/svg+xml;base64,' . base64_encode( '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 23.45 22.48"><title>Asset 2</title><g id="Layer_2" data-name="Layer 2"><g id="Layer_1-2" data-name="Layer 1" fill="#fff"><path d="M6.71,9.25c-.09.65-.17,1.27-.27,1.89s-.28,1.54-.4,2.31a.36.36,0,0,0,.07.22c.47.73.93,1.47,1.42,2.18a2.27,2.27,0,0,1,.39,1c.18,1.43.38,2.86.57,4.29a1,1,0,1,1-2,.3C6.3,20.31,6.13,19.14,6,18a3.19,3.19,0,0,0-.59-1.62C5,15.76,4.6,15.11,4.18,14.5a.7.7,0,0,0-.26-.22,1.58,1.58,0,0,1-1-1.69q.5-3.54,1-7.06A1.61,1.61,0,0,1,7.19,6a.82.82,0,0,0,.09.41c.19.39.4.77.62,1.14a.82.82,0,0,0,.35.29c1,.37,2.06.71,3.09,1.07a1,1,0,0,1,.35,1.61.83.83,0,0,1-.85.22c-1.32-.44-2.62-.9-3.93-1.35Z"/><path d="M2.4,3.38A1.36,1.36,0,0,1,3.75,5c-.23,1.6-.46,3.2-.71,4.79a3,3,0,0,1-.26,1,1.3,1.3,0,0,1-1.57.63,1.33,1.33,0,0,1-1-1.5Q.61,7.22,1,4.58A1.38,1.38,0,0,1,2.4,3.38Z"/><path d="M3.05,14.2a2.41,2.41,0,0,1,.75.39,14.73,14.73,0,0,1,.91,1.32c-.07.32-.17.63-.22.95a8.43,8.43,0,0,1-1.11,2.42C2.92,20.15,2.43,21,2,21.87a1,1,0,1,1-1.8-1L2.29,17a1.74,1.74,0,0,0,.14-.38c.19-.78.38-1.55.58-2.33Z"/><path d="M8.34,2a2,2,0,0,1-4,0,2,2,0,0,1,4,0Z"/><path d="M10.6,10.94l.56.07c0,.36,0,.73-.06,1.1,0,.68-.11,1.37-.15,2.05-.14,2-.27,4-.4,6L10.43,22c0,.35-.11.51-.31.5s-.28-.16-.25-.52c.11-1.76.23-3.51.34-5.27.1-1.51.19-3,.28-4.53C10.52,11.76,10.56,11.36,10.6,10.94Z"/><path d="M11.31,8.57c-.54-.14-.54-.14-.52-.64s.06-.9.1-1.34c0-.19.1-.31.3-.3s.27.15.26.33C11.4,7.27,11.36,7.91,11.31,8.57Z"/><path d="M18.16,9.25c-.1.65-.17,1.27-.28,1.89s-.27,1.54-.4,2.31a.37.37,0,0,0,.08.22c.47.73.93,1.47,1.42,2.18a2.27,2.27,0,0,1,.39,1c.18,1.43.38,2.86.57,4.29a1,1,0,1,1-2,.3c-.16-1.17-.33-2.34-.47-3.51a3.18,3.18,0,0,0-.58-1.62c-.44-.59-.82-1.24-1.23-1.85a.7.7,0,0,0-.26-.22,1.58,1.58,0,0,1-1-1.69q.5-3.54,1-7.06A1.59,1.59,0,0,1,17.2,4.2,1.62,1.62,0,0,1,18.64,6a.82.82,0,0,0,.08.41q.3.59.63,1.14a.82.82,0,0,0,.35.29c1,.37,2.06.71,3.08,1.07a1,1,0,0,1,.35,1.61.83.83,0,0,1-.85.22c-1.31-.44-2.62-.9-3.92-1.35Z"/><path d="M13.84,3.38A1.36,1.36,0,0,1,15.2,5c-.23,1.6-.47,3.2-.71,4.79a3,3,0,0,1-.26,1,1.3,1.3,0,0,1-1.57.63,1.33,1.33,0,0,1-1-1.5q.38-2.65.77-5.29A1.37,1.37,0,0,1,13.84,3.38Z"/><path d="M14.49,14.2a2.36,2.36,0,0,1,.76.39c.34.41.61.88.91,1.32-.08.32-.17.63-.22.95a8.7,8.7,0,0,1-1.11,2.42c-.46.87-.95,1.72-1.43,2.59a1,1,0,0,1-1.44.47,1,1,0,0,1-.35-1.46L13.74,17a2.46,2.46,0,0,0,.14-.38c.19-.78.38-1.55.58-2.33A.19.19,0,0,1,14.49,14.2Z"/><path d="M19.79,2a2,2,0,1,1-2-2A2,2,0,0,1,19.79,2Z"/><path d="M22.05,10.94l.56.07-.06,1.1c-.05.68-.11,1.37-.16,2.05l-.39,6c-.05.61-.08,1.23-.12,1.85,0,.35-.11.51-.31.5s-.28-.16-.26-.52c.12-1.76.24-3.51.35-5.27.1-1.51.19-3,.28-4.53C22,11.76,22,11.36,22.05,10.94Z"/><path d="M22.76,8.57c-.54-.14-.55-.14-.52-.64s.06-.9.09-1.34c0-.19.11-.31.3-.3a.26.26,0,0,1,.26.33C22.85,7.27,22.8,7.91,22.76,8.57Z"/></g></g></svg>' );
	}

	/**
	 * Retrieve the arguments for the custom post type.
	 *
	 * Returns an array of arguments for the custom post type.
	 * This function provides the arguments for the trip custom post type.
	 *
	 * @return array Array of arguments for the custom post type.
	 */
	public function get_args(): array {

		$permalink = \wp_travel_engine_get_permalink_structure();

		return array(
			'labels'             => $this->get_labels(),
			'description'        => esc_html__( 'Description.', 'wp-travel-engine' ),
			'public'             => true,
			'menu_icon'          => $this->get_icon(),
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'show_in_rest'       => true,
			'query_var'          => true,
			'rewrite'            => array(
				'slug'       => $permalink['wp_travel_engine_trip_base'],
				'with_front' => true,
			),
			'capability_type'    => 'post',
			'has_archive'        => true,
			'hierarchical'       => false,
			'menu_position'      => 31,
			// 'rest_base'              => 'trips',
			'supports'           => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments' ),
			// 'rest_namespace'        => 'wptravelengine/v2',
			// 'rest_controller_class' => \WPTravelEngine\Core\Controllers\RestAPI\V2\Trip::class,
		);
	}

	/**
	 * Retrieves the taxonomies associated with the TripPackage class.
	 *
	 * @return array The taxonomies.
	 */
	public function taxonomies(): array {
		return array(
			'destination' => $this->taxonomy_destination(),
			'activites'   => $this->taxonomy_activities(),
			'trip_types'  => $this->taxonomy_trip_types(),
			'difficulty'  => $this->taxonomy_difficulty(),
			'trip_tag'    => $this->taxonomy_trip_tags(),
		);
	}

	/**
	 * Get arguments for the 'destination' taxonomy.
	 *
	 * @return array Taxonomy arguments.
	 */
	public function taxonomy_destination(): array {
		$permalink = wp_travel_engine_get_permalink_structure();
		$labels    = array(
			'name'              => _x( 'Destinations', 'taxonomy general name', 'wp-travel-engine' ),
			'singular_name'     => _x( 'Destinations', 'taxonomy singular name', 'wp-travel-engine' ),
			'search_items'      => esc_html__( 'Search Destinations', 'wp-travel-engine' ),
			'all_items'         => esc_html__( 'All Destinations', 'wp-travel-engine' ),
			'parent_item'       => esc_html__( 'Parent Destinations', 'wp-travel-engine' ),
			'parent_item_colon' => esc_html__( 'Parent Destinations', 'wp-travel-engine' ),
			'edit_item'         => esc_html__( 'Edit Destinations', 'wp-travel-engine' ),
			'update_item'       => esc_html__( 'Update Destinations', 'wp-travel-engine' ),
			'add_new_item'      => esc_html__( 'Add New Destinations', 'wp-travel-engine' ),
			'new_item_name'     => esc_html__( 'New Destinations Name', 'wp-travel-engine' ),
			'menu_name'         => esc_html__( 'Destinations', 'wp-travel-engine' ),
		);

		return array(
			'hierarchical'      => true,
			'labels'            => $labels,
			'show_ui'           => true,
			'show_in_rest'      => true,
			'show_admin_column' => true,
			'rewrite'           => array(
				'slug'         => $permalink['wp_travel_engine_destination_base'],
				'hierarchical' => true,
			),
		);
	}

	/**
	 * Get arguments for the 'activites' taxonomy.
	 *
	 * @return array Taxonomy arguments.
	 */
	public function taxonomy_activities(): array {
		$permalink = wp_travel_engine_get_permalink_structure();
		$labels    = array(
			'name'              => _x( 'Activities', 'taxonomy general name', 'wp-travel-engine' ),
			'singular_name'     => _x( 'Activities', 'taxonomy singular name', 'wp-travel-engine' ),
			'search_items'      => esc_html__( 'Search Activities', 'wp-travel-engine' ),
			'all_items'         => esc_html__( 'All Activities', 'wp-travel-engine' ),
			'parent_item'       => esc_html__( 'Parent Activities', 'wp-travel-engine' ),
			'parent_item_colon' => esc_html__( 'Parent Activities', 'wp-travel-engine' ),
			'edit_item'         => esc_html__( 'Edit Activities', 'wp-travel-engine' ),
			'update_item'       => esc_html__( 'Update Activities', 'wp-travel-engine' ),
			'add_new_item'      => esc_html__( 'Add New Activities', 'wp-travel-engine' ),
			'new_item_name'     => esc_html__( 'New Activities Name', 'wp-travel-engine' ),
			'menu_name'         => esc_html__( 'Activities', 'wp-travel-engine' ),
		);

		return array(
			'hierarchical'      => true,
			'labels'            => $labels,
			'show_ui'           => true,
			'show_in_rest'      => true,
			'show_admin_column' => true,
			'rewrite '          => array(
				'slug'         => $permalink['wp_travel_engine_activity_base'],
				'hierarchical' => true,
			),
		);
	}

	/**
	 * Get arguments for the 'trip_types' taxonomy.
	 *
	 * @return array Taxonomy arguments.
	 */
	public function taxonomy_trip_types(): array {
		$permalink = wp_travel_engine_get_permalink_structure();
		$labels    = array(
			'name'              => _x( 'Trip Type', 'taxonomy general name', 'wp-travel-engine' ),
			'singular_name'     => _x( 'Trip Type', 'taxonomy singular name', 'wp-travel-engine' ),
			'search_items'      => esc_html__( 'Search Trip Type', 'wp-travel-engine' ),
			'all_items'         => esc_html__( 'All Trip Type', 'wp-travel-engine' ),
			'parent_item'       => esc_html__( 'Parent Trip Type', 'wp-travel-engine' ),
			'parent_item_colon' => esc_html__( 'Parent Trip Type', 'wp-travel-engine' ),
			'edit_item'         => esc_html__( 'Edit Trip Type', 'wp-travel-engine' ),
			'update_item'       => esc_html__( 'Update Trip Type', 'wp-travel-engine' ),
			'add_new_item'      => esc_html__( 'Add New Trip Type', 'wp-travel-engine' ),
			'new_item_name'     => esc_html__( 'New Trip Type Name', 'wp-travel-engine' ),
			'menu_name'         => esc_html__( 'Trip Type', 'wp-travel-engine' ),
		);

		return array(
			'hierarchical'      => true,
			'labels'            => $labels,
			'show_ui'           => true,
			'show_in_rest'      => true,
			'show_admin_column' => true,
			'rewrite'           => array(
				'slug'         => $permalink['wp_travel_engine_trip_type_base'],
				'hierarchical' => true,
			),
		);
	}

	/**
	 * Get arguments for the 'difficulty' taxonomy.
	 *
	 * @return array Taxonomy arguments.
	 */
	public function taxonomy_difficulty(): array {
		$permalink = wp_travel_engine_get_permalink_structure();
		$labels    = array(
			'name'              => _x( 'Difficulty', 'taxonomy general name', 'wp-travel-engine' ),
			'singular_name'     => _x( 'Difficulty', 'taxonomy singular name', 'wp-travel-engine' ),
			'search_items'      => esc_html__( 'Search Difficulty', 'wp-travel-engine' ),
			'all_items'         => esc_html__( 'All Difficulties', 'wp-travel-engine' ),
			'parent_item'       => esc_html__( 'Parent Difficulty', 'wp-travel-engine' ),
			'parent_item_colon' => esc_html__( 'Parent Difficulty', 'wp-travel-engine' ),
			'edit_item'         => esc_html__( 'Edit Difficulty', 'wp-travel-engine' ),
			'update_item'       => esc_html__( 'Update Difficulty', 'wp-travel-engine' ),
			'add_new_item'      => esc_html__( 'Add New Difficulty', 'wp-travel-engine' ),
			'new_item_name'     => esc_html__( 'New Difficulty Name', 'wp-travel-engine' ),
			'menu_name'         => esc_html__( 'Difficulty', 'wp-travel-engine' ),
		);

		return array(
			'hierarchical'       => true,
			'labels'             => $labels,
			'show_ui'            => true,
			'show_in_rest'       => true,
			'show_in_quick_edit' => false,
			'show_admin_column'  => true,
			'rewrite'            => array(
				'slug'         => $permalink['wp_travel_engine_difficulty_base'],
				'hierarchical' => true,
			),
			'meta_box_cb'        => false,
		);
	}

	/**
	 * Get arguments for the 'trip_tag' taxonomy.
	 *
	 * @return array Taxonomy arguments.
	 */
	public function taxonomy_trip_tags(): array {
		$permalink = wp_travel_engine_get_permalink_structure();
		$labels    = array(
			'name'              => _x( 'Trip Tag', 'taxonomy general name', 'wp-travel-engine' ),
			'singular_name'     => _x( 'Tag', 'taxonomy singular name', 'wp-travel-engine' ),
			'search_items'      => esc_html__( 'Search Tag', 'wp-travel-engine' ),
			'all_items'         => esc_html__( 'All Tags', 'wp-travel-engine' ),
			'parent_item'       => esc_html__( 'Parent Tag', 'wp-travel-engine' ),
			'parent_item_colon' => esc_html__( 'Parent Tag', 'wp-travel-engine' ),
			'edit_item'         => esc_html__( 'Edit Tag', 'wp-travel-engine' ),
			'update_item'       => esc_html__( 'Update Tag', 'wp-travel-engine' ),
			'add_new_item'      => esc_html__( 'Add New Tag', 'wp-travel-engine' ),
			'new_item_name'     => esc_html__( 'New Tag Name', 'wp-travel-engine' ),
			'menu_name'         => esc_html__( 'Tag', 'wp-travel-engine' ),
		);

		return array(
			'hierarchical'      => false,
			'labels'            => $labels,
			'show_ui'           => true,
			'show_in_rest'      => true,
			'show_admin_column' => true,
			'rewrite'           => array(
				'slug'         => $permalink['wp_travel_engine_tags_base'],
				'hierarchical' => false,
			),
		);
	}
}
