<?php
/**
 * @var int $trip_id
 */

$tax_terms = get_the_terms( $trip_id, 'difficulty' );

if ( empty( $tax_terms ) || is_wp_error( $tax_terms ) ) {
	return;
}

foreach ( $tax_terms as $term ) : ?>
	<span class="category-trip-difficulty">
		<?php
		if ( isset( $term->term_id ) ) {
			$term_id           = $term->term_id;
			$difficulty_level  = get_option( 'difficulty_level_by_terms', array() );
			$terms             = get_terms(
				array(
					'taxonomy'   => 'difficulty',
					'hide_empty' => false,
				)
			);
			$difficulty_levels = '';
			foreach ( $difficulty_level as $level ) {
				if ( $term_id == $level['term_id'] ) :
					$difficulty_levels = sprintf( __( '<span>(%1$s/%2$d)</span>', 'wp-travel-engine' ), $level['level'], count( $terms ) );
				endif;
			}

			$term_thumbnail = (int) get_term_meta( $term_id, 'category-image-id', true );
			if ( isset( $term_thumbnail ) && $term_thumbnail != 0 ) {
				?>
				<i>
					<?php
					$term_thumbnail && print( \wp_get_attachment_image(
						$term_thumbnail,
						array( '16', '16' ),
						false,
						array( 'itemprop' => 'image' )
					) );
					?>
				</i>
				<?php
			} else {
				?>
				<i>
					<svg width="16" height="17" viewBox="0 0 16 17" fill="none" xmlns="http://www.w3.org/2000/svg">
						<path d="M14.6667 8.49998C14.6667 12.1819 11.6819 15.1666 8.00004 15.1666C4.31814 15.1666 1.33337 12.1819 1.33337 8.49998M14.6667 8.49998C14.6667 4.81808 11.6819 1.83331 8.00004 1.83331M14.6667 8.49998H13M1.33337 8.49998C1.33337 4.81808 4.31814 1.83331 8.00004 1.83331M1.33337 8.49998H3.00004M8.00004 1.83331V3.49998M12.719 3.83331L8.99998 7.49998M12.719 13.2189L12.5831 13.083C12.1219 12.6218 11.8912 12.3912 11.6221 12.2263C11.3835 12.0801 11.1234 11.9723 10.8513 11.907C10.5444 11.8333 10.2183 11.8333 9.56605 11.8333L6.43401 11.8333C5.78177 11.8333 5.45565 11.8333 5.14875 11.907C4.87666 11.9723 4.61654 12.0801 4.37795 12.2263C4.10884 12.3912 3.87824 12.6218 3.41704 13.083L3.28113 13.2189M3.28113 3.83331L4.43876 4.99095M9.33337 8.49998C9.33337 9.23636 8.73642 9.83331 8.00004 9.83331C7.26366 9.83331 6.66671 9.23636 6.66671 8.49998C6.66671 7.7636 7.26366 7.16665 8.00004 7.16665C8.73642 7.16665 9.33337 7.7636 9.33337 8.49998Z" stroke="currentColor" stroke-width="1.33" stroke-linecap="round" stroke-linejoin="round" />
					</svg>
				</i>
				<?php
			}
		}

		$difficulty_term_description = term_description( $term->term_id, 'difficulty' );
		// $difficulty_link             = get_term_link($term);
		$difficulty_name       = $term->name;
		$difficulty_span_class = ! empty( $difficulty_term_description ) ? 'wte-difficulty-content tippy-exist' : 'wte-difficulty-content';

		echo '<span class="' . esc_attr( $difficulty_span_class ) . '" data-content="' . esc_attr( wp_kses_post( $difficulty_term_description ) ) . '">' . esc_html( $difficulty_name ) . '</span>';
		?>
	</span>
<?php endforeach; ?>