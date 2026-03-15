<?php
/**
 * Booking Summary.
 *
 * @var \WPTravelEngine\Helpers\CartInfoParser $cart_info
 * @var \WPTravelEngine\Core\Models\Post\Booking $booking
 * @var array $pricing_arguments
 * @since 6.7.0
 */
global $wte_cart;

$excl              = $wte_cart->get_exclusion_label( $cart_info->get_fees() );
$payments_total    = $booking->get_payments_data()['totals'] ?? array();
$cart_line_items ??= $cart_info->get_item()->get_line_items();
?>

<div class="wpte-booking-summary">
	<h5 class="wpte-booking-summary-title"><?php echo __( 'Booking Summary', 'wp-travel-engine' ); ?></h5>
	<div class="wpte-booking-summary-table-wrap">
		<table class="wpte-booking-summary-table">
			<tbody>
			<?php
			if ( $cart_line_items ) {
				// Define the desired order for line item types.
				$item_type_order = array(
					'pricing_category' => 1,
					'accommodation'    => 2,
					'extra_service'    => 3,
					'pickup_point'     => 4,
					'travel_insurance' => 5,
				);

				// Sort cart_line_items by the defined order
				uksort(
					$cart_line_items,
					function ( $a, $b ) use ( $item_type_order ) {
						$a_order = $item_type_order[ $a ] ?? 999;
						$b_order = $item_type_order[ $b ] ?? 999;
						return $a_order <=> $b_order;
					}
				);

				foreach ( $cart_line_items as $item_type => $line_items ) :
					$line_item_group_title = apply_filters( 'wptravelengine_booking_line_item_group_title', $item_type, $line_items );
					if ( empty( $line_items ) ) {
						continue;
					}
					?>
					<tr class="title">
						<td colspan="1"><strong><?php echo esc_html( $line_item_group_title ); ?></strong></td>
					</tr>
					<?php
					foreach ( $line_items as $line_item ) {
						$quantity = (float) $line_item['quantity'] ?? 0;
						$price    = (float) $line_item['price'] ?? 0;
						$total    = (float) ( isset( $line_item['total'] ) && $line_item['total'] > 0 ? $line_item['total'] : $price * $quantity );
						printf(
							'<tr><td colspan="2" class="pricing-details"><span class="wpte-line-item-label">%1$s: <span class="wpte-line-item-quantity">%2$d x %3$s %4$s</span></span></td><td class="pricing-total"><b>%5$s</b</td></tr>',
							esc_html( $line_item['label'] ?? '' ),
							esc_html( $quantity ?? 0 ),
							wptravelengine_the_price( $price, false, $pricing_arguments ),
							( $item_type === 'pricing_category' && isset( $line_item['pricingType'] ) ) ? '/ ' . wptravelengine_get_pricing_type( false, $line_item['pricingType'] )['label'] ?? '' : '',
							wptravelengine_the_price( $total, false, $pricing_arguments ),
						);
					}
				endforeach;
			}

				$deductible_items = $cart_info->get_deductible_items() ?? array();
			if ( $deductible_items ) {
				foreach ( $deductible_items as $line_item ) {
					printf(
						'<tr class="wpte-booking-discount"><td colspan="2">%1$s</td><td class="pricing-total"><b>-%2$s</b</td></tr>',
						esc_html( $line_item['label'] ?? '' ),
						wptravelengine_the_price( $line_item['value'] && $line_item['value'] > 0 ? $line_item['value'] : $cart_info->get_totals( 'total_' . $line_item['name'] ) ?? 0, false, $pricing_arguments )
					);
				}
			}
			?>
				<tr class="wpte-booking-total-separator">
					<td colspan="3"></td>
				</tr>
				<tr class="wpte-booking-total">
					<td colspan="2"><strong><?php esc_html_e( 'Total', 'wp-travel-engine' ); ?></strong></td>
					<td class="amount-column">
						<strong><?php wptravelengine_the_price( $payments_total['total_exclusive'] ?? 0, true, $pricing_arguments ); ?></strong>
					</td>
				</tr>
				<?php if ( ( $payments_total['due_exclusive'] ?? 0 ) > 0 ) : ?>
					<tr class="wpte-booking-due">
						<td colspan="2">
							<?php printf( '<strong>%s</strong>%s', __( 'Amount Due', 'wp-travel-engine' ), $excl ? sprintf( ' (excl. %s)', $excl ) : '' ); ?>
						</td>
						<td class="amount-column">
							<strong>
								<?php wptravelengine_the_price( $payments_total['due_exclusive'], true, $pricing_arguments ); ?>
							</strong>
						</td>
					</tr>
				<?php endif; ?>
			</tbody>
		</table>
	</div>
	<?php
	/**
	 * @since 6.7.1
	 * @description This is the action for the after booking summary totals.
	 * @param array $args The arguments for the action.
	 * @return void
	 */
	do_action( 'wptravelengine_after_booking_summary_totals', $args );
	?>
</div>

<?php wptravelengine_get_admin_template( 'booking/partials/payment-summary-items.php', array( 'payments_data' => $booking->get_payments_data() ) ); ?>
<?php wptravelengine_get_admin_template( 'booking/partials/payment-summary-total.php' ); ?>