<?php
/**
 * Trip Card Body
 *
 * @var array $engine_settings
 * @var boolean $related_query
 * @var string $view_mode
 * @var string $destination
 * @var string $pax
 * @var int $j
 */

$show_trip_tags      = $related_query ? $show_related_trip_tags : $show_trip_tags;
$show_excerpt        = $related_query ? $show_related_excerpt : $show_excerpt;
$show_difficulty_tax = $related_query ? $show_related_difficulty_tax : $show_difficulty_tax;

$see_more = __( 'See more details', 'wp-travel-engine' );
$see_less = __( 'See less details', 'wp-travel-engine' );

?>
<div class="category-trip-prc-title-wrap">
	<h2 class="category-trip-title" itemprop="name">
		<a itemprop="url" href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
	</h2>
	<?php
		echo empty( $j ) ? '' : "<meta itemprop='position' content='" . esc_attr( $j ) . "' />";
		wptravelengine_get_template( 'trip-card/components/card-avg-rating.php' );
	?>
	<?php if ( 'grid' === $view_mode ) : ?>
		<button type="button" class="wpte-details-toggler-button" data-see-more="<?php echo esc_attr( $see_more ); ?>" data-see-less="<?php echo esc_attr( $see_less ); ?>">
			<?php echo esc_html( $see_more ); ?>
		</button>
	<?php endif; ?>
</div>

<div class="category-trip-prc-wrap">
	<?php
	if ( $show_trip_tags ) {
		wptravelengine_get_template( 'trip-card/components/card-tags.php' );
	}
	if ( $show_excerpt ) {
		echo '<div class="category-trip-desc">';
			wptravelengine_the_trip_excerpt();
		echo '</div>';
	}
	?>
	<div class="category-trip-desti">
		<?php
		if ( ! empty( $destination ) ) {
			wptravelengine_get_template( 'trip-card/components/card-destination.php' );
		}
		if ( $show_difficulty_tax ) {
			wptravelengine_get_template( 'trip-card/components/card-difficulty.php' );
		}
		wptravelengine_get_template( 'trip-card/components/card-pax.php' );
		?>
	</div>
</div>
<?php
