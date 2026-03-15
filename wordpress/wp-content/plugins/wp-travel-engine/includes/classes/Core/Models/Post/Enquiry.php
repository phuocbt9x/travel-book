<?php
/**
 * Enquiry Model.
 *
 * @package WPTravelEngine/Core/Models
 * @since 6.0.0
 */

namespace WPTravelEngine\Core\Models\Post;

use WP_Error;
use WPTravelEngine\Abstracts\PostModel;
use WPTravelEngine\Filters\Events;

/**
 * Class Enquiry.
 * This class represents an enquiry to the WP Travel Engine plugin.
 *
 * @since 6.0.0
 */
class Enquiry extends PostModel {

	/**
	 * Post-type name.
	 *
	 * @var string
	 */
	protected string $post_type = 'enquiry';

	/**
	 * Retrieves enquiry data.
	 *
	 * @return array Enquiry data
	 */
	public function get_enquiry_data(): array {
		if ( empty( $enquiry_data = $this->get_meta( 'wp_travel_engine_enquiry_formdata' ) ) ) {
			$enquiry_data = $this->get_meta( 'wp_travel_engine_setting' )['enquiry'] ?? array();
		}

		return $enquiry_data;
	}

	/**
	 * Retrieves package name.
	 *
	 * @return string Package name
	 */
	public function get_package_name(): string {
		return $this->get_enquiry_data()['pname'] ?? '';
	}

	/**
	 * Retrieves customer name.
	 *
	 * @return string Customer name
	 */
	public function get_customer_name(): string {
		return $this->get_enquiry_data()['enquiry_name'] ?? $this->get_enquiry_data()['name'] ?? '';
	}

	/**
	 * Retrieves customer email.
	 *
	 * @return string Customer email
	 */
	public function get_customer_email(): string {
		return $this->get_enquiry_data()['enquiry_email'] ?? $this->get_enquiry_data()['email'] ?? '';
	}

	/**
	 * Retrieves customer country.
	 *
	 * @return string Customer country
	 */
	public function get_customer_country(): string {
		return $this->get_enquiry_data()['enquiry_country'] ?? '';
	}

	/**
	 * Retrieves customer contact.
	 *
	 * @return string Customer contact
	 */
	public function get_customer_contact(): string {
		return $this->get_enquiry_data()['enquiry_contact'] ?? '';
	}

	/**
	 * Retrieves customer message.
	 *
	 * @return string Customer message
	 */
	public function get_customer_message(): string {
		return $this->get_enquiry_data()['enquiry_message'] ?? $this->get_enquiry_data()['message'] ?? '';
	}

	/**
	 * @param array $data
	 *
	 * @return static|WP_Error
	 * @since 6.0.0
	 */
	public static function insert( array $data ) {

		// Insert the post into the database.
		$post_id = wp_insert_post(
			array(
				'post_title'  => 'enquiry ',
				'post_status' => 'publish',
				'post_type'   => 'enquiry',
			),
			true
		);

		if ( is_wp_error( $post_id ) ) {
			return $post_id;
		}

		/**
		 * @action_hook wte_after_enquiry_created
		 *
		 * @since 2.2.0
		 */
		do_action( 'wte_after_enquiry_created', $post_id );

		$arr = array();

		$arr['enquiry'] = array(
			'name'    => $data['enquiry_name'],
			'email'   => $data['enquiry_email'],
			'message' => $data['enquiry_message'],
			'pname'   => $data['package_id'],
		);

		add_post_meta( $post_id, 'wp_travel_engine_setting', $arr );

		add_post_meta( $post_id, 'wp_travel_engine_enquiry_formdata', $data );

		$post_data = array(
			'ID'         => $post_id,
			'post_title' => $data['enquiry_name'],
		);

		// Update the post into the database.
		wp_update_post( $post_data );

		$enquiry = new static( $post_id );

		/**
		 * @action_hook wptravelengine.enquiry.created
		 *
		 * @param array $data Enquiry data.
		 * @param Enquiry $enquiry Enquiry object.
		 *
		 * @since 6.5.2
		 */
		Events::enquiry_created( $enquiry );

		return new static( $post_id );
	}

	/**
	 * Retrieves the data.
	 *
	 * @return array The retrieved data.
	 */
	public function get_data(): array {
		$data = array(
			'full_name' => $this->get_customer_name(),
			'email'     => $this->get_customer_email(),
			'country'   => $this->get_customer_country(),
			'contact'   => $this->get_customer_contact(),
			'message'   => $this->get_customer_message(),
			'trip'      => array(
				'id'    => '',
				'title' => '',
				'url'   => '',
			),
		);

		$trip_id = $this->get_enquiry_data()['pname'] ?? $this->get_enquiry_data()['package_id'] ?? 0;

		if ( is_numeric( $trip_id ) && ( $trip = get_post( $trip_id ) ) ) {
			$data['trip'] = array(
				'id'    => $trip->ID,
				'title' => get_the_title( $trip->ID ),
				'url'   => get_permalink( $trip->ID ),
			);
		}

		return $data;
	}
}
