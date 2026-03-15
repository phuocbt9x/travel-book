<?php
/**
 * Global Settings Load Controller.
 *
 * @package WPTravelEngine/Core/Controllers
 * @since 6.0.0
 */

namespace WPTravelEngine\Core\Controllers\Ajax;

use WPTravelEngine\Abstracts\AjaxController;

/**
 * Loads global settings tab content callback.
 */
class LoadGlobalSettings extends AjaxController {

	const NONCE_KEY    = 'nonce';
	const NONCE_ACTION = 'wpte_global_settings_load_tab_content';
	const ACTION       = 'wpte_global_settings_load_tab_content';

	/**
	 * Process Request.
	 * Load global settings tab ajax callback.
	 *
	 * @return void
	 */
	public function process_request() {
		$post_data = $this->request->get_body_params();

		if ( ! class_exists( '\Wp_Travel_Engine_Settings' ) ) {
			require_once plugin_dir_path( WP_TRAVEL_ENGINE_FILE_PATH ) . 'includes/class-wp-travel-engine-settings.php';
		}

		$tab_details = $post_data['tab_details'] ?? false;

		if ( $tab_details ) {
			ob_start();
			?>
			<div class="wpte-tab-content <?php echo esc_attr( $post_data['content_key'] ?? '' ); ?>-content wpte-global-settngstab">
				<div class="wpte-block-content">
					<?php
					$sub_tabs = $tab_details['sub_tabs'] ?? array();
					if ( ! empty( $sub_tabs ) ) :
						?>
						<div class="wpte-tab-sub wpte-horizontal-tab">
							<div class="wpte-tab-wrap">
								<?php
								$current = 1;
								foreach ( $sub_tabs as $key => $tab ) :
									?>
									<a href="javascript:void(0);"
										data-wte-update="<?php echo esc_attr( $tab['has_updates'] ?? '' ); ?>"
										class="wpte-tab <?php echo esc_attr( $key ); ?> <?php echo 1 === $current++ ? 'current' : ''; ?>"><?php echo esc_html( $tab['label'] ); ?></a>
									<?php
								endforeach;
								?>
							</div>
							<div class="wpte-tab-content-wrap">
								<?php
								$current = 1;
								foreach ( $sub_tabs as $key => $tab ) :
									$tab_content_class  = isset( $tab['has_sidebar'] ) && $tab['has_sidebar'] ? 'wpte-tab-content-sidebar' : '';
									$tab_content_class .= " {$key}-content";
									$tab_content_class .= 1 === $current++ ? ' current' : '';
									$tab_aside_content  = '';
									?>
									<div class="wpte-tab-content <?php echo esc_attr( $tab_content_class ); ?>">
										<div class="wpte-block-content">
											<?php
											if ( file_exists( $tab['content_path'] ) ) {
												include $tab['content_path'];
											}
											?>
										</div>
										<?php
										if ( ! empty( $tab_aside_content ) ) {
											printf( '<div class="wpte-block-content-aside">%s</div>', wp_kses_post( $tab_aside_content ) );
										}
										?>
									</div>
									<?php
								endforeach;
								?>
							</div>
						</div>
						<?php
					else :
						?>
						<div class="wpte-alert">
							<?php
							echo wp_kses(
							// Translators: %1$s: Addon download link.
								sprintf(
									__( 'There are no <b>WP Travel Engine Addons</b> installed on your site currently. To extend features and get additional functionality settings,  <a target="_blank" href="%1$s">Get Addons Here</a>', 'wp-travel-engine' ),
									WP_TRAVEL_ENGINE_STORE_URL . '/plugins/'
								),
								array(
									'a' => array(
										'target' => array(),
										'href'   => array(),
									),
								)
							);
							?>
						</div>
						<?php
					endif;
					?>
					<div class="wpte-field wpte-submit">
						<input data-tab="<?php echo esc_attr( $post_data['content_key'] ?? '' ); ?>"
								data-nonce="<?php echo esc_attr( wp_create_nonce( 'wpte_global_tabs_save_data' ) ); ?>"
								class="wpte-save-global-settings" type="submit" name="wpte_save_global_settings"
								value="<?php esc_attr_e( 'Save', 'wp-travel-engine' ); ?>">
					</div>
				</div> <!-- .wpte-block-content -->
			</div>
			<?php
			$data = ob_get_clean();

			wp_send_json_success(
				array(
					'message' => esc_html__( 'Data Fetched', 'wp-travel-engine' ),
					'html'    => $data,
				)
			);
		}
		wp_send_json_error( array( 'message' => esc_html__( 'Invalid Tab Data', 'wp-travel-engine' ) ) );
	}
}
