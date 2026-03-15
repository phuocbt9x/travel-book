<?php

/**
 * Traveler Categories Model.
 *
 * @package WPTravelEngine
 * @since 6.0.0
 */

namespace WPTravelEngine\Core\Models\Post;

use WPTravelEngine\Abstracts\Iterator;
use WPTravelEngine\Core\Models\Post\Trip;
use WPTravelEngine\Core\Models\Post\TripPackage;
use WPTravelEngine\Traits\Factory;
use WPTravelEngine\Core\Models\Settings\Options;

/**
 * Class TravelerCategories.
 * This class represents a traveler category to the WP Travel Engine plugin.
 *
 * @since 6.0.0
 */
class TravelerCategories extends Iterator {
	use Factory;

	/**
	 * The trip object.
	 *
	 * @var Trip
	 */
	protected Trip $trip;

	/**
	 * The package object.
	 *
	 * @var TripPackage
	 */
	protected TripPackage $package;

	/**
	 * Available traveler categories.
	 *
	 * @var ?array
	 * @since 6.5.5
	 */
	protected $available_categories = null;

	/**
	 * Traveler Categories Model Constructor.
	 *
	 * @param Trip        $trip The trip object.
	 * @param TripPackage $package The trip package object.
	 */
	public function __construct( Trip $trip, TripPackage $package ) {
		$this->trip    = $trip;
		$this->package = $package;

		$categories = (array) $this->package->get_meta( 'package-categories' );

		$group_pricing = $this->package->get_meta( 'group-pricing' ) ?? array();

		$categories['enabled_group_discount'] = $categories['enabled_group_discount'] ?? array();
		$categories['group_pricing']          = array_map(
			function ( $gp ) {
				return array_map(
					function ( $p ) {
						return array(
							'from' => is_numeric( $p['from'] ) ? (int) $p['from'] : '',
							'to'   => is_numeric( $p['to'] ) ? (int) $p['to'] : '',
							'rate' => (float) $p['price'],
						);
					},
					$gp
				);
			},
			is_array( $group_pricing ) ? $group_pricing : array()
		);

		$_categories = array();

		$this->available_categories = wptravelengine_settings()->get_traveler_categories( array( 'fields' => 'id=>name' ) );

		/**
		 * This is temporary max. pax. as the max. pax for each pricing category is not set
		 * This can be replaced if max. pax. per pricing category is set in the future.
		 *
		 * @since 6.6.7
		 */
		$max_pax = wptravelengine_normalize_numeric_val( $this->trip->get_maximum_participants() );

		foreach ( $this->available_categories as $id => $label ) {
			foreach ( $categories as $key => $values ) {
				switch ( $key ) {
					case 'c_ids':
						$_categories[ $id ]['c_ids'] = $id;
						break;
					case 'labels':
						$_categories[ $id ]['labels'] = $label;
						break;
					case 'prices':
						$_categories[ $id ]['prices'] = $values[ $id ] ?? '';
						break;
					case 'pricing_types':
						$_categories[ $id ]['pricing_types'] = $values[ $id ] ?? 'per-person';
						break;
					case 'sale_prices':
						$_categories[ $id ]['sale_prices'] = $values[ $id ] ?? '';
						break;
					case 'min_paxes':
						$_categories[ $id ]['min_paxes'] = $values[ $id ] ?? '';
						break;
					// case 'max_paxes':
						// $_categories[ $id ][ 'max_paxes' ] = $values[ $id ] ?? '';
						// break;
					case 'enabled_sale':
						$_categories[ $id ]['enabled_sale'] = (bool) ( $values[ $id ] ?? false );
						break;
					case 'enabled_group_discount':
						$_categories[ $id ]['enabled_group_discount'] = (bool) ( $values[ $id ] ?? false );
						break;
					case 'group_pricing':
						$_categories[ $id ]['group_pricing'] = ( $_categories[ $id ]['enabled_group_discount'] ?? false ) ? ( $values[ $id ] ?? array() ) : array();
						break;
					default:
						$_categories[ $id ][ $key ] = $values[ $id ] ?? '';
						break;
				}
			}
			$_categories[ $id ]['max_paxes'] = $max_pax;
			$_categories[ $id ]['age_group'] = (string) ( get_term_meta( $id )['age_group'][0] ?? '' );
		}

		$primary_category = $this->get_primary_category_id();

		if ( isset( $_categories[ $primary_category ] ) ) {
			$_categories = array( $primary_category => $_categories[ $primary_category ] ) + array_diff_key( $_categories, array( $primary_category => true ) );
		}

		$data = array_map(
			function ( $category ) {
				return new TravelerCategory( $this->trip, $this->package, $category );
			},
			$_categories
		);

		parent::__construct( array_values( $data ) );
	}

	/**
	 * Get category by id.
	 *
	 * @param $id
	 *
	 * @return mixed|null
	 */
	public function get( $id ) {
		return $this->data[ array_search( $id, array_column( $this->data, 'id' ) ) ] ?? null;
	}


	/**
	 * Get all categories.
	 *
	 * @return array
	 */
	public function get_categories() {
		return $this->data;
	}

	/**
	 * Get primar category id.
	 *
	 * @return int
	 * @since 6.5.5
	 */
	public function get_primary_category_id(): int {

		$term_ids = array(
			$this->package->get_meta( '_primary_category_id' ),
			$this->trip->get_meta( 'primary_category' ),
			wptravelengine_settings()->get_primary_pricing_category()->term_id,
		);

		foreach ( $term_ids as $id ) {
			if ( $id && isset( $this->available_categories[ $id ] ) ) {
				return (int) $id;
			}
		}

		return 0;
	}

	/**
	 * Get the primary traveler category.
	 *
	 * @return TravelerCategory
	 * @updated 6.5.5
	 */
	public function get_primary_traveler_category(): TravelerCategory {

		$traveler_term_id = $this->get_primary_category_id();

		foreach ( $this->data as $category ) {
			if ( isset( $category->id ) && $category->id === $traveler_term_id ) {
				return $category;
			}
		}

		return $this->data[0];
	}

	/**
	 * Check if price is set only for primary pricing category.
	 *
	 * @return bool
	 */
	public function is_single_pricing_category() {
		$category_id        = Options::get( 'primary_pricing_category', 0 );
		$pricing_categories = $this->data;
		$primary_cat_price  = null;

		foreach ( $pricing_categories as $category ) {
			if ( isset( $category->c_ids ) && $category->c_ids === $category_id ) {
				$primary_cat_price = $category->prices;
				break;
			}
		}

		if ( isset( $primary_cat_price ) && $primary_cat_price != '' ) {
			foreach ( $pricing_categories as $category ) {
				$price = $category->prices ?? '';
				if ( $price == '' ) {
					return true;
				}
			}
		}

		return false;
	}

	/**
	 * Is category available.
	 *
	 * @param int $category_id
	 *
	 * @return bool
	 * @since 6.5.5
	 */
	public function is_category_available( int $category_id ): bool {
		return isset( $this->available_categories[ $category_id ] );
	}
}
