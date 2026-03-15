<?php
/**
 * Email Header
 *
 * @since 6.5.0
 */
use WPTravelEngine\Core\Models\Settings\Options;

$settings = Options::get( 'wp_travel_engine_settings' );
$logo_url = $settings['email']['logo']['url'];
?>
<div style="background-color: #F4F4F4;width: 100%;font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; padding-bottom: 72px;">
<table style="max-width: 590px; width: 100%;margin: 0 auto;color: #111322;font-size: 14px;line-height: 1.7;">
	<thead>
		<tr>
			<th style="text-align: center;padding: 32px 0 24px;">
				<?php if ( ! empty( $logo_url ) ) : ?>
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>">
						<img style="max-width: 100px;margin: 0 auto;height: auto;" src="<?php echo esc_url( $logo_url ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>">
					</a>
				<?php else : ?>
					<h2 style="margin: 0;"><?php echo esc_html( get_bloginfo( 'name' ) ); ?></h2>
				<?php endif; ?>
			</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td style="padding: 32px;background-color: #fff;border-radius: 8px;">
