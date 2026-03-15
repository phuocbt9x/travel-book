<?php
/**
 * Factory Trait Class.
 *
 * @package WPTravelEngine\Traits
 * @since 6.0.0
 */

namespace WPTravelEngine\Traits;

use WPTravelEngine\Container;
use WPTravelEngine\Abstracts\PostModel;

/**
 * Factory Trait Class.
 *
 * @since 6.0.0
 */
trait Factory {
	/**
	 * Create a new instance of the class.
	 *
	 * @param array $args The arguments to pass to the constructor.
	 *
	 * @return object
	 */
	public static function make( ...$args ): object {
		$className = static::class;

		if ( is_subclass_of( $className, PostModel::class ) ) {
			return Container::post( $args, $className );
		}

		if ( ! Container::has_class( $className ) ) {
			Container::register( $className, $className );
		}

		return Container::get( $className, ...$args );
	}

	/**
	 * Get or create a singleton instance for the given key.
	 *
	 * This method returns a cached instance identified by the key. If no instance exists,
	 * it creates one and caches it for future calls.
	 *
	 * @param string|int $key    The unique identifier for this instance.
	 * @param mixed      ...$args Optional constructor arguments (used only on first creation).
	 *
	 * @return static
	 * @since 6.7.0
	 */
	public static function for( $key, ...$args ): object {
		$cache_key = self::generate_cache_key( $key );

		if ( ! Container::has_instance( $cache_key ) ) {
			$instance = empty( $args ) ? new static( $key ) : new static( ...$args );
			Container::register_instance( $cache_key, $instance );
		}

		return Container::get( $cache_key );
	}

	/**
	 * Generate a unique cache key for the factory instance.
	 *
	 * @param string|int $key The identifier.
	 *
	 * @return string
	 * @since 6.7.0
	 */
	private static function generate_cache_key( $key ): string {
		$class_key = str_replace( '\\', '_', strtolower( static::class ) );
		return "{$class_key}_{$key}";
	}
}
