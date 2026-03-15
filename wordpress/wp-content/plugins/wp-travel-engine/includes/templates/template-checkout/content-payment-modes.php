<?php
/**
 * @var string $payment_mode
 * @var string $due_payment_amount
 * @var string $full_payment_amount
 * @var bool $full_payment_enabled
 * @var float $down_payment_amount
 * @since 6.3.0
 */
?>
<div class="wpte-checkout__payment-options">
	<label for="" class="wpte-bf-label">
		<?php
		echo apply_filters(
			'wte_checkout_partial_pay_heading',
			__( 'Choose Payment Option', 'wp-travel-engine' )
		);
		?>
	</label>
	<?php if ( $full_payment_enabled ) : ?>
		<div class="wpte-checkout__form-control">
			<input type="radio" name="wp_travel_engine_payment_mode"
					value="full_payment"
					id="wp_travel_engine_payment_mode-full"
				<?php checked( 'full' === $payment_mode ); ?>
			>
			<label for="wp_travel_engine_payment_mode-full">
				<?php
				printf(
					apply_filters(
						'wptravelengine_checkout_full_pay_label',
						__( 'Pay Full Amount (%s)', 'wp-travel-engine' )
					),
					wptravelengine_the_price( $full_payment_amount, false, false )
				);
				?>
			</label>
		</div>
	<?php endif; ?>
	<div class="wpte-checkout__form-control">
		<input type="radio" name="wp_travel_engine_payment_mode" value="partial"
				id="wp_travel_engine_payment_mode-partial" <?php checked( 'partial' === $payment_mode ); ?>>
		<label for="wp_travel_engine_payment_mode-partial">
			<?php
			printf(
				apply_filters(
					'wptravelengine_checkout_down_pay_label',
					__( 'Pay Deposit (%s)', 'wp-travel-engine' )
				),
				wptravelengine_the_price( $down_payment_amount, false, false )
			);
			?>
		</label>
	</div>
	<?php do_action( 'wptravelengine_after_checkout_payment_modes_', $args ); ?>
</div>
