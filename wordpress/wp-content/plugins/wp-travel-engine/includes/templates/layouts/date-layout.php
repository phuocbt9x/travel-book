<div class="category-trip-dates">
<span class="trip-dates-title"><?php echo esc_html__( 'Next Departure', 'wp-travel-engine' ); ?></span>
<?php
if ( ! $is_fsds ) {
	$fsd  = date_create( 'M j' );
	$fsds = array(
		1 => date( 'Y-m-d', strtotime( ' + 1 day' ) ),
		2 => date( 'Y-m-d', strtotime( ' + 2 day' ) ),
		3 => date( 'Y-m-d', strtotime( ' + 3 day' ) ),
	);
}
$i = 0;
foreach ( $fsds as $fsd ) {
	if ( $is_fsds && --$list_count < 0 ) {
		break;
	}
	if ( $i <= 4 ) {
		?>
		<span class="category-trip-start-date">
			<span>
				<?php printf( '%1$s', wte_esc_price( wte_get_new_formated_date( $is_fsds ? ( $fsd['start_date'] ?? $fsd ) : $fsd ) ) ); ?>
			</span>
		</span>
		<?php
	}
	++$i;
}
?>
</div>
