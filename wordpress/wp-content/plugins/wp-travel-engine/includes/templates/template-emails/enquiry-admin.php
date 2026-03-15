<?php
/**
 *
 * Customer Enquiry Submission template.
 *
 * @since 6.5.0
 */
?>
<table style="width:100%;">
	<tr>
		<td colspan="2" style="text-align: center;font-size: 24px;line-height: 1.5;font-weight: bold;">
			<?php echo esc_html__( 'New Enquiry', 'wp-travel-engine' ); ?>
		</td>
	</tr>
	<tr>
		<td colspan="2" style="text-align: center;padding: 16px 0 8px;">
			<strong><?php echo esc_html__( 'Enquiry Details', 'wp-travel-engine' ); ?></strong>
		</td>
	</tr>
	<?php
	$enquiry_display       = wptravelengine_get_enquiry_form_field_map( isset( $args['package_id'] ) ? absint( $args['package_id'] ) : 0 );
	$enquiry_field_map     = $enquiry_display['field_map'];
	$validation_only_types = $enquiry_display['validation_only_types'];

	foreach ( $args as $key => $data ) :
		if ( wptravelengine_enquiry_should_hide_field( $key, $enquiry_field_map, $validation_only_types ) ) {
			continue;
		}

			$data        = is_array( $data ) ? implode( ', ', $data ) : $data;
			$field_label = wptravelengine_enquiry_get_field_display_label( $key, $enquiry_field_map );
		?>
		<tr>
			<td style="color: #566267;"><?php echo esc_html( $field_label ); ?></td>
			<td style="width: 50%;text-align: right;"><strong>
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
			</strong></td>
		</tr>
	<?php endforeach; ?>
</table>
