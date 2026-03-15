<?php
/**
 * @var \WPTravelEngine\Core\Models\Post\Trip $trip_instance
 */

$is_enabled = $trip_instance->is_enabled_min_max_participants();

if ( ! $is_enabled ) {
	return;
}

$pax = array();

if ( ! empty( $trip_instance->get_minimum_participants() ) ) {
	$pax[] = $trip_instance->get_minimum_participants();
}

if ( ! empty( $trip_instance->get_maximum_participants() ) ) {
	$pax[] = $trip_instance->get_maximum_participants();
}

if ( empty( $pax ) ) {
	return;
}
$pax_label = wptravelengine_get_label_by_slug( 'person', end( $pax ) );
?>
<span class="category-trip-pax">
	<i>
		<svg width="16" height="17" viewBox="0 0 16 17" fill="none" xmlns="http://www.w3.org/2000/svg">
			<path d="M10.6667 2.81184C11.6545 3.30274 12.3334 4.32209 12.3334 5.5C12.3334 6.67791 11.6545 7.69726 10.6667 8.18816M12 11.6776C13.0077 12.1336 13.9151 12.8767 14.6667 13.8333M1.33337 13.8333C2.63103 12.1817 4.39283 11.1667 6.33337 11.1667C8.27392 11.1667 10.0357 12.1817 11.3334 13.8333M9.33337 5.5C9.33337 7.15685 7.99023 8.5 6.33337 8.5C4.67652 8.5 3.33337 7.15685 3.33337 5.5C3.33337 3.84315 4.67652 2.5 6.33337 2.5C7.99023 2.5 9.33337 3.84315 9.33337 5.5Z" stroke="currentColor" stroke-width="1.33333" stroke-linecap="round" stroke-linejoin="round" />
		</svg>
	</i>
	<span><?php echo esc_html( implode( '-', $pax ) . ' ' . $pax_label ); ?></span>
</span>
