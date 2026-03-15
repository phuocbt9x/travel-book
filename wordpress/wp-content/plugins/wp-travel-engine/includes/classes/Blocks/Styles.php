<?php
/**
 * Styles for blocks.
 *
 * @since 5.8.3
 */

namespace WPTravelEngine\Blocks;

class Styles {

	const SCREENS = array(
		'desktop' => 'desktop',
		'tablet'  => '@media(max-width: 1024px)',
		'mobile'  => '@media(max-width: 767px)',
	);

	protected array $styles     = array();
	protected array $classnames = array();
	protected ?\WP_Block_Type $block;

	protected string $classname = 'default';

	public function __construct() {
		$this->block = \WP_Block_Type_Registry::get_instance()->get_registered( \WP_Block_Supports::$block_to_render['blockName'] );
	}

	public function map_styles( $styles ): array {
		$result = array_map(
			function ( $style, $value ) {
				$style = is_string( $style ) ? $style : '';
				$value = is_string( $value ) ? $value : '';

				return "{$style}:{$value};";
			},
			array_keys( $styles ),
			$styles
		);

		return $result;
	}

	public function print( $block_id ) {

		$selectors_values = $this->styles;

		$css = array(
			'desktop' => array(),
			'tablet'  => array(),
			'mobile'  => array(),
		);

		foreach ( $selectors_values as $selector => $styles ) {

			foreach ( $styles as $style => $value ) {
				if ( ! is_string( $style ) ) {
					continue;
				}

				if ( is_scalar( $value ) ) {
					$css['desktop'][ $selector ]  = $css['desktop'][ $selector ] ?? '';
					$css['desktop'][ $selector ] .= "{$style}:{$value};";
				} elseif ( isset( static::SCREENS[ $style ] ) ) {
					$css[ $style ][ $selector ]  = $css[ $style ][ $selector ] ?? '';
					$css[ $style ][ $selector ] .= implode(
						'',
						array_map(
							function ( $style, $value ) {
								return "{$style}:{$value};";
							},
							array_keys( $value ),
							$value
						)
					);
				}
			}
		}
		$output_css = '';
		foreach ( $css as $screen => $_styles ) {

			if ( 'desktop' === $screen ) {
				$output_css .= implode(
					'',
					array_map(
						function ( $style, $value ) {
							if ( empty( $value ) ) {
								return '';
							}

							return "{$style}{{$value}}";
						},
						array_keys( $_styles ),
						$_styles
					),
				);
				continue;
			}

			$output_css .= static::SCREENS[ $screen ] . '{';
			$output_css .= implode(
				'',
				array_map(
					function ( $style, $value ) {
						if ( empty( $value ) ) {
							return '';
						}

						return "{$style}{{$value}}";
					},
					array_keys( $_styles ),
					$_styles
				),
			);
			$output_css .= '}';
		}

		printf(
			'<style id="%s">%s</style>',
			esc_attr( $block_id ),
			strip_tags( str_replace( '%WRAPPER%', ".{$block_id}", $output_css ) )
		);
	}

	/**
	 *
	 * @return $this
	 */
	public function parse( $attributes = array() ): Styles {

		foreach ( $attributes as $key => $value ) {
			if ( isset( $this->block->attributes[ $key ] ) ) {
				$this->parse_attribute( $key, $value, $this->block->attributes[ $key ] );
			}
		}

		return $this;
	}

	protected function parse_attribute( $key, $value, $settings ): void {

		$control_type = $settings['control']['type'] ?? false;

		if ( ! ( $settings['control']['type'] ?? false ) ) {
			return;
		}

		$instance = null;
		switch ( $control_type ) {
			case 'color':
				$instance = Styles\Color::parse( $key, $value, $settings );
				break;
			case 'border':
				$instance = Styles\Border::parse( $key, $value, $settings );
				break;
			case 'spacing':
				$instance = Styles\Spacing::parse( $key, $value, $settings );
				break;
			case 'borderRadius':
			case 'border-radius':
				$instance = Styles\BorderRadius::parse( $key, $value, $settings );
				break;
			case 'boxShadow':
			case 'box-shadow':
				$instance = Styles\BoxShadow::parse( $key, $value, $settings );
				break;
			case 'typography':
				$instance = Styles\Typography::parse( $key, $value, $settings );
				break;
			default:
				if ( $settings['control']['style'] ?? false ) {
					$instance = Styles\Style::parse( $key, $value, $settings );
				} else {
					return;
				}
				break;
		}

		$this->styles = array_merge_recursive( $this->styles, $instance->styles() );
	}
}
