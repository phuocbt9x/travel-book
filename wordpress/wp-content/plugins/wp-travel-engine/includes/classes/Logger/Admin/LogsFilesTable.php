<?php
/**
 * Logs files browser table.
 *
 * @package WPTravelEngine\Logger\Admin
 * @since 6.7.6
 */

namespace WPTravelEngine\Logger\Admin;

use WP_List_Table;
use WPTravelEngine\Logger\Utilities\LogUtils;

if ( ! class_exists( 'WP_List_Table' ) ) {
	require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
}

/**
 * Logs files table class.
 *
 * Simple file browser for log files (similar to WooCommerce logs).
 *
 * @since 6.7.6
 */
class LogsFilesTable extends WP_List_Table {

	/**
	 * Log utils instance.
	 *
	 * @var LogUtils
	 */
	protected LogUtils $utils;

	/**
	 * Log directory.
	 *
	 * @var string
	 */
	protected string $log_dir;

	/**
	 * Constructor.
	 *
	 * @since 6.7.6
	 */
	public function __construct() {
		parent::__construct(
			array(
				'singular' => 'file',
				'plural'   => 'files',
				'ajax'     => false,
			)
		);

		$this->utils   = new LogUtils();
		$this->log_dir = LogUtils::get_log_directory();
	}

	/**
	 * Get columns.
	 *
	 * @return array Column definitions.
	 * @since 6.7.6
	 */
	public function get_columns(): array {
		return array(
			'cb'            => '<input type="checkbox" />',
			'filename'      => __( 'File', 'wp-travel-engine' ),
			'date_modified' => __( 'Date modified', 'wp-travel-engine' ),
			'file_size'     => __( 'File size', 'wp-travel-engine' ),
			'actions'       => __( 'Actions', 'wp-travel-engine' ),
		);
	}

	/**
	 * Get sortable columns.
	 *
	 * @return array Sortable column definitions.
	 * @since 6.7.6
	 */
	public function get_sortable_columns(): array {
		return array(
			'filename'      => array( 'filename', false ),
			'date_modified' => array( 'date_modified', true ),
			'file_size'     => array( 'file_size', false ),
		);
	}

	/**
	 * Get bulk actions.
	 *
	 * @return array Bulk actions.
	 * @since 6.7.6
	 */
	protected function get_bulk_actions(): array {
		return array(
			'delete' => __( 'Delete', 'wp-travel-engine' ),
		);
	}

	/**
	 * Process bulk actions.
	 *
	 * @return void
	 * @since 6.7.6
	 */
	public function process_bulk_action(): void {
		// Detect when bulk action is being triggered
		$action = $this->current_action();

		if ( ! $action ) {
			return;
		}

		// Security check - capability
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'You do not have sufficient permissions.', 'wp-travel-engine' ) );
		}

		// Security check - nonce verification
		$nonce_action = 'bulk-' . $this->_args['plural'];
		if ( ! isset( $_GET['_wpnonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_GET['_wpnonce'] ) ), $nonce_action ) ) {
			wp_die( esc_html__( 'Security check failed.', 'wp-travel-engine' ) );
		}

		// Get selected files
		$files = isset( $_GET['file'] ) ? (array) wp_unslash( $_GET['file'] ) : array();

		if ( empty( $files ) ) {
			return;
		}

		$log_dir = LogUtils::get_log_directory();
		$deleted = 0;

		switch ( $action ) {
			case 'delete':
				foreach ( $files as $filename ) {
					$filename  = sanitize_file_name( $filename );
					$file_path = $log_dir . '/' . $filename;

					// Security: Verify file exists and is within log directory (path traversal protection)
					if ( ! file_exists( $file_path ) ) {
						continue;
					}

					$real_file_path = realpath( $file_path );
					$real_log_dir   = realpath( $log_dir );
					if ( false === $real_file_path || false === $real_log_dir || strpos( $real_file_path, $real_log_dir ) !== 0 ) {
						continue;
					}

					// phpcs:ignore WordPress.WP.AlternativeFunctions.unlink_unlink
					if ( unlink( $file_path ) ) {
						++$deleted;
						// Clear event for this specific file
						LogUtils::clear_log_event_for_file( $file_path );
					}
				}

				// Clear cache
				LogUtils::clear_cache();

				// Redirect with success message
				wp_safe_redirect(
					add_query_arg(
						array(
							'page'    => LogsPage::SLUG,
							'view'    => 'files',
							'deleted' => $deleted,
						),
						admin_url( 'tools.php' )
					)
				);
				exit;
		}
	}

	/**
	 * Prepare items for display.
	 *
	 * @return void
	 * @since 6.7.6
	 */
	public function prepare_items(): void {
		// Process bulk actions first
		$this->process_bulk_action();
		$columns  = $this->get_columns();
		$hidden   = array();
		$sortable = $this->get_sortable_columns();

		$this->_column_headers = array( $columns, $hidden, $sortable );

		// Get filter from request
		$source_filter = isset( $_GET['source'] ) ? sanitize_key( wp_unslash( $_GET['source'] ) ) : 'all';

		// Get all log files
		$log_files = $this->utils->get_log_files( $this->log_dir );

		// Build file data array
		$files = array();
		foreach ( $log_files as $file_path ) {
			$basename = basename( $file_path );

			// Extract source from filename: source-YYYY-MM-DD.log
			if ( preg_match( '/^([^-]+)-/', $basename, $matches ) ) {
				$source = $matches[1];
			} else {
				$source = 'unknown';
			}

			// Apply source filter
			if ( $source_filter !== 'all' && $source !== $source_filter ) {
				continue;
			}

			// phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_filemtime
			$modified_time = filemtime( $file_path );
			// phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_filesize
			$file_size = filesize( $file_path );

			$files[] = array(
				'file_path'     => $file_path,
				'filename'      => $basename,
				'source'        => $source,
				'date_modified' => $modified_time,
				'file_size'     => $file_size,
			);
		}

		// Sort files
		$orderby = isset( $_GET['orderby'] ) ? sanitize_text_field( wp_unslash( $_GET['orderby'] ) ) : 'date_modified';
		$order   = isset( $_GET['order'] ) ? sanitize_text_field( wp_unslash( $_GET['order'] ) ) : 'desc';

		usort(
			$files,
			function ( $a, $b ) use ( $orderby, $order ) {
				$result = 0;

				switch ( $orderby ) {
					case 'filename':
						$result = strcmp( $a['filename'], $b['filename'] );
						break;

					case 'date_modified':
						$result = $a['date_modified'] - $b['date_modified'];
						break;

					case 'file_size':
						$result = $a['file_size'] - $b['file_size'];
						break;
				}

				return ( $order === 'asc' ) ? $result : -$result;
			}
		);

		// Pagination
		$per_page     = 20;
		$current_page = $this->get_pagenum();
		$total_items  = count( $files );

		$this->set_pagination_args(
			array(
				'total_items' => $total_items,
				'per_page'    => $per_page,
				'total_pages' => ceil( $total_items / $per_page ),
			)
		);

		$this->items = array_slice( $files, ( ( $current_page - 1 ) * $per_page ), $per_page );
	}

	/**
	 * Default column output.
	 *
	 * @param array  $item        Item data.
	 * @param string $column_name Column name.
	 * @return string Column output.
	 * @since 6.7.6
	 */
	protected function column_default( $item, $column_name ): string {
		switch ( $column_name ) {
			case 'filename':
				return $this->column_filename( $item );

			case 'date_modified':
				return esc_html( wp_date( 'Y-m-d H:i:s', $item['date_modified'] ) );

			case 'file_size':
				return esc_html( size_format( $item['file_size'], 2 ) );

			default:
				return '';
		}
	}

	/**
	 * Checkbox column.
	 *
	 * @param array $item Item data.
	 * @return string Checkbox HTML.
	 * @since 6.7.6
	 */
	protected function column_cb( $item ): string {
		return sprintf(
			'<input type="checkbox" name="file[]" value="%s" />',
			esc_attr( basename( $item['file_path'] ) )
		);
	}

	/**
	 * Filename column.
	 *
	 * @param array $item Item data.
	 * @return string Column HTML.
	 * @since 6.7.6
	 */
	protected function column_filename( $item ): string {
		$filename = basename( $item['file_path'] );

		// Extract date from filename: wptravelengine-2026-01-29.log
		$date_filter = '';
		if ( preg_match( '/-(\d{4}-\d{2}-\d{2})\.log/', $filename, $matches ) ) {
			$date_filter = $matches[1];
		}

		$args = array(
			'page'   => LogsPage::SLUG,
			'view'   => 'entries',
			'source' => $item['source'],
		);

		// Add custom date filter
		if ( ! empty( $date_filter ) ) {
			$args['date']      = 'custom';
			$args['date_from'] = $date_filter;
			$args['date_to']   = $date_filter;
		}

		$file_url = add_query_arg( $args, admin_url( 'tools.php' ) );

		return sprintf(
			'<a href="%s"><strong><code>%s</code></strong></a>',
			esc_url( $file_url ),
			esc_html( $filename )
		);
	}

	/**
	 * Actions column with buttons.
	 *
	 * @param array $item Item data.
	 * @return string Column HTML with action buttons.
	 * @since 6.7.6
	 */
	protected function column_actions( $item ): string {
		$filename = basename( $item['file_path'] );

		$download_button = sprintf(
			'<button type="button" class="button button-small wte-download-single-log" data-file="%s" data-nonce="%s" title="%s">
				<span class="dashicons dashicons-download" style="vertical-align: middle;"></span> %s
			</button>',
			esc_attr( $filename ),
			esc_attr( wp_create_nonce( 'wptravelengine_download_log' ) ),
			esc_attr__( 'Download this log file', 'wp-travel-engine' ),
			esc_html__( 'Download', 'wp-travel-engine' )
		);

		$email_button = sprintf(
			'<button type="button" class="button button-small wte-send-log-support" data-file="%s" data-nonce="%s" title="%s" style="margin-left: 5px;">
				<span class="dashicons dashicons-email" style="vertical-align: middle;"></span> %s
			</button>',
			esc_attr( $filename ),
			esc_attr( wp_create_nonce( 'wptravelengine_send_log_support' ) ),
			esc_attr__( 'Send this log file to support', 'wp-travel-engine' ),
			esc_html__( 'Email', 'wp-travel-engine' )
		);

		$delete_button = sprintf(
			'<button type="button" class="button button-small wte-delete-single-log" data-file="%s" data-nonce="%s" title="%s" style="margin-left: 5px; color: #b32d2e;">
				<span class="dashicons dashicons-trash" style="vertical-align: middle;"></span> %s
			</button>',
			esc_attr( $filename ),
			esc_attr( wp_create_nonce( 'wptravelengine_clear_logs' ) ),
			esc_attr__( 'Delete this log file', 'wp-travel-engine' ),
			esc_html__( 'Delete', 'wp-travel-engine' )
		);

		return $download_button . $email_button . $delete_button;
	}

	/**
	 * Display when no items found.
	 *
	 * @return void
	 * @since 6.7.6
	 */
	public function no_items(): void {
		esc_html_e( 'No log files found.', 'wp-travel-engine' );
	}

	/**
	 * Extra table navigation.
	 *
	 * @param string $which Top or bottom.
	 * @return void
	 * @since 6.7.6
	 */
	protected function extra_tablenav( $which ): void {
		if ( 'top' !== $which ) {
			return;
		}

		$current_source = isset( $_GET['source'] ) ? sanitize_key( wp_unslash( $_GET['source'] ) ) : 'all';
		$sources        = $this->utils->get_sources( $this->log_dir );
		?>
		<div class="alignleft actions">
			<label class="screen-reader-text" for="filter-by-source"><?php esc_html_e( 'Filter by source', 'wp-travel-engine' ); ?></label>
			<select name="source" id="filter-by-source">
				<option value="all"><?php esc_html_e( 'All sources', 'wp-travel-engine' ); ?></option>
				<?php foreach ( $sources as $source ) : ?>
					<option value="<?php echo esc_attr( $source ); ?>" <?php selected( $current_source, $source ); ?>>
						<?php echo esc_html( $source ); ?>
					</option>
				<?php endforeach; ?>
			</select>

			<?php submit_button( __( 'Filter', 'wp-travel-engine' ), '', 'filter_action', false ); ?>
		</div>
		<?php
	}
}
