<?php
/**
 * Trip Handler Class.
 */
namespace WPTravelEngine\Posttype;

use WP_Post;

/**
 * @deprecated 6.0.0
 */
#[\AllowDynamicProperties]
class Trip {

	/**
	 * Class instance holder.
	 *
	 * @var array<int, self>
	 */
	protected static $instance = array();

	/**
	 * WP post Object.
	 *
	 * @var ?WP_Post
	 */
	public $post = null;

	/**
	 * Trip Packages post objects.
	 *
	 * @var array
	 */
	public $packages = array();

	public function __construct( $post ) {
		// wptravelengine_deprecated_class( __CLASS__, '6.0.0', \WPTravelEngine\Core\Models\Post\Trip::class );
		// add_action( 'wp', array( $this, 'initialize' ) );
		$this->post = $post;

		$this->initialize();
	}

	public function initialize() {
		$_post = $this->post;

		$trip_version = get_post_meta( $_post->ID, 'trip_version', true );
		if ( empty( $trip_version ) ) {
			$trip_version = '1.0.0';
		}

		$this->trip_version = $trip_version;

		$this->use_legacy_trip = defined( 'USE_WTE_LEGACY_VERSION' ) && USE_WTE_LEGACY_VERSION;

		$this->set_packages();
		$this->set_default_package();
	}

	public function set_packages() {

		if ( ! $this->post ) {
			return array();
		}

		// Get Trip package Ids.
		$package_ids = $this->{'packages_ids'};

		if ( ! is_array( $package_ids ) ) {
			$package_ids = array();
		}

		if ( ! empty( $package_ids ) ) {
			$packages = \get_posts(
				array(
					'post_type'        => 'trip-packages',
					'include'          => $package_ids,
					'suppress_filters' => true,
				)
			);
		} else {
			$packages = array();
		}

		$_packages = array();

		foreach ( $packages as $package ) {
			$_packages[ $package->ID ] = $package;
		}

		$this->packages = $_packages;
	}

	public function set_default_package() {
		if ( $this->post->post_type !== 'trip' ) {
			$default_package = null;
		} else {
			$default_package = wptravelengine_get_trip_primary_package( $this->post->ID );
		}
		$this->has_sale        = $default_package->{'has_sale'} ?? false;
		$this->price           = $default_package->{'price'} ?? 0;
		$this->sale_price      = $default_package->{'sale_price'} ?? 0;
		$this->sale_percentage = $default_package->{'sale_percentage'} ?? 0;
		$this->default_package = $default_package->post ?? false;
	}

	public function __isset( $key ) {
		return isset( $this->{$key} );
	}

	public function __get( $key ) {

		if ( $this->__isset( $key ) ) {
			return $this->{$key};
		}
		switch ( $key ) {
			case 'has_group_discount':
				return \apply_filters( 'has_packages_group_discounts', false, $this->post->ID );
			default:
				return \get_post_meta( $this->post->ID, $key, true );
		}
	}

	public function has_group_discount() {
		$packages = $this->packages;

		$primary_pricing_category_id = get_option( 'primary_pricing_category', 0 );

		if ( $primary_pricing_category_id ) {
			$term = get_term( $primary_pricing_category_id );
		}
		foreach ( $packages as $package ) {
			$package_categories = (object) $package->{'package-categories'};

			$package_categories_ids = ( isset( $package_categories->{'c_ids'} ) ) ? $package_categories->{'c_ids'} : array();

			if ( ! $primary_pricing_category_id ) {
				$primary_pricing_category_id = ! empty( $package_categories_ids ) && is_array( $package_categories_ids ) ? array_shift( $package_categories_ids ) : 0;
			}
			if ( ! $primary_pricing_category_id ) {
				return false;
			}

			$term = get_term( $primary_pricing_category_id );

			if ( ! ( $term instanceof \WP_Term ) ) {
				return false;
			}

			if ( isset( $package_categories->enabled_group_discount[ $term->term_id ] ) && '1' == $package_categories->enabled_group_discount[ $term->term_id ] ) {
				return true;
			}
		}

		return false;
	}

	public static function instance( $trip_id ) {

		$trip_id = (int) $trip_id;

 		if ( isset( self::$instance[ $trip_id ] ) ) {
			return self::$instance[ $trip_id ];
		}

		$trip = get_post( $trip_id );

		if ( ! ( $trip instanceof WP_Post ) ) {
			return false;
		}

		self::$instance[ $trip_id ] = new self( $trip );

		return self::$instance[ $trip_id ];
	}
}
