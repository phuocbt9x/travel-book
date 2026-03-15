<?php
/**
 * Render File for Fixed Starting Date block.
 *
 * @var string $wrapper_attributes
 * @var Attributes $attributes_parser
 * @var Render $render
 * @package Wp_Travel_Engine
 * @since 5.9
 */

use WPTravelEngine\Blocks\SampleData;

global $wtetrip;

$dates_data         = array();
$trip_duration_unit = 'days';
$today              = gmdate( 'Y-m-d' );
$globals_settings   = wptravelengine_settings()->get();
$pagination_num     = (int) $attributes_parser->get( 'noofRow' );
$date_format        = $attributes_parser->get( 'dateFormat' );
$custom_date_format = $attributes_parser->get( 'customDateFormat' );

if ( $render->is_editor() ) {
	$dates_data = SampleData::fsd();
} else {
	$trip_id            = $_GET['post'] ?? $post->ID;
	$trip_settings      = get_post_meta( $trip_id, 'wp_travel_engine_setting', true );
	$trip_duration_unit = isset( $trip_settings['trip_duration_unit'] ) ? $trip_settings['trip_duration_unit'] : 'days';
	if ( isset( $trip_id ) ) {
		$post_type = get_post_type( $trip_id );
	}
	if ( ! $trip_id ) {
		return;
	}
	if ( ! defined( 'WTE_FIXED_DEPARTURE_VERSION' ) ) {
		$sorted_fsd = array();
	} else {
		$sorted_fsd = call_user_func(
			array( new WTE_Fixed_Starting_Dates_Shortcodes(), 'generate_fsds' ),
			$trip_id,
			array(
				'year'  => '',
				'month' => '',
			)
		);
	}
	$dates_data = \WPTravelEngine\Blocks\Helpers::fsd_date_format( $sorted_fsd, $date_format, $custom_date_format );
}
?>
<div <?php echo esc_attr( $attributes_parser->wrapper_attributes() ); ?>>
	<div class="wte-fsd-frontend-holders" id="nestable1">
			<div class="dd-list outer" data-date-column="<?php echo esc_attr( $attributes['dateColumn'] ); ?>"
			data-availability-column="<?php echo esc_attr( $attributes['availabilityColumn'] ); ?>"
			data-price-column="<?php echo esc_attr( $attributes['priceColumn'] ); ?>" data-space-column="<?php echo esc_attr( $attributes['spaceColumn'] ); ?>"
			data-pagination-number="<?php echo esc_attr( $pagination_num ); ?>" data-start-date="<?php echo esc_attr( $attributes['startDate'] ); ?>"
			data-end-date="<?php echo esc_attr( $attributes['endDate'] ); ?>"
			data-fsd-count="<?php echo esc_attr( count( $dates_data ) ); ?>" data-date-format="<?php echo esc_attr( $date_format ); ?>" data-custom-date-format="<?php echo esc_attr( $custom_date_format ); ?>">
				<table>
					<thead>
						<tr>
							<?php
							if ( $attributes['dateColumn'] ) :
								?>
								<th><?php echo wp_kses_post( $attributes['dateLabel'] ); ?></th>
							<?php endif; ?>
							<?php if ( $attributes['availabilityColumn'] ) : ?>
								<th><?php echo wp_kses_post( $attributes['availabilityLabel'] ); ?></th>
							<?php endif; ?>
							<?php if ( $attributes['priceColumn'] ) : ?>
								<th><?php echo wp_kses_post( $attributes['priceLabel'] ); ?></th>
							<?php endif; ?>
							<?php if ( $attributes['spaceColumn'] ) : ?>
								<th><?php echo wp_kses_post( $attributes['spaceLabel'] ); ?></th>
							<?php endif; ?>
							<th></th>
						</tr>
					</thead>
					<tbody>
						<?php
						if ( ! empty( $dates_data ) ) :
							$counter = 0;
							?>
								<?php
								$limited_dates_data = array_slice( $dates_data, 0, $pagination_num );
								if ( ! empty( $limited_dates_data ) ) :
									$counter = 0;
									foreach ( $limited_dates_data as $key => $fsd ) :
										?>
										<tr style="display: table-row;">
											<?php if ( $attributes_parser->get( 'dateColumn' ) ) : ?>
												<td
													data-label="<?php esc_attr_e( 'TRIP DATES', 'wp-travel-engine' ); ?>"
													dates=""
													class="accordion-sdate"
													data-id="<?php echo esc_attr( $fsd['content_id'] ); ?>"
												>
														<?php
														if ( $attributes_parser->get( 'startDate' ) || $attributes_parser->get( 'endDate' ) ) :
															function_exists( 'wptravelengine_svg_by_fa_icon' ) ? wptravelengine_svg_by_fa_icon( 'far fa-calendar' ) : print( '<i class="fa fa-calendar"></i>' );
														endif;
														?>
														<?php if ( $attributes_parser->get( 'startDate' ) ) : ?>
														<span class="start-date" data-id="<?php echo esc_attr( $fsd['start_date'] ); ?>">
															<?php echo esc_html( $fsd['start_date'] ); ?>
														</span>
														<?php endif; ?>
														<?php if ( $attributes_parser->get( 'startDate' ) && $attributes_parser->get( 'endDate' ) ) : ?>
														-
														<?php endif; ?>
														<?php if ( $attributes_parser->get( 'endDate' ) ) : ?>
														<span class="end-date" data-id="<?php echo esc_attr( $fsd['end_date'] ); ?>">
															<?php echo esc_html( $fsd['end_date'] ); ?>
														</span>
														<?php endif; ?>
												</td>
												<?php
											endif;
											if ( $attributes_parser->get( 'availabilityColumn' ) ) :
												?>
												<td
												data-label="<?php esc_attr_e( 'AVAILABILITY', 'wp-travel-engine' ); ?>"
												class="accordion-availability"
												data-id="<?php echo esc_attr( $fsd['availability'] ); ?>"
												><span class="<?php echo esc_attr( $fsd['availability'] ); ?>"><?php echo esc_html( $fsd['availability'] ); ?></span></td>
												<?php
											endif;
											if ( $attributes_parser->get( 'priceColumn' ) ) :
												?>
												<td
													data-label="<?php echo esc_attr_e( 'PRICE', 'wp-travel-engine' ); ?>"
													class="accordion-cost"
												>
													<span class="currency-code">
													<?php function_exists( 'wptravelengine_svg_by_fa_icon' ) ? wptravelengine_svg_by_fa_icon( 'fas fa-tag' ) : print( '<i class="fa fa-tag"></i>' ); ?>
													</span>
													<strong
														class="trip-cost-holder"><?php echo esc_html( wte_get_formated_price( $fsd['price'] ) ); ?></strong>
												</td>
												<?php
											endif;
											if ( $attributes_parser->get( 'spaceColumn' ) ) :
												?>
												<td
													data-label="<?php esc_attr_e( 'SPACE LEFT', 'wp-travel-engine' ); ?>"
													class="accordion-seats"
													data-id="<?php echo esc_attr( $fsd['space'] ); ?>"
												>
													<div class="seats-available">
														<?php
														if ( ( $fsd['space'] === '' ) || ( (int) $fsd['space'] > 0 ) ) :
															?>
															<?php function_exists( 'wptravelengine_svg_by_fa_icon' ) ? wptravelengine_svg_by_fa_icon( 'fas fa-user' ) : print( '<i class="fa fa-user"></i>' ); ?>
															<span class="seats"><?php echo esc_html( sprintf( __( '%1$s Available', 'wp-travel-engine' ), $fsd['space'] ) ); ?></span>
															<?php
															else :
																echo '<span class="sold-out">' . esc_html( __( 'sold out', 'wp-travel-engine' ) ) . '</span>';
															endif;
															?>
													</div>
												</td>
												<?php
											endif;
											if ( ( 0 < $fsd['space'] || '' === $fsd['space'] ) && $wtetrip ) :
												?>
												<td
													data-label=""
													data-cost="<?php echo esc_attr( $fsd['price'] ); ?>"
													class="accordion-book"
													data-id="<?php echo esc_attr( $fsd['content_id'] ); ?>"
												>
													<?php if ( WP_TRAVEL_ENGINE_POST_TYPE === $wtetrip->post->post_type ) : ?>
														<button
														data-info="<?php echo esc_attr( strtotime( $fsd['start_date'] ) ); ?>"
														class="book-btn wte-fsd-list-booknow-btn"
														><?php echo wp_kses_post( $attributes_parser->get( 'bookingLabel' ) ); ?>
														</button>
													<?php endif; ?>
												</td>
											<?php endif; ?>
										</tr>
										<?php
										++$counter;
									endforeach;
								else :
									// Handle the case where no dates are available
									?>
									<tr style="display: table-row;">
										<td colspan="5"><?php echo esc_html__( 'No Fixed Departure Dates available.', 'wp-travel-engine' ); ?></td>
									</tr>
									<?php
								endif;
								foreach ( $dates_data as $key => $fsd ) :
									$no_of_row = count( $dates_data );
									if ( $render->is_editor() ) {
										$no_of_row = 2;
									}
									if ( $counter >= $no_of_row ) {
										break;
									}
									++$counter;
							endforeach;
								?>
						<?php else : ?>
							<tr style="display: table-row;">
								<td colspan="5"><?php echo esc_html__( 'No Fixed Departure Dates available.', 'wp-travel-engine' ); ?></td>
							</tr>
						<?php endif; ?>
					</tbody>
				</table>
			</div>
	</div>
</div>
