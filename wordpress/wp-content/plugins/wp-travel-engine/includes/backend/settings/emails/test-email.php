<?php
/**
 * Test email settings
 *
 * @package WP_Travel_Engine
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$user_email   = '';
$current_user = wp_get_current_user();

if ( $current_user->ID !== 0 ) {
	$user_email = $current_user->user_email;
}
?>
<div class="wpte-field wpte-text wpte-floated">
	<label class="wpte-field-label"
			for="wptravelengine_send_to_email"><?php esc_html_e( 'Send to', 'wp-travel-engine' ); ?>
	</label>
	<input type="email"
			id="wptravelengine_send_to_email"
			value="<?php echo esc_attr( $user_email ); ?>"
			placeholder="Your Email"
	/>
	<span class="wpte-tooltip">
		<?php esc_html_e( 'Enter the email to test.', 'wp-travel-engine' ); ?>
	</span>

	<div class="wpte-field">
		<button
			data-nonce="<?php echo esc_attr( wp_create_nonce( 'wptravelengine_test_email_nonce' ) ); ?>"
			class="wptravelengine_test_email button" type="submit"
			name="wptravelengine_test_email"> <?php esc_html_e( 'Send Test Email', 'wp-travel-engine' ); ?></button>
	</div>
	<div class="wpte-info-block">
		<b><?php esc_html_e( 'Note:', 'wp-travel-engine' ); ?></b>
		<p>
			<?php
			echo wp_kses(
				sprintf(
					__( 'After sending the test email and receiving a success message, please check your inbox. If your server is properly configured for email sending, you will receive the test email. However, if something seems amiss or the email does not arrive, please refer to the %s for troubleshooting assistance.', 'wp-travel-engine' ),
					'<a href="https://docs.wptravelengine.com/article/email-troubleshooting/" target="_blank" class="wptravelengine-smtp-guidance">' . __( 'Email FAQ page', 'wp-travel-engine' ) . '</a>',
				),
				array(
					'a' => array(
						'href'   => array(),
						'class'  => array(),
						'target' => array(),
					),
				)
			);
			?>
		</p>
	</div>

</div>
