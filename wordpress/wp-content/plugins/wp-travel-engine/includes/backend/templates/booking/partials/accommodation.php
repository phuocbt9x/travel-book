<?php
/**
 * Accommodation Details.
 *
 * @var array $cart_item
 * @var float $due_amount
 * @var float $paid_amount
 * @var \WPTravelEngine\Helpers\CartInfoParser $cart_info
 * @var \WPTravelEngine\Core\Models\Post\Booking $booking
 * @var array $pricing_arguments
 * @since 6.4.0
 */

if ( ! ( $cart_line_items['accommodation'] ?? false ) ) {
	return;
}
?>

<div class="wpte-form-section" data-target-id="accommodation">
	<div class="wpte-accordion">
		<div class="wpte-accordion-header">
			<h3 class="wpte-accordion-title"><?php echo __( 'Accommodation Details', 'wp-travel-engine' ); ?></h3>
			<button type="button" class="wpte-accordion-toggle">
				<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
					<path d="M5 7.5L10 12.5L15 7.5" stroke="currentColor" stroke-width="1.66667" stroke-linecap="round"
						stroke-linejoin="round" />
				</svg>
			</button>
		</div>
		<div class="wpte-accordion-content">
				<div class="wpte-table-wrap">
					<table class="wpte-table">
						<thead>
							<tr>
								<th style="width: 0%;"><?php echo __( 'S.N', 'wp-travel-engine' ); ?></th>
								<th style="width: 50%;"><?php echo __( 'ROOM TYPE', 'wp-travel-engine' ); ?></th>
								<th><?php echo __( 'QTY', 'wp-travel-engine' ); ?></th>
								<th style="width: 20%;"><?php echo __( 'COST', 'wp-travel-engine' ); ?></th>
							</tr>
						</thead>
						<tbody>
							<?php
							foreach ( $cart_line_items['accommodation'] as $line_item => $value ) :
								?>
										<tr>
											<td><?php echo $line_item + 1; ?></td>
											<td>
												<?php echo esc_html( $value['label'] ); ?>
											</td>
											<td>
												<?php echo esc_html( $value['quantity'] ); ?>
											</td>
											<td>
												<?php echo esc_html( $value['price'] ); ?>
											</td>
										</tr>
									<?php
								endforeach;
							?>
						</tbody>
					</table>
				</div>
		</div>
	</div>
</div>