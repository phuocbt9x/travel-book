<?php
/**
 * Class PostType.
 *
 * @package WPTravelEngine\Abstracts
 * @since 6.0.0
 */

namespace WPTravelEngine\Abstracts;

/**
 * Class PostType
 * This class represents a post type in the WP Travel Engine plugin.
 *
 * @since 6.0.0
 */
abstract class PostType {

	/**
	 * Post type name.
	 *
	 * @var string
	 */
	protected string $post_type;

	/**
	 * Retrieves the post type associated with the PostType class.
	 *
	 * @return string The post type.
	 */
	public function get_post_type(): string {
		return $this->post_type;
	}

	/**
	 * Retrieves the taxonomies associated with the Post-Type.
	 *
	 * @return array The taxonomies.
	 */
	public function taxonomies(): array {
		return array();
	}

	/**
	 * Retrieves the default capabilities associated with the Post-Type.
	 *
	 * @since 6.3.5
	 * @return array The default capabilities.
	 */
	protected function get_default_capabilities(): array {
		return array(
			'edit_post'          => 'edit_post',
			'read_post'          => 'read_post',
			'delete_post'        => 'delete_post',
			'edit_posts'         => 'edit_posts',
			'edit_others_posts'  => 'edit_others_posts',
			'publish_posts'      => 'publish_posts',
			'read_private_posts' => 'read_private_posts',
		);
	}

	/**
	 * Retrieves the capabilities associated with the Post-Type.
	 * Can be overridden by child classes to provide custom capabilities.
	 *
	 * @since 6.3.5
	 * @return array The capabilities.
	 */
	public function get_capabilities(): array {
		return $this->get_default_capabilities();
	}
}
