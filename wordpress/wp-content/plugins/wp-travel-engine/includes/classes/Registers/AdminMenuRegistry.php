<?php
/**
 * Shortcodes Helpers.
 *
 * @package WPTravelEngine/Helpers
 * @since 6.0.0
 */

namespace WPTravelEngine\Registers;

use WPTravelEngine\Abstracts\AdminMenuPage;
use WPTravelEngine\Abstracts\Registrable;

/**
 * Admin Menu Registry.
 */
class AdminMenuRegistry extends Registrable {

	/**
	 * Register an admin page.
	 *
	 * @param string $class_name The class representing the admin page.
	 *
	 * @return void
	 * @throws \InvalidArgumentException If the class is not an AdminMenuPage.
	 */
	public function register( string $class_name ): void {
		if ( ! is_subclass_of( $class_name, AdminMenuPage::class ) ) {
			throw new \InvalidArgumentException( "Class '$class_name' must extend AdminMenuPage" );
		}

		$instance = new $class_name();
		if ( $instance->add_to_menu() ) {
			if ( $instance->is_submenu() ) {
				$this->add_submenu( $instance );
			} else {
				$this->add_menu( $instance );
			}
		}

		$this->items[ $class_name::SLUG ] = $class_name;
	}

	/**
	 * Add a top-level menu.
	 *
	 * @param AdminMenuPage $menu The menu object.
	 *
	 * @return void
	 * @throws \InvalidArgumentException If required arguments are missing.
	 */
	protected function add_menu( AdminMenuPage $menu ): void {
		$args = $menu->args();

		$this->check_missing_args( $args );

		\add_menu_page(
			$args['page_title'],
			$args['menu_title'],
			$args['capability'],
			$menu::SLUG,
			$args['callback'],
			$menu->get_icon(),
			$args['position'] ?? null
		);
	}

	/**
	 * Check for missing arguments.
	 *
	 * @param array $args The arguments to check.
	 *
	 * @return void
	 * @throws \InvalidArgumentException If required arguments are missing.
	 */
	protected function check_missing_args( array $args ) {
		$required_args = array( 'page_title', 'menu_title', 'capability', 'callback' );
		$missing_args  = array_diff( $required_args, array_keys( $args ) );

		if ( ! empty( $missing_args ) ) {
			throw new \InvalidArgumentException( 'Missing required arguments: ' . implode( ', ', $missing_args ) );
		}
	}

	/**
	 * Add a submenu.
	 *
	 * @param AdminMenuPage $menu The submenu object.
	 *
	 * @return void
	 * @throws \InvalidArgumentException If required arguments are missing.
	 */
	protected function add_submenu( AdminMenuPage $menu ): void {
		$args = $menu->args();

		$this->check_missing_args( $args );

		\add_submenu_page(
			$args['parent_slug'],
			$args['page_title'],
			$args['menu_title'],
			$args['capability'],
			$menu::SLUG,
			$args['callback'],
			$args['position'] ?? null
		);
	}
}
