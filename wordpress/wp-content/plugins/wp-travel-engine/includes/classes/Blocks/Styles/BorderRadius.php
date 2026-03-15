<?php

namespace WPTravelEngine\Blocks\Styles;

class BorderRadius extends Spacing {


	public function styles(): array {
		$value = $this->value;

		$selector_value = array();

		$mapping = array(
			'top'    => 'border-top-left-radius',
			'right'  => 'border-top-right-radius',
			'bottom' => 'border-bottom-right-radius',
			'left'   => 'border-bottom-left-radius',
		);

		$unit = $value['unit'] ?? 'px';
		unset( $value['unit'] );
		foreach ( $value as $border_radius_property => $border_radius_value ) {
			if ( isset( $mapping[ $border_radius_property ] ) && ! empty( $border_radius_value ) ) {
				$selector_value[ $mapping[ $border_radius_property ] ] = $border_radius_value;
			}
		}

		return $this->styles_by_selectors( $selector_value );
	}
}
