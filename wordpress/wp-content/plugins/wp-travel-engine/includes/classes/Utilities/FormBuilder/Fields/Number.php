<?php
/**
 *  Number field class.
 *
 * @package WPTravelEngine
 * @since 6.0.0
 */

namespace WPTravelEngine\Utilities\FormBuilder\Fields;

/**
 * Number field class.
 */
class Number extends Text {
	/**
	 * Field type name
	 *
	 * @var string
	 */
	protected string $field_type = 'number';

	/**
	 * Field type render.
	 *
	 * @param string $value Value to be sanitized.
	 *
	 * @return false|float
	 */
	public function sanitize( $value ) {
		return is_numeric( $value ) ? (float) $value : false;
	}
}
