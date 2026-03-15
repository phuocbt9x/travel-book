<?php

namespace WPTravelEngine\Blocks\Styles;

class Typography extends Style {

	public function styles(): array {
		$value = $this->value;

		$selector_value = array(
			'desktop' => array(),
			'tablet'  => array(),
			'mobile'  => array(),
		);

		$mapping = array(
			'family'         => 'font-family',
			'size'           => 'font-size',
			'line-height'    => 'line-height',
			'letter-spacing' => 'letter-spacing',
			'word-spacing'   => 'word-spacing',
			'weight'         => 'font-weight',
			'style'          => 'font-style',
			'transform'      => 'text-transform',
			'decoration'     => 'text-decoration',
		);

		foreach ( $value as $font_property => $property_value ) {
			if ( isset( $mapping[ $font_property ] ) && ! empty( $property_value ) ) {
				if ( is_scalar( $property_value ) && 'default' !== $property_value ) {
					$selector_value['desktop'][ $mapping[ $font_property ] ] = $property_value;
				} elseif ( is_array( $property_value ) ) {
					foreach ( static::SCREENS as $screen ) {
						if ( ! empty( $property_value[ $screen ] ) ) {
							$selector_value[ $screen ][ $mapping[ $font_property ] ] = $property_value[ $screen ];
						}
					}
				}
			}
		}

		return $this->styles_by_selectors( $selector_value );
	}
}
