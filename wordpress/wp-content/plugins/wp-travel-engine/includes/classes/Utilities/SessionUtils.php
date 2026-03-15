<?php
/**
 * Utility class for session utilities.
 *
 * @since 6.0.0
 */

namespace WPTravelEngine\Utilities;

class SessionUtils {
	/**
	 * Count the total sessions in the database.
	 *
	 * @return int Total number of sessions.
	 * @global wpdb $wpdb
	 */
	public static function count_sessions(): int {
		global $wpdb;

		$query = "SELECT COUNT(*) FROM $wpdb->options WHERE option_name LIKE '_wp_session_expires_%'";

		/**
		 * Filter the query in case tables are non-standard.
		 *
		 * @param string $query Database count query
		 */
		$query = apply_filters( 'wp_session_count_query', $query );

		$sessions = $wpdb->get_var( $query );

		return absint( $sessions );
	}

	/**
	 * Create a new, random session in the database.
	 *
	 * @param null|string $date
	 */
	public static function create_dummy_session( $date = null ) {
		// Generate our date
		if ( null !== $date ) {
			$time = strtotime( $date );

			if ( false === $time ) {
				$date = null;
			} else {
				$expires = date( 'U', strtotime( $date ) );
			}
		}

		// If null was passed, or if the string parsing failed, fall back on a default
		if ( null === $date ) {
			/**
			 * Filter the expiration of the session in the database
			 *
			 * @param int
			 */
			$expires = time() + (int) apply_filters( 'wp_session_expiration', 30 * 60 );
		}

		$session_id = self::generate_id();

		// Store the session
		add_option( "_wp_session_{$session_id}", array(), '', 'no' );
		add_option( "_wp_session_expires_{$session_id}", $expires, '', 'no' );
	}

	/**
	 * Delete old sessions from the database.
	 *
	 * @param int $limit Maximum number of sessions to delete.
	 *
	 * @return int Sessions deleted.
	 * @global \wpdb $wpdb
	 */
	public static function delete_old_sessions( $limit = 1000 ) {
		return \WP_Session_Utils::delete_old_sessions( $limit );
	}

	/**
	 * Remove all sessions from the database, regardless of expiration.
	 *
	 * @return int Sessions deleted
	 * @global wpdb $wpdb
	 */
	public static function delete_all_sessions() {
		global $wpdb;

		$count = $wpdb->query( "DELETE FROM $wpdb->options WHERE option_name LIKE '_wp_session_%'" );

		return (int) ( $count / 2 );
	}

	/**
	 * Generate a new, random session ID.
	 *
	 * @return string
	 */
	public static function generate_id() {
		require_once ABSPATH . 'wp-includes/class-phpass.php';
		$hash = new \PasswordHash( 8, false );

		return md5( $hash->get_random_bytes( 32 ) );
	}
}
