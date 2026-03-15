<?php
/**
 * Post Type Customer.
 *
 * @package WPTravelEngine/Core/PostTypes
 * @since 6.0.0
 */

namespace WPTravelEngine\Core\PostTypes;

use WP_Error;
use WPTravelEngine\Abstracts\PostType;
use WPTravelEngine\Core\Models\Post\Customer as CustomerModel;
use WPTravelEngine\Helpers\Functions;

/**
 * Class Customer
 * This class represents a customer to the WP Travel Engine plugin.
 *
 * @since 6.0.0
 */
class Customer extends PostType {

	/**
	 * Post type name.
	 *
	 * @var string
	 */
	protected string $post_type = 'customer';

	/**
	 * @since 6.4.0
	 */
	public function __construct() {
		add_action( "add_meta_boxes_{$this->post_type}", array( $this, 'meta_box_customer' ) );
		add_action( 'wp_insert_post', array( $this, 'save' ), 10, 3 );
	}

	/**
	 * @return void
	 * @since 6.4.0
	 */
	public function meta_box_customer() {
		add_meta_box(
			'customer_id',
			__( 'Customer Details', 'wp-travel-engine' ),
			array( $this, 'meta_box_customer_callback' ),
			array( 'customer' ),
			'normal',
			'high'
		);
	}

	/**
	 * Save the customer details.
	 *
	 * @param int      $post_id
	 * @param \WP_Post $post
	 * @param bool     $update
	 *
	 * @return void|WP_Error
	 * @since 6.4.0
	 */
	public function save( $post_id, $post, $update ) {

		if ( $this->post_type !== $post->post_type ) {
			return;
		}

		$params = Functions::create_request( 'POST' )->get_params();

		if ( $update ) {
			$nonce         = sanitize_text_field(
				$params['wptravelengine_customer_save_nonce']
				?? ''
			);
			$valid_actions = array(
				'wptravelengine_customer_save_nonce_action',
			);
		} else {
			$nonce         = sanitize_text_field(
				$params['wp_travel_engine_new_booking_process_nonce']
				?? $params['wptravelengine_new_booking_nonce'] ?? ''
			);
			$valid_actions = array(
				'wp_travel_engine_new_booking_process_nonce_action',
				'wptravelengine_new_booking',
			);
		}

		if ( empty( array_intersect( array_map( fn( $action ) => wp_verify_nonce( $nonce, $action ), $valid_actions ), array( true ) ) ) ) {
			return new WP_Error( 'invalid_nonce', __( 'Invalid nonce', 'wp-travel-engine' ) );
		}

		$my_modal = new CustomerModel( $post_id );

		$my_modal->set_customer_details( $params['wp_travel_engine_booking_setting']['place_order']['booking'] ?? $params['billing'] ?? array() );

		$my_modal->save();
	}

	/**
	 * @return void
	 * @since 6.4.0
	 */
	public function meta_box_customer_callback() {
		global $post;
		wp_enqueue_script( 'wptravelengine-customer-edit' );

		$customer = new CustomerModel( $post );

		wptravelengine_get_admin_template(
			'customer/index.php',
			array(
				'customer_id'   => $customer->get_user_id( $customer->get_customer_email() ),
				'customer_name' => $customer->get_customer_info()['fname'] . ' ' . $customer->get_customer_info()['lname'],
				'avatar'        => $customer->get_customer_avatar(),
				'infos'         => $this->get_infos_details( $customer->get_customer_info() ),
				'addresses'     => $this->get_address_details( $customer->get_customer_addresses() ),
				'tabs'          => $this->get_sidebar_tabs(),
				'orders_data'   => $customer->get_customer_bookings(),
				'notes'         => $customer->get_customer_notes(),
			)
		);
	}

	/**
	 * Retrieve the labels for the Customer post type.
	 *
	 * Returns an array containing the labels used for the Customer post type, including
	 * names for various elements such as the post type itself, singular and plural names,
	 * menu labels, and more.
	 *
	 * @return array An array containing the labels for the Customer post type.
	 */
	public function get_labels(): array {
		return array(
			'name'               => _x( 'Customers', 'post type general name', 'wp-travel-engine' ),
			'singular_name'      => _x( 'Customer', 'post type singular name', 'wp-travel-engine' ),
			'menu_name'          => _x( 'Customers', 'admin menu', 'wp-travel-engine' ),
			'name_admin_bar'     => _x( 'Customer', 'add new on admin bar', 'wp-travel-engine' ),
			'add_new'            => _x( 'Add New', 'Customer', 'wp-travel-engine' ),
			'add_new_item'       => esc_html__( 'Add New Customer', 'wp-travel-engine' ),
			'new_item'           => esc_html__( 'New Customer', 'wp-travel-engine' ),
			'edit_item'          => esc_html__( 'Edit Customer', 'wp-travel-engine' ),
			'view_item'          => esc_html__( 'View Customer', 'wp-travel-engine' ),
			'all_items'          => esc_html__( 'Customers', 'wp-travel-engine' ),
			'search_items'       => esc_html__( 'Search Customers', 'wp-travel-engine' ),
			'parent_item_colon'  => esc_html__( 'Parent Customers:', 'wp-travel-engine' ),
			'not_found'          => esc_html__( 'No Customers found.', 'wp-travel-engine' ),
			'not_found_in_trash' => esc_html__( 'No Customers found in Trash.', 'wp-travel-engine' ),
		);
	}

	/**
	 * Retrieve the icon for the Customer post type in the admin menu.
	 *
	 * Returns the icon URL or slug for the Customer post type to be displayed in the admin menu.
	 *
	 * @return string The icon URL or slug for the Customer post type.
	 */
	public function get_icon(): string {
		return 'dashicons-location-alt';
	}

	/**
	 * Retrieve the arguments for the Customer post type.
	 *
	 * Returns an array containing the arguments used to register the Customer post type.
	 *
	 * @return array An array containing the arguments for the Customer post type.
	 */
	public function get_args(): array {
		return array(
			'labels'             => $this->get_labels(),
			'description'        => esc_html__( 'Description.', 'wp-travel-engine' ),
			'public'             => false,
			'publicly_queryable' => false,
			'show_ui'            => true,
			'show_in_menu'       => 'edit.php?post_type=booking',
			'query_var'          => true,
			'rewrite'            => array( 'slug' => 'customer' ),
			'capability_type'    => 'post',
			'map_meta_cap'       => true,
			'has_archive'        => true,
			'hierarchical'       => false,
			'menu_position'      => 50,
			'menu_icon'          => $this->get_icon(),
			'supports'           => array( '' ),
			'capabilities'       => array(
				'create_posts' => 'do_not_allow',
			),
		);
	}

	/**
	 * Get the sidebar tabs for the Customer post type.
	 *
	 * @return array The sidebar tabs for the Customer post type.
	 * @since 6.4.0
	 */
	private function get_sidebar_tabs() {
		return apply_filters(
			'wptravelengine_customer_sidebar_tabs',
			array(
				array(
					'class'  => 'is-active',
					'target' => 'profile',
					'label'  => __( 'Profile', 'wp-travel-engine' ),
					'icon'   => '<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M2.5 16.6667C4.44649 14.6021 7.08918 13.3333 10 13.3333C12.9108 13.3333 15.5535 14.6021 17.5 16.6667M13.75 6.25C13.75 8.32107 12.0711 10 10 10C7.92893 10 6.25 8.32107 6.25 6.25C6.25 4.17893 7.92893 2.5 10 2.5C12.0711 2.5 13.75 4.17893 13.75 6.25Z" stroke="currentColor" stroke-width="1.39" stroke-linecap="round" stroke-linejoin="round" /></svg>',
				),
				array(
					'class'  => '',
					'target' => 'orders',
					'label'  => __( 'Orders', 'wp-travel-engine' ),
					'icon'   => '<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M1.66675 1.66666H2.75522C2.96023 1.66666 3.06274 1.66666 3.14523 1.70436C3.21793 1.73758 3.27954 1.79101 3.32271 1.85828C3.37169 1.93461 3.38619 2.03609 3.41518 2.23904L3.80961 4.99999M3.80961 4.99999L4.68618 11.4428C4.79742 12.2604 4.85304 12.6692 5.0485 12.9769C5.22073 13.2481 5.46765 13.4637 5.75954 13.5978C6.0908 13.75 6.50337 13.75 7.3285 13.75H14.4601C15.2455 13.75 15.6383 13.75 15.9592 13.6087C16.2422 13.4841 16.485 13.2832 16.6603 13.0285C16.8592 12.7397 16.9327 12.3539 17.0796 11.5823L18.1827 5.7914C18.2344 5.51983 18.2603 5.38404 18.2228 5.27791C18.1899 5.1848 18.125 5.10639 18.0397 5.05667C17.9425 4.99999 17.8042 4.99999 17.5278 4.99999H3.80961ZM8.33341 17.5C8.33341 17.9602 7.96032 18.3333 7.50008 18.3333C7.03984 18.3333 6.66675 17.9602 6.66675 17.5C6.66675 17.0398 7.03984 16.6667 7.50008 16.6667C7.96032 16.6667 8.33341 17.0398 8.33341 17.5ZM15.0001 17.5C15.0001 17.9602 14.627 18.3333 14.1667 18.3333C13.7065 18.3333 13.3334 17.9602 13.3334 17.5C13.3334 17.0398 13.7065 16.6667 14.1667 16.6667C14.627 16.6667 15.0001 17.0398 15.0001 17.5Z" stroke="currentColor" stroke-width="1.39" stroke-linecap="round" stroke-linejoin="round" /></svg>',
				),
				array(
					'class'  => '',
					'target' => 'notes',
					'label'  => __( 'Notes', 'wp-travel-engine' ),
					'icon'   => '<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M11.6666 1.89127V5.33338C11.6666 5.80009 11.6666 6.03345 11.7574 6.21171C11.8373 6.36851 11.9648 6.49599 12.1216 6.57589C12.2999 6.66672 12.5332 6.66672 12.9999 6.66672H16.442M11.6666 14.1667H6.66659M13.3333 10.8333H6.66659M16.6666 8.32351V14.3333C16.6666 15.7335 16.6666 16.4335 16.3941 16.9683C16.1544 17.4387 15.772 17.8212 15.3016 18.0608C14.7668 18.3333 14.0667 18.3333 12.6666 18.3333H7.33325C5.93312 18.3333 5.23306 18.3333 4.69828 18.0608C4.22787 17.8212 3.84542 17.4387 3.60574 16.9683C3.33325 16.4335 3.33325 15.7335 3.33325 14.3333V5.66666C3.33325 4.26653 3.33325 3.56646 3.60574 3.03168C3.84542 2.56128 4.22787 2.17882 4.69828 1.93914C5.23306 1.66666 5.93312 1.66666 7.33325 1.66666H10.0097C10.6212 1.66666 10.9269 1.66666 11.2147 1.73573C11.4698 1.79697 11.7136 1.89798 11.9373 2.03506C12.1896 2.18966 12.4058 2.40585 12.8382 2.83823L15.495 5.49508C15.9274 5.92746 16.1436 6.14365 16.2982 6.39594C16.4353 6.61962 16.5363 6.86349 16.5975 7.11858C16.6666 7.4063 16.6666 7.71203 16.6666 8.32351Z" stroke="currentColor" stroke-width="1.39167" stroke-linecap="round" stroke-linejoin="round" /></svg>',
				),
			)
		);
	}

	/**
	 * Get the infos details for the customer.
	 *
	 * @param array $infos The customer's infos details.
	 * @return array The infos details for the customer.
	 * @since 6.4.0
	 */
	private function get_infos_details( $infos ) {
		return apply_filters(
			'wptravelengine_customer_infos_details',
			array(
				array(
					'class' => 'wpte-customer-phone',
					'label' => __( 'Phone:', 'wp-travel-engine' ),
					'icon'  => '<svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M5.58685 5.90217C6.05085 6.86859 6.68338 7.77435 7.48443 8.5754C8.28548 9.37645 9.19124 10.009 10.1577 10.473C10.2408 10.5129 10.2823 10.5328 10.3349 10.5482C10.5218 10.6027 10.7513 10.5635 10.9096 10.4502C10.9542 10.4183 10.9923 10.3802 11.0685 10.304C11.3016 10.0709 11.4181 9.95437 11.5353 9.87818C11.9772 9.59085 12.5469 9.59085 12.9889 9.87818C13.106 9.95437 13.2226 10.0709 13.4556 10.304L13.5856 10.4339C13.9399 10.7882 14.117 10.9653 14.2132 11.1556C14.4046 11.534 14.4046 11.9808 14.2132 12.3592C14.117 12.5494 13.9399 12.7266 13.5856 13.0809L13.4805 13.186C13.1274 13.539 12.9508 13.7156 12.7108 13.8504C12.4445 14 12.0308 14.1076 11.7253 14.1067C11.45 14.1059 11.2619 14.0525 10.8856 13.9457C8.86333 13.3717 6.95509 12.2887 5.36311 10.6967C3.77112 9.10473 2.68814 7.19649 2.11416 5.17423C2.00735 4.79793 1.95395 4.60978 1.95313 4.33448C1.95222 4.029 2.0598 3.61534 2.20941 3.34901C2.34424 3.10898 2.52078 2.93244 2.87386 2.57936L2.97895 2.47427C3.33325 2.11998 3.5104 1.94283 3.70065 1.8466C4.07903 1.65522 4.52587 1.65522 4.90424 1.8466C5.0945 1.94283 5.27164 2.11998 5.62594 2.47427L5.75585 2.60418C5.98892 2.83726 6.10546 2.95379 6.18165 3.07098C6.46898 3.5129 6.46898 4.08262 6.18165 4.52455C6.10546 4.64174 5.98892 4.75827 5.75585 4.99134C5.67964 5.06755 5.64154 5.10565 5.60965 5.15019C5.49631 5.30848 5.45717 5.53799 5.51165 5.72489C5.52698 5.77748 5.54694 5.81905 5.58685 5.90217Z" stroke="#0F1D23" stroke-width="1.33333" stroke-linecap="round" stroke-linejoin="round"/></svg>',
					'value' => $infos['phone'],
				),
				array(
					'class' => 'wpte-customer-email',
					'label' => __( 'Email:', 'wp-travel-engine' ),
					'icon'  => '<svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M1.3335 4.66666L6.77678 8.47695C7.21756 8.7855 7.43795 8.93977 7.67767 8.99953C7.88943 9.05231 8.1109 9.05231 8.32265 8.99953C8.56238 8.93977 8.78277 8.7855 9.22355 8.47695L14.6668 4.66666M4.5335 13.3333H11.4668C12.5869 13.3333 13.147 13.3333 13.5748 13.1153C13.9511 12.9236 14.2571 12.6176 14.4488 12.2413C14.6668 11.8135 14.6668 11.2534 14.6668 10.1333V5.86666C14.6668 4.74655 14.6668 4.1865 14.4488 3.75868C14.2571 3.38235 13.9511 3.07639 13.5748 2.88464C13.147 2.66666 12.5869 2.66666 11.4668 2.66666H4.5335C3.41339 2.66666 2.85334 2.66666 2.42552 2.88464C2.04919 3.07639 1.74323 3.38235 1.55148 3.75868C1.3335 4.1865 1.3335 4.74655 1.3335 5.86666V10.1333C1.3335 11.2534 1.3335 11.8135 1.55148 12.2413C1.74323 12.6176 2.04919 12.9236 2.42552 13.1153C2.85334 13.3333 3.41339 13.3333 4.5335 13.3333Z" stroke="#0F1D23" stroke-width="1.33333" stroke-linecap="round" stroke-linejoin="round"/></svg>',
					'value' => $infos['email'],
				),
			)
		);
	}

	/**
	 * Get the address details for the customer.
	 *
	 * @param array $addresses The customer's address details.
	 * @return array The address details for the customer.
	 * @since 6.4.0
	 */
	private function get_address_details( $addresses ) {
		return apply_filters(
			'wptravelengine_customer_address_details',
			array(
				array(
					'class' => 'wpte-customer-address',
					'label' => __( 'Address:', 'wp-travel-engine' ),
					'icon'  => '<svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M7.99984 8.33334C9.10441 8.33334 9.99984 7.43791 9.99984 6.33334C9.99984 5.22877 9.10441 4.33334 7.99984 4.33334C6.89527 4.33334 5.99984 5.22877 5.99984 6.33334C5.99984 7.43791 6.89527 8.33334 7.99984 8.33334Z" stroke="#0F1D23" stroke-width="1.33333" stroke-linecap="round" stroke-linejoin="round"/><path d="M7.99984 14.6667C9.33317 12 13.3332 10.2789 13.3332 6.66668C13.3332 3.72116 10.9454 1.33334 7.99984 1.33334C5.05432 1.33334 2.6665 3.72116 2.6665 6.66668C2.6665 10.2789 6.6665 12 7.99984 14.6667Z" stroke="#0F1D23" stroke-width="1.33333" stroke-linecap="round" stroke-linejoin="round"/></svg>',
					'value' => $addresses['address'],
				),
				array(
					'class' => 'wpte-customer-postcode',
					'label' => __( 'Zip/Postal Code:', 'wp-travel-engine' ),
					'icon'  => '<svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M7.00028 8.00001H3.33362M3.27718 8.19435L1.72057 12.8441C1.59828 13.2094 1.53713 13.3921 1.58101 13.5046C1.61912 13.6022 1.70096 13.6763 1.80195 13.7045C1.91824 13.7369 2.09388 13.6579 2.44517 13.4998L13.5862 8.48637C13.929 8.33208 14.1005 8.25493 14.1535 8.14775C14.1995 8.05464 14.1995 7.94539 14.1535 7.85228C14.1005 7.7451 13.929 7.66795 13.5862 7.51366L2.44129 2.4985C2.09106 2.34089 1.91595 2.26209 1.79977 2.29442C1.69888 2.32249 1.61704 2.39635 1.57881 2.49384C1.53478 2.60611 1.59527 2.78836 1.71625 3.15286L3.27761 7.85703C3.29839 7.91964 3.30878 7.95094 3.31288 7.98295C3.31652 8.01136 3.31649 8.04012 3.31277 8.06852C3.30859 8.10052 3.29812 8.1318 3.27718 8.19435Z" stroke="#0F1D23" stroke-width="1.33333" stroke-linecap="round" stroke-linejoin="round"/></svg>',
					'value' => $addresses['postcode'],
				),
				array(
					'class' => 'wpte-customer-country',
					'label' => __( 'Country:', 'wp-travel-engine' ),
					'icon'  => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M15 2.4578C14.053 2.16035 13.0452 2 12 2C6.47715 2 2 6.47715 2 12C2 17.5228 6.47715 22 12 22C17.5228 22 22 17.5228 22 12C22 10.2847 21.5681 8.67022 20.8071 7.25945M17 5.75H17.005M10.5001 21.8883L10.5002 19.6849C10.5002 19.5656 10.5429 19.4502 10.6205 19.3596L13.1063 16.4594C13.3106 16.2211 13.2473 15.8556 12.9748 15.6999L10.1185 14.0677C10.0409 14.0234 9.97663 13.9591 9.93234 13.8814L8.07046 10.6186C7.97356 10.4488 7.78657 10.3511 7.59183 10.3684L2.06418 10.8607M21 6C21 8.20914 19 10 17 12C15 10 13 8.20914 13 6C13 3.79086 14.7909 2 17 2C19.2091 2 21 3.79086 21 6ZM17.25 5.75C17.25 5.88807 17.1381 6 17 6C16.8619 6 16.75 5.88807 16.75 5.75C16.75 5.61193 16.8619 5.5 17 5.5C17.1381 5.5 17.25 5.61193 17.25 5.75Z" stroke="#0F1D23" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>',
					'value' => Functions::get_country_by_code( $addresses['country'] ),
				),
			)
		);
	}
}
