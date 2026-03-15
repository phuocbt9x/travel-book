<?php
/**
 * Enquiry Mail controller.
 *
 * @package WPTravelEngine/Core/Controllers
 * @since 6.0.0
 */

namespace WPTravelEngine\Core\Controllers\Ajax;

use Exception;
use WP_Error;
use WP_Travel_Engine_Enquiry_Form_Shortcodes;
use WPTravelEngine\Abstracts\AjaxController;
use WPTravelEngine\Core\Models\Post\Enquiry;
use WPTravelEngine\Core\Models\Settings\PluginSettings;
use WPTravelEngine\Email\Email;
use WPTravelEngine\Email\UserEmail;
use WTE_Default_Form_Fields;

/**
 * Handles enquiry related ajax mail request.
 */
class EnquiryMail extends AjaxController {

	const NONCE_KEY    = 'nonce';
	const NONCE_ACTION = 'wte_enquiry_send_mail';
	const ACTION       = 'wte_enquiry_send_mail';

	/**
	 * @param array $data
	 *
	 * @return array
	 * @throws Exception
	 * @since 6.5.2
	 */
	protected function sanitize_form_data( array $data ): array {
		$form_fields    = WTE_Default_Form_Fields::enquiry();
		$sanitized_data = array();

		// Do not store validation-only fields (e.g. recaptcha) in enquiry data.
		$validation_only_types = apply_filters(
			'wptravelengine_enquiry_validation_only_field_types',
			array( 'recaptcha', 'captcha', 'grecaptcha_v2', 'grecaptcha_v3' )
		);
		$validation_only_types = array_map( 'strtolower', (array) $validation_only_types );

		foreach ( $form_fields as $form_field ) {
			if ( ! empty( $form_field['type'] ) && in_array( strtolower( (string) $form_field['type'] ), $validation_only_types, true ) ) {
				continue;
			}

			if ( isset( $form_field['validations']['required'] ) && $form_field['validations']['required'] == 'true' && empty( $data[ $form_field['name'] ] ) ) {
				throw new Exception( sprintf( __( 'Missing required fields: %s', 'wp-travel-engine' ), $form_field['field_label'] ) );
			}
			if ( isset( $form_field['name'] ) && isset( $data[ $form_field['name'] ] ) ) {
				switch ( $form_field['type'] ) {
					case 'number':
						$value = abs( (float) $data[ $form_field['name'] ] );
						break;
					case 'email':
						$value = sanitize_email( $data[ $form_field['name'] ] );
						break;
					case 'tel':
					case 'checkbox':
					case 'text':
					default:
						$value = sanitize_text_field( $data[ $form_field['name'] ] );
				}

				$sanitized_data[ $form_field['name'] ] = $value;
			}
		}

		if ( isset( $data['package_id'] ) ) {
			$sanitized_data['package_id'] = absint( $data['package_id'] );
		}

		return $sanitized_data;
	}

	/**
	 * Process Request.
	 * Sends mail to subscriber and admin.
	 *
	 * @since 3.0.0
	 */
	protected function process_request() {

		// phpcs:disable
		if ( 'wte_enquiry_send_mail' !== $this->request->get_param( 'action' ) ) {
			wp_send_json_error( array( 'message' => __( 'Invalid Action', 'wp-travel-engine' ) ) );
		}

		try {
			$formdata = $this->sanitize_form_data( $this->request->get_params() );
		} catch ( Exception $e ) {
			wp_send_json_error( array( 'message' => $e->getMessage() ) );
		}

		$email           = ! empty( $formdata[ 'enquiry_email' ] ) ? ( $formdata[ 'enquiry_email' ] ) : false;
		$enquiry_message = ! empty( $formdata[ 'enquiry_message' ] ) ? ( $formdata[ 'enquiry_message' ] ) : false;
		$name            = ! empty( $formdata[ 'enquiry_name' ] ) ? ( $formdata[ 'enquiry_name' ] ) : false;

		$cust_enquiry_subject = ! empty( $formdata[ 'enquiry_subject' ] ) ? $formdata[ 'enquiry_subject' ] : false;

		$validation_check = apply_filters( 'wp_travel_engine_enquiry_validation_check', array( 'status' => true ) );

		if ( ! empty( $validation_check ) && false === $validation_check[ 'status' ] ) {
			$result[ 'type' ]    = 'error';
			$result[ 'message' ] = $validation_check[ 'message' ];
			if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
				wp_send_json_error(
					array(
						'message' => $validation_check[ 'message' ],
					)
				);
				die;
			}
		}

		$wp_travel_engine_settings = get_option( 'wp_travel_engine_settings', true );
		$postid                    = get_post( $formdata[ 'package_id' ] );
		$slug                      = $postid->post_title;

		$url     = '<a href=' . esc_url( get_permalink( $postid ) ) . '>' . esc_attr( $slug ) . '</a>';
		$subject = $wp_travel_engine_settings[ 'query_subject' ] ?? __( 'Enquiry received', 'wp-travel-engine' );

		if ( $cust_enquiry_subject ) {
			$subject = $cust_enquiry_subject;
		}

		$enquirer_tags             = array( '{enquirer_name}', '{enquirer_email}' );
		$enquirer_replace_tags     = array( $name, $email );
		$subject                   = str_replace( $enquirer_tags, $enquirer_replace_tags, $subject );
		$admin_email               = get_option( 'admin_email' );
		$wp_travel_engine_settings = get_option( 'wp_travel_engine_settings' );

		if ( ! empty ( $wp_travel_engine_settings[ 'email' ][ 'enquiry_emailaddress' ] ) ) {
			$enquiry_emailaddress = $wp_travel_engine_settings[ 'email' ][ 'enquiry_emailaddress' ];

			$explode_email = explode( ',', $enquiry_emailaddress );
			$to            = array_map( 'sanitize_email', $explode_email );

		} else {
			$emails = array_filter( array_map( 'sanitize_email', explode( ',', $wp_travel_engine_settings[ 'email' ][ 'emails' ] ) ) );
			$to     = ! empty( $emails ) ? $emails : array( sanitize_email( $admin_email ) );
		}

		$ipaddress = '';
		if ( isset( $_SERVER['REMOTE_ADDR'] ) ) {
			$ipaddress = sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) );
		
			// Validate IP format
			if ( ! filter_var( $ipaddress, FILTER_VALIDATE_IP ) ) {
				$ipaddress = '';
			}
		}

		$remove_keys = array(
			'package_id',
			'redirect-url',
			'enquiry_confirmation[]',
			'enquiry_confirmation',
			'wp_travel_engine_enquiry_submit_name',
			'_wp_http_referer',
			'action',
		);

		// Add Package Name.
		$formdata[ 'package_name' ] = $url;

		$valid_form_fields = array_keys( self::get_enquiry_form_fields( $formdata[ 'package_id' ] ) );

		// Add customer IP Address.
		$formdata[ 'IP Address:' ] = $ipaddress;

		// Mail class.
		require_once plugin_dir_path( WP_TRAVEL_ENGINE_FILE_PATH ) . 'includes/class-wp-travel-engine-emails.php';

		// Prepare enquiry emails.
		$admin_email_template_content = wte_get_template_html( 'emails/enquiry.php', compact( 'formdata' ) );

		if ( ! function_exists( 'wp_handle_upload' ) ) {
			require_once ABSPATH . 'wp-admin/includes/file.php';
		}
		$uploadedfile = $_FILES;
		$attachments  = array();

		// Define allowed file types once.
		$allowed_mimes = array(
			'jpg|jpeg|jpe' => 'image/jpeg',
			'png'          => 'image/png',
			'gif'          => 'image/gif',
			'pdf'          => 'application/pdf',
		);

		foreach ( $uploadedfile as $key => $file ) {
			// Skip if no file was uploaded or there was an upload error.
			if ( empty( $file['name'] ) || ! empty( $file['error'] ) ) {
				continue;
			}

			// Validate file type early.
			$file_type = wp_check_filetype( $file['name'], $allowed_mimes );
			if ( ! $file_type['type'] ) {
				continue;
			}

			// Handle the file upload.
			$upload_file = wp_handle_upload(
				$file,
				array(
					'test_form' => false,
					'mimes'     => $allowed_mimes,
				)
			);

			// Add to attachments if successful.
			if ( $upload_file && ! isset( $upload_file['error'] ) ) {
				$attachments[ $key ] = $upload_file['file'];
			}
		}

		$admin_sent = false;
		foreach ( $to as $val ) {
			$email_instance = new Email();
			$admin_sent     = $email_instance->add_headers( array( "reply_to" => "Reply-To: {$name}<{$email}>" ) )
			                                 ->set( 'to', $val )
			                                 ->set( 'my_subject', esc_html( $subject ) )
			                                 ->set( 'attachments', $attachments )
			                                 ->set( 'content', $admin_email_template_content )
			                                 ->send();
		}

		if ( isset( $wp_travel_engine_settings[ 'email' ][ 'cust_notif' ] ) && $wp_travel_engine_settings[ 'email' ][ 'cust_notif' ] == '1' ) {

			$user = (object) array(
				'user_login' => $name,
				'user_email' => $email,
			);
			$mail = new UserEmail( $user );
			$mail->set( 'to', $email )
			     ->set( 'my_subject', $wp_travel_engine_settings[ 'customer_email_notify_tabs' ][ 'enquiry' ][ 'subject' ] ?? __( 'Enquiry Sent.', 'wp-travel-engine' ) )
			     ->set( 'content', $wp_travel_engine_settings[ 'customer_email_notify_tabs' ][ 'enquiry' ][ 'content' ] ?? '' )
			     ->send();
		}

		$post_id = Enquiry::insert( $formdata );

		do_action( 'wp_travel_engine_after_enquiry_post_insert', $post_id );

		$result = array();
		if ( ! is_wp_error( $post_id ) ) {

			if ( $admin_sent ) {

				$result[ 'type' ]          = 'success';
				$result[ 'message' ]       = __( 'Your query has been successfully sent. Thank You.', 'wp-travel-engine' );
				$wp_travel_engine_settings = get_option( 'wp_travel_engine_settings', array() );
				$enquiry_thank_you_url     = home_url();
				if ( ! empty( $wp_travel_engine_settings[ 'pages' ][ 'enquiry' ] ) ) {
					$enquiry_thank_you_url = $wp_travel_engine_settings[ 'pages' ][ 'enquiry' ];
					$enquiry_thank_you_url = get_permalink( $enquiry_thank_you_url );
				}

				$result[ 'redirect' ] = $enquiry_thank_you_url;

				/**
				 * Hook - after_enquiry_sent
				 */
				do_action( 'wp_travel_engine_after_enquiry_sent', $post_id );
			}
		} else {
			$result[ 'type' ]    = 'failed';
			$result[ 'message' ] = __( 'Sorry, your query could not be sent at the moment. May be try again later. Thank You.', 'wp-travel-engine' );
		}

		if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
			if ( 'success' === $result[ 'type' ] ) {
				wp_send_json_success( $result );
			} else {
				wp_send_json_error( $result );
			}
		}
	}

	/**
	 * Gets Enquiry Form Fields.
	 */
	public function get_enquiry_form_fields( $post_id, $args = array() ) {
		$enquiry_form_fields = \WTE_Default_Form_Fields::enquiry();

		if ( ( isset( $args[ 'use_current' ] ) && 'yes' === $args[ 'use_current' ] && WP_TRAVEL_ENGINE_POST_TYPE === get_post_type( $post_id ) ) || ( ! isset( $args[ 'shortcode' ] ) || ! $args[ 'shortcode' ] ) ) {
			$package_fields = self::get_package_detail_fields( $post_id );
		} else {
			$trip_select_options = wp_list_pluck(
				get_posts(
					array(
						'post_type'      => WP_TRAVEL_ENGINE_POST_TYPE,
						'post_status'    => 'publish',
						'posts_per_page' => - 1,
					)
				),
				'post_title',
				'ID'
			);

			$trip_select_options = array( '' => __( 'Select Trip*', 'wp-travel-engine' ) ) + $trip_select_options;

			$attributes = array();
			if ( ! empty( $args[ 'trip_id' ] ) ) {
				$attributes[ 'disabled' ] = true;
			}
			$package_fields = array(
				'package_id' => array(
					'field_label'   => __( 'Trip Name', 'wp-travel-engine' ),
					'wrapper_class' => 'row-repeater',
					'type'          => 'select',
					'name'          => 'package_id',
					'id'            => 'package_id',
					'options'       => $trip_select_options,
					'priority'      => 7,
					'default'       => ! empty( $args[ 'trip_id' ] ) ? $args[ 'trip_id' ] : '',
					'validations'   => array(
						'required' => true,
					),
					'attributes'    => $attributes,
				),
			);
		}

		return apply_filters( 'wp_travel_engine_enquiry_fields_display', array_merge( $package_fields, $enquiry_form_fields ), $post_id );
	}

	/**
	 * Gets Package Details Fields for Enquiry Form.
	 *
	 * @since 5.3.1
	 */
	public function get_package_detail_fields( $post_id ) {
		$wp_travel_engine_settings = get_option( 'wp_travel_engine_settings', array() );
		$enquiry_thank_you_url     = home_url();
		if ( ! empty( $wp_travel_engine_settings[ 'pages' ][ 'enquiry' ] ) ) {
			$enquiry_thank_you_url = $wp_travel_engine_settings[ 'pages' ][ 'enquiry' ];
			$enquiry_thank_you_url = get_permalink( $enquiry_thank_you_url );
		}

		$package_detail_fields = array(
			'package_name'  => array(
				'label'    => __( 'Trip Name', 'wp-travel-engine' ),
				'type'     => 'hidden',
				'name'     => 'package_name',
				'id'       => 'package_name',
				'default'  => get_the_title( $post_id ),
				'priority' => 7,
			),
			'package_id'    => array(
				'type'          => 'hidden',
				'name'          => 'package_id',
				'wrapper_class' => 'row-repeater package-name-holder',
				'id'            => 'package_id',
				'default'       => esc_attr( $post_id ),
				'priority'      => 8,
			),
			'package_label' => array(
				'type'          => 'text_info',
				'wrapper_class' => 'row-repeater package-name-holder',
				'field_label'   => __( 'Trip name:', 'wp-travel-engine' ),
				'name'          => 'package_label',
				'id'            => 'package_label',
				'validations'   => array(
					'required' => true,
				),
				'remove_wrap'   => true,
				'default'       => get_the_title( $post_id ),
				'priority'      => 9,
			),
			'redirect_url'  => array(
				'type'          => 'hidden',
				'name'          => 'redirect-url',
				'wrapper_class' => 'row-repeater package-name-holder',
				'id'            => 'redirect-url',
				'default'       => esc_url( $enquiry_thank_you_url ),
				'priority'      => 8,
			),
		);

		return apply_filters( 'wte_enquiry_package_detail_fields', $package_detail_fields, $post_id );
	}
}
