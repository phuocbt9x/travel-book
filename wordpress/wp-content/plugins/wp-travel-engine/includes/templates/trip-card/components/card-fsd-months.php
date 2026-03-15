<?php
/**
 * @var int $trip_id
 * @var array $engine_settings
 * @var boolean $related_query
 * @var boolean $show_available_months
 * @var boolean $show_available_dates
 * @var boolean $show_related_available_months
 * @var boolean $show_related_available_dates
 */

$show_available_months = $related_query ? $show_related_available_months : $show_available_months;
$show_available_dates  = $related_query ? $show_related_available_dates : $show_available_dates;

if ( false === $fsds || ! $show_available_months || ! $has_date ) {
	return;
}

$availability_txt = apply_filters( 'wte_available_throughout_txt', __( 'Availability:', 'wp-travel-engine' ) );

$fsds                     = is_numeric( $fsds ) ? array() : $fsds;
$available_months         = array();
$available_dates_in_month = array();

foreach ( $fsds as $index => $fsd ) {
	$month                              = date_i18n( 'n', strtotime( $fsd['start_date'] ) );
	$available_months[ $month ]       ??= $index;
	$available_dates_in_month[ $month ] = ( $available_dates_in_month[ $month ] ?? 0 ) + 1;
}

?>

<div class="category-trip-aval-time">
	<div class="category-trip-avl-tip-inner-wrap new-layout">
	<span class="category-available-trip-text"> <?php echo esc_html( $availability_txt ); ?> </span>
	<ul class="category-available-months">
<?php

$current_month = date_i18n( 'n' );

foreach ( range( 1, 12 ) as $month_number ) {

	$timestamp   = strtotime( "2025-{$month_number}-01" );
	$month_label = esc_html( date_i18n( 'M', $timestamp ) );
	$month_value = (int) date_i18n( 'n', $timestamp );

	if ( $month_value < $current_month ) {
		echo '<li><a href="#" class="disabled">' . $month_label . '</a></li>';
		continue;
	}

	if ( empty( $available_months ) ) {
		echo '<li>' . $month_label . '</li>';
		continue;
	}

	$dates_attribute = '';
	$classname       = '';

	if ( isset( $available_dates_in_month[ $month_value ], $available_months[ $month_number ] ) ) {
		$dates_available = $available_dates_in_month[ $month_value ];

		if ( $dates_available && $show_available_dates ) {
			$dates_attribute = ' data-content="' . esc_attr(
				sprintf(
					'%d %s %s',
					$dates_available,
					_n( 'date', 'dates', $dates_available, 'wp-travel-engine' ),
					__( 'available', 'wp-travel-engine' )
				)
			) . '"';
			$classname       = 'wte-dates-available tippy-exist';
		} else {
			$classname = 'wte-dates-available';
		}
	}

	if ( isset( $available_months[ $month_number ] ) ) {
		$month_param = esc_html( $available_months[ $month_number ] );
		$month_url   = esc_url( get_the_permalink() ) . '?month=' . $month_param . '#wte-fixed-departure-dates';

		printf(
			'<li class="%1$s"%2$s><a href="%3$s">%4$s</a></li>',
			esc_attr( $classname ),
			$dates_attribute,
			$month_url,
			$month_label
		);

	} else {
		echo '<li><a href="#" class="disabled">' . $month_label . '</a></li>';
	}
}

?>
	</ul>
	</div>
</div>