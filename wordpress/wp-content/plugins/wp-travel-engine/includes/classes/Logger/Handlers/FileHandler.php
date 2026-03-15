<?php
/**
 * File-based log handler with rotation.
 *
 * @package WPTravelEngine\Logger\Handlers
 * @since 6.7.6
 */

namespace WPTravelEngine\Logger\Handlers;

use WPTravelEngine\Filters\Events;

/**
 * File handler class.
 *
 * Handles logging to files with automatic rotation and security.
 *
 * @since 6.7.6
 */
class FileHandler extends Handler {

	/**
	 * Log directory path.
	 *
	 * @var string
	 */
	protected string $log_dir;

	/**
	 * Maximum number of rotated files to keep.
	 *
	 * @var int
	 */
	protected int $max_rotated_files = 10;

	/**
	 * Flag to track if directory has been initialized (lazy initialization).
	 *
	 * @var bool
	 */
	private bool $directory_initialized = false;

	/**
	 * Constructor.
	 *
	 * @param string $log_dir Log directory path.
	 * @since 6.7.6
	 */
	public function __construct( string $log_dir ) {
		$this->log_dir = rtrim( $log_dir, '/' );
		// Note: Directory initialization is lazy (only when first log is written)
	}

	/**
	 * Handle a log entry.
	 *
	 * @param string $level   Log level.
	 * @param string $message Log message.
	 * @param array  $context Additional context.
	 * @return void
	 * @since 6.7.6
	 */
	public function handle( string $level, string $message, array $context ): void {
		// Lazy initialization: only create directory when first log is written
		if ( ! $this->directory_initialized ) {
			$this->ensure_directory_security();
			$this->directory_initialized = true;
		}

		$file_path = $this->get_log_file_path( $context );

		// Check if this is a new file
		$is_new_file = ! file_exists( $file_path );

		// Check if rotation is needed
		if ( $this->should_rotate_file( $file_path ) ) {
			$this->rotate_file( $file_path );
		}

		// Format and write log entry with separator
		$entry = $this->format_entry( $level, $message, $context ) . PHP_EOL . str_repeat( '-', 80 ) . PHP_EOL;

		// phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_fopen
		$handle = fopen( $file_path, 'a' );
		if ( $handle ) {
			// Try non-blocking lock ONCE - don't retry to avoid stalling requests
			// It's better to miss a log entry than to delay user requests
			// phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_flock
			if ( flock( $handle, LOCK_EX | LOCK_NB ) ) {
				// Got lock - write and release
				// phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_fwrite
				fwrite( $handle, $entry );
				// phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_flock
				flock( $handle, LOCK_UN );
			} else {
				// Lock failed - fallback for FATAL errors only (prevent data loss)
				if ( 'FATAL' === strtoupper( $level ) ) {
					// phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_error_log
					error_log(
						sprintf(
							'[WTE Logger - Lock Failed] %s: %s %s',
							strtoupper( $level ),
							$message,
							! empty( $context ) ? wp_json_encode( $context ) : ''
						)
					);
				}
			}

			// phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_fclose
			fclose( $handle );
		}

		// Schedule cleanup event for new log files
		if ( $is_new_file && class_exists( 'WPTravelEngine\Filters\Events' ) ) {
			$this->schedule_cleanup_event( $file_path );
		}
	}

	/**
	 * Format a log entry with human-readable stack traces.
	 *
	 * Overrides parent to extract stack_trace from context and format it on separate lines
	 * for readability, while keeping the main log line single-line for grep/parsing.
	 *
	 * @param string $level   Log level.
	 * @param string $message Log message.
	 * @param array  $context Additional context.
	 * @return string Formatted log entry.
	 * @since 6.7.7
	 */
	protected function format_entry( string $level, string $message, array $context ): string {
		$timestamp = $this->format_utc_timestamp( time() );
		$level     = strtoupper( $level );

		// Extract stack_trace for separate formatting (more readable)
		$stack_trace = null;
		if ( isset( $context['stack_trace'] ) ) {
			$stack_trace = $context['stack_trace'];
			unset( $context['stack_trace'] ); // Remove from JSON context
		}

		// Format main log line (single-line for grep)
		$entry = sprintf(
			'%s %s %s',
			$timestamp,
			$level,
			$message
		);

		// Add context as JSON (if any remains after removing stack_trace)
		if ( ! empty( $context ) ) {
			$entry .= ' CONTEXT: ' . wp_json_encode( $context, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE );
		}

		// Add stack trace on separate lines for readability (if exists)
		if ( ! empty( $stack_trace ) ) {
			$entry .= PHP_EOL . 'STACK TRACE:' . PHP_EOL . $stack_trace;
		}

		return $entry;
	}

	/**
	 * Get log file path for a log entry.
	 *
	 * Day-based file naming - one file per day for all sources.
	 *
	 * @param array $context Log context.
	 * @return string Log file path.
	 * @since 6.7.6
	 */
	protected function get_log_file_path( array $context ): string {
		$date = gmdate( 'Y-m-d' );

		// Pattern: wptravelengine-YYYY-MM-DD.log (all errors in one file)
		$filename = sprintf( 'wptravelengine-%s.log', $date );

		return $this->log_dir . '/' . $filename;
	}

	/**
	 * Get maximum file size from settings.
	 *
	 * @return int Maximum file size in bytes.
	 * @since 6.7.6
	 */
	protected function get_max_file_size(): int {
		$size_mb = (int) \WPTravelEngine\Logger\LoggerSettings::instance()->get( 'max_file_size', 10 );
		$size    = $size_mb * 1048576; // Convert MB to bytes
		return max( 1048576, min( 104857600, $size ) ); // Clamp between 1MB-100MB
	}

	/**
	 * Check if file should be rotated.
	 *
	 * @param string $file_path File path.
	 * @return bool True if rotation needed.
	 * @since 6.7.6
	 */
	protected function should_rotate_file( string $file_path ): bool {
		if ( ! file_exists( $file_path ) ) {
			return false;
		}

		// phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_filesize
		return filesize( $file_path ) >= $this->get_max_file_size();
	}

	/**
	 * Rotate log file.
	 *
	 * @param string $file_path File path.
	 * @return void
	 * @since 6.7.6
	 */
	protected function rotate_file( string $file_path ): void {
		// Get base path without .log extension
		$base_path = preg_replace( '/\.log$/', '', $file_path );

		// Shift existing rotated files
		for ( $i = $this->max_rotated_files - 1; $i >= 1; $i-- ) {
			$old_file = $base_path . '-' . $i . '.log';
			$new_file = $base_path . '-' . ( $i + 1 ) . '.log';

			if ( file_exists( $old_file ) ) {
				if ( $i === $this->max_rotated_files - 1 ) {
					// Delete oldest file
					// phpcs:ignore WordPress.WP.AlternativeFunctions.unlink_unlink
					unlink( $old_file );
				} else {
					// phpcs:ignore WordPress.WP.AlternativeFunctions.rename_rename
					rename( $old_file, $new_file );
				}
			}
		}

		// Rotate current file with timestamp for uniqueness
		if ( file_exists( $file_path ) ) {
			$rotated_file = $base_path . '-1.log';
			// phpcs:ignore WordPress.WP.AlternativeFunctions.rename_rename
			rename( $file_path, $rotated_file );
		}
	}

	/**
	 * Ensure log directory exists and is secure.
	 *
	 * Only called once on first log write (lazy initialization for performance).
	 *
	 * @return void
	 * @since 6.7.6
	 */
	protected function ensure_directory_security(): void {
		// Create directory if it doesn't exist
		if ( ! file_exists( $this->log_dir ) ) {
			$result = wp_mkdir_p( $this->log_dir );
			if ( ! $result ) {
				// phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_error_log
				error_log( 'WP Travel Engine Logger: Failed to create log directory: ' . $this->log_dir );
				return; // Cannot proceed without directory
			}
		}

		// Create .htaccess to deny access
		$htaccess_file = $this->log_dir . '/.htaccess';
		if ( ! file_exists( $htaccess_file ) ) {
			// phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_file_put_contents
			file_put_contents( $htaccess_file, 'deny from all' );
		}

		// Create blank index.html
		$index_file = $this->log_dir . '/index.html';
		if ( ! file_exists( $index_file ) ) {
			// phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_file_put_contents
			file_put_contents( $index_file, '<!-- Silence is golden. -->' );
		}
	}

	/**
	 * Schedule cleanup event for a log file.
	 *
	 * Writes directly to database because this runs during shutdown (after Events::fire()).
	 *
	 * @param string $file_path Log file path.
	 * @return void
	 * @since 6.7.6
	 */
	protected function schedule_cleanup_event( string $file_path ): void {
		// Get retention days from settings
		$retention_days = (int) \WPTravelEngine\Logger\LoggerSettings::instance()->get( 'retention_days', 7 );
		$retention_days = max( 1, min( 365, $retention_days ) );

		// Calculate cleanup time
		$cleanup_time = gmdate( 'Y-m-d H:i:s', time() + $retention_days * DAY_IN_SECONDS );

		// Use filename hash as object_id
		$filename  = basename( $file_path );
		$object_id = absint( crc32( $filename ) );

		// Check if event already exists
		if ( Events::exists( 'wptravelengine_cleanup_log_file', $object_id, 'log_file' ) ) {
			return;
		}

		Events::add_event(
			'wptravelengine_cleanup_log_file',
			$object_id,
			'log_file',
			$cleanup_time,
			array( 'file_path' => $file_path )
		);
	}
}
