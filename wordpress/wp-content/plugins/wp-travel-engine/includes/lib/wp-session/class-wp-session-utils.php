<?php

/**
 * Utility class for sesion utilities
 *
 * THIS CLASS SHOULD NEVER BE INSTANTIATED
 */
class WP_Session_Utils {
	/**
	 * Count the total sessions in the database.
	 *
	 * @return int
	 * @global wpdb $wpdb
	 */
	public static function count_sessions() {
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
	 * @return void
	 * @since 6.4.3
	 */
	public static function clear_leftover_cache_data() {
		global $wpdb;

		$prefix     = '_wp_session_';
		$batch_size = 1000;
		$loop_count = 0;
		$max_loops  = 1000;

		do {
			$option_names = $wpdb->get_col(
				$wpdb->prepare(
					"SELECT option_name FROM {$wpdb->options} WHERE option_name LIKE %s LIMIT %d",
					$prefix . '%',
					$batch_size
				)
			);

			if ( ! empty( $option_names ) ) {
				$placeholders = implode( ',', array_fill( 0, count( $option_names ), '%s' ) );
				$query        = $wpdb->prepare(
					"DELETE FROM {$wpdb->options} WHERE option_name IN ($placeholders)",
					...$option_names
				);
				$wpdb->query( $query );
			} else {
				update_option( '_wptravelengine_leftover_cached_cleared', 'yes' );
			}

			++$loop_count;
		} while ( ! empty( $option_names ) && $loop_count < $max_loops );
	}

	/**
	 * @param int|null $time
	 *
	 * @return array|object|stdClass[]|null
	 *
	 * @since 6.5.2
	 */
	public static function get_expired_sessions( int $time = null ) {
		global $wpdb;
		$time  = $time ?: time();
		$query = $wpdb->prepare(
			"
			    SELECT
			        REPLACE(e.option_name, %s, '') AS session_id,
			        e.option_value AS expiry,
			        s.option_value AS value
			    FROM {$wpdb->options} e
			    JOIN {$wpdb->options} s
			      ON REPLACE(e.option_name, %s, '') = REPLACE(s.option_name, %s, '')
			    WHERE e.option_name LIKE %s
			      AND e.option_value < %d
			      AND s.option_name LIKE %s
			    ORDER BY e.option_value DESC
			",
			'_wp_session_expires_',
			'_wp_session_expires_',
			'_wp_session_',
			$wpdb->esc_like( '_wp_session_expires_' ) . '%',
			$time,
			$wpdb->esc_like( '_wp_session_' ) . '%'
		);

		return $wpdb->get_results( $query );
	}

	/**
	 * @TODO: Implement this feature later.
	 */
	public static function prepare_abandoned_cart_data( object $cart_data ) {
		$cart_data = json_decode( $cart_data['wpte_trip_cart'] );
		if ( is_object( $cart_data ) ) {
			return $cart_data;
		}

		return array();
	}

	/**
	 * Delete old sessions from the database.
	 *
	 * @param int $limit Maximum number of sessions to delete.
	 *
	 * @return int Sessions deleted.
	 * @global wpdb $wpdb
	 */
	public static function delete_old_sessions( int $limit = 1000 ) {
		global $wpdb;

		if ( 'yes' !== get_option( '_wptravelengine_leftover_cached_cleared', 'no' ) ) {
			static::clear_leftover_cache_data();
		}

		$expired_sessions = self::get_expired_sessions();

		$expired = array();
		foreach ( $expired_sessions as $expired_session ) {
			$session_data = maybe_unserialize( $expired_session->value );
			$expired[]    = "_wp_session_{$expired_session->session_id}";
			$expired[]    = "_wp_session_expires_{$expired_session->session_id}";
			/**
			 * @TODO: Implement this feature later.
			 * Don't remove this code, as it may be needed in the future.
			 */
			// if ( isset( $session_data[ "wpte_trip_cart" ] ) ) {
			// $cart_data = json_decode( $session_data[ "wpte_trip_cart" ] );
			// if ( is_object( $cart_data ) ) {
			// do_action( "wptravelengine.cart.abandoned", static::prepare_abandoned_cart_data( $cart_data ) );
			// }
			// }
		}

		// Delete expired sessions
		if ( ! empty( $expired ) ) {
			$placeholders = implode( ', ', array_fill( 0, count( $expired ), '%s' ) );
			$prepare      = $wpdb->prepare( "DELETE FROM $wpdb->options WHERE option_name IN ($placeholders)", $expired );
			$wpdb->query( $prepare );
		}

		return 0;
	}

	/**
	 * Remove all sessions from the database, regardless of expiration.
	 *
	 * @return int Sessions deleted
	 * @global wpdb $wpdb
	 */
	public static function delete_all_sessions(): int {
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
		$hash = new PasswordHash( 8, false );

		return md5( $hash->get_random_bytes( 32 ) );
	}
}
