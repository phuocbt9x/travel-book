<?php
/**
 * Form Renderer class.
 *
 * @package WPTravelEngine
 * 6.0.0
 */

namespace WPTravelEngine\Utilities\FormBuilder;

use WPTravelEngine\Utilities\FormBuilder\Fields\Base;

/**
 * Class Form Renderer.
 * This class is responsible for rendering form fields.
 */
class Renderer {

	/**
	 * Field types array.
	 *
	 * @var array
	 */
	protected static array $field_types = array();

	/**
	 * @var array|mixed
	 */
	protected $fields;

	/**
	 * Form Renderer constructor.
	 *
	 * @param $fields
	 */
	public function __construct( $fields ) {
		$this->fields = $fields;
		$this->set_field_types();
	}

	/**
	 * Render form fields.
	 *
	 * @return void
	 */
	public function render() {

		foreach ( $this->fields as $field ) {
			if ( $field = $this->parse_form_arguments( $field ) ) {
				$field_type      = $field['type'];
				$field_classname = static::$field_types[ $field_type ]['field_class'];
				if ( class_exists( $field_classname ) ) {
					/** @var Base $field_classname */
					$field_obj      = new $field_classname();
					$field_renderer = $field_obj->init( $field );
					if ( in_array( $field['type'], array( 'hidden', 'heading' ), true ) ) {
						echo $field_renderer->render();
					}
					$this->template( $field, $field_renderer );
				}
			}
		}
	}

	/**
	 * Form field render template.
	 *
	 * @param array  $field Field.
	 * @param object $field_renderer Field renderer.
	 *
	 * @return void
	 */
	protected function template( array $field, object $field_renderer ) {
		$args = array(
			'field'              => $field,
			'renderer'           => $field_renderer,
			'wrapper_classnames' => $field['wrapper_class'] ?? '',
			'label_classnames'   => $field['label_class'] ?? '',
			'field_id'           => $field['id'] ?? '',
			'field_label'        => $field['field_label'] ?? '',
		);

		extract( $args );
		include __DIR__ . '/template.php';
	}

	/**
	 * Initialize form renderer.
	 *
	 * @param array $fields Form fields.
	 * @param array $args Additional arguments.
	 *
	 * @return Renderer
	 */
	public static function init( array $fields, array $args = array() ): Renderer {

		if ( ! empty( $args['single'] ) && true === $args['single'] ) {
			$fields = array( $fields );
		}

		return new self( $fields );
	}

	/**
	 * Parse form arguments.
	 *
	 * @return array|false
	 */
	protected function parse_form_arguments( $field ) {
		if ( ! array_key_exists( ( $field['type'] ?? '__invalid' ), static::$field_types ) ) {
			return false;
		}

		$field['field_label']   = $field['field_label'] ?? '';
		$field['name']          = $field['name'] ?? '';
		$field['id']            = $field['id'] ?? $field['name'];
		$field['label_class']   = $field['label_class'] ?? '';
		$field['class']         = $field['class'] ?? '';
		$field['placeholder']   = $field['placeholder'] ?? '';
		$field['wrapper_class'] = $field['wrapper_class'] ?? '';
		$field['wrapper_class'] = ( 'text_info' === $field['type'] ) ? $field['wrapper_class'] . ' wp-travel-engine-info-field' : $field['wrapper_class'];
		$field['default']       = $field['default'] ?? '';
		$field['attributes']    = $field['attributes'] ?? array();
		$field['remove_wrap']   = $field['remove_wrap'] ?? false;

		if ( ! in_array( $field['validations']['required'] ?? false, array( 'true', true, '1' ) ) ) {
			unset( $field['validations']['required'] );
		} else {
			$field['attributes']['data-parsley-required-message'] = __( 'This value is required', 'wp-travel-engine' );
		}

		if ( empty( $field['attributes']['placeholder'] ) && ! empty( $field['placeholder'] ) ) {
			$field['attributes']['placeholder'] = $field['placeholder'];
		}

		if ( empty( $field['attributes']['rows'] ) && ! empty( $field['rows'] ) ) {
			$field['attributes']['rows'] = $field['rows'];
		}

		if ( empty( $field['attributes']['cols'] ) && ! empty( $field['cols'] ) ) {
			$field['attributes']['cols'] = $field['cols'];
		}

		return $field;
	}

	/**
	 * Include required form field types.
	 *
	 * @return void
	 */
	protected function set_field_types() {
		$field_types = include_once __DIR__ . '/fieldTypes.php';

		if ( is_array( $field_types ) ) {
			static::$field_types = $field_types;
		}
		//
		// foreach ( static::$field_types as $type => $field ) {
		// $file_path_incl = WP_TRAVEL_ENGINE_ABSPATH . 'includes/lib/wte-form-framework/field-types/class-field-' . $type . '.php';
		//
		// if ( file_exists( $file_path_incl ) ) {
		// include_once $file_path_incl;
		// }
		// }
	}
}
