<?php
/**
 * Interface for Asset.
 *
 * @package WPTravelEngine/Interfaces
 * @since 6.0.0
 */

namespace WPTravelEngine\Interfaces;

interface Asset {

	/**
	 * Set dependencies.
	 *
	 * @param array|string $dependencies Dependencies.
	 */
	public function dependencies( $dependencies );

	/**
	 * Set version.
	 *
	 * @param string|bool $version Version.
	 */
	public function version( $version = false );

	/**
	 * Set in footer.
	 *
	 * @param array|bool $in_footer In footer.
	 */
	public function in_footer( $in_footer = true );

	/**
	 * Register the asset.
	 *
	 * @return void
	 */
	public static function register( $handle, $source ): \WPTravelEngine\Helpers\Asset;
}
