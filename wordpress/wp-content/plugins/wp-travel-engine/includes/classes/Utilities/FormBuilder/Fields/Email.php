<?php
/**
 * Email field class.
 * This class is replacement for WP_Travel_Engine_Form_Field_Email class.
 *
 * @package WPTravelEngine
 * 6.0.0
 */

namespace WPTravelEngine\Utilities\FormBuilder\Fields;

class Email extends Text {

	/**
	 * Field type name
	 *
	 * @var string
	 */
	protected string $field_type = 'email';

	/**
	 * Field type render.
	 *
	 * @param string $value Value to be sanitized.
	 *
	 * @return string|false
	 */
	public function sanitize( $value ) {
		return is_email( $value ) ? sanitize_email( $value ) : false;
	}
}
