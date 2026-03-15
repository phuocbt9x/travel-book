<?php
/**
 * Cart Info Parser.
 *
 * @since 6.4.0
 */

namespace WPTravelEngine\Helpers;

use WPTravelEngine\Utilities\ArrayUtility;

class CartInfoParser {

	/**
	 * @var array
	 */
	protected array $data;

	/**
	 * @var array
	 */
	protected array $totals = array();

	/**
	 * @var array
	 */
	protected array $deductible_items = array();

	/**
	 * @var array
	 */
	protected array $fees = array();

	/**
	 * @var array|mixed
	 */
	protected array $items = array();

	/**
	 * @var string
	 */
	protected string $currency;

	/**
	 * Stores the cart fees by type.
	 *
	 * @var array
	 * @since 6.7.0
	 */
	protected array $fees_by_type = array();

	/**
	 * Stores the cart version.
	 *
	 * @var string
	 * @since 6.7.0
	 */
	public string $version = '3.0';

	public function __construct( array $data ) {
		$this->data    = $data;
		$this->version = $data['version'] ?? '3.0';
		$this->parse( $data );
	}

	protected function parse( $data ) {
		$this->totals           = $data['totals'] ?? array();
		$this->deductible_items = $this->parse_deductible_items( $data );
		$this->fees             = $this->parse_fees( $data );
		$this->items            = $data['items'] ?? array();
		$this->currency         = $data['currency'] ?? '';
	}

	protected function parse_deductible_items( $data ) {
		if ( isset( $data['deductible_items'] ) ) {
			return $data['deductible_items'];
		}

		if ( ! empty( $data['discounts'] ) ) {
			return array_map(
				function ( $item ) {
					return array(
						'name'                     => 'coupon',
						'order'                    => '-1',
						'label'                    => $item['name'],
						'description'              => '',
						'adjustment_type'          => $item['type'],
						'apply_to_actual_subtotal' => true,
						'percentage'               => $item['value'],
						'value'                    => $this->totals['discount_total'] ?? 0,
					);
				},
				$data['discounts']
			);
		}

		return array();
	}

	/**
	 * Parses the fees.
	 *
	 * @param mixed $data
	 * @return array
	 * @updated 6.7.0
	 */
	protected function parse_fees( $data ) {
		if ( isset( $data['fees'] ) ) {
			$unique_fees = array();
			foreach ( $data['fees'] as $fee ) {
				$apply_tax = $fee['apply_tax'] ?? true;
				if ( 'tax' === $fee['name'] ) {
					$this->fees_by_type['tax'] = $fee;
				} elseif ( $apply_tax ) {
					$this->fees_by_type['tax_inclusive'][] = $fee;
				} else {
					$this->fees_by_type['tax_exclusive'][] = $fee;
				}
				$unique_fees[ $fee['name'] ] = $fee;
			}

			return array_values( $unique_fees );
		}

		if ( ! empty( $data['tax_amount'] ) ) {
			return array(
				array(
					'name'                     => 'tax',
					'order'                    => '-1',
					'label'                    => __( 'Tax', 'wp-travel-engine' ),
					'description'              => '',
					'adjustment_type'          => 'percentage',
					'apply_to_actual_subtotal' => false,
					'percentage'               => $data['tax_amount'],
					'value'                    => $this->totals['total_tax'] ?? 0,
				),
			);
		}

		return array();
	}

	/**
	 * @param string|null $key
	 *
	 * @return array|float|null
	 */
	public function get_totals( string $key = null ) {
		if ( $key ) {
			$value = $this->totals[ $key ] ?? 0;
			return is_numeric( $value ) ? round( $value, 2 ) : 0;
		}

		return $this->totals;
	}

	public function get_deductible_items(): array {
		return array_map(
			function ( $item ) {
				if ( ! isset( $item['value'] ) ) {
						$item['value'] = $this->get_totals( 'total_' . $item['name'] );
				}

				return $item;
			},
			$this->deductible_items
		);
	}

	public function get_fees(): array {
		return array_map(
			function ( $item ) {
				if ( ! isset( $item['value'] ) ) {
						$item['value'] = $this->get_totals( 'total_' . $item['name'] );
				}

				return $item;
			},
			$this->fees
		);
	}

	/**
	 * @return BookedItem[]
	 */
	public function get_items(): array {
		return array_map(
			function ( $line ) {
				return new BookedItem( $line );
			},
			$this->items
		);
	}

	public function get_item( string $id = null ): BookedItem {
		if ( $id ) {
			foreach ( $this->get_items() as $item ) {
				if ( $item['id'] == $id ) {
					return $item;
				}
			}
		}

		return $this->get_items()[0] ?? new BookedItem( array() );
	}

	public function get_currency(): string {
		return $this->currency;
	}

	public function __get( $key ) {
		if ( method_exists( $this, "get_{$key}" ) ) {
			return $this->{"get_{$key}"}();
		}

		return $this->{$key} ?? $this->data[ $key ] ?? null;
	}

	/**
	 * Checks if the cart version is 4.0.
	 *
	 * @param string $op Operator to compare.
	 * @param string $ver Version to compare with. Default is '4.0'.
	 *
	 * @return bool
	 * @since 6.7.0
	 */
	public function is_curr_cart_ver( string $op = '==', string $ver = '4.0' ): bool {
		return version_compare( $this->version, $ver, $op );
	}
}
