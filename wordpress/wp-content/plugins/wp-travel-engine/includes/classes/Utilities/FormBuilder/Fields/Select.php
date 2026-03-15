<?php
/**
 * Select field class.
 *
 * @package WPTravelEngine
 * @since 6.0.0
 */

namespace WPTravelEngine\Utilities\FormBuilder\Fields;

/**
 * Select field class.
 */
class Select extends Base {

	/**
	 * Field type name
	 *
	 * @var string
	 */
	protected string $field_type = 'select';

	/**
	 * @var array
	 */
	protected array $options;

	/**
	 * @var array
	 */
	protected array $option_attributes;

	/**
	 * Initialize field type class.
	 *
	 * @param array $field Field attributes.
	 *
	 * @return Base
	 */
	public function init( array $field ): Base {

		if ( ! empty( $field['options'] ) && ! is_array( $field['options'] ) ) {
			$this->options = json_decode( $field['options'], true );
		} else {
			$this->options = $field['options'] ?? array();
		}
		$this->option_attributes = $field['option_attributes'] ?? array();

		return parent::init( $field );
	}

	/**
	 * Get select options.
	 *
	 * @return string
	 */
	protected function select_options(): string {

		$options = array();
		foreach ( $this->options as $name => $value ) {
			$options[] = sprintf(
				'<option value="%s" %s>%s</option>',
				$name,
				$this->concat_attributes( $this->option_attributes, array( 'value' ) ),
				$value,
			);
		}

		return implode( '', $options );
	}

	/**
	 * Render field.
	 *
	 * @return void
	 */
	public function render( $display = true ): string {

		$output = sprintf(
			'<select %s>%s</select>',
			$this->concat_attributes( $this->field_attributes(), array( 'placeholder', 'value' ) ),
			$this->select_options(),
		);

		if ( $display ) {
			echo $output;
		}

		return $output;
	}

	/**
	 * @param $value
	 *
	 * @return array|string|false
	 */
	public function sanitize( $value ) {
		if ( is_array( $value ) ) {
			$diff = array_diff( $value, array_merge( array_keys( $this->options ), array_values( $this->options ) ) );
			if ( ! empty( $diff ) ) {
				return false;
			}

			return $value;
		}

		return ( $this->options[ $value ] ?? false ) ? $value : false;
	}
}
