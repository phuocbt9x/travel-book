<?php

/**
 * Datepicker field.
 *
 * @package WP_Travel_Engine
 */
class WP_Travel_Engine_Form_Field_Date extends WP_Travel_Engine_Form_Field_Text {
	protected $field;
	protected $field_type = 'text';

	function init( $field ) {
		$this->field                               = $field;
		$this->field['attributes']['autocomplete'] = 'off';

		if ( ! isset( $this->field['attributes']['data-id'] ) ) {
			$this->field['attributes']['data-id'] = $field['id'];
		}

		return $this;
	}

	function render( $display = true ) {
		$output = parent::render( false );
		wp_enqueue_script( 'wte-fpickr' );
		wp_enqueue_style( 'wte-fpickr' );

		// Localization for flatpickr.
		$locale    = explode( '_', get_locale() )[0] ?? 'en';
		$max_today = isset( $this->field['attributes'] ) && isset( $this->field['attributes']['data-max-today'] ) ? $this->field['attributes']['data-max-today'] : '';
		$output   .= sprintf(
			'<script>;(function() {
				window.addEventListener("load",function() {
					var fpArgs = {
						dateFormat: "Y-m-d",
						locale: "%3$s"
					}
					if("%2$s" == "false") {
						fpArgs["minDate"] = new Date()
					}
					if("%2$s" == "true") {
						fpArgs["maxDate"] = new Date()
					}
					window.flatpickr && window.flatpickr(document.querySelector("[data-id=\'%1$s\']"), fpArgs)
				})
			})();</script>',
			$this->field['attributes']['data-id'],
			$max_today,
			$locale
		);

		// $output .= '<script>';
		// $output .= 'jQuery(function($){ ';
		// $output .= '$("#' . $this->field['id'] . '").datepicker({';
		// $output .= "dateFormat: 'yy-mm-dd',";
		// if ( '' !== $max_today && true == $max_today ) {
		// $output .= 'maxDate: new Date(),';
		// } elseif ( '' !== $max_today && false == $max_today ) {
		// $output .= 'minDate: new Date(),';
		// }

		// $output .= '});';
		// $output .= '} );';
		// $output .= '</script>';

		if ( ! $display ) {
			return $output;
		}

		echo $output;
	}
}
