<?php
/**
 * Content for Activities Block.
 *
 * @package wp-travel-engine/blocks
 */
if ( isset( $attributes ) ) {
	// print_r($attributes);
	$attributes['taxonomy'] = 'activities';
	include dirname( __DIR__ ) . '/terms/block.php';
}
