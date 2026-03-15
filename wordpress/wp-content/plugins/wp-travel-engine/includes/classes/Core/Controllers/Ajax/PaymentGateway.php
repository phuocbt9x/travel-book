<?php
/**
 * Payment Gateway Controller.
 *
 * @package WPTravelEngine/Core/Controllers
 * @since 6.0.0
 */

namespace WPTravelEngine\Core\Controllers\Ajax;

use WPTravelEngine\Abstracts\AjaxController;
use WPTravelEngine\Helpers\Functions;

/**
 * Handles the payment gateway ajax request.
 */
class PaymentGateway extends AjaxController {

	const NONCE_KEY    = '_nonce';
	const NONCE_ACTION = 'wp_rest';
	const ACTION       = 'wte_payment_gateway';

	/**
	 * Process Request.
	 */
	public function process_request() {
		$post_data = wp_unslash( $this->request->get_body_params() );

		// $post_data = wp_clean( wp_unslash( $this->request->get_body_param() ) ); // phpcs:ignore WordPress.Security.NonceVerification.Missing

		if ( in_array( $post_data['val'], array( 'Test Payment', 'PayPal' ), true ) ) {
			ob_start();
			$obj             = \wptravelengine_functions();
			$billing_options = $obj->order_form_billing_options();
			foreach ( $billing_options as $key => $value ) {
				?>
				<div class='wp-travel-engine-billing-details-field-wrap'>
					<?php
					if ( in_array(
						$key,
						array(
							'fname',
							'lname',
							'email',
							'passport',
							'password',
							'address',
							'country',
						),
						true
					) ) {
						if ( 'country' === $key ) {
							?>
							<label for="<?php echo esc_attr( $key ); ?>"><?php esc_html( $value['label'] ); ?><span
									class="required">*</span></label>
							<select required id="<?php echo esc_attr( $key ); ?>"
									name="wp_travel_engine_booking_setting[place_order][booking][<?php echo esc_attr( $key ); ?>]"
									data-placeholder="<?php esc_attr_e( 'Choose a field type&hellip;', 'wp-travel-engine' ); ?>"
									class="wc-enhanced-select">
								<option
									value=" "><?php esc_html_e( 'Choose country&hellip;', 'wp-travel-engine' ); ?></option>
								<?php
								$options = Functions::get_countries();
								foreach ( $options as $key => $val ) {
									echo '<option value="' . ( ! empty( $key ) ? esc_attr( $key ) : 'Please select' ) . '">' . esc_html( $val ) . '</option>';
								}
								?>
							</select>
							<?php
						}
						?>
						<label
							for="wp_travel_engine_booking_setting[place_order][booking][<?php echo esc_attr( $key ); ?>]"><?php echo esc_html( $value['label'] ); ?>
							<span class="required">*</span></label>
						<input type="<?php echo esc_attr( $value['type'] ); ?>"
							name="wp_travel_engine_booking_setting[place_order][booking][<?php echo esc_attr( $key ); ?>]"
							id="wp_travel_engine_booking_setting[place_order][booking][<?php echo esc_attr( $key ); ?>]"
							<?php ( + $value['required'] === 1 ) && print 'required'; ?>/>
						<?php
					}
					?>
				</div>
				<?php
			}
			wp_reset_postdata();
			$data = ob_get_clean();
			wp_send_json_success( $data );
		}
	}
}

