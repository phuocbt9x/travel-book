<?php

namespace WPTravelEngine;

use Wp_Travel_Engine_Activator;
use Wp_Travel_Engine_Admin;
use Wp_Travel_Engine_Deactivator;
use WP_Travel_Engine_Enquiry_Forms;
use Wp_Travel_Engine_Loader;
use Wp_Travel_Engine_Public;
use WPTravelEngine\Core\Booking\BookingProcess;
use WPTravelEngine\Core\Booking\ExternalPayment;
use WPTravelEngine\Core\Cart\Cart;
use WPTravelEngine\Core\Controllers\RestAPI\V2\Settings;
use WPTravelEngine\Core\Controllers\RestAPI\V2\Trip;
use WPTravelEngine\Core\Models\Post\Booking;
use WPTravelEngine\Core\Models\Review;
use WPTravelEngine\Core\Shortcodes\CheckoutV2;
use WPTravelEngine\Core\Shortcodes\General;
use WPTravelEngine\Core\Shortcodes\ThankYou;
use WPTravelEngine\Core\Shortcodes\TravelerInformation;
use WPTravelEngine\Core\Shortcodes\TripCheckout;
use WPTravelEngine\Core\Shortcodes\TripsList;
use WPTravelEngine\Core\Shortcodes\UserAccount;
use WPTravelEngine\Core\Updates;
use WPTravelEngine\Core\SEO;
use WPTravelEngine\Filters\Events;
use WPTravelEngine\Filters\SettingsAPISchema;
use WPTravelEngine\Filters\Template;
use WPTravelEngine\Filters\TripAPISchema;
use WPTravelEngine\Filters\TripMetaTabs;
use WPTravelEngine\Helpers\Functions;
use WPTravelEngine\Helpers\Translators;
use WPTravelEngine\Modules\CouponCode;
use WPTravelEngine\Modules\Filters as CustomFilters;
use WPTravelEngine\Modules\TripCode;
use WPTravelEngine\Modules\TripSearch;
use WPTravelEngine\Optimizer\Optimizer;
use WPTravelEngine\Registers\ShortcodeRegistry;
use WPTravelEngine\Traits\Singleton;
use WPTravelEngine\Email\Email;
use function WTE\Upgrade500\wte_process_migration;
use const WP_TRAVEL_ENGINE_FILE_PATH;
use WPTravelEngine\Core\Models\Post\TripPackages;
use WPTravelEngine\Core\Controllers\RestAPI\V2\Booking as BookingController;
use WPTravelEngine\Core\Models\Post\Payment;

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @since      1.0.0
 *
 * @package    Wp_Travel_Engine
 * @subpackage Wp_Travel_Engine/includes
 */
defined( 'ABSPATH' ) || exit;

/**
 * Main Class.
 */
final class Plugin {

	use Singleton;

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Wp_Travel_Engine_Loader $loader Maintains and registers all hooks for the plugin.
	 */
	protected Wp_Travel_Engine_Loader $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string $plugin_name The string used to uniquely identify this plugin.
	 */
	protected string $plugin_name = 'wp-travel-engine';

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string $version The current version of the plugin.
	 */
	protected string $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {

		spl_autoload_register( array( $this, 'autoload' ) );

		$GLOBALS['wptravelengine_template_args'] = array();

		$this->version = WP_TRAVEL_ENGINE_VERSION;

		$this->define_constants();
		$this->load_dependencies();

		$this->initialize_freemius();

		$this->loader = new Wp_Travel_Engine_Loader();

		$this->set_locale();

		$this->hooks();

		$this->init_shortcodes();

		/**
		 * This fetches the notice from the server and displays it in the admin dashboard.
		 *
		 * @since 6.5.7
		 */
		new AdminNotice();

		$template_filters = new Template();
		$template_filters->hooks();

		$schema_filters = new SettingsAPISchema();
		$schema_filters->hooks();

		new Events();

		TripAPISchema::instance();

		TripMetaTabs::instance();

		new Blocks\Blocks();

		// Modules.
		new CouponCode();
		new CustomFilters();
		new TripCode();
		new TripSearch();

		new Translators();

		$this->set_cart();
		$this->run();

		$optimizer = new Optimizer();
		$optimizer->hooks();

		$static_strings = new Core\Models\Settings\StaticStrings();
		$static_strings->hooks();

		// SEO.
		new SEO();

		// Register shutdown handler for automatic error capture (zero overhead).
		Logger\ErrorHandlers\ShutdownErrorHandler::register();

		$this->set_class_aliases();
	}

	/**
	 * Define constants.
	 *
	 * @return void
	 * @since 5.0.0
	 */
	protected function define_constants() {
		define( 'WP_TRAVEL_ENGINE_BASE_PATH', dirname( WP_TRAVEL_ENGINE_FILE_PATH ) );
		define( 'WP_TRAVEL_ENGINE_ABSPATH', dirname( WP_TRAVEL_ENGINE_FILE_PATH ) . '/' );
		define( 'WP_TRAVEL_ENGINE_IMG_PATH', dirname( WP_TRAVEL_ENGINE_FILE_PATH ) . '/admin/css/icons' );
		define( 'WP_TRAVEL_ENGINE_TEMPLATE_PATH', dirname( WP_TRAVEL_ENGINE_FILE_PATH ) . '/includes/templates' );
		define( 'WP_TRAVEL_ENGINE_FILE_URL', plugin_dir_url( WP_TRAVEL_ENGINE_FILE_PATH ) );
		define( 'WP_TRAVEL_ENGINE_POST_TYPE', 'trip' );
		define( 'WP_TRAVEL_ENGINE_TRIP_VERSION', '2.0.0' );
		define( 'WP_TRAVEL_ENGINE_URL', rtrim( plugin_dir_url( WP_TRAVEL_ENGINE_FILE_PATH ), '/' ) );
		define( 'WP_TRAVEL_ENGINE_IMG_URL', rtrim( plugin_dir_url( WP_TRAVEL_ENGINE_FILE_PATH ), '/' ) );
		define( 'WP_TRAVEL_ENGINE_STORE_URL', 'https://wptravelengine.com/' );
		define( 'WP_TRAVEL_ENGINE_PLUGIN_LICENSE_PAGE', 'wp_travel_engine_license_page' );
		define( 'WPTRAVELENGINE_UPDATES_DATA_PATH', dirname( WP_TRAVEL_ENGINE_FILE_PATH ) . '/admin/partials/plugin-updates/getting-started/' . implode( '', array_slice( explode( '.', WP_TRAVEL_ENGINE_VERSION ), 0, 2 ) ) . '0' );
		define( 'WP_TRAVEL_ENGINE_PAYMENT_DEBUG', ( get_option( 'wp_travel_engine_settings', array() )['payment_debug'] ?? 'no' ) === 'yes' );
	}

	private function check_version() {
		if ( ! defined( 'IFRAME_REQUEST' ) ) {
			if ( version_compare( get_option( 'wptravelengine_version', '0.0.0' ), WP_TRAVEL_ENGINE_VERSION, '<' ) ) {
				update_option( 'wptravelengine_version', WP_TRAVEL_ENGINE_VERSION );
			}
			if ( version_compare( get_option( 'wptravelengine_trip_version', '0.0.0' ), WP_TRAVEL_ENGINE_TRIP_VERSION, '<' ) ) {
				update_option( 'wptravelengine_trip_version', WP_TRAVEL_ENGINE_TRIP_VERSION );
			}
			if ( ! get_option( 'wptravelengine_since', false ) ) {
				update_option( 'wptravelengine_since', WP_TRAVEL_ENGINE_VERSION );
			}
		}
	}

	/**
	 * Hooks into WP `init` hook.
	 *
	 * @return void
	 * @since 6.0.0
	 */
	protected function add_init_hooks() {
		add_action( 'init', array( $this, 'wte_login_integration' ) ); // check for the social logins
		add_action( 'init', array( $this, 'process_booking' ), 12 );
		add_action( 'init', array( BookingProcess::class, 'initialize_legacy_booking_hooks' ) );
		add_action(
			'init',
			function () {
				// Deactivate core integrated Plugin.
				foreach (
				array(
					'WTE_TRIP_CODE_FILE_PATH'              => __( 'Trip Code', 'wp-travel-engine' ),
					'WP_TRAVEL_ENGINE_COUPONS_PLUGIN_FILE' => __( 'Coupon Code', 'wp-travel-engine' ),
					'WTE_ADVANCED_SEARCH_FILE_PATH'        => __( 'Advanced Search', 'wp-travel-engine' ),
				) as $constant_name => $plugin_name
				) {
					if ( defined( $constant_name ) ) {
						$plugin = constant( $constant_name );
						deactivate_plugins( $plugin );

						add_action(
							'admin_notices',
							function () use ( $plugin_name ) {
								printf(
									'<div id="message" class="notice notice-info is-dismissible"><p>%1$s</p><button type="button" class="notice-dismiss"><span class="screen-reader-text">%2$s</span></button></div>',
									esc_html( sprintf( __( '%1$s has been automatically deactivated, the feature providing by the plugin is now available in the WP Travel Engine Core.', 'wp-travel-engine' ), $plugin_name ) ),
									esc_html__( 'Dismiss this notice.', 'wp-travel-engine' )
								);
							}
						);
					}
				}

				/**
				 * Set default value for display_new_trip_listing as yes.
				 *
				 * @since 6.6.0
				 */
				$settings         = wptravelengine_settings();
				$new_trip_listing = $settings->get( 'display_new_trip_listing' );
				if ( 'yes' !== $new_trip_listing ) {
					$settings->set( 'display_new_trip_listing', 'yes' );
					$settings->save();
				}
			}
		);

		add_action( 'admin_init', array( \WTE_Ajax::class, 'ajax_request_middleware' ) );
		add_action( 'admin_init', array( $this, 'plugin_inline_update_notices' ) );
		add_action( 'admin_notices', array( $this, 'booking_dashboard_notice' ), 99 );
		add_action( 'comment_post', array( $this, 'on_insert_comment' ) );
	}

	/**
	 * @return void
	 * @since 6.5.2
	 */
	public function on_insert_comment( $comment_id ) {

		$review = Review::instance( $comment_id );

		if ( ! $review instanceof Review ) {
			return;
		}

		Events::review_created( $review );
	}

	/**
	 * Handle plugin update notices.
	 * Show plugins/addons compatibility notices.
	 *
	 * @return void
	 * @since 6.0.0
	 */
	public function plugin_inline_update_notices() {
		new Updates();
	}

	/**
	 * Prints booking dashboard notice.
	 *
	 * @return void
	 * @since 6.4.0
	 */
	public function booking_dashboard_notice() {
		$screen = get_current_screen();
		if ( 'booking' !== $screen->id ) {
			return;
		}
		$class   = 'notice notice-info is-dismissible';
		$message = sprintf(
			'<p><strong>%1$s</strong></p>',
			__( 'Notice: Please be aware that you are responsible for any mistakes, payment issues, or customer concerns that may arise when editing the booking summary. Double-check your changes, update payment settings, and contact support if you need assistance.', 'wp-travel-engine' )
		);

		printf(
			'<div class="%1$s">%2$s</div>',
			esc_attr( $class ),
			wp_kses(
				$message,
				array(
					'p'      => array(),
					'strong' => array(),
					'br'     => array(),
				)
			)
		);
	}

	/**
	 * @return void
	 */
	public function process_booking() {
		if ( ExternalPayment::is_request() ) {
			new ExternalPayment( Functions::create_request( 'GET' ) );
		} elseif ( BookingProcess::is_booking_request() ) {
			global $wte_cart;
			new BookingProcess( Functions::create_request( 'POST' ), $wte_cart );
		} elseif ( BookingProcess::is_gateway_callback() ) {
			BookingProcess::process_gateway_callback();
		} elseif ( BookingProcess::is_traveler_information_save_request() ) {
			$temp_tf_redirection = WTE()->session->get( 'temp_tf_direction' );
			if ( ! empty( $temp_tf_redirection ) ) {
				list( $booking_id, $payment_id ) = explode( '|', $temp_tf_redirection );

				if ( $booking_id ) {
					Booking::save_travellers_information( $booking_id );
				}
				if ( $payment_id ) {
					$redirect_url = wptravelengine_get_page_url( 'wp_travel_engine_thank_you' );
					$payment      = wptravelengine_get_payment( $payment_id );

					wp_redirect( add_query_arg( array( 'payment_key' => $payment->get_payment_key() ), $redirect_url ) );
					exit;
				}
			}
		}
	}

	/**
	 * Add Color Settings to the body.
	 *
	 * @return void
	 * @since 6.6.1
	 */
	public function add_color_settings_to_body() {
		$appearance = get_option( 'wptravelengine_appearance' );
		if ( ! empty( $appearance['primary_color'] ) ) {
			echo '<style>body{--wpte-primary-color: ' . $appearance['primary_color'] . '; --wpte-primary-color-rgb: ' . $appearance['primary_color_rgb'] . ';}</style>';
		}

		if ( ! empty( $appearance['discount_color'] ) ) {
			echo '<style>body{--wpte-discount-color: ' . $appearance['discount_color'] . ';}</style>';
		}

		if ( ! empty( $appearance['featured_color'] ) ) {
			echo '<style>body{--wpte-featured-color: ' . $appearance['featured_color'] . ';}</style>';
		}

		if ( ! empty( $appearance['icon_color'] ) ) {
			echo '<style>body{--wpte-icon-color: ' . $appearance['icon_color'] . ';}</style>';
		}
	}

	protected function hooks() {
		$this->define_admin_hooks();
		$this->define_public_hooks();

		$this->add_init_hooks();

		add_filter( 'is_wptravelengine_active', '__return_true' );

		/**
		 * Add query vars.
		 *
		 * @since 6.7.6
		 */
		add_filter(
			'query_vars',
			function ( $vars ) {
				$vars[] = 'wte_id';
				$vars[] = 'payment_key';
				return $vars;
			}
		);

		add_action( 'wp_footer', array( $this, 'add_booking_modal_container' ) );

		add_filter( 'widget_text', 'do_shortcode' );
		add_filter( 'meta_content', 'wptexturize' );
		add_filter( 'meta_content', 'convert_smilies' );
		add_filter( 'meta_content', 'convert_chars' );
		add_filter( 'meta_content', 'shortcode_unautop' );
		add_filter( 'meta_content', 'prepend_attachment' );
		add_filter( 'meta_content', 'do_shortcode' );
		add_filter( 'term_description', 'wpautop' );

		add_action( 'wp_head', array( $this, 'add_color_settings_to_body' ) );

		/**
		 * Filter for resend purchase receipt to send modified post meta data to the email template.
		 *
		 * @param array $mail_tags
		 * @param int $payment_id
		 * @param int $booking_id
		 *
		 * @return array
		 * @since enhancement/booking-details
		 */
		add_action( 'plugins_loaded', array( $this, 'plugins_loaded' ), 20 );

		/**
		 * Cron Job.
		 *
		 * @since 6.5.2
		 */
		add_filter(
			'cron_schedules',
			function ( $schedules ) {
				$schedules['every_minute'] = array(
					'interval' => 60,
					'display'  => 'Every Minute',
				);
				return $schedules;
			}
		);

		add_action( 'plugins_loaded', array( $this, 'add_event_table' ) );

		add_action(
			'wp',
			function () {
				global $post;

				if ( $post ) {
					$GLOBALS['wtetrip'] = Posttype\Trip::instance( $post->ID );
				}
			}
		);

		add_filter( 'body_class', array( $this, 'body_class' ) );

		register_activation_hook(
			WP_TRAVEL_ENGINE_FILE_PATH,
			function () {
				require_once plugin_dir_path( WP_TRAVEL_ENGINE_FILE_PATH ) . 'includes/class-wp-travel-engine-activator.php';
				Wp_Travel_Engine_Activator::activate();

				if ( version_compare( WP_TRAVEL_ENGINE_VERSION, '4.2.1', '>=' ) ) {
					include_once sprintf( '%s/upgrade/500.php', WP_TRAVEL_ENGINE_BASE_PATH );
					wte_process_migration();
				}

				Events::schedule();
				wptravelengine_create_events_table();
			}
		);

		register_deactivation_hook(
			WP_TRAVEL_ENGINE_FILE_PATH,
			function () {
				require_once plugin_dir_path( WP_TRAVEL_ENGINE_FILE_PATH ) . 'includes/class-wp-travel-engine-deactivator.php';
				Wp_Travel_Engine_Deactivator::deactivate();
				/*
				 * @since 6.5.2
				 */
				wp_clear_scheduled_hook( 'wptravelengine_check_events' );
			}
		);

		add_action(
			'activated_plugin',
			function () {
				$path    = str_replace( WP_CONTENT_DIR . '/plugins/', '', WP_TRAVEL_ENGINE_FILE_PATH );
				$plugins = get_option( 'active_plugins', array() );
				if ( ! empty( $plugins ) ) {
					$key = array_search( $path, $plugins, true );
					if ( ! empty( $key ) ) {
						array_splice( $plugins, $key, 1 );
						array_unshift( $plugins, $path );
						update_option( 'active_plugins', $plugins );
					}
				}
			}
		);

		// add_action( 'wp_enqueue_scripts', array( \WPTravelEngine\Assets::instance(), 'wp_enqueue_scripts' ) );
		add_action( 'admin_enqueue_scripts', array( Assets::instance(), 'admin_enqueue_scripts' ) );

		add_action(
			'admin_init',
			function () {
				// Check version.
				$this->check_version();
			}
		);

		add_filter(
			'term_name',
			function ( $name, $tag ) {
				if ( isset( $tag->{'taxonomy'} ) && 'trip-packages-categories' === $tag->{'taxonomy'} ) {
					$primary_category = get_option( 'primary_pricing_category', 0 );
					if ( $primary_category == $tag->term_id ) {
						$name .= ' — &#128974;';
					}
				}

				return $name;
			},
			10,
			2
		);

		add_action(
			'init',
			function () {

				// Email Template preview.
				// phpcs:disable
				if ( wte_array_get( $_REQUEST, '_action', '' ) == 'email-template-preview' ) {
					if ( ! isset( $_REQUEST[ 'pid' ] ) ) {
						return;
					}

					// Mail class.
					require_once plugin_dir_path( WP_TRAVEL_ENGINE_FILE_PATH ) . 'includes/class-wp-travel-engine-emails.php';

					( new Email() )->template_preview( wte_clean( wp_unslash( $_REQUEST[ 'pid' ] ) ), wte_clean( wp_unslash( wte_array_get( $_REQUEST, 'template_type', 'order' ) ) ), wte_clean( wp_unslash( wte_array_get( $_REQUEST, 'to', 'customer' ) ) ) );
				}

				if ( wte_array_get( $_REQUEST, '_action', '' ) == 'wte-email-template-update' ) {
					if ( ! isset( $_REQUEST[ 'field' ] ) ) {
						return;
					}
					switch ( $_REQUEST[ 'field' ] ) {
						case 'email.sales_wpeditor':
							$settings                                = get_option( 'wp_travel_engine_settings', array() );
							$settings[ 'email' ][ 'sales_wpeditor' ] = '';
							update_option( 'wp_travel_engine_settings', $settings );
							update_option( 'payment_notification_admin_version', '2.0.0' );
							break;
						case 'email.purchase_wpeditor':
							$settings                                   = get_option( 'wp_travel_engine_settings', array() );
							$settings[ 'email' ][ 'purchase_wpeditor' ] = '';
							update_option( 'wp_travel_engine_settings', $settings );
							update_option( 'payment_notification_customer_version', '2.0.0' );
							break;
					}
				}
				// phpcs:enable
			}
		);

		// @TODO: Move to form Editor
		add_filter(
			'wte_booking_mail_tags',
			function ( $mail_tags, $payment_id ) {
				$booking_id = get_post_meta( $payment_id, 'booking_id', ! 0 );
				$booking    = get_post( $booking_id );
				if ( is_null( $booking ) || 'booking' !== $booking->post_type ) {
					return $mail_tags;
				}

				$additional_fields = wte_array_get( get_post_meta( $booking->ID, 'wptravelengine_billing_details', ! 0 ), null, array() );

				foreach ( $additional_fields as $field_name => $field_value ) {
					if ( is_array( $field_value ) ) {
						$field_value = implode( ',', $field_value );
					}
					$mail_tags[ '{' . $field_name . '}' ] = is_array( $field_value ) ? implode( ',', $field_value ) : $field_value;
				}

				// Move to Discount Coupon.
				// Discount Tags.
				$mail_tags['{discount_name}']   = '';
				$mail_tags['{discount_amount}'] = '';
				$mail_tags['{discount_sign}']   = '';
				$mail_tags['{discount_value}']  = '';

				if ( isset( $booking->cart_info['discounts'] ) ) {
					$discounts = $booking->cart_info['discounts'];
					if ( ! is_array( $discounts ) || empty( $discounts ) ) {
						return $mail_tags;
					}
					$discount  = (object) array_shift( $discounts );
					$cart_info = $booking->cart_info;

					$mail_tags['{discount_name}']   = $discount->name;
					$mail_tags['{discount_amount}'] = 'percentage' === $discount->type ? wte_get_formated_price( ( + $cart_info['subtotal'] * ( + $discount->value ) / 100 ), $cart_info['currency'] ) : wte_get_formated_price( $discount->value, $cart_info['currency'] );
					$mail_tags['{discount_sign}']   = 'percentage' === $discount->type ? '%' : $cart_info['currency'];
					$mail_tags['{discount_value}']  = 'percentage' === $discount->type ? $discount->value : wte_get_formated_price( $discount->value, $cart_info['currency'] );
				}

				return $mail_tags;
			},
			11,
			2
		);

		add_filter( 'extra_theme_headers', array( $this, 'plugin_headers' ) );
		add_filter( 'extra_plugin_headers', array( $this, 'plugin_headers' ) );

		// Show changelog for 5.0.
		add_filter( 'wte_show_changelog_for_500', '__return_true' );
		add_filter( 'wte_show_changelog_for_550', '__return_true' );

		add_filter(
			'wp_kses_allowed_html',
			function ( $allowedtags, $context ) {
				if ( is_array( $context ) ) {
					return $allowedtags;
				}
				switch ( $context ) {
					case 'wte_iframe':
						return array(
							'iframe' => array(
								'src'             => array(),
								'width'           => array(),
								'height'          => array(),
								'style'           => array(),
								'allowfullscreen' => array(),
								'loading'         => array(),
							),
						);
					case 'wte_formats':
						return array(
							'a'      => array(
								'href'   => array(),
								'target' => array(),
								'class'  => array(),
								'title'  => array(),
							),
							'p'      => array(
								'class' => array(),
							),
							'b'      => array(),
							'i'      => array(),
							'code'   => array(),
							'span'   => array(),
							'em'     => array(),
							'strong' => array(),
						);
					case 'allowed_price_html':
						return array(
							'span'   => array(
								'class'      => array(),
								'data-value' => array(),
							),
							'del'    => array(),
							'em'     => array(),
							'strong' => array(),
							'b'      => array(),
						);
					default:
						return $allowedtags;
				}
			},
			10,
			2
		);

		add_action(
			'wp_trash_post',
			function ( $post_id ) {
				$_post_type = get_post_type( $post_id );
				if ( 'booking' === $_post_type ) {
					Booking::trashing_booking( $post_id );
				}
			}
		);

		add_action(
			'untrashed_post',
			function ( $post_id ) {
				$_post_type = get_post_type( $post_id );
				if ( 'booking' === $_post_type ) {
					Booking::untrashing_booking( $post_id );
				}
			}
		);

		/**
		 * System File Downloader.
		 */
		add_action(
			'admin_init',
			function () {
				if ( isset( $_GET['wte_action'], $_GET['_nonce'] ) && 'download_system_info' === wp_unslash( $_GET['wte_action'] ) ) {
					$nonce = sanitize_text_field( wp_unslash( $_GET['_nonce'] ) );
					if ( wp_verify_nonce( $nonce, 'wte_download_system_info' ) ) {
						ob_start();
						$response = wptravelengine_system_info();
						ob_end_flush();
						if ( ! headers_sent() ) {
							header( 'Content-Type: application/json; charset=' . get_option( 'blog_charset' ) );
							status_header( 200 );
						}
						echo wp_json_encode( $response, JSON_PRETTY_PRINT );
						die;
					}
				}
			}
		);

		add_action( 'rest_api_init', array( $this, 'rest_api_init' ) );
		/**
		 * Add extra email tags for services.
		 *
		 * @since 6.2.0
		 */
		add_filter( 'emails-admin-fields', array( $this, 'wte_extra_services_email_tags' ) );

		/**
		 * Modify the display name of the WTE Customer role
		 *
		 * @since 6.4.0
		 */
		add_filter( 'editable_roles', array( $this, 'modify_role_display' ) );

		/**
		 * Update the paid amount of the booking
		 *
		 * @since v6.6.4
		 */
		add_action( 'wp_travel_engine_after_remaining_payment_process_completed', array( $this, 'update_paid_amount' ) );
	}

	/**
	 * Update the paid amount of the booking.
	 *
	 * @since v6.6.4
	 */
	public function update_paid_amount( $booking_id ) {
		$booking     = new Booking( $booking_id );
		$payments    = $booking->get_meta( 'payments' );
		$paid_amount = 0;
		if ( is_array( $payments ) && ! empty( $payments ) ) {
			foreach ( $payments as $payment ) {
				$payment_id   = Payment::make( $payment );
				$paid_amount += $payment_id->get_amount();
			}
		}

		$booking->set_meta( 'paid_amount', $paid_amount );
		$booking->save();
	}

	/**
	 * Modify the display name of the WTE Customer role
	 *
	 * @param array $roles The array of editable roles
	 *
	 * @return array The modified array of editable roles
	 * @since 6.4.0
	 */
	public function modify_role_display( $roles ) {
		if ( isset( $roles['wp-travel-engine-customer'] ) ) {
			$roles['wp-travel-engine-customer']['name'] = __( 'WTE-Customer', 'wp-travel-engine' );
		}

		return $roles;
	}

	/**
	 * @return void
	 * @since 6.5.2
	 */
	public function add_event_table() {
		if ( version_compare( get_option( 'wptravelengine_version' ), '6.6.0', '<' ) ) {
			wptravelengine_create_events_table();
			Events::schedule();
		}
	}

	/**
	 * Get formatted package name
	 */
	private function get_package_name( $booking_id, $trip ) {
		$package_name = get_post_meta( $booking_id, 'package_name', true );
		if ( empty( $package_name ) ) {
			return '';
		}

		$trip_packages = new TripPackages( $trip );
		foreach ( $trip_packages as $package ) {
			if ( $package_name == $package->ID ) {
				return $package->post->post_title;
			}
		}

		return '';
	}

	/**
	 * Render trip dates section
	 */
	private function render_trip_dates( $trip_dates ) {
		if ( empty( $trip_dates ) ) {
			return;
		}
		?>
		<tr>
			<td><?php esc_html_e( 'Trip Date', 'wp-travel-engine' ); ?></td>
			<td class="alignright"><?php echo esc_html( $trip_dates['start_date'] ?? '' ); ?></td>
		</tr>
		<?php
		if ( ! empty( $trip_dates['end_date'] ) ) :
			?>
			<tr>
				<td><?php esc_html_e( 'Trip End Date', 'wp-travel-engine' ); ?></td>
				<td class="alignright"><?php echo esc_html( $trip_dates['end_date'] ); ?></td>
			</tr>
			<?php
		endif;
	}

	/**
	 * Render traveller pricing details
	 */
	private function render_traveller_pricing( $pricing_data, $currency ) {
		if ( ! is_array( $pricing_data ) ) {
			return;
		}

		foreach ( $pricing_data as $detail ) {
			$price    = $detail['price'] ?? 0;
			$quantity = intval( $detail['quantity'] ?? 0 );
			$sum      = $detail['sum'] ?? 0;
			?>
			<tr>
				<td>
					<?php
					printf(
						'%s: %d x $%s = %s',
						esc_html( $detail['label'] ?? '' ),
						$quantity,
						number_format( $price, 2 ),
						esc_html( $currency ) . number_format( $sum, 2 )
					);
					?>
				</td>
			</tr>
			<?php
		}
	}

	/**
	 * Render extra services section
	 */
	private function render_extra_services( $extra_data, $currency ) {
		if ( ! is_array( $extra_data ) ) {
			return;
		}

		foreach ( $extra_data as $extra ) {
			$price    = $extra['price'] ?? 0;
			$quantity = intval( $extra['qty'] ?? 0 );
			$total    = $price * $quantity;
			?>
			<tr>
				<td>
					<?php
					printf(
						'%s: %d x $%s = %s',
						esc_html( $extra['extra_service'] ?? '' ),
						$quantity,
						number_format( $price, 2 ),
						esc_html( $currency ) . number_format( $total, 2 )
					);
					?>
				</td>
			</tr>
			<?php
		}
	}

	/**
	 * Render cost summary section
	 */
	private function render_cost_summary( $line_items, $currency ) {
		// Subtotal
		?>
		<tr class="title wpte-booking-subtotal">
			<td colspan="1"><?php esc_html_e( 'Subtotal', 'wp-travel-engine' ); ?></td>
			<td><?php echo esc_html( $currency ) . number_format( $line_items['totals']['subtotal'] ?? 0, 2 ); ?></td>
		</tr>

		<?php
		// Discounts
		if ( ! empty( $line_items['discounts'] ) ) {
			foreach ( $line_items['discounts'] as $discount ) {
				?>
				<tr class="wpte-booking-discount">
					<td><?php printf( esc_html__( 'Discount (%s)', 'wp-travel-engine' ), esc_html( $discount['name'] ?? '' ) ); ?></td>
					<td>-<?php echo esc_html( $currency ) . number_format( $discount['value'] ?? 0, 2 ); ?></td>
				</tr>
				<?php
			}
		}

		// Tax
		if ( ! empty( $line_items['tax_amount'] ) && $line_items['tax_amount'] > 0 ) {
			?>
			<tr class="wpte-booking-tax">
				<td><?php printf( esc_html__( 'Tax (%s%%)', 'wp-travel-engine' ), $line_items['tax_amount'] ); ?></td>
				<td><?php echo esc_html( $currency ) . number_format( $line_items['totals']['total_tax'] ?? 0, 2 ); ?></td>
			</tr>
			<?php
		}

		// Total amounts
		$this->render_total_amounts( $line_items, $currency );
	}

	/**
	 * Render total amounts section
	 */
	private function render_total_amounts( $line_items, $currency ) {
		// Total
		if ( ! empty( $line_items['total'] ) ) {
			?>
			<tr class="wpte-booking-total">
				<td><?php esc_html_e( 'Total', 'wp-travel-engine' ); ?></td>
				<td><?php echo esc_html( $currency ) . number_format( $line_items['total'], 2 ); ?></td>
			</tr>
			<?php
		}

		// Deposit
		if ( ! empty( $line_items['cart_partial'] ) ) {
			?>
			<tr>
				<td><?php esc_html_e( 'Deposit Today', 'wp-travel-engine' ); ?></td>
				<td><?php echo esc_html( $currency ) . number_format( $line_items['cart_partial'], 2 ); ?></td>
			</tr>
			<?php
		}

		// Amount Due
		if ( ! empty( $line_items['totals']['due_total'] ) ) {
			?>
			<tr>
				<td><?php esc_html_e( 'Amount Due', 'wp-travel-engine' ); ?></td>
				<td><?php echo esc_html( $currency ) . number_format( $line_items['totals']['due_total'], 2 ); ?></td>
			</tr>
			<?php
		}
	}

	/**
	 * @return void
	 * @since 5.6.10
	 */
	public function rest_api_init() {
		require_once plugin_dir_path( WP_TRAVEL_ENGINE_FILE_PATH ) . 'includes/rest-api/class-trip-controller.php';

		$trip_controller = new Trip( \WP_TRAVEL_ENGINE_POST_TYPE );
		$trip_controller->register_routes();

		$settings_controller = new Settings();
		$settings_controller->register_routes();

		$booking_controller = new BookingController( 'booking' );
		$booking_controller->register_routes();
	}

	function wte_login_integration() {
		include plugin_dir_path( WP_TRAVEL_ENGINE_FILE_PATH ) . 'includes/social-login/redirection.php';
	}

	public function plugins_loaded() {

		$add_caps_by_roles = get_option( 'wptravelengine_add_caps_by_roles', true );

		if ( $add_caps_by_roles ) {
			$roles = array( 'administrator', 'editor' ); // Define roles to which you want to add capabilities

			foreach ( $roles as $role_name ) {
				$role = get_role( $role_name );
				if ( $role instanceof \WP_Role ) {
					$role->add_cap( 'manage_trip' );
					$role->add_cap( 'edit_trip' );
					$role->add_cap( 'read_trip' );
					$role->add_cap( 'delete_trip' );
					$role->add_cap( 'edit_trips' );
					$role->add_cap( 'edit_others_trips' );
					$role->add_cap( 'publish_trips' );
					$role->add_cap( 'read_private_trips' );

					update_option( 'wptravelengine_add_caps_by_roles', false );
				}
			}
		}

		// phpcs:disable
		if ( is_admin() && ! empty( $_REQUEST[ 'action' ] ) && 'activate' === $_REQUEST[ 'action' ] && isset( $_REQUEST[ 'plugin' ] ) ) {
			$plugin = wte_clean( wp_unslash( $_REQUEST[ 'plugin' ] ) );
			if ( strpos( $plugin, 'wte-advanced-search.php' ) > - 1 ) {
				if ( headers_sent() ) {
					echo "<meta http-equiv='refresh' content='" . esc_attr( '0;url=plugins.php?deactivate=true&plugin_status=all&paged=1' ) . "' />";
				} else {
					wp_redirect( self_admin_url( 'plugins.php?deactivate=true&plugin_status=all&paged=1' ) );
				}
				exit;
			}
		}
		// phpcs:enable
	}

	public function add_booking_modal_container() {
		global $post;
		$trip_id           = is_singular( WP_TRAVEL_ENGINE_POST_TYPE ) ? $post->ID : null;
		$trip_booking_data = wptravelengine_trip_booking_modal_data( $trip_id );
		?>
		<div id="wptravelengine-trip-booking-modal"
			data-trip-booking="<?php echo esc_attr( wp_json_encode( $trip_booking_data ) ); ?>"></div>
		<?php
	}

	/**
	 * Additional WP Travel Engine headers for plugins and themes.
	 *
	 * @param array $headers Headers.
	 *
	 * @return array
	 * @since 4.3.8
	 */
	public function plugin_headers( array $headers ): array {
		// WTE requires at least.
		$headers[] = 'WTE requires at least';
		// WTE Tested up to.
		$headers[] = 'WTE tested up to';
		// WTE.
		$headers[] = 'WTE';

		return $headers;
	}

	/**
	 * Freemius Setup.
	 *
	 * @return void
	 * @since 5.0.0
	 */
	protected function initialize_freemius() {
		global $wte_fs;

		if ( ! $wte_fs ) {
			// Include Freemius SDK.
			require_once dirname( WP_TRAVEL_ENGINE_FILE_PATH ) . '/includes/lib/freemius/start.php';

			$wp_travel_engine_first_time_activation_flag = get_option( 'wp_travel_engine_first_time_activation_flag', 'false' );

			if ( $wp_travel_engine_first_time_activation_flag == 'false' ) {
				$slug = 'wp-travel-engine-onboard';
			} else {
				$slug = 'wptravelengine-admin-page';
			}
			$arg_array = array(
				'id'             => '5392',
				'slug'           => 'wp-travel-engine',
				'type'           => 'plugin',
				'public_key'     => 'pk_d9913f744dc4867caeec5b60fc76d',
				'is_premium'     => false,
				'has_addons'     => false,
				'has_paid_plans' => false,
				'menu'           => array(
					'slug'    => $slug, // Default: class-wp-travel-engine-admin.php.
					'account' => false,
					'contact' => false,
					'support' => false,
					'parent'  => array(
						'slug' => 'edit.php?post_type=booking',
					),
				),
			);
			try {
				$wte_fs = fs_dynamic_init( $arg_array );
			} catch ( \Freemius_Exception $e ) {
				// Catch Freemius Exception.
			}
		}

		$wte_fs->add_action(
			'after_uninstall',
			function () {
			}
		);
		do_action( 'wte_fs_loaded' );
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Wp_Travel_Engine_Loader. Orchestrates the hooks of the plugin.
	 * - Wp_Travel_Engine_i18n. Defines internationalization functionality.
	 * - Wp_Travel_Engine_Admin. Define all hooks for the admin area.
	 * - Wp_Travel_Engine_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   protected
	 */
	protected function load_dependencies() {

		/**
		 * WTE Helper and utility functions.
		 *
		 * @since 4.3.0
		 */
		require_once WP_TRAVEL_ENGINE_BASE_PATH . '/includes/helpers/helpers.php';
		require_once WP_TRAVEL_ENGINE_BASE_PATH . '/includes/helpers/helpers-packages.php';
		require_once WP_TRAVEL_ENGINE_BASE_PATH . '/includes/helpers/privacy-functions.php';

		/**
		 * WP Travel Engine Settings Class.
		 */
		// require_once WP_TRAVEL_ENGINE_BASE_PATH . '/includes/class-settings.php';

		include WP_TRAVEL_ENGINE_BASE_PATH . '/includes/class-wte.php';

		// require WP_TRAVEL_ENGINE_BASE_PATH . '/includes/class-wte-trip.php';

		// Plugin Updater.
		include WP_TRAVEL_ENGINE_BASE_PATH . '/admin/plugin-updates/plugin-updater.php';

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( WP_TRAVEL_ENGINE_FILE_PATH ) . 'includes/class-wp-travel-engine-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		// require_once plugin_dir_path( WP_TRAVEL_ENGINE_FILE_PATH ) . 'includes/class-wp-travel-engine-i18n.php';

		/**
		 * Helpers
		 */
		// require_once plugin_dir_path( WP_TRAVEL_ENGINE_FILE_PATH ) . 'includes/wp-travel-engine-helpers.php';

		/**
		 * Default form fields
		 */
		require_once plugin_dir_path( WP_TRAVEL_ENGINE_FILE_PATH ) . 'includes/class-wte-default-form-fields.php';

		/**
		 *
		 * @since
		 */
		// require_once plugin_dir_path( WP_TRAVEL_ENGINE_FILE_PATH ) . 'neo/class-wte-field-builder.php';
		require_once plugin_dir_path( WP_TRAVEL_ENGINE_FILE_PATH ) . 'includes/class-wte-field-builder.php';

		/**
		 * Form Fields
		 */
		// require_once plugin_dir_path( WP_TRAVEL_ENGINE_FILE_PATH ) . 'includes/wp-travel-engine-form-fields.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( WP_TRAVEL_ENGINE_FILE_PATH ) . 'admin/class-wp-travel-engine-admin.php';

		/**
		 * The class responsible for the admin settings.
		 */
		require_once plugin_dir_path( WP_TRAVEL_ENGINE_FILE_PATH ) . 'admin/class-wp-travel-engine-permalinks.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( WP_TRAVEL_ENGINE_FILE_PATH ) . 'public/class-wp-travel-engine-public.php';

		require_once plugin_dir_path( WP_TRAVEL_ENGINE_FILE_PATH ) . 'admin/class-wp-travel-engine-messages-list.php';

		/**
		 * Custom Enquiry Form
		 *
		 * @since 5.7.1
		 */
		require WP_TRAVEL_ENGINE_BASE_PATH . '/includes/class-wp-travel-engine-enquiry-forms.php';

		/**
		 * The class responsible for building tabs in post-type.
		 * Side of the site.
		 */
		require WP_TRAVEL_ENGINE_BASE_PATH . '/includes/class-wp-travel-engine-meta-tabs.php';

		require WP_TRAVEL_ENGINE_BASE_PATH . '/includes/class-wp-travel-engine-onboard.php';

		/**
		 * The class responsible for defining tabs in custom post type.
		 */
		require WP_TRAVEL_ENGINE_BASE_PATH . '/admin/class-wp-travel-engine-tabs.php';

		/**
		 * The class responsible for defining functions for backend.
		 */
		require WP_TRAVEL_ENGINE_BASE_PATH . '/includes/class-wp-travel-engine-functions.php';

		/**
		 * The class responsible for defining templates.
		 */
		// require WP_TRAVEL_ENGINE_BASE_PATH . '/includes/frontend/class-wp-travel-engine-templates.php';

		/**
		 * The class responsible for placing order.
		 */
		require WP_TRAVEL_ENGINE_BASE_PATH . '/includes/class-wp-travel-engine-place-order.php';

		/**
		 * The class responsible for thank you.
		 */
		require WP_TRAVEL_ENGINE_BASE_PATH . '/includes/class-wp-travel-engine-thank-you.php';
		/**
		 * The class responsible for final confirmation.
		 */
		require WP_TRAVEL_ENGINE_BASE_PATH . '/includes/class-wp-travel-engine-confirmation.php';

		/**
		 * The class responsible for creating metas for an order form.
		 */
		require WP_TRAVEL_ENGINE_BASE_PATH . '/includes/class-wp-travel-engine-order-meta.php';

		/**
		 * The class responsible for creating meta-tags for a single trip.
		 */
		require WP_TRAVEL_ENGINE_BASE_PATH . '/includes/frontend/trip-meta/class-wp-travel-engine-meta-tags.php';

		/**
		 * The class responsible for creating hooks for archive.
		 */
		require WP_TRAVEL_ENGINE_BASE_PATH . '/includes/class-wp-travel-engine-archive-hooks.php';

		/**
		 * The class responsible for creating widget area.
		 */
		require WP_TRAVEL_ENGINE_BASE_PATH . '/includes/class-wte-widget-area-admin.php';

		/**
		 * The class responsible for showing widgets from the widget area.
		 */
		require WP_TRAVEL_ENGINE_BASE_PATH . '/includes/class-wte-widget-area-main.php';

		/**
		 * The class responsible for showing image field in taxonomies.
		 */
		require WP_TRAVEL_ENGINE_BASE_PATH . '/includes/class-wp-travel-engine-taxonomy-thumb.php';

		/**
		 * Including the trip facts shortcode.
		 */
		include WP_TRAVEL_ENGINE_BASE_PATH . '/includes/frontend/trip-meta/trip-meta-parts/trip-facts-shortcode.php';

		/**
		 * Including the trip facts shortcode.
		 */
		include WP_TRAVEL_ENGINE_BASE_PATH . '/includes/class-wp-travel-engine-enquiry-form-shortcodes.php';

		/**
		 * The class responsible for compatibility check.
		 */
		require WP_TRAVEL_ENGINE_BASE_PATH . '/includes/class-wp-travel-engine-compatibility-check.php';

		/**
		 * Including the trip facts shortcode.
		 */
		// include WP_TRAVEL_ENGINE_BASE_PATH . '/includes/privacy-functions.php';

		include WP_TRAVEL_ENGINE_BASE_PATH . '/includes/class-wp-travel-engine-custom-shortcodes.php';

		// include WP_TRAVEL_ENGINE_BASE_PATH . '/includes/class-wp-travel-engine-seo.php';

		// require WP_TRAVEL_ENGINE_BASE_PATH . '/includes/cart/class-wte-cart.php';

		include WP_TRAVEL_ENGINE_BASE_PATH . '/includes/class-wte-ajax.php';

		// include_once WP_TRAVEL_ENGINE_BASE_PATH . '/includes/payment-gateways/standard-paypal/paypal-functions.php';

		// include_once WP_TRAVEL_ENGINE_BASE_PATH . '/includes/payment-gateways/standard-paypal/class-wp-travel-engine-paypal-request.php';

		include_once WP_TRAVEL_ENGINE_BASE_PATH . '/public/class-wp-travel-engine-template-hooks.php';

		/** Admin Ui New Changes indicator Pointer */
		include_once WP_TRAVEL_ENGINE_BASE_PATH . '/includes/class-wp-travel-engine-ui-pointers.php';
		include_once WP_TRAVEL_ENGINE_BASE_PATH . '/includes/class-wte-getting-started.php';

		/**
		 * Featured Trips widget
		 */
		require_once WP_TRAVEL_ENGINE_BASE_PATH . '/includes/widgets/widget-featured-trip.php';

		// load user modules.
		/**
		 * Include Query Classes.
		 *
		 * @since 1.2.6
		 */
		include sprintf( '%s/includes/dashboard/class-wp-travel-engine-query.php', WP_TRAVEL_ENGINE_ABSPATH );

		// User Modules.
		include sprintf( '%s/includes/dashboard/wp-travel-engine-user-functions.php', WP_TRAVEL_ENGINE_ABSPATH );
		// include sprintf( '%s/includes/dashboard/class-wp-travel-engine-user-account.php', WP_TRAVEL_ENGINE_ABSPATH );
		include sprintf( '%s/includes/dashboard/class-wp-travel-engine-form-handler.php', WP_TRAVEL_ENGINE_ABSPATH );

		// WP Travel Engine Neo.
		if ( ! defined( 'USE_WTE_LEGACY_VERSION' ) || ! USE_WTE_LEGACY_VERSION ) {
			require_once sprintf( '%s/includes/tour-packages/packages.php', WP_TRAVEL_ENGINE_ABSPATH );
		}

		// require_once sprintf( '%s/includes/class-wp-travel-engine-emails.php', WP_TRAVEL_ENGINE_ABSPATH );
		require_once sprintf( '%s/includes/bookings/class-wte-process-booking-core.php', WP_TRAVEL_ENGINE_ABSPATH );
		/**
		 * Booking Tags.
		 *
		 * @since 5.5.3
		 */
		// require_once sprintf( '%s/includes/emails/class-email-template-tags.php', WP_TRAVEL_ENGINE_ABSPATH );

		/**
		 * @since 5.5.2
		 */
		// require_once sprintf( '%s/includes/bookings/class-booking.php', WP_TRAVEL_ENGINE_ABSPATH );
		// require_once sprintf( '%s/includes/bookings/class-booking-inventory.php', WP_TRAVEL_ENGINE_ABSPATH );

		/**
		 * Modules integrated on a later version.
		 */
		// include_once sprintf( '%s/includes/modules/class-trip-code.php', WP_TRAVEL_ENGINE_ABSPATH );
		// include_once sprintf( '%s/includes/modules/coupon-code/class-coupon-code.php', WP_TRAVEL_ENGINE_ABSPATH );
		// include_once sprintf( '%s/includes/modules/trip-search/class-trip-search.php', WP_TRAVEL_ENGINE_ABSPATH );
		// include_once sprintf( '%s/includes/modules/custom-filters/class-custom-filters.php', WP_TRAVEL_ENGINE_ABSPATH );

		/**
		 * Includes classes for trip blocks.
		 *
		 * @since 5.9
		 */
		include_once sprintf( '%s/includes/classes/Blocks/Metadata.php', WP_TRAVEL_ENGINE_ABSPATH );

		/**
		 * Rest API.
		 */
		include_once sprintf( '%s/includes/rest-api/index.php', WP_TRAVEL_ENGINE_ABSPATH );

		/**
		 * String Translation.
		 *
		 * @since 5.7.3
		 */
		include_once sprintf( '%s/includes/class-static-strings.php', WP_TRAVEL_ENGINE_ABSPATH );

		/**
		 * CW Pattern Inserter Module for the plugin.
		 *
		 * @since 5.8.5
		 */
		if ( ! class_exists( 'CWPatternImport\CW_Pattern_Import' ) ) {
			require_once sprintf( '%s/includes/classes/Modules/pattern-inserter/class-import-patterns.php', WP_TRAVEL_ENGINE_BASE_PATH );
		}
	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Wp_Travel_Engine_i18n class to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	protected function set_locale() {
		add_action(
			'init',
			function () {

				$locale = apply_filters( 'plugin_locale', determine_locale(), 'wp-travel-engine' );

				unload_textdomain( 'wp-travel-engine', true );
				load_textdomain( 'wp-travel-engine', WP_LANG_DIR . '/wp-travel-engine/wp-travel-engine-' . $locale . '.mo' );
				load_plugin_textdomain(
					'wp-travel-engine',
					false,
					dirname( WP_TRAVEL_ENGINE_FILE_PATH ) . '/languages/'
				);
			}
		);
	}

	/**
	 * Register all the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Wp_Travel_Engine_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_init', $plugin_admin, 'wp_travel_engine_register_settings' );
		$this->loader->add_action( 'admin_init', $plugin_admin, 'wte_update_actual_prices_for_filter' );
		$this->loader->add_action( 'admin_head', $plugin_admin, 'wp_travel_engine_tabs_template', 0 );
		$this->loader->add_filter( 'manage_enquiry_posts_columns', $plugin_admin, 'wp_travel_engine_enquiry_cpt_columns' );
		$this->loader->add_filter( 'post_row_actions', $plugin_admin, 'enquiry_remove_row_actions', 10, 1 );
		$this->loader->add_action( 'manage_posts_custom_column', $plugin_admin, 'wp_travel_engine_enquiry_custom_columns', 10, 2 );
		$this->loader->add_filter( 'manage_booking_posts_columns', $plugin_admin, 'wp_travel_engine_booking_cpt_columns' );
		$this->loader->add_action( 'manage_posts_custom_column', $plugin_admin, 'wp_travel_engine_booking_custom_columns', 10, 2 );
		$this->loader->add_filter( 'manage_customer_posts_columns', $plugin_admin, 'wp_travel_engine_customer_cpt_columns' );
		$this->loader->add_action( 'manage_posts_custom_column', $plugin_admin, 'wp_travel_engine_customer_custom_columns', 10, 2 );
		$this->loader->add_filter( 'manage_edit-trip_types_columns', $plugin_admin, 'wp_travel_engine_trip_types_columns', 10, 2 );
		$this->loader->add_action( 'manage_trip_types_custom_column', $plugin_admin, 'wp_travel_engine_trip_types_custom_columns', 10, 3 );
		$this->loader->add_filter( 'manage_edit-destination_columns', $plugin_admin, 'wp_travel_engine_trip_types_columns', 10, 2 );
		$this->loader->add_action( 'manage_destination_custom_column', $plugin_admin, 'wp_travel_engine_trip_types_custom_columns', 10, 3 );
		$this->loader->add_filter( 'manage_edit-activities_columns', $plugin_admin, 'wp_travel_engine_trip_types_columns', 10, 2 );

		/*
		 * ADMIN COLUMN - HEADERS
		 */
		$this->loader->add_filter( 'manage_edit-trip_columns', $plugin_admin, 'wp_travel_engine_trips_columns' );
		$this->loader->add_action( 'manage_activities_custom_column', $plugin_admin, 'wp_travel_engine_trip_types_custom_columns', 10, 3 );
		$this->loader->add_action( 'admin_head-post.php', $plugin_admin, 'hide_publishing_actions', 10, 2 );
		$this->loader->add_action( 'init', $plugin_admin, 'wp_travel_engine_create_destination_taxonomies' );
		$this->loader->add_action( 'init', $plugin_admin, 'wp_travel_engine_create_activities_taxonomies' );
		$this->loader->add_action( 'init', $plugin_admin, 'wp_travel_engine_create_trip_types_taxonomies' );
		$this->loader->add_action( 'init', $plugin_admin, 'create_difficulty_taxonomies' );
		$this->loader->add_action( 'init', $plugin_admin, 'register_terms_for_difficulty_taxonomies', 25 );
		$this->loader->add_action( 'init', $plugin_admin, 'create_tags_taxonomies' );
		$this->loader->add_action( 'init', $plugin_admin, 'register_terms_for_tags_taxonomies' );
		$this->loader->add_action( 'add_meta_boxes', $plugin_admin, 'add_custom_wte_metabox' );

		if ( isset( $_GET[ 'page' ] ) && $_GET[ 'page' ] == 'class-wp-travel-engine-admin.php' ) { // phpcs:ignore
			$this->loader->add_action( 'admin_footer', $plugin_admin, 'trip_facts_template', 20 );
		}

		$this->loader->add_action( 'admin_footer', $plugin_admin, 'wpte_add_itinerary_template', 20 );
		$this->loader->add_action( 'admin_footer', $plugin_admin, 'wpte_add_faq_template', 20 );
		$this->loader->add_action( 'wp_loaded', $plugin_admin, 'wpte_add_destination_templates' );
		$this->loader->add_action( 'rest_api_init', $plugin_admin, 'wpte_add_destination_templates' );
		$this->loader->add_action( 'wte_paypal_form', $plugin_admin, 'wte_paypal_form' );
		// $this->loader->add_action( 'add_meta_boxes', $plugin_admin, 'wpte_trip_pay_add_meta_boxes' );
		// $this->loader->add_action( 'save_post', $plugin_admin, 'wp_travel_engine_trip_pay_meta_box_data' );
		$this->loader->add_filter( 'tiny_mce_before_init', $plugin_admin, 'wte_tinymce_config' );
		$this->loader->add_filter( 'manage_trip_posts_columns', $plugin_admin, 'wp_travel_engine_trip_cpt_columns' );
		$this->loader->add_action( 'manage_posts_custom_column', $plugin_admin, 'wp_travel_engine_trip_custom_columns', 10, 2 );

		// $this->loader->add_action( 'admin_notices', $plugin_admin, 'admin_notices' );
		$this->loader->add_action( 'in_plugin_update_message-wp-travel-engine/wp-travel-engine.php', $plugin_admin, 'in_plugin_update_message', 10, 2 );
		$this->loader->add_action( 'wp_travel_engine_trip_itinerary_setting', $plugin_admin, 'wte_itinerary_setting' );

		// Add bulk actions to migrate customers.
		$this->loader->add_filter( 'bulk_actions-edit-customer', $plugin_admin, 'wte_add_customer_bulk_actions' );
		// Handle bulk action migrate users to customer.
		$this->loader->add_filter( 'handle_bulk_actions-edit-customer', $plugin_admin, 'wte_add_customer_bulk_action_handler', 10, 3 );

		$this->loader->add_action( 'admin_notices', $plugin_admin, 'customer_bulk_action_notices' );
		/*
		 * ADMIN COLUMN - Featured CONTENT
		 */
		$this->loader->add_action( 'manage_trip_posts_custom_column', $plugin_admin, 'wte_itineraries_manage_columns', 10, 2 );

		// Display message feature only if the user has enabled it.
		// if ( '1' === \get_option( 'wte_messages_enabled' ) || ( isset( $_GET['wte-message-enabled'] ) && '1' === $_GET['wte-message-enabled'] ) ) { // phpcs:ignore
		// $this->loader->add_action( 'admin_menu', $plugin_admin, 'messages_page' );
		// }

		// lOAD TAB CONTENT AJAX

		// Save tab and continue button ajax.

		// Trip Code section.
		// $this->loader->add_action( 'wp_travel_engine_trip_code_display', $plugin_admin, 'wpte_display_trip_code_section' );

		// Pricing Tab upsell notes section.
		$this->loader->add_action( 'wte_after_pricing_upsell_notes', $plugin_admin, 'wpte_display_extension_upsell_notes' );

		// Load Global Tabs AJAX
		// lOAD TAB CONTENT AJAX

		// Save global tabs data.
		$this->loader->add_filter( 'admin_body_class', $plugin_admin, 'wpte_body_class_before_header_callback' );
		$this->loader->add_action( 'wp_travel_engine_trip_custom_info', $plugin_admin, 'wp_travel_engine_trip_custom_info' );

		$this->loader->add_action( 'post_submitbox_misc_actions', $plugin_admin, 'wte_publish_metabox' );

		/**
		 * @since 5.5.3
		 */
		add_action( 'save_post_booking', array( Core\Models\Post\Booking::class, 'save_post_booking' ), 20, 3 );

		/**
		 * @since 5.5.3
		 */
		add_action(
			'wptravelengine_booking_inventory',
			array(
				'\WPTravelEngine\Core\Booking_Inventory',
				'booking_inventory',
			),
			10,
			2
		);
		/**
		 * @since 5.7.2
		 */
		add_action( 'save_post_customer', array( $this, 'save_post_customer' ), 11, 3 );

		/**
		 * Sets default columns order and hides some columns in trip list table.
		 *
		 * @since 6.3.5
		 */
		$this->loader->add_filter( 'manage_edit-trip_columns', $plugin_admin, 'set_trip_columns_order' );
		$this->loader->add_filter( 'get_user_option_manageedit-tripcolumnshidden', $plugin_admin, 'set_default_hidden_trip_columns', 10, 1 );
	}

	/**
	 * Saves and updates customer data while creating customer.
	 *
	 * @param int      $post_id
	 * @param \WP_Post $post Post Object.
	 * @param boolean  $update Is Updating?
	 *
	 * @since 5.7.2
	 */
	public function save_post_customer( int $post_id, \WP_Post $post, bool $update ) {
		if ( ! $update ) {
			update_post_meta( $post_id, '_update_title', 'true' );
		} else {
			$should_update_title = get_post_meta( $post_id, '_update_title', true );
			if ( 'true' === $should_update_title ) {
				if ( isset( $_POST['wp_travel_engine_booking_setting']['place_order']['booking']['email'] ) ) {
					remove_action( 'save_post_customer', array( $this, 'save_post_customer' ), 11 );
					$result = wp_update_post(
						array(
							'ID'         => $post_id,
							'post_title' => sanitize_text_field( wp_unslash( $_POST['wp_travel_engine_booking_setting']['place_order']['booking']['email'] ) ),
						)
					);
					if ( is_numeric( $result ) ) {
						delete_post_meta( $post_id, '_update_title', 'true' );
					}
				}
			}
		}
	}

	/**
	 * Register all the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Wp_Travel_Engine_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'init', $plugin_public, 'wpte_start_session', 1 );
		$this->loader->add_action( 'wte_cart_trips', $plugin_public, 'wte_cart_trips' );
		// $this->loader->add_action( 'wte_update_cart', $plugin_public, 'wte_update_cart' );
		$this->loader->add_action( 'wte_cart_form_wrapper', $plugin_public, 'wte_cart_form_wrapper' );
		$this->loader->add_action( 'wte_cart_form_close', $plugin_public, 'wte_cart_form_close' );
		$this->loader->add_action( 'wte_payment_gateways_dropdown', $plugin_public, 'wte_payment_gateways_dropdown' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'wpte_be_load_more_js' );

		$this->loader->add_action( 'show_user_profile', $plugin_public, 'wte_wishlist_user_profile_field' );
		$this->loader->add_action( 'edit_user_profile', $plugin_public, 'wte_wishlist_user_profile_field' );
		$this->loader->add_action( 'personal_options_update', $plugin_public, 'wte_save_wishlist_user_profile_field' );
		$this->loader->add_action( 'edit_user_profile_update', $plugin_public, 'wte_save_wishlist_user_profile_field' );

		// $this->loader->add_action( 'rest_api_init', $plugin_public, 'rest_register_fields' );
		$this->loader->add_action( 'rest_product_collection_params', $plugin_public, 'maximum_api_filter' );

		$this->loader->add_action( 'init', $plugin_public, 'do_output_buffer' );
		$wp_travel_engine_settings = get_option( 'wp_travel_engine_settings', true );
		if ( isset( $wp_travel_engine_settings['paypal_payment'] ) ) {
			$this->loader->add_filter( 'wte_payment_gateways_dropdown_options', $plugin_public, 'wte_paypal_add_option' );
		}
		if ( isset( $wp_travel_engine_settings['test_payment'] ) ) {
			$this->loader->add_filter( 'wte_payment_gateways_dropdown_options', $plugin_public, 'wte_test_add_option' );
		}
		// $this->loader->add_action( 'wp_footer', $plugin_public, 'wpte_calendar_custom_code' );

		// Form dynamic hook - Booking form
		$this->loader->add_action( 'wp_travel_engine_order_form_before_form_field', $plugin_public, 'wpte_order_form_before_fields' );
		$this->loader->add_action( 'wp_travel_engine_order_form_after_form_field', $plugin_public, 'wpte_order_form_after_fields' );

		// Before a Submit Button - Booking form.
		$this->loader->add_action( 'wp_travel_engine_order_form_before_submit_button', $plugin_public, 'wpte_order_form_before_submit_button' );
		$this->loader->add_action( 'wp_travel_engine_order_form_after_submit_button', $plugin_public, 'wpte_order_form_after_submit_button' );

		$this->loader->add_action( 'wte_enquiry_contact_form_after_submit_button', $plugin_public, 'wte_enquiry_contact_form_after_submit_button' );

		// Tinymce Filters.
		$this->loader->add_filter( 'mce_buttons_2', $plugin_public, 'register_tinymce_buttons', 999, 2 );
		$this->loader->add_filter( 'mce_external_plugins', $plugin_public, 'register_tinymce_plugin', 999 );

		// $this->loader->add_action( 'wp_travel_engine_before_trip_add_to_cart', $plugin_public, 'check_min_max_pax', 9, 6 );
		$this->loader->add_action( 'wte_before_add_to_cart', $plugin_public, 'check_min_max_pax', 9, 2 );

		// add_filter(
		// 'wp_travel_engine_available_payment_gateways',
		// function ($gateways_list) {
		// if ( array_key_exists( 'direct_bank_transfer', $gateways_list ) ) {
		// $settings = get_option( 'wp_travel_engine_settings', array() );
		// $method = $settings['bank_transfer'] ?? array();
		// if ( ! empty( $method['title'] ) ) {
		// $gateways_list['direct_bank_transfer']['label'] = $method['title'];
		// }
		// if ( ! empty( $method['description'] ) ) {
		// $gateways_list['direct_bank_transfer']['info_text'] = $method['description'];
		// }
		// }
		// if ( array_key_exists( 'check_payments', $gateways_list ) ) {
		// $settings = get_option( 'wp_travel_engine_settings', array() );
		// $method = $settings['check_payment'] ?? array();
		// if ( ! empty( $method['title'] ) ) {
		// $gateways_list['check_payments']['label'] = $method['title'];
		// }
		// if ( ! empty( $method['description'] ) ) {
		// $gateways_list['check_payments']['info_text'] = $method['description'];
		// }
		// }
		//
		// return $gateways_list;
		// }
		// );

		/**
		 * Custom Enquiry Form
		 *
		 * @since 5.7.1
		 */
		$enquiry_form = new WP_Travel_Engine_Enquiry_Forms();
		$this->loader->add_action( 'ninja_forms_after_submission', $enquiry_form, 'catch_ninja_forms_data', 10, 1 );
		$this->loader->add_action( 'wpforms_frontend_confirmation_message', $enquiry_form, 'catch_wpforms_data', 10, 2 );
		$this->loader->add_action( 'gform_after_submission', $enquiry_form, 'catch_gravity_forms_data', 10, 2 );

		// Add action to output WTE rich snippet for Elementor templates on trip single pages.
		$this->loader->add_action( 'elementor/page_templates/header-footer/after_content', $this, 'output_wte_rich_snippet_for_elementor' );
	}

	/**
	 * Adds body classes.
	 *
	 * @return void
	 */
	public function body_class( $classes ) {

		$settings                 = get_option( 'wp_travel_engine_settings', array() );
		$new_trip_listing         = isset( $settings['display_new_trip_listing'] ) && $settings['display_new_trip_listing'] == 'yes';
		$related_new_trip_listing = isset( $settings['related_display_new_trip_listing'] ) && $settings['related_display_new_trip_listing'] == 'yes';

		$c_themes = array(
			'Travel Agency'      => '1.4.5',
			'Travel Agency Pro'  => '2.6.6',
			'Travel Booking'     => '1.2.6',
			'Travel Booking Pro' => '2.2.8',
			'travel-booking'     => '1.2.6',
			'travel-agency'      => '1.4.5',
		);

		$theme = wp_get_theme();

		if ( isset( $c_themes[ $theme->stylesheet ] ) ) {
			$theme_key = $theme->stylesheet;
		} elseif ( isset( $c_themes[ $theme->name ] ) ) {
			$theme_key = $theme->name;
		}

		if ( isset( $theme_key ) ) {
			if ( version_compare( $c_themes[ $theme_key ], $theme->version, '<=' ) ) {
				$classes[] = 'wptravelengine_' . str_replace( '.', '', $this->version );
				$classes[] = 'wptravelengine_css_v2';
			}
		} else {
			$classes[] = 'wptravelengine_' . str_replace( '.', '', $this->version );
			$classes[] = 'wptravelengine_css_v2';
		}

		if ( $new_trip_listing || $related_new_trip_listing ) {
			$classes[] = 'wpte_has-tooltip';
		}

		if ( is_singular( WP_TRAVEL_ENGINE_POST_TYPE ) ) {
			if ( isset( $settings['wte_sticky_booking_widget'] ) && 'yes' === $settings['wte_sticky_booking_widget'] ) {
				$classes[] = 'wpte_has-sticky-booking-widget';
			}
		}

		if ( get_queried_object_id() == wp_travel_engine_get_dashboard_page_id() ) {
			$classes[] = 'wpte-user-account';
		}

		return $classes;
	}

	/**
	 * Run the loader to execute all the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @return    string    The name of the plugin.
	 * @since     1.0.0
	 */
	public function get_plugin_name(): string {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @return    Wp_Travel_Engine_Loader    Orchestrates the hooks of the plugin.
	 * @since     1.0.0
	 */
	public function get_loader(): Wp_Travel_Engine_Loader {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @return    string    The version number of the plugin.
	 * @since     1.0.0
	 */
	public function get_version(): string {
		return $this->version;
	}

	/**
	 * Init shortcodes.
	 *
	 * @since    1.0.0
	 */
	public function init_shortcodes() {
		ShortcodeRegistry::make()
						->register( CheckoutV2::class )
						->register( ThankYou::class )
						->register( TravelerInformation::class )
						->register( General::class )
						->register( TripCheckout::class )
						->register( UserAccount::class )
						->register( TripsList::class );
	}

	/**
	 * Set Cart.
	 *
	 * @return void
	 */
	protected function set_cart() {
		$GLOBALS['wte_cart'] = new Cart();
	}

	/**
	 * Autoload classes.
	 *
	 * @param string $class_name Class name.
	 */
	public function autoload( string $class_name ) {
		$class_name     = strtolower( $class_name );
		$class_mappings = array(
			'wp_travel_engine'                      => WP_TRAVEL_ENGINE_BASE_PATH . '/includes/deprecated/class-wp-travel-engine.php',
			'wp_travel_engine_emails'               => WP_TRAVEL_ENGINE_BASE_PATH . '/includes/class-wp-travel-engine-emails.php',
			'wte_booking_emails'                    => WP_TRAVEL_ENGINE_BASE_PATH . '/includes/class-wp-travel-engine-emails.php',
			'wptravelengine\core\trip\booking'      => WP_TRAVEL_ENGINE_BASE_PATH . '/includes/deprecated/class-booking.php',
			'wte_cart'                              => WP_TRAVEL_ENGINE_BASE_PATH . '/includes/deprecated/class-wte-cart.php',
			'wptravelengine\core\booking_inventory' => WP_TRAVEL_ENGINE_BASE_PATH . '/includes/deprecated/class-booking-inventory.php',
			'wptravelengine\posttype\trip'          => WP_TRAVEL_ENGINE_BASE_PATH . '/includes/deprecated/class-wte-trip.php',
		);

		if ( isset( $class_mappings[ $class_name ] ) ) {
			require_once $class_mappings[ $class_name ];
		}
	}

	/**
	 * Add extra email tags for extra services and user history.
	 *
	 * @param array $email_tags Email tags.
	 *
	 * @return array
	 */
	public function wte_extra_services_email_tags( $email_tags ) {
		$active_extensions        = apply_filters( 'wpte_get_global_extensions_tab', array() );
		$extra_services_file_path = $active_extensions['wte_extra_services']['content_path'] ?? '';
		$user_history_file_path   = $active_extensions['wte_user_history']['content_path'] ?? '';

		$extra_email_tags = array();

		if ( file_exists( $extra_services_file_path ) ) {
			$extra_email_tags[] = array(
				'field_type' => 'TITLE',
				'title'      => __( 'Extra Services', 'wp-travel-engine' ),
			);
			$extra_email_tags[] = array(
				'field_type' => 'TEMPLATE_TAGS',
				'value'      => array(
					'{extra_services}' => __( 'Extra services', 'wp-travel-engine' ),
				),
				'name'       => 'emails.extra_services_email_tags',
			);
		}

		if ( file_exists( $user_history_file_path ) ) {
			$extra_email_tags[] = array(
				'field_type' => 'TITLE',
				'title'      => __( 'User History Addon E-mail Tags', 'wp-travel-engine' ),
			);
			$extra_email_tags[] = array(
				'field_type' => 'TEMPLATE_TAGS',
				'value'      => array(
					'{user_history}' => __( 'Show buyer\'s browsing history before making the booking', 'wp-travel-engine' ),
				),
				'name'       => 'emails.user_history_email_tags',
			);
		}

		return array_merge( $email_tags, $extra_email_tags );
	}

	/**
	 * Set class aliases.
	 *
	 * @return void
	 * @since 6.5.0
	 */
	public function set_class_aliases() {

		/**
		 * WTE_Booking_Emails class's functionality has been moved to \WPTravelEngine\Email\Booking.
		 *
		 * For backward compatibility, \WPTravelEngine\Email\Booking is aliased as WTE_Booking_Email.
		 */
		class_alias( '\WPTravelEngine\Email\BookingEmail', 'WTE_Booking_Emails' );
	}

	/**
	 * Output WTE rich snippet for Elementor templates on trip single pages.
	 *
	 * This ensures schema is output even when Elementor Full Width template is used,
	 * as the trip content template (single-trip.php) won't be loaded in that case.
	 *
	 * @since 6.7.1
	 */
	public function output_wte_rich_snippet_for_elementor() {
		// Only output on trip single pages.
		if ( ! is_singular( 'trip' ) ) {
			return;
		}

		// Output the schema using the display_wte_rich_snippet action.
		// This ensures schema is available even when Elementor Full Width template bypasses single-trip.php.
		do_action( 'display_wte_rich_snippet' );
	}
}
