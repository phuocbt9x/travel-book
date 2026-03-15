<?php
/**
 * Dashboard Tab.
 *
 * @package WPTravelEngine
 * @since 6.2.0
 */

return array(
	'title'    => esc_html__( 'Dashboard', 'wp-travel-engine' ),
	'order'    => 30,
	'sub_tabs' => __DIR__ . '/dashboard',
	'icon'     => 'bar-chart',
	'id'       => 'dashboard',
);
