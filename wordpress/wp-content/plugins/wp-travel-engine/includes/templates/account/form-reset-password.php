<?php

/**
 * Customer Lost Password Reset Form.
 *
 * This template can be overridden by copying it to yourtheme/wp-travel/account/form-reset-password.php.
 *
 * HOWEVER, on occasion wp-travel will need to update template files and you (the theme developer).
 * will need to copy the new files to your theme to maintain compatibility. We try to do this.
 * as little as possible, but it does happen. When this occurs the version of the template file will.
 * be bumped and the readme will list any important changes.
 *
 * @see         https://wptravelengine.com
 * @author      Wp Travel Engine
 * @package     wp-travel/Templates
 * @since       1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

wp_enqueue_script( 'parsely' );

$user_account_page_id    = wp_travel_engine_get_dashboard_page_id();
$settings                = wptravelengine_settings()->get();
$set_password_page_label = $settings['set_password_page_label'] ?? __( 'Set New Password', 'wp-travel-engine' );

// Print Errors / Notices.
wp_travel_engine_print_notices(); ?>
<div class="wpte-lrf-wrap wpte-reset-pass">
	<div class="wpte-lrf-top">
		<div class="wpte-lrf-head">
			<h2 class="wpte-lrf-title"><?php echo wp_kses_post( apply_filters( 'wp_travel_engine_reset_password_message', $set_password_page_label ) ); ?></h2>
		</div>
		<form method="post" class="wpte-lrf">
			<div class="wpte-form-field wpte-material-ui-input-control">
				<label for="password_1"><?php echo esc_attr__( 'Enter new password', 'wp-travel-engine' ); ?><span class="required">*</span></label>
				<input required name="password_1" id="password_1" type="password" placeholder="<?php esc_html_e( 'New password', 'wp-travel-engine' ); ?>">
				<fieldset>
					<legend>
						<span><?php echo esc_attr__( 'Enter new password', 'wp-travel-engine' ); ?><span class="required">*</span></span>
					</legend>
				</fieldset>
			</div>
			<div class="wpte-form-field wpte-material-ui-input-control">
				<label for="password_2"><?php echo esc_attr__( 'Re-enter new password', 'wp-travel-engine' ); ?><span class="required">*</span></label>
				<input required name="password_2" id="password_2" type="password" placeholder="<?php esc_html_e( 'Re-enter new password', 'wp-travel-engine' ); ?>">
				<fieldset>
					<legend>
						<span><?php echo esc_attr__( 'Re-enter new password', 'wp-travel-engine' ); ?><span class="required">*</span></span>
					</legend>
				</fieldset>
			</div>

			<input type="hidden" name="reset_key" value="<?php echo esc_attr( $args['key'] ); ?>" />
			<input type="hidden" name="reset_login" value="<?php echo esc_attr( $args['login'] ); ?>" />

			<?php do_action( 'wp_travel_resetpassword_form' ); ?>

			<div class="wpte-form-field wpte-form-submit">
				<input type="hidden" name="wp_travel_engine_reset_password" value="true" />
				<input type="submit" name="wp_travel_engine_reset_password_submit" value="<?php esc_attr_e( 'Set Password', 'wp-travel-engine' ); ?>">
			</div>
			<?php wp_nonce_field( 'wp_travel_engine_reset_password_nonce' ); ?>
		</form>
	</div>
</div>
<?php
