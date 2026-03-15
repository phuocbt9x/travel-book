<?php
/**
 * Multidimensional ArrayAccess
 *
 * Allows ArrayAccess-like functionality with multidimensional arrays.  Fully supports
 * both sets and unsets.
 *
 * @package WordPress
 * @subpackage Session
 * @since 3.7.0
 */

namespace WPTravelEngine\Utilities;

use ArrayAccess;
use Countable;
use Iterator;

/**
 * Recursive array class to allow multidimensional array access.
 *
 * @package WordPress
 * @since 3.7.0
 */
class RecursiveArrayAccess implements ArrayAccess, Iterator, Countable {
	/**
	 * Internal data collection.
	 *
	 * @var array
	 */
	protected array $container = array();

	/**
	 * Flag whether the internal collection has been changed.
	 *
	 * @var bool
	 */
	protected bool $dirty = false;

	/**
	 * Default object constructor.
	 *
	 * @param array $data
	 */
	protected function __construct( $data = array() ) {
		foreach ( $data as $key => $value ) {
			$this[ $key ] = $value;
		}
	}

	/**
	 * Allow deep copies of objects
	 */
	public function __clone() {
		foreach ( $this->container as $key => $value ) {
			if ( $value instanceof self ) {
				$this[ $key ] = clone $value;
			}
		}
	}

	/**
	 * Output the data container as a multidimensional array.
	 *
	 * @return array
	 */
	public function toArray(): array {
		$data = $this->container;
		foreach ( $data as $key => $value ) {
			if ( $value instanceof self ) {
				$data[ $key ] = $value->toArray();
			}
		}

		return $data;
	}

	/*****************************************************************/
	/*                   ArrayAccess Implementation                  */
	/*****************************************************************/

	/**
	 * Whether a offset exists
	 *
	 * @link https://php.net/manual/en/arrayaccess.offsetexists.php
	 *
	 * @param string $offset An offset to check for.
	 *
	 * @return boolean true on success or false on failure.
	 */
	#[\ReturnTypeWillChange]
	public function offsetExists( $offset ): bool {
		return isset( $this->container[ $offset ] );
	}

	/**
	 * Offset to retrieve
	 *
	 * @link https://php.net/manual/en/arrayaccess.offsetget.php
	 *
	 * @param mixed $offset The offset to retrieve.
	 *
	 * @return mixed Can return all value types.
	 */
	#[\ReturnTypeWillChange]
	public function offsetGet( $offset ) {
		return isset( $this->container[ $offset ] ) ? $this->container[ $offset ] : null;
	}

	/**
	 * Offset to set
	 *
	 * @link https://php.net/manual/en/arrayaccess.offsetset.php
	 *
	 * @param mixed $offset The offset to assign the value to.
	 * @param mixed $value The value to set.
	 *
	 * @return void
	 */
	#[\ReturnTypeWillChange]
	public function offsetSet( $offset, $data ) {
		if ( is_array( $data ) ) {
			$data = new self( $data );
		}
		if ( $offset === null ) { // don't forget this!
			$this->container[] = $data;
		} else {
			$this->container[ $offset ] = $data;
		}

		$this->dirty = true;
	}

	/**
	 * Offset to unset
	 *
	 * @link https://php.net/manual/en/arrayaccess.offsetunset.php
	 *
	 * @param mixed $offset The offset to unset.
	 *
	 * @return void
	 */
	#[\ReturnTypeWillChange]
	public function offsetUnset( $offset ) {
		unset( $this->container[ $offset ] );

		$this->dirty = true;
	}


	/*****************************************************************/
	/*                     Iterator Implementation                   */
	/*****************************************************************/

	/**
	 * Current position of the array.
	 *
	 * @link https://php.net/manual/en/iterator.current.php
	 *
	 * @return mixed
	 */
	#[\ReturnTypeWillChange]
	public function current() {
		return current( $this->container );
	}

	/**
	 * Key of the current element.
	 *
	 * @link https://php.net/manual/en/iterator.key.php
	 *
	 * @return mixed
	 */
	#[\ReturnTypeWillChange]
	public function key() {
		return key( $this->container );
	}

	/**
	 * Move the internal point of the container array to the next item
	 *
	 * @link https://php.net/manual/en/iterator.next.php
	 *
	 * @return void
	 */
	#[\ReturnTypeWillChange]
	public function next() {
		next( $this->container );
	}

	/**
	 * Rewind the internal point of the container array.
	 *
	 * @link https://php.net/manual/en/iterator.rewind.php
	 *
	 * @return void
	 */
	#[\ReturnTypeWillChange]
	public function rewind() {
		reset( $this->container );
	}

	/**
	 * Is the current key valid?
	 *
	 * @link https://php.net/manual/en/iterator.rewind.php
	 *
	 * @return bool
	 */
	#[\ReturnTypeWillChange]
	public function valid() {
		return $this->offsetExists( $this->key() );
	}

	/*****************************************************************/
	/*                    Countable Implementation                   */
	/*****************************************************************/

	/**
	 * Get the count of elements in the container array.
	 *
	 * @link https://php.net/manual/en/countable.count.php
	 *
	 * @return int
	 */
	#[\ReturnTypeWillChange]
	public function count() {
		return count( $this->container );
	}
}
