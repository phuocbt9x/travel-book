<?php
/**
 * Helper functions.
 *
 * @package WPTravelEngine/Helpers
 * @since 6.0.0
 */

namespace WPTravelEngine\Helpers;

use WPTravelEngine\Utilities\RequestParser;

class Functions {

	/**
	 *
	 * Get the request instance form current request.
	 *
	 * @param $method
	 *
	 * @return RequestParser
	 * @since 6.0.0
	 */
	public static function create_request( $method ): RequestParser {
		$request = new RequestParser( $method );

		$request->set_body( file_get_contents( 'php://input' ) );
		$request->set_query_params( $_GET );
		$request->set_header( 'Content-Type', 'application/json' );
		$request->set_body_params( $_POST );
		$request->set_file_params( $_FILES );

		return $request;
	}

	public function trip_price( $post_id ) {
		$wp_travel_engine_setting = \get_post_meta( $post_id, 'wp_travel_engine_setting', true );

		$cost = isset( $wp_travel_engine_setting['trip_price'] ) ? $wp_travel_engine_setting['trip_price'] : '';

		$prev_cost = isset( $wp_travel_engine_setting['trip_prev_price'] ) ? $wp_travel_engine_setting['trip_prev_price'] : '';

		if ( $cost != '' && isset( $wp_travel_engine_setting['sale'] ) ) {
			return $cost;
		} elseif ( $prev_cost != '' ) {
				$cost = $prev_cost;
		}

		return $cost;
	}

	public function init() {
		add_filter(
			'pre_term_description',
			function ( $value, $taxonomy ) {
				$taxonomies = apply_filters(
					'wp_travel_engine_taxonomies',
					array(
						'destination',
						'activities',
						'trip_types',
					)
				);
				if ( in_array( $taxonomy, $taxonomies ) ) {
					/**
					 * The taxonomies uses wp editor for as term description
					 * using wp_filter_kses strip all the tags so it
					 * is removed from pre term description.
					 * However we filters input using wp_kses_post() later.
					 */
					remove_filter( 'pre_term_description', 'wp_filter_kses' );

					return wp_kses_post( $value );
				}

				return $value;
			},
			9,
			2
		);

		foreach ( array( 'destination', 'activities', 'trip_types' ) as $taxonomy ) {
			$field = 'description';
			add_filter(
				"{$taxonomy}_{$field}",
				function ( $value ) {
					if ( is_admin() ) {
						return substr( $value, 0, 50 ) . '...';
					}

					return $value;
				}
			);
		}

		add_filter( 'term_description', 'shortcode_unautop' );
		add_filter( 'term_description', 'do_shortcode' );
		add_filter( 'the_content', array( __CLASS__, 'wte_remove_empty_p' ), 20, 1 );
		add_filter( 'term_description', array( __CLASS__, 'wte_remove_empty_p' ), 20, 1 );
		add_filter( 'pll_get_post_types', array( __CLASS__, 'wte_add_cpt_to_pll' ), 10, 2 );
		add_filter( 'pll_get_taxonomies', array( __CLASS__, 'wte_add_tax_to_pll' ), 10, 2 );
		add_filter( 'wp_travel_engine_setting', 'do_shortcode', 10 );
		add_action( 'wp_ajax_wte_user_profile_image_upload', array( __CLASS__, 'upload_user_profile_image' ) );
	}

	/**
	 * Upload profile image from form.
	 *
	 * @return void
	 */
	public static function upload_user_profile_image() {

		if ( ! empty( $_FILES ) && wp_verify_nonce( $_REQUEST['nonce'], 'wte-user-profile-image-nonce' ) ) :

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

			$source            = $_FILES['file']['tmp_name'];
			$salt              = md5( $_FILES['file']['name'] . time() );
			$file_name         = $salt . '-' . $_FILES['file']['name'];
			$img_file_location = trailingslashit( $wte_temp_dir ) . $file_name;

			$upload_url        = trailingslashit( $wte_temp_url ) . $file_name;
			$uploaded_filetype = wp_check_filetype( basename( $img_file_location ), null );

			$uploaded_filesize = $_FILES['file']['size'];
			$max_upload_size   = wp_max_upload_size();

			if ( $uploaded_filesize > $max_upload_size ) {
				wp_send_json_error( array( 'message' => __( 'File size too large.', 'wp-travel-engine' ) ) );
			}

			if ( ! in_array( $uploaded_filetype['type'], $allowed_filetypes ) ) {
				wp_send_json_error( array( 'message' => __( 'Unsupported file type uploaded.', 'wp-travel-engine' ) ) );
			}

			if ( wp_mkdir_p( $wte_temp_dir ) ) :
				if ( move_uploaded_file( $source, $img_file_location ) ) :

					$file_array = array(
						'file' => $img_file_location,
						'url'  => $upload_url,
						'type' => $uploaded_filetype,
					);
					echo json_encode( $file_array );
					wp_die();

				endif;
			endif;
		endif;

		wp_send_json_error( __( 'Invalid request. Nonce verification failed.', 'wp-travel-engine' ) );
	}

	public static function wte_add_cpt_to_pll( $post_types, $is_settings ) {
		if ( $is_settings ) {
			unset( $post_types['my_cpt'] );
			unset( $post_types['my_cpt1'] );
			unset( $post_types['my_cpt2'] );
			unset( $post_types['my_cpt3'] );
		} else {
			$post_types['my_cpt']  = 'trip';
			$post_types['my_cpt1'] = 'booking';
			$post_types['my_cpt2'] = 'customer';
			$post_types['my_cpt3'] = 'enquiry';
		}

		return $post_types;
	}


	public static function wte_add_tax_to_pll( $taxonomies, $is_settings ) {
		if ( $is_settings ) {
			unset( $taxonomies['my_tax'] );
			unset( $taxonomies['my_tax1'] );
			unset( $taxonomies['my_tax2'] );
		} else {
			$taxonomies['my_tax']  = 'destination';
			$taxonomies['my_tax1'] = 'activities';
			$taxonomies['my_tax2'] = 'trip_types';
		}

		return $taxonomies;
	}

	public function trip_currency_code( $post ) {
		$wp_travel_engine_setting_option_setting = get_option( 'wp_travel_engine_settings', true );
		$user                                    = get_userdata( $post->post_author );
		if ( class_exists( 'Vendor_Wp_Travel_Engine' ) && $user && in_array( 'trip_vendor', $user->roles ) ) {
			$userid = $user->ID;
			$user   = get_user_meta( $userid, 'wpte_vendor', true );
			if ( isset( $user['currency_code'] ) && $user['currency_code'] != '' ) {
				$code = $user['currency_code'];
			}
		} elseif ( isset( $wp_travel_engine_setting_option_setting['currency_code'] ) && $wp_travel_engine_setting_option_setting['currency_code'] != '' ) {
			$code = esc_attr( $wp_travel_engine_setting_option_setting['currency_code'] );
		} else {
			$code = 'USD';
		}
		$apiKey = isset( $wp_travel_engine_setting_option_setting['currency_converter_api'] ) && $wp_travel_engine_setting_option_setting['currency_converter_api'] != '' ? esc_attr( $wp_travel_engine_setting_option_setting['currency_converter_api'] ) : '';

		if ( class_exists( 'Wte_Trip_Currency_Converter_Init' ) && $apiKey != '' ) {
			$obj  = new \Wte_Trip_Currency_Converter_Init();
			$code = $obj->wte_trip_currency_code_converter( $code );
		}

		return $code;
	}

	public function convert_trip_price( $post, $trip_price ) {
		$code                                    = 'USD';
		$userid                                  = '';
		$wp_travel_engine_setting_option_setting = get_option( 'wp_travel_engine_settings', true );
		$user                                    = get_userdata( $post->post_author );
		if ( $user && in_array( 'trip_vendor', $user->roles ) ) {
			$userid = $user->ID;
			$user   = get_user_meta( $userid, 'wpte_vendor', true );
			if ( isset( $user['currency_code'] ) && $user['currency_code'] != '' ) {
				$code = $user['currency_code'];
			}
		} elseif ( isset( $wp_travel_engine_setting_option_setting['currency_code'] ) && $wp_travel_engine_setting_option_setting['currency_code'] != '' ) {
			$code = esc_attr( $wp_travel_engine_setting_option_setting['currency_code'] );
		}

		$global_code = $code;
		$obj         = new \Wte_Trip_Currency_Converter_Init();
		$code        = $obj->wte_trip_currency_code_converter( $global_code );
		$apiKey      = isset( $wp_travel_engine_setting_option_setting['currency_converter_api'] ) && $wp_travel_engine_setting_option_setting['currency_converter_api'] != '' ? esc_attr( $wp_travel_engine_setting_option_setting['currency_converter_api'] ) : '';

		if ( $global_code != $code && $apiKey != '' ) {
			$trip_price = $obj->wte_trip_price_converter( $userid, $global_code, $code, $trip_price );
		}

		return $trip_price;
	}

	/**
	 * Get Base Currency Code.
	 *
	 * @return string
	 */
	public function wp_travel_engine_currency() {
		$option        = get_option( 'wp_travel_engine_settings', array() );
		$currency_type = $option['currency_code'] ?? 'USD';

		return apply_filters( 'wp_travel_engine_currency', $currency_type );
	}

	public static function wte_remove_empty_p( $content ) {
		/**
		 * Commented to resolve the conflict with ninja form.
		 *
		 * @since 4.3.6
		 */
		// $content = force_balance_tags( $content );
		$content = preg_replace( '#<p>\s*+(<br\s*/*>)?\s*</p>#i', '', $content );
		$content = preg_replace( '~\s?<p>(\s|&nbsp;)+</p>\s?~', '', $content );

		return $content;
	}


	/**
	 * Get Pagination.
	 *
	 * @return string
	 */
	function pagination_bar( $custom_query ) {

		$total_pages = $custom_query->max_num_pages;
		$big         = 999999999; // need an unlikely integer

		if ( $total_pages > 1 ) {
			$current_page = max( 1, get_query_var( 'paged' ) );

			echo paginate_links( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				array(
					'base'    => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
					'format'  => '?paged=%#%',
					'current' => $current_page,
					'total'   => $total_pages,
				)
			);
		}
	}

	function wpte_pagination_option() {
		$pagination_type = get_theme_mod( 'pagination_type' );
		if ( $pagination_type == 'pagination_type-radio-numbered' ) {
			\wptravelengine_functions()->pagination_bar();
		} elseif ( $pagination_type == 'pagination_type-radio-default' ) {
			$args = array(
				'base'               => '%_%',
				'format'             => '?paged=%#%',
				'total'              => 1,
				'current'            => 0,
				'show_all'           => false,
				'end_size'           => 1,
				'mid_size'           => 2,
				'prev_next'          => true,
				'prev_text'          => __( '« Previous', 'wp-travel-engine' ),
				'next_text'          => __( 'Next »', 'wp-travel-engine' ),
				'type'               => 'plain',
				'add_args'           => false,
				'add_fragment'       => '',
				'before_page_number' => '',
				'after_page_number'  => '',
			);
			echo paginate_links( $args ); //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}
	}

	/**
	 * Get currency codes or currency symbol.
	 *
	 * @return array
	 */
	function wte_trip_symbol_options( $code, $currency, $cost ) {
		$obj      = \wptravelengine_functions();
		$settings = get_option( 'wp_travel_engine_settings' );
		$option   = isset( $settings['currency_option'] ) && $settings['currency_option'] != '' ? esc_attr( $settings['currency_option'] ) : 'symbol';

		if ( isset( $option ) && $option == 'symbol' ) {
			return '<span class="price-holder"><span>' . esc_attr( $currency ) . '&nbsp;' . esc_attr( $obj->wp_travel_engine_price_format( $cost ) ) . '</span></span>';
		} else {
			return '<span class="price-holder"><span>' . esc_attr( $code ) . '&nbsp;' . esc_attr( $obj->wp_travel_engine_price_format( $cost ) ) . '</span></span>';
		}
	}

	/**
	 * Get currencies list with country name.
	 *
	 * @return array List of currencies.
	 * @since 5.8.0
	 */
	public static function get_currencies(): array {
		return apply_filters( 'wp_travel_engine_currencies', Currencies::list() );
	}


	public static function currency_symbol_by_code( $currency = '' ) {
		if ( ! $currency ) {
			$currency = \wptravelengine_functions()->wp_travel_engine_currency();
		}

		$symbols = apply_filters( 'wp_travel_engine_currency_symbols', Currencies::code_symbols() );

		return apply_filters( 'wp_travel_engine_currency_symbol', $symbols[ $currency ] ?? '', $currency );
	}

	/**
	 * Get Currency symbol.
	 *
	 * @param string $currency (default: '')
	 *
	 * @return string
	 */
	function wp_travel_engine_currencies_symbol( string $currency = '' ): string {
		return self::currency_symbol_by_code( $currency );
	}

	/**
	 * Get clean special characters free string
	 *
	 * @return clean string
	 */
	public function wpte_clean( $string ) {
		$string = str_replace( ' ', '-', $string ); // Replaces all spaces with hyphens.
		$string = preg_replace( '/[^A-Za-z0-9\-]/', '', $string ); // Removes special chars.
		$string = strtolower( $string ); // Convert to lowercase

		return $string;
	}

	/**
	 * Get field options for trip facts.
	 *
	 * @return array
	 */
	function trip_facts_field_options(): array {
		$options = array(
			'text'     => 'text',
			'number'   => 'number',
			'select'   => 'select',
			'textarea' => 'textarea',
			'duration' => 'duration',
		);

		return apply_filters( 'wp_travel_engine_trip_facts_field_options', $options );
	}

	/**
	 * Get options for title while a booking trip.
	 *
	 * @return array
	 */
	function order_form_title_options(): array {
		$options = array(
			'Mr'    => __( 'Mr', 'wp-travel-engine' ),
			'Mrs'   => __( 'Mrs', 'wp-travel-engine' ),
			'Ms'    => __( 'Ms', 'wp-travel-engine' ),
			'Miss'  => __( 'Miss', 'wp-travel-engine' ),
			'Other' => __( 'Other', 'wp-travel-engine' ),
		);

		return apply_filters( 'wp_travel_engine_order_form_title_options', $options );
	}

	/**
	 * Get default payment method.
	 *
	 * @param string $options (default: '')
	 *
	 * @return string
	 */
	function payment_gateway_options() {
		$options = array(
			'paypal_standard' => 'PayPal Standard',
			'test_payment'    => 'Test Payment',
			'amazon'          => 'Amazon',
		);
		$options = apply_filters( 'wp_travel_engine_default_payment_gateway_options', $options );

		return $options;
	}

	/**
	 * Get field options for place order form.
	 */
	function wp_travel_engine_place_order_field_options() {
		$options = array(
			'text'         => 'text',
			'number'       => 'number',
			'select'       => 'select',
			'textarea'     => 'textarea',
			'country-list' => 'countrylist',
			'datetime'     => 'datetime',
			'email'        => 'email',
		);
		$options = apply_filters( 'wp_travel_engine_place_order_field_options', $options );

		return $options;
	}

	/**
	 * Get template options for place order form.
	 */
	function wp_travel_engine_template_options() {
		$options = array(
			'default-template' => 'default-template',
		);
		$options = apply_filters( 'wp_travel_engine_template_options', $options );

		return $options;
	}

	function getLen( $var ) {
		$settings            = get_option( 'wp_travel_engine_settings' );
		$thousands_separator = isset( $settings['thousands_separator'] ) && $settings['thousands_separator'] != '' ? esc_attr( $settings['thousands_separator'] ) : ',';
		$tmp                 = explode( $thousands_separator, $var );
		if ( count( $tmp ) > 1 ) {
			return strlen( $tmp[1] );
		}
	}

	/**
	 * Get formatted cost.
	 *
	 * @param string $formatted_cost (default: '')
	 *
	 * @return string
	 */
	function wp_travel_engine_price_format( $cost = '' ) {
		$settings            = get_option( 'wp_travel_engine_settings' );
		$thousands_separator = isset( $settings['thousands_separator'] ) && $settings['thousands_separator'] != '' ? esc_attr( $settings['thousands_separator'] ) : ',';
		// $formatted_cost = number_format($cost, $this->getLen($cost));
		$formatted_cost = number_format( (int) $cost, 0, '', apply_filters( 'wp_travel_engine_default_separator', $thousands_separator ) );

		return $formatted_cost;
	}

	/**
	 *
	 * Get list of countries with their country codes.
	 *
	 * @return array
	 * @since 5.8.0
	 */
	public static function get_countries(): array {
		return apply_filters( 'wp_travel_engine_country_options', Countries::list() );
	}

	/**
	 * Get country list for dropdown.
	 *
	 * @deprecated 5.8.0 Use `get_countries` method instead.
	 * @since 1.0.0
	 */
	function wp_travel_engine_country_list(): array {
		return static::get_countries();
	}

	function order_form_billing_options() {
		$options = array(
			'fname'   => array(
				'label'       => __( 'First Name', 'wp-travel-engine' ),
				'type'        => 'text',
				'placeholder' => __( 'Your First Name', 'wp-travel-engine' ),
				'required'    => '1',
			),
			'lname'   => array(
				'label'       => __( 'Last Name', 'wp-travel-engine' ),
				'type'        => 'text',
				'placeholder' => __( 'Your Last Name', 'wp-travel-engine' ),
				'required'    => '1',
			),
			'email'   => array(
				'label'       => __( 'Email', 'wp-travel-engine' ),
				'type'        => 'email',
				'placeholder' => __( 'Your Valid Email', 'wp-travel-engine' ),
				'required'    => '1',
			),
			'address' => array(
				'label'       => __( 'Address', 'wp-travel-engine' ),
				'type'        => 'text',
				'placeholder' => __( 'Your Address', 'wp-travel-engine' ),
				'required'    => '1',
			),
			'city'    => array(
				'label'       => __( 'City', 'wp-travel-engine' ),
				'type'        => 'text',
				'placeholder' => __( 'Your City', 'wp-travel-engine' ),
				'required'    => '1',
			),
			'country' => array(
				'label'    => __( 'Country', 'wp-travel-engine' ),
				'type'     => 'country-list',
				'required' => '1',
			),
		);
		$options = apply_filters( 'wp_travel_engine_order_form_billing_options', $options );

		return $options;
	}

	function order_form_personal_options() {
		$options = array(
			'title'    => array(
				'label'    => __( 'Title', 'wp-travel-engine' ),
				'type'     => 'select',
				'required' => '1',
				'options'  => array(
					'Mr'    => __( 'Mr', 'wp-travel-engine' ),
					'Mrs'   => __( 'Mrs', 'wp-travel-engine' ),
					'Ms'    => __( 'Ms', 'wp-travel-engine' ),
					'Miss'  => __( 'Miss', 'wp-travel-engine' ),
					'Other' => __( 'Other', 'wp-travel-engine' ),
				),
			),
			'fname'    => array(
				'label'       => __( 'First Name', 'wp-travel-engine' ),
				'type'        => 'text',
				'placeholder' => __( 'Your First Name', 'wp-travel-engine' ),
				'required'    => '1',
			),
			'lname'    => array(
				'label'       => __( 'Last Name', 'wp-travel-engine' ),
				'type'        => 'text',
				'placeholder' => __( 'Your Last Name', 'wp-travel-engine' ),
				'required'    => '1',
			),
			'passport' => array(
				'label'       => __( 'Passport Number', 'wp-travel-engine' ),
				'type'        => 'text',
				'placeholder' => __( 'Your Valid Passport Number', 'wp-travel-engine' ),
				'required'    => '1',
			),
			'email'    => array(
				'label'       => __( 'Email', 'wp-travel-engine' ),
				'type'        => 'email',
				'placeholder' => __( 'Your Valid Email', 'wp-travel-engine' ),
				'required'    => '1',
			),
			'address'  => array(
				'label'       => __( 'Address', 'wp-travel-engine' ),
				'type'        => 'text',
				'placeholder' => __( 'Your Address', 'wp-travel-engine' ),
				'required'    => '1',
			),
			'city'     => array(
				'label'       => __( 'City', 'wp-travel-engine' ),
				'type'        => 'text',
				'placeholder' => __( 'Your City', 'wp-travel-engine' ),
				'required'    => '1',
			),
			'country'  => array(
				'label'    => __( 'Country', 'wp-travel-engine' ),
				'type'     => 'country-list',
				'required' => '1',
			),
			'postcode' => array(
				'label'    => __( 'Post-code', 'wp-travel-engine' ),
				'type'     => 'number',
				'required' => '1',
			),
			'phone'    => array(
				'label'    => __( 'Phone', 'wp-travel-engine' ),
				'type'     => 'tel',
				'required' => '1',
			),
			'dob'      => array(
				'label'    => __( 'Date of Birth', 'wp-travel-engine' ),
				'type'     => 'text',
				'required' => '1',
			),
			'special'  => array(
				'label'    => __( 'Special Requirements', 'wp-travel-engine' ),
				'type'     => 'textarea',
				'required' => '1',
			),
		);
		$options = apply_filters( 'wp_travel_engine_order_form_personal_options', $options );

		return $options;
	}

	function wpte_enquiry_options() {
		$options = array(

			'country'  => array(
				'label'       => __( 'Country', 'wp-travel-engine' ),
				'type'        => 'country-list',
				'placeholder' => __( 'Choose a country&hellip;', 'wp-travel-engine' ),
				'required'    => '1',
			),
			'contact'  => array(
				'label'       => __( 'Contact No.', 'wp-travel-engine' ),
				'type'        => 'tel',
				'placeholder' => __( 'Enter Your Contact Number', 'wp-travel-engine' ),
				'required'    => '1',
			),
			'adults'   => array(
				'label'       => __( 'Adults', 'wp-travel-engine' ),
				'type'        => 'number',
				'placeholder' => __( 'Enter Number of Adults', 'wp-travel-engine' ),
				'required'    => '1',
			),
			'children' => array(
				'label'       => __( 'Children', 'wp-travel-engine' ),
				'type'        => 'number',
				'placeholder' => __( 'Enter Number of Children', 'wp-travel-engine' ),
				'required'    => '0',
			),
			'message'  => array(
				'label'       => __( 'Your Message', 'wp-travel-engine' ),
				'type'        => 'textarea',
				'placeholder' => __( 'Enter Your message', 'wp-travel-engine' ),
				'required'    => '1',
			),
		);
		$options = apply_filters( 'wp_travel_engine_inquiry_form_options', $options );

		return $options;
	}

	function order_form_relation_options() {
		$options = array(
			'title'    => array(
				'label'    => __( 'Title', 'wp-travel-engine' ),
				'type'     => 'select',
				'required' => '1',
				'options'  => array(
					'Mr'    => __( 'Mr', 'wp-travel-engine' ),
					'Mrs'   => __( 'Mrs', 'wp-travel-engine' ),
					'Ms'    => __( 'Ms', 'wp-travel-engine' ),
					'Miss'  => __( 'Miss', 'wp-travel-engine' ),
					'Other' => __( 'Other', 'wp-travel-engine' ),
				),
			),
			'fname'    => array(
				'label'       => __( 'First Name', 'wp-travel-engine' ),
				'type'        => 'text',
				'placeholder' => __( 'Your First Name', 'wp-travel-engine' ),
				'required'    => '1',
			),
			'lname'    => array(
				'label'       => __( 'Last Name', 'wp-travel-engine' ),
				'type'        => 'text',
				'placeholder' => __( 'Your Last Name', 'wp-travel-engine' ),
				'required'    => '1',
			),
			'phone'    => array(
				'label'    => __( 'Phone', 'wp-travel-engine' ),
				'type'     => 'tel',
				'required' => '1',
			),
			'relation' => array(
				'label'    => __( 'Relationship', 'wp-travel-engine' ),
				'type'     => 'text',
				'required' => '1',
			),
		);
		$options = apply_filters( 'wp_travel_engine_order_form_relation_options', $options );

		return $options;
	}

	/**
	 * Get gender options.
	 *
	 * @return array Gender options.
	 */
	public function gender_options(): array {
		$options = array(
			'male'   => __( 'Male', 'wp-travel-engine' ),
			'female' => __( 'Female', 'wp-travel-engine' ),
			'other'  => __( 'Other', 'wp-travel-engine' ),
		);

		return apply_filters( 'wp_travel_engine_gender_options', $options );
	}

	function wp_mail_from() {
		$current_site = get_option( 'blogname' );

		return 'wordpress@' . $current_site;
	}

	/**
	 * Sanitize a multidimensional array
	 *
	 * @param (array)
	 *
	 * @return (array) the sanitized array
	 * @uses htmlspecialchars
	 */
	function wte_sanitize_array( $data = array() ) {
		if ( ! is_array( $data ) || ! count( $data ) ) {
			return array();
		}
		foreach ( $data as $k => $v ) {
			if ( ! is_array( $v ) && ! is_object( $v ) ) {
				$data[ $k ] = htmlspecialchars( trim( $v ) );
			}
			if ( is_array( $v ) ) {
				$data[ $k ] = \wptravelengine_functions()->wte_sanitize_array( $v );
			}
		}

		return $data;
	}

	function recursive_html_entity_decode( $data = array() ) {
		if ( ! is_array( $data ) || ! count( $data ) ) {
			return array();
		}
		foreach ( $data as $key => &$value ) {
			if ( is_array( $value ) ) {
				$value = \wptravelengine_functions()->recursive_html_entity_decode( $value );
			} else {
				$value = html_entity_decode( $value );
			}
		}

		return $data;
	}

	/**
	 * @param $code string Country code.
	 *
	 * @return false|string
	 * @since 5.8.0
	 */
	public static function get_country_by_code( string $code ) {
		return self::get_countries()[ $code ] ?? false;
	}


	/**
	 * Return template.
	 *
	 * @param String $template_name Path of template.
	 * @param array  $args arguments.
	 *
	 * @return Mixed
	 */
	public static function get_template( $template_name, $args = array() ) {
		$template_path = 'template-parts/';
		$default_path  = \WP_TRAVEL_ENGINE_BASE_PATH . '/includes/templates/';

		extract( $args );
		// Look templates in theme first.
		$template = locate_template(
			array(
				trailingslashit( $template_path ) . $template_name,
				$template_name,
			)
		);
		if ( ! $template ) {
			$template = $default_path . $template_name;
		}
		if ( file_exists( $template ) ) {
			include $template;
		}

		return false;
	}

	/**
	 * Add "None" as first option to a select field options array (DRY for form fields).
	 *
	 * @param array $options       Select field options (key => label).
	 * @param bool  $skip_sentinel If true, when __skip_none_option__ is present, remove it and return without adding "None".
	 * @return array Modified options array.
	 * @since 6.7.6
	 */
	public static function add_none_option_to_select( array $options, bool $skip_sentinel = false ): array {
		$has_skip_sentinel = isset( $options['__skip_none_option__'] );
		if ( $has_skip_sentinel ) {
			unset( $options['__skip_none_option__'] );
			if ( $skip_sentinel ) {
				return $options;
			}
		}

		if ( ! empty( $options ) && ! array_key_exists( '', $options ) ) {
			return array( '' => __( 'None', 'wp-travel-engine' ) ) + $options;
		}

		return $options;
	}

	/**
	 * @param $code string Country code.
	 *
	 * @return false|string
	 * @deprecated 5.8.0 Use country_by_code method instead.
	 */
	function Wte_countryCodeToName( $code ) {
		return static::get_country_by_code( $code );
	}

	/**
	 * Get full list of currency codes.
	 *
	 * @return array
	 * @deprecated 5.8.0 Use `get_currencies` method instead.
	 */
	public function wp_travel_engine_currencies(): array {
		return static::get_currencies();
	}
}
