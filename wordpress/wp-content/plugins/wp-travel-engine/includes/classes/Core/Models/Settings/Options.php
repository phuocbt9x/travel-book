<?php
/**
 * Options Model.
 *
 * @package WPTravelEngine\Core\Models
 */

namespace WPTravelEngine\Core\Models\Settings;

/**
 * Class Options.
 */
class Options {

	/**
	 * Options array.
	 *
	 * @var array
	 */
	protected static array $options = array();

	/**
	 * Get option value.
	 *
	 * @param string $option_name Option Name.
	 * @param mixed  $default_value Option Value to update.
	 *
	 * @return mixed
	 */
	public static function get( string $option_name, $default_value = null ) {
		if ( ! isset( self::$options[ $option_name ] ) ) {
			self::$options[ $option_name ] = get_option( $option_name, $default_value );
		}

		return self::$options[ $option_name ];
	}

	/**
	 * Update option value.
	 *
	 * @param string $option_name Option Name.
	 * @param mixed  $value Option Value to update.
	 *
	 * @return bool
	 */
	public static function update( string $option_name, $value ): bool {
		static::unset( $option_name );

		return update_option( $option_name, $value );
	}

	/**
	 * Delete option value.
	 *
	 * @param string $option_name Option Name.
	 *
	 * @return bool
	 */
	public static function delete( string $option_name ): bool {
		return delete_option( $option_name );
	}

	/**
	 *
	 * Unset option value.
	 *
	 * @param string $option_name Option Name.
	 *
	 * @return void
	 */
	public static function unset( string $option_name ) {
		unset( self::$options[ $option_name ] );
	}

	/**
	 * This returns instance of Base Settings.
	 *
	 * @param string $option_name Option Name.
	 * @param mixed  $default_settings Default settings.
	 *
	 * @return BaseSetting
	 */
	public static function create( string $option_name, $default_settings ): BaseSetting {
		return new BaseSetting( $option_name, $default_settings );
	}
}
