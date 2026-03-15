<?php

/**
 * Trip Banner multi-layout template.
 *
 * This template can be overridden by copying it to yourtheme/wp-travel-engine/single-trip/multi-banners.php.
 *
 * @package Wp_Travel_Engine
 * @subpackage Wp_Travel_Engine/includes/templates
 * @since 6.3.3
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

?>
	<div class="wpte-gallery-wrapper__multi-banners">
		<?php
		wptravelengine_get_template( 'single-trip/banner-layouts/base.php' );
		?>
	</div>