<?php
/**
 * View Helper.
 *
 * @package WPTravelEngine
 * @since 6.0.0
 */

namespace WPTravelEngine\Helpers;

/**
 * View class.
 */
class View {

	/**
	 * Render a view.
	 *
	 * @param string $view View file.
	 * @param array  $args Arguments to pass to the view.
	 * @return void
	 */
	protected static function render( string $view, array $args = array() ) {
		if ( file_exists( $view ) ) {
			extract( $args );
			include $view;
		}
	}

	/**
	 * Render an admin view.
	 *
	 * @param string $view View file.
	 * @param array  $args Arguments to pass to the view.
	 * @return void
	 */
	public static function admin_view( string $view, array $args = array() ) {
		self::render( plugin_dir_path( WP_TRAVEL_ENGINE_FILE_PATH ) . 'includes/backend/' . $view . '.php', $args );
	}
}
