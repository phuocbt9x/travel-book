<?php
/**
 * Empty cart content.
 *
 * @since 6.3.0
 */
?>
<div class="wpte-checkout__main">
	<div class="wpte-checkout__container">
		<div class="wpte-checkout__box wpte-checkout__cart-empty-box">
			<div class="wpte-checkout__box-content">
				<p><strong><?php echo esc_html__( 'Your cart is empty!', 'wp-travel-engine' ); ?></strong></p>
				<p><?php echo esc_html__( 'It looks like you haven\'t added any trips or tours to your cart yet.', 'wp-travel-engine' ); ?></p>
				<p>
				<?php
					printf(
						__( 'Explore our %s and plan your next adventure today!', 'wp-travel-engine' ),
						sprintf( '<a href="%s">%s</a>', get_post_type_archive_link( WP_TRAVEL_ENGINE_POST_TYPE ), __( 'Trips', 'wp-travel-engine' ) ),
					);
					?>
				</p>
			</div>
		</div>
	</div>
</div>

