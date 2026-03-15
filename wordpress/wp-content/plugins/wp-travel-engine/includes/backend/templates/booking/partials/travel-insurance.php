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
 * @since 6.7.0
 */
$has_travel_insurance = $cart_line_items['travel_insurance'] ?? false;

$inner_content = '';
if ( ! $has_travel_insurance ) {
	ob_start();
	// Check if this is a new booking (no existing travel insurance data)
	$is_new_booking = empty( $booking->get_id() ) || $booking->post->post_status === 'auto-draft';
	$trip_id        = $cart_info->get_item()->get_trip_id();

	// Get travel insurance plans for new bookings
	$available_plans = array();
	if ( $is_new_booking && $trip_id ) {
		$trip_meta       = get_post_meta( $trip_id, 'travel_insurance', true );
		$global_settings = get_option( 'wptravelengine_travel_insurance', array() );

		// Get plans from trip meta or global settings
		if ( ! empty( $trip_meta['plans'] ) && is_array( $trip_meta['plans'] ) ) {
			$available_plans = $trip_meta['plans'];
		} elseif ( ! empty( $global_settings['plans'] ) && is_array( $global_settings['plans'] ) ) {
			$available_plans = $global_settings['plans'];
		}
	}

	// Check booking meta for travel insurance data
	$travel_insurance_meta = $booking->get_meta( 'wptravelengine_travel_insurance' );

	if ( $travel_insurance_meta ) {
		// Check if we have declined insurance with reason
		if ( isset( $travel_insurance_meta['follow_up_question'] ) &&
			isset( $travel_insurance_meta['follow_up_answer'] ) &&
			! empty( $travel_insurance_meta['follow_up_answer'] ) ) {

			// Show the table with 2 rows for declined insurance
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
							<td><?php echo esc_html( $travel_insurance_meta['follow_up_question'] ); ?></td>
						</tr>
						<tr>
							<td><?php echo __( 'Reason Provided', 'wp-travel-engine' ); ?></td>
							<td><?php echo esc_html( $travel_insurance_meta['follow_up_answer'] ); ?></td>
						</tr>
					</tbody>
				</table>
			</div>
			<?php
		}
		// Check if we have affiliate link data
		elseif ( isset( $travel_insurance_meta['travel_insurance_affiliate_link'] ) &&
				! empty( $travel_insurance_meta['travel_insurance_affiliate_link'] ) ) {

			// Show the table with 1 row for affiliate link
			?>
			<div class="wpte-table-wrap">
				<table class="wpte-table">
					
					<tbody>
					<tr>
							<td><?php echo esc_html( 'Travel Insurance Affiliate Link' ); ?></td>
							<td><?php echo esc_html( $travel_insurance_meta['travel_insurance_affiliate_link'] ); ?></td>
						</tr>
					</tbody>
				</table>
			</div>
			<?php
		}
	}

	// Show available plans for new bookings
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
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
		<?php
	}
	$inner_content = ob_get_clean();
}

if ( ! $has_travel_insurance && empty( $inner_content ) ) {
	return;
}

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

			// If we have travel insurance line items, show the regular table
			if ( $has_travel_insurance ) {
				?>
				<div class="wpte-table-wrap">
					<table class="wpte-table">
						<thead>
							<tr>
								<th style="width: 0%;"><?php echo __( 'S.N', 'wp-travel-engine' ); ?></th>
								<th style="width: 50%;"><?php echo __( 'PLAN NAME', 'wp-travel-engine' ); ?></th>
								<th><?php echo __( 'QTY', 'wp-travel-engine' ); ?></th>
								<th style="width: 20%;"><?php echo __( 'COST', 'wp-travel-engine' ); ?></th>
							</tr>
						</thead>
						<tbody>
							<?php
							foreach ( $cart_line_items['travel_insurance'] as $line_item => $value ) :
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
				<?php
			} else {
				echo $inner_content;
			}
			?>
		</div>
	</div>
</div>