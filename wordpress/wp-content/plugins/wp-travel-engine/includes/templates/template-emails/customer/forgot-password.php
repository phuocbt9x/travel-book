<?php
/**
 *
 * Customer Account Registration template.
 *
 * @since 6.5.0
 */
?>
	<table style="width:100%;">
		<tr>
			<td colspan="2" style="font-size: 24px;line-height: 1.5;font-weight: bold;">
				<?php esc_html_e( 'Hi', 'wp-travel-engine' ); ?> {customer_first_name},
			</td>
		</tr>
		<tr>
			<td colspan="2" style="padding: 16px 0 8px;">
				<?php esc_html_e( 'Welcome to', 'wp-travel-engine' ); ?> <strong>{sitename}!</strong>
			<p style="margin: 8px 0 0;"><?php echo esc_html( sprintf( __( 'We received a request to reset your password for your %s account.', 'wp-travel-engine' ), '{sitename}' ) ); ?></p>
			</td>
		</tr>
		<tr>
			<td colspan="2">
				<?php esc_html_e( 'Click the link below to set a new password:', 'wp-travel-engine' ); ?>
				<a href="{password_reset_link}"><?php esc_html_e( 'Reset Password', 'wp-travel-engine' ); ?></a>
			</td>
		</tr>
		<tr>
			<td colspan="2" style="padding: 16px 0;">
				<?php esc_html_e( 'This link will expire in 24 hours for your security.', 'wp-travel-engine' ); ?>
			</td>
		</tr>
		<tr>
			<td colspan="2">
				<?php esc_html_e( 'If you didn\'t request this, please ignore this email or contact us.', 'wp-travel-engine' ); ?>
			</td>
		</tr>
		<tr>
			<td colspan="2">
				<?php esc_html_e( 'Thanks,', 'wp-travel-engine' ); ?>
				<br>
				{sitename} <?php esc_html_e( 'Team', 'wp-travel-engine' ); ?>
			</td>
		</tr>
	</table>
