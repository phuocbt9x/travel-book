<?php

namespace WPTravelEngine\Builders\FormFields;

/**
 * Checkbox field class.
 *
 * @since 6.3.0
 */
class Checkbox extends \WP_Travel_Engine_Form_Field_Checkbox {
	/**
	 * field object.
	 *
	 * @var array
	 */
	private array $field;

	/**
	 * Initialize checkbox class
	 *
	 * @param array $field
	 *
	 * @return Checkbox
	 */
	function init( $field ): Checkbox {
		$this->field = $field;

		return $this;
	}

	/**
	 * Checkbox field
	 *
	 * @param boolean $display
	 *
	 * @return void
	 */
	function render( $display = true ) {
		$validations = '';
		if ( isset( $this->field['validations'] ) ) :
			foreach ( $this->field['validations'] as $key => $attr ) :
				$validations .= sprintf( '%s="%s"', $key, $attr );
			endforeach;
		endif;

		$output = '';

		if ( ! empty( $this->field['options'] ) ) {
			$index       = 0;
			$options_arr = $this->field['options'];
			if ( ! is_array( $this->field['options'] ) ) {
				$options_arr = json_decode( $this->field['options'], true );
			}
			foreach ( $options_arr as $key => $value ) {
				// Option Attributes.
				$option_attributes = '';
				if ( isset( $this->field['option_attributes'] ) && count( $this->field['option_attributes'] ) > 0 ) {
					foreach ( $this->field['option_attributes'] as $key1 => $attr ) {
						if ( ! is_array( $attr ) ) {
							$option_attributes .= sprintf( '%s="%s"', $key1, $attr );
						} else {
							foreach ( $attr as $att ) {
								$option_attributes .= sprintf( '%s="%s"', $key1, $att );
							}
						}
					}
				}
				if ( is_array( $this->field['default'] ) && count( $this->field['default'] ) > 0 ) {
					$checked = ( in_array( $key, $this->field['default'] ) ) ? 'checked' : '';
				} else {
					$checked = ( $key === $this->field['default'] ) ? 'checked' : '';
				}
				$error_container_id = sprintf( 'error_container-%s', $this->field['id'] );
				if ( count( $options_arr ) > 1 ) {
					if ( ! empty( $this->field['validations']['required'] ) ) {
						$validations .= sprintf(
							' data-parsley-multiple="%s" data-parsley-mincheck="1" data-parsley-required="true" data-parsley-errors-container="#%s"',
							$this->field['id'],
							$error_container_id
						);
					}
					$output .= sprintf(
						'<div class="wpte-bf-checkbox-wrap wpte-checkbox-wrap">
						<input type="checkbox" name="%s[]" value="%s" id="%s" %s %s %s>
						<label for="%s">
							%s
						</label>
					</div>',
						$this->field['name'],
						$key,
						$this->field['id'] . '_' . $index,
						$option_attributes,
						$checked,
						$validations,
						$this->field['id'] . '_' . $index,
						$value
					);
				} else {
					$output .= sprintf(
						'<div class="wpte-bf-checkbox-wrap wpte-checkbox-wrap">
							<input type="checkbox" name="%s[]" value="%s" id="%s" %s %s %s>
							<label for="%s">
								%s
							</label>
						</div>',
						$this->field['name'],
						$key,
						$this->field['id'],
						$option_attributes,
						$checked,
						$validations,
						$this->field['id'],
						$value
					);
				}

				++$index;
			}

			$output .= sprintf( '<div id="%s"></div>', $error_container_id );
		} else {
			$output .= sprintf(
				'<div class="wpte-checkbox-wrap">
				<input type="checkbox" name="%s" value="%s" id="%s" %s %s %s>
			</div>',
				$this->field['name'],
				'1',
				$this->field['id'],
				'',
				$this->field['default'] === '1' ? 'checked' : '',
				$validations,
				$this->field['id'],
				''
			);
		}

		if ( ! $display ) {
			return $output;
		}

		echo $output;
	}
}
