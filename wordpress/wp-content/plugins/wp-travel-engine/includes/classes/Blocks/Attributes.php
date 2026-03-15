<?php
/**
 * Attributes Parser for Blocks.
 *
 * @since 5.8.3
 */

namespace WPTravelEngine\Blocks;

use WPTravelEngine\Blocks\Helpers;

/**
 * Class Attributes
 *
 * @package WPTravelEngine\Block
 * @since 5.8.3
 */
class Attributes {

	/**
	 * Attributes.
	 *
	 * @var array attribute name.
	 */
	protected array $attributes = array();
	protected string $block_id;

	/**
	 * Constructor.
	 *
	 * @param array $attributes Attributes.
	 */
	public function __construct( array $attributes ) {
		$this->attributes = $attributes;
		$this->set_block_id();
	}

	/**
	 * Get an attribute value.
	 *
	 * @param string|null  $key The attribute key.
	 * @param mixed|string $default The default value to return if the attribute does not exist.
	 *
	 * @return mixed The attribute value, or the default value if the attribute does not exist.
	 */
	public function get( string $key = null, $default = '' ) {

		if ( is_null( $key ) ) {
			return $this->attributes;
		}

		$keys       = explode( '.', $key );
		$attributes = $this->attributes;
		foreach ( $keys as $_key ) {
			if ( isset( $attributes[ $_key ] ) ) {
				$attributes = $attributes[ $_key ];
			} else {
				$attributes = $default;
			}
		}

		return $attributes;
	}

	public function wrapper_attributes( $extra_attributes = array() ) {
		echo wp_kses_post( get_block_wrapper_attributes( array_merge( array( 'class' => $this->block_id() ), $extra_attributes ) ) );
	}

	public function print_styles() {
		$styles_parser = new Styles();

		$styles_parser->parse( $this->attributes );

		$styles_parser->print( $this->block_id() );
	}

	protected function set_block_id() {
		$this->block_id = Helpers::unique_id( 'wptravelenginetripblocks-' );
	}

	public function block_id(): string {
		return $this->block_id;
	}
}
