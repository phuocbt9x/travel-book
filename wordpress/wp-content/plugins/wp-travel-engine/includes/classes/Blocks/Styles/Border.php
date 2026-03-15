<?php

namespace WPTravelEngine\Blocks\Styles;

class Border extends Style {

	public function styles(): array {
		$value          = $this->value;
		$selector_value = array();

		if ( is_array( $value ) ) {
			$keys = array(
				'width' => 'border-width',
				'style' => 'border-style',
				'color' => 'border-color',
			);

			foreach ( $keys as $key => $cssProperty ) {
				if ( ! empty( $value[ $key ] ) ) {
					$selector_value[ $cssProperty ] = $key === 'width' ? "{$value[ $key ]}px" : $value[ $key ];
				}
			}
		} else {
			$selector_value[ $this->css_property() ] = $value;
		}

		return $this->styles_by_selectors( $selector_value );
	}
}
