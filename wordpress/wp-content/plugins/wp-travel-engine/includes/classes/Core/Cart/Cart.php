<?php
/**
 * WP Travel Engine Core Cart.
 *
 * @package WP Travel Engine
 */

namespace WPTravelEngine\Core\Cart;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use WPTravelEngine\Core\PartialPayment;
use WPTravelEngine\Legacy\Cart as LegacyCart;
use WPTravelEngine\PaymentGateways\BaseGateway;
use WPTravelEngine\PaymentGateways\PaymentGateways;
use WPTravelEngine\Core\Cart\Adjustments\TaxAdjustment;
use WPTravelEngine\Utilities\PaymentCalculator;
use WPTravelEngine\Abstracts\CartAdjustment;

/**
 * WP Travel Engine Cart Class.
 */
class Cart extends LegacyCart {

	/**
	 * The current cart version.
	 *
	 * @var string
	 * @since 6.7.0
	 */
	public const CURRENT_VERSION = '4.0';

	/**
	 * @inheritDoc
	 */
	public function __construct() {
		$this->version = self::CURRENT_VERSION;
		parent::__construct();
	}

	/**
	 * Compares cart version with the provided version.
	 *
	 * @param string $op Operator to compare.
	 * @param string $ver Version to compare with. Default is '4.0'.
	 *
	 * @return bool
	 * @since 6.7.0
	 */
	public function is_curr_cart( string $op = '==', string $ver = '4.0' ): bool {
		return version_compare( $this->version, $ver, $op );
	}

	/**
	 * @inheritDoc
	 * @updated 6.7.0
	 */
	public function calculate_totals(): void {

		if ( ! $this->booking_ref ) {
			$payment_gateway_obj = PaymentGateways::instance()->get_payment_gateway( $this->payment_gateway );
			$this->version       = ( $payment_gateway_obj instanceof BaseGateway ) ? $payment_gateway_obj::$cart_version : $this->version;
		}

		$this->reset_totals();

		do_action( 'wptravelengine_before_calculate_totals', $this );

		// Here the other cart versions can be handled in the future.
		if ( $this->is_curr_cart( '<' ) ) {
			parent::calculate_totals();
		} elseif ( $this->is_curr_cart() ) {
			$this->calculate_totals_v4_0();
		}

		do_action( 'wptravelengine_after_calculate_totals', $this );
	}

	/**
	 * Calculate cart totals with precise financial calculations.
	 *
	 * @return void
	 * @since 6.7.0
	 */
	protected function calculate_totals_v4_0(): void {
		// Initialize calculator
		$calculator = PaymentCalculator::for( wptravelengine_settings()->get( 'currency_code', 'USD' ) );

		$totals                        = $this->totals;
		$totals['payable_now']         = '0.00';
		$totals['total_extra_charges'] = '0.00';

		$set_to_total = function ( $key, $value ) use ( &$totals, $calculator ) {
			$current        = $totals[ $key ] ?? '0.00';
			$totals[ $key ] = $calculator->add( (string) $current, (string) $value );
		};

		$fees_arr = $this->get_fees_by_type();

		foreach ( $this->items as $item ) {
			$item->calculate_totals();

			$temp_totals = array(
				'deductible_fees'    => '0.00',
				'tax_inclusive_fees' => '0.00',
				'tax_exclusive_fees' => '0.00',
			);

			// Calculate deductible items
			foreach ( $this->get_deductible_items() as $deductible_item ) {
				$key = "total_{$deductible_item->name}";
				$val = (string) $item->get_totals( $key );
				$set_to_total( $key, $val );
				$temp_totals['deductible_fees'] = $calculator->add(
					$temp_totals['deductible_fees'],
					$val
				);
			}

			// Calculate subtotal and total
			$item_subtotal      = (string) $item->get_totals( 'subtotal' );
			$totals['subtotal'] = isset( $totals['subtotal'] )
				? $calculator->add( $totals['subtotal'], $item_subtotal )
				: $calculator->normalize( $item_subtotal );

			$totals['total'] = $calculator->subtract(
				$totals['subtotal'],
				$temp_totals['deductible_fees']
			);

			// Determine amount to apply fees to
			$amount = '0.00';
			if ( $this->booking_ref ) {
				$due_amount = get_post_meta( $this->booking_ref, 'total_due_amount', true );
				$amount     = ! empty( $due_amount ) ? (string) $due_amount : '0.00';

				if ( $calculator->compare( $amount, '0.00' ) === 0 ) {
					$amount = $totals['total'];
				}

				$totals['partial_total'] = $calculator->subtract( $totals['total'], $amount );
			} else {
				// Calculate partial total
				$totals['partial_total'] = $calculator->normalize(
					PartialPayment::instance()->apply_to_cart_item(
						$item,
						(float) $totals['total']
					)
				);
				$amount                  = 'partial' === $this->payment_type
					? $totals['partial_total']
					: $totals['total'];
			}

			// Apply tax-inclusive fees
			foreach ( $fees_arr['tax_inclusive'] ?? array() as $inc_fee ) {
				if ( $this->booking_ref && $inc_fee->apply_upfront ) {
					continue;
				}

				$key = "total_{$inc_fee->name}";
				$amt = $inc_fee->apply_upfront ? $totals['total'] : $amount;
				$val = (string) $inc_fee->apply( (float) $amt, $item );
				$set_to_total( $key, $val );
				$temp_totals['tax_inclusive_fees'] = $calculator->add(
					$temp_totals['tax_inclusive_fees'],
					$val
				);
			}

			// Calculate payable now (amount + tax-inclusive fees)
			$totals['payable_now'] = $calculator->add(
				$amount,
				$temp_totals['tax_inclusive_fees']
			);

			// Apply tax
			if ( ( $fees_arr['tax'] ?? null ) instanceof TaxAdjustment ) {
				$val = (string) $fees_arr['tax']->apply( (float) $totals['payable_now'], $item );
				$set_to_total( 'total_tax', $val );
			}

			// Apply tax-exclusive fees
			foreach ( $fees_arr['tax_exclusive'] ?? array() as $exc_fee ) {
				if ( $this->booking_ref && $exc_fee->apply_upfront ) {
					continue;
				}

				$key = "total_{$exc_fee->name}";
				$amt = $exc_fee->apply_upfront ? $totals['total'] : $amount;
				$val = (string) $exc_fee->apply( (float) $amt, $item );
				$set_to_total( $key, $val );
				$temp_totals['tax_exclusive_fees'] = $calculator->add(
					$temp_totals['tax_exclusive_fees'],
					$val
				);
			}

			// Add tax and tax-exclusive fees to payable now
			$tax_and_exclusive     = $calculator->add(
				$totals['total_tax'] ?? '0.00',
				$temp_totals['tax_exclusive_fees']
			);
			$totals['payable_now'] = $calculator->add(
				$totals['payable_now'],
				$tax_and_exclusive
			);

			// Calculate due total
			$totals['due_total'] = $calculator->subtract(
				$totals['total'],
				$totals['partial_total']
			);

			// Calculate total extra charges
			$base_amount   = $this->booking_ref ? $totals['due_total'] : $amount;
			$extra_charges = $calculator->subtract(
				$totals['payable_now'],
				$base_amount
			);

			$totals['total_extra_charges'] = $calculator->add(
				$totals['total_extra_charges'],
				$extra_charges
			);

			$totals['deposit'] = $calculator->subtract(
				$totals['payable_now'],
				$totals['total_extra_charges']
			);
		}

		$this->totals = apply_filters( 'wptravelengine_cart_calculate_totals', $totals, $this );
	}

	/**
	 * Retrieves all fees segmented by tax type.
	 *
	 * Returns an array with keys:
	 *  - 'tax'            => main tax fee
	 *  - 'tax_inclusive'  => fees with tax applied
	 *  - 'tax_exclusive'  => fees without tax
	 *
	 * @param string|null $key Optional. Specific fee segment key to retrieve.
	 *
	 * @return array
	 * @since 6.7.0
	 */
	public function get_fees_by_type( $key = null ) {
		$fees_arr = array();

		foreach ( $this->fees as $fee ) {
			if ( 'tax' === $fee->name ) {
				$fees_arr['tax'] = $fee;
			} elseif ( $fee->apply_tax ) {
				$fees_arr['tax_inclusive'][] = $fee;
			} else {
				$fees_arr['tax_exclusive'][] = $fee;
			}
		}

		return is_null( $key ) ? $fees_arr : ( $fees_arr[ $key ] ?? array() );
	}

	/**
	 * Get total payable now.
	 *
	 * @return float|string Numeric value
	 * @since 6.7.0
	 */
	public function get_total_payable_amount() {
		if ( $this->is_curr_cart() ) {
			return $this->totals['payable_now'] ?? '0.00';
		}

		if ( $this->is_curr_cart( '<' ) ) {
			if ( 'partial' == $this->payment_type ) {
				return $this->totals['partial_total'];
			} elseif ( in_array( $this->payment_type, array( 'due', 'remaining_payment' ) ) ) {
				return $this->totals['due_total'];
			} else {
				return $this->totals['total'];
			}
		}

		return $this->totals['total'];
	}

	/**
	 * Get exclusion fees label.
	 *
	 * @param array|null $fees Optional. Fees to get exclusion label for. Default null.
	 *
	 * @return string
	 * @since 6.7.0
	 */
	public function get_exclusion_label( $fees = null ): string {
		$excl   = '';
		$fees ??= $this->get_fees();
		$count = count( $fees );

		for ( $i = $count - 1; $i >= 0; $i-- ) {
			$label = $fees[ $i ]->label ?? $fees[ $i ]['label'] ?? '';
			$label = trim( preg_replace( '/\(.*$/', '', $label ) );
			$excl .= $label . ( $i === 1 ? ' & ' : ( $i > 0 ? ', ' : '' ) );
		}

		return $excl;
	}
}
