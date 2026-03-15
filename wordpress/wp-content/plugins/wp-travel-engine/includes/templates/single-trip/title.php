<?php
/**
 * Single Trip header
 *
 * This template can be overridden by copying it to yourtheme/wp-travel-engine/single-trip/title.php.
 *
 * @package Wp_Travel_Engine
 * @subpackage Wp_Travel_Engine/includes/templates
 * @since @release-version //TODO: change after travel muni is live
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>
<header class="entry-header<?php echo ( 'days_and_nights' === $trip_duration_format && ! empty( $nights ) && 'hours' !== $duration_unit ) ? ' has-night' : ''; ?>">
	<h1 class="entry-title" itemprop="name">
		<?php the_title(); ?>
	</h1>
	<?php if ( ! empty( $duration ) ) { ?>
		<!-- Display duration -->
		<span class="wte-title-duration">
			<span class="duration">
				<?php echo esc_html( number_format_i18n( $duration ) ); ?>
			</span>
			<span class="days">
				<?php
				if ( 'days' === $duration_unit ) {
					printf( esc_html( _nx( 'Day', 'Days', $duration, 'days', 'wp-travel-engine' ) ) );
				}
				if ( 'hours' === $duration_unit ) {
					printf( esc_html( _nx( 'Hour', 'Hours', $duration, 'hours', 'wp-travel-engine' ) ) );
				}
				?>
			</span>
		</span>
	<?php } ?>

	<?php if ( 'days_and_nights' === $trip_duration_format && ! empty( $nights ) && 'hours' !== $duration_unit ) { ?>
		<span class="wte-title-duration wte-duration-night">
			<span class="duration">
				<?php echo esc_html( number_format_i18n( $nights ) ); ?>
			</span>
			<span class="days">
				<?php printf( esc_html( _nx( 'Night', 'Nights', $nights, 'nights', 'wp-travel-engine' ) ) ); ?>
			</span>
		</span>
	<?php } ?>

	<!-- ./ Display duraiton -->
	<?php do_action( 'wp_travel_engine_header_hook' ); ?>
</header>
<!-- ./entry-header -->
<?php
/* Omit closing PHP tag at the end of PHP files to avoid "headers already sent" issues. */
