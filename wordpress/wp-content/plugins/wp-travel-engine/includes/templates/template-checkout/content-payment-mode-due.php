<?php
/**
 * @var string $due_payment_amount
 * @since 6.3.0
 */

global $wte_cart;
?>
<div class="wpte-checkout__payment-options">
	<label for="" class="wpte-bf-label">
		<?php
		echo apply_filters( 'wte_checkout_partial_pay_heading', __( 'Choose Payment Option', 'wp-travel-engine' ) );
		?>
	</label>
	<div class="wpte-checkout__form-control">
		<input type="radio" name="wp_travel_engine_payment_mode"
				value="remaining_payment"
				id="wp_travel_engine_payment_mode-partial" checked>
		<label for="wp_travel_engine_payment_mode-partial">
			<?php
			printf(
				apply_filters(
					'wptravelengine_checkout_due_pay_label',
					__( 'Remaining Amount (%s)', 'wp-travel-engine' )
				),
				wptravelengine_the_price( $due_payment_amount, false )
			);
			?>
		</label>
	</div>
</div>
