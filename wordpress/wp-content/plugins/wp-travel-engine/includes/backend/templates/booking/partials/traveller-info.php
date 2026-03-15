<?php

/**
 * @var TravellerEditFormFields[] $travellers_form_fields
 * @var string $template_mode
 */

use WPTravelEngine\Builders\FormFields\TravellerEditFormFields;
$pricing_categories = get_terms(
	array(
		'taxonomy'   => 'trip-packages-categories',
		'hide_empty' => false,
		'orderby'    => 'term_id',
		'fields'     => 'id=>name',
	)
);

// Handle get_terms error
if ( is_wp_error( $pricing_categories ) ) {
	$pricing_categories = array();
}

// Get primary pricing category ID if not already set
if ( ! isset( $primary_pricing_category_id ) ) {
	$primary_pricing_category_id = get_option( 'primary_pricing_category', 0 );
}

// Find primary pricing category name from the associative array
$primary_pricing_category = '';
if ( ! empty( $primary_pricing_category_id ) && isset( $pricing_categories[ $primary_pricing_category_id ] ) ) {
	$primary_pricing_category = $pricing_categories[ $primary_pricing_category_id ];
} elseif ( ! empty( $pricing_categories ) ) {
	// Fallback to first category if primary not found
	$primary_pricing_category = reset( $pricing_categories );
}

// Build pricing category assignment map from cart line items
$pricing_category_map = array();
if ( ! empty( $cart_line_items['pricing_category'] ) ) {
	$traveller_index = 0;
	foreach ( $cart_line_items['pricing_category'] as $pricing_category ) {
		$quantity = isset( $pricing_category['quantity'] ) ? intval( $pricing_category['quantity'] ) : 1;

		// Try to get category_id from different possible keys
		$category_id = '';
		if ( isset( $pricing_category['category_id'] ) ) {
			$category_id = $pricing_category['category_id'];
		} elseif ( isset( $pricing_category['label'] ) ) {
			// Try to get term by name if category_id is not set
			$term = get_term_by( 'name', $pricing_category['label'], 'trip-packages-categories' );
			if ( $term && ! is_wp_error( $term ) ) {
				$category_id = $term->term_id;
			}
		}

		// Assign this pricing category to the number of travelers based on quantity
		for ( $j = 0; $j < $quantity; $j++ ) {
			if ( ! empty( $category_id ) ) {
				$pricing_category_map[ $traveller_index ] = $category_id;
			}
			++$traveller_index;
		}
	}
}


$booked_travelers_count = $cart_info->get_item()->travelers_count;
if ( empty( $travellers_form_fields ) ) {
	for ( $i = 0; $i < $booked_travelers_count; $i++ ) {
		$defaults = array(
			'index'       => $i,
			'total_count' => $booked_travelers_count,
		);

		// Set pricing category if available in the map
		if ( isset( $pricing_category_map[ $i ] ) ) {
			$defaults['pricing_category'] = $pricing_category_map[ $i ];
		}
		$travellers_form_fields[] = new TravellerEditFormFields(
			$defaults,
			$template_mode ?? 'edit',
			$booking ?? null
		);
	}
} else {
	// If travellers_form_fields exists, check and update pricing categories if not set
	foreach ( $travellers_form_fields as $index => $traveller_form_field ) {
		if ( isset( $pricing_category_map[ $index ] ) ) {
			$current_defaults = $traveller_form_field->get_defaults();

			// Only set pricing category if it's not already set or is empty
			if ( empty( $current_defaults['pricing_category'] ) || $current_defaults['pricing_category'] === 'selectoption' ) {
				$current_defaults['pricing_category'] = $pricing_category_map[ $index ];

				// Recreate the traveller form field with updated defaults
				$travellers_form_fields[ $index ] = new TravellerEditFormFields(
					array_merge(
						$current_defaults,
						array(
							'index'            => $index,
							'total_count'      => $booked_travelers_count,
							'pricing_category' => $pricing_category_map[ $index ],
						)
					),
					$template_mode ?? 'edit',
					$booking ?? null
				);
			}
		}
	}
}
if ( ! empty( $travellers_form_fields ) && count( $travellers_form_fields ) < $booked_travelers_count ) {
	for ( $i = count( $travellers_form_fields ); $i < $booked_travelers_count; $i++ ) {
		$defaults = array(
			'index'       => $i,
			'total_count' => $booked_travelers_count,
		);

		// Set pricing category if available in the map
		if ( isset( $pricing_category_map[ $i ] ) ) {
			$defaults['pricing_category'] = $pricing_category_map[ $i ];
		}

		$travellers_form_fields[] = new TravellerEditFormFields(
			$defaults,
			$template_mode ?? 'edit',
			$booking ?? null
		);
	}
}

?>
<div class="wpte-form-section" data-target-id="travellers">
	<?php if ( 'edit' === $template_mode || ! empty( $travellers_form_fields ) ) : ?>
		<div class="wpte-accordion">
			<div class="wpte-accordion-header">
				<h3 class="wpte-accordion-title"><?php echo __( 'Traveller(s) Details', 'wp-travel-engine' ); ?></h3>
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
								<th><?php echo __( 'NAME', 'wp-travel-engine' ); ?></th>
								<th style="text-align: center;" class="wpte-pricing-category-label">
									<?php echo __( 'TRAVELLER(S)', 'wp-travel-engine' ); ?></th>
								<th style="width: 189px;text-align: center;">
									<?php echo __( 'ACTION', 'wp-travel-engine' ); ?></th>
							</tr>
						</thead>
						<tbody data-traveller-section>
							<?php
							if ( is_array( $travellers_form_fields ) ) :
								foreach ( $travellers_form_fields as $index => $traveller_form_fields ) :
									?>
									<tr>
										<td>
											<?php
											$fields = $traveller_form_fields->get_fields();

											// Find and display fname and lname values
											$fname_value = '';
											$lname_value = '';
											foreach ( $fields as $field ) {
												if ( isset( $field['name'] ) && preg_match( '/fname/', $field['name'] ) ) {
													$fname_value = isset( $field['default'] ) ? $field['default'] : '';
												} elseif ( isset( $field['name'] ) && preg_match( '/lname/', $field['name'] ) ) {
													$lname_value = isset( $field['default'] ) ? $field['default'] : '';
												}
											}
											// Display both first and last name values, or fallback to "Traveller X" if name not available
											$full_name = trim( $fname_value . ' ' . $lname_value );
											if ( empty( $full_name ) ) {
												$full_name = sprintf( __( 'Traveller %d', 'wp-travel-engine' ), $index + 1 );
											}
											echo esc_html( $full_name );
											?>
										</td>
										<td style="text-align: center;" class="wpte-pricing-category-label">
											<?php
											$fields                 = $traveller_form_fields->get_defaults();
											$pricing_category_value = '';
											foreach ( $fields as $field ) {
												if ( isset( $field['name'] ) && preg_match( '/pricing_category/', $field['name'] ) ) {
													$pricing_category_value = isset( $field['default'] ) ? $field['default'] : '';
													break;
												}
											}

											// If not found in fields, try to get from the form fields object defaults
											if ( empty( $pricing_category_value ) ) {
												$defaults               = $traveller_form_fields->get_defaults();
												$pricing_category_value = $defaults['pricing_category'] ?? '';
											}

											// Convert ID to name if it's numeric, or get name if it's already a name
											if ( ! empty( $pricing_category_value ) ) {
												if ( is_numeric( $pricing_category_value ) ) {
													// It's an ID, get the name
													$pricing_category       = get_term_by( 'id', $pricing_category_value, 'trip-packages-categories' );
													$pricing_category_value = $pricing_category ? $pricing_category->name : '';
												} else {
													// It's already a name, use it directly
													$pricing_category       = get_term_by( 'name', $pricing_category_value, 'trip-packages-categories' );
													$pricing_category_value = $pricing_category ? $pricing_category->name : $pricing_category_value;
												}
											}

											if ( empty( $pricing_category_value ) ) {
												$pricing_category_value = __( 'Not Set', 'wp-travel-engine' );
											}

											if ( $pricing_category_value == 'selectoption' || $pricing_category_value == '' ) {
												$pricing_category_value = __( 'Not Set', 'wp-travel-engine' );
											}

											// Display the pricing category value
											echo esc_html( $pricing_category_value );
											?>
										</td>
										<td style="text-align: center;">
											<button class="wpte-button wpte-toggle-button" type="button">
												<?php echo __( 'View Details', 'wp-travel-engine' ); ?>
												<svg width="16" height="16" viewBox="0 0 16 16" fill="none"
													xmlns="http://www.w3.org/2000/svg">
													<path d="M4 6L8 10L12 6" stroke="currentColor" stroke-width="1.33333"
														stroke-linecap="round" stroke-linejoin="round" />
												</svg>
											</button>
											<?php if ( 'edit' === $template_mode ) : ?>
												<button
													class="wpte-button wpte-delete-button wpte-delete-section <?php echo count( $travellers_form_fields ) > 1 ? '' : 'hidden'; ?>"
													type="button">
													<svg width="20" height="20" viewBox="0 0 20 20" fill="none"
														xmlns="http://www.w3.org/2000/svg">
														<path
															d="M13.3333 5V4.33333C13.3333 3.39991 13.3333 2.9332 13.1517 2.57668C12.9919 2.26308 12.7369 2.00811 12.4233 1.84832C12.0668 1.66666 11.6001 1.66666 10.6667 1.66666H9.33333C8.39991 1.66666 7.9332 1.66666 7.57668 1.84832C7.26308 2.00811 7.00811 2.26308 6.84832 2.57668C6.66667 2.9332 6.66667 3.39991 6.66667 4.33333V5M8.33333 9.58333V13.75M11.6667 9.58333V13.75M2.5 5H17.5M15.8333 5V14.3333C15.8333 15.7335 15.8333 16.4335 15.5608 16.9683C15.3212 17.4387 14.9387 17.8212 14.4683 18.0608C13.9335 18.3333 13.2335 18.3333 11.8333 18.3333H8.16667C6.76654 18.3333 6.06647 18.3333 5.53169 18.0608C5.06129 17.8212 4.67883 17.4387 4.43915 16.9683C4.16667 16.4335 4.16667 15.7335 4.16667 14.3333V5"
															stroke="#E84B4B" stroke-width="1.66667" stroke-linecap="round"
															stroke-linejoin="round" />
													</svg>
												</button>
											<?php endif; ?>
										</td>
									</tr>
									<tr class="wpte-row-details">
										<td colspan="3">
											<div class="wpte-fields-grid" data-columns="2">
												<?php $traveller_form_fields->render(); ?>
											</div>
										</td>
									</tr>
									<?php
								endforeach;
							endif;
							?>
						</tbody>
					</table>
				</div>
				<?php if ( 'edit' === $template_mode ) : ?>
					<div style="padding:16px;">
						<button class="wpte-button wpte-link" data-type="add"
							data-total-count="<?php echo count( $travellers_form_fields ); ?>"
							data-template="traveller-template" data-target="[data-traveller-section]"
							data-line-item-template="cart-line-item-pricing-category"
							data-line-item-target="[data-line-item__pricing_category_section]">
							<?php echo __( '+ Add Traveller', 'wp-travel-engine' ); ?>
						</button>
					</div>
				<?php endif; ?>
			</div>
		</div>
	<?php endif; ?>
</div>
<script type="text/html" id="tmpl-traveller-template">

	<tr class="wpte-new-traveller">
		<td><?php echo __( 'New Traveller', 'wp-travel-engine' ); ?></td>
		<td style="text-align: center;" class="wpte-pricing-category-label"><?php echo esc_html( $primary_pricing_category ); ?></td>
		<td style="text-align: center;">
			<button class="wpte-button wpte-toggle-button" type="button">
				<?php echo __( 'View Details', 'wp-travel-engine' ); ?>
				<svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
					<path d="M4 6L8 10L12 6" stroke="currentColor" stroke-width="1.33333" stroke-linecap="round" stroke-linejoin="round"/>
				</svg>
			</button>
			<# if ( data.travellerType === 'traveller' ) { #>
			<button class="wpte-button wpte-delete-button wpte-delete-section" type="button">
				<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
					<path d="M13.3333 5V4.33333C13.3333 3.39991 13.3333 2.9332 13.1517 2.57668C12.9919 2.26308 12.7369 2.00811 12.4233 1.84832C12.0668 1.66666 11.6001 1.66666 10.6667 1.66666H9.33333C8.39991 1.66666 7.9332 1.66666 7.57668 1.84832C7.26308 2.00811 7.00811 2.26308 6.84832 2.57668C6.66667 2.9332 6.66667 3.39991 6.66667 4.33333V5M8.33333 9.58333V13.75M11.6667 9.58333V13.75M2.5 5H17.5M15.8333 5V14.3333C15.8333 15.7335 15.8333 16.4335 15.5608 16.9683C15.3212 17.4387 14.9387 17.8212 14.4683 18.0608C13.9335 18.3333 13.2335 18.3333 11.8333 18.3333H8.16667C6.76654 18.3333 6.06647 18.3333 5.53169 18.0608C5.06129 17.8212 4.67883 17.4387 4.43915 16.9683C4.16667 16.4335 4.16667 15.7335 4.16667 14.3333V5" stroke="#E84B4B" stroke-width="1.66667" stroke-linecap="round" stroke-linejoin="round"/>
				</svg>
			</button>
			<# } else { #>
			<div style="width: 22px;display: inline-block"></div>
			<# } #>
		</td>
	</tr>
	<tr class="wpte-row-details">
		<td colspan="3">
			<div class="wpte-fields-grid" data-columns="2">
				<# if ( data.travellerType === 'lead-traveller' ) { #>
					<?php
						TravellerEditFormFields::create(
							array(
								'index' => 0,
							),
							'edit'
						)->render();
						?>
				<# } else { #>
					<?php
						TravellerEditFormFields::create(
							array(
								'index' => 1,
							),
							'edit'
						)->render();
						?>
				<# } #>
			</div>
		</td>
	</tr>
</script>