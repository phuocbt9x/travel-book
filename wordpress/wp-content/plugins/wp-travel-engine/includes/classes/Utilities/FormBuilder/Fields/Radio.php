<?php
/**
 * Input field class for radio.
 *
 * @since 6.0.0
 * @package WPTravelEngine
 */

namespace WPTravelEngine\Utilities\FormBuilder\Fields;

class Radio extends Checkbox {

	/**
	 * Get select options.
	 *
	 * @return string
	 */
	protected function options(): string {

		$options     = array();
		$index       = 0;
		$field_value = is_scalar( $this->field_value ) ? array( $this->field_value ) : $this->field_value;

		$option_attributes = $this->concat_attributes( $this->option_attributes );
		foreach ( $this->options as $value => $label ) {
			$options[] = sprintf(
				'<div class="wpte-bf-radio-wrap">
							<input type="radio" name="%1$s" value="%2$s" id="%3$s" %4$s %6$s>
							<label for="%3$s">%5$s</label>
						</div>',
				$this->field_name . '[]',
				$value,
				$this->field_id . '_' . $index++,
				$option_attributes,
				$label,
				checked( in_array( $value, $field_value, true ), true, false )
			);
		}

		return implode( '', $options ) . '<div id="error_container-' . $this->field_id . '"></div>';
	}
}
