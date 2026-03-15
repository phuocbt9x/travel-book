<?php
/**
 * Enquiry Preview controller.
 *
 * @package WPTravelEngine/Core/Controllers
 * @since 6.0.0
 */

namespace WPTravelEngine\Core\Controllers\Ajax;

use WPTravelEngine\Abstracts\AjaxController;
use WPTravelEngine\Core\Models\Post\Enquiry;

/**
 * Handles ajax request for enquiry preview.
 */
class EnquiryPreview extends AjaxController {

	const NONCE_KEY    = '_nonce';
	const NONCE_ACTION = 'wte_get_enquiry_preview';
	const ACTION       = 'wte_get_enquiry_preview';

	/**
	 * Process Request.
	 * Get Enquiry preview.
	 *
	 * @return void
	 */
	protected function process_request() {
		$enquiry_id = $this->request->get_param( 'enquiry_id' );
		if ( isset( $enquiry_id ) ) {
			$enquiry_instance                  = new Enquiry( (int) $enquiry_id );
			$wp_travel_engine_enquiry_formdata = $enquiry_instance->get_enquiry_data();
			$wte_old_enquiry_details           = $enquiry_instance->get_old_enquiry_data();

			$enquiry_display       = wptravelengine_get_enquiry_form_field_map( isset( $wp_travel_engine_enquiry_formdata['package_id'] ) ? absint( $wp_travel_engine_enquiry_formdata['package_id'] ) : 0 );
			$enquiry_field_map     = $enquiry_display['field_map'];
			$validation_only_types = $enquiry_display['validation_only_types'];

			ob_start();
			?>
			<div style="background-color:#ffffff" class="wpte-main-wrap wpte-edit-enquiry">
				<div class="wpte-block-wrap">
					<div class="wpte-block">
						<div class="wpte-block-content">
							<ul class="wpte-list">
								<?php
								if ( ! empty( $wp_travel_engine_enquiry_formdata ) ) :
									foreach ( $wp_travel_engine_enquiry_formdata as $key => $data ) :
										if ( wptravelengine_enquiry_should_hide_field( $key, $enquiry_field_map, $validation_only_types ) ) {
											continue;
										}

										$data       = is_array( $data ) ? implode( ', ', $data ) : $data;
										$data_label = wptravelengine_enquiry_get_field_display_label( $key, $enquiry_field_map );
										?>
										<li>
											<b><?php echo esc_html( $data_label ); ?></b>
											<span>
												<?php echo wp_kses_post( $data ); ?>
											</span>
										</li>
										<?php
									endforeach;
								elseif ( ! empty( $wte_old_enquiry_details ) ) :
									if ( isset( $wte_old_enquiry_details['pname'] ) ) :
										?>
											<li>
												<b><?php esc_html_e( 'Package Name', 'wp-travel-engine' ); ?></b>
												<span>
												<?php echo wp_kses_post( $wte_old_enquiry_details['pname'] ); ?>
												</span>
											</li>
											<?php
										endif;
									if ( isset( $wte_old_enquiry_details['name'] ) ) :
										?>
											<li>
												<b><?php esc_html_e( 'Name', 'wp-travel-engine' ); ?></b>
												<span>
												<?php echo wp_kses_post( $wte_old_enquiry_details['name'] ); ?>
												</span>
											</li>
											<?php
										endif;
									if ( isset( $wte_old_enquiry_details['email'] ) ) :
										?>
											<li>
												<b><?php esc_html_e( 'Email', 'wp-travel-engine' ); ?></b>
												<span>
												<?php echo wp_kses_post( $wte_old_enquiry_details['email'] ); ?>
												</span>
											</li>
											<?php
										endif;
									if ( isset( $wte_old_enquiry_details['country'] ) ) :
										?>
											<li>
												<b><?php esc_html_e( 'Country', 'wp-travel-engine' ); ?></b>
												<span>
												<?php echo wp_kses_post( $wte_old_enquiry_details['country'] ); ?>
												</span>
											</li>
											<?php
										endif;
									if ( isset( $wte_old_enquiry_details['contact'] ) ) :
										?>
											<li>
												<b><?php esc_html_e( 'Contact', 'wp-travel-engine' ); ?></b>
												<span>
												<?php echo wp_kses_post( $wte_old_enquiry_details['contact'] ); ?>
												</span>
											</li>
											<?php
										endif;
									if ( isset( $wte_old_enquiry_details['adults'] ) ) :
										?>
											<li>
												<b><?php esc_html_e( 'Adults', 'wp-travel-engine' ); ?></b>
												<span>
												<?php echo wp_kses_post( $wte_old_enquiry_details['adults'] ); ?>
												</span>
											</li>
											<?php
										endif;
									if ( isset( $wte_old_enquiry_details['children'] ) ) :
										?>
											<li>
												<b><?php esc_html_e( 'Children', 'wp-travel-engine' ); ?></b>
												<span>
												<?php echo wp_kses_post( $wte_old_enquiry_details['children'] ); ?>
												</span>
											</li>
											<?php
										endif;
									if ( isset( $wte_old_enquiry_details['message'] ) ) :
										?>
											<li>
												<b><?php esc_html_e( 'Message', 'wp-travel-engine' ); ?></b>
												<span>
												<?php echo wp_kses_post( $wte_old_enquiry_details['message'] ); ?>
												</span>
											</li>
											<?php
										endif;
								endif;
								?>
							</ul>
						</div>
					</div> <!-- .wpte-block -->
				</div> <!-- .wpte-block-wrap -->
			</div><!-- .wpte-main-wrap -->
			<?php
			$data = ob_get_clean();

			wp_send_json_success(
				array(
					'message' => esc_html__( 'Data Fetched', 'wp-travel-engine' ),
					'html'    => $data,
				)
			);
		}
		wp_send_json_error( array( 'message' => esc_html__( 'Enquiry ID is missing', 'wp-travel-engine' ) ) );
	}
}
