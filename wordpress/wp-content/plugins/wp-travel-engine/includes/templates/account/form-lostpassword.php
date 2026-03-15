<?php

/**
 * Lost password form
 *
 * This template can be overridden by copying it to yourtheme/wp-travel-engine/account/form-lostpassword.php.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

wp_enqueue_script( 'parsely' );
$settings              = wptravelengine_settings()->get();
$lost_password_message = $settings['forgot_page_description'] ?? __( 'If an account with that email exist, we\'ll send you a link to reset your password. Please check your inbox including spam/junk folder.', 'wp-travel-engine' );
$forgot_page_label     = $settings['forgot_page_label'] ?? __( 'Reset Your Password', 'wp-travel-engine' );
$user_account_page_id  = wp_travel_engine_get_dashboard_page_id();

// Notices.
wp_travel_engine_print_notices();
?>
<div class="wpte-lrf-wrap wpte-forgot-pass">
	<div class="wpte-lrf-top">
		<form method="post" class="wpte-lrf">
			<div class="wpte-lrf-head">
				<div class="wpte-lrf-desc">
					<?php echo wp_kses_post( wpautop( apply_filters( 'wp_travel_engine_lost_password_message', $lost_password_message ) ) ); ?>
				</div>
				<h2 class="wpte-lrf-title">
					<?php echo esc_html( $forgot_page_label ); ?>
				</h2>
			</div>
			<div class="wpte-form-field wpte-material-ui-input-control">
				<label for="username"><?php echo esc_attr__( 'Username or Email', 'wp-travel-engine' ); ?><span class="required">*</span></label>
				<input id="username" required type="text" name="user_login" id="user_login" value="" placeholder="<?php echo esc_attr__( 'Email or username', 'wp-travel-engine' ); ?>">
				<fieldset>
					<legend>
						<span><?php echo esc_attr__( 'Username or Email', 'wp-travel-engine' ); ?><span class="required">*</span></span>
					</legend>
				</fieldset>
			</div>
			<?php do_action( 'wp_travel_engine_lostpassword_form' ); ?>
			<input type="hidden" name="wp_travel_engine_reset_password" value="true" />
			<div class="wpte-form-field wpte-form-submit">
				<input type="submit" name="wp_travel_engine_reset_password_submit" value="<?php echo esc_attr__( 'Reset My Password', 'wp-travel-engine' ); ?>">
			</div>
			<?php wp_nonce_field( 'wp_travel_engine_lost_password' ); ?>
		</form>
		<div class="wpte-lrf-bottom">
			<a href="<?php echo esc_url( get_permalink( $user_account_page_id ) ); ?>" class="wpte-btn-secondary">
				<svg width="12" height="13" viewBox="0 0 12 13" fill="none" xmlns="http://www.w3.org/2000/svg">
					<path d="M0.666504 3.66667H7.33317C9.54231 3.66667 11.3332 5.45753 11.3332 7.66667C11.3332 9.87581 9.54231 11.6667 7.33317 11.6667H0.666504M0.666504 3.66667L3.33317 1M0.666504 3.66667L3.33317 6.33333" stroke="currentColor" stroke-width="1.33333" stroke-linecap="round" stroke-linejoin="round" />
				</svg>
				<?php echo esc_html__( 'Back to login', 'wp-travel-engine' ); ?>
			</a>
		</div>
	</div>
</div>
<?php
