<?php
/**
 * WP Travel Engine Trip Booking Controller class.
 *
 * @package WPTravelEngine
 * @since 6.5.2
 */

namespace WPTravelEngine\Core\Controllers\RestAPI\V2;

use WP_Error;
use WP_REST_Posts_Controller;
use WP_REST_Request;
use WP_REST_Response;
use WP_REST_Server;
use WPTravelEngine\Core\Models\Post\Booking as BookingModel;

/**
 * Class Booking
 *
 * @since 6.5.2
 */
class Booking extends WP_REST_Posts_Controller {
	/**
	 * Constructor.
	 *
	 * @param string $post_type Post type.
	 */
	public function __construct( $post_type ) {
		parent::__construct( $post_type );
		$this->post_type = $post_type;
		$this->rest_base = 'bookings';
		$this->namespace = 'wptravelengine/v2';
	}

	public function register_routes() {
		register_rest_route(
			$this->namespace,
			"/$this->rest_base",
			array(
				array(
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_items' ),
					'permission_callback' => array( $this, 'get_items_permissions_check' ),
					'args'                => $this->get_collection_params(),
				),
			)
		);

		register_rest_route(
			$this->namespace,
			"/$this->rest_base/(?P<id>[\d]+)",
			array(
				array(
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_item' ),
					'permission_callback' => array( $this, 'get_item_permissions_check' ),
					'args'                => $this->get_collection_params(),
				),
			)
		);
	}

	/**
	 * @inerhitDoc
	 */
	public function get__items( $request ): WP_REST_Response {

		$items_query = new \WP_Query();

		$query_result = $items_query->query(
			array(
				'post_type'      => $this->post_type,
				'posts_per_page' => $request['per_page'] ?? 10,
				'paged'          => $request['page'] ?? 1,
				'post_status'    => 'publish',
			)
		);

		$items = array();
		foreach ( $query_result as $item ) {
			$items[] = $this->prepare_item_for_response( $item, $request );
		}

		return rest_ensure_response( $items );
	}

	/**
	 * @inerhitDoc
	 * @since 6.5.2
	 */
	public function get_items_permissions_check( $request ) {
		$post_type = get_post_type_object( $this->post_type );

		if ( ! current_user_can( $post_type->cap->edit_posts ) ) {
			return new WP_Error(
				'rest_forbidden_context',
				__( 'Sorry, you are not allowed to view this resource.', 'wp-travel-engine' ),
				array( 'status' => rest_authorization_required_code() )
			);
		}

		return true;
	}

	/**
	 * @inheritDoc
	 * @since 6.5.2
	 */
	public function get_item_permissions_check( $request ) {
		$post = $this->get_post( $request['id'] );
		if ( is_wp_error( $post ) ) {
			return $post;
		}

		if ( $post ) {
			return $this->check_read_permission( $post );
		}

		return true;
	}

	/**
	 * @inheritDoc
	 * @return bool|WP_Error
	 * @since 6.5.2
	 */
	public function check_read_permission( $post ) {
		if ( ! current_user_can( 'edit_post', $post->ID ) ) {
			return new WP_Error(
				'rest_forbidden_context',
				__( 'Sorry, you are not allowed to view this resource.', 'wp-travel-engine' ),
				array( 'status' => rest_authorization_required_code() )
			);
		}

		return true;
	}

	/**
	 * @inheritDoc
	 * @since 6.5.2
	 */
	public function prepare_item_for_response( $item, $request ) {
		$item = new BookingModel( $item->ID );

		return $item->get_data();
	}
}
