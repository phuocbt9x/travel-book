<?php
/**
 * Upload Profile Picture Controller.
 *
 * @package WPTravelEngine/Core/Controllers
 * @since 6.0.0
 */

namespace WPTravelEngine\Core\Controllers\Ajax;

use WPTravelEngine\Abstracts\AjaxController;

/**
 * Handles user profile picture upload ajax request.
 */
class UploadProfilePicture extends AjaxController {

	const NONCE_KEY    = 'nonce';
	const NONCE_ACTION = 'wte-user-profile-image-nonce';
	const ACTION       = 'wte_user_profile_image_upload';

	/**
	 * Process Request.
	 * Upload profile picture from form.
	 *
	 * @return void
	 */
	public function process_request() {
		$files = $this->request->get_param( '__files' );
		if ( ! empty( $files ) ) :

			$allowed_filetypes = array( 'image/jpeg', 'image/png', 'image/gif' );

			$uploaddir    = wp_upload_dir();
			$wte_temp_dir = trailingslashit( $uploaddir['basedir'] ) . 'wp-travel-engine/tmp';
			$wte_temp_url = str_replace(
				array(
					'http://',
					'https://',
				),
				'//',
				trailingslashit( $uploaddir['baseurl'] ) . 'wp-travel-engine/tmp'
			);

			$source            = $files['file']['tmp_name'];
			$salt              = md5( $files['file']['name'] . time() );
			$file_name         = $salt . '-' . $files['file']['name'];
			$img_file_location = trailingslashit( $wte_temp_dir ) . $file_name;

			$upload_url        = trailingslashit( $wte_temp_url ) . $file_name;
			$uploaded_filetype = wp_check_filetype( basename( $img_file_location ), null );

			if ( $files['file']['size'] > wp_max_upload_size() ) {
				wp_send_json_error( array( 'message' => __( 'File size too large.', 'wp-travel-engine' ) ) );
			}

			if ( ! in_array( $uploaded_filetype['type'], $allowed_filetypes, true ) ) {
				wp_send_json_error( array( 'message' => __( 'Unsupported file type uploaded.', 'wp-travel-engine' ) ) );
			}

			if ( wp_mkdir_p( $wte_temp_dir ) ) :
				if ( move_uploaded_file( $source, $img_file_location ) ) :

					$file_array = array(
						'file' => $img_file_location,
						'url'  => $upload_url,
						'type' => $uploaded_filetype,
					);
					echo wp_json_encode( $file_array );
					wp_die();

				endif;
			endif;
		endif;

		wp_send_json_error( __( 'Invalid request. Nonce verification failed.', 'wp-travel-engine' ) );
	}
}
