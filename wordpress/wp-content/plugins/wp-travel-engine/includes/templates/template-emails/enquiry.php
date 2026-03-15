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
			<td colspan="2" style="font-size: 24px;line-height: 1.5;font-weight: bold;">
				<?php echo esc_html__( 'Hi', 'wp-travel-engine' ); ?> {customer_full_name},
			</td>
		</tr>
		<tr>
			<td colspan="2" style="padding: 16px 0 8px;">
				<?php echo esc_html__( 'Thank you for reaching out to us. We’ve received your enquiry and will get back to you as soon as possible.', 'wp-travel-engine' ); ?>
			<p style="margin: 8px 0 0;"><?php echo esc_html__( 'Our team is reviewing your message and will respond shortly.', 'wp-travel-engine' ); ?></p>
			</td>
		</tr>
		<tr>
			<td colspan="2">
				<?php echo esc_html__( 'Meanwhile, feel free to explore our available tours.', 'wp-travel-engine' ); ?>
			</td>
		</tr>
		<tr>
			<td colspan="2">
				<?php echo esc_html__( 'Warm regards,', 'wp-travel-engine' ); ?>
				<br>
				{sitename} <?php echo esc_html__( 'Team', 'wp-travel-engine' ); ?>
			</td>
		</tr>
	</table>
