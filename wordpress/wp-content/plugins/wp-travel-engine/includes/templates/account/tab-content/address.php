<?php
/**
 * Address Tab content.
 */
	// Get current user.
	$current_user = $args['current_user'];
?>
<div class="wpte-lrf-block-wrap">
	<div class="wpte-lrf-block">
		<?php
			wte_get_template(
				'account/form-edit-billing.php',
				array(
					'user' => $current_user,
				)
			);
			?>
	</div>
</div>
<?php
