<?php

namespace WPTravelEngine\Blocks\Styles;

class Color extends Style {

	/**
	 *
	 * Expects three parameters
	 * 1. $key - string
	 * 2. $value - mixed
	 * 3. $settings - array
	 *
	 * @param mixed ...$args
	 *
	 * @return Style
	 */
	public static function parse( ...$args ): Style {
		if ( is_array( $args[1] ) ) {
			return new Colors( ...$args );
		}

		return new self( ...$args );
	}
}
