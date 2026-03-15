<?php
/**
 * Traveler Category Model.
 *
 * @package WPTravelEngine
 * @since 6.0.0
 */

namespace WPTravelEngine\Core\Models\Post;

use WPTravelEngine\Helpers\Translators;

#[\AllowDynamicProperties]
/**
 * Class TravelerCategory.
 * This class represents a traveler category to the WP Travel Engine plugin.
 *
 * @since 6.0.0
 */
class TravelerCategory {

	/**
	 * The traveler category id.
	 *
	 * @var int
	 */
	public int $id;

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
	 * The traveler category price.
	 *
	 * @var float|string
	 */
	public $price;

	/**
	 * The traveler category sale price.
	 *
	 * @var float|string
	 */
	public $sale_price;

	/**
	 * @var string
	 * @since 6.4.3
	 */
	public string $label;

	/**
	 * Traveler Category Model Constructor.
	 *
	 * @param Trip        $trip The trip object.
	 * @param TripPackage $package The trip package object.
	 * @param array       $package_category_data The package category data.
	 */
	public function __construct( Trip $trip, TripPackage $package, array $package_category_data ) {
		$this->trip    = $trip;
		$this->package = $package;

		$key_mapping = array(
			'c_ids'         => 'id',
			'labels'        => 'label',
			'prices'        => 'price',
			'pricing_types' => 'pricing_type',
			'sale_prices'   => 'sale_price',
			'min_paxes'     => 'min_pax',
			'max_paxes'     => 'max_pax',
			'enabled_sale'  => 'has_sale',
		);

		foreach ( $package_category_data as $property => $value ) {
			if ( isset( $key_mapping[ $property ] ) ) {
				$mapped_property = $key_mapping[ $property ];
				if ( in_array( $property, array( 'prices', 'sale_prices' ), true ) ) {
					$value = is_numeric( $value ) ? max( 0, (float) $value ) : '';
				}
				if ( $value instanceof \WP_Term ) {
					$value = $value->name;
				}
				$this->{$mapped_property} = $value;
				continue;
			}
			$this->{$property} = $value;
		}
	}

	/**
	 * @return string
	 * @since 6.4.3
	 * @since 6.7.4 Update label retrieval to support WPML translation.
	 */
	public function get_label(): string {
		$language = '';

		if ( Translators::is_wpml_multilingual_active() ) {
			$language = apply_filters( 'wpml_current_language', null ) ?? substr( get_locale(), 0, 2 );
		} elseif ( isset( $_GET['lang'] ) ) {
			$language = substr( sanitize_text_field( wp_unslash( $_GET['lang'] ) ), 0, 2 );
		}

		$category_term_meta = get_term_meta( $this->id, 'pll_category_name', true );
		return is_array( $category_term_meta ) && isset( $category_term_meta[ $language ] ) ? $category_term_meta[ $language ] : $this->label;
	}

	/**
	 * Get category value.
	 *
	 * @param mixed $key The key to get.
	 * @param mixed $default The default value to return if the key is not set.
	 *
	 * @return mixed
	 */
	public function get( $key, $default = null ) {
		switch ( $key ) {
			case 'group_pricing':
				$value = $this->package->get_group_pricing()[ $this->id ] ?? array();
				break;
			case 'description':
				$value = get_term_by( 'id', $this->id, 'trip-packages-categories' )->{$key};
				break;
			case 'label':
				$value = $this->get_label();
				break;
			default:
				$value = $this->{$key} ?? $default;
		}

		return $value;
	}

	/**
	 * Calculate Sale Percentage.
	 *
	 * @return float
	 */
	public function sale_percentage(): float {
		return ( ! $this->price ) ? 0 : ( ( $this->price - $this->sale_price ) / $this->price ) * 100;
	}

	/**
	 * Get the traveler category actual price.
	 *
	 * @return float|string
	 */
	public function get_price() {
		return $this->price;
	}

	/**
	 * Get the traveler category sale price.
	 *
	 * @return float|string
	 */
	public function get_sale_price() {
		return $this->sale_price;
	}
}
