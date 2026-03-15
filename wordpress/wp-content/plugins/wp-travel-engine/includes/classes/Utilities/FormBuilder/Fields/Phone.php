<?php
/**
 * Phone field class.
 *
 * @package WPTravelEngine
 * @since 6.0.0
 */

namespace WPTravelEngine\Utilities\FormBuilder\Fields;

/**
 * Phone field class.
 */
class Phone extends Text {
	/**
	 * Field type name
	 *
	 * @var string
	 */
	protected string $field_type = 'tel';
}
