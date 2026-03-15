<?php

namespace WPTravelEngine\Blocks\Styles;

class BoxShadow extends Style {

	protected function css_value() {
		$this->css_property = 'box-shadow';

		$value = $this->value;

		$_value = array_map(
			function ( $property ) use ( $value ) {
				if ( $property === 'position' ) {
					return $value[ $property ] !== 'inset' ? '' : 'inset';
				}

				if ( $property === 'color' ) {
					return $value[ $property ] ?? '';
				}

				$value = $value[ $property ] ?? '';

				return ! empty( $value ) ? $value : 0;
			},
			array( 'position', 'xOffset', 'yOffset', 'blur', 'spread', 'color' )
		);

		return implode( ' ', $_value );
	}
}
