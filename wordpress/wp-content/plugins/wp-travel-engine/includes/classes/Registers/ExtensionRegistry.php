<?php
/**
 * Extension Registry.
 *
 * @package WPTravelEngine/Registers
 * @since 6.0.0
 */

namespace WPTravelEngine\Registers;

use WPTravelEngine\Abstracts\AjaxController;
use WPTravelEngine\Abstracts\Registrable;
use WPTravelEngine\Traits\Singleton;

/**
 * Ajax Request Registry.
 */
class ExtensionRegistry extends Registrable {

	use Singleton;

	/**
	 * Register the admin pages.
	 *
	 * @param string $class_name The class.
	 *
	 * @return \WPTravelEngine\Interfaces\Registrable
	 */
	public function register( string $class_name ): \WPTravelEngine\Interfaces\Registrable {

		$this->items[ $class_name::ID ] = new $class_name();

		return $this;
	}

	/**
	 * Get the items.
	 *
	 * @return array
	 */
	public function items(): array {
		return $this->items;
	}
}
