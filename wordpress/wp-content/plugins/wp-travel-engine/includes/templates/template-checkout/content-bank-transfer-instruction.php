<?php
/**
 * Direct Bank Transfer Instructions template.
 *
 * @since 6.3.3
 */

/**
 * @var string $instruction Instructions.
 * @var array $bank_details Bank details.
 */
?>
<div class="wpte-thankyou__block">
	<div class="wpte-thankyou__block-title"><?php echo esc_html__( 'Bank Details:', 'wp-travel-engine' ); ?></div>
	<div class="wpte-thankyou__block-content">
		<div class="wte-bank-transfer-instructions">
			<?php echo wp_kses_post( $instruction ); ?>
		</div>
		<?php if ( isset( $bank_details[0] ) ) : ?>
			<?php foreach ( $bank_details as $bank ) : ?>
				<table>
					<?php foreach ( array_chunk( $bank, 2 ) as $bank_detail ) : ?>
						<tr>
							<td><?php printf( '<strong>%s</strong><br/>%s', esc_html( $bank_detail[0]['label'] ), esc_html( $bank_detail[0]['value'] ) ); ?></td>
							<td><?php isset( $bank_detail[1] ) && printf( '<strong>%s</strong><br/>%s', esc_html( $bank_detail[1]['label'] ), esc_html( $bank_detail[1]['value'] ) ); ?></td>
						</tr>
					<?php endforeach; ?>
				</table>
			<?php endforeach; ?>
		<?php endif; ?>
	</div>
</div>
