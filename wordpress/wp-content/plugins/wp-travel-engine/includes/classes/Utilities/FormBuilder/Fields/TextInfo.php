<?php

/**
 * Text Info field class.
 *
 * @package WPTravelEngine
 * @since 6.0.0
 */

namespace WPTravelEngine\Utilities\FormBuilder\Fields;

/**
 * Text Info field class.
 */
class TextInfo extends Base {

	/**
	 * Render field layout.
	 *
	 * @param boolean $display
	 *
	 * @return string
	 */
	public function render( bool $display = true ): string {

		$output = '%s%s';

		if ( ! $this->field['remove_wrap'] ) :
			$output = '<div class="wp-travel-engine-info-wrap">%s%s</div>';
		endif;

		$output = sprintf(
			$output,
			$this->before_field(),
			sprintf( '<span class="wp-travel-engine-info" id="%s">%s</span>', $this->field_id, $this->field_value )
		);

		if ( $display ) {
			echo $output;
		}

		return $output;
	}
}
