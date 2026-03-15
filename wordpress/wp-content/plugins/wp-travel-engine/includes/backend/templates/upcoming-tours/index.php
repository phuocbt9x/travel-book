<?php
/**
 * Upcoming Tours Content.
 *
 * @package wp-travel-engine
 * @since 6.4.3
 *
 * @var array $dates
 * @var array $trips
 * @var bool $show_more_btn
 * @var bool $show_less_btn
 * @var int $count
 */
?>

<div class="wpte-upcoming-tours">
	<!-- Header -->
	<?php wptravelengine_get_admin_template( 'upcoming-tours/partials/header.php' ); ?>
	<!-- Content -->
	<div class="wpte-upcoming-tours-content-wrap">
		<?php wptravelengine_get_admin_template( 'upcoming-tours/partials/content.php' ); ?>
	</div>
</div>