<?php
/**
 * Template Name: Checkout Template
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Include header if enabled in settings
if ( $attributes['header'] == 'default' ) {
	get_header();
}

wp_head();
?>

<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head></head>
<body>
	<?php echo do_shortcode( '[WP_TRAVEL_ENGINE_PLACE_ORDER version="2.0"]' ); ?>
</body>

<?php
wp_footer();

// Include footer if enabled in settings
if ( $attributes['footer'] == 'default' ) {
	get_footer();
}
?>
