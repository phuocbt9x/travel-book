<?php

/**
 * Booking Details Page
 *
 * This template can be overridden by copying it to yourtheme/wp-travel-engine/account/booking-details.php.
 *
 * HOWEVER, on occasion WP Travel will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://wptravelengine.com
 * @author  WP Travel Engine
 * @package WP Travel Engine/includes/templates
 * @version 1.3.7
 */

use WPTravelEngine\Core\Models\Post\Trip;
use WPTravelEngine\Core\Models\Post\Booking;
use WPTravelEngine\Builders\FormFields\TravellerFormFields;
use WPTravelEngine\Helpers\CartInfoParser;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$booking_instance = new Booking( $booking );
$booking_payments = $booking_instance->get_payments() ?? array();
$cart_info        = new CartInfoParser( $booking_instance->get_cart_info() ?? array() );
$settings         = wptravelengine_settings()->get();
$dashboard_id     = isset( $settings['pages']['wp_travel_engine_dashboard_page'] ) ? esc_attr( $settings['pages']['wp_travel_engine_dashboard_page'] ) : wp_travel_engine_get_page_id( 'my-account' );

$currency_code = $cart_info->get_currency() ?? '';
$order_trips   = $booking_instance->get_meta( 'order_trips' );
$order_trip    = reset( $order_trips );
$trip_id       = $order_trip['ID'] ?? '';

$additional_note    = $booking_instance->get_meta( 'wptravelengine_additional_note' );
$_traveller_details = $booking_instance->get_meta( 'wptravelengine_travelers_details' );

$trip = new Trip( $trip_id );
$cart = $booking_instance->get_cart_info();

$start_datetime  = $order_trip['datetime'];
$trip_start_date = wptravelengine_format_trip_datetime( $start_datetime );
$trip_end_date   = wptravelengine_format_trip_end_datetime( $start_datetime, $trip );

// if ( isset( $cart['items'][0] ) && is_array( $cart['items'][0] ) ) {
// ** @var array $cart_item */
// $trip_start_date = wptravelengine_format_trip_datetime( $cart['items'][0]['trip_date'] ) ?? $trip_start_date;
// $trip_end_date   = isset( $cart['items'][0]['end_date'] ) ? wptravelengine_format_trip_datetime( $cart['items'][0]['end_date'] ) : $trip_end_date;
// }

$traveller_details = array();
if ( is_array( $_traveller_details ) ) {
	foreach ( $_traveller_details as $traveller ) {
		$traveller_form_fields = new TravellerFormFields();
		$traveller_details[]   = $traveller_form_fields->with_values( $traveller );
	}
}

if ( $booking_instance->is_curr_cart() ) {
	$due = $booking_instance->get_total_due_amount();
} else {
	$due = (float) $booking_instance->get_due_amount() < 1 ? 0 : (float) $booking_instance->get_due_amount();
}

?>
<div class="wpte-full">
	<div class="wpte-container container">
		<a href="<?php echo esc_url( get_permalink( $dashboard_id ) ); ?>" class="wpte-back-btn">
			<?php wptravelengine_svg_by_fa_icon( 'fas fa-arrow-left' ); ?><?php esc_html_e( 'Back to Booking', 'wp-travel-engine' ); ?>
		</a>
		<?php
		$payment_link = $booking_instance->get_due_payment_link();

		if ( $due > 0 && ! empty( $payment_link ) ) {
			?>
			<div class="wpte-ud-message wpte-warning" style="margin: 0 0 32px;">
				<p>
				<?php
				printf(
					/* translators: %s: amount of money */
					esc_html__( 'Due %1$s needs to be paid.', 'wp-travel-engine' ),
					'<strong>' . wptravelengine_the_price( $due, false, compact( 'currency_code' ) ) . '</strong>'
				);
				?>
				<a href="<?php echo esc_url( $payment_link ); ?>"><?php esc_html_e( 'Pay Now', 'wp-travel-engine' ); ?></a>
				</p>
			</div>
		<?php } ?>
		<div class="wpte-booking-details-wrapper">
			<div class="wpte-booking-detail-left-section">
				<?php
				wptravelengine_get_template(
					'thank-you/content-booking-details.php',
					compact(
						'trip_start_date',
						'trip_end_date',
						'additional_note',
						'traveller_details'
					)
				);
				?>

				<?php if ( ! $booking_instance->is_curr_cart() ) { ?>
					<div class="wpte-payment-details">
						<h5 class="wpte-payment-heading"><?php esc_html_e( 'Payment Details', 'wp-travel-engine' ); ?></h5>
						<div class="wpte-payment-data">
							<?php
							if ( is_array( $booking_payments ) ) {
								foreach ( $booking_payments as $index => $booking_payment ) {

									$payment_status = get_post_meta( $booking_payment->ID, 'payment_status', true );
									?>
									<h6>
										<?php
										// Translators: %s: Payment number.
										printf( __( 'Payment #%s', 'wp-travel-engine' ), $index + 1 );
										?>
									</h6>
									<ul>
										<li>
											<span><?php esc_html_e( 'Payment ID:', 'wp-travel-engine' ); ?></span>
											<span><?php echo esc_html( $booking_payment->ID ); ?></span>
										</li>
										<li>
											<span><?php esc_html_e( 'Payment Status:', 'wp-travel-engine' ); ?></span>
											<span
												class="wpte-status <?php echo esc_attr( $payment_status ); ?>">
												<?php
												$payment_status_labels = wptravelengine_payment_status();
												$payment_status        = $payment_status_labels[ $payment_status ] ?? $payment_status;
												echo esc_html( $payment_status );
												?>
											</span>
										</li>
										<li>
											<span><?php esc_html_e( 'Amount:', 'wp-travel-engine' ); ?></span>
											<span>
												<?php
												$payable = get_post_meta( $booking_payment->ID, 'payment_amount', true ) ?? 0;
												wptravelengine_the_price( $payable['value'] ?? 0, true, compact( 'currency_code' ) );
												?>
											</span>
										</li>
										<?php
										$wc_order_id = get_post_meta( $booking, '_wte_wc_order_id', true );
										if ( ! empty( $wc_order_id ) ) :
											?>
											<li>
												<?php
												printf(
													__( 'This booking was made using WooCommerce payments, view detail payment information %1$shere%2$s', 'wp-travel-engine' ),
													'<a href="' . admin_url( "/post.php?post={$wc_order_id}&action=edit" ) . '">',
													'</a>'
												);
												?>
											</li>
										<?php endif; ?>
									</ul>
									<?php
								}
							}
							?>
						</div>
					</div>
				<?php } ?>

			</div>
			<div class="wte-booking-detail-right-section">
				<div class="wpte-booking-details">
					<?php
					$tour_details = array();

					foreach ( $cart['items'] ?? array() as $cart_item ) {
						/** @var array $cart_item */

						$_start_date     = ( $cart_item['trip_time'] ?? '' ) ?: ( ( $cart_item['trip_date'] ?? '' ) ?: null );
						$trip            = new Trip( $cart_item['trip_id'] );
						$end_date        = isset( $cart_item['end_date'] ) ? wptravelengine_format_trip_datetime( $cart_item['end_date'] ) : $trip_end_date;
						$start_date      = isset( $_start_date ) ? wptravelengine_format_trip_datetime( $_start_date ) : $trip_start_date;
						$trip_package    = $cart_item['trip_package'] ?? get_the_title( $cart_item['price_key'] );
						$travelers_count = $cart_item['travelers_count'] ?? array_sum( $cart_item['pax'] ?? array() );
						$link            = wptravelengine_toggled( $trip->get_meta( 'is_created_from_booking' ) ) ? '' : $trip->get_permalink();
						$item            = array(
							sprintf(
								'<tr><td colspan="2">%s</td></tr>',
								sprintf(
									'<tr><td colspan="2">%s</td></tr>',
									sprintf(
										'<a %sclass="wpte-checkout__trip-name">%s</a>',
										$link ? 'href="' . esc_url( $link ) . '" ' : '',
										esc_html( $trip->get_title() )
									)
								)
							),
							sprintf( '<tr><td>%s</td><td><strong>%s</strong></td></tr>', __( 'Booking ID:', 'wp-travel-engine' ), $booking ),
							sprintf( '<tr><td>%s</td><td><strong>%s</strong></td></tr>', __( 'Package:', 'wp-travel-engine' ), $trip_package ),
							sprintf( '<tr><td>%s</td><td><strong>%s</strong></td></tr>', __( 'Trip Code:', 'wp-travel-engine' ), $trip->get_trip_code() ),
							sprintf( '<tr><td>%s</td><td><strong>%s</strong></td></tr>', __( 'Starts on:', 'wp-travel-engine' ), $start_date ),
							sprintf( '<tr><td>%s</td><td><strong>%s</strong></td></tr>', __( 'Ends on:', 'wp-travel-engine' ), $end_date ),
							sprintf( '<tr><td>%s</td><td><strong>%s</strong></td></tr>', __( 'No. of Travellers:', 'wp-travel-engine' ), $travelers_count ),
						);

						$tour_details[] = $item;
					}

					wptravelengine_get_template(
						'template-checkout/content-tour-details.php',
						array_merge(
							compact( 'tour_details' ),
							array(
								'content_only' => true,
							)
						)
					);

					wptravelengine_set_template_args(
						array(
							'cart_info'         => $cart_info,
							'pricing_arguments' => array(
								'currency_code' => $currency_code,
							),
							'booking'           => $booking_instance,
						)
					);

					if ( $booking_instance->is_curr_cart() ) {
						wptravelengine_get_admin_template( 'booking/partials/booking-summary.php' );
					} else {
						wptravelengine_get_admin_template( 'booking/legacy/partials/booking-summary.php' );
					}

					?>
				</div>
			</div>
		</div>
	</div>
</div>