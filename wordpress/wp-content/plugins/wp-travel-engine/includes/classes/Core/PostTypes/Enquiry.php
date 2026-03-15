<?php
/**
 * Post Type Enquiry.
 *
 * @package WPTravelEngine/Core/PostTypes
 * @since 6.0.0
 */

namespace WPTravelEngine\Core\PostTypes;

use WPTravelEngine\Abstracts\PostType;

/**
 * Class Enquiry
 * This class represents a enquiry to the WP Travel Engine plugin..
 *
 * @since 6.0.0
 */
class Enquiry extends PostType {

	/**
	 * Post type name.
	 *
	 * @var string
	 */
	protected string $post_type = 'enquiry';

	/**
	 * Retrieve the labels for the custom post type.
	 *
	 * Returns an array of labels for the custom post type.
	 * This function provides the label, for enquiry custom post type.
	 *
	 * @return array Array of labels for the custom post type.
	 */
	public function get_labels(): array {
		return array(
			'name'               => _x( 'Enquiries', 'post type general name', 'wp-travel-engine' ),
			'singular_name'      => _x( 'Enquiry', 'post type singular name', 'wp-travel-engine' ),
			'menu_name'          => _x( 'Enquiries', 'admin menu', 'wp-travel-engine' ),
			'name_admin_bar'     => _x( 'Enquiry', 'add new on admin bar', 'wp-travel-engine' ),
			'add_new'            => _x( 'Add New', 'Enquiry', 'wp-travel-engine' ),
			'add_new_item'       => esc_html__( 'Add New Enquiry', 'wp-travel-engine' ),
			'new_item'           => esc_html__( 'New Enquiry', 'wp-travel-engine' ),
			'edit_item'          => esc_html__( 'Edit Enquiry', 'wp-travel-engine' ),
			'view_item'          => esc_html__( 'View Enquiry', 'wp-travel-engine' ),
			'all_items'          => esc_html__( 'Enquiries', 'wp-travel-engine' ),
			'search_items'       => esc_html__( 'Search Enquiries', 'wp-travel-engine' ),
			'parent_item_colon'  => esc_html__( 'Parent Enquiries:', 'wp-travel-engine' ),
			'not_found'          => esc_html__( 'No Enquiries found.', 'wp-travel-engine' ),
			'not_found_in_trash' => esc_html__( 'No Enquiries found in Trash.', 'wp-travel-engine' ),
		);
	}

	/**
	 * Retrieve the arguments for the custom post type.
	 *
	 * Returns an array of arguments for the custom post type.
	 * This function provides the arguments for the enquiry custom post type.
	 *
	 * @return array Array of arguments for the custom post type.
	 */
	public function get_args(): array {

		return array(
			'labels'             => $this->get_labels(),
			'description'        => esc_html__( 'Description.', 'wp-travel-engine' ),
			'public'             => false,
			'publicly_queryable' => false,
			'show_ui'            => true,
			'show_in_menu'       => 'edit.php?post_type=booking',
			'query_var'          => true,
			'rewrite'            => array( 'slug' => 'enquiry' ),
			'capability_type'    => 'post',
			'capabilities'       => array(
				'create_posts' => 'do_not_allow',
			),
			'map_meta_cap'       => true,
			'has_archive'        => true,
			'hierarchical'       => false,
			'menu_position'      => 40,
			'supports'           => array( 'title' ),
		);
	}
}
