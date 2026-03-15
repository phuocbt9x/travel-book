<?php
/**
 * Customer Profile.
 *
 * @var string $customer_id
 * @var string $customer_name
 * @var array $infos Customer info fields.
 * @var array $addresses Customer address fields.
 * @var string $avatar Customer avatar.
 */
?>
<h2 class="wpte-title"><?php esc_html_e( 'Customer Details', 'wp-travel-engine' ); ?></h2>
<div class="wpte-form-section" data-target-id="profile">
	<div class="wpte-accordion">
		<div class="wpte-accordion-content">
			<div class="wpte-customer-profile-details">
				<div class="wpte-customer-avatar">
					<img src="<?php echo esc_url( $avatar ); ?>">
					<?php
					if ( ! empty( $customer_id ) ) :
						$edit_user_url = add_query_arg(
							array(
								'user_id'         => $customer_id,
								'wp_http_referer' => urlencode( admin_url( 'users.php' ) ),
							),
							admin_url( 'user-edit.php' )
						);
						?>
						<a href="<?php echo esc_url( $edit_user_url ); ?>" class="wpte-customer-id"><?php echo esc_html( '#' . $customer_id ); ?></a>
					<?php endif; ?>
				</div>
				<div class="wpte-customer-details">
					<div class="wpte-fields-grid" data-columns="2">
						<div class="wpte-customer-details-item">
						<div class="wpte-customer-name"><?php echo esc_html( $customer_name ); ?></div>
							<?php
							foreach ( $infos as $info ) {
								?>
									<div class="<?php echo esc_attr( $info['class'] ); ?>">
									<?php
									if ( ! empty( $info['icon'] ) ) :
										echo wptravelengine_display_icon( $info['icon'] );
										endif;
									?>
										<div>
											<span class="label"><?php echo esc_html( $info['label'] ); ?></span>
											<span class="value"><?php echo esc_html( $info['value'] ); ?></span>
										</div>
									</div>
									<?php
							}
							?>
						</div>
						<div class="wpte-customer-details-item">
							<h5><?php esc_html_e( 'Address', 'wp-travel-engine' ); ?></h5>
							<?php
							foreach ( $addresses as $address ) {
								?>
									<div class="<?php echo esc_attr( $address['class'] ); ?>">
									<?php echo wptravelengine_display_icon( $address['icon'] ); ?>
										<div>
											<span class="label"><?php echo esc_html( $address['label'] ); ?></span>
											<span class="value"><?php echo esc_html( $address['value'] ); ?></span>
										</div>
									</div>
									<?php
							}
							?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
