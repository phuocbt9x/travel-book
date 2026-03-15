<?php
/**
 * Upcoming tours filter controller.
 *
 * @since 6.4.1
 */
namespace WPTravelEngine\Core\Controllers\Ajax;

use WPTravelEngine\Abstracts\AjaxController;
use WPTravelEngine\Pages\Admin\UpcomingTours;

class UpcomingToursFilter extends AjaxController {

	const NONCE_KEY    = 'nonce';
	const NONCE_ACTION = 'wte_filter_upcoming_tours';
	const ACTION       = 'wte_filter_upcoming_tours';
	const ALLOW_NOPRIV = false;


	/**
	 * Constructor.
	 */
	public function __construct() {
		parent::__construct();
	}

	/**
	 * Process request
	 */
	protected function process_request() {
		$post   = $this->request->get_body_params();
		$status = isset( $post['status'] ) ? sanitize_text_field( $post['status'] ) : 'all';
		// Get valid statuses (allow plugins to add custom statuses)
		$valid_statuses = apply_filters( 'wptravelengine_upcoming_tours_valid_statuses', array( 'all', 'booked' ) );
		// Ensure status is one of the valid values
		if ( ! in_array( $status, $valid_statuses, true ) ) {
			$status = 'all';
		}
		$html = UpcomingTours::get_upcoming_tours_html(
			array(
				'date'   => isset( $post['date'] ) ? $post['date'] : 'all',
				'count'  => isset( $post['count'] ) ? absint( $post['count'] ) : 10,
				'status' => $status,
			)
		);
		wp_send_json_success( array( 'html' => $html ) );
	}
}
