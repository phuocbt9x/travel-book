<?php
/**
 * Customer Model.
 *
 * @package WPTravelEngine/Core/Models
 * @since 6.0.0
 */

namespace WPTravelEngine\Core\Models\Post;

use WPTravelEngine\Abstracts\PostModel;
use WPTravelEngine\Utilities\ArrayUtility;

/**
 * Class Customer.
 * This class represents a customer to the WP Travel Engine plugin.
 *
 * @since 6.0.0
 */
class Customer extends PostModel {

	/**
	 * User Role.
	 */
	const USER_ROLE = 'wp-travel-engine-customer';

	/**
	 * Post type name.
	 *
	 * @var string
	 */
	protected string $post_type = 'customer';

	/**
	 * Customer data.
	 *
	 * @var ?ArrayUtility
	 */
	protected $my_data = null;

	/**
	 * Retrieves customer meta.
	 *
	 * @return array Customer meta
	 */
	public function get_customer_meta() {
		return $this->get_meta( 'wp_travel_engine_booking_setting' ) ?? array();
	}

	/**
	 * Retrieves customer details.
	 *
	 * @return array Customer details
	 */
	public function get_customer_details() {
		$customer_meta    = $this->get_customer_meta();
		$customer_details = $customer_meta['place_order']['booking'] ?? array();

		return $customer_details;
	}

	/**
	 * Retrieves customer first name.
	 *
	 * @return string Customer First Name
	 */
	public function get_customer_fname() {
		$customer_details = $this->get_customer_details();

		return $customer_details['fname'] ?? '';
	}

	/**
	 * Retrieves customer last name.
	 *
	 * @return string Customer Last Name
	 */
	public function get_customer_lname() {
		$customer_details = $this->get_customer_details();

		return $customer_details['lname'] ?? '';
	}

	/**
	 * Retrieves customer email.
	 *
	 * @return string Customer Email
	 */
	public function get_customer_email() {
		$customer_details = $this->get_customer_details();

		return $customer_details['email'] ?? '';
	}

	/**
	 * Retrieves customer address.
	 *
	 * @return string Customer Address
	 */
	public function get_customer_address() {
		$customer_details = $this->get_customer_details();

		return $customer_details['address'] ?? '';
	}

	/**
	 * Retrieves customer city.
	 *
	 * @return string Customer City
	 */
	public function get_customer_city() {
		$customer_details = $this->get_customer_details();

		return $customer_details['city'] ?? '';
	}

	/**
	 * Retrives customer country.
	 */
	public function get_customer_country() {
		$customer_details = $this->get_customer_details();

		return $customer_details['country'] ?? '';
	}

	/**
	 * Retrieves customer post code.
	 *
	 * @return string Customer Post Code
	 */
	public function get_customer_postcode() {
		$customer_details = $this->get_customer_details();

		return $customer_details['postcode'] ?? '';
	}

	/**
	 * Retrieves customer avatar.
	 *
	 * @return string Customer Avatar
	 * @since 6.4.0
	 */
	public function get_customer_avatar() {
		return get_avatar_url( $this->get_customer_email(), array( 'default' => 'mm' ) );
	}

	/**
	 * Retrieves customer phone.
	 *
	 * @return string Customer Phone
	 * @since 6.4.0
	 */
	public function get_customer_phone() {
		$customer_details = $this->get_customer_details();

		return $customer_details['phone'] ?? '';
	}

	/**
	 * Retrieves customer state.
	 *
	 * @return string Customer State
	 * @since 6.4.0
	 */
	public function get_customer_state() {
		$customer_details = $this->get_customer_details();

		return $customer_details['state'] ?? '';
	}

	/**
	 * Retrieves customer notes.
	 *
	 * @return string Customer Notes
	 */
	public function get_customer_notes() {
		$customer_details = $this->get_customer_details();

		return $customer_details['notes'] ?? '';
	}

	/**
	 * Retrieves the IDs of the customer's booked trip.
	 *
	 * @return array Customer Booked Trip IDs
	 */
	public function get_customer_bookings() {
		$bookings_ids = $this->get_meta( 'wp_travel_engine_bookings' );
		$bookings     = array();
		foreach ( is_array( $bookings_ids ) ? $bookings_ids : array() as $booking_id ) {
			try {
				$bookings[] = new Booking( $booking_id );
			} catch ( \Exception $e ) {
				// Do nothing.
			}
		}

		return $bookings;
	}

	/**
	 * Retrieves customer booked trip details.
	 *
	 * @return array Customer Booked Trip Details
	 */
	public function get_customer_booked_trip_settings() {
		return $this->get_meta( 'wp_travel_engine_booked_trip_setting' ) ?? array();
	}

	/**
	 * Customer detail info.
	 *
	 * @return array Customer Detail Info
	 * @since 6.4.0
	 */
	public function get_customer_info() {
		$user = get_user_by( 'email', $this->get_customer_email() );
		$data = array();
		if ( $user instanceof \WP_User ) {
			$data = get_user_meta( $user->ID, 'wp_travel_engine_customer_billing_details', true );
		}

		return array(
			'fname' => $user->first_name ?? $this->get_customer_fname(),
			'lname' => $user->last_name ?? $this->get_customer_lname(),
			'email' => $user->user_email ?? $this->get_customer_email(),
			'phone' => $data['billing_phone'] ?? $this->get_customer_phone(),
		);
	}

	/**
	 * Retrieves customer all address info.
	 *
	 * @return array Customer Address Info
	 * @since 6.4.0
	 */
	public function get_customer_addresses() {
		$user = get_user_by( 'email', $this->get_customer_email() );
		$data = array();
		if ( $user instanceof \WP_User ) {
			$data = get_user_meta( $user->ID, 'wp_travel_engine_customer_billing_details', true );
		}

		return array(
			'address'  => $data['billing_address'] ?? $this->get_customer_address(),
			'city'     => $data['billing_city'] ?? $this->get_customer_city(),
			'state'    => $data['billing_state'] ?? $this->get_customer_state(),
			'postcode' => $data['billing_zip_code'] ?? $this->get_customer_postcode(),
			'country'  => $data['billing_country'] ?? $this->get_customer_country(),
		);
	}

	/**
	 * @return void
	 */
	public function maybe_register_as_user( bool $force = false ) {
		$register_user = $force || wptravelengine_settings()->is( 'generate_user_account', 'yes' );

		if ( $register_user ) {
			$email_address = trim( $this->get_title() );
			$email_address = sanitize_email( $email_address );

			if ( is_email( $email_address ) && ! email_exists( $email_address ) ) {
				$userdata = apply_filters(
					'wp_travel_engine_new_customer_data',
					array(
						'user_login' => $email_address,
						'user_pass'  => '',
						'user_email' => $email_address,
						'role'       => static::USER_ROLE,
						'first_name' => $this->get_customer_fname(),
						'last_name'  => $this->get_customer_lname(),
					)
				);

				$user_id = wp_insert_user( $userdata );

				if ( is_wp_error( $user_id ) ) {
					error_log(
						sprintf(
							'Failed to create user for customer %d: %s',
							$this->get_id(),
							$user_id->get_error_message()
						)
					);
					return;
				}

				update_user_meta( $user_id, 'customer_id', $this->get_id() );
				$data_array = array(
					'billing_address'  => $this->get_customer_address(),
					'billing_city'     => $this->get_customer_city(),
					'billing_zip_code' => $this->get_customer_postcode(),
					'billing_country'  => $this->get_customer_country(),
					'billing_phone'    => $this->get_customer_phone(),
				);
				update_user_meta( $user_id, 'wp_travel_engine_customer_billing_details', $data_array );
				do_action( 'wp_travel_engine_created_customer', $user_id, $userdata, true, 'emails/customer-new-account.php' );
			}
		}
	}

	/**
	 * Create a new post for this post-type.
	 *
	 * @return $this
	 */
	public static function create_post( array $postarr ): Customer {
		/* @var $model Customer */
		$model = parent::create_post( $postarr );

		return $model;
	}

	/**
	 * Update customer bookings.
	 *
	 * @param int $booking_id
	 *
	 * @return $this
	 */
	public function update_customer_bookings( int $booking_id ): Customer {
		$customer_bookings   = $this->get_meta( 'wp_travel_engine_bookings' );
		$customer_bookings   = ! is_array( $customer_bookings ) ? array() : $customer_bookings;
		$customer_bookings[] = $booking_id;

		return $this->set_meta( 'wp_travel_engine_bookings', array_unique( $customer_bookings ) );
	}

	/**
	 * Update customer user meta for bookings.
	 *
	 * @param int $booking_id
	 *
	 * @return void
	 */
	public function update_customer_meta( int $booking_id ): void {

		$meta_mappings = array(
			'wp_travel_engine_bookings' => 'wp_travel_engine_user_bookings',
		);

		$billing_info  = get_post_meta( $booking_id, 'wptravelengine_billing_details', true );
		$billing_email = $billing_info['email'] ?? $this->get_customer_email();

		if ( empty( $billing_email ) || ! is_email( $billing_email ) ) {
			return;
		}

		$user           = get_user_by( 'email', $billing_email );
		$logged_in_user = null;

		if ( is_user_logged_in() ) {
			$logged_in_user = wp_get_current_user();
		}

		// Collect users to update (avoid duplicates).
		$users_to_update = array();
		if ( $user instanceof \WP_User ) {
			$users_to_update[ $user->ID ] = $user;
		}
		if ( $logged_in_user instanceof \WP_User && ! isset( $users_to_update[ $logged_in_user->ID ] ) ) {
			$users_to_update[ $logged_in_user->ID ] = $logged_in_user;
		}

		// Update user meta for all relevant users.
		if ( ! empty( $users_to_update ) ) {
			foreach ( $this->data['__changes'] as $meta_key => $meta_value ) {
				if ( isset( $meta_mappings[ $meta_key ] ) && 'wp_travel_engine_bookings' === $meta_key ) {
					// Get the new bookings array from the customer meta changes
					$new_bookings = is_array( $meta_value ) ? $meta_value : array();

					foreach ( $users_to_update as $user_to_update ) {
						// Get existing user bookings
						$existing_bookings = get_user_meta( $user_to_update->ID, 'wp_travel_engine_user_bookings', true );
						$existing_bookings = is_array( $existing_bookings ) ? $existing_bookings : array();

						// Merge and deduplicate bookings
						$merged_bookings = array_unique( array_merge( $existing_bookings, $new_bookings ) );

						update_user_meta( $user_to_update->ID, 'wp_travel_engine_user_bookings', $merged_bookings );
					}
				}
			}
		}
	}

	/**
	 * Checks if customer exists
	 *
	 * @return int|false
	 */
	public static function is_exists( string $email ) {
		global $wpdb;

		if ( empty( $email ) ) {
			return false;
		}

		$prepared_statement = $wpdb->prepare( "SELECT `ID` FROM {$wpdb->posts} WHERE `post_title` LIKE %s AND `post_type` = %s", '%' . $wpdb->esc_like( sanitize_email( $email ) ) . '%', 'customer' );

		return $wpdb->get_row( $prepared_statement )->ID ?? false;
	}

	/**
	 * @param $meta_key
	 * @param $meta_value
	 *
	 * @return $this
	 * @since 6.4.0
	 */
	public function set_my_meta( $meta_key, $meta_value ): Customer {

		$this->my_data ??= ArrayUtility::make( $this->get_customer_meta() );

		$this->my_data->set( $meta_key, $meta_value );

		return parent::set_meta( 'wp_travel_engine_booking_setting', $this->my_data->value() );
	}

	/**
	 * Set customer details.
	 *
	 * @param array $customer_details
	 *
	 * @return void
	 * @since 6.4.0
	 */
	public function set_customer_details( array $customer_details ): void {
		foreach ( $customer_details as $key => $value ) {
			$this->set_my_meta( 'place_order.booking.' . $key, $value );
		}
	}

	/**
	 * Retrieves the user ID for a given email.
	 *
	 * @param string $email The email address to search for.
	 *
	 * @return int|false The user ID if found, false otherwise.
	 * @since 6.4.0
	 */
	public function get_user_id( string $email ) {
		$user = get_user_by( 'email', $email );

		return $user->ID ?? false;
	}

	/**
	 * @return array
	 * @since 6.5.2
	 */
	public function get_data(): array {
		return array(
			'id'         => $this->get_id(),
			'email'      => $this->get_customer_email(),
			'first_name' => $this->get_customer_fname(),
			'last_name'  => $this->get_customer_lname(),
			'address'    => $this->get_customer_address(),
			'city'       => $this->get_customer_city(),
			'state'      => $this->get_customer_state(),
			'country'    => $this->get_customer_country(),
			'postcode'   => $this->get_customer_postcode(),
			'phone'      => $this->get_customer_phone(),
		);
	}
}
