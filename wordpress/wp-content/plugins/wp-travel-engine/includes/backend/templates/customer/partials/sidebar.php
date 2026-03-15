<?php
/**
 * Customer Details Metabox Content.
 *
 * @var array $tabs Sidebar tabs for the customer details metabox.
 */
?>
<aside class="wpte-customer-sidebar-area">
	<ul class="wpte-tabs">
		<?php foreach ( $tabs as $tab ) : ?>
			<li class="wpte-tab-item <?php echo esc_attr( $tab['class'] ); ?>">
				<a href="#" class="wpte-tab" data-target="<?php echo esc_attr( $tab['target'] ); ?>">
					<?php echo wptravelengine_display_icon( $tab['icon'] ); ?>
					<?php echo esc_html( $tab['label'] ); ?>
				</a>
			</li>
		<?php endforeach; ?>
	</ul>
</aside>
