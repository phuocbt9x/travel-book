<?php
/**
 * Asset Helper.
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
class Asset implements \WPTravelEngine\Interfaces\Asset {

	/**
	 * @var string
	 */
	public string $handle;

	/**
	 * @var string
	 */
	public string $source;

	/**
	 * @var array
	 */
	public array $dependencies = array();

	/**
	 * @var string|bool|null
	 */
	public $version = false;

	/**
	 * @var bool|array
	 */
	public $in_footer = true;

	/**
	 * @var mixed|string
	 */
	public $media = 'all';

	/**
	 * @var string file path.
	 */
	protected string $file;


	/**
	 * Constructor.
	 */
	protected function __construct( string $handle, string $source ) {
		$this->handle = $handle;
		$this->file   = plugin_dir_path( WP_TRAVEL_ENGINE_FILE_PATH ) . "dist/$source";
		$this->source = plugin_dir_url( WP_TRAVEL_ENGINE_FILE_PATH ) . "dist/$source";

		$script_asset_path = preg_replace( '#\.js$#', '', $this->file ) . '.asset.php';
		$this->version();

		if ( file_exists( $script_asset_path ) ) {
			$assets             = include_once $script_asset_path;
			$this->dependencies = array_merge( $this->dependencies, $assets['dependencies'] ?? array() );

			$this->version( $assets['version'] ?? '' );
		}
	}

	/**
	 * Set dependencies.
	 *
	 * @param array|string $dependencies Dependencies.
	 *
	 * @return $this
	 */
	public function dependencies( $dependencies ) {
		if ( is_string( $dependencies ) ) {
			$dependencies = (array) explode( ',', $dependencies );
		}

		$this->dependencies = array_unique( array_merge( $this->dependencies, $dependencies ) );

		return $this;
	}

	/**
	 * Set version.
	 *
	 * @param string|bool $version Version.
	 *
	 * @return $this
	 */
	public function version( $version = false ) {
		if ( ! $version ) {
			if ( file_exists( $this->file ) ) {
				$version = filemtime( $this->file );
			}
		}

		$this->version = $version;

		return $this;
	}

	/**
	 * Set in footer.
	 *
	 * @param array|bool $in_footer In footer.
	 *
	 * @return $this
	 */
	public function in_footer( $in_footer = true ): Asset {
		$this->in_footer = $in_footer;

		return $this;
	}

	/**
	 * Set media.
	 *
	 * @param string $media Media.
	 *
	 * @return $this
	 */
	public function media( string $media = 'all' ): Asset {
		$this->media = $media;

		return $this;
	}

	/**
	 * Register the asset.
	 *
	 * @return void
	 */
	public static function register( $handle, $source ): Asset {
		return new static( $handle, $source );
	}
}
