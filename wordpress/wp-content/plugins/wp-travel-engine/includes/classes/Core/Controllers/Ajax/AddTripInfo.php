<?php
/**
 * Trip Information Controller.
 *
 * @package WPTravelEngine/Core/Controllers
 * @since 6.0.0
 */

namespace WPTravelEngine\Core\Controllers\Ajax;

use WPTravelEngine\Abstracts\AjaxController;

/**
 * Adds trip information by ajax request.
 */
class AddTripInfo extends AjaxController {

	const NONCE_KEY    = 'nonce';
	const NONCE_ACTION = 'wp_add_trip_info';
	const ACTION       = 'wp_add_trip_info';
	const ALLOW_NOPRIV = false;

	/**
	 * Process Request.
	 * Trip facts ajax callback.
	 */
	protected function process_request() {
		$trip_facts = wptravelengine_get_trip_facts_options();
		// phpcs:ignore
		$id  = $this->request->get_param('val');
		$key = array_search( $id, $trip_facts['field_id'], true );

		$value = $trip_facts['field_type'][ $key ];

		$response = '<div class="wpte-repeater-block wpte-sortable wpte-trip-fact-row"><div class="wpte-field wpte-floated"><label for="wp_travel_engine_setting[trip_facts][' . $key . '][' . $key . ']" class="wpte-field-label">' . $id . '</label>';

		$response   .= '<input type="hidden" name="wp_travel_engine_setting[trip_facts][field_id][' . $key . ']" value="' . $id . '">';
		$response   .= '<input type="hidden" name="wp_travel_engine_setting[trip_facts][field_type][' . $key . ']" value="' . $value . '">';
		$placeholder = esc_attr( $trip_facts['input_placeholder'][ $key ] ?? '' );

		switch ( $value ) {
			case 'select':
				$options   = explode( ',', $trip_facts['select_options'][ $key ] );
				$response .= '<select id="wp_travel_engine_setting[trip_facts][' . $key . '][' . $key . ']" name="wp_travel_engine_setting[trip_facts][' . $key . '][' . $key . ']" data-placeholder="' . __( 'Choose a field type&hellip;', 'wp-travel-engine' ) . '">';
				$response .= '<option value=" ">' . __( 'Choose input type&hellip;', 'wp-travel-engine' ) . '</option>';
				foreach ( $options as $key => $val ) {
					$response .= '<option value="' . ( ! empty( $val ) ? esc_attr( $val ) : 'Please select' ) . '">' . esc_html( $val ) . '</option>';
				}
				$response .= '</select>';
				break;

			case 'duration':
				$response .= '<input type="number" min="1" placeholder = "' . esc_html__( 'Number of days', 'wp-travel-engine' ) . '" class="duration" id="wp_travel_engine_setting[trip_facts][' . $key . '][' . $key . ']" name="wp_travel_engine_setting[trip_facts][' . $key . '][' . $key . ']" value=""/>';
				break;

			case 'number':
				$response .= '<input  type="number" min="1" id="wp_travel_engine_setting[trip_facts][' . $key . '][' . $key . ']" name="wp_travel_engine_setting[trip_facts][' . $key . '][' . $key . ']" value="">';
				break;

			case 'text':
				$response .= '<input type="text" id="wp_travel_engine_setting[trip_facts][' . $key . '][' . $key . ']" name="wp_travel_engine_setting[trip_facts][' . $key . '][' . $key . ']" value="" placeholder="' . esc_attr( $placeholder ) . '">';
				break;

			case 'textarea':
				$response .= '<textarea id="wp_travel_engine_setting[trip_facts][' . $key . '][' . $key . ']" name="wp_travel_engine_setting[trip_facts][' . $key . '][' . $key . ']" placeholder="' . $placeholder . '"></textarea>';

				break;
			default:
				$response .= '<input type="text" id="wp_travel_engine_setting[trip_facts][' . $key . '][' . $key . ']" name="wp_travel_engine_setting[trip_facts][' . $key . '][' . $key . ']" value="" placeholder="' . esc_attr( $placeholder ) . '">';
				break;
		}
		$response .= '<button class="wpte-delete wpte-remove-trp-fact"></button></div></div>';
		echo $response; // phpcs:ignore
		die;
	}
}
