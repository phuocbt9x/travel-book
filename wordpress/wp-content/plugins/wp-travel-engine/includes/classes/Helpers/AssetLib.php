<?php
/**
 * Asset Library Helper.
 *
 * @package WPTravelEngine
 * @since 6.0.0
 */

namespace WPTravelEngine\Helpers;

/**
 * Asset Helper class.
 *
 * @since 6.0.0
 */
class AssetLib extends Asset {
	/**
	 * Constructor.
	 */
	protected function __construct( string $handle, string $source ) {
		parent::__construct( $handle, $source );
		$this->file   = plugin_dir_path( WP_TRAVEL_ENGINE_FILE_PATH ) . "assets/lib/$source";
		$this->source = plugin_dir_url( WP_TRAVEL_ENGINE_FILE_PATH ) . "assets/lib/$source";
	}
}
