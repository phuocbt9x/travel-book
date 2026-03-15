<?php
/**
 * Upcoming Tours Admin Page.
 *
 * @package WPTravelEngine\Pages\Admin
 * @since 6.4.3
 */

namespace WPTravelEngine\Pages\Admin;

use WPTravelEngine\Interfaces\AdminPage;
use WPTravelEngine\Core\Models\Post\Booking;
use WPTravelEngine\Core\Models\Post\Trip;

/**
 * Upcoming Tours Class
 */
class UpcomingTours implements AdminPage {

	/**
	 * Parent slug.
	 *
	 * @var string
	 */
	public string $parent_slug;

	/**
	 * Page title.
	 *
	 * @var string
	 */
	public string $page_title;

	/**
	 * Menu title.
	 *
	 * @var string
	 */
	public string $menu_title;

	/**
	 * Capability.
	 *
	 * @var string
	 */
	public string $capability;

	/**
	 * Position.
	 *
	 * @var int
	 */
	public int $position;

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->parent_slug = 'edit.php?post_type=booking';
		$this->page_title  = __( 'Upcoming Tours', 'wp-travel-engine' );
		$this->menu_title  = __( 'Upcoming Tours', 'wp-travel-engine' );
		$this->capability  = 'manage_options';
		$this->position    = 1;

		// Clear cache when bookings are created or updated
		add_action( 'wptravelengine.booking.created', array( __CLASS__, 'clear_cache' ), 10 );
		add_action( 'wptravelengine.booking.updated', array( __CLASS__, 'clear_cache' ), 10 );
	}

	/**
	 * Render the page of Upcoming Tours.
	 */
	public function view() {
		wp_enqueue_script( 'wptravelengine-upcoming-tours' );
		wptravelengine_get_admin_template( 'upcoming-tours/index.php', self::get_template_args() );
	}

	/**
	 * Get upcoming tours html.
	 *
	 * @param array $args Arguments.
	 *
	 * @return string
	 */
	public static function get_upcoming_tours_html( array $args = array() ) {
		ob_start();
		wptravelengine_get_admin_template( 'upcoming-tours/partials/content.php', self::get_template_args( $args ) );
		return ob_get_clean();
	}

	/**
	 * Get formatted date.
	 * Uses UTC for output so the displayed time does not change when WordPress timezone is changed.
	 *
	 * @param string $datetime Datetime.
	 *
	 * @return array
	 */
	public static function get_formatted_date( $datetime ) {
		if ( ! is_string( $datetime ) || '' === trim( $datetime ) ) {
			return array(
				'date_time' => '',
				'month'     => '',
				'day'       => '',
				'time'      => '',
			);
		}
		$time_stamp = strtotime( $datetime );
		if ( false === $time_stamp ) {
			return array(
				'date_time' => '',
				'month'     => '',
				'day'       => '',
				'time'      => '',
			);
		}
		$tz_utc = new \DateTimeZone( 'UTC' );
		$time   = strpos( $datetime, 'T' ) !== false ? wp_date( get_option( 'time_format', 'g:i a' ), $time_stamp, $tz_utc ) : '';
		return array(
			'date_time' => wp_date( 'M d, Y', $time_stamp, $tz_utc ) . $time,
			'month'     => wp_date( 'M', $time_stamp, $tz_utc ),
			'day'       => wp_date( 'd', $time_stamp, $tz_utc ),
			'time'      => $time,
		);
	}

	/**
	 * Get filtered dates For Header Filter Buttons.
	 *
	 * @return array
	 */
	public static function get_filtered_dates() {
		return array(
			'today'        => array(
				'from' => wp_date( 'Y-m-d' ),
				'to'   => wp_date( 'Y-m-d' ),
			),
			'this_week'    => array(
				'from' => wp_date( 'Y-m-d', strtotime( 'last sunday' ) ),
				'to'   => wp_date( 'Y-m-d', strtotime( 'next saturday' ) ),
			),
			'next_week'    => array(
				'from' => wp_date( 'Y-m-d', strtotime( 'next saturday' ) ),
				'to'   => wp_date( 'Y-m-d', strtotime( 'next saturday' ) ),
			),
			'next_15_days' => array(
				'from' => wp_date( 'Y-m-d' ),
				'to'   => wp_date( 'Y-m-d', strtotime( '+15 days' ) ),
			),
			'this_month'   => array(
				'from' => wp_date( 'Y-m-d', strtotime( 'first day of this month' ) ),
				'to'   => wp_date( 'Y-m-d', strtotime( 'last day of this month' ) ),
			),
		);
	}

	/**
	 * Get template args.
	 *
	 * @param array $args Arguments.
	 *
	 * @return array
	 */
	public static function get_template_args( array $args = array() ) {
		$request = wp_parse_args( // phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.obfuscation_wp_parse_args
			$args,
			array(
				'date'   => 'all',
				'count'  => 10,
				'status' => 'all',
			)
		);
		$trips   = array();
		$status  = isset( $request['status'] ) ? sanitize_text_field( $request['status'] ) : 'all';
		// Get valid statuses (allow plugins to add custom statuses)
		$valid_statuses = apply_filters( 'wptravelengine_upcoming_tours_valid_statuses', array( 'all', 'booked' ) );
		// Ensure status is one of the valid values
		if ( ! in_array( $status, $valid_statuses, true ) ) {
			$status = 'all';
		}

		// Get trips from bookings (only if status is 'all' or 'booked').
		if ( $status === 'all' || $status === 'booked' ) {
			// Get bookings with caching (already filtered to future dates)
			$bookings = self::get_post_bookings();

			// Prime post meta cache for bookings to avoid N+1 queries
			if ( ! empty( $bookings ) ) {
				update_postmeta_cache( wp_list_pluck( $bookings, 'ID' ) );
			}

			// Collect unique trip IDs for bulk meta caching
			$trip_ids = array();
			foreach ( $bookings as $booking ) {
				$booking_model = new Booking( $booking->ID );
				$trip_id       = $booking_model->get_trip_id();
				if ( $trip_id ) {
					$trip_ids[] = $trip_id;
				}
				$trip_datetime = $booking_model->get_trip_datetime();

				// Additional safety check (though get_post_bookings already filters)
				if ( ! $trip_datetime || $trip_datetime < wp_date( 'Y-m-d' ) ) {
					continue;
				}

				if ( isset( $request['date'] ) && $request['date'] !== 'all' ) {
					$date_range = json_decode( stripslashes( $request['date'] ), true );

					if ( strtotime( $trip_datetime ) < strtotime( $date_range['from'] . ' 00:00:00' ) || strtotime( $trip_datetime ) > strtotime( $date_range['to'] . ' 23:59:59' ) ) {
						continue;
					}
				}

				$u_id       = $trip_id . '_' . $trip_datetime;
				$travellers = 0;
				foreach ( $booking_model->get_trip_pax() as $pax ) {
					$travellers += (int) $pax;
				}
				// Travellers meta is change if custom reservation (booking is edited from booking details page).
				if ( $travellers <= 0 ) {
					$data       = $booking_model->get_data();
					$travellers = $data['booked_trips'][0]['number_of_travellers'] ?? 0;
				}
				if ( ! isset( $trips[ $u_id ] ) ) {
					try {
						$trip = new Trip( $trip_id );
					} catch ( \Exception $e ) {
						continue;
					}

					$trips[ $u_id ] = array(
						'permalink'  => $trip->get_permalink(),
						'title'      => $trip->get_title(),
						'datetime'   => self::get_formatted_date( $trip_datetime ),
						'travellers' => 0,
						'image'      => $trip->get_gallery_images()[0]['src'] ?? '',
					);
				}
				$trips[ $u_id ]['travellers'] += $travellers;
			}

			// Prime trip meta cache
			if ( ! empty( $trip_ids ) ) {
				update_postmeta_cache( array_unique( $trip_ids ) );
			}
		}

		/**
		 * Allow plugins to modify trips array (e.g., add custom entries, filter by status, etc.).
		 *
		 * @param array  $trips   Trips array.
		 * @param string $status  Current status filter.
		 * @param array  $request Request arguments.
		 *
		 * @return array Modified trips array.
		 * @since 6.7.0
		 */
		$trips = apply_filters( 'wptravelengine_upcoming_tours_modify_trips', $trips, $status, $request );

		array_multisort(
			array_map( 'strtotime', array_column( array_column( $trips, 'datetime' ), 'date_time' ) ),
			SORT_ASC,
			$trips
		);

		$count         = $request['count'];
		$show_more_btn = count( $trips ) > $count;

		$trips = $show_more_btn ? array_slice( $trips, 0, $count ) : $trips;

		$show_less_btn = 10 < count( $trips );

		$dates = self::get_filtered_dates();
		return compact(
			'dates',
			'trips',
			'show_more_btn',
			'show_less_btn',
			'count'
		);
	}

	/**
	 * Get upcoming tours details Html.
	 *
	 * @param int $id Unique Trip DateTime & Booking Id.
	 *
	 * @return string
	 */
	public static function get_details_html( $id ) {
		$bookings_total = 0;
		$bookings       = array();
		// Use cached bookings for details view as well
		foreach ( self::get_post_bookings() as $booking ) {
			$booking_model  = new Booking( $booking->ID );
			$trip_id        = $booking_model->get_trip_id();
			$start_datetime = $booking_model->get_trip_datetime();

			if ( $id !== $trip_id . '_' . $start_datetime ) {
				continue;
			}
			$trip       = new Trip( $trip_id );
			$travellers = 0;
			foreach ( $booking_model->get_trip_pax() as $pax ) {
				$travellers += $pax;
			}
			// Travellers meta is change if custom reservation (booking is edited from booking details page).
			if ( $travellers <= 0 ) {
				$data       = $booking_model->get_data();
				$travellers = $data['booked_trips'][0]['number_of_travellers'] ?? 0;
			}
			$bookings[ $booking->ID ] = array(
				'id'           => $booking->ID,
				'billing_info' => $booking_model->get_billing_fname() . ' ' . $booking_model->get_billing_lname(),
				'travellers'   => $travellers,
			);
			$bookings_total          += $booking_model->get_total();
			$trip_details             = array(
				'title'      => $trip->get_title(),
				'image'      => $trip->get_gallery_images()[0]['src'] ?? '',
				'duration'   => self::get_trip_duration( $trip ),
				'start_date' => wptravelengine_format_trip_datetime( $start_datetime ),
				'end_date'   => wptravelengine_format_trip_end_datetime( $start_datetime, $trip ),
				'travellers' => isset( $trip_details['travellers'] ) ? $trip_details['travellers'] + $travellers : $travellers,
				'total'      => wte_get_formated_price( $bookings_total ),
			);
		}
		ob_start();
		wptravelengine_get_admin_template( 'upcoming-tours/partials/details.php', compact( 'trip_details', 'bookings' ) );
		return ob_get_clean();
	}

	/**
	 * Get post bookings.
	 *
	 * @param array $args Optional query arguments.
	 * @return array
	 */
	private static function get_post_bookings( array $args = array() ) {
		// Get cache version for invalidation
		$cache_version = wp_cache_get( 'wptravelengine_upcoming_tours_version', 'wptravelengine_upcoming_tours' );
		if ( false === $cache_version ) {
			$cache_version = 1;
			wp_cache_set( 'wptravelengine_upcoming_tours_version', $cache_version, 'wptravelengine_upcoming_tours', 2592000 );
		}

		// Build cache key based on arguments and version
		$cache_key = 'wptravelengine_upcoming_tours_bookings_' . $cache_version . '_' . md5( wp_json_encode( $args ) );

		// Try to get from cache first (5 minute cache)
		$cached = wp_cache_get( $cache_key, 'wptravelengine_upcoming_tours' );
		if ( false !== $cached ) {
			return $cached;
		}

		// Default query args - only get bookings with future trip dates
		$default_args = array(
			'post_type'      => 'booking',
			'posts_per_page' => -1,
			'post_status'    => 'publish',
			'meta_query'     => array(
				array(
					'key'     => 'wp_travel_engine_booking_status',
					'value'   => array( 'booked', 'pending' ),
					'compare' => 'IN',
				),
				array(
					'key'     => 'wp_travel_engine_booking_setting',
					'compare' => 'EXISTS',
				),
				array(
					'relation' => 'OR',
					array(
						'key'     => 'trip_datetime',
						'compare' => 'NOT EXISTS',
					),
					array(
						'key'     => 'trip_datetime',
						'value'   => wp_date( 'Y-m-d' ),
						'compare' => '>=',
						'type'    => 'DATE',
					),
				),
			),
			'orderby'        => 'date',
			'order'          => 'DESC',
		);

		$query_args = wp_parse_args( $args, $default_args );

		$bookings = get_posts( $query_args );

		// Prime post meta cache for all bookings to avoid N+1 queries
		if ( ! empty( $bookings ) ) {
			update_postmeta_cache( wp_list_pluck( $bookings, 'ID' ) );
		}

		// Filter bookings to only include those with future trip dates
		$filtered_bookings = array();
		$today             = wp_date( 'Y-m-d' );

		foreach ( $bookings as $booking ) {
			try {
				$booking_model = new Booking( $booking->ID );
				$trip_datetime = $booking_model->get_trip_datetime();

				// Only include bookings with future trip dates
				if ( $trip_datetime && $trip_datetime >= $today ) {
					$filtered_bookings[] = $booking;
				}
			} catch ( \Exception $e ) {
				// Skip invalid bookings
				continue;
			}
		}

		// Cache for 5 minutes
		wp_cache_set( $cache_key, $filtered_bookings, 'wptravelengine_upcoming_tours', 300 );

		return $filtered_bookings;
	}

	/**
	 * Get trip duration.
	 *
	 * @param Trip $trip Trip.
	 *
	 * @return string
	 */
	private static function get_trip_duration( $trip ) {
		$trip_duration         = $trip->get_trip_duration() . ' ' . $trip->get_trip_duration_unit();
		$trip_duration_minutes = '';
		$trip_nights           = '';
		if ( $trip->get_meta( 'trip_type' ) === 'single' ) {
			$trip_duration_minutes = $trip->get_meta( 'trip_duration_minutes' ) . ' minutes';
		} elseif ( $trip->get_trip_duration_unit() === 'days' ) {
			$trip_nights = $trip->get_setting( 'trip_duration_nights' ) ? $trip->get_setting( 'trip_duration_nights' ) . ' ' . __( 'Nights', 'wp-travel-engine' ) : '';
		}

		return trim( $trip_duration . ' ' . $trip_nights . ' ' . $trip_duration_minutes );
	}

	/**
	 * Clear cache for upcoming tours.
	 *
	 * @return void
	 * @since 6.7.0
	 */
	public static function clear_cache() {
		// Increment cache version to invalidate all cached bookings
		$current_version = wp_cache_get( 'wptravelengine_upcoming_tours_version', 'wptravelengine_upcoming_tours' );
		$new_version     = ( false !== $current_version ) ? (int) $current_version + 1 : 1;
		wp_cache_set( 'wptravelengine_upcoming_tours_version', $new_version, 'wptravelengine_upcoming_tours', 2592000 );
	}
}
