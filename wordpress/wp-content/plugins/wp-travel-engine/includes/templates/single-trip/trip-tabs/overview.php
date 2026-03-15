<?php
/**
 * Overview Template
 *
 * This template can be overridden by copying it to yourtheme/wp-travel-engine/single-trip/trip-tabs/overview.php.
 *
 * @package Wp_Travel_Engine
 * @subpackage Wp_Travel_Engine/includes/templates
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
?>

<?php do_action( 'wte_before_overview_content' ); ?>

	<div class="post-data overview">
		<?php
		/**
		 * Hook - Display tab content title, left for themes.
		 */
		do_action( 'wte_overview_tab_title' );

		/**
		 * Hook - Display tab highlights, left for themes.
		 */
		do_action( 'wte_after_overview_tab_title' );
		?>
		<!-- Display Overview content -->
		<?php if ( ! empty( $overview ) ) : ?>
			<?php
			echo apply_filters( 'the_content', wp_kses( $overview, 'wptravelengine_post' ) );
			?>
		<?php endif; ?>
		<!-- ./ Display Overview content -->
	</div>

<?php
do_action( 'wte_after_overview_content' );
