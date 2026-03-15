<?php

namespace WPTravelEngine\Blocks\Styles;

class Spacing extends Style {


	public function styles(): array {
		$value = $this->value;

		$unit = $value['unit'] ?? 'px';
		unset( $value['unit'] );

		$selector_value = array();
		foreach ( $value as $key => $val ) {
			if ( empty( $val ) ) {
				continue;
			}
			$selector_value[ "{$this->css_property()}-{$key}" ] = $val;
		}

		return $this->styles_by_selectors( $selector_value );
	}

	public static function parse( ...$args ): Style {
		if ( isset( $args[2]['control']['style'] ) && ( $args[2]['control']['style'] === 'borderRadius' ) ) {
			return new BorderRadius( ...$args );
		}

		return new self( ...$args );
	}
}
