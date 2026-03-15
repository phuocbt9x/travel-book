<?php
/**
 * Base Option Model Class.
 *
 * @package WPTravelEngine/Core/Models
 * @since 6.0.0
 */

namespace WPTravelEngine\Core\Models\Settings;

use Exception;
use WPTravelEngine\Utilities\ArrayUtility;

/**
 * Base Setting/Option Class.
 *
 * @since 6.0.0
 */
class BaseSetting {

	/**
	 * Option name used to store the plugin settings array in the database.
	 *
	 * @var string
	 */
	protected string $option_name;

	/**
	 * Cached settings data.
	 *
	 * @var array|null
	 */
	protected ?ArrayUtility $settings = null;

	/**
	 * Optional default settings array.
	 *
	 * @var array
	 */
	protected array $default_settings = array();

	/**
	 * Setting data types for validation.
	 *
	 * @var array
	 */
	protected array $setting_types = array();

	/**
	 * Constructor to set the option name and optional default settings.
	 *
	 * @param string $option_name The name of the option.
	 * @param array  $default_settings The default settings array.
	 */
	public function __construct( string $option_name, $default_settings ) {
		$this->option_name      = $option_name;
		$this->default_settings = $default_settings;
		$this->settings         = ArrayUtility::make( Options::get( $this->option_name, $default_settings ) );
		// $this->settings         = array_merge( $this->default_settings, $this->settings );
	}

	/**
	 * Retrieves a specific setting value from the settings array.
	 *
	 * @param string|null $key The key of the setting to retrieve.
	 * @param mixed       $default_value The default value to return if the key is not found.
	 *
	 * @return mixed The value of the setting, or the default value if not found.
	 */
	public function get( string $key = null, $default_value = null ) {
		if ( is_null( $key ) ) {
			return $this->settings->value();
		}

		return $this->settings->get( $key ) ?? $default_value;
	}

	/**
	 * Checks if the default value is equal to the setting value.
	 *
	 * @param string $key The key of the setting to retrieve.
	 * @param mixed  $default_value The default value for the setting.
	 *
	 * @return bool Returns true if retrieved value is equal to setting value, otherwise returns false.
	 */
	public function is( string $key, $default_value ) {
		return $this->get( $key, $default_value ) === $default_value;
	}

	/**
	 * Magic method to provide dynamic access to settings data.
	 *
	 * @param string $name The name of the setting property.
	 *
	 * @return mixed The value of the setting property, or null if not found.
	 */
	public function __get( string $name ) {
		return $this->get( $name );
	}

	/**
	 * Updates a specific setting value in the settings array.
	 *
	 * @param string|null $key The key of the setting to update.
	 * @param mixed       $value The new value for the setting.
	 */
	public function set( ?string $key, $value ) {
		// if ( isset( $this->setting_types[ $key ] ) ) {
		// $type = $this->setting_types[ $key ];
		// if ( ! settype( $value, $type ) ) {
		// throw new Exception( sprintf( "Setting '%s' type validation failed. Expected '%s'.", esc_html( $key ), esc_html( $type ) ) );
		// }
		// }
		if ( is_null( $key ) ) {
			$this->settings = ArrayUtility::make( $value );
		} else {
			$this->settings->set( $key, $value );
		}
	}

	/**
	 * Defines the expected data type for a specific setting.
	 *
	 * @param string $key The key of the setting.
	 * @param string $type The expected data type (e.g., 'string', 'int').
	 */
	public function set_type( string $key, string $type ) {
		$this->setting_types[ $key ] = $type;
	}

	/**
	 * Recursive helper function to retrieve nested values from the settings array.
	 *
	 * @param array $data The current level of data to traverse.
	 * @param array $keys The remaining keys in the dot-separated path.
	 *
	 * @return mixed The value at the end of the path, or null if not found.
	 */
	private function search( array $data, array $keys ) {
		$key = array_shift( $keys );
		if ( ! isset( $data[ $key ] ) ) {
			return null;
		}

		if ( empty( $keys ) ) {
			return $data[ $key ];
		} else {
			if ( ! is_array( $data[ $key ] ) ) {
				return null;
			}

			return $this->search( $data[ $key ], $keys );
		}
	}

	/**
	 * Saves the updated settings to the WordPress options table.
	 */
	public function save() {
		if ( Options::update( $this->option_name, $this->settings->value() ) ) {
			$this->load_settings();
		}
	}

	/**
	 * Loads the settings data from the option table into the cache.
	 */
	public function load_settings() {
		Options::unset( $this->option_name );
		$this->settings = ArrayUtility::make( Options::get( $this->option_name ) );
	}
}
