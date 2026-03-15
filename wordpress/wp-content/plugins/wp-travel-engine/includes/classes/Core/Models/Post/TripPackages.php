<?php
/**
 * Trip Packages Model.
 *
 * @package WPTravelEngine/Core/Models
 * @since 6.0.0
 */

namespace WPTravelEngine\Core\Models\Post;

use WPTravelEngine\Traits\Factory;

/**
 * Class TripPackages.
 * This class represents a trip package to the WP Travel Engine plugin.
 *
 * @since 6.0.0
 */
class TripPackages extends TripPackageIterator {

	use Factory;

	/**
	 * The default package of the trip.
	 *
	 * @var TripPackage|null
	 */
	protected ?TripPackage $default_package = null;

	/**
	 * The position of the current element in the data array.
	 *
	 * @var intw
	 */
	protected int $position = 0;

	/**
	 * Find the lowest package with low price.
	 *
	 * @return TripPackage|null
	 */
	protected function lowest_price_package(): ?TripPackage {

		$lowest_package = null;
		$lowest_price   = null;
		foreach ( $this->packages as $package ) {

			if ( is_null( $lowest_package ) ) {
				$lowest_package = $package;
			}

			/* @var TripPackage $package */
			$price = $package->get_traveler_categories()->get_primary_traveler_category()->get_sale_price();

			if ( is_null( $lowest_price ) || $price < $lowest_price ) {
				$lowest_price   = $price;
				$lowest_package = $package;
			}
		}

		return $lowest_package;
	}

	/**
	 * Set the default package.
	 *
	 * @return ?TripPackage
	 */
	public function get_package_with_low_price(): ?TripPackage {
		if ( count( $this->packages ) < 1 ) {
			return null;
		}

		if ( count( $this->packages ) === 1 ) {
			return $this->packages[0];
		}

		return $this->lowest_price_package();
	}

	/**
	 * Get the package.
	 *
	 * @param int $package_id
	 *
	 * @return ?TripPackage
	 */
	public function get_package( int $package_id ): ?TripPackage {
		$packages = $this->packages;

		foreach ( $packages as $package ) {
			if ( $package->get_id() === $package_id ) {
				return $package;
			}
		}

		return null;
	}
}
