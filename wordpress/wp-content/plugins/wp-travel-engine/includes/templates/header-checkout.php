<?php
/**
 * Checkout V2 Header Template.
 *
 * @package WPTravelEngine
 *
 * @since 6.1.3
 */
// Include header if enabled in settings
if ( $attributes['header'] == 'default' ) {
	get_header();
}
?>
<!doctype html>
<html <?php language_attributes(); ?>>
	<head>
		<meta charset="<?php bloginfo( 'charset' ); ?>">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="profile" href="http://gmpg.org/xfn/11">
		<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">

		<?php wp_head(); ?>
	</head>
<body <?php body_class(); ?>>

<?php wp_body_open(); ?>
	<div id="page">
		<div class="wpte-checkout">
			<?php
			wte_get_template( 'template-checkout/content-header.php' );
