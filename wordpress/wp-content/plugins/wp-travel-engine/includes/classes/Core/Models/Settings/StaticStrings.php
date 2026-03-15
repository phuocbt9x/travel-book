<?php
/**
 * String Translation.
 *
 * @since 5.7.3
 * @since 6.0.0 Namespace changed to WPTravelEngine\Core\Models\Settings
 */

namespace WPTravelEngine\Core\Models\Settings;

/**
 * Class StaticStrings.
 *
 * @since 5.7.3
 * @since 6.0.0 Extends BaseSetting.
 */
class StaticStrings extends BaseSetting {

	/**
	 * Option key.
	 *
	 * @var string
	 */
	protected static string $option_key = 'wptravelengine_custom_strings';

	/**
	 * Constructor to set the option name and optional default settings.
	 */
	public function __construct() {
		parent::__construct( static::$option_key, array() );
	}

	/**
	 * Saves modified labels to DB.
	 * Removes labels from DB.
	 *
	 * @param array $posted_data Posted Data.
	 *
	 * @since 5.7.3
	 * @deprecated 6.0.0 static::update() should be used instead.
	 */
	public static function save_custom_strings_settings( array $posted_data ) {
		if ( isset( $posted_data[ self::$option_key ] ) ) {
			if ( is_string( $posted_data[ self::$option_key ] ) ) {
				update_option( self::$option_key, array() );

				return;
			}
			$custom_strings = array();
			foreach ( $posted_data[ self::$option_key ] as $value ) {
				$label_key = wptravelengine_generate_key( $value['initial_label'] );

				$custom_strings[ $label_key ] = array(
					'initial_label'  => sanitize_text_field( $value['initial_label'] ),
					'modified_label' => sanitize_text_field( $value['modified_label'] ),
				);
			}
			update_option( self::$option_key, $custom_strings );
		}
	}

	/**
	 * Update the current setting.
	 *
	 * @param $value
	 *
	 * @return void
	 */
	public function update( $value ) {
		$this->set( null, array() );

		if ( is_array( $value ) ) {
			foreach ( $value as $string ) {
				$label_key = wptravelengine_generate_key( $string['initial_label'] );

				$this->set(
					$label_key,
					array(
						'initial_label'  => sanitize_text_field( $string['initial_label'] ),
						'modified_label' => sanitize_text_field( $string['modified_label'] ),
					)
				);
			}
		}
		$this->save();
	}

	/**
	 * Replace translated strings with custom alternatives.
	 *
	 * @param string $translated Translated string.
	 * @param string $original Original string to be translated.
	 * @param string $domain Text domain.
	 *
	 * @return string             Custom translation if available, otherwise the translated string.
	 */
	public static function translateString( string $translated, string $original, string $domain ): string {
		$saved_strings = Options::get( static::$option_key, array() );
		$key           = wptravelengine_generate_key( $translated );
		if ( isset( $saved_strings[ $key ] ) ) {
			return $saved_strings[ $key ]['modified_label'];
		}
		$key = sanitize_key( $translated );

		return $saved_strings[ $key ]['modified_label'] ?? $translated;
	}

	/**
	 * Register hooks.
	 *
	 * @since 6.3.3
	 */
	public function hooks() {
		add_filter( 'gettext_wp-travel-engine', array( __CLASS__, 'translateString' ), 11, 3 );
		add_action( 'wpte_after_save_global_settings_data', array( __CLASS__, 'save_custom_strings_settings' ) );
	}
}
