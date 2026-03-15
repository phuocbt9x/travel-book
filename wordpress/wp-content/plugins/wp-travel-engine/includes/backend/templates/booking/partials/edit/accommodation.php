<?php
/**
 * Booking Summary Data.
 *
 * @var array $cart_item
 * @var float $due_amount
 * @var float $paid_amount
 * @var \WPTravelEngine\Helpers\CartInfoParser $cart_info
 * @var \WPTravelEngine\Core\Models\Post\Booking $booking
 * @var array $pricing_arguments
 * @since 6.4.0
 */

// Check if accommodation addon is active
$is_accommodation_active = wptravelengine_is_addon_active( 'accommodation' );
$readonly_attr           = ! $is_accommodation_active || 'readonly' === $template_mode ? 'readonly' : '';
$disabled_attr           = ! $is_accommodation_active || 'readonly' === $template_mode ? 'disabled' : '';
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
							<th><?php echo __( 'ROOM TYPE', 'wp-travel-engine' ); ?></th>
							<th style="text-align: center;"><?php echo __( 'QTY', 'wp-travel-engine' ); ?></th>
							<th style="text-align: center;"><?php echo __( 'COST', 'wp-travel-engine' ); ?></th>
							<th style="width: 60px;text-align: center;"><?php echo __( 'ACTION', 'wp-travel-engine' ); ?></th>
						</tr>
					</thead>
					<tbody data-accommodation-section="">
						<?php
						if ( ! empty( $cart_line_items['accommodation'] ) && is_array( $cart_line_items['accommodation'] ) ) {
							$accommodation_index = 0;
							foreach ( $cart_line_items['accommodation'] as $accommodation_item ) {
								// Skip if not a valid accommodation item
								if ( empty( $accommodation_item['label'] ) || $accommodation_item['_class_name'] !== 'Accommodation\Backend\Accommodation' ) {
									continue;
								}
								?>
								<tr>
									<td>
										<input type="text"
											name="line_items[accommodation][label][<?php echo $accommodation_index; ?>]"
											value="<?php echo esc_attr( $accommodation_item['label'] ); ?>"
											class="wpte-checkout__input" data-parsley-required-message="This value is required"
											<?php echo $disabled_attr; ?>>
									</td>
									<td style="text-align: center;">
										<input type="number"
											name="line_items[accommodation][quantity][<?php echo $accommodation_index; ?>]"
											value="<?php echo esc_attr( $accommodation_item['quantity'] ); ?>" min="1"
											class="wpte-checkout__input" data-parsley-required-message="This value is required"
											<?php echo $disabled_attr; ?>>
									</td>
									<td style="text-align: center;">
										<input type="number"
											name="line_items[accommodation][price][<?php echo $accommodation_index; ?>]"
											value="<?php echo esc_attr( $accommodation_item['price'] ); ?>" min="0" step="0.01"
											class="wpte-checkout__input wpte-cost-input"
											data-parsley-required-message="This value is required" data-parsley-type="number"
											data-parsley-pattern="^\d+(\.\d{1,2})?$" <?php echo $disabled_attr; ?>>
									</td>
									<td style="text-align: center;">
										<input type="hidden"
											name="line_items[accommodation][total][<?php echo $accommodation_index; ?>]"
											value="<?php echo esc_attr( $accommodation_item['total'] ); ?>">
										<button class="wpte-button wpte-delete-button wpte-table-delete-row" type="button" <?php echo $disabled_attr; ?>>
											<svg width="20" height="20" viewBox="0 0 20 20" fill="none"
												xmlns="http://www.w3.org/2000/svg">
												<path
													d="M13.3333 5V4.33333C13.3333 3.39991 13.3333 2.9332 13.1517 2.57668C12.9919 2.26308 12.7369 2.00811 12.4233 1.84832C12.0668 1.66666 11.6001 1.66666 10.6667 1.66666H9.33333C8.39991 1.66666 7.9332 1.66666 7.57668 1.84832C7.26308 2.00811 7.00811 2.26308 6.84832 2.57668C6.66667 2.9332 6.66667 3.39991 6.66667 4.33333V5M8.33333 9.58333V13.75M11.6667 9.58333V13.75M2.5 5H17.5M15.8333 5V14.3333C15.8333 15.7335 15.8333 16.4335 15.5608 16.9683C15.3212 17.4387 14.9387 17.8212 14.4683 18.0608C13.9335 18.3333 13.2335 18.3333 11.8333 18.3333H8.16667C6.76654 18.3333 6.06647 18.3333 5.53169 18.0608C5.06129 17.8212 4.67883 17.4387 4.43915 16.9683C4.16667 16.4335 4.16667 15.7335 4.16667 14.3333V5"
													stroke="#E84B4B" stroke-width="1.66667" stroke-linecap="round"
													stroke-linejoin="round"></path>
											</svg>
										</button>
									</td>
									<input type="hidden"
										name="line_items[accommodation][total][<?php echo $accommodation_index; ?>]"
										value="<?php echo esc_attr( $accommodation_item['total'] ); ?>">
								</tr>
								<?php
								++$accommodation_index;
							}
						}
						?>
					</tbody>
				</table>
			</div>

			<?php if ( 'edit' === $template_mode && $is_accommodation_active ) : ?>
				<div style="padding:16px;">
					<button class="wpte-button wpte-link" data-type="add" data-template="accommodation-template"
						data-target="[data-accommodation-section]" data-line-item-template="cart-line-item"
						data-line-item-target="[data-line-item__accommodation_section]">
						<?php echo __( '+ Add Accommodation', 'wp-travel-engine' ); ?>
					</button>
				</div>
			<?php endif; ?>
		</div>
	</div>
</div>

<script type="text/html" id="tmpl-accommodation-template">
	<tr>
		<td>
			<input type="text" name="line_items[accommodation][label][]" value="Room" class="wpte-checkout__input" placeholder="<?php echo __( 'Room Type', 'wp-travel-engine' ); ?>" data-parsley-required-message="This value is required">
		</td>
		<td style="text-align: center;">
			<input type="number" name="line_items[accommodation][quantity][]" value="1" class="wpte-checkout__input" placeholder="<?php echo __( 'Quantity', 'wp-travel-engine' ); ?>" data-parsley-required-message="This value is required">
		</td>
		<td style="text-align: center;">
			<input type="number" name="line_items[accommodation][price][]" value="" min="0" step="0.01" class="wpte-checkout__input wpte-cost-input" placeholder="0.00" data-parsley-required-message="This value is required" data-parsley-type="number" data-parsley-pattern="^\d+(\.\d{1,2})?$">
		</td>
		<td style="text-align: center;">
			<input type="hidden" name="line_items[accommodation][total][]" value="">
			<button class="wpte-button wpte-delete-button wpte-table-delete-row" type="button">
				<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
					<path d="M13.3333 5V4.33333C13.3333 3.39991 13.3333 2.9332 13.1517 2.57668C12.9919 2.26308 12.7369 2.00811 12.4233 1.84832C12.0668 1.66666 11.6001 1.66666 10.6667 1.66666H9.33333C8.39991 1.66666 7.9332 1.66666 7.57668 1.84832C7.26308 2.00811 7.00811 2.26308 6.84832 2.57668C6.66667 2.9332 6.66667 3.39991 6.66667 4.33333V5M8.33333 9.58333V13.75M11.6667 9.58333V13.75M2.5 5H17.5M15.8333 5V14.3333C15.8333 15.7335 15.8333 16.4335 15.5608 16.9683C15.3212 17.4387 14.9387 17.8212 14.4683 18.0608C13.9335 18.3333 13.2335 18.3333 11.8333 18.3333H8.16667C6.76654 18.3333 6.06647 18.3333 5.53169 18.0608C5.06129 17.8212 4.67883 17.4387 4.43915 16.9683C4.16667 16.4335 4.16667 15.7335 4.16667 14.3333V5" stroke="#E84B4B" stroke-width="1.66667" stroke-linecap="round" stroke-linejoin="round"></path>
				</svg>
			</button>
		</td>
	</tr>
</script>