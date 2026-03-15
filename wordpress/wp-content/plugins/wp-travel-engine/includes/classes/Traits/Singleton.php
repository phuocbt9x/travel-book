<?php
/**
 * Singleton trait for plugin.
 *
 * @package wp-travel-engine
 * @since 5.3.0
 */

namespace WPTravelEngine\Traits;

/**
 * Singleton trait.
 */
trait Singleton {
	/**
	 * The single instance of the class.
	 *
	 * @since 1.0.0
	 *
	 * @var object|null
	 */
	protected static ?object $instance = null;

	/**
	 * Constructor.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	protected function __construct() {
	}

	/**
	 * Initialize singleton instance of the class. will return this instance if created otherwise create new instance first.
	 *
	 * @return object WPTravelEngine Main singleton instance.
	 * @since 1.0.0
	 */
	final public static function instance( ...$args ): object {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self( ...$args );
		}

		return self::$instance;
	}

	/**
	 * Prevent cloning.
	 *
	 * @since 1.0.0
	 */
	private function __clone() {
	}

	/**
	 * Prevent unserializing.
	 *
	 * @since 1.0.0
	 */
	public function __wakeup() {
	}
}
