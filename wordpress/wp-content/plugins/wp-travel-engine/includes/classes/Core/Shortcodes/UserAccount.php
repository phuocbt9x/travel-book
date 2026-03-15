<?php
/**
 * ShortCode dashboard.
 *
 * @package    WPTravelEngine
 * @subpackage WPTravelEngine\Core\Shortcodes
 */

namespace WPTravelEngine\Core\Shortcodes;

use WPTravelEngine\Abstracts\Shortcode;
use WPTravelEngine\Core\Models\Settings\PluginSettings;
use WPTravelEngine\Email\UserEmail;

/**
 * Class UserAccount.
 *
 * Responsible for creating shortcodes for user account displaying and maintaining it.
 *
 * @since 6.0.0
 */
class UserAccount extends Shortcode {
	/**
	 * Shortcode tag.
	 *
	 * @var string
	 */
	const TAG = 'wp_travel_engine_dashboard';

	/**
	 * Dashboard menus.
	 *
	 * @return array Menus.
	 */
	private static function dashboard_menus() {
		$dashboard_menus        = array(
			'bookings' => array(
				'menu_title'      => __( 'Booking', 'wp-travel-engine' ),
				'menu_class'      => 'lrf-bookings',
				'menu_content_cb' => array( __CLASS__, 'dashboard_menu_bookings_tab' ),
				'priority'        => 20,
			),
			'address'  => array(
				'menu_title'      => __( 'Billing Info', 'wp-travel-engine' ),
				'menu_class'      => 'lrf-address',
				'menu_content_cb' => array( __CLASS__, 'dashboard_menu_address_tab' ),
				'priority'        => 30,
			),
			'account'  => array(
				'menu_title'      => __( 'Account Details', 'wp-travel-engine' ),
				'menu_class'      => 'lrf-account',
				'menu_content_cb' => array( __CLASS__, 'dashboard_menu_account_tab' ),
				'priority'        => 40,
			),
		);
		return $dashboard_menus = apply_filters( 'wp_travel_engine_user_dashboard_menus', $dashboard_menus );
	}

	/**
	 * Bookings Dashboard menus.
	 *
	 * @return array Menus.
	 */
	private static function bookings_dashboard_menus() {
		$bookings_dashboard_menus        = array(
			'active'  => array(
				'menu_title'      => __( 'Upcoming Trips', 'wp-travel-engine' ),
				'menu_class'      => 'wpte-active-bookings',
				'menu_content_cb' => array( __CLASS__, 'bookings_menu_active_tab' ),
				'priority'        => 10,
			),
			'history' => array(
				'menu_title'      => __( 'Booking History', 'wp-travel-engine' ),
				'menu_class'      => 'wpte-history-bookings',
				'menu_content_cb' => array( __CLASS__, 'bookings_menu_history_tab' ),
				'priority'        => 30,
			),
		);
		return $bookings_dashboard_menus = apply_filters( 'wp_travel_engine_user_dashboard_booking_menus', $bookings_dashboard_menus );
	}

	public static function dashboard_menu_bookings_tab( $args ) {
		wte_get_template( 'account/tab-content/bookings.php', $args );
	}

	public static function dashboard_menu_address_tab( $args ) {
		wte_get_template( 'account/tab-content/address.php', $args );
	}

	public static function dashboard_menu_account_tab( $args ) {
		wte_get_template( 'account/tab-content/account.php', $args );
	}

	public static function bookings_menu_active_tab( $args ) {
		wte_get_template( 'account/tab-content/bookings/bookings-manager.php', array_merge( $args, array( 'type' => 'active' ) ) );
	}

	public static function bookings_menu_history_tab( $args ) {
		wte_get_template( 'account/tab-content/bookings/bookings-manager.php', array_merge( $args, array( 'type' => 'history' ) ) );
	}

	/**
	 * Retrieves the UserAccount shortcode output.
	 *
	 * This function generates the HTML output for the user account shortcode based on the provided attributes.
	 *
	 * @param array $atts Shortcode attributes.
	 * @return string The generated HTML output.
	 */
	public function output( $atts ): string {

		/**
		 * Remove sidebar from user account page
		 */
		$themes = array(
			'travel-monster',
			'travel-agency',
			'travel-agency-pro',
			'travel-booking',
			'travel-booking-pro',
			'travel-muni',
			'travel-muni-pro',
		);

		if ( in_array( get_template(), $themes ) ) {
			add_filter( str_replace( '-', '_', get_template() ) . '_display_page_sidebar', '__return_false' );
		}

		ob_start();

		if ( ! is_user_logged_in() ) {
			// phpcs:disable
			// After password reset, add confirmation message.
			if ( ! empty( $_GET['password-reset'] ) ) {
				WTE()->notices->add( __( 'Your Password has been updated successfully. Please Log in to continue.', 'wp-travel-engine' ), 'success' );
			}
			if ( isset( $_GET['action'] ) && 'lost-pass' == $_GET['action'] ) {
				self::lost_password();
			} else {
				// Get user login.
				wte_get_template( 'account/form-login.php' );
			}
		} else {
			$current_user = wp_get_current_user();
			$args['current_user'] = $current_user;
			$args['dashboard_menus'] = self::dashboard_menus();
			$args['bookings_dashboard_menus'] = self::bookings_dashboard_menus();
			// Get user Dashboard.
			wte_get_template( 'account/content-dashboard.php', $args );
		}

		return ob_get_clean();
	}

	/**
	 * Lost password page handling.
	 */
	public static function lost_password() {
		// phpcs:disable
		/**
		 * After sending the reset link, don't show the form again.
		 */
		if ( ! empty( $_GET['reset-link-sent'] ) ) {
			wte_get_template( 'account/lostpassword-confirm.php' );
			return;
			/**
			 * Process reset key / login from email confirmation link
			*/
		} elseif ( ! empty( $_GET['show-reset-form'] ) ) {
			if ( isset( $_COOKIE[ 'wp-resetpass-' . COOKIEHASH ] ) && 0 < strpos( $_COOKIE[ 'wp-resetpass-' . COOKIEHASH ], ':' ) ) {
				list( $rp_login, $rp_key ) = array_map( 'wp_travel_engine_clean_vars', explode( ':', wp_unslash( $_COOKIE[ 'wp-resetpass-' . COOKIEHASH ] ), 2 ) );
				$user = self::check_password_reset_key( $rp_key, $rp_login );

				// reset key / login is correct, display reset password form with hidden key / login values
				if ( is_object( $user ) ) {

					wte_get_template( 'account/form-reset-password.php', array(
						'key'   => $rp_key,
						'login' => $rp_login,
					) );

					return;
				}
			}
		}

		// Show lost password form by default.
		wte_get_template( 'account/form-lostpassword.php' );
		// phpcs:enable
	}

	/**
	 * Retrieves a user row based on password reset key and login.
	 *
	 * @uses $wpdb WordPress Database object
	 *
	 * @param string $key Hash to validate sending user's password
	 * @param string $login The user login
	 *
	 * @return WP_User|bool User's database row on success, false for invalid keys
	 */
	public static function check_password_reset_key( $key, $login ) {
		// Check for the password reset key.
		// Get user data or an error message in case of invalid or expired key.
		$user = check_password_reset_key( $key, $login );

		if ( is_wp_error( $user ) ) {
			WTE()->notices->add( __( 'Error:', 'wp-travel-engine' ) . __( 'This key is invalid or has already been used. Please reset your password again if needed.', 'wp-travel-engine' ), 'error' );
			return false;
		}

		return $user;
	}

	/**
	 * Handles sending password retrieval email to customer.
	 *
	 * Based on retrieve_password() in core wp-login.php.
	 *
	 * @uses $wpdb WordPress Database object
	 * @return bool True: when finish. False: on error
	 */
	public static function retrieve_password() {
		$login = trim( sanitize_text_field( wp_unslash( $_POST['user_login'] ) ) ); // phpcs:ignore

		if ( empty( $login ) ) {

			WTE()->notices->add( __( 'Error:', 'wp-travel-engine' ) . __( 'Enter an email or username.', 'wp-travel-engine' ), 'error' );

			return false;

		} else {
			// Check on username first, as customers can use emails as usernames.
			$user_data = get_user_by( 'login', $login );
		}

		// If no user found, check if it login is email and lookup user based on email.
		if ( ! $user_data && is_email( $login ) && apply_filters( 'wp_travel_engine_get_username_from_email', true ) ) {
			$user_data = get_user_by( 'email', $login );
		}

		$errors = new \WP_Error();

		do_action( 'wptravelengine_lostpassword_post', $errors );

		if ( $errors->get_error_code() ) {

			WTE()->notices->add( __( 'Error:', 'wp-travel-engine' ) . $errors->get_error_message(), 'error' );

			return false;
		}

		if ( ! $user_data ) {

			WTE()->notices->add( __( 'Error:', 'wp-travel-engine' ) . __( 'Invalid username or email.', 'wp-travel-engine' ), 'error' );

			return false;
		}

		if ( is_multisite() && ! is_user_member_of_blog( $user_data->ID, get_current_blog_id() ) ) {
			WTE()->notices->add( __( 'Error:', 'wp-travel-engine' ) . __( 'Invalid username or email.', 'wp-travel-engine' ), 'error' );

			return false;
		}

		// redefining user_login ensures we return the right case in the email.
		$user_login = $user_data->user_login;

		do_action( 'wptravelengine_retrieve_password', $user_login );

		$allow = apply_filters( 'allow_password_reset', true, $user_data->ID );

		if ( ! $allow ) {

			WTE()->notices->add( __( 'Error:', 'wp-travel-engine' ) . __( 'Password reset is not allowed for this user.', 'wp-travel-engine' ), 'error' );

			return false;

		} elseif ( is_wp_error( $allow ) ) {

			WTE()->notices->add( __( 'Error:', 'wp-travel-engine' ) . $allow->get_error_message(), 'error' );

			return false;
		}

		$plugin_settings = new PluginSettings();

		$forgot_password_settings = $plugin_settings->get( 'customer_email_notify_tabs.forgot_password' );
		if ( wptravelengine_toggled( $forgot_password_settings['enabled'] ) ) {
			// Get password reset key (function introduced in WordPress 4.4).
			$key = get_password_reset_key( $user_data );

			if ( $user_login && $key ) {
				$email = new UserEmail( $user_data->ID );
				$email->set( 'to', $user_data->user_email );
				$email->set( 'my_subject', $forgot_password_settings['subject'] );
				$email->set( 'content', $forgot_password_settings['content'] );
				$email->send();
			}
		}

		return true;
	}

	/**
	 * Handles resetting the user's password.
	 *
	 * @param object $user The user
	 * @param string $new_pass New password for the user in plaintext
	 */
	public static function reset_password( $user, $new_pass ) {
		do_action( 'password_reset', $user, $new_pass );

		wp_set_password( $new_pass, $user->ID );
		self::set_reset_password_cookie();

		wp_password_change_notification( $user );
	}

	/**
	 * Set or unset the cookie.
	 *
	 * @param string $value
	 */
	public static function set_reset_password_cookie( $value = '' ) {
		$rp_cookie = 'wp-resetpass-' . \COOKIEHASH;
		$rp_path   = current( explode( '?', wte_clean( wp_unslash( $_SERVER['REQUEST_URI'] ) ) ) ); // phpcs:ignore

		if ( $value ) {
			setcookie( $rp_cookie, $value, 0, $rp_path, \COOKIE_DOMAIN, is_ssl(), true );
		} else {
			setcookie( $rp_cookie, ' ', time() - \YEAR_IN_SECONDS, $rp_path, \COOKIE_DOMAIN, is_ssl(), true );
		}
	}
}
