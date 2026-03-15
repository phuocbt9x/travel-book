<?php
/**
 * Package Controller.
 *
 * @package WPTravelEngine
 * @since 6.2.3
 */

namespace WPTravelEngine\Core\Controllers\RestAPI\V2;

use WP_Error;
use WP_REST_Posts_Controller;
use WP_REST_Server;
use WPTravelEngine\Core\Models\Post;

/**
 * Package Controller.
 *
 * @package WPTravelEngine
 * @since 6.2.3
 */
class TripPackages extends WP_REST_Posts_Controller {

	/**
	 * Constructor.
	 */
	public function __construct() {
		parent::__construct( 'trip-packages' );
		$this->namespace = 'wptravelengine/v2';
	}

	/**
	 * Registers the routes for posts.
	 *
	 * Overrides parent to add schema-level parameter validation.
	 *
	 * @see register_rest_route()
	 */
	public function register_routes() {
		parent::register_routes();

		// Override get_items route with proper parameter validation.
		register_rest_route(
			$this->namespace,
			'/' . $this->rest_base,
			array(
				'methods'             => WP_REST_Server::READABLE,
				'callback'            => array( $this, 'get_items' ),
				'permission_callback' => array( $this, 'get_items_permissions_check' ),
				'args'                => array(
					'trip_id' => array(
						'required'          => true,
						'type'              => 'integer',
						'sanitize_callback' => 'absint',
						'validate_callback' => function ( $param ) {
							return is_numeric( $param ) && $param > 0;
						},
					),
				),
			)
		);

		// Override create_item route with proper parameter validation.
		register_rest_route(
			$this->namespace,
			'/' . $this->rest_base,
			array(
				'methods'             => WP_REST_Server::CREATABLE,
				'callback'            => array( $this, 'create_item' ),
				'permission_callback' => array( $this, 'create_item_permissions_check' ),
				'args'                => array_merge(
					$this->get_endpoint_args_for_item_schema( WP_REST_Server::CREATABLE ),
					array(
						'trip_id'       => array(
							'required'          => true,
							'type'              => 'integer',
							'sanitize_callback' => 'absint',
							'validate_callback' => function ( $param ) {
								return is_numeric( $param ) && $param > 0;
							},
						),
						'clone_package' => array(
							'required'          => false,
							'type'              => 'integer',
							'sanitize_callback' => 'absint',
							'validate_callback' => function ( $param ) {
								return empty( $param ) || ( is_numeric( $param ) && $param > 0 );
							},
						),
					)
				),
			)
		);
	}

	/**
	 * @inerhitDoc
	 */
	public function get_items( $request ) {
		$trip_id = $request->get_param( 'trip_id' );

		$packages = array();

		$trip          = new Post\Trip( $trip_id );
		$trip_packages = new Post\TripPackages( $trip );

		foreach ( $trip_packages as $trip_package ) {
			$packages[] = $this->prepare_package_data( $trip_package );
		}

		return rest_ensure_response( $packages );
	}

	/**
	 * @inerhitDoc
	 */
	public function create_item( $request ) {
		$response = parent::create_item( $request );

		if ( $cloning_package_id = $request->get_param( 'clone_package' ) ) {

			$cloned_package_id = $response->data['id'];
			$cloning_package   = get_post( $cloning_package_id );

			$package_arr = new \stdClass();

			$package_arr->ID          = $cloned_package_id;
			$package_arr->post_title  = $cloning_package->post_title;
			$package_arr->post_status = $cloning_package->post_status;

			$meta  = get_post_meta( $cloning_package_id );
			$_meta = array();
			foreach ( $meta as $key => $value ) {
				$_meta[ $key ] = maybe_unserialize( $value[0] );
			}

			$_meta['trip_ID']        = $request->get_param( 'trip_id' );
			$package_arr->meta_input = $_meta;

			wp_update_post( $package_arr );
		}

		$post = get_post( $response->data['id'] );

		$response = $this->prepare_item_for_response( $post, $request );
		$response = rest_ensure_response( $response );

		$response->set_status( 201 );
		$response->header( 'Location', rest_url( rest_get_route_for_post( $post ) ) );

		return $response;
	}

	/**
	 * Update trip package ids.
	 *
	 * @param array $package Package data.
	 * @param int   $trip_id Trip ID.
	 *
	 * @return void
	 */
	public function update_trip_package_ids( $package, $trip_id ) {
		$packages = get_post_meta( $trip_id, 'packages_ids', true );

		if ( ! is_array( $packages ) ) {
			$packages = array();
		}

		$packages[] = $package['id'];

		update_post_meta( $trip_id, 'packages_ids', array_unique( $packages ) );
	}

	/**
	 * Prepare package data.
	 *
	 * @param Post\TripPackage $trip_package Trip package object.
	 *
	 * @return array
	 */
	public static function prepare_package_data( Post\TripPackage $trip_package ): array {
		$data = array();
		/**
		 * @var Post\TripPackage $trip_package
		 * @var Post\Trip $trip
		 */
		$data['id']          = $trip_package->get_id();
		$data['name']        = $trip_package->get_title();
		$data['description'] = $trip_package->get_content();
		$data['is_primary']  = $trip_package->get_id() === (int) $trip_package->get_trip()->get_meta( 'primary_package' );
		$data['min_pax']     = (int) $trip_package->get_trip()->get_minimum_participants();

		$package_categories = $trip_package->get_traveler_categories();

		/** @var Post\TravelerCategory $category */
		foreach ( $package_categories as $category ) {
			$min_pax = $category->get( 'min_pax', '' );
			$min_pax = is_numeric( $min_pax ) ? (int) $min_pax : 0;
			$max_pax = wptravelengine_normalize_numeric_val( $category->get( 'max_pax', '' ) );
			$max_pax = is_numeric( $max_pax ) ? ( $max_pax >= $min_pax ? $max_pax : $min_pax ) : '';

			$group_pricing = $category->get( 'group_pricing', array() );

			$group_pricing = array_map(
				function ( $gp ) {
					return array(
						'from'  => is_numeric( $gp['from'] ) ? (int) $gp['from'] : 0,
						'to'    => is_numeric( $gp['to'] ) ? (int) $gp['to'] : '',
						'price' => is_numeric( $gp['price'] ) ? (float) $gp['price'] : 0,
					);
				},
				$group_pricing
			);

			$get_pricing_type              = $category->get( 'pricing_type', 'per-person' );
			$pricing_label                 = wptravelengine_get_pricing_type( false, $get_pricing_type );
			$price                         = $category->get( 'price', '' );
			$sale_price                    = $category->get( 'sale_price', '' );
			$data['traveler_categories'][] = array(
				'id'                => (int) $category->get( 'id', 0 ),
				'label'             => $category->get( 'label', '' ),
				'price'             => is_numeric( $price ) ? (float) $price : '',
				'age_group'         => $category->get( 'age_group', '' ),
				'pricing_type'      => array(
					'value'       => $get_pricing_type,
					'label'       => $pricing_label['label'],
					'description' => $pricing_label['description'],
				),
				'sale_price'        => is_numeric( $sale_price ) ? (float) $sale_price : '',
				'has_sale'          => wptravelengine_toggled( $category->get( 'has_sale', false ) ),
				'has_group_pricing' => wptravelengine_toggled( $category->get( 'enabled_group_discount', false ) && ! empty( $group_pricing ) ),
				'group_pricing'     => $group_pricing,
				'min_pax'           => $min_pax,
				'max_pax'           => '',
				'description'       => $category->get( 'description', '' ),
			);

		}

		return apply_filters( 'wptravelengine_rest_prepare_package_data', $data, $trip_package );
	}
}
