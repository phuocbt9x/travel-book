<?php
/**
 * Ajax Request Registry.
 *
 * @package WPTravelEngine/Registers
 * @since 6.0.0
 */

namespace WPTravelEngine\Registers;

use WPTravelEngine\Abstracts\AjaxController;
use WPTravelEngine\Abstracts\Registrable;

/**
 * Ajax Request Registry.
 */
class AjaxRequestRegistry extends Registrable {

	/**
	 * Register by path.
	 *
	 * @param string|null $pathname The path.
	 * @param string|null $name_space The namespace.
	 *
	 * @return void
	 */
	public function register_by_path( $pathname = null, $name_space = null ) {
		parent::register_by_path(
			$pathname ?? WP_TRAVEL_ENGINE_BASE_PATH . '/classes/Core/Controllers/Ajax',
			$name_space ?? 'WPTravelEngine\Core\Controllers\Ajax'
		);
	}

	/**
	 * Register the admin pages.
	 *
	 * @param string $class_name The class.
	 *
	 * @return \WPTravelEngine\Interfaces\Registrable
	 */
	public function register( string $class_name ): \WPTravelEngine\Interfaces\Registrable {
		if ( is_subclass_of( $class_name, AjaxController::class ) ) {
			$actions = is_array( $class_name::ACTION ) ? $class_name::ACTION : array( $class_name::ACTION );
			foreach ( $actions as $action ) {
				$this->items[ $action ] = $class_name;
			}
			new $class_name();
		}

		return $this;
	}
}
