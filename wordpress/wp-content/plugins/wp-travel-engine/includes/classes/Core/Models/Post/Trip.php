<?php
/**
 * Trip Model.
 *
 * @package WPTravelEngine/Core/Models
 * @since 6.0.0
 */

namespace WPTravelEngine\Core\Models\Post;

use WP_Post;
use WPTravelEngine\Abstracts\PostModel;
use WPTravelEngine\Core\Booking\Inventory;
use WPTravelEngine\Core\Models\Settings\Options;
use WPTravelEngine\Core\Models\Settings\PluginSettings;

/**
 * Class Trip.
 * This class represents a trip to the WP Travel Engine plugin.
 *
 * @property array $packages
 * @since 6.0.0
 */
class Trip extends PostModel {

	/**
	 * Trip version.
	 *
	 * @var string
	 */
	protected string $trip_version = '1.0.0';

	/**
	 * Post type name.
	 *
	 * @var string
	 */
	protected string $post_type = 'trip';

	/**
	 * Holds Inventory object.
	 */
	protected ?Inventory $inventory = null;

	/**
	 * Trip packages.
	 *
	 * @var TripPackages
	 */
	protected TripPackages $trip_packages;

	/**
	 * The primary package of the trip.
	 *
	 * @var ?TripPackage
	 */
	protected ?TripPackage $primary_package = null;

	/**
	 * The inventory of available packages.
	 *
	 * @var array
	 * @since 6.6.7
	 */
	protected array $my_booked_seats = array();

	/**
	 * The start date of the trip without fsd addon.
	 *
	 * @var string
	 * @since 6.7.3
	 */
	public string $plain_start_date = '';

	/**
	 * Constructs a new instance of the Trip class.
	 *
	 * This constructor calls the parent constructor and then retrieves
	 * the trip version from the post meta. If the trip version is not
	 * found in the post meta, it defaults to the initial trip version.
	 *
	 * @param WP_Post|int $post The post-object.
	 */
	public function __construct( $post ) {
		parent::__construct( $post );

		$trip_version = $this->get_meta( 'trip_version' );

		$this->trip_version = empty( $trip_version ) ? $this->trip_version : $trip_version;

		$this->set_plain_start_date();

		$this->trip_packages = new TripPackages( $this );

		$this->set_primary_package();
	}

	/**
	 * Trip Version.
	 *
	 * @return string
	 */
	public function version(): string {
		return $this->trip_version;
	}

	/**
	 * Get the post meta value.
	 *
	 * @param string $dot_keys The key for the setting you want to retrieve.
	 * @param mixed  $default_value The default value to return if the setting is not found.
	 *
	 * @return mixed The meta value or null if not found
	 */
	public function get_setting( string $dot_keys, $default_value = null ) {
		return $this->get_nested_setting( $dot_keys, $default_value );
	}

	/**
	 * Gets the settings values to be fetched in nested key.
	 *
	 * @param string $dot_keys The nested keys. Designed to be dot-separated.
	 * @param mixed  $default_value The default value to return if the setting is not found.
	 *
	 * @return mixed
	 */
	public function get_nested_setting( string $dot_keys, $default_value = null ) {
		return $this->search_in_meta( 'wp_travel_engine_setting.' . $dot_keys, $default_value );
	}

	/**
	 * This method updates the current `wp_travel_engine_setting` meta-value
	 * with a provided nested key, and it's value.
	 *
	 * @param string $dot_keys keys to be set in `wp_travel_engine_setting`.
	 * @param mixed  $value The value to be stored.
	 *
	 * @return void
	 */
	public function set_setting( string $dot_keys, $value ) {
		$this->set_nested_setting( 'wp_travel_engine_setting.' . $dot_keys, $value );
	}

	/**
	 * Handles the meta-values to be stored in nested key.
	 * If the meta-value of the first key from $dot_keys is already defined,
	 * this function copies this value.
	 * Otherwise, this function first fetches the meta-value.
	 *
	 * @param string $dot_keys The nested keys. Designed to be dot-separated.
	 * @param mixed  $value The value to be stored.
	 *
	 * @return void
	 */
	public function set_nested_setting( string $dot_keys, $value ) {
		$keys = explode( '.', $dot_keys );

		$first_key = array_shift( $keys );

		$this->data['__changes'][ $first_key ] ??= $this->get_meta( $first_key ) ?: array();
		$settings                                = &$this->data['__changes'][ $first_key ];

		foreach ( $keys as $key ) {
			if ( ! isset( $settings[ $key ] ) ) {
				$settings[ $key ] = array();
			}
			$settings = &$settings[ $key ];
		}
		$settings = $value;
	}


	/**
	 * Get the services.
	 *
	 * @return array
	 */
	public function get_services(): array {

		if ( ! post_type_exists( 'wte-services' ) ) {
			return array();
		}

		$service_ids = explode( ',', $this->get_setting( 'wte_services_ids' ) ?? '' );

		$services = get_posts(
			array(
				'post_type'      => 'wte-services',
				'post_status'    => 'publish',
				'post__in'       => $service_ids,
				'posts_per_page' => - 1,
				'orderby'        => 'post__in',
			)
		);

		$data = array();

		$unit_labels = apply_filters(
			'wptravelengine-extra-services-per-unit-labels',
			array(
				'unit'     => __( 'Unit', 'wp-travel-engine' ),
				'traveler' => __( 'Traveler', 'wp-travel-engine' ),
			)
		);

		$trip_services = $this->get_setting( 'trip_extra_services' );

		foreach ( $services as $service ) {
			$service_options = array();
			$service_data    = get_post_meta( $service->ID, 'wte_services', true ) ?: array();
			$trip_service    = current( array_filter( $trip_services ?? array(), fn( $trip_service ) => $trip_service['id'] == $service->ID ) );

			$is_default_service  = ( $service_data['service_type'] ?? 'default' ) === 'default';
			$service_unit        = $service_data['service_unit'] ?? 'unit';
			$default_description = $service_data['default_descriptions'] ?? apply_filters( 'the_content', get_the_content( '', false, $service->ID ) );

			if ( $is_default_service ) {
				$price = ! empty( $trip_service ) ? floatval( $trip_service['prices'][0] ?? 0 ) : floatval( is_numeric( $service_data['service_cost'] ) ? $service_data['service_cost'] : 0 );

				$service_options[] = array(
					'key'         => wptravelengine_generate_key( $service->ID ?? 0 ),
					'label'       => $service->post_title,
					'price'       => $price,
					'description' => ! empty( $trip_service )
										? $trip_service['descriptions'][0]
										: $default_description,
					'serviceUnit' => array(
						'value' => $service_unit,
						'label' => $unit_labels[ $service_unit ],
					),
					'attributes'  => array(),
				);
			} else {
				$options = ! empty( $trip_service ) ? $trip_service['options'] : ( $service_data['options'] ?? array() );
				foreach ( $options as $index => $option ) {
					$price        = ! empty( $trip_service ) ? floatval( $trip_service['prices'][ $index ] ?? 0 ) : floatval( $service_data['prices'][ $index ] ?? 0 );
					$option_label = ! empty( $trip_service ) ? $option : ( $service_data['options'][ $index ] ?? $option );

					$service_options[] = array(
						'key'         => wptravelengine_generate_key( "$service->ID-$index" ),
						'label'       => $option_label,
						'price'       => $price,
						'serviceUnit' => array(
							'value' => 'unit',
							'label' => isset( $service_data['unit'][ $index ] ) && ! empty( $service_data['unit'][ $index ] ) ? $service_data['unit'][ $index ] : $unit_labels['unit'],
						),
						'description' => ! empty( $trip_service )
											? $trip_service['descriptions'][ $index ]
											: ( $service_data['descriptions'][ $index ] ?? '' ),
						'attributes'  => $service_data['attributes'][ $index ] ?? array(),
					);
				}
			}

			$data[] = array(
				'id'       => $service->ID,
				'title'    => $service->post_title,
				'required' => ! empty( $service_data['service_required'] ),
				'multiple' => $service_data['field_type'] === 'checkbox' && ! $is_default_service,
				'options'  => $service_options,
			);
		}

		return $data;
	}

	/**
	 * Get the trip price.
	 *
	 * @return float
	 */
	public function get_price(): float {
		return $this->primary_package->price ?? 0;
	}

	/**
	 * Get the trip sale price.
	 *
	 * @return float
	 */
	public function get_sale_price(): float {
		return $this->primary_package->sale_price ?? 0;
	}

	/**
	 * Check if the trip has a sale price.
	 *
	 * @return bool
	 */
	public function has_sale(): bool {
		return $this->primary_package->has_sale ?? false;
	}

	/**
	 * Checks if any package has group discount.
	 *
	 * @return bool
	 * @since 6.5.5
	 */
	public function has_group_discount(): bool {
		foreach ( $this->packages() as $package ) {
			if ( $package->has_group_discount() ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Get the default package.
	 *
	 * @return ?TripPackage
	 */
	public function default_package(): ?TripPackage {

		if ( ! $this->has_package() ) {
			return null;
		}

		if ( count( $this->trip_packages ) === 1 ) {
			return $this->trip_packages->current();
		}

		$primary_package_id = $this->get_meta( 'primary_package' );

		if ( is_numeric( $primary_package_id ) ) {
			$primary_package = $this->trip_packages->get_package( $primary_package_id );
			if ( $primary_package ) {
				return $primary_package;
			}
		}

		return $this->trip_packages->get_package_with_low_price();
	}

	/**
	 * Get the trip packages.
	 *
	 * @return TripPackages
	 */
	public function packages(): TripPackages {
		return $this->trip_packages;
	}

	/**
	 * Check if the trip has a package.
	 *
	 * @return bool
	 */
	public function has_package(): bool {
		return count( $this->trip_packages ) > 0;
	}

	/**
	 *
	 * Check if a property is set.
	 *
	 * @param string $key The key for the value you want to retrieve.
	 *
	 * @return bool
	 */
	public function __isset( string $key ) {
		return isset( $this->data[ $key ] );
	}

	/**
	 *
	 * Get the value of a protected property.
	 *
	 * @param string $key Property name.
	 *
	 * @return mixed
	 */
	public function __get( string $key ) {

		if ( $this->__isset( $key ) ) {
			return $this->data[ $key ];
		}

		switch ( $key ) {
			case 'settings':
				return $this->get_meta( 'wp_travel_engine_setting' );
			case 'trip_version':
				return $this->version();
			case 'post':
				return $this->post;
			case 'use_legacy_trip':
				return defined( 'USE_WTE_LEGACY_VERSION' ) && USE_WTE_LEGACY_VERSION;
			case 'has_package':
				return $this->has_package();
			case 'packages':
				return $this->trip_packages;
			case 'default_package':
				$this->data['default_package'] = $this->default_package();

				return $this->data['default_package'];
			default:
				return $this->post->{$key};
		}
	}

	/**
	 * Get the trip code.
	 *
	 * @return string
	 */
	public function get_trip_code(): string {

		$trip_code = $this->get_setting( 'trip_code', 'WTE-' . $this->post->ID );

		return apply_filters( 'wptravelengine_get_trip_code', $trip_code, $this->post->ID );
	}

	/**
	 * Get the trip duration.
	 *
	 * @return string
	 */
	public function get_trip_duration(): string {
		return $this->get_setting( 'trip_duration' ) ?? '';
	}


	/**
	 * Get the trip duration unit.
	 *
	 * @return string
	 */
	public function get_trip_duration_unit(): string {
		return $this->get_setting( 'trip_duration_unit' ) ?? 'days';
	}

	/**
	 * Get the trip nights.
	 *
	 * @return string
	 */
	public function get_trip_nights(): string {
		$nights = $this->get_setting( 'trip_duration_nights' );

		/* translators: %s: number of nights */

		return sprintf( _nx( '%s Night', '%s Nights', $nights, 'trip duration nights', 'wp-travel-engine' ), $nights );
	}

	/**
	 * Check if the trip cutoff time is enabled.
	 *
	 * @return boolean
	 */
	public function is_enabled_cutoff_time(): bool {
		return $this->get_setting( 'trip_cutoff_enable' ) ?? false;
	}

	/**
	 * Get the trip cutoff time.
	 *
	 * @return string
	 */
	public function get_trip_cutoff_time(): string {
		return $this->get_setting( 'trip_cut_off_time' ) ?? '';
	}

	/**
	 * Get the trip cutoff unit.
	 *
	 * @return string
	 */
	public function get_trip_cutoff_unit(): string {
		return $this->get_setting( 'trip_cut_off_unit' ) ?? '';
	}

	/**
	 * Check if the trip minimum/maximum age is enabled.
	 *
	 * @return boolean
	 */
	public function is_enabled_min_max_age(): bool {
		return $this->get_setting( 'min_max_age_enable' ) ?? false;
	}

	/**
	 * Get the trip minimum age.
	 *
	 * @return string
	 */
	public function get_minimum_age(): string {
		return $this->get_meta( 'wp_travel_engine_trip_min_age' ) ?? '';
	}

	/**
	 * Get the trip maximum age.
	 *
	 * @return string
	 */
	public function get_maximum_age(): string {
		return $this->get_meta( 'wp_travel_engine_trip_max_age' ) ?? '';
	}

	/**
	 * Check if the trip minimum/maximum participants are enabled.
	 *
	 * @return boolean
	 * @updated 6.6.7
	 */
	public function is_enabled_min_max_participants(): bool {
		return true;
		// return wptravelengine_toggled( $this->get_setting( 'minmax_pax_enable' ) ?? false );
	}

	/**
	 * Get the trip minimum participants.
	 *
	 * @return string
	 */
	public function get_minimum_participants(): string {
		return $this->get_setting( 'trip_minimum_pax' ) ?? '';
	}

	/**
	 * Get the trip maximum participants.
	 *
	 * @return int|string
	 * @updated 6.6.7
	 */
	public function get_maximum_participants() {

		$value = get_metadata_raw(
			'post',
			$this->post->ID,
			'total_travellers_per_day',
			true
		);

		if ( is_null( $value ) ) {
			$value = $this->get_meta( 'max_travellers_per_day' );
			$this->update_meta( 'total_travellers_per_day', $value );
		}

		return wptravelengine_normalize_numeric_val( $value );
		// return $this->get_setting( 'trip_maximum_pax' ) ?? '';
	}

	/**
	 * Get group size.
	 *
	 * @return string
	 */
	public function get_group_size(): string {
		$group_size = array();
		if ( ! empty( $this->get_minimum_participants() ) ) {
			$group_size[] = (int) $this->get_minimum_participants();
		}
		if ( ! empty( $this->get_maximum_participants() ) ) {
			$group_size[] = $this->get_maximum_participants();
		}

		return ! empty( $group_size ) ? implode( ' - ', $group_size ) : '';
	}

	/**
	 * Get Trip Terms
	 *
	 * @param string $taxonomy The taxonomy to retrieve terms from.
	 *
	 * @return string A string of term links.
	 */
	public function get_trip_terms( string $taxonomy ): string {
		$terms = get_the_terms( $this->post->ID, $taxonomy );
		$value = '';
		if ( is_array( $terms ) ) {
			foreach ( $terms as $term ) {
				$value .= sprintf( '<a href="%s">%s</a>', get_term_link( $term, $taxonomy ), $term->name );
			}
		}

		return $value;
	}

	/**
	 * Get the tab sections title based on tab name.
	 *
	 * @param string $key The tab name to get the section title for.
	 *
	 * @return string
	 */
	public function get_tab_section_title( string $key ): string {
		$mapping = array(
			'trip_facts'      => 'trip_facts_title',
			'trip_overview'   => 'overview_section_title',
			'trip_highlights' => 'trip_highlights_title',
			'trip_itinerary'  => 'trip_itinerary_title',
			'trip_cost'       => 'cost_tab_sec_title',
			'trip_map'        => 'map_section_title',
			'trip_faq'        => 'faq_section_title',
		);

		return $this->get_setting( $mapping[ $key ] ?? "{$key}_title" ) ?? '';
	}

	/**
	 * Get wp-editor content.
	 *
	 * @param int $id The id of the wp-editor.
	 *
	 * @return mixed
	 */
	public function get_editor_content( int $id ) {
		$tab_content = $this->get_setting( 'tab_content' );
		if ( isset( $tab_content ) ) {
			$tab_content = $tab_content[ $id . '_wpeditor' ];
		}

		return $tab_content;
	}

	/**
	 * Get the trip overview content.
	 *
	 * @return string
	 */
	public function get_overview_content(): string {
		$overview_content = $this->get_setting( 'tab_content' );
		if ( isset( $overview_content ) ) {
			$overview_content = $overview_content['1_wpeditor'];
		}

		return $overview_content;
	}

	/**
	 * Get the trip highlights content.
	 *
	 * @return string
	 */
	public function get_highlights_content(): string {
		return $this->get_setting( 'trip_highlights' ) ?? '';
	}

	/**
	 * Get the trip itinerary chart labels.
	 *
	 * @return array
	 * @since 6.4.1
	 */
	public function get_itinerary_chart_labels(): array {

		$unit   = wptravelengine_get_label_by_slug( $this->get_trip_duration_unit() );
		$labels = array_values( $this->get_setting( 'itinerary.itinerary_days_label', array() ) );

		return array_map(
			function ( $label, $key ) use ( $unit ) {
				return $label ?: ( 'days' === $this->get_trip_duration_unit() ? $unit . ' ' . ( $key + 1 ) : '' );
			},
			$labels,
			array_keys( $labels )
		);
	}

	/**
	 * Get the trip itinerary data.
	 *
	 * @return array
	 */
	public function get_itinerary_data(): array {
		$itinerary_data = $this->get_setting( 'itinerary' );

		if ( ! is_array( $itinerary_data ) || ! isset( $itinerary_data['itinerary_title'] ) ) {
			return array();
		}

		$itineraries = array();
		foreach ( $itinerary_data['itinerary_title'] as $itinerary => $title ) {
			$itineraries[] = array(
				'title'   => $title,
				'content' => $itinerary_data['itinerary_content'][ $itinerary ],
			);
		}

		return $itineraries;
	}

	/**
	 * Get the trip cost includes title.
	 *
	 * @return string
	 */
	public function get_cost_includes_title(): string {
		$cost_includes = $this->get_setting( 'cost' );

		return $cost_includes['includes_title'];
	}

	/**
	 * Get the trip cost includes content.
	 *
	 * @return array
	 */
	public function get_cost_includes_content(): array {
		$cost_includes = $this->get_setting( 'cost' );

		return preg_split( '/\r\n|[\r\n]/', $cost_includes['cost_includes'] );
	}

	/**
	 * Get the trip cost includes data.
	 *
	 * @return array
	 */
	public function get_cost_includes_data(): array {
		$title   = $this->get_cost_includes_title();
		$content = $this->get_cost_includes_content();

		return array(
			'title'   => $title,
			'content' => $content,
		);
	}

	/**
	 * Get the trip cost excludes title.
	 *
	 * @return string
	 */
	public function get_cost_excludes_title() {
		$cost_excludes       = $this->get_setting( 'cost' );
		$cost_excludes_title = $cost_excludes['excludes_title'];

		return $cost_excludes_title;
	}

	/**
	 * Get the trip cost excludes content.
	 *
	 * @return array
	 */
	public function get_cost_excludes_content() {
		$cost_excludes         = $this->get_setting( 'cost' );
		$cost_excludes_content = preg_split( '/\r\n|[\r\n]/', $cost_excludes['cost_excludes'] );

		return $cost_excludes_content;
	}

	/**
	 * Get the trip cost excludes data.
	 *
	 * @return array
	 */
	public function get_cost_excludes_data() {
		$title   = $this->get_cost_excludes_title();
		$content = $this->get_cost_excludes_content();

		return array(
			'title'   => $title,
			'content' => $content,
		);
	}

	/**
	 * Get the trip facts.
	 *
	 * @return array
	 */
	public function get_trip_facts_data() {
		$facts_data = $this->get_setting( 'trip_facts' );

		if ( ! is_array( $facts_data ) || ! isset( $facts_data['field_id'] ) ) {
			return array();
		}

		$facts        = array();
		$global_facts = $this->get_global_trip_facts();
		foreach ( $facts_data['field_id'] as $fact => $value ) {
			if ( isset( $global_facts['fid'][ $fact ] ) ) {
				$field_content = $facts_data[ $fact ][ $fact ];
				$field_type    = $facts_data['field_type'][ $fact ];
				if ( 'textarea' === $field_type ) {
					$field_content = nl2br( $facts_data[ $fact ][ $fact ] );
				}
				if ( 'duration' === $field_type && ! preg_match( '/([^\d]+)/', trim( $field_content ) ) ) {
					$duration_type = 'days';
					// if ( isset( $trip_settings[ 'trip_duration_unit' ] ) && in_array( $trip_settings[ 'trip_duration_unit' ], array(
					// 'days',
					// 'hours',
					// ), true ) ) {
					// $duration_type = $trip_settings[ 'trip_duration_unit' ];
					// }

					if ( 'hours' === $duration_type ) {
						/* translators: %d: number of hours */
						$field_content = sprintf(
							_n( '%d Hour', '%d Hours', (int) $facts_data[ $fact ][ $fact ], 'wp-travel-engine' ),
							(int) $field_content
						);
					} else {
						/* translators: %d: number of days */
						$field_content = sprintf(
							_n( '%d Day', '%d Days', (int) $facts_data[ $fact ][ $fact ], 'wp-travel-engine' ),
							(int) $field_content
						);
					}
				}
				$facts[ $fact ] = array(
					'field_title'   => $value,
					'field_type'    => $facts_data['field_type'][ $fact ],
					'field_content' => $field_content,
					'field_icon'    => $global_facts['field_icon'][ $fact ],
				);
			}
		}

		return $facts;
	}

	/**
	 * Get the trip facts.
	 *
	 * @return array
	 */
	public function get_default_trip_facts() {
		$minimum_age           = $this->is_enabled_min_max_age() ? $this->get_minimum_age() : '';
		$maximum_age           = $this->is_enabled_min_max_age() ? $this->get_maximum_age() : '';
		$group_size            = $this->get_group_size();
		$trip_facts_value      = array(
			'minimum-age' => array(
				'value' => $minimum_age,
			),
			'maximum-age' => array(
				'value' => $maximum_age,
			),
			'group-size'  => array(
				'value' => $group_size,
			),
		);
		$facts                 = array();
		$additional_trip_facts = wptravelengine_get_trip_facts_default_options();
		foreach ( $additional_trip_facts as $key => $value ) {
			if ( ! isset( $value['enabled'] ) || 'no' === $value['enabled'] ) {
				continue;
			}
			$fact_class = '';

			$facts_value = $trip_facts_value[ $value['field_type'] ]['value'] ?? '';

			$position = strpos( $value['field_type'], 'taxonomy:' );

			if ( isset( $value['field_type'] ) && 0 === $position ) {
				list( $label, $taxonomy ) = explode( ':', $value['field_type'] );
				$facts_value              = $this->get_trip_terms( $taxonomy );
				$fact_class               = 'trip-facts-taxonomy';
			}
			if ( '' !== $facts_value ) {
				$facts[ $key ] = array(
					'field_title'   => $value['field_id'],
					'field_type'    => $value['field_type'],
					'field_content' => $facts_value,
					'field_icon'    => $value['field_icon'],
					'field_class'   => $fact_class,
				);
			}
		}

		return $facts;
	}

	/**
	 * Get the trip facts.
	 *
	 * @return array
	 */
	public function get_trip_facts() {
		$trip_facts         = $this->get_trip_facts_data();
		$default_trip_facts = $this->get_default_trip_facts();
		$trip_facts         = array_merge( $default_trip_facts, $trip_facts );

		return $trip_facts;
	}

	/**
	 * Get the global trip facts.
	 *
	 * @return array
	 */
	public function get_global_trip_facts() {
		return wptravelengine_get_trip_facts_options() ?? array();
	}

	/**
	 * Get the trip difficulty term data.
	 *
	 * @return array
	 */
	public function get_difficulty_term() {
		$difficulty_level = $this->get_setting( 'difficulty_level' );
		$difficulty_term  = get_term( (int) $difficulty_level, 'difficulty' );

		return $difficulty_term;
	}

	/**
	 * Check if image gallery is enabled.
	 *
	 * @return boolean
	 */
	public function is_enabled_image_gallery() {
		$gallery_setting = $this->get_meta( 'wpte_gallery_id' );

		return $gallery_setting['enable'] ?? false;
	}

	/**
	 * Get the trip gallery images.
	 *
	 * @return array
	 */
	public function get_gallery_images(): array {
		$gallery_data                   = $this->get_meta( 'wpte_gallery_id' );
		$option                         = new PluginSettings();
		$show_featured_image_in_gallery = $option->get( 'show_featured_image_in_gallery', 'yes' );

		if ( isset( $gallery_data['enable'] ) ) {
			unset( $gallery_data['enable'] );
		}

		if ( $show_featured_image_in_gallery && has_post_thumbnail( $this->post->ID ) ) {
			$thumbnail_id = get_post_thumbnail_id( $this->post->ID );
			// Remove featured image from gallery if it exists at any position
			$gallery_data = array_diff( $gallery_data, array( $thumbnail_id ) );
			// Add featured image to the beginning
			array_unshift( $gallery_data, $thumbnail_id );
		}
		$gallery_image_size = apply_filters( 'wp_travel_engine_trip_single_gallery_image_size', 'large' );
		$gallery_images     = array();

		if ( $this->is_enabled_image_gallery() ) {
			foreach ( $gallery_data as $image ) {
				$link      = wp_get_attachment_image_src( $image, $gallery_image_size );
				$image_alt = get_post_meta( $image, '_wp_attachment_image_alt', true );

				if ( empty( $link ) ) {
					continue;
				}

				if ( empty( $image_alt ) ) {
					$image_alt = get_the_title( $image );
				}

				$gallery_images[] = array(
					'src' => $link[0],
					'alt' => $image_alt,
				);
			}
		}

		return $gallery_images;
	}

	/**
	 * Get the trip featured image.
	 *
	 * @return array
	 */
	public function get_featured_image(): array {
		$featured_image_url = WP_TRAVEL_ENGINE_IMG_URL . '/public/css/images/single-trip-featured-img.jpg';
		$image_alt          = get_the_title();
		if ( has_post_thumbnail( $this->post->ID ) ) {
			$trip_feat_img_size         = apply_filters( 'wp_travel_engine_single_trip_feat_img_size', 'trip-single-size' );
			list( $featured_image_url ) = wp_get_attachment_image_src( get_post_thumbnail_id( $this->post->ID ), $trip_feat_img_size );
			$image_alt                  = get_post_meta( get_post_thumbnail_id( $this->post->ID ), '_wp_attachment_image_alt', true );
			if ( empty( $image_alt ) ) {
				$image_alt = get_the_title( get_post_thumbnail_id( $this->post->ID ) );
			}
		}

		return array(
			'src' => $featured_image_url,
			'alt' => $image_alt,
		);
	}

	/**
	 * Check if a video gallery is enabled.
	 *
	 * @return boolean
	 */
	public function is_enabled_video_gallery(): bool {
		return (bool) ( $this->get_setting( 'enable_video_gallery' ) ?? false );
	}

	/**
	 * Get the trip video gallery.
	 *
	 * @return array
	 */
	public function get_video_gallery(): array {
		$video_gallery = $this->get_meta( 'wpte_vid_gallery' );

		if ( ! is_array( $video_gallery ) ) {
			return array();
		}

		$videos = array();
		foreach ( $video_gallery as $video ) {
			$videos[] = array(
				'type'       => $video['type'],
				'id'         => $video['id'],
				'thumb'      => $video['thumb'],
				'url'        => 'youtube' === $video['type'] ? '//www.youtube.com/watch?v=' . $video['id'] : '//vimeo.com/' . $video['id'],
				'slider_url' => 'youtube' === $video['type'] ? 'https://www.youtube.com/embed/' . $video['id'] . '?enablejsapi=1&controls=0&fs=0&iv_load_policy=3&rel=0&showinfo=0&loop=1' : 'https://player.vimeo.com/video/' . $video['id'] . '?api=1&byline=0&portrait=0&title=0&background=1&mute=1&loop=1&autoplay=0&id=' . $video['id'],
			);
		}

		return $videos;
	}

	/**
	 * Get map image url.
	 *
	 * @return string
	 */
	public function get_map_img_url() {
		return $this->get_setting( 'map' )['image_url'] ?? '';
	}

	/**
	 * Get map iframe code.
	 *
	 * @return string
	 */
	public function get_map_iframe_code() {
		return $this->get_setting( 'map' )['iframe'] ?? '';
	}

	/**
	 * Get the trip faq data.
	 *
	 * @return array
	 */
	public function get_faq_data() {
		$faq_data = $this->get_setting( 'faq' );

		if ( ! is_array( $faq_data ) || ! isset( $faq_data['faq_title'] ) ) {
			return array();
		}

		$faqs = array();
		foreach ( $faq_data['faq_title'] as $faq => $title ) {
			$faqs[] = array(
				'question' => $title,
				'answer'   => $faq_data['faq_content'][ $faq ],
			);
		}

		return $faqs;
	}

	/**
	 * Get the trip difficulty level.
	 *
	 * @return array Array of trip difficulty level.
	 */
	public function get_trip_difficulty_term() {
		$termss                = get_the_terms( $this->post->ID, 'difficulty' );
		$trip_difficulty_level = array();
		if ( ! empty( $termss ) ) {
			$key = 0;
			foreach ( $termss as $term ) {
				if ( isset( $term->term_id ) ) {
					$term_id           = $term->term_id;
					$difficulty_level  = Options::get( 'difficulty_level_by_terms', array() );
					$terms             = get_terms(
						array(
							'taxonomy'   => 'difficulty',
							'hide_empty' => false,
						)
					);
					$difficulty_levels = '';
					foreach ( $difficulty_level as $level ) {
						if ( $term_id == $level['term_id'] ) :
							$difficulty_levels = sprintf( __( '<span>(%1$s/%2$d)</span>', 'wp-travel-engine' ), $level['level'], count( $terms ) );
						endif;
					}
					$term_thumbnail = (int) get_term_meta( $term_id, 'category-image-id', true );

					$trip_difficulty_level[ $key ] = array(
						'term_id'        => $term_id,
						'term_thumbnail' => $term_thumbnail,
					);
				}
				?>
				<?php
				$difficulty_term_description = term_description( $term->term_id, 'difficulty' );
				$difficulty_attribute        = $difficulty_term_description ? 'data-content="' . $difficulty_term_description . '"' : '';
				$difficulty_link             = get_term_link( $term );
				$difficulty_name             = $term->name;
				$difficulty_span_class       = $difficulty_attribute != '' ? 'wte-difficulty-content tippy-exist' : 'wte-difficulty-content';
				$difficulty_data_content     = $difficulty_attribute != '' ? $difficulty_attribute : '';

				$trip_difficulty_level[ $key ] += array(
					'difficulty_levels'       => $difficulty_levels,
					'difficulty_link'         => $difficulty_link,
					'difficulty_name'         => $difficulty_name,
					'difficulty_span_class'   => $difficulty_span_class,
					'difficulty_data_content' => $difficulty_data_content,
				);
				++$key;
			}
		}

		return $trip_difficulty_level;
	}

	/**
	 * Checks if a trip is partially payable.
	 *
	 * @return bool
	 */
	public function is_partially_payable(): bool {
		$enabled          = wptravelengine_toggled( $this->get_setting( 'partial_payment_enable', 'no' ) );
		$enabled_globally = PluginSettings::make()->is( 'partial_payment_enable', 'yes' );

		return class_exists( 'Wte_Partial_Payment_Admin' ) && $enabled_globally && $enabled;
	}

	/**
	 * Trip Card Fixed Departure Dates Content.
	 *
	 * @param array  $fsds Fixed Departure Dates.
	 * @param string $dates_layout Dates layout either months or days.
	 * @param bool   $related_trip Related trip or not for recommendation.
	 *
	 * @return
	 */
	public function fsds_content( $fsds, $dates_layout, $related_trip = false ) {
		$return = array();
		if ( 'months' === $dates_layout ) {
			$available_months         = array_map(
				function ( $fsd ) {
					return date_i18n( 'n', strtotime( $fsd['start_date'] ) );
				},
				$fsds
			);
			$available_dates_in_month = array_count_values( $available_months );
			$available_months         = array_flip( $available_months );

			$availability_txt     = ! empty( $available_months ) && is_array( $available_months ) ? __( 'Available in the following months:', 'wp-travel-engine' ) : __( 'Available through out the year:', 'wp-travel-engine' );
			$available_throughout = apply_filters( 'wte_available_throughout_txt', $availability_txt );

			foreach ( range( 1, 12 ) as $month_number ) {
				if ( isset( $available_months[ $month_number ] ) && ( $related_trip ) ) {
					$month_value = date_i18n( 'm', strtotime( "2021-{$month_number}-01" ) );
					foreach ( $available_dates_in_month as $months => $dates_available ) {
						if ( $month_value == $months ) {
							$dates_attribute = $dates_available ? 'data-content="' . $dates_available . ' ' . _n( 'date', 'dates', $dates_available, 'wp-travel-engine' ) . ' available"' : '';
						}
					}
					$classname = 'wte-dates-available' . ( '' != ( $dates_attribute ?? '' ) ) ? ' tippy-exist' : '';
				}
			}

			$return = array(
				'available_months'     => $available_months,
				'available_throughout' => $available_throughout,
				'dates_attribute'      => $dates_attribute ?? '',
				'classname'            => $classname ?? '',
			);
		} elseif ( 'dates' === $dates_layout ) {
			$return = array(
				'list_count' => (int) PluginSettings::make()->get( 'trip_dates.number', 3 ),
				'icon'       => '<i><svg xmlns="http://www.w3.org/2000/svg" width="17.332" height="15.61" viewBox="0 0 17.332 15.61"><g transform="translate(283.072 34.13)"><path  d="M-283.057-26.176h.1c.466,0,.931,0,1.4,0,.084,0,.108-.024.1-.106-.006-.156,0-.313,0-.469a5.348,5.348,0,0,1,.066-.675,5.726,5.726,0,0,1,.162-.812,5.1,5.1,0,0,1,.17-.57,9.17,9.17,0,0,1,.383-.946,10.522,10.522,0,0,1,.573-.96c.109-.169.267-.307.371-.479a3.517,3.517,0,0,1,.5-.564,6.869,6.869,0,0,1,1.136-.97,9.538,9.538,0,0,1,.933-.557,7.427,7.427,0,0,1,1.631-.608c.284-.074.577-.11.867-.162a7.583,7.583,0,0,1,1.49-.072c.178,0,.356.053.534.062a2.673,2.673,0,0,1,.523.083c.147.038.3.056.445.1.255.07.511.138.759.228a6.434,6.434,0,0,1,1.22.569c.288.179.571.366.851.556a2.341,2.341,0,0,1,.319.259c.3.291.589.592.888.882a4.993,4.993,0,0,1,.64.85,6.611,6.611,0,0,1,.71,1.367c.065.175.121.352.178.53s.118.348.158.526c.054.242.09.487.133.731.024.14.045.281.067.422a.69.69,0,0,1,.008.1c0,.244.005.488,0,.731s-.015.5-.04.745a4.775,4.775,0,0,1-.095.5c-.04.191-.072.385-.128.572-.094.312-.191.625-.313.926a7.445,7.445,0,0,1-.43.9c-.173.3-.38.584-.579.87a8.045,8.045,0,0,1-1.2,1.26,5.842,5.842,0,0,1-.975.687,8.607,8.607,0,0,1-1.083.552,11.214,11.214,0,0,1-1.087.36c-.19.058-.386.1-.58.137-.121.025-.245.037-.368.052a12.316,12.316,0,0,1-1.57.034,3.994,3.994,0,0,1-.553-.065c-.166-.024-.33-.053-.5-.082a1.745,1.745,0,0,1-.21-.043c-.339-.1-.684-.189-1.013-.317a7,7,0,0,1-1.335-.673c-.2-.136-.417-.263-.609-.415a6.9,6.9,0,0,1-.566-.517.488.488,0,0,1-.128-.331.935.935,0,0,1,.1-.457.465.465,0,0,1,.3-.223.987.987,0,0,1,.478-.059.318.318,0,0,1,.139.073c.239.185.469.381.713.559a5.9,5.9,0,0,0,1.444.766,5.073,5.073,0,0,0,.484.169c.24.062.485.1.727.154a1.805,1.805,0,0,0,.2.037c.173.015.346.033.52.036.3.006.6.01.9,0a3.421,3.421,0,0,0,.562-.068c.337-.069.676-.139,1-.239a6.571,6.571,0,0,0,.783-.32,5.854,5.854,0,0,0,1.08-.663,5.389,5.389,0,0,0,.588-.533,8.013,8.013,0,0,0,.675-.738,5.518,5.518,0,0,0,.749-1.274,9.733,9.733,0,0,0,.366-1.107,4.926,4.926,0,0,0,.142-.833c.025-.269.008-.542.014-.814a4.716,4.716,0,0,0-.07-.815,5.8,5.8,0,0,0-.281-1.12,5.311,5.311,0,0,0-.548-1.147,9.019,9.019,0,0,0-.645-.914,9.267,9.267,0,0,0-.824-.788,3.354,3.354,0,0,0-.425-.321,5.664,5.664,0,0,0-1.048-.581c-.244-.093-.484-.2-.732-.275a6.877,6.877,0,0,0-.688-.161c-.212-.043-.427-.074-.641-.109a.528.528,0,0,0-.084,0c-.169,0-.338,0-.506,0a5.882,5.882,0,0,0-1.177.1,6.79,6.79,0,0,0-1.016.274,6.575,6.575,0,0,0-1.627.856,6.252,6.252,0,0,0-1.032.948,6.855,6.855,0,0,0-.644.847,4.657,4.657,0,0,0-.519,1.017c-.112.323-.227.647-.307.979a3.45,3.45,0,0,0-.13.91,4.4,4.4,0,0,1-.036.529c-.008.086.026.1.106.1.463,0,.925,0,1.388,0a.122.122,0,0,1,.08.028c.009.009-.005.051-.019.072q-.28.415-.563.827c-.162.236-.33.468-.489.705-.118.175-.222.359-.339.535-.1.144-.2.281-.3.423-.142.2-.282.41-.423.615-.016.023-.031.047-.048.069-.062.084-.086.083-.142,0-.166-.249-.332-.5-.5-.746-.3-.44-.6-.878-.9-1.318q-.358-.525-.714-1.051c-.031-.045-.063-.09-.094-.134Z" transform="translate(0 0)"/><path id="Path_23384" data-name="Path 23384" d="M150.612,112.52c0,.655,0,1.31,0,1.966a.216.216,0,0,0,.087.178,4.484,4.484,0,0,1,.358.346.227.227,0,0,0,.186.087q1.616,0,3.233,0a.659.659,0,0,1,.622.4.743.743,0,0,1-.516,1.074,1.361,1.361,0,0,1-.323.038q-1.507,0-3.013,0a.248.248,0,0,0-.216.109,1.509,1.509,0,0,1-.765.511,1.444,1.444,0,0,1-1.256-2.555.218.218,0,0,0,.09-.207q0-1.916,0-3.831a.784.784,0,0,1,.741-.732.742.742,0,0,1,.761.544.489.489,0,0,1,.015.127Q150.612,111.547,150.612,112.52Z" transform="translate(-423.686 -141.471)"/></g></svg></i>',
			);

		}

		return $return;
	}

	/**
	 * Get the trip Inventory.
	 *
	 * @return Inventory
	 */
	public function get_inventory(): Inventory {
		if ( is_null( $this->inventory ) ) {
			$this->inventory = new Inventory( $this->ID );
		}

		return $this->inventory;
	}

	/**
	 * Retrieves metadata from a nested structure.
	 * If meta_key exists, it fetches and returns its value;
	 * otherwise, sets the default value if not null.
	 *
	 * @param string $dot_keys The nested keys. Designed to be dot-separated.
	 * @param mixed  $default The default value.
	 *
	 * @return mixed The meta-value or null if not found.
	 */
	public function search_in_meta( string $dot_keys, $default = null ) {
		$key_arr   = explode( '.', $dot_keys );
		$first_key = array_shift( $key_arr );
		$data      = (array) ( $this->data[ $first_key ] ?? $this->get_meta( $first_key ) ?: array() );

		if ( $default !== null ) {
			if ( ( empty( $key_arr ) && empty( $data ) && $default !== '' ) || ( ! empty( $key_arr ) && ! wptravelengine_key_exists( $data, $key_arr ) ) ) {
				$this->set_nested_setting( $dot_keys, $default );
				return $default;
			}
			if ( empty( $key_arr ) ) {
				return $data[0];
			}
		}

		return $this->search( $data, $key_arr ) ?? $default;
	}

	/**
	 * Recursive helper function to retrieve nested values from the settings array.
	 *
	 * @param array $data The current level of data to traverse.
	 * @param array $meta_keyss The remaining keys in the dot-separated path.
	 *
	 * @return mixed The value at the end of the path, or null if not found.
	 */
	protected function search( array $data, array $meta_keyss ) {
		$meta_keys = array_shift( $meta_keyss );
		if ( ! isset( $data[ $meta_keys ] ) ) {
			return null;
		}

		if ( empty( $meta_keyss ) ) {
			return $data[ $meta_keys ];
		} else {
			if ( ! is_array( $data[ $meta_keys ] ) ) {
				return null;
			}

			return $this->search( $data[ $meta_keys ], $meta_keyss );
		}
	}

	/**
	 * Set Primary Package.
	 *
	 * @return $this
	 *
	 * @since 6.1.2
	 */
	public function set_primary_package( ?TripPackage $package = null ): Trip {

		if ( $package ) {
			$this->primary_package = $package;
		} elseif ( $this->has_package() ) {

			$primary_package_id = $this->get_meta( 'primary_package' );

			if ( is_numeric( $primary_package_id ) ) {
				$primary_package = $this->trip_packages->get_package( intval( $primary_package_id ) );
				if ( $primary_package ) {
					$this->primary_package = $primary_package;

					return $this;
				}
			}

			$this->trip_packages->rewind();

			$this->primary_package = $this->trip_packages->current();

		}

		return $this;
	}

	/**
	 * Check if the trip is featured.
	 *
	 * @return boolean
	 * @since 6.6.0
	 */
	public function is_featured(): bool {
		return wptravelengine_toggled( $this->get_meta( 'wp_travel_engine_featured_trip' ) ?? false );
	}

	/**
	 * Get the trip discount percent.
	 *
	 * @return int
	 * @since 6.6.0
	 */
	public function get_discount_percent(): int {
		$trip_price = $this->get_price();
		if ( $this->has_sale() && (float) $trip_price > 0 ) {
			$sale_price = $this->get_sale_price();
			return round( ( ( $trip_price - $sale_price ) * 100 ) / $trip_price );
		}

		return 0;
	}

	/**
	 * Get the trip type
	 *
	 * @return string
	 * @since 6.6.0
	 */
	public function get_trip_type(): string {
		return $this->get_meta( 'trip_type' ) ?: 'multi';
	}

	/**
	 * Get the primary package.
	 *
	 * @return ?TripPackage
	 * @since 6.5.5
	 */
	public function get_primary_package(): ?TripPackage {
		return $this->primary_package;
	}

	/**
	 * Is any date available.
	 *
	 * @return bool
	 * @since 6.2.2
	 * @updated 6.2.3
	 */
	public function has_date(): bool {

		if ( ! $this->has_package() || ! wptravelengine_is_addon_active( 'fixed-starting-dates' ) ) {
			return true;
		}

		foreach ( $this->packages() as $package ) {
			/** @var TripPackage $package */
			if ( empty( $package->get_meta( 'package-dates' ) ) || ! empty( $package->get_package_dates() ) ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Get the trip booked seats of only available packages.
	 *
	 * @return array
	 * @since 6.6.7
	 */
	public function get_my_booked_seats(): array {

		if ( empty( $this->my_booked_seats ) ) {
			$this->my_booked_seats = $this->get_inventory()->inventory_of_( array_flip( $this->get_all_package_ids() ) );
		}

		return $this->my_booked_seats;
	}

	/**
	 * Get all package ids.
	 *
	 * @return array
	 * @since 6.6.7
	 */
	public function get_all_package_ids(): array {
		return array_column( $this->packages()->array(), 'ID' );
	}

	/**
	 * Set the plain start date.
	 *
	 * @return void
	 * @since 6.7.3
	 */
	protected function set_plain_start_date() {
		$start_date = wp_date( 'Y-m-d' );

		if ( $this->is_enabled_cutoff_time() ) {
			$cut_off_period = (int) $this->get_trip_cutoff_time();
			$cut_off_unit   = $this->get_trip_cutoff_unit();
			$start_date     = wp_date( 'Y-m-d', strtotime( "+{$cut_off_period} {$cut_off_unit}" ) );
		}

		$this->plain_start_date = $start_date;
	}
}
