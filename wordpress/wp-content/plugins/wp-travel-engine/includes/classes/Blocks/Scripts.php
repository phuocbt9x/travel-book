<?php
/**
 * Scripts Registration and Enqueue.
 *
 * @since 5.8.3
 */

namespace WPTravelEngine\Blocks;

class Scripts {

	public function __construct() {
		add_action( 'enqueue_block_assets', array( __CLASS__, 'block_editor_assets' ) );
		add_action( 'wp_enqueue_scripts', array( __CLASS__, 'view_assets' ), 99 );

		add_action( 'wp_footer', array( __CLASS__, 'dequeue_plugin_css' ), - 1 );
	}

	public static function dequeue_plugin_css() {
		$wp_travel_engine_settings = get_option( 'wp_travel_engine_settings', array() );
		$is_enabled_fse_template   = $wp_travel_engine_settings['enable_fse_template'] ?? 'no';
		if ( ( current_theme_supports( 'wptravelengine-templates' ) || ( wp_is_block_theme() && $is_enabled_fse_template == 'yes' ) ) && is_singular( \WP_TRAVEL_ENGINE_POST_TYPE ) ) {
			wp_dequeue_style( 'wp-travel-engine' );
		}
	}

	public static function view_assets() {
		$wp_travel_engine_settings = get_option( 'wp_travel_engine_settings', array() );
		$is_enabled_fse_template   = $wp_travel_engine_settings['enable_fse_template'] ?? 'no';
		$assets                    = include dirname( \WP_TRAVEL_ENGINE_FILE_PATH ) . '/dist/blocks/index.asset.php';
		extract( $assets );

		if ( ( current_theme_supports( 'wptravelengine-templates' ) || ( wp_is_block_theme() && $is_enabled_fse_template == 'yes' ) ) && is_singular( \WP_TRAVEL_ENGINE_POST_TYPE ) ) {
			wp_enqueue_script( 'wte-blocks-index', plugins_url( 'dist/blocks/index.js', \WP_TRAVEL_ENGINE_FILE_PATH ), $dependencies, $version, true );
			wp_localize_script(
				'wte-blocks-index',
				'tripBlocksScript',
				array(
					'ajaxurl' => admin_url( 'admin-ajax.php' ),
					'nonce'   => wp_create_nonce( 'handle_fsd_nonce' ),
				)
			);
			static::dequeue_plugin_css();
			wp_enqueue_style( 'wte-trip-blocks-index', plugins_url( 'dist/blocks/view.css', \WP_TRAVEL_ENGINE_FILE_PATH ), array() );
		}
	}

	public static function block_editor_assets() {
		if ( ! is_admin() ) {
			return;
		}

		$assets = include dirname( \WP_TRAVEL_ENGINE_FILE_PATH ) . '/dist/blocks/editor/editor.asset.php';
		extract( $assets );
		wp_enqueue_script( 'wte-global' );
		wp_enqueue_script( 'wte-blocks-editor', plugins_url( 'dist/blocks/editor/editor.js', \WP_TRAVEL_ENGINE_FILE_PATH ), $dependencies, $version, true );
		wp_enqueue_style( 'wte-blocks-editor', plugins_url( 'dist/blocks/editor/editor.css', \WP_TRAVEL_ENGINE_FILE_PATH ) );
		wp_enqueue_script( 'chartjs' );
		wp_enqueue_script( 'chartjs-datalabels' );
		wp_dequeue_script( 'wte-ai-common' );

		$pages_assets = include dirname( \WP_TRAVEL_ENGINE_FILE_PATH ) . '/dist/blocks/editor/trip-pages-blocks.asset.php';
		extract( $pages_assets );
		wp_enqueue_script( 'wte-trip-pages-blocks-editor', plugins_url( 'dist/blocks/editor/trip-pages-blocks.js', \WP_TRAVEL_ENGINE_FILE_PATH ), $dependencies, $version, true );
		$assets = include dirname( \WP_TRAVEL_ENGINE_FILE_PATH ) . '/dist/blocks/editor/trip-blocks.asset.php';
		extract( $assets );

		$screen           = get_current_screen();
		$image_sizes      = \Wp_Travel_Engine_Public::get_image_sizes();
		$review_gallery[] = array(
			'thumbnail' => esc_url( plugins_url( 'includes/classes/Blocks/assets/logo.png', \WP_TRAVEL_ENGINE_FILE_PATH ) ),
		);

		if ( $screen->id === 'site-editor' ) {
			wp_enqueue_script( 'wte-trip-blocks-editor', plugins_url( 'dist/blocks/editor/trip-blocks.js', \WP_TRAVEL_ENGINE_FILE_PATH ), $dependencies, $version, true );
			wp_localize_script(
				'wte-trip-blocks-editor',
				'wptravelengineTripBlocks',
				array(
					'imageSizes' => $image_sizes,
					'images'     => $review_gallery,
					'fsdVersion' => defined( 'WTE_FIXED_DEPARTURE_VERSION' ) ? WTE_FIXED_DEPARTURE_VERSION : '',
				)
			);
			wp_enqueue_style( 'wte-trip-blocks-editor', plugins_url( 'dist/blocks/editor/trip-blocks.css', \WP_TRAVEL_ENGINE_FILE_PATH ) );
			// FSD enqueue for block editor page.
			wp_enqueue_style(
				'wte-fsd-admin',
				defined( 'WTE_FIXED_DEPARTURE_FILE_URL' ) ? WTE_FIXED_DEPARTURE_FILE_URL . '/dist/wte-fsd-public.css' : '',
				array(),
				defined( 'WTE_FIXED_DEPARTURE_VERSION' ) ? WTE_FIXED_DEPARTURE_VERSION : ''
			);
		}
	}
}
