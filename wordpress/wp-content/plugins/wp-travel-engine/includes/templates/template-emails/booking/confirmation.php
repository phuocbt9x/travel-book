<?php
/**
 *
 * Booking confirmation template.
 *
 * @since 6.5.0
 */
?>
	<table style="width:100%;">
		<tr>
			<td colspan="2" style="font-size: 24px;line-height: 1.5;font-weight: bold;">
				<?php echo WTE_Booking_Emails::get_string( 'order_confirmation', $args['sent_to'], 'heading' ); ?>
			</td>
		</tr>
		<tr>
			<td colspan="2" style="padding: 16px 0 8px;">
			<strong><?php echo WTE_Booking_Emails::get_string( 'order_confirmation', $args['sent_to'], 'greeting' ); ?></strong>
			<p style="margin: 8px 0 0;"><?php echo WTE_Booking_Emails::get_string( 'order_confirmation', $args['sent_to'], 'greeting_byline' ); ?></p>
			</td>
		</tr>
		<tr>
			<td colspan="2">
				{trip_payment_details}
			</td>
		</tr>
		<tr>
			<td colspan="2" style="padding: 16px 0;">
				<a target="_blank" style="display: block;font-size: 16px;line-height: 1;font-weight: 600;color: #fff;padding: 20px;background-color: #000;border-radius: 50px;text-align: center;text-decoration: none;" href="<?php echo 'admin' === $args['sent_to'] ? '{booking_url}' : get_permalink( wp_travel_engine_get_dashboard_page_id() ); ?>"><?php esc_html_e( 'VIEW DETAILS', 'wp-travel-engine' ); ?></a>
			</td>
		</tr>
		<tr>
			<td colspan="2">
				<?php echo WTE_Booking_Emails::get_string( 'order_confirmation', $args['sent_to'], 'footer' ); ?>
			</td>
		</tr>
	</table>
