<?php
/**
 * New Booking Summary.
 *
 * @var \WPTravelEngine\Core\Models\Post\Booking $booking
 * @var \WPTravelEngine\Helpers\CartInfoParser $cart_info
 * @var \WPTravelEngine\Utilities\PaymentCalculator  $calculator
 * @var array $pricing_arguments
 * @since 6.7.0
 */

global $current_screen, $wte_cart;

$_cart_info              = $booking->get_meta( 'cart_info' );
$is_booking_edit_enabled = isset( $_cart_info['items'] ) || ( $current_screen->id === 'booking' && $current_screen->action === 'add' );

$excl           = $wte_cart->get_exclusion_label( $_cart_info['fees'] ?? array() );
$payments_total = $booking->get_payments_data( false )['totals'] ?? array();
?>

<div class="wpte-booking-summary" data-booking-mode="edit" data-currency-symbol="<?php echo esc_attr( wp_travel_engine_get_currency_symbol( $cart_info->get_currency() ?: wptravelengine_settings()->get( 'currency_code', 'USD' ) ) ); ?>">
	<h5 class="wpte-booking-summary-title"><?php echo __( 'Booking Summary', 'wp-travel-engine' ); ?></h5>
	<div class="wpte-booking-summary-table-wrap">
		<table class="wpte-booking-summary-table">
			<tbody>
			<?php
			wptravelengine_get_admin_template(
				'booking/partials/edit/booking-summary/line-items.php',
				array(
					'is_booking_edit_enabled' => $is_booking_edit_enabled,
				)
			);

			$deductible_items = $cart_info->get_deductible_items() ?? array();
			if ( $deductible_items ) {
				foreach ( $deductible_items as $line_item ) {
					printf(
						'<tr class="wpte-booking-discount"><td>%1$s</td><td class="pricing-total"><b>-%2$s</b</td></tr>',
						esc_html( $line_item['label'] ?? '' ),
						wptravelengine_the_price( $line_item['value'] && $line_item['value'] > 0 ? $line_item['value'] : $cart_info->get_totals( 'total_' . $line_item['name'] ) ?? 0, false, $pricing_arguments )
					);
				}
			}
			?>
			<tr class="wpte-booking-total">
				<td><strong><?php esc_html_e( 'Total', 'wp-travel-engine' ); ?></strong></td>
				<td class="amount-column">
					<strong><?php wptravelengine_the_price( $payments_total['total_exclusive'] ?? 0, true, $pricing_arguments ); ?></strong>
					<input type="hidden" name="total" value="<?php echo esc_attr( $payments_total['total_exclusive'] ?? 0 ); ?>">
				</td>
			</tr>
			<tr class="wpte-booking-due">
				<td><?php printf( '<strong>%s</strong>%s', __( 'Amount Due', 'wp-travel-engine' ), $excl ? sprintf( ' (excl. %s)', $excl ) : '' ); ?></td>
				<td class="amount-column">
					<strong> <?php wptravelengine_the_price( $payments_total['due_exclusive'] ?? 0, true, $pricing_arguments ); ?> </strong>
				</td>
			</tr>
			</tbody>
		</table>
	</div>
</div>
<script type="text/html" id="tmpl-cart-adjustment-line-item">
	<tr class="wpte-booking-discount">
		<td>
			<div class="discount-input-wrapper" style="display: flex; align-items: center; gap: 0.5em;">
				<input type="text"
						name="{{data.item_type}}[label][]"
						placeholder="Label"
						value="">
			</div>
		</td>
		<td>
			<input type="number"
					name="{{data.item_type}}[value][]"
					aria-label="Total coupon amount"
					value=""
					style="flex: 0 0 80px;">
		</td>
		<td class="wpte-delete-column">
			<button type="button" class="wpte-table-delete-row">
				<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
					<path d="M16 6V5.2C16 4.0799 16 3.51984 15.782 3.09202C15.5903 2.71569 15.2843 2.40973 14.908 2.21799C14.4802 2 13.9201 2 12.8 2H11.2C10.0799 2 9.51984 2 9.09202 2.21799C8.71569 2.40973 8.40973 2.71569 8.21799 3.09202C8 3.51984 8 4.0799 8 5.2V6M10 11.5V16.5M14 11.5V16.5M3 6H21M19 6V17.2C19 18.8802 19 19.7202 18.673 20.362C18.3854 20.9265 17.9265 21.3854 17.362 21.673C16.7202 22 15.8802 22 14.2 22H9.8C8.11984 22 7.27976 22 6.63803 21.673C6.07354 21.3854 5.6146 20.9265 5.32698 20.362C5 19.7202 5 18.8802 5 17.2V6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
				</svg>
			</button>
		</td>
	</tr>
</script>

<?php wptravelengine_get_admin_template( 'booking/partials/payment-summary-items.php', array( 'payments_data' => $booking->get_payments_data( false ) ) ); ?>
<?php wptravelengine_get_admin_template( 'booking/partials/payment-summary-total.php' ); ?>