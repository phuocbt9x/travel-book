<?php
/**
 * Payment Details
 *
 * @since 4.3.0
 */

use WPTravelEngine\Core\Models\Post\Payment;

global $post;

$booking_details = new \stdClass();
extract( $_args );

wp_enqueue_script( 'wte-highlightjs' );
wp_enqueue_style( 'wte-highlightjs' );
?>
<div class="wpte-block wpte-col3">
	<div class="wpte-title-wrap">
		<h4 class="wpte-title"><?php esc_html_e( 'Payment Details', 'wp-travel-engine' ); ?></h4>
		<div class="wpte-button-wrap wpte-edit-booking-detail">
			<a href="#" class="wpte-btn-transparent wpte-btn-sm">
				<?php wptravelengine_svg_by_fa_icon( 'fas fa-pencil-alt' ); ?>
				<?php esc_html_e( 'Edit', 'wp-travel-engine' ); ?>
			</a>
		</div>
	</div>
	<div class="wpte-block-content">
		<?php
		$payments = $booking_details->payments;
		foreach ( $payments as $index => $payment_id ) {
			$payment         = get_post( $payment_id );
			$booking_id      = get_post_meta( $payment_id, 'booking_id', true );
			$payment_status  = isset( $payment->payment_status ) ? $payment->payment_status : __( 'N/A', 'wp-travel-engine' );
			$payment_gateway = $payment->payment_gateway;
			?>
			<h4><?php printf( esc_html__( 'Payment #%d', 'wp-travel-engine' ), (int) $index + 1 ); ?></h4>
			<?php
			$wc_order_id = get_post_meta( $booking_id, '_wte_wc_order_id', true );
			if ( ! empty( $wc_order_id ) ) :
				?>
				<div class="wpte-info-block" style="margin-bottom: 16px;">
					<p>
						<?php
						printf(
							wp_kses(
							/* translators: %1$s: start anchor tag, %2$s: end anchor tag */
								__( 'This booking was made using WooCommerce payments, view detail payment information %1$shere%2$s', 'wp-travel-engine' ),
								array(
									'a' => array(
										'href' => array(),
									),
								)
							),
							'<a href="' . esc_url( admin_url( "/post.php?post={$wc_order_id}&action=edit" ) ) . '">',
							'</a>'
						);
						?>
					</p>
				</div>
			<?php endif; ?>
			<ul class="wpte-list">
				<li>
					<b><?php esc_html_e( 'Payment ID', 'wp-travel-engine' ); ?></b>
					<span><a
							href="<?php echo esc_url( get_edit_post_link( $payment_id ) ); ?>"><?php echo esc_html( "#{$payment_id}" ); ?></a></span>
				</li>
				<li>
					<b><?php esc_html_e( 'Payment Status', 'wp-travel-engine' ); ?></b>
					<span style="text-transform:uppercase;">
					<div class="wpte-field">
						<input readonly type="text"
								data-attrib-name="<?php echo esc_attr( "payments[{$payment->ID}][payment_status]" ); ?>"
								value="<?php echo esc_html( $payment_status ); ?>" />
					</div>
				</span>
				</li>
				<li>
					<b><?php esc_html_e( 'Payment Gateway', 'wp-travel-engine' ); ?></b>
					<span>
					<div class="wpte-field">
						<input readonly type="text"
								data-attrib-name="<?php echo esc_attr( "payments[{$payment->ID}][payment_gateway]" ); ?>"
								value="<?php echo esc_html( $payment_gateway ); ?>" />
					</div>
				</span>
				</li>
				<li>
					<b><?php esc_html_e( 'Amount', 'wp-travel-engine' ); ?></b>
					<span>
					<div class="wpte-field">
						<?php
						/**
						 * Payment object.
						 *
						 * @var Payment $payment
						 */
						$payment = Payment::make( $payment_id );
						// if ( $payment->is_completed() ) {
						// $amount = $payment->get_amount();
						// } else {
							$amount = $payment->get_payable_amount();
						$currency   = $payment->get_payable_currency();
						// }
						?>
						<input readonly type="text"
								data-attrib-name="<?php echo esc_attr( "payments[{$payment->ID}][payable][amount]" ); ?>"
								data-attrib-value="<?php echo esc_attr( $amount ); ?>"
								value="<?php echo esc_attr( wte_get_formated_price( $amount, $currency, '', ! 1 ) ); ?>" />
					</div>
				</span>
				</li>
				<?php
				$gateway_response = $payment->get_gateway_response();
				if ( ! empty( $gateway_response ) ) : // ifpgr.
					if ( is_array( $gateway_response ) || is_object( $gateway_response ) ) {
						$gw_response = wp_json_encode( $gateway_response, JSON_PRETTY_PRINT );
					} else {
						$gw_response = $gateway_response;
					}
					?>
					<li>
						<b><?php esc_html_e( 'Gateway raw response', 'wp-travel-engine' ); ?></b>
						<span><a href="#" class="wte-onclick-toggler"
								data-target="#gateway_response-<?php echo esc_attr( $payment_id ); ?>"><?php esc_html_e( 'View response', 'wp-travel-engine' ); ?></a></span>
					</li>
					<li id="gateway_response-<?php echo esc_attr( $payment_id ); ?>" style="display:none;">
				<pre style="width:100%">
						<code class="wte-code" data-height="500"><?php echo esc_html( $gw_response ); ?></code>
					</pre>
					</li>
				<?php endif; // endifpgr. ?>
				<?php
				$last_updated = get_post_modified_time( 'G', ! 0, $payment_id );
				if ( empty( $last_updated ) ) {
					$last_updated = '-';
				} else {
					$last_updated = date_i18n( 'M j, Y h:i:s a', $last_updated );
				}
				?>
				<li>
					<b><?php esc_html_e( 'Last Updated', 'wp-travel-engine' ); ?></b>
					<span><?php echo esc_html( $last_updated ); ?></span>
				</li>
			</ul>
			<?php
		}
		?>
	</div>
	<div class="wpte-block-content">
		<h4 class="wpte-title"><?php esc_html_e( 'Payment info', 'wp-travel-engine' ); ?></h4>
		<ul class="wpte-list">
			<li>
				<b><?php esc_html_e( 'Total Cost', 'wp-travel-engine' ); ?></b>
				<span>
					<div class="wpte-field">
						<input readonly type="text" min="0" step=".01"
								data-attrib-type="number"
								data-attrib-value="<?php echo esc_attr( $booking_details->cart_info['total'] ); ?>"
								data-attrib-name="<?php echo esc_attr( 'cart_info[total]' ); ?>"
								value="<?php echo esc_attr( wte_get_formated_price( esc_html( $booking_details->cart_info['total'] ), $booking_details->cart_info['currency'], '', '' ) ); ?>" />
					</div>
				</span>
			</li>
			<li>
				<b><?php esc_html_e( 'Paid amount', 'wp-travel-engine' ); ?></b>
				<span>
					<div class="wpte-field">
						<input readonly type="text" min="0" step=".01" data-attrib-type="number"
								data-attrib-value="<?php echo esc_attr( $booking_details->paid_amount ); ?>"
								data-attrib-name="<?php echo esc_attr( 'paid_amount' ); ?>"
								value="<?php echo esc_attr( wte_get_formated_price( esc_html( $booking_details->paid_amount ), $booking_details->cart_info['currency'], '', '' ) ); ?>" />
					</div>
				</span>
			</li>
			<?php if ( + $booking_details->due_amount > 0 ) : ?>
				<li>
					<b><?php esc_html_e( 'Due amount', 'wp-travel-engine' ); ?></b>
					<span>
					<div class="wpte-field">
						<input readonly type="text" min="0" step=".01" data-attrib-type="number"
								data-attrib-value="<?php echo esc_attr( $booking_details->due_amount ); ?>"
								data-attrib-name="<?php echo esc_attr( 'due_amount' ); ?>"
								value="<?php echo esc_attr( wte_get_formated_price( esc_html( $booking_details->due_amount ), $booking_details->cart_info['currency'], '', '' ) ); ?>" />
					</div>
				</span>
				</li>
			<?php else : ?>
				<li>
					<b><?php esc_html_e( 'Due amount', 'wp-travel-engine' ); ?></b>
					<span>
					<div class="wpte-field">
						<input type="number" min="0" step=".1"
								name="<?php echo esc_attr( 'due_amount' ); ?>"
								value="0" />
					</div>
				</span>
				</li>
			<?php endif; ?>
			<!-- Currency -->
			<li class="show_on_edit" style="display:none;">
				<b><?php esc_html_e( 'Currency', 'wp-travel-engine' ); ?></b>
				<span>
					<div class="wpte-field">
						<input type="text"
								name="cart_info[currency]"
								value="<?php echo esc_attr( $booking_details->cart_info['currency'] ); ?>" />
					</div>
				</span>
			</li>
		</ul>
	</div>
</div>
