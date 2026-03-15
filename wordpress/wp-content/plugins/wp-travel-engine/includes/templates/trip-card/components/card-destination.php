<?php
/**
 * @var int $trip_id
 */

$destinations = wte_get_the_tax_term_list( $trip_id, 'destination', '', ', ', '' );

if ( empty( $destinations ) || is_wp_error( $destinations ) ) {
	return;
}

?>
<span class="category-trip-loc">
	<i>
		<svg width="16" height="17" viewBox="0 0 16 17" fill="none" xmlns="http://www.w3.org/2000/svg">
			<path d="M7.99996 8.83337C9.10453 8.83337 9.99996 7.93794 9.99996 6.83337C9.99996 5.7288 9.10453 4.83337 7.99996 4.83337C6.89539 4.83337 5.99996 5.7288 5.99996 6.83337C5.99996 7.93794 6.89539 8.83337 7.99996 8.83337Z" stroke="currentColor" stroke-width="1.33333" stroke-linecap="round" stroke-linejoin="round" />
			<path d="M7.99996 15.1667C9.33329 12.5 13.3333 10.7789 13.3333 7.16671C13.3333 4.22119 10.9455 1.83337 7.99996 1.83337C5.05444 1.83337 2.66663 4.22119 2.66663 7.16671C2.66663 10.7789 6.66663 12.5 7.99996 15.1667Z" stroke="currentColor" stroke-width="1.33333" stroke-linecap="round" stroke-linejoin="round" />
		</svg>
	</i>
	<span><?php echo wp_kses_post( $destinations ); ?></span>
</span>
