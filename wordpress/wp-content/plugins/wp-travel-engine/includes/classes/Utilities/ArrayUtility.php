<?php
/**
 * This file contains the Array class.
 *
 * @package WPTravelEngine
 * @since 6.0.0
 */

namespace WPTravelEngine\Utilities;

use WPTravelEngine\Traits\Factory;

/**
 * Array class.
 */
class ArrayUtility {
	/**
	 * @var mixed
	 */
	protected $data;

	/**
	 * Create Instance.
	 */
	public static function make( $array ): ArrayUtility {
		$array = is_array( $array ) ? $array : array();

		return new static( $array );
	}

	/**
	 * ArrayUtility constructor.
	 *
	 * @param array $array Array.
	 */
	protected function __construct( array $array ) {
		$this->data = $array;
	}

	/**
	 * Get value from array.
	 *
	 * @param string $key Key.
	 * @param mixed  $default Default.
	 *
	 * @return mixed
	 */
	public function get( string $key, $default = null ) {
		$keys = explode( '.', $key );

		return static::search( $this->data, $keys ) ?? $default;
	}

	/**
	 * Set value in array.
	 *
	 * @param string $key Key.
	 * @param mixed  $value Value.
	 *
	 * @return ArrayUtility
	 */
	public function set( string $key, $value ): ArrayUtility {
		$keys    = explode( '.', $key );
		$lastKey = array_pop( $keys );
		$data    = &$this->data;
		foreach ( $keys as $key ) {
			if ( ! isset( $data[ $key ] ) || ! is_array( $data[ $key ] ) ) {
				$data[ $key ] = array();
			}
			$data = &$data[ $key ];
		}
		$data[ $lastKey ] = $value;

		return $this;
	}

	/**
	 * Get the value of the array.
	 *
	 * @return mixed
	 */
	public function value() {
		return $this->data;
	}

	/**
	 * Recursive helper function to retrieve nested values from the settings array.
	 *
	 * @param array $data The current level of data to traverse.
	 * @param array $keys The remaining keys in the dot-separated path.
	 *
	 * @return mixed The value at the end of the path, or null if not found.
	 */
	public static function search( array $data, array $keys ) {
		$key = array_shift( $keys );
		if ( ! isset( $data[ $key ] ) ) {
			return null;
		}

		if ( empty( $keys ) ) {
			return $data[ $key ];
		} else {
			if ( ! is_array( $data[ $key ] ) ) {
				return null;
			}

			return static::search( $data[ $key ], $keys );
		}
	}

	/**
	 * Flatten a multi-dimensional associative array with dots.
	 *
	 * @param array  $array The array to flatten.
	 * @param string $prefix The prefix to prepend to the keys.
	 *
	 * @return array The flattened array.
	 * @since 6.2.0
	 */
	public static function flatten( array $array, string $prefix = '' ): array {
		$result = array();
		foreach ( $array as $key => $value ) {
			if ( is_array( $value ) ) {
				$result = array_merge( $result, static::flatten( $value, $prefix . $key . '.' ) );
			} else {
				$result[ $prefix . $key ] = $value;
			}
		}

		return $result;
	}

	/**
	 * @param array $data
	 * @param null  $base_index
	 *
	 * @return array
	 * @since 6.4.0
	 */
	public static function normalize( array $data, $base_index = null ): array {
		$result = array();

		// Get the first key to determine the number of elements
		$keys = array_keys( $data );
		if ( empty( $keys ) ) {
			return $result; // Return empty if no data
		}

		if ( ! $base_index ) {
			$base_index = $keys[0];
		}

		if ( count( $data[ $base_index ] ?? array() ) < 1 ) {
			return $result;
		}

		foreach ( array_keys( $data[ $base_index ] ) as $_key ) {
			$temp = array();
			foreach ( $keys as $key ) {
				$temp[ $key ] = $data[ $key ][ $_key ] ?? null; // Use null if index is missing
			}
			$result[] = $temp;
		}

		return $result;
	}
}
