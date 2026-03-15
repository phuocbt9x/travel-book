<?php
/**
 * @since 6.3.0
 */
?>
<!-- Checkout Footer -->
<footer class="wpte-checkout__footer">
	<div class="wpte-checkout__container">
		<div class="wpte-checkout__copyright">
			<?php
			$copyright = $attributes['footer_copyright'] ?? __( 'Copyright © %1$current_year% %2$site_name% . All Rights Reserved.', 'wp-travel-engine' );
			echo wp_kses_post(
				strtr(
					$copyright,
					array(
						'%current_year%' => date( 'Y' ),
						'%site_name%'    => get_bloginfo( 'name' ),
					)
				)
			);
			?>
		</div>
	</div>
</footer>
