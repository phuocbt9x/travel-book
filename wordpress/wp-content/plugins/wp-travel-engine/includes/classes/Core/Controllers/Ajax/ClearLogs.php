<?php
/**
 * Clear Logs AJAX Controller.
 *
 * @package WPTravelEngine\Core\Controllers\Ajax
 * @since 6.7.6
 */

namespace WPTravelEngine\Core\Controllers\Ajax;

use WPTravelEngine\Abstracts\AjaxController;
use WPTravelEngine\Logger\Utilities\LogUtils;

/**
 * Handles clear logs AJAX request.
 *
 * @since 6.7.6
 */
class ClearLogs extends AjaxController {

	const NONCE_KEY    = 'nonce';
	const NONCE_ACTION = 'wptravelengine_clear_logs';
	const ACTION       = 'wptravelengine_clear_logs';
	const ALLOW_NOPRIV = false;

	/**
	 * Process request.
	 *
	 * @return void
	 */
	protected function process_request() {
		// Check capability
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( array( 'message' => __( 'You do not have sufficient permissions.', 'wp-travel-engine' ) ) );
		}

		// Get log directory
		$log_dir = LogUtils::get_log_directory();

		if ( ! is_dir( $log_dir ) ) {
			wp_send_json_error( array( 'message' => __( 'Log directory not found.', 'wp-travel-engine' ) ) );
		}

		// Check if deleting a single file or all files
		$single_file = $this->request->get_param( 'file' );

		if ( ! empty( $single_file ) ) {
			$this->delete_single_file( $log_dir, $single_file );
		} else {
			$this->delete_all_files( $log_dir );
		}
	}

	/**
	 * Delete a single log file.
	 *
	 * @param string $log_dir Log directory path.
	 * @param string $filename File name to delete.
	 * @return void
	 */
	protected function delete_single_file( string $log_dir, string $filename ): void {
		$filename  = sanitize_file_name( $filename );
		$file_path = $log_dir . '/' . $filename;

		// Security: verify file exists first (realpath returns false for non-existent files)
		if ( ! file_exists( $file_path ) ) {
			wp_send_json_error( array( 'message' => __( 'File not found.', 'wp-travel-engine' ) ) );
		}

		// Security: ensure file is within log directory (path traversal protection)
		$real_file_path = realpath( $file_path );
		$real_log_dir   = realpath( $log_dir );
		if ( false === $real_file_path || false === $real_log_dir || strpos( $real_file_path, $real_log_dir ) !== 0 ) {
			wp_send_json_error( array( 'message' => __( 'Invalid file path.', 'wp-travel-engine' ) ) );
		}

		// phpcs:ignore WordPress.WP.AlternativeFunctions.unlink_unlink
		if ( unlink( $file_path ) ) {
			LogUtils::clear_cache();

			wp_send_json_success(
				array(
					'message' => sprintf(
						// translators: %s file name
						__( 'Log file "%s" deleted successfully.', 'wp-travel-engine' ),
						$filename
					),
				)
			);
		} else {
			wp_send_json_error( array( 'message' => __( 'Failed to delete file.', 'wp-travel-engine' ) ) );
		}
	}

	/**
	 * Delete all log files.
	 *
	 * @param string $log_dir Log directory path.
	 * @return void
	 */
	protected function delete_all_files( string $log_dir ): void {
		// Get all log files
		$utils     = new LogUtils();
		$log_files = $utils->get_log_files( $log_dir );

		if ( empty( $log_files ) ) {
			wp_send_json_success( array( 'message' => __( 'No log files to delete.', 'wp-travel-engine' ) ) );
		}

		$deleted      = 0;
		$real_log_dir = realpath( $log_dir );

		// Delete all log files with path traversal protection
		foreach ( $log_files as $file ) {
			// Security: verify file exists first
			if ( ! file_exists( $file ) ) {
				continue;
			}

			// Security: ensure file is within log directory (path traversal protection)
			$real_file_path = realpath( $file );
			if ( false === $real_file_path || false === $real_log_dir || strpos( $real_file_path, $real_log_dir ) !== 0 ) {
				continue;
			}

			// phpcs:ignore WordPress.WP.AlternativeFunctions.unlink_unlink
			if ( unlink( $file ) ) {
				++$deleted;
			}
		}

		// Clear cache and cleanup events
		LogUtils::clear_cache();
		LogUtils::clear_log_events();

		wp_send_json_success(
			array(
				'message' => sprintf(
					// translators: %d number of files deleted
					__( '%d log files deleted successfully.', 'wp-travel-engine' ),
					$deleted
				),
			)
		);
	}
}
