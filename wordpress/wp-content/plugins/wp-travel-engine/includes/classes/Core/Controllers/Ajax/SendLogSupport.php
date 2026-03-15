<?php
/**
 * Send Log to Support AJAX Controller.
 *
 * @package WPTravelEngine\Core\Controllers\Ajax
 * @since 6.7.6
 */

namespace WPTravelEngine\Core\Controllers\Ajax;

use WPTravelEngine\Abstracts\AjaxController;
use WPTravelEngine\Email\Email;
use WPTravelEngine\Logger\Utilities\LogUtils;

/**
 * Handles send log to support AJAX request.
 *
 * @since 6.7.6
 */
class SendLogSupport extends AjaxController {

	const NONCE_KEY    = 'nonce';
	const NONCE_ACTION = 'wptravelengine_send_log_support';
	const ACTION       = 'wptravelengine_send_log_support';
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

		// Get filename
		$filename = $this->request->get_param( 'file' );
		if ( empty( $filename ) ) {
			wp_send_json_error( array( 'message' => __( 'No file specified.', 'wp-travel-engine' ) ) );
		}

		$filename = sanitize_file_name( $filename );

		// Security: Validate file extension - only .log files allowed
		if ( ! preg_match( '/\.log$/', $filename ) ) {
			wp_send_json_error( array( 'message' => __( 'Invalid file type. Only .log files are allowed.', 'wp-travel-engine' ) ) );
		}

		$log_dir   = LogUtils::get_log_directory();
		$file_path = $log_dir . '/' . $filename;

		// Security: Verify file exists first (realpath returns false for non-existent files)
		if ( ! file_exists( $file_path ) ) {
			wp_send_json_error( array( 'message' => __( 'File not found.', 'wp-travel-engine' ) ) );
		}

		// Security: Ensure file is within log directory (path traversal protection)
		$real_file_path = realpath( $file_path );
		$real_log_dir   = realpath( $log_dir );
		if ( false === $real_file_path || false === $real_log_dir || strpos( $real_file_path, $real_log_dir ) !== 0 ) {
			wp_send_json_error( array( 'message' => __( 'Invalid file.', 'wp-travel-engine' ) ) );
		}

		// Security: Check file size limit (5MB max) to prevent memory issues
		// phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_filesize
		$file_size    = filesize( $file_path );
		$max_filesize = 5 * 1024 * 1024; // 5MB
		if ( false === $file_size || $file_size > $max_filesize ) {
			wp_send_json_error(
				array(
					'message' => __( 'File is too large to send (max 5MB).', 'wp-travel-engine' ),
				)
			);
		}

		// Security: Strip newlines from site name to prevent email header injection
		$site_name = str_replace( array( "\r", "\n" ), '', esc_html( get_bloginfo( 'name' ) ) );

		// Prepare email (with escaped HTML to prevent email injection)
		$email_body = sprintf(
			'Hi WPTravelEngine,<br><br>My site is facing issue you can look into following log.<br>Site URL: %s<br>Site Name: %s<br>Log File: %s<br><br>Regards',
			esc_url( get_site_url() ),
			$site_name,
			esc_html( $filename )
		);

		// Strip newlines from subject to prevent email header injection
		$subject = sprintf( esc_html__( 'Log File Support Request - %s', 'wp-travel-engine' ), $site_name );

		// Security: Validate email address from filter to prevent injection
		$support_email = apply_filters( 'wptravelengine_log_support_email', 'support@wptravelengine.com' );
		$support_email = filter_var( $support_email, FILTER_VALIDATE_EMAIL );
		if ( ! $support_email ) {
			$support_email = 'support@wptravelengine.com'; // Fallback to safe default
		}

		if ( ! is_readable( $file_path ) ) {
			wp_send_json_error(
				array(
					'message' => __( 'Log file is not readable.', 'wp-travel-engine' ),
				)
			);
		}

		// Create unique temp file to avoid race conditions with concurrent requests
		$txt_filepath = $log_dir . '/' . uniqid( 'wte-log-', true ) . '.txt';

		// Copy log to unique temp file
		// phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_copy
		if ( ! copy( $file_path, $txt_filepath ) ) {
			wp_send_json_error(
				array(
					'message' => __( 'Failed to create temporary file for sending.', 'wp-travel-engine' ),
				)
			);
		}

		// Send email with attachment (with exception handling)
		try {
			$email  = new Email();
			$result = $email->set( 'to', $support_email )
							->set( 'my_subject', $subject )
							->set( 'content', $email_body )
							->set( 'attachments', array( $txt_filepath ) )
							->send();

			if ( $result ) {
				wp_send_json_success(
					array(
						'message' => __( 'Log file sent to support successfully.', 'wp-travel-engine' ),
					)
				);
			} else {
				wp_send_json_error(
					array(
						'message' => __( 'Failed to send email. Please try again.', 'wp-travel-engine' ),
					)
				);
			}
		} catch ( \Exception $e ) {
			// Clean up .txt file on exception
			wp_send_json_error(
				array(
					'message' => sprintf(
						// translators: %s: error message
						__( 'Email error: %s', 'wp-travel-engine' ),
						$e->getMessage()
					),
				)
			);
		} finally {
			if ( file_exists( $txt_filepath ) ) {
				unlink( $txt_filepath ); // phpcs:ignore
			}
		}
	}
}
