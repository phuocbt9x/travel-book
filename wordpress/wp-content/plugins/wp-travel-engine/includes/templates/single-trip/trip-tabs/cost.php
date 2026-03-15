<?php
/**
 * Single Trip Cost Include/Exclude Template
 *
 * This template can be overridden by copying it to yourtheme/wp-travel-engine/single-trip/trip-tabs/cost.php.
 *
 * @package Wp_Travel_Engine
 * @subpackage Wp_Travel_Engine/includes/templates
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$include_title = ! empty( $cost['includes_title'] ?? '' ) ? $cost['includes_title'] : '';
$cost_includes = preg_split( '/\r\n|[\r\n]/', $cost['cost_includes'] ?? '' );
$exclude_title = ! empty( $cost['excludes_title'] ?? '' ) ? $cost['excludes_title'] : '';
$cost_excludes = preg_split( '/\r\n|[\r\n]/', $cost['cost_excludes'] ?? '' );

do_action( 'wte_before_cost_content' ); ?>

<div class="post-data cost">
	<?php
	/**
	 * Hook - Display tab content title, left for themes.
	 */
	do_action( 'wte_cost_tab_title' );
	?>
	<div class="content">
		<?php
		if ( ! empty( $include_title ) ) {
			echo '<h3>' . esc_attr( $include_title ) . '</h3>';
		}
		if ( ! empty( trim( $cost['cost_includes'] ?? '' ) ) ) :
			echo "<ul id='include-result'>";
			foreach ( $cost_includes as $include ) {
				echo '<li>' . esc_html( $include ) . '</li>';
			}
			echo '</ul>';
		endif;
		?>
	</div>
	<div class="content">
		<?php
		if ( ! empty( $exclude_title ) ) :
			echo '<h3>' . esc_attr( $exclude_title ) . '</h3>';
		endif;
		if ( ! empty( trim( $cost['cost_excludes'] ?? '' ) ) ) :
			echo "<ul id='exclude-result'>";
			foreach ( $cost_excludes as $exclude ) {
				echo '<li>' . esc_html( $exclude ) . '</li>';
			}
			echo '</ul>';
		endif;
		?>
	</div>
</div>

<?php
do_action( 'wte_after_cost_content' );
