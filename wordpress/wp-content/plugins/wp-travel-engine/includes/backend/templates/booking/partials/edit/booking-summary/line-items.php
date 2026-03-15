<?php
/**
 * Line Items
 *
 * @var array $cart_line_items
 * @since 6.4.0
 */
// Define the desired order for line item types
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

$format = wptravelengine_settings()->get( 'amount_display_format', '%CURRENCY_SYMBOL% %AMOUNT%' );

$pricing_arguments = array(
	'currency_code' => $cart_info->get_currency(),
	'format'        => str_replace( '%FORMATED_AMOUNT%', '%AMOUNT%', $format ),
);

foreach ( $cart_line_items as $item_type => $line_items ) :
	if ( $item_type == 'pricing_category' || $item_type == 'pickup_point' ) {
		$is_booking_edit_enabled = true;
	} else {
		$is_booking_edit_enabled = false;
	}
	$line_item_group_title = apply_filters( 'wptravelengine_booking_line_item_group_title', $item_type, $line_items );
	?>
	<tr class="title">
		<td colspan="2"><strong><?php echo esc_html( $line_item_group_title ); ?></strong></td>
	</tr>
	<tr>
		<td colspan="2" style="padding: 0;">
			<table data-line-item__<?php echo esc_attr( $item_type ); ?>_section>
				<?php
				foreach ( $line_items as $line_item ) {
					if ( empty( $line_item['category_id'] ) && $item_type == 'pricing_category' ) {
						$pricing_category         = get_term_by( 'name', $line_item['label'], 'trip-packages-categories' );
						$line_item['category_id'] = $pricing_category->term_id ?? '';
					}
					$category_id  = $line_item['category_id'] ?? '';
					$traveller_id = $line_item['traveller_id'] ?? '';
					$quantity     = (float) $line_item['quantity'] ?? 0;
					$price        = (float) $line_item['price'] ?? 0;
					$total        = (float) ( isset( $line_item['total'] ) && $line_item['total'] > 0 ? $line_item['total'] : $quantity * $price );
					?>
					<tr data-category-id="<?php echo esc_attr( $category_id ); ?>" data-traveller-id="<?php echo esc_attr( $traveller_id ); ?>">
						<td>
							<div style="display: flex;align-items:center;gap:.5em;">
							<span class="wpte-line-item-label" data-traveller-category-id="<?php echo esc_attr( $category_id ); ?>"><?php echo esc_html( $line_item['label'] ); ?>:</span>
							<span class="wpte-line-item-quantity"><?php echo esc_html( $quantity ); ?></span> ×
								<?php if ( $item_type == 'pricing_category' || $item_type == 'pickup_point' ) { ?>
									<input type="hidden"
										name="line_items[<?php echo esc_attr( $item_type ); ?>][<?php echo esc_attr( $category_id ); ?>][quantity][]"
										value="<?php echo esc_attr( $quantity ); ?>"
										class="wpte-line-item-quantity-input"
										data-category-id="<?php echo esc_attr( $category_id ); ?>">
									<input type="number"
											name="line_items[<?php echo esc_attr( $item_type ); ?>][<?php echo esc_attr( $category_id ); ?>][price][]"
											value="<?php echo esc_attr( $line_item['price'] ); ?>"
											style="min-width: 50px;" min="0" step="any"
											data-category-id="<?php echo esc_attr( $category_id ); ?>"
											<?php echo $is_booking_edit_enabled ? '' : 'readonly'; ?>>
								<?php } else { ?>
									<span class="wpte-line-item-price"><?php echo esc_html( $price ); ?></span>
								<?php } ?>
							</div>
						</td>
						<td class="wpte-line-item-total">
							<?php wptravelengine_the_price( $total, true, $pricing_arguments ); ?>
						</td>
					</tr>
					<?php
				}
				?>
			</table>
		</td>
	</tr>
<?php endforeach; ?>

<script type="text/html" id="tmpl-cart-line-item-pricing-category">
	<tr data-category-id="{{data.categoryId}}">
		<td>
			<div style="display: flex;align-items:center;gap:.5em;">
				<span class="wpte-line-item-label" data-traveller-category-id="selectoption">{{{ data.displayLabel }}}:</span>
				<span class="wpte-line-item-quantity">1</span> ×
				<input type="number"
						placeholder="Price"
						name="line_items[pricing_category][price][]"
						value=""
						style="min-width: 50px;" min="0" step="any">
			</div>
		</td>
		<td class="wpte-line-item-total">
			<?php wptravelengine_the_price( 0, true, $pricing_arguments ); ?>
		</td>
	</tr>
</script>

<script type="text/html" id="tmpl-cart-line-item">
	<tr>
		<td>
			<div style="display: flex;align-items:center;gap:.5em;">
				<span class="wpte-line-item-label"><?php echo __( 'Label', 'wp-travel-engine' ); ?>:</span>
				<span class="wpte-line-item-quantity">1</span> ×
				<span class="wpte-line-item-price"><?php wptravelengine_the_price( 0, true, $pricing_arguments ); ?></span>
			</div>
		</td>
		<td class="wpte-line-item-total">
			<?php wptravelengine_the_price( 0, true, $pricing_arguments ); ?>
		</td>
	</tr>
</script>