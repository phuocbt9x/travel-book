<?php
/**
 * Booking Summary Data.
 *
 * @var array $cart_item
 * @var float $due_amount
 * @var float $paid_amount
 * @var CartInfoParser $cart_info
 * @var Booking $booking
 * @var array $pricing_arguments
 * @since 6.4.0
 */

?>

<div class="wpte-booking-summary">
	<h5 class="wpte-booking-summary-title"><?php echo __( 'Booking Summary', 'wp-travel-engine' ); ?></h5>
	<div class="wpte-booking-summary-table-wrap">
		<table class="wpte-booking-summary-table">
			<tbody>
				<?php
				if ( $cart_info->get_item()->get_line_items() ?? false ) {
					foreach ( $cart_info->get_item()->get_line_items() as $item_type => $line_items ) :
						$line_item_group_title = apply_filters( 'wptravelengine_booking_line_item_group_title', $item_type, $line_items );
						?>
						<tr class="title">
							<td colspan="2"><strong><?php echo esc_html( $line_item_group_title ); ?></strong></td>
						</tr>
						<?php
						foreach ( $line_items as $line_item ) {
							$quantity = (float) $line_item['quantity'] ?? 0;
							$price    = (float) $line_item['price'] ?? 0;
							$total    = (float) ( isset( $line_item['total'] ) && $line_item['total'] > 0 ? $line_item['total'] : $price * $quantity );
							printf(
								'<tr><td class="pricing-details">%1$s: %2$d x %3$s %4$s</td><td class="pricing-total"><b>%5$s</b</td></tr>',
								esc_html( $line_item['label'] ?? '' ),
								esc_html( $quantity ?? 0 ),
								wptravelengine_the_price( $price, false, $pricing_arguments ),
								( $item_type === 'pricing_category' && isset( $line_item['pricingType'] ) ) ? '/ ' . wptravelengine_get_pricing_type( false, $line_item['pricingType'] )['label'] ?? '' : '',
								wptravelengine_the_price( $total, false, $pricing_arguments ),
							);
						}
					endforeach;
				}
				?>
				<tr class="wpte-booking-subtotal">
					<td><strong><?php esc_html_e( 'Subtotal', 'wp-travel-engine' ); ?></strong></td>
					<td>
						<strong>
							<?php
							wptravelengine_the_price( $cart_info->get_totals( 'subtotal' ) ?? 0, true, $pricing_arguments );
							?>
						</strong>
					</td>
				</tr>
				<?php
				if ( ! empty( $cart_info->get_deductible_items() ) ) {
					foreach ( $cart_info->get_deductible_items() as $line_item ) {
						printf(
							'<tr class="wpte-booking-discount"><td>%1$s</td><td class="pricing-total"><b>-%2$s</b</td></tr>',
							esc_html( $line_item['label'] ?? '' ),
							wptravelengine_the_price( $line_item['value'] && $line_item['value'] > 0 ? $line_item['value'] : $cart_info->get_totals( 'total_' . $line_item['name'] ) ?? 0, false, $pricing_arguments )
						);
					}
				}

				if ( ! empty( $cart_info->get_fees() ) ) {
					foreach ( $cart_info->get_fees() as $line_item ) {
						printf(
							'<tr class="wpte-booking-tax"><td>%1$s</td><td class="pricing-total"><b>+%2$s</b</td></tr>',
							esc_html( $line_item['label'] ?? '' ),
							wptravelengine_the_price( $line_item['value'] && $line_item['value'] > 0 ? $line_item['value'] : $cart_info->get_totals( 'total_' . $line_item['name'] ) ?? 0, false, $pricing_arguments ),
							$line_item['name']
						);
					}
				}
				?>
				<tr class="wpte-booking-total">
					<td><strong><?php esc_html_e( 'Total', 'wp-travel-engine' ); ?></strong></td>
					<td class="amount-column">
						<strong><?php wptravelengine_the_price( $cart_info->get_totals( 'total' ) ?? 0, true, $pricing_arguments ); ?></strong>
					</td>
				</tr>
				<tr class="wpte-booking-paid">
					<td><strong><?php esc_html_e( 'Amount Paid', 'wp-travel-engine' ); ?></strong></td>
					<td class="amount-column">
						<strong>
							<span class="amount-paid">-
								<?php
								wptravelengine_the_price( $booking->get_total_paid_amount(), true, $pricing_arguments );
								// wptravelengine_the_price( $booking->get_meta('paid_amount'), true, $pricing_arguments );
								?>
							</span>
						</strong>
					</td>
				</tr>
				<tr class="wpte-booking-due">
					<td><strong><?php esc_html_e( 'Amount Due', 'wp-travel-engine' ); ?></strong></td>
					<td class="amount-column">
						<strong>
							<?php
							wptravelengine_the_price( $booking->get_total_due_amount(), true, $pricing_arguments );
							// TODO: remove this after well testing
							// wptravelengine_the_price( $cart_info->get_totals( 'due_total' ), true, $pricing_arguments );
							// wptravelengine_the_price( $booking->get_meta('due_amount'), true, $pricing_arguments );
							?>
						</strong>
					</td>
				</tr>
			</tbody>
		</table>
	</div>
</div>