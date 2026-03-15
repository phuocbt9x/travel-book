<?php
/**
 * Resend purchase receipt controller.
 *
 * @since 6.4.0
 */
namespace WPTravelEngine\Core\Controllers\Ajax;

use WP_Error;
use WPTravelEngine\Abstracts\AjaxController;
use WPTravelEngine\Core\Models\Post\Booking as BookingModel;
use WPTravelEngine\Core\Models\Post\Payment as PaymentModel;
use WPTravelEngine\Core\Models\Post\Trip as TripModel;
use WPTravelEngine\Helpers\CartInfoParser;

class ResendPurchaseReceipt extends AjaxController {

	const NONCE_KEY    = 'nonce';
	const NONCE_ACTION = 'wte_resend_purchase_receipt';
	const ACTION       = 'wte_resend_purchase_receipt';
	const ALLOW_NOPRIV = false;

	public function __construct() {
		parent::__construct();
	}

	/**
	 * Get booking details.
	 *
	 * @param array $mail_tags Mail tags.
	 * @param int   $payment_id Payment ID.
	 * @param int   $booking_id Booking ID.
	 * @since 6.4.0
	 *
	 * @return array
	 */
	public function wptravelengine_booking_mail_tags( $mail_tags, $payment_id, $booking_id ) {
		$booking           = BookingModel::make( $booking_id );
		$cart_info         = $booking->get_cart_info();
		$pricing_arguments = array(
			'currency_code' => $cart_info['currency'] ?? wptravelengine_settings()->get( 'currency_code', 'USD' ),
		);
		$cart_info         = new CartInfoParser( $cart_info );
		$totals            = $cart_info->get_totals() ?? array();

		return array_merge(
			$mail_tags,
			array(
				'{booking_details}' => $this->get_booking_detail( $booking_id, $mail_tags ),
				'{subtotal}'        => wte_get_formated_price( $totals['subtotal'], $pricing_arguments['currency_code'], '', true ),
				'{total}'           => wte_get_formated_price( $totals['total'], $pricing_arguments['currency_code'], '', true ),
				'{paid_amount}'     => wte_get_formated_price( $booking->get_total_paid_amount(), $pricing_arguments['currency_code'], '', true ),
				'{due}'             => wte_get_formated_price( $booking->get_total_due_amount(), $pricing_arguments['currency_code'], '', true ),
				'{price}'           => wte_get_formated_price( $booking->get_total(), $pricing_arguments['currency_code'], '', true ),
			)
		);
	}

	/**
	 * Get booking details.
	 *
	 * @param int $booking_id Booking ID.
	 * @since 6.4.0
	 *
	 * @return string
	 */


	/**
	 * Process request
	 */
	protected function process_request() {
		if ( ! $this->validate_request() ) {
			return;
		}

		try {
			add_filter( 'wte_booking_mail_tags', array( $this, 'wptravelengine_booking_mail_tags' ), 10, 3 );
			$post            = $this->request->get_body_params();
			$booking_id      = $this->validate_booking( $post );
			$booking_details = $this->get_booking_details( $booking_id );
			$this->send_receipt_emails( $booking_details );
		} catch ( \Exception $e ) {
			wp_send_json_error(
				new \WP_Error(
					'RESEND_RECEIPT_ERROR',
					$e->getMessage()
				)
			);
		}
	}

	/**
	 * Validate request
	 *
	 * @return bool True if request is valid, false otherwise
	 */
	private function validate_request() {
		if ( ! $this->request->get_param( 'resend_purchase_receipt' ) ) {
			wp_send_json_error(
				new \WP_Error(
					'RESEND_RECEIPT_ERROR',
					__( 'Invalid request.', 'wp-travel-engine' )
				)
			);
			return false;
		}
		return true;
	}

	/**
	 * Validate booking
	 *
	 * @param array $post Post data
	 * @return int Booking ID
	 */
	private function validate_booking( $post ) {
		if ( ! isset( $post['booking_id'] ) || is_null( get_post( $post['booking_id'] ) ) ) {
			wp_send_json_error(
				new \WP_Error(
					'RESEND_RECEIPT_ERROR',
					__( 'Invalid booking ID.', 'wp-travel-engine' )
				)
			);
		}

		$booking_id = absint( $post['booking_id'] );
		$booking    = get_post( $booking_id );

		if ( 'booking' !== $booking->post_type ) {
			wp_send_json_error(
				new \WP_Error(
					'RESEND_RECEIPT_ERROR',
					__( 'Invalid booking type.', 'wp-travel-engine' )
				)
			);
		}

		return $booking_id;
	}

	/**
	 * Get booking details
	 *
	 * @param int $booking_id Booking ID
	 * @return array Booking details
	 */
	private function get_booking_details( $booking_id ) {
		$booking_details = BookingModel::make( $booking_id );
		return array(
			'booking_id'   => $booking_id,
			'details'      => $booking_details,
			'payments'     => $booking_details->get_payments(),
			'new_payments' => array(),
		);
	}

	/**
	 * Get booking detail
	 *
	 * @param int   $booking_id Booking ID
	 * @param array $mail_tags Mail tags
	 * @return bool|string Booking detail
	 */
	public function get_booking_detail( $booking_id, $mail_tags ) {
		$booking          = BookingModel::make( $booking_id );
		$cart_info        = $booking->get_cart_info() ?? array();
		$line_items       = $cart_info['items'][0]['line_items'] ?? array();
		$cart_info        = new CartInfoParser( $cart_info );
		$totals           = $cart_info->get_totals() ?? array();
		$deductible_items = $cart_info->get_deductible_items() ?? array();
		$fees             = $cart_info->get_fees() ?? array();

		$order_trip = $cart_info->get_item();
		ob_start();
		$trip = TripModel::make( $order_trip->get_trip_id() );

		$currency_code     = $booking->get_cart_info()['currency'] ?? wptravelengine_settings()->get( 'currency_code', 'USD' );
		$pricing_arguments = array(
			'currency_code' => $currency_code,
		);

		?>
			<table width="100%" cellpadding="0" cellspacing="0">
					<tr>
						<td colspan="2"><b><?php echo esc_html( $trip->get_title() ); ?></b></td>
					</tr>
					<tr>
						<td><?php esc_html_e( 'Package Name', 'wp-travel-engine' ); ?></td>
						<td class="alignright"><?php echo esc_html( $order_trip->get_package_name() ); ?></td>
					</tr>
					<tr>
						<td><?php esc_html_e( 'Trip Booked Date', 'wp-travel-engine' ); ?></td>
						<td class="alignright"><?php echo esc_html( $booking->post->post_date ); ?></td>
					</tr>
					<?php if ( $start_date = $order_trip->get_trip_date() ) : ?>
						<tr>
							<td><?php esc_html_e( 'Trip Start Date', 'wp-travel-engine' ); ?></td>
							<td class="alignright"><?php echo esc_html( $start_date ); ?></td>
						</tr>
					<?php endif; ?>
					<?php if ( $end_date = $order_trip->get_end_date() ) : ?>
						<tr>
							<td><?php esc_html_e( 'Trip End Date', 'wp-travel-engine' ); ?></td>
							<td class="alignright"><?php echo esc_html( $end_date ); ?></td>
						</tr>
					<?php endif; ?>
					<tr>
						<td><?php esc_html_e( 'Travellers', 'wp-travel-engine' ); ?></td>
						<td class="alignright"><?php echo esc_html( $order_trip->travelers_count() ); ?></td>
					</tr>
					<tr>
						<td><h3><?php esc_html_e( 'Booking Summary', 'wp-travel-engine' ); ?></h3></td>
					</tr>
					<?php if ( ! empty( $line_items ) ) : ?>
						<?php foreach ( $line_items as $cart_data => $data ) : ?>
						<tr>
							<td class="title-holder" style="margin: 0" valign="top">
								<h4 class="alignleft"><?php echo esc_html( $cart_data ); ?></h4>
							</td>
						</tr>
							<?php foreach ( $data as $cart_data => $value ) : ?>
							<tr>
								<td><?php echo esc_html( $value['label'] ); ?></td>
								<td class="alignright">
								<?php
								echo esc_html( $value['quantity'] ) . ' X ';
								wptravelengine_the_price( $value['price'], true, $pricing_arguments );
								echo ' = ';
								wptravelengine_the_price( $value['total'] && $value['total'] > 0 ? $value['total'] : $value['quantity'] * $value['price'], true, $pricing_arguments );
								?>
								</td>
							</tr>
						<?php endforeach; ?>
					<?php endforeach; ?>
					<?php endif; ?>
					<?php
					if ( ! empty( $totals ) ) :
						?>
						<tr>
							<td class="title-holder" style="margin: 0" valign="top">
								<h4 class="alignleft"><?php esc_html_e( 'Subtotal', 'wp-travel-engine' ); ?></h4>
							</td>
							<td class="alignright">
								<?php wptravelengine_the_price( $totals['subtotal'], true, $pricing_arguments ); ?>
							</td>
						</tr>
						<?php if ( ! empty( $deductible_items ) ) : ?>
							<tr>
								<td class="title-holder" style="margin: 0" valign="top">
									<h4 class="alignleft"><?php esc_html_e( 'Deductible Items', 'wp-travel-engine' ); ?></h4>
								</td>
							</tr>
							<?php
							if ( is_array( $deductible_items ) ) :
								foreach ( $deductible_items as $deductible_item ) :
									?>
								<tr>
									<td class="alignright">
										<?php
										echo esc_html( $deductible_item['label'] );
										echo ' = ';
										wptravelengine_the_price( $deductible_item['value'] && $deductible_item['value'] > 0 ? $deductible_item['value'] : $totals['total_coupon'], true, $pricing_arguments );
										?>
									</td>
								</tr>
								<?php endforeach; ?>
							<?php endif; ?>
						<?php endif; ?>
						<?php if ( ! empty( $fees ) ) : ?>
							<tr>
							<td class="title-holder" style="margin: 0" valign="top">
								<h4 class="alignleft"><?php esc_html_e( 'Fees', 'wp-travel-engine' ); ?></h4>
							</td>
							</tr>
							<?php if ( is_array( $fees ) ) : ?>
								<?php foreach ( $fees as $fee ) : ?>
							<tr>
								<td class="alignright">
									<?php
									echo esc_html( $fee['label'] );
									echo ' = ';
									wptravelengine_the_price( $fee['value'] && $fee['value'] > 0 ? $fee['value'] : $totals['total_tax'], true, $pricing_arguments );
									?>
								</td>
							</tr>
							<?php endforeach; ?>
								<?php
						endif;
						endif;
						?>
						<tr>
							<td class="title-holder" style="margin: 0" valign="top">
								<h4 class="alignleft"><?php esc_html_e( 'Total', 'wp-travel-engine' ); ?></h4>
							</td>

							<td class="alignright">
								<?php wptravelengine_the_price( $totals['total'], true, $pricing_arguments ); ?>
							</td>
						</tr>
						<tr>
						<td class="title-holder" style="margin: 0" valign="top">
								<h4 class="alignleft"><?php esc_html_e( 'Paid Amount', 'wp-travel-engine' ); ?></h4>
							</td>

							<td class="alignright"><?php wptravelengine_the_price( $booking->get_total_paid_amount(), true, $pricing_arguments ); ?></td>
						</tr>
						<tr>
							<td class="title-holder" style="margin: 0" valign="top">
								<h4 class="alignleft"><?php esc_html_e( 'Due Amount', 'wp-travel-engine' ); ?></h4>
							</td>
							<td class="alignright"><?php wptravelengine_the_price( $booking->get_total_due_amount(), true, $pricing_arguments ); ?></td>
						</tr>

					<?php endif; ?>

				</table>
			<?php
			return ob_get_clean();
	}

	/**
	 * Send receipt emails
	 *
	 * @param array $booking_data Booking data
	 */
	private function send_receipt_emails( $booking_data ) {
		if ( ! is_array( $booking_data ) || ! isset( $booking_data['payments'] ) ) {
			wp_send_json_error(
				array(
					'code'    => 'RESEND_RECEIPT_ERROR',
					'message' => __( 'Payment not found. Make sure payment is added to the booking.', 'wp-travel-engine' ),
				)
			);
			return;
		}
		$latest_payment_status = '';

		if ( is_array( $booking_data['payments'] ) && ! empty( $booking_data['payments'] ) ) {
			foreach ( $booking_data['payments'] as $payment ) {
				if ( isset( $payment->ID ) ) {
					$payment = PaymentModel::make( $payment->ID );
					wptravelengine_send_booking_emails( $payment, 'order', 'customer' );
					$latest_payment_status = $payment->get_payment_status();
					$success_values        = array( 'completed', 'success', 'captured', 'complete', 'succeed', 'capture' );

					if ( in_array( $latest_payment_status, $success_values, true ) ) {
						wptravelengine_send_booking_emails( $payment->ID, 'order_confirmation', 'all' );
					}

					wp_send_json_success(
						array(
							'code'       => 'RESEND_RECEIPT_SUCCESS',
							'message'    => __( 'Purchase receipt sent successfully.', 'wp-travel-engine' ),
							'booking_id' => $booking_data['booking_id'],
							'payment'    => $payment,
						)
					);
				}
			}
		} else {
			wp_send_json_error(
				array(
					'code'    => 'RESEND_RECEIPT_ERROR',
					'message' => __( 'Payment not found. Make sure payment is added to the booking.', 'wp-travel-engine' ),
				)
			);
		}
	}
}