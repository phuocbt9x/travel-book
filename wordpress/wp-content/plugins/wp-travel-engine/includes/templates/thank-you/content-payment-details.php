<?php
/**
 * Content Thank-you payment details.
 *
 * @since 6.3.3
 */

/**
 * @var float $payment_amount
 * @var string $payment_status
 * @var string $remarks
 */
?>
<div class="wpte-checkout__booking-summary" style="margin-top:24px;">
	<div class="wpte-checkout__table-wrap">
		<table class="wpte-checkout__booking-summary-table">
			<tr class="wpte-checkout__booking-summary-total">
				<td><strong><?php echo __( 'Payment Details', 'wp-travel-engine' ); ?></strong></td>
				<td></td>
			</tr>
			<tr>
				<td><strong><?php echo __( 'Payment Amount', 'wp-travel-engine' ); ?></strong></td>
				<td>
					<?php wptravelengine_the_price( $payment_amount ); ?>
					<code><?php echo esc_html( " [$payment_status]" ); ?></code>
				</td>
			</tr>
			<tr>
				<td colspan="2" style="text-align: left; white-space: normal;">
					<strong><?php echo __( 'Remarks:', 'wp-travel-engine' ); ?></strong><br />
					<?php echo wp_kses_post( $remarks ); ?>
				</td>
			</tr>
		</table>
	</div>
</div>

