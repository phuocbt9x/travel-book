<?php
/**
 * Dashboard General Settings.
 *
 * @addsince 8.5.2
 * Automatically Generate User Account.
 */
// Get saved settings for the tab.
$wp_travel_engine_settings                = get_option( 'wp_travel_engine_settings', true );
$enable_checkout_customer_registration    = $wp_travel_engine_settings['enable_checkout_customer_registration'] ?? 'no';
$disable_my_account_customer_registration = $wp_travel_engine_settings['disable_my_account_customer_registration'] ?? 'no';
$generate_username_from_email             = $wp_travel_engine_settings['generate_username_from_email'] ?? 'no';
$generate_user_password                   = $wp_travel_engine_settings['generate_user_password'] ?? 'no';
$generate_user_account                    = $wp_travel_engine_settings['generate_user_account'] ?? 'yes';
?>

<div class="wpte-field wpte-checkbox advance-checkbox">
	<label class="wpte-field-label"
			for="wp_travel_engine_settings[generate_user_account]"><?php esc_html_e( 'Automatically Generate User Account', 'wp-travel-engine' ); ?></label>
	<div class="wpte-checkbox-wrap">
		<input type="hidden" name="wp_travel_engine_settings[generate_user_account]" value="yes">
		<input type="checkbox"
				data-onchange
				data-onchange-toggle-target="[data-generate-account-section]"
				data-onchange-toggle-off-value="yes"
				id="wp_travel_engine_settings[generate_user_account]"
				name="wp_travel_engine_settings[generate_user_account]"
				value="yes" <?php checked( $generate_user_account, 'yes' ); ?>>
		<label for="wp_travel_engine_settings[generate_user_account]"></label>
	</div>
	<span
		class="wpte-tooltip"><?php esc_html_e( 'It automatically creates user account (username and password) when booking a trip and sends the details to the customer', 'wp-travel-engine' ); ?></span>
</div>

<div class="<?php wptravelengine_hidden_class( 'yes' === $generate_user_account, true ); ?>"
	data-generate-account-section>
	<div class="wpte-field wpte-checkbox advance-checkbox">
		<label class="wpte-field-label"
				for="wp_travel_engine_settings[enable_checkout_customer_registration]"><?php esc_html_e( 'Require Registration for Booking', 'wp-travel-engine' ); ?></label>
		<div class="wpte-checkbox-wrap">
			<input type="hidden" name="wp_travel_engine_settings[enable_checkout_customer_registration]" value="no">
			<input type="checkbox" id="wp_travel_engine_settings[enable_checkout_customer_registration]"
					name="wp_travel_engine_settings[enable_checkout_customer_registration]"
					value="yes" <?php checked( $enable_checkout_customer_registration, 'yes' ); ?>>
			<label for="wp_travel_engine_settings[enable_checkout_customer_registration]"></label>
		</div>
		<span
			class="wpte-tooltip"><?php esc_html_e( 'Customers must sign in or create an account to complete trip bookings.', 'wp-travel-engine' ); ?></span>
	</div>

	<div class="wpte-field wpte-checkbox advance-checkbox">
		<label class="wpte-field-label"
				for="wp_travel_engine_settings[disable_my_account_customer_registration]"><?php esc_html_e( 'Disable Customer Registration', 'wp-travel-engine' ); ?></label>
		<div class="wpte-checkbox-wrap">
			<input type="hidden" name="wp_travel_engine_settings[disable_my_account_customer_registration]" value="no">
			<input type="checkbox" id="wp_travel_engine_settings[disable_my_account_customer_registration]"
					name="wp_travel_engine_settings[disable_my_account_customer_registration]"
					value="yes" <?php checked( $disable_my_account_customer_registration, 'yes' ); ?>>
			<label for="wp_travel_engine_settings[disable_my_account_customer_registration]"></label>
		</div>
		<span
			class="wpte-tooltip"><?php esc_html_e( 'Prevent customers from creating new accounts on my account page.', 'wp-travel-engine' ); ?></span>
	</div>

	<div class="wpte-field wpte-checkbox advance-checkbox">
		<label class="wpte-field-label"
				for="wp_travel_engine_settings[generate_username_from_email]"><?php esc_html_e( 'Generate Username From Customer Email', 'wp-travel-engine' ); ?></label>
		<div class="wpte-checkbox-wrap">
			<input type="hidden" name="wp_travel_engine_settings[generate_username_from_email]" value="no">
			<input type="checkbox" id="wp_travel_engine_settings[generate_username_from_email]"
					name="wp_travel_engine_settings[generate_username_from_email]"
					value="yes" <?php checked( $generate_username_from_email, 'yes' ); ?>>
			<label for="wp_travel_engine_settings[generate_username_from_email]"></label>
		</div>
		<span
			class="wpte-tooltip"><?php esc_html_e( "The customer's email will be used to generate their username.", 'wp-travel-engine' ); ?></span>
	</div>

	<div class="wpte-field wpte-checkbox advance-checkbox">
		<label class="wpte-field-label"
				for="wp_travel_engine_settings[generate_user_password]"><?php esc_html_e( 'Generate Secure Passwords', 'wp-travel-engine' ); ?></label>
		<div class="wpte-checkbox-wrap">
			<input type="hidden" name="wp_travel_engine_settings[generate_user_password]" value="no">
			<input type="checkbox" id="wp_travel_engine_settings[generate_user_password]"
					name="wp_travel_engine_settings[generate_user_password]"
					value="yes" <?php checked( $generate_user_password, 'yes' ); ?>>
			<label for="wp_travel_engine_settings[generate_user_password]"></label>
		</div>
		<span
			class="wpte-tooltip"><?php esc_html_e( 'Customers will receive a strong, randomly generated password upon signup.', 'wp-travel-engine' ); ?></span>
	</div>

</div>
