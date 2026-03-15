<?php
/**
 * Trip Package Model.
 *
 * @package WPTravelEngine/Core/Models
 * @since 6.0.0
 */

namespace WPTravelEngine\Core\Models\Post;

use DateInterval;
use DateTime;
use WPTravelEngine\Abstracts\PostModel;
use WPTravelEngine\Helpers\PackageDateParser;
use WPTravelEngine\Core\Models\Settings\Options;

/**
 * Class TripPackage.
 * This class represents a trip package to the WP Travel Engine plugin.
 *
 * @since 6.0.0
 */
class TripPackage extends PostModel {

	/**
	 * Post type name.
	 *
	 * @var string
	 */
	protected string $post_type = 'trip-packages';

	/**
	 * The trip object.
	 *
	 * @var Trip
	 */
	protected Trip $trip;

	/**
	 * if the primary traveler category has sale.
	 *
	 * @var bool
	 */
	public bool $has_sale = false;

	/**
	 * The primary traveler category price.
	 *
	 * @var float
	 */
	public float $price = 0.0;

	/**
	 * The primary traveler category sale price.
	 *
	 * @var float
	 */
	public float $sale_price = 0.0;

	/**
	 * The primary traveler category sale percentage.
	 *
	 * @var float
	 */
	public float $sale_percentage = 0.0;

	/**
	 * If the package has a group discount.
	 *
	 * @var bool
	 */
	public bool $has_group_discount = false;

	/**
	 * Group pricing.
	 *
	 * @var array
	 */
	public array $group_pricing = array();

	/**
	 * The default pricing.
	 *
	 * @var array
	 */
	public array $categories_pricings = array();

	/**
	 * The primary pricing category.
	 *
	 * @var TravelerCategory
	 */
	public TravelerCategory $primary_pricing_category;

	/**
	 * The primary pricing category sale amount.
	 *
	 * @var float
	 * @since 6.6.5
	 */
	public float $sale_amount = 0.0;

	/**
	 *
	 * @param $package
	 * @param Trip $trip
	 */
	public function __construct( $package, Trip $trip ) {
		$this->trip = $trip;
		parent::__construct( $package );

		$this->set_primary_pricing_category_details();
	}

	/**
	 * Gets package's categories data.
	 *
	 * @return TravelerCategories
	 */
	public function get_traveler_categories(): TravelerCategories {
		return new TravelerCategories( $this->trip, $this );
	}

	/**
	 * Package Group Pricing.
	 *
	 * @return array
	 */
	public function get_group_pricing(): array {
		$fields = apply_filters( 'wte_rest_fields__trip-packages', array(), true );

		$callback = $fields['group-pricing']['get_callback'] ?? false;

		if ( $callback ) {
			return $callback( array( 'id' => $this->ID ), 'group-pricing' );
		}

		return array();
	}

	/**
	 * Package Dates.
	 *
	 * @param array $args
	 *
	 * @return array
	 */
	public function get_package_dates( array $args = array() ): array {

		$dates = apply_filters( 'wptravelengine_get_package_dates', false, $this, $args );

		if ( false !== $dates ) {
			return $dates;
		}

		$from = $args['from'] ?? wp_date( 'Y-m-d' );
		$to   = $args['to'] ?? wp_date( 'Y-m-d', strtotime( "{$from} +3 years" ) );

		$cut_off_enabled = wptravelengine_toggled( $this->trip->get_setting( 'trip_cutoff_enable', 'false' ) );
		if ( $cut_off_enabled ) {
			$cut_off_period  = (int) $this->trip->get_setting( 'trip_cut_off_time', 0 );
			$cut_off_unit    = $this->trip->get_setting( 'trip_cut_off_unit', 'days' );
			$valid_date_time = wp_date( 'Y-m-d\TH:i', strtotime( "+$cut_off_period $cut_off_unit" ) );
			$from            = wp_date( 'Y-m-d', strtotime( "+$cut_off_period $cut_off_unit", strtotime( $from ) ) );
		} else {
			$valid_date_time = wp_date( 'Y-m-d\TH:i' );
		}

		$fields = apply_filters( 'wte_rest_fields__trip-packages', array(), true );

		$callback = $fields['package-dates']['get_callback'] ?? false;

		if ( $callback ) {

			$package_dates = $callback( array( 'id' => $this->ID ), 'package-dates' );

			if ( ! is_array( $package_dates ) || empty( $package_dates ) ) {
				$package_dates = array(
					array(
						'dtstart'      => $valid_date_time,
						'is_recurring' => '1',
						'rrule'        => array(
							'r_frequency' => 'DAILY',
							'r_until'     => $to,
						),
						'seats'        => '',
					),
				);
			}

			$dates = array();

			$check_dates = array(
				wp_date( 'Y-m-d' )                        => true,
				wp_date( 'Y-m-d', strtotime( '+1 day' ) ) => true,
				wp_date( 'Y-m-d', strtotime( '+2 day' ) ) => true,
			);

			foreach ( $package_dates as $package_date ) {
				$package_date_parser = new PackageDateParser( $this, $package_date );

				$package_dates = $package_date_parser->get_dates( false, compact( 'from', 'to' ) );
				foreach ( $package_dates as $date => $date_data ) {
					if ( $date < $from ) {
						continue;
					}
					if ( isset( $check_dates[ $date ] ) && $date_data['times'] ) {
						$date_data['times'] = array_filter(
							$date_data['times'],
							function ( $time ) use ( $valid_date_time ) {
								return $time['from'] >= $valid_date_time;
							}
						);
						if ( empty( $date_data['times'] ) ) {
							continue;
						} else {
							$date_data['times'] = array_values( $date_data['times'] );
						}
					}

					if ( ! isset( $dates[ $date ] ) ) {
						$dates[ $date ] = $date_data;
						continue;
					}

					foreach ( $date_data['times'] as $value ) {
						if ( ! in_array( $value['key'], array_column( $dates[ $date ]['times'], 'key' ) ) ) {
							$dates[ $date ]['times'][] = $value;
						}
					}
				}
			}

			return $dates;
		}

		$duration           = (int) $this->trip->get_setting( 'trip_duration', 0 );
		$enabled_time_slots = ( $this->get_meta( 'enable_weekly_time_slots' ) ?? 'no' ) === 'yes';

		$dates           = array();
		$available_seats = $this->trip->get_maximum_participants();
		$available_seats = is_numeric( $available_seats ) ? (int) $available_seats : '';

		/**
		 * @since 6.7.1 Added try-catch block to handle exceptions.
		 */
		try {
			if ( $enabled_time_slots && 'days' !== $this->trip->get_setting( 'trip_duration_unit', 'days' ) ) {
				$week_days_mapping = array_combine( range( 1, 7 ), array( 'MO', 'TU', 'WE', 'TH', 'FR', 'SA', 'SU' ) );
				$weekly_time_slots = $this->get_meta( 'weekly_time_slots' );
				$enable_week_days  = $this->get_meta( 'enable_week_days' );
				$enable_week_days  = empty( $enable_week_days ) ? array_combine( array_values( $week_days_mapping ), array_fill( 0, 7, true ) ) : $enable_week_days;
				$week_days         = array_keys( $weekly_time_slots );

				$package_date_parser = new PackageDateParser(
					$this,
					array(
						'dtstart'      => $valid_date_time,
						'is_recurring' => '1',
						'rrule'        => array(
							'r_frequency' => 'WEEKLY',
							'r_weekdays'  => array_filter(
								array_map(
									function ( $week_day ) use ( $week_days_mapping, $enable_week_days ) {
										if ( $enable_week_days[ $week_days_mapping[ $week_day ] ] ) {
											return $week_days_mapping[ $week_day ];
										}

										return null;
									},
									$week_days
								)
							),
							'r_until'     => wp_date( 'Y-m-d', strtotime( "{$valid_date_time} +3 years" ) ),
						),
						'seats'        => '',
						'version'      => $args['version'] ?? PackageDateParser::$version ?? 'v2',
					)
				);

				$package_dates = $package_date_parser->get_dates();
				/* @var DateTime $package_date */
				foreach ( $package_dates as $package_date ) {
					$date          = $package_date->format( 'Y-m-d' );
					$times         = array();
					$times_by_days = $weekly_time_slots[ $package_date->format( 'w' ) ?: 7 ] ?? array();
					foreach ( $times_by_days as $key => $time ) {
						if ( empty( $time ) ) {
							continue;
						}

						list( $hours, $minutes ) = explode( ':', $time );

						$package_date->setTime( $hours, $minutes );

						$from_time = $package_date->format( 'Y-m-d\TH:i' );

						if ( $from_time < $valid_date_time ) {
							continue;
						}

						$duration = intval( $this->trip->get_setting( 'trip_duration', 0 ) );

						$to = clone $package_date;
						$to->add( new DateInterval( "PT{$duration}H" ) );

						list( $seats, $capacity ) = $package_date_parser->get_seats_details( $date, $time );

						$times[ $key ] = array(
							'key'   => "{$this->ID}_{$package_date->format( 'Y-m-d_H:i' )}_{$to->format('H:i')}",
							'from'  => $from_time,
							'to'    => $to->format( 'Y-m-d\TH:i' ),
							'seats' => $seats,
						);

						if ( 'v3' === $package_date_parser->version ) {
							$times[ $key ]['capacity']   = $capacity;
							$times[ $key ]['seats_left'] = $seats;
						}
					}

					$capacity       = $seats = '';
					$dates[ $date ] = array();
					if ( is_int( $available_seats ) ) {
						$seats = array_sum( array_column( $times, 'seats' ) );
						if ( 'v3' === $package_date_parser->version ) {
							$capacity                   = array_sum( array_column( $times, 'capacity' ) );
							$dates[ $date ]['capacity'] = $capacity;
						}
					}

					$dates[ $date ]['times']   = $times;
					$dates[ $date ]['seats']   = $seats;
					$dates[ $date ]['pricing'] = $this->get_default_pricings();
				}
			} else {
				$package_date_parser = new PackageDateParser(
					$this,
					array(
						'dtstart'      => wp_date( 'Y-m-d' ),
						'is_recurring' => '1',
						'rrule'        => array(
							'r_frequency' => 'DAILY',
							'r_until'     => $to,
						),
						'seats'        => $available_seats,
						'version'      => $args['version'] ?? PackageDateParser::$version ?? 'v2',
					)
				);

				$dates = $package_date_parser->get_dates( false, compact( 'from', 'to' ) );
			}
		} catch ( \Exception $e ) {
			error_log( 'WPTE Error in get_package_dates for trip package ' . $this->ID . ': ' . $e->getMessage() );
			return array( 'error' => $e->getMessage() );
		}

		return $dates;
	}

	/**
	 * Returns the default traveler categories pricing.
	 *
	 * @return array
	 * @since 6.3.1
	 */
	protected function get_default_pricings(): array {
		if ( empty( $this->categories_pricings ) ) {
			$this->categories_pricings    = array();
			$traveler_categories          = $this->get_traveler_categories();
			$primary_traveler_category_id = $traveler_categories->get_primary_traveler_category()->get( 'id' );
			foreach ( $traveler_categories as $traveler_category ) {
				/** @var TravelerCategory $traveler_category */
				$this->categories_pricings[] = array(
					'id'                => $traveler_category->get( 'id' ),
					'label'             => $traveler_category->get( 'label' ),
					'price'             => $traveler_category->get( 'has_sale' ) ? $traveler_category->get( 'sale_price' ) : $traveler_category->get( 'price' ),
					'is_primary'        => $traveler_category->get( 'id' ) === $primary_traveler_category_id,
					'has_group_pricing' => $traveler_category->get( 'enabled_group_discount' ),
					'group_pricing'     => $traveler_category->get( 'group_pricing' ),
				);
			}
		}

		return apply_filters( 'wptravelengine_trip_package_default_pricings', $this->categories_pricings, $this );
	}

	/**
	 * Get the trip object.
	 *
	 * @return Trip
	 */
	public function get_trip(): Trip {
		return $this->trip;
	}

	/**
	 * Get Package meta-value.
	 *
	 * @param $key string The meta-key.
	 *
	 * @return mixed
	 * @since 6.1.0
	 */
	public function __get( string $key ) {
		switch ( $key ) {
			case 'package-categories':
			case 'group-pricing':
			case 'package-dates':
				return $this->data[ $key ] ?? $this->get_meta( $key );
			case 'default_pricings':
				return $this->get_default_pricings();
			default:
				return null;
		}
	}

	/**
	 * Check if the package has a group discount.
	 *
	 * @return bool
	 * @since 6.1.0
	 */
	public function has_group_discount(): bool {
		return $this->has_group_discount;
	}

	/**
	 * Sets primary pricing category details.
	 *
	 * @return void
	 * @since 6.1.0
	 */
	protected function set_primary_pricing_category_details() {

		$this->primary_pricing_category = $this->get_traveler_categories()->get_primary_traveler_category();

		$this->has_sale   = (bool) ( $this->primary_pricing_category->get( 'has_sale' ) ?? false );
		$this->price      = (float) ( $this->primary_pricing_category->get( 'price' ) ?? 0 );
		$this->sale_price = (float) ( $this->primary_pricing_category->get( 'sale_price' ) ?? 0 );

		if ( $this->has_sale && $this->price > 0 ) {
			$this->sale_amount     = $this->price - $this->sale_price;
			$this->sale_percentage = round( ( ( $this->price - $this->sale_price ) / $this->price ) * 100 );
		} else {
			$this->sale_amount = $this->sale_percentage = 0;
		}

		$this->has_group_discount = (bool) ( $this->primary_pricing_category->get( 'enabled_group_discount' ) ?? false );
		$this->group_pricing      = (array) ( $this->primary_pricing_category->get( 'group_pricing' ) ?? array() );
	}

	/**
	 * Set the categories pricings.
	 *
	 * @return void
	 * @since 6.2.2
	 */
	public function set_categories_pricings() {

		if ( ! wptravelengine_is_addon_active( 'conditional-price' ) ) {
			$this->categories_pricings = $this->get_default_pricings();
			return;
		}

		$package_dates = $this->get_meta( 'package-dates' ) ?: array();

		$package_date = array(
			'dtstart'      => wp_date( 'Y-m-d' ),
			'is_recurring' => false,
			'seats'        => '',
		);

		if ( wptravelengine_is_addon_active( 'fixed-starting-dates' ) && ! empty( $package_dates ) ) {
			usort( $package_dates, fn( $a, $b ) => strtotime( $a['dtstart'] ) <=> strtotime( $b['dtstart'] ) );
			$comp_date       = wp_date( 'Y-m-d' );
			$cut_off_enabled = wptravelengine_toggled( $this->trip->get_setting( 'trip_cutoff_enable', false ) );
			if ( $cut_off_enabled ) {
				$cut_off_period = (int) $this->trip->get_setting( 'trip_cut_off_time', 0 );
				$cut_off_unit   = $this->trip->get_setting( 'trip_cut_off_unit', 'days' );
				$comp_date      = wp_date( 'Y-m-d', strtotime( "+$cut_off_period $cut_off_unit" ) );
			}
			$package_date = current( array_filter( $package_dates, fn( $date ) => $date['dtstart'] >= $comp_date ) );
		}

		if ( $package_date ) {
			$parser                    = new PackageDateParser( $this, $package_date );
			$this->categories_pricings = $parser->get_data_of( $package_date['dtstart'], 'pricing' );
		} else {
			$this->categories_pricings = $this->get_default_pricings();
		}
	}

	/**
	 * This function retrieves the nearest date pricing for the primary traveler category, even when the price has been conditionally overridden.
	 *
	 * @return array
	 * @since 6.2.2
	 */
	public function get_actual_pricing_infos(): array {

		$this->set_categories_pricings(); // phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase

		$new_price     = floatval( $this->categories_pricings[0]['price'] ?? 0 );
		$price         = $this->has_sale ? $this->price : $new_price;
		$sale_price    = $this->has_sale ? $new_price : $this->sale_price;
		$group_pricing = (array) ( $this->categories_pricings[0]['group_pricing'] ?? $this->group_pricing );

		$has_sale        = $this->has_sale && ( $sale_price < $price );
		$sale_percentage = ( $this->has_sale && $price > 0 ) ? round( ( ( $price - $sale_price ) / $price ) * 100 ) : 0;

		return compact( 'price', 'sale_price', 'group_pricing', 'has_sale', 'sale_percentage' );
	}
}
