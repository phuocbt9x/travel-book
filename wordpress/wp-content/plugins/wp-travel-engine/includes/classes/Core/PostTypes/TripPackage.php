<?php
/**
 * Post Type TripPackage.
 *
 * @package WPTravelEngine/Core/PostTypes
 * @since 6.0.0
 */

namespace WPTravelEngine\Core\PostTypes;

use WPTravelEngine\Abstracts\PostType;

/**
 * Class TripPackage
 * This class represents a trip package to the WP Travel Engine plugin.
 *
 * @since 6.0.0
 */
class TripPackage extends PostType {

	/**
	 * Post type name.
	 *
	 * @var string
	 */
	protected string $post_type = 'trip-packages';

	/**
	 * Retrieve the arguments for the custom post type.
	 *
	 * Returns an array of arguments for the custom post type.
	 * This function provides the arguments for the tripPackage custom post type.
	 *
	 * @return array Array of arguments for the custom post type.
	 */
	public function get_args(): array {

		return array(
			'label'        => __( 'Trip Packages', 'wp-travel-engine' ),
			'public'       => false,
			'show_in_rest' => true,
			'rest_base'    => 'packages',
			'supports'     => array( 'title', 'editor', 'custom-fields' ),
			'show_in_menu' => false,
		);
	}

	/**
	 * Retrieves the post type associated with the TripPackage class.
	 *
	 * @return string The post type.
	 */
	public function taxonomies(): array {
		return array(
			'trip-packages-categories' => $this->taxonomy_package(),
		);
	}

	/**
	 * Get arguments for the 'trip-packages-categories' taxonomy.
	 *
	 * @return array Taxonomy arguments.
	 */
	public function taxonomy_package(): array {
		return array(
			'public'       => true,
			'show_in_rest' => true,
			'rest_base'    => 'package-categories',
			'hierarchical' => true,
			'show_in_menu' => true,
		);
	}
}
