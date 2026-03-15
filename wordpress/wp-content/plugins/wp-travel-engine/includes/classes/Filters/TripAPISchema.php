<?php
/**
 * Trip API Schema
 *
 * @package WPTravelEngine
 * @since 6.2.2
 */

namespace WPTravelEngine\Filters;

use WP_REST_Request;
use WPTravelEngine\Core\Models\Post\Trip;
use WPTravelEngine\Utilities\ArrayUtility;
use WPTravelEngine\Core\Models\Settings\Options;
use WPTravelEngine\Core\Controllers\RestAPI\V2\Trip as TripController;

class TripAPISchema {

	/**
	 * Trip Object.
	 *
	 * @var Trip
	 */
	protected Trip $trip;

	/**
	 * Trip Settings Object.
	 *
	 * @var ArrayUtility
	 */
	protected ArrayUtility $trip_settings;

	/**
	 * Instance.
	 *
	 * @var TripAPISchema
	 */
	protected static $instance;

	/**
	 * Returns trip api schema instance.
	 *
	 * @return TripAPISchema
	 */
	public static function instance() {
		if ( ! isset( self::$instance ) || ! ( self::$instance instanceof self ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->hooks();
	}

	/**
	 * Initializes hooks for trip edit api schema.
	 *
	 * @return void
	 */
	public function hooks() {
		add_filter( 'wptravelengine_trip_api_schema', array( $this, 'trip_edit_api_schema' ), 10, 2 );
		add_filter( 'wptravelengine_rest_prepare_trip', array( $this, 'trip_edit_api_prepare' ), 10, 3 );
		add_action( 'wptravelengine_api_update_trip', array( $this, 'trip_edit_api_update' ), 10, 2 );
	}

	/**
	 * Filters the trip edit api schema.
	 *
	 * @param array          $schema
	 * @param TripController $controller
	 *
	 * @return array
	 */
	public function trip_edit_api_schema( array $schema, TripController $controller ): array {

		// Fixed starting dates schema.
		$schema['fsd'] = array(
			'description' => __( 'Fixed starting dates.', 'wp-travel-engine' ),
			'type'        => 'object',
			'properties'  => array(
				'title' => array(
					'description' => __( 'Fixed starting dates title.', 'wp-travel-engine' ),
					'type'        => 'string',
					'context'     => array( 'edit' ),
				),
				'hide'  => array(
					'description' => __( 'Fixed starting dates hide.', 'wp-travel-engine' ),
					'type'        => 'boolean',
				),
			),
		);

		// Extra services schema.
		$schema['trip_extra_services'] = array(
			'description' => __( 'Trip extra services.', 'wp-travel-engine' ),
			'type'        => 'array',
			'items'       => array(
				'type'       => 'object',
				'properties' => array(
					'id'           => array(
						'description' => __( 'Extra service ID.', 'wp-travel-engine' ),
						'type'        => 'integer',
					),
					'label'        => array(
						'description' => __( 'Extra service label.', 'wp-travel-engine' ),
						'type'        => 'string',
					),
					'type'         => array(
						'description' => __( 'Extra service type.', 'wp-travel-engine' ),
						'type'        => 'string',
					),
					'options'      => array(
						'description' => __( 'Extra service options.', 'wp-travel-engine' ),
						'type'        => 'array',
						'items'       => array(
							'type' => 'string',
						),
					),
					'descriptions' => array(
						'description' => __( 'Extra service descriptions.', 'wp-travel-engine' ),
						'type'        => 'array',
						'items'       => array(
							'type' => 'string',
						),
					),
					'prices'       => array(
						'description' => __( 'Extra service prices.', 'wp-travel-engine' ),
						'type'        => 'array',
						'items'       => array(
							'type' => 'number',
						),
					),
				),
			),
		);

		// File downloads schema.
		$schema['file_downloads'] = array(
			'description' => __( 'Trip File downloads.', 'wp-travel-engine' ),
			'type'        => 'array',
			'items'       => array(
				'type'       => 'object',
				'properties' => array(
					'id'    => array(
						'description' => __( 'File downloads id.', 'wp-travel-engine' ),
						'type'        => 'integer',
						'context'     => array( 'edit' ),
					),
					'type'  => array(
						'description' => __( 'File downloads type.', 'wp-travel-engine' ),
						'type'        => 'string',
					),
					'title' => array(
						'description' => __( 'File downloads title.', 'wp-travel-engine' ),
						'type'        => 'string',
					),
					'url'   => array(
						'description' => __( 'File downloads url.', 'wp-travel-engine' ),
						'type'        => 'string',
					),
				),
			),
		);

		// Partial payment schema.
		$schema['partial_payment'] = array(
			'description' => __( 'Trip partial payment.', 'wp-travel-engine' ),
			'type'        => 'object',
			'properties'  => array(
				'enable'     => array(
					'description' => __( 'Partial payment enabled.', 'wp-travel-engine' ),
					'type'        => 'boolean',
				),
				'amount'     => array(
					'description' => __( 'Partial payment amount.', 'wp-travel-engine' ),
					'type'        => 'float',
				),
				'percentage' => array(
					'description' => __( 'Partial payment percentage.', 'wp-travel-engine' ),
					'type'        => 'float',
				),
			),
		);

		$schema['full_payment_enable'] = array(
			'description' => __( 'Trip full payment enabled.', 'wp-travel-engine' ),
			'type'        => 'boolean',
		);

		return $schema;
	}

	/**
	 * Prepares the fixed starting dates data.
	 *
	 * @param array           $data
	 * @param WP_REST_Request $request
	 *
	 * @return void
	 */
	protected function prepare_fsd( array &$data, WP_REST_Request $request ): void {

		if ( wptravelengine_is_addon_active( 'fixed-starting-dates' ) ) {
			$fsd         = (array) $this->trip->get_meta( 'WTE_Fixed_Starting_Dates_setting' ) ?? array();
			$data['fsd'] = array(
				'title' => (string) ( $fsd['availability_title'] ?? '' ),
				'hide'  => wptravelengine_toggled( $fsd['departure_dates']['section'] ?? false ),
			);
		}
	}

	/**
	 * Prepares the extra services data.
	 *
	 * @param array           $data
	 * @param WP_REST_Request $request
	 *
	 * @return void
	 */
	public function prepare_extra_services( array &$data, WP_REST_Request $request ): void {

		// Check if the addon is active.
		if ( ! wptravelengine_is_addon_active( 'extra-services' ) ) {
			return;
		}

		// Get the extra services ids.
		$extra_services_ids = $this->trip->get_setting( 'wte_services_ids' );
		if ( empty( $extra_services_ids ) ) {
			return;
		}

		$services = get_posts(
			array(
				'post_type'      => 'wte-services',
				'post_status'    => 'publish',
				'post__in'       => explode( ',', $extra_services_ids ),
				'posts_per_page' => -1,
				'orderby'        => 'post__in',
			)
		);

		// Get the trip extra services.
		$trip_extra_services = $this->trip->get_setting( 'trip_extra_services' ) ?? array();

		// Loop through the services.
		foreach ( $services as $index => $service ) {
			$service_data = get_post_meta( $service->ID, 'wte_services', true );

			// Skip if service data is empty.
			if ( empty( $service_data ) ) {
				continue;
			}

			// Get extra service data saved in meta for current index.
			$extra_service_data = $trip_extra_services[ $index ] ?? array();

			// Handle Options.
			$service_data['options'] = $extra_service_data['options'] ?? $service_data['options'] ?? array();

			// Handle Descriptions.
			$service_data['descriptions'] = $extra_service_data['descriptions'] ?? $service_data['descriptions'] ?? array();

			// Handle Prices.
			$service_data['prices'] = $extra_service_data['prices'] ?? $service_data['prices'] ?? array();

			// Handle Compatibility with old data for Default Service Type.
			if ( $service_data['service_type'] != 'custom' ) {
				// Set default price if not set or empty string.
				if ( ! isset( $service_data['prices'][0] ) || $service_data['prices'][0] === '' ) {
					$service_data['prices'] = array(
						0 => $service_data['service_cost'] ?? 0,
					);
				}

				// Set default description if not set or empty string.
				if ( ! isset( $service_data['descriptions'][0] ) || $service_data['descriptions'][0] === '' ) {
					$service_data['descriptions'] = array(
						0 => get_the_content( '', false, $service->ID ) ?? '',
					);
				}
			}

			// Handle Service Type.
			$service_data['service_type'] = isset( $service_data['service_type'] )
				? ( $service_data['service_type'] == 'custom' ? 'Advanced' : 'Default' )
				: 'Default';

			// Add service data to trip extra services.
			$data['trip_extra_services'][] = array(
				'id'           => (int) $service->ID,
				'label'        => (string) $service->post_title,
				'type'         => (string) $service_data['service_type'],
				'is_required'  => (bool) ( $service_data['service_required'] ?? false ),
				'options'      => (array) $service_data['options'],
				'descriptions' => (array) $service_data['descriptions'],
				'prices'       => (array) $service_data['prices'],
			);
		}
	}



	/**
	 * Prepares the file downloads data.
	 *
	 * @param array           $data
	 * @param WP_REST_Request $request
	 *
	 * @return void
	 */
	protected function prepare_file_downloads( array &$data, WP_REST_Request $request ): void {

		if ( wptravelengine_is_addon_active( 'file-downloads' ) ) {
			$downloadable = (array) $this->trip->search_in_meta( 'wte_file_downloads.file_downloads.wte_files_downloadable', array() );
			foreach ( $downloadable as $file_info ) {
				$data['file_downloads'][] = array(
					'id'    => (int) ( $file_info['id'] ?? 0 ),
					'type'  => (string) get_post_mime_type( $file_info['id'] ?? '' ),
					'title' => (string) ( $file_info['title'] ?? '' ),
					'url'   => (string) ( $file_info['url'] ?? '' ),
				);
			}
		}
	}

	/**
	 * Prepares the partial payment data.
	 *
	 * @param array           $data
	 * @param WP_REST_Request $request
	 *
	 * @return void
	 */
	protected function prepare_partial_payment( array &$data, WP_REST_Request $request ): void {

		if ( wptravelengine_is_addon_active( 'partial-payment' ) ) {
			$data['partial_payment']     = array(
				'enable'     => wptravelengine_toggled( $this->trip->get_setting( 'partial_payment_enable', false ) ),
				'amount'     => (float) ( $this->trip->get_setting( 'partial_payment_amount', 0 ) ),
				'percentage' => (float) ( $this->trip->get_setting( 'partial_payment_percent', 0 ) ),
			);
			$data['full_payment_enable'] = wptravelengine_toggled( $this->trip->get_setting( 'trip_full_payment_enabled', 'no' ) );
		}
	}

	/**
	 * Filters the trip edit api prepare.
	 *
	 * @param array           $data
	 * @param WP_REST_Request $request
	 * @param TripController  $controller
	 *
	 * @return array
	 */
	public function trip_edit_api_prepare( array $data, WP_REST_Request $request, TripController $controller ): array {

		$this->trip = $controller->trip;

		$this->prepare_fsd( $data, $request );
		$this->prepare_extra_services( $data, $request );
		$this->prepare_file_downloads( $data, $request );
		$this->prepare_partial_payment( $data, $request );

		return $data;
	}

	/**
	 * Updates the trip edit api.
	 *
	 * @param WP_REST_Request $request
	 * @param TripController  $controller
	 *
	 * @return void
	 */
	public function trip_edit_api_update( WP_REST_Request $request, TripController $controller ): void {

		$this->trip          = $controller->trip;
		$this->trip_settings = $controller->trip_settings;

		$this->update_fsd( $request );
		$this->update_extra_services( $request );
		$this->update_file_downloads( $request );
		$this->update_partial_payment( $request, $controller );
	}

	/**
	 * Updates the fixed starting dates.
	 *
	 * @param WP_REST_Request $request
	 *
	 * @return void
	 */
	protected function update_fsd( WP_REST_Request $request ): void {

		if ( isset( $request['fsd'] ) ) {

			if ( isset( $request['fsd']['title'] ) ) {
				$availability_title = $request['fsd']['title'];
			} else {
				$availability_title = $this->trip->get_meta( 'WTE_Fixed_Starting_Dates_setting' )['availability_title'] ?? '';
			}

			if ( isset( $request['fsd']['hide'] ) ) {
				$hide_departure_dates = $request['fsd']['hide'];
			} else {
				$hide_departure_dates = wptravelengine_toggled( $this->trip->get_meta( 'WTE_Fixed_Starting_Dates_setting' )['departure_dates']['section'] ?? false );
			}

			$this->trip->set_meta(
				'WTE_Fixed_Starting_Dates_setting',
				array(
					'availability_title' => $availability_title,
					'departure_dates'    => $hide_departure_dates ? array( 'section' => '1' ) : array(),
				)
			);

		}
	}

	/**
	 * Updates the extra services.
	 *
	 * @param WP_REST_Request $request
	 *
	 * @return void
	 */
	public function update_extra_services( WP_REST_Request $request ): void {
		if ( isset( $request['trip_extra_services'] ) ) {
			$this->trip_settings->set( 'trip_extra_services', $request['trip_extra_services'] );
			$this->trip_settings->set( 'wte_services_ids', implode( ',', array_column( $request['trip_extra_services'], 'id' ) ) );
		}
	}

	/**
	 * Updates the file downloads.
	 *
	 * @param WP_REST_Request $request
	 *
	 * @return void
	 */
	protected function update_file_downloads( WP_REST_Request $request ): void {
		if ( isset( $request['file_downloads'] ) ) {
			$file_downloads = array();
			foreach ( $request['file_downloads'] ?? array() as $key => $value ) {
				if ( in_array( $value['id'] ?? '', array_column( $file_downloads, 'id' ) ) ) {
					continue;
				}
				unset( $value['type'] );
				$file_downloads[ ++$key ] = $value;
			}
			$this->trip->set_meta(
				'wte_file_downloads',
				array(
					'file_downloads' => array(
						'file_downloads_meta_max_count' => count( $request['file_downloads'] ),
						'wte_files_downloadable'        => $file_downloads,
					),
				)
			);
		}
	}

	/**
	 * Updates the partial payment.
	 *
	 * @param WP_REST_Request $request
	 * @param TripController  $controller
	 *
	 * @return void
	 */
	protected function update_partial_payment( WP_REST_Request $request, TripController $controller ): void {
		if ( isset( $request['partial_payment']['enable'] ) ) {
			$this->trip_settings->set( 'partial_payment_enable', $request['partial_payment']['enable'] );
		}

		if ( isset( $request['partial_payment']['amount'] ) ) {
			if ( $request['partial_payment']['amount'] >= 0 ) {
				$this->trip_settings->set( 'partial_payment_amount', $request['partial_payment']['amount'] );
			} else {
				$controller->set_bad_request( 'invalid_partial_payment_amount', sprintf( __( '%1$sPartial Payment Amount%2$s must be greater than or equal to 0.', 'wp-travel-engine' ), '<strong>', '</strong>' ) );
			}
		}

		if ( isset( $request['partial_payment']['percentage'] ) ) {
			if ( $request['partial_payment']['percentage'] >= 0 ) {
				$this->trip_settings->set( 'partial_payment_percent', $request['partial_payment']['percentage'] );
			} else {
				$controller->set_bad_request( 'invalid_partial_payment_percentage', sprintf( __( '%1$sPartial Payment Percentage%2$s must be greater than or equal to 0.', 'wp-travel-engine' ), '<strong>', '</strong>' ) );
			}
		}

		if ( isset( $request['full_payment_enable'] ) ) {
			$this->trip_settings->set( 'trip_full_payment_enabled', $request['full_payment_enable'] ? 'yes' : 'no' );
		}
	}
}
