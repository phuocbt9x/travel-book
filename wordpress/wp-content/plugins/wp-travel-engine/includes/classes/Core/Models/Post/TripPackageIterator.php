<?php
/**
 * Class TripPackageIterator.
 *
 * @package WPTravelEngine\Models
 * @since 6.0.0
 */

namespace WPTravelEngine\Core\Models\Post;

use WPTravelEngine\Abstracts\Iterator;

/**
 * Class TripPackageIterator.
 *
 * @package WPTravelEngine\Models
 * @since 6.0.0
 */
class TripPackageIterator extends Iterator {

	/**
	 * The trip object.
	 *
	 * @var Trip
	 */
	protected Trip $trip;

	/**
	 * The data array.
	 *
	 * @var array
	 */
	protected array $packages = array();

	/**
	 * Iterator Constructor.
	 *
	 * @param Trip $trip The trip object.
	 *
	 * @since 6.1.2
	 */
	public function __construct( Trip $trip ) {
		$this->trip = $trip;

		$package_ids        = (array) $this->trip->get_meta( 'packages_ids' );
		$primary_package_id = $this->trip->get_meta( 'primary_package' );

		if ( ! empty( $primary_package_id ) ) {
			if ( ! in_array( $primary_package_id, $package_ids ) ) {
				update_post_meta( $this->trip->get_id(), 'primary_package', $package_ids[0] );
			}
			$package_ids = array_diff( $package_ids, array( $primary_package_id ) );
			array_unshift( $package_ids, $primary_package_id );
		}

		foreach ( $package_ids as $package_id ) {
			if ( is_numeric( $package_id ) ) {
				$package            = get_post( $package_id );
				$package_categories = get_post_meta( $package_id, 'package-categories', true );
				if ( $package && $package_categories ) {
					$this->packages[] = new TripPackage( $package_id, $this->trip );
				}
			}
		}

		parent::__construct( $this->packages );
	}
}
