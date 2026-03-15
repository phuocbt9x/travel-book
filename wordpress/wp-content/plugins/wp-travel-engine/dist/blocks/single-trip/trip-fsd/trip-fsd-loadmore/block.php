<?php
/**
 * Render File for Fixed Starting Date block.
 *
 * @var string $wrapper_attributes
 * @var Attributes $attributes_parser
 * @var Render $render
 * @package Wp_Travel_Engine
 * @since 5.9
 */

global $wtetrip;
$trip_id = $_GET[ 'post' ] ?? $post->ID;
if ( defined( 'WTE_FIXED_DEPARTURE_VERSION' ) && version_compare( WTE_FIXED_DEPARTURE_VERSION, '2.4.0', '<' ) )  {
	?>
	<div <?php echo esc_attr($attributes_parser->wrapper_attributes()); ?> style="display: flex;">
		<button data-current_page='1' data-trip-id="<?= esc_attr($trip_id) ?>" class="load-more <?= esc_attr($attributes['alignment']) ?>"><?= wp_kses_post($attributes['loadMore']) ?></button>
		<button data-current_page='1' data-trip-id="<?= esc_attr($trip_id) ?>" style="display:none;" class="show-less"><?= wp_kses_post($attributes['showLess']) ?></button>
	</div>
<?php
}
?>
