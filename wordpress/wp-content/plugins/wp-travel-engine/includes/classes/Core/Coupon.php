<?php
/**
 * Class Coupon
 *
 * This class represents a coupon in the WP Travel Engine plugin.
 *
 * @since 5.7.4
 */

namespace WPTravelEngine\Core;

/**
 * Class Coupon
 *
 * This class represents a coupon in the WP Travel Engine plugin.
 *
 * @since 5.7.4
 */
class Coupon {

	protected static string $post_type = 'wte-coupon';

	/**
	 * @var string
	 */
	protected string $name;

	/**
	 * @var int
	 */
	protected int $id;

	/**
	 * @var string
	 */
	protected $code;

	/**
	 * @var array
	 */
	protected array $settings = array();

	/**
	 * @var string
	 * @since 6.0.0
	 */
	protected $type = '';

	/**
	 * @var float
	 * @since 6.0.0
	 */
	protected float $value = 0;

	public function __construct( int $coupon_id ) {
		$coupon = get_post( $coupon_id );
		if ( ! $coupon || $coupon->post_type !== static::$post_type ) {
			throw new \InvalidArgumentException( 'Invalid coupon ID' );
		}

		$settings = get_post_meta( $coupon_id, 'wp_travel_engine_coupon_metas', true );
		if ( is_array( $settings ) ) {
			$this->settings = $settings;
		}

		$this->id    = $coupon_id;
		$this->name  = get_the_title( $coupon_id );
		$this->code  = get_post_meta( $coupon_id, 'wp_travel_engine_coupon_code', true );
		$this->type  = $this->settings['general']['coupon_type'] ?? '';
		$this->value = $this->settings['general']['coupon_value'] ?? 0;
	}

	/**
	 * Retrieves a coupon object by its code.
	 *
	 * @param string $discount_code The code of the coupon to retrieve.
	 *
	 * @return \WP_Error|self Returns a coupon object if found, or a WP_Error object if not found.
	 */
	public static function by_code( string $discount_code ) {
		$args = array(
			'post_type'      => static::$post_type,
			'meta_query'     => array(
				array(
					'key'   => 'wp_travel_engine_coupon_code',
					'value' => $discount_code,
				),
			),
			'fields'         => 'ids',
			'posts_per_page' => 1,
		);

		$post_ids = get_posts( $args );

		if ( isset( $post_ids[0] ) ) {
			try {
				return new self( (int) $post_ids[0] );
			} catch ( \InvalidArgumentException $e ) {
				return new \WP_Error( 'coupon_not_found', __( 'Coupon not found', 'wp-travel-engine' ) );
			}
		}

		return new \WP_Error( 'coupon_not_found', __( 'Coupon not found', 'wp-travel-engine' ) );
	}

	public function is_active(): bool {
		return get_post_status( $this->id ) === 'publish';
	}

	public function id() {
		return $this->id;
	}

	public function code() {
		return $this->code;
	}

	public function name(): string {
		return $this->name;
	}

	public function type() {
		return $this->type;
	}

	public function value() {
		return $this->value;
	}

	public function start_date() {
		return $this->settings['general']['coupon_start_date'] ?? '';
	}

	public function expiry_date() {
		return $this->settings['general']['coupon_expiry_date'] ?? '';
	}

	public function limit() {
		return $this->settings['restriction']['coupon_limit_number'] ?? INF;
	}

	public function allowed_trips() {
		return $this->settings['restriction']['restricted_trips'] ?? array();
	}

	public function is_expired(): bool {
		$expiry_date = $this->expiry_date();
		if ( empty( $expiry_date ) ) {
			return false;
		}

		$expiry_date = strtotime( $expiry_date );
		if ( $expiry_date < time() ) {
			return true;
		}

		return false;
	}

	/**
	 * Checks if the discount coupon is valid for a given trip.
	 *
	 * @param int $trip_id Trip ID to check.
	 *
	 * @return bool
	 */
	public function is_valid_for_trip( int $trip_id ): bool {
		$allowed_trips = $this->allowed_trips();
		if ( empty( $allowed_trips ) ) {
			return true;
		}

		return in_array( $trip_id, $allowed_trips );
	}

	public function has_limit(): bool {
		return $this->limit() > 0;
	}

	public function calculated_value( $total ) {
		$coupon_type  = $this->type();
		$coupon_value = $this->value();

		return static::calculate_value( $total, $coupon_type, $coupon_value );
	}

	/**
	 * Calculate discount amount by coupon type and value.
	 *
	 * @param float  $total
	 * @param string $coupon_type percentage|fixed
	 * @param float  $coupon_value
	 *
	 * @return float
	 * @since 6.0.0
	 */
	public static function calculate_value( $total, $coupon_type, $coupon_value ) {
		if ( $coupon_type === 'percentage' ) {
			return ( $total * $coupon_value ) / 100;
		}

		return $coupon_value;
	}

	/**
	 * @param $type
	 *
	 * @return void
	 * @since 6.0.0
	 */
	public function set_type( $type ) {
		$this->type = $type;
	}
}
