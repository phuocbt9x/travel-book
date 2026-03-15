<?php
/**
 * Line Items
 *
 * @var array $cart_line_items
 * @since 6.4.0
 */

foreach ( $cart_line_items as $item_type => $line_items ) :
	$line_item_group_title = apply_filters( 'wptravelengine_booking_line_item_group_title', $item_type, $line_items );
	?>
	<tr class="title">
		<td colspan="1"><strong><?php echo esc_html( $line_item_group_title ); ?></strong></td>
		<td>
			<?php if ( $is_booking_edit_enabled ) : ?>
				<button type="button" class="wpte-button wpte-link"
						data-template="cart-line-item"
						data-target="[data-line-item__<?php echo esc_attr( $item_type ); ?>_section]"
					data-args="
					<?php
					echo esc_attr(
						wp_json_encode(
							array(
								'item_type' => $item_type,
							)
						)
					);
					?>
					"
					data-type="add">
				<?php echo __( '+', 'wp-travel-engine' ); ?>
			</button>
			<?php endif; ?>
		</td>
	</tr>
	<tr>
		<td colspan="2" style="padding: 0;">
			<table data-line-item__<?php echo esc_attr( $item_type ); ?>_section>
				<?php
				foreach ( $line_items as $line_item ) {
					$quantity = (float) $line_item['quantity'] ?? 0;
					$price    = (float) $line_item['price'] ?? 0;
					$total    = (float) ( $line_item['total'] ?? $quantity * $price );
					?>
					<tr>
						<td>
							<div style="display: flex;align-items:center;gap:.5em;">
								<input type="text"
										name="line_items[<?php echo esc_attr( $item_type ); ?>][label][]"
										placeholder="Label"
										value="<?php echo esc_attr( $line_item['label'] ); ?>"
										style="min-width: 100px;"
										<?php echo $is_booking_edit_enabled ? '' : 'readonly'; ?>>
								<input type="number"
										name="line_items[<?php echo esc_attr( $item_type ); ?>][quantity][]"
										value="<?php echo esc_attr( $quantity ); ?>"
										style="min-width: 50px;" min="0" step="1"
										<?php echo $is_booking_edit_enabled ? '' : 'readonly'; ?>> ×
								<input type="number"
										name="line_items[<?php echo esc_attr( $item_type ); ?>][price][]"
										value="<?php echo esc_attr( $line_item['price'] ); ?>"
										style="min-width: 50px;" min="0" step="any"
										<?php echo $is_booking_edit_enabled ? '' : 'readonly'; ?>>
							</div>
						</td>
						<td>
							<input type="number"
									name="line_items[<?php echo esc_attr( $item_type ); ?>][total][]"
									value="<?php echo esc_attr( $total ); ?>"
									step="any"
									<?php echo $is_booking_edit_enabled ? '' : 'readonly'; ?>>
						</td>
						<td class="wpte-delete-column">
							<button type="button" class="wpte-table-delete-row">
								<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
									<path d="M16 6V5.2C16 4.0799 16 3.51984 15.782 3.09202C15.5903 2.71569 15.2843 2.40973 14.908 2.21799C14.4802 2 13.9201 2 12.8 2H11.2C10.0799 2 9.51984 2 9.09202 2.21799C8.71569 2.40973 8.40973 2.71569 8.21799 3.09202C8 3.51984 8 4.0799 8 5.2V6M10 11.5V16.5M14 11.5V16.5M3 6H21M19 6V17.2C19 18.8802 19 19.7202 18.673 20.362C18.3854 20.9265 17.9265 21.3854 17.362 21.673C16.7202 22 15.8802 22 14.2 22H9.8C8.11984 22 7.27976 22 6.63803 21.673C6.07354 21.3854 5.6146 20.9265 5.32698 20.362C5 19.7202 5 18.8802 5 17.2V6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
								</svg>
							</button>
						</td>
					</tr>
					<?php
				}
				?>
			</table>
		</td>
	</tr>
<?php endforeach; ?>

<script type="text/html" id="tmpl-cart-line-item">
	<tr>
		<td>
			<div style="display: flex;align-items:center;gap:.5em;">
				<input type="text"
						name="line_items[{{data.item_type}}][label][]"
						placeholder="Label"
						value="" style="min-width: 100px;">
				<input type="number"
						name="line_items[{{data.item_type}}][quantity][]"
						value=""
						style="flex:0 0 50px;" min="0"> ×
				<input type="number"
						name="line_items[{{data.item_type}}][price][]"
						value=""
						style="flex:0 0 50px;" min="0" step="any">
			</div>
		</td>
		<td>
			<input type="number" name="line_items[{{data.item_type}}][total][]" value="" step="any">
		</td>
		<td class="wpte-delete-column">
			<button type="button" class="wpte-table-delete-row">
				<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
					<path d="M16 6V5.2C16 4.0799 16 3.51984 15.782 3.09202C15.5903 2.71569 15.2843 2.40973 14.908 2.21799C14.4802 2 13.9201 2 12.8 2H11.2C10.0799 2 9.51984 2 9.09202 2.21799C8.71569 2.40973 8.40973 2.71569 8.21799 3.09202C8 3.51984 8 4.0799 8 5.2V6M10 11.5V16.5M14 11.5V16.5M3 6H21M19 6V17.2C19 18.8802 19 19.7202 18.673 20.362C18.3854 20.9265 17.9265 21.3854 17.362 21.673C16.7202 22 15.8802 22 14.2 22H9.8C8.11984 22 7.27976 22 6.63803 21.673C6.07354 21.3854 5.6146 20.9265 5.32698 20.362C5 19.7202 5 18.8802 5 17.2V6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
				</svg>
			</button>
		</td>
	</tr>
</script>
