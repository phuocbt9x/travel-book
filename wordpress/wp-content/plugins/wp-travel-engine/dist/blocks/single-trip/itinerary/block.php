<?php
/**
 * Render File for Itinerary block.
 *
 * @var string $wrapper_attributes
 * @var Attributes $attributes_parser
 * @var Render $render
 * @package Wp_Travel_Engine
 * @since 5.9
 */

use WPTravelEngine\Blocks\SampleData;

global $wtetrip;

$wp_travel_engine_settings = get_option( 'wp_travel_engine_settings', array() );
$enabled_expand_all        = $attributes_parser->get( 'showAll' ) ?? false;
wp_enqueue_style( 'jquery-fancy-box' );
wp_enqueue_script( 'jquery-fancy-box' );
if ( $render->is_editor() ) {
	$itineraries = SampleData::itinerary();
} else {
	/**
	 * Fetch the metadata associated with the trip post.
	 */
	$wp_travel_engine_setting    = get_post_meta( $wtetrip->post->ID, 'wp_travel_engine_setting', true );
	$advanced_itinerary_settings = get_post_meta( $wtetrip->post->ID, 'wte_advanced_itinerary', true );
	$itinerary                   = isset( $wp_travel_engine_setting['itinerary'] ) ? $wp_travel_engine_setting['itinerary'] : '';
	$advanced_itinerary          = isset( $advanced_itinerary_settings['advanced_itinerary'] ) ? $advanced_itinerary_settings['advanced_itinerary'] : array();
	$itineraries                 = array();
	if ( ! isset( $wp_travel_engine_setting['itinerary'] ) ) {
		$itineraries = array();
	}
	if ( ! defined( 'WTEAI_VERSION' ) ) {
		if ( isset( $itinerary ) && isset( $itinerary['itinerary_title'] ) ){
			foreach ( $itinerary['itinerary_title'] as $key => $itinerary_title ) {
				if ( isset( $itinerary['itinerary_content'][ $key ] ) ) {
					$itineraries[] = array(
						'title'   => $itinerary_title,
						'content' => $itinerary['itinerary_content'][ $key ],
					);
				}
			}
		}
	} else {
		if ( isset( $advanced_itinerary ) && !is_array( $advanced_itinerary ) ) {
			$itineraries = array();
		} else {
			$merged_itinerary = array();
			if ( is_array( $itinerary ) && is_array( $advanced_itinerary ) ) {
				$merged_itinerary = array_merge( $itinerary, $advanced_itinerary );
			}
			if ( ! isset( $merged_itinerary['itinerary_title'] ) ) {
				return;
			}
			foreach ( $merged_itinerary['itinerary_title'] as $key => $itinerary_title ) {
				$itineraries[] = array(
					'title'                  => $itinerary_title,
					'days_label'             => $merged_itinerary['itinerary_days_label'][ $key ] ?? '',
					'content'                => $merged_itinerary['itinerary_content'][ $key ] ?? '',
					'duration'               => $merged_itinerary['itinerary_duration'][ $key ] ?? '',
					'duration_type'          => $merged_itinerary['itinerary_duration_type'][ $key ] ?? '',
					'sleep_modes'            => $merged_itinerary['sleep_modes'][ $key ] ?? '',
					'sleep_mode_description' => $merged_itinerary['itinerary_sleep_mode_description'][ $key ] ?? '',
					'itinerary_image'        => $merged_itinerary['itinerary_image'][ $key ] ?? '',
					'meals_included'         => $merged_itinerary['meals_included'][ $key ] ?? '',
				);
			}
		}
	}
}

	?>
	<div <?php echo esc_attr( $attributes_parser->wrapper_attributes() ); ?> >
		<div class="post-data itinerary wte-trip-itinerary-v2<?php echo $attributes_parser->get('showDivider') ? ' has-divider' : '';echo $attributes_parser->get('showBullets') ? ' has-bullets' : ''; ?>">
			<?php
			if ( $attributes_parser->get( 'expandAll' ) && ( $itinerary || $render->is_editor() ) ) {
				?>
				<div class="wte-toggle-button-wrap">
					<div class="aib-button-toggle toggle-button expand-all-button">
						<label for="itinerary-toggle-button" class="aib-button-label">
							<?php echo esc_html__( 'Expand all', 'wp-travel-engine' ); ?>
						</label>
						<input id="itinerary-toggle-button" type="checkbox" class="checkbox" <?php echo $enabled_expand_all !== '' ? 'checked' : ''; ?>>
					</div>
				</div>
				<?php
			}
			foreach ( $itineraries as $index => $itinerary ) : ?>
				<div class="itinerary-row <?php echo ( $enabled_expand_all || 0 === $index ) ? 'active' : ''; ?>">
					<div class="wte-itinerary-head-wrap">
						<div class="title">
							<span class="itinerary-day">
							<?php
							if ( isset( $itinerary['days_label'] ) && ! empty( $itinerary['days_label'] ) ) {
								?>
									<?php echo esc_attr( $itinerary['days_label'] ); ?>
									<?php
							} else {
								if ( true === $attributes_parser->get( 'dayLabel' ) ) {
									// Translators: %s => day number.
									echo sprintf( esc_html__( 'Day %s : ', 'wp-travel-engine' ), esc_attr( $index + 1 ) );
								}
							}
							?>
							</span>
						</div>
						<a class="accordion-tabs-toggle <?php echo ( $enabled_expand_all || 0 === $index ) ? 'active' : ''; ?>" href="javascript:void(0);">
							<span class="dashicons dashicons-arrow-down custom-toggle-tabs rotator <?php echo ( $enabled_expand_all || 0 === $index ) ? 'open' : ''; ?>"></span>
							<div class="itinerary-title">
								<span>
									<?php echo( esc_attr( $itinerary['title'] ) ?? '' ); ?>
								</span>
							</div>
						</a>
					</div>
					<?php echo $enabled_expand_all ? '<style id="itinerary-content-show"> .itinerary-content{ disply:block!important; } </style>' : ''; ?>
					<div class="itinerary-content <?php echo ( $enabled_expand_all || 0 === $index ) ? 'show' : ''; ?>" <?php echo ( $enabled_expand_all || 0 === $index ) ? 'style="display: block;"' : ''; ?>>
						<div class="content">
							<p>
								<?php
									$content_itinerary = wpautop( $itinerary['content'] );
									echo apply_filters( 'the_content', wp_kses_post( $content_itinerary ) );
								?>
							</p>
						</div>
						<?php
						if ( defined( 'WTEAI_VERSION' ) ) {
							$itineraries_galleries_id = isset( $itinerary['itinerary_image'] ) && ! empty( $itinerary['itinerary_image'] ) ? $itinerary['itinerary_image'] : '';
							if ( isset( $itineraries_galleries_id ) && is_array( $itineraries_galleries_id ) && ! empty( $itineraries_galleries_id ) ) {
								?>
								<div class="itenary-detail-gallery">
									<?php
									foreach ( $itineraries_galleries_id as $keys => $image_id ) {
										$image_thumbnail = wp_get_attachment_image_src( $image_id, 'wteai-gallery-thumbnail' );
										$image_full      = wp_get_attachment_image_src( $image_id, 'large' );
										if ( ! empty( $image_thumbnail ) ) {
											?>
											<a class="itinerary-gallery-link" href="<?php echo esc_url( $image_full[0] ); ?>" data-fancybox="itinerary-gallery">
												<img src="<?php echo esc_attr( $image_thumbnail[0] ); ?>" class="itinerary-indv-image" />
											</a>
											<?php
										}
									}
									?>
								</div>
								<?php
							}
							if ( isset( $itinerary['duration'] ) && ! empty( $itinerary['duration'] ) || ( isset( $itinerary['meals_included'] ) ) || ( isset( $itinerary['sleep_modes'] ) && ! empty( $itinerary['sleep_modes'] ) ) || ( isset( $itinerary['itinerary_image'] ) && ! empty( $itinerary['itinerary_image'] ) ) ) {
								?>
								<div class="itinerary-detail-additional-info<?php echo $attributes_parser->get('itineraryInfoDivider') ? ' has-divider' : ''; ?>">
									<?php
									if ( isset( $itinerary['duration'] ) && ! empty( $itinerary['duration'] ) ) {
										if ( isset( $itinerary['duration'] ) ) {
											$duration_type_text  = isset( $itinerary['duration_type'] ) ? esc_attr( $itinerary['duration_type'] ) : '';
											$duration_type_text .= ( $itinerary['duration'] > 1 ) ? 's' : '';
										} else {
											$duration_type_text = '';
										}
										?>
										<div class="itinerary-duration">
											<span class="itinierary-icon-wrap"><svg xmlns="http://www.w3.org/2000/svg" width="16.44" height="14.807" viewBox="0 0 16.44 14.807"><g id="time" transform="translate(0)"><path id="Path_23383" data-name="Path 23383" d="M-283.058-26.585h.095c.442,0,.883,0,1.325,0,.08,0,.1-.023.1-.1-.006-.148,0-.3,0-.445a5.067,5.067,0,0,1,.063-.64,5.429,5.429,0,0,1,.153-.77,4.837,4.837,0,0,1,.161-.541,8.685,8.685,0,0,1,.364-.9,9.969,9.969,0,0,1,.544-.911c.1-.16.253-.292.351-.455a3.335,3.335,0,0,1,.475-.535,6.516,6.516,0,0,1,1.077-.92,9.043,9.043,0,0,1,.885-.528,7.044,7.044,0,0,1,1.547-.577c.269-.07.548-.1.822-.154a7.193,7.193,0,0,1,1.413-.068c.169,0,.337.05.507.059a2.536,2.536,0,0,1,.5.078c.139.036.283.053.422.091.242.066.485.131.72.216a6.1,6.1,0,0,1,1.157.539c.273.17.541.347.808.527a2.225,2.225,0,0,1,.3.245c.284.276.558.561.843.836a4.736,4.736,0,0,1,.607.806,6.27,6.27,0,0,1,.673,1.3c.062.166.115.334.169.5s.112.33.15.5c.051.229.086.462.126.694.023.133.043.267.063.4a.652.652,0,0,1,.008.1c0,.231.005.463,0,.694s-.014.472-.038.706a4.532,4.532,0,0,1-.09.476c-.038.181-.068.366-.122.543-.089.3-.181.592-.3.879a7.062,7.062,0,0,1-.408.858c-.164.286-.36.554-.549.826a7.633,7.633,0,0,1-1.137,1.2,5.54,5.54,0,0,1-.925.652,8.162,8.162,0,0,1-1.027.523,10.633,10.633,0,0,1-1.031.342c-.18.055-.366.092-.55.13-.115.024-.232.035-.349.05a11.682,11.682,0,0,1-1.489.032,3.787,3.787,0,0,1-.524-.062c-.157-.022-.313-.051-.47-.077a1.655,1.655,0,0,1-.2-.041c-.321-.1-.649-.179-.961-.3a6.637,6.637,0,0,1-1.266-.638c-.194-.129-.4-.249-.578-.394a6.543,6.543,0,0,1-.537-.49.463.463,0,0,1-.122-.313.887.887,0,0,1,.1-.433.441.441,0,0,1,.283-.211.936.936,0,0,1,.453-.056.3.3,0,0,1,.132.069c.226.176.445.361.676.53a5.6,5.6,0,0,0,1.369.727,4.811,4.811,0,0,0,.459.161c.228.058.46.1.69.146a1.713,1.713,0,0,0,.191.035c.164.015.329.031.493.034.284.005.569.009.854,0a3.244,3.244,0,0,0,.533-.064c.32-.066.641-.131.953-.227a6.234,6.234,0,0,0,.742-.3,5.556,5.556,0,0,0,1.024-.629,5.113,5.113,0,0,0,.558-.505,7.591,7.591,0,0,0,.64-.7,5.234,5.234,0,0,0,.71-1.209,9.23,9.23,0,0,0,.347-1.05,4.675,4.675,0,0,0,.135-.79c.024-.255.008-.514.014-.772a4.477,4.477,0,0,0-.067-.773,5.5,5.5,0,0,0-.267-1.062,5.036,5.036,0,0,0-.52-1.088,8.549,8.549,0,0,0-.612-.867,8.788,8.788,0,0,0-.782-.748,3.173,3.173,0,0,0-.4-.3,5.373,5.373,0,0,0-.994-.551c-.231-.088-.459-.188-.694-.261a6.522,6.522,0,0,0-.652-.153c-.2-.041-.4-.07-.608-.1a.5.5,0,0,0-.08,0c-.16,0-.32,0-.48,0a5.579,5.579,0,0,0-1.116.1,6.441,6.441,0,0,0-.963.26,6.235,6.235,0,0,0-1.543.812,5.93,5.93,0,0,0-.979.9,6.5,6.5,0,0,0-.611.8,4.418,4.418,0,0,0-.492.965c-.106.306-.215.614-.292.928a3.273,3.273,0,0,0-.123.864,4.183,4.183,0,0,1-.034.5c-.008.082.025.1.1.1.439,0,.878,0,1.316,0a.116.116,0,0,1,.076.027c.009.009,0,.049-.018.068q-.265.393-.534.785c-.154.224-.313.444-.464.669-.112.166-.211.341-.322.507-.091.137-.194.266-.288.4-.135.193-.267.389-.4.584-.015.022-.03.044-.045.066-.059.079-.082.079-.135,0-.158-.236-.314-.473-.474-.708-.284-.417-.572-.833-.856-1.25q-.34-.5-.677-1c-.029-.043-.06-.085-.09-.127Z" transform="translate(283.072 34.13)" fill="#00b98b" /><path id="Path_23384" data-name="Path 23384" d="M150.372,112.235c0,.584,0,1.168,0,1.752a.193.193,0,0,0,.077.158,4,4,0,0,1,.319.308.2.2,0,0,0,.166.077q1.44,0,2.881,0a.587.587,0,0,1,.554.36.662.662,0,0,1-.46.957,1.212,1.212,0,0,1-.288.033q-1.343,0-2.685,0a.221.221,0,0,0-.193.1,1.345,1.345,0,0,1-.682.455,1.287,1.287,0,0,1-1.12-2.277.194.194,0,0,0,.08-.185q0-1.707,0-3.415a.7.7,0,0,1,.661-.653.661.661,0,0,1,.678.485.435.435,0,0,1,.013.114Q150.372,111.368,150.372,112.235Z" transform="translate(-140.729 -107.341)" fill="#00b98b" /></g></svg></span> <span>
											<?php echo ( isset( $itinerary['duration'] ) ) ? esc_attr( $itinerary['duration'] ) : ''; ?>
											<?php echo esc_attr( $duration_type_text ); ?>
										</span>
										</div>
										<?php
									}
									if ( isset( $itinerary['meals_included'] ) && is_array( $itinerary['meals_included'] ) ) {
										?>
										<div class="itinerary-meals">
											<span class="itinierary-icon-wrap"><svg xmlns="http://www.w3.org/2000/svg" width="18.933" height="15.3" viewBox="0 0 18.933 15.3"><g id="tray" transform="translate(0 -26.502)" opacity="0.9"><path id="Path_23622" data-name="Path 23622" d="M21.531,208.212H4.427a.586.586,0,0,0-.509.338.852.852,0,0,0-.033.7l.537,1.341a1.509,1.509,0,0,0,1.356,1.024h14.4a1.509,1.509,0,0,0,1.356-1.024l.537-1.341a.851.851,0,0,0-.033-.7A.586.586,0,0,0,21.531,208.212Z" transform="translate(-3.512 -169.812)" fill="#00b98b" /><path id="Path_23623" data-name="Path 23623" d="M18.931,36.377c-.374-3.812-4.2-6.839-8.95-7.044v-.552a1.191,1.191,0,0,0,.766-1.089,1.283,1.283,0,0,0-2.559,0,1.191,1.191,0,0,0,.766,1.089v.552C4.2,29.538.377,32.565,0,36.377a.538.538,0,0,0,.156.43.626.626,0,0,0,.446.184H18.329a.626.626,0,0,0,.447-.183A.539.539,0,0,0,18.931,36.377ZM5.6,32.336a5.025,5.025,0,0,0-2.6,3.017.509.509,0,0,1-.5.358.55.55,0,0,1-.129-.015A.476.476,0,0,1,2,35.112,5.984,5.984,0,0,1,5.09,31.5a.535.535,0,0,1,.7.179A.461.461,0,0,1,5.6,32.336Z" transform="translate(0)" fill="#00b98b" /></g></svg></span>
											<?php
											$before_meal_string = '';
											$before_meal_string = apply_filters( 'wte_filtered_advanced_itinerary_meal_before_text', $before_meal_string );
											echo esc_attr( $before_meal_string );
											?>
											<span>
											<?php
											$iti_meals_array  = apply_filters(
												'wpte_ai_trip_meals_array',
												array(
													'breakfast' => __( 'Breakfast', 'wp-travel-engine' ),
													'lunch'  => __( 'Lunch', 'wp-travel-engine' ),
													'dinner' => __( 'Dinner', 'wp-travel-engine' ),
												)
											);
											$cloned_meals_inc = is_array( $itinerary['meals_included'] ) ? $itinerary['meals_included'] : array();
											$count            = count( $cloned_meals_inc );
											$i                = 1;
											$selected_meals   = array_map( 'strtolower', $cloned_meals_inc );
											foreach ( $selected_meals as $key => $val ) {
												if ( in_array( $val, $cloned_meals_inc, true ) ) {
													echo esc_html( $iti_meals_array[ $val ] );
													if ( $i < $count && $i !== $count ) {
														echo ', ';
													}
												}
												$i ++;
											}
											?>
										</span>
										</div>
										<?php
									}
									if ( isset( $itinerary['sleep_modes'] ) && ! empty( $itinerary['sleep_modes'] ) ) {
										?>
										<div class="itinerary-sleep-mode">
											<span class="itinierary-icon-wrap"><svg xmlns="http://www.w3.org/2000/svg" width="32.603" height="19.876" viewBox="0 0 32.603 19.876"><g id="bed" transform="translate(-167 -14.511)" opacity="0.9"><path id="Path_23624" data-name="Path 23624" d="M167,14.906a3.091,3.091,0,0,1,.269-.193c.806-.46,1.43-.113,1.43.794q0,5.689,0,11.377v.646h.823c0-.223,0-.424,0-.625,0-.807.093-.895.923-.9q1.925,0,3.849,0h.629c0-.787,0-1.535,0-2.283,0-.916.011-.93.955-.93q5.887,0,11.774,0c1.792,0,3.588.066,5.377-.015a4.437,4.437,0,0,1,4.884,4.529,1.8,1.8,0,0,0,.052.258c.314,0,.645.016.975,0a.54.54,0,0,1,.654.59c.01,1.887.014,3.774-.01,5.66,0,.192-.2.382-.306.573H198.26a2.7,2.7,0,0,1-.328-.839c-.034-1.389-.016-2.779-.016-4.184H168.691c0,1.444.011,2.838-.01,4.232a3.351,3.351,0,0,1-.209.791H167Z" transform="translate(0 0)" fill="#00b98b" /><path id="Path_23625" data-name="Path 23625" d="M192.907,73.425a2.387,2.387,0,1,1,4.773.124,2.387,2.387,0,0,1-4.773-.124Z" transform="translate(-22.973 -50.167)" fill="#00b98b" /></g></svg></span>
											<span class="label">
											<?php
											if ( isset( $itinerary['sleep_mode_description'] ) && '' !== $itinerary['sleep_mode_description'] ) {
												echo '<a href="javascript:void(0);">' . esc_attr( $itinerary['sleep_modes'] ) . '<span>';
												echo function_exists( 'wptravelengine_svg_by_fa_icon' ) ? wptravelengine_svg_by_fa_icon( 'fas fa-info' ) : '<i class="fas fa-info-circle'; // phpcs:ignore Escaping done in function wptravelengine_svg_by_fa_icon.
												echo '</span></a>';
											} else {
												echo esc_attr( $itinerary['sleep_modes'] );
											}
											?>
										</span>
										</div>
										<?php
									}
									?>
								</div>
								<?php
							}
							?>
							<?php
							if ( isset( $itinerary['sleep_mode_description'] ) && '' !== $itinerary['sleep_mode_description'] ) {
								?>
								<div class="content-additional-sleep-mode" id="content-additional-sleep-mode-<?php echo esc_attr( $index ); ?>" style="display: none;">
									<div class="additional-sleep-mode-inner">
										<a href="javascript:void(0);" class="wte-ai-close-button"><?php esc_html_e( 'Close', 'wp-travel-engine' ); ?></a>
										<div class="advanced-sleep-mode-content">
											<p>
												<?php
												if ( isset( $itinerary['sleep_mode_description'] ) && '' !== $itinerary['sleep_mode_description'] ) {
													$content_sleep_mode = $itinerary['sleep_mode_description'];
												} else {
													$content_sleep_mode = '';
												}
												echo apply_filters( 'the_content',  wp_kses_post( $content_sleep_mode ) );
												?>
											</p>
										</div>
									</div>
								</div>
								<?php
							}
						}
						?>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
	<?php
