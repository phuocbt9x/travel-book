<?php
/**
 * Trips List field class.
 *
 * @package WPTravelEngine
 * @since 6.0.0
 */

namespace WPTravelEngine\Utilities\FormBuilder\Fields;

/**
 * Trips List field class.
 */
class TripSelector extends Select {

	/**
	 * @var array|null $trips_options Trips options.
	 */
	protected static ?array $trips_options = null;

	/**
	 * Get Select options.
	 *
	 * @return array
	 */
	protected function get_options(): array {
		global $wpdb;
		if ( is_null( self::$trips_options ) ) {
			$query = $wpdb->prepare(
				"SELECT ID, post_title FROM {$wpdb->posts} WHERE post_type = %s and post_status = %s ORDER BY post_title ASC",
				WP_TRAVEL_ENGINE_POST_TYPE,
				'publish'
			);

			$trips = array_column( $wpdb->get_results( $query ) ?? array(), 'post_title', 'ID', );

			self::$trips_options = array( '' => __( 'Choose a Trip', 'wp-travel-engine' ) ) + $trips;
		}

		return self::$trips_options;
	}

	/**
	 * Initialize field type class.
	 *
	 * @param array $field Field attributes.
	 *
	 * @return Base
	 */
	public function init( array $field ): Base {

		$field['options'] = array( '' => __( 'Choose a Trip', 'wp-travel-engine' ) ) + $this->get_options();

		return parent::init( $field );
	}
}
