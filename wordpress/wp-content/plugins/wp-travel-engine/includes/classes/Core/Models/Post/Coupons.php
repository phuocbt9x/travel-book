<?php
/**
 * Coupons Model.
 *
 * @package WPTravelEngine/Core/Models
 * @since 6.0.0
 */

namespace WPTravelEngine\Core\Models\Post;

use WPTravelEngine\Abstracts\PostModel;
use WPTravelEngine\Modules\CouponCode;

/**
 * Class Coupons.
 * This class represents a coupon to the WP Travel Engine plugin.
 *
 * @since 6.0.0
 */
class Coupons extends PostModel {
	/**
	 * Post type name.
	 *
	 * @var string
	 */
	protected string $post_type = 'wte-coupon';

	/**
	 * Retrieves the coupon code.
	 *
	 * @return string Coupon code
	 */
	public function get_coupon_code() {
		return $this->get_meta( 'wp_travel_engine_coupon_code' ) ?? '';
	}

	/**
	 * Retrieves the coupon metadata.
	 *
	 * @return array Coupon metas
	 */
	public function get_coupon_metas() {
		return $this->get_meta( 'wp_travel_engine_coupon_metas' ) ?? array();
	}

	/**
	 * Retrieves general values of the coupon.
	 *
	 * @return array Coupon general values
	 */
	public function get_coupon_general_values() {
		$coupon_metas = $this->get_coupon_metas();
		return $coupon_metas['general'] ?? array();
	}

	/**
	 * Retrieves the coupon type - General values.
	 *
	 * @return string Coupon type
	 */
	public function get_coupon_type() {
		$general_values = $this->get_coupon_general_values();
		return $general_values['coupon_type'] ?? '';
	}

	/**
	 * Retrieves the coupon value - General values.
	 *
	 * @return int Coupon value
	 */
	public function get_coupon_value() {
		$general_values = $this->get_coupon_general_values();
		return $general_values['coupon_value'] ?? '';
	}

	/**
	 * Retrieves the coupon start date - General values.
	 *
	 * @return string Coupon start date
	 */
	public function get_coupon_start_date() {
		$general_values = $this->get_coupon_general_values();
		return $general_values['coupon_start_date'] ?? '';
	}

	/**
	 * Retrieves the coupon expiry date - General values.
	 *
	 * @return string Coupon expiry date
	 */
	public function get_coupon_expiry_date() {
		$general_values = $this->get_coupon_general_values();
		return $general_values['coupon_expiry_date'] ?? '';
	}

	/**
	 * Retrieves restriction values of the coupon.
	 *
	 * @return array Coupon restriction values
	 */
	public function get_coupon_restriction_values() {
		$coupon_metas = $this->get_coupon_metas();
		return $coupon_metas['restriction'] ?? array();
	}

	/**
	 * Retrieves trips linked with the coupon.
	 *
	 * @return array Trips linked with the coupon
	 */
	public function get_coupon_trips() {
		$restriction_values = $this->get_coupon_restriction_values();
		return $restriction_values['restricted_trips'] ?? array();
	}

	/**
	 * Retrieves the coupon limit number.
	 *
	 * @return int Coupon limit number
	 */
	public function get_coupon_limit_number() {
		$restriction_values = $this->get_coupon_restriction_values();
		return $restriction_values['coupon_limit_number'] ?? '';
	}

	/**
	 * Retrieves the coupon usage count.
	 *
	 * @return int Coupon usage count
	 */
	public function get_coupon_usage_count() {
		return $this->get_meta( 'wp_travel_engine_coupon_usage_count' ) ?? 0;
	}

	/**
	 * Get Coupon by coupon code.
	 *
	 * @param string $coupon_code Coupon Code.
	 * @return Coupons|bool
	 */
	public static function by_code( $coupon_code ) {
		$id = CouponCode::coupon_id_by_code( $coupon_code ); // phpcs:ignore
		return $id ? new self( $id ) : false;
	}

	/**
	 * Checks coupon validity.
	 *
	 * @param int $trip_id Trip ID.
	 * @return bool
	 */
	public function is_valid( $trip_id = null ) {
		if ( is_null( $trip_id ) ) {
			return ! CouponCode::is_coupon_date_valid( $this->ID );
		} elseif ( is_numeric( $trip_id ) ) {
			return ! CouponCode::coupon_can_be_applied( $this->ID, $trip_id );
		}
	}
}
