<?php
/**
 * Admin Load Tab Content Controller.
 *
 * @package WPTravelEngine/Core/Controllers
 * @since 6.0.0
 */

namespace WPTravelEngine\Core\Controllers\Ajax;

use WPTravelEngine\Abstracts\AjaxController;

/**
 * Loads admin tab content.
 */
class LoadTabContent extends AjaxController {

	const NONCE_KEY    = 'nonce';
	const NONCE_ACTION = 'wpte_admin_load_tab_content';
	const ACTION       = 'wpte_admin_load_tab_content';

	/**
	 * Process Request.
	 * Load tab ajax callback.
	 *
	 * @return void
	 */
	public function process_request() {
		$post_data = $this->request->get_body_params();
		// phpcs:disable
		$tab_details = $post_data[ 'tab_details' ] ?? false;

		if ( $tab_details ) {

			$content_path = base64_decode( $tab_details[ 'content_path' ] ?? '' );

			ob_start();
			if ( file_exists( $content_path ) ) {
				?>
				<div data-trigger="<?php echo esc_attr( $tab_details[ 'content_key' ] ); ?>"
					 class="wpte-tab-content <?php echo esc_attr( $tab_details[ 'content_key' ] ); ?>-content ">
					<div class="wpte-title-wrap">
						<h2 class="wpte-title"><?php echo esc_html( $tab_details[ 'tab_heading' ] ); ?></h2>
					</div> <!-- .wpte-title-wrap -->
					<div class="wpte-block-content">
						<?php
						$args[ 'post_id' ]     = wte_clean( wp_unslash( $post_data[ 'post_id' ] ) );
						$args[ 'next_tab' ]    = wte_clean( wp_unslash( $post_data[ 'next_tab' ] ) );
						$args[ 'tab_details' ] = wte_clean( wp_unslash( $post_data[ 'tab_details' ] ) );
						// load template.
						include $content_path;
						?>
					</div>
				</div>
				<?php
			}
			$data = ob_get_clean();

			wp_send_json_success(
				array(
					'message' => esc_html__( 'Data Fetched', 'wp-travel-engine' ),
					'html'    => $data,
				)
			);
		}
		wp_send_json_error( array( 'message' => esc_html__( 'Invalid Tab Data', 'wp-travel-engine' ) ) );
		// phpcs:enable
	}
}
