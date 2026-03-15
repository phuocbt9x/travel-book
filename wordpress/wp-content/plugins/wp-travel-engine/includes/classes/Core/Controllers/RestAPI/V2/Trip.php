<?php
/**
 * WP Travel Engine Trip Post Controller class.
 *
 * @package WPTravelEngine
 * @since 6.0.0
 */

namespace WPTravelEngine\Core\Controllers\RestAPI\V2;

use WP_Error;
use WP_HTTP_Response;
use WP_Post;
use WP_REST_Posts_Controller;
use WP_REST_Request;
use WP_REST_Response;
use WP_REST_Server;
use WPTravelEngine\Core\Models\Post;
use WPTravelEngine\Core\Models\Settings\Options;
use WPTravelEngine\Utilities\ArrayUtility;
use WPTravelEngine\Core\Models\Post\TripPackage;
use WPTravelEngine\Helpers\PackageDateParser;

/**
 * REST API: Trip Post Controller class
 *
 * @since 6.0.0
 * @see WP_REST_Posts_Controller
 */
class Trip extends WP_REST_Posts_Controller {

	/**
	 * Trip Schema.
	 *
	 * @access protected
	 * @var array
	 */
	protected $schema;

	/**
	 * Request object.
	 *
	 * @access protected
	 * @var WP_REST_Request
	 */
	protected $request;

	/**
	 * Error object.
	 *
	 * @access protected
	 * @var ?WP_Error
	 */
	protected $errors;

	/**
	 * Post type.
	 *
	 * @access protected
	 * @var string
	 */
	protected $post_type;

	/**
	 * Trip Object.
	 *
	 * @access public
	 * @var Post\Trip
	 */
	public $trip;

	/**
	 * Trip Settings Object.
	 *
	 * @access public
	 * @var ArrayUtility
	 */
	public $trip_settings;

	/**
	 * Maximum number of recurring date instances to generate.
	 * This limit prevents performance issues with unbounded recurrence rules.
	 *
	 * @var int
	 * @since 6.7.4
	 */
	public static $recurring_date_count_limit = 10;

	/**
	 * Constructor.
	 *
	 * @param string $post_type Post type.
	 *
	 * @since 4.7.0
	 */
	public function __construct( $post_type ) {
		parent::__construct( $post_type );
		$this->post_type = $post_type;
		$this->rest_base = 'trips';
		$this->namespace = 'wptravelengine/v2';
	}

	/**
	 * Registers the routes for posts.
	 *
	 * @see register_rest_route()
	 */
	public function register_routes() {
		parent::register_routes();

		register_rest_route(
			$this->namespace,
			"/$this->rest_base/schema",
			array(
				'methods'             => WP_REST_Server::READABLE,
				'callback'            => array( $this, 'get_public_item_schema' ),
				'permission_callback' => array( $this, 'get_items_permissions_check' ),
				'args'                => $this->get_collection_params(),
			)
		);

		register_rest_route(
			$this->namespace,
			"/$this->rest_base/(?P<id>[\d]+)/services",
			array(
				array(
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_services' ),
					'permission_callback' => array( $this, 'get_items_permissions_check' ),
					'args'                => $this->get_collection_params(),
				),
			)
		);

		register_rest_route(
			$this->namespace,
			"/$this->rest_base/(?P<id>[\d]+)/packages",
			array(
				array(
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_packages' ),
					'permission_callback' => array( $this, 'get_items_permissions_check' ),
					'args'                => $this->get_collection_params(),
				),
			)
		);

		foreach ( array( 'v2', 'v3' ) as $ver ) {
			register_rest_route(
				"wptravelengine/{$ver}",
				"/$this->rest_base/(?P<id>[\d]+)/dates",
				array(
					array(
						'methods'             => WP_REST_Server::READABLE,
						'callback'            => function ( $req ) use ( $ver ) {
							PackageDateParser::$version = $ver;
							return $this->get_dates( $req );
						},
						'permission_callback' => array( $this, 'get_items_permissions_check' ),
						'args'                => $this->get_collection_params(),
					),
				)
			);
		}

		register_rest_route(
			$this->namespace,
			"/$this->rest_base/(?P<id>[\d]+)/packages/(?P<package_id>[\d]+)",
			array(
				array(
					'methods'             => WP_REST_Server::DELETABLE,
					'callback'            => array( $this, 'delete_package' ),
					'permission_callback' => array( $this, 'delete_package_permissions_check' ),
					'args'                => $this->get_collection_params(),
				),
			)
		);
	}

	/**
	 * Retrieves the item's schema for display / public consumption purposes.
	 *
	 * @return array Public item schema data.
	 */
	public function get_public_item_schema() {

		$schema = $this->get_item_schema();

		if ( ! empty( $schema['properties'] ) ) {
			foreach ( $schema['properties'] as &$property ) {
				unset( $property['arg_options'] );
			}
		}

		return $schema;
	}

	/**
	 * Adds Bad Request '400 status' error to the error object.
	 *
	 * @param string $error_code Error code.
	 * @param string $error_message Error message to be displayed.
	 * @param string $error_param Error parameter.
	 *
	 * @return void
	 */
	public function set_bad_request( string $error_code = '', string $error_message = '', string $error_param = '' ): void {
		if ( ! isset( $this->errors ) ) {
			$this->errors = new WP_Error();
		}
		$this->errors->add(
			$error_code,
			$error_message ?? 'Bad Request.',
			array(
				'status'  => 400,
				'param'   => $error_param,
				'details' => strip_tags( $error_message ),
			)
		);
	}

	/**
	 * Has errors.
	 *
	 * @return bool
	 * @since 6.7.0
	 */
	public function has_errors(): bool {
		return isset( $this->errors ) && $this->errors->has_errors();
	}

	/**
	 * Checks if a given request has access to delete a package.
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 * @return bool|WP_Error True if the request has access to delete the package, WP_Error object otherwise.
	 * @since 6.5.2
	 */
	public function delete_package_permissions_check( $request ) {
		$post = get_post( $request['id'] );

		if ( ! current_user_can( 'edit_post', $post->ID ) ) {
			return new WP_Error(
				'rest_cannot_delete',
				__( 'Sorry, you are not allowed to delete packages for this trip.', 'wp-travel-engine' ),
				array( 'status' => rest_authorization_required_code() )
			);
		}

		if ( ! $post ) {
			return new WP_Error(
				'rest_post_not_found',
				__( 'Trip not found.', 'wp-travel-engine' ),
				array( 'status' => 404 )
			);
		}

		return true;
	}

	/**
	 * Deletes a package.
	 *
	 * @param WP_REST_Request $request
	 *
	 * @return mixed
	 */
	public function delete_package( WP_REST_Request $request ) {
		try {
			if ( wp_delete_post( $request['package_id'], true ) ) {
				$trip = new Post\Trip( $request->get_param( 'id' ) );
				if ( (int) $trip->get_meta( 'primary_package' ) === (int) $request['package_id'] ) {
					$trip->delete_meta( 'primary_package' );
				}
				$default_package = $trip->default_package();
				if ( $default_package ) {
					$trip->update_meta( '_s_price', $trip->has_sale() ? $trip->get_sale_price() : $trip->get_price() );
				} else {
					$trip->update_meta( '_s_price', 0 );
				}
				$previous           = $this->prepare_item_for_response( $trip->post, $request );
				$remaining_packages = array_diff( (array) $trip->get_meta( 'packages_ids' ), array( $request['package_id'] ) );
				$trip->set_meta( 'packages_ids', empty( $remaining_packages ) ? '' : $remaining_packages )->save();

				return new WP_REST_Response(
					array(
						'deleted'  => true,
						'previous' => $previous->get_data(),
					)
				);
			} else {
				throw new \Exception();
			}
		} catch ( \Exception $e ) {
			// error_log( $e->getMessage() );
			return new WP_Error(
				'delete_package_failed',
				sprintf( __( 'Failed to delete the package. %s', 'wp-travel-engine' ), $e->getMessage() ),
				array( 'status' => 500 )
			);
		}
	}

	/**
	 * Update Item.
	 *
	 * @param WP_REST_Request $request Request object.
	 *
	 * @return WP_Error|WP_REST_Response
	 * @since 6.2.0
	 */
	public function update_item( $request ) {

		$req_params = $request->get_json_params();

		foreach ( $this->sanitize_params_recursive( $req_params ) as $key => $value ) {
			$request->set_param( $key, $value );
		}

		$this->trip          = new Post\Trip( $request->get_param( 'id' ) );
		$this->trip_settings = ArrayUtility::make( $this->trip->{'settings'} );

		$this->set_core_settings( $request );

		if ( isset( $request['max_travellers_per_day'] ) ) {
			unset( $request['max_travellers_per_day'] );
		}

		do_action( 'wptravelengine_api_update_trip', $request, $this );

		if ( isset( $this->errors ) ) {
			return $this->errors;
		}

		$this->trip->set_meta( 'wp_travel_engine_setting', $this->trip_settings->value() );

		$this->trip->save();

		return $this->prepare_item_for_response( $this->trip->post, $request );
	}

	/**
	 * Sets WP Travel Engine Core Settings.
	 *
	 * @param WP_REST_Request $request Request object.
	 *
	 * @return void
	 * @since 6.2.2
	 */
	protected function set_core_settings( WP_REST_Request $request ): void {

		$trip          = $this->trip;
		$trip_settings = $this->trip_settings;

		if ( empty( $trip->get_setting( 'trip_code' ) ) ) {
			$request->set_param( 'trip_code', $trip->get_trip_code() );
		}

		if ( isset( $request['trip_code'] ) ) {
			if ( $request['trip_code'] !== '' ) {
				$this->trip_settings->set( 'trip_code', $request['trip_code'] );
			} else {
				$this->set_bad_request( 'invalid_trip_code', sprintf( __( '%1$sTrip Code%2$s is required.', 'wp-travel-engine' ), '<strong>', '</strong>' ) );
			}
		}

		if ( isset( $request['duration'] ) ) {
			$duration_unit = $request['duration']['unit'] ?? $trip_settings->get( 'trip_duration_unit', 'days' );
			if ( in_array( $duration_unit, array( 'days', 'hours' ) ) ) {
				$trip_settings->set( 'trip_duration_unit', $duration_unit );
			}
			$duration_period = $request['duration']['period'] ?? $trip_settings->get( 'trip_duration' );
			if ( is_numeric( $duration_period ) ) {
				$trip_settings->set( 'trip_duration', $duration_period );
				$duration = $duration_unit === 'days' ? $duration_period * 24 : $duration_period;
				$trip->set_meta( 'wp_travel_engine_setting_trip_duration', $duration );
				$trip->set_meta( '_s_duration', $duration );
			} else {
				$this->set_bad_request( 'invalid_duration_unit', sprintf( __( '%1$sDuration%2$s must be a number.', 'wp-travel-engine' ), '<strong>', '</strong>' ) );
			}
		}

		if ( isset( $request['duration']['nights'] ) ) {
			if ( $request['duration']['nights'] >= 0 ) {
				$trip_settings->set( 'trip_duration_nights', $request['duration']['nights'] );
			} else {
				$this->set_bad_request( 'invalid_trip_duration_nights', sprintf( __( '%1$sTrip Duration Night%2$s must be greater than or equal to 0.', 'wp-travel-engine' ), '<strong>', '</strong>' ) );
			}
		}

		if ( isset( $request['cut_off_time']['enable'] ) ) {
			$trip_settings->set( 'trip_cutoff_enable', $request['cut_off_time']['enable'] );
		}

		if ( isset( $request['cut_off_time']['period'] ) ) {
			$cut_off_unit = $request['cut_off_time']['unit'] ?? $trip_settings->get( 'trip_cut_off_unit' );
			if ( 'hours' === $cut_off_unit && ( $request['cut_off_time']['period'] > 24 || $request['cut_off_time']['period'] < 0 ) ) {
				$this->set_bad_request( 'invalid_cut_off_time_period', sprintf( __( '%1$sCut-off Time Period%2$s must fall within the range of 0 to 24 hours.', 'wp-travel-engine' ), '<strong>', '</strong>' ) );
			} elseif ( 'days' === $cut_off_unit && $request['cut_off_time']['period'] < 0 ) {
				$this->set_bad_request( 'invalid_cut_off_time_period', sprintf( __( '%1$sCut-off Time Period%2$s must be greater than or equal to 0.', 'wp-travel-engine' ), '<strong>', '</strong>' ) );
			}

			if ( ! $this->errors ) {
				$trip_settings->set( 'trip_cut_off_time', $request['cut_off_time']['period'] );
			}
		}

		if ( isset( $request['cut_off_time']['unit'] ) ) {
			if ( in_array( $request['cut_off_time']['unit'], array( 'days', 'hours' ) ) ) {
				$trip_settings->set( 'trip_cut_off_unit', $request['cut_off_time']['unit'] );
			} else {
				$this->set_bad_request( 'invalid_cut_off_time_unit', sprintf( __( '%1$sCut-off Time Unit%2$s must be either \'days\' or \'hours\'.', 'wp-travel-engine' ), '<strong>', '</strong>' ) );
			}
		}

		if ( isset( $request['age_limit']['enable'] ) ) {
			$trip_settings->set( 'min_max_age_enable', (string) ( $request['age_limit']['enable'] ? 'true' : 'false' ) );
		}

		if ( isset( $request['age_limit']['min'] ) ) {
			if ( $request['age_limit']['min'] >= 0 ) {
				$trip->set_meta( 'wp_travel_engine_trip_min_age', $request['age_limit']['min'] );
			} else {
				$this->set_bad_request( 'invalid_age_limit_min', sprintf( __( '%1$sMinimum Age Limit%2$s must be greater than or equal to 0.', 'wp-travel-engine' ), '<strong>', '</strong>' ) );
			}
		}

		if ( isset( $request['age_limit']['max'] ) ) {
			if ( $request['age_limit']['max'] > 0 ) {
				$trip->set_meta( 'wp_travel_engine_trip_max_age', $request['age_limit']['max'] );
			} else {
				$this->set_bad_request( 'invalid_age_limit_max', sprintf( __( '%1$sMaximum Age Limit%2$s must be greater than 0.', 'wp-travel-engine' ), '<strong>', '</strong>' ) );
			}
		}

		// if ( isset( $request[ 'participants' ][ 'enable' ] ) ) {
		// $trip_settings->set( 'minmax_pax_enable', (string) ( $request[ 'participants' ][ 'enable' ] ? 'true' : 'false' ) );
		// }

		if ( isset( $request['participants']['min'] ) ) {
			if ( $request['participants']['min'] >= 0 ) {
				$trip->set_meta( '_s_min_pax', $request['participants']['min'] );
				$trip_settings->set( 'trip_minimum_pax', $request['participants']['min'] );
			} else {
				$this->set_bad_request( 'invalid_participants_min', sprintf( __( '%1$sMinimum Participants%2$s must be greater than or equal to 0.', 'wp-travel-engine' ), '<strong>', '</strong>' ) );
			}
		}

		if ( isset( $request['participants']['max'] ) ) {
			if ( $request['participants']['max'] > 0 || $request['participants']['max'] === '' ) {
				$trip->set_meta( '_s_max_pax', $request['participants']['max'] );
				$trip->set_meta( 'total_travellers_per_day', $request['participants']['max'] );
			} else {
				$this->set_bad_request( 'invalid_participants_max', sprintf( __( '%1$sMaximum Participants%2$s must be greater than 0.', 'wp-travel-engine' ), '<strong>', '</strong>' ) );
			}
		}

		if ( isset( $request['overview_title'] ) ) {
			$trip_settings->set( 'overview_section_title', $request['overview_title'] );
		}

		if ( isset( $request['overview'] ) ) {
			$trip_settings->set( 'tab_content.1_wpeditor', $request['overview'] );
		}

		if ( isset( $request['highlights_title'] ) ) {
			$trip_settings->set( 'trip_highlights_title', $request['highlights_title'] );
		}

		if ( isset( $request['highlights'] ) && is_array( $request['highlights'] ) ) {
			$trip_highlights = array();
			foreach ( $request['highlights'] as $highlight ) {
				$trip_highlights[]['highlight_text'] = $highlight;
			}
			$trip_settings->set( 'trip_highlights', $trip_highlights );
		}

		if ( isset( $request['itinerary_title'] ) ) {
			$trip_settings->set( 'trip_itinerary_title', $request['itinerary_title'] );
		}
		if ( isset( $request['itinerary_description'] ) ) {
			$trip_settings->set( 'trip_itinerary_description', $request['itinerary_description'] );
		}

		if ( isset( $request['itineraries'] ) ) {
			$itineraries = $request['itineraries'];
			if ( empty( $itineraries ) ) {
				$trip_settings->set( 'itinerary', array() );
				$trip->set_meta( 'wte_advanced_itinerary', array() );
				$trip->set_meta( 'trip_itinerary_chart_data', '' );
			} else {
				$itinerary_arr_range = range( 1, count( $itineraries ) );
				$basic_itinerary     = array(
					'itinerary_title'      => array_combine( $itinerary_arr_range, array_column( $itineraries, 'title' ) ),
					'itinerary_days_label' => array_combine( $itinerary_arr_range, array_column( $itineraries, 'label' ) ),
					'itinerary_content'    => array_combine( $itinerary_arr_range, array_column( $itineraries, 'content' ) ),
				);
				$trip_settings->set( 'itinerary', $basic_itinerary );

				if ( defined( 'WTEAD_FILE_PATH' ) ) {
					$imgs               = array_column( $itineraries, 'images' );
					$sleep_modes        = array_column( $itineraries, 'sleep_mode' );
					$overnights         = array_column( $itineraries, 'overnights' );
					$advanced_itinerary = array(
						'itinerary_duration'               => array_combine( $itinerary_arr_range, array_column( $itineraries, 'period' ) ),
						'itinerary_duration_type'          => array_combine( $itinerary_arr_range, array_column( $itineraries, 'unit' ) ),
						'sleep_modes'                      => array_combine( $itinerary_arr_range, array_column( $sleep_modes, 'field_id' ) ),
						'itinerary_sleep_mode_description' => array_combine( $itinerary_arr_range, array_column( $sleep_modes, 'description' ) ),
						'meals_included'                   => array_filter( array_combine( $itinerary_arr_range, array_column( $itineraries, 'meals_included' ) ) ),
						'itinerary_image_max_count'        => array_combine( $itinerary_arr_range, array_map( 'count', $imgs ) ),
					);

					$chart_data = array();
					foreach ( $itinerary_arr_range as $key => $val ) {
						$advanced_itinerary['itinerary_image'][ $val ] = array_column( $imgs[ $key ] ?? array(), 'id' );
						$advanced_itinerary['overnight'][ $val ]       = array(
							'at'       => $overnights[ $key ][0]['location'] ?? '',
							'altitude' => $overnights[ $key ][0]['altitude'] ?? 0,
						);
						if ( ! empty( $advanced_itinerary['overnight'][ $val ]['at'] ?? '' ) ) {
							$chart_data[ $val ] = $advanced_itinerary['overnight'][ $val ];
						}
					}

					$trip->set_meta( 'wte_advanced_itinerary', array( 'advanced_itinerary' => $advanced_itinerary ) );

					if ( isset( $chart_data ) ) {
						$trip->set_meta( 'trip_itinerary_chart_data', wp_unslash( wp_json_encode( $chart_data, JSON_UNESCAPED_UNICODE ) ) );
					}

					unset( $itineraries, $itinerary_arr_range, $basic_itinerary, $advanced_itinerary, $imgs, $sleep_modes, $overnights, $chart_data );
				}
			}
		}

		if ( isset( $request['cost_title'] ) ) {
			$trip_settings->set( 'cost_tab_sec_title', $request['cost_title'] );
		}

		if ( isset( $request['cost_includes_title'] ) ) {
			$trip_settings->set( 'cost.includes_title', $request['cost_includes_title'] );
		}

		if ( isset( $request['cost_includes'] ) ) {
			$trip_settings->set( 'cost.cost_includes', implode( "\n", $request['cost_includes'] ) );
		}

		if ( isset( $request['cost_excludes_title'] ) ) {
			$trip_settings->set( 'cost.excludes_title', $request['cost_excludes_title'] );
		}

		if ( isset( $request['cost_excludes'] ) ) {
			$trip_settings->set( 'cost.cost_excludes', implode( "\n", $request['cost_excludes'] ) );
		}

		if ( isset( $request['trip_info_title'] ) ) {
			$trip_settings->set( 'trip_facts_title', $request['trip_info_title'] );
		}

		if ( isset( $request['trip_info'] ) ) {
			$trip_facts = array();
			foreach ( $request['trip_info'] as $info ) {
				if ( isset( $trip_facts[ $info['id'] ] ) ) {
					continue;
				}
				$trip_facts['field_id'][ $info['id'] ]   = $info['label'];
				$trip_facts['field_type'][ $info['id'] ] = $info['type'];
				$trip_facts[ $info['id'] ]               = array( $info['id'] => $info['content'] );
			}
			$trip_settings->set( 'trip_facts', $trip_facts );
		}

		if ( isset( $request['gallery'] ) || isset( $request['gallery_enable'] ) ) {
			$gallery        = $trip->get_meta( 'wpte_gallery_id' );
			$gallery        = empty( $gallery ) ? array() : $gallery;
			$enable_gallery = $gallery ? array_shift( $gallery ) : false;
			$trip->set_meta(
				'wpte_gallery_id',
				array_merge(
					array( 'enable' => isset( $request['gallery_enable'] ) ? ( $request['gallery_enable'] ? '1' : null ) : $enable_gallery ),
					isset( $request['gallery'] ) ? array_unique( array_column( $request['gallery'], 'id' ) ) : $gallery
				)
			);
		}

		if ( isset( $request['video_gallery_enable'] ) ) {
			$trip_settings->set( 'enable_video_gallery', $request['video_gallery_enable'] );
		}

		if ( isset( $request['video_gallery'] ) ) {
			$video_gallery = array();
			$youtube_regex = '/(?:youtube\.com\/.*v=|youtu\.be\/)([a-zA-Z0-9_-]+)/';
			$vimeo_regex   = '/vimeo\.com\/([0-9]+)/';
			foreach ( $request['video_gallery'] as $video ) {
				if ( preg_match( $youtube_regex, $video['url'] ?? '', $matches ) ) {
					$video_id = $matches[1];
					$type     = 'youtube';
				} elseif ( preg_match( $vimeo_regex, $video['url'] ?? '', $matches ) ) {
					$video_id = $matches[1];
					$type     = 'vimeo';
				}
				$video_gallery[] = array(
					'id'    => $video_id ?? '',
					'type'  => $type ?? '',
					'thumb' => $video['thumbnail'],
				);
			}
			$trip->set_meta( 'wpte_vid_gallery', $video_gallery );
		}

		if ( isset( $request['map_title'] ) ) {
			$trip_settings->set( 'map_section_title', $request['map_title'] );
		}

		if ( isset( $request['trip_map'] ) || isset( $request['map_iframe'] ) ) {
			$map        = $trip->get_nested_setting( 'map', '' );
			$map_url    = isset( $request['trip_map']['images'] ) ? array_column( $request['trip_map']['images'], 'id' ) : (array) ( $map['image_url'] ?? array( '' ) );
			$map_iframe = $request['trip_map']['iframe'] ?? $map['iframe'] ?? '';

			$trip_settings->set(
				'map',
				array(
					'image_url' => $map_url[0] ?? '',
					'iframe'    => $map_iframe,
				)
			);
		}

		if ( isset( $request['faqs_title'] ) ) {
			$trip_settings->set( 'faq_section_title', $request['faqs_title'] );
		}

		if ( isset( $request['faqs'] ) ) {
			$trip_settings->set(
				'faq',
				array(
					'faq_title'   => array_column( $request['faqs'], 'question' ),
					'faq_content' => array_column( $request['faqs'], 'answer' ),
				)
			);
		}

		if ( isset( $request['packages'] ) ) {

			$primary_package_id = $trip->get_meta( 'primary_package' );

			foreach ( $request['packages'] ?? array() as $key => $package ) {

				if ( ! ( $package['_changed'] ?? false ) && ! is_null( $package['id'] ?? null ) ) {
					continue;
				}

				$package['name'] = empty( $package['name'] ?? '' ) ? wptravelengine_get_num_suffix( ++$key ) : $package['name'];

				if ( is_null( $package['id'] ?? null ) ) {
					$package['id'] = wp_insert_post(
						array(
							'post_title'   => $package['name'],
							'post_content' => $package['description'] ?? '',
							'post_type'    => 'trip-packages',
							'post_status'  => 'publish',
							'post_author'  => get_current_user_id(),
						)
					);
				}

				if ( $package['is_primary'] || empty( $primary_package_id ) ) {
					$primary_package_id = $package['id'];
				}

				$group_pricing = null;
				foreach ( array_column( $package['traveler_categories'], 'group_pricing', 'id' ) ?? array() as $id => $group_princings ) {
					$group_pricing[ $id ] = array_map(
						fn ( $gp ) => array(
							'from'  => $gp['from'],
							'to'    => $gp['to'],
							'price' => $gp['price'],
						),
						$group_princings
					);
				}

				$weekdays_map = array_combine( range( 1, 7 ), array( 'MO', 'TU', 'WE', 'TH', 'FR', 'SA', 'SU' ) );
				$stored_dates = get_post_meta( $package['id'], 'package-dates' );
				foreach ( $package['dates'] ?? array() as $dates ) {
					$start_date = $dates['start_date'];
					$package_dates [ str_replace( '-', '', $start_date ) ] = array_filter(
						array(
							'dtstart'            => $start_date,
							'times'              => $dates['times'],
							'is_recurring'       => $dates['enable_repeat'] ? '1' : null,
							'rrule'              => array_filter(
								array(
									'r_frequency' => $dates['repeat']['frequency'],
									'r_weekdays'  => $dates['repeat']['frequency'] === 'WEEKLY'
										? array_intersect( $weekdays_map, $dates['repeat']['weekdays'] )
										: ( $stored_dates['rrule']['r_weekdays'] ?? null ),
									'r_months'    => $dates['repeat']['frequency'] === 'MONTHLY'
										? array_combine( $dates['repeat']['months'], $dates['repeat']['months'] )
										: ( $stored_dates['rrule']['r_months'] ?? null ),
									'r_until'     => empty( $dates['repeat']['until'] ) ? wp_date( 'Y-m-d', strtotime( "{$start_date} +3 years" ) ) : $dates['repeat']['until'],
									'r_count'     => self::$recurring_date_count_limit,
								)
							),
							'seats'              => $dates['total_seats'] ?? 0,
							'availability_label' => $dates['availability_label'],
							'addon_metas'        => is_array( $dates['addon_metas'] ?? null ) ? $dates['addon_metas'] : array(),
						),
						fn ( $v ) => $v !== null
					);
				}

				$package_ids            = array_column( $package['traveler_categories'], 'id', 'id' );
				$primary_category_index = array_search( true, array_column( $package['traveler_categories'], 'is_primary' ) );
				$primary_category_id    = $package['traveler_categories'][ $primary_category_index ]['id'];

				$meta_inputs[] = apply_filters(
					'wptravelengine_package_meta_inputs',
					array(
						'package_id'               => $package['id'],
						'package_name'             => $package['name'],
						'package_description'      => $package['description'] ?? '',
						'trip_ID'                  => $trip->ID,
						'enable_weekly_time_slots' => isset( $package['time_slots_enable'] ) ? ( $package['time_slots_enable'] ? 'yes' : 'no' ) : 'no',
						'weekly_time_slots'        => empty( $package['time_slots'] ) ? null : array_filter( array_combine( range( 1, 7 ), $package['time_slots'] ) ),
						'enable_week_days'         => $package['enable_week_days'] ?? array_combine( array_values( $weekdays_map ), array_fill( 0, 7, false ) ),
						'package-categories'       => array_filter(
							array(
								'c_ids'                  => $package_ids,
								'labels'                 => array_column( $package['traveler_categories'], 'label', 'id' ),
								'prices'                 => array_column( $package['traveler_categories'], 'price', 'id' ),
								'pricing_types'          => array_combine( $package_ids, array_column( array_column( $package['traveler_categories'], 'pricing_type' ), 'value' ) ),
								'enabled_sale'           => array_filter( array_combine( $package_ids, array_map( fn ( $val ) => $val ? '1' : null, array_column( $package['traveler_categories'], 'has_sale' ) ) ) ),
								'sale_prices'            => array_column( $package['traveler_categories'], 'sale_price', 'id' ),
								'min_paxes'              => array_column( $package['traveler_categories'], 'min_pax', 'id' ),
								// 'max_paxes'              => array_column( $package[ 'traveler_categories' ], 'max_pax', 'id' ),
								'enabled_group_discount' => array_filter( array_combine( $package_ids, array_map( fn ( $val ) => $val ? '1' : null, array_column( $package['traveler_categories'], 'has_group_pricing' ) ) ) ),
							),
							fn ( $v ) => $v !== null && ! empty( $v )
						),
						'group-pricing'            => $group_pricing,
						'package-dates'            => $package_dates ?? array(),
						'_primary_category_id'     => $primary_category_id,
					),
					$package,
					$this
				);

				$last_meta_input   = end( $meta_inputs );
				$common_plain_text = sprintf( __( 'must be greater than or equal to 0 in \'%s Package\'.', 'wp-travel-engine' ), $package['name'] );

				if ( '' === $last_meta_input['package-categories']['prices'][ $primary_category_id ] ) {
					$this->set_bad_request( 'invalid_param', sprintf( __( 'The %1$s%3$s%2$s category in the %1$s%4$s%2$s Package requires a valid price.', 'wp-travel-engine' ), '<strong>', '</strong>', $last_meta_input['package-categories']['labels'][ $primary_category_id ], $package['name'] ) );
				}

				if ( '1' === ( $last_meta_input['package-categories']['enabled_sale'][ $primary_category_id ] ?? '' ) ) {
					$sale = $last_meta_input['package-categories']['sale_prices'][ $primary_category_id ] ?? '';
					if ( $sale === '' ) {
						$this->set_bad_request( 'invalid_param', sprintf( __( 'The %1$s%3$s%2$s category in the %1$s%4$s%2$s Package requires a valid sale price.', 'wp-travel-engine' ), '<strong>', '</strong>', $last_meta_input['package-categories']['labels'][ $primary_category_id ], $package['name'] ) );
					}
				}

				$regular_vs_sale_prices = (bool) array_filter( $last_meta_input['package-categories']['prices'] ?? array(), fn ( $val, $key ) => '' !== $val ? ( $val > 0 && ( $val <= $last_meta_input['package-categories']['sale_prices'][ $key ] ?? 0 ) ) : false, ARRAY_FILTER_USE_BOTH );
				if ( $regular_vs_sale_prices ) {
					$this->set_bad_request( 'invalid_param', sprintf( __( '%1$sSale price%2$s must be less than %1$sRegular price%2$s in \'%3$s Package\'.', 'wp-travel-engine' ), '<strong>', '</strong>', $package['name'] ), "{$package['name']}_sale_price" );
				}

				// $are_counts_valid = (bool) array_filter( array_column( $last_meta_input['package-dates']['rrule'] ?? array(), 'r_count' ), fn ( $val ) => $val < 0 );
				// if ( $are_counts_valid ) {
				// $this->set_bad_request( 'invalid_param', sprintf( __( '%1$sRepeat Limit%2$s %3$s', 'wp-travel-engine' ), '<strong>', '</strong>', $common_plain_text ), "{$package['name']}_repeat_limit" );
				// }

				$are_seats_valid = (bool) array_filter( array_column( $last_meta_input['package-dates'] ?? array(), 'seats' ), fn ( $val ) => ( $val < 0 && '' !== $val ) );
				if ( $are_seats_valid ) {
					$this->set_bad_request( 'invalid_param', sprintf( __( '%1$sTotal Seats%2$s %3$s', 'wp-travel-engine' ), '<strong>', '</strong>', $common_plain_text ), "{$package['name']}_total_seats" );
				}

				$are_prices_valid = (bool) array_filter( $last_meta_input['package-categories']['prices'] ?? array(), fn ( $val ) => '' !== $val ? $val < 0 : false );
				if ( $are_prices_valid ) {
					$this->set_bad_request( 'invalid_param', sprintf( __( '%1$sPrice%2$s %3$s', 'wp-travel-engine' ), '<strong>', '</strong>', $common_plain_text ), "{$package['name']}_price" );
				}

				$are_sale_prices_valid = (bool) array_filter( $last_meta_input['package-categories']['sale_prices'] ?? array(), fn ( $val ) => '' !== $val ? $val < 0 : false );
				if ( $are_sale_prices_valid ) {
					$this->set_bad_request( 'invalid_param', sprintf( __( '%1$sSale Price%2$s %3$s', 'wp-travel-engine' ), '<strong>', '</strong>', $common_plain_text ), "{$package['name']}_sale_price" );
				}

				$are_min_paxes_valid = (bool) array_filter( $last_meta_input['package-categories']['min_paxes'] ?? array(), fn ( $val ) => $val < 0 );
				if ( $are_min_paxes_valid ) {
					$this->set_bad_request( 'invalid_param', sprintf( __( '%1$sMinimum Pax%2$s %3$s', 'wp-travel-engine' ), '<strong>', '</strong>', $common_plain_text ), "{$package['name']}_min_pax" );
				}

				// $are_max_paxes_valid = ! ! array_filter( $last_meta_input[ 'package-categories' ][ 'max_paxes' ] ?? [], fn ( $val ) => ( $val <= 0 && '' !== $val ) );
				// if ( $are_max_paxes_valid ) {
				// $this->set_bad_request( 'invalid_param', sprintf( __( '%sMaximum Pax%s %s must be greater than 0 in \'%s Package\'.', 'wp-travel-engine' ), '<strong>', '</strong>', $package[ 'name' ] ), "{$package['name']}_max_pax" );
				// }

				unset( $group_pricing, $package_dates, $package_ids, $last_meta_input, $common_plain_text, $regular_vs_sale_prices, $are_counts_valid, $are_seats_valid, $are_prices_valid, $are_sale_prices_valid, $are_min_paxes_valid, $are_max_paxes_valid );
			}

			$trip->set_meta( 'primary_package', $primary_package_id );

			$trip_package_ids = array_unique(
				array_merge(
					array_filter( array_column( $request['packages'], 'id' ) ),
					array_column( $meta_inputs ?? array(), 'package_id' )
				)
			);

			if ( ! isset( $this->errors ) ) {
				$trip->set_meta( 'packages_ids', $trip_package_ids );
				foreach ( $meta_inputs ?? array() as $meta_input ) {
					$ID           = array_shift( $meta_input );
					$post_title   = array_shift( $meta_input );
					$post_content = array_shift( $meta_input );
					$meta_input   = array_filter( $meta_input, fn ( $v ) => $v !== null );

					wp_update_post( compact( 'ID', 'post_title', 'post_content', 'meta_input' ) );
				}

				$available_months = array();
				foreach ( $trip_package_ids as $package_id ) {
					$package_dates    = get_post_meta( $package_id, 'package-dates', true ) ?: array();
					$available_months = array_merge(
						$available_months,
						array_values(
							array_unique(
								call_user_func_array(
									'array_merge',
									array_map(
										function ( $string ) use ( $package_id, $trip, $package_dates ) {
											return ( new PackageDateParser(
												new TripPackage( $package_id, $trip ),
												$package_dates[ $string ]
											) )->get_unique_dates( false, array(), 'ym' );
										},
										array_keys( $package_dates )
									)
								)
							)
						)
					);
				}

				if ( isset( $available_months ) ) {
					$trip->set_meta( 'trip_available_months', implode( ',', array_unique( $available_months ) ) );
				}

				$trip->save();

				$updated_trip = new Post\Trip( $trip->ID );
				$price        = $updated_trip->has_sale() ? $updated_trip->get_sale_price() : $updated_trip->get_price();
				$trip->set_meta( 'wp_travel_engine_setting_trip_actual_price', $updated_trip->get_price() );
				$trip->set_meta( 'wp_travel_engine_setting_trip_price', $price );
				$trip->set_meta( '_s_price', $price );
				$trip->set_meta( '_s_has_sale', $updated_trip->has_sale() ? 'yes' : 'no' );
				Options::delete( 'wptravelengine_indexed_trips_by_dates' );
			}
		}

		if ( isset( $request['custom_tabs'] ) ) {
			$tab_content = $trip_settings->get( 'tab_content', array() );
			$settings    = Options::get( 'wp_travel_engine_settings', array() );
			foreach ( $settings['trip_tabs']['id'] ?? array() as $key => $i ) {
				$i = (int) $i;
				if ( isset( $request['custom_tabs'][ 'tab_' . $i ]['title'] ) ) {
					$trip_settings->set( 'tab_' . $i . '_title', $request['custom_tabs'][ 'tab_' . $i ]['title'] );
				}
				if ( isset( $request['custom_tabs'][ 'tab_' . $i ]['content'] ) ) {
					$tab_content[ $i . '_wpeditor' ] = $request['custom_tabs'][ 'tab_' . $i ]['content'];
				}
			}
			$trip_settings->set( 'tab_content', $tab_content );
		}

		if ( ! $trip->get_meta( '_s_price' ) ) {
			$trip->set_meta( '_s_price', $trip->get_price() );
		}
	}

	/**
	 * Get single trip data.
	 *
	 * @param WP_Post         $item Post object as ID is valid.
	 * @param WP_REST_Request $request Full details about the request.
	 * TODO: Create methods in trip modal to get these values, like $trip->get_code, $trip->get_duration, etc.
	 *
	 * @return WP_Error|WP_HTTP_Response|WP_REST_Response
	 */
	public function prepare_item_for_response( $item, $request ) {

		remove_filter(
			"rest_prepare_{$this->post_type}",
			array(
				\WPTravelEngine\Core\REST_API::class,
				'filter_rest_data_trip',
			),
			10
		);

		$data = parent::prepare_item_for_response( $item, $request )->get_data();

		$this->trip = $trip = new Post\Trip( $item->ID );

		// Fixed starting dates data.
		$data['fsd'] = array(
			'title' => '',
			'hide'  => false,
		);

		$data['trip_code'] = (string) $trip->get_trip_code();

		$data['overview_title'] = (string) $trip->get_setting( 'overview_section_title', '' );
		$data['overview']       = (string) $trip->get_setting( 'tab_content.1_wpeditor', '' );

		$data['cost_title'] = (string) $trip->get_setting( 'cost_tab_sec_title', '' );

		$data['cost_includes_title'] = (string) $trip->get_setting( 'cost.includes_title', '' );
		$data['cost_includes']       = array_values( array_filter( explode( "\n", $trip->get_setting( 'cost.cost_includes', '' ) ), fn ( $val ) => ! empty( $val ) ) );

		$data['cost_excludes_title'] = (string) $trip->get_setting( 'cost.excludes_title', '' );
		$data['cost_excludes']       = array_values( array_filter( explode( "\n", $trip->get_setting( 'cost.cost_excludes', '' ) ), fn ( $val ) => ! empty( $val ) ) );

		$data['duration']    = array(
			'period' => (int) $trip->get_setting( 'trip_duration', 1 ),
			'unit'   => (string) $trip->get_setting( 'trip_duration_unit', 'days' ),
			'nights' => (int) $trip->get_setting( 'trip_duration_nights', 0 ),
		);
		$duration            = ( 'days' === ( $data['duration']['unit'] ?? 'days' ) ) ? $data['duration']['period'] * 24 : $data['duration']['period'];
		$data['_s_duration'] = $trip->search_in_meta( '_s_duration', $duration );

		$data['age_limit'] = array(
			'enable' => wptravelengine_toggled( $trip->get_setting( 'min_max_age_enable', false ) ),
			'min'    => (int) $trip->get_meta( 'wp_travel_engine_trip_min_age' ) ?? 0,
			'max'    => (int) $trip->get_meta( 'wp_travel_engine_trip_max_age' ) ?? 0,
		);

		$data['cut_off_time'] = array(
			'enable' => wptravelengine_toggled( $trip->get_setting( 'trip_cutoff_enable', false ) ),
			'period' => (int) $trip->get_setting( 'trip_cut_off_time', 0 ),
			'unit'   => (string) $trip->get_setting( 'trip_cut_off_unit', 'days' ),
		);

		$data['participants'] = array(
			// 'enable' => wptravelengine_toggled( $trip->get_setting( 'minmax_pax_enable', false ) ),
			'min' => (int) $trip->get_setting( 'trip_minimum_pax', 1 ),
			'max' => $trip->get_maximum_participants(),
		);

		$map_img_id = $trip->get_setting( 'map.image_url', array() );
		$trip_imgs  = array();
		if ( ! empty( $map_img_id ) ) {
			$id          = (int) $map_img_id;
			$alt         = get_post_meta( $id, '_wp_attachment_image_alt', true );
			$url         = wp_get_attachment_image_url( $id, 'full' );
			$trip_imgs[] = compact( 'id', 'alt', 'url' );
		}

		$data['map_title'] = (string) $trip->get_setting( 'map_section_title', '' );
		$data['trip_map']  = array(
			'images' => $trip_imgs,
			'iframe' => (string) $trip->get_setting( 'map.iframe', '' ),
		);

		$data['highlights_title'] = (string) $trip->get_setting( 'trip_highlights_title', '' );
		$highlights               = (array) ( $trip->get_setting( 'trip_highlights', array() ) );
		$data['highlights']       = array();
		foreach ( $highlights as $highlight ) {
			$data['highlights'][] = (string) $highlight['highlight_text'];
		}

		$data['faqs']       = array();
		$data['faqs_title'] = (string) $trip->get_setting( 'faq_section_title', '' );
		$faqs               = (array) $trip->get_setting( 'faq.faq_title', array() );
		foreach ( $faqs as $key => $faq ) {
			$answer = (string) $trip->get_setting( 'faq.faq_content.' . $key, '' );

			$data['faqs'][] = array(
				'question' => (string) $faq,
				'answer'   => (string) $answer,
			);
		}

		$data['gallery_enable'] = wptravelengine_toggled( $trip->search_in_meta( 'wpte_gallery_id.enable', false ) );
		$data['gallery']        = array();
		$i                      = 0;
		$gallery                = empty( $trip->get_meta( 'wpte_gallery_id' ) ) ? array() : $trip->get_meta( 'wpte_gallery_id' );
		foreach ( (array) $gallery as $index => $id ) {
			if ( 'enable' === $index ) {
				continue;
			}
			$id  = (int) $id;
			$alt = get_post_meta( $id, '_wp_attachment_image_alt', true );
			$url = wp_get_attachment_image_url( $id, 'full' );

			$data['gallery'][ $i++ ] = compact( 'id', 'alt', 'url' );
		}

		$data['video_gallery_enable'] = wptravelengine_toggled( $trip->get_setting( 'enable_video_gallery', false ) );
		$data['video_gallery']        = array();
		$video_gallery                = empty( $trip->get_meta( 'wpte_vid_gallery' ) ) ? array() : $trip->get_meta( 'wpte_vid_gallery' );
		foreach ( (array) $video_gallery as $key => $value ) {
			$id        = (string) ( $value['id'] ?? '' );
			$type      = (string) ( $value['type'] ?? '' );
			$thumbnail = (string) ( $value['thumb'] ?? '' );
			$url       = ( 'youtube' === $type ) ? '//www.youtube.com/watch?v=' . $id : '//vimeo.com/' . $id;

			$data['video_gallery'][] = compact( 'url', 'thumbnail' );
		}

		$itinerary_title               = (array) $trip->get_setting( 'itinerary.itinerary_title', array() );
		$data['itinerary_title']       = (string) $trip->get_setting( 'trip_itinerary_title', '' );
		$data['itinerary_description'] = (string) $trip->get_setting( 'trip_itinerary_description', '' );
		$data['itineraries']           = array();
		foreach ( $itinerary_title as $key => $itinerary ) {
			$label   = (string) ( $trip->get_setting( 'itinerary.itinerary_days_label.' . $key, '' ) );
			$content = (string) ( $trip->get_setting( 'itinerary.itinerary_content.' . $key, '' ) );

			if ( empty( $itinerary ) && empty( $label ) && empty( $content ) ) {
				continue;
			}

			$data['itineraries'][] = array(
				'title'          => (string) $itinerary,
				'label'          => (string) $label,
				'content'        => (string) $content,
				'period'         => 0,
				'unit'           => 'hour',
				'sleep_mode'     => array(
					'field_id'    => '',
					'description' => '',
				),
				'meals_included' => array(),
				'images'         => array(),
				'overnights'     => array(
					array(
						'location' => '',
						'altitude' => 0,
					),
				),
			);
		}
		// Advance Itinerary
		if ( defined( 'WTEAD_FILE_PATH' ) ) {
			$advanced_itinerary = (array) $trip->search_in_meta( 'wte_advanced_itinerary.advanced_itinerary', array() );
			$all_overnights     = array();
			foreach ( $advanced_itinerary['overnight'] ?? array() as $key => $overnight ) {
				$all_overnights[ $key ] = array(
					'location' => (string) ( $overnight['at'] ?? '' ),
					'altitude' => (float) ( $overnight['altitude'] ?? '' ),
				);
			}
			$img = $trip->search_in_meta( 'wte_advanced_itinerary.advanced_itinerary.itinerary_image', array() );
			foreach ( $advanced_itinerary['itinerary_duration'] ?? array() as $key => $value ) {
				if ( ! isset( $data['itineraries'][ $key - 1 ] ) ) {
					continue;
				}
				$temp_img       = empty( $img[ $key ] ) ? array() : array_values( array_unique( $img[ $key ] ) );
				$images         = array_values(
					array_filter(
						array_map(
							function ( $id ) {
								$id = (int) $id;
								if ( ! wp_attachment_is_image( $id ) ) {
										return null;
								}
								$alt = get_post_meta( $id, '_wp_attachment_image_alt', true );
								$url = wp_get_attachment_image_url( $id, 'full' );

								return compact( 'id', 'alt', 'url' );
							},
							$temp_img
						)
					)
				);
				$period         = (float) ( $advanced_itinerary['itinerary_duration'][ $key ] ?? 0 );
				$meals_included = (array) ( $advanced_itinerary['meals_included'][ $key ] ?? array() );
				$unit           = (string) ( $advanced_itinerary['itinerary_duration_type'][ $key ] ?? 'hour' );
				$sleep_mode     = array(
					'field_id'    => (string) ( $advanced_itinerary['sleep_modes'][ $key ] ?? '' ),
					'description' => (string) ( $advanced_itinerary['itinerary_sleep_mode_description'][ $key ] ?? '' ),
				);

				$overnights = array( (array) $all_overnights[ $key ] ?? array() );

				$data['itineraries'][ $key - 1 ] = array_merge( $data['itineraries'][ $key - 1 ] ?? array(), compact( 'period', 'unit', 'sleep_mode', 'meals_included', 'images', 'overnights' ) );
			}
		}

		$trip_facts              = $trip->get_setting( 'trip_facts', array() );
		$data['trip_info_title'] = (string) $trip->get_setting( 'trip_facts_title', '' );
		$data['trip_info']       = array();
		$global_trip_facts       = wptravelengine_get_trip_facts_options();
		foreach ( $trip_facts['field_id'] ?? array() as $key => $field_name ) {
			if ( isset( $trip_facts[ $key ][ $key ] ) && ( $global_trip_facts['field_id'][ $key ] ?? '' ) === $field_name ) {
				$data['trip_info'][] = array(
					'id'      => (int) $key,
					'label'   => (string) $field_name,
					'content' => (string) $trip->get_setting( 'trip_facts.' . $key . '.' . $key, '' ),
					'type'    => (string) $trip->get_setting( 'trip_facts.field_type.' . $key, '' ),
					'options' => (array) ( isset( $global_trip_facts['select_options'] ) ? explode( ',', $global_trip_facts['select_options'][ $key ] ) : array() ),
				);
			}
		}

		$data['file_downloads'] = array();

		$data['trip_extra_services'] = array();

		$primary_package  = (int) $trip->get_meta( 'primary_package' );
		$trip_packages    = new Post\TripPackages( $trip );
		$data['packages'] = array();
		foreach ( $trip_packages as $key => $trip_package ) {
			/** @var Post\TripPackage $trip_package */
			$data['packages'][ $key ]               = $this->prepare_package( $trip_package );
			$data['packages'][ $key ]['is_primary'] = $primary_package === $trip_package->ID;
		}

		$settings      = Options::get( 'wp_travel_engine_settings', array() );
		$pricing_label = wptravelengine_get_pricing_type( true );

		$pricing_type = array();
		foreach ( $pricing_label as $key => $item ) {
			$pricing_type[ $key ] = __( 'Per', 'wp-travel-engine' ) . ' ' . $item['label'];
		}
		$data['price_types'] = $pricing_type;
		$def_tabs            = array( 'itinerary', 'cost', 'dates', 'faqs', 'map', 'review' );
		foreach ( $settings['trip_tabs']['id'] ?? array() as $key => $i ) {
			$i     = (int) $i;
			$field = $settings['trip_tabs']['field'][ $i ] ?? '';
			if ( 1 === $i || in_array( $field, $def_tabs ) ) {
				continue;
			}
			$data['custom_tabs'][ 'tab_' . $i ] = array(
				'title'   => (string) $trip->get_setting( 'tab_' . $i . '_title', '' ),
				'content' => (string) $trip->get_setting( 'tab_content.' . $i . '_wpeditor', '' ),
			);
		}

		$data = apply_filters( 'wptravelengine_rest_prepare_trip', $data, $request, $this );

		$trip->save();

		return rest_ensure_response( $this->filter_by_context( $data ) );
	}

	/**
	 * Prepares the data for package.
	 *
	 * @param POST\TripPackage $trip_package
	 *
	 * @return array
	 */
	public function prepare_package( $trip_package ) {
		$return_data = array(
			'time_slots_enable' => false,
			'time_slots'        => array_combine(
				array(
					'MO',
					'TU',
					'WE',
					'TH',
					'FR',
					'SA',
					'SU',
				),
				array_fill( 0, 7, array() )
			),
			'enable_week_days'  => array_combine(
				array(
					'MO',
					'TU',
					'WE',
					'TH',
					'FR',
					'SA',
					'SU',
				),
				array_fill( 0, 7, false )
			),
			'dates'             => array(),
		);
		if ( class_exists( 'WTE_Fixed_Starting_Dates' ) ) {
			foreach ( $trip_package->get_meta( 'package-dates' ) ?: array() as $date_data ) {
				$times = array();
				foreach ( $date_data['times'] ?? array() as $time ) {
					$times[] = array(
						'from' => (string) $time['from'],
						'to'   => (string) $time['to'],
					);
				}
				$repeat = array();
				if ( isset( $date_data['rrule'] ) ) {
					$repeat = array(
						'frequency' => (string) ( $date_data['rrule']['r_frequency'] ?? '' ),
						'months'    => (array) array_values( $date_data['rrule']['r_months'] ?? array() ),
						'weekdays'  => (array) array_values( $date_data['rrule']['r_weekdays'] ?? array() ),
						'until'     => (string) $date_data['rrule']['r_until'] ?? $date_data['dtstart'] ?? wp_date( 'Y-m-d' ),
						'limit'     => self::$recurring_date_count_limit,
					);
				}
				$return_data['dates'][] = array(
					'start_date'         => (string) ( $date_data['dtstart'] ?? wp_date( 'Y-m-d' ) ),
					'times'              => (array) $times,
					'enable_repeat'      => (bool) wptravelengine_toggled( $date_data['is_recurring'] ?? false ),
					'repeat'             => (array) $repeat,
					'total_seats'        => $date_data['seats'] ?? '',
					'availability_label' => (string) $date_data['availability_label'] ?? 'guaranteed',
				);
			}
		} elseif ( 'hours' === $trip_package->get_trip()->get_setting( 'trip_duration_unit' ) ) {
			$return_data['time_slots_enable'] = wptravelengine_toggled( $trip_package->get_meta( 'enable_weekly_time_slots' ) );
			$return_data['enable_week_days']  = ! empty( $trip_package->get_meta( 'enable_week_days' ) ) ? $trip_package->get_meta( 'enable_week_days' ) : $return_data['enable_week_days'];
			$weekly_time_slots                = $trip_package->get_meta( 'weekly_time_slots' );
			foreach ( array( 'MO', 'TU', 'WE', 'TH', 'FR', 'SA', 'SU' ) as $index => $day ) {
				$return_data['time_slots'][ $day ] = $weekly_time_slots[ ++$index ] ?? array();
			}
		}

		return apply_filters( 'wptravelengine_rest_prepare_package', array_merge( $this->prepare_package_data( $trip_package ), $return_data ), $trip_package );
	}

	/**
	 * Get Trip Services.
	 *
	 * @param WP_REST_Request $request
	 *
	 * @return WP_Error|WP_HTTP_Response|WP_REST_Response
	 */
	public function get_services( \WP_REST_Request $request ) {
		$id = $request->get_param( 'id' );

		try {
			$trip = new Post\Trip( $id );

			$data = $trip->get_services();
		} catch ( \Exception $e ) {
			$data = array();
		}

		return rest_ensure_response( $data );
	}

	/**
	 * Get Trip Dates.
	 *
	 * @param WP_REST_Request $request Request Class.
	 *
	 * @return WP_Error|WP_REST_Response
	 */
	public function get_dates( WP_REST_Request $request ) {
		$id = $request->get_param( 'id' );

		/* @var Post\Trip $trip */
		$trip          = Post\Trip::make( $id );
		$trip_packages = new Post\TripPackages( $trip );

		$from = $request->get_param( 'from' ) ?? wp_date( 'Y-m-d' );
		$to   = $request->get_param( 'to' ) ?? wp_date( 'Y-m-d', strtotime( "{$from} +3 years" ) );

		$dates = array();
		foreach ( $trip_packages as $trip_package ) {
			/** @var Post\TripPackage $trip_package */
			$data = array(
				'package' => $this->prepare_package_data( $trip_package ),
				'dates'   => $trip_package->get_package_dates( compact( 'from', 'to' ) ),
			);

			$dates[] = $data;

		}

		return rest_ensure_response( $dates );
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
			$label                         = sprintf( __( 'Per %s', 'wp-travel-engine' ), $pricing_label['label'] );
			$price                         = $category->get( 'price', '' );
			$sale_price                    = $category->get( 'sale_price', '' );
			$data['traveler_categories'][] = array(
				'id'                => (int) $category->get( 'id', 0 ),
				'label'             => $category->get( 'label', '' ),
				'price'             => is_numeric( $price ) ? (float) $price : '',
				'age_group'         => $category->get( 'age_group', '' ),
				'pricing_type'      => array(
					'value' => $get_pricing_type,
					'label' => $label,
				),
				'sale_price'        => is_numeric( $sale_price ) ? (float) $sale_price : '',
				'has_sale'          => wptravelengine_toggled( $category->get( 'has_sale', false ) ),
				'has_group_pricing' => wptravelengine_toggled( $category->get( 'enabled_group_discount', false ) && ! empty( $group_pricing ) ),
				'group_pricing'     => $group_pricing,
				'min_pax'           => $min_pax,
				'max_pax'           => '',
				'description'       => $category->get( 'description', '' ),
				'is_primary'        => $trip_package->primary_pricing_category->id === $category->id,
			);
		}

		return $data;
	}

	/**
	 * Get Trip packages.
	 *
	 * @param WP_REST_Request $request Request Class.
	 *
	 * @return WP_Error|WP_REST_Response
	 */
	public function get_packages( WP_REST_Request $request ) {
		$packages_controller = new TripPackages();

		$request->set_param( 'trip_id', $request->get_param( 'id' ) );

		return $packages_controller->get_items( $request );
	}

	/**
	 * Get Trip Schema.
	 *
	 * @return array
	 */
	public function get_item_schema(): array {

		$schema = parent::get_item_schema();

		$properties = $schema['properties'] ?? array();

		$item_properties = array(
			// Trip specific properties.
			'trip_code'            => array(
				'description' => __( 'Trip code.', 'wp-travel-engine' ),
				'type'        => 'string',
			),
			'duration'             => array(
				'description' => __( 'Trip duration.', 'wp-travel-engine' ),
				'type'        => 'object',
				'properties'  => array(
					'period' => array(
						'description' => __( 'Duration period.', 'wp-travel-engine' ),
						'type'        => array( 'integer', 'string' ),
					),
					'unit'   => array(
						'description' => __( 'Duration unit.', 'wp-travel-engine' ),
						'type'        => 'string',
						'enum'        => array( 'days', 'hours' ),
					),
					'nights' => array(
						'description' => __( 'Duration nights.', 'wp-travel-engine' ),
						'type'        => array( 'integer', 'string' ),
					),
				),
			),
			'cut_off_time'         => array(
				'description' => __( 'Trip cut off time.', 'wp-travel-engine' ),
				'type'        => 'object',
				'context'     => array( 'view', 'edit' ),
				'properties'  => array(
					'enable' => array(
						'description' => __( 'Cut off enabled.', 'wp-travel-engine' ),
						'type'        => 'boolean',
						'enum'        => array( true, false ),
						'context'     => array( 'edit' ),
					),
					'period' => array(
						'description' => __( 'Cut off period.', 'wp-travel-engine' ),
						'type'        => array( 'integer', 'string' ),
					),
					'unit'   => array(
						'description' => __( 'Cut off unit.', 'wp-travel-engine' ),
						'type'        => 'string',
						'enum'        => array( 'days', 'hours' ),
					),
				),
			),
			'age_limit'            => array(
				'description' => __( 'Trip age.', 'wp-travel-engine' ),
				'type'        => 'object',
				'properties'  => array(
					'enable' => array(
						'description' => __( 'Age enabled.', 'wp-travel-engine' ),
						'type'        => 'boolean',
						'enum'        => array( true, false ),
						'context'     => array( 'edit' ),
					),
					'min'    => array(
						'description' => __( 'Minimum age.', 'wp-travel-engine' ),
						'type'        => array( 'integer', 'string' ),
					),
					'max'    => array(
						'description' => __( 'Maximum age.', 'wp-travel-engine' ),
						'type'        => array( 'integer', 'string' ),
					),
				),
			),
			'participants'         => array(
				'description' => __( 'Minimum and maximum participants for booking this trip.', 'wp-travel-engine' ),
				'type'        => 'object',
				'properties'  => array(
					// 'enable' => array(
					// 'description' => __( 'Pax enabled.', 'wp-travel-engine' ),
					// 'type'        => 'boolean',
					// 'enum'        => array( true, false ),
					// 'context'     => array( 'edit' ),
					// ),
					'min' => array(
						'description' => __( 'Minimum pax.', 'wp-travel-engine' ),
						'type'        => array( 'integer', 'string' ),
					),
					'max' => array(
						'description' => __( 'Maximum pax.', 'wp-travel-engine' ),
						'type'        => array( 'integer', 'string' ),
					),
				),
			),
			'overview_title'       => array(
				'description' => __( 'Trip overview title.', 'wp-travel-engine' ),
				'type'        => 'string',
				'context'     => array( 'edit' ),
			),
			'overview'             => array(
				'description' => __( 'Trip overview.', 'wp-travel-engine' ),
				'type'        => 'string',
			),
			'highlights_title'     => array(
				'description' => __( 'Trip highlights title.', 'wp-travel-engine' ),
				'type'        => 'string',
				'context'     => array( 'edit' ),
			),
			'highlights'           => array(
				'description' => __( 'Trip highlights.', 'wp-travel-engine' ),
				'type'        => 'array',
				'items'       => array(
					'type' => 'string',
				),
			),
			'itinerary_title'      => array(
				'description' => __( 'Trip itinerary title.', 'wp-travel-engine' ),
				'type'        => 'string',
				'context'     => array( 'edit' ),
			),
			'itineraries'          => array(
				'description' => __( 'Trip itineraries.', 'wp-travel-engine' ),
				'type'        => 'array',
				'items'       => array(
					'type'       => 'object',
					'properties' => array(
						'title'          => array(
							'description' => __( 'Itinerary title.', 'wp-travel-engine' ),
							'type'        => 'string',
						),
						'label'          => array(
							'description' => __( 'Itinerary label.', 'wp-travel-engine' ),
							'type'        => 'string',
							'context'     => array( 'edit' ),
						),
						'content'        => array(
							'description' => __( 'Itinerary content.', 'wp-travel-engine' ),
							'type'        => 'string',
						),
						'period'         => array(
							'description' => __( 'Itinerary period.', 'wp-travel-engine' ),
							'type'        => 'float',
						),
						'unit'           => array(
							'description' => __( 'Itinerary period unit.', 'wp-travel-engine' ),
							'type'        => 'string',
							'enum'        => array( 'hour', 'minute' ),
						),
						'sleep_mode'     => array(
							'description' => __( 'Itinerary sleep mode.', 'wp-travel-engine' ),
							'type'        => 'object',
							'properties'  => array(
								'field_id'    => array(
									'description' => __( 'Sleep mode field.', 'wp-travel-engine' ),
									'type'        => 'string',
								),
								'description' => array(
									'description' => __( 'Sleep mode description.', 'wp-travel-engine' ),
									'type'        => 'string',
								),
							),
						),
						'meals_included' => array(
							'description' => __( 'Itinerary meals included.', 'wp-travel-engine' ),
							'type'        => 'array',
							'items'       => array(
								'type' => 'string',
							),
						),
						'images'         => array(
							'description' => __( 'Itinerary images.', 'wp-travel-engine' ),
							'type'        => 'array',
							'items'       => array(
								'type'       => 'object',
								'properties' => array(
									'id'  => array(
										'description' => __( 'Image ID.', 'wp-travel-engine' ),
										'type'        => 'integer',
									),
									'alt' => array(
										'description' => __( 'Image alt.', 'wp-travel-engine' ),
										'type'        => 'string',
									),
									'url' => array(
										'description' => __( 'Image URL.', 'wp-travel-engine' ),
										'type'        => 'string',
									),
								),
							),
						),
						'overnights'     => array(
							'description' => __( 'Itinerary overnights.', 'wp-travel-engine' ),
							'type'        => 'object',
							'properties'  => array(
								'location' => array(
									'description' => __( 'Overnight location.', 'wp-travel-engine' ),
									'type'        => 'string',
								),
								'altitude' => array(
									'description' => __( 'Overnight altitude.', 'wp-travel-engine' ),
									'type'        => 'float',
								),
							),
						),
					),
				),
			),
			'cost_title'           => array(
				'description' => __( 'Trip cost title.', 'wp-travel-engine' ),
				'type'        => 'string',
				'context'     => array( 'edit' ),
			),
			'cost_includes_title'  => array(
				'description' => __( 'Trip cost includes title.', 'wp-travel-engine' ),
				'type'        => 'string',
				'context'     => array( 'edit' ),
			),
			'cost_includes'        => array(
				'description' => __( 'Trip cost includes.', 'wp-travel-engine' ),
				'type'        => 'array',
				'items'       => array(
					'type' => 'string',
				),
			),
			'cost_excludes_title'  => array(
				'description' => __( 'Trip cost excludes title.', 'wp-travel-engine' ),
				'type'        => 'string',
				'context'     => array( 'edit' ),
			),
			'cost_excludes'        => array(
				'description' => __( 'Trip cost excludes.', 'wp-travel-engine' ),
				'type'        => 'array',
				'items'       => array(
					'type' => 'string',
				),
			),
			'trip_info_title'      => array(
				'description' => __( 'Trip info title.', 'wp-travel-engine' ),
				'type'        => 'string',
				'context'     => array( 'edit' ),
			),
			'trip_info'            => array(
				'description' => __( 'Trip facts.', 'wp-travel-engine' ),
				'type'        => 'array',
				'items'       => array(
					'type'       => 'object',
					'properties' => array(
						'id'      => array(
							'description' => __( 'Facts ID.', 'wp-travel-engine' ),
							'type'        => 'integer',
						),
						'label'   => array(
							'description' => __( 'Facts label.', 'wp-travel-engine' ),
							'type'        => 'string',
						),
						'content' => array(
							'description' => __( 'Facts content.', 'wp-travel-engine' ),
							'type'        => 'string',
						),
						'type'    => array(
							'description' => __( 'Facts type.', 'wp-travel-engine' ),
							'type'        => 'string',
							'context'     => array( 'edit' ),
						),
						'options' => array(
							'description' => __( 'Facts options.', 'wp-travel-engine' ),
							'context'     => array( 'edit' ),
							'type'        => 'array',
							'items'       => array(
								'type' => 'string',
							),
						),
					),
				),
			),
			'gallery_enable'       => array(
				'description' => __( 'Trip gallery enabled.', 'wp-travel-engine' ),
				'type'        => 'boolean',
				'enum'        => array( true, false ),
				'context'     => array( 'edit' ),
			),
			'gallery'              => array(
				'description' => __( 'Trip image gallery.', 'wp-travel-engine' ),
				'type'        => 'array',
				'items'       => array(
					'type'       => 'object',
					'properties' => array(
						'id'  => array(
							'description' => __( 'Attachment ID.', 'wp-travel-engine' ),
							'type'        => 'integer',
						),
						'alt' => array(
							'description' => __( 'Image alt text.', 'wp-travel-engine' ),
							'type'        => 'string',
						),
						'url' => array(
							'description' => __( 'Image URL.', 'wp-travel-engine' ),
							'type'        => 'string',
						),
					),
					'context'    => array( 'view' ),
				),
			),
			'video_gallery_enable' => array(
				'description' => __( 'Trip video gallery enabled.', 'wp-travel-engine' ),
				'type'        => 'boolean',
				'enum'        => array( true, false ),
				'context'     => array( 'edit' ),
			),
			'video_gallery'        => array(
				'description' => __( 'Trip video gallery.', 'wp-travel-engine' ),
				'type'        => 'array',
				'items'       => array(
					'type'       => 'object',
					'properties' => array(
						'url'       => array(
							'description' => __( 'Video URL.', 'wp-travel-engine' ),
							'type'        => 'string',
						),
						'thumbnail' => array(
							'description' => __( 'Video thumbnail.', 'wp-travel-engine' ),
							'type'        => 'string',
						),
					),
				),
			),
			'map_title'            => array(
				'description' => __( 'Trip map title.', 'wp-travel-engine' ),
				'type'        => 'string',
				'context'     => array( 'edit' ),
			),
			'trip_map'             => array(
				'description' => __( 'Trip map.', 'wp-travel-engine' ),
				'type'        => 'object',
				'properties'  => array(
					'images' => array(
						'description' => __( 'Map images.', 'wp-travel-engine' ),
						'type'        => 'array',
						'items'       => array(
							'type'       => 'object',
							'properties' => array(
								'id'  => array(
									'description' => __( 'Attachment ID.', 'wp-travel-engine' ),
									'type'        => 'integer',
								),
								'alt' => array(
									'description' => __( 'Image alt text.', 'wp-travel-engine' ),
									'type'        => 'string',
								),
								'url' => array(
									'description' => __( 'Image URL.', 'wp-travel-engine' ),
									'type'        => 'string',
								),
							),
						),
					),
					'iframe' => array(
						'description' => __( 'Map iframe.', 'wp-travel-engine' ),
						'type'        => 'string',
					),
				),
			),
			'faqs_title'           => array(
				'description' => __( 'Trip FAQs title.', 'wp-travel-engine' ),
				'type'        => 'string',
				'context'     => array( 'edit' ),
			),
			'faqs'                 => array(
				'description' => __( 'Trip FAQs.', 'wp-travel-engine' ),
				'type'        => 'array',
				'items'       => array(
					'type'       => 'object',
					'properties' => array(
						'question' => array(
							'description' => __( 'Question.', 'wp-travel-engine' ),
							'type'        => 'string',
						),
						'answer'   => array(
							'description' => __( 'Answer.', 'wp-travel-engine' ),
							'type'        => 'string',
						),
					),
				),
			),
			'packages'             => array(
				'description' => __( 'Trip packages.', 'wp-travel-engine' ),
				'type'        => 'array',
				'context'     => array( 'edit' ),
				'items'       => array(
					'type'       => 'object',
					'properties' => array(
						'id'                  => array(
							'description' => __( 'Package ID.', 'wp-travel-engine' ),
							'type'        => array( 'integer', 'null' ),
						),
						'name'                => array(
							'description' => __( 'Package name.', 'wp-travel-engine' ),
							'type'        => 'string',
						),
						'description'         => array(
							'description' => __( 'Package description.', 'wp-travel-engine' ),
							'type'        => 'string',
						),
						'is_primary'          => array(
							'description' => __( 'Is package primary.', 'wp-travel-engine' ),
							'type'        => 'boolean',
							'enum'        => array( true, false ),
						),
						'_changed'            => array(
							'description' => __( 'Package changed.', 'wp-travel-engine' ),
							'type'        => 'boolean',
							'enum'        => array( true, false ),
						),
						'traveler_categories' => array(
							'description' => __( 'Package traveler categories.', 'wp-travel-engine' ),
							'type'        => 'array',
							'items'       => array(
								'type'       => 'object',
								'properties' => array(
									'id'                => array(
										'description' => __( 'Traveler category ID.', 'wp-travel-engine' ),
										'type'        => 'integer',
									),
									'label'             => array(
										'description' => __( 'Traveler category label.', 'wp-travel-engine' ),
										'type'        => 'string',
									),
									'price'             => array(
										'description' => __( 'Traveler category price.', 'wp-travel-engine' ),
										'type'        => array( 'float', 'string' ),
									),
									'pricing_type'      => array(
										'description' => __( 'Traveler category pricing type.', 'wp-travel-engine' ),
										'type'        => 'object',
										'properties'  => array(
											'value' => array(
												'description' => __( 'Pricing type value.', 'wp-travel-engine' ),
												'type' => 'string',
											),
											'label' => array(
												'description' => __( 'Pricing type label.', 'wp-travel-engine' ),
												'type' => 'string',
											),
										),
									),
									'sale_price'        => array(
										'description' => __( 'Traveler category sale price.', 'wp-travel-engine' ),
										'type'        => array( 'float', 'string' ),
									),
									'has_sale'          => array(
										'description' => __( 'Traveler category has sale.', 'wp-travel-engine' ),
										'type'        => 'boolean',
										'enum'        => array( true, false ),
									),
									'has_group_pricing' => array(
										'description' => __( 'Traveler category has group pricing.', 'wp-travel-engine' ),
										'type'        => 'boolean',
										'enum'        => array( true, false ),
									),
									'group_pricing'     => array(
										'description' => __( 'Traveler category group pricing.', 'wp-travel-engine' ),
										'type'        => 'array',
										'items'       => array(
											'type'       => 'object',
											'properties' => array(
												'from'  => array(
													'description' => __( 'Group pricing from.', 'wp-travel-engine' ),
													'type' => 'integer',
												),
												'to'    => array(
													'description' => __( 'Group pricing to.', 'wp-travel-engine' ),
													'type' => array( 'integer', 'string' ),
												),
												'price' => array(
													'description' => __( 'Group pricing price.', 'wp-travel-engine' ),
													'type' => 'float',
												),
											),
										),
									),
									'min_pax'           => array(
										'description' => __( 'Traveler category minimum pax.', 'wp-travel-engine' ),
										'type'        => array( 'integer', 'string' ),
									),
									'max_pax'           => array(
										'description' => __( 'Traveler category maximum pax.', 'wp-travel-engine' ),
										'type'        => array( 'integer', 'string' ),
									),
								),
							),
						),
						'time_slots_enable'   => array(
							'description' => __( 'Package time slots enabled.', 'wp-travel-engine' ),
							'type'        => 'boolean',
							'enum'        => array( true, false ),
						),
						'time_slots'          => array(
							'description' => __( 'Package time slots.', 'wp-travel-engine' ),
							'type'        => 'object',
							'properties'  => array(
								'MO' => array(
									'description' => __( 'Monday time slots.', 'wp-travel-engine' ),
									'type'        => array( 'array' ),
								),
								'TU' => array(
									'description' => __( 'Tuesday time slots.', 'wp-travel-engine' ),
									'type'        => array( 'array' ),
								),
								'WE' => array(
									'description' => __( 'Wednesday time slots.', 'wp-travel-engine' ),
									'type'        => array( 'array' ),
								),
								'TH' => array(
									'description' => __( 'Thursday time slots.', 'wp-travel-engine' ),
									'type'        => array( 'array' ),
								),
								'FR' => array(
									'description' => __( 'Friday time slots.', 'wp-travel-engine' ),
									'type'        => array( 'array' ),
								),
								'SA' => array(
									'description' => __( 'Saturday time slots.', 'wp-travel-engine' ),
									'type'        => array( 'array' ),
								),
								'SU' => array(
									'description' => __( 'Sunday time slots.', 'wp-travel-engine' ),
									'type'        => array( 'array' ),
								),
							),
						),
						'enable_week_days'    => array(
							'description' => __( 'Package weekly time enabled.', 'wp-travel-engine' ),
							'type'        => 'object',
							'context'     => array( 'edit' ),
							'properties'  => array(
								'MO' => array(
									'description' => __( 'Monday time enabled.', 'wp-travel-engine' ),
									'type'        => 'boolean',
									'enum'        => array( true, false ),
								),
								'TU' => array(
									'description' => __( 'Tuesday time enabled.', 'wp-travel-engine' ),
									'type'        => 'boolean',
									'enum'        => array( true, false ),
								),
								'WE' => array(
									'description' => __( 'Wednesday time enabled.', 'wp-travel-engine' ),
									'type'        => 'boolean',
									'enum'        => array( true, false ),
								),
								'TH' => array(
									'description' => __( 'Thursday time enabled.', 'wp-travel-engine' ),
									'type'        => 'boolean',
									'enum'        => array( true, false ),
								),
								'FR' => array(
									'description' => __( 'Friday time enabled.', 'wp-travel-engine' ),
									'type'        => 'boolean',
									'enum'        => array( true, false ),
								),
								'SA' => array(
									'description' => __( 'Saturday time enabled.', 'wp-travel-engine' ),
									'type'        => 'boolean',
									'enum'        => array( true, false ),
								),
								'SU' => array(
									'description' => __( 'Sunday time enabled.', 'wp-travel-engine' ),
									'type'        => 'boolean',
									'enum'        => array( true, false ),
								),
							),
						),
						'dates'               => array(
							'description' => __( 'Package dates.', 'wp-travel-engine' ),
							'type'        => 'array',
							'items'       => array(
								'type'       => 'object',
								'properties' => array(
									'start_date'         => array(
										'description' => __( 'Package start date.', 'wp-travel-engine' ),
										'type'        => 'string',
									),
									'times'              => array(
										'description' => __( 'Package times.', 'wp-travel-engine' ),
										'type'        => 'array',
										'items'       => array(
											'type'       => 'object',
											'properties' => array(
												'from' => array(
													'description' => __( 'Package time from.', 'wp-travel-engine' ),
													'type' => 'string',
												),
												'to'   => array(
													'description' => __( 'Package time to.', 'wp-travel-engine' ),
													'type' => 'string',
												),
											),
										),
									),
									'enable_repeat'      => array(
										'description' => __( 'Package enable repeat.', 'wp-travel-engine' ),
										'type'        => 'boolean',
										'enum'        => array( true, false ),
									),
									'repeat'             => array(
										'description' => __( 'Package repeat.', 'wp-travel-engine' ),
										'type'        => 'object',
										'properties'  => array(
											'frequency' => array(
												'description' => __( 'Package repeated frequency.', 'wp-travel-engine' ),
												'type' => 'string',
											),
											'weekdays'  => array(
												'description' => __( 'Package repeated weekdays.', 'wp-travel-engine' ),
												'type'  => 'array',
												'items' => array(
													'type' => 'string',
												),
											),
											'months'    => array(
												'description' => __( 'Package repeated months.', 'wp-travel-engine' ),
												'type'  => 'array',
												'items' => array(
													'type' => 'integer',
												),
											),
											'until'     => array(
												'description' => __( 'Package repeated until.', 'wp-travel-engine' ),
												'type' => 'string',
											),
											'limit'     => array(
												'description' => __( 'Package repeated limit.', 'wp-travel-engine' ),
												'type' => array( 'integer', 'string' ),
											),
										),
									),
									'total_seats'        => array(
										'description' => __( 'Package total seats.', 'wp-travel-engine' ),
										'type'        => array( 'integer', 'string' ),
									),
									'availability_label' => array(
										'description' => __( 'Package availability label.', 'wp-travel-engine' ),
										'type'        => 'string',
									),
								),
							),
						),
					),
				),
			),
			'custom_tabs'          => array(
				'description' => __( 'Trip custom tabs.', 'wp-travel-engine' ),
				'type'        => 'object',
				'properties'  => array(
					'type'  => 'array',
					'items' => array(
						'title'   => array(
							'description' => __( 'Custom tab title.', 'wp-travel-engine' ),
							'type'        => 'string',
						),
						'content' => array(
							'description' => __( 'Custom tab content.', 'wp-travel-engine' ),
							'type'        => 'string',
						),
					),
				),
			),
		);

		$item_properties = apply_filters( 'wptravelengine_trip_api_schema', $item_properties, $this );

		$schema['properties'] = array_merge( $properties, $item_properties );

		return $schema;
	}

	/**
	 * @return void
	 */
	protected function set_trip_images( $trip, $images ) {

		if ( is_array( $images ) ) {
			$gallery = $trip->get_meta( 'wpte_gallery_id' ) ?? array(
				'enable' => 'yes',
			);

			foreach ( $images as $image ) {
				$attachment_id = isset( $image['id'] ) ? absint( $image['id'] ) : 0;

				if ( 0 === $attachment_id && isset( $image['src'] ) ) {
					$attachment_id = $this->upload_image( $image['src'] );
				}

				if ( wp_attachment_is_image( $attachment_id ) ) {
					$gallery[] = $attachment_id;
				}
			}

			$trip->set_meta( 'wpte_gallery_id', $gallery );
		}

		return $trip;
	}

	/**
	 * Upload image from URL.
	 *
	 * @param string $src Image URL.
	 *
	 * @return int
	 */
	protected function upload_image( string $src ): int {
		$upload_dir = wp_upload_dir();
		$filename   = basename( $src );
		$filename   = sanitize_file_name( $filename );
		$filename   = wp_unique_filename( $upload_dir['path'], $filename );

		$contents = wp_remote_retrieve_body( wp_remote_get( $src ) );

		if ( ! $contents ) {
			return 0;
		}

		$upload = wp_upload_bits( $filename, null, $contents );

		if ( ! empty( $upload['error'] ) ) {
			return 0;
		}

		$allowed_mime_types = array(
			'jpg|jpeg|jpe' => 'image/jpeg',
			'gif'          => 'image/gif',
			'png'          => 'image/png',
			'bmp'          => 'image/bmp',
			'tiff|tif'     => 'image/tiff',
			'webp'         => 'image/webp',
		);

		$attachment_id = wp_insert_attachment(
			array(
				'post_mime_type' => wp_check_filetype( $upload['file'], $allowed_mime_types )['type'],
				'post_title'     => preg_replace( '/\.[^.]+$/', '', $filename ),
				'post_content'   => '',
				'post_status'    => 'inherit',
			),
			$upload['file']
		);

		if ( is_wp_error( $attachment_id ) ) {
			return 0;
		}

		return $attachment_id;
	}

	/**
	 * This method is used to filter response data
	 * based on the context and current user.
	 *
	 * @param array $response_data Response data.
	 *
	 * @return array
	 */
	public function filter_by_context( $response_data ) {

		if ( ! current_user_can( 'edit_posts', $response_data['id'] ?? null ) ) {

			$filter_recursive = function ( &$data, $schema ) use ( &$filter_recursive ) {

				foreach ( $data as $key => &$value ) {

					$current_context = $schema[ $key ]['context'] ?? $schema['context'] ?? array();

					if ( in_array( 'edit', array_merge_recursive( $current_context ), true ) && ! in_array( 'view', $current_context, true ) ) {
						unset( $data[ $key ] );
						continue;
					}

					if ( is_array( $value ) ) {
						$current_items = $schema[ $key ]['items'] ?? $schema['items'] ?? array();
						$current_props = $schema[ $key ]['properties'] ?? $schema['properties'] ?? null;
						$filter_recursive( $value, $current_props ?? $current_items );
					}
				}
			};

			$filter_recursive( $response_data, $this->get_item_schema()['properties'] );

		}

		return $response_data;
	}

	/**
	 * Sanitize Request Params.
	 *
	 * @param WP_REST_Request $request Request object.
	 *
	 * @return mixed
	 * @since 6.2.2
	 * @updated 6.2.3
	 */
	private function sanitize_params_recursive( $params ) {
		$sanitized_params = array();

		foreach ( $params as $key => $value ) {
			if ( is_array( $value ) ) {
				$sanitized_params[ $key ] = $this->sanitize_params_recursive( $value );
			} elseif ( is_int( $value ) ) {
				$sanitized_params[ $key ] = intval( $value );
			} elseif ( is_float( $value ) ) {
				$sanitized_params[ $key ] = floatval( $value );
			} elseif ( is_bool( $value ) ) {
				$sanitized_params[ $key ] = filter_var( $value, FILTER_VALIDATE_BOOLEAN );
			} elseif ( is_email( $value ) ) {
				$sanitized_params[ $key ] = sanitize_email( $value );
			} elseif ( filter_var( $value, FILTER_VALIDATE_URL ) ) {
				$sanitized_params[ $key ] = esc_url_raw( $value );
			} else {
				$sanitized_params[ $key ] = $value;
			}
		}

		return $sanitized_params;
	}
}
