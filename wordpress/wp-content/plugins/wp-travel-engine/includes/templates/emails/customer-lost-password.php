<?php
/**
 * Customer Lost Password email Template
 *
 * This template can be overridden by copying it to yourtheme/wp-travel-engine/emails/customer-lost-password.php.
 *
 * HOWEVER, on occasion WP Travel Engine will need to update template files and you (the theme developer).
 * will need to copy the new files to your theme to maintain compatibility. We try to do this.
 * as little as possible, but it does happen. When this occurs the version of the template file will.
 * be bumped and the readme will list any important changes.
 *
 * @see         https://wptravelengine.com
 * @author      Wp Travel Engine
 * @package     wp-travel-engine/includes/templates
 * @since       1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

// Set Login Data and Reset Keys.
$user_login = $args['user_login'];
$reset_key  = $args['reset_key'];
?>
<table style="width:100%;">
	<tr>
		<td colspan="2" style="font-size: 24px;line-height: 1.5;font-weight: bold;">
			<?php echo esc_html__( 'Hi', 'wp-travel-engine' ) . ' ' . esc_html( $first_name ); ?>,
		</td>
	</tr>
	<tr>
		<td colspan="2" style="padding: 16px 0 8px;">
			<?php echo esc_html__( 'Welcome to', 'wp-travel-engine' ); ?> <strong><?php bloginfo( 'name' ) . esc_html( '!' ); ?></strong>
		<p style="margin: 8px 0 0;"><?php printf( __( 'We received a request to reset your password for your %s account.', 'wp-travel-engine' ), $user_login ); ?></p>
		</td>
	</tr>
	<tr>
		<td colspan="2">
			<?php echo esc_html__( 'Click the link below to set a new password:', 'wp-travel-engine' ); ?>
			<a target="_blank" class="link" href="
				<?php
				echo esc_url(
					add_query_arg(
						array(
							'key'   => $reset_key,
							'login' => rawurlencode( $user_login ),
						),
						wp_travel_engine_lostpassword_url()
					)
				);
				?>
				">
				<?php esc_html_e( 'Reset Password', 'wp-travel-engine' ); ?>
			</a>
		</td>
	</tr>
	<tr>
		<td colspan="2" style="padding: 16px 0;">
			<?php echo esc_html__( 'This link will expire in 24 hours for your security.', 'wp-travel-engine' ); ?>
		</td>
	</tr>
	<tr>
		<td colspan="2" style="padding: 16px 0;">
			<?php echo esc_html__( 'If you didn\'t request this, please ignore this email or contact us.', 'wp-travel-engine' ); ?>
		</td>
	</tr>
	<tr>
		<td colspan="2">
			<?php echo esc_html__( 'Thanks,', 'wp-travel-engine' ); ?>
			<br>
			<?php bloginfo( 'name' ); ?>
			<?php echo esc_html__( 'Team', 'wp-travel-engine' ); ?>
		</td>
	</tr>
</table>