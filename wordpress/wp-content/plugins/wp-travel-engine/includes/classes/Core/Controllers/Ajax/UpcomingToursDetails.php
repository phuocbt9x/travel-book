<?php
/**
 * Upcoming tours details controller.
 *
 * @since 6.4.1
 */
namespace WPTravelEngine\Core\Controllers\Ajax;

use WPTravelEngine\Abstracts\AjaxController;
use WPTravelEngine\Pages\Admin\UpcomingTours;

class UpcomingToursDetails extends AjaxController {

	const NONCE_KEY    = 'nonce';
	const NONCE_ACTION = 'wte_upcoming_tours_details';
	const ACTION       = 'wte_upcoming_tours_details';
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
		$get  = $this->request->get_query_params();
		$html = UpcomingTours::get_details_html( $get['id'] );
		wp_send_json_success(
			array(
				'html' => $html,
			)
		);
	}
}
