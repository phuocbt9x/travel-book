<?php

namespace WPTravelEngine\Core;

use WPTravelEngine\Interfaces\Tax as TaxInterface;

class Tax implements TaxInterface {

	protected array $settings = array();

	/**
	 * Tax percentage
	 *
	 * @var float
	 */
	protected float $tax_percentage = 0;

	public function __construct() {
		$settings       = get_option( 'wp_travel_engine_settings', array() );
		$this->settings = is_array( $settings ) ? $settings : array();

		$this->tax_percentage = (float) ( $this->settings['tax_percentage'] ?? 0 );
	}

	/**
	 * Get Tax label
	 *
	 * @param null $tax_percentage
	 *
	 * @return string
	 */
	public function get_tax_label( $tax_percentage = null ): string {
		$label_format = $this->settings['tax_label'] ?? __( 'Tax (%s%%)', 'wp-travel-engine' );

		if ( is_null( $tax_percentage ) ) {
			$tax_percentage = $this->tax_percentage;
		}

		return apply_filters( __FUNCTION__, sprintf( $label_format, $tax_percentage ), $tax_percentage );
	}

	/**
	 * Get Tax percentage
	 *
	 * @return float
	 */
	public function get_tax_percentage(): float {
		return $this->tax_percentage;
	}

	/**
	 * Get Tax amount
	 *
	 * @param float $price
	 *
	 * @return float
	 */
	public function get_tax_amount( float $price ): float {
		$tax_percentage = $this->get_tax_percentage();

		return ( $price * $tax_percentage ) / 100;
	}

	public function is_inclusive(): bool {
		return ( $this->settings['tax_type_option'] ?? false ) === 'inclusive';
	}

	public function is_exclusive(): bool {
		return ( $this->settings['tax_type_option'] ?? false ) === 'exclusive';
	}

	public function is_taxable(): bool {
		return ( $this->settings['tax_enable'] ?? 'no' ) === 'yes';
	}

	/**
	 * Set tax percentage
	 *
	 * @since 6.0.0
	 */
	public function set_tax_percentage( float $percentage ) {
		$this->tax_percentage = $percentage;
	}
}
