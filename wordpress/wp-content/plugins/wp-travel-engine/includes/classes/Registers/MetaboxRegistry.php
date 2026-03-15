<?php
/**
 * Metabox Registry.
 *
 * @package WPTravelEngine/Registers
 * @since 6.0.0
 */

namespace WPTravelEngine\Registers;

use WPTravelEngine\Abstracts\Registrable;

/**
 * Metabox Registry.
 * Class MetaboxRegistry
 */
class MetaboxRegistry extends Registrable {

	/**
	 * Register the admin pages.
	 *
	 * @param string $class_name The class.
	 *
	 * @return void
	 */
	public function register( string $class_name ): void {
		$this->items[ $class_name::ID ] = $class_name;

		$instance = new $class_name();

		add_meta_box(
			$instance::ID,
			$instance->get_title(),
			array( $instance, 'callback' ),
			$instance::SCREEN,
			$instance::CONTEXT,
			$instance::PRIORITY
		);
	}
}
