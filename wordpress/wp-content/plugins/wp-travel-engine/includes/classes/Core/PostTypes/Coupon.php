<?php
/**
 * Post Type Booking.
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
class Coupon extends PostType {

	/**
	 * Post type name.
	 *
	 * @var string
	 */
	protected string $post_type = 'wte-coupon';

	/**
	 * Retrieve the labels for the Customer post type.
	 *
	 * Returns an array containing the labels used for the Customer post type, including
	 * names for various elements such as the post type itself, singular and plural names,
	 * menu labels, and more.
	 *
	 * @return array An array containing the labels for the Customer post type.
	 */
	public function get_labels(): array {
		return array(
			'name'               => _x( 'Coupons', 'post type general name', 'wp-travel-engine' ),
			'singular_name'      => _x( 'Coupon', 'post type singular name', 'wp-travel-engine' ),
			'menu_name'          => _x( 'Coupons', 'admin menu', 'wp-travel-engine' ),
			'name_admin_bar'     => _x( 'Coupon', 'add new on admin bar', 'wp-travel-engine' ),
			'add_new'            => _x( 'Add New', 'Coupon', 'wp-travel-engine' ),
			'add_new_item'       => esc_html__( 'Add New Coupon', 'wp-travel-engine' ),
			'new_item'           => esc_html__( 'New Coupon', 'wp-travel-engine' ),
			'edit_item'          => esc_html__( 'Edit Coupon', 'wp-travel-engine' ),
			'view_item'          => esc_html__( 'View Coupon', 'wp-travel-engine' ),
			'all_items'          => esc_html__( 'Coupons', 'wp-travel-engine' ),
			'search_items'       => esc_html__( 'Search Coupons', 'wp-travel-engine' ),
			'parent_item_colon'  => esc_html__( 'Parent Coupons:', 'wp-travel-engine' ),
			'not_found'          => esc_html__( 'No Coupons found.', 'wp-travel-engine' ),
			'not_found_in_trash' => esc_html__( 'No Coupons found in Trash.', 'wp-travel-engine' ),
		);
	}

	/**
	 * Retrieve the arguments for the Coupon post type.
	 *
	 * Returns an array containing the arguments used to register the Coupon post type.
	 *
	 * @return array An array containing the arguments for the Coupon post type.
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
			'rewrite'            => array( 'slug' => 'coupon' ),
			'capability_type'    => 'post',
			'has_archive'        => false,
			'hierarchical'       => false,
			'menu_position'      => null,
			'supports'           => array( 'title' ),
			'menu_icon'          => 'dashicons-location',
			'with_front'         => false,
		);
	}
}
