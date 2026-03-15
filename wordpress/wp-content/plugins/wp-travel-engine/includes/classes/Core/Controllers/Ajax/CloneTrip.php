<?php
/**
 * Clone trip controller.
 *
 * @package WPTravelEngine/Core/Controllers
 * @since 6.0.0
 */

namespace WPTravelEngine\Core\Controllers\Ajax;

use WP_Error;
use WPTravelEngine\Abstracts\AjaxController;
use WPTravelEngine\Core\Models\Post\Trip;

/**
 * Handles clone trip ajax request.
 */
class CloneTrip extends AjaxController {

	const NONCE_KEY    = 'security';
	const NONCE_ACTION = 'wte_clone_post_nonce';
	const ACTION       = 'wte_fxn_clone_trip_data';
	const ALLOW_NOPRIV = false;

	/**
	 * Process request.
	 * Clone Trip.
	 */
	protected function process_request() {

		$post_id = $this->request->get_param( 'post_id' );

		if ( is_null( $post_id ) ) {
			wp_send_json_error(
				new WP_Error(
					'invalid_arguments',
					__( 'Missing Trip ID.', 'wp-travel-engine' )
				)
			);
		}

		if ( WP_TRAVEL_ENGINE_POST_TYPE !== get_post_type( $post_id ) ) {
			wp_send_json_error(
				new WP_Error(
					'INVALID_POST_TYPE',
					__( 'Cloning post must be of trip posttype.', 'wp-travel-engine' )
				)
			);
		}

		$trip_instance = new Trip( $post_id );

		$new_post_id = wptravelengine_duplicate_post( $trip_instance->post );

		$clone_trip_instance = new Trip( $new_post_id );

		$packages_ids = $trip_instance->get_meta( 'packages_ids' );

		$_new_package_ids = array();
		if ( is_array( $packages_ids ) ) {
			foreach ( $packages_ids as $package_id ) {
				$_package_id = wptravelengine_duplicate_post( $package_id );
				if ( ! is_null( $_package_id ) ) {
					$_new_package_ids[] = $_package_id;
					wp_update_post(
						array(
							'ID'          => $_package_id,
							'post_status' => 'publish',
							'meta_input'  => array(
								'trip_ID' => $new_post_id,
							),
						)
					);
				}
			}
		}
		$clone_trip_instance->set_meta( 'packages_ids', $_new_package_ids );
		$clone_trip_instance->set_meta( 'wte_fsd_booked_seats', array() );
		$clone_trip_instance->save();

		if ( ! is_null( $new_post_id ) ) {
			wp_send_json_success(
				array(
					'post_id'   => $new_post_id,
					'edit_link' => add_query_arg(
						array(
							'post'   => $new_post_id,
							'action' => 'edit',
						),
						admin_url( 'post.php' )
					),
				)
			);
			die;
		}
	}
}
