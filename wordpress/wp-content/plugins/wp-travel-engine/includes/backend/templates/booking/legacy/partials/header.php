<?php
/**
 * Booking Header.
 *
 * @since 6.4.0
 */

/**
 * @var Booking $booking
 */

use WPTravelEngine\Core\Models\Post\Booking;

?>
<!-- .wpte-page-header -->
<header class="wpte-page-header">
		<a href="<?php echo 'edit' === $template_mode ? esc_url( admin_url( "post.php?post={$booking->get_id()}&action=edit" ) ) : esc_url( admin_url( 'edit.php?post_type=booking' ) ); ?>" class="wpte-page-back-button">
			<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
				<path d="M19 12H5M5 12L12 19M5 12L12 5" stroke="currentColor" stroke-width="2" stroke-linecap="round"
						stroke-linejoin="round" />
			</svg>
		</a>
	<?php if ( 'edit' === $template_mode ) : ?>
		<div class="wpte-fields-grid" data-columns="1">
			<div class="wpte-field">
				<input type="text" name="post_title" value="<?php echo $booking->post->post_title == '' ? esc_html( 'Booking #' . $booking->get_id() ) : esc_html( $booking->post->post_title ); ?>" />
			</div>
		</div>
	<?php else : ?>
		<h1><?php echo esc_html( $booking->get_title() ); ?></h1>
		<?php
	endif;

	$status_tag       = sprintf( '<span class="wpte-tag %1$s">%1$s</span>', $booking->get_booking_status() );
	$admin_edited_tag = wptravelengine_toggled( $booking->get_meta( '_user_edited' ) ) ? sprintf( '<span class="wpte-tag %1$s">%1$s</span>', __( 'Customized Reservation', 'wp-travel-engine' ) ) : '';
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
	<div class="wpte-button-group">
		<?php if ( 'edit' === $template_mode ) : ?>
			<div class="wpte-button-group">
				<button style="display:block;" id="wpte-booking-submit-button" type="submit"
						class="wpte-button wpte-solid"><?php echo __( 'Save', 'wp-travel-engine' ); ?></button>
			</div>
		<?php else : ?>
			<a id="wpte-booking-edit-button"
				type="button"
				href="<?php echo esc_url( admin_url( "post.php?post={$booking->get_id()}&action=edit&wptravelengine_action=edit" ) ); ?>"
				class="wpte-button wpte-outlined">
				<?php echo __( 'Edit', 'wp-travel-engine' ); ?>
			</a>
		<?php endif ?>
	</div>
	<!-- </div> -->
</header> <!-- end .wpte-page-header -->
