<?php
/**
 * Display commission details.
 *
 * @param  object $booking
 * @return void
 */
if ( ! function_exists( 'slicewp_get_commissions' ) || ! function_exists( 'slicewp_get_affiliate' ) ) {
	return;
}

$commission_detail = slicewp_get_commissions(
	array(
		'number'    => -1,
		'reference' => $booking->ID,
		'origin'    => 'wptravelengine',
		'order'     => 'ASC',
	)
);

if ( empty( $commission_detail ) ) {
	return;
}

$obj = \wte_functions();
foreach ( $commission_detail as $commission ) {
	$currency       = $commission->get( 'currency' );
	$commission_id  = $commission->get( 'id' );
	$commission_amt = $commission->get( 'amount' );
	$affiliate_id   = $commission->get( 'affiliate_id' );
	$affiliate_obj  = slicewp_get_affiliate( $affiliate_id );
	$currency_symb  = $obj->wp_travel_engine_currencies_symbol( $currency ) ?? '';

	$affiliate = ! is_null( $affiliate_obj ) ? $affiliate_obj->get( 'payment_email' ) : '';
	// if not edit mode
	?>
<div class="wpte-commission-details">
	<h5 class="wpte-commission-details-title"><?php echo __( 'Commission Details', 'wp-travel-engine' ); ?></h5>
	<div class="wpte-fields-grid">
		<div class="wpte-field">
			<label for="affiliate"><?php echo __( 'Affiliate', 'wp-travel-engine' ); ?></label>
			<input type="text" name="affiliate" id="affiliate" value="<?php echo esc_attr( $affiliate ); ?>" readonly/>
		</div>
		<div class="wpte-field">
			<label for="amount"><?php echo __( 'Amount', 'wp-travel-engine' ); ?></label>
			<div class="wpte-amount-wrap">
				<span class="wpte-field-suffix wpte-amount-currency"><?php echo esc_html( $currency ) . ' ' . esc_html( $currency_symb ); ?></span>
				<input type="text" value ='<?php echo esc_attr( $commission_amt ); ?>' readonly>
			</div>
		</div>
	</div>
</div>
	<?php
}?>