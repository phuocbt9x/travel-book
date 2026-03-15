<?php
/**
 * Registrable Abstract Class.
 *
 * @package WPTravelEngine/Abstracts
 */

namespace WPTravelEngine\Abstracts;

use WPTravelEngine\Interfaces\Registrable as RegistrableInterface;
use WPTravelEngine\Traits\Factory;

/**
 * Class Registrable.
 *
 * @since 6.0.0
 */
abstract class Registrable implements RegistrableInterface {

	use Factory;

	/**
	 * The items.
	 *
	 * @var array $items
	 */
	protected array $items = array();

	/**
	 *
	 * Register the class.
	 *
	 * @param string $class_name The class name.
	 *
	 * @return $this
	 */
	abstract public function register( string $class_name ): RegistrableInterface;

	/**
	 * Register by path.
	 *
	 * @param string $pathname The path.
	 * @param string $name_space The namespace.
	 *
	 * @return void
	 */
	public function register_by_path( string $pathname, string $name_space = '' ) {

		$files = new \DirectoryIterator( $pathname );

		foreach ( $files as $file ) {
			if ( $file->isFile() && 'php' === $file->getExtension() ) {
				$class_name = $name_space . '\\' . str_replace( '.php', '', $file->getFilename() );
				if ( class_exists( $class_name ) ) {
					$this->register( $class_name );
				}
			}
		}
	}
}
