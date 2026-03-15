<?php
/**
 * Base field class.
 *
 * @package WPTravelEngine
 * 6.0.0
 */

namespace WPTravelEngine\Utilities\FormBuilder\Fields;

class Base {

	/**
	 * Field with attributes
	 *
	 * @var array
	 */
	protected array $field;

	/**
	 * Field type name
	 *
	 * @var string
	 */
	protected string $field_type = '';

	/**
	 * Field id.
	 *
	 * @var string
	 */
	protected string $field_id;

	/**
	 * Field name.
	 *
	 * @var string
	 */
	protected string $field_name;

	/**
	 * Field value.
	 *
	 * @var mixed
	 */
	protected $field_value;

	/**
	 * Field classnames.
	 *
	 * @var string
	 */
	protected string $field_classnames;

	/**
	 * Field attributes.
	 *
	 * @var array|mixed
	 */
	protected $attributes;

	/**
	 * @var array|mixed
	 */
	protected $validation_attributes;

	/**
	 * Initialize field type class.
	 *
	 * @param array $field Field attributes.
	 *
	 * @return Base
	 */
	public function init( array $field ): Base {

		$this->field = $field;

		$this->field_id              = $field['id'] ?? '';
		$this->field_name            = $field['name'] ?? '';
		$this->field_value           = $field['default'] ?? '';
		$this->field_classnames      = $field['class'] ?? '';
		$this->attributes            = $field['attributes'] ?? array();
		$this->validation_attributes = $field['validations'] ?? array();

		return $this;
	}

	/**
	 * Concat attributes and returns a key=value string.
	 *
	 * @param array $attributes
	 * @param array $exclude
	 *
	 * @return string
	 * @since 6.0.0
	 */
	protected function concat_attributes( array $attributes, array $exclude = array() ): string {
		$attr = '';
		foreach ( $attributes as $key => $value ) {
			if ( in_array( $key, $exclude, true ) ) {
				continue;
			}
			$attr .= sprintf( '%1$s="%2$s" ', $key, $value );
		}

		return $attr;
	}

	/**
	 * All Field attributes.
	 *
	 * @return array All field attributes.
	 */
	protected function field_attributes(): array {
		return array_merge(
			$this->attributes,
			$this->validation_attributes,
			array(
				'id'    => $this->field_id,
				'name'  => $this->field_name,
				'type'  => $this->field_type,
				'value' => $this->field_value,
				'class' => $this->field_classnames,
			),
		);
	}

	/**
	 * Before field.
	 *
	 * @return string
	 */
	protected function before_field(): string {
		$before_field = '';
		if ( $this->field['before_field'] ?? false ) {
			$before_field = sprintf( '<span class="%1$s">%2$s</span>', $this->field['before_field_class'] ?? '', $this->field['before_field'] );
		}

		return $before_field;
	}

	/**
	 * After field.
	 *
	 * @return string
	 */
	protected function after_field(): string {
		$after_field = '';
		if ( $this->field['after_field'] ?? false ) {
			$after_field = sprintf( '<span class="%1$s">%2$s</span>', $this->field['after_field_class'] ?? '', $this->field['after_field'] );
		}

		return $after_field;
	}

	/**
	 * Field type render.
	 *
	 * @param boolean $display Display field or return.
	 *
	 * @return string
	 */
	public function render( bool $display = true ): string {

		$output = sprintf(
			'%s<input %s/>%s',
			$this->before_field(),
			$this->concat_attributes( $this->field_attributes() ),
			$this->after_field()
		);

		if ( $display ) {
			echo $output;
		}

		return $output;
	}

	/**
	 * Sanitize field value.
	 *
	 * @param mixed $value Value to be sanitized.
	 *
	 * @return string|array
	 */
	public function sanitize( $value ) {
		if ( ! is_scalar( $value ) ) {
			return array_map( 'sanitize_text_field', (array) $value );
		}

		return sanitize_text_field( $value );
	}

	/**
	 * Check if field is required.
	 *
	 * @return bool
	 * @since 6.0.0
	 */
	public function is_required(): bool {
		return in_array( ( $this->field['validations']['required'] ?? false ), array( 'true', true, '1' ), true );
	}
}
