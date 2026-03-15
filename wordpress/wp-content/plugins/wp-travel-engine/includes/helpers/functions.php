<?php
/**
 * WTE Helper functions.
 */
use Elementor\Plugin;
use Firebase\JWT\JWT;
use WPTravelEngine\Email;
use WPTravelEngine\Booking\Email\Template_Tags;
use WPTravelEngine\Builders\FormFields\FormField;
use WPTravelEngine\Core\Cart\Cart;
use WPTravelEngine\Core\Functions;
use WPTravelEngine\Core\Models\Post\Booking;
use WPTravelEngine\Core\Models\Post\Payment;
use WPTravelEngine\Core\Models\Post\Trip;
use WPTravelEngine\Core\Models\Settings\PluginSettings;
use WPTravelEngine\Helpers\Functions as HelperFunctions;
require_once __DIR__ . '/tax.php';
require_once __DIR__ . '/helpers-analytics.php';
require_once __DIR__ . '/helpers-prices.php';
require_once __DIR__ . '/wp-travel-engine-form-fields.php';
require_once __DIR__ . '/cart.php';

/**
 * Defined successful payment status.
 *
 * @return array
 * @since 6.7.0
 */
function wptravelengine_success_payment_status() {
	$options = array(
		'completed'        => __( 'Completed', 'wp-travel-engine' ),
		'complete'         => __( 'Completed', 'wp-travel-engine' ),
		'captured'         => __( 'Captured', 'wp-travel-engine' ),
		'capture'          => __( 'Captured', 'wp-travel-engine' ),
		'check-received'   => __( 'Check Received', 'wp-travel-engine' ),
		'partially-paid'   => __( 'Partially Paid', 'wp-travel-engine' ),
		'settlement'       => __( 'Settlement', 'wp-travel-engine' ),
		'success'          => __( 'Success', 'wp-travel-engine' ),
		'voucher-received' => __( 'Voucher Received', 'wp-travel-engine' ),
	);

	$options = apply_filters( 'wptravelengine_success_payment_status_options', $options );

	return $options;
}

/**
 * Defined pending payment status.
 *
 * @return array
 * @since 6.7.0
 */
function wptravelengine_pending_payment_status() {
	$options = array(
		'check-waiting'    => __( 'Waiting for Check', 'wp-travel-engine' ),
		'pending'          => __( 'Pending', 'wp-travel-engine' ),
		'voucher-waiting'  => __( 'Waiting for Voucher', 'wp-travel-engine' ),
		'voucher-awaiting' => __( 'Waiting for Voucher', 'wp-travel-engine' ),
	);

	$options = apply_filters( 'wptravelengine_pending_payment_status_options', $options );

	return $options;
}

/**
 * Defined failed payment status.
 *
 * @return array
 * @since 6.7.0
 */
function wptravelengine_failed_payment_status() {
	$options = array(
		'abandoned' => __( 'Abandoned', 'wp-travel-engine' ),
		'cancelled' => __( 'Cancelled', 'wp-travel-engine' ),
		'cancel'    => __( 'Cancelled', 'wp-travel-engine' ),
		'failed'    => __( 'Failed', 'wp-travel-engine' ),
		'deny'      => __( 'Denied', 'wp-travel-engine' ),
		'expire'    => __( 'Expired', 'wp-travel-engine' ),
		'revoked'   => __( 'Revoked', 'wp-travel-engine' ),
	);

	$options = apply_filters( 'wptravelengine_failed_payment_status_options', $options );

	return $options;
}

/**
 * Get trip booking data.
 *
 * @param int|string $trip_id Trip ID.
 *
 * @return array
 * @since 6.7.0
 */
function wptravelengine_trip_booking_modal_data( $trip_id ) {
	global $wte_cart;
	$global_settings = wptravelengine_settings();

	return apply_filters(
		'wptravelengine_trip_booking_modal_data',
		array(
			'tripID'              => $trip_id,
			'nonce'               => wp_create_nonce( 'wte_add_trip_to_cart' ),
			'wpXHR'               => esc_url_raw( admin_url( 'admin-ajax.php' ) ),
			'cartVersion'         => $wte_cart->version,
			'buttonLabel'         => esc_html__( 'Check Availability', 'wp-travel-engine' ),
			'showModalWarning'    => wptravelengine_toggled( $global_settings->get( 'show_booking_modal_warning', true ) ),
			'modalWarningMessage' => $global_settings->get( 'booking_modal_warning_message', '' ),
		)
	);
}

/**
 * Check if the user is new user.
 *
 * @since 6.6.7
 */
function wptravelengine_is_new_user(): bool {
	return WP_TRAVEL_ENGINE_VERSION === ( get_option( 'wptravelengine_since' ) ?: '0.0.0' );
}

/**
 * Normalize a numeric value by casting it to a specific type.
 *
 * Checks if the given value is numeric, and if so, casts it to the specified type.
 * If the value is not numeric, it is returned unchanged.
 *
 * @param mixed  $value The input value to normalize.
 * @param string $type  The target numeric type: 'int' or 'float'. Default is 'int'.
 *
 * @return mixed The normalized numeric value as int or float, or the original value if not numeric.
 * @since 6.6.7
 */
function wptravelengine_normalize_numeric_val( $value, $type = 'int' ) {
	if ( ! is_numeric( $value ) ) {
		return $value;
	}

	switch ( $type ) :
		case 'float':
			return floatval( $value );
		case 'int':
		default:
			return intval( $value );
	endswitch;
}

/**
 * Get the trip duration in array format.
 *
 * @param int|Trip $trip Trip ID or Trip instance.
 * @param string   $set_duration_type Set duration type. Accepts: 'days', 'nights', 'both'.
 *
 * @return array Trip duration in array format.
 *
 * @since 6.6.0
 */
function wptravelengine_get_trip_duration_arr( $trip, string $set_duration_type = 'both' ): array {

	$trip = wptravelengine_get_trip( $trip );

	if ( ! $trip ) {
		return array();
	}

	$trip_duration = (int) $trip->get_trip_duration();
	$trip_type     = $trip->get_trip_type();

	$duration_label = array();
	if ( $trip_duration && in_array( $set_duration_type, array( 'both', 'days' ) ) ) {
		$duration_label[] = sprintf( __( '%1$d %2$s', 'wp-travel-engine' ), $trip_duration, wptravelengine_get_label_by_slug( $trip->get_trip_duration_unit(), $trip_duration ) );
	}

	$trip_duration_unit = $trip->get_trip_duration_unit();
	if ( 'days' === $trip_duration_unit && 'multi' === $trip_type && in_array( $set_duration_type, array( 'both', 'nights' ) ) ) {
		$nights = $trip->get_setting( 'trip_duration_nights' );
		if ( $nights ) {
			$duration_label[] = $trip->get_trip_nights();
		}
	}

	return apply_filters( 'wptravelengine_trip_duration_arr', $duration_label, $trip, $set_duration_type );
}

/**
 * Get the label of provided slug and count.
 *
 * @param string     $slug Slug of the label to get.
 * @param int|string $count Count of the label to get.
 *
 * @return string Label of the provided slug.
 * @since 6.4.1
 * @updated 6.5.2
 * @since 6.7.3 Added support for (person) plural labels.
 */
function wptravelengine_get_label_by_slug( string $slug, $count = 1 ): string {

	$slug_array = apply_filters(
		'wptravelengine_get_label_by_slug',
		array(
			'day'    => array(
				'single' => __( 'Day', 'wp-travel-engine' ),
				'plural' => __( 'Days', 'wp-travel-engine' ),
			),
			'night'  => array(
				'single' => __( 'Night', 'wp-travel-engine' ),
				'plural' => __( 'Nights', 'wp-travel-engine' ),
			),
			'hour'   => array(
				'single' => __( 'Hour', 'wp-travel-engine' ),
				'plural' => __( 'Hours', 'wp-travel-engine' ),
			),
			'minute' => array(
				'single' => __( 'Minute', 'wp-travel-engine' ),
				'plural' => __( 'Minutes', 'wp-travel-engine' ),
			),
			'person' => array(
				'single' => __( 'Person', 'wp-travel-engine' ),
				'plural' => __( 'People', 'wp-travel-engine' ),
			),
		)
	);

	$singular_map = array(
		'days'    => 'day',
		'nights'  => 'night',
		'hours'   => 'hour',
		'minutes' => 'minute',
		'people'  => 'person',
	);

	if ( isset( $singular_map[ $slug ] ) ) {
		$slug = $singular_map[ $slug ];
	}

	if ( ! isset( $slug_array[ $slug ] ) ) {
		return '';
	}

	return $slug_array[ $slug ][ 2 > intval( $count ) ? 'single' : 'plural' ];
}

/**
 * Checks if a WP Travel Engine add-on is active.
 *
 * @param string $addon Addon slug to check. Accepts:
 *                     - wptravelengine
 *                     - fixed-starting-dates
 *                     - partial-payment
 *                     - extra-services
 *                     - file-downloads
 *                     - group-discount
 *                     - advanced-itinerary
 *                     - currency-converter
 *                     - form-editor
 *                     - itinerary-downloader
 *                     - trip-reviews
 *                     - user-history
 *                     - we-travel
 *                     - weather-forecast
 *                     - zapier
 *                     - custom-booking-link
 *                     - booking-fee
 *                     - activity-tour
 *                     - email-automator
 *                     - conditional-price
 *                     - stripe
 *                     - paypal_express
 *                     - authorize_net
 *                     - midtrans
 *                     - payu_money_bolt
 *                     - accommodation
 *                     - travel-insurance
 *                     - waitlist
 *
 * @return ?bool Returns true if addon is active, false if inactive, null if invalid addon
 * @since 6.2.2
 */
function wptravelengine_is_addon_active( string $addon ) {

	if ( empty( $addon ) ) {
		return null;
	}

	$addon_files = array(
		'wptravelengine'       => 'WP_TRAVEL_ENGINE_FILE_PATH',
		'fixed-starting-dates' => 'WTE_FIXED_DEPARTURE_FILE_PATH',
		'partial-payment'      => 'WP_TRAVEL_ENGINE_PARTIAL_PAYMENT_FILE_PATH',
		'extra-services'       => 'WTE_EXTRA_SERVICES_FILE_PATH',
		'file-downloads'       => 'WTEFD_FILE_PATH',
		'group-discount'       => 'WP_TRAVEL_ENGINE_GROUP_DISCOUNT_FILE_PATH',
		'advanced-itinerary'   => 'WTEAD_FILE_PATH',
		'currency-converter'   => 'WTE_CURRENCY_CONVERTER_ABSPATH',
		'form-editor'          => 'WTE_FORM_EDITOR_PLUGIN_FILE',
		'itinerary-downloader' => 'WTE_ITINERARY_DOWNLOADER_ABSPATH',
		'trip-reviews'         => 'WTE_TRIP_REVIEW_FILE_PATH',
		'user-history'         => 'WTE_USER_HISTORY_FILE_PATH',
		'we-travel'            => 'WTE_AFFILIATE_BOOKING_FILE_PATH',
		'weather-forecast'     => 'WTE_WEATHER_FORECAST_BASE_PATH',
		'zapier'               => 'WTE_ZAPIER_PLUGIN_FILE',
		'custom-booking-link'  => 'WTE_CBL_FILE_PATH',
		'booking-fee'          => 'WPTRAVELENGINE_BOOKING_FEE_FILE',
		'activity-tour'        => 'WPTRAVELENGINE_ACTIVITY_TOUR_BOOKING_PATH',
		'email-automator'      => 'WPTRAVELENGINE_EMAIL_AUTOMATOR_PATH',
		'conditional-price'    => 'WPTRAVELENGINE_CONDITIONAL_PRICE_PLUGIN_PATH',
		'stripe'               => 'WTE_STRIPE_GATEWAY_FILE_PATH',
		'paypal_express'       => 'WP_TRAVEL_ENGINE_PAYPAL_EXPRESS_FILE_PATH',
		'authorize_net'        => 'WP_TRAVEL_ENGINE_AUTHORIZE_NET_FILE_PATH',
		'midtrans'             => 'WTE_MIDTRANS_ABSPATH',
		'payu_money_bolt'      => 'WTE_PAYU_MONEY_BOLT_FILE_PATH',
		'waitlist'             => 'WPTRAVELENGINE_WAITLIST_DIR_PATH',
		'accommodation'        => 'WPTRAVELENGINE_ACCOMMODATION_DIR_PATH',
		'travel-insurance'     => 'WPTRAVELENGINE_TRAVEL_INSURANCE_DIR_PATH',
		'installment-payments' => 'WPTRAVELENGINE_INSTALLMENT_PAYMENTS_PATH',
	);

	if ( ! isset( $addon_files[ $addon ] ) ) {
		return null;
	}

	return defined( $addon_files[ $addon ] ) && file_exists( constant( $addon_files[ $addon ] ) );
}

/**
 * Recursive helper function to check if key exists in given data.
 *
 * @param array $data The current level of data to traverse.
 * @param array $keys The remaining keys in the dot-separated path.
 *
 * @return bool
 * @since 6.2.0
 */
function wptravelengine_key_exists( array $data, array $keys ): bool {
	$key = array_shift( $keys );

	if ( ! array_key_exists( $key, $data ) ) {
		return false;
	}

	if ( ! is_array( $data[ $key ] ) || empty( $keys ) ) {
		return true;
	}

	return wptravelengine_key_exists( $data[ $key ], $keys );
}

/**
 * Replaces the target value with a given value in arrays or scalar types.
 * Returns the fallback value if the target is not found.
 *
 * @param mixed      $data Data to replace the value in (array or scalar).
 * @param mixed      $target_value Value to search for and replace.
 * @param mixed      $by Value to replace the target value with.
 * @param mixed      $else Value to return if target is not found. Defaults to `null`.
 * @param string|int $target_key Key in array to replace value in. Defaults to `null`.
 *
 * @return mixed Modified data or fallback value if target is not found.
 * @since 6.2.0
 * @updated 6.2.3
 */
function wptravelengine_replace( $data, $target_value, $by, $else = null, $target_key = null ) {

	if ( is_scalar( $data ) ) {
		return $data === $target_value ? $by : $else;
	}

	if ( ! is_array( $data ) ) {
		return $data;
	}

	$result      = array();
	$target_keys = array_filter( array_map( 'trim', explode( ',', $target_key ?? '' ) ), 'strlen' );

	foreach ( $data as $key => $value ) {
		if ( is_array( $value ) ) {
			$result[ $key ] = wptravelengine_replace( $value, $target_value, $by, $else, $target_key );
		} elseif ( empty( $target_keys ) ) {
			$result[ $key ] = ( $value === $target_value ) ? $by : ( ( is_bool( $target_value ) && is_bool( $value ) ) ? $else : $value );
		} elseif ( in_array( $key, $target_keys, true ) ) {
			$result[ $key ] = ( $value === $target_value ) ? $by : $else;
		} else {
			$result[ $key ] = $value;
		}
	}

	return $result;
}

/**
 * Get the instance of the wptravelengine tabs UI.
 *
 * @return WP_Travel_Engine_Tabs_UI
 * @since 6.1.2
 */
function wptravelengine_tabs_ui(): WP_Travel_Engine_Tabs_UI {
	if ( ! class_exists( 'WP_Travel_Engine_Tabs_UI' ) ) {
		require_once plugin_dir_path( WP_TRAVEL_ENGINE_FILE_PATH ) . 'admin/class-wp-travel-engine-tabs-ui.php';
	}

	return new WP_Travel_Engine_Tabs_UI();
}

/**
 * Generates and Render Admin Tabs UI.
 *
 * @return void
 * @since 6.1.2
 */
function wptravelengine_tabs_ui_render( $args = array(), $tabs = array() ) {
	wptravelengine_tabs_ui()->init( $args )->template( $tabs );
}

/**
 * Get the ordinal suffix for a number.
 *
 * @param int $num
 *
 * @return string
 * @since 6.1.0
 */
function wptravelengine_get_num_suffix( int $num ): string {
	$last_two_digits = $num % 100;

	if ( in_array( $last_two_digits, range( 11, 13 ) ) ) {
		return $num . 'th';
	}

	$suffix = array( 'st', 'nd', 'rd' );

	return $num . ( $suffix[ ( $num % 10 ) - 1 ] ?? 'th' );
}

/**
 * Gets actual checkout URL to check out a trip.
 *
 * @return string
 * @since 6.0.4
 */
function wptravelengine_get_checkout_url(): string {
	return apply_filters( __FUNCTION__, wptravelengine_get_page_url( 'wp_travel_engine_place_order' ) );
}

/**
 * This function compares the value of toggle fields against the provided value and returns boolean.
 *
 * @param mixed $value Value to compare.
 *
 * @return bool
 * @since 6.0.3
 */
function wptravelengine_toggled( $value ): bool {

	if ( ! is_scalar( $value ) ) {
		return false;
	}

	if ( is_numeric( $value ) ) {
		return (bool) $value;
	} elseif ( is_string( $value ) ) {
		return in_array( $value, array( 'yes', 'true', 'on' ), true );
	}

	return (bool) $value;
}

/**
 * @param $function
 * @param $version
 * @param $replacement
 *
 * @return void
 * @since 6.0.0
 */
function wptravelengine_deprecated_function( $function, $version, $replacement = null ) {
	if ( WP_DEBUG && apply_filters( 'deprecated_class_trigger_error', true ) ) {
		// Initialize log storage
		static $logs = array();

		// Get the backtrace
		$backtrace = debug_backtrace( DEBUG_BACKTRACE_IGNORE_ARGS, 3 ); // Get more backtrace steps

		// Create a log entry
		$log_entry = array(
			'function'    => $function,
			'version'     => $version,
			'replacement' => $replacement,
			'backtrace'   => $backtrace,
		);

		// Store the log entry
		$logs[] = $log_entry;

		// Buffer to store formatted log messages
		$buffer = "=== Deprecation Notice ===\n";
		foreach ( $logs as $log ) {
			$buffer .= "Deprecated function {$log['function']} called in version {$log['version']}. Use {$log['replacement']} instead.\n";
			foreach ( $log['backtrace'] as $index => $trace ) {
				$file     = $trace['file'] ?? 'unknown file';
				$line     = $trace['line'] ?? 'unknown line';
				$function = $trace['function'] ?? 'unknown function';
				$buffer  .= "#{$index} {$file}({$line}): {$function}()\n";
			}
			$buffer .= "==========================\n";
		}

		// Print the buffered log messages as a single entry
		error_log( $buffer );
	}
}

/**
 * @param $class
 * @param $version
 * @param $replacement
 *
 * @return void
 * @since 6.0.0
 */
function wptravelengine_deprecated_class( $class, $version, $replacement = null ) {

	if ( WP_DEBUG && apply_filters( 'deprecated_class_trigger_error', true ) ) {
		// Initialize log storage
		static $logs = array();

		// Get the backtrace
		$backtrace = debug_backtrace( DEBUG_BACKTRACE_IGNORE_ARGS, 3 ); // Get more backtrace steps

		// Create a log entry
		$log_entry = array(
			'function'    => $class,
			'version'     => $version,
			'replacement' => $replacement,
			'backtrace'   => $backtrace,
		);

		// Store the log entry
		$logs[] = $log_entry;

		// Buffer to store formatted log messages
		$buffer = "=== Deprecation Notice ===\n";
		foreach ( $logs as $log ) {
			$buffer .= "Deprecated clas {$log['function']} called in version {$log['version']}. Use {$log['replacement']} instead.\n";
			foreach ( $log['backtrace'] as $index => $trace ) {
				$file     = $trace['file'] ?? 'unknown file';
				$line     = $trace['line'] ?? 'unknown line';
				$function = $trace['function'] ?? 'unknown function';
				$buffer  .= "#{$index} {$file}({$line}): {$function}()\n";
			}
			$buffer .= "==========================\n";
		}

		// Print the buffered log messages as a single entry
		error_log( $buffer );
	}
}

/**
 * Get WP Travel Engine Page URLs.
 *
 * @return false|string Page URL.
 */
function wptravelengine_get_page_url( string $page, string $default = null ) {
	$settings = PluginSettings::make();
	$page_id  = $settings->get( "pages.{$page}", null );

	$page_url = false;
	if ( is_numeric( $page_id ) ) {
		$page_url = get_permalink( $page_id );
	}

	if ( ! is_null( $default ) && $page_url ) {
		$page_url = $default;
	}

	return $page_url;
}

/**
 * Get WP Travel Engine Email Instance.
 *
 * @return Email\Email
 *
 * @since 6.5.0
 */
function wptravelengine_get_email(): Email\Email {
	return new Email\Email();
}

/**
 * Set booking email tags.
 *
 * @param int $booking_id The booking ID.
 * @param int $payment_id The payment ID.
 *
 * @return Template_Tags
 * @since 6.5.0
 */
function wptravelengine_set_booking_email_tags( int $booking_id, int $payment_id ): Template_Tags {
	$template_tags = new Template_Tags( $booking_id, $payment_id );
	$template_tags->set_tags();
	return $template_tags;
}

/**
 * Send booking email.
 *
 * @param int|Payment $payment Payment Object or ID.
 * @param string      $template Template Name (order|order_confirmation).
 * @param string      $to Recipient (customer|admin|all).
 *
 * @return void
 */
function wptravelengine_send_booking_emails( $payment, string $template = 'order', string $to = 'all' ) {

	if ( $payment instanceof Payment ) {
		$payment = $payment->get_id();
	}

	if ( 'order' === $template && wptravelengine_toggled( get_post_meta( $payment, 'is_due_payment', true ) ) ) {
		return;
	}

	if ( ! in_array( $to, array( 'admin', 'customer', 'all' ), true ) ) {
		throw new InvalidArgumentException( __( 'Invalid email recipient.', 'wp-travel-engine' ) );
	}

	$to = 'all' === $to ? array( 'admin', 'customer' ) : array( $to );

	$settings = wptravelengine_settings();
	foreach ( $to as $recipient ) {
		$enable = $settings->get( $recipient . '_email_notify_tabs.' . wptravelengine_map_email_template( $template ) . '.enabled', 1 );

		if ( ( 'admin' === $recipient || 'customer' === $recipient ) && ! wptravelengine_toggled( $enable ) ) {
			continue;
		}

		$email = new Email\BookingEmail();
		$email->prepare( $payment, $template )
			->to( $recipient )
			->set_headers()
			->set( 'my_subject', $email->get_my_subject() )
			->set_content()
			->send();
	}
}

/**
 * Get email template name.
 *
 * @param string $template_name
 *
 * @return string
 *
 * @since 6.5.0
 */
function wptravelengine_map_email_template( string $template_name = 'order' ): string {
	$map = array(
		'order'              => 'booking_confirmation',
		'order_confirmation' => 'payment_confirmation',
	);

	return $map[ $template_name ] ?? $template_name;
}

/**
 * Get Template to be used for email.
 *
 * @return string Template Content.
 * @since 6.0.0
 */
function wptravelengine_get_email_template( string $template_name ): string {

	switch ( $template_name ) {
		case 'booking_notification_admin':
			$template = wptravelengine_settings()->get( 'email.booking_notification_template_admin' );
			break;
		case 'booking_notification_customer':
			$template = wptravelengine_settings()->get( 'email.booking_notification_template_customer' );
			break;
		case 'payment_notification_admin':
			$template = wptravelengine_settings()->get( 'email.sales_wpeditor' );
			break;
		case 'payment_notification_customer':
			$template = wptravelengine_settings()->get( 'email.purchase_wpeditor' );
			break;
	}

	return $template;
}

/**
 * Get the instance of the trip.
 *
 * @param int|WP_Post|Trip $object Object Name.
 *
 * @return Trip|null
 * @since 6.0.0
 * @updated 6.6.0
 */
function wptravelengine_get_trip( $object ): ?Trip {

	if ( $object instanceof Trip ) {
		return $object;
	}

	try {
		return new Trip( $object );
	} catch ( InvalidArgumentException $e ) {
		return null;
	}
}

/**
 * Get the instance of the booking.
 *
 * @param int|WP_Post $object Object Name.
 *
 * @return Booking|null
 * @since 6.0.0
 */
function wptravelengine_get_booking( $object ): ?Booking {
	try {
		return new Booking( $object );
	} catch ( InvalidArgumentException $e ) {
		return null;
	}
}

/**
 * Get the instance of the payment.
 *
 * @param int|WP_Post $object Object Name.
 *
 * @return Payment|null
 * @since 6.0.0
 */
function wptravelengine_get_payment( $object ): ?Payment {
	try {
		return new Payment( $object );
	} catch ( InvalidArgumentException $e ) {
		return null;
	}
}

/**
 * Returns singleton Instance of the main Class.
 *
 * @return WPTravelEngine\Plugin
 * @since 5.0
 */
function WPTravelEngine(): \WPTravelEngine\Plugin { // phpcs:ignore WordPress.NamingConventions.ValidFunctionName.FunctionNameInvalid
	return WPTravelEngine\Plugin::instance();
}

/**
 * Gets value of provided index.
 *
 * @param array  $array Array to pick value from.
 * @param string $index Index.
 * @param mixed  $default Default Values.
 *
 * @return mixed
 */
function wte_array_get( $array, $index = null, $default = null ) {
	if ( ! is_array( $array ) ) {
		return $default;
	}
	if ( is_null( $index ) ) {
		return $array;
	}
	$multi_label_indices = explode( '.', $index );
	$value               = $array;
	foreach ( $multi_label_indices as $key ) {
		if ( ! isset( $value[ $key ] ) ) {
			$value = $default;
			break;
		}
		$value = $value[ $key ];
	}

	return $value;
}

/**
 * Generate Random Integer.
 */
function wte_get_random_integer( $min, $max ) {
	$range = ( $max - $min );

	if ( $range < 0 ) {
		// Not so random...
		return $min;
	}

	$log = log( $range, 2 );

	// Length in bytes.
	$bytes = (int) ( $log / 8 ) + 1;

	// Length in bits.
	$bits = (int) $log + 1;

	// Set all lower bits to 1.
	$filter = (int) ( 1 << $bits ) - 1;

	do {
		$rnd = hexdec( bin2hex( openssl_random_pseudo_bytes( $bytes ) ) );

		// Discard irrelevant bits.
		$rnd = $rnd & $filter;

	} while ( $rnd >= $range );

	return ( $min + $rnd );
}

/**
 * Generates uniq ID.
 *
 * @return string
 */
function wte_uniqid( $length = 5 ) {
	if ( ! isset( $length ) || intval( $length ) < 5 ) {
		$length = 5;
	}
	$token      = '';
	$characters = implode( range( 'a', 'z' ) ) . implode( range( 'A', 'Z' ) );
	for ( $i = 0; $i < $length; $i++ ) {
		$random_key = wte_get_random_integer( 0, strlen( $characters ) );
		$token     .= $characters[ $random_key ];
	}

	return $token;
}

/**
 * Generate JWT.
 *
 * @param array  $payload
 * @param string $key
 *
 * @return string
 */
function wte_jwt( array $payload, string $key ): string {
	if ( ! class_exists( 'Firebase\JWT\JWT' ) ) {
		include_once WP_TRAVEL_ENGINE_BASE_PATH . '/includes/lib/jwt/loader.php';
	}
	return JWT::encode( $payload, $key );
}

/**
 * Decode JWT.
 */
function wte_jwt_decode( string $jwt, string $key ) {
	if ( ! class_exists( 'Firebase\JWT\JWT' ) ) {
		include_once WP_TRAVEL_ENGINE_BASE_PATH . '/includes/lib/jwt/loader.php';
	}
	return JWT::decode( $jwt, $key, array( 'HS256' ) );
}

/**
 * WTE Log data in json format.
 *
 * @param mixed $data
 *
 * @return void
 */
function wte_log( $data, $name = 'data', $dump = false, $raw = true ) {
	if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
		if ( $raw ) {
			error_log( print_r( $data, true ), 3, WP_CONTENT_DIR . '/wte.log' ); // phpcs:ignore

			return;
		}
		$data = wp_json_encode( array( $name => $data ), JSON_PRETTY_PRINT );
		error_log( $data, 3, WP_CONTENT_DIR . '/wte.log' ); // phpcs:ignore
		if ( $dump ) {
			var_dump( $data );
		} else {
			return $data;
		}
	}
}

/**
 * Returns Booking Email instance.
 *
 * @return WTE_Booking_Emails
 */
function wte_booking_email(): WTE_Booking_Emails {
	// Mail class.
	require_once plugin_dir_path( WP_TRAVEL_ENGINE_FILE_PATH ) . 'includes/class-wp-travel-engine-emails.php';

	return new \WTE_Booking_Emails();
}

/**
 * Undocumented function
 *
 * @return void
 * @since 4.3.8
 */
function wte_form_fields( array $fields, $echo = ! 0 ) {
	ob_start();
	( new WTE_Field_Builder_Admin( $fields ) )->render();
	$html = ob_get_clean();

	if ( $echo ) {
		echo $html;
	} else {
		return $html;
	}
}

/**
 *
 * @since 5.3.1
 */
function wte_admin_form_fields( $fields = array() ) {
	if ( ! class_exists( '\WP_Travel_Engine_Form_Field' ) || ! class_exists( '\WP_Travel_Engine_Form_Field_Admin' ) ) {
		include_once plugin_dir_path( WP_TRAVEL_ENGINE_FILE_PATH ) . 'includes/lib/wte-form-framework/class-wte-form-field.php';
	}

	$wte_form_field_instance = new \WP_Travel_Engine_Form_Field_Admin();

	$wte_form_field_instance->init( $fields );

	return $wte_form_field_instance;
}

/**
 * Availability Options.
 */
function wte_get_availability_options( $key = ! 1 ) {
	$options = apply_filters(
		'wte_date_availability_options',
		array(
			'guaranteed' => __( 'Guaranteed', 'wp-travel-engine' ),
			'available'  => __( 'Available', 'wp-travel-engine' ),
			'limited'    => __( 'Limited', 'wp-travel-engine' ),
		)
	);
	if ( $key && isset( $options[ $key ] ) ) {
		return $options[ $key ];
	} else {
		return $options;
	}
}

/**
 * Get Requested Raw Data.
 *
 * @return void
 */
function wte_get_request_raw_data() {
	// phpcs:disable PHPCompatibility.Variables.RemovedPredefinedGlobalVariables.http_raw_post_dataDeprecatedRemoved
	global $HTTP_RAW_POST_DATA;

	// $HTTP_RAW_POST_DATA was deprecated in PHP 5.6 and removed in PHP 7.0.
	if ( ! isset( $HTTP_RAW_POST_DATA ) ) {
		$HTTP_RAW_POST_DATA = file_get_contents( 'php://input' );
	}

	return $HTTP_RAW_POST_DATA;
	// phpcs:enable
}

/**
 * Timezone info.
 *
 * @return void
 */
function wte_get_timezone_info() {
	$tz_string     = get_option( 'timezone_string' );
	$timezone_info = array();

	if ( $tz_string ) {
		try {
			$tz = new DateTimezone( $tz_string );
		} catch ( Exception $e ) {
			$tz = '';
		}

		if ( $tz ) {
			$now                  = new DateTime( 'now', $tz );
			$formatted_gmt_offset = wte_format_gmt_offset( $tz->getOffset( $now ) / 3600 );
			$tz_name              = str_replace( '_', ' ', $tz->getName() );
		}
	} else {
		$formatted_gmt_offset = wte_format_gmt_offset( (float) get_option( 'gmt_offset', 0 ) );

		$timezone_info['description'] = sprintf(
		/* translators: 1: UTC abbreviation and offset, 2: UTC offset. */
			__( 'Your timezone is set to %1$s (Coordinated Universal Time %2$s).', 'wp-travel-engine' ),
			'<abbr>UTC</abbr>' . $formatted_gmt_offset,
			$formatted_gmt_offset
		);
	}

	return $formatted_gmt_offset;
}

/**
 *
 */
function wte_format_gmt_offset( $offset ) {
	$offset = number_format( $offset, 2 );

	if ( 0 <= (float) $offset ) {
		$formatted_offset = '+' . (string) $offset;
	} else {
		$formatted_offset = (string) $offset;
	}

	preg_match( '/(\+|\-)?(\d+\.\d+)/', $formatted_offset, $matches );

	if ( isset( $matches[2] ) ) {
		$formatted_offset = substr( '0000' . $matches[2], - 5 );
	}

	$formatted_offset = $matches[1] . $formatted_offset;

	$formatted_offset = str_replace(
		array( '.25', '.50', '.75', '.00' ),
		array( ':15', ':30', ':45', ':00' ),
		$formatted_offset
	);

	return $formatted_offset;
}

function wte_get_trip( $trip = null ) {
	if ( empty( $trip ) && isset( $GLOBALS['wtetrip'] ) ) {
		$trip = $GLOBALS['wtetrip'];
	}

	if ( $trip instanceof Posttype\Trip ) {
		$_trip = $trip;
	} else {
		$_trip = WPTravelEngine\Posttype\Trip::instance( $trip );
	}

	if ( ! $_trip ) {
		return null;
	}

	return $_trip;
}

function wte_get_engine_extensions() {
	$plugins = get_plugins();

	$matches = array();
	foreach ( $plugins as $file => $plugin ) {
		if ( 'WordPress Travel Booking Plugin - WP Travel Engine' !== $plugin['Name'] && ( stristr( $plugin['Name'], 'wp travel engine' ) || stristr( $plugin['Description'], 'wp travel engine' ) ) ) {
			$matches[ $file ] = $plugin;
		}
	}

	return $matches;
}

function wte_get_extensions_ids( $key = null ) {
	$ids = apply_filters(
		'wp_travel_engine_addons_id',
		array(
			'wte_group_discount'           => 146,
			'wte_currency_converter'       => 30074,
			'wte_fixed_starting_dates'     => 79,
			'wte_midtrans'                 => 31412,
			'wte_hbl_payment'              => 20311,
			'wte_partial_payment'          => 1750,
			'wte_payfast'                  => 1744,
			'wte_paypal_express'           => 7093,
			'wte_payu'                     => 1055,
			'wte_advanced_itinerary'       => 31567,
			'wte_advance_search'           => 1757,
			'wte_authorize_net'            => 577,
			'wte_extra_services'           => 20573,
			'wte_form_editor'              => 33247,
			'wte_payhere_payment'          => 30754,
			'wte_payu_money_bolt_checkout' => 30752,
			'wte_stripe_gateway'           => 557,
			'wte_trip_code'                => 40085,
			'wte_coupons'                  => 42678,
		)
	);
	if ( $key && ! isset( $ids[ $key ] ) ) {
		return false;
	}

	return $key ? $ids[ $key ] : $ids;
}

function wte_functions(): Functions {
	return new Functions();
}

/**
 * @return HelperFunctions
 * @since 6.0.0
 */
function wptravelengine_functions(): HelperFunctions {
	return new HelperFunctions();
}

function wte_readonly( $value, $check_against, $echo = true ) {
	if ( ( is_array( $check_against ) && in_array( $value, $check_against ) )
		|| ( ! is_array( $check_against ) && $value === $check_against )
	) {
		if ( $echo ) {
			echo 'readonly=\"readonly\"';
		}

		return true;
	}
}

/**
 * Gets Trip Reviews.
 */
function wte_get_trip_reviews( $trip_id ): array {

	/**
	 * Removes sql query to fix compatibility issue with different dbms.
	 */
	$results = get_comments(
		array(
			'post_id'   => $trip_id,
			'post_type' => \WP_TRAVEL_ENGINE_POST_TYPE,
		)
	);

	$_result = array();
	if ( $results && is_array( $results ) ) {
		$reviews_meta = array(
			'phone'           => '',
			'title'           => '',
			'stars'           => 0,
			'experience_date' => '',
		);
		$i            = 0;
		foreach ( $results as $result ) {
			$_result[ $i ]['ID']      = (int) $result->comment_ID;
			$_result[ $i ]['content'] = $result->comment_content;

			if ( isset( $result->reviews_meta ) && json_decode( $result->reviews_meta ) ) {
				$_metas = json_decode( $result->reviews_meta );
				foreach ( $reviews_meta as $key => $value ) {
					if ( isset( $_metas->$key ) ) {
						$_result[ $i ][ $key ] = 'stars' === $key ? (int) $_metas->{$key} : $_metas->{$key};
					} else {
						$_result[ $i ][ $key ] = $value;
					}
				}
			}
			++$i;
		}
	}

	$stars = array_column( $_result, 'stars' );

	return array(
		'reviews' => $_result,
		'average' => count( $stars ) > 0 ? array_sum( $stars ) / count( $stars ) : 0,
		'count'   => count( $stars ),
	);
}

/**
 * Use it as a templating function inside loop.
 */
function wte_get_the_trip_reviews( $trip_id = null ) {
	if ( ! defined( 'WTE_TRIP_REVIEW_VERSION' ) ) {
		return '';
	}
	if ( is_null( $trip_id ) ) {
		$trip_id = get_the_ID();
	}

	$trip_reviews = (object) wte_get_trip_reviews( $trip_id );

	if ( ! isset( $trip_reviews->average ) || $trip_reviews->average <= 0 ) {
		return;
	}

	// phpcs:disable
	ob_start();
	?>
	<div class="wpte-trip-review-stars">
		<div class="stars-group-wrapper">
			<div class="stars-placeholder-group">
				<?php
				echo implode(
					'',
					array_map(
						function () {
							return '<svg width="15" height="15" viewBox="0 0 15 15" fill="none"><path d="M6.41362 0.718948C6.77878 -0.0301371 7.84622 -0.0301371 8.21138 0.718948L9.68869 3.74946C9.83326 4.04602 10.1148 4.25219 10.4412 4.3005L13.7669 4.79272C14.5829 4.91349 14.91 5.91468 14.3227 6.49393L11.902 8.88136C11.6696 9.1105 11.5637 9.4386 11.6182 9.76034L12.1871 13.1191C12.3258 13.9378 11.464 14.559 10.7311 14.1688L7.78252 12.5986C7.4887 12.4421 7.1363 12.4421 6.84248 12.5986L3.89386 14.1688C3.16097 14.559 2.29922 13.9378 2.43789 13.1191L3.0068 9.76034C3.06129 9.4386 2.95537 9.1105 2.72303 8.88136L0.302324 6.49393C-0.285 5.91468 0.0420871 4.91349 0.85811 4.79272L4.18383 4.3005C4.5102 4.25219 4.79174 4.04602 4.93631 3.74946L6.41362 0.718948Z" fill="#EBAD34"></path></svg>';
						},
						range( 0, 4 )
					)
				);
				?>
			</div>
			<div
				class="stars-rated-group"
				style="width: <?php echo esc_attr( $trip_reviews->average * 20 ); ?>%">
				<?php
				echo implode(
					'',
					array_map(
						function () {
							return '<svg width="15" height="15" viewBox="0 0 15 15" fill="none"><path d="M6.41362 0.718948C6.77878 -0.0301371 7.84622 -0.0301371 8.21138 0.718948L9.68869 3.74946C9.83326 4.04602 10.1148 4.25219 10.4412 4.3005L13.7669 4.79272C14.5829 4.91349 14.91 5.91468 14.3227 6.49393L11.902 8.88136C11.6696 9.1105 11.5637 9.4386 11.6182 9.76034L12.1871 13.1191C12.3258 13.9378 11.464 14.559 10.7311 14.1688L7.78252 12.5986C7.4887 12.4421 7.1363 12.4421 6.84248 12.5986L3.89386 14.1688C3.16097 14.559 2.29922 13.9378 2.43789 13.1191L3.0068 9.76034C3.06129 9.4386 2.95537 9.1105 2.72303 8.88136L0.302324 6.49393C-0.285 5.91468 0.0420871 4.91349 0.85811 4.79272L4.18383 4.3005C4.5102 4.25219 4.79174 4.04602 4.93631 3.74946L6.41362 0.718948Z" fill="#EBAD34"></path></svg>';
						},
						range( 0, 4 )
					)
				);
				?>
			</div>
		</div>
		<?php if ( (float) $trip_reviews->count > 0 ) : ?>
			<a class="wpte-trip-review-count"
			   href="<?php echo esc_url( get_the_permalink() . "#nb-7-configurations" ); ?>"><?php printf( esc_html( _n( '%d Review', '%d Reviews', $trip_reviews->count, 'wp-travel-engine' ) ), (float) $trip_reviews->count ); ?></a>
		<?php endif; ?>
	</div>
	<?php
	return ob_get_clean();
	// phpcs:enable
}

/**
 * Get the trip reviews.
 *
 * @return string
 */
function wte_the_trip_reviews() {
	echo wte_get_the_trip_reviews( get_the_ID() ); //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}

function wte_get_the_excerpt( $trip_id = null, $words = 25 ) {
	return wptravelengine_get_the_trip_excerpt( $trip_id, $words );
}

function wte_list( $array, $vars ) {
	$_array = array();
	if ( is_array( $array ) && is_array( $vars ) ) {
		foreach ( $vars as $index => $key ) {
			$_array[ $index ] = isset( $array[ $key ] ) ? $array[ $key ] : null;
		}
	}

	return $_array;
}

function wte_get_media_details( $media_id ) {
	$media_details = \wp_get_attachment_metadata( $media_id );

	// Ensure empty details is an empty object.
	if ( empty( $media_details ) ) {
		$media_details = new \stdClass();
	} elseif ( ! empty( $media_details['sizes'] ) ) {

		foreach ( $media_details['sizes'] as $size => &$size_data ) {

			if ( isset( $size_data['mime-type'] ) ) {
				$size_data['mime_type'] = $size_data['mime-type'];
				unset( $size_data['mime-type'] );
			}

			// Use the same method image_downsize() does.
			$image_src = wp_get_attachment_image_src( $media_id, $size );
			if ( ! $image_src ) {
				continue;
			}

			$size_data['source_url'] = $image_src[0];
		}

		$full_src = wp_get_attachment_image_src( $media_id, 'full' );

		if ( ! empty( $full_src ) ) {
			$media_details['sizes']['full'] = array(
				'file'       => wp_basename( $full_src[0] ),
				'width'      => $full_src[1],
				'height'     => $full_src[2],
				// 'mime_type'  => $post->post_mime_type,
				'source_url' => $full_src[0],
			);
		}
	} else {
		$media_details['sizes'] = new \stdClass();
	}

	unset( $media_details->{'image_meta'} );

	return $media_details;
}

/**
 * Checks if trip has group discount.
 */
function wte_has_trip_group_discount( $trip_id ) {
	return \apply_filters( 'has_packages_group_discounts', false, $trip_id );
}

function wte_get_terms_by_id( $taxonomy, $args = array() ) {
	$terms        = get_terms( $taxonomy, array_merge( $args, array( 'pad_counts' => true ) ) );
	$terms_by_ids = array();

	if ( is_array( $terms ) ) {
		foreach ( $terms as $term_object ) {
			$term_object->children  = array();
			$term_object->link      = get_term_link( $term_object->term_id );
			$term_object->thumbnail = (int) get_term_meta( $term_object->term_id, 'category-image-id', true );
			if ( isset( $terms_by_ids[ $term_object->term_id ] ) ) {
				foreach ( (array) $terms_by_ids[ $term_object->term_id ] as $prop_name => $prop_value ) {
					$term_object->{$prop_name} = $prop_value;
				}
			}
			if ( $term_object->parent ) {
				if ( ! isset( $terms_by_ids[ $term_object->parent ] ) ) {
					$terms_by_ids[ $term_object->parent ] = new \stdClass();
				}
				$terms_by_ids[ $term_object->parent ]->children[] = $term_object->term_id;
			}

			$terms_by_ids[ $term_object->term_id ] = $term_object;
		}
	}

	return $terms_by_ids;
}

// wte_trip_get_trip_rest_metadata
function wte_trip_get_trip_rest_metadata( $trip_id ) {

	$post = get_post( $trip_id );

	$trip_details = \wte_get_trip_details( $trip_id );

	$data = new \stdClass();

	$featured_media = get_post_thumbnail_id( $trip_id );

	foreach (
		array(
			'code'             => array(
				'key'  => 'trip_settings.trip_code',
				'type' => 'string',
			),
			'price'            => array(
				'key'  => 'display_price',
				'type' => 'number',
			),
			'has_sale'         => array(
				'key'  => 'on_sale',
				'type' => 'boolean',
			),
			'sale_price'       => array(
				'key'  => 'sale_price',
				'type' => 'number',
			),
			'discount_percent' => array(
				'key'     => 'discount_percent',
				'type'    => 'number',
				'decimal' => 0,
			),
			'currency'         => array(
				'type'  => 'array',
				'items' => array(
					'code'   => array(
						'key'  => 'code',
						'type' => 'string',
					),
					'symbol' => array(
						'key'  => 'currency',
						'type' => 'string',
					),
				),
			),
			'duration'         => array(
				'type'  => 'array',
				'items' => array(
					'days'          => array(
						'key'  => 'trip_duration',
						'type' => 'number',
					),
					'nights'        => array(
						'key'  => 'trip_duration_nights',
						'type' => 'number',
					),
					'duration_unit' => array(
						'key'  => 'trip_duration_unit',
						'type' => 'string',
					),
					'duration_type' => array(
						'key'  => 'set_duration_type',
						'type' => 'string',
					),
				),
			),
		) as $property_name => $args
	) {
		$value = isset( $args['key'] ) ? wte_array_get( $trip_details, $args['key'], '' ) : '';

		if ( 'array' === $args['type'] && isset( $args['items'] ) ) {
			$value = array();
			$items = $args['items'];
			foreach ( $items as $sub_property_name => $item ) {
				if ( isset( $trip_details[ $item['key'] ] ) ) {
					if ( 'number' === $item['type'] ) {
						$decimal                     = isset( $item['decimal'] ) ? (int) $item['decimal'] : 0;
						$value[ $sub_property_name ] = round( (float) $trip_details[ $item['key'] ], $decimal );
					} else {
						$value[ $sub_property_name ] = $trip_details[ $item['key'] ];
					}
				}
			}
			$data->{$property_name} = $value;
			continue;
		}
		$data->{$property_name} = 'number' === $args['type'] ? round( (float) $value, 2 ) : $value;
	}

	// $wte_trip = \wte_get_trip( $trip_id );

	$data->min_pax = '';
	$data->max_pax = '';

	$trip = wptravelengine_get_trip( $trip_id );
	if ( $trip ) {
		$default_package  = $trip->default_package();
		$primary_category = $default_package->primary_pricing_category->id ?? 0;

		$data->discount_value = $default_package->sale_amount ?? 0;
		$data->discount_label = wptravelengine_get_discount_label( $default_package );

		// @since 6.7.3 Added support for min/max participants.
		if ( $trip->is_enabled_min_max_participants() ) {
			if ( ! empty( $trip->get_minimum_participants() ) ) {
				$data->min_pax = (int) $trip->get_minimum_participants();
			}
			if ( ! empty( $trip->get_maximum_participants() ) ) {
				$data->max_pax = (int) $trip->get_maximum_participants();
			}
		}
	} else {
		$default_package = false;
	}

	$primary_category ??= (int) ( get_post_meta( $trip_id, 'primary_category', true ) ?: wptravelengine_settings()->get_primary_pricing_category()->term_id );

	$data->price            = $default_package->price ?? '';
	$data->has_sale         = $default_package->has_sale ?? false;
	$data->sale_price       = $default_package->sale_price ?? '';
	$data->primary_category = $primary_category;

	$data->available_times = array(
		'type'  => 'default',
		'items' => array_map(
			function ( $month ) {
				return "2021-{$month}-01";
			},
			range( 1, 12 )
		),
	);

	$trip_settings = get_post_meta( $trip_id, 'wp_travel_engine_setting', true );

	if ( ! $trip && isset( $trip_settings['minmax_pax_enable'] ) && 'true' === $trip_settings['minmax_pax_enable'] ) {
		if ( ! empty( $trip_settings['trip_minimum_pax'] ) ) {
			$data->min_pax = (int) $trip_settings['trip_minimum_pax'];
		}
		if ( ! empty( $trip_settings['trip_maximum_pax'] ) ) {
			$data->max_pax = (int) $trip_settings['trip_maximum_pax'];
		}
	}

	$data->description   = $trip_settings['tab_content']['1_wpeditor'] ?? '';
	$data->cost_includes = $trip_settings['cost']['cost_includes'] ?? '';
	$data->cost_excludes = $trip_settings['cost']['cost_excludes'] ?? '';
	$data->itineraries   = array();
	if ( isset( $trip_settings['itinerary']['itinerary_title'] ) && is_array( $trip_settings['itinerary']['itinerary_title'] ) ) {
		foreach ( $trip_settings['itinerary']['itinerary_title'] as $key => $itinerary ) {
			$data->itineraries[] = array(
				'title'   => $trip_settings['itinerary']['itinerary_title'][ $key ] ?? '',
				'content' => $trip_settings['itinerary']['itinerary_content'][ $key ] ?? '',
			);
		}
	}

	$data->faqs = array();
	if ( isset( $trip_settings['faq']['faq_title'] ) && is_array( $trip_settings['faq']['faq_title'] ) ) {
		foreach ( $trip_settings['faq']['faq_title'] as $key => $faq ) {
			$data->faqs[] = array(
				'title'   => $trip_settings['faq']['faq_title'][ $key ] ?? '',
				'content' => $trip_settings['faq']['faq_content'][ $key ] ?? '',
			);
		}
	}

	if ( isset( $trip_settings['trip_facts'][2][2] ) ) {
		$data->group_size = $trip_settings['trip_facts'][2][2];
	}

	$data->is_featured = \wte_is_trip_featured( $trip_id );

	if ( defined( 'WTE_TRIP_REVIEW_VERSION' ) ) {
		$data->{'trip_reviews'} = \wte_get_trip_reviews( $trip_id );
	}

	$media_details = \wte_get_media_details( $featured_media );

	$data->featured_image = $media_details;

	return $data;
}

/**
 * Retrieve currency code.
 *
 * @since 5.2.0
 */
function wte_currency_code(): string {
	return wptravelengine_functions()
		->wp_travel_engine_currencies_symbol(
			wptravelengine_settings()->get( 'currency_code', 'USD' )
		);
}

/**
 * Default Sanitize Callback
 *
 * @since 5.0.0
 */
function wte_default_sanitize_callback( $value ) {
	return $value;
}

/**
 * Update trip packages with posted data.
 *
 * @deprecated deprecated since 5.3.0
 * @since 5.0.0
 */
function wte_update_trip_packages( $post_ID, $posted_data ) {
	_deprecated_function( __FUNCTION__, '5.3.0', '\WPTravelEngine\Packages\update_trip_packages' );
	\WPTravelEngine\Packages\update_trip_packages( $post_ID, $posted_data );
}

/**
 * Default text and labels.
 *
 * @since 5.3.0
 */
function wte_default_labels( $labelof = null ) {
	$defaults = array(
		'checkout.submitButtonText' => __( 'Book Now', 'wp-travel-engine' ),
		'checkout.bookingSummary'   => __( 'Booking Summary', 'wp-travel-engine' ),
		'checkout.totalPayable'     => __( 'Total Payable Now', 'wp-travel-engine' ),
	);

	$labels = apply_filters( 'wte_default_labels', $defaults );

	if ( ! $labelof ) {
		return $labels;
	} else {
		return isset( $labels[ $labelof ] ) ? $labels[ $labelof ] : '';
	}
}

/**
 * Clean variables using sanitize_text_field. Arrays are cleaned recursively.
 * Non-scalar values are ignored.
 *
 * @param string|array $var Data to sanitize.
 * @param string       $sanitize_callback Sanitize callback.
 *
 * @return string|array
 * @since 5.3.1
 */
function wte_clean( $var ) {
	if ( is_array( $var ) ) {
		return array_map( 'wte_clean', $var );
	} elseif ( is_scalar( $var ) ) {
		return sanitize_text_field( $var );
	} else {
		return $var;
	}
}

/**
 *
 * @since 5.3.1
 */
function wte_nonce_verify( $key, $action ) {
	if ( isset( $_REQUEST[ $key ] ) ) {
		$nonce = wte_clean( wp_unslash( $_REQUEST[ $key ] ) );

		if ( ! is_string( $nonce ) ) {
			return false;
		}

		return wp_verify_nonce( $nonce, $action );
	}

	return false;
}

/**
 *
 * @since 5.3.2
 */
function wte_input_clean( $data, $schema = array() ) {

	if ( is_array( $data ) ) {
		$_data = array();
		foreach ( $data as $index => $value ) {
			if ( isset( $schema['properties'][ $index ] ) ) {
				$_data[ $index ] = call_user_func( 'wte_input_clean', $value, $schema['properties'][ $index ] );
			} else {
				if ( is_array( $value ) ) {
					$_data[ $index ] = call_user_func(
						'wte_input_clean',
						$value,
						array(
							'type'       => 'array',
							'properties' => array(),
						)
					);
				} elseif ( is_scalar( $value ) ) {
					if ( isset( $schema['items'] ) ) {
						$_data[ $index ] = call_user_func(
							'wte_input_clean',
							$value,
							array(
								'type'              => $schema['items']['type'],
								'sanitize_callback' => $schema['items']['sanitize_callback'],
							)
						);
					} else {
						$_data[ $index ] = call_user_func( 'wte_input_clean', $value, array( 'type' => 'string' ) );
					}
				}
				continue;
			}
		}

		return $_data;
	} else {
		if ( ! is_scalar( $data ) ) {
			return $data;
		}
		if ( isset( $schema['sanitize_callback'] ) ) {
			return call_user_func( $schema['sanitize_callback'], $data );
		} else {
			return sanitize_text_field( $data );
		}
	}
}

/**
 *
 * Escapes for SVGs.
 *
 * @param string $value SVG String.
 *
 * 5.3.2
 */
function wte_esc_svg( $value ) {
	return wp_kses(
		$value,
		array(
			'svg'    => array(
				'id'              => array(),
				'class'           => array(),
				'aria-hidden'     => array(),
				'aria-labelledby' => array(),
				'role'            => array(),
				'xmlns'           => array(),
				'width'           => array(),
				'height'          => array(),
				'viewbox'         => array(), // <= Must be lower case!
				'fill'            => array(),
			),
			'g'      => array(
				'fill'      => array(),
				'id'        => array(),
				'transform' => array(),
			),
			'title'  => array( 'title' => array() ),
			'path'   => array(
				'id'              => array(),
				'd'               => array(),
				'fill'            => array(),
				'data-name'       => array(),
				'transform'       => array(),
				'stroke'          => array(),
				'stroke-width'    => array(),
				'stroke-linecap'  => array(),
				'stroke-linejoin' => array(),
			),
			'i'      => array(),
			'circle' => array(
				'cx'              => array(),
				'cy'              => array(),
				'r'               => array(),
				'fill'            => array(),
				'stroke'          => array(),
				'stroke-width'    => array(),
				'stroke-linecap'  => array(),
				'stroke-linejoin' => array(),
			),
		)
	);
}

/**
 * Output icon (supports SVG markup, img tags, or image URLs).
 *
 * This function handles three types of icon inputs:
 * - SVG markup (inline SVG content)
 * - Pre-formatted img tags
 * - Image URLs (will be wrapped in img tag)
 *
 * @param string $icon_url Icon URL, SVG markup, or img tag.
 * @param string $alt_text Alt text for the icon. Default: 'Payment Method'.
 * @param bool   $echo     Whether to echo the output. Default: false.
 *
 * @return string Escaped HTML markup for the icon.
 * @since 6.7.0
 */
function wptravelengine_display_icon( string $icon_url, string $alt_text = 'Payment Method', bool $echo = false ): string { // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped, WordPress.WP.AlternativeFunctions.esc_html_e
	if ( empty( $icon_url ) ) {
		return ''; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped, WordPress.WP.AlternativeFunctions.esc_html_e
	}

	$output = '';

	// Check if it's SVG markup
	if ( strpos( $icon_url, '<svg' ) !== false ) {
		$output = wte_esc_svg( $icon_url );
	} elseif ( strpos( $icon_url, '<img' ) !== false ) {
		// Already an img tag, sanitize it
		$output = wp_kses_post( $icon_url );
	} else {
		// It's a URL, wrap it in an img tag
		$output = '<img src="' . esc_url( $icon_url ) . '" alt="' . esc_attr( $alt_text ) . '">';
	}

	if ( $echo ) {
		echo $output; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	return $output;
}

/**
 * Booking Meta related to Payment value passed as array for multi use
 *
 * @param [type] $booking_id
 *
 * @return void
 */
function booking_meta_details( $booking_id ) {
	$booking_meta                      = array();
	$booking_metas                     = get_post_meta( $booking_id, 'wp_travel_engine_booking_setting', true );
	$booking_meta['booked_travellers'] = isset( $booking_metas['place_order']['traveler'] ) ? $booking_metas['place_order']['traveler'] : 0;

	$booking_meta['total_paid']                = isset( $booking_metas['place_order']['cost'] ) ? $booking_metas['place_order']['cost'] : 0;
	$booking_meta['remaining_payment']         = isset( $booking_metas['place_order']['due'] ) ? $booking_metas['place_order']['due'] : 0;
	$booking_meta['total_cost']                = isset( $booking_metas['place_order']['due'] ) && $booking_metas['place_order']['due'] != '' ? floatval( $booking_metas['place_order']['cost'] ) + floatval( $booking_metas['place_order']['due'] ) : $booking_meta['total_paid'];
	$booking_meta['partial_due']               = isset( $booking_metas['place_order']['partial_due'] ) ? $booking_metas['place_order']['partial_due'] : 0;
	$booking_meta['partial_cost']              = isset( $booking_metas['place_order']['partial_cost'] ) ? $booking_metas['place_order']['partial_cost'] : 0;
	$booking_meta['trip_id']                   = isset( $booking_metas['place_order']['tid'] ) ? $booking_metas['place_order']['tid'] : 0;
	$booking_meta['trip_name']                 = isset( $booking_metas['place_order']['tid'] ) ? esc_html( get_the_title( $booking_metas['place_order']['tid'] ) ) : '';
	$booking_meta['trip_start_date']           = isset( $booking_metas['place_order']['datetime'] ) ? $booking_metas['place_order']['datetime'] : '';
	$booking_meta['trip_start_date_with_time'] = isset( $booking_metas['place_order']['datewithtime'] ) ? $booking_metas['place_order']['datewithtime'] : '';
	$booking_meta['date_format']               = get_option( 'date_format' );

	return $booking_meta;
}

/**
 * Escape html attributes key-value pair
 *
 * @since 5.3.1
 */
function wte_esc_attr( $value ) {
	if ( is_string( $value ) ) {
		preg_match( "/([\w\-]+)=(['\"](.*)['\"])/", $value, $matches );
		if ( $matches && isset( $matches[1] ) && $matches[3] ) {
			return esc_attr( $matches[1] ) . '="' . $matches[3] . '"';
		}

		return esc_attr( $value );
	}

	return $value;
}

/**
 * Duplicate any post by provided Post ID or Post Object.
 */
function wptravelengine_duplicate_post( $post_id ) {

	global $wpdb;

	$post = get_post( $post_id );

	/*
	 * if you don't want current user to be the new post author,
	 * then change next couple of lines to this: $new_post_author = $post->post_author;
	 */
	$current_user    = wp_get_current_user();
	$new_post_author = $current_user->ID;

	/*
	 * if post data exists, create the post duplicate
	 */
	if ( isset( $post ) && ! is_null( $post ) ) {
		/*
		 * new post data array
		 */
		$args = array(
			'comment_status' => $post->comment_status,
			'ping_status'    => $post->ping_status,
			'post_author'    => $new_post_author,
			'post_content'   => wp_kses_post( $post->post_content ),
			'post_excerpt'   => sanitize_text_field( $post->post_excerpt ),
			'post_parent'    => $post->post_parent,
			'post_password'  => $post->post_password,
			'post_status'    => 'draft',
			'post_title'     => $post->post_title,
			'post_type'      => $post->post_type,
			'to_ping'        => $post->to_ping,
			'menu_order'     => $post->menu_order,
		);

		/*
		 * insert the post by wp_insert_post() function
		 */
		$new_post_id = wp_insert_post( $args );

		/*
		 * get all current post terms ad set them to the new post draft
		 */
		$taxonomies = get_object_taxonomies( $post->post_type );
		foreach ( $taxonomies as $taxonomy ) {
			$post_terms = wp_get_object_terms( $post->ID, $taxonomy, array( 'fields' => 'slugs' ) );
			wp_set_object_terms( $new_post_id, $post_terms, $taxonomy, false );
		}

		/*
		 * duplicate all post meta just in two SQL queries
		 */
		$post_meta_info  = $wpdb->prepare( "SELECT meta_key, meta_value FROM $wpdb->postmeta WHERE post_id = %s", $post->ID ); // phpcs:ignore
		$post_meta_infos = $wpdb->get_results( $post_meta_info ); // phpcs:ignore

		if ( isset( $post_meta_infos[0] ) ) {
			$sql_query     = $wpdb->prepare( "INSERT INTO {$wpdb->postmeta} (post_id, meta_key, meta_value) VALUES " );
			$sql_query_sel = array();
			foreach ( $post_meta_infos as $meta_info ) {
				$meta_key = $meta_info->meta_key;

				if ( '_wp_old_slug' === $meta_key || 'wc_product_id' === $meta_key ) {
					continue;
				}

				$meta_value      = $meta_info->meta_value;
				$sql_query_sel[] = $wpdb->prepare( '(%d, %s, %s)', $new_post_id, $meta_key, $meta_value );

			}
			$sql_query .= implode( ', ', $sql_query_sel );
			$wpdb->query( $sql_query ); // phpcs:ignore
		}

		return $new_post_id;
	} else {
		return null;
	}
}

/**
 * Get Products from store.
 *
 * @since 5.4.3
 */
function wptravelengine_get_products_from_store( $type = 'addons' ) {
	$addons_data = get_transient( "wp_travel_engine_store_{$type}_list" );

	$links_by_type = (object) array(
		'addons'   => 'add-ons',
		'themes'   => 'travel-wordpress-themes',
		'services' => 'services',
	);

	if ( ! $addons_data ) {
		$addons_data = wp_safe_remote_get( WP_TRAVEL_ENGINE_STORE_URL . "/edd-api/v2/products/?category={$links_by_type->{$type}}&number=-1&orderby=menu_order&order=asc" );

		if ( is_wp_error( $addons_data ) ) {
			return array();
		}

		$addons_data = wp_remote_retrieve_body( $addons_data );
		set_transient( "wp_travel_engine_store_{$type}_list", $addons_data, 48 * HOUR_IN_SECONDS );
	}

	if ( ! empty( $addons_data ) ) :

		$addons_data = json_decode( $addons_data );
		$addons_data = $addons_data->products;

	endif;

	return $addons_data;
}

/**
 * Get Community Themes.
 *
 * @since 5.4.3
 */
function wptravelengine_get_community_themes(): array {
	return array(
		array(
			'title'     => 'Travel Agency',
			'url'       => 'https://rarathemes.com/wordpress-themes/travel-agency/',
			'thumbnail' => WP_TRAVEL_ENGINE_FILE_URL . 'admin/css/images/travel-agency-pro-screenshot.jpg',
		),
		array(
			'title'     => 'Travel Agency Pro',
			'url'       => 'https://rarathemes.com/wordpress-themes/travel-agency-pro/',
			'thumbnail' => WP_TRAVEL_ENGINE_FILE_URL . 'admin/css/images/travel-agency-pro-screenshot.jpg',
		),
		array(
			'title'     => 'WP Tour Package',
			'url'       => 'https://rarathemes.com/wordpress-themes/tour-package/',
			'thumbnail' => WP_TRAVEL_ENGINE_FILE_URL . 'admin/css/images/tour package.png',
		),
		array(
			'title'     => 'Tour Operator',
			'url'       => 'https://rarathemes.com/wordpress-themes/tour-operator/',
			'thumbnail' => WP_TRAVEL_ENGINE_FILE_URL . 'admin/css/images/tour-operator.png',
		),
		array(
			'title'     => 'Travel Tour',
			'url'       => 'https://thebootstrapthemes.com/downloads/free-travel-tour-wordpress-theme/',
			'thumbnail' => WP_TRAVEL_ENGINE_FILE_URL . 'admin/css/images/travel-tour-pro.jpg',
		),
		array(
			'title'     => 'Travel Tour Pro',
			'url'       => 'https://thebootstrapthemes.com/downloads/travel-tour-pro/',
			'thumbnail' => WP_TRAVEL_ENGINE_FILE_URL . 'admin/css/images/travel-tour-pro.jpg',
		),
	);
}

/**
 * Get the excerpt content.
 *
 * @since 5.4.3
 */
function wptravelengine_get_the_trip_excerpt( $post_id = null, $length = 30 ): string {
	if ( ! $post_id ) {
		$post_id = get_the_ID();
	}
	$content = '';
	if ( has_excerpt( $post_id ) ) {
		return get_the_excerpt( $post_id );
	} else {
		$content = get_the_content( null, false, $post_id );

		if ( class_exists( '\Elementor\Plugin' ) ) {
			$elementor_page = Plugin::$instance->documents->get( $post_id );
			$settings       = $elementor_page->get_properties();
			if ( $elementor_page->is_built_with_elementor() && isset( $settings['support_wp_page_templates'] ) ) {
				$content = '';
			}
		}

		if ( empty( $content ) ) {
			$trip_settings = get_post_meta( $post_id, 'wp_travel_engine_setting', true );
			$content       = $trip_settings['tab_content']['1_wpeditor'] ?? '';
		}
	}

	return wp_trim_words( strip_shortcodes( $content ), $length, '...' );
}

/**
 * Print the excerpt content.
 *
 * @since 5.4.3
 */
function wptravelengine_the_trip_excerpt( $post_id = null, $length = 25 ) {
	if ( ! $post_id ) {
		$post_id = get_the_ID();
	}
	echo wp_kses_post( wptravelengine_get_the_trip_excerpt( $post_id, $length ) );
}

/**
 *
 * @since 5.5.0
 */
function wptravelengine_get_fa_icons() {
	$data = wp_cache_get( 'fa_icons', 'wptravelengine' );
	if ( ! $data ) {
		ob_start();
		include_once plugin_dir_path( WP_TRAVEL_ENGINE_FILE_PATH ) . 'assets/lib/fontawesome/icons.json';
		$data = json_decode( ob_get_clean(), true );
		wp_cache_set( 'fa_icons', $data, 'wptravelengine' );
	}

	return $data;
}

/**
 *
 * @since 5.5.0
 */
function wptravelengine_svg_by_fa_icon( $icon, $echo = true, $class_names = array() ) {
	$data = wptravelengine_get_fa_icons();

	if ( is_array( $icon ) ) {
		$new_icon = $icon['icon'] ?? '';
		$path     = ( $icon['path'] ?? '' ) ?: ( $data[ $new_icon ]['path'] ?? '' );
		if ( empty( $path ) ) {
			return $path;
		}
		$view_box = ( $icon['view_box'] ?? '' ) ?: ( $data[ $new_icon ]['viewBox'] ?? '' );
		$icon     = $new_icon;
	} else {
		$path = $data[ $icon ] ?? '';
		if ( empty( $path ) ) {
			return $path;
		}
		$view_box = $path['viewBox'] ?? '';
		$path     = $path['path'] ?? '';
	}

	$attr       = array(
		'fill'        => 'currentColor',
		'data-prefix' => substr( $icon, 0, 3 ),
		'data-icon'   => substr( $icon, 7, strlen( $icon ) ),
		'xmlns'       => 'http://www.w3.org/2000/svg',
		'class'       => implode( ' ', array_merge( array( 'svg-inline--fa' ), $class_names ) ),
		'viewBox'     => $view_box,
		'height'      => 24,
		'width'       => 24,
	);
	$attributes = array();
	foreach ( $attr as $attr_name => $attr_value ) {
		$attributes[] = "{$attr_name}=\"$attr_value\"";
	}

	$svg  = '<svg ';
	$svg .= implode( ' ', $attributes );
	$svg .= "><path d=\"{$path}\"/></svg>";

	if ( ! $echo ) {
		return $svg;
	}

	echo wte_esc_svg( $svg );
}

function wptravelengine_hidden_class( $hidden, $current = true, $echo = true ) {
	if ( $hidden === $current ) {
		if ( $echo ) {
			echo esc_attr( 'hidden' );
		}

		return 'hidden';
	}
}

/**
 * Returns instance of Plugin Settings.
 *
 * @return PluginSettings
 */
function wptravelengine_settings(): PluginSettings {
	return PluginSettings::make();
}

/**
 *
 * Returns Sorting options.
 *
 * @since 5.5.7
 */
function wptravelengine_get_sorting_options() {
	return apply_filters(
		'wp_travel_engine_archive_header_sorting_options',
		array(
			'latest'     => esc_html__( 'Recently Added', 'wp-travel-engine' ),
			'rating'     => esc_html__( 'Top Rated', 'wp-travel-engine' ),
			'price'      => esc_html__( 'Lowest Price First', 'wp-travel-engine' ),
			'price-desc' => esc_html__( 'Highest Price First', 'wp-travel-engine' ),
			'days'       => esc_html__( 'Shortest Duration First', 'wp-travel-engine' ),
			'days-desc'  => esc_html__( 'Longest Duration First', 'wp-travel-engine' ),
			'name'       => esc_html__( 'Alphabetical - A to Z', 'wp-travel-engine' ),
			'name-desc'  => esc_html__( 'Alphabetical - Z to A', 'wp-travel-engine' ),
			// ''       => esc_html__( 'Default Sorting', 'wp-travel-engine' ),
			// 'latest' => esc_html__( 'Latest', 'wp-travel-engine' ),
			// 'rating' => esc_html__( 'Most Reviewed', 'wp-travel-engine' ),
			// 'price'  => array(
			// 'label'   => esc_html__( 'Price', 'wp-travel-engine' ),
			// 'options' => array(
			// 'price'      => esc_html__( 'Low to High', 'wp-travel-engine' ),
			// 'price-desc' => esc_html__( 'High to Low', 'wp-travel-engine' ),
			// ),
			// ),
			// 'days'   => array(
			// 'label'   => esc_html__( 'Days', 'wp-travel-engine' ),
			// 'options' => array(
			// 'days'      => esc_html__( 'Low to High', 'wp-travel-engine' ),
			// 'days-desc' => esc_html__( 'High to Low', 'wp-travel-engine' ),
			// ),
			// ),
			// 'name'   => array(
			// 'label'   => esc_html__( 'Name', 'wp-travel-engine' ),
			// 'options' => array(
			// 'name'      => __( 'a - z', 'wp-travel-engine' ),
			// 'name-desc' => __( 'z - a', 'wp-travel-engine' ),
			// ),
			// ),
		)
	);
}

/**
 *
 * @since 5.5.7
 */
function wptravelengine_get_trip_taxonomies( $output = 'names' ) {
	$taxonomies = get_taxonomies( array( 'object_type' => array( WP_TRAVEL_ENGINE_POST_TYPE ) ), $output );

	return $taxonomies;
}

/**
 * Get User wishlists
 *
 * @since 5.5.7
 */
function wptravelengine_user_wishlists() {
	if ( is_user_logged_in() ) {
		$user_id        = get_current_user_id();
		$user_wishlists = get_user_meta( $user_id, 'wptravelengine_wishlists', true );
	} else {
		$user_wishlists = WTE()->session->get( 'user_wishlists' );
	}

	return is_array( $user_wishlists ) ? $user_wishlists : array();
}

/**
 *
 * System info.
 *
 * @since 5.6.0
 */
function wptravelengine_system_info() {
	$data = array();

	$data = array(
		array(
			'label' => __( 'Site Info', 'wp-travel-engine' ),
			'items' => array(
				'Site Title' => get_bloginfo( 'name', 'display' ),
				'Site URL'   => get_bloginfo( 'url', 'display' ),
				'Multisite'  => is_multisite() ? __( 'Yes', 'wp-travel-engine' ) : __( 'No', 'wp-travel-engine' ),
			),
		),
		array(
			'label' => __( 'WordPress Configuration', 'wp-travel-engine' ),
			'items' => array(
				'Version'             => get_bloginfo( 'version', 'display' ),
				'Language'            => get_locale(),
				'Permalink Structure' => get_option( 'permalink_structure' ),
				'Active Theme'        => wp_get_theme()->get( 'Name' ),
			),
		),
	);

	$extensions = array(
		'label' => __( 'Extensions', 'wp-travel-engine' ),
		'items' => array(),
	);

	$plugins = get_plugins();

	$wptravelengine_addons = array();
	if ( is_array( $plugins ) ) {
		foreach ( $plugins as $plugin ) {
			if ( preg_match( '/(WP Travel Engine)/', $plugin['Name'] ) ) {
				$wptravelengine_addons[ $plugin['Name'] ] = '<code>' . $plugin['Version'] . '</code>';
			}
		}
	}

	if ( ! empty( $wptravelengine_addons ) ) {
		$extensions = array(
			'label' => __( 'Extensions', 'wp-travel-engine' ),
			'items' => $wptravelengine_addons,
		);
		$data[]     = $extensions;
	}

	return $data;
}

/**
 * Defined payment status.
 *
 * @since 5.6.3
 */
function wptravelengine_payment_status( $status = null ) {
	$options = array(
		'abandoned'        => __( 'Abandoned', 'wp-travel-engine' ),
		'completed'        => __( 'Completed', 'wp-travel-engine' ),
		'complete'         => __( 'Completed', 'wp-travel-engine' ),
		'cancelled'        => __( 'Cancelled', 'wp-travel-engine' ),
		'cancel'           => __( 'Cancelled', 'wp-travel-engine' ),
		'captured'         => __( 'Captured', 'wp-travel-engine' ),
		'capture'          => __( 'Captured', 'wp-travel-engine' ),
		'check-received'   => __( 'Check Received', 'wp-travel-engine' ),
		'check-waiting'    => __( 'Waiting for Check', 'wp-travel-engine' ),
		'failed'           => __( 'Failed', 'wp-travel-engine' ),
		'partially-paid'   => __( 'Partially Paid', 'wp-travel-engine' ),
		'settlement'       => __( 'Settlement', 'wp-travel-engine' ),
		'pending'          => __( 'Pending', 'wp-travel-engine' ),
		'deny'             => __( 'Denied', 'wp-travel-engine' ),
		'expire'           => __( 'Expired', 'wp-travel-engine' ),
		'refunded'         => __( 'Refunded', 'wp-travel-engine' ),
		'revoked'          => __( 'Revoked', 'wp-travel-engine' ),
		'success'          => __( 'Success', 'wp-travel-engine' ),
		'voucher-waiting'  => __( 'Waiting for Voucher', 'wp-travel-engine' ),
		'voucher-awaiting' => __( 'Waiting for Voucher', 'wp-travel-engine' ),
		'voucher-received' => __( 'Voucher Received', 'wp-travel-engine' ),
	);

	$success_status = wptravelengine_success_payment_status();
	$pending_status = wptravelengine_pending_payment_status();
	$failed_status  = wptravelengine_failed_payment_status();

	$options = apply_filters( 'wp_travel_engine_payment_status_options', array_merge( $options, $success_status, $pending_status, $failed_status ) );

	if ( is_null( $status ) ) {
		return $options;
	}

	return $options[ $status ] ?? __( 'N/A', 'wp-travel-engine' );
}

/**
 * Defined booking status.
 *
 * @since 5.6.3
 */
function wptravelengine_booking_status( $status = null ) {
	$options = array(
		'booked'    => __( 'Booked', 'wp-travel-engine' ),
		'completed' => __( 'Completed', 'wp-travel-engine' ),
		'pending'   => __( 'Pending', 'wp-travel-engine' ),
		'canceled'  => __( 'Cancelled', 'wp-travel-engine' ),
	);
	$options = apply_filters( 'wp_travel_engine_booking_status_options', $options );

	if ( is_null( $status ) ) {
		return $options;
	}

	return $options[ $status ] ?? __( 'N/A', 'wp-travel-engine' );
}

/**
 * Get Page by title.
 *
 * @since 5.6.9
 */
function wptravelengine_get_page_by_title( $title, $post_type = 'page' ) {
	$pages = get_posts(
		array(
			'post_type'      => $post_type,
			'post_status'    => 'publish',
			'posts_per_page' => 1,
			'title'          => $title,
		)
	);

	return $pages[0] ?? null;
}

/**
 * Adds Admin Menu Separator.
 *
 * @param number $position Position.
 *
 * @return void
 * @since 5.7
 */
function wptravelengine_add_admin_menu_separator( $position ) {

	global $menu;

	$_menu = $menu;
	ksort( $_menu, SORT_NUMERIC );

	$previous_index       = 0;
	$add_bottom_separator = false;
	foreach ( $_menu as $index => $item ) {
		if ( ( $item[2] === 'edit.php?post_type=booking' ) || $add_bottom_separator ) {
			if ( substr( $item[2], 0, 9 ) !== 'separator' ) {
				$menu[ ( ( $previous_index + $index ) / 2 ) . '' ] = array(
					'',
					'read',
					"separator{$index}",
					'',
					'wp-menu-separator',
				);
			}
		}

		if ( $add_bottom_separator ) {
			break;
		}
		$previous_index = $index;

		if ( $item[2] === 'wp-travel-engine-analytics' ) {
			$add_bottom_separator = true;
		}
	}
}

/**
 * @param array $fields Form fields.
 *
 * @param bool  $use_legacy_template Use legacy template.
 *
 * @return void
 * @since 5.7.4
 */
function wptravelengine_render_form_fields( array $fields, bool $use_legacy_template = true ) {
	$instance = wptravelengine_form_field( $use_legacy_template );
	$instance->init( $fields )->render();
}

/**
 * @param bool $use_legacy_template
 *
 * @return FormField
 * @since 5.7.4
 */
function wptravelengine_form_field( bool $use_legacy_template = true ): FormField {
	// Include the form class - framework.
	include_once \WP_TRAVEL_ENGINE_ABSPATH . '/includes/lib/wte-form-framework/class-wte-form.php';

	// form fields initialize.
	return new FormField( $use_legacy_template );
}

/**
 * @return Cart
 * @since 5.7.4
 */
function wptravelengine_cart(): Cart {
	global $wte_cart;

	return $wte_cart;
}

/**
 * Get the plugin info.
 *
 * @since 5.8.5
 */
function wptravelengine_plugin_file_info(): array {
	static $plugin_data = null;

	if ( null !== $plugin_data ) {
		return $plugin_data;
	}

	$plugin_data = array(
		basename( dirname( WP_TRAVEL_ENGINE_FILE_PATH ) ),
		pathinfo( WP_TRAVEL_ENGINE_FILE_PATH, PATHINFO_FILENAME ),
	);

	return $plugin_data;
}

/**
 * Escape iframe tag.
 *
 * @param string $iframe_tag Iframe tag.
 *
 * @return string
 * @since 5.9.2
 */
function wptravelengine_esc_iframe( $iframe_tag ) {
	$allowed_tags = array(
		'iframe' => array(
			'src'             => true,
			'width'           => true,
			'height'          => true,
			'frameborder'     => true,
			'allow'           => true,
			'allowfullscreen' => true,
		),
	);

	return wp_kses( $iframe_tag, $allowed_tags );
}

/**
 * Backward Compatibility - This function may be used on some extensions.
 *
 * @deprecated 6.0.0
 */
function run_Wp_Travel_Engine() { // phpcs:ignore WordPress.NamingConventions.ValidFunctionName.FunctionNameInvalid
	_deprecated_function( __FUNCTION__, '6.0.0', 'WPTravelEngine' );
	WPTravelEngine()->run();
}

/**
 * Generate a random hex.
 *
 * @return string
 * @since 6.0.0
 */
function wptravelengine_random_hex(): string {
	$chars = '0123456789abcdef';

	return $chars[ mt_rand( 0, 15 ) ];
}

/**
 * Generate hext to rgb.
 *
 * @param string $hex Hex color.
 *
 * @return string
 * @since 6.6.1
 */
function wptravelengine_hex_to_rgb( string $hex ) {
	// Remove the "#" if present
	$hex = ltrim( $hex, '#' );

	// Handle shorthand format like #f60
	if ( strlen( $hex ) === 3 ) {
		$hex = $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2];
	}

	// Convert to RGB components
	if ( strlen( $hex ) === 6 ) {
		list($r, $g, $b) = array(
			hexdec( substr( $hex, 0, 2 ) ),
			hexdec( substr( $hex, 2, 2 ) ),
			hexdec( substr( $hex, 4, 2 ) ),
		);
		return '' . $r . ', ' . $g . ', ' . $b . '';
	}

	// Invalid format
	return null;
}

/**
 * Generate a unique ID.
 *
 * @param string $pattern
 *
 * @return string
 * @since 6.0.0
 */
function wptravelengine_unique_id( string $pattern = 'xxxx-xxxxx-xxxxx-xxxx' ) {

	// Initialize the result
	$result = '';

	// Iterate over each character in the pattern
	for ( $i = 0; $i < strlen( $pattern ); $i++ ) {
		if ( $pattern[ $i ] === 'x' ) {
			$result .= wptravelengine_random_hex();
		} else {
			$result .= $pattern[ $i ];
		}
	}

	return $result;
}

/**
 * Generate a key using hash.
 *
 * @param string $input Input string.
 *
 * @return string
 * @since 6.0.0
 */
function wptravelengine_generate_key( string $input ): string {

	$auth_salt = defined( 'AUTH_SALT' ) ? AUTH_SALT : '';
	$hash      = hash( 'sha256', $input . $auth_salt );

	return sprintf(
		'%s-%s-%s-%s',
		substr( $hash, 0, 4 ),
		substr( $hash, 4, 5 ),
		substr( $hash, 9, 5 ),
		substr( $hash, 14, 4 )
	);
}

/**
 * @return void
 * @since 6.5.2
 */
function wptravelengine_create_events_table() {
	global $wpdb;

	$table_name      = $wpdb->prefix . 'wptravelengine_events';
	$charset_collate = $wpdb->get_charset_collate();

	$sql = "CREATE TABLE $table_name (
		id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
		object_id BIGINT(20) UNSIGNED NOT NULL,
		object_type VARCHAR(50) NOT NULL,
		event_name VARCHAR(255) NOT NULL,
		event_data LONGTEXT NOT NULL,
		trigger_time DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
		event_created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
		PRIMARY KEY (id),
		UNIQUE KEY unique_event (object_id, event_name, object_type)
	) $charset_collate;";

	require_once ABSPATH . 'wp-admin/includes/upgrade.php';
	dbDelta( $sql );
}