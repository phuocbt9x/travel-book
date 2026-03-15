<?php
/**
 * Customer Details Metabox Content.
 */
?>
<div id="wptravelengine-customer-details">
	<?php wptravelengine_get_admin_template( 'customer/partials/header.php' ); ?>
	<!-- .wpte-form-container -->
	<div class="wpte-form-container">
		<div class="wpte-customer-details-layout">

			<!-- Sidebar Area -->
			<?php wptravelengine_get_admin_template( 'customer/partials/sidebar.php' ); ?>
			<!-- End of Sidebar Area -->

			<!-- Customer Fields Area -->
			<div class="wpte-customer-fields-area">

				<!-- Custom Profile Section -->
				<?php wptravelengine_get_admin_template( 'customer/partials/profile.php' ); ?>
				<!-- End of Custom Profile Section -->

				<!-- Orders Section -->
				<?php wptravelengine_get_admin_template( 'customer/partials/orders.php' ); ?>
				<!-- End of Orders Section -->

				<!-- Notes Section -->
				<?php wptravelengine_get_admin_template( 'customer/partials/notes.php' ); ?>
				<!-- End of Notes Section -->
			</div>
			<!-- End of Customer Fields Area -->
		</div>
	</div> <!-- end .wpte-form-container -->
</div>
