<?php
/**
 * Personal details
 */

use WPTravelEngine\Helpers\Functions;

wp_enqueue_script( 'jquery-ui-datepicker' );

global $post;
?>
	<div class="wpte-block-wrap">
		<div class="wpte-block">
			<div class="wpte-title-wrap">
				<h4 class="wpte-title"><?php esc_html_e( 'Traveller Details', 'wp-travel-engine' ); ?></h4>
			</div>
			<div class="wpte-block-content wpte-floated">
				<?php
				if ( isset( $traveller_information ) && is_array( $traveller_information ) ) :
					?>
				<div class="wpte-toggle-item-wrap wpte-col2">
					<?php
					for ( $i = 1; $i <= count( $traveller_information ); $i++ ) {
						?>
						<div class="wpte-toggle-item">
							<div class="wpte-toggle-title">
								<a href="Javascript:void(0);"><?php printf( esc_html__( 'Traveller %1$s', 'wp-travel-engine' ), (int) $i ); ?></a>
							</div>
							<div class="wpte-toggle-content">
								<div class="wpte-prsnl-dtl-blk wpte-floated">
									<div class="wpte-button-wrap wpte-rightalign wpte-edit-prsnl-details">
										<a href="#" class="wpte-btn-transparent wpte-btn-sm">
											<?php wptravelengine_svg_by_fa_icon( 'fas fa-pencil-alt' ); ?>
											<?php esc_html_e( 'Edit', 'wp-travel-engine' ); ?>
										</a>
									</div>
										<h4><?php esc_html_e( 'Traveller information', 'wp-travel-engine' ); ?></h4>
										<div class="wpte-prsnl-dtl-blk-content">
											<?php do_action( 'wptravelengine_before_travellers_information', $traveller_information, $post->ID ); ?>
											<ul class="wpte-list">
												<?php
												foreach ( $traveller_information[ $i - 1 ] as $data_label => $data_value ) :
													$key_map = array(
														'title' => 'Title',
														'fname'   => 'First Name',
														'lname'   => 'Last Name',
														'email'   => 'Email',
														'phone'   => 'Phone',
														'address' => 'Address',
														'city'    => 'City',
														'country' => 'Country',
														'postcode' => 'Postcode',
														'dob'     => 'Date of Birth',
														'passport' => 'Passport Number',
													);
													if ( array_key_exists( $data_label, $key_map ) ) {
														$data_label = $key_map[ $data_label ];
													}

													if ( is_array( $data_value ) ) {
														$data_value = implode( ',', $data_value );
													}
													if ( $data_value ) :
														?>
														<li>
															<b><?php echo esc_html( $data_label ); ?></b>
															<?php
															if ( 'dob' === $key ) :
																$data_value = wte_get_formated_date( $data_value );
															endif;
															switch ( $data_label ) {
																case 'dob':
																	$data_value = wte_get_formated_date( $data_value );
																	?>
																	<span>
																		<div class="wpte-field wpte-text">
																			<input
																				class="wp-travel-engine-datetime hasDatepicker"
																				type="text"
																				name="travelers[<?php echo esc_attr( $key ); ?>][<?php echo esc_attr( $i ); ?>]"
																				value="<?php echo esc_attr( $date_value ); ?>">
																		</div>
																		</span>
																	<?php
																	break;
																case 'country':
																	?>
																	<span>
																			<div class="wpte-field wpte-select">
																			<select class="wpte-enhanced-select"
																					name="travelers[<?php echo esc_attr( $key ); ?>][<?php echo esc_attr( $i ); ?>]">
																		<?php
																		$country_options = Functions::get_countries();
																		foreach ( $country_options as $key => $country ) {
																			$selected = selected( $value, $key, false );
																			echo '<option ' . $selected . " value='" . esc_attr( $key ) . "'>" . esc_html( $country ) . "</option>"; // phpcs:ignore
																		}
																		?>
																		</select>
																		</div>
																	</span>
																	<?php
																	break;
																default:
																	?>
																	<span><?php echo esc_html( $data_value ); ?></span>
																	<?php
															}
															?>
														</li>
														<?php
													endif;
												endforeach;
												?>
											</ul>
											<?php do_action( 'wptravelengine_after_travellers_information', $traveller_information, $post->ID ); ?>
										</div>
										<div style="display:none;"
											class="wpte-prsnl-dtl-blk-content-edit edit-personal-info">
											<ul class="wpte-list">
												<?php
												foreach ( $traveller_information[ $i - 1 ] as $data_label => $data_value ) :
													$key_map = array(
														'title' => 'Title',
														'fname'   => 'First Name',
														'lname'   => 'Last Name',
														'email'   => 'Email',
														'phone'   => 'Phone',
														'address' => 'Address',
														'city'    => 'City',
														'country' => 'Country',
														'postcode' => 'Postcode',
														'dob'     => 'Date of Birth',
														'passport' => 'Passport Number',
													);
													if ( array_key_exists( $data_label, $key_map ) ) {
														$data_label = $key_map[ $data_label ];
													}

													if ( is_array( $data_value ) ) {
														$data_value = implode( ',', $data_value );
													}
													if ( $data_value ) :
														?>
														<li>
															<b><?php echo esc_html( $data_label ); ?></b>
															<?php
															if ( 'dob' === $data_label ) :
																$data_value = wte_get_formated_date( $data_value );
															endif;
															switch ( $data_label ) {
																case 'dob':
																	$data_value = wte_get_formated_date( $data_value );
																	?>
																	<span>
																		<div class="wpte-field wpte-text">
																			<input
																				class="wp-travel-engine-datetime hasDatepicker"
																				type="text"
																				name="travelers[<?php echo esc_attr( $data_label ); ?>][<?php echo esc_attr( $i ); ?>]"
																				value="<?php echo esc_attr( $date_value ); ?>">
																		</div>
																		</span>
																	<?php
																	break;
																case 'country':
																	?>
																	<span>
																			<div class="wpte-field wpte-select">
																			<select class="wpte-enhanced-select"
																					name="travelers[<?php echo esc_attr( $data_label ); ?>][<?php echo esc_attr( $i ); ?>]">
																		<?php
																		$country_options = Functions::get_countries();
																		foreach ( $country_options as $key => $country ) {
																			$selected = selected( $data_value, $key, false );
																			echo '<option ' . $selected . " value='" . esc_attr( $key ) . "'>" . esc_html( $country ) . "</option>"; // phpcs:ignore
																		}
																		?>
																		</select>
																		</div>
																	</span>
																	<?php
																	break;
																default:
																	?>
																	<span>
																		<div class="wpte-field wpte-text">
																			<input type="text"
																					name="travelers[<?php echo esc_attr( $data_label ); ?>][<?php echo esc_attr( $i ); ?>]"
																					value="<?php echo esc_attr( $data_value ); ?>">
																			</div>
																		</span>
																	<?php
															}
															?>
														</li>
														<?php
													endif;
												endforeach;
												?>
											</ul>
										</div>
								</div>
								<?php if ( isset( $emergency_contact ) && is_array( $emergency_contact ) && $i == 1 ) : ?>
								<div class="wpte-prsnl-dtl-blk wpte-floated">
									<div class="wpte-button-wrap wpte-rightalign wpte-edit-prsnl-details">
										<a href="#" class="wpte-btn-transparent wpte-btn-sm">
											<?php wptravelengine_svg_by_fa_icon( 'fas fa-pencil-alt' ); ?>
											<?php esc_html_e( 'Edit', 'wp-travel-engine' ); ?>
										</a>
									</div>
									<?php if ( isset( $emergency_contact ) && is_array( $emergency_contact ) && $i == 1 ) : ?>
										<h4><?php esc_html_e( 'Emergency Contact', 'wp-travel-engine' ); ?></h4>
										<div class="wpte-prsnl-dtl-blk-content">
											<?php do_action( 'wptravelengine_before_emergency_contact_information', $emergency_contact, $post->ID ); ?>
											<ul class="wpte-list">
												<?php
													// Map keys to more readable formats
													$key_map = array(
														'title' => 'Title',
														'fname'   => 'First Name',
														'lname'   => 'Last Name',
														'email'   => 'Email',
														'phone'   => 'Phone',
														'address' => 'Address',
														'city'    => 'City',
														'country' => 'Country',
														'relation' => 'Relation',
													);

													foreach ( $emergency_contact as $key => $data_value ) :
														if ( array_key_exists( $key, $key_map ) ) {
															$key = $key_map[ $key ];
														}
														if ( is_array( $data_value ) ) {
															$data_value = implode( ',', $data_value );
														}
														?>
														<li>
															<b><?php echo esc_html( $key ); ?></b>
															<span><?php echo esc_html( $data_value ); ?></span>
														</li>
														<?php
													endforeach;
													?>
											</ul>
											<?php do_action( 'wptravelengine_after_emergency_contact_information', $emergency_contact, $post->ID ); ?>
										</div>
										<div style="display:none;"
											class="wpte-prsnl-dtl-blk-content-edit edit-relation-info">
											<ul class="wpte-list">
												<?php
													// Map keys to more readable formats
													$key_map = array(
														'title' => 'Title',
														'fname'   => 'First Name',
														'lname'   => 'Last Name',
														'email'   => 'Email',
														'phone'   => 'Phone',
														'address' => 'Address',
														'city'    => 'City',
														'country' => 'Country',
														'relation' => 'Relation',
													);
													foreach ( $emergency_contact as $key => $data_value ) :
														if ( array_key_exists( $key, $key_map ) ) {
															$key = $key_map[ $key ];
														}
														?>
														<?php if ( isset( $data_value ) && ! empty( $data_value ) ) : ?>
														<li>
															<b><?php echo esc_html( $key ); ?></b>
															<?php
															switch ( $key ) {
																case 'dob':
																	$data_value = wte_get_formated_date( $data_value );
																	?>
																	<span>
																			<div class="wpte-field wpte-select">
																			<select class="wpte-enhanced-select"
																					name="emergency[<?php echo esc_attr( $key ); ?>][<?php echo esc_attr( $i ); ?>]">
																		<?php
																		$country_options = Functions::get_countries();
																		foreach ( $country_options as $key => $country ) {
																			$selected = selected( $data_value, $key, false );
																			echo '<option ' . $selected . " value='" . esc_attr( $key ) . "'>" . esc_html( $country ) . "</option>"; // phpcs:ignore
																		}
																		?>
																		</select>
																		</div>
																	</span>
																	<?php
																	break;
																case 'country':
																	?>
																	<span>
																			<div class="wpte-field wpte-select">
																			<select class="wpte-enhanced-select"
																					name="emergency[<?php echo esc_attr( $key ); ?>][<?php echo esc_attr( $i ); ?>]">
																		<?php
																		$country_options = Functions::get_countries();
																		foreach ( $country_options as $key => $country ) {
																			$selected = selected( $data_value, $key, false );
																			echo '<option ' . $selected . " value='" . esc_attr( $key ) . "'>" . esc_html( $country ) . "</option>"; // phpcs:ignore
																		}
																		?>
																		</select>
																		</div>
																	</span>
																	<?php
																	break;
																default:
																	?>
																	<span>
																			<div class="wpte-field wpte-text">
																				<input type="text"
																						name="emergency[<?php echo esc_attr( $key ); ?>][<?php echo esc_attr( $i ); ?>]"
																						value="<?php echo esc_attr( $data_value ); ?>">
																				</div>
																			</span>
																	<?php
															}
															?>
														</li>
													<?php endif; ?>
														<?php
												endforeach;
													?>
											</ul>
										</div>
									<?php endif; ?>
								</div>
								<?php endif; ?>
							</div>
						</div>
						<?php
						if ( $i % 3 === 0 && $i != $booked_travellers ) {
							echo '</div><div class="wpte-toggle-item-wrap wpte-col2">';
						}
					}
					?>
				</div>
				<?php endif; ?>

			</div>
		</div> <!-- .wpte-block -->
	</div> <!-- .wpte-block-wrap -->
<?php
