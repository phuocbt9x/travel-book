<?php
/**
 * Abstract Ajax Controller.
 *
 * @package WPTravelEngine/Core/Controllers
 * @since 6.0.0
 */

namespace WPTravelEngine\Abstracts;

use WPTravelEngine\Utilities\RequestParser;

/**
 * Handles add to cart ajax request.
 */
abstract class AjaxController {

	/**
	 * Nonce key.
	 *
	 * @var string
	 */
	const NONCE_KEY = '';

	/**
	 * Nonce action.
	 *
	 * @var string
	 */
	const NONCE_ACTION = '';

	/**
	 * Allow non-priv users.
	 *
	 * @var bool
	 */
	const ALLOW_NOPRIV = true;

	/**
	 * Post REST Request.
	 *
	 * @var \WP_REST_Request $request
	 */
	protected \WP_REST_Request $request;

	/**
	 * Constructor.
	 */
	public function __construct() {
		add_action( 'wp_ajax_' . static::ACTION, array( static::class, 'handle' ) );
		// If ALLOW_NOPRIV is true, add the action for non-priv users.
		if ( static::ALLOW_NOPRIV ) {
			add_action( 'wp_ajax_nopriv_' . static::ACTION, array( static::class, 'handle' ) );
		}
	}

	/**
	 * Handle request.
	 *
	 * @return void
	 */
	public static function handle() {

		$instance = new static();

		if ( $instance->authorize_request() ) {
			$instance->create_request();
			$instance->process_request();
		}
	}

	/**
	 * Process request.
	 */
	abstract protected function process_request();

	/**
	 * Authorize request.
	 *
	 * @return bool|void
	 */
	protected function authorize_request() {
		if ( check_ajax_referer( static::NONCE_ACTION, static::NONCE_KEY, false ) === false ) {
			wp_send_json_error( new \WP_Error( 'invalid_request', __( 'Invalid request.', 'wp-travel-engine' ) ) );
		}

		return true;
	}

	protected function create_request() {
		$request = new \WP_REST_Request( 'POST' );

		$request->set_body( file_get_contents( 'php://input' ) );
		$request->set_query_params( $_GET );
		$request->set_header( 'Content-Type', 'application/json' );
		$request->set_body_params( array_merge( $_POST, array( '__files' => $_FILES ) ) );

		$this->request = $request;
	}

	/**
	 * @return $this
	 * @since 6.4.0
	 */
	public static function create( RequestParser $request ): AjaxController {
		$instance = new static();

		if ( wp_verify_nonce( $request->get_param( '_nonce' ), 'wte_add_trip_to_cart' ) ) {
			$instance->request = $request;
		}
		// TODO: need to verify this later.
		// if ( $instance->authorize_request() ) {
		// $instance->request = $request;
		// }

		return $instance;
	}
}
