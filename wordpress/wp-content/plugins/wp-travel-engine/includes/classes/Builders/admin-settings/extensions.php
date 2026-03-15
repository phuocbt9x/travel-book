<?php
/**
 * Extensions Tab.
 *
 * @package WPTravelEngine
 * @since 6.2.0
 */

return array(
	'title'    => esc_html__( 'Extensions', 'wp-travel-engine' ),
	'order'    => 35,
	'sub_tabs' => __DIR__ . '/extensions',
	'icon'     => 'puzzle-piece',
	'id'       => 'extensions',
);
