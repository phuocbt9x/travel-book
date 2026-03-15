<?php
/**
 * Admin settings builder.
 *
 * @package WPTravelEngine
 * @since 6.2.0
 */

namespace WPTravelEngine\Builders;

class AdminSettings {

	public function tabs(): array {
		$tab_dir = __DIR__ . '/admin-settings';

		$iterator = new \DirectoryIterator( $tab_dir );

		$tabs = array();
		foreach ( $iterator as $directory ) {

			if ( $directory->isDot() || $directory->isDir() || $directory->getExtension() !== 'php' ) {
				continue;
			}

			$tab_settings = include $directory->getPathname();

			if ( ! is_array( $tab_settings ) || ! isset( $tab_settings['id'] ) ) {
				continue;
			}

			if ( isset( $tab_settings['sub_tabs'] ) ) {
				$sub_tabs = $tab_settings['sub_tabs'];
				if ( is_string( $sub_tabs ) && is_dir( $sub_tabs ) ) {
					$tab_settings['sub_tabs'] = $this->get_sub_tabs( $sub_tabs );
				}
			}

			$tab_settings = apply_filters( 'wptravelengine_settings:tabs:' . $tab_settings['id'], $tab_settings );

			usort(
				$tab_settings['sub_tabs'],
				function ( $a, $b ) use ( $tab_settings ) {
					if ( 'extensions' === $tab_settings['id'] ) {
						return strtolower( $a['title'] ) <=> strtolower( $b['title'] );
					}
					return $a['order'] <=> $b['order'];
				}
			);

			$tabs[] = $tab_settings;
		}

		$tabs = apply_filters( 'wptravelengine_settings_ui_config', $tabs );

		usort(
			$tabs,
			function ( $a, $b ) {
				return $a['order'] - $b['order'];
			}
		);

		return $tabs;
	}

	/**
	 * Get sub tabs.
	 *
	 * @param string $sub_tabs Sub tabs directory.
	 *
	 * @return array
	 */
	public function get_sub_tabs( string $sub_tabs ): array {
		$iterator = new \DirectoryIterator( $sub_tabs );

		$tabs = array();
		foreach ( $iterator as $directory ) {

			if ( $directory->isDot() || ! $directory->isFile() || $directory->getExtension() !== 'php' ) {
				continue;
			}

			$tab_settings = include $directory->getPathname();

			if ( ! is_array( $tab_settings ) || ! isset( $tab_settings['id'] ) ) {
				continue;
			}

			$tab_settings = apply_filters( 'wptravelengine_settings:sub_tabs:' . $tab_settings['id'], $tab_settings );

			$tabs[] = $tab_settings;

		}

		usort(
			$tabs,
			function ( $a, $b ) {
				// check if order is set.
				if ( ! isset( $a['order'] ) || ! isset( $b['order'] ) ) {
					return 100;
				}

				return $a['order'] - $b['order'];
			}
		);

		$tabs = array_values(
			array_filter(
				$tabs,
				function ( $tab ) {
					return ! empty( $tab );
				}
			)
		);

		return $tabs;
	}
}
