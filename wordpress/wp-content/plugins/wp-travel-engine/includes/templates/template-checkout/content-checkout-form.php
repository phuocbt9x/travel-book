<?php
/**
 * Checkout form template content.
 *
 * @since 6.3.0
 */

do_action( 'wptravelengine_checkout_before_form' );

do_action( 'checkout_template_parts_lead-travellers-details' );

do_action( 'checkout_template_parts_travellers-details' );

do_action( 'checkout_template_parts_emergency-details' );

do_action( 'checkout_template_parts_billing-details' );

do_action( 'checkout_template_parts_checkout-note' );

do_action( 'checkout_template_parts_payments' );

do_action( 'wptravelengine_checkout_after_form' );
