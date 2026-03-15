<?php
/**
 * Payments Tab.
 *
 * @package WPTravelEngine
 * @since 6.2.0
 */

return array(
	'title'    => esc_html__( 'Payments', 'wp-travel-engine' ),
	'order'    => 25,
	'sub_tabs' => __DIR__ . '/payments',
	'icon'     => 'credit-card-check',
	'id'       => 'payments',
);
