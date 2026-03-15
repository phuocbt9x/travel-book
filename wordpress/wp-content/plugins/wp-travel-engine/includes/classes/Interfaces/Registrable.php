<?php
/**
 * Registrable interface.
 *
 * @package WPTravelEngine/Interfaces
 * @since 6.0.0
 */

namespace WPTravelEngine\Interfaces;

interface Registrable {

	/**
	 * Register the object with WordPress.
	 *
	 * @param string $class_name
	 *
	 * @return $this
	 */
	public function register( string $class_name ): Registrable;
}
