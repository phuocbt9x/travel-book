<?php
/**
 * Wrapper for WP Session Manager.
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * WP Session Manager wraper.
 */
class WTE_Session {
	/**
	 * Holds session data.
	 *
	 * @var array
	 */
	private $session;

	/**
	 * Constructor function.
	 */
	public function __construct() {
		// Let users change the session cookie name.
		if ( ! defined( 'WP_TRAVEL_ENGINE_SESSION_COOKIE' ) ) {
			define( 'WP_TRAVEL_ENGINE_SESSION_COOKIE', 'wordpress_wp_travel_engine_session' );
		}

		if ( empty( $this->session ) ) { // on page load or refresh.
			add_action( 'plugins_loaded', array( $this, 'init' ), - 1 );
		}
	}

	/**
	 * Setup the WP_Session instance
	 *
	 * @access public
	 * @return array
	 * @since 1.5
	 */
	public function init() {
		$this->session = WP_Session::get_instance();

		return $this->session;
	}

	/**
	 * Get session data.
	 *
	 * @param string $key session data key.
	 *
	 * @return mixed      session data.
	 */
	public function get( string $key ) {
		$key = sanitize_key( $key );

		if ( isset( $this->session[ $key ] ) ) {
			// Check if JSON encoded.
			if ( is_string( $this->session[ $key ] ) && ( is_array( json_decode( $this->session[ $key ], true ) ) ) ) {
				return json_decode( $this->session[ $key ], true );
			}

			return maybe_unserialize( $this->session[ $key ] );
		}

		return false;
	}

	/**
	 * @since 6.3.3
	 */
	public function set_json( $key, $value ) {
		$key = sanitize_key( $key );
		if ( is_array( $value ) ) {
			$this->session[ $key ] = wp_json_encode( $value );
		} else {
			$this->session[ $key ] = $value;
		}
	}

	/**
	 * Set data in session.
	 *
	 * @param string $key session data key.
	 * @param mixed  $value session data.
	 *
	 * @return mixed
	 */
	public function set( $key, $value ) {
		$key = sanitize_key( $key );
		if ( is_array( $value ) ) {
			$this->session[ $key ] = serialize( $value );
		} else {
			$this->session[ $key ] = $value;
		}

		return $this->session[ $key ];
	}

	/**
	 * delete data in session.
	 *
	 * @param string $key session data key.
	 *
	 * @return boolean
	 */
	public function delete( $key ) {
		$key = sanitize_key( $key );
		unset( $this->session[ $key ] );

		return ! isset( $this->session[ $key ] );
	}
}
