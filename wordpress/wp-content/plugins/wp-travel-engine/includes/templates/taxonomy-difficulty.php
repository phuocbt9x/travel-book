<?php
/**
 * The template for displaying trip difficulty taxonomy terms.
 * Simply includes the archive template
 *
 * This template can be overridden by copying it to yourtheme/wp-travel-engine/taxonomy-difficulty.php.
 *
 * @package Wp_Travel_Engine
 * @subpackage Wp_Travel_Engine/includes/templates
 * @since 6.6.5
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

wte_get_template( 'archive-trip.php' );
