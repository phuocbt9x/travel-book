<?php
/**
 * Shortcodes Helpers.
 *
 * @package WPTravelEngine/Helpers
 * @since 6.0.0
 */

namespace WPTravelEngine\Registers;

use WPTravelEngine\Abstracts\Registrable;

/**
 * Shortcode Registry.
 */
class ShortcodeRegistry extends Registrable {

	/**
	 * Register the admin pages.
	 *
	 * @param string $class_name The class.
	 *
	 * @return \WPTravelEngine\Interfaces\Registrable
	 */
	public function register( string $class_name ): \WPTravelEngine\Interfaces\Registrable {
		$this->items[ $class_name::TAG ] = $class_name;

		$instance = new $class_name();
		// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.DynamicHooknameFound
		$short_tag = apply_filters( $instance::TAG . '_shortcode_tag', $instance::TAG );
		// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.DynamicHooknameFound
		$callback = apply_filters( $instance::TAG . '_shortcode_callback', array( $instance, 'render' ) );

		add_shortcode( $short_tag, $callback );

		return $this;
	}

	/**
	 * Register by path.
	 *
	 * @param string|null $pathname The path.
	 * @param string|null $name_space The namespace.
	 *
	 * @return void
	 */
	public function register_by_path( $pathname = null, $name_space = null ) {
		parent::register_by_path(
			$pathname ?? WP_TRAVEL_ENGINE_BASE_PATH . '/classes/Core/Shortcodes',
			$name_space ?? 'WPTravelEngine\Core\Shortcodes'
		);
	}
}
