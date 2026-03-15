<?php
/**
 * Trips List field class
 *
 * @package WP Travel Engine
 * @since 6.7.0
 */
class WP_Travel_Engine_Form_Field_Trips_Select extends WP_Travel_Engine_Form_Field_Select {

	/**
	 * Field type name
	 *
	 * @var string
	 */
	protected $field_type = 'trips_select';

	/**
	 * Initialize class
	 *
	 * @param mixed $field
	 * @return $this
	 */
	function init( $field ) {

		$wte           = \wte_functions();
		$trips_options = wp_travel_engine_get_trips_array();

		// Put default and Other at the top
		$trips_options = array(
			''      => __( 'Choose a Trip', 'wp-travel-engine' ),
			'other' => __( 'Other', 'wp-travel-engine' ),
		) + $trips_options;

		$this->field = $field;

		if ( is_array( $this->field ) ) {
			$this->field['options'] = $trips_options;
		} elseif ( is_object( $this->field ) ) {
			$this->field->options = $trips_options;
		}

		return $this;
	}

	/**
	 * Render template for trips list with conditional custom input
	 *
	 * @param boolean $display
	 * @return string|void
	 */
	function render( $display = true ) {

		// Capture parent select field output
		ob_start();
		parent::render( true );
		$output = ob_get_clean();

		// Add JavaScript to toggle existing custom_trip input field
		$output .= sprintf(
			'<script>
			(function($) {
				$(document).ready(function() {
					var tripSelect = $("#%s");
					var customTripInput = $("#order_trip_custom_trip, input[name=\'order_trip[custom_trip]\']");
					var customTripWrapper = customTripInput.closest(".wpte-field");
					var tripCodeInput = $("#order_trip_trip_code, input[name=\'order_trip[trip_code]\']");
					var tripCodeWrapper = tripCodeInput.closest(".wpte-field");

					// If custom trip already has a value (edit mode), force select to Other
					if ($.trim(customTripInput.val()).length > 0) {
						tripSelect.val("other");
					}

					// Toggle custom trip field based on selection
					tripSelect.on("change", function() {
						if ($(this).val() === "other") {
							customTripWrapper.show();
							customTripInput.prop("disabled", false);
							tripCodeWrapper.show();
							tripCodeInput.prop("disabled", true);
						} else {
							customTripWrapper.hide();
							customTripInput.prop("disabled", true);
							customTripInput.val("");
							tripCodeWrapper.show();
							tripCodeInput.prop("disabled", true);
						}
					});

					// Trigger on page load if "other" is already selected
					if (tripSelect.val() === "other") {
						customTripWrapper.show();
						tripCodeWrapper.show();
						tripCodeInput.prop("disabled", true);
					} else {
						customTripWrapper.hide();
						customTripInput.prop("disabled", true);
						tripCodeWrapper.show();
						tripCodeInput.prop("disabled", true);
					}
				});
			})(jQuery);
			</script>',
			esc_js( is_array( $this->field ) ? ( $this->field['id'] ?? '' ) : ( is_object( $this->field ) ? ( $this->field->id ?? '' ) : '' ) )
		);

		if ( ! $display ) {
			return $output;
		}

		echo $output;
	}
}
