<?php
/**
 * Download Log AJAX Controller.
 *
 * @package WPTravelEngine\Core\Controllers\Ajax
 * @since 6.7.6
 */

namespace WPTravelEngine\Core\Controllers\Ajax;

use WPTravelEngine\Abstracts\AjaxController;
use WPTravelEngine\Logger\Utilities\LogUtils;

/**
 * Handles log download AJAX request.
 *
 * Supports both single file download and filtered multi-file download.
 *
 * @since 6.7.6
 */
class DownloadLog extends AjaxController {

	const NONCE_KEY    = 'nonce';
	const NONCE_ACTION = 'wptravelengine_download_log';
	const ACTION       = 'wptravelengine_download_log';
	const ALLOW_NOPRIV = false;

	/**
	 * Process request.
	 *
	 * Handles two modes:
	 * - Single file: If 'file' param provided, downloads that specific file
	 * - Filtered: If no 'file' param, downloads filtered logs from all files
	 *
	 * @return void
	 */
	protected function process_request() {
		// Check capability
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( __( 'You do not have sufficient permissions.', 'wp-travel-engine' ), 403 );
		}

		// Check if downloading a single file
		$single_file = $this->request->get_param( 'file' );
		if ( ! empty( $single_file ) ) {
			$this->download_single_file( $single_file );
			return;
		}

		// Otherwise, download filtered logs
		$this->download_filtered_logs();
	}

	/**
	 * Download a single log file.
	 *
	 * @param string $filename Filename to download.
	 * @return void
	 */
	protected function download_single_file( string $filename ): void {
		$filename = sanitize_file_name( $filename );

		// Security: Validate file extension - only .log files allowed
		if ( ! preg_match( '/\.log$/', $filename ) ) {
			wp_die( __( 'Invalid file type. Only .log files are allowed.', 'wp-travel-engine' ), 403 );
		}

		$log_dir   = LogUtils::get_log_directory();
		$file_path = $log_dir . '/' . $filename;

		// Security: Verify file exists first (realpath returns false for non-existent files)
		if ( ! file_exists( $file_path ) ) {
			wp_die( __( 'File not found.', 'wp-travel-engine' ), 404 );
		}

		// Security: Ensure file is within log directory (path traversal protection)
		$real_file_path = realpath( $file_path );
		$real_log_dir   = realpath( $log_dir );
		if ( false === $real_file_path || false === $real_log_dir || strpos( $real_file_path, $real_log_dir ) !== 0 ) {
			wp_die( __( 'Invalid file.', 'wp-travel-engine' ), 403 );
		}

		// Get file size for Content-Length header
		// phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_filesize
		$file_size = filesize( $file_path );

		// Sanitize filename for header injection protection (remove control characters and quotes)
		$safe_filename = preg_replace( '/[^\w\-.]/', '_', $filename );

		// Set headers for download
		status_header( 200 );
		header( 'Content-Type: text/plain; charset=utf-8' );
		header( 'Content-Disposition: attachment; filename="' . $safe_filename . '"' );
		header( 'Content-Length: ' . $file_size );

		// phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_readfile
		readfile( $file_path );
		wp_die();
	}

	/**
	 * Download filtered logs from all files.
	 *
	 * @return void
	 */
	protected function download_filtered_logs(): void {
		// Get filters
		$level  = $this->request->get_param( 'level' ) ?? 'all';
		$source = $this->request->get_param( 'source' ) ?? 'all';
		$date   = $this->request->get_param( 'date' ) ?? 'all';
		$search = $this->request->get_param( 'search' ) ?? '';

		// Build filename
		$filename = 'wte-logs-' . gmdate( 'Y-m-d-H-i-s' ) . '.log';

		// Get log directory
		$log_dir = LogUtils::get_log_directory();

		// Collect all matching logs
		$utils     = new LogUtils();
		$log_files = $utils->get_log_files( $log_dir );

		$filters = array_filter(
			array(
				'level'  => $level !== 'all' ? $level : null,
				'source' => $source !== 'all' ? $source : null,
				'date'   => $date !== 'all' ? $date : null,
				'search' => ! empty( $search ) ? $search : null,
			)
		);

		// Sanitize filename for header injection protection (remove control characters and quotes)
		$safe_filename = preg_replace( '/[^\w\-.]/', '_', $filename );

		// Set headers for download (no Content-Length since we're streaming)
		status_header( 200 );
		header( 'Content-Type: text/plain; charset=utf-8' );
		header( 'Content-Disposition: attachment; filename="' . $safe_filename . '"' );

		// Memory-safe streaming: output entries as we parse them instead of building huge string
		$max_entries    = 50000; // Hard limit to prevent memory exhaustion
		$max_file_size  = 50 * 1024 * 1024; // 50MB per file max
		$entries_output = 0;
		$has_output     = false;

		foreach ( $log_files as $file ) {
			// Skip files that are too large to parse safely
			// phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_filesize
			$file_size = filesize( $file );
			if ( false === $file_size || $file_size > $max_file_size ) {
				continue;
			}

			// Stop if we've hit the entry limit
			if ( $entries_output >= $max_entries ) {
				echo "\n--- Maximum entries reached ($max_entries). Download stopped for memory safety. ---\n"; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				break;
			}

			$entries = $utils->parse_file( $file, $filters );
			foreach ( $entries as $entry ) {
				// Check limit before each entry
				if ( $entries_output >= $max_entries ) {
					break 2; // Break outer loop
				}

				// Output entry immediately (streaming)
				printf( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					"[%s] %s: %s %s\n",
					$entry['timestamp'],
					strtoupper( $entry['level'] ),
					$entry['message'],
					! empty( $entry['context'] ) ? wp_json_encode( $entry['context'] ) : ''
				);

				++$entries_output;
				$has_output = true;

				// Flush output buffer periodically for large downloads
				if ( $entries_output % 100 === 0 ) {
					flush();
				}
			}
		}

		if ( ! $has_output ) {
			echo 'No logs found matching the selected filters.'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}

		wp_die();
	}
}
