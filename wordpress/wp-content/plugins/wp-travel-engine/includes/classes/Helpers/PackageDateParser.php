<?php
/**
 * Package Date Parser.
 *
 * @package WPTravelEngine
 * @since 6.0.0
 */

namespace WPTravelEngine\Helpers;

use DateTime;
use RRule\RRule;
use WPTravelEngine\Core\Booking\Inventory;
use WPTravelEngine\Core\Models\Post\TripPackage;
use WPTravelEngine\Core\Models\Post\TravelerCategory;

#[\AllowDynamicProperties]
/**
 * Class PackageDateParser.
 * This class is responsible for parsing package dates.
 *
 * @since 6.0.0
 */
class PackageDateParser {
	/**
	 * @var string
	 */
	protected string $dtstart;

	/**
	 * @var array
	 */
	protected array $times;

	/**
	 * @var bool
	 */
	protected bool $is_recurring = false;

	/**
	 * @var array
	 */
	protected array $rrule;

	/**
	 * @var mixed
	 */
	protected $seats;

	/**
	 * @var TripPackage
	 */
	protected TripPackage $package;

	/**
	 * @var array
	 */
	protected array $booked_seats = array();

	/**
	 * @var array
	 */
	protected array $default_pricing = array();

	/**
	 * @var int|string
	 * @since 6.6.7
	 */
	protected $total_seats;

	/**
	 * @var ?string
	 * @since 6.6.7
	 */
	public static $version = null;

	/**
	 * Constructor.
	 *
	 * @param TripPackage $trip_package Trip package object.
	 */
	public function __construct( TripPackage $trip_package, array $args ) {

		$args['is_recurring'] = empty( $args['is_recurring'] ) ? false : $args['is_recurring'];

		$this->package      = $trip_package;
		$this->dtstart      = $args['dtstart'];
		$this->times        = array_values( $args['times'] ?? array() );
		$this->is_recurring = is_bool( $args['is_recurring'] ) ? $args['is_recurring'] : '1' === $args['is_recurring'];
		$this->seats        = wptravelengine_normalize_numeric_val( $args['seats'] ?? '' );
		$this->rrule        = $this->parse_rrule( $args['rrule'] ?? array() );
		$this->total_seats  = wptravelengine_normalize_numeric_val( $trip_package->get_trip()->get_maximum_participants() );

		self::$version = strtolower( (string) ( $args['version'] ?? self::$version ?? 'v2' ) );

		do_action( 'wptravelengine_package_date_parser_construct', $this->package, $args );
	}

	/**
	 * Parse the rrule.
	 *
	 * @param array $args RRULE arguments.
	 *
	 * @return array
	 */
	protected function parse_rrule( $args ): array {
		$rrule = array();

		try {
			$rrule['dtstart'] = new DateTime( $this->dtstart );
			$rrule['freq']    = $args['r_frequency'] ?? RRule::DAILY;
			if ( $args['r_until'] ?? false ) {
				$rrule['until'] = new DateTime( $args['r_until'] );
			} elseif ( is_numeric( $args['r_count'] ?? false ) ) {
				$rrule['count'] = $args['r_count'];
			} else {
				$rrule['count'] = 10;
			}

			switch ( $rrule['freq'] ) {
				case 'WEEKLY':
					$rrule['byday'] = $args['r_weekdays'] ?? array();
					break;
				case 'MONTHLY':
					$rrule['bymonth'] = $args['r_months'] ?? array();
					break;
			}

			return $rrule;
		} catch ( \Exception $e ) {
			return array();
		}
	}

	/**
	 * @return string
	 */
	public function get_starting_date(): string {
		return $this->dtstart;
	}

	/**
	 * Get the time slots.
	 *
	 * @return array
	 */
	public function get_times(): array {
		return $this->times;
	}

	/**
	 * If the data is recurring.
	 *
	 * @return bool
	 */
	public function is_recurring(): bool {
		return $this->is_recurring;
	}

	/**
	 * @param $date
	 *
	 * @return array
	 */
	protected function prepare_date( $date ): array {
		$times          = array();
		$formatted_date = $date->format( 'Y-m-d' );
		$end_date 		= wptravelengine_format_trip_end_datetime( $formatted_date, $this->package->get_trip(), 'Y-m-d' );

		$i = 0;
		foreach ( $this->times as $time ) {
			list( $available_time_seats, $capacity ) = $this->get_seats_details( $formatted_date, $time['from'] );

			if ( is_numeric( $available_time_seats ) && $available_time_seats <= 0 ) {
				continue;
			}

			$times[ $i ] = array(
				'key'   => implode(
					'_',
					array(
						$this->package->get_id(),
						$formatted_date,
						$time['from'],
						$time['from'],
					)
				),
				'from'  => $formatted_date . 'T' . $time['from'],
				'to'    => $end_date . 'T' . $time['from'],
				'seats' => $available_time_seats,
			);

			if ( self::$version === 'v3' ) {
				$times[ $i ]['capacity']   = $capacity;
				$times[ $i ]['seats_left'] = $available_time_seats;
			}
			++$i;
		}

		list( $seats_left, $capacity ) = $this->get_seats_details( $formatted_date );

		if ( ! empty( $this->times ) && empty( $times ) ) {
			$available_seats = 0;
		} else {
			$available_seats = $seats_left;
		}

		if ( ! empty( $times ) ) {
			if ( is_numeric( $available_seats ) ) {
				$capacity = $available_seats = 0;
				foreach ( $times as $time ) {
					$available_seats += $time['seats'];
					$capacity        += $time['capacity'] ?? 0;
				}
			}
		}

		/**
		 * Here, 'capacity' represents the overall seat capacity for the given date/time,
		 * while 'seats' & 'seats_left' indicates the seats left for that date/time.
		 *
		 * @updated 6.6.7
		 */
		$date_data = array(
			'times'   => $times,
			'seats'   => is_numeric( $available_seats ) ? (int) $available_seats : '',
			'pricing' => $this->package->default_pricings,
		);

		if ( self::$version === 'v3' ) {
			$date_data['capacity']   = $capacity;
			$date_data['seats_left'] = $date_data['seats'];
		}

		return apply_filters( 'wptravelengine_package_date_parser_prepare_date', $date_data, $this->package, $formatted_date );
	}

	/**
	 * Get the dates from the rrule.
	 *
	 * @param RRule $rrule
	 * @param array $args
	 *
	 * @return array
	 */
	protected function get_dates_from_rrule( RRule $rrule, $args = array() ): array {

		$recurring_dates = $rrule;

		if ( isset( $args['from'], $args['to'] ) ) {
			$recurring_dates = $rrule->getOccurrencesBetween( $args['from'], $args['to'] );
		} elseif ( isset( $args['from'] ) ) {
			$recurring_dates = $rrule->getOccurrencesAfter( $args['from'], true );
		}

		$dates = array();

		foreach ( $recurring_dates as $date ) {
			$dates[ $date->format( 'Y-m-d' ) ] = $this->prepare_date( $date );
		}

		return $dates;
	}

	/**
	 * Get the recurring Dates.
	 *
	 * @return RRule|array
	 */
	public function get_dates( bool $object = true, $args = array() ) {

		$this->booked_seats = $this->package->get_trip()->get_my_booked_seats();

		if ( ! $this->is_recurring || empty( $this->rrule ) ) {
			if ( $this->dtstart < date( 'Y-m-d' ) ) {
				return array();
			}
			try {
				return array( $this->dtstart => $this->prepare_date( new \DateTime( $this->dtstart ) ) );
			} catch ( \Exception $e ) {
				return array();
			}
		}

		$rrule = new RRule( $this->rrule );

		if ( $object ) {
			return $rrule;
		}

		return $this->get_dates_from_rrule( $rrule, $args );
	}

	/**
	 * Get the unique dates.
	 *
	 * @param bool   $object Whether to return the dates as an object.
	 * @param array  $args Arguments.
	 * @param string $format The format of the dates.
	 *
	 * @return array
	 */
	public function get_unique_dates( bool $object = true, $args = array(), string $format = 'Y-m-d' ): array {
		return array_unique(
			array_map(
				function ( $date ) use ( $format ) {
					return wp_date( $format, strtotime( $date ) );
				},
				array_keys( $this->get_dates( $object, $args ) )
			)
		);
	}

	/**
	 * Get data of a single date.
	 *
	 * @param string  $date Date.
	 * @param ?string $key Key.
	 *
	 * @return array
	 * @since 6.5.5
	 */
	public function get_data_of( string $date, $key = null ): array {
		$data = $this->prepare_date( new \DateTime( $date ) );
		return $key ? ( $data[ $key ] ?? array() ) : $data;
	}

	/**
	 * Retrieves seating information for a specific date and time.
	 *
	 * Returns an array containing seating availability data in the following format:
	 * - Index 0: Number of seats remaining (int)
	 * - Index 1: Total seating capacity (int)
	 *
	 * @param string $date The target date in YYYY-MM-DD format.
	 * @param string $time The target time in HH:MM format.
	 *
	 * @return array{0: int, 1: int} Indexed array with seats left and capacity
	 *
	 * @since 6.6.7
	 */
	public function get_seats_details( string $date, string $time = '00:00' ) {

		$my_seats    = $this->seats;
		$total_seats = $this->total_seats;

		$booked_seats_for_this_pac = $this->booked_seats[ $this->package->get_id() ][ $date ][ $time ] ?? 0;

		if ( ! is_numeric( $my_seats ) ) {
			if ( ! is_numeric( $total_seats ) ) {
				return array( '', '' );
			}
			$my_seats = $total_seats;
		} elseif ( ! is_numeric( $total_seats ) ) {
			return array( max( $my_seats - $booked_seats_for_this_pac, 0 ), $my_seats );
		}

		$total_booked_seats = array_reduce(
			$this->booked_seats,
			function ( $acc, $seats ) use ( $date, $time ) {
				return $acc + ( $seats[ $date ][ $time ] ?? 0 );
			},
			0
		);

		$available_capacity    = min( $my_seats, $total_seats );
		$remaining_for_package = max( $available_capacity - $booked_seats_for_this_pac, 0 );
		$remaining_total       = max( $total_seats - $total_booked_seats, 0 );

		$seats_left = min( $remaining_for_package, $remaining_total );
		$capacity   = ( $seats_left === $remaining_for_package ) ? $available_capacity : $total_seats;

		return array( $seats_left, $capacity );
	}
}
