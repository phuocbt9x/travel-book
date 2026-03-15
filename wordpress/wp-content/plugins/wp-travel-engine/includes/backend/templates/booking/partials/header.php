<?php
/**
 * Booking Header.
 *
 * @var \WPTravelEngine\Core\Models\Post\Booking $booking
 * @since 6.4.0
 */

$cart_line_items = $cart_info->get_item()->get_line_items();

wptravelengine_set_template_args( compact( 'cart_line_items' ) );

?>
<!-- .wpte-page-header -->
<header class="wpte-page-header">
	<?php
	if ( $booking->get_id() ) :
		// Determine back button URL based on booking status
		if ( $booking->get_booking_status() === 'auto-draft' ) {
			// New booking - go back to bookings list
			$back_url = esc_url( admin_url( 'edit.php?post_type=booking' ) );
		} elseif ( 'edit' === $template_mode ) {
			// Existing booking in edit mode - go to view mode
			$back_url = esc_url( admin_url( "post.php?post={$booking->get_id()}&action=edit" ) );
		} else {
			// View mode - go to bookings list
			$back_url = esc_url( admin_url( 'edit.php?post_type=booking' ) );
		}
		?>
		<a href="<?php echo $back_url; ?>" class="wpte-page-back-button"
			data-post-status="<?php echo esc_attr( $booking->get_booking_status() ); ?>">
			<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
				<path d="M19 12H5M5 12L12 19M5 12L12 5" stroke="currentColor" stroke-width="2" stroke-linecap="round"
					stroke-linejoin="round" />
			</svg>
		</a>
	<?php endif; ?>
	<?php if ( 'edit' === $template_mode ) : ?>
		<div class="wpte-fields-grid" data-columns="1">
			<div class="wpte-field">
				<input type="text" name="post_title"
					value="<?php echo $booking->post->post_title == '' ? esc_html( 'Booking #' . $booking->get_id() ) : esc_html( $booking->post->post_title ); ?>" />
			</div>
		</div>
	<?php else : ?>
		<h1><?php echo esc_html( $booking->get_title() ); ?></h1>
		<?php
	endif;

	$status_tag       = sprintf( '<span class="wpte-tag %1$s">%1$s</span>', $booking->get_booking_status() );
	$admin_edited_tag = wptravelengine_toggled( $booking->get_meta( '_user_edited' ) ) ? sprintf( '<span class="wpte-tag %1$s">%1$s</span>', __( 'Customized Reservation', 'wp-travel-engine' ) ) : '';
	if ( $booking->get_booking_status() !== 'auto-draft' ) {
		?>
		<div class="wpte-page-header-content">
			<?php
			printf(
				'<div class="wpte-tags-wrap">%1$s%2$s</div>',
				$admin_edited_tag,
				$status_tag,
			);
			?>
		</div>
	<?php } ?>
	<!-- </div> -->
</header> <!-- end .wpte-page-header -->