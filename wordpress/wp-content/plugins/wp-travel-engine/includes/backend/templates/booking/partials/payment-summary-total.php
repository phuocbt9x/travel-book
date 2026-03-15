<?php
/**
 * Booking Summary Payment Details.
 *
 * @var \WPTravelEngine\Core\Models\Post\Booking $booking
 * @var array $payments_total
 * @since 6.7.0
 */
$payments_total = $temp_payments_data['totals'] ?? $payments_data['totals'] ?? array();

?>

<div class="wpte-payment-summary-item-total">
	<div class="wpte-payment-summary-card">
		<h3 class="wpte-payment-summary-title"><?php esc_html_e( 'Payment Summary', 'wp-travel-engine' ); ?></h3>
		<table class="wpte-payment-summary-table">
			<tr class="wpte-payment-deposit">
				<td><?php esc_html_e( 'Total Deposit Amount', 'wp-travel-engine' ); ?></td>
				<td><?php wptravelengine_the_price( $payments_total['total_deposit'] ?? 0, true, $pricing_arguments ); ?></td>
			</tr>
			<?php
			foreach ( $payments_total['tax_inclusive'] ?? array() as $fee_name => $fee ) {
				printf(
					'<tr class="wpte-payment-%1$s"><td>%2$s</td><td><strong>%3$s</strong></td></tr>',
					esc_attr( $fee_name ),
					esc_html( $fee['label'] ),
					wptravelengine_the_price( $fee['value'], false, $pricing_arguments )
				);
			}

			if ( isset( $payments_total['tax'] ) ) {
				printf(
					'<tr class="wpte-payment-tax"><td>%1$s</td><td><strong>%2$s</strong></td></tr>',
					esc_html( $payments_total['tax']['label'] ),
					wptravelengine_the_price( $payments_total['tax']['value'], false, $pricing_arguments )
				);
			}

			foreach ( $payments_total['tax_exclusive'] ?? array() as $fee_name => $fee ) {
				printf(
					'<tr class="wpte-payment-%1$s"><td>%2$s</td><td><strong>%3$s</strong></td></tr>',
					esc_attr( $fee_name ),
					esc_html( $fee['label'] ),
					wptravelengine_the_price( $fee['value'], false, $pricing_arguments )
				);
			}
			?>
			<tr class="wpte-payment-summary-total wpte-payment-amount">
				<td><?php esc_html_e( 'Total Amount Paid', 'wp-travel-engine' ); ?></td>
				<td class="wpte-payment-summary-amount"><?php wptravelengine_the_price( $payments_total['total_paid'] ?? 0, true, $pricing_arguments ); ?></td>
			</tr>
		</table>
	</div>
</div>