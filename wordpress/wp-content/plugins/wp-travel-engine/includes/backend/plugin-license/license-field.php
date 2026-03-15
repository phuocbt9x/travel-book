<?php
/* @var $license \WPTravelEngine\Abstracts\LicenseController */
$active_class = $license->valid() ? 'wte-license-activate' : '';
$color        = $license->valid() ? 'style=color:#11b411;' : 'style=color:#f66757;';
?>

<div class="wpte-field wpte-floated <?php echo esc_attr( $active_class ); ?>">
	<label
		for="wp_travel_engine_license[<?php echo esc_attr( $license->slug ); ?>_license_key]"
		class="wpte-field-label"><?php echo esc_html( $license->item_name ); ?></label>
	<input id="<?php echo esc_attr( $license->slug ); ?>"
			class="wp_travel_engine_addon_license_key"
			name="wp_travel_engine_license[<?php echo esc_attr( $license->slug ); ?>_license_key]"
			type="text" class="regular-text"
			value="<?php echo esc_attr( $license->license() ); ?>" />
	<?php if ( $license->valid() ) : ?>
		<span class="wte-license-active">
			<?php wptravelengine_svg_by_fa_icon( 'fas fa-check' ); ?>
			<?php esc_html_e( 'Activated', 'wp-travel-engine' ); ?>
		</span>
	<?php endif; ?>
	<!-- <div class="wpte-password">
	</div> -->
	<div class="wpte-btn-wrap">
		<?php if ( $license->valid() ) { ?>
			<input type="submit" class="wpte-btn wpte-btn-deactive deactivate-license"
					data-id="<?php echo esc_attr( $license->slug ); ?>"
					name="edd_license_deactivate"
					value="<?php echo esc_attr__( 'Deactivate License', 'wp-travel-engine' ); ?>" />
		<?php } elseif ( ! empty( $addon->{'license_key'} ) ) { ?>
			<input type="submit" class="wpte-btn wpte-btn-active activate-license"
					data-id="<?php echo esc_attr( $addon->slug ); ?>"
					name="edd_license_activate" value="<?php echo esc_attr__( 'Activate License', 'wp-travel-engine' ); ?>" />
		<?php } ?>
	</div>
	<span <?php echo esc_html( $color ); ?> class="wpte-tooltip"><?php echo esc_html( $message ); ?></span>
</div>
