<?php
/**
 * @var array $attributes
 * @var array $coupons
 * @var bool $show_coupon_form
 * @since 6.3.0
 */

if ( isset( $coupons[0] ) ) : // Checks if has at least one coupon applied.
	foreach ( $coupons as $coupon ) :
		?>
		<!-- Applied Coupon Card -->
		<div class="wpte-checkout__coupon-card">
			<div class="wpte-checkout__coupon-title">
			<?php
			esc_html_e( 'YAY! You saved ', 'wp-travel-engine' );
				wptravelengine_the_price( $coupon['amount'] ?? 0 );
			?>
				</div>
			<div class="wpte-checkout__coupon-content">
			<?php
			esc_html_e( 'Coupon ', 'wp-travel-engine' );
				echo esc_html( $coupon['label'] ?? '' );
				esc_html_e( ' Applied', 'wp-travel-engine' );
			?>
				</div>
			<button class="wpte-checkout__coupon-cancel-button"
					data-coupon-nonce="<?php echo esc_attr( wp_create_nonce( 'wte_session_cart_reset_coupon' ) ); ?>"
					data-remove-coupon
			>
				<svg>
					<use xlink:href="#x-circle"></use>
				</svg>
			</button>
		</div>
		<?php
	endforeach;
elseif ( $show_coupon_form ) :
	?>
	<!-- Coupon Form -->
	<form action="" class="wpte-checkout__coupon-form">
		<label><?php esc_html_e( 'Have a coupon code?', 'wp-travel-engine' ); ?></label>
		<div class="wpte-checkout__form-control wpte-material-ui-input-control">
			<input type="text" id="wpte-checkout__coupon" name="wpte-checkout__coupon"
					class="wpte-checkout__input wpte-checkout__coupon-code-input"
					data-coupon-code
					placeholder="<?php esc_attr_e( 'Coupon code', 'wp-travel-engine' ); ?>">
			<label for="wpte-checkout__coupon"><?php esc_html_e( 'Coupon code', 'wp-travel-engine' ); ?></label>
			<fieldset>
				<legend><span><?php esc_html_e( 'Coupon code', 'wp-travel-engine' ); ?></span></legend>
			</fieldset>
		</div>
		<div class="wpte-checkout__form-submit">
			<button type="submit"
					data-apply-coupon
					data-coupon-source="[data-coupon-code]"
					data-coupon-nonce="<?php echo esc_attr( wp_create_nonce( 'wte_session_cart_apply_coupon' ) ); ?>"
					class="wpte-checkout__form-submit-button"><?php esc_html_e( 'Apply', 'wp-travel-engine' ); ?></button>
		</div>
		<span style="display: none;"
				class="wpte-checkout__form-invalid-text"
				data-coupon-error-message><?php esc_html_e( 'Enter valid coupon code.', 'wp-travel-engine' ); ?></span>
	</form>
<?php endif; ?>
