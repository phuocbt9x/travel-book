<?php
/**
 * Logs admin page.
 *
 * @package WPTravelEngine\Logger\Admin
 * @since 6.7.6
 */

namespace WPTravelEngine\Logger\Admin;

use WPTravelEngine\Interfaces\AdminPage;
use WPTravelEngine\Logger\Utilities\LogUtils;

/**
 * Logs page class.
 *
 * Admin page under Tools > WTE Logs.
 *
 * @since 6.7.6
 */
class LogsPage implements AdminPage {

	/**
	 * Page slug.
	 */
	const SLUG = 'wptravelengine-logs';

	/**
	 * Parent slug.
	 *
	 * @var string
	 */
	public string $parent_slug;

	/**
	 * Page title.
	 *
	 * @var string
	 */
	public string $page_title;

	/**
	 * Menu title.
	 *
	 * @var string
	 */
	public string $menu_title;

	/**
	 * Capability.
	 *
	 * @var string
	 */
	public string $capability;

	/**
	 * Position.
	 *
	 * @var int
	 */
	public int $position;

	/**
	 * Constructor.
	 *
	 * @since 6.7.6
	 */
	public function __construct() {
		$this->parent_slug = 'tools.php';
		$this->page_title  = __( 'WP Travel Engine Logs', 'wp-travel-engine' );
		$this->menu_title  = __( 'WTE Logs', 'wp-travel-engine' );
		$this->capability  = 'manage_options';
		$this->position    = 99;
	}

	/**
	 * View method - renders the admin page.
	 *
	 * @return void
	 * @since 6.7.6
	 */
	public function view(): void {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'You do not have sufficient permissions to access this page.', 'wp-travel-engine' ) );
		}

		// Handle settings save
		$settings_saved = false;
		if ( isset( $_POST['wte_save_logger_settings'] ) ) {
			// Use check_admin_referer() for more secure CSRF protection (dies automatically on failure)
			check_admin_referer( 'wptravelengine_logger_settings', 'wptravelengine_logger_settings_nonce' );
			$this->save_logger_settings();
			$settings_saved = true;
		}

		// Get current view (entries, files, or settings)
		$current_view = isset( $_GET['view'] ) ? sanitize_text_field( wp_unslash( $_GET['view'] ) ) : 'entries';

		// Create appropriate list table (only for entries and files views)
		$list_table = null;
		if ( $current_view === 'files' ) {
			$list_table = new LogsFilesTable();
			$list_table->prepare_items();
		} elseif ( $current_view === 'entries' ) {
			$list_table = new LogsListTable();
			$list_table->prepare_items();
		}

		// Get log directory size
		$log_size       = size_format( LogUtils::get_log_directory_size() );
		$retention_days = \WPTravelEngine\Logger\LoggerSettings::instance()->get( 'retention_days', 7 );

		// View URLs
		$entries_url  = add_query_arg(
			array(
				'page' => self::SLUG,
				'view' => 'entries',
			),
			admin_url( 'tools.php' )
		);
		$files_url    = add_query_arg(
			array(
				'page' => self::SLUG,
				'view' => 'files',
			),
			admin_url( 'tools.php' )
		);
		$settings_url = add_query_arg(
			array(
				'page' => self::SLUG,
				'view' => 'settings',
			),
			admin_url( 'tools.php' )
		);

		?>
		<div class="wrap">
			<h1 class="wp-heading-inline"><?php esc_html_e( 'WP Travel Engine Logs', 'wp-travel-engine' ); ?></h1>

			<hr class="wp-header-end">

			<?php if ( isset( $_GET['cleared'] ) && '1' === $_GET['cleared'] ) : ?>
				<div class="notice notice-success is-dismissible">
					<p><?php esc_html_e( 'All logs have been cleared.', 'wp-travel-engine' ); ?></p>
				</div>
			<?php endif; ?>

			<?php if ( $settings_saved ) : ?>
				<div class="notice notice-success is-dismissible">
					<p><?php esc_html_e( 'Logger settings saved successfully.', 'wp-travel-engine' ); ?></p>
				</div>
			<?php endif; ?>

			<?php if ( isset( $_GET['deleted'] ) ) : ?>
				<div class="notice notice-success is-dismissible">
					<p>
						<?php
						printf(
							/* translators: %d number of files deleted */
							esc_html( _n( '%d log file deleted.', '%d log files deleted.', absint( $_GET['deleted'] ), 'wp-travel-engine' ) ),
							absint( $_GET['deleted'] )
						);
						?>
					</p>
				</div>
			<?php endif; ?>

			<nav class="nav-tab-wrapper wp-clearfix" style="margin: 15px 0 0 0;">
				<a href="<?php echo esc_url( $entries_url ); ?>" class="nav-tab <?php echo $current_view === 'entries' ? 'nav-tab-active' : ''; ?>">
					<?php esc_html_e( 'Log Entries', 'wp-travel-engine' ); ?>
				</a>
				<a href="<?php echo esc_url( $files_url ); ?>" class="nav-tab <?php echo $current_view === 'files' ? 'nav-tab-active' : ''; ?>">
					<?php esc_html_e( 'Browse Files', 'wp-travel-engine' ); ?>
				</a>
				<a href="<?php echo esc_url( $settings_url ); ?>" class="nav-tab <?php echo $current_view === 'settings' ? 'nav-tab-active' : ''; ?>">
					<?php esc_html_e( 'Settings', 'wp-travel-engine' ); ?>
				</a>
			</nav>

			<?php if ( $current_view !== 'settings' ) : ?>
				<p class="description" style="margin-bottom: 15px;">
					<?php
					printf(
						/* translators: 1: Log directory size, 2: Retention days */
						esc_html__( 'Total log size: %1$s. Logs older than %2$d days are automatically cleaned up daily.', 'wp-travel-engine' ),
						esc_html( $log_size ),
						(int) $retention_days
					);
					?>
				</p>

				<form method="get">
					<input type="hidden" name="page" value="<?php echo esc_attr( self::SLUG ); ?>">
					<input type="hidden" name="view" value="<?php echo esc_attr( $current_view ); ?>">
					<?php
					if ( $current_view === 'entries' ) {
						$list_table->search_box( __( 'Search logs', 'wp-travel-engine' ), 'log' );
					}
					$list_table->display();
					?>
				</form>
			<?php else : ?>
				<?php $this->render_settings_view(); ?>
			<?php endif; ?>
		</div>

		<script type="text/javascript">
		document.addEventListener('DOMContentLoaded', function() {
			// Toggle expandable details
			document.addEventListener('click', function(e) {
				if (e.target.classList.contains('wte-log-details-toggle')) {
					e.preventDefault();
					var rowId = e.target.dataset.row;
					var detailsRow = document.getElementById('details-' + rowId);
					var isVisible = detailsRow.style.display !== 'none';
					detailsRow.style.display = isVisible ? 'none' : '';
					e.target.textContent = isVisible ? '<?php esc_html_e( 'View', 'wp-travel-engine' ); ?>' : '<?php esc_html_e( 'Hide', 'wp-travel-engine' ); ?>';
				}
			});

			// Download log
			document.addEventListener('click', function(e) {
				if (e.target.classList.contains('wte-download-log')) {
					e.preventDefault();
					var nonce = e.target.dataset.nonce;
					var url = ajaxurl + '?action=wptravelengine_download_log&nonce=' + nonce;

					// Get current filters
					var filters = new URLSearchParams(window.location.search);
					if (filters.get('level')) url += '&level=' + filters.get('level');
					if (filters.get('source')) url += '&source=' + filters.get('source');
					if (filters.get('date')) url += '&date=' + filters.get('date');
					if (filters.get('s')) url += '&search=' + filters.get('s');

					window.location.href = url;
				}
			});

			// Clear all logs
			document.addEventListener('click', function(e) {
				if (e.target.classList.contains('wte-clear-all-logs')) {
					e.preventDefault();

					if (!confirm('<?php echo esc_js( __( 'Are you sure you want to delete all log files? This action cannot be undone.', 'wp-travel-engine' ) ); ?>')) {
						return;
					}

					var button = e.target;
					var nonce = button.dataset.nonce;

					button.disabled = true;
					button.textContent = '<?php echo esc_js( __( 'Clearing...', 'wp-travel-engine' ) ); ?>';

					var formData = new FormData();
					formData.append('action', 'wptravelengine_clear_logs');
					formData.append('nonce', nonce);

					fetch(ajaxurl, {
						method: 'POST',
						body: formData
					})
					.then(function(response) { return response.json(); })
					.then(function(response) {
						if (response.success) {
							window.location.href = '<?php echo esc_url( admin_url( 'tools.php?page=' . self::SLUG . '&cleared=1' ) ); ?>';
						} else {
							alert(response.data.message || '<?php echo esc_js( __( 'An error occurred.', 'wp-travel-engine' ) ); ?>');
							button.disabled = false;
							button.textContent = '<?php echo esc_js( __( 'Clear All Logs', 'wp-travel-engine' ) ); ?>';
						}
					})
					.catch(function() {
						alert('<?php echo esc_js( __( 'Network error. Please try again.', 'wp-travel-engine' ) ); ?>');
						button.disabled = false;
						button.textContent = '<?php echo esc_js( __( 'Clear All Logs', 'wp-travel-engine' ) ); ?>';
					});
				}
			});

			// Download single log file
			document.addEventListener('click', function(e) {
				var target = e.target;

				// Check if clicked element or its parent has the class
				if (!target.classList.contains('wte-download-single-log')) {
					if (target.parentElement && target.parentElement.classList.contains('wte-download-single-log')) {
						target = target.parentElement;
					} else {
						return;
					}
				}

				e.preventDefault();
				var file = target.dataset.file;
				var nonce = target.dataset.nonce;

				window.location.href = ajaxurl + '?action=wptravelengine_download_log&file=' + encodeURIComponent(file) + '&nonce=' + nonce;
			});

			// Send log to support
			document.addEventListener('click', function(e) {
				var target = e.target;

				// Check if clicked element or its parent has the class
				if (!target.classList.contains('wte-send-log-support')) {
					// Check if parent button has the class (in case user clicked the icon)
					if (target.parentElement && target.parentElement.classList.contains('wte-send-log-support')) {
						target = target.parentElement;
					} else {
						return;
					}
				}

				e.preventDefault();

				if (!confirm('<?php echo esc_js( __( 'Do you want to send this log file to WP Travel Engine team?', 'wp-travel-engine' ) ); ?>')) {
					return;
				}

				var button = target;
				var file = button.dataset.file;
				var nonce = button.dataset.nonce;
				var originalHTML = button.innerHTML;

				button.innerHTML = '<span class="dashicons dashicons-update" style="vertical-align: middle; animation: rotation 1s infinite linear;"></span> <?php echo esc_js( __( 'Sending...', 'wp-travel-engine' ) ); ?>';
				button.disabled = true;

				var formData = new FormData();
				formData.append('action', 'wptravelengine_send_log_support');
				formData.append('file', file);
				formData.append('nonce', nonce);

				fetch(ajaxurl, {
					method: 'POST',
					body: formData
				})
				.then(function(response) { return response.json(); })
				.then(function(response) {
					if (response.success) {
						alert(response.data.message);
					} else {
						alert(response.data.message || '<?php echo esc_js( __( 'An error occurred.', 'wp-travel-engine' ) ); ?>');
					}
					button.innerHTML = originalHTML;
					button.disabled = false;
				})
				.catch(function() {
					alert('<?php echo esc_js( __( 'Network error. Please try again.', 'wp-travel-engine' ) ); ?>');
					button.innerHTML = originalHTML;
					button.disabled = false;
				});
			});

			// Delete single log file
			document.addEventListener('click', function(e) {
				var target = e.target;

				// Check if clicked element or its parent has the class
				if (!target.classList.contains('wte-delete-single-log')) {
					if (target.parentElement && target.parentElement.classList.contains('wte-delete-single-log')) {
						target = target.parentElement;
					} else {
						return;
					}
				}

				e.preventDefault();

				if (!confirm('<?php echo esc_js( __( 'Are you sure you want to delete this log file? This action cannot be undone.', 'wp-travel-engine' ) ); ?>')) {
					return;
				}

				var button = target;
				var file = button.dataset.file;
				var nonce = button.dataset.nonce;
				var originalHTML = button.innerHTML;

				button.disabled = true;
				button.innerHTML = '<span class="dashicons dashicons-update" style="vertical-align: middle; animation: rotation 1s infinite linear;"></span> <?php echo esc_js( __( 'Deleting...', 'wp-travel-engine' ) ); ?>';

				var formData = new FormData();
				formData.append('action', 'wptravelengine_clear_logs');
				formData.append('file', file);
				formData.append('nonce', nonce);

				fetch(ajaxurl, {
					method: 'POST',
					body: formData
				})
				.then(function(response) { return response.json(); })
				.then(function(response) {
					if (response.success) {
						window.location.reload();
					} else {
						alert(response.data.message || '<?php echo esc_js( __( 'An error occurred.', 'wp-travel-engine' ) ); ?>');
						button.disabled = false;
						button.innerHTML = originalHTML;
					}
				})
				.catch(function() {
					alert('<?php echo esc_js( __( 'Network error. Please try again.', 'wp-travel-engine' ) ); ?>');
					button.disabled = false;
					button.innerHTML = originalHTML;
				});
			});
		});
		</script>

		<style>
		.wte-log-details {
			background: #f9f9f9;
		}
		.wte-log-details-toggle {
			text-decoration: none;
			font-weight: bold;
			color: #2271b1;
		}
		.wte-log-details-toggle:hover {
			color: #135e96;
		}
		@keyframes rotation {
			from {
				transform: rotate(0deg);
			}
			to {
				transform: rotate(360deg);
			}
		}
		</style>
		<?php
	}

	/**
	 * Save logger settings from POST data.
	 *
	 * Validates all inputs against allowlists to prevent arbitrary data injection.
	 *
	 * @return void
	 * @since 6.7.6
	 */
	protected function save_logger_settings(): void {
		// Get logger settings instance
		$settings = \WPTravelEngine\Logger\LoggerSettings::instance();

		// Enable/Disable - allowlist validation
		if ( isset( $_POST['logger_enabled'] ) ) {
			$allowed_enabled = array( 'yes', 'no' );
			$enabled         = sanitize_text_field( wp_unslash( $_POST['logger_enabled'] ) );
			if ( in_array( $enabled, $allowed_enabled, true ) ) {
				$settings->set( 'enabled', $enabled );
			}
		}

		// Log Level - allowlist validation
		if ( isset( $_POST['logger_log_level'] ) ) {
			$allowed_levels = array( 'FATAL', 'WARNING' );
			$log_level      = strtoupper( sanitize_text_field( wp_unslash( $_POST['logger_log_level'] ) ) );
			if ( in_array( $log_level, $allowed_levels, true ) ) {
				$settings->set( 'log_level', $log_level );
			}
		}

		// Retention Days - numeric validation
		if ( isset( $_POST['logger_retention_days'] ) ) {
			$retention_days = absint( wp_unslash( $_POST['logger_retention_days'] ) );
			// Clamp to 1-365 days
			$settings->set( 'retention_days', max( 1, min( 365, $retention_days ) ) );
		}

		// Auto Cleanup - allowlist validation
		if ( isset( $_POST['logger_auto_cleanup'] ) ) {
			$allowed_cleanup = array( 'yes', 'no' );
			$auto_cleanup    = sanitize_text_field( wp_unslash( $_POST['logger_auto_cleanup'] ) );
			if ( in_array( $auto_cleanup, $allowed_cleanup, true ) ) {
				$settings->set( 'auto_cleanup', $auto_cleanup );
			}
		}

		// Max File Size - numeric validation
		if ( isset( $_POST['logger_max_file_size'] ) ) {
			$max_size = absint( wp_unslash( $_POST['logger_max_file_size'] ) );
			// Clamp to 1-100 MB
			$settings->set( 'max_file_size', max( 1, min( 100, $max_size ) ) );
		}

		// Cache Duration - numeric validation
		if ( isset( $_POST['logger_cache_duration'] ) ) {
			$cache_duration = absint( wp_unslash( $_POST['logger_cache_duration'] ) );
			// Clamp to 1-2880 minutes (2 days)
			$settings->set( 'cache_duration', max( 1, min( 2880, $cache_duration ) ) );
		}

		// Persist to database
		$settings->save();
	}

	/**
	 * Render settings view.
	 *
	 * @return void
	 * @since 6.7.6
	 */
	protected function render_settings_view(): void {
		// Get logger settings instance
		$settings = \WPTravelEngine\Logger\LoggerSettings::instance();

		// Get current settings with defaults
		$enabled        = $settings->get( 'enabled', 'yes' );
		$log_level      = $settings->get( 'log_level', 'FATAL' );
		$retention_days = $settings->get( 'retention_days', 7 );
		$auto_cleanup   = $settings->get( 'auto_cleanup', 'yes' );
		$max_file_size  = $settings->get( 'max_file_size', 10 );
		$cache_duration = $settings->get( 'cache_duration', 1440 ); // Already in minutes

		// Simplified log levels - just 2 options
		$log_levels = array(
			'FATAL'   => __( 'Fatal Errors Only', 'wp-travel-engine' ),
			'WARNING' => __( 'Warnings and Fatal Errors', 'wp-travel-engine' ),
		);

		?>
		<form method="post" action="">
			<?php wp_nonce_field( 'wptravelengine_logger_settings', 'wptravelengine_logger_settings_nonce' ); ?>

			<table class="form-table" role="presentation">
				<tbody>
					<!-- Enable/Disable Logging -->
					<tr>
						<th scope="row">
							<label for="logger_enabled"><?php esc_html_e( 'Enable Logging', 'wp-travel-engine' ); ?></label>
						</th>
						<td>
							<select name="logger_enabled" id="logger_enabled">
								<option value="yes" <?php selected( $enabled, 'yes' ); ?>><?php esc_html_e( 'Yes', 'wp-travel-engine' ); ?></option>
								<option value="no" <?php selected( $enabled, 'no' ); ?>><?php esc_html_e( 'No', 'wp-travel-engine' ); ?></option>
							</select>
							<p class="description">
								<?php esc_html_e( 'Enable automatic PHP error logging for WP Travel Engine plugins and addons.', 'wp-travel-engine' ); ?>
							</p>
						</td>
					</tr>

					<!-- Log Level -->
					<tr>
						<th scope="row">
							<label for="logger_log_level"><?php esc_html_e( 'Minimum Log Level', 'wp-travel-engine' ); ?></label>
						</th>
						<td>
							<select name="logger_log_level" id="logger_log_level">
								<?php foreach ( $log_levels as $level_value => $level_label ) : ?>
									<option value="<?php echo esc_attr( $level_value ); ?>" <?php selected( $log_level, $level_value ); ?>>
										<?php echo esc_html( $level_label ); ?>
									</option>
								<?php endforeach; ?>
							</select>
							<p class="description">
								<?php esc_html_e( 'FATAL: Only log fatal errors and parse errors. WARNING: Log both warnings and fatal errors.', 'wp-travel-engine' ); ?>
							</p>
						</td>
					</tr>

					<!-- Retention Days -->
					<tr>
						<th scope="row">
							<label for="logger_retention_days"><?php esc_html_e( 'Log Retention Days', 'wp-travel-engine' ); ?></label>
						</th>
						<td>
							<input type="number" name="logger_retention_days" id="logger_retention_days"
									value="<?php echo esc_attr( $retention_days ); ?>" min="1" max="365" class="small-text">
							<p class="description">
								<?php esc_html_e( 'Number of days to keep log files before automatic cleanup (1-365 days).', 'wp-travel-engine' ); ?>
							</p>
						</td>
					</tr>

					<!-- Auto Cleanup -->
					<tr>
						<th scope="row">
							<label for="logger_auto_cleanup"><?php esc_html_e( 'Automatic Cleanup', 'wp-travel-engine' ); ?></label>
						</th>
						<td>
							<select name="logger_auto_cleanup" id="logger_auto_cleanup">
								<option value="yes" <?php selected( $auto_cleanup, 'yes' ); ?>><?php esc_html_e( 'Enabled', 'wp-travel-engine' ); ?></option>
								<option value="no" <?php selected( $auto_cleanup, 'no' ); ?>><?php esc_html_e( 'Disabled', 'wp-travel-engine' ); ?></option>
							</select>
							<p class="description">
								<?php esc_html_e( 'Automatically delete old log files daily based on retention days setting.', 'wp-travel-engine' ); ?>
							</p>
						</td>
					</tr>

					<!-- Max File Size -->
					<tr>
						<th scope="row">
							<label for="logger_max_file_size"><?php esc_html_e( 'Max File Size (MB)', 'wp-travel-engine' ); ?></label>
						</th>
						<td>
							<input type="number" name="logger_max_file_size" id="logger_max_file_size"
									value="<?php echo esc_attr( $max_file_size ); ?>" min="1" max="100" class="small-text">
							<p class="description">
								<?php esc_html_e( 'Maximum size for individual log files in megabytes (1-100 MB).', 'wp-travel-engine' ); ?>
							</p>
						</td>
					</tr>

					<!-- Cache Duration -->
					<tr>
						<th scope="row">
							<label for="logger_cache_duration"><?php esc_html_e( 'Cache Duration (minutes)', 'wp-travel-engine' ); ?></label>
						</th>
						<td>
							<input type="number" name="logger_cache_duration" id="logger_cache_duration"
									value="<?php echo esc_attr( $cache_duration ); ?>" min="1" max="2880" class="small-text">
							<p class="description">
								<?php esc_html_e( 'How long to cache log file lists in minutes (1-2880 minutes).', 'wp-travel-engine' ); ?>
							</p>
						</td>
					</tr>
				</tbody>
			</table>

			<p class="submit">
				<input type="submit" name="wte_save_logger_settings" id="submit" class="button button-primary"
						value="<?php esc_attr_e( 'Save Settings', 'wp-travel-engine' ); ?>">
			</p>
		</form>
		<?php
	}
}
