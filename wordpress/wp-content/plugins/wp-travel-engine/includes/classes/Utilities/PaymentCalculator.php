<?php
/**
 * Payment Calculator for precise financial calculations.
 *
 * @package WPTravelEngine\Utilities
 * @subpackage MoneyMath
 * @since 6.7.0
 */
declare(strict_types=1);

namespace WPTravelEngine\Utilities;

use InvalidArgumentException;
use WPTravelEngine\Traits\Factory;

/**
 * PaymentCalculator class for handling precise monetary calculations.
 * Uses BCMath extension for arbitrary precision arithmetic.
 */
final class PaymentCalculator {

	use Factory;

	/**
	 * Number of decimal places for each currency.
	 *
	 * @var array<string, int>
	 */
	private const SCALE = array(
		'USD' => 2,
	);

	/**
	 * Number of decimal places for calculations.
	 *
	 * @var int
	 */
	private int $scale;

	/**
	 * Has bcmath extension.
	 *
	 * @var bool
	 */
	private bool $has_bcmath;

	/**
	 * Constructor.
	 *
	 * @param string   $currency Currency code.
	 * @param int|null $scale Number of decimal places.
	 *
	 * @since 6.7.1 Added $scale parameter.
	 */
	public function __construct( string $currency = 'USD', $scale = null ) {
		$this->scale      = $scale ?? self::SCALE[ $currency ] ?? 2;
		$this->has_bcmath = extension_loaded( 'bcmath' );

		if ( $this->has_bcmath ) {
			bcscale( $this->scale );
		}
	}

	/**
	 * Add two numbers.
	 *
	 * @param string $a First number.
	 * @param string $b Second number.
	 * @return string Result of addition.
	 */
	public function add( string $a, string $b ): string {
		if ( $this->has_bcmath ) {
			return bcadd( $a, $b, $this->scale );
		}
		return number_format( floatval( $a ) + floatval( $b ), $this->scale, '.', '' );
	}

	/**
	 * Subtract second number from first.
	 *
	 * @param string $a First number.
	 * @param string $b Second number (to subtract).
	 * @return string Result of subtraction.
	 */
	public function subtract( string $a, string $b ): string {
		if ( $this->has_bcmath ) {
			return bcsub( $a, $b, $this->scale );
		}
		return number_format( floatval( $a ) - floatval( $b ), $this->scale, '.', '' );
	}

	/**
	 * Multiply two numbers.
	 *
	 * @param string $a First number.
	 * @param string $b Second number.
	 * @return string Result of multiplication.
	 */
	public function multiply( string $a, string $b ): string {
		if ( $this->has_bcmath ) {
			return bcmul( $a, $b, $this->scale );
		}
		return number_format( floatval( $a ) * floatval( $b ), $this->scale, '.', '' );
	}

	/**
	 * Divide first number by second.
	 *
	 * @param string $a Dividend.
	 * @param string $b Divisor.
	 * @return string Result of division.
	 * @throws InvalidArgumentException If divisor is zero.
	 */
	public function divide( string $a, string $b ): string {
		if ( $this->has_bcmath ) {
			if ( bccomp( $b, '0', $this->scale ) === 0 ) {
				throw new InvalidArgumentException( 'Division by zero is not allowed' );
			}
			return bcdiv( $a, $b, $this->scale );
		}
		if ( floatval( $b ) === 0 ) {
			throw new InvalidArgumentException( 'Division by zero is not allowed' );
		}
		return number_format( floatval( $a ) / floatval( $b ), $this->scale, '.', '' );
	}

	/**
	 * Calculate tax amount.
	 *
	 * @param string $amount Base amount.
	 * @param string $taxRate Tax rate (e.g., '0.13' for 13%).
	 * @return string Calculated tax amount.
	 */
	public function calculate_tax( string $amount, string $taxRate ): string {
		if ( $this->has_bcmath ) {
			return bcmul( $amount, $taxRate, $this->scale );
		}
		return $this->multiply( $amount, $taxRate );
	}

	/**
	 * Calculate total with tax and discount.
	 *
	 * @param string $subtotal Subtotal amount.
	 * @param string $tax Tax amount.
	 * @param string $discount Discount amount (default: '0.00').
	 * @return string Final total.
	 */
	public function calculate_total( string $subtotal, string $tax, string $discount = '0.00' ): string {
		$afterDiscount = $this->subtract( $subtotal, $discount );
		return $this->add( $afterDiscount, $tax );
	}

	/**
	 * Compare two numbers.
	 *
	 * @param string $a First number.
	 * @param string $b Second number.
	 * @return int Returns 0 if equal, 1 if $a > $b, -1 if $a < $b.
	 */
	public function compare( string $a, string $b ): int {
		if ( $this->has_bcmath ) {
			return bccomp( $a, $b, $this->scale );
		}
		$a_rounded = round( floatval( $a ), $this->scale );
		$b_rounded = round( floatval( $b ), $this->scale );
		return $a_rounded <=> $b_rounded;
	}

	/**
	 * Get the current scale.
	 *
	 * @return int Current scale value.
	 */
	public function get_scale(): int {
		return $this->scale;
	}

	/**
	 * Normalize the value to the scale.
	 *
	 * @param string $value Value to normalize.
	 *
	 * @return string Normalized value.
	 */
	public function normalize( string $value ): string {
		if ( $this->has_bcmath ) {
			return bcadd( $value, '0', $this->scale );
		}
		return number_format( floatval( $value ), $this->scale, '.', '' );
	}

	/**
	 * Converts the value to positive.
	 *
	 * @param string $value Value to convert.
	 * @return string Positive value.
	 * @since 6.7.7
	 */
	public function abs( string $value ): string {
		$value = (string) abs( floatval( $value ) );
		return $this->normalize( $value );
	}

	/**
	 * Check if the value is equals to the given value.
	 *
	 * @param string $a Value to check.
	 * @param string $operator Operator to compare. e.g. '==', '!=', '>', '<', '>=', '<='.
	 * @param string $b Value to check against.
	 * @return bool True if the value is equals to the given value, false otherwise.
	 * @throws InvalidArgumentException If the operator is invalid.
	 * @since 6.7.7
	 */
	public function is( string $a, string $operator, string $b ) {
		$result = $this->compare( $a, $b );
		switch ( $operator ) {
			case '==':
			case '===':
				return $result === 0;
			case '!=':
				return $result !== 0;
			case '>':
				return $result > 0;
			case '<':
				return $result < 0;
			case '>=':
				return $result >= 0;
			case '<=':
				return $result <= 0;
			default:
				throw new InvalidArgumentException( 'Invalid operator' );
		}
	}

	/**
	 * Check if the value is negative.
	 *
	 * @param string $value Value to check.
	 * @return bool True if the value is negative, false otherwise.
	 * @since 6.7.7
	 */
	public function is_negative( string $value ): bool {
		return $this->is( $value, '<', '0.00' );
	}

	/**
	 * Check if the value is positive.
	 *
	 * @param string $value Value to check.
	 * @return bool True if the value is positive, false otherwise.
	 * @since 6.7.7
	 */
	public function is_positive( string $value ): bool {
		return $this->is( $value, '>', '0.00' );
	}

	/**
	 * Find the highest value among the provided arguments.
	 *
	 * @param string|int|float ...$args The values to compare.
	 * @return string The highest value as a string.
	 * @throws InvalidArgumentException If no arguments are provided.
	 * @since 6.7.7
	 */
	public function max( ...$args ): string {
		// Handle array passed as first argument
		if ( count( $args ) === 1 && is_array( $args[0] ) ) {
			$args = $args[0];
		}

		if ( empty( $args ) ) {
			throw new InvalidArgumentException( 'max(): Array must contain at least one element' );
		}

		if ( ! $this->has_bcmath ) {
			return strval( max( array_map( 'floatval', $args ) ) );
		}

		$max = strval( $args[0] );
		foreach ( $args as $val ) {
			$curr = strval( $val );
			// BCMath doesn't support 'e', so we ignore or convert.
			// Check is_numeric and exclude scientific notation for BCMath safety.
			if ( is_numeric( $curr ) && stripos( $curr, 'e' ) === false ) {
				if ( bccomp( $curr, $max ) === 1 ) {
					$max = $curr;
				}
			}
		}

		return $max;
	}

	/**
	 * Find the lowest value among the provided arguments.
	 *
	 * @param string|int|float ...$args The values to compare.
	 * @return string The lowest value as a string.
	 * @throws InvalidArgumentException If no arguments are provided.
	 * @since 6.7.7
	 */
	public function min( ...$args ): string {
		if ( count( $args ) === 1 && is_array( $args[0] ) ) {
			$args = $args[0];
		}

		if ( empty( $args ) ) {
			throw new InvalidArgumentException( 'min(): Array must contain at least one element' );
		}

		if ( ! $this->has_bcmath ) {
			return strval( min( array_map( 'floatval', $args ) ) );
		}

		$min = strval( $args[0] );
		foreach ( $args as $val ) {
			$curr = strval( $val );
			if ( is_numeric( $curr ) && stripos( $curr, 'e' ) === false ) {
				if ( bccomp( $curr, $min ) === -1 ) {
					$min = $curr;
				}
			}
		}

		return $min;
	}
}
