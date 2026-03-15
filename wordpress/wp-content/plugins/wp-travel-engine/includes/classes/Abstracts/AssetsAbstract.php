<?php
/**
 * Assets Abstract class.
 *
 * @package WPTravelEngine
 * @since 6.0.0
 */

namespace WPTravelEngine\Abstracts;

use WPTravelEngine\Interfaces\Asset as AssetInterface;
use WPTravelEngine\Helpers\Asset;

/**
 * Assets Abstract class.
 */
abstract class AssetsAbstract {

	/**
	 * Assets.
	 *
	 * @var array
	 */
	protected static array $registered_assets = array();

	/**
	 * To be enqueued scripts.
	 *
	 * @var array
	 */
	protected static array $to_be_dequeued_scripts = array();

	/**
	 * @var array To be enqueued styles.
	 */
	protected static array $to_be_dequeued_styles = array();

	protected static array $enqueued_assets = array();

	/**
	 * @param string|AssetInterface $asset
	 * @param $type
	 * @param bool                  $enqueue
	 *
	 * @return $this
	 */
	protected function register( $asset, $type, bool $enqueue = false ) {
		$handle        = is_string( $asset ) ? $asset : $asset->handle;
		$register_func = "wp_register_{$type}";
		$enqueue_func  = "wp_enqueue_{$type}";

		if ( $asset instanceof AssetInterface ) {
			static::$registered_assets[ $type ][ $handle ] = $asset;
			$register_func(
				$handle,
				$asset->source,
				$asset->dependencies,
				$asset->version,
				'script' === $type ? $asset->in_footer : $asset->media
			);
		}
		if ( $enqueue ) {
			$enqueue_func( $handle );
		}

		return $this;
	}

	/**
	 * @param $asset AssetInterface
	 *
	 * @return $this
	 */
	public function register_script( AssetInterface $asset ) {
		return $this->register( $asset, 'script' );
	}

	/**
	 * @param $asset AssetInterface
	 *
	 * @return $this
	 */
	public function register_style( AssetInterface $asset ) {
		return $this->register( $asset, 'style' );
	}

	/**
	 * Enqueue style.
	 *
	 * @param AssetInterface|string $style The style to enqueue.
	 *
	 * @return $this
	 */
	public function enqueue_style( $style ) {
		return $this->register( $style, 'style', true );
	}

	/**
	 * Enqueue script.
	 *
	 * @param AssetInterface|string $script The script to enqueue.
	 *
	 * @return $this
	 */
	public function enqueue_script( $script ) {
		return $this->register( $script, 'script', true );
	}

	/**
	 * Dequeue style.
	 *
	 * @param string $handle The handle of the style to dequeue.
	 *
	 * @return $this
	 */
	public function dequeue_script( string $handle ) {
		static::$to_be_dequeued_scripts[] = $handle;

		return $this;
	}

	/**
	 * Dequeue style.
	 *
	 * @param string $handle The handle of the style to dequeue.
	 *
	 * @return $this
	 */
	public function dequeue_style( string $handle ) {
		static::$to_be_dequeued_styles[] = $handle;

		return $this;
	}

	/**
	 * Localize Scripts.
	 */
	public function localize_script( $handle, $object_name, $l10n ): AssetsAbstract {

		static $cache = array();

		if ( ! isset( $cache[ $handle ] ) || ! in_array( $object_name, $cache[ $handle ], true ) ) {
			$cache[ $handle ][] = $object_name;

			$l10n   = is_array( $l10n ) ? wp_json_encode( $l10n ) : $l10n;
			$script = ";(function(){
				var {$object_name} = window[{$object_name}] || {};
				if(! window.{$object_name}){
					window.{$object_name} = $l10n;
				}
			})();";

			wp_add_inline_script( $handle, $script, 'before' );
		}

		return $this;
	}
}
