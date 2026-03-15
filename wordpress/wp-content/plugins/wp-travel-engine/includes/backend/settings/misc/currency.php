<?php
/**
 * Currency Converter
 */
if ( ! defined( 'WTE_CURRENCY_CONVERTER_VERSION' ) ) {
	?>
	<div class="wpte-form-block-wrap">
		<div class="wpte-form-block">
			<div style="margin-bottom: 40px;" class="wpte-info-block">
				<b><?php esc_html_e( 'Note:', 'wp-travel-engine' ); ?></b>
				<p>
					<?php
						echo wp_kses(
							sprintf(
								__( 'Looking to offer customers seamless currency conversion? Currency Converter allows easy conversion of trip prices between multiple currencies, providing customers with clear price display and convenience. %1$sGet Currency Converter extension now%2$s.', 'wp-travel-engine' ),
								'<a target="_blank" href="https://wptravelengine.com/plugins/currency-converter/?utm_source=free_plugin&utm_medium=pro_addon&utm_campaign=upgrade_to_pro">',
								'</a>'
							),
							array(
								'a' => array(
									'target' => array(),
									'href'   => array(),
								),
							)
						);
					?>
				</p>
			</div>
		</div>
	</div>
	<?php
}
$fields = \Wp_Travel_Engine_Settings::get_currency_fields();

\wte_admin_form_fields( $fields )->render();
