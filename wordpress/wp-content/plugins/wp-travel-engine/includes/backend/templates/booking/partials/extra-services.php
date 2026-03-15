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

if ( 'readonly' === $template_mode && ! ( $cart_line_items['extra_service'] ?? false ) ) {
	return;
}

// get extra services meta from trip id :trip_extra_services
$trip_settings       = get_post_meta( $booking->get_trip_id(), 'wp_travel_engine_setting', true );
$trip_extra_services = $trip_settings['trip_extra_services'] ?? array();

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
								<th style="width: 50%;"><?php echo __( 'SERVICE NAME', 'wp-travel-engine' ); ?></th>
								<th><?php echo __( 'QTY', 'wp-travel-engine' ); ?></th>
								<th style="width: 20%;"><?php echo __( 'COST', 'wp-travel-engine' ); ?></th>
							</tr>
						</thead>
						<tbody>
							<?php
							foreach ( $cart_line_items['extra_service'] as $line_item => $value ) :
								if ( $value['_class_name'] == 'WPTravelEngine\Core\Cart\Items\ExtraService' ) {
									if ( isset( $value['type'] ) ) {
										$service_type = $value['type'];
									} else {
										$service_type = get_extra_service_type( $value['label'], $trip_extra_services );
									}
								} else {
									continue;
								}
								?>
								<tr>
									<td><?php echo $line_item + 1; ?></td>
									<td>
										<?php echo esc_html( $value['label'] ); ?> <span class="wpte-tag"><?php echo esc_html( $service_type ); ?></span>
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