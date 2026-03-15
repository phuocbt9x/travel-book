<?php
/**
 * Logger Settings Storage.
 *
 * Simple settings class for logger configuration stored separately from main plugin settings.
 *
 * @package WPTravelEngine\Logger
 * @since 6.7.6
 */

namespace WPTravelEngine\Logger;

use WPTravelEngine\Traits\Singleton;

/**
 * Logger settings class.
 *
 * Stores logger configuration in a separate option 'wptravelengine_logger_settings'.
 *
 * @since 6.7.6
 */
class LoggerSettings {

	use Singleton;

	/**
	 * Option name for storing settings.
	 */
	const OPTION_NAME = 'wptravelengine_logger_settings';

	/**
	 * Transient key for caching settings.
	 */
	const TRANSIENT_KEY = 'wptravelengine_logger_settings_cache';

	/**
	 * Default settings.
	 *
	 * @var array
	 */
	protected static $defaults = array(
		'enabled'        => 'yes',
		'log_level'      => 'FATAL',
		'retention_days' => 7,
		'auto_cleanup'   => 'yes',
		'max_file_size'  => 10,
		'cache_duration' => 1440, // Minutes
	);

	/**
	 * Cached settings.
	 *
	 * @var array|null
	 */
	protected $settings = null;

	/**
	 * Get a setting value.
	 *
	 * @param string $key     Setting key.
	 * @param mixed  $default Default value if not set.
	 * @return mixed Setting value.
	 * @since 6.7.6
	 */
	public function get( string $key, $default = null ) {
		$this->load();

		if ( isset( $this->settings[ $key ] ) ) {
			return $this->settings[ $key ];
		}

		if ( null !== $default ) {
			return $default;
		}

		return isset( self::$defaults[ $key ] ) ? self::$defaults[ $key ] : null;
	}

	/**
	 * Set a setting value.
	 *
	 * @param string $key   Setting key.
	 * @param mixed  $value Setting value.
	 * @return void
	 * @since 6.7.6
	 */
	public function set( string $key, $value ): void {
		$this->load();
		$this->settings[ $key ] = $value;
	}

	/**
	 * Get all settings.
	 *
	 * @return array All settings with defaults merged.
	 * @since 6.7.6
	 */
	public function get_all(): array {
		$this->load();
		return array_merge( self::$defaults, $this->settings );
	}

	/**
	 * Save settings to database.
	 *
	 * @return bool True on success, false on failure.
	 * @since 6.7.6
	 */
	public function save(): bool {
		if ( null === $this->settings ) {
			return false;
		}

		$result = update_option( self::OPTION_NAME, $this->settings, false );

		// Update transient cache on successful save
		if ( $result ) {
			$cache_duration = (int) $this->get( 'cache_duration', 1440 );
			set_transient( self::TRANSIENT_KEY, $this->settings, $cache_duration * MINUTE_IN_SECONDS );
		}

		return $result;
	}

	/**
	 * Load settings from database.
	 *
	 * Uses transient cache for performance across requests.
	 *
	 * @return void
	 * @since 6.7.6
	 */
	protected function load(): void {
		if ( null !== $this->settings ) {
			return;
		}

		// Try to load from transient cache first (performance optimization)
		$cached = get_transient( self::TRANSIENT_KEY );
		if ( false !== $cached && is_array( $cached ) ) {
			$this->settings = $cached;
			return;
		}

		// Load from database if not cached
		$this->settings = get_option( self::OPTION_NAME, array() );

		if ( ! is_array( $this->settings ) ) {
			$this->settings = array();
		}

		// Cache for future requests
		$cache_duration = isset( $this->settings['cache_duration'] ) ? (int) $this->settings['cache_duration'] : 1440;
		set_transient( self::TRANSIENT_KEY, $this->settings, $cache_duration * MINUTE_IN_SECONDS );
	}

	/**
	 * Check if logging is enabled.
	 *
	 * @return bool True if logging is enabled.
	 * @since 6.7.6
	 */
	public function is_enabled(): bool {
		return 'yes' === $this->get( 'enabled', 'yes' );
	}
}
