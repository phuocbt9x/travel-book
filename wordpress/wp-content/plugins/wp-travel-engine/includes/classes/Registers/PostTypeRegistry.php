<?php
/**
 * Class PostTypes.
 *
 * @package WPTravelEngine\Core\PostTypes
 */

namespace WPTravelEngine\Registers;

use WPTravelEngine\Abstracts\PostType;
use WPTravelEngine\Abstracts\Registrable;

/**
 * Class PostTypes
 * This class handles the registration of post types in the WP Travel Engine plugin.
 *
 * @since 6.0.0
 */
class PostTypeRegistry extends Registrable {

	/**
	 * Register a post type.
	 *
	 * @param string $class_name The post type class.
	 * @return void
	 */
	public function register( string $class_name ): void {
		$instance = new $class_name();

		register_post_type( $instance->get_post_type(), $instance->get_args() );

		$taxonomies = $instance->taxonomies();

		foreach ( $taxonomies as $key => $taxonomy ) {
			self::register_taxonomy( $key, $instance->get_post_type(), $taxonomy );
		}
	}

	/**
	 * Register a taxonomy.
	 *
	 * @param string|array $key      The name of the taxonomy.
	 * @param string|array $post_type The name of the post type(s) to register the taxonomy for.
	 * @param array        $taxonomy  Optional. Array of taxonomy arguments.
	 * @return void
	 */
	public static function register_taxonomy( string $key, $post_type, array $taxonomy ) {
		register_taxonomy( $key, $post_type, $taxonomy );
	}
}
