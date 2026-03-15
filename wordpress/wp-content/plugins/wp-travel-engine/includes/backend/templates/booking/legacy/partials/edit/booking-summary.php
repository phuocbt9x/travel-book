<?php
/**
 * @var CartInfoParser $cart_info
 * @var Booking $booking
 */

use WPTravelEngine\Helpers\CartInfoParser;
use WPTravelEngine\Core\Models\Post\Booking;

global $current_screen;

$_cart_info              = $booking->get_meta( 'cart_info' );
$is_booking_edit_enabled = isset( $_cart_info['items'] ) || ( $current_screen->id === 'booking' && $current_screen->action === 'add' );
?>

<div class="wpte-booking-summary" data-booking-mode="edit">
	<h5 class="wpte-booking-summary-title"><?php echo __( 'Booking Summary', 'wp-travel-engine' ); ?></h5>
	<div class="wpte-booking-summary-table-wrap">
		<table class="wpte-booking-summary-table">
			<tbody>
			<?php
			wptravelengine_get_admin_template(
				'booking/legacy/partials/edit/booking-summary/line-items.php',
				array(
					'cart_line_items'         => $cart_info->get_item()->get_line_items(),
					'is_booking_edit_enabled' => $is_booking_edit_enabled,
				)
			);
			?>
			<tr class="wpte-booking-subtotal">
				<td><strong><?php echo esc_html__( 'Subtotal', 'wp-travel-engine' ); ?></strong></td>
				<td>
					<input type="number" name="subtotal"
							value="<?php echo esc_attr( (float) ( $cart_info->get_totals( 'subtotal' ) ?? 0 ) ); ?>" step="any"
							<?php echo $is_booking_edit_enabled ? '' : 'readonly'; ?>/>
				</td>
			</tr>
			<?php
			wptravelengine_get_admin_template(
				'booking/legacy/partials/edit/booking-summary/deductible-items.php',
				array(
					'deductible_items'        => $cart_info->get_deductible_items(),
					'is_booking_edit_enabled' => $is_booking_edit_enabled,
				)
			);

			wptravelengine_get_admin_template(
				'booking/legacy/partials/edit/booking-summary/fee-items.php',
				array(
					'fee_items'               => $cart_info->get_fees(),
					'is_booking_edit_enabled' => $is_booking_edit_enabled,
				)
			);

			?>
			<tr class="wpte-booking-total">
				<td><strong><?php echo esc_html__( 'Total', 'wp-travel-engine' ); ?></strong></td>
				<td>
					<input type="number" name="total"
							value="<?php echo esc_attr( (float) ( $cart_info->get_totals( 'total' ) ?? 0 ) ); ?>" step="any"
							<?php echo $is_booking_edit_enabled ? '' : 'readonly'; ?>/>
				</td>
			</tr>
			<?php
			wptravelengine_get_admin_template( 'booking/legacy/partials/edit/booking-summary/payment-amount-status.php', array( 'is_booking_edit_enabled' => $is_booking_edit_enabled ) );
			?>
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
