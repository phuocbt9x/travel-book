<?php

/**
 * Customer new account email
 *
 * This template can be overridden by copying it to yourtheme/wp-travel-engine/emails/customer-new-account.php.
 *
 * HOWEVER, on occasion WP Travel Engine will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see         https://wptravelengine.com
 * @author      WP Travel Engine
 * @package     WP_Travel_Engine/Includes/Templates/Emails
 * @version     1.2.7
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$settings = wptravelengine_settings()->get();

$generate_user_account = $settings['generate_user_account'] ?? 'yes';
$custom_logo_id        = get_theme_mod( 'custom_logo' );
$image                 = wp_get_attachment_image_src( $custom_logo_id, 'full' );
$manual_password       = '' !== $user_pass ? $user_pass : false; // If the user is signing up manually, password is sent to the email.
$user                  = new \WP_User( (int) $user_id );
$rp_key                = get_password_reset_key( $user );

$rp_link = esc_url(
	add_query_arg(
		array(
			'key'   => $rp_key,
			'login' => rawurlencode( $user_login ),
		),
		wp_travel_engine_lostpassword_url()
	)
);
?>

<table
	style="width: 100%;background-color: #F5F4F6;padding: 60px 24px; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol';">
	<?php if ( has_custom_logo() ) : ?>
		<tr>
			<td>
				<h3 style="text-align: center;font-size: 32px;margin: 0 0 24px"><img
						src="<?php echo esc_url( $image[0] ); ?>"
						style="
					max-height: 56px;
					width: auto;
				"></h3>
			</td>
		</tr>
	<?php endif; ?>
	<tr>
		<td>
			<table style="
				width: 100%;
				border-spacing: 0px;
				max-width: 526px;
				margin: 0 auto;
				font-size: 16px;
				line-height: 1.5;
				border: 1px solid #efefef;
				border-radius: 8px;
				background-color: #ffffff;
				box-shadow: 0 2px 10px rgba(0,0,0,0.05);
				padding: 24px;">
				<tbody>
				<tr>
					<td style="padding: 24px 0px 12px;text-align: center;"><?php echo esc_html__( 'Hi ', 'wp-travel-engine' ) . esc_html( $first_name ); ?>
					</td>
				</tr>
				<tr>
					<td>
						<?php echo esc_html__( 'Your account has been created. Below are your login details:', 'wp-travel-engine' ); ?>
					</td>
				</tr>
				<tr>
					<td style="padding: 24px 0px 12px;text-align: center;"><?php echo esc_html__( 'Your username:', 'wp-travel-engine' ); ?>
						<a
							href="<?php echo esc_html( $user_login ); ?>"><?php echo esc_html( $user_login ); ?></a>
					</td>
				</td>
				<tr>
					<td style="padding: 8px 0px 32px;text-align: center;">
						<?php echo esc_html__( 'Your password:', 'wp-travel-engine' ); ?>
						<?php
						if ( $manual_password ) {
							echo esc_html( $manual_password );
						} else {
							printf( '<a target="_blank" href="%s">%s</a>', esc_url( $rp_link ), esc_html__( 'Set Your Password', 'wp-travel-engine' ) );
						}
						?>
					</td>
				</tr>
			
				<tr>
					<td style="padding: 24px 0px;text-align: center;border-top: 1px solid rgba(0,0,0,0.1);line-height: 1.75;">
						<?php printf( esc_html__( 'For your security, this link will expire in 24 hours. If it does, simply request a new one from our: %s.', 'wp-travel-engine' ), make_clickable( esc_url( wp_travel_engine_get_page_permalink_by_id( wp_travel_engine_get_dashboard_page_id() ) ) ) ); ?>
					</td>
				</tr>
				<tr>
					<td style="padding: 32px 0px 24px;text-align: center;border-top: 1px solid rgba(0,0,0,0.1);">
						<?php echo esc_html__( 'Regards,', 'wp-travel-engine' ); ?>
						<a href="<?php echo esc_url( home_url( '/' ) ); ?>" target="_blank" style="
								display: inline-block;
								text-decoration: none;
								padding: 14px 40px;
								background-color: #1F2324;
								color: #ffffff;
								border-radius: 4px;
								line-height: 1.75;
							"><?php bloginfo( 'name' ); ?></a>
					</td>
				</tr>
				</tbody>
			</table>
		</td>
	</tr>
</table>
