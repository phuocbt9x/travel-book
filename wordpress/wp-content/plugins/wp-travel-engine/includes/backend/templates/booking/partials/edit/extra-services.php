<?php
/**
 * Extra Services Details.
 *
 * @var array $cart_item
 * @var float $due_amount
 * @var float $paid_amount
 * @var \WPTravelEngine\Helpers\CartInfoParser $cart_info
 * @var \WPTravelEngine\Core\Models\Post\Booking $booking
 * @var array $pricing_arguments
 * @since 6.4.0
 */
// get extra services meta from trip id :trip_extra_services
$trip_settings       = get_post_meta( $booking->get_trip_id(), 'wp_travel_engine_setting', true );
$trip_extra_services = $trip_settings['trip_extra_services'] ?? array();

// Check if extra-services addon is active
$is_extra_services_active = wptravelengine_is_addon_active( 'extra-services' );
$readonly_attr            = ! $is_extra_services_active || 'readonly' === $template_mode ? 'readonly' : '';
$disabled_attr            = ! $is_extra_services_active || 'readonly' === $template_mode ? 'disabled' : '';

/**
 * Function to get extra service type by matching label
 */
if ( ! function_exists( 'get_extra_service_type' ) ) {
	function get_extra_service_type( $line_item_label, $trip_extra_services ) {
		foreach ( $trip_extra_services as $service ) {
			if ( isset( $service['options'] ) && is_array( $service['options'] ) ) {
				foreach ( $service['options'] as $option ) {
					if ( $option === $line_item_label ) {
						return $service['type'] ?? 'Default';
					}
				}
			}
		}
		return 'Default';
	}
}
?>

<div class="wpte-form-section" data-target-id="extra-services">
	<div class="wpte-accordion">
		<div class="wpte-accordion-header">
			<h3 class="wpte-accordion-title"><?php echo __( 'Extra Services Details', 'wp-travel-engine' ); ?></h3>
			<button type="button" class="wpte-accordion-toggle">
				<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
					<path d="M5 7.5L10 12.5L15 7.5" stroke="currentColor" stroke-width="1.66667" stroke-linecap="round" stroke-linejoin="round" />
				</svg>
			</button>
		</div>
		<div class="wpte-accordion-content">
			<div class="wpte-table-wrap">
				<table class="wpte-table">
					<thead>
						<tr>
							<th><?php echo __( 'SERVICE NAME', 'wp-travel-engine' ); ?></th>
							<th style="text-align: center;"><?php echo __( 'TYPE', 'wp-travel-engine' ); ?></th>
							<th style="text-align: center;"><?php echo __( 'QTY', 'wp-travel-engine' ); ?></th>
							<th style="text-align: center;"><?php echo __( 'COST', 'wp-travel-engine' ); ?></th>
							<th style="width: 60px;text-align: center;"><?php echo __( 'ACTION', 'wp-travel-engine' ); ?></th>
						</tr>
					</thead>
					<tbody data-extra-service-section="">
						<?php
						$extra_service_index = 0;
						if ( $cart_line_items['extra_service'] ?? false ) {
							foreach ( $cart_line_items['extra_service'] as $line_item => $value ) :
								if ( isset( $value['type'] ) ) {
									$service_type = $value['type'];
								} else {
									$service_type = get_extra_service_type( $value['label'], $trip_extra_services );
								}
								?>
								<tr>
									<td>
										<input type="text" name="line_items[extra_service][label][<?php echo $extra_service_index; ?>]" value="<?php echo esc_attr( $value['label'] ); ?>" class="wpte-checkout__input" data-parsley-required-message="This value is required" <?php echo $disabled_attr; ?>>
									</td>
									<td style="text-align: center;">
										<div class="wpte-field">
											<select name="line_items[extra_service][type][<?php echo $extra_service_index; ?>]" class="wpte-checkout__input" data-parsley-required-message="This value is required" <?php echo $disabled_attr; ?>>
												<option value="default" <?php echo $service_type == 'default' ? 'selected' : ''; ?>><?php echo __( 'Default', 'wp-travel-engine' ); ?></option>
												<option value="advanced" <?php echo $service_type == 'advanced' ? 'selected' : ''; ?>><?php echo __( 'Advanced', 'wp-travel-engine' ); ?></option>
											</select>
										</div>
									</td>
									<td style="text-align: center;">
										<input type="number" name="line_items[extra_service][quantity][<?php echo $extra_service_index; ?>]" value="<?php echo esc_attr( $value['quantity'] ); ?>" min="1" class="wpte-checkout__input" data-parsley-required-message="This value is required" <?php echo $disabled_attr; ?>>
									</td>
									<td style="text-align: center;">
										<input type="number" name="line_items[extra_service][price][<?php echo $extra_service_index; ?>]" value="<?php echo esc_attr( $value['price'] ); ?>" min="0" step="0.01" class="wpte-checkout__input wpte-cost-input" data-parsley-required-message="This value is required" data-parsley-type="number" data-parsley-pattern="^\d+(\.\d{1,2})?$" <?php echo $disabled_attr; ?>>
									</td>
									<td style="text-align: center;">
										<button class="wpte-button wpte-delete-button wpte-table-delete-row" type="button" <?php echo $disabled_attr; ?>>
											<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
												<path d="M13.3333 5V4.33333C13.3333 3.39991 13.3333 2.9332 13.1517 2.57668C12.9919 2.26308 12.7369 2.00811 12.4233 1.84832C12.0668 1.66666 11.6001 1.66666 10.6667 1.66666H9.33333C8.39991 1.66666 7.9332 1.66666 7.57668 1.84832C7.26308 2.00811 7.00811 2.26308 6.84832 2.57668C6.66667 2.9332 6.66667 3.39991 6.66667 4.33333V5M8.33333 9.58333V13.75M11.6667 9.58333V13.75M2.5 5H17.5M15.8333 5V14.3333C15.8333 15.7335 15.8333 16.4335 15.5608 16.9683C15.3212 17.4387 14.9387 17.8212 14.4683 18.0608C13.9335 18.3333 13.2335 18.3333 11.8333 18.3333H8.16667C6.76654 18.3333 6.06647 18.3333 5.53169 18.0608C5.06129 17.8212 4.67883 17.4387 4.43915 16.9683C4.16667 16.4335 4.16667 15.7335 4.16667 14.3333V5" stroke="#E84B4B" stroke-width="1.66667" stroke-linecap="round" stroke-linejoin="round"></path>
											</svg>
										</button>
									</td>
									<?php $total_value = isset( $value['total'] ) ? $value['total'] : $value['price'] * $value['quantity']; ?>
									<input type="hidden" name="line_items[extra_service][total][<?php echo $extra_service_index; ?>]" value="<?php echo esc_attr( $total_value ); ?>">
								</tr>
								<?php
								++$extra_service_index;
							endforeach;
						}
						?>
					</tbody>
				</table>
			</div>
			
			<?php if ( 'edit' === $template_mode && $is_extra_services_active ) : ?>
				<div style="padding:16px;">
					<button class="wpte-button wpte-link"
						data-type="add"
						data-template="extra-service-template"
						data-target="[data-extra-service-section]"
						data-line-item-template="cart-line-item"
						data-line-item-target="[data-line-item__extra_service_section]"
						>
						<?php echo __( '+ Add Extra Service', 'wp-travel-engine' ); ?>
					</button>
				</div>
			<?php endif; ?>
		</div>
	</div>
</div>

<script type="text/html" id="tmpl-extra-service-template">
	<tr>
		<td>
			<input type="text" name="line_items[extra_service][label][]" value="Service" class="wpte-checkout__input" placeholder="<?php echo __( 'Service', 'wp-travel-engine' ); ?>" data-parsley-required-message="This value is required">
		</td>
		<td style="text-align: center;">
			<div class="wpte-field">
				<select name="line_items[extra_service][type][]" class="wpte-checkout__input" data-parsley-required-message="This value is required">
					<option value="default"><?php echo __( 'Default', 'wp-travel-engine' ); ?></option>
					<option value="advanced"><?php echo __( 'Advanced', 'wp-travel-engine' ); ?></option>
				</select>
			</div>
		</td>
		<td style="text-align: center;">
			<input type="number" name="line_items[extra_service][quantity][]" value="1" class="wpte-checkout__input" placeholder="<?php echo __( 'Quantity', 'wp-travel-engine' ); ?>" data-parsley-required-message="This value is required">
		</td>
		<td style="text-align: center;">
			<input type="number" name="line_items[extra_service][price][]" value="" min="0" step="0.01" class="wpte-checkout__input wpte-cost-input" placeholder="0.00" data-parsley-required-message="This value is required" data-parsley-type="number" data-parsley-pattern="^\d+(\.\d{1,2})?$">
		</td>
		<td style="text-align: center;">
			<button class="wpte-button wpte-delete-button wpte-table-delete-row" type="button">
				<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
					<path d="M13.3333 5V4.33333C13.3333 3.39991 13.3333 2.9332 13.1517 2.57668C12.9919 2.26308 12.7369 2.00811 12.4233 1.84832C12.0668 1.66666 11.6001 1.66666 10.6667 1.66666H9.33333C8.39991 1.66666 7.9332 1.66666 7.57668 1.84832C7.26308 2.00811 7.00811 2.26308 6.84832 2.57668C6.66667 2.9332 6.66667 3.39991 6.66667 4.33333V5M8.33333 9.58333V13.75M11.6667 9.58333V13.75M2.5 5H17.5M15.8333 5V14.3333C15.8333 15.7335 15.8333 16.4335 15.5608 16.9683C15.3212 17.4387 14.9387 17.8212 14.4683 18.0608C13.9335 18.3333 13.2335 18.3333 11.8333 18.3333H8.16667C6.76654 18.3333 6.06647 18.3333 5.53169 18.0608C5.06129 17.8212 4.67883 17.4387 4.43915 16.9683C4.16667 16.4335 4.16667 15.7335 4.16667 14.3333V5" stroke="#E84B4B" stroke-width="1.66667" stroke-linecap="round" stroke-linejoin="round"></path>
				</svg>
			</button>
		</td>
		<input type="hidden" name="line_items[extra_service][total][]" value="">
	</tr>
</script>