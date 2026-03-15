<?php
/**
 * Travel Insurance Details.
 *
 * @var array $cart_item
 * @var float $due_amount
 * @var float $paid_amount
 * @var \WPTravelEngine\Helpers\CartInfoParser $cart_info
 * @var \WPTravelEngine\Core\Models\Post\Booking $booking
 * @var array $pricing_arguments
 * @since 6.4.0
 */

// Check if travel insurance addon is active
$is_travel_insurance_active = wptravelengine_is_addon_active( 'travel-insurance' );
$readonly_attr              = ! $is_travel_insurance_active || 'readonly' === $template_mode ? 'readonly' : '';
$disabled_attr              = ! $is_travel_insurance_active || 'readonly' === $template_mode ? 'disabled' : '';
?>

<div class="wpte-form-section" data-target-id="travel-insurance">
	<div class="wpte-accordion">
		<div class="wpte-accordion-header">
			<h3 class="wpte-accordion-title"><?php echo __( 'Travel Insurance Details', 'wp-travel-engine' ); ?></h3>
			<button type="button" class="wpte-accordion-toggle">
				<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
					<path d="M5 7.5L10 12.5L15 7.5" stroke="currentColor" stroke-width="1.66667" stroke-linecap="round" stroke-linejoin="round" />
				</svg>
			</button>
		</div>
		<div class="wpte-accordion-content">
			<?php
			$has_travel_insurance_line_items = ! empty( $cart_line_items['travel_insurance'] ) && is_array( $cart_line_items['travel_insurance'] );

			// Check if this is a new booking (no existing travel insurance data)
			$is_new_booking = empty( $booking->get_id() ) || $booking->post->post_status === 'auto-draft';
			$trip_id        = $cart_info->get_item()->get_trip_id();

			// Ensure travel_insurance key exists and is an array
			if ( ! isset( $cart_line_items['travel_insurance'] ) || ! is_array( $cart_line_items['travel_insurance'] ) ) {
				$cart_line_items['travel_insurance'] = array();
			}
			?>
			<div class="wpte-table-wrap">
				<table class="wpte-table">
					<thead>
						<tr>
							<th><?php echo __( 'PLAN', 'wp-travel-engine' ); ?></th>
							<th style="text-align: center;"><?php echo __( 'QTY', 'wp-travel-engine' ); ?></th>
							<th style="text-align: center;"><?php echo __( 'COST', 'wp-travel-engine' ); ?></th>
							<th style="width: 60px;text-align: center;"><?php echo __( 'ACTION', 'wp-travel-engine' ); ?></th>
						</tr>
					</thead>
					<tbody data-travel-insurance-section="">
						<?php
						$travel_insurance_index = 0;
						foreach ( $cart_line_items['travel_insurance'] as $travel_insurance_item ) {
							// Skip if not a valid travel insurance item
							if ( empty( $travel_insurance_item['label'] ) ) {
								continue;
							}
							?>
							<tr>
								<td>
									<input type="text" name="line_items[travel_insurance][label][<?php echo $travel_insurance_index; ?>]" value="<?php echo esc_attr( $travel_insurance_item['label'] ); ?>" class="wpte-checkout__input" data-parsley-required-message="This value is required" <?php echo $disabled_attr; ?>>
								</td>
								<td style="text-align: center;">
									<input type="number" name="line_items[travel_insurance][quantity][<?php echo $travel_insurance_index; ?>]" value="<?php echo esc_attr( $travel_insurance_item['quantity'] ); ?>" class="wpte-checkout__input" data-parsley-required-message="This value is required" <?php echo $disabled_attr; ?>>
								</td>
								<td style="text-align: center;">
									<input type="number" name="line_items[travel_insurance][price][<?php echo $travel_insurance_index; ?>]" value="<?php echo esc_attr( $travel_insurance_item['price'] ); ?>" min="0" step="0.01" class="wpte-checkout__input wpte-cost-input" data-parsley-required-message="This value is required" data-parsley-type="number" data-parsley-pattern="^\d+(\.\d{1,2})?$" <?php echo $readonly_attr; ?> <?php echo $disabled_attr; ?>>
								</td>
								<td style="text-align: center;">
									<input type="hidden" name="line_items[travel_insurance][total][<?php echo $travel_insurance_index; ?>]" value="<?php echo esc_attr( $travel_insurance_item['total'] ); ?>">
									<button class="wpte-button wpte-delete-button wpte-table-delete-row" type="button" <?php echo $disabled_attr; ?>>
										<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
											<path d="M13.3333 5V4.33333C13.3333 3.39991 13.3333 2.9332 13.1517 2.57668C12.9919 2.26308 12.7369 2.00811 12.4233 1.84832C12.0668 1.66666 11.6001 1.66666 10.6667 1.66666H9.33333C8.39991 1.66666 7.9332 1.66666 7.57668 1.84832C7.26308 2.00811 7.00811 2.26308 6.84832 2.57668C6.66667 2.9332 6.66667 3.39991 6.66667 4.33333V5M8.33333 9.58333V13.75M11.6667 9.58333V13.75M2.5 5H17.5M15.8333 5V14.3333C15.8333 15.7335 15.8333 16.4335 15.5608 16.9683C15.3212 17.4387 14.9387 17.8212 14.4683 18.0608C13.9335 18.3333 13.2335 18.3333 11.8333 18.3333H8.16667C6.76654 18.3333 6.06647 18.3333 5.53169 18.0608C5.06129 17.8212 4.67883 17.4387 4.43915 16.9683C4.16667 16.4335 4.16667 15.7335 4.16667 14.3333V5" stroke="#E84B4B" stroke-width="1.66667" stroke-linecap="round" stroke-linejoin="round"></path>
										</svg>
									</button>
								</td>
							</tr>
							<?php
							++$travel_insurance_index;
						}
						?>
					</tbody>
				</table>
			</div>

			<?php if ( 'edit' === $template_mode && $is_travel_insurance_active ) : ?>
				<div style="padding:16px;">
					<button class="wpte-button wpte-link"
						data-type="add"
						data-template="travel-insurance-template"
						data-target="[data-travel-insurance-section]"
						data-line-item-template="cart-line-item"
						data-line-item-target="[data-line-item__travel_insurance_section]">
						<?php echo __( '+ Add Travel Insurance', 'wp-travel-engine' ); ?>
					</button>
				</div>
			<?php endif; ?>

			<?php
			// Check for legacy travel insurance meta data (declined insurance or affiliate links)
			if ( ! $has_travel_insurance_line_items ) {
				// Check booking meta for travel insurance data
				$travel_insurance_meta = $booking->get_meta( 'wptravelengine_travel_insurance' );

				if ( $travel_insurance_meta ) {
					// Check if we have declined insurance with reason
					if ( isset( $travel_insurance_meta['follow_up_question'] ) &&
						isset( $travel_insurance_meta['follow_up_answer'] ) &&
						! empty( $travel_insurance_meta['follow_up_answer'] ) ) {

						// Show editable form for declined insurance
						?>
						<div class="wpte-table-wrap">
							<table class="wpte-table">
								<thead>
									<tr>
										<th><?php echo __( 'Question', 'wp-travel-engine' ); ?></th>
										<th><?php echo __( 'Answer', 'wp-travel-engine' ); ?></th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td><?php echo esc_html( $travel_insurance_meta['opt_in_question'] ?? 'Do you need Travel Insurance?' ); ?></td>
										<td>
											<select name="travel_insurance_meta[follow_up_question]" class="wpte-checkout__input">
												<option value="yes" <?php selected( $travel_insurance_meta['follow_up_question'], 'yes' ); ?>><?php echo __( 'Yes', 'wp-travel-engine' ); ?></option>
												<option value="no" <?php selected( $travel_insurance_meta['follow_up_question'], 'no' ); ?>><?php echo __( 'No', 'wp-travel-engine' ); ?></option>
											</select>
										</td>
									</tr>
									<tr>
										<td><?php echo __( 'Reason Provided', 'wp-travel-engine' ); ?></td>
										<td>
											<textarea name="travel_insurance_meta[follow_up_answer]" class="wpte-checkout__input" rows="3" placeholder="<?php echo __( 'Enter reason for declining travel insurance...', 'wp-travel-engine' ); ?>"><?php echo esc_textarea( $travel_insurance_meta['follow_up_answer'] ); ?></textarea>
										</td>
									</tr>
								</tbody>
							</table>
						</div>
						
						<!-- Hidden fields to preserve other meta data -->
						<input type="hidden" name="travel_insurance_meta[travel_insurance_plans]" value="<?php echo esc_attr( $travel_insurance_meta['travel_insurance_plans'] ?? '' ); ?>">
						<input type="hidden" name="travel_insurance_meta[travel_insurance_affiliate_link]" value="<?php echo esc_attr( $travel_insurance_meta['travel_insurance_affiliate_link'] ?? '' ); ?>">
						<input type="hidden" name="travel_insurance_meta[travel_insurance_affiliate_label]" value="<?php echo esc_attr( $travel_insurance_meta['travel_insurance_affiliate_label'] ?? '' ); ?>">
						<input type="hidden" name="travel_insurance_meta[opt_in_question]" value="<?php echo esc_attr( $travel_insurance_meta['opt_in_question'] ?? '' ); ?>">
						<?php
					}
					// Check if we have affiliate link data
					elseif ( isset( $travel_insurance_meta['travel_insurance_affiliate_link'] ) &&
							! empty( $travel_insurance_meta['travel_insurance_affiliate_link'] ) ) {

						// Show editable form for affiliate link
						?>
						<div class="wpte-table-wrap">
							<table class="wpte-table">
								<tbody>
									<tr>
										<td><?php echo esc_html( 'Travel Insurance Affiliate Link' ); ?></td>
										<td>
											<input type="url" name="travel_insurance_meta[travel_insurance_affiliate_link]" value="<?php echo esc_attr( $travel_insurance_meta['travel_insurance_affiliate_link'] ); ?>" class="wpte-checkout__input" placeholder="<?php echo __( 'Travel Insurance Link', 'wp-travel-engine' ); ?>">
										</td>
									</tr>
								</tbody>
							</table>
						</div>
						
						<!-- Hidden fields to preserve other meta data -->
						<input type="hidden" name="travel_insurance_meta[follow_up_question]" value="<?php echo esc_attr( $travel_insurance_meta['follow_up_question'] ?? '' ); ?>">
						<input type="hidden" name="travel_insurance_meta[follow_up_answer]" value="<?php echo esc_attr( $travel_insurance_meta['follow_up_answer'] ?? '' ); ?>">
						<input type="hidden" name="travel_insurance_meta[travel_insurance_plans]" value="<?php echo esc_attr( $travel_insurance_meta['travel_insurance_plans'] ?? '' ); ?>">
						<input type="hidden" name="travel_insurance_meta[travel_insurance_affiliate_label]" value="<?php echo esc_attr( $travel_insurance_meta['travel_insurance_affiliate_label'] ?? '' ); ?>">
						<input type="hidden" name="travel_insurance_meta[opt_in_question]" value="<?php echo esc_attr( $travel_insurance_meta['opt_in_question'] ?? '' ); ?>">
						<?php
					}
				}
			}

			// Show plan selection for new bookings
			if ( $is_new_booking && ! empty( $available_plans ) ) {
				?>
				<div class="wpte-table-wrap">
					<h4><?php echo __( 'Available Travel Insurance Plans', 'wp-travel-engine' ); ?></h4>
					<table class="wpte-table">
						<thead>
							<tr>
								<th><?php echo __( 'Plan Name', 'wp-travel-engine' ); ?></th>
								<th><?php echo __( 'Coverage', 'wp-travel-engine' ); ?></th>
								<th><?php echo __( 'Price Type', 'wp-travel-engine' ); ?></th>
								<th style="text-align: center;"><?php echo __( 'Action', 'wp-travel-engine' ); ?></th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ( $available_plans as $plan ) : ?>
								<tr>
									<td>
										<strong><?php echo esc_html( $plan['title'] ?? '' ); ?></strong>
									</td>
									<td>
										<?php echo esc_html( $plan['coverage'] ?? '' ); ?>
									</td>
									<td>
										<?php echo esc_html( ucfirst( $plan['pricing_type'] ?? 'per_person' ) ); ?>
									</td>
									<td style="text-align: center;">
										<button type="button" class="wpte-button wpte-button-primary add-travel-insurance-plan" 
												data-plan-id="<?php echo esc_attr( $plan['id'] ?? '' ); ?>"
												data-plan-title="<?php echo esc_attr( $plan['title'] ?? '' ); ?>"
												data-plan-coverage="<?php echo esc_attr( $plan['coverage'] ?? '' ); ?>"
												data-plan-pricing-type="<?php echo esc_attr( $plan['pricing_type'] ?? 'per_person' ); ?>">
											<?php echo __( 'Add Plan', 'wp-travel-engine' ); ?>
										</button>
									</td>
								</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
				</div>
				<?php
			}
			?>
		</div>
	</div>
</div>

<script type="text/html" id="tmpl-travel-insurance-template">
	<tr>
		<td>
			<input type="text" name="line_items[travel_insurance][label][]" value="Plan" class="wpte-checkout__input" placeholder="<?php echo __( 'Plan', 'wp-travel-engine' ); ?>" data-parsley-required-message="This value is required">
		</td>
		<td style="text-align: center;">
			<input type="number" name="line_items[travel_insurance][quantity][]" value="1" class="wpte-checkout__input" placeholder="<?php echo __( 'Quantity', 'wp-travel-engine' ); ?>" data-parsley-required-message="This value is required">
		</td>
		<td style="text-align: center;">
			<input type="number" name="line_items[travel_insurance][price][]" value="" min="0" step="0.01" class="wpte-checkout__input wpte-cost-input" placeholder="0.00" data-parsley-required-message="This value is required" data-parsley-type="number" data-parsley-pattern="^\d+(\.\d{1,2})?$">
		</td>
		<td style="text-align: center;">
			<input type="hidden" name="line_items[travel_insurance][total][]" value="">
			<button class="wpte-button wpte-delete-button wpte-table-delete-row" type="button">
				<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
					<path d="M13.3333 5V4.33333C13.3333 3.39991 13.3333 2.9332 13.1517 2.57668C12.9919 2.26308 12.7369 2.00811 12.4233 1.84832C12.0668 1.66666 11.6001 1.66666 10.6667 1.66666H9.33333C8.39991 1.66666 7.9332 1.66666 7.57668 1.84832C7.26308 2.00811 7.00811 2.26308 6.84832 2.57668C6.66667 2.9332 6.66667 3.39991 6.66667 4.33333V5M8.33333 9.58333V13.75M11.6667 9.58333V13.75M2.5 5H17.5M15.8333 5V14.3333C15.8333 15.7335 15.8333 16.4335 15.5608 16.9683C15.3212 17.4387 14.9387 17.8212 14.4683 18.0608C13.9335 18.3333 13.2335 18.3333 11.8333 18.3333H8.16667C6.76654 18.3333 6.06647 18.3333 5.53169 18.0608C5.06129 17.8212 4.67883 17.4387 4.43915 16.9683C4.16667 16.4335 4.16667 15.7335 4.16667 14.3333V5" stroke="#E84B4B" stroke-width="1.66667" stroke-linecap="round" stroke-linejoin="round"></path>
				</svg>
			</button>
		</td>
	</tr>
</script>

