<?php
/**
 * Direct Bank Transfer Instructions template.
 *
 * @since 6.3.3
 */

/**
 * @var string $instruction Instructions.
 */
?>
<div class="wpte-thankyou__block">
	<div class="wpte-thankyou__block-title">
		<?php echo esc_html__( 'Check Payment', 'wp-travel-engine' ); ?>
	</div>
	<div class="wpte-thankyou__block-content">
		<div class="wte-bank-transfer-instructions">
			<?php echo wp_kses_post( $instruction ); ?>
		</div>
	</div>
</div>
