<?php
/**
 * @var \WPTravelEngine\Core\Models\Post\Trip $trip_instance
 * @var array $fsds
 * @var boolean $related_query
 * @var boolean $show_related_date_layout
 * @var boolean $show_date_layout
 */

$show_date_layout = $related_query ? $show_related_date_layout : $show_date_layout;

if ( ! $trip_instance->has_package() || ! $has_date || ! $show_date_layout || false === $fsds ) {
	return;
}

if ( is_numeric( $fsds ) || empty( $fsds ) ) {
	$start_date = $trip_instance->plain_start_date;

	$fsds = array(
		$start_date,
		wp_date( 'Y-m-d', strtotime( "{$start_date} +1 day" ) ),
		wp_date( 'Y-m-d', strtotime( "{$start_date} +2 days" ) ),
	);
}

$i          = 0;
$content    = '';
$list_count = intval( $engine_settings['trip_dates']['number'] ?? 3 );
foreach ( $fsds as $fsd ) :

	$seats_left = $fsd['seats_left'] ?? '';

	if ( '' !== $seats_left && 0 >= $seats_left && $i < $list_count ) {
		continue;
	}

	$content .= '<span class="category-trip-start-date"><span>';
	$content .= wte_get_new_formated_date( $fsd['start_date'] ?? $fsd, get_option( 'date_format' ) );

	if ( empty( $seats_left ) ) {
		$content .= ' <em>(' . esc_html__( 'Available', 'wp-travel-engine' ) . ')</em>';
	} else {
		$content .= ' <em>(' . $seats_left . ' ' . _n( 'Seat Available', 'Seats Available', $seats_left, 'wp-travel-engine' ) . ')</em>';
	}

	$content .= '</span></span>';

	if ( ++$i >= $list_count ) {
		break;
	}

endforeach;

if ( empty( $content ) ) {
	return;
}

?>

<div class="category-trip-dates">
	<span class="trip-dates-title"><?php echo esc_html__( 'Next Departures', 'wp-travel-engine' ); ?></span>
	<?php echo $content; ?>
</div>
