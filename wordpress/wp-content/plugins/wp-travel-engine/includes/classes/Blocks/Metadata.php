<?php

/**
 * Metadata for Blocks.
 *
 * @since 5.8.3
 */

namespace WPTravelEngine\Blocks;

class Metadata {

	public static function supports( array $metadata = array() ): array {

		$metadata['supports'] = static::parse_args(
			$metadata['supports'] ?? array(),
			array(
				'wptravelenginetripblocks' => array(
					'colors'     => array(
						'textColor'  => true,
						'background' => true,
						'link'       => true,
					),
					'typography' => true,
					'border'     => true,
					'spacing'    => true,
					'panels'     => array(
						'color'      => array( 'title' => __( 'Color', 'wp-travel-engine' ) ),
						'border'     => array( 'title' => __( 'Border', 'wp-travel-engine' ) ),
						'dimensions' => array( 'title' => __( 'Dimensions', 'wp-travel-engine' ) ),
						'typography' => array( 'title' => __( 'Typography', 'wp-travel-engine' ) ),
					),
				),
			)
		);

		return $metadata['supports'];
	}

	public static function parse_args( $args, $defaults ): array {

		$args = array_merge( $defaults, $args );

		foreach ( $args as $key => $value ) {
			if ( is_array( $value ) && isset( $defaults[ $key ] ) ) {
				if ( ! is_array( $defaults[ $key ] ) ) {
					continue;
				}
				$args[ $key ] = static::parse_args( $value, $defaults[ $key ] );
			}
		}

		return $args;
	}

	public static function colors( $metadata ): array {
		$global_settings = wp_get_global_settings();
		if ( isset( $global_settings['color']['palette']['theme'] ) ) {
			$theme_color_palette = $global_settings['color']['palette']['theme'];
		}
		$color_palettes = array();
		if ( isset( $theme_color_palette ) && is_array( $theme_color_palette ) ) {
			foreach ( $theme_color_palette as $color_palette => $palettes ) {
				$color_palettes[ $color_palette ] = 'var(--wp--preset--color--' . $palettes['slug'] . ')';
			}
		}
		$defaults = array(
			'textColor'  => array(
				'type'    => 'string',
				'label'   => __( 'Text', 'wp-travel-engine' ),
				'default' => '',
				'control' => array(
					'type'  => 'color',
					'style' => 'color',
				),
			),
			'background' => array(
				'type'    => 'string',
				'label'   => __( 'Background', 'wp-travel-engine' ),
				'default' => '',
				'control' => array(
					'type'  => 'color',
					'style' => 'background',
				),
				'panel'   => 'color',
			),
			'link'       => array(
				'type'      => 'object',
				'default'   => array(
					'initial' => '',
					'hover'   => '',
				),
				'selectors' => '%WRAPPER% a',
				'label'     => __( 'Link', 'wp-travel-engine' ),
				'control'   => array(
					'type'   => 'color',
					'labels' => array(
						'initial' => array(
							'label' => __( 'Link', 'wp-travel-engine' ),
							'style' => 'color',
						),
						'hover'   => array(
							'label' => __( 'Hover', 'wp-travel-engine' ),
							'style' => 'color:hover',
						),
					),
				),
			),
		);

		$supports = $metadata['supports']['wptravelenginetripblocks']['colors'] ?? true;

		if ( ! $supports ) {
			return array();
		}

		if ( is_array( $supports ) ) {
			foreach ( array_keys( $defaults ) as $key ) {
				if ( ! isset( $supports[ $key ] ) || ! $supports[ $key ] ) {
					unset( $defaults[ $key ] );
				}
			}
		}

		return $defaults;
	}

	public static function border(): array {
		return array(
			'border'       => array(
				'type'    => 'object',
				'label'   => __( 'Border', 'wp-travel-engine' ),
				'default' => array(
					'width' => 1,
					'style' => 'none',
					'color' => '',
				),
				'control' => array(
					'type' => 'border',
				),
			),
			'boxShadow'    => array(
				'type'    => 'object',
				'default' => array(
					'enable'           => false,
					'color'            => '',
					'xOffset'          => '',
					'horizontalOffset' => '',
					'verticalOffset'   => '',
					'yOffset'          => '',
					'blur'             => '',
					'spread'           => '',
					'position'         => 'outline',
				),
				'label'   => __( 'Box Shadow', 'wp-travel-engine' ),
				'control' => array(
					'type' => 'box-shadow',
				),
				'panel'   => 'border',
			),
			'borderRadius' => array(
				'type'    => 'object',
				'default' => array(
					'top'    => '',
					'right'  => '',
					'bottom' => '',
					'left'   => '',
				),
				'label'   => __( 'Border Radius', 'wp-travel-engine' ),
				'control' => array(
					'type'  => 'spacing',
					'style' => 'borderRadius',
				),
				'panel'   => 'border',
			),
		);
	}

	public static function spacing( $metadata ): array {
		return array(
			'padding' => array(
				'type'    => 'object',
				'default' => array(
					'top'    => '',
					'right'  => '',
					'bottom' => '',
					'left'   => '',
				),
				'label'   => __( 'Padding', 'wp-travel-engine' ),
				'control' => array(
					'type'  => 'spacing',
					'style' => 'padding',
				),
				'panel'   => 'dimensions',
			),
			'margin'  => array(
				'type'    => 'object',
				'default' => array(
					'top'    => '',
					'right'  => '',
					'bottom' => '',
					'left'   => '',
				),
				'label'   => __( 'Margin', 'wp-travel-engine' ),
				'control' => array(
					'type'  => 'spacing',
					'style' => 'margin',
				),
				'panel'   => 'dimensions',
			),
		);
	}

	public static function typography( $metadata ): array {

		if ( ! isset( $metadata['supports']['wptravelenginetripblocks']['typography'] ) || ! $metadata['supports']['wptravelenginetripblocks']['typography'] ) {
			return array();
		}

		return array(
			'typography' => array(
				'type'    => 'object',
				'label'   => __( 'Typography', 'wp-travel-engine' ),
				'control' => array(
					'type' => 'typography',
				),
				'panel'   => 'typography',
				'default' => array(
					'family'         => 'inherit',
					'size'           => array(
						'desktop' => 'px',
						'tablet'  => 'px',
						'mobile'  => 'px',
					),
					'line-height'    => array(
						'desktop' => '',
						'tablet'  => '',
						'mobile'  => '',
					),
					'letter-spacing' => array(
						'desktop' => 'px',
						'tablet'  => 'px',
						'mobile'  => 'px',
					),
					'weight'         => '400',
					'style'          => 'default',
					'transform'      => 'default',
					'decoration'     => 'default',
				),
			),
		);
	}

	public static function attributes( array $metadata = array() ): array {

		$defaults = array_merge( static::colors( $metadata ), static::border( $metadata ), static::spacing( $metadata ), static::typography( $metadata ), );

		$metadata['attributes'] = static::parse_args(
			$metadata['attributes'] ?? array(),
			$defaults
		);

		$metadata['attributes']['editor'] = array(
			'type'    => 'boolean',
			'default' => false,
			'label'   => __( 'Editor', 'wp-travel-engine' ),
		);

		return $metadata['attributes'] ?? array();
	}

	public static function filter_block_metadata( $metadata ) {

		if ( ! str_contains( $metadata['name'], 'wptravelengine' ) ) {
			return $metadata;
		}

		if ( preg_match( '/(wptravelenginetripblocks|wptravelenginepagesblocks)/', $metadata['name'] ) ) {
			$metadata['supports']   = self::supports( $metadata );
			$metadata['attributes'] = self::attributes( $metadata );
		} else {
			$filters = get_option( 'wte_custom_filters', array() );
			switch ( substr( $metadata['name'], strlen( 'wptravelengine/' ), strlen( $metadata['name'] ) ) ) {
				case 'trips':
				case 'trip-types':
				case 'terms':
				case 'destinations':
				case 'activities':
					$metadata['attributes']['viewAllButtonText'] = array(
						'type'    => 'string',
						'default' => 'View All',
					);
					$metadata['attributes']['viewAllLink']       = array(
						'type'    => 'string',
						'default' => '',
					);

					$metadata['attributes']['layoutFilters']['default']['showViewAll'] = false;
				case 'trip-search':
					if ( isset( $metadata['attributes']['searchFilters']['default'] ) ) {
						if ( defined( 'WTE_FIXED_DEPARTURE_VERSION' ) ) {
							$metadata['attributes']['searchFilters']['default']['date'] = array(
								'label'   => __( 'Date', 'wp-travel-engine' ),
								'default' => __( 'Date', 'wp-travel-engine' ),
								'show'    => true,
								'order'   => 5,
								'icon'    => 'calendar',
							);
						}
						foreach ( $filters as $filter ) {
							$search_filter = array(
								'label'   => $filter['label'],
								'show'    => false,
								'default' => $filter['label'],
							);
							$metadata['attributes']['searchFilters']['default'][ $filter['slug'] ] = $search_filter;
						}
					}
					break;
			}
		}

		return $metadata;
	}
}
