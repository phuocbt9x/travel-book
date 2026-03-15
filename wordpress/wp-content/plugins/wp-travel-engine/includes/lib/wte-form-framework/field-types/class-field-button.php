<?php
/**
 * Button form field class
 *
 * @package WP Travel Engine
 * @since 6.7.6
 */
require_once WP_TRAVEL_ENGINE_ABSPATH . 'includes/lib/wte-form-framework/traits/secure-attributes.php';

class WP_Travel_Engine_Form_Field_Button {

	use Secure_Attributes;

	/**
	 * Field with attributes
	 *
	 * @var array
	 */
	protected $field;

	/**
	 * Field type name
	 *
	 * @var string
	 */
	protected $field_type = 'button';

	/**
	 * Track if assets are enqueued
	 *
	 * @var bool
	 */
	protected static $assets_enqueued = false;

	/**
	 * Initialize field type class.
	 *
	 * @param array $field
	 * @return WP_Travel_Engine_Form_Field_Button
	 */
	public function init( $field ) {
		$this->field = $field;
		return $this;
	}

	/**
	 * Enqueue button field assets.
	 *
	 * @return void
	 */
	protected function enqueue_assets() {
		if ( self::$assets_enqueued ) {
			return;
		}

		$script_path = WP_TRAVEL_ENGINE_ABSPATH . 'dist/global/button-form-field.js';
		$script_url  = plugin_dir_url( WP_TRAVEL_ENGINE_FILE_PATH ) . 'dist/global/button-form-field.js';

		if ( file_exists( $script_path ) ) {
			wp_enqueue_script(
				'wte-button-form-field',
				$script_url,
				array(),
				filemtime( $script_path ),
				true
			);

			wp_localize_script(
				'wte-button-form-field',
				'wteButtonField',
				array(
					'ajaxurl' => admin_url( 'admin-ajax.php' ),
					'i18n'    => array(
						'processing' => __( 'Processing...', 'wp-travel-engine' ),
						'error'      => __( 'An error occurred. Please try again.', 'wp-travel-engine' ),
					),
				)
			);

			self::$assets_enqueued = true;
		}
	}

	/**
	 * Field type render.
	 *
	 * @param boolean $display
	 * @return string|void
	 */
	public function render( $display = true ) {
		// Enqueue assets
		$this->enqueue_assets();

		$attributes  = '';
		$button_type = $this->field['button_type'] ?? 'button';
		$button_text = $this->field['button_text'] ?? __( 'Submit', 'wp-travel-engine' );
		$action      = ( $this->field['allow_nopriv'] ?? false ) ? 'wte_public_button_action' : 'wte_admin_button_action';

		$this->field['attributes'] = array_merge(
			( $this->field['attributes'] ?? array() ),
			array(
				'data-action' => $action,
				'data-nonce'  => wp_create_nonce( $action . '_nonce' ),
			)
		);

		// Render attributes with validation and escaping.
		$attributes = $this->render_safe_attributes( $this->field['attributes'] );

		// Render label if field_label is provided
		$label = '';
		if ( ! empty( $this->field['field_label'] ) ) {
			$label_class = $this->field['label_class'] ?? '';

			$label = sprintf(
				'<label class="%1$s" for="%2$s">%3$s</label>',
				esc_attr( $label_class ),
				esc_attr( $this->field['id'] ),
				esc_html( $this->field['field_label'] )
			);
		}

		$before_field = '';
		if ( isset( $this->field['before_field'] ) ) {
			$before_field_class = $this->field['before_field_class'] ?? '';
			$before_field       = sprintf( '<span class="%1$s">%2$s</span>', esc_attr( $before_field_class ), esc_html( $this->field['before_field'] ) );
		}

		$after_field = '';
		if ( isset( $this->field['after_field'] ) ) {
			$after_field_class = $this->field['after_field_class'] ?? '';
			$after_field       = sprintf( '<span class="%1$s">%2$s</span>', esc_attr( $after_field_class ), esc_html( $this->field['after_field'] ) );
		}

		$button_element = sprintf(
			'<button type="%1$s" id="%2$s" name="%3$s" class="%4$s wte-button-field" %5$s>%6$s</button>',
			esc_attr( $button_type ),
			esc_attr( $this->field['id'] ),
			esc_attr( $this->field['name'] ),
			esc_attr( $this->field['class'] ),
			$attributes,
			esc_html( $button_text )
		);

		$output = $label . $before_field . $button_element . $after_field;

		// Allow filtering of output
		$output = apply_filters( 'wte_form_field_button_output', $output, $this->field );

		if ( ! $display ) {
			return $output;
		}

		echo $output;
	}
}
