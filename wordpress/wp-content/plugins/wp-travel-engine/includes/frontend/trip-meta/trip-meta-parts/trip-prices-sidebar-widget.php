<?php
wp_enqueue_script( 'parsley' );
$wrapper_classes = apply_filters( 'wpte_bf_outer_wrapper_classes', '' );
$wte_options     = get_option( 'wp_travel_engine_settings', true );
// Get the currency symbol
$currency_code   = isset( $wte_options['currency_code'] ) ? $wte_options['currency_code'] : '';
$currency_symbol = wp_travel_engine_get_currency_symbol( $currency_code );

// Pricing Section layout Options
$form_layout           = isset( $settings['pricing_section_layout'] ) ? $settings['pricing_section_layout'] : 'layout-1';
$class_based_on_layout = isset( $settings['pricing_section_layout'] ) ? ' wpte-form-' . $settings['pricing_section_layout'] . '' : ' wpte-form-layout-1';
if ( $form_layout == 'layout-2' ) {
	if ( \WP_Travel_Engine_Template_Hooks::is_single_pricing_category() ) {
		$class_based_on_layout = $form_layout == 'layout-2' ? ' wpte-form-layout-2 wpte-default-form' : ' wpte-form-layout-2';
	}
}

// Compact Layout.
$enable_compact_layout = isset( $settings['enable_compact_layout'] ) && 'yes' === $settings['enable_compact_layout'];
// Enquiry Form Link.
$show_enquiry_info   = ! isset( $settings['show_enquiry_info'] ) || 'yes' === $settings['show_enquiry_info'];
$enquiry_link        = $settings['enquiry_form_link'] ?? 'default';
$custom_enquiry_link = $settings['custom_enquiry_link'] ?? '#';

$global_settings = wptravelengine_settings();
?>
	<div class="widget wpte-booking-area-wrapper wpte-bf-outer <?php echo esc_attr( $wrapper_classes ); ?>">
		<!-- Prices List -->
		<?php do_action( 'wte_before_price_info' ); ?>
		<div class="wpte-booking-area<?php echo esc_attr( $class_based_on_layout ); ?> <?php echo $enable_compact_layout ? 'wpte-compact-layout' : ''; ?>">
			<?php if ( ( 'layout-3' === $form_layout ) || ( ! $enable_compact_layout ) ) : ?>
				<button data-text="<?php echo $form_layout == 'layout-3' ? esc_attr( $currency_symbol ) : ''; ?>"
						type="button" id="wpte_price-toggle-btn-mb" class="wpte_price-toggle-btn-mb"
						data-active-text="<?php $form_layout == 'layout-1' ? _e( 'Hide Prices', 'wp-travel-engine' ) : ''; ?>">
					<?php if ( $form_layout == 'layout-1' ) : ?>
						<span class="current-text">
						<?php _e( 'Show Prices', 'wp-travel-engine' ); ?>
					</span>
					<?php endif; ?>
				</button>
				<?php
			endif;
			if ( $form_layout == 'layout-3' ) :
				?>
			<div class="wrap">
				<button type="button" id="wpte_price-toggle-btn-mb-<?php echo esc_attr( $form_layout ); ?>"
						class="wpte_price-toggle-btn-mb-<?php echo esc_attr( $form_layout ); ?>"></button>
				<?php endif; ?>
				<div class="wpte-booking-inner-wrapper">
					<?php
					if ( true || wte_array_get( $wte_options, 'show_multiple_pricing_list_disp', '' ) == '1' ) :
						?>
						<!-- Group Discount Badge Section -->
						<?php
						if ( $trip->has_group_discount() ) :
							?>
							<span
								class="wpte-bf-gd-text"><?php echo esc_html( apply_filters( 'wte_group_discount_badge_text', __( 'Group Discount Available', 'wp-travel-engine' ) ) ); ?></span>
							<?php
						endif; // Group Discount Badge.
						?>

						<!-- Discount Percent Badge -->
						<?php
						// Show Discount Percent if Available.
						if ( $default_package->has_sale ) :
							?>
							<span
								class="wpte-bf-discount-tag"><?php echo esc_html( wptravelengine_get_discount_label( $default_package ) ); ?></span>
							<?php
						endif;
						?>
						<div class="wpte-bf-price-wrap">
							<?php
							// Displays Package with lowest pricings.
							\WP_Travel_Engine_Template_Hooks::categorised_trip_prices();
							?>
						</div>

						<?php
						// Show highlights if available.
						$highlights = isset( $settings['trip_highlights'] ) && is_array( $settings['trip_highlights'] ) ? $settings['trip_highlights'] : array();
						if ( count( $highlights ) > 0 ) :
							?>
							<div class="wpte-bf-content">
								<ul>
									<?php
									foreach ( $highlights as $highlight ) {
										$highlight = (object) $highlight;
										printf( '<li>%1$s%2$s</li>', esc_html( $highlight->highlight ), ! empty( $highlight->help ) ? '<span class="wpte-custom-tooltip" data-title="' . esc_attr( $highlight->help ) . '"></span>' : '' );
									}
									?>
								</ul>
							</div>
						<?php endif; ?>
					<?php endif; // Show_multiple_pricing_list_disp. ?>
					<?php
					$trip_booking_data = wptravelengine_trip_booking_modal_data( $trip->ID );
					?>
					<div class="wpte-bf-btn-wrap">
						<?php
							$show_whatsapp_icon = isset( $wte_options['show_whatsapp_icon'] ) && 'yes' === $wte_options['show_whatsapp_icon'];
							$whatsapp_number    = $wte_options['whatsapp_number'] ?? '';
						if ( $show_whatsapp_icon && ! empty( $whatsapp_number ) ) :
							// Normalize the number to remove any dashes or spaces
							$normalized_number = preg_replace( '/[^0-9]/', '', $whatsapp_number );
							?>
							<!-- Whatsapp Call to Action -->
							<div class="wpte-bf-whatsapp-cta">
								<a href="https://wa.me/<?php echo esc_attr( $normalized_number ); ?>" class="wpte-bf-whatsapp-cta-link" target="_blank">
									<svg width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
										<g clip-path="url(#clip0_330_17)">
										<g filter="url(#filter0_f_330_17)">
										<path d="M12.4491 31.5462L12.9593 31.8479C15.1023 33.1173 17.5593 33.7887 20.0651 33.7899H20.0703C27.7653 33.7899 34.0277 27.5408 34.0309 19.8601C34.0323 16.1381 32.5815 12.6381 29.9457 10.0052C28.6529 8.7068 27.1149 7.67719 25.4207 6.97601C23.7265 6.27483 21.9098 5.916 20.0758 5.9203C12.3749 5.9203 6.11222 12.1687 6.10948 19.8489C6.10569 22.4715 6.84563 25.0416 8.24376 27.2621L8.57592 27.7889L7.16532 32.9288L12.4491 31.5462ZM3.13235 36.9108L5.51547 28.2264C4.04574 25.6849 3.27252 22.8017 3.27344 19.8478C3.27732 10.607 10.8118 3.08923 20.0705 3.08923C24.5635 3.09151 28.7807 4.83664 31.9523 8.00447C35.124 11.1723 36.869 15.383 36.8674 19.8612C36.8633 29.1014 29.3277 36.6205 20.0703 36.6205H20.063C17.2521 36.6194 14.49 35.9155 12.0366 34.5803L3.13235 36.9108Z" fill="#C5C5C5"/>
										</g>
										<path d="M2.95999 36.7385L5.34311 28.0541C3.87085 25.5065 3.09752 22.6166 3.10107 19.6755C3.10496 10.4347 10.6394 2.91698 19.8982 2.91698C24.3911 2.91926 28.6083 4.66438 31.78 7.83222C34.9516 11.0001 36.6966 15.2108 36.695 19.689C36.6909 28.9291 29.1553 36.4482 19.8979 36.4482H19.8906C17.0797 36.4471 14.3176 35.7433 11.8642 34.408L2.95999 36.7385Z" fill="white"/>
										<path d="M19.9032 5.74804C12.2023 5.74804 5.93964 11.9964 5.9369 19.6767C5.93311 22.2993 6.67305 24.8694 8.07118 27.0899L8.40335 27.6169L6.99274 32.7568L12.2767 31.374L12.787 31.6756C14.9299 32.945 17.387 33.6163 19.8927 33.6176H19.898C27.5929 33.6176 33.8556 27.3685 33.8586 19.6878C33.8644 17.8572 33.5063 16.0435 32.8051 14.352C32.1038 12.6604 31.0733 11.1244 29.7733 9.83298C28.4805 8.53451 26.9424 7.50489 25.2482 6.8037C23.554 6.10252 21.7373 5.74371 19.9032 5.74804Z" fill="url(#paint0_linear_330_17)"/>
										<path fill-rule="evenodd" clip-rule="evenodd" d="M15.6999 12.6693C15.3853 11.9718 15.0543 11.9577 14.7555 11.9456L13.951 11.9358C13.6711 11.9358 13.2164 12.0406 12.8319 12.4599C12.4475 12.8791 11.3629 13.8924 11.3629 15.9533C11.3629 18.0143 12.8669 20.0057 13.0764 20.2855C13.286 20.5653 15.9798 24.9294 20.2459 26.6084C23.791 28.0038 24.5124 27.7263 25.2822 27.6565C26.052 27.5868 27.7653 26.6433 28.1148 25.6651C28.4643 24.6869 28.4645 23.8489 28.3597 23.6737C28.2549 23.4985 27.9751 23.3943 27.555 23.1847C27.135 22.9751 25.0719 21.9618 24.6872 21.8219C24.3026 21.682 24.0229 21.6125 23.7428 22.0318C23.4627 22.451 22.6593 23.3941 22.4144 23.6737C22.1694 23.9532 21.9249 23.9883 21.5049 23.7789C21.0848 23.5695 19.7334 23.1271 18.1298 21.7C16.8822 20.5897 16.0401 19.2185 15.7949 18.7994C15.5497 18.3804 15.7689 18.1535 15.9794 17.9448C16.1677 17.757 16.399 17.4556 16.6092 17.2111C16.8195 16.9666 16.8886 16.7918 17.0283 16.5127C17.1681 16.2336 17.0984 15.9884 16.9934 15.779C16.8884 15.5696 16.073 13.4978 15.6999 12.6693Z" fill="white"/>
										</g>
										<defs>
										<filter id="filter0_f_330_17" x="-0.867645" y="-0.910767" width="41.735" height="41.8215" filterUnits="userSpaceOnUse" color-interpolation-filters="sRGB">
										<feFlood flood-opacity="0" result="BackgroundImageFix"/>
										<feBlend mode="normal" in="SourceGraphic" in2="BackgroundImageFix" result="shape"/>
										<feGaussianBlur stdDeviation="2" result="effect1_foregroundBlur_330_17"/>
										</filter>
										<linearGradient id="paint0_linear_330_17" x1="19.6135" y1="7.42048" x2="19.7545" y2="31.2368" gradientUnits="userSpaceOnUse">
										<stop stop-color="#57D163"/>
										<stop offset="1" stop-color="#23B33A"/>
										</linearGradient>
										<clipPath id="clip0_330_17">
										<rect width="40" height="40" fill="white"/>
										</clipPath>
										</defs>
									</svg>
								</a>
							</div>
							<?php
						endif;
						?>
						<?php if ( $trip->has_date() ) : ?>
						<button type="button"
								data-trip-booking="<?php echo esc_attr( wp_json_encode( $trip_booking_data ) ); ?>"
								disabled="disabled"
								class="wpte-bf-btn wte-book-now btn-loading"><?php esc_html_e( 'Check Availability', 'wp-travel-engine' ); ?></button>
						<?php else : ?>
							<button type="button" class="wpte-bf-btn wpte-button-disabled" disabled><?php esc_html_e( 'Sold Out', 'wp-travel-engine' ); ?></button>
						<?php endif; ?>
					</div>
					<?php do_action( 'wptravelengine_after_booking_button' ); ?>
				</div>
				<?php
				if ( $show_enquiry_info ) :
					$is_custom_link = 'custom' === $enquiry_link && ! empty( $custom_enquiry_link ) && $custom_enquiry_link !== '#';
					$link           = $is_custom_link ? $custom_enquiry_link : '#wte_enquiry_form_scroll_wrapper';
					$target         = $is_custom_link ? '_blank' : '_self';
					$id             = $is_custom_link ? 'wte-open-enquiry-link' : 'wte-send-enquiry-message';

					$enquiry_message = __( 'Need help with booking?', 'wp-travel-engine' );
					$link_label      = __( 'Send Us A Message', 'wp-travel-engine' );
					if ( ( $settings['pricing_widget_enquiry_message'] ?? '' ) && preg_match( '/^(.*?)?(?:\s*\[\[(.*?)\]\])?$/', $settings['pricing_widget_enquiry_message'], $matches ) ) {
						$enquiry_message = trim( $matches[1] ?? '' );
						$link_label      = trim( $matches[2] ?? '' );
					}

					?>
						<div class="wpte-booking-footer-text">
							<span><?php echo esc_html( $enquiry_message ); ?></span>
							<a href="<?php echo esc_url( $link ); ?>" target="<?php echo esc_attr( $target ); ?>" id="<?php echo esc_attr( $id ); ?>">
							<?php echo esc_html( $link_label ); ?>
							</a>
						</div>
					<?php
					endif;
				if ( $form_layout == 'layout-3' ) :
					?>
			</div>
		<?php endif; ?>
		</div>
		<?php
		do_action( 'wte_after_price_info' );
		?>
		<!-- ./ Prices List -->
	</div>
	
<?php
