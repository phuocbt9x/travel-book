<?php

/**
 * Booking Details Metabox Content.
 */

$booking_details       = null;
$traveller_information = get_post_meta( $post->ID, 'wp_travel_engine_placeorder_setting', true );
$personal_options      = isset( $traveller_information['place_order'] ) ? $traveller_information['place_order'] : array();
$traveller_information = get_post_meta( $post->ID, 'wptravelengine_travelers_details', true );
$emergency_contact     = get_post_meta( $post->ID, 'wptravelengine_emergency_details', true );
/** @var array $_args */
extract( $_args );

if ( is_null( $booking_details ) ) {
	return;
}
?>
<div id="wptravelengine-booking-details">
	<!-- .wpte-page-header -->
	<header class="wpte-page-header">
		<button class="wpte-page-back-button">
			<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
				<path d="M19 12H5M5 12L12 19M5 12L12 5" stroke="currentColor" stroke-width="2" stroke-linecap="round"
					stroke-linejoin="round" />
			</svg>
		</button>
		<h1><?php echo __( 'Sophia Isabella Bennett - #24', 'wp-travel-engine' ); ?></h1>
		<div class="wpte-page-header-content">
			<div class="wpte-tags-wrap">
				<span class="wpte-tag warning"><?php echo __( 'Pending', 'wp-travel-engine' ); ?></span>
				<span class="wpte-tag success"><?php echo __( 'Paid', 'wp-travel-engine' ); ?></span>
				<span class="wpte-tag error"><?php echo __( 'Refunded', 'wp-travel-engine' ); ?></span>
				<span class="wpte-tag"><?php echo __( 'Cancelled', 'wp-travel-engine' ); ?></span>
			</div>
			<div class="wpte-button-group">
				<button id="wpte-booking-edit-button" type="button"
					class="wpte-button wpte-outlined"><?php echo __( 'Edit', 'wp-travel-engine' ); ?></button>
				<button id="wpte-booking-submit-button" type="submit"
					class="wpte-button wpte-solid"><?php echo __( 'Save', 'wp-travel-engine' ); ?></button>
			</div>
		</div>
	</header> <!-- end .wpte-page-header -->

	<!-- .wpte-form-container -->
	<div class="wpte-form-container">
		<!-- .wpte-tabs -->
		<ul class="wpte-tabs horizontal">
			<li class="wpte-tab-item is-active">
				<a href="#" class="wpte-tab"
					data-target="trip-details"><?php echo __( 'Trip Details', 'wp-travel-engine' ); ?></a>
			</li>
			<li class="wpte-tab-item">
				<a href="#" class="wpte-tab"
					data-target="travellers"><?php echo __( 'Travellers', 'wp-travel-engine' ); ?></a>
			</li>
			<li class="wpte-tab-item">
				<a href="#" class="wpte-tab"
					data-target="emergency-contact"><?php echo __( 'Emergenecy Contact', 'wp-travel-engine' ); ?></a>
			</li>
			<li class="wpte-tab-item">
				<a href="#" class="wpte-tab"
					data-target="extra-service"><?php echo __( 'Extra Service', 'wp-travel-engine' ); ?></a>
			</li>
			<li class="wpte-tab-item">
				<a href="#" class="wpte-tab"
					data-target="accommodation"><?php echo __( 'Accommodation', 'wp-travel-engine' ); ?></a>
			</li>
			<li class="wpte-tab-item">
				<a href="#" class="wpte-tab"
					data-target="payment"><?php echo __( 'Payment', 'wp-travel-engine' ); ?></a>
			</li>
			<li class="wpte-tab-item">
				<a href="#" class="wpte-tab"
					data-target="billing"><?php echo __( 'Billing', 'wp-travel-engine' ); ?></a>
			</li>
			<li class="wpte-tab-item">
				<a href="#" class="wpte-tab"
					data-target="additional-notes"><?php echo __( 'Additional Notes', 'wp-travel-engine' ); ?></a>
			</li>
		</ul> <!-- end .wpte-tabs -->
		<div class="wpte-booking-details-layout">

			<!-- .wpte-booking-fields-area -->
			<div class="wpte-booking-fields-area">
				<!-- .wpte-booking-trip-name -->
				<h2 class="wpte-booking-trip-name">
					<a href="#" target="_blank">Best of Greece (15 days) Athens & 4 Islands in 15 days (Self-Guided)
						<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
							<path
								d="M17.5 7.50001L17.5 2.50001M17.5 2.50001H12.5M17.5 2.50001L10 10M8.33333 2.5H6.5C5.09987 2.5 4.3998 2.5 3.86502 2.77248C3.39462 3.01217 3.01217 3.39462 2.77248 3.86502C2.5 4.3998 2.5 5.09987 2.5 6.5V13.5C2.5 14.9001 2.5 15.6002 2.77248 16.135C3.01217 16.6054 3.39462 16.9878 3.86502 17.2275C4.3998 17.5 5.09987 17.5 6.5 17.5H13.5C14.9001 17.5 15.6002 17.5 16.135 17.2275C16.6054 16.9878 16.9878 16.6054 17.2275 16.135C17.5 15.6002 17.5 14.9001 17.5 13.5V11.6667"
								stroke="#859094" stroke-width="1.39" stroke-linecap="round" stroke-linejoin="round" />
						</svg>
					</a>
				</h2> <!-- end .wpte-booking-trip-name -->

				<!-- booking-detail-fields -->
				<div class="wpte-form-section" data-target-id="trip-details">
					<div class="wpte-fields-grid" data-columns="3">
						<div class="wpte-field">
							<label for="booked-date"><?php echo __( 'Booked Date', 'wp-travel-engine' ); ?></label>
							<input type="text" id="booked-date" class="wpte-date-picker"
								data-options='{"defaultDate": "2025-01-04"}' disabled>
						</div>
						<div class="wpte-field">
							<label for="start-date"><?php echo __( 'Start Date', 'wp-travel-engine' ); ?></label>
							<input type="text" id="start-date" class="wpte-date-picker"
								data-options='{"defaultDate": "2025-01-04"}' disabled>
						</div>
						<div class="wpte-field">
							<label for="end-date"><?php echo __( 'End Date', 'wp-travel-engine' ); ?></label>
							<input type="text" id="end-date" class="wpte-date-picker"
								data-options='{"defaultDate": "2025-01-04"}' disabled>
						</div>
						<div class="wpte-field">
							<label for="trip-code"><?php echo __( 'Trip Code', 'wp-travel-engine' ); ?></label>
							<input type="text" id="trip-code" value="WTE-2762" disabled>
						</div>
						<div class="wpte-field">
							<label
								for="no-of-travellers"><?php echo __( 'No. of Travellers', 'wp-travel-engine' ); ?></label>
							<input type="text" id="no-of-travellers" disabled>
						</div>
						<div class="wpte-field">
							<label for="package"><?php echo __( 'Package', 'wp-travel-engine' ); ?></label>
							<input type="text" id="package" disabled>
						</div>
					</div>
				</div> <!-- end booking-detail-fields -->

				<div class="wpte-booking-collapsible-content">
					<div class="wpte-form-section" data-target-id="travellers">
						<div class="wpte-accordion">
							<div class="wpte-accordion-header">
								<h3 class="wpte-accordion-title">
									<?php echo __( 'Traveller(s) Details', 'wp-travel-engine' ); ?></h3>
								<button type="button" class="wpte-accordion-toggle">
									<svg width="20" height="20" viewBox="0 0 20 20" fill="none"
										xmlns="http://www.w3.org/2000/svg">
										<path d="M5 7.5L10 12.5L15 7.5" stroke="currentColor" stroke-width="1.66667"
											stroke-linecap="round" stroke-linejoin="round" />
									</svg>
								</button>
							</div>
							<div class="wpte-accordion-content">
								<h5 class="wpte-accordion-subtitle">
									<?php echo __( 'Traveller 1 (Adult)', 'wp-travel-engine' ); ?></h5>
								<div class="wpte-fields-grid" data-columns="2">
									<div class="wpte-field">
										<label
											for="traveller-first-name"><?php echo __( 'Name', 'wp-travel-engine' ); ?><span
												class="required">*</span></label>
										<input type="text" id="traveller-name" value="Sophia" data-editable="true"
											disabled>
									</div>
									<div class="wpte-field">
										<label
											for="traveller-last-name"><?php echo __( 'Last Name', 'wp-travel-engine' ); ?><span
												class="required">*</span></label>
										<input type="text" id="traveller-last-name" value="Isabella Bennett"
											data-editable="true" disabled>
									</div>
									<div class="wpte-field">
										<label
											for="traveller-email"><?php echo __( 'Email', 'wp-travel-engine' ); ?><span
												class="required">*</span></label>
										<input type="email" id="traveller-email" value="" data-editable="true" disabled>
									</div>
									<div class="wpte-field">
										<label
											for="traveller-phone"><?php echo __( 'Phone Number', 'wp-travel-engine' ); ?><span
												class="required">*</span></label>
										<input type="number" id="traveller-phone" value="" data-editable="true"
											disabled>
									</div>
									<div class="wpte-field">
										<label for="country"><?php echo __( 'Country', 'wp-travel-engine' ); ?><span
												class="required">*</span></label>
										<select name="" id="" data-editable="true" disabled>
											<option value="">Select Country</option>
											<option value="Nepal">Nepal</option>
										</select>
									</div>
								</div>
								<hr>
								<h5 class="wpte-accordion-subtitle">
									<?php echo __( 'Traveller 2 (Child)', 'wp-travel-engine' ); ?></h5>
								<div class="wpte-fields-grid" data-columns="2">
									<div class="wpte-field">
										<label
											for="traveller-first-name"><?php echo __( 'Name', 'wp-travel-engine' ); ?><span
												class="required">*</span></label>
										<input type="text" id="traveller-name" value="Sophia" data-editable="true"
											disabled>
									</div>
									<div class="wpte-field">
										<label
											for="traveller-last-name"><?php echo __( 'Last Name', 'wp-travel-engine' ); ?><span
												class="required">*</span></label>
										<input type="email" id="traveller-last-name" value="Isabella Bennett"
											data-editable="true" disabled>
									</div>
									<div class="wpte-field">
										<label
											for="traveller-email"><?php echo __( 'Email', 'wp-travel-engine' ); ?><span
												class="required">*</span></label>
										<input type="text" id="traveller-email" value="" data-editable="true" disabled>
									</div>
									<div class="wpte-field">
										<label
											for="traveller-phone"><?php echo __( 'Phone Number', 'wp-travel-engine' ); ?><span
												class="required">*</span></label>
										<input type="number" id="traveller-phone" value="" data-editable="true"
											disabled>
									</div>
									<div class="wpte-field">
										<label for="country"><?php echo __( 'Country', 'wp-travel-engine' ); ?><span
												class="required">*</span></label>
										<select name="" id="" data-editable="true" disabled>
											<option value="">Select Country</option>
											<option value="Nepal">Nepal</option>
										</select>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="wpte-form-section" data-target-id="extra-service">
						<div class="wpte-accordion">
							<div class="wpte-accordion-header">
								<h3 class="wpte-accordion-title">
									<?php echo __( 'Extra Services Details', 'wp-travel-engine' ); ?></h3>
								<button type="button" class="wpte-accordion-toggle">
									<svg width="20" height="20" viewBox="0 0 20 20" fill="none"
										xmlns="http://www.w3.org/2000/svg">
										<path d="M5 7.5L10 12.5L15 7.5" stroke="currentColor" stroke-width="1.66667"
											stroke-linecap="round" stroke-linejoin="round" />
									</svg>
								</button>
							</div>
							<div class="wpte-accordion-content">
								<div class="wpte-table-wrap">
									<table class="wpte-table">
										<thead>
											<tr>
												<th style="width: 0%;"><?php echo __( 'S.N', 'wp-travel-engine' ); ?>
												</th>
												<th><?php echo __( 'Room Type', 'wp-travel-engine' ); ?></th>
												<th><?php echo __( 'Qty', 'wp-travel-engine' ); ?></th>
												<th><?php echo __( 'Cost', 'wp-travel-engine' ); ?></th>
											</tr>
										</thead>
										<tbody>
											<tr>
												<td>1.</td>
												<td>Single</td>
												<td>1</td>
												<td>$1200</td>
											</tr>
											<tr>
												<td>2.</td>
												<td>Single</td>
												<td>1</td>
												<td>$1200</td>
											</tr>
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div> <!-- end .wpte-booking-fields-area -->

			<!-- .wpte-booking-summary-area -->
			<div class="wpte-booking-summary-area">
				<div class="wpte-booking-summary">
					<h5 class="wpte-booking-summary-title"><?php echo __( 'Booking Summary', 'wp-travel-engine' ); ?>
					</h5>
					<div class="wpte-booking-summary-table-wrap">
						<table class="wpte-booking-summary-table">
							<tbody>
								<tr class="title">
									<td colspan="2">
										<strong><?php echo __( 'Traveller(s):', 'wp-travel-engine' ); ?></strong></td>
								</tr>
								<tr>
									<td>Adult: 2 x $3,500</td>
									<td><strong>$7,000</strong></td>
								</tr>
								<tr>
									<td>Child: 1 x $1,000</td>
									<td><strong>$7,000</strong></td>
								</tr>
								<tr class="title">
									<td colspan="2">
										<strong><?php echo __( 'Accommodation:', 'wp-travel-engine' ); ?></strong></td>
								</tr>
								<tr>
									<td>Single Room: 1 x $1,200</td>
									<td><strong>$1,200</strong></td>
								</tr>
								<tr>
									<td>Double Room: 2 x $1,500</td>
									<td><strong>$3,000</strong></td>
								</tr>
								<tr class="title">
									<td colspan="2">
										<strong><?php echo __( 'Extra Services:', 'wp-travel-engine' ); ?></strong></td>
								</tr>
								<tr>
									<td>Private Car: 1 x $120</td>
									<td><strong>$120</strong></td>
								</tr>
								<tr>
									<td>Guide: 2 x $180</td>
									<td><strong>$360</strong></td>
								</tr>
								<tr class="title wpte-booking-subtotal">
									<td colspan="2"><strong><?php echo __( 'Subtotal', 'wp-travel-engine' ); ?></strong>
									</td>
								</tr>
								<tr class="wpte-booking-discount">
									<td>Discount (10%)</td>
									<td><strong>-$1,268</strong></td>
								</tr>
								<tr>
									<td><?php echo __( 'Booking Fee', 'wp-travel-engine' ); ?> <span
											class="wpte-tooltip" data-content="Booking fee is non-refundable.">
											<svg width="16" height="16" viewBox="0 0 16 16" fill="none"
												xmlns="http://www.w3.org/2000/svg">
												<path
													d="M6.06004 6C6.21678 5.55444 6.52614 5.17874 6.93334 4.93942C7.34055 4.7001 7.8193 4.61262 8.28483 4.69247C8.75035 4.77232 9.17259 5.01435 9.47676 5.37568C9.78093 5.73702 9.94741 6.19435 9.94671 6.66667C9.94671 8 7.94671 8.66667 7.94671 8.66667M8.00004 11.3333H8.00671M14.6667 8C14.6667 11.6819 11.6819 14.6667 8.00004 14.6667C4.31814 14.6667 1.33337 11.6819 1.33337 8C1.33337 4.3181 4.31814 1.33333 8.00004 1.33333C11.6819 1.33333 14.6667 4.3181 14.6667 8Z"
													stroke="#7D89AF" stroke-width="1.33333" stroke-linecap="round"
													stroke-linejoin="round" />
											</svg>
										</span></td>
									<td><strong>$180</strong></td>
								</tr>
								<tr class="wpte-booking-tax">
									<td><?php echo __( 'Tax (13%)', 'wp-travel-engine' ); ?></td>
									<td><strong>$1,506.96</strong></td>
								</tr>
								<tr class="wpte-booking-total">
									<td><strong><?php echo __( 'Total', 'wp-travel-engine' ); ?></strong></td>
									<td><strong>$13,098.96</strong></td>
								</tr>
								<tr>
									<td><strong><?php echo __( 'Deposit Today', 'wp-travel-engine' ); ?></strong></td>
									<td><strong>$13,098.96</strong></td>
								</tr>
								<tr>
									<td><?php echo __( 'Amount Due', 'wp-travel-engine' ); ?></td>
									<td><strong>$13,098.96</strong></td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
				<div class="wpte-field">
					<label for=""><?php echo __( 'Remaining Payment Link', 'wp-travel-engine' ); ?></label>
					<div class="wpte-copy-field">
						<input type="url" name="" id=""
							value="https://checkout.stripe.com/pay/cs_test_a1b2c3d4e5f6g7h8i9j0k1l2m3n4o5p6" readonly>
						<button type="button" class="wpte-button wpte-link wpte-tooltip" data-content="Copy Link">
							<svg width="20" height="20" viewBox="0 0 20 20" fill="none"
								xmlns="http://www.w3.org/2000/svg">
								<path
									d="M6.25 2.5H12.1667C14.0335 2.5 14.9669 2.5 15.68 2.86331C16.3072 3.18289 16.8171 3.69282 17.1367 4.32003C17.5 5.03307 17.5 5.96649 17.5 7.83333V13.75M5.16667 17.5H11.9167C12.8501 17.5 13.3168 17.5 13.6733 17.3183C13.9869 17.1586 14.2419 16.9036 14.4017 16.59C14.5833 16.2335 14.5833 15.7668 14.5833 14.8333V8.08333C14.5833 7.14991 14.5833 6.6832 14.4017 6.32668C14.2419 6.01308 13.9869 5.75811 13.6733 5.59832C13.3168 5.41667 12.8501 5.41667 11.9167 5.41667H5.16667C4.23325 5.41667 3.76654 5.41667 3.41002 5.59832C3.09641 5.75811 2.84144 6.01308 2.68166 6.32668C2.5 6.6832 2.5 7.14991 2.5 8.08333V14.8333C2.5 15.7668 2.5 16.2335 2.68166 16.59C2.84144 16.9036 3.09641 17.1586 3.41002 17.3183C3.76654 17.5 4.23325 17.5 5.16667 17.5Z"
									stroke="currentColor" stroke-width="1.39" stroke-linecap="round"
									stroke-linejoin="round" />
							</svg>
						</button>
					</div>
				</div>
				<div class="wpte-field">
					<label for=""><?php echo __( 'Booking Status', 'wp-travel-engine' ); ?></label>
					<select name="" id="">
						<option value="pending">Pending</option>
						<option value="paid">Paid</option>
						<option value="refunded">Refunded</option>
						<option value="cancelled">Cancelled</option>
					</select>
				</div>
				<div>
					<button type="button" class="wpte-button wpte-outlined">
						<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
							<path
								d="M1.66675 5.83331L8.47085 10.5962C9.02182 10.9819 9.29731 11.1747 9.59697 11.2494C9.86166 11.3154 10.1385 11.3154 10.4032 11.2494C10.7029 11.1747 10.9783 10.9819 11.5293 10.5962L18.3334 5.83331M5.66675 16.6666H14.3334C15.7335 16.6666 16.4336 16.6666 16.9684 16.3942C17.4388 16.1545 17.8212 15.772 18.0609 15.3016C18.3334 14.7668 18.3334 14.0668 18.3334 12.6666V7.33331C18.3334 5.93318 18.3334 5.23312 18.0609 4.69834C17.8212 4.22793 17.4388 3.84548 16.9684 3.6058C16.4336 3.33331 15.7335 3.33331 14.3334 3.33331H5.66675C4.26662 3.33331 3.56655 3.33331 3.03177 3.6058C2.56137 3.84548 2.17892 4.22793 1.93923 4.69834C1.66675 5.23312 1.66675 5.93318 1.66675 7.33331V12.6666C1.66675 14.0668 1.66675 14.7668 1.93923 15.3016C2.17892 15.772 2.56137 16.1545 3.03177 16.3942C3.56655 16.6666 4.26662 16.6666 5.66675 16.6666Z"
								stroke="currentColor" stroke-width="1.39" stroke-linecap="round"
								stroke-linejoin="round" />
						</svg>
						<?php echo __( 'Resend Purchase Receipt', 'wp-travel-engine' ); ?>
					</button>
				</div>
			</div> <!-- end .wpte-booking-summary-area -->
		</div>
	</div> <!-- end .wpte-form-container -->
</div>
<div class="wpte-main-wrap wpte-edit-booking">
	<div class="wpte-block-wrap wpte-floated">
		<?php
		foreach ( array( 'trip-info', 'payments', 'customer' ) as $file ) {
			$_args = array( 'booking_details' => $booking_details );
			require plugin_dir_path( __FILE__ ) . "booking-details_{$file}.php";
		}
		?>
	</div> <!-- .wpte-block-wrap -->

	<?php
	if ( isset( $personal_options ) && ! empty( $personal_options ) || ( isset( $traveller_information ) && ! empty( $traveller_information ) ) || ( isset( $emergency_contact ) && ! empty( $emergency_contact ) ) ) {
		include plugin_dir_path( __FILE__ ) . 'booking-details_personal.php';
	}
	/**
	 * Hooks for Addons.
	 *
	 * @param int $post ->ID Post ID.
	 */
	do_action( 'wp_travel_engine_booking_screen_after_personal_details', $post->ID );
	?>
</div><!-- .wpte-main-wrap -->