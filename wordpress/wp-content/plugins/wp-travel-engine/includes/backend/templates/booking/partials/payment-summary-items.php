<?php
/**
 * Booking Summary Payment Details.
 *
 * @var \WPTravelEngine\Core\Models\Post\Booking $booking
 * @var array $pricing_arguments
 * @since 6.7.0
 */
$payments_total = $temp_payments_data['totals'] ?? $payments_data['totals'] ?? array();
$all_payments   = $payments_data['payments'] ?? array();

if ( $booking->get_total_due_amount() <= 0 && count( $all_payments ) === 1 ) {
	echo '<div class="wpte-payment-summary-items" data-payment-summary-items></div>';
	return;
}

?>
<div class="wpte-payment-summary-items" data-payment-summary-items>
<?php
foreach ( $all_payments as $key => $payment ) {
	if ( empty( $payment ) ) {
		continue;
	}
	?>
	<div class="wpte-payment-card wpte-accordion">
		<div class="wpte-accordion-header">
			<h3 class="wpte-accordion-title wpte-payment-card-title"><?php echo esc_html( __( 'Payment', 'wp-travel-engine' ) . ' #' . $key ); ?></h3></button>
			<?php if ( is_admin() ) { ?>
				<button type="button" class="wpte-accordion-toggle active">
					<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
						<path d="M5 7.5L10 12.5L15 7.5" stroke="currentColor" stroke-width="1.66667" stroke-linecap="round" stroke-linejoin="round"></path>
					</svg>
				</button>
			<?php } ?>
		</div>
		<div class="wpte-accordion-content">
			<table class="wpte-payment-card-table">
				<tr class="wpte-payment-deposit">
					<td><?php esc_html_e( 'Deposit Amount', 'wp-travel-engine' ); ?></td>
					<td><?php wptravelengine_the_price( $payment['deposit'], true, $pricing_arguments ); ?></td>
				</tr>
				<?php
				foreach ( $payments_total['tax_inclusive'] ?? array() as $fee_name => $fee ) {
					printf(
						'<tr class="wpte-payment-%1$s"><td>%2$s</td><td>%3$s</td></tr>',
						esc_attr( $fee_name ),
						esc_html( $fee['label'] ),
						wptravelengine_the_price( $payment[ $fee_name ] ?? 0, false, $pricing_arguments )
					);
				}

				if ( isset( $payments_total['tax'] ) ) {
					printf(
						'<tr class="wpte-payment-tax"><td>%1$s</td><td>%2$s</td></tr>',
						esc_html( $payments_total['tax']['label'] ),
						wptravelengine_the_price( $payment['tax'] ?? 0, false, $pricing_arguments )
					);
				}

				foreach ( $payments_total['tax_exclusive'] ?? array() as $fee_name => $fee ) {
					printf(
						'<tr class="wpte-payment-%1$s"><td>%2$s</td><td>%3$s</td></tr>',
						esc_attr( $fee_name ),
						esc_html( $fee['label'] ),
						wptravelengine_the_price( $payment[ $fee_name ] ?? 0, false, $pricing_arguments )
					);
				}
				?>
				<tr class="wpte-payment-total wpte-payment-amount">
					<td><?php esc_html_e( 'Amount Paid', 'wp-travel-engine' ); ?></td>
					<td><?php wptravelengine_the_price( $payment['total'], true, $pricing_arguments ); ?></td>
				</tr>
			</table>
		</div>
	</div>
	<?php
}
?>
</div>
