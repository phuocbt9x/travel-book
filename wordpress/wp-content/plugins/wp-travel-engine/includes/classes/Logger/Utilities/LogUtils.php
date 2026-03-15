<?php
/**
 * Log utilities - helper for logging operations.
 *
 * Handles parsing, formatting, file operations, and cleanup.
 *
 * @package WPTravelEngine\Logger\Utilities
 * @since 6.7.6
 */

namespace WPTravelEngine\Logger\Utilities;

/**
 * Log utilities class.
 *
 * @since 6.7.6
 */
class LogUtils {

	// ============================================================================
	// FILE MANAGEMENT METHODS
	// ============================================================================


	/**
	 * Get log directory path.
	 *
	 * @return string Log directory path.
	 */
	public static function get_log_directory(): string {
		$upload_dir = wp_upload_dir();
		return $upload_dir['basedir'] . '/wte-logs';
	}

	/**
	 * Get log directory size in bytes.
	 *
	 * @return int Directory size in bytes.
	 */
	public static function get_log_directory_size(): int {
		$log_dir = self::get_log_directory();
		if ( ! is_dir( $log_dir ) ) {
			return 0;
		}

		$size = 0;
		// Use specific pattern *.log to match only log files (not .log.bak, .log.txt, etc.)
		$files = glob( $log_dir . '/*.log' );

		if ( is_array( $files ) ) {
			foreach ( $files as $file ) {
				// phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_filesize
				$file_size = filesize( $file );
				// Skip if filesize() returns false (file deleted/inaccessible)
				if ( false !== $file_size ) {
					$size += $file_size;
				}
			}
		}

		return $size;
	}

	/**
	 * Detect source name from error file path.
	 *
	 * Determines which plugin/addon the error came from:
	 * - wp-travel-engine → 'wptravelengine'
	 * - wp-travel-engine-* → 'wp-travel-engine-addon-name'
	 * - wptravelengine-* → 'wptravelengine-addon-name'
	 * - wte-* → 'wte-addon-name'
	 * - wpte-* → 'wpte-addon-name'
	 *
	 * If error file is from WordPress core, checks backtrace to find
	 * the actual plugin that triggered the error.
	 *
	 * @param string $file Error file path.
	 * @return string Source name for log file.
	 * @since 6.7.6
	 */
	public static function detect_source_from_file( string $file ): string {
		if ( ! defined( 'WP_PLUGIN_DIR' ) ) {
			return 'wptravelengine';
		}

		$plugins_dir = wp_normalize_path( WP_PLUGIN_DIR );

		// Try to detect source from error file first
		$source = self::get_source_from_file( $file, $plugins_dir );
		if ( 'wptravelengine' !== $source ) {
			return $source; // Found specific addon
		}

		// Normalize path to check if it's in plugins directory
		$file_normalized = wp_normalize_path( $file );

		// check backtrace to find the actual plugin source
		if ( strpos( $file_normalized, $plugins_dir ) !== 0 ) {
			$backtrace = debug_backtrace( DEBUG_BACKTRACE_IGNORE_ARGS, 15 );
			foreach ( $backtrace as $frame ) {
				if ( ! isset( $frame['file'] ) ) {
					continue;
				}

				$frame_source = self::get_source_from_file( $frame['file'], $plugins_dir );
				if ( 'wptravelengine' !== $frame_source ) {
					return $frame_source; // Found specific addon in backtrace
				}

				// Check if it's from main plugin
				$frame_file = wp_normalize_path( $frame['file'] );
				if ( strpos( $frame_file, $plugins_dir . '/wp-travel-engine/' ) === 0 ) {
					return 'wptravelengine';
				}
			}
		}

		// Fallback to main plugin
		return 'wptravelengine';
	}

	/**
	 * Extract source plugin name from file path.
	 *
	 * Helper method to identify which plugin/addon a file belongs to.
	 *
	 * @param string $file        File path to check.
	 * @param string $plugins_dir WordPress plugins directory path.
	 * @return string Source name, or 'wptravelengine' if not from addon.
	 * @since 6.7.6
	 */
	protected static function get_source_from_file( string $file, string $plugins_dir ): string {
		// Normalize path
		$file = wp_normalize_path( $file );

		// Check if from main wp-travel-engine plugin
		if ( strpos( $file, $plugins_dir . '/wp-travel-engine/' ) === 0 ) {
			return 'wptravelengine';
		}

		// Extract plugin folder name if file is in plugins directory
		$prefix = $plugins_dir . '/';
		if ( strpos( $file, $prefix ) === 0 ) {
			$relative = substr( $file, strlen( $prefix ) );
			$parts    = explode( '/', $relative );
			$folder   = $parts[0] ?? '';

			// Check if folder matches addon patterns
			if ( ! empty( $folder ) && preg_match( '/^(wp-travel-engine-.+|wptravelengine-.+|wte-.+|wpte-.+)$/', $folder ) ) {
				return $folder;
			}
		}

		// Not from our plugin/addons
		return 'wptravelengine';
	}

	/**
	 * Clear all log file caches.
	 *
	 * Uses delete_transient() for known keys instead of LIKE queries for better performance.
	 *
	 * @return void
	 */
	public static function clear_cache(): void {
		$log_dir = self::get_log_directory();

		// Delete sources cache (predictable key)
		$sources_key = 'wte_log_sources_' . md5( $log_dir );
		delete_transient( $sources_key );

		// Delete parse_file() caches for all known log files
		// Note: We can't delete ALL possible variations (different filters, max_entries, old mtimes)
		// but we delete the current state which covers 99% of cases
		if ( is_dir( $log_dir ) ) {
			$utils = new self();
			$files = $utils->get_log_files( $log_dir );

			foreach ( $files as $file ) {
				// Delete cache for this file with current mtime and no filters
				// phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_filemtime
				$file_mtime = file_exists( $file ) ? filemtime( $file ) : 0;
				$cache_key  = sprintf(
					'wte_logs_%s_%d_%s_%d',
					md5( $file ),
					$file_mtime,
					md5( wp_json_encode( array() ) ), // No filters
					10000 // Default max_entries
				);
				delete_transient( $cache_key );
			}
		}
	}

	/**
	 * Clear all log cleanup events.
	 *
	 * Called when all logs are deleted manually.
	 *
	 * @return void
	 * @since 6.7.6
	 */
	public static function clear_log_events(): void {
		global $wpdb;

		// Use %i placeholder for table name (WP 6.2+)
		$table = $wpdb->prefix . 'wptravelengine_events';

		$wpdb->query(
			$wpdb->prepare(
				'DELETE FROM %i WHERE event_name = %s AND object_type = %s',
				$table,
				'wptravelengine_cleanup_log_file',
				'log_file'
			)
		);
	}

	/**
	 * Clear cleanup event for a specific log file.
	 *
	 * Called when a log file is deleted manually.
	 *
	 * @param string $file_path Full path to the log file.
	 * @return void
	 * @since 6.7.6
	 */
	public static function clear_log_event_for_file( string $file_path ): void {
		global $wpdb;

		// Use %i placeholder for table name (WP 6.2+)
		$table = $wpdb->prefix . 'wptravelengine_events';

		// Calculate the same object_id used when creating the event
		$filename  = basename( $file_path );
		$object_id = absint( crc32( $filename ) );

		$wpdb->query(
			$wpdb->prepare(
				'DELETE FROM %i WHERE event_name = %s AND object_type = %s AND object_id = %d',
				$table,
				'wptravelengine_cleanup_log_file',
				'log_file',
				$object_id
			)
		);
	}

	/**
	 * Parse log file.
	 *
	 * Parses multi-line log entries written by FileHandler.
	 *
	 * @param string $file_path File path.
	 * @param array  $filters   Filters to apply (level, source, date, search).
	 * @param int    $max_entries Maximum entries to return (0 = unlimited, default: 10000 for memory safety).
	 * @return array Parsed log entries.
	 */
	public function parse_file( string $file_path, array $filters = array(), int $max_entries = 10000 ): array {
		if ( ! file_exists( $file_path ) ) {
			return array();
		}

		// Memory safety: check file size before parsing (max 100MB recommended)
		// phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_filesize
		$file_size = filesize( $file_path );
		$max_size  = 100 * 1024 * 1024; // 100MB
		if ( false === $file_size || $file_size > $max_size ) {
			// Return error indicator instead of empty array for large files
			return array(
				array(
					'timestamp' => gmdate( 'Y-m-d H:i:s' ),
					'level'     => 'WARNING',
					'message'   => sprintf( 'Log file too large to parse (%s). Maximum size: 100MB', size_format( $file_size ) ),
					'context'   => array( 'source' => 'wptravelengine' ),
				),
			);
		}

		// Generate smart cache key (include max_entries to avoid cache collisions)
		$cache_key = $this->get_cache_key( $file_path, $filters, $max_entries );
		$cached    = get_transient( $cache_key );
		if ( false !== $cached ) {
			return $cached;
		}

		// Read and parse file (with memory protection via max_entries limit and hard line limit)
		// Now supports multi-line entries with stack traces
		$entries        = array();
		$lines_read     = 0;
		$max_lines_read = 50000; // Hard limit: prevent DoS from massive files
		// phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_fopen
		$handle = fopen( $file_path, 'r' );

		if ( $handle ) {
			// State machine for multi-line log entries
			$main_line   = null;   // Current entry's main log line
			$in_stack    = false;  // Currently reading stack trace section
			$stack_trace = null;   // Accumulated stack trace lines

			while ( ( $line = fgets( $handle ) ) !== false ) { // phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_fgets
				++$lines_read;

				// Hard limit protection: stop reading after max lines (prevents memory exhaustion)
				if ( $lines_read > $max_lines_read ) {
					break;
				}

				$trimmed = trim( $line );

				// Separator line marks end of entry (written by FileHandler)
				if ( preg_match( '/^-{10,}$/', $trimmed ) ) {
					// Process buffered entry
					if ( null !== $main_line ) {
						$entry = $this->parse_line( $main_line );

						// Add stack trace if collected
						if ( $entry && ! empty( $stack_trace ) ) {
							$entry['context']['stack_trace'] = trim( $stack_trace );
						}

						if ( $entry && $this->matches_filters( $entry, $filters ) ) {
							$entries[] = $entry;

							// Memory protection: stop if max entries reached (0 = unlimited)
							if ( $max_entries > 0 && count( $entries ) >= $max_entries ) {
								// Clear state before breaking to prevent duplicate in flush block
								$main_line = null;
								break;
							}
						}
					}

					// Reset state
					$main_line   = null;
					$stack_trace = null;
					$in_stack    = false;
					continue;
				}

				// Check if this is a stack trace section
				if ( $trimmed === 'STACK TRACE:' ) {
					$in_stack    = true;
					$stack_trace = '';
					continue;
				}

				// Collect stack trace lines (including empty lines - PHP stack traces can have blank lines)
				if ( $in_stack ) {
					$stack_trace .= $line; // Keep original newlines and blank lines
					continue;
				}

				// Main log line (timestamp prefix)
				if ( preg_match( '/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}/', $trimmed ) ) {
					$main_line = $line;
				}
			}

			// Flush last buffered entry (if file doesn't end with separator)
			if ( null !== $main_line ) {
				$entry = $this->parse_line( $main_line );

				// Add stack trace if collected
				if ( $entry && ! empty( $stack_trace ) ) {
					$entry['context']['stack_trace'] = trim( $stack_trace );
				}

				if ( $entry && $this->matches_filters( $entry, $filters ) ) {
					$entries[] = $entry;
				}
			}

			fclose( $handle ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_fclose
		}

		// Cache the results (setting is in minutes, convert to seconds for transient)
		$cache_duration_minutes = (int) \WPTravelEngine\Logger\LoggerSettings::instance()->get( 'cache_duration', 5 );
		set_transient( $cache_key, $entries, $cache_duration_minutes * 60 );

		return $entries;
	}

	/**
	 * Parse a single log line.
	 *
	 * @param string $line Log line.
	 * @return array|null Parsed entry or null if invalid.
	 */
	public function parse_line( string $line ): ?array {
		$line = trim( $line );
		if ( empty( $line ) ) {
			return null;
		}

		// Pattern: YYYY-MM-DD HH:MM:SS LEVEL Message CONTEXT: {...}
		$pattern = '/^(\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2})\s+([A-Z]+)\s+(.+?)(?:\s+CONTEXT:\s+(.+))?$/';

		if ( ! preg_match( $pattern, $line, $matches ) ) {
			return null;
		}

		$timestamp = $matches[1];
		$level     = $matches[2];
		$message   = $matches[3];
		$context   = isset( $matches[4] ) ? json_decode( $matches[4], true ) : array();

		if ( ! is_array( $context ) ) {
			$context = array();
		}

		return array(
			'timestamp' => $timestamp,
			'level'     => $level,
			'message'   => $message,
			'context'   => $context,
		);
	}

	/**
	 * Get all log files from directory.
	 *
	 * @param string $log_dir Log directory path.
	 * @return array Array of log file paths.
	 */
	public function get_log_files( string $log_dir ): array {
		if ( ! is_dir( $log_dir ) ) {
			return array();
		}

		$files = glob( $log_dir . '/*.log' );

		if ( ! is_array( $files ) ) {
			return array();
		}

		// Sort by modification time (newest first)
		usort(
			$files,
			function ( $a, $b ) {
				// phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_filemtime
				$time_a = filemtime( $a );
				// phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_filemtime
				$time_b = filemtime( $b );

				// Handle cases where filemtime() returns false (file deleted/inaccessible)
				if ( false === $time_a && false === $time_b ) {
					return 0;
				}
				if ( false === $time_a ) {
					return 1; // Move invalid files to end
				}
				if ( false === $time_b ) {
					return -1; // Move invalid files to end
				}

				return $time_b - $time_a;
			}
		);

		return $files;
	}

	/**
	 * Get unique sources from log files.
	 *
	 * Uses lightweight regex extraction from CONTEXT JSON instead of full parse.
	 * Performance: ~100x faster than full parse for large log directories.
	 * Uses transient caching to prevent N+1 performance issues.
	 *
	 * @param string $log_dir Log directory path.
	 * @return array Array of unique sources.
	 */
	public function get_sources( string $log_dir ): array {
		// Check cache first (N+1 performance optimization)
		$cache_key = 'wte_log_sources_' . md5( $log_dir );
		$cached    = get_transient( $cache_key );
		if ( false !== $cached && is_array( $cached ) ) {
			return $cached;
		}

		$files   = $this->get_log_files( $log_dir );
		$sources = array();

		foreach ( $files as $file ) {
			// Memory safety: skip files > 100MB (prevent exhaustion)
			// phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_filesize
			$file_size = filesize( $file );
			if ( false === $file_size || $file_size > 100 * 1024 * 1024 ) {
				continue;
			}

			// Performance: Line-by-line reading with lightweight regex (memory-efficient)
			// phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_fopen
			$handle = fopen( $file, 'r' );

			if ( $handle ) {
				while ( ( $line = fgets( $handle ) ) !== false ) { // phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_fgets
					// Extract source from CONTEXT JSON in this line
					if ( preg_match( '/"source"\s*:\s*"([a-zA-Z0-9_-]+)"/', $line, $match ) ) {
						$sources[] = $match[1];
					}
				}

				fclose( $handle ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_fclose
			}
		}

		// Remove duplicates and sort
		$sources = array_unique( $sources );
		sort( $sources );

		// Cache for 5 minutes (same as log cache duration setting)
		$cache_duration_minutes = (int) \WPTravelEngine\Logger\LoggerSettings::instance()->get( 'cache_duration', 5 );
		set_transient( $cache_key, $sources, $cache_duration_minutes * 60 );

		return $sources;
	}

	/**
	 * Generate smart cache key with file modification time.
	 *
	 * @param string $file_path File path.
	 * @param array  $filters   Filters to apply.
	 * @param int    $max_entries Maximum entries limit.
	 * @return string Cache key.
	 */
	protected function get_cache_key( string $file_path, array $filters, int $max_entries = 10000 ): string {
		// phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_filemtime
		$file_mtime = file_exists( $file_path ) ? filemtime( $file_path ) : 0;
		return sprintf(
			'wte_logs_%s_%d_%s_%d',
			md5( $file_path ),
			$file_mtime,
			md5( wp_json_encode( $filters ) ),
			$max_entries
		);
	}

	/**
	 * Check if entry matches filters.
	 *
	 * @param array $entry   Log entry.
	 * @param array $filters Filters to apply.
	 * @return bool True if matches.
	 */
	protected function matches_filters( array $entry, array $filters ): bool {
		// Level filter
		if ( ! empty( $filters['level'] ) && $filters['level'] !== 'all' ) {
			if ( strtoupper( $entry['level'] ) !== strtoupper( $filters['level'] ) ) {
				return false;
			}
		}

		// Source filter
		if ( ! empty( $filters['source'] ) && $filters['source'] !== 'all' ) {
			$entry_source = $entry['context']['source'] ?? 'wptravelengine';
			if ( $entry_source !== $filters['source'] ) {
				return false;
			}
		}

		// Date filter
		if ( ! empty( $filters['date'] ) && $filters['date'] !== 'all' ) {
			$entry_timestamp = strtotime( $entry['timestamp'] );
			$now             = current_time( 'timestamp' );

			switch ( $filters['date'] ) {
				case 'today':
					if ( ! ( $entry_timestamp >= strtotime( 'today', $now ) ) ) {
						return false;
					}
					break;

				case 'last_7_days':
					if ( ! ( $entry_timestamp >= strtotime( '-7 days', $now ) ) ) {
						return false;
					}
					break;

				case 'last_30_days':
					if ( ! ( $entry_timestamp >= strtotime( '-30 days', $now ) ) ) {
						return false;
					}
					break;

				case 'custom':
					if ( ! empty( $filters['date_from'] ) ) {
						$from = strtotime( $filters['date_from'] );
						if ( $entry_timestamp < $from ) {
							return false;
						}
					}

					if ( ! empty( $filters['date_to'] ) ) {
						$to = strtotime( $filters['date_to'] . ' 23:59:59' );
						if ( $entry_timestamp > $to ) {
							return false;
						}
					}
					break;
			}
		}

		// Search filter
		if ( ! empty( $filters['search'] ) ) {
			$search = strtolower( $filters['search'] );
			$text   = strtolower( $entry['message'] . ' ' . wp_json_encode( $entry['context'] ) );

			if ( strpos( $text, $search ) === false ) {
				return false;
			}
		}

		return true;
	}

	// ============================================================================
	// FORMATTING METHODS
	// ============================================================================

	/**
	 * Format timestamp for display in admin UI.
	 *
	 * Converts ISO 8601 timestamp string to localized display format.
	 * Uses wp_date() for WordPress timezone support.
	 *
	 * @param string $timestamp ISO 8601 timestamp string.
	 * @return string Formatted localized timestamp.
	 */
	public static function format_display_timestamp( string $timestamp ): string {
		$datetime = strtotime( $timestamp );
		if ( ! $datetime ) {
			return $timestamp;
		}

		return wp_date( 'Y-m-d H:i:s', $datetime );
	}

	/**
	 * Format log level as HTML badge.
	 *
	 * Simplified to just FATAL and WARNING.
	 *
	 * @param string $level Log level.
	 * @return string HTML badge.
	 */
	public static function format_level( string $level ): string {
		$level = strtoupper( $level );

		// Normalize old levels to new simplified levels
		if ( in_array( $level, array( 'ERROR', 'CRITICAL', 'EMERGENCY', 'ALERT' ), true ) ) {
			$level = 'FATAL';
		}

		// Only 2 colors now
		$colors = array(
			'FATAL'   => '#dc143c', // Red
			'WARNING' => '#ffa500', // Orange
		);

		$color = $colors[ $level ] ?? '#808080'; // Gray for unknown

		return sprintf(
			'<span style="display:inline-block;padding:2px 8px;border-radius:3px;background-color:%s;color:#fff;font-size:11px;font-weight:600;">%s</span>',
			esc_attr( $color ),
			esc_html( $level )
		);
	}

	/**
	 * Format message with truncation.
	 *
	 * @param string $message    Message to format.
	 * @param int    $max_length Maximum length before truncation.
	 * @return string Formatted message.
	 */
	public static function format_message( string $message, int $max_length = 100 ): string {
		if ( strlen( $message ) > $max_length ) {
			return substr( $message, 0, $max_length ) . '...';
		}

		return $message;
	}

	/**
	 * Format context data as pretty JSON.
	 *
	 * @param array $context Context data.
	 * @return string Formatted JSON.
	 */
	public static function format_context( array $context ): string {
		// Remove sensitive or large data (stack traces are shown separately)
		$filtered = array_filter(
			$context,
			function ( $key ) {
				return ! in_array( $key, array( 'backtrace', 'trace', 'stack_trace' ), true );
			},
			ARRAY_FILTER_USE_KEY
		);

		return wp_json_encode( $filtered, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE );
	}

	/**
	 * Format backtrace for display.
	 *
	 * @param string|array $trace Backtrace string or array.
	 * @return string Formatted backtrace.
	 */
	public static function format_backtrace( $trace ): string {
		if ( is_string( $trace ) ) {
			return $trace;
		}

		if ( is_array( $trace ) ) {
			$output = array();
			foreach ( $trace as $i => $frame ) {
				$line = '#' . $i . ' ';

				if ( isset( $frame['file'] ) ) {
					$line .= $frame['file'];
					if ( isset( $frame['line'] ) ) {
						$line .= ':' . $frame['line'];
					}
					$line .= ' - ';
				}

				if ( isset( $frame['class'] ) ) {
					$line .= $frame['class'] . $frame['type'];
				}

				if ( isset( $frame['function'] ) ) {
					$line .= $frame['function'] . '()';
				}

				$output[] = $line;
			}

			return implode( "\n", $output );
		}

		return '';
	}
}
