<?php
namespace WPTravelEngine\Builders\FormFields;

/**
 * Text Area Field.
 *
 * @since 6.3.0
 */
class TextAreaField extends \WP_Travel_Engine_Form_Field_Textarea {
	/**
	 * Render form template
	 *
	 * @access public
	 */
	public function render( $display = true ) {
		$validations = '';
		if ( isset( $this->field['validations'] ) ) {
			foreach ( $this->field['validations'] as $key => $attr ) {
				$validations .= sprintf( 'data-parsley-%s="%s"', $key, $attr );
			}
		}

		$attributes = '';
		if ( isset( $this->field['attributes'] ) ) {
			foreach ( $this->field['attributes'] as $attribute => $attribute_val ) {
				$attributes .= sprintf( ' %s="%s" ', $attribute, $attribute_val );
			}
		}

		$output  = sprintf( '<textarea id="%s" name="%s" %s %s class="wpte-checkout__input">', $this->field['id'], $this->field['name'], $validations, $attributes );
		$output .= $this->field['default'];
		$output .= sprintf( '</textarea>' );

		if ( ! $display ) {
			return $output;
		}

		echo $output;
	}
}
