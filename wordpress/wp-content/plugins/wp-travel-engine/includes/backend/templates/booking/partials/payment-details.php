<?php

/**
 * @var PaymentEditFormFields[] $payments_edit_form_fields
 * @var \WPTravelEngine\Core\Models\Post\Booking $booking
 * @var bool $is_new_booking
 */

use WPTravelEngine\Builders\FormFields\PaymentEditFormFields;

$payment_edit_fields = PaymentEditFormFields::create( array( 'tax' => '' ), 'edit', array( 'tax' => __( 'Tax', 'wp-travel-engine' ) ) );

$def_arr = array(
	'id'               => true,
	'status'           => true,
	'gateway'          => true,
	'date'             => true,
	'currency'         => true,
	'transaction_id'   => true,
	'gateway_response' => true,
);

$extra_fields = array_diff_key( $payment_edit_fields->get_my_fields(), $def_arr );

if ( ! $is_new_booking && empty( $payments_edit_form_fields ) ) {
	return;
}

?>
<div class="wpte-form-section" data-target-id="payments" data-summary-info='<?php echo esc_attr( json_encode( array_keys( $extra_fields ) ) ); ?>'>
	<div class="wpte-accordion">
		<div class="wpte-accordion-header">
			<h3 class="wpte-accordion-title"><?php echo __( 'Payment Details', 'wp-travel-engine' ); ?></h3>
			<button type="button" class="wpte-accordion-toggle">
				<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
					<path d="M5 7.5L10 12.5L15 7.5" stroke="currentColor" stroke-width="1.66667" stroke-linecap="round"
						stroke-linejoin="round" />
				</svg>
			</button>
		</div>
		<div class="wpte-accordion-content">
			<div data-payments-section>
				<?php
				foreach ( $payments_edit_form_fields as $index => $payment_edit_form_fields ) :
					if ( $index > 0 ) :
						?>
						<hr>
					<?php endif; ?>
					<h5 class="wpte-accordion-subtitle"><?php printf( 'Payment #%d', $index + 1 ); ?></h5>
					<div class="wpte-fields-grid" data-columns="2">
						<?php
						$payment_edit_form_fields->update_fields( 'deposit.field_label', $extra_fields['deposit']['field_label'] );
						$payment_edit_form_fields->render();
						?>
					</div>
				<?php endforeach; ?>
			</div>
			<?php if ( 'edit' === $template_mode ) : ?>
				<div style="padding:16px;">
					<button class="wpte-button wpte-link"
						data-total-count="<?php echo count( $payments_edit_form_fields ); ?>"
						data-target="[data-payments-section]"
						data-template="payment-template"
						data-type="add"
						data-line-item-template="payment-summary-card"
						data-line-item-target="[data-payment-summary-cards]">
						<?php echo __( '+ Add Payment', 'wp-travel-engine' ); ?>
					</button>
				</div>
			<?php endif ?>
		</div>
	</div>
</div>

<?php
if ( ! $is_new_booking ) {
	return;
}

$extra_fields['deposit'] = null;
$extra_fields['amount']  = null;

$payments_data    = $booking->get_payments_data();
$_edit_form_field = $payments_edit_form_fields[0] ?? $payment_edit_fields;

$fee_hidden_markups = '';
foreach ( $_edit_form_field->get_my_fields() as $id => $field ) {
	if ( str_ends_with( $id, '_hidden' ) || isset( $def_arr[ $id ] ) ) {
		continue;
	}
	$defaults = $_edit_form_field->get_defaults() ?: array();
	$behav_   = $field['behaviours'] ?? array();
	if ( 'fee' === ( $behav_['type'] ?? '' ) ) {
		$fee_hidden_markups .= '<input type="hidden" name="fees[slug][]" value="' . esc_attr( $id ) . '">';
		$fee_hidden_markups .= '<input type="hidden" name="fees[label][]" value="' . esc_attr( $field['field_label'] ?? '' ) . '">';
		$fee_hidden_markups .= '<input type="hidden" name="fees[_class_name][]" value="' . esc_attr( $behav_['class_name'] ?? '' ) . '">';
		$fee_hidden_markups .= '<input type="hidden" name="fees[value][]" value="' . esc_attr( $behav_['value'] ?? '' ) . '">';
		$fee_hidden_markups .= '<input type="hidden" name="fees[adjustment_type][]" value="' . esc_attr( $behav_['adjustment_type'] ?? '' ) . '">';
		$fee_hidden_markups .= '<input type="hidden" name="fees[percentage][]" value="' . esc_attr( $behav_['percentage'] ?? $behav_['value'] ?? '' ) . '">';
		$fee_hidden_markups .= '<input type="hidden" name="fees[apply_tax][]" value="' . wptravelengine_replace( $behav_['apply_tax'] ?? true, true, 'yes', 'no' ) . '">';
		$fee_hidden_markups .= '<input type="hidden" name="fees[apply_upfront][]" value="' . wptravelengine_replace( $behav_['apply_upfront'] ?? false, true, 'yes', 'no' ) . '">';
	}
}

echo $fee_hidden_markups;
?>

<script type="text/html" id="tmpl-payment-template">
	<hr>
	<div class="wpte-payment-wrapper">
		<h5 class="wpte-accordion-subtitle">
			<?php echo __( 'Payment ', 'wp-travel-engine' ); ?>
			#{{{ data.index + 1 }}}
			<button type="button" class="wpte-delete-section">
				<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
					<path d="M13.3333 4.99996V4.33329C13.3333 3.39987 13.3333 2.93316 13.1517 2.57664C12.9919 2.26304 12.7369 2.00807 12.4233 1.84828C12.0668 1.66663 11.6001 1.66663 10.6667 1.66663H9.33333C8.39991 1.66663 7.9332 1.66663 7.57668 1.84828C7.26308 2.00807 7.00811 2.26304 6.84832 2.57664C6.66667 2.93316 6.66667 3.39987 6.66667 4.33329V4.99996M8.33333 9.58329V13.75M11.6667 9.58329V13.75M2.5 4.99996H17.5M15.8333 4.99996V14.3333C15.8333 15.7334 15.8333 16.4335 15.5608 16.9683C15.3212 17.4387 14.9387 17.8211 14.4683 18.0608C13.9335 18.3333 13.2335 18.3333 11.8333 18.3333H8.16667C6.76654 18.3333 6.06647 18.3333 5.53169 18.0608C5.06129 17.8211 4.67883 17.4387 4.43915 16.9683C4.16667 16.4335 4.16667 15.7334 4.16667 14.3333V4.99996" stroke="#E84B4B" stroke-width="1.66667" stroke-linecap="round" stroke-linejoin="round" />
				</svg>
			</button>
		</h5>
		<div class="wpte-fields-grid" data-columns="2">
			<?php $payment_edit_fields->render(); ?>
		</div>
	</div>
</script>

<?php
	unset( $extra_fields['deposit'], $extra_fields['amount'] );

	$payments_data = $cart_info->is_curr_cart_ver( '>=' ) ? $booking->get_payments_data() : array();

	$extra_fun = function ( $p_index ) use ( $extra_fields, &$payments_data ) {
		foreach ( $extra_fields as $field => $value ) {

			$payments_data['payments'][ $p_index ][ $field ] = '';

			$apply_tax = $value['behaviours']['apply_tax'] ?? null;
			$label     = $value['field_label'] ?? wptravelengine_get_tax_label();

			$key = $field;
			if ( 'tax' === $field ) {
				$payments_data['totals'][ $key ]['label'] ??= $label;
				$payments_data['totals'][ $key ]['value'] ??= '';
				continue;
			}

			$key  = 'tax_exclusive';
			$_key = 'tax_inclusive';
			if ( $apply_tax ) {
				$key  = 'tax_inclusive';
				$_key = 'tax_exclusive';
			}

			if ( isset( $payments_data['totals'][ $_key ][ $field ] ) ) {
				continue;
			}

			$payments_data['totals'][ $key ][ $field ]['label'] ??= $label;
			$payments_data['totals'][ $key ][ $field ]['value'] ??= '';
		}
	};

	if ( empty( $payments_data['payments'] ) ) {
		$payments_data['payments'] = array();
		$extra_fun( 0 );
	} else {
		foreach ( $payments_data['payments'] as $p_id => $val1 ) {
			$extra_fun( $p_id );
		}
		if ( count( $payments_data['payments'] ) > 1 ) {
			array_pop( $payments_data['payments'] );
		}
	}
	$payments_data['payments'][] = array();

	$payments_total = $payments_data['totals'];
	$all_payments   = $payments_data['payments'];

	wptravelengine_set_template_args( array( 'temp_payments_data' => $payments_data ) );
	?>

<!-- Payment Summary Items -->
<script type="text/html" id="tmpl-payment-summary-card">
	<div class="wpte-payment-card wpte-accordion">
		<div class="wpte-accordion-header">
			<h3 class="wpte-accordion-title wpte-payment-card-title"><?php echo esc_html( __( 'Payment', 'wp-travel-engine' ) . ' #' ); ?>{{{ data.index + 1 }}}</h3>
			<button type="button" class="wpte-accordion-toggle">
				<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
					<path d="M5 7.5L10 12.5L15 7.5" stroke="currentColor" stroke-width="1.66667" stroke-linecap="round"
						stroke-linejoin="round" />
				</svg>
			</button>
		</div>
		<div class="wpte-accordion-content">
			<table class="wpte-payment-card-table">
				<tr class="wpte-payment-deposit">
					<td><?php esc_html_e( 'Deposit Amount', 'wp-travel-engine' ); ?></td>
					<td><?php wptravelengine_the_price( '', true, $pricing_arguments ); ?></td>
				</tr>
				<?php
				foreach ( $payments_total['tax_inclusive'] ?? array() as $fee_name => $fee ) {
					printf(
						'<tr class="wpte-payment-%1$s"><td>%2$s</td><td><strong>%3$s</strong></td></tr>',
						esc_attr( $fee_name ),
						esc_html( $fee['label'] ),
						wptravelengine_the_price( '', false, $pricing_arguments )
					);
				}

				if ( isset( $payments_total['tax'] ) ) {
					printf(
						'<tr class="wpte-payment-tax"><td>%1$s</td><td><strong>%2$s</strong></td></tr>',
						esc_html( $payments_total['tax']['label'] ),
						wptravelengine_the_price( '', false, $pricing_arguments )
					);
				}

				foreach ( $payments_total['tax_exclusive'] ?? array() as $fee_name => $fee ) {
					printf(
						'<tr class="wpte-payment-%1$s"><td>%2$s</td><td><strong>%3$s</strong></td></tr>',
						esc_attr( $fee_name ),
						esc_html( $fee['label'] ),
						wptravelengine_the_price( '', false, $pricing_arguments )
					);
				}
				?>
				<tr class="wpte-payment-total wpte-payment-amount">
					<td><?php esc_html_e( 'Amount Paid', 'wp-travel-engine' ); ?></td>
					<td><?php wptravelengine_the_price( '', true, $pricing_arguments ); ?></td>
				</tr>
			</table>
		</div>
	</div>
</script>

<!-- Payment Summary Total -->
<script type="text/html" id="tmpl-payment-summary-card-total"> 
	<div class="wpte-payment-summary-card">
		<h3 class="wpte-payment-summary-title"><?php esc_html_e( 'Payment Summary', 'wp-travel-engine' ); ?></h3>
		<table class="wpte-payment-summary-table">
			<tr>
				<td><?php esc_html_e( 'Total Deposit Amount', 'wp-travel-engine' ); ?></td>
				<td><?php wptravelengine_the_price( '', true, $pricing_arguments ); ?></td>
			</tr>
			<?php
			foreach ( $payments_total['tax_inclusive'] ?? array() as $fee_name => $fee ) {
				printf(
					'<tr class="wpte-payment-%1$s"><td>%2$s</td><td><strong>%3$s</strong></td></tr>',
					esc_attr( $fee_name ),
					esc_html( $fee['label'] ),
					wptravelengine_the_price( '', false, $pricing_arguments )
				);
			}

			if ( isset( $payments_total['tax'] ) ) {
				printf(
					'<tr class="wpte-payment-tax"><td>%1$s</td><td><strong>%2$s</strong></td></tr>',
					esc_html( $payments_total['tax']['label'] ),
					wptravelengine_the_price( '', false, $pricing_arguments )
				);
			}

			foreach ( $payments_total['tax_exclusive'] ?? array() as $fee_name => $fee ) {
				printf(
					'<tr class="wpte-payment-%1$s"><td>%2$s</td><td><strong>%3$s</strong></td></tr>',
					esc_attr( $fee_name ),
					esc_html( $fee['label'] ),
					wptravelengine_the_price( '', false, $pricing_arguments )
				);
			}
			?>
			<tr class="wpte-payment-summary-total wpte-payment-amount">
				<td><?php esc_html_e( 'Total Amount Paid', 'wp-travel-engine' ); ?></td>
				<td class="wpte-payment-summary-amount"><?php wptravelengine_the_price( $payments_total['total'] ?? 0, true, $pricing_arguments ); ?></td>
			</tr>
		</table>
	</div>
</script>