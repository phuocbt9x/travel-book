<?php
/**
 * Global post call inside the edit metabox.
 *
 * @package WP_Travel_Engine
 *
 * @access Admin
 */
// POST
global $post;

$wp_travel_engine_setting          = get_post_meta( $post->ID, 'wp_travel_engine_setting', true );
$wp_travel_engine_enquiry_formdata = get_post_meta( $post->ID, 'wp_travel_engine_enquiry_formdata', true );
$wte_old_enquiry_details           = $wp_travel_engine_setting['enquiry'] ?? array();

$enquiry_display       = wptravelengine_get_enquiry_form_field_map( isset( $wp_travel_engine_enquiry_formdata['package_id'] ) ? absint( $wp_travel_engine_enquiry_formdata['package_id'] ) : 0 );
$enquiry_field_map     = $enquiry_display['field_map'];
$validation_only_types = $enquiry_display['validation_only_types'];
?>
	<div class="wpte-main-wrap wpte-edit-enquiry">
		<div class="wpte-block-wrap">
			<div class="wpte-block">
				<div class="wpte-block-content">
					<?php do_action( 'wptravelengine_before_enquiry_details', $wp_travel_engine_enquiry_formdata, $post->ID ); ?>
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
					<?php do_action( 'wptravelengine_after_enquiry_details', $wp_travel_engine_enquiry_formdata, $post->ID ); ?>
				</div>
			</div> <!-- .wpte-block -->
		</div> <!-- .wpte-block-wrap -->
	</div><!-- .wpte-main-wrap -->
<?php
