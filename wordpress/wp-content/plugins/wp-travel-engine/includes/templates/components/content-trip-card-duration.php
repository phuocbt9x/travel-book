<?php

/**
 * Trip Card - Duration Component.
 *
 * @since 5.5.4
 */

$trip_duration_unit   ??= 'days';
$trip_duration        ??= 0;
$trip_duration_nights ??= 0;
$set_duration_type    ??= 'both';
$is_block_layout      ??= false;
$is_featured_widget   ??= false;
$is_booking_detail    ??= false;
$duration_icon = '<i>
			<svg width="25" height="25" viewBox="0 0 25 25" fill="none" xmlns="http://www.w3.org/2000/svg">
				<path d="M21.0122 12C21.0122 7.02944 16.9828 3 12.0122 3C7.04164 3 3.01221 7.02944 3.01221 12C3.01221 16.9706 7.04164 21 12.0122 21C16.9828 21 21.0122 16.9706 21.0122 12ZM11.0122 6C11.0122 5.44772 11.4599 5 12.0122 5C12.5645 5 13.0122 5.44772 13.0122 6V11.3818L16.4595 13.1055C16.9535 13.3525 17.1537 13.9533 16.9067 14.4473C16.6597 14.9412 16.0589 15.1415 15.5649 14.8945L11.5649 12.8945C11.2262 12.7251 11.0122 12.3788 11.0122 12V6ZM23.0122 12C23.0122 18.0751 18.0873 23 12.0122 23C5.93707 23 1.01221 18.0751 1.01221 12C1.01221 5.92487 5.93707 1 12.0122 1C18.0873 1 23.0122 5.92487 23.0122 12Z" fill="currentColor"/>
			</svg>
		</i>';

global $post;

$duration_label = wptravelengine_get_trip_duration_arr( $trip_instance ?? $post, $set_duration_type );

if ( empty( $duration_label ) ) {
	return;
}

if ( $is_block_layout ) {
	?>
	<span class="wpte-trip-meta wpte-trip-duration">
		<?php echo wte_esc_svg( $duration_icon ); ?>
		<span>
			<?php echo esc_html( implode( ' - ', $duration_label ) ); ?>
		</span>
	</span>
	<?php
} elseif ( $is_featured_widget ) {
	?>
	<span class="category-trip-dur">
		<?php echo wte_esc_svg( $duration_icon ); ?>
		<?php echo esc_html( implode( ' - ', $duration_label ) ); ?>
	</span>
	<?php
} elseif ( $is_booking_detail ) {
	echo esc_html( implode( ' - ', $duration_label ) );
} else {
	?>
	<span class="category-trip-dur">
		<?php echo wte_esc_svg( $duration_icon ); ?>
		<span>
			<?php echo esc_html( implode( ' - ', $duration_label ) ); ?>
		</span>
	</span>
	<?php
}
