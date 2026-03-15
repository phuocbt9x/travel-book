<?php
/**
 * Logs list table.
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
 * Logs list table class.
 *
 * Displays logs using WordPress WP_List_Table.
 *
 * @since 6.7.6
 */
class LogsListTable extends WP_List_Table {

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
				'singular' => 'log',
				'plural'   => 'logs',
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
			'timestamp' => __( 'Timestamp', 'wp-travel-engine' ),
			'level'     => __( 'Level', 'wp-travel-engine' ),
			'message'   => __( 'Message', 'wp-travel-engine' ),
			'source'    => __( 'Source', 'wp-travel-engine' ),
			'details'   => __( 'Details', 'wp-travel-engine' ),
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
			'timestamp' => array( 'timestamp', true ),
			'level'     => array( 'level', false ),
			'source'    => array( 'source', false ),
		);
	}

	/**
	 * Prepare items for display.
	 *
	 * @return void
	 * @since 6.7.6
	 */
	public function prepare_items(): void {
		$columns  = $this->get_columns();
		$hidden   = array();
		$sortable = $this->get_sortable_columns();

		$this->_column_headers = array( $columns, $hidden, $sortable );

		// Get filters from request
		$filters = array(
			'level'  => isset( $_GET['level'] ) ? sanitize_text_field( wp_unslash( $_GET['level'] ) ) : 'all',
			'source' => isset( $_GET['source'] ) ? sanitize_key( wp_unslash( $_GET['source'] ) ) : 'all',
			'date'   => isset( $_GET['date'] ) ? sanitize_text_field( wp_unslash( $_GET['date'] ) ) : 'all',
			'search' => isset( $_GET['s'] ) ? sanitize_text_field( wp_unslash( $_GET['s'] ) ) : '',
		);

		if ( $filters['date'] === 'custom' ) {
			$filters['date_from'] = isset( $_GET['date_from'] ) ? sanitize_text_field( wp_unslash( $_GET['date_from'] ) ) : '';
			$filters['date_to']   = isset( $_GET['date_to'] ) ? sanitize_text_field( wp_unslash( $_GET['date_to'] ) ) : '';
		}

		// Parse log files with memory safety limits
		$all_entries    = array();
		$log_files      = $this->utils->get_log_files( $this->log_dir );
		$max_entries    = 10000; // Hard limit to prevent memory exhaustion
		$max_file_size  = 50 * 1024 * 1024; // 50MB per file max
		$entries_parsed = 0;
		$files_skipped  = 0;
		$limit_reached  = false;

		foreach ( $log_files as $file ) {
			// Skip files that are too large to parse safely
			// phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_filesize
			$file_size = filesize( $file );
			if ( false === $file_size || $file_size > $max_file_size ) {
				++$files_skipped;
				continue;
			}

			// Stop parsing if we've hit the entry limit
			if ( $entries_parsed >= $max_entries ) {
				$limit_reached = true;
				break;
			}

			$entries        = $this->utils->parse_file( $file, $filters );
			$all_entries    = array_merge( $all_entries, $entries );
			$entries_parsed = count( $all_entries );

			// If this file pushed us over the limit, truncate
			if ( $entries_parsed > $max_entries ) {
				$all_entries   = array_slice( $all_entries, 0, $max_entries );
				$limit_reached = true;
				break;
			}
		}

		// Show admin notice if limits were hit
		if ( $limit_reached || $files_skipped > 0 ) {
			$notice = array();
			if ( $limit_reached ) {
				$notice[] = sprintf(
					// translators: %d: maximum number of entries
					__( 'Showing maximum %d entries for performance.', 'wp-travel-engine' ),
					$max_entries
				);
			}
			if ( $files_skipped > 0 ) {
				$notice[] = sprintf(
					// translators: %d: number of files skipped
					__( '%d large log files skipped (>50MB).', 'wp-travel-engine' ),
					$files_skipped
				);
			}
			add_action(
				'admin_notices',
				function () use ( $notice ) {
					echo '<div class="notice notice-warning"><p>' . esc_html( implode( ' ', $notice ) ) . '</p></div>';
				}
			);
		}

		// Sort entries
		$orderby = isset( $_GET['orderby'] ) ? sanitize_text_field( wp_unslash( $_GET['orderby'] ) ) : 'timestamp';
		$order   = isset( $_GET['order'] ) ? sanitize_text_field( wp_unslash( $_GET['order'] ) ) : 'desc';

		usort(
			$all_entries,
			function ( $a, $b ) use ( $orderby, $order ) {
				$result = 0;

				switch ( $orderby ) {
					case 'timestamp':
						$result = strtotime( $a['timestamp'] ) - strtotime( $b['timestamp'] );
						break;

					case 'level':
						$result = strcmp( $a['level'], $b['level'] );
						break;

					case 'source':
						$source_a = $a['context']['source'] ?? 'wptravelengine';
						$source_b = $b['context']['source'] ?? 'wptravelengine';
						$result   = strcmp( $source_a, $source_b );
						break;
				}

				return ( $order === 'asc' ) ? $result : -$result;
			}
		);

		// Pagination
		$per_page     = 20;
		$current_page = $this->get_pagenum();
		$total_items  = count( $all_entries );

		$this->set_pagination_args(
			array(
				'total_items' => $total_items,
				'per_page'    => $per_page,
				'total_pages' => ceil( $total_items / $per_page ),
			)
		);

		$this->items = array_slice( $all_entries, ( ( $current_page - 1 ) * $per_page ), $per_page );
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
			case 'timestamp':
				return esc_html( LogUtils::format_display_timestamp( $item['timestamp'] ) );

			case 'level':
				return LogUtils::format_level( $item['level'] );

			case 'message':
				return esc_html( LogUtils::format_message( $item['message'], 100 ) );

			case 'source':
				$source = $item['context']['source'] ?? 'wptravelengine';
				return '<code>' . esc_html( $source ) . '</code>';

			case 'details':
				$row_id = md5( wp_json_encode( $item ) );
				return sprintf(
					'<a href="#" class="wte-log-details-toggle" data-row="%s">%s</a>',
					esc_attr( $row_id ),
					esc_html__( 'View', 'wp-travel-engine' )
				);

			default:
				return '';
		}
	}

	/**
	 * Display when no items found.
	 *
	 * @return void
	 * @since 6.7.6
	 */
	public function no_items(): void {
		esc_html_e( 'No logs found.', 'wp-travel-engine' );
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

		$current_level  = isset( $_GET['level'] ) ? sanitize_text_field( wp_unslash( $_GET['level'] ) ) : 'all';
		$current_source = isset( $_GET['source'] ) ? sanitize_key( wp_unslash( $_GET['source'] ) ) : 'all';
		$current_date   = isset( $_GET['date'] ) ? sanitize_text_field( wp_unslash( $_GET['date'] ) ) : 'all';

		$sources = $this->utils->get_sources( $this->log_dir );
		?>
		<div class="alignleft actions">
			<label class="screen-reader-text" for="filter-by-level"><?php esc_html_e( 'Filter by level', 'wp-travel-engine' ); ?></label>
			<select name="level" id="filter-by-level">
				<option value="all"><?php esc_html_e( 'All Levels', 'wp-travel-engine' ); ?></option>
				<option value="FATAL" <?php selected( $current_level, 'FATAL' ); ?>><?php esc_html_e( 'Fatal', 'wp-travel-engine' ); ?></option>
				<option value="WARNING" <?php selected( $current_level, 'WARNING' ); ?>><?php esc_html_e( 'Warning', 'wp-travel-engine' ); ?></option>
			</select>

			<label class="screen-reader-text" for="filter-by-source"><?php esc_html_e( 'Filter by source', 'wp-travel-engine' ); ?></label>
			<select name="source" id="filter-by-source">
				<option value="all"><?php esc_html_e( 'All Sources', 'wp-travel-engine' ); ?></option>
				<?php foreach ( $sources as $source ) : ?>
					<option value="<?php echo esc_attr( $source ); ?>" <?php selected( $current_source, $source ); ?>>
						<?php echo esc_html( $source ); ?>
					</option>
				<?php endforeach; ?>
			</select>

			<label class="screen-reader-text" for="filter-by-date"><?php esc_html_e( 'Filter by date', 'wp-travel-engine' ); ?></label>
			<select name="date" id="filter-by-date">
				<option value="all"><?php esc_html_e( 'All Time', 'wp-travel-engine' ); ?></option>
				<option value="today" <?php selected( $current_date, 'today' ); ?>><?php esc_html_e( 'Today', 'wp-travel-engine' ); ?></option>
				<option value="last_7_days" <?php selected( $current_date, 'last_7_days' ); ?>><?php esc_html_e( 'Last 7 Days', 'wp-travel-engine' ); ?></option>
				<option value="last_30_days" <?php selected( $current_date, 'last_30_days' ); ?>><?php esc_html_e( 'Last 30 Days', 'wp-travel-engine' ); ?></option>
			</select>

			<?php submit_button( __( 'Filter', 'wp-travel-engine' ), '', 'filter_action', false ); ?>

			<button type="button" class="button wte-clear-all-logs" data-nonce="<?php echo esc_attr( wp_create_nonce( 'wptravelengine_clear_logs' ) ); ?>" style="color: #b32d2e;">
				<?php esc_html_e( 'Clear All Logs', 'wp-travel-engine' ); ?>
			</button>
		</div>
		<?php
	}

	/**
	 * Single row output with expandable details.
	 *
	 * @param array $item Item data.
	 * @return void
	 * @since 6.7.6
	 */
	public function single_row( $item ): void {
		$row_id = md5( wp_json_encode( $item ) );
		echo '<tr id="row-' . esc_attr( $row_id ) . '">';
		$this->single_row_columns( $item );
		echo '</tr>';

		// Details row (hidden by default)
		echo '<tr class="wte-log-details" id="details-' . esc_attr( $row_id ) . '" style="display:none;">';
		echo '<td colspan="' . esc_attr( $this->get_column_count() ) . '">';
		$this->render_details( $item );
		echo '</td>';
		echo '</tr>';
	}

	/**
	 * Render expandable details.
	 *
	 * @param array $item Item data.
	 * @return void
	 * @since 6.7.6
	 */
	protected function render_details( array $item ): void {
		?>
		<div style="padding: 15px; background: #f9f9f9; border-left: 4px solid #2271b1;">
			<h3 style="margin-top: 0;"><?php esc_html_e( 'Log Details', 'wp-travel-engine' ); ?></h3>

			<p><strong><?php esc_html_e( 'Full Message:', 'wp-travel-engine' ); ?></strong><br>
			<?php echo esc_html( $item['message'] ); ?></p>

			<?php if ( ! empty( $item['context']['file'] ) ) : ?>
				<p><strong><?php esc_html_e( 'File:', 'wp-travel-engine' ); ?></strong>
				<code><?php echo esc_html( $item['context']['file'] ); ?></code>
				<?php if ( ! empty( $item['context']['line'] ) ) : ?>
					<?php esc_html_e( 'Line:', 'wp-travel-engine' ); ?> <code><?php echo esc_html( $item['context']['line'] ); ?></code>
				<?php endif; ?>
				</p>
			<?php endif; ?>

			<?php if ( ! empty( $item['context'] ) ) : ?>
				<p><strong><?php esc_html_e( 'Context:', 'wp-travel-engine' ); ?></strong></p>
				<pre style="background: #fff; padding: 10px; overflow-x: auto;"><?php echo esc_html( LogUtils::format_context( $item['context'] ) ); ?></pre>
			<?php endif; ?>

			<?php if ( ! empty( $item['context']['stack_trace'] ) || ! empty( $item['context']['trace'] ) || ! empty( $item['context']['backtrace'] ) ) : ?>
				<p><strong><?php esc_html_e( 'Stack Trace:', 'wp-travel-engine' ); ?></strong></p>
				<pre style="background: #fff; padding: 10px; overflow-x: auto;">
				<?php
				// Prefer stack_trace (from exception) over backtrace (from debug_backtrace)
				// stack_trace is a formatted string, backtrace is an array
				if ( ! empty( $item['context']['stack_trace'] ) ) {
					// Stack trace is already formatted as string - just display it
					echo esc_html( $item['context']['stack_trace'] );
				} else {
					// Backtrace is array - needs formatting
					$trace = $item['context']['trace'] ?? $item['context']['backtrace'];
					echo esc_html( LogUtils::format_backtrace( $trace ) );
				}
				?>
				</pre>
			<?php endif; ?>
		</div>
		<?php
	}
}
