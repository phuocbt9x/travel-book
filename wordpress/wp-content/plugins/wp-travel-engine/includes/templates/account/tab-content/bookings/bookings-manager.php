<?php
/**
 * Passes the booking details to the bookings html template.
 *
 * @since 6.0.0
 * @package wp-travel-engine/includes/templates/account/tab-content/bookings
 */

use WPTravelEngine\Core\Models\Post\Booking;

$booking_details = array();
foreach ( $args['bookings'] ?? array() as $booking ) {

	if ( empty( get_metadata( 'post', $booking ) ) ) {
		continue;
	}

	$booking_instance = new Booking( $booking );
	if ( 'publish' !== $booking_instance->post->post_status ) {
		continue;
	}

	$trip_date = $booking_instance->get_trip_datetime();

	$order_items = $booking_instance->get_order_items();
	$booked_trip = is_array( $order_items ) ? array_pop( $order_items ) : '';
	$booked_trip = is_null( $booked_trip ) || empty( $booked_trip ) ? '' : (object) $booked_trip;
	if ( ( empty( $trip_date ) ) || ( ( $trip_date < gmdate( 'Y-m-d' ) ) && 'active' === $type ) ) {
		continue;
	}

	$active_payment_methods = wp_travel_engine_get_active_payment_gateways();

	if ( $booking_instance->is_curr_cart() ) {
		$p_data     = $booking_instance->get_payments_data()['totals'] ?? array();
		$total_paid = $p_data['total_paid'] ?? 0;
		$due        = $p_data['due_exclusive'] ?? 0;
	} else {
		$total_paid = $booking_instance->get_paid_amount() ?? 0;
		$due        = $booking_instance->get_due_amount() ?? 0;
	}

	$show_pay_now_btn = $due > 0;
	$payment_status   = $show_pay_now_btn && $total_paid > 0 ? __( 'Partially Paid', 'wp-travel-engine' ) : ( $show_pay_now_btn ? __( 'Pending', 'wp-travel-engine' ) : __( 'Paid', 'wp-travel-engine' ) );

	if ( 'active' !== $type && ! $payment_status ) {
		$payment_status = __( 'Pending', 'wp-travel-engine' );
	}

	/**
	 * Filter the payment status for the booking.
	 *
	 * @param string  $payment_status Payment status.
	 * @param Booking $booking_instance Booking instance.
	 * @return string
	 */
	$payment_status = apply_filters( 'wptravelengine_my_account_payment_status', $payment_status, $booking_instance );

	$currency_code = $booking_instance->get_cart_info( 'currency' ) ?? '';

	$booking_details[] = compact(
		'active_payment_methods',
		'booked_trip',
		'trip_date',
		'payment_status',
		'total_paid',
		'due',
		'show_pay_now_btn',
		'booking_instance',
		'currency_code'
	);

}

?>
<div class="wpte-bookings-contents">
<?php
if ( ! empty( $booking_details ) ) :
	foreach ( $booking_details as $details ) :
		wte_get_template( 'account/tab-content/bookings/bookings-html.php', array_merge( $details, array( 'type' => $type ) ) );
	endforeach;
else :
	esc_html_e( 'You haven\'t booked any trip yet.', 'wp-travel-engine' );
endif;
?>
</div>
