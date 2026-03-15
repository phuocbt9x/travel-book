<?php
if ( false !== $fsds ) {
	if ( ( $fsds == get_the_ID() || empty( $fsds ) ) ) {
		?>
			<div class="category-trip-avl-tip-inner-wrap<?php echo ( ! $display_available_months ) ? '' : ' new-layout'; ?>">
			<?php echo '<span class="category-available-trip-text"> ' . __( 'Available through out the year:', 'wp-travel-engine' ) . '</span>'; ?>
			<?php if ( $display_available_months ) : ?>
					<i>
						<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
							<path d="M10.95 18.35L7.4 14.8L8.85 13.35L10.95 15.45L15.15 11.25L16.6 12.7L10.95 18.35ZM5 22C4.45 22 3.979 21.8043 3.587 21.413C3.19567 21.021 3 20.55 3 20V6C3 5.45 3.19567 4.97933 3.587 4.588C3.979 4.196 4.45 4 5 4H6V2H8V4H16V2H18V4H19C19.55 4 20.021 4.196 20.413 4.588C20.8043 4.97933 21 5.45 21 6V20C21 20.55 20.8043 21.021 20.413 21.413C20.021 21.8043 19.55 22 19 22H5ZM5 20H19V10H5V20Z" fill="currentColor" />
						</svg>
					</i>
				<?php endif; ?>
				<ul class="category-available-months">
				<?php foreach ( range( 1, 12 ) as $month_number ) : ?>
						<li><?php echo date_i18n( 'M', strtotime( "2021-{$month_number}-01" ) ); ?></li>
				<?php endforeach; ?>
				</ul>
			</div>
			<?php
	} elseif ( is_array( $fsds ) && count( $fsds ) > 0 ) {
		switch ( $dates_layout ) {
			case 'months_list':
				$months_list = $trip_instance->fsds_content( $fsds, 'months', $related_trip ? $show_related_available_dates : $show_available_dates );
				if ( empty( $months_list['available_months'] ) ) {
					echo '<ul class="category-available-months">';
					foreach ( range( 1, 12 ) as $month_number ) :
						echo '<li>' . date_i18n( 'n-M', strtotime( "2021-{$month_number}-01" ) ) . '</li>';
						endforeach;
					echo '</ul>';
					break;
				}
				?>
						<div class="category-trip-avl-tip-inner-wrap<?php echo ( ! $display_available_months ) ? '' : ' new-layout'; ?>">
						<?php
							echo '<span class="category-available-trip-text"> ' . esc_html( $months_list['available_throughout'] ) . '</span>';
						if ( $display_available_months ) :
							?>
								<i>
									<svg width="24" height="24" viewBox="0 0 24 24" fill="none"
										xmlns="http://www.w3.org/2000/svg">
									<path d="M10.95 18.35L7.4 14.8L8.85 13.35L10.95 15.45L15.15 11.25L16.6 12.7L10.95 18.35ZM5 22C4.45 22 3.979 21.8043 3.587 21.413C3.19567 21.021 3 20.55 3 20V6C3 5.45 3.19567 4.97933 3.587 4.588C3.979 4.196 4.45 4 5 4H6V2H8V4H16V2H18V4H19C19.55 4 20.021 4.196 20.413 4.588C20.8043 4.97933 21 5.45 21 6V20C21 20.55 20.8043 21.021 20.413 21.413C20.021 21.8043 19.55 22 19 22H5ZM5 20H19V10H5V20Z" fill="currentColor" />
								</svg>
							</i>
							<?php
							endif;
						echo '<ul class="category-available-months">';
						foreach ( range( 1, 12 ) as $month_number ) {
							isset( $months_list['available_months'][ $month_number ] ) ? printf( '<li class="' . $months_list['classname'] . '"' . wp_kses_post( $months_list['dates_attribute'] ) . '><a href="%1$s">%2$s</a></li>', esc_url( get_the_permalink() ) . '?month=' . esc_html( $months_list['available_months'][ $month_number ] ) . '#wte-fixed-departure-dates', date_i18n( 'M', strtotime( "2021-{$month_number}-01" ) ) ) : printf( '<li><a href="#" class="disabled">%1$s</a></li>', date_i18n( 'M', strtotime( "2021-{$month_number}-01" ) ) );
						}
						echo '</ul>' . 'list' === $view_mode ? '' : '</div>';
						?>
					<?php
				break;
			case 'dates_list':
				$dates_list = $trip_instance->fsds_content( $fsds, 'dates', $related_trip ? $show_related_available_dates : $show_available_dates );
				echo '<div class="next-trip-info' . ( 'list' === $view_mode ? ' date' : '' ) . '">';
				printf( '<div class="fsd-title">%1$s</div>', esc_html__( 'Next Departure', 'wp-travel-engine' ) );
				echo '<ul class="next-departure-list">';
				foreach ( $fsds as $fsd ) {
					if ( --$dates_list['list_count'] < 0 ) {
						break;
					}
					printf( '<li><span class="left">%1$s %2$s</span><span class="right">%3$s</span></li>', $dates_list['icon'], wte_esc_price( wte_get_formated_date( $fsd['start_date'] ) ), sprintf( __( '%s Available', 'wp-travel-engine' ), (float) $fsd['seats_left'] ) );
				}
				echo '</ul>' . 'list' === $view_mode ? '' : '</div>';
				break;
			default:
				break;
		}
	}
}
if ( $trip_listing && $show_excerpt && 'list' === $view_mode ) :
	echo '<a href="' . esc_url( get_the_permalink() ) . '" class="button category-trip-viewmre-btn">' . esc_html( apply_filters( 'wp_travel_engine_view_detail_txt', __( 'View Trip', 'wp-travel-engine' ) ) ) . '</a>';
	elseif ( 'list' === $view_mode ) :
		echo '<a href="' . esc_url( get_the_permalink() ) . '" class="button category-trip-viewmre-btn">' . esc_html( apply_filters( 'wp_travel_engine_view_detail_txt', __( 'View Details', 'wp-travel-engine' ) ) ) . '</a>';
	endif;
