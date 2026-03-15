<?php
/**
 * Post Type Payment.
 *
 * @package WPTravelEngine/Core/PostTypes
 * @since 6.0.0
 */

namespace WPTravelEngine\Core\PostTypes;

use WPTravelEngine\Abstracts\PostType;

/**
 * Class Payment
 * This class represents a payment to the WP Travel Engine plugin.
 *
 * @since 6.0.0
 */
class Payment extends PostType {

	/**
	 * Post type name.
	 *
	 * @var string
	 */
	protected string $post_type = 'wte-payments';

	/**
	 * Retrieve the labels for the custom post type.
	 *
	 * Returns an array of labels for the custom post type.
	 * This function provides the label, for payment custom post type.
	 *
	 * @return array Array of labels for the custom post type.
	 */
	public function get_labels(): array {
		return array(
			'name' => _x( 'Payments', 'post type general name', 'wp-travel-engine' ),
		);
	}

	/**
	 * Retrieve the arguments for the custom post type.
	 *
	 * Returns an array of arguments for the custom post type.
	 * This function provides the arguments for the payment custom post type.
	 *
	 * @return array Array of arguments for the custom post type.
	 */
	public function get_args(): array {

		return array(
			'labels'              => $this->get_labels(),
			'description'         => esc_html__( 'Description.', 'wp-travel-engine' ),
			'public'              => false,
			'exclude_from_search' => true,
			'publicly_queryable'  => false,
			'show_in_menu'        => false,
			'show_in_nav_menus'   => false,
		);
	}
}
