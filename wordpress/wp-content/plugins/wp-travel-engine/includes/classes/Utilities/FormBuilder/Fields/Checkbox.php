<?php
/**
 * Input field class for checkbox.
 *
 * @since 2.2.6
 * @since 6.0.0 Class is updated with this class.
 * @package WP Travel Engine
 */

namespace WPTravelEngine\Utilities\FormBuilder\Fields;

class Checkbox extends Select {

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
		if ( empty( $this->options ) ) {
			$checked            = checked( $this->field_value, '1', false );
			$option_attributes .= ' data-parsley-multiple="checkbox" data-parsley-mincheck="1" data-parsley-required ';
			$attributes         = array( $this->field_name, '1', $this->field_id, $checked, $option_attributes );

			return sprintf(
				'<div class="wpte-checkbox-wrap"><input type="checkbox" name="%1$s" value="%2$s" id="%3$s" %4$s %5$s><label for="%3$s"></label></div>',
				...$attributes
			);
		}
		foreach ( $this->options as $value => $label ) {
			$options[] = sprintf(
				'<div class="wpte-bf-checkbox-wrap wpte-checkbox-wrap">
							<input type="checkbox" name="%1$s" value="%2$s" id="%3$s" %4$s %6$s>
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

	/**
	 * Checkbox field
	 *
	 * @param boolean $display
	 *
	 * @return string
	 */
	public function render( $display = true ): string {

		$output = $this->options();

		if ( $display ) {
			echo $output;
		}

		return $output;
	}
}
