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
			<p style="margin: 8px 0 0;"><?php esc_html_e( 'Your account has been successfully created. You can now manage your bookings, view trip details, and much more.', 'wp-travel-engine' ); ?></p>
			</td>
		</tr>
		<tr>
			<td colspan="2">
				<?php esc_html_e( 'Your Email:', 'wp-travel-engine' ); ?> <strong>{customer_email}</strong>
			</td>
		</tr>
		<tr>
			<td colspan="2">
				<?php esc_html_e( 'Your Password:', 'wp-travel-engine' ); ?> <a href="{password_reset_link}"><?php esc_html_e( 'Click here to set your password', 'wp-travel-engine' ); ?></a>
			</td>
		</tr>
		<tr>
			<td colspan="2" style="padding: 16px 0;">
				<?php esc_html_e( 'If you didn\'t create this account, please contact us immediately at {site_admin_email}', 'wp-travel-engine' ); ?>
			</td>
		</tr>
		<tr>
			<td colspan="2">
				<?php esc_html_e( 'Cheers,', 'wp-travel-engine' ); ?>
				<br>
				{sitename} <?php esc_html_e( 'Team', 'wp-travel-engine' ); ?>
			</td>
		</tr>
	</table>
