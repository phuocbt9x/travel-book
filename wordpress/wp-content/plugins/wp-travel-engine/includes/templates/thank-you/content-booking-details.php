<?php
/**
 * @var array $traveller_details
 * @var string $trip_start_date
 * @var string $trip_end_date
 */
use WPTravelEngine\Helpers\Countries;
?>
<div class="wpte-thankyou__booking-details">
	<?php do_action( 'wptravelengine_thankyou_before_booking_details' ); ?>
	<div class="wpte-thankyou__date-block">
		<div class="wpte-thankyou__start-date">
			<span class="wpte-thankyou__date-label"><?php echo esc_html__( 'Starts on:', 'wp-travel-engine' ); ?></span>
			<div><strong><?php echo esc_html__( $trip_start_date, 'wp-travel-engine' ); ?></strong></div>
		</div>
		<span class="wpte-thankyou__arrow">
			<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
				<path d="M5 12H19M19 12L12 5M19 12L12 19" stroke="url(#paint0_linear_2338_6281)" stroke-width="2"
						stroke-linecap="round" stroke-linejoin="round" />
				<defs>
					<linearGradient
						id="paint0_linear_2338_6281" x1="12" y1="5" x2="12" y2="19"
						gradientUnits="userSpaceOnUse">
						<stop stop-color="#1FC0A1" />
						<stop stop-color="#1FC0A1" />
						<stop offset="1" stop-color="#00A89F" />
					</linearGradient>
				</defs>
			</svg>
		</span>
		<div class="wpte-thankyou__end-date">
			<span class="wpte-thankyou__date-label"><?php echo esc_html__( 'Ends on:', 'wp-travel-engine' ); ?></span>
			<div><strong><?php echo esc_html__( $trip_end_date, 'wp-travel-engine' ); ?></strong></div>
		</div>
	</div>
	<?php
	if ( is_array( $traveller_details ?? '' ) ) :
		foreach ( $traveller_details as $index => $traveller_detail ) :
			$traveller_label = sprintf( __( 'Traveller %1$d%2$s', 'wp-travel-engine' ), $index + 1, $index === 0 ? __( ' (Lead Traveller)', 'wp-travel-engine' ) : '' );
			?>
			<div class="wpte-thankyou__block">
				<div class="wpte-thankyou__block-title"><?php echo esc_html( $traveller_label ); ?></div>
				<div class="wpte-thankyou__block-content">
					<div class="wpte-thankyou__grid">
						<?php foreach ( $traveller_detail as $name => $field ) : ?>
							<?php
							if ( empty( $field['value'] ) ) {
								continue;}
							$countries_list = Countries::list();
							if ( $field['type'] == 'country_dropdown' ) {
								$field['value'] = $countries_list[ $field['value'] ];
							}
							?>
							<div>
								<span
									class="wpte-thankyou__label"><?php echo esc_html( $field['field_label'] ); ?></span>
									<strong><?php echo is_array( $field['value'] ) ? esc_html( implode( ', ', $field['value'] ) ) : esc_html( $field['value'] ?? '' ); ?></strong>
							</div>
						<?php endforeach; ?>
					</div>
				</div>
			</div>
			<?php
		endforeach;
endif;
	if ( is_array( $emergency_details ?? '' ) ) :
		foreach ( $emergency_details as $index => $emergency_detail ) :
			?>
				<div class="wpte-thankyou__block">
					<div class="wpte-thankyou__block-title"><?php echo esc_html( sprintf( __( 'Emergency Details %s', 'wp-travel-engine' ), count( $emergency_details ) > 1 ? $index + 1 : '' ) ); ?></div>
					<div class="wpte-thankyou__block-content">
						<div class="wpte-thankyou__grid">
						<?php foreach ( $emergency_detail as $name => $field ) : ?>
								<?php
								if ( empty( $field['value'] ) ) {
									continue;}
								$countries_list = Countries::list();
								if ( $field['type'] == 'country_dropdown' && ! empty( $field['value'] ) && isset( $countries_list[ $field['value'] ] ) ) {
									$field['value'] = $countries_list[ $field['value'] ];
								}
								?>
								<div>
									<span
										class="wpte-thankyou__label"><?php echo esc_html( $field['field_label'] ); ?></span>
										<strong><?php echo is_array( $field['value'] ) ? esc_html( implode( ', ', $field['value'] ) ) : esc_html( $field['value'] ?? '' ); ?></strong>
								</div>
							<?php endforeach; ?>
						</div>
					</div>
				</div>
			<?php
			endforeach;
		endif;
	if ( is_array( $booking_details ?? '' ) ) :
		foreach ( $booking_details as $index => $booking_detail ) :
			?>
				<div class="wpte-thankyou__block">
					<div class="wpte-thankyou__block-title"><?php echo esc_html__( 'Billing Details', 'wp-travel-engine' ); ?></div>
					<div class="wpte-thankyou__block-content">
						<div class="wpte-thankyou__grid">
						<?php foreach ( $booking_detail as $name => $field ) : ?>
								<?php
								if ( empty( $field['value'] ) ) {
									continue;}
								$countries_list = Countries::list();
								if ( $field['type'] == 'country_dropdown' && ! empty( $field['value'] ) && isset( $countries_list[ $field['value'] ] ) ) {
									$field['value'] = $countries_list[ $field['value'] ];
								}
								?>
								<div>
									<span
										class="wpte-thankyou__label"><?php echo esc_html( $field['field_label'] ); ?></span>
										<strong><?php echo is_array( $field['value'] ) ? esc_html( implode( ', ', $field['value'] ) ) : esc_html( $field['value'] ?? '' ); ?></strong>
								</div>
							<?php endforeach; ?>
						</div>
					</div>
				</div>
			<?php
			endforeach;
		endif;
	if ( ! empty( $additional_note ) ) :
		?>
		<div class="wpte-thankyou__block">
			<div class="wpte-thankyou__block-title"><?php echo __( 'Additional Notes:', 'wp-travel-engine' ); ?></div>
			<div class="wpte-thankyou__block-content">
				<div><?php echo wp_kses_post( $additional_note ); ?></div>
			</div>
		</div>
	<?php endif; ?>
	<?php do_action( 'wptravelengine_thankyou_after_booking_details' ); ?>
</div>
