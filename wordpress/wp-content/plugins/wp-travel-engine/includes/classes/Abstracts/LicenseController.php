<?php
/**
 * License Controller
 *
 * @since 6.2.1
 */

namespace WPTravelEngine\Abstracts;

use DateTime;
use Exception;

abstract class LicenseController {

	public string $slug = '';

	public int $item_id = 0;

	public string $item_name = '';

	protected string $license = '';

	protected DateTime $expiry_datetime;

	protected string $status;

	protected string $customer_name = '';

	protected string $customer_email = '';

	protected int $limit = 0;

	protected int $activations_left = 0;

	public function __construct( $item_id, $license ) {
		$this->item_id = $item_id;
		$this->license = $license;
	}

	public function license(): string {
		return $this->license;
	}

	/**
	 * Set Expiry Date.
	 *
	 * $datetime string|DateTime
	 *
	 * @since 2.4.0
	 */
	public function set_expiry_datetime( $datetime ) {
		try {
			if ( ! $datetime instanceof DateTime ) {
				if ( 'lifetime' === $datetime ) {
					$datetime = new DateTime( '9999-12-31 23:59:59' );
				} else {
					$datetime = new DateTime( $datetime );
				}
			}
		} catch ( Exception $e ) {
			$datetime = new DateTime();
		}

		$this->expiry_datetime = $datetime;
	}

	/**
	 * Get Expiry Date.
	 *
	 * @since 2.4.0
	 */
	public function expiry_datetime(): DateTime {
		return $this->expiry_datetime;
	}

	/**
	 * Check if the license is expired.
	 *
	 * @since 2.4.0
	 */
	public function expired(): bool {
		$now = new DateTime();

		return $now > $this->expiry_datetime;
	}

	/**
	 * Get License Key.
	 *
	 * @since 2.4.0
	 */
	public function set_status( string $status ) {
		$this->status = $status;
	}

	/**
	 * Get License Key.
	 *
	 * @since 2.4.0
	 */
	public function get_status(): string {
		return $this->status;
	}

	/**
	 * Is License Valid.
	 *
	 * @since 2.4.0
	 */
	public function valid(): bool {
		return ! $this->expired() && ! $this->invalid();
	}

	/**
	 * Is License Invalid.
	 *
	 * @since 2.4.0
	 */
	public function invalid(): bool {
		return $this->status === 'invalid';
	}

	public function set_customer_name( string $customer_name ) {
		$this->customer_name = $customer_name;
	}

	public function customer_name(): string {
		return $this->customer_name;
	}

	public function set_customer_email( string $customer_email ) {
		$this->customer_email = $customer_email;
	}

	public function customer_email(): string {
		return $this->customer_email;
	}

	public function set_limit( $limit ): string {
		return $this->limit = $limit;
	}

	public function limit(): string {
		return $this->limit;
	}

	public function set_activations_left( $activations_left ): string {
		return $this->activations_left = $activations_left;
	}

	public function activations_left(): string {
		return $this->activations_left;
	}

	public function days_left() {
		$now  = new DateTime();
		$diff = $now->diff( $this->expiry_datetime );

		return $diff->days;
	}

	public function is_lifetime(): bool {
		return $this->expiry_datetime->format( 'Y-m-d H:i:s' ) === '9999-12-31 23:59:59';
	}
}
