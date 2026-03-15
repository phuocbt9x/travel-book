<?php
/**
 * @var array $payment_methods
 * @since 6.3.0
 */
?>
<div class="wpte-checkout__payment-methods">
	<?php foreach ( $payment_methods as $key => $payment_method ) : ?>
		<div class="wpte-checkout__payment-method">
			<div class="wpte-checkout__form-control <?php echo esc_attr( $wte_cart->payment_gateway === $key ? 'checked' : '' ); ?>">
				<input type="radio" name="wpte_checkout_paymnet_method" value="<?php echo esc_attr( $key ); ?>"
						id="<?php echo esc_attr( $key ); ?>" <?php checked( $wte_cart->payment_gateway === $key, true ); ?>>
				<label
					for="<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $payment_method['label'] ); ?></label>
			</div>
			<?php if ( isset( $payment_method['icon_url'] ) ) : ?>
				<div class="wpte-checkout__payment-method-logo">
					<?php
					wptravelengine_display_icon(
						$payment_method['icon_url'],
						$payment_method['label'] ?? 'Payment Method',
						true
					);
					?>
				</div>
			<?php endif; ?>
			<?php if ( ! empty( $payment_method['description'] ) ) : ?>
				<div class="wpte-checkout__payment-method-info">
					<?php echo wp_kses_post( $payment_method['description'] ); ?>
				</div>
			<?php endif; ?>
			<?php do_action( "wptravelengine_{$key}_payment_cc" ); ?>
		</div>
	<?php endforeach; ?>
</div>
