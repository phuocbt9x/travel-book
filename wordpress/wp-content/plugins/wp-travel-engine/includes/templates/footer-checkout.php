<?php
/**
 * Checkout V2 Footer Template.
 *
 * @since 6.3.0
 */

wptravelengine_get_template( 'template-checkout/content-footer.php' );
?>
			</div><!-- \.wpte-checkout -->
		</div><!-- \#page -->
		<?php wp_footer(); ?>
	</body>
</html>
<?php
// Include footer if enabled in settings
if ( $attributes['footer'] == 'default' ) {
	get_footer();
}
?>
