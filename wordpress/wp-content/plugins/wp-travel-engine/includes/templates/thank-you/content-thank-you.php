<?php
/**
 * Thank You Page Content Template.
 *
 * @since 6.3.3
 */
?>
<style>
	.wpte-thankyou__page-layout {
		display: flex;
		flex-wrap: wrap;
		gap: 48px;
	}

	.wpte-thankyou__page-layout > div {
		flex: 1;
	}
</style>
<div class="wpte-thankyou__main">
	<div class="wpte-thankyou__container">
		<div class="wpte-thankyou__page-layout">
			<?php do_action( 'wptravelengine_thankyou_booking_details' ); ?>
			<?php do_action( 'wptravelengine_thankyou_cart_summary' ); ?>
		</div>
	</div>
</div>
