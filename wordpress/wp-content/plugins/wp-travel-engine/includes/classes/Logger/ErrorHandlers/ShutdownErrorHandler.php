<?php
/**
 * Error handler for automatic error capture.
 *
 * Captures important errors only (warnings and fatal errors):
 * - set_error_handler(): Captures warnings, user errors (as they occur)
 * - register_shutdown_function(): ONLY for true fatal errors (E_ERROR, E_PARSE)
 *
 * Does NOT capture: Deprecated, Notices, Strict (too noisy)
 *
 * @package WPTravelEngine\Logger\ErrorHandlers
 * @since 6.7.6
 */

namespace WPTravelEngine\Logger\ErrorHandlers;

use WPTravelEngine\Logger\Utilities\LogUtils;

/**
 * Error handler class.
 *
 * Captures ALL important errors from WP Travel Engine plugin and addons.
 * Ignores noise (deprecated, notices, strict).
 *
 * @since 6.7.6
 */
class ShutdownErrorHandler {

	/**
	 * Previously registered error handler (for proper chaining).
	 *
	 * @var callable|null
	 */
	protected static $previous_handler = null;

	/**
	 * Cached log level (performance optimization).
	 *
	 * @var string|null
	 */
	protected static $log_level_cache = null;

	/**
	 * Cached enabled status (performance optimization).
	 *
	 * @var bool|null
	 */
	protected static $enabled_cache = null;

	/**
	 * Catchable error types (captured by set_error_handler).
	 *
	 * These are captured immediately as they occur.
	 * Includes: Warnings and recoverable errors.
	 * Excludes: Deprecated, Notices, Strict.
	 *
	 * @var array
	 */
	protected static $catchable_types = array(
		E_WARNING,           // Warnings
		E_CORE_WARNING,      // Core warnings
		E_COMPILE_WARNING,   // Compile-time warnings
		E_USER_WARNING,      // User-triggered warnings
		E_USER_ERROR,        // User-triggered errors
		E_RECOVERABLE_ERROR, // Catchable fatal errors
	);

	/**
	 * Uncatchable fatal error types (captured by shutdown function).
	 *
	 * These CANNOT be caught by set_error_handler() - PHP limitation.
	 * Must use register_shutdown_function() for these.
	 *
	 * Note: E_USER_ERROR and E_RECOVERABLE_ERROR are in $catchable_types
	 * as they can be caught by set_error_handler().
	 *
	 * @var array
	 */
	protected static $uncatchable_fatal_types = array(
		E_ERROR,         // Fatal runtime errors
		E_PARSE,         // Parse errors
		E_CORE_ERROR,    // Core fatal errors
		E_COMPILE_ERROR, // Compile-time fatal errors
	);

	/**
	 * Register error handlers.
	 *
	 * Two handlers needed due to PHP limitations:
	 *
	 * 1. set_error_handler() - Captures warnings, user errors
	 *    - Runs immediately when error occurs
	 *    - Captures EVERY error (not just last one)
	 *    - Cannot capture E_ERROR or E_PARSE (PHP limitation)
	 *
	 * 2. register_shutdown_function() - Captures ONLY true fatal errors
	 *    - Runs at script end
	 *    - Only for E_ERROR, E_PARSE (can't be caught any other way)
	 *    - Minimal overhead - only checks if script died
	 *
	 * @return void
	 * @since 6.7.6
	 */
	public static function register(): void {
		if ( ! \WPTravelEngine\Logger\LoggerSettings::instance()->is_enabled() ) {
			return;
		}
		// Capture previous error handler to properly chain with other plugins
		$mask                   = E_WARNING | E_CORE_WARNING | E_COMPILE_WARNING
		| E_USER_WARNING | E_USER_ERROR | E_RECOVERABLE_ERROR;
		self::$previous_handler = set_error_handler( array( __CLASS__, 'handle_error' ), $mask );

		// Register shutdown handler ONLY for uncatchable fatal errors
		add_action( 'shutdown', array( __CLASS__, 'handle_shutdown' ), 9 );
	}

	/**
	 * Handle catchable errors as they occur.
	 *
	 * Called by PHP when warnings or recoverable errors happen.
	 * Captures EVERY error individually (not just the last one).
	 *
	 * Captures: Warnings, User Errors, Recoverable Errors
	 * Ignores: Deprecated, Notices, Strict (too noisy)
	 *
	 * IMPORTANT: Warnings respect the logger enabled/disabled setting.
	 * Fatal errors (in handle_shutdown) always log regardless of settings.
	 *
	 * @param int    $errno   Error level.
	 * @param string $errstr  Error message.
	 * @param string $errfile File where error occurred.
	 * @param int    $errline Line where error occurred.
	 * @return bool False to allow PHP's normal error handling.
	 * @since 6.7.6
	 */
	public static function handle_error( int $errno, string $errstr, string $errfile, int $errline ): bool {
		// Only capture important errors (warnings, user errors)
		// Ignore: E_DEPRECATED, E_NOTICE, E_STRICT, E_USER_DEPRECATED, E_USER_NOTICE
		if ( ! in_array( $errno, self::$catchable_types, true ) ) {
			return false; // Let PHP handle it (or ignore it)
		}

		// Only capture errors from WP Travel Engine plugin files
		// Pass backtrace to verify if WordPress core errors were caused by our plugin
		if ( ! self::is_plugin_error( $errfile, debug_backtrace( DEBUG_BACKTRACE_IGNORE_ARGS, 20 ) ) ) {
			return false;
		}

		// Check log level - if FATAL only, skip warnings
		// Cache log level to avoid DB calls on every error (performance optimization)
		if ( null === self::$log_level_cache ) {
			self::$log_level_cache = \WPTravelEngine\Logger\LoggerSettings::instance()->get( 'log_level', 'FATAL' );
		}
		if ( 'FATAL' === self::$log_level_cache ) {
			// Skip warnings - only fatal errors pass through
			return false;
		}

		// Rate limiting
		if ( ! self::check_rate_limit() ) {
			return false;
		}

		// Build error array
		$error = array(
			'type'    => $errno,
			'message' => $errstr,
			'file'    => $errfile,
			'line'    => $errline,
		);

		// Deduplication
		if ( ! self::should_log_error( $error ) ) {
			return false;
		}

		// Log the error
		self::log_error( $errno, $errstr, $errfile, $errline );

		// Call previous error handler if one was registered (proper chaining)
		if ( null !== self::$previous_handler && is_callable( self::$previous_handler ) ) {
			return (bool) call_user_func( self::$previous_handler, $errno, $errstr, $errfile, $errline );
		}

		// Return false to allow PHP's normal error handling to continue
		return false;
	}

	/**
	 * Handle shutdown - ONLY for uncatchable fatal errors.
	 *
	 * Called at script end. Only handles fatal errors that CANNOT be caught
	 * by set_error_handler() due to PHP limitations (E_ERROR, E_PARSE).
	 *
	 * All warnings are captured by handle_error() instead.
	 *
	 * Why needed: set_error_handler() cannot catch E_ERROR or E_PARSE.
	 * These kill the script immediately, so we must check at shutdown.
	 *
	 * IMPORTANT: Fatal errors are ALWAYS logged regardless of settings,
	 * because they break the site and must be tracked.
	 *
	 * @return void
	 * @since 6.7.6
	 */
	public static function handle_shutdown(): void {
		// Get the last error (if any)
		$error = error_get_last();

		// No error occurred
		if ( null === $error ) {
			return;
		}

		// Only handle uncatchable fatal errors (E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR)
		// All other errors are handled by handle_error()
		if ( ! in_array( $error['type'], self::$uncatchable_fatal_types, true ) ) {
			return;
		}

		// Only capture errors from WP Travel Engine plugin files
		// Pass error message so we can extract stack trace from it
		if ( ! self::is_plugin_error( $error['file'], null, $error['message'] ) ) {
			return;
		}

		// Rate limiting
		if ( ! self::check_rate_limit() ) {
			return;
		}

		// Deduplication
		if ( ! self::should_log_error( $error ) ) {
			return;
		}

		// Log the fatal error
		self::log_error(
			$error['type'],
			$error['message'],
			$error['file'],
			$error['line']
		);
	}

	/**
	 * Log an error to the logger.
	 *
	 * Shared method used by both handle_error() and handle_shutdown().
	 *
	 * @param int    $errno   Error type.
	 * @param string $errstr  Error message.
	 * @param string $errfile Error file.
	 * @param int    $errline Error line.
	 * @return void
	 * @since 6.7.6
	 */
	protected static function log_error( int $errno, string $errstr, string $errfile, int $errline ): void {
		// Get logger instance
		$logger = self::get_logger();

		// Map error type to log level
		$level = self::get_log_level_for_error_type( $errno );

		// Extract original stack trace from error message before stripping it
		// For uncaught exceptions, PHP includes the full stack trace in the error message
		// This is MORE valuable than debug_backtrace() at shutdown (which only shows hook chain)
		// Pattern handles all line ending styles (LF, CRLF, CR) for cross-platform compatibility
		$original_stack_trace = null;
		if ( preg_match( '/(?:\r\n|\r|\n)Stack trace:(?:\r\n|\r|\n)(.+)/s', $errstr, $stack_matches ) ) {
			$original_stack_trace = trim( $stack_matches[1] );
		}

		// Strip stack trace from error message for cleaner single-line logging
		$clean_errstr = preg_replace( '/(?:\r\n|\r|\n)Stack trace:.*/s', '', $errstr );

		// Build error message
		$message = sprintf(
			'PHP %s: %s in %s on line %d',
			self::get_error_type_name( $errno ),
			$clean_errstr,
			$errfile,
			$errline
		);

		// Build context (source will be detected later from stack trace if available)
		$context = array(
			'error_type' => $errno,
			'file'       => $errfile,
			'line'       => $errline,
		);

		// Add original stack trace if available (from uncaught exception)
		// This is the REAL stack trace showing the call chain, not the shutdown hook chain
		if ( null !== $original_stack_trace ) {
			$context['stack_trace'] = $original_stack_trace;
		}

		// Detect source from stack trace first (more accurate - shows actual caller)
		// Fall back to error file if no stack trace available
		if ( null !== $original_stack_trace ) {
			$source = self::detect_source_from_stack_trace( $original_stack_trace );
		} else {
			$source = LogUtils::detect_source_from_file( $errfile );
		}

		$context['source'] = $source;

		// Log the error
		$logger->log( $level, $message, $context );
	}

	/**
	 * Get log level for PHP error type.
	 *
	 * Simplified to just 2 levels: FATAL and WARNING.
	 *
	 * @param int $type PHP error type constant.
	 * @return string Log level.
	 * @since 6.7.6
	 */
	protected static function get_log_level_for_error_type( int $type ): string {
		// All fatal errors → FATAL
		if ( in_array(
			$type,
			array( E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR, E_USER_ERROR, E_RECOVERABLE_ERROR ),
			true
		) ) {
			return 'FATAL';
		}

		// All warnings → WARNING
		return 'WARNING';
	}

	/**
	 * Get human-readable error type name.
	 *
	 * Only includes error types we actually capture.
	 *
	 * @param int $type PHP error type constant.
	 * @return string Error type name.
	 * @since 6.7.6
	 */
	protected static function get_error_type_name( int $type ): string {
		$types = array(
			// Uncatchable fatal errors (shutdown handler only)
			E_ERROR             => 'Fatal Error',
			E_PARSE             => 'Parse Error',
			E_CORE_ERROR        => 'Core Fatal Error',
			E_COMPILE_ERROR     => 'Compile Fatal Error',

			// Catchable errors (set_error_handler)
			E_WARNING           => 'Warning',
			E_CORE_WARNING      => 'Core Warning',
			E_COMPILE_WARNING   => 'Compile Warning',
			E_USER_WARNING      => 'User Warning',
			E_USER_ERROR        => 'User Error',
			E_RECOVERABLE_ERROR => 'Recoverable Error',

			// NOT captured (too noisy):
			// E_NOTICE, E_USER_NOTICE, E_STRICT, E_DEPRECATED, E_USER_DEPRECATED
		);

		return isset( $types[ $type ] ) ? $types[ $type ] : 'Unknown Error';
	}

	/**
	 * Check if error is from WP Travel Engine plugin or addons.
	 *
	 * @param string      $file          Error file path.
	 * @param array|null  $backtrace     Optional backtrace from handle_error().
	 * @param string|null $error_message Optional error message from handle_shutdown().
	 * @return bool True if error is from plugin or addons.
	 * @since 6.7.6
	 */
	protected static function is_plugin_error( string $file, ?array $backtrace = null, ?string $error_message = null ): bool {
		// Check if file is in plugins directory
		if ( ! defined( 'WP_PLUGIN_DIR' ) ) {
			return false;
		}

		$plugins_dir = wp_normalize_path( WP_PLUGIN_DIR );
		$file        = wp_normalize_path( $file );

		// First, check if the error file itself is from our plugin
		if ( self::is_our_plugin_file( $file, $plugins_dir ) ) {
			return true;
		}

		// If error file is from a DIFFERENT plugin (not WordPress core), reject it
		if ( strpos( $file, $plugins_dir . '/' ) === 0 ) {
			// Error is from plugins directory but not our plugin = different plugin
			return false;
		}

		// If error message provided (from fatal error), extract and parse stack trace
		if ( null !== $error_message ) {
			return self::is_plugin_error_from_message( $error_message, $plugins_dir );
		}

		// If backtrace provided (from warning/error), use it
		if ( null !== $backtrace ) {
			return self::is_plugin_error_from_backtrace( $backtrace, $plugins_dir );
		}

		// No backtrace or message available - for WordPress core errors, accept them
		// (might be caused by our plugin)
		return true;
	}

	/**
	 * Check if error is from our plugin using backtrace array.
	 *
	 * @param array  $backtrace   Backtrace from debug_backtrace().
	 * @param string $plugins_dir Normalized plugins directory path.
	 * @return bool True if our plugin is in the call stack.
	 * @since 6.7.6
	 */
	protected static function is_plugin_error_from_backtrace( array $backtrace, string $plugins_dir ): bool {
		// Check backtrace - skip error handler frames, find first plugin
		foreach ( $backtrace as $frame ) {
			if ( ! isset( $frame['file'] ) ) {
				continue;
			}

			$frame_file = wp_normalize_path( $frame['file'] );

			// Skip error handler frames (contains Logger/ErrorHandlers or Logger/Handlers)
			if ( strpos( $frame_file, 'Logger/ErrorHandlers/' ) !== false ||
				strpos( $frame_file, 'Logger/Handlers/' ) !== false ) {
				continue;
			}

			// First non-error-handler frame: check if it's our plugin
			if ( self::is_our_plugin_file( $frame_file, $plugins_dir ) ) {
				return true; // Our plugin is first - we caused the error
			}

			// If it's a different plugin, reject immediately
			if ( strpos( $frame_file, $plugins_dir . '/' ) === 0 ) {
				return false; // Other plugin caused the error
			}
		}

		return false;
	}

	/**
	 * Check if error is from our plugin using error message stack trace.
	 *
	 * @param string $error_message Error message (may contain stack trace).
	 * @param string $plugins_dir   Normalized plugins directory path.
	 * @return bool True if our plugin is in the stack trace.
	 * @since 6.7.7
	 */
	protected static function is_plugin_error_from_message( string $error_message, string $plugins_dir ): bool {
		// Extract stack trace from error message
		// Pattern: matches "Stack trace:" followed by stack trace lines
		if ( ! preg_match( '/(?:\r\n|\r|\n)Stack trace:(?:\r\n|\r|\n)(.+)/s', $error_message, $matches ) ) {
			// No stack trace in message - for WordPress core errors, accept them
			return true;
		}

		$stack_trace = $matches[1];
		$lines       = explode( "\n", $stack_trace );

		// Parse stack trace lines to extract file paths
		// Format: #0 /path/to/file.php(123): function()
		foreach ( $lines as $line ) {
			// Extract file path from stack trace line
			// Pattern: #N /path/to/file.php(line): or #N [internal function]:
			if ( ! preg_match( '~^#\d+\s+([^(]+)\(\d+\):~', $line, $match ) ) {
				continue;
			}

			$frame_file = trim( $match[1] );
			$frame_file = wp_normalize_path( $frame_file );

			// Skip internal functions
			if ( '[internal function]' === $frame_file || '[internal' === $frame_file ) {
				continue;
			}

			// Skip error handler frames
			if ( strpos( $frame_file, 'Logger/ErrorHandlers/' ) !== false ||
				strpos( $frame_file, 'Logger/Handlers/' ) !== false ) {
				continue;
			}

			// First non-error-handler frame: check if it's our plugin
			if ( self::is_our_plugin_file( $frame_file, $plugins_dir ) ) {
				return true; // Our plugin is first - we caused the error
			}

			// If it's a different plugin, reject immediately
			if ( strpos( $frame_file, $plugins_dir . '/' ) === 0 ) {
				return false; // Other plugin caused the error
			}
		}

		// No plugin found in stack trace - for WordPress core errors, accept them
		return true;
	}

	/**
	 * Check if a file belongs to WP Travel Engine plugin or addons.
	 *
	 * @param string $file        File path to check.
	 * @param string $plugins_dir Normalized WP_PLUGIN_DIR path.
	 * @return bool True if file is from plugin or addons.
	 * @since 6.7.6
	 */
	protected static function is_our_plugin_file( string $file, string $plugins_dir ): bool {
		// Normalize path
		$file = wp_normalize_path( $file );

		// Must be in plugins directory
		if ( strpos( $file, $plugins_dir ) !== 0 ) {
			return false;
		}

		// Check if from main plugin
		if ( strpos( $file, $plugins_dir . '/wp-travel-engine/' ) === 0 ) {
			return true;
		}

		// Check for addons with these patterns:
		// - wp-travel-engine-* (e.g., wp-travel-engine-stripe)
		// - wptravelengine-* (e.g., wptravelengine-advanced-gallery)
		// - wte-* (e.g., wte-paypal-express-checkout)
		// - wpte-* (e.g., wpte-fixed-departure)
		if ( preg_match( '#/plugins/(wp-travel-engine-[^/]+|wptravelengine-[^/]+|wte-[^/]+|wpte-[^/]+)/#', $file ) ) {
			return true;
		}

		return false;
	}

	/**
	 * Detect source from stack trace string.
	 *
	 * Parses the stack trace to find the first frame from WP Travel Engine plugins/addons.
	 * This is more accurate than using the error file location, as it shows the actual caller.
	 *
	 * Example: If PostModel.php throws an error but was called by Partial Payment addon,
	 * the source should be 'wp-travel-engine-partial-payment', not 'wptravelengine'.
	 *
	 * @param string $stack_trace Stack trace string from exception.
	 * @return string Source name.
	 * @since 6.7.7
	 */
	protected static function detect_source_from_stack_trace( string $stack_trace ): string {
		if ( ! defined( 'WP_PLUGIN_DIR' ) ) {
			return 'wptravelengine';
		}

		$plugins_dir = wp_normalize_path( WP_PLUGIN_DIR );

		// Parse stack trace lines to extract file paths
		$lines = explode( "\n", $stack_trace );

		foreach ( $lines as $line ) {
			// Extract file path from stack trace line
			// Use ~ as delimiter to avoid conflict with # in the pattern
			if ( preg_match( '~^#\d+\s+([^(]+)\(\d+\):~', trim( $line ), $matches ) ) {
				$file   = trim( $matches[1] );
				$source = self::get_source_from_file( $file, $plugins_dir );

				// If we found a specific addon (not just 'wptravelengine'), return it
				// This means we found the actual caller addon
				if ( 'wptravelengine' !== $source ) {
					return $source;
				}
			}
		}

		// Fallback: return 'wptravelengine' if no addon found in stack
		return 'wptravelengine';
	}

	/**
	 * Extract source name from a single file path.
	 *
	 * @param string $file        File path to check.
	 * @param string $plugins_dir Normalized WP_PLUGIN_DIR path.
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
	 * Check rate limit to prevent logging storms.
	 *
	 * Uses in-memory counter first (reliable during shutdown), then transients as backup.
	 * Limits to 100 errors per request to prevent server overload during catastrophic failures.
	 *
	 * @return bool True if under rate limit, false if limit exceeded.
	 * @since 6.7.6
	 */
	protected static function check_rate_limit(): bool {
		// Per-request in-memory counter (reliable, works even after fatal errors)
		static $request_error_count = 0;

		// Hard limit: 100 errors per request (prevents logging storms in single request)
		if ( $request_error_count >= 100 ) {
			return false;
		}

		// Increment in-memory counter (always reliable)
		++$request_error_count;

		// Optional: Try transient-based cross-request rate limiting (best effort)
		// If DB is broken after fatal error, this will fail silently (graceful degradation)
		try {
			$rate_limit_key = 'wte_log_rate_limit';
			$log_count      = (int) get_transient( $rate_limit_key );

			// Cross-request limit: 500 errors per minute
			if ( $log_count >= 500 ) {
				return false;
			}

			// Increment cross-request counter (may fail if DB broken, that's OK)
			set_transient( $rate_limit_key, $log_count + 1, 60 );
		} catch ( \Throwable $e ) {
			// DB broken or WordPress not fully initialized - ignore transient check
			// In-memory limit is still active (100 per request)
		}

		return true;
	}

	/**
	 * Check if this specific error should be logged.
	 *
	 * Prevents duplicate logging of the same error within the configured cache duration.
	 * Uses MD5 hash of file:line:message to identify unique errors.
	 *
	 * @param array $error Error array from error_get_last().
	 * @return bool True if should log, false if already logged recently.
	 * @since 6.7.6
	 */
	protected static function should_log_error( array $error ): bool {
		// Create unique hash for this error
		$error_hash = md5(
			$error['file'] . ':' . $error['line'] . ':' . $error['message']
		);

		$cache_key = 'wte_logged_error_' . $error_hash;

		// Check if we've logged this exact error recently
		if ( get_transient( $cache_key ) ) {
			return false;
		}

		// Get cache duration from settings (in minutes)
		$cache_duration_minutes = (int) \WPTravelEngine\Logger\LoggerSettings::instance()->get( 'cache_duration', 1440 );
		$cache_duration_seconds = $cache_duration_minutes * 60;

		// Mark as logged for the configured duration
		set_transient( $cache_key, true, $cache_duration_seconds );

		return true;
	}

	/**
	 * Get logger instance with lazy initialization (internal use only).
	 *
	 * This method is private to ShutdownErrorHandler and should NOT be
	 * used for manual logging. It exists solely for automatic PHP error capture.
	 *
	 * @return \WPTravelEngine\Logger\Logger
	 * @since 6.7.6
	 */
	private static function get_logger() {
		static $logger      = null;
		static $initialized = false;

		// Return existing instance if already initialized
		if ( $initialized && null !== $logger ) {
			return $logger;
		}

		// Initialize logger instance
		$logger = \WPTravelEngine\Logger\Logger::instance();

		// Cache wp_upload_dir() result to avoid repeated filesystem calls
		static $log_dir = null;
		if ( null === $log_dir ) {
			$upload_dir = wp_upload_dir();
			$log_dir    = $upload_dir['basedir'] . '/wte-logs';
		}

		// Add file handler
		$file_handler = new \WPTravelEngine\Logger\Handlers\FileHandler( $log_dir );
		$logger->add_handler( $file_handler );

		$initialized = true;

		return $logger;
	}
}
