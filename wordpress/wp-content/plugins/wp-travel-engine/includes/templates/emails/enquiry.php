<?php
/**
 * Enquiry notification emails.
 */
use WPTravelEngine\Core\Models\Settings\Options;

$formdata = $args['formdata'];
if ( wptravelengine_toggled( Options::get( 'wte_update_mail_template', false ) ) ) {
	wte_get_template( 'template-emails/enquiry-admin.php', $formdata );
} else {
	$enquiry_display       = wptravelengine_get_enquiry_form_field_map( isset( $formdata['package_id'] ) ? absint( $formdata['package_id'] ) : 0 );
	$enquiry_field_map     = $enquiry_display['field_map'];
	$validation_only_types = $enquiry_display['validation_only_types'];
	?>
<table class="main" width="100%" cellpadding="0" cellspacing="0">
	<tr>
		<td class="content-wrap aligncenter">
			<table width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<td class="content-block">
						<h1 class="aligncenter"><?php echo esc_html__( 'New Enquiry', 'wp-travel-engine' ); ?></h1>
					</td>
				</tr>
				<tr>
					<td class="content-block">
						<h3 class="aligncenter"><?php echo esc_html__( 'Enquiry Details', 'wp-travel-engine' ); ?></h3>
					</td>
				</tr>
				<tr>
					<td class="content-block aligncenter">
						<table class="invoice">
							<tr>
								<td style="margin: 0; padding: 5px 0;" valign="top">
									<table class="invoice-items" cellpadding="0" cellspacing="0">
										<?php
										foreach ( $formdata as $key => $data ) :
											if ( wptravelengine_enquiry_should_hide_field( $key, $enquiry_field_map, $validation_only_types ) ) {
												continue;
											}

											$data        = is_array( $data ) ? implode( ', ', $data ) : $data;
											$field_label = wptravelengine_enquiry_get_field_display_label( $key, $enquiry_field_map );
											?>
										<tr>
											<td><?php echo esc_html( $field_label ); ?></td>
											<td class="alignright">
												<?php
												if ( in_array( $key, array( 'package_name', 'enquiry_message' ) ) ) {
													echo wp_kses(
														$data,
														array(
															'a' => array( 'href' => array() ),
															'b' => array(),
														)
													);
												} else {
													echo esc_html( $data );
												}
												?>
											</td>
										</tr>
										<?php endforeach; ?>
									</table>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
	<?php
}
