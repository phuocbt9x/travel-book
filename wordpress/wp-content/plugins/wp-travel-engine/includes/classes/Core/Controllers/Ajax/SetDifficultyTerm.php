<?php
/**
 * Set Difficulty Term Controller.
 *
 * @package WPTravelEngine/Core/Controllers
 * @since 6.0.0
 */

namespace WPTravelEngine\Core\Controllers\Ajax;

use WPTravelEngine\Abstracts\AjaxController;

/**
 * Handles to set difficulty term ajax request.
 */
class SetDifficultyTerm extends AjaxController {

	const NONCE_KEY    = '_nonce';
	const NONCE_ACTION = 'wp_xhr';
	const ACTION       = 'wte_set_difficulty_term_level';
	const ALLOW_NOPRIV = false;

	/**
	 * Process Request.
	 *
	 * @since 5.5.7
	 */
	public function process_request() {
		$post_data = $this->request->get_params();
		// save in options.
		$difficulty_level                        = get_option( 'difficulty_level_by_terms', array() );
		$difficulty_level[ $post_data['level'] ] = array(
			'level'   => $post_data['level'],
			'term_id' => $post_data['term_id'],
			'label'   => get_term( $post_data['term_id'] )->name,
		);
		foreach ( $difficulty_level as $key => $val ) {
			if ( ( $post_data['level'] != $key && $post_data['term_id'] === $val['term_id'] ) || ( '' === $post_data['level'] && $post_data['term_id'] === $val['term_id'] ) ) {
				unset( $difficulty_level[ $key ] );
			}
		}
		if ( array_key_exists( 'Select Level', $difficulty_level ) ) {
			unset( $difficulty_level['Select Level'] );
		}
		update_option( 'difficulty_level_by_terms', $difficulty_level );
		wp_send_json_success( $difficulty_level );

		wp_die();
	}
}
