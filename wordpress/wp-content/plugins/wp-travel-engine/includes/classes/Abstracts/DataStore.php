<?php
/**
 * Data.
 *
 * @package WPTravelEngine/Abstracts
 * @since 6.0.0
 */

namespace WPTravelEngine\Abstracts;

use WP_Post;

abstract class DataStore {

	/**
	 * Post data.
	 *
	 * @var array
	 */
	protected array $data = array();

	/**
	 *
	 * Check if a property is set.
	 *
	 * @param $key
	 *
	 * @return bool
	 */
	public function __isset( $key ) {
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

		return null;
	}
}
