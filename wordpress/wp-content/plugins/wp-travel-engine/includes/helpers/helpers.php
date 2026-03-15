<?php

/**
 * Helper functions for WP Travel Engine.
 *
 * @package WP_Travel_Engine
 */

use WPTravelEngine\Builders\FormFields\BillingFormFields;
use WPTravelEngine\Builders\FormFields\EmergencyFormFields;
use WPTravelEngine\Builders\FormFields\LeadTravellersFormFields;
use WPTravelEngine\Builders\FormFields\PrivacyPolicyFields;
use WPTravelEngine\Builders\FormFields\TravellersFormFields;
use WPTravelEngine\Core\Coupons;
use WPTravelEngine\Core\Models\Post\Trip;
use WPTravelEngine\Core\Models\Post\TripPackage;
use WPTravelEngine\Core\Models\Settings\Options;
use WPTravelEngine\Core\Models\Settings\PluginSettings;
use WPTravelEngine\Email\UserEmail;
use WPTravelEngine\Pages\Checkout;
use WPTravelEngine\PaymentGateways\PaymentGateways;
use WPTravelEngine\Utilities\Price;
use WPTravelEngine\Utilities\RequestParser;

/**
 * Sanitize Request Params.
 *
 * @param array $params Parameters to sanitize.
 *
 * @return array
 * @since 6.7.0
 */
function wptravelengine_sanitize_params_recursive( array $params ): array {

	$sanitized_params = array();

	foreach ( $params as $key => $value ) {
		if ( is_array( $value ) ) {
			$sanitized_params[ $key ] = wptravelengine_sanitize_params_recursive( $value );
		} elseif ( is_int( $value ) ) {
			$sanitized_params[ $key ] = intval( $value );
		} elseif ( is_float( $value ) ) {
			$sanitized_params[ $key ] = floatval( $value );
		} elseif ( is_bool( $value ) ) {
			$sanitized_params[ $key ] = filter_var( $value, FILTER_VALIDATE_BOOLEAN );
		} elseif ( is_email( $value ) ) {
			$sanitized_params[ $key ] = sanitize_email( $value );
		} elseif ( filter_var( $value, FILTER_VALIDATE_URL ) ) {
			$sanitized_params[ $key ] = esc_url_raw( $value );
		} else {
			$sanitized_params[ $key ] = sanitize_text_field( $value );
		}
	}

	return $sanitized_params;
}

/**
 * Get template by view mode.
 *
 * @param string $view_mode View mode.
 *
 * @return string Template path.
 * @since 6.6.8
 */
function wptravelengine_get_template_by_view_mode( $view_mode = 'default' ) {
	if ( $view_mode === 'default' ) {
		$view_mode = wp_travel_engine_get_archive_view_mode();
	}
	return sanitize_file_name( 'content-' . $view_mode . '.php' );
}

/**
 * Get the discount label.
 *
 * @param TripPackage $trip_package
 * @param int         $discount_percent
 * @param int         $discount_amount
 *
 * @return string
 * @since 6.6.5
 */
function wptravelengine_get_discount_label( $trip_package ) {

	if ( ! $trip_package instanceof TripPackage ) {
		return null;
	}

	$engine_settings     = wptravelengine_settings();
	$show_discounts_type = $engine_settings->get( 'show_discounts_type', 'percentage' );

	$discount_label = '';
	if ( 'percentage' === $show_discounts_type ) {
		if ( is_rtl() ) {
			$discount_label = sprintf( __( '%s Off', 'wp-travel-engine' ), '%' . $trip_package->sale_percentage );
		} else {
			$discount_label = sprintf( __( '%1$s%% Off', 'wp-travel-engine' ), $trip_package->sale_percentage );
		}
	} else {
		$sale_amount = $trip_package->sale_amount;

		$price_object = new Price( $sale_amount );
		$price_object->use_html( false )
					->format( true );

		$formatted_price = $price_object->output;
		$discount_label  = sprintf( __( '%s Off', 'wp-travel-engine' ), $formatted_price );
	}

	return apply_filters( 'wptravelengine_get_discount_label', $discount_label, $trip_package );
}

/**
 * @param $form_data
 *
 * @return void
 * @since 6.3.3
 */
function wptravelengine_cache_form_data( $key, $form_data ) {
	WTE()->session->set_json( $key, $form_data );
}

/**
 * @param WP_REST_Request $request
 *
 * @since 6.3.3
 */
function wptravelengine_cache_checkout_form_data( RequestParser $request ) {

	if ( $data = $request->get_param( 'emergency' ) ) {
		wptravelengine_cache_form_data( 'emergency_form_data', $data );
	}

	if ( $data = $request->get_param( 'billing' ) ) {
		wptravelengine_cache_form_data( 'billing_form_data', $data );
	}

	if ( $data = $request->get_param( 'travellers' ) ) {
		wptravelengine_cache_form_data( 'travellers_form_data', $data );
	}

	if ( $data = $request->get_param( 'wptravelengine_additional_note' ) ) {
		wptravelengine_cache_form_data( 'additional_note', $data );
	}
}

/**
 * Get the current template arg value.
 *
 * @param string $key
 * @param mixed  $default
 *
 * @return mixed|string
 * @since 6.3.3
 */
function wptravelengine_get_template_arg( string $key, $default = '' ) {
	global $wptravelengine_template_args;

	return $wptravelengine_template_args[ $key ] ?? $default;
}

/**
 * @param string $date
 * @param string $format
 *
 * @return string
 * @since 6.3.0
 */
function wptravelengine_format_trip_datetime( string $date, string $format = '' ): string {
	$date_format = $format;
	if ( empty( $format ) ) {
		$date_format  = get_option( 'date_format', 'j M, Y' );
		$date_format .= strpos( $date, 'T' ) !== false ? ' \a\t ' . get_option( 'time_format', 'g:i a' ) : '';
	}

	return wp_date( $date_format, strtotime( $date ), new DateTimeZone( 'UTC' ) );
}

/**
 * @param string $start_date
 * @param Trip   $trip
 * @param string $format
 *
 * @return string
 * @since 6.3.0
 */
function wptravelengine_format_trip_end_datetime( string $start_date, Trip $trip, string $format = '' ): string {
	$date_format = $format;
	if ( empty( $format ) ) {
		$date_format  = get_option( 'date_format', 'j M, Y' );
		$date_format .= strpos( $start_date, 'T' ) !== false ? ' \a\t ' . get_option( 'time_format', 'g:i a' ) : '';
	}

	$trip_duration_type = $trip->get_trip_duration_unit() ?? 'days';
	$trip_duration      = 'days' === $trip_duration_type ? ( intval( $trip->get_trip_duration() ) - 1 ) : intval( $trip->get_trip_duration() );

	return wp_date( $date_format, strtotime( "+$trip_duration {$trip_duration_type}", strtotime( $start_date ) ), new DateTimeZone( 'UTC' ) );
}

/**
 * This function is used to get the template and pass arguments through the templates.
 *
 * @since 6.3.0
 */
function wptravelengine_get_template( string $template_name, array $args = array(), string $template_path = '', string $default_path = '' ) {
	wte_get_template( $template_name, wptravelengine_set_template_args( $args ), $template_path, $default_path );
}

/**
 * Get admin template.
 *
 * @param string $template_name
 * @param array  $args
 * @param string $template_path
 * @param string $default_path
 *
 * @return void
 * @since 6.4.0
 */
function wptravelengine_get_admin_template( string $template_name, array $args = array(), string $template_path = '', string $default_path = '' ) {
	wptravelengine_get_template(
		$template_name,
		wptravelengine_set_template_args( $args ),
		$template_path,
		WP_TRAVEL_ENGINE_BASE_PATH . '/includes/backend/templates/'
	);
}

/**
 * Add arguments to be used by templates.
 *
 * @param array $args Additional arguments.
 *
 * @return array
 * @since 6.3.0
 */
function wptravelengine_set_template_args( array $args ): array {
	global $wptravelengine_template_args;

	$wptravelengine_template_args = array_merge( $wptravelengine_template_args, $args );

	return $wptravelengine_template_args;
}

/**
 * Template Arguments.
 *
 * @param array $args
 *
 * @return array
 * @since 6.3.0
 */
function wptravelengine_get_template_args( array $args = array() ): array {
	global $wptravelengine_template_args;

	return array_merge( $wptravelengine_template_args, $args );
}

/**
 * Checkout page template args.
 *
 * @param array $args
 *
 * @return array
 * @since 6.3.0
 */
function wptravelengine_get_checkout_template_args( array $args = array() ): array {
	global $wte_cart;

	$wptravelengine_settings = get_option( 'wp_travel_engine_settings', array() );
	$checkout_page_template  = $wptravelengine_settings['checkout_page_template'] ?? '1.0';
	$display_header_footer   = $wptravelengine_settings['display_header_footer'] ?? 'no';
	$show_travellers_info    = $wptravelengine_settings['display_travellers_info'] ?? 'yes';
	$show_emergency_contact  = $wptravelengine_settings['display_emergency_contact'] ?? 'yes';
	$traveller_details_form  = $wptravelengine_settings['traveller_emergency_details_form'] ?? 'on_checkout';
	$display_billing_details = $wptravelengine_settings['display_billing_details'] ?? 'yes';
	$show_additional_note    = $wptravelengine_settings['show_additional_note'] ?? 'yes';
	$show_coupon_form        = $wptravelengine_settings['show_discount'] ?? 'yes';
	$is_payment_due          = $wte_cart->get_booking_ref();
	$attributes              = array(
		'version'               => $checkout_page_template,
		'header'                => $checkout_page_template == '2.0' && $display_header_footer == 'yes' ? 'default' : 'none',
		'footer'                => $checkout_page_template == '2.0' && $display_header_footer == 'yes' ? 'default' : 'none',
		'checkout-steps'        => 'show',
		'tour-details'          => 'show',
		'tour-details-title'    => 'show',
		'cart-summary'          => 'show',
		'cart-summary-title'    => 'show',
		'lead-travellers'       => $is_payment_due ? 'hide' : ( $show_travellers_info == 'yes' && $traveller_details_form == 'on_checkout' ? 'show' : 'hide' ),
		'lead-travellers-title' => 'show',
		'travellers'            => $is_payment_due ? 'hide' : ( $show_travellers_info == 'yes' && $traveller_details_form == 'on_checkout' ? 'show' : 'hide' ),
		'travellers-title'      => 'show',
		'emergency'             => $is_payment_due ? 'hide' : ( $show_emergency_contact == 'yes' && $traveller_details_form == 'on_checkout' ? 'show' : 'hide' ),
		'emergency-title'       => 'show',
		'billing'               => $display_billing_details == 'yes' ? 'show' : 'hide',
		'billing-title'         => 'show',
		'additional_note'       => $show_additional_note == 'yes' ? 'show' : 'hide',
		'additional-note-title' => 'show',
		'payment'               => 'show',
		'payment-title'         => 'show',
		'coupon_form'           => $show_coupon_form == 'yes' && Coupons::is_coupon_available() && 'due' !== $wte_cart->get_payment_type() ? 'show' : 'hide',
		'footer_copyright'      => $wptravelengine_settings['footer_copyright'] ?? '',
	);

	$form_sections = array(
		'billing' => 'content-billing-details',
		'payment' => 'content-payments',
	);

	$template_args['form_sections'] = apply_filters( 'wptravelengine_checkoutv2_form_templates', $form_sections );

	$is_partial_payment_applicable = 'due' !== $wte_cart->get_payment_type() && 'booking_only' !== ( $wte_cart->payment_gateway ?? 'booking_only' );
	if ( $is_partial_payment_applicable ) {
		foreach ( $wte_cart->getItems( true ) as $item ) {
			$is_partial_payment_applicable = wp_travel_engine_is_trip_partially_payable( $item->trip_id );
			if ( ! $is_partial_payment_applicable ) {
				break;
			}
		}
	}

	$coupons = array();

	foreach ( $wte_cart->get_deductible_items() as $coupon_item ) {
		if ( 'coupon' !== $coupon_item->name ) {
			continue;
		}
		$coupons[] = array(
			'label'  => $coupon_item->label,
			'amount' => $wte_cart->get_totals()['total_coupon'] ?? 0,
		);
	}
	$checkout_page               = new Checkout( $wte_cart );
	$tour_details                = $checkout_page->get_tour_details();
	$cart_line_items             = $checkout_page->get_cart_line_items();
	$billing_form_fields         = new BillingFormFields();
	$lead_travellers_form_fields = array();
	foreach ( $wte_cart->getItems( true ) as $cart_item ) {
		$lead_travellers_form_fields[] = new LeadTravellersFormFields();
	}

	$travellers_form_fields = array();
	foreach ( $wte_cart->getItems( true ) as $cart_item ) {
		$travellers_form_fields[] = new TravellersFormFields(
			array(
				'number_of_travellers'      => array_sum( $cart_item->travelers ?? $cart_item->pax ),
				'number_of_lead_travellers' => 1,
			)
		);
	}
	$note_form_fields = wptravelengine_form_field( false )->init( $checkout_page->get_note_form_fields() );

	$emergency_contact_fields = new EmergencyFormFields();

	$payment_options = $checkout_page->get_payment_options();

	$payment_type = $checkout_page->get_payment_type();

	$full_payment_enabled = $checkout_page->is_full_payment_enabled();

	$full_payment_amount = $checkout_page->get_full_payment_amount();

	$down_payment_amount = $checkout_page->get_down_payment_amount();

	$due_payment_amount = $checkout_page->get_due_payment_amount();

	$privacy_policy_fields = new PrivacyPolicyFields();

	return array_merge(
	// $template_args,
		compact( 'note_form_fields' ),
		compact(
			'tour_details',
			'cart_line_items',
			'billing_form_fields',
			'lead_travellers_form_fields',
			'travellers_form_fields',
			'privacy_policy_fields',
			'emergency_contact_fields',
			'payment_options',
			'payment_type',
			'full_payment_enabled',
			'full_payment_amount',
			'down_payment_amount',
			'due_payment_amount'
		),
		compact( 'attributes', 'form_sections', 'is_partial_payment_applicable', 'coupons' ),
		$args
	);
}

/**
 * Trip primary package.
 *
 * @param Trip|int $trip The trip-object or ID.
 *
 * @return ?TripPackage
 * @since 6.1.0
 */
function wptravelengine_get_trip_primary_package( $trip ) {
	$trip = wptravelengine_get_trip( $trip );

	if ( $trip instanceof Trip && ! $trip->use_legacy_trip ) {
		return $trip->default_package();
	}

	return null;
}


/**
 * Wrapper for _doing_it_wrong().
 *
 * @param string $function Function used.
 * @param string $message Message to log.
 * @param string $version Version the message was added in.
 *
 * @since  3.1.3
 */
function wte_doing_it_wrong( $function, $message, $version ) {
	// @codingStandardsIgnoreStart
	$message .= ' Backtrace: ' . wp_debug_backtrace_summary();

	if ( wp_doing_ajax() ) {
		do_action( 'doing_it_wrong_run', $function, $message, $version );
		error_log( "{$function} was called incorrectly. {$message}. This message was added in version {$version}." );
	} else {
		_doing_it_wrong( $function, $message, $version );
	}
	// @codingStandardsIgnoreEnd
}

/**
 * Return array list of all trips.
 *
 * @return Array
 */
function wp_travel_engine_get_trips_array( $use_titles = false ) {
	$args = array(
		'post_type'   => 'trip',
		'numberposts' => - 1,
		'orderby'     => 'title',
		'order'       => 'ASC',
	);

	$trips = get_posts( $args );

	$trips_array = array();
	foreach ( $trips as $trip ) {
		if ( $use_titles ) {
			$trips_array[ $trip->post_title ] = $trip->post_title;
		} else {
			$trips_array[ $trip->ID ] = $trip->post_title;
		}
	}

	return apply_filters( 'wp_travel_engine_trips_array', $trips_array, $args );
}

/**
 * Get permalink settings for WP Travel Engine.
 *
 * @return array
 * @since  2.2.4
 */
function wp_travel_engine_get_permalink_structure() {

	$permalinks = wp_parse_args(
		(array) get_option( 'wp_travel_engine_permalinks', array() ),
		array(
			'wp_travel_engine_trip_base'        => '',
			'wp_travel_engine_trip_type_base'   => '',
			'wp_travel_engine_destination_base' => '',
			'wp_travel_engine_activity_base'    => '',
			'wp_travel_engine_difficulty_base'  => '',
			'wp_travel_engine_tags_base'        => '',
		)
	);

	$permalinks['wp_travel_engine_trip_base']        = untrailingslashit( empty( $permalinks['wp_travel_engine_trip_base'] ) ? 'trip' : $permalinks['wp_travel_engine_trip_base'] );
	$permalinks['wp_travel_engine_trip_type_base']   = untrailingslashit( empty( $permalinks['wp_travel_engine_trip_type_base'] ) ? 'trip-types' : $permalinks['wp_travel_engine_trip_type_base'] );
	$permalinks['wp_travel_engine_destination_base'] = untrailingslashit( empty( $permalinks['wp_travel_engine_destination_base'] ) ? 'destinations' : $permalinks['wp_travel_engine_destination_base'] );
	$permalinks['wp_travel_engine_activity_base']    = untrailingslashit( empty( $permalinks['wp_travel_engine_activity_base'] ) ? 'activities' : $permalinks['wp_travel_engine_activity_base'] );
	$permalinks['wp_travel_engine_difficulty_base']  = untrailingslashit( empty( $permalinks['wp_travel_engine_difficulty_base'] ) ? 'trip-difficulty' : $permalinks['wp_travel_engine_difficulty_base'] );
	$permalinks['wp_travel_engine_tags_base']        = untrailingslashit( empty( $permalinks['wp_travel_engine_tags_base'] ) ? 'trip-tag' : $permalinks['wp_travel_engine_tags_base'] );

	return $permalinks;
}

/**
 * Get trip settings meta.
 *
 * @param int $trip_id
 *
 * @return mixed $trip_settings | false
 * @since 2.2.4
 */
function wp_travel_engine_get_trip_metas( $trip_id ) {

	if ( ! $trip_id ) {
		return false;
	}

	$trip_settings = get_post_meta( $trip_id, 'wp_travel_engine_setting', true );

	return ! empty( $trip_settings ) ? $trip_settings : false;
}

/**
 * Get trip preview price ( Before sale )
 *
 * @param int $trip_id
 *
 * @return int $prev_price
 * @since 2.2.4
 */
function wp_travel_engine_get_prev_price( $trip_id, $no_convert = false ) {

	if ( ! $trip_id ) {
		return 0;
	}

	$wtetrip = \wte_get_trip( $trip_id );

	if ( isset( $wtetrip->post ) && $wtetrip->post->ID === (int) $trip_id ) {
		$trip = $wtetrip;
	} else {
		$trip = wte_get_trip( $trip_id );
	}

	if ( $trip && ! $trip->use_legacy_trip ) {
		return $no_convert ? $trip->price : apply_filters( 'wp_travel_engine_trip_prev_price', $trip->price, $trip_id );
	}

	$trip_settings = wp_travel_engine_get_trip_metas( $trip_id );
	$prev_price    = '';

	if ( $trip_settings ) {
		$prev_price = isset( $trip_settings['trip_prev_price'] ) ? $trip_settings['trip_prev_price'] : '';
	}

	if ( $no_convert ) {
		return $prev_price;
	}

	return apply_filters( 'wp_travel_engine_trip_prev_price', $prev_price, $trip_id );
}

/**
 * Get trip sale price
 *
 * @param [type] $trip_id
 *
 * @return void
 */
function wp_travel_engine_get_sale_price( $trip_id, $no_convert = false ) {

	if ( ! $trip_id ) {
		return 0;
	}

	$wtetrip = \wte_get_trip( $trip_id );

	if ( isset( $wtetrip->post ) && $wtetrip->post->ID === (int) $trip_id ) {
		$trip = $wtetrip;
	} else {
		$trip = wte_get_trip( $trip_id );
	}

	if ( $wtetrip && ! $wtetrip->use_legacy_trip ) {
		return $no_convert ? $trip->sale_price : apply_filters( 'wp_travel_engine_trip_prev_price', $trip->sale_price, $trip_id );
	}

	$trip_settings = wp_travel_engine_get_trip_metas( $trip_id );
	$sale_price    = '';

	if ( $trip_settings ) {
		$sale_price = isset( $trip_settings['trip_price'] ) ? $trip_settings['trip_price'] : '';
	}

	if ( $no_convert ) {
		return $sale_price;
	}

	return apply_filters( 'wp_travel_engine_trip_prev_price', $sale_price, $trip_id );
}

/**
 * Check if the trip is on sale
 *
 * @param int $trip_id
 *
 * @return bool
 */
function wp_travel_engine_is_trip_on_sale( $trip_id ) {

	if ( ! $trip_id ) {
		return false;
	}

	$wtetrip = \wte_get_trip( $trip_id );

	if ( isset( $wtetrip->post ) && $wtetrip->post->ID === (int) $trip_id ) {
		$trip = $wtetrip;
	} else {
		$trip = wte_get_trip( $trip_id );
	}

	if ( $wtetrip && ! $wtetrip->use_legacy_trip ) {
		return $trip->has_sale;
	}

	$trip_settings = wp_travel_engine_get_trip_metas( $trip_id );

	if ( ! $trip_settings ) {
		return false;
	}

	$trip_on_sale = isset( $trip_settings['sale'] ) ? true : false;

	return apply_filters( 'wp_travel_engine_is_trip_on_sale', $trip_on_sale, $trip_id );
}

/**
 * Get actual trip price.
 *
 * @param [type] $trip_id
 *
 * @return void
 */
function wp_travel_engine_get_actual_trip_price( $trip_id, $no_convert = false ) {

	if ( ! $trip_id ) {
		return 0;
	}

	$on_sale = wp_travel_engine_is_trip_on_sale( $trip_id );

	$trip_actual_price = $on_sale ? wp_travel_engine_get_sale_price( $trip_id, $no_convert ) : wp_travel_engine_get_prev_price( $trip_id, $no_convert );

	return apply_filters( 'wp_travel_engine_actual_trip_price', $trip_actual_price, $trip_id );
}

/**
 * Get currenct code.
 *
 * @return string
 */
function wp_travel_engine_get_currency_code( $use_default_currency_code = false ) {

	$wp_travel_engine_settings = get_option( 'wp_travel_engine_settings', true );

	$code = 'USD';

	if ( isset( $wp_travel_engine_settings['currency_code'] ) && $wp_travel_engine_settings['currency_code'] != '' ) {
		$code = $wp_travel_engine_settings['currency_code'];
	}

	return apply_filters( 'wp_travel_engine_currency_code', $code, $use_default_currency_code );
}

/**
 * Get currency symbol
 *
 * @param string $code
 *
 * @return void
 */
function wp_travel_engine_get_currency_symbol( $code = 'USD' ) {

	$wte      = \wte_functions();
	$currency = $wte->wp_travel_engine_currencies_symbol( $code );

	return $currency;
}

/**
 * Get fixed departure dates array.
 *
 * @param [type] $trip_id
 *
 * @return void
 */
function wp_travel_engine_get_fixed_departure_dates( $trip_id, $get_month = false ) {

	$obj                         = \wte_functions();
	$valid_departure_dates_array = array();

	if ( ! $trip_id ) {
		return $valid_departure_dates_array;
	}

	if ( class_exists( 'WTE_Fixed_Starting_Dates_Functions' ) && method_exists( 'WTE_Fixed_Starting_Dates_Functions', 'get_formated_fsd_dates' ) ) {

		$WTE_Fixed_Starting_Dates_option_setting = get_option( 'wp_travel_engine_settings', true );

		$num = isset( $WTE_Fixed_Starting_Dates_option_setting['trip_dates']['number'] ) ? $WTE_Fixed_Starting_Dates_option_setting['trip_dates']['number'] : 3;

		$fsd_functions = new WTE_Fixed_Starting_Dates_Functions();
		$sorted_fsd    = $fsd_functions->get_formated_fsd_dates( $trip_id );

		$valid_departure_dates_array = $sorted_fsd;
	}

	return $valid_departure_dates_array;
}

/**
 * Get checkout page URL
 *
 * @return string
 * @deprecated 6.0.4
 */
function wp_travel_engine_get_checkout_url(): string {
	return wptravelengine_get_checkout_url();
}

/**
 * Sorted extras
 *
 * @param [type] $trip_id
 * @param array  $extra_services
 *
 * @return void
 */
function wp_travel_engine_sort_extra_services( $trip_id, $extra_services = array() ) {

	$sorted_extras = array();

	if ( ! $trip_id ) {

		return $sorted_extras;
	}

	$wp_travel_engine_setting = get_post_meta( $trip_id, 'wp_travel_engine_setting', true );
	// phpcs:disable
	foreach ( $extra_services as $key => $value ) {
		if ( ! empty( $extra_services[ $key ] ) && ! empty( $_POST[ 'extra_service_name' ][ $key ] ) && '0' !== $extra_services[ $key ] ) {
			$sorted_extras[ $key ] = array(
				'extra_service' => $wp_travel_engine_setting[ 'extra_service' ][ $key ],
				'qty'           => $extra_services[ $key ],
				'price'         => wte_clean( wp_unslash( $_POST[ 'extra_service_name' ][ $key ] ) ),
			);
		}
	}

	return $sorted_extras;
	// phpcs:enable
}

/**
 * Get trip duration [ formatted ]
 */
function wp_travel_engine_get_trip_duration( $trip_id ) {

	if ( ! $trip_id ) {
		return false;
	}

	$trip_settings = get_post_meta( $trip_id, 'wp_travel_engine_setting', true );

	return sprintf( _nx( '%s Day', '%s Days', $trip_settings['trip_duration'], 'trip duration days', 'wp-travel-engine' ), number_format_i18n( $trip_settings['trip_duration'] ) ) . ' ' . sprintf( _nx( '%s Night', '%s Nights', $trip_settings['trip_duration_nights'], 'trip duration nights', 'wp-travel-engine' ), number_format_i18n( $trip_settings['trip_duration_nights'] ) );
}

add_action( 'wp_travel_engine_proceed_booking_btn', 'wp_travel_engine_default_booking_proceed' );

/**
 * Default proceed booking button.
 *
 * @return void
 */
function wp_travel_engine_default_booking_proceed() {

	$data = apply_filters( 'wp_travel_engine_booking_process_btn_html', false );

	if ( (bool) $data ) {
		echo wp_kses_post( $data );

		return;
	}

	$wp_travel_engine_setting_option_setting = get_option( 'wp_travel_engine_settings', true );

	global $post;
	?>
	<button class="check-availability">
		<?php
		$button_txt = __( 'Check Availability', 'wp-travel-engine' );
		echo esc_html( apply_filters( 'wp_travel_engine_check_availability_button_text', $button_txt ) );
		?>
	</button>
	<?php
	$btn_txt = wte_default_labels( 'checkout.submitButtonText' );
	if ( isset( $wp_travel_engine_setting_option_setting['book_btn_txt'] ) && $wp_travel_engine_setting_option_setting['book_btn_txt'] != '' ) {
		$btn_txt = $wp_travel_engine_setting_option_setting['book_btn_txt'];
	}
	?>
	<input name="booking_btn" data-formid="booking-frm-<?php echo esc_attr( $post->ID ); ?>" type="submit"
			class="book-submit" value="<?php echo esc_attr( $btn_txt ); ?>">
	<?php
}

/**
 * Locate a template and return the path for inclusion.
 *
 * This is the load order:
 *
 * yourtheme/$template_path/$template_name
 * yourtheme/$template_name
 * $default_path/$template_name
 *
 * @param string $template_name Template name.
 * @param string $template_path Template path. (default: '').
 * @param string $default_path Default path. (default: '').
 *
 * @return string Template path.
 * @since 1.0.0
 */
function wte_locate_template( $template_name, $template_path = '', $default_path = '' ) {
	if ( ! $template_path ) {
		$template_path = apply_filters( 'wp_travel_engine_template_path', 'wp-travel-engine/' );
	}

	if ( ! $default_path ) {
		$default_path = WP_TRAVEL_ENGINE_BASE_PATH . '/includes/templates/';
	}

	// Look within passed path within the theme - this is priority.
	$template = locate_template(
		array(
			trailingslashit( $template_path ) . $template_name,
			$template_name,
		)
	);

	// Get default template.
	if ( ! $template ) {
		// Look within passed path within the theme - this is priority.
		$template = locate_template(
			array(
				trailingslashit( $template_name ),
				$template_name,
			)
		);
		if ( ! $template ) {
			$template = trailingslashit( $default_path ) . $template_name;
		}
	}

	// Return what we found.
	return apply_filters( 'wte_locate_template', $template, $template_name, $template_path );
}

/**
 * Get other templates (e.g. article attributes) passing attributes and including the file.
 *
 * @param string $template_name Template name.
 * @param array  $args Arguments. (default: array).
 * @param string $template_path Template path. (default: '').
 * @param string $default_path Default path. (default: '').
 *
 * @since 1.0.0
 */
function wte_get_template( $template_name, $args = array(), $template_path = '', $default_path = '' ) {

	$cache_key = sanitize_key(
		implode(
			'-',
			array(
				'template',
				$template_name,
				$template_path,
				$default_path,
				WP_TRAVEL_ENGINE_VERSION,
			)
		)
	);
	$template  = (string) wp_cache_get( $cache_key, 'wp-travel-engine' );

	if ( ! $template ) {
		$template = wte_locate_template( $template_name, $template_path, $default_path );
		wp_cache_set( $cache_key, $template, 'wp-travel-engine' );
	}

	// Allow 3rd party plugin filter template file from their plugin.
	$filter_template = apply_filters( 'wte_get_template', $template, $template_name, $args, $template_path, $default_path );

	if ( $filter_template !== $template ) {
		if ( ! file_exists( $filter_template ) ) {
			/* translators: %s template */
			wte_doing_it_wrong( __FUNCTION__, sprintf( __( '%s does not exist.', 'wp-travel-engine' ), '<code>' . $template . '</code>' ), '1.0.0' );

			return;
		}
		$template = $filter_template;
	}

	$action_args = array(
		'template_name' => $template_name,
		'template_path' => $template_path,
		'located'       => $template,
		'args'          => $args,
	);

	if ( ! empty( $args ) && is_array( $args ) ) {
		if ( isset( $args['action_args'] ) ) {
			wte_doing_it_wrong(
				__FUNCTION__,
				__( 'action_args should not be overwritten when calling wte_get_template.', 'wp-travel-engine' ),
				'1.0.0'
			);
			unset( $args['action_args'] );
		}
		extract( $args );
	}

	do_action( 'wte_before_template_part', $action_args['template_name'], $action_args['template_path'], $action_args['located'], $action_args['args'] );

	include $action_args['located'];

	do_action( 'wte_after_template_part', $action_args['template_name'], $action_args['template_path'], $action_args['located'], $action_args['args'] );
}


/**
 * Like wte_get_template, but return the HTML instaed of outputting.
 *
 * @param string $template_name Template name.
 * @param array  $args Arguments. (default: array).
 * @param string $template_path Template path. (default: '').
 * @param string $default_path Default path. (default: '').
 *
 * @return string.
 * @since 1.0.0
 *
 * @see wte_get_template
 */
function wte_get_template_html( $template_name, $args = array(), $template_path = '', $default_path = '' ) {
	ob_start();
	wte_get_template( $template_name, $args, $template_path, $default_path );

	return ob_get_clean();
}

/**
 * Get list of all available payment gateways.
 *
 * @return array
 */
function wp_travel_engine_get_available_payment_gateways(): array {
	return PaymentGateways::instance()->get_payment_gateways( true );
}

/**
 * Get a sorted payment gateway list array.
 *
 * @return array
 */
function wp_travel_engine_get_sorted_payment_gateways(): array {

	$wpte_settings      = get_option( 'wp_travel_engine_settings' );
	$available_gateways = wp_travel_engine_get_available_payment_gateways();

	$payment_gateway_sorted_settings = ! empty( $wpte_settings['sorted_payment_gateways'] ) ? $wpte_settings['sorted_payment_gateways'] : array_keys( $available_gateways );

	$sorted_payment_gateways = array();

	foreach ( $payment_gateway_sorted_settings as $key ) :
		if ( array_key_exists( $key, $available_gateways ) ) :
			$sorted_payment_gateways[ $key ] = $available_gateways[ $key ];
			unset( $available_gateways[ $key ] );
		endif;
	endforeach;

	return $sorted_payment_gateways + $available_gateways;
}

/**
 * Return active payment gateways.
 *
 * @return array
 */
function wp_travel_engine_get_active_payment_gateways(): array {
	return PaymentGateways::instance()->get_active_payment_gateways( true );
}

/**
 * Get booking confirmation page URL
 *
 * @return url Confirmation page url.
 */
function wp_travel_engine_get_booking_confirm_url() {

	$wte_settings = get_option( 'wp_travel_engine_settings', array() );

	$wte_confirm = wte_array_get( $wte_settings, 'pages.wp_travel_engine_confirmation_page', '' );

	$hide_traveler_information = apply_filters( 'wptravelengine_hide_traveler_emergency_form', wte_array_get( $wte_settings, 'travelers_information', 'yes' ) === 'yes' );

	if ( $hide_traveler_information || empty( $wte_confirm ) ) {
		$wte_confirm = wte_array_get( $wte_settings, 'pages.wp_travel_engine_thank_you', '' );
	}

	if ( empty( $wte_confirm ) ) :
		$wte_confirm = esc_url( home_url( '/' ) );
	else :
		$wte_confirm = get_permalink( $wte_confirm );
	endif;

	return $wte_confirm;
}

/*
 * Delete all the transients with a prefix.
 */
function wte_purge_transients( $prefix ) {
	global $wpdb;

	$prefix = esc_sql( $prefix );

	$options = $wpdb->options;

	$t = esc_sql( "_transient_timeout_{$prefix}%" );

	$sql = $wpdb->prepare(
		"
		SELECT option_name
		FROM $options
		WHERE option_name LIKE '%s'
	  ",
		$t
	);

	$transients = $wpdb->get_col( $sql );

	// For each transient...
	foreach ( $transients as $transient ) {

		// Strip away the WordPress prefix in order to arrive at the transient key.
		$key = str_replace( '_transient_timeout_', '', $transient );

		// Now that we have the key, use WordPress core to the delete the transient.
		delete_transient( $key );
	}

	// But guess what?  Sometimes transients are not in the DB, so we have to do this too:
	wp_cache_flush();
}

/**
 * Get view mode from request and default view mode.
 *
 * @return string $view_mode
 * @updated 6.6.8
 */
function wp_travel_engine_get_archive_view_mode() {

	$view_mode = apply_filters( 'wp_travel_engine_default_archive_view_mode', get_option( 'wptravelengine_trip_view_mode', 'list' ) );

	if ( wp_is_mobile() ) {
		$view_mode = 'grid';
	}

	if ( isset( $_REQUEST['mode'] ) ) {
		$view_mode = sanitize_text_field( wp_unslash( $_REQUEST['mode'] ) );
	} elseif ( isset( $_REQUEST['view_mode'] ) ) {
		$view_mode = sanitize_text_field( wp_unslash( $_REQUEST['view_mode'] ) );
	}

	$allowed_modes = apply_filters( 'wptravelengine_allowed_archive_view_modes', array( 'grid', 'list' ) );
	$view_mode     = apply_filters( 'wptravelengine_archive_view_mode', $view_mode );

	if ( ! in_array( $view_mode, $allowed_modes, true ) ) {
		$view_mode = 'list';
	}

	return $view_mode;
}

/**
 * Outputs hidden form inputs for each query string variable.
 *
 * @param string|array $values Name value pairs, or a URL to parse.
 * @param array        $exclude Keys to exclude.
 * @param string       $current_key Current key we are outputting.
 * @param bool         $return Whether to return.
 *
 * @return string
 * @since 3.0.6
 */
function wte_query_string_form_fields( $values = null, $exclude = array(), $current_key = '', $return = false ) {
	if ( is_null( $values ) ) {
		$values = wte_clean( wp_unslash( $_GET ) ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
	} elseif ( is_string( $values ) ) {
		$url_parts = wp_parse_url( $values );
		$values    = array();

		if ( ! empty( $url_parts['query'] ) ) {
			// This is to preserve full-stops, pluses and spaces in the query string when ran through parse_str.
			$replace_chars = array(
				'.' => '{dot}',
				'+' => '{plus}',
			);

			$query_string = str_replace( array_keys( $replace_chars ), array_values( $replace_chars ), $url_parts['query'] );

			// Parse the string.
			parse_str( $query_string, $parsed_query_string );

			// Convert the full-stops, pluses and spaces back and add to values array.
			foreach ( $parsed_query_string as $key => $value ) {
				$new_key            = str_replace( array_values( $replace_chars ), array_keys( $replace_chars ), $key );
				$new_value          = str_replace( array_values( $replace_chars ), array_keys( $replace_chars ), $value );
				$values[ $new_key ] = $new_value;
			}
		}
	}
	$html = '';

	foreach ( $values as $key => $value ) {
		if ( in_array( $key, $exclude, true ) ) {
			continue;
		}
		if ( $current_key ) {
			$key = $current_key . '[' . $key . ']';
		}
		if ( is_array( $value ) ) {
			$html .= wte_query_string_form_fields( $value, $exclude, $key, true );
		} else {
			$html .= '<input type="hidden" name="' . esc_attr( $key ) . '" value="' . esc_attr( wp_unslash( $value ) ) . '" />';
		}
	}

	if ( $return ) {
		return $html;
	}

	echo $html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}

/**
 * Get Enquiry form field by name.
 */
function wp_travel_engine_get_enquiry_field_label_by_name( $name = false ) {
	if ( ! $name ) {
		return false;
	}
	$enquiry_form_fields = \WTE_Default_Form_Fields::enquiry();

	$enquiry_form_fields                 = apply_filters( 'wp_travel_engine_enquiry_fields_display', $enquiry_form_fields );
	$enquiry_form_fields['package_name'] = array(
		'field_label' => __( 'Trip Name', 'wp-travel-engine' ),
	);

	$field_label = isset( $enquiry_form_fields[ $name ] ) && isset( $enquiry_form_fields[ $name ]['field_label'] ) ? $enquiry_form_fields[ $name ]['field_label'] : $name;

	return $field_label;
}

/**
 * Get Booking form field by name.
 */
function wp_travel_engine_get_booking_field_label_by_name( $name = false ) {
	if ( ! $name ) {
		return false;
	}
	$booking_form_fields = \WTE_Default_Form_Fields::booking();
	$booking_form_fields = apply_filters( 'wp_travel_engine_booking_fields_display', $booking_form_fields );

	$field_label = isset( $booking_form_fields[ $name ] ) && isset( $booking_form_fields[ $name ]['field_label'] ) ? $booking_form_fields[ $name ]['field_label'] : $name;

	return $field_label;
}

/**
 * Get ller Info form field by name.
 */
function wp_travel_engine_get_traveler_info_field_label_by_name( $name = false ) {
	if ( ! $name ) {
		return false;
	}
	$traveller_info_form_fields = WTE_Default_Form_Fields::traveller_information();

	$traveller_info_form_fields = apply_filters( 'wp_travel_engine_traveller_info_fields_display', $traveller_info_form_fields );

	$field_label = isset( $traveller_info_form_fields[ $name ] ) && isset( $traveller_info_form_fields[ $name ]['field_label'] ) ? $traveller_info_form_fields[ $name ]['field_label'] : $name;
	$_name       = preg_replace( '/^traveller_/', '', $name );

	if ( ( $field_label === $name ) && isset( $traveller_info_form_fields[ $_name ]['field_label'] ) ) {
		$field_label = $traveller_info_form_fields[ $_name ]['field_label'];
	}

	return $field_label;
}

/**
 * Get ller Info form field by name.
 */
function wp_travel_engine_get_relationship_field_label_by_name( $name = false ) {
	if ( ! $name ) {
		return false;
	}
	$emergency_contact_form_fields = WTE_Default_Form_Fields::emergency_contact();
	$emergency_contact_form_fields = apply_filters( 'wp_travel_engine_emergency_contact_fields_display', $emergency_contact_form_fields );

	$field_label = isset( $emergency_contact_form_fields[ $name ] ) && isset( $emergency_contact_form_fields[ $name ]['field_label'] ) ? $emergency_contact_form_fields[ $name ]['field_label'] : $name;

	return $field_label;
}

/**
 * Get Default Settings Tab
 */
function wte_get_default_settings_tab() {
	$default_tabs = array(
		'name'  => array(
			'1' => __( 'Overview', 'wp-travel-engine' ),
			'2' => __( 'Itinerary', 'wp-travel-engine' ),
			'3' => __( 'Cost', 'wp-travel-engine' ),
			'4' => __( 'Dates', 'wp-travel-engine' ),
			'5' => __( 'FAQs', 'wp-travel-engine' ),
			'6' => __( 'Map', 'wp-travel-engine' ),
		),

		'field' => array(
			'1' => 'wp_editor',
			'2' => 'itinerary',
			'3' => 'cost',
			'4' => 'dates',
			'5' => 'faqs',
			'6' => 'map',
		),
		'id'    => array(
			'1' => '1',
			'2' => '2',
			'3' => '3',
			'4' => '4',
			'5' => '5',
			'6' => '6',
		),
	);

	return $default_tabs;
}

/**
 * Get From Email Address
 */
function wte_get_from_email() {
	$admin_email = get_option( 'admin_email' );
	$sitename    = strtolower( wte_clean( wp_unslash( $_SERVER[ 'SERVER_NAME' ] ) ) ); // phpcs:ignore

	if ( in_array( $sitename, array( 'localhost', '127.0.0.1' ) ) ) {
		return $admin_email;
	}

	if ( substr( $sitename, 0, 4 ) == 'www.' ) {
		$sitename = substr( $sitename, 4 );
	}

	if ( strpbrk( $admin_email, '@' ) == '@' . $sitename ) {
		return $admin_email;
	}

	return 'wordpress@' . $sitename;
}

/**
 * Check if site is using old booking process.
 */
function wp_travel_engine_use_old_booking_process() {
	return defined( 'WTE_USE_OLD_BOOKING_PROCESS' ) && WTE_USE_OLD_BOOKING_PROCESS;
}

/**
 *
 * @since 5.4.1
 */
function wptravelengine_get_option( $option_name, $key = null ) {
	$options = get_option( $option_name, array() );
	if ( $key ) {
		return isset( $options[ $key ] ) ? $options[ $key ] : null;
	}

	return $options;
}

/**
 * Return All Settings of WP travel Engine.
 *
 * @deprecated 6.0.0
 */
function wp_travel_engine_get_settings( $key = null ) {
	wptravelengine_deprecated_function( __FUNCTION__, '6.0.0', 'wptravelengine_settings' );

	return wptravelengine_settings()->get( $key );
}

/**
 * Get dashboard page ID or resort to default.
 *
 * @return void
 */
function wp_travel_engine_get_dashboard_page_id() {
	$settings = wptravelengine_settings()->get();

	$wp_travel_engine_dashboard_id = isset( $settings['pages']['wp_travel_engine_dashboard_page'] ) ? esc_attr( $settings['pages']['wp_travel_engine_dashboard_page'] ) : wp_travel_engine_get_page_id( 'my-account' );

	return $wp_travel_engine_dashboard_id;
}

/**
 * Checks whether the content passed contains a specific short code.
 *
 * @param string $tag Shortcode tag to check.
 *
 * @return bool
 */
function wp_travel_engine_post_content_has_shortcode( $tag = '' ) {
	global $post;

	return is_singular() && is_a( $post, 'WP_Post' ) && has_shortcode( $post->post_content, $tag );
}

/**
 * Retrieve page ids - cart, checkout. returns -1 if no page is found.
 *
 * @param string $page Page slug.
 *
 * @return int
 */
function wp_travel_engine_get_page_id( $page ) {

	if ( 'search' === $page ) {
		return \WPTravelEngine\Modules\TripSearch::get_page_id();
	}

	$settings = get_option( 'wp_travel_engine_settings' ); // Not used wp_travel_engine_get_settings due to infinite loop.
	$page     = str_replace( 'wp-travel-engine-', '', $page );
	if ( ! empty( $settings['pages']['wp_travel_engine_place_order'] ) ) {
		return $settings['pages']['wp_travel_engine_place_order'];
	}
	$page_id = ( isset( $settings[ $page . '_page_id' ] ) ) ? $settings[ $page . '_page_id' ] : '';

	if ( ! $page_id ) {
		$page_id = get_option( 'wp_travel_engine_' . $page . '_page_id' );
	}

	$page_id = apply_filters( 'wp_travel_engine_get_' . $page . '_page_id', $page_id );

	return $page_id ? absint( $page_id ) : - 1;
}

/**
 * Retrieve page permalink.
 *
 * @param string $page page slug.
 *
 * @return string
 */
function wp_travel_engine_get_page_permalink( $page ) {
	$page_id   = wp_travel_engine_get_page_id( $page );
	$permalink = 0 < $page_id ? get_permalink( $page_id ) : get_home_url();

	return apply_filters( 'wp_travel_engine_get_' . $page . '_page_permalink', $permalink );
}

/**
 * Retrieve page permalink by id.
 *
 * @param string $page page id.
 *
 * @return string
 */
function wp_travel_engine_get_page_permalink_by_id( $page_id ) {
	$permalink = 0 < $page_id ? get_permalink( $page_id ) : get_home_url();

	return apply_filters( 'wp_travel_engine_get_' . $page_id . '_permalink', $permalink );
}

/**
 * Check whether page is dashboard page or not.
 *
 * @return Boolean
 */
function wp_travel_engine_is_dashboard_page() {
	if ( is_admin() ) {
		return false;
	}
	$page_id  = get_the_ID();
	$settings = wptravelengine_settings()->get();
	if ( ( isset( $settings['dashboard_page_id'] ) && (int) $settings['dashboard_page_id'] === $page_id ) || wp_travel_engine_post_content_has_shortcode( 'wp_travel_engine_dashboard' ) ) {
		return true;
	}

	return false;
}

/**
 * Check whether page is thank you page or not.
 *
 * @return Boolean
 */
function wp_travel_engine_is_thank_you_page() {
	if ( is_admin() ) {
		return false;
	}
	$page_id  = get_the_ID();
	$settings = wptravelengine_settings()->get();
	if ( ( isset( $settings['wp_travel_engine_thank_you'] ) && (int) $settings['wp_travel_engine_thank_you'] === $page_id ) || wp_travel_engine_post_content_has_shortcode( 'WP_TRAVEL_ENGINE_THANK_YOU' ) ) {
		return true;
	}

	return false;
}

if ( ! function_exists( 'wp_travel_engine_is_account_page' ) ) {

	/**
	 * wp_travel_engine_is_account_page - Returns true when viewing an account page.
	 *
	 * @return bool
	 */
	function wp_travel_engine_is_account_page() {
		return is_page( wp_travel_engine_get_dashboard_page_id() ) || wp_travel_engine_post_content_has_shortcode( 'wp_travel_engine_dashboard' ) || apply_filters( 'wp_travel_engine_is_account_page', false );
	}
}

if ( ! function_exists( 'wp_travel_engine_is_checkout_page' ) ) {

	/**
	 * wp_travel_engine_is_checkout_page - Returns true when viewing an account page.
	 *
	 * @return bool
	 */
	function wp_travel_engine_is_checkout_page() {
		return is_page( wp_travel_engine_get_page_id( 'wp_travel_engine_place_order' ) ) || wp_travel_engine_post_content_has_shortcode( 'WP_TRAVEL_ENGINE_PLACE_ORDER' ) || apply_filters( 'wp_travel_engine_is_checkout_page', false );
	}
}

/**
 * Create a page and store the ID in an option.
 *
 * @param mixed  $slug Slug for the new page
 * @param string $option Option name to store the page's ID
 * @param string $page_title (default: '') Title for the new page
 * @param string $page_content (default: '') Content for the new page
 * @param int    $post_parent (default: 0) Parent for the new page
 *
 * @return int page ID
 */
function wp_travel_engine_create_page( $slug, $option = '', $page_title = '', $page_content = '', $post_parent = 0 ) {
	global $wpdb;

	$option_value = get_option( $option );

	if ( $option_value > 0 && ( $page_object = get_post( $option_value ) ) ) {
		if ( 'page' === $page_object->post_type && ! in_array(
			$page_object->post_status,
			array(
				'pending',
				'trash',
				'future',
				'auto-draft',
			)
		) ) {
			// Valid page is already in place
			if ( strlen( $page_content ) > 0 ) {
				// Search for an existing page with the specified page content (typically a shortcode)
				$valid_page_found = $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE post_type='page' AND post_status NOT IN ( 'pending', 'trash', 'future', 'auto-draft' ) AND post_content LIKE %s LIMIT 1;", "%{$page_content}%" ) );
			} else {
				// Search for an existing page with the specified page slug
				$valid_page_found = $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE post_type='page' AND post_status NOT IN ( 'pending', 'trash', 'future', 'auto-draft' )  AND post_name = %s LIMIT 1;", $slug ) );
			}

			$valid_page_found = apply_filters( 'wp_travel_engine_create_page_id', $valid_page_found, $slug, $page_content );

			if ( $valid_page_found ) {
				if ( $option ) {
					update_option( $option, $valid_page_found );
				}

				return $valid_page_found;
			}
		}
	}

	// Search for a matching valid trashed page
	if ( strlen( $page_content ) > 0 ) {
		// Search for an existing page with the specified page content (typically a shortcode)
		$trashed_page_found = $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE post_type='page' AND post_status = 'trash' AND post_content LIKE %s LIMIT 1;", "%{$page_content}%" ) );
	} else {
		// Search for an existing page with the specified page slug
		$trashed_page_found = $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE post_type='page' AND post_status = 'trash' AND post_name = %s LIMIT 1;", $slug ) );
	}

	if ( $trashed_page_found ) {
		$page_id   = $trashed_page_found;
		$page_data = array(
			'ID'          => $page_id,
			'post_status' => 'publish',
		);
		wp_update_post( $page_data );
	} else {
		$page_data = array(
			'post_status'    => 'publish',
			'post_type'      => 'page',
			'post_author'    => 1,
			'post_name'      => $slug,
			'post_title'     => $page_title,
			'post_content'   => $page_content,
			'post_parent'    => $post_parent,
			'comment_status' => 'closed',
		);
		$page_id   = wp_insert_post( $page_data );
	}

	if ( $option ) {
		update_option( $option, $page_id );
	}

	return $page_id;
}

/**
 * Print success and error notices set by WP Travel Plugin.
 */
function wp_travel_engine_print_notices() {
	// Print Errors / Notices.
	WTE()->notices->print_notices( 'error', true );
	WTE()->notices->print_notices( 'success', true );
}

/**
 * Sort array by priority.
 *
 * @return array $array
 */
function wp_travel_engine_sort_array_by_priority( $array, $priority_key = 'priority' ) {
	$priority = array();
	if ( is_array( $array ) && count( $array ) > 0 ) {
		foreach ( $array as $key => $row ) {
			$priority[ $key ] = isset( $row[ $priority_key ] ) ? $row[ $priority_key ] : 1;
		}
		array_multisort( $priority, SORT_ASC, $array );
	}

	return $array;
}

/**
 * Retrieves unvalidated referer from '_wp_http_referer' or HTTP referer.
 *
 * Do not use for redirects, use {@see wp_get_referer()} instead.
 *
 * @return string|false Referer URL on success, false on failure.
 * @since 1.3.3
 */
function wp_travel_engine_get_raw_referer() {
	if ( function_exists( 'wp_get_raw_referer' ) ) {
		return wp_get_raw_referer();
	}

	// phpcs:disable
	if ( ! empty( $_REQUEST[ '_wp_http_referer' ] ) ) {
		return wte_clean( wp_unslash( $_REQUEST[ '_wp_http_referer' ] ) );
	} else if ( ! empty( $_SERVER[ 'HTTP_REFERER' ] ) ) {
		return wte_clean( wp_unslash( $_SERVER[ 'HTTP_REFERER' ] ) );
	}
	// phpcs:enable
}

function wte_get_active_single_trip_tabs() {
	global $post;

	$settings  = get_option( 'wp_travel_engine_settings', array() );
	$post_meta = get_post_meta( $post->ID, 'wp_travel_engine_setting', true );

	if ( empty( $post_meta ) ) {
		return false;
	}

	if ( empty( $settings['trip_tabs'] ) || ! is_array( $settings['trip_tabs']['id'] ) ) {
		$settings['trip_tabs'] = \wte_get_default_settings_tab();
	}

	foreach ( $settings['trip_tabs']['id'] as $key => $value ) {
		$enable = isset( $settings['trip_tabs']['enable'][ $value ] ) && ! empty( $settings['trip_tabs']['enable'][ $value ] ) ? $settings['trip_tabs']['enable'][ $value ] : 'yes';

		if ( 'no' === $enable ) {
			unset( $settings['trip_tabs']['id'][ $value ] );
			continue;
		}

		switch ( $settings['trip_tabs']['field'][ $value ] ) {
			case 'wp_editor':
				if ( ! isset( $post_meta['tab_content'][ $key . '_wpeditor' ] ) || empty( $post_meta['tab_content'][ $key . '_wpeditor' ] ) ) {
					unset( $settings['trip_tabs']['id'][ $value ] );
				}
				break;
			case 'itinerary':
				if ( ! isset( $post_meta['itinerary']['itinerary_title'] ) || empty( reset( $post_meta['itinerary']['itinerary_title'] ) ) ) {
					unset( $settings['trip_tabs']['id'][ $value ] );
				}
				break;
			case 'cost':
				if (
					empty( $post_meta['cost']['cost_includes'] )
					&& empty( $post_meta['cost']['cost_excludes'] )
				) {
					unset( $settings['trip_tabs']['id'][ $value ] );
				}
				break;
			case 'faqs':
				if ( empty( $post_meta['faq']['faq_title'] ) ) {
					unset( $settings['trip_tabs']['id'][ $value ] );
				}
				break;
			case 'review':
				if (
					! class_exists( 'Wte_Trip_Review_Init' )
					|| isset( $settings['trip_reviews']['hide'] )
				) {
					unset( $settings['trip_tabs']['id'][ $value ] );
				}
				break;
			case 'guides':
				if ( ! class_exists( 'WPTE_Guides_Profile_Init' ) ) {
					unset( $settings['trip_tabs']['id'][ $value ] );
				}
				break;
			case 'map':
				$map_image  = isset( $post_meta['map']['image_url'] ) && ! empty( $post_meta['map']['image_url'] ) ? true : false;
				$map_iframe = isset( $post_meta['map']['iframe'] ) && ! empty( $post_meta['map']['iframe'] ) ? true : false;
				if ( ! $map_image && ! $map_iframe ) {
					unset( $settings['trip_tabs']['id'][ $value ] );
				}
				break;
			case 'dates':
				$trip_id    = $post->ID;
				$active     = false;
				$fsd_active = apply_filters( 'wte_is_fsd_active_available', $active, $trip_id );
				if ( ! $fsd_active ) {
					unset( $settings['trip_tabs']['id'][ $value ] );
				}
				break;
		}
	}

	return $settings;
}

/**
 * Check if the current page is WP Travel page or not.
 *
 * @return boolean
 * @since Travel Muni release version
 */
function is_wte_archive_page() {

	if ( ( is_post_type_archive( 'trip' ) || is_tax(
		array(
			'destination',
			'activities',
			'trip_types',
			'trip_tag',
			'difficulty',
		)
	) ) && ! is_search() ) {
		return true;
	}

	return false;
}

/**
 * Check if trip is featured trip.
 *
 * @param [type] $trip_id
 *
 * @return boolean
 */
function wte_is_trip_featured( $trip_id ) {
	if ( ! $trip_id ) {
		return false;
	}
	$featured = get_post_meta( $trip_id, 'wp_travel_engine_featured_trip', true );

	return ! empty( $featured ) && 'yes' === $featured;
}

/**
 * Get a list of featured trips id array.
 *
 * @param bool $get_all Get all featured trips.
 *
 * @return array The featured trips ids.
 * @since 6.7.3 Added $get_all parameter to get all featured trips.
 */
function wte_get_featured_trips_array( bool $get_all = false ): array {
	$featured_trips = Wp_Travel_Engine_Archive_Hooks::get_featured_trips_ids( $get_all );
	return is_array( $featured_trips ) ? $featured_trips : array();
}

/**
 * Get information about available image sizes
 */
function wte_get_image_sizes( $size = '' ) {

	global $_wp_additional_image_sizes;

	$sizes                        = array();
	$get_intermediate_image_sizes = get_intermediate_image_sizes();

	// Create the full array with sizes and crop info
	foreach ( $get_intermediate_image_sizes as $_size ) {
		if ( in_array( $_size, array( 'thumbnail', 'medium', 'medium_large', 'large' ) ) ) {
			$sizes[ $_size ]['width']  = get_option( $_size . '_size_w' );
			$sizes[ $_size ]['height'] = get_option( $_size . '_size_h' );
			$sizes[ $_size ]['crop']   = (bool) get_option( $_size . '_crop' );
		} elseif ( isset( $_wp_additional_image_sizes[ $_size ] ) ) {
			$sizes[ $_size ] = array(
				'width'  => $_wp_additional_image_sizes[ $_size ]['width'],
				'height' => $_wp_additional_image_sizes[ $_size ]['height'],
				'crop'   => $_wp_additional_image_sizes[ $_size ]['crop'],
			);
		}
	}
	// Get only 1 size if found
	if ( $size ) {
		if ( isset( $sizes[ $size ] ) ) {
			return $sizes[ $size ];
		} else {
			return false;
		}
	}

	return $sizes;
}

/**
 * Get Fallback SVG
 */
function wte_get_fallback_svg( $post_thumbnail, $dimension = false ) {
	if ( ! $post_thumbnail ) {
		return;
	}

	$image_size = array();

	if ( $dimension ) {
		$image_size['width']  = $post_thumbnail['width'];
		$image_size['height'] = $post_thumbnail['height'];
	} else {
		$image_size = wte_get_image_sizes( $post_thumbnail );
	}

	if ( $image_size ) {
		?>
		<div class="svg-holder">
			<svg class="fallback-svg"
				viewBox="0 0 <?php echo esc_attr( $image_size['width'] ); ?> <?php echo esc_attr( $image_size['height'] ); ?>"
				preserveAspectRatio="none">
				<rect width="<?php echo esc_attr( $image_size['width'] ); ?>"
						height="<?php echo esc_attr( $image_size['height'] ); ?>" style="fill:#f2f2f2;"></rect>
			</svg>
		</div>
		<?php
	}
}

/**
 * Get discount percent
 *
 * @param $trip_id
 */
function wte_get_discount_percent( $trip_id ) {
	if ( ! $trip_id ) {
		return false;
	}

	$wtetrip = wte_get_trip( $trip_id );

	if ( $wtetrip && ! $wtetrip->use_legacy_trip ) {
		return $wtetrip->sale_percentage;
	}

	$trip_price = wp_travel_engine_get_prev_price( $trip_id );
	$on_sale    = wp_travel_engine_is_trip_on_sale( $trip_id );

	if ( $trip_price != '' && $on_sale && (float) $trip_price > 0 ) {
		$sale_price       = wp_travel_engine_get_sale_price( $trip_id );
		$discount_percent = ( ( $trip_price - $sale_price ) * 100 ) / $trip_price;

		return round( $discount_percent );
	}

	return false;
}

/**
 * Send new account notification to users.
 */
function wp_travel_engine_user_new_account_created( $customer_id, $new_customer_data, $password_generated, $template ) {
	$plugin_settings       = new PluginSettings();
	$account_settings      = $plugin_settings->get( 'customer_email_notify_tabs.account_registration' );
	$is_admin_booking_edit = false;
	if ( is_admin() && isset( $_POST['wptravelengine_new_booking_nonce'] ) ) {
		$nonce_verified        = wp_verify_nonce( $_POST['wptravelengine_new_booking_nonce'], 'wptravelengine_new_booking' );
		$is_admin_booking_edit = ( false !== $nonce_verified );
	}
	if ( $is_admin_booking_edit ) {
		$send_notification = true;
	} else {
		$send_notification = wptravelengine_toggled( $account_settings['enabled'] );
	}
	if ( $send_notification && $new_customer_data ) {
		$email = new UserEmail( $customer_id );
		$email->set( 'to', $new_customer_data['user_email'] );
		$email->set( 'my_subject', $account_settings['subject'] );
		$email->set( 'content', $account_settings['content'] );
		if ( $email->send() ) {
			return true;
		}
	}
	return false;
}

add_action( 'wp_travel_engine_created_customer', 'wp_travel_engine_user_new_account_created', 20, 4 );

/**
 * Clean variables using sanitize_text_field. Arrays are cleaned recursively.
 * Non-scalar values are ignored.
 *
 * @param string|array $var Data to sanitize.
 *
 * @return string|array
 */
function wp_travel_engine_clean_vars( $var ) {
	if ( is_array( $var ) ) {
		return array_map( 'wp_travel_engine_clean_vars', $var );
	} else {
		return is_scalar( $var ) ? sanitize_text_field( $var ) : $var;
	}
}

/**
 * Add notices for WP Errors.
 *
 * @param WP_Error $errors Errors.
 */
function wp_travel_engine_add_wp_error_notices( $errors ) {
	if ( is_wp_error( $errors ) && $errors->get_error_messages() ) {
		foreach ( $errors->get_error_messages() as $error ) {
			WTE()->notices->add( $error, 'error' );
		}
	}
}

/**
 * Get the count of notices added, either for all notices (default) or for one.
 * particular notice type specified by $notice_type.
 *
 * @param string $notice_type Optional. The name of the notice type - either error, success or notice.
 *
 * @return int
 */
function wp_travel_engine_get_notice_count( $notice_type = '' ) {

	$notice_count = 0;
	$all_notices  = WTE()->notices->get( $notice_type, false );

	if ( ! empty( $all_notices ) && is_array( $all_notices ) ) {

		foreach ( $all_notices as $key => $notices ) {
			++$notice_count;
		}
	}

	return $notice_count;
}

/*
 * get term lists.
 *
 * @param [type] $id
 * @param [type] $taxonomy
 * @param string $before
 * @param string $sep
 * @param string $after
 * @return void
 */
function wte_get_the_tax_term_list( $id, $taxonomy, $before = '', $sep = '', $after = '', $nofollow = false ) {

	$terms = get_the_terms( $id, $taxonomy );

	if ( is_wp_error( $terms ) ) {
		return $terms;
	}

	if ( empty( $terms ) ) {
		return false;
	}

	$nof_attr = $nofollow ? 'rel=nofollow' : 'rel=tag';
	$target   = $nofollow ? '_blank' : '_self';

	$links = array();

	foreach ( $terms as $term ) {
		$link = get_term_link( $term, $taxonomy );
		if ( is_wp_error( $link ) ) {
			return $link;
		}
		$links[] = '<a ' . esc_attr( $nof_attr ) . ' target="' . esc_attr( $target ) . '" href="' . esc_url( $link ) . '" >' . $term->name . '</a>';
	}

	/**
	 * Filters the term links for a given taxonomy.
	 *
	 * The dynamic portion of the filter name, `$taxonomy`, refers
	 * to the taxonomy slug.
	 *
	 * @param string[] $links An array of term links.
	 *
	 * @since 2.5.0
	 */
	$term_links = apply_filters( "term_links-{$taxonomy}", $links );  // phpcs:ignore WordPress.NamingConventions.ValidHookName.UseUnderscores

	return $before . join( $sep, $term_links ) . $after;
}

function wte_get_trip_details( $trip_id ) {
	if ( ! $trip_id || get_post_type( $trip_id ) !== 'trip' ) {
		return false;
	}

	$_trip_instance   = new Trip( $trip_id );
	$_engine_settings = wptravelengine_settings();
	$wte_global       = $_engine_settings->get();
	$trip_settings    = wp_travel_engine_get_trip_metas( $trip_id );
	$code             = wp_travel_engine_get_currency_code();
	$destinations     = wte_get_the_tax_term_list( $trip_id, 'destination', '', ', ', '' );
	$destination      = '';

	if ( ! empty( $destinations ) && ! is_wp_error( $destinations ) ) {
		$destination = $destinations;
	}

	$pax = array();
	if ( wptravelengine_toggled( $trip_settings['minmax_pax_enable'] ?? false ) ) {
		if ( ! empty( $trip_settings['trip_minimum_pax'] ) ) {
			$pax[] = (int) $trip_settings['trip_minimum_pax'];
		}
		if ( ! empty( $trip_settings['trip_maximum_pax'] ) ) {
			$pax[] = (int) $trip_settings['trip_maximum_pax'];
		}
	}

	$has_map = ! empty( array_filter( $trip_settings['map'] ?? array() ) );

	$details = array(
		'engine_settings_instance'      => $_engine_settings,
		'engine_settings'               => $wte_global,
		'trip_settings'                 => $trip_settings,
		'trip_instance'                 => $_trip_instance,
		'has_date'                      => $_trip_instance->has_date(),
		'trip_id'                       => $trip_id,
		'code'                          => $code,
		'currency'                      => wp_travel_engine_get_currency_symbol( $code ),
		'trip_price'                    => wp_travel_engine_get_prev_price( $trip_id ),
		'on_sale'                       => wp_travel_engine_is_trip_on_sale( $trip_id ),
		'sale_price'                    => wp_travel_engine_get_sale_price( $trip_id ),
		'display_price'                 => wp_travel_engine_get_actual_trip_price( $trip_id ),
		'discount_percent'              => wte_get_discount_percent( $trip_id ),
		'destination'                   => $destination,
		'group_discount'                => $_trip_instance->has_group_discount(),
		'show_excerpt'                  => wptravelengine_toggled( $wte_global['show_excerpt'] ?? false ),
		'dates_layout'                  => 'months_list',
		'pax'                           => $pax,
		'trip_duration_unit'            => ! empty( $trip_settings['trip_duration_unit'] ?? '' ) ? $trip_settings['trip_duration_unit'] : 'days',
		'trip_duration'                 => '' !== ( $trip_settings['trip_duration'] ?? '' ) ? $trip_settings['trip_duration'] : false,
		'trip_duration_nights'          => ! empty( $trip_settings['trip_duration_nights'] ?? '' ) ? $trip_settings['trip_duration_nights'] : false,
		'set_duration_type'             => ! empty( $wte_global['set_duration_type'] ?? '' ) ? $wte_global['set_duration_type'] : 'days',
		'trip_difficulty_levels'        => get_option( 'difficulty_level_by_terms', array() ),
		'show_related_map'              => wptravelengine_toggled( $wte_global['show_related_map'] ?? false ) || $has_map,
		'show_related_excerpt'          => wptravelengine_toggled( $wte_global['show_related_excerpt'] ?? false ),
		'show_related_trip_carousel'    => wptravelengine_toggled( $wte_global['show_related_trip_carousel'] ?? false ),
		'show_related_wishlist'         => wptravelengine_toggled( $wte_global['show_related_wishlist'] ?? false ),
		'show_related_difficulty_tax'   => wptravelengine_toggled( $wte_global['show_related_difficulty_tax'] ?? false ),
		'show_related_trip_tags'        => wptravelengine_toggled( $wte_global['show_related_trip_tags'] ?? false ),
		'show_related_date_layout'      => wptravelengine_toggled( $wte_global['show_related_date_layout'] ?? false ),
		'show_related_available_months' => wptravelengine_toggled( $wte_global['show_related_available_months'] ?? false ),
		'show_related_available_dates'  => wptravelengine_toggled( $wte_global['show_related_available_dates'] ?? false ),
		'show_related_featured_tag'     => wptravelengine_toggled( $wte_global['show_related_featured_tag'] ?? false ),
		'show_map'                      => wptravelengine_toggled( $wte_global['show_map_on_card'] ?? false ) && $has_map,
		'show_trip_carousel'            => wptravelengine_toggled( $wte_global['display_slider_layout'] ?? false ),
		'show_wishlist'                 => wptravelengine_toggled( $wte_global['show_wishlist'] ?? false ),
		'show_difficulty_tax'           => wptravelengine_toggled( $wte_global['show_difficulty_tax'] ?? false ),
		'show_trip_tags'                => wptravelengine_toggled( $wte_global['show_trips_tag'] ?? false ),
		'show_date_layout'              => wptravelengine_toggled( $wte_global['show_date_layout'] ?? false ),
		'show_available_months'         => wptravelengine_toggled( $wte_global['show_available_months'] ?? false ),
		'show_available_dates'          => wptravelengine_toggled( $wte_global['show_available_dates'] ?? false ),
		'show_featured_tag'             => wptravelengine_toggled( $wte_global['show_featured_tag'] ?? false ),
		'related_query'                 => false,
	);

	if ( $details['show_trip_carousel'] ) {
		if ( ! has_action( 'wp_travel_engine_feat_img_trip_galleries' ) ) {
			$wpte_trip_images              = get_post_meta( get_the_ID(), 'wpte_gallery_id', true );
			$details['show_trip_carousel'] = ! has_action( 'wp_travel_engine_feat_img_trip_galleries' ) && ! empty( $wpte_trip_images );
		}
	}

	if ( wptravelengine_toggled( $wte_global['related_display_new_trip_listing'] ?? false ) ) {
		if ( $details['show_related_trip_carousel'] && ! has_action( 'wp_travel_engine_feat_img_trip_galleries' ) ) {
			$wpte_trip_images                      = get_post_meta( get_the_ID(), 'wpte_gallery_id', true );
			$details['show_related_trip_carousel'] = ! has_action( 'wp_travel_engine_feat_img_trip_galleries' ) && ! empty( $wpte_trip_images );
		}
	} else {
		$details['show_related_trip_carousel']    = false;
		$details['show_related_wishlist']         = false;
		$details['show_related_map']              = false;
		$details['show_related_excerpt']          = false;
		$details['show_related_difficulty_tax']   = false;
		$details['show_related_trip_tags']        = false;
		$details['show_related_date_layout']      = false;
		$details['show_related_available_months'] = false;
		$details['show_related_available_dates']  = false;
		$details['show_related_featured_tag']     = false;
	}

	return $details;
}

/**
 * Check if group discount is enabled or not.
 *
 * @return boolean
 */
function wte_is_group_discount_enabled( $trip_id = null ) {
	if ( ! $trip_id ) {
		return false;
	}

	$trip = wte_get_trip( $trip_id );

	if ( ! isset( $trip->post ) ) {
		return false;
	}

	if ( $trip && ! $trip->use_legacy_trip ) {
		return $trip->has_group_discount;
	}

	$trip_settings = wp_travel_engine_get_trip_metas( $trip_id );
	$wte_global    = get_option( 'wp_travel_engine_settings', true );

	if ( class_exists( 'Wp_Travel_Engine_Group_Discount' ) ) {
		$adult_gd_enable  = isset( $trip_settings['group']['discount'] ) && 1 == $trip_settings['group']['discount'] ? true : false;
		$child_gd_enable  = isset( $trip_settings['child-group']['discount'] ) && 1 == $trip_settings['child-group']['discount'] ? true : false;
		$infant_gd_enable = isset( $trip_settings['infant-group']['discount'] ) && 1 == $trip_settings['infant-group']['discount'] ? true : false;
	}

	$group_discount = class_exists( 'Wp_Travel_Engine_Group_Discount' ) && isset( $wte_global['group']['discount'] )
						&& ( $adult_gd_enable || $child_gd_enable || $infant_gd_enable ) ? true : false;

	return $group_discount;
}

/**
 * Get months array with name and code.
 *
 * @return void
 */
function wp_travel_engine_get_months_array() {
	$months = array(
		'01' => 'Jan',
		'02' => 'Feb',
		'03' => 'Mar',
		'04' => 'Apr',
		'05' => 'May',
		'06' => 'Jun',
		'07' => 'Jul',
		'08' => 'Aug',
		'09' => 'Sep',
		'10' => 'Oct',
		'11' => 'Nov',
		'12' => 'Dec',
	);

	$months = array_map(
		function ( $mon ) {
			return date_i18n( 'M', strtotime( $mon ) );
		},
		$months
	);

	return apply_filters( 'wp_travel_engine_months_array', $months );
}

/**
 * check if trip has reviews
 *
 * @param [type] $type
 * @param [type] $post_id
 *
 * @return void
 */
function wp_travel_engine_trip_has_reviews( $post_id ) {

	if ( ! $post_id || ! defined( 'WTE_TRIP_REVIEW_VERSION' ) ) {
		return false;
	}

	$comments = get_comments(
		array(
			'post_id' => $post_id,
			'count'   => true,
		)
	);

	return 0 < $comments;
}

/**
 * Format date string as per get_option( 'date_format' )
 *
 * @param [type] $date_string
 *
 * @return [string] $formated_date
 */
function wte_get_formated_date( $date_string, $format = false ) {
	if ( ! $format ) {
		$date_format = get_option( 'date_format' ) ? get_option( 'date_format' ) : 'Y m d';
	}

	if ( empty( $date_string ) ) {
		return false;
	}

	$date = strtotime( $date_string );

	return date_i18n( $date_format, $date );
}

/**
 * Format date string as per get_option( 'date_format' )
 *
 * @param [type] $date_string
 *
 * @return [string] $formated_date
 * @updated 6.2.2
 */
function wte_get_new_formated_date( $date_string, $format = false ) {
	if ( ! $format ) {
		$format = 'M d';
	}

	if ( empty( $date_string ) ) {
		return false;
	}

	$date = strtotime( $date_string );

	return date_i18n( $format, $date );
}

/**
 * Get Human readable Time diff / Date with default date format.
 *
 * @param int $post_id Post ID.
 *
 * @return string
 */
function wte_get_human_readable_diff_post_published_date( int $post_id ): string {
	if ( ! $post_id ) {
		return '&ndash;';
	}

	$timestamp = get_post_time( $format = 'U', $gmt = false, $post_id, $translate = false ) ? get_post_time( $format = 'U', $gmt = false, $post_id, $translate = false ) : '';

	// Check if the order was created within the last 24 hours, and not in the future.
	if ( $timestamp > strtotime( '-1 day', time() ) && $timestamp <= time() ) {
		$show_date = sprintf(
		/* translators: %s: human-readable time difference */
			_x( '%s ago', '%s = human-readable time difference', 'wp-travel-engine' ),
			human_time_diff( $timestamp, time() )
		);
	} else {
		$show_date = get_the_date( get_option( 'date_format' ), $post_id );
	}

	return sprintf(
		'<time datetime="%1$s">%2$s</time>',
		esc_attr( get_the_date( 'c', $post_id ) ),
		esc_html( $show_date )
	);
}

/**
 * Calculates trip date
 *
 * @param int $post_id
 *
 * @return string Trip Booked Date.
 * @since 5.8.2
 */
function wptravelengine_trip_date( int $post_id ): string {
	$order_trips = get_post_meta( $post_id, 'order_trips', true );
	$key_names   = is_array( $order_trips ) ? array_keys( $order_trips ) : array();
	if ( count( $key_names ) > 0 ) :
		$datetime = $order_trips[ $key_names[0] ]['datetime'] ?? '';
		if ( $datetime ) :
			$formatted_date = date_i18n( get_option( 'date_format' ), strtotime( $datetime ) );

			return sprintf(
				'<time datetime="%1$s">%2$s</time>',
				esc_attr( $key_names[0] ),
				esc_html( $formatted_date )
			);
		endif;
	endif;

	return '&ndash;';
}

/**
 * Trip cutoff array.
 *
 * @param integer $post_id
 *
 * @return array
 */
function wpte_get_booking_cutoff( int $post_id ): array {

	$cutoff_array = array(
		'enable' => false,
		'cutoff' => 0,
		'unit'   => 'days',
	);

	if ( ! $post_id ) {
		return $cutoff_array;
	}

	$post_metas = get_post_meta( $post_id, 'wp_travel_engine_setting', true );

	if ( empty( $post_metas ) || ! isset( $post_metas['trip_cutoff_enable'] ) ) {
		return $cutoff_array;
	}

	$cutoff_array = array(
		'enable' => true,
		'cutoff' => $post_metas['trip_cut_off_time'] ?? 0,
		'unit'   => $post_metas['trip_cut_off_unit'] ?? 'days',
	);

	return $cutoff_array;
}

/**
 * Get custom tabs array.
 *
 * @param array $trip_meta_tabs
 *
 * @return array
 * @since 6.7.7 Updated the response to new version array
 */
function wpte_add_custom_tabs_to_trip_meta( array $trip_meta_tabs ): array {
	// $default_tabs = wte_get_default_settings_tab();
	$settings = get_option( 'wp_travel_engine_settings', array() );

	$def_tabs = array(
		'2' => 'itinerary',
		'3' => 'cost',
		'4' => 'dates',
		'5' => 'faqs',
		'6' => 'map',
	);

	if ( empty( $settings ) || ! isset( $settings['trip_tabs']['id'] ) ) {
		return $trip_meta_tabs;
	}

	$custom_tabs = array();
	foreach ( $settings['trip_tabs']['id'] as $key => $value ) {

		$field = $settings['trip_tabs']['field'][ $value ];

		if ( '1' == $value || in_array( $field, $def_tabs ) || 'review' === $field ) {
			continue;
		}

		$tab_label = ! empty( $settings['trip_tabs']['name'][ $value ] ?? '' ) ? $settings['trip_tabs']['name'][ $value ] : __( 'Custom Tab', 'wp-travel-engine' );

		$custom_tabs[] = array(
			'id'     => 'wp_editor_tab_' . $value,
			'title'  => $tab_label,
			'fields' => array(
				array(
					'label'   => __( 'Section Title', 'wp-travel-engine' ),
					'divider' => true,
					'field'   => array(
						'name'        => 'custom_tabs.tab_' . $key . '.title',
						'type'        => 'TEXT',
						'placeholder' => __( 'Title', 'wp-travel-engine' ),
					),
				),
				array(
					'label'   => __( 'Tab Content', 'wp-travel-engine' ),
					'divider' => true,
					'field'   => array(
						'name' => 'custom_tabs.tab_' . $key . '.content',
						'type' => 'EDITOR',
					),
				),
			),
		);
	}

	if ( ! empty( $custom_tabs ) ) {
		$trip_meta_tabs['wpte-custom-tabs'] = array(
			'tab_label'   => __( 'Custom Tabs', 'wp-travel-engine' ),
			'tab_heading' => __( 'Custom Tabs', 'wp-travel-engine' ),
			'content_key' => 'wpte-custom-tabs',
			'is_custom'   => true,
			'priority'    => 80,
			'icon'        => 'tool',
			'fields'      => array(
				array(
					'field' => array(
						'type' => 'TAB',
						'tabs' => $custom_tabs,
					),
				),
			),
		);
	}

	return $trip_meta_tabs;
}

/**
 * Get Booking Status List.
 *
 * @since 1.0.5
 */
function wp_travel_engine_get_booking_status() {
	$status = array(
		'pending'  => array(
			'color' => '#FF9800',
			'text'  => __( 'Pending', 'wp-travel-engine' ),
		),
		'booked'   => array(
			'color' => '#008600',
			'text'  => __( 'Booked', 'wp-travel-engine' ),
		),
		'refunded' => array(
			'color' => '#FE450E',
			'text'  => __( 'Refunded', 'wp-travel-engine' ),
		),
		'canceled' => array(
			'color' => '#FE450E',
			'text'  => __( 'Canceled', 'wp-travel-engine' ),
		),
		'failed'   => array(
			'color' => '#FE450E',
			'text'  => __( 'Failed', 'wp-travel-engine' ),
		),
		'N/A'      => array(
			'color' => '#892E2C',
			'text'  => __( 'N/A', 'wp-travel-engine' ),
		),
	);

	return apply_filters( 'wp_travel_engine_booking_status_list', $status );
}

/**
 * Get all Booking IDs that have the given trip ID.
 *
 * @param int $trip_id Trip ID
 * @since 6.3.5
 *
 * @return array
 */
function wptravelengine_get_booking_ids( $trip_id ) {
	global $wpdb;

	// Get all bookings with cart_info.
	$booking_list = $wpdb->get_col(
		$wpdb->prepare(
			"
        SELECT DISTINCT p.ID
        FROM {$wpdb->posts} p
        JOIN {$wpdb->postmeta} pm ON p.ID = pm.post_id
        WHERE p.post_type = %s
        AND pm.meta_key = %s
    ",
			'booking',
			'cart_info'
		)
	);

	$filtered_bookings = array();

	if ( ! empty( $booking_list ) ) {
		foreach ( $booking_list as $booking_id ) {
			$cart_info = get_post_meta( $booking_id, 'cart_info', true );
			if ( ! empty( $cart_info ) ) {
				$cart_data = maybe_unserialize( $cart_info );
				if ( is_array( $cart_data ) && isset( $cart_data['items'] ) && is_array( $cart_data['items'] ) ) {
					foreach ( $cart_data['items'] as $item ) {
						if ( is_array( $item ) && isset( $item['trip_id'] ) && intval( $item['trip_id'] ) === intval( $trip_id ) ) {
							$filtered_bookings[] = $booking_id;
							break;
						}
					}
				}
			}
		}
	}

	// Fallback: Try using order_trips if cart_info doesn't have the data
	if ( empty( $filtered_bookings ) ) {
		$bookings = array_reduce(
			get_posts(
				array(
					'post_type'      => 'booking',
					'posts_per_page' => -1,
				)
			),
			function ( $result, $item ) use ( $trip_id ) {
				$order_trips = $item->order_trips ?? array();
				if ( ! empty( $order_trips ) ) {
					$order_trip    = (object) array_shift( $order_trips );
					$order_trip_id = $order_trip->ID ?? null;

					if ( $order_trip_id !== null && intval( $order_trip_id ) === intval( $trip_id ) ) {
						$result[] = $item->ID;
					}
				}
				return $result;
			},
			array()
		);

		$filtered_bookings = $bookings;
	}

	return apply_filters( 'wp_travel_engine_booking_ids_list', $filtered_bookings );
}

/**
 * Check if currency is supported by the Paypal Gateway
 * Currently supports 26 currencies
 *
 * @param [type] $currency
 *
 * @return void
 */
function wp_travel_engine_paypal_supported_currencies( $currency ) {
	if ( ! $currency ) {
		return;
	}
	$settings                  = get_option( 'wp_travel_engine_settings' );
	$currency_option           = isset( $settings['currency_option'] ) && $settings['currency_option'] != '' ? esc_attr( $settings['currency_option'] ) : 'symbol';
	$supported_paypal_currency = apply_filters(
		'wp_travel_engine_filter_paypal_supported_currencies',
		array(
			'AUD' => '&#36;', // Australian Dollar
			'BRL' => '&#82;&#36;', // Brazilian real
			'CAD' => '&#36;', // Canadian dollar
			'CNY' => '&yen;', // Chinese Renmenbi
			'CZK' => '&#75;&#269;', // Czech koruna
			'DKK' => 'DKK', // Danish krone
			'EUR' => '&euro;', // Euro
			'HKD' => '&#36;', // Hong Kong dollar
			'HUF' => '&#70;&#116;', // Hungarian forint
			'INR' => '&#8377;', // Indian rupee
			'ILS' => '&#8362;', // Israeli
			'JPY' => '&yen;', // Japanese yen
			'MYR' => '&#82;&#77;', // Malaysian ringgit
			'MXN' => '&#36;', // Mexican peso
			'TWD' => '&#78;&#84;&#36;', // New Taiwan dollar
			'NZD' => '&#36;', // New Zealand dollar
			'NOK' => '&#107;&#114;', // Norwegian krone
			'PHP' => '&#8369;', // Philippine peso
			'PLN' => '&#122;&#322;', // Polish złoty
			'GBP' => '&pound;', // Pound sterling
			'RUB' => '&#8381;', // Russian ruble
			'SGD' => '&#36;', // Singapore dollar
			'SEK' => '&#107;&#114;', // Swedish krona
			'CHF' => '&#67;&#72;&#70;', // New Zealand dollar
			'THB' => '&#3647;', // Thai baht
			'USD' => '&#36;', // United States dollar
		)
	);

	// if ( isset( $currency_option ) && $currency_option == 'code'):
	// $return = array_key_exists($currency, $supported_paypal_currency)?true:false;
	// else:
	// $obj    = \wte_functions();
	// $return = $obj->in_multi_array($currency, $supported_paypal_currency);
	// endif;
	$return = array_key_exists( $currency, $supported_paypal_currency ) ? true : false;

	return $return;
}

/**
 *
 * since 5.5.7
 */
function wptravelengine_get_trip_facts_default_options() {

	$trip_facts = array(
		'minimum-age'  => array(
			'field_id'          => __( 'Minimum Age', 'wp-travel-engine' ),
			'field_icon'        => 'fas fa-child',
			'field_type'        => 'minimum-age',
			'dynamic'           => 'yes',
			'input_placeholder' => __( 'Minimum Age from Trip Settings', 'wp-travel-engine' ),
			'enabled'           => 'yes',
		),
		'maximum-age'  => array(
			'field_id'          => __( 'Maximum Age', 'wp-travel-engine' ),
			'field_icon'        => 'fas fa-male',
			'field_type'        => 'maximum-age',
			'dynamic'           => 'yes',
			'input_placeholder' => __( 'Maximum Age from Trip Settings', 'wp-travel-engine' ),
			'enabled'           => 'yes',
		),
		'group-size'   => array(
			'field_id'          => 'Group Size',
			'field_icon'        => 'fas fa-user-group',
			'field_type'        => 'group-size',
			'dynamic'           => 'yes',
			'input_placeholder' => __( 'Minimum and Maximum praticipants from Trip Settings', 'wp-travel-engine' ),
			'enabled'           => 'no',
		),
		'difficulties' => array(
			'field_id'          => 'Difficulties',
			'field_icon'        => 'fas fa-shoe-prints',
			'field_type'        => 'difficulties',
			'dynamic'           => 'yes',
			'input_placeholder' => __( 'Trip Difficulty', 'wp-travel-engine' ),
			'enabled'           => 'no',
		),
	);

	$taxonomies = wptravelengine_get_trip_taxonomies( 'objects' );

	if ( is_array( $taxonomies ) ) {
		foreach ( $taxonomies as $taxonomy ) {
			$trip_facts[ $taxonomy->name ] = array(
				'field_id'          => $taxonomy->label,
				'field_icon'        => 'fas fa-person-hiking',
				'field_type'        => 'taxonomy:' . $taxonomy->name,
				'permanent'         => 'yes',
				'input_placeholder' => sprintf( __( 'List of %s', 'wp-travel-engine' ), $taxonomy->label ),
				'enabled'           => 'no',
			);
		}
	}

	$settings = get_option( 'wp_travel_engine_settings', array() );

	if ( isset( $settings['default_trip_facts'] ) && is_array( $settings['default_trip_facts'] ) ) {
		return wp_parse_args( $settings['default_trip_facts'], $trip_facts );
	}

	return $trip_facts;
}

/**
 *
 * @since 5.5.7
 */
function wptravelengine_get_trip_facts_custom_options() {
	$trip_facts = array(
		'accomodation'       => array(
			'fid'               => 1,
			'field_id'          => 'Accomodation',
			'field_icon'        => 'fas fa-hotel',
			'field_type'        => 'text',
			'input_placeholder' => '5 Star Hotel',
		),
		'admission-fee'      => array(
			'fid'               => 2,
			'field_id'          => 'Admission Fee',
			'field_icon'        => 'fas fa-tag',
			'field_type'        => 'text',
			'input_placeholder' => 'No',
		),
		'arrival-city'       => array(
			'fid'               => 3,
			'field_id'          => 'Arrival City',
			'field_icon'        => 'fas fa-city',
			'field_type'        => 'text',
			'input_placeholder' => 'Pokhara',
		),
		'best-season'        => array(
			'fid'               => 4,
			'field_id'          => 'Best Season',
			'field_icon'        => 'fas fa-cloud-sun',
			'field_type'        => 'text',
			'input_placeholder' => 'Autumn',
		),
		'departure-city'     => array(
			'fid'               => 5,
			'field_id'          => 'Departure City',
			'field_icon'        => 'fas fa-sun-plant-wilt',
			'field_type'        => 'text',
			'input_placeholder' => 'Kathmandu',
		),
		'free-cancellation'  => array(
			'fid'               => 6,
			'field_id'          => 'Free Cancellation',
			'field_icon'        => 'far fa-calendar-xmark',
			'field_type'        => 'text',
			'input_placeholder' => 'Yes',
		),
		'guide'              => array(
			'fid'               => 7,
			'field_id'          => 'Guide',
			'field_icon'        => 'fas fa-hands-praying',
			'field_type'        => 'text',
			'input_placeholder' => 'Guided',
		),
		'hotel-transfer'     => array(
			'fid'               => 8,
			'field_id'          => 'Hotel Transfer',
			'field_icon'        => 'fas fa-hotel',
			'field_type'        => 'text',
			'input_placeholder' => 'Available',
		),
		'insurance-coverage' => array(
			'fid'               => 9,
			'field_id'          => 'Insurance Coverage',
			'field_icon'        => 'fas fa-hospital-user',
			'field_type'        => 'text',
			'input_placeholder' => 'Covered 80%',
		),
		'language'           => array(
			'fid'               => 10,
			'field_id'          => 'Language',
			'field_icon'        => 'fas fa-language',
			'field_type'        => 'text',
			'input_placeholder' => 'English, Deutsch',
		),
		'maximum-altitude'   => array(
			'fid'               => 11,
			'field_id'          => 'Maximum Altitude',
			'field_icon'        => 'fas fa-mountain',
			'field_type'        => 'text',
			'input_placeholder' => '8848m',
		),
		'meals'              => array(
			'fid'               => 12,
			'field_id'          => 'Meals',
			'field_icon'        => 'fas fa-bowl-food',
			'field_type'        => 'text',
			'input_placeholder' => 'Breakfast and Dinner',
		),
		'meeting-point'      => array(
			'fid'               => 13,
			'field_id'          => 'Meeting Point',
			'field_icon'        => 'fas fa-handshake-simple',
			'field_type'        => 'text',
			'input_placeholder' => 'Hotel',
		),
		'mineral-water'      => array(
			'fid'               => 14,
			'field_id'          => 'Mineral Water',
			'field_icon'        => 'fas fa-bottle-droplet',
			'field_type'        => 'text',
			'input_placeholder' => 'Available',
		),
		'payment-method'     => array(
			'fid'               => 15,
			'field_id'          => 'Payment Method',
			'field_icon'        => 'fab fa-cc-mastercard',
			'field_type'        => 'text',
			'input_placeholder' => 'VISA, Master Card',
		),
		'tour-availability'  => array(
			'fid'               => 16,
			'field_id'          => 'Tour Availability',
			'field_icon'        => 'fas fa-person-hiking',
			'field_type'        => 'text',
			'input_placeholder' => 'Available',
		),
		'transportation'     => array(
			'fid'               => 17,
			'field_id'          => 'Transportation',
			'field_icon'        => 'fas fa-bus',
			'field_type'        => 'text',
			'input_placeholder' => 'Bus, Taxi',
		),
		'walking-hours'      => array(
			'fid'               => 18,
			'field_id'          => 'Walking Hours',
			'field_icon'        => 'far fa-clock',
			'field_type'        => 'text',
			'input_placeholder' => '5-6 Hours',
		),
		'wifi'               => array(
			'fid'               => 19,
			'field_id'          => 'Wifi',
			'field_icon'        => 'fas fa-wifi',
			'field_type'        => 'text',
			'input_placeholder' => 'Available',
		),
	);

	return $trip_facts;
}

/**
 * Provides Trip Infos.
 *
 * @since 5.5.7
 */
function wptravelengine_get_trip_facts_options() {

	$settings = get_option( 'wp_travel_engine_settings', array() );

	if ( isset( $settings['trip_facts'] ) && is_array( $settings['trip_facts'] ) ) {
		return $settings['trip_facts'];
	}

	$trip_facts = wptravelengine_get_trip_facts_custom_options();

	$facts = array();
	$index = 1;
	foreach ( $trip_facts as $_id => $_args ) {
		foreach (
			array(
				'fid',
				'field_id',
				'field_icon',
				'field_type',
				'input_placeholder',
				'deleteable',
				'enabled',
			) as $key
		) {
			if ( 'fid' === $key ) {
				$facts[ $key ][ $index ] = $index;
				continue;
			}
			if ( 'deleteable' === $key ) {
				$facts[ $key ][ $index ] = isset( $_args[ $key ] ) ? $_args[ $key ] : 'yes';
				continue;
			}
			if ( 'enabled' === $key ) {
				$facts[ $key ][ $index ] = isset( $_args[ $key ] ) ? $_args[ $key ] : 'yes';
				continue;
			}
			$facts[ $key ][ $index ] = isset( $_args[ $key ] ) ? $_args[ $key ] : '';
		}
		++$index;
	}

	return $facts;
}

/**
 * Formats the duration of a trip.
 *
 * @param string $field_value The duration value to be formatted.
 * @param array  $trip_settings The settings of the trip, including the unit of duration.
 *
 * @return string The formatted duration.
 */
function wptravelengine_format_duration( $field_value, $trip_settings ) {
	if ( ! preg_match( '/([^\d]+)/', trim( $field_value ) ) ) {
		$duration_type = isset( $trip_settings['trip_duration_unit'] ) && in_array(
			$trip_settings['trip_duration_unit'],
			array(
				'days',
				'hours',
			),
			true
		) ? $trip_settings['trip_duration_unit'] : 'days';
		if ( 'hours' === $duration_type ) {
			$field_value = sprintf(
				_n( '%d Hour', '%d Hours', (int) $field_value, 'wp-travel-engine' ),
				(int) $field_value
			);
		} else {
			$field_value = sprintf(
				_n( '%d Day', '%d Days', (int) $field_value, 'wp-travel-engine' ),
				(int) $field_value
			);
		}
	}

	return $field_value;
}

/**
 * Retrieves the terms associated with a trip for a given taxonomy.
 *
 * @param int    $trip_id The ID of the trip.
 * @param string $taxonomy The taxonomy to retrieve the terms from.
 *
 * @return string A string containing HTML links to the terms.
 */
function wptravelengine_trip_terms( int $trip_id, string $taxonomy ): string {
	$terms = get_the_terms( $trip_id, $taxonomy );
	$value = array();
	if ( is_array( $terms ) ) {
		foreach ( $terms as $term ) {
			$value[] = sprintf( '<a href="%s">%s</a>', get_term_link( $term, $taxonomy ), $term->name );
		}
	}

	return implode( ', ', $value );
}

/**
 * Retrieves the facts about a trip.
 *
 * @param int   $trip_id The ID of the trip.
 * @param array $trip_settings The settings of the trip.
 *
 * @return array An associative array containing the facts about the trip.
 */
function wptravelengine_trip_facts_value( $trip_id, $trip_settings ) {
	return array(
		'minimum-age'  => array(
			'value'     => function () use ( $trip_id ) {
				return get_post_meta( $trip_id, 'wp_travel_engine_trip_min_age', true );
			},
			'condition' => isset( $trip_settings['min_max_age_enable'] ) && 'true' === $trip_settings['min_max_age_enable'],
		),
		'maximum-age'  => array(
			'value'     => function () use ( $trip_id ) {
				return get_post_meta( $trip_id, 'wp_travel_engine_trip_max_age', true );
			},
			'condition' => isset( $trip_settings['min_max_age_enable'] ) && 'true' === $trip_settings['min_max_age_enable'],
		),
		'group-size'   => array(
			'value'     => function () use ( $trip_settings ) {
				$group_size = array_filter(
					array(
						! empty( $trip_settings['trip_minimum_pax'] ) ? (int) $trip_settings['trip_minimum_pax'] : null,
						! empty( $trip_settings['trip_maximum_pax'] ) ? (int) $trip_settings['trip_maximum_pax'] : null,
					)
				);

				return ! empty( $group_size ) ? implode( ' - ', $group_size ) : '';
			},
			'condition' => isset( $trip_settings['minmax_pax_enable'] ) && 'true' === $trip_settings['minmax_pax_enable'],
		),
		'difficulties' => array(
			'value' => function () use ( $trip_settings ) {
				$difficulty_level = isset( $trip_settings['difficulty_level'] ) ? $trip_settings['difficulty_level'] : '';
				$difficulty_term  = get_term( (int) $difficulty_level, 'difficulty' );

				return $difficulty_term instanceof \WP_Term ? $difficulty_term->name : '';
			},
		),
	);
}

/**
 * @param $template
 * @param array $args
 *
 * @return void
 * @since 6.2.1
 */
function wptravelengine_view( $template, array $args = array() ) {
	$template = apply_filters( 'wptravelengine_view_template_path', $template );

	if ( ! file_exists( $template ) ) {
		return;
	}

	if ( ! empty( $args ) && is_array( $args ) ) {
		extract( $args );
	}

	include $template;
}

/**
 * Get active theme version.
 *
 * @param string $theme_name Name of the theme
 *
 * @return boolean|string
 * @since 6.3.3
 */
function wptravelengine_get_active_theme_version( $theme_name ) {
	$theme = wp_get_theme(); // gets the current theme
	if ( $theme_name == $theme->name ) {
		return $theme->version ?? false;
	}
	if ( $theme_name == $theme->parent_theme ) {
		return $theme->parent()->version ?? false;
	}
	return false;
}

/**
 * Check if the active theme is compatible with new WPTE banner
 *
 * @param $theme_name Name of the theme
 *
 * @return boolean
 * @since 6.3.3
 */
function wptravelengine_revert_to_old_banner( $theme_name ) {

	$conditions = array(
		'Travel Monster'  => '1.2.5',
		'Travel Muni'     => '1.1.4',
		'Travel Muni Pro' => '2.1.2',
	);

	$theme_version = wptravelengine_get_active_theme_version( $theme_name ) ?? false;

	if ( $theme_version && version_compare( $theme_version, $conditions[ $theme_name ], '<' ) ) {
		return true;
	}

	return false;
}

/**
 * If booking reference is set, redirect to thank you page instead of redirecting to the travellers information page.
 * This is to avoid redirecting to the travellers information page when the user is trying to pay for due amount.
 * This supports backward compatibility with old travellers information page.
 *
 * @param string $booking_ref The booking reference.
 * @param string $payment_key The payment key.
 *
 * @since 6.5.0
 * @return void
 */
function wptravelengine_redirect_to_thank_you_page( $booking_ref, $payment_key ) {
	if ( ! $booking_ref || ! $payment_key ) {
		return;
	}

	$wptravelengine_settings = get_option( 'wp_travel_engine_settings', array() );
	$thank_you_page_id       = wte_array_get( $wptravelengine_settings, 'pages.wp_travel_engine_thank_you', '' );
	$thank_you_page_url      = $thank_you_page_id ? get_permalink( $thank_you_page_id ) : esc_url( home_url( '/' ) );

	if ( empty( $thank_you_page_url ) ) {
		return;
	}

	wp_safe_redirect( add_query_arg( 'payment_key', $payment_key, $thank_you_page_url ) );
	exit;
}

/**
 * Get pricing type.
 *
 * @param bool   $all Whether to get all pricing types.
 * @param string $key The type of pricing to get.
 * @return array
 * @since v6.6.4
 * @updated 6.6.10
 */
function wptravelengine_get_pricing_type( $all = false, $key = 'per-person' ) {
	$defaults = array(
		'per-person' => array(
			'label'       => __( 'Person', 'wp-travel-engine' ),
			'description' => '',
		),
		'per-group'  => array(
			'label'       => __( 'Group', 'wp-travel-engine' ),
			'description' => '',
		),
	);

	$pricing_type = Options::get( 'wptravelengine_pricing_type', $defaults );

	$pricing_types = apply_filters( 'wptravelengine-packages-labels', $pricing_type );

	return $all ? $pricing_types : ( $pricing_types[ $key ] ?? $pricing_types['per-person'] );
}

/**
 * Get enquiry form field map (name => type, field_label) and validation-only types.
 *
 * Used when displaying enquiry data so validation-only fields (e.g. recaptcha) can be
 * hidden. Validation-only types are filterable via wptravelengine_enquiry_validation_only_field_types.
 *
 * Results are cached per request and per package_id to avoid repeated work when the
 * same map is needed multiple times (e.g. enquiry meta, emails, previews).
 *
 * @param int $package_id Trip/package ID; use 0 for default form fields.
 * @return array{field_map: array, validation_only_types: array}
 * @since 6.7.6
 */
function wptravelengine_get_enquiry_form_field_map( $package_id = 0 ) {
	static $cache = array();
	$cache_key    = (int) $package_id;
	if ( isset( $cache[ $cache_key ] ) ) {
		return $cache[ $cache_key ];
	}

	if ( ! class_exists( 'WP_Travel_Engine_Enquiry_Form_Shortcodes' ) ) {
		$result              = array(
			'field_map'             => array(),
			'validation_only_types' => array_map( 'strtolower', (array) apply_filters( 'wptravelengine_enquiry_validation_only_field_types', array( 'recaptcha', 'captcha', 'grecaptcha_v2', 'grecaptcha_v3' ) ) ),
		);
		$cache[ $cache_key ] = $result;
		return $result;
	}

	$form_fields = WP_Travel_Engine_Enquiry_Form_Shortcodes::get_enquiry_form_fields( $package_id, array( 'shortcode' => true ) );
	if ( ! is_array( $form_fields ) ) {
		$form_fields = array();
	}
	$field_map = array();

	$validation_only_types = apply_filters(
		'wptravelengine_enquiry_validation_only_field_types',
		array( 'recaptcha', 'captcha', 'grecaptcha_v2', 'grecaptcha_v3' )
	);
	$validation_only_types = array_map( 'strtolower', (array) $validation_only_types );

	foreach ( $form_fields as $field ) {
		if ( ! empty( $field['name'] ) ) {
			$field_map[ $field['name'] ] = array(
				'type'        => isset( $field['type'] ) ? strtolower( (string) $field['type'] ) : '',
				'field_label' => isset( $field['field_label'] ) ? $field['field_label'] : '',
			);
		}
	}

	$result              = array(
		'field_map'             => $field_map,
		'validation_only_types' => $validation_only_types,
	);
	$cache[ $cache_key ] = $result;
	return $result;
}

/**
 * Whether an enquiry form field should be hidden from display.
 *
 * Returns true when the field type is validation-only (e.g. recaptcha, captcha).
 * Used so these values are not shown in enquiry meta, emails, or previews.
 *
 * @param string $key                   Field name (key in enquiry formdata).
 * @param array  $field_map             From wptravelengine_get_enquiry_form_field_map()['field_map'].
 * @param array  $validation_only_types From wptravelengine_get_enquiry_form_field_map()['validation_only_types'].
 * @return bool True if the field should be hidden from display.
 * @since 6.7.6
 */
function wptravelengine_enquiry_should_hide_field( $key, array $field_map, array $validation_only_types ) {
	if ( isset( $field_map[ $key ]['type'] ) && in_array( $field_map[ $key ]['type'], $validation_only_types, true ) ) {
		return true;
	}
	return false;
}

/**
 * Get display label for an enquiry form field key.
 *
 * Uses, in order: special case for package_name, then field_label from the field map,
 * then fallback to wp_travel_engine_get_enquiry_field_label_by_name() for legacy/custom keys.
 *
 * @param string $key       Field name (key in enquiry formdata).
 * @param array  $field_map From wptravelengine_get_enquiry_form_field_map()['field_map'].
 * @return string Label for display.
 * @since 6.7.6
 */
function wptravelengine_enquiry_get_field_display_label( $key, array $field_map ) {
	if ( 'package_name' === $key ) {
		return esc_html__( 'Package Name', 'wp-travel-engine' );
	}
	if ( isset( $field_map[ $key ]['field_label'] ) && $field_map[ $key ]['field_label'] !== '' ) {
		return $field_map[ $key ]['field_label'];
	}
	return wp_travel_engine_get_enquiry_field_label_by_name( $key );
}

