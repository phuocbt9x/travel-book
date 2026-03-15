<?php
/**
 * Main logger implementation.
 *
 * Simplified two-level logging system: FATAL and WARNING.
 *
 * @package WPTravelEngine\Logger
 * @since 6.7.6
 */

namespace WPTravelEngine\Logger;

use WPTravelEngine\Logger\Handlers\Handler;
use WPTravelEngine\Logger\Utilities\LogUtils;
use WPTravelEngine\Traits\Singleton;

/**
 * Logger class.
 *
 * Simplified logger with two levels: FATAL and WARNING.
 * All errors are automatically captured by ShutdownErrorHandler.
 *
 * @since 6.7.6
 */
class Logger {

	use Singleton;

	/**
	 * Registered handlers.
	 *
	 * @var Handler[]
	 */
	protected array $handlers = array();

	/**
	 * Add a handler.
	 *
	 * @param Handler $handler Handler instance.
	 * @return void
	 * @since 6.7.6
	 */
	public function add_handler( Handler $handler ): void {
		$this->handlers[] = $handler;
	}

	/**
	 * Log a fatal error.
	 *
	 * Fatal errors: E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR, E_USER_ERROR, E_RECOVERABLE_ERROR.
	 *
	 * @api Public API method for logging fatal errors.
	 *
	 * @param string $message Log message.
	 * @param array  $context Additional context.
	 * @return void
	 * @since 6.7.6
	 */
	public function fatal( string $message, array $context = array() ): void {
		$this->log( 'FATAL', $message, $context );
	}

	/**
	 * Log a warning.
	 *
	 * Warnings: E_WARNING, E_CORE_WARNING, E_COMPILE_WARNING, E_USER_WARNING.
	 *
	 * @api Public API method for logging warnings.
	 *
	 * @param string $message Log message.
	 * @param array  $context Additional context.
	 * @return void
	 * @since 6.7.6
	 */
	public function warning( string $message, array $context = array() ): void {
		$this->log( 'WARNING', $message, $context );
	}

	/**
	 * Log with an arbitrary level.
	 *
	 * Supported levels: FATAL and WARNING.
	 * PSR-3 backward compatibility: EMERGENCY, ALERT, CRITICAL, ERROR map to FATAL.
	 * PSR-3 backward compatibility: INFO, DEBUG, NOTICE map to WARNING.
	 *
	 * @param string $level   Log level (FATAL, WARNING, or PSR-3 levels).
	 * @param string $message Log message.
	 * @param array  $context Additional context.
	 * @return void
	 * @throws \InvalidArgumentException If level is not recognized.
	 * @since 6.7.6
	 */
	public function log( string $level, string $message, array $context = array() ): void {
		// Normalize level to uppercase
		$level = strtoupper( $level );

		// Map PSR-3 levels to simplified levels (backward compatibility)
		if ( in_array( $level, array( 'EMERGENCY', 'ALERT', 'CRITICAL', 'ERROR' ), true ) ) {
			$level = 'FATAL';
		} elseif ( in_array( $level, array( 'INFO', 'DEBUG', 'NOTICE' ), true ) ) {
			$level = 'WARNING';
		} elseif ( ! in_array( $level, array( 'FATAL', 'WARNING' ), true ) ) {
			// Unknown levels throw exception (PSR-3 compliance)
			throw new \InvalidArgumentException(
				sprintf(
					'Unknown log level: %s. Supported levels: FATAL, WARNING (or PSR-3: EMERGENCY, ALERT, CRITICAL, ERROR, INFO, DEBUG, NOTICE)',
					$level
				)
			);
		}

		// Check minimum log level from settings
		$min_level = strtoupper( LoggerSettings::instance()->get( 'log_level', 'FATAL' ) );

		// Simple level check: if setting is FATAL, only log FATAL
		if ( 'FATAL' === $min_level && 'WARNING' === $level ) {
			return; // Don't log warnings if set to FATAL only
		}

		// Performance: Only capture backtrace for FATAL errors (not for WARNING)
		// Backtrace generation has overhead, and warnings are more common
		// Skip if stack_trace already exists (original exception stack is more valuable than debug_backtrace at shutdown)
		if ( 'FATAL' === $level && ! isset( $context['backtrace'] ) && ! isset( $context['stack_trace'] ) ) {
			$backtrace = debug_backtrace( DEBUG_BACKTRACE_IGNORE_ARGS, 5 );
			// Remove logger calls from backtrace
			$backtrace            = array_filter(
				$backtrace,
				function ( $trace ) {
					return ! isset( $trace['class'] ) || strpos( $trace['class'], 'WPTravelEngine\Logger' ) === false;
				}
			);
			$context['backtrace'] = array_values( $backtrace );
		}

		// Detect source from backtrace if not provided (only if backtrace exists)
		if ( ! isset( $context['source'] ) && ! empty( $context['backtrace'] ) ) {
			$context['source'] = $this->detect_source_from_backtrace( $context['backtrace'] );
		}

		foreach ( $this->handlers as $handler ) {
			$handler->handle( $level, $message, $context );
		}
	}

	/**
	 * Detect source from backtrace.
	 *
	 * @param array $backtrace Debug backtrace.
	 * @return string Source identifier.
	 * @since 6.7.6
	 */
	protected function detect_source_from_backtrace( array $backtrace ): string {
		foreach ( $backtrace as $trace ) {
			if ( isset( $trace['file'] ) ) {
				return LogUtils::detect_source_from_file( $trace['file'] );
			}
		}

		return 'wptravelengine';
	}

	/**
	 * Handle log file cleanup event.
	 *
	 * Deletes the log file specified in event data.
	 *
	 * @param array $event_data Event data array.
	 * @return void
	 * @since 6.7.6
	 */
	public static function handle_log_file_cleanup( array $event_data ): void {
		if ( ! isset( $event_data['file_path'] ) || ! file_exists( $event_data['file_path'] ) ) {
			return;
		}

		$file_path = $event_data['file_path'];

		// Don't delete index.html or .htaccess
		$basename = basename( $file_path );
		if ( in_array( $basename, array( 'index.html', '.htaccess' ), true ) ) {
			return;
		}

		// Delete the log file
		// phpcs:ignore WordPress.WP.AlternativeFunctions.unlink_unlink
		if ( unlink( $file_path ) ) {
			// Also delete rotated versions (wptravelengine-2026-01-29-1.log, -2.log, etc.)
			$base_path     = preg_replace( '/\.log$/', '', $file_path );
			$rotated_files = glob( $base_path . '-*.log' );
			if ( is_array( $rotated_files ) ) {
				// Get log directory for path traversal protection
				$log_dir      = dirname( $file_path );
				$real_log_dir = realpath( $log_dir );

				foreach ( $rotated_files as $rotated_file ) {
					// Security: Verify file is within log directory (path traversal protection)
					$real_rotated = realpath( $rotated_file );
					if ( false === $real_rotated || false === $real_log_dir || strpos( $real_rotated, $real_log_dir ) !== 0 ) {
						continue;
					}

					// phpcs:ignore WordPress.WP.AlternativeFunctions.unlink_unlink
					unlink( $rotated_file );
				}
			}
		}
	}
}
