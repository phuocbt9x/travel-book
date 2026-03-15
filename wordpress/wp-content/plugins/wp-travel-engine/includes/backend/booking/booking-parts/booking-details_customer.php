<?php

/**
 * Customer Details parts.
 */
global $post;

$booking_details = new \stdClass();

/** @var array $_args */
extract( $_args );

$additional_note = get_post_meta( $post->ID, 'wptravelengine_additional_note', true );
$billing_details = get_post_meta( $post->ID, 'wptravelengine_billing_details', true );
$billing_info    = $booking_details->billing_info;

$additional_fields = get_post_meta( $post->ID, 'additional_fields', ! 0 );
$additional_fields = is_array( $additional_fields ) ? $additional_fields : array();
?>
<div class="wpte-block wpte-col3">
	<div class="wpte-title-wrap">
		<h4 class="wpte-title"><?php esc_html_e( 'Billing Details', 'wp-travel-engine' ); ?></h4>
		<div class="wpte-button-wrap wpte-edit-booking-detail">
			<a href="#" class="wpte-btn-transparent wpte-btn-sm">
				<?php wptravelengine_svg_by_fa_icon( 'fas fa-pencil-alt' ); ?>
				<?php esc_html_e( 'Edit', 'wp-travel-engine' ); ?>
			</a>
		</div>
	</div>
	<div class="wpte-block-content">
		<?php do_action( 'wptravelengine_before_billing_details', $billing_info, $post->ID ); ?>
		<?php if ( isset( $billing_details ) && is_array( $billing_details ) ) : ?>
			<ul class="wpte-list">
				<?php foreach ( $billing_details as $key => $value ) : ?>
					<?php
						// Map keys to more readable formats
						$key_map = array(
							'fname'   => 'First Name',
							'lname'   => 'Last Name',
							'email'   => 'Email',
							'phone'   => 'Phone',
							'address' => 'Address',
							'city'    => 'City',
							'country' => 'Country',
						);

						if ( array_key_exists( $key, $key_map ) ) {
							$key = $key_map[ $key ];
						}
						if ( is_array( $value ) ) {
							$value = implode( ',', $value );
						}
						?>
					<?php if ( isset( $value ) && ! empty( $value ) ) : ?>
					<li>
						<b><?php echo esc_html( $key ); ?></b>
						
						<span>
							<?php
							if ( filter_var( $value, FILTER_VALIDATE_URL ) ) :
								?>
								<a href="<?php echo esc_url( $value ); ?>" target="_blank"><?php echo esc_html( basename( $value ) ); ?></a>
							<?php else : ?>
								<div class="wpte-field">
									<input readonly data-attrib-name="billing_info[<?php echo esc_attr( $key ); ?>]" type="text"
									value="<?php echo esc_attr( $value ); ?>" />
								</div>
							<?php endif; ?>
							</span>
						</li>
					<?php endif; ?>
				<?php endforeach; ?>
			</ul>
		<?php endif; ?>
		<?php if ( isset( $billing_info ) && isset( $billing_details ) && $billing_details == '' ) : ?>
		<ul class="wpte-list">
			<?php
			if ( is_array( $billing_info ) ) {
				foreach ( $billing_info as $key => $value ) : // foreachbokv
					$booking_key = 'booking_' . $key;
					if ( isset( $additional_fields[ $key ] ) ) {
						$booking_key = $key;
					}
					if ( 'fname' === $key ) {
						$booking_key = 'booking_first_name';
					} elseif ( 'lname' === $key ) {
						$booking_key = 'booking_last_name';
					}
					if ( 'survey' === $key ) {
						continue;
					}

					if ( is_array( $value ) ) {
						$value = implode( ',', $value );
					}
					$data_label = wp_travel_engine_get_booking_field_label_by_name( preg_replace( '/(^booking_booking_)/', 'booking_', $booking_key ) );
					?>
					<li>
						<b><?php echo esc_html( $data_label ); ?></b>
						<span>
							<?php if ( filter_var( $value, FILTER_VALIDATE_URL ) ) : ?>
								<a href="<?php echo esc_url( $value ); ?>" target="_blank"><?php echo esc_html( basename( $value ) ); ?></a>
							<?php else : ?>
								<div class="wpte-field">
									<input readonly data-attrib-name="billing_info[<?php echo esc_attr( $key ); ?>]" type="text"
									value="<?php echo esc_attr( $value ); ?>" />
								</div>
							<?php endif; ?>
						</span>
					</li>
					<?php
				endforeach; // endforeachbokv
			}
			?>
		</ul>
		<?php endif; ?>
		<?php do_action( 'wptravelengine_after_billing_details', $billing_info, $post->ID ); ?>
		<?php if ( ! empty( $additional_note ) ) : ?>
			<div class="wpte-block wpte-col1">
				<div class="wpte-title-wrap">
					<h4 class="wpte-title"><?php esc_html_e( 'Additional Note', 'wp-travel-engine' ); ?></h4>
					<div class="wpte-button-wrap wpte-edit-additional-note">
						<a href="#" class="wpte-btn-transparent wpte-btn-sm">
							<?php wptravelengine_svg_by_fa_icon( 'fas fa-pencil-alt' ); ?>
							<?php esc_html_e( 'Edit', 'wp-travel-engine' ); ?>
						</a>
					</div>
				</div>
				<div class="wpte-block-content">
					<div class="wpte-field">
						<input readonly data-attrib-name="wptravelengine_additional_note" type="text"
						value="<?php echo esc_attr( $additional_note ?? '' ); ?>" />
					</div>
				</div>
			</div>
		<?php endif; ?>
	</div>
</div>

