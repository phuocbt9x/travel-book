<?php

namespace WPTravelEngine\Email;

/**
 * User Email class
 */
class UserEmail extends Email {

	/**
	 * User
	 */
	protected $user = null;

	/**
	 * Constructor
	 */
	public function __construct( $user ) {

		if ( is_numeric( $user ) ) {
			$user = get_userdata( $user );
		}

		parent::__construct();

		$this->set_tags(
			array(
				'{customer_first_name}' => empty( $user->first_name ) ? $user->user_login ?? '' : $user->first_name,
				'{customer_last_name}'  => empty( $user->last_name ) ? $user->user_login ?? '' : $user->last_name,
				'{customer_full_name}'  => ( empty( $user->first_name ) && empty( $user->last_name ) ) ? ( $user->user_login ?: '' ) : "{$user->first_name} {$user->last_name}",
				'{customer_email}'      => $user->user_email ?? '',
				'{password_reset_link}' => self::get_password_reset_link( $user ),
			)
		);
	}

	/**
	 * Get password reset link
	 *
	 * @param \WP_User $user
	 * @return string
	 */
	public static function get_password_reset_link( $user ) {
		if ( $user instanceof \WP_User ) {
			return add_query_arg(
				array(
					'key'   => get_password_reset_key( $user ),
					'login' => rawurlencode( $user->user_login ),
				),
				wp_travel_engine_lostpassword_url()
			);
		}
		return '';
	}
}
