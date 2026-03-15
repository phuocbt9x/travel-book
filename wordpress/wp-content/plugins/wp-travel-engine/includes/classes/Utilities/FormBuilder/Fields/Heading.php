<?php
/**
 * Heading form field class.
 *
 * @package WPTravelEngine
 * @since 6.0.0
 */

namespace WPTravelEngine\Utilities\FormBuilder\Fields;

class Heading extends Base {

	/**
	 * Field type name
	 *
	 * @var string
	 */
	protected string $field_type = 'heading';

	/**
	 * Field type render.
	 *
	 * @param boolean $display
	 *
	 * @return string
	 */
	public function render( bool $display = true ): string {

		$output = sprintf(
			'%1$s<%2$s class="%3$s">%4$s</%2$s>%5$s',
			$this->before_field(),
			$this->field['tag'] ?? 'h2',
			$this->field_classnames,
			$this->field['title'] ?? '',
			$this->after_field()
		);

		if ( $display ) {
			echo $output;
		}

		return $output;
	}
}
