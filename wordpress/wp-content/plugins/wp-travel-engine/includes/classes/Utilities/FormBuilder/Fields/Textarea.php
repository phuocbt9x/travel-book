<?php
/**
 * Textarea field render.
 *
 * @package WPTravelEngine
 * @since 6.0.0
 */

namespace WPTravelEngine\Utilities\FormBuilder\Fields;

/**
 * Textarea field class.
 */
class Textarea extends Base {

	/**
	 * Field type name
	 *
	 * @var string
	 */
	protected string $field_type = 'textarea';

	/**
	 * Render form template
	 *
	 * @param bool $display Display or return.
	 *
	 * @return string
	 */
	public function render( bool $display = true ): string {
		$validations = array();
		if ( isset( $this->field['validations'] ) ) {
			foreach ( $this->field['validations'] as $key => $attr ) {
				$validations[ "data-parsley-{$key}" ] = $attr;
			}
		}

		$this->validation_attributes = $validations;

		$output = sprintf(
			'<textarea %s>%s</textarea>',
			$this->concat_attributes( $this->field_attributes(), array( 'value', 'type' ) ),
			$this->field_value
		);

		if ( $display ) {
			echo $output;
		}

		return $output;
	}
}
