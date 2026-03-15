<?php

/**
 * User dashboard template.
 *
 * @package WP_Travel
 */
wp_enqueue_script( 'wte-dropzone' );
wp_enqueue_style( 'wte-dropzone' );
// Print Errors / Notices.
wp_travel_engine_print_notices();

// Set User.
$current_user         = $args['current_user'];
$user_dashboard_menus = wp_travel_engine_sort_array_by_priority( $args['dashboard_menus'] );
$bookings = array();
$bookings_glance = false;

if ( ! empty( $current_user->user_email ) ) {
	$customer_id = \WPTravelEngine\Core\Models\Post\Customer::is_exists( $current_user->user_email );
	if ( $customer_id ) {
		$customer = new \WPTravelEngine\Core\Models\Post\Customer( $customer_id );
		$customer_bookings = $customer->get_meta( 'wp_travel_engine_bookings' );
		if ( ! empty( $customer_bookings ) && is_array( $customer_bookings ) ) {
			$bookings = array_map( 'intval', $customer_bookings );
		}
	}
}

/**
 * Merge user meta bookings for backward compatibility with legacy data.
 */
$user_bookings = get_user_meta( $current_user->ID, 'wp_travel_engine_user_bookings', true );
if ( ! empty( $user_bookings ) && is_array( $user_bookings ) ) {
	$user_bookings = array_map( 'intval', $user_bookings );
	$bookings = array_unique( array_merge( $bookings, $user_bookings ) );
}

// Resverse Chronological Order For Bookings.
if ( ! empty( $bookings ) && is_array( $bookings ) ) {
	$bookings        = array_reverse( $bookings );
	$bookings_glance = array_slice( $bookings, 0, 5 );
}

$biling_glance_data = get_user_meta( $current_user->ID, 'wp_travel_engine_customer_billing_details', true );
$settings           = wptravelengine_settings()->get();
$dashboard_id       = isset( $settings['pages']['wp_travel_engine_dashboard_page'] ) ? esc_attr( $settings['pages']['wp_travel_engine_dashboard_page'] ) : wp_travel_engine_get_page_id( 'my-account' );

?>
	<div class="wpte-lrf-wrap wpte-dashboard">
		<div class="wpte-lrf-head wpte-full wpte-bg">
			<div class="wpte-container container">		
				<div class="wpte-user-title-wrapper">
					<div class=" wpte-left-aligned">
						<h2 class="wpte-my-account-page-title"><?php echo esc_html( get_the_title( $dashboard_id ) ); ?></h2>
						<p class="wpte-lrf-description"><?php esc_html_e( 'You can manage your booking and profile from here.', 'wp-travel-engine' ); ?></p>
					</div>
					<div class="wpte-ud-tabs">
						<?php foreach ( $user_dashboard_menus as $key => $menu ) : ?>
							<?php
							if ( $menu['menu_class'] == 'lrf-bookings' ) {
								$cndtnl_active_class = 'active';
							} elseif ( $menu['menu_class'] == 'lrf-dashboard' && ! isset( $_GET['action'] ) ) {
								$cndtnl_active_class = 'active';
							} else {
								$cndtnl_active_class = '';
							}
							?>
							<a data-target="<?php echo esc_attr( $menu['menu_class'] ); ?>" class="wpte-ud-tab <?php echo esc_attr( $cndtnl_active_class ); ?>" href="Javascript:void(0);"><?php echo esc_html( $menu['menu_title'] ); ?></a>
						<?php endforeach; ?>
					</div>
					<div class="wpte-right-aligned">
						<?php
						if ( isset( $current_user->user_email ) && $current_user->user_email != '' ) {
							echo get_avatar( $current_user->user_email );
						} elseif ( has_custom_logo() ) {
							?>
								<div class="wpte-lrf-logo">
									<?php the_custom_logo(); ?>
								</div>
								<?php

						}
						?>
						<a class="lrf-userprofile-logout" href="<?php echo esc_url( wp_logout_url( wp_travel_engine_get_page_permalink_by_id( wp_travel_engine_get_dashboard_page_id() ) ) ); ?>">
							<span><?php esc_html_e( 'Log Out', 'wp-travel-engine' ); ?></span>
						</a>
					</div>
				</div>
			</div>
		</div>

		<div class="wpte-lrf-content-area">
			<div class="wpte-lrf-main">
				<div class="wpte-full">
					<div class="wpte-container container">
						<?php foreach ( $user_dashboard_menus as $key => $menu ) : ?>
							<?php
							// if ( $menu['menu_class'] == 'lrf-bookings' && isset( $_GET['action'] ) && $_GET['action'] == 'partial-payment' ) {
							if ( $menu['menu_class'] == 'lrf-bookings' ) {
								$cndtnl_active_class = 'active';
							} elseif ( $menu['menu_class'] == 'lrf-dashboard' && ! isset( $_GET['action'] ) ) {
								$cndtnl_active_class = 'active';
							} else {
								$cndtnl_active_class = '';
							}
							?>
							<div class="wpte-ud-tab-content lrf-<?php echo esc_attr( $key ); ?>-content <?php echo esc_attr( $menu['menu_class'] ); ?> <?php echo esc_attr( $cndtnl_active_class ); ?>">
								<?php
								if ( ! empty( $menu['menu_content_cb'] ) ) {
									$args['bookings_glance']    = $bookings_glance;
									$args['biling_glance_data'] = $biling_glance_data;
									$args['bookings']           = $bookings;
									call_user_func( $menu['menu_content_cb'], $args );
								}
								?>
							</div><!-- .lrf-dashboard-content -->
						<?php endforeach; ?>
					</div>
				</div>
			</div><!-- .wpte-lrf-main -->
		</div><!-- .wpte-lrf-content-area -->
	</div><!-- .wpte-lrf-wrap -->
<?php
