<?php
/**
 * Cart Payment Amount.
 *
 * @since 6.4.0
 */

/**
 * @var Booking $booking
 */
use WPTravelEngine\Core\Models\Post\Booking;

?>
<tr>
	<td>
		<div style="display: flex;align-items:center;gap:.5em;"><?php echo esc_html( 'Amount Paid' ); ?></div>
	</td>
	<td>
		<input type="number" name="paid_amount" step="any"
				value="<?php echo esc_attr( $booking->get_total_paid_amount() ); ?>"
				<?php echo $is_booking_edit_enabled ? '' : 'readonly'; ?>/>
	</td>
</tr>

<tr>
	<td>
		<div style="display: flex;align-items:center;gap:.5em;"><?php echo esc_html( 'Amount Due' ); ?></div>
	</td>
	<td>
		<input type="number" name="due_amount" step="any"
				value="<?php echo esc_attr( $booking->get_total_due_amount() ); ?>"
				<?php echo $is_booking_edit_enabled ? '' : 'readonly'; ?>/>
	</td>
</tr>
