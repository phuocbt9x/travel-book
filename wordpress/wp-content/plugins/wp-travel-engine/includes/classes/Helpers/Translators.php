<?php
/**
 * Translation helper class.
 *
 * @since 6.5.1
 * @package WPTravelEngine
 */

namespace WPTravelEngine\Helpers;

class Translators {

	public function __construct() {
		add_filter(
			'wpml_pre_save_pro_translation',
			function ( $postarr, $job ) {

				if ( WP_TRAVEL_ENGINE_POST_TYPE !== ( $postarr['post_type'] ?? false ) ) {
					return $postarr;
				}

				return static::wpml_pre_save_pro_translation( $postarr, $job );
			},
			10,
			2
		);

		/**
		 * This filter is used to modify the attributes and extradata of the translation units for WPML Advanced Translation Editor.
		 *
		 * @since 6.6.9
		 */
		add_filter( 'wpml_tm_adjust_translation_job', array( __CLASS__, 'wpml_ate_translation_adjust' ) );
	}

	public static function wpml_pre_save_pro_translation( $postarr, $job ) {

		$original_trip_id = $job->original_doc_id;
		$post_metas       = get_post_meta( $original_trip_id );

		$meta_input = $postarr['meta_input'] ?? array();
		foreach ( $post_metas as $key => $value ) {
			if ( preg_match( '#^_wpml_#', $key ) ) {
				continue;
			}

			$meta_input[ $key ] = maybe_unserialize( $value[0] );
		}

		$postarr['meta_input'] = $meta_input;

		return $postarr;
	}

	/**
	 * @return array[]|false|mixed|null
	 */
	public static function get_current_language() {
		if ( function_exists( 'pll_current_language' ) ) {
			$language = pll_current_language();

			return $language ? array( $language => array() ) : false;
		}

		return apply_filters( 'wpml_active_languages', null, array() );
	}

	/**
	 * Checks if WPML multilingual is active.
	 *
	 * @return bool True if WPML multilingual mode is active, false otherwise.
	 * @since 6.6.6
	 */
	public static function is_wpml_multilingual_active(): bool {
		return defined( 'WPML_ST_VERSION' ) && apply_filters( 'wpml_current_language', null ) !== apply_filters( 'wpml_default_language', null );
	}

	/**
	 * Summary of set_wpml_language.
	 *
	 * @param string|null $lang
	 * @return void
	 * @since 6.6.6
	 */
	public static function set_wpml_language( $lang = null ): void {
		do_action( 'wpml_switch_language', $lang ?? apply_filters( 'wpml_current_language', null ) );
	}

	/**
	 * This function is used to save the translations to the wpml string.
	 *
	 * @param array|string $translations Translations to save.
	 * @param string       $base_context Name of the wpml string.
	 * @since 6.6.6
	 */
	public static function save_wpml_translation( $translations, $base_context ): void {
		if ( ! function_exists( 'icl_update_string_translation' ) ) {
			return;
		}

		$lang = apply_filters( 'wpml_current_language', null );

		if ( is_string( $translations ) ) {
			icl_update_string_translation( $base_context, $lang, $translations, 10 );
			return;
		}

		foreach ( $translations as $key => $translation ) {
			if ( is_array( $translation ) ) {
				$current_context = $base_context . '[' . $key . ']';
				static::save_wpml_translation( $translation, $current_context );
			} else {
				icl_update_string_translation( $base_context . $key, $lang, $translation, 10 );
			}
		}
	}

	/**
	 * Auto register admin strings.
	 *
	 * @return void
	 * @since 6.6.6
	 */
	public static function register_wpml_admin_strings() {
		if ( function_exists( 'wpml_st_parse_config' ) ) {
			$config_file = WP_TRAVEL_ENGINE_BASE_PATH . '/wpml-config.xml';

			if ( file_exists( $config_file ) ) {
				$config_hash = md5( serialize( $config_file ) );
				delete_transient( 'wpml_admin_text_import:parse_config:' . $config_hash );
				wpml_st_parse_config( $config_file );
			}
		}
	}

	/**
	 * This function is used to modify the attributes and extradata of the translation units for WPML Advanced Translation Editor.
	 *
	 * @since 6.6.9
	 * @param array $translation_units Translation units.
	 * @return array Translation units.
	 */
	public static function wpml_ate_translation_adjust( $translation_units ) {
		foreach ( $translation_units as $key => $value ) {
			if ( ! isset( $value['attributes']['id'] ) || strpos( $value['attributes']['id'], 'field-wp_travel_engine_setting' ) === false ) {
				continue;
			}
			$translation_units[ $key ]['attributes']['resname'] = '';
			$translation_units[ $key ]['extradata']['unit']     = '';
		}
		return $translation_units;
	}
}
