<?php
/**
 * Datepicker field.
 *
 * @package WPTravelEngine
 * @since 6.0.0
 */

namespace WPTravelEngine\Utilities\FormBuilder\Fields;

/**
 * Datepicker field class.
 * This class is replacement for WP_Travel_Engine_Form_Field_Date class.
 */
class DatePicker extends Text {

	/**
	 * Render datepicker field.
	 *
	 * @param bool $display Display or return.
	 *
	 * @return string
	 */
	public function render( bool $display = true ): string {
		wp_enqueue_script( 'wte-fpickr' );
		wp_enqueue_style( 'wte-fpickr' );

		$this->attributes['autocomplete'] = 'off';
		$this->attributes['data-id']      = $this->field_id;

		$output = parent::render( false );

		$max_today = $this->attributes['data-max-today'] ?? '';

		$output .= sprintf(
			'<script>;(function() {
				window.addEventListener("load",function() {
					var fpArgs = {
						dateFormat: "Y-m-d"
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
			$this->attributes['data-id'],
			$max_today
		);

		if ( $display ) {
			echo $output;
		}

		return $output;
	}
}
