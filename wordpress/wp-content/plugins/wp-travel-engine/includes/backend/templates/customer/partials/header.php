<?php

/**
 * Customer Header.
 *
 * @var string $customer_name
 */
?>

<div id="wpte-delete-confirm-modal" class="wpte-confirm-modal-overlay">
	<div class="wpte-confirm-modal">
		<button type="button" class="wpte-button wpte-cancel wpte-close-modal">
			<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
				<path d="M18 6L6 18M6 6L18 18" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
			</svg>
		</button>
		<div class="wpte-confirm-message">
			<h5><?php esc_html_e( 'Are you sure you want to delete?', 'wp-travel-engine' ); ?></h5>
			<p><?php esc_html_e( 'This action cannot be undone. It will permanently delete this from your site.', 'wp-travel-engine' ); ?></p>
		</div>
		<div class="wpte-button-group">
			<button type="button" class="wpte-button wpte-solid wpte-user-delete">
				<?php esc_html_e( 'Delete', 'wp-travel-engine' ); ?>
			</button>
			<button type="button" class="wpte-button wpte-outlined wpte-cancel">
				<?php esc_html_e( 'Cancel', 'wp-travel-engine' ); ?>
			</button>
		</div>
	</div>
</div>

<header class="wpte-page-header">
	<a href="<?php echo esc_url( admin_url( 'edit.php?post_type=customer' ) ); ?>" class="wpte-page-back-button">
		<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
			<path d="M19 12H5M5 12L12 19M5 12L12 5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
		</svg>
	</a>
	<h1><?php echo esc_html( $customer_name ); ?></h1>
	<div class="wpte-button-group">
		<button type="submit" class="wpte-button wpte-solid"><?php esc_html_e( 'Save', 'wp-travel-engine' ); ?></button>
		<div class="wpte-dropdown">
			<button type="button" class="wpte-button wpte-dropdown-button">
				<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
					<rect width="24" height="24" rx="6" fill="#F6F6F6" />
					<path d="M12 13C12.5523 13 13 12.5523 13 12C13 11.4477 12.5523 11 12 11C11.4477 11 11 11.4477 11 12C11 12.5523 11.4477 13 12 13Z" stroke="#0F1D23" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
					<path d="M19 13C19.5523 13 20 12.5523 20 12C20 11.4477 19.5523 11 19 11C18.4477 11 18 11.4477 18 12C18 12.5523 18.4477 13 19 13Z" stroke="#0F1D23" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
					<path d="M5 13C5.55228 13 6 12.5523 6 12C6 11.4477 5.55228 11 5 11C4.44772 11 4 11.4477 4 12C4 12.5523 4.44772 13 5 13Z" stroke="#0F1D23" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
				</svg>
			</button>
			<div class="wpte-dropdown-content">
				<a href="
				<?php
							$post_id = get_the_ID();
							echo admin_url( sprintf( 'post.php?post=%d&action=delete&_wpnonce=%s', $post_id, wp_create_nonce( 'delete-post_' . $post_id ) ) );
				?>
							" class="wpte-button" id="wpte-delete-customer">
					<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
						<path d="M13.3333 5.00002V4.33335C13.3333 3.39993 13.3333 2.93322 13.1517 2.5767C12.9919 2.2631 12.7369 2.00813 12.4233 1.84834C12.0668 1.66669 11.6001 1.66669 10.6667 1.66669H9.33333C8.39991 1.66669 7.9332 1.66669 7.57668 1.84834C7.26308 2.00813 7.00811 2.2631 6.84832 2.5767C6.66667 2.93322 6.66667 3.39993 6.66667 4.33335V5.00002M8.33333 9.58335V13.75M11.6667 9.58335V13.75M2.5 5.00002H17.5M15.8333 5.00002V14.3334C15.8333 15.7335 15.8333 16.4336 15.5608 16.9683C15.3212 17.4387 14.9387 17.8212 14.4683 18.0609C13.9335 18.3334 13.2335 18.3334 11.8333 18.3334H8.16667C6.76654 18.3334 6.06647 18.3334 5.53169 18.0609C5.06129 17.8212 4.67883 17.4387 4.43915 16.9683C4.16667 16.4336 4.16667 15.7335 4.16667 14.3334V5.00002" stroke="#F04438" stroke-width="1.67" stroke-linecap="round" stroke-linejoin="round" />
					</svg>
					<?php esc_html_e( 'Delete Account', 'wp-travel-engine' ); ?>
				</a>
			</div>
		</div>
	</div>
</header>
