<?php
/**
 * Class AdminMenuPage.
 *
 * @package WPTravelEngine\Abstracts
 * @since 6.0.0
 */

namespace WPTravelEngine\Abstracts;

use WPTravelEngine\Traits\Factory;

/**
 * Class AdminMenuPage
 * This class represents an admin menu page in the WP Travel Engine plugin.
 *
 * @since 6.0.0
 */
abstract class AdminMenuPage {
	use Factory;

	/**
	 * The parent slug of the admin menu page.
	 *
	 * @var string|null
	 */
	protected ?string $parent_slug = null;

	/**
	 * The page title of the admin menu page.
	 *
	 * @var string
	 */
	protected string $page_title;

	/**
	 * The menu title of the admin menu page.
	 *
	 * @var string
	 */
	protected string $menu_title;

	/**
	 * The capability of the admin menu page.
	 *
	 * @var string
	 */
	protected string $capability;

	/**
	 * The position of the admin menu page.
	 *
	 * @var int
	 */
	protected int $position;

	/**
	 * The callback of the admin menu page.
	 *
	 * @return void
	 */
	abstract public static function callback();

	/**
	 * Get the slug of the admin menu page.
	 *
	 * @return string
	 */
	public function slug(): string {
		return static::SLUG;
	}

	/**
	 * Get the parent slug of the admin menu page.
	 *
	 * @return string
	 */
	public function parent_slug(): string {
		return $this->parent_slug;
	}

	/**
	 * Get the page title of the admin menu page.
	 *
	 * @return string
	 */
	public function page_title(): string {
		return $this->page_title;
	}

	/**
	 * Get the menu title of the admin menu page.
	 *
	 * @return string
	 */
	public function menu_title(): string {
		return $this->menu_title;
	}

	/**
	 * Get the capability of the admin menu page.
	 *
	 * @return string
	 */
	public function capability(): string {
		return $this->capability;
	}

	/**
	 * Get the position of the admin menu page.
	 *
	 * @return int
	 */
	public function position(): int {
		return $this->position;
	}

	/**
	 * Should be added to the admin menu.
	 *
	 * @return bool
	 */
	public function add_to_menu(): bool {
		return true;
	}

	/**
	 * Get the arguments of the admin menu page.
	 *
	 * @return array
	 */
	public function args(): array {
		return array(
			'parent_slug' => $this->parent_slug,
			'page_title'  => $this->page_title,
			'menu_title'  => $this->menu_title,
			'capability'  => $this->capability,
			'callback'    => array( static::class, 'callback' ),
			'position'    => $this->position,
		);
	}

	/**
	 * Check if the admin menu page is a submenu.
	 *
	 * @return bool
	 */
	public function is_submenu(): bool {
		return ! is_null( $this->parent_slug );
	}

	/**
	 * Get the icon for the admin menu page.
	 *
	 * @return string The icon for the admin menu page.
	 */
	public function get_icon(): string {
		return '';
	}
}
