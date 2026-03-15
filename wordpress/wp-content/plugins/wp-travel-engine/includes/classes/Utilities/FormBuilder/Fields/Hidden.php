<?php
/**
 * Hidden field class.
 * This class is replacement for WP_Travel_Engine_Form_Field_Email class.
 *
 * @package WPTravelEngine
 * 6.0.0
 */

namespace WPTravelEngine\Utilities\FormBuilder\Fields;

class Hidden extends Text {

	/**
	 * Field type name
	 *
	 * @var string
	 */
	protected string $field_type = 'hidden';
}
