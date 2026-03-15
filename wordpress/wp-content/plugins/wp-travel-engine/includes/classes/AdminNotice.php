<?php
/**
 * Manages server-sourced admin notifications and their display functionality.
 *
 * @package WPTravelEngine
 * @since 6.5.7
 * @since 6.7.2 Added local notice content.
 */

namespace WPTravelEngine;

class AdminNotice {

	/**
	 * Notice content.
	 *
	 * @var array
	 */
	private $notice_content = array();

	/**
	 * Constructor.
	 *
	 * @since 6.7.2 Made only triggered in admin area.
	 */
	public function __construct() {
		add_action(
			'admin_init',
			function () {
				if ( empty( $this->get_notice_content() ) ) {
					return;
				}

				add_action( 'admin_notices', array( $this, 'display_notice' ), 99 );
				add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ), 99 );
			}
		);
	}

	/**
	 * Display notice content.
	 *
	 * @since 6.7.2 Made compatible for local notice too.
	 */
	public function display_notice(): void {
		foreach ( $this->notice_content as $key => $notice ) :
			$content = wp_kses_post( $notice['content'] ?? '' );

			if ( empty( $content ) ) {
				continue;
			} elseif ( 'local' === $key ) {
				$this->print_local_notice_style();
			}

			?>
			<div class="notice wptravelengine-admin-notice is-dismissible <?php echo esc_attr( $key ); ?>" style="background: none; box-shadow: none;">
				<div class="wptravelengine-notice-content"><?php echo $content; ?></div>
			</div>
			<?php
		endforeach;
	}

	/**
	 * Enqueue scripts.
	 */
	public function enqueue_scripts(): void {

		wp_enqueue_script(
			'wptravelengine-admin-global',
			plugins_url( 'dist/admin/admin-global.js', WP_TRAVEL_ENGINE_FILE_PATH ),
			array(),
			filemtime( plugin_dir_path( WP_TRAVEL_ENGINE_FILE_PATH ) . 'dist/admin/admin-global.js' ),
			true
		);

		wp_localize_script(
			'wptravelengine-admin-global',
			'WPTravelEngineAdminGlobal',
			array(
				'ajaxurl'      => admin_url( 'admin-ajax.php' ),
				'action'       => 'wptravelengine_notice_dismiss',
				'nonce'        => wp_create_nonce( '_wptravelengine_notice_dismiss' ),
				'last_updated' => $this->notice_content['server']['last_updated'] ?? $this->notice_content['local']['last_updated'] ?? current_time( 'timestamp' ),
			)
		);
	}

	/**
	 * Get notice content.
	 *
	 * @return bool|array
	 */
	public function get_notice_content() {
		$notice = get_transient( 'wptravelengine_last_notice' );

		if ( ! $notice ) {
			$response = wp_remote_get(
				'https://stats.wptravelengine.com/wp-json/wptravelengine-server/v1/notice',
				array(
					'timeout'   => 5,
					'sslverify' => true,
				)
			);

			if ( ! is_wp_error( $response ) ) {
				$notice = json_decode( wp_remote_retrieve_body( $response ), true );
				if ( ! empty( $notice['content'] ?? '' ) && ! empty( $notice['last_updated'] ?? 0 ) ) {
					set_transient( 'wptravelengine_last_notice', $notice, DAY_IN_SECONDS );
				}
			}
		}

		if ( $notice && ! empty( $notice['content'] ?? '' ) && ! empty( $notice['last_updated'] ?? 0 ) ) {
			$dismissed_at = intval( get_option( 'wptravelengine_notice_dismissed_at', 0 ) );
			if ( $dismissed_at < $notice['last_updated'] ) {
				$this->notice_content['server'] = $notice;
			}
		}

		$this->set_local_notice_content();

		return $this->notice_content;
	}

	/**
	 * Set local notice content.
	 *
	 * @return void
	 * @since 6.7.2
	 */
	private function set_local_notice_content() {

		if ( get_option( 'wptravelengine_local_notice_dismissed_at' ) || ! defined( 'WPTRAVELENGINE_PRO_VERSION' ) || version_compare( WPTRAVELENGINE_PRO_VERSION, '1.0.13', '>=' ) ) {
			return;
		}

		ob_start();
		?>
		<div class="warning-alert">
			<div class="warning-alert__icon">
				<img src="<?php echo plugins_url( 'assets/images/admin-alert.svg', WP_TRAVEL_ENGINE_FILE_PATH ); ?>" alt="Admin Alert" />
			</div>
			<div class="warning-alert__content">
				<h3 class="warning-alert__title"><?php _e( 'Update Required', 'wp-travel-engine' ); ?></h3>
				<p class="warning-alert__text"><?php printf( __( 'You\'re using an older version of %1$sWP Travel Engine Pro%2$s. Upgrade to the latest version to use new features and receive the latest security and stability improvements.', 'wp-travel-engine' ), '<strong>', '</strong>' ); ?></p>
				<a href="<?php echo admin_url( 'plugin-install.php?tab=wptravelengine' ); ?>" class="warning-alert__button" target="_blank"><?php _e( 'Update Now', 'wp-travel-engine' ); ?></a>
			</div>
		</div>
		<?php

		$this->notice_content['local'] = array(
			'content'      => ob_get_clean(),
			'last_updated' => current_time( 'timestamp' ),
		);
	}

	/**
	 * Get local notice content.
	 *
	 * @return void
	 * @since 6.7.2
	 */
	private function print_local_notice_style() {
		?>
		<style type="text/css">
			:root {
				--wpte-alert-warning: 38 92% 50%;
				--wpte-alert-warning-light: 45 100% 96%;
				--wpte-alert-warning-border: 38 90% 75%;
				--wpte-alert-warning-foreground: 32 95% 28%;
				--wpte-alert-warning-icon: 38 92% 45%;
				--radius: 0.75rem;
			}

			.warning-alert {
			display: flex;
			align-items: flex-start;
			gap: 1rem;
			padding: 1.25rem 1.5rem;
			background-color: hsl(var(--wpte-alert-warning-light));
			border: 1px solid hsl(var(--wpte-alert-warning-border));
			border-left: 4px solid hsl(var(--wpte-alert-warning));
			border-radius: var(--radius);
			box-shadow: 
				0 1px 3px 0 rgba(0, 0, 0, 0.05),
				0 4px 12px -2px hsla(var(--wpte-alert-warning), 0.1);
			animation: slideIn 0.3s ease-out;
			}

			@keyframes slideIn {
			from {
				opacity: 0;
				transform: translateY(-8px);
			}
			to {
				opacity: 1;
				transform: translateY(0);
			}
			}

			.warning-alert__icon {
				flex-shrink: 0;
				width: 28px;
				height: 28px;
				color: hsl(var(--wpte-alert-warning-icon));
				margin-top: 2px;
			}

			.warning-alert__icon svg {
				width: 100%;
				height: 100%;
			}

			.warning-alert__content {
				flex: 1;
				min-width: 0;
			}

			.warning-alert__title {
				margin: 0 0 0.375rem 0;
				font-size: 1rem;
				font-weight: 600;
				color: hsl(var(--wpte-alert-warning-foreground));
				line-height: 1.4;
				letter-spacing: -0.01em;
			}

			.warning-alert__text {
				margin: 0 0 1rem 0;
				font-size: 0.9rem;
				color: hsl(var(--wpte-alert-warning-foreground) / 0.85);
				line-height: 1.55;
			}

			.warning-alert__button {
				display: inline-flex;
				align-items: center;
				justify-content: center;
				padding: 0.5rem 1rem;
				font-size: 0.875rem;
				font-weight: 500;
				color: hsl(var(--wpte-alert-warning-foreground));
				background-color: hsl(var(--wpte-alert-warning) / 0.15);
				border: 1px solid hsl(var(--wpte-alert-warning) / 0.4);
				border-radius: calc(var(--radius) - 2px);
				cursor: pointer;
				transition: all 0.2s ease;
				text-decoration: none;
			}

			.warning-alert__button:hover {
				background-color: hsl(var(--wpte-alert-warning) / 0.25);
				border-color: hsl(var(--wpte-alert-warning) / 0.6);
				transform: translateY(-1px);
				color: hsl(var(--wpte-alert-warning-foreground));
			}

			.warning-alert__button:active {
				transform: translateY(0);
			}

			.warning-alert__button:focus {
				outline: none;
				box-shadow: 0 0 0 3px hsl(var(--wpte-alert-warning) / 0.25);
				color: hsl(var(--wpte-alert-warning-foreground));
			}
		</style>
		<?php
	}
}