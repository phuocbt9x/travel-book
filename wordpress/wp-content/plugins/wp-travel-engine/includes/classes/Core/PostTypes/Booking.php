<?php
/**
 * Post Type Booking.
 *
 * @package WPTravelEngine/Core/PostTypes
 * @since 6.0.0
 */

namespace WPTravelEngine\Core\PostTypes;

use WP_Exception;
use WPTravelEngine\Abstracts\PostType;
use WPTravelEngine\Builders\FormFields\BillingEditFormFields;
use WPTravelEngine\Builders\FormFields\EmergencyEditFormFields;
use WPTravelEngine\Builders\FormFields\PaymentEditFormFields;
use WPTravelEngine\Builders\FormFields\TravellerEditFormFields;
use WPTravelEngine\Builders\FormFields\OrderTripEditFormFields;
use WPTravelEngine\Core\Cart\Adjustments\CouponAdjustment;
use WPTravelEngine\Core\Cart\Adjustments\TaxAdjustment;
use WPTravelEngine\Core\Cart\Items\PricingCategory;
use WPTravelEngine\Core\Cart\Items\ExtraService;
use WPTravelEngine\Core\Models\Post\Booking as BookingModel;
use WPTravelEngine\Core\Models\Post\Payment;
use WPTravelEngine\Helpers\BookedItem;
use WPTravelEngine\Helpers\CartInfoParser;
use WPTravelEngine\Helpers\Functions;
use WPTravelEngine\Utilities\ArrayUtility;
use WPTravelEngine\Core\Models\Post\Customer;
use WPTravelEngine\Validator\Validator;
use DateTime;
use WPTravelEngine\Core\Booking\BookingProcess;
use WPTravelEngine\Core\Models\Post\TripPackageIterator;
use WPTravelEngine\Core\Models\Post\TripPackage;
use WPTravelEngine\Core\Models\Post\Trip;
use WPTravelEngine\Abstracts\CartItem;
use WPTravelEngine\Core\Cart\Cart;
use WPTravelEngine\Utilities\PaymentCalculator;
use WPTravelEngine\Core\Tax;

/**
 * Class Booking
 * This class represents a trip booking to the WP Travel Engine plugin.
 *
 * @since 6.0.0
 */
class Booking extends PostType {


	/**
	 * Post type name.
	 *
	 * @var string
	 */
	protected string $post_type = 'booking';

	/**
	 * Date format constant.
	 *
	 * @var string
	 * @since 6.7.0
	 */
	private const DATE_FORMAT = 'Y-m-d';

	/**
	 * DateTime format constant.
	 *
	 * @var string
	 * @since 6.7.0
	 */
	private const DATETIME_FORMAT = 'Y-m-d\TH:i';

	/**
	 * Request object.
	 *
	 * @var \WP_REST_Request
	 * @since 6.7.0
	 */
	private \WP_REST_Request $request;

	/**
	 * Set fees.
	 *
	 * @var array
	 * @since 6.7.0
	 */
	private array $set_fees = array();

	/**
	 * Constructor.
	 *
	 * @since 6.4.0
	 */
	public function __construct() {
		add_action( "add_meta_boxes_{$this->post_type}", array( $this, 'meta_box_booking' ) );
		add_action( 'wp_insert_post', array( $this, 'save' ), 10, 3 );
		add_action( 'restrict_manage_posts', array( $this, 'add_filter_options' ) );
		add_action( 'parse_query', array( $this, 'filter_bookings' ) );
		add_filter( 'disable_months_dropdown', array( $this, 'remove_date_filter' ) );
		add_action( 'admin_init', array( $this, 'export_bookings' ) );
		add_action( 'admin_head', array( $this, 'add_booking_export_button' ) );

		/**
		 * @since 6.7.0
		 */
		$this->init_booking_hooks();

		add_filter( 'wptravelengine_booking_line_item_group_title', array( $this, 'add_booking_line_item_title' ), 10, 2 );
		add_filter( 'wptravelengine_booking_line_items', array( $this, 'add_booking_line_items' ), 10, 2 );
		add_filter( 'wp_travel_engine_traveller_info_fields_display', array( $this, 'add_pricing_category_field_to_traveller' ), 10, 1 );
		add_filter( 'wp_travel_engine_lead_traveller_info_fields_display', array( $this, 'add_pricing_category_field_to_lead_traveller' ), 10, 1 );
		add_filter( 'wptravelengine_form_field_options', array( $this, 'add_none_option_to_select_options' ), 10, 1 );
	}

	/**
	 * Add "None" as first option for select fields on backend (same as frontend FormField filter).
	 *
	 * @param array $options Select field options.
	 * @return array Modified options.
	 * @since 6.7.6
	 */
	public function add_none_option_to_select_options( $options ): array {
		if ( ! is_array( $options ) ) {
			return $options;
		}

		if ( ! is_admin() || get_post_type() !== 'booking' ) {
			return $options;
		}

		return Functions::add_none_option_to_select( $options, true );
	}

	/**
	 * Add pricing category field to traveller info fields.
	 *
	 * @param array $fields Traveller info fields.
	 * @return array Modified fields array.
	 * @since 6.7.0
	 */
	public function add_pricing_category_field_to_traveller( $fields ) {
		return $this->add_pricing_category_field( $fields, 'traveller_pricing_category' );
	}

	/**
	 * Add pricing category field to lead traveller info fields.
	 *
	 * @param array $fields Lead traveller info fields.
	 * @return array Modified fields array.
	 * @since 6.7.0
	 */
	public function add_pricing_category_field_to_lead_traveller( $fields ) {
		return $this->add_pricing_category_field( $fields, 'pricing_category' );
	}

	/**
	 * Add pricing category field to fields array.
	 *
	 * @param array  $fields    Fields array.
	 * @param string $field_key Key to use for the field.
	 * @return array Modified fields array.
	 * @since 6.7.0
	 */
	private function add_pricing_category_field( $fields, $field_key ) {
		// Only process on booking post type admin page.
		if ( get_post_type() !== 'booking' ) {
			return $fields;
		}
		global $post;

		$post_id = isset( $post->ID ) ? $post->ID : null;
		if ( $post_id ) {
			global $post;
			$booking      = BookingModel::for( $post_id, $post );
			$is_curr_cart = $booking->is_curr_cart();
			if ( ! $is_curr_cart ) {
				return $fields;
			}
		}

		// Ensure $fields is an array.
		if ( ! is_array( $fields ) ) {
			$fields = array();
		}

		// Return early if field already exists.
		if ( isset( $fields[ $field_key ] ) ) {
			return $fields;
		}

		// Get pricing categories.
		$pricing_categories = get_terms(
			array(
				'taxonomy'   => 'trip-packages-categories',
				'hide_empty' => false,
				'orderby'    => 'term_id',
				'fields'     => 'id=>name',
			)
		);

		// Handle get_terms error.
		if ( is_wp_error( $pricing_categories ) ) {
			$pricing_categories = array();
		}

		/**
		 * Sentinel to skip adding None option to the pricing category field.
		 */
		$pricing_categories['__skip_none_option__'] = true;
		// Create the pricing category field.
		$pricing_category_field = array(
			'type'          => 'select',
			'wrapper_class' => 'row-repeater',
			'field_label'   => __( 'Traveller(s)', 'wp-travel-engine' ),
			'name'          => 'travelers[pricing_category]',
			'id'            => 'pricing_category',
			'class'         => 'input',
			'default_field' => true,
			'options'       => $pricing_categories,
		);

		// Add the field at the beginning of the array.
		$fields = array( $field_key => $pricing_category_field ) + $fields;

		return $fields;
	}

	/**
	 * Initialize booking-specific hooks
	 *
	 * @since 6.7.0
	 */
	private function init_booking_hooks() {
		// add_filter( 'wp_travel_engine_traveller_info_fields_display', array( $this, 'add_pricing_category_field' ), 10, 1 );
		// add_filter( 'wp_travel_engine_lead_traveller_info_fields_display', array( $this, 'add_pricing_category_field' ), 10, 1 );
		// Consolidated addon display hooks
		$this->init_addon_display_hooks();
	}


	/**
	 * Initialize addon display hooks with consolidated logic
	 */
	private function init_addon_display_hooks() {
		$addons = array(
			'accommodation'    => array(
				'priority'      => 5,
				'edit_priority' => 5,
				'tab_template'  => 'booking/partials/tabs/accommodation.php',
				'template'      => 'booking/partials/accommodation.php',
				'edit_template' => 'booking/partials/edit/accommodation.php',
				'tab_text'      => __( 'Accommodation', 'wp-travel-engine' ),
				'tab_target'    => 'accommodation',
			),
			'travel-insurance' => array(
				'priority'      => 15,
				'edit_priority' => 15,
				'tab_template'  => 'booking/partials/tabs/travel-insurance.php',
				'template'      => 'booking/partials/travel-insurance.php',
				'edit_template' => 'booking/partials/edit/travel-insurance.php',
				'tab_text'      => __( 'Travel Insurance', 'wp-travel-engine' ),
				'tab_target'    => 'travel-insurance',
			),
			'extra-services'   => array(
				'priority'      => 10,
				'edit_priority' => 10,
				'tab_template'  => 'booking/partials/tabs/extra-services.php',
				'template'      => 'booking/partials/extra-services.php',
				'edit_template' => 'booking/partials/edit/extra-services.php',
				'tab_text'      => __( 'Extra Services', 'wp-travel-engine' ),
				'tab_target'    => 'extra-services',
			),

		);

		foreach ( $addons as $addon => $config ) {
			$this->register_addon_hooks( $addon, $config );
		}
	}

	/**
	 * Register hooks for a specific addon
	 *
	 * @param string $addon Addon name
	 * @param array  $config Addon configuration
	 *
	 * @return void
	 * @since 6.7.0
	 */
	private function register_addon_hooks( $addon, $config ) {
		// Tab display
		add_action(
			'wptravelengine_booking_details_tabs',
			function ( $booking ) use ( $addon, $config ) {
				// Show tab if addon is active OR if there's existing data
				if ( $this->should_display_addon( $booking, $addon ) ) {
					// Use template file for tab display (allows addon override via template hierarchy)
					$this->display_addon_content( $booking, $addon, $config['tab_template'] );
				}
			},
			$config['priority'],
			1
		);

		// Line items display
		add_action(
			'wptravelengine_booking_details_line_items',
			function ( $booking ) use ( $addon, $config ) {
				if ( $this->should_display_addon( $booking, $addon ) ) {
					$this->display_addon_content( $booking, $addon, $config['template'] );
				}
			},
			$config['priority'],
			1
		);

		// Edit line items display
		add_action(
			'wptravelengine_booking_details_edit_line_items',
			function ( $booking ) use ( $addon, $config ) {
				if ( $this->should_display_addon( $booking, $addon ) ) {
					$this->display_addon_content( $booking, $addon, $config['edit_template'] );
				}
			},
			$config['edit_priority'],
			1
		);
	}

	/**
	 * Check if addon should be displayed
	 * Display if addon is active OR if there's existing data for the addon
	 *
	 * @param object $booking Booking object
	 * @param string $addon Addon name
	 *
	 * @return bool
	 * @since 6.7.0
	 */
	private function should_display_addon( $booking, $addon ) {
		// Always show if addon is active
		if ( wptravelengine_is_addon_active( $addon ) ) {
			return true;
		}

		// Check if there's existing data for this addon
		$cart_info = $booking->get_cart_info();
		if ( empty( $cart_info['items'][0]['line_items'] ) ) {
			return false;
		}

		$line_items = $cart_info['items'][0]['line_items'];

		// Map addon names to their line item keys
		$addon_line_item_map = array(
			'accommodation'    => 'accommodation',
			'extra-services'   => 'extra_service',
			'travel-insurance' => 'travel_insurance',
		);

		$line_item_key = $addon_line_item_map[ $addon ] ?? $addon;

		// Check if line items exist and are not empty
		return isset( $line_items[ $line_item_key ] ) && ! empty( $line_items[ $line_item_key ] );
	}

	/**
	 * Display addon content with proper checks
	 *
	 * @param object $booking Booking object
	 * @param string $addon Addon name
	 * @param string $template Template path
	 *
	 * @return void
	 * @since 6.7.0
	 */
	private function display_addon_content( $booking, $addon, $template ) {
		// Check if another addon has handled the display
		$handled = apply_filters( "wptravelengine_{$addon}_display_handled", false, $booking );
		if ( $handled ) {
			return;
		}

		// Load template
		wptravelengine_get_admin_template( $template );
	}

	/**
	 * Add booking line item title.
	 *
	 * @param string $title Title.
	 * @param array  $item Item.
	 *
	 * @return string
	 * @since 6.7.0
	 */
	public function add_booking_line_item_title( string $title, array $item ): string {
		if ( 'pricing_category' === $title ) {
			$title = __( 'Traveller(s)', 'wp-travel-engine' );
		}
		if ( 'extra_service' === $title ) {
			if ( wptravelengine_settings()->get( 'extra_service_title' ) !== '' ) {
				$title = wptravelengine_settings()->get( 'extra_service_title' );
			} else {
				$title = __( 'Extra Services', 'wp-travel-engine' );
			}
		}

		return $title;
	}

	/**
	 * Add booking line items.
	 *
	 * @param array      $line_items Line items.
	 * @param BookedItem $item Item.
	 *
	 * @return array
	 * @since 6.4.0
	 */
	public function add_booking_line_items( array $line_items, BookedItem $item ): array {
		$line_items['pricing_category'] ??= array();
		if ( wptravelengine_is_addon_active( 'extra-services' ) ) {
			$line_items['extra_service'] ??= array();
		}
		if ( wptravelengine_is_addon_active( 'travel-insurance' ) ) {
			$line_items['travel_insurance'] ??= array();
		}

		return $line_items;
	}

	/**
	 * Retrieve the labels for the Booking post type.
	 *
	 * Returns an array containing the labels used for the Booking post type, including
	 * names for various elements such as the post type itself, singular and plural names,
	 * menu labels, and more.
	 *
	 * @return array An array containing the labels for the Booking post type.
	 */
	public function get_labels(): array {
		return array(
			'name'               => _x( 'Bookings', 'post type general name', 'wp-travel-engine' ),
			'singular_name'      => _x( 'Booking', 'post type singular name', 'wp-travel-engine' ),
			'menu_name'          => _x( 'WP Travel Engine', 'admin menu', 'wp-travel-engine' ),
			'name_admin_bar'     => _x( 'Booking', 'add new on admin bar', 'wp-travel-engine' ),
			'add_new'            => _x( 'Add New', 'Booking', 'wp-travel-engine' ),
			'add_new_item'       => esc_html__( 'Add New Booking', 'wp-travel-engine' ),
			'new_item'           => esc_html__( 'New Booking', 'wp-travel-engine' ),
			'edit_item'          => esc_html__( 'Edit Booking', 'wp-travel-engine' ),
			'view_item'          => esc_html__( 'View Booking', 'wp-travel-engine' ),
			'all_items'          => esc_html__( 'Bookings', 'wp-travel-engine' ),
			'search_items'       => esc_html__( 'Search Bookings', 'wp-travel-engine' ),
			'parent_item_colon'  => esc_html__( 'Parent Bookings:', 'wp-travel-engine' ),
			'not_found'          => esc_html__( 'No Bookings found.', 'wp-travel-engine' ),
			'not_found_in_trash' => esc_html__( 'No Bookings found in Trash.', 'wp-travel-engine' ),
		);
	}

	/**
	 * Retrieve the post type name.
	 *
	 * Returns the name of the post type.
	 *
	 * @return string The name of the post type.
	 */
	public function get_post_type(): string {
		return $this->post_type;
	}

	/**
	 * Retrieve the icon for the Booking post type.
	 *
	 * Returns the icon for the Booking post type.
	 *
	 * @return string The icon for the Booking post type.
	 */
	public function get_icon(): string {
		return 'data:image/svg+xml;base64,' . base64_encode( '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><g clip-path="url(#clip0_60_548)"><path d="M22.8963 12.1856C23.1956 11.7415 22.7501 11.3673 22.7501 11.3673C22.7501 11.3673 22.2301 11.1051 21.9322 11.5491C21.633 11.9932 20.8789 13.1159 20.8789 13.1159L17.8029 13.1871L17.287 13.954L19.8988 14.572L18.7272 15.9741C19.0916 16.1151 19.4014 16.3747 19.7525 16.5486L20.863 15.2085L22.4442 17.359L22.9602 16.5921L21.8418 13.7524C21.8431 13.7524 22.5984 12.6297 22.8963 12.1856Z" fill="white"></path><path d="M11.9222 11.5544C12.8513 11.5544 13.6045 10.8081 13.6045 9.88745C13.6045 8.96683 12.8513 8.22052 11.9222 8.22052C10.9931 8.22052 10.2399 8.96683 10.2399 9.88745C10.2399 10.8081 10.9931 11.5544 11.9222 11.5544Z" fill="white"></path><path d="M21.2379 13.4954C20.9587 13.3215 20.589 13.4045 20.4134 13.6825C18.7032 16.3733 16.9172 17.8439 15.2482 17.9335C13.1351 18.0495 11.744 16.011 10.5299 14.6498C9.8862 13.9276 9.30105 13.1568 8.79038 12.3371C8.3861 11.6901 7.93927 10.9166 7.93927 10.1339C7.93794 7.95699 9.72528 6.18596 11.9222 6.18596C14.1178 6.18596 15.9052 7.95699 15.9052 10.1339C15.9052 11.4371 14.3226 13.5244 12.9635 15.0477C12.7494 15.2875 12.7733 15.6525 13.0114 15.87C13.0154 15.8726 13.018 15.8766 13.022 15.8792C13.2641 16.1006 13.6444 16.0795 13.8625 15.8357C15.2668 14.2716 17.1034 11.8904 17.1034 10.1326C17.1021 7.30208 14.7788 5 11.9222 5C9.06567 5 6.74106 7.30208 6.74106 10.1339C6.74106 11.7876 8.36749 13.9935 9.73326 15.555L9.72927 15.5511C10.091 15.8897 10.4022 16.2996 10.744 16.6593C11.4076 17.3551 12.0858 18.0969 12.9382 18.5634C12.9396 18.5647 12.9422 18.5647 12.9475 18.5687C13.5181 18.877 14.2375 19.1235 15.0807 19.1235C15.1511 19.1235 15.223 19.1221 15.2961 19.1182C17.4039 19.0141 19.4666 17.3972 21.4255 14.3137C21.6023 14.037 21.5172 13.6707 21.2379 13.4954Z" fill="white"></path><path d="M10.6349 17.7979C10.4607 17.6345 10.2054 17.5937 9.98463 17.6859C9.58567 17.852 9.11889 17.9626 8.59625 17.9337C6.92727 17.844 5.14126 16.3735 3.4377 13.6919L2.11049 11.5137C1.94027 11.233 1.57189 11.1434 1.28996 11.312C1.0067 11.482 0.914938 11.8457 1.08649 12.1264L2.41902 14.3138C4.37791 17.3973 6.44054 19.0142 8.54838 19.1183C8.62152 19.1222 8.69333 19.1236 8.76381 19.1236C9.40082 19.1236 9.96867 18.9826 10.4541 18.7796C10.8544 18.6123 10.9528 18.0957 10.6376 17.7992L10.6349 17.7979Z" fill="white"></path></g></svg>' ); // phpcs:ignore WordPress.WP.EnsuredPHPCS.Base64Encode.FileWithoutSafety
	}

	/**
	 * Retrieve the arguments for the Booking post type.
	 *
	 * Returns an array containing the arguments used to register the Booing post type.
	 *
	 * @return array An array containing the arguments for the Booking post type.
	 */
	public function get_args(): array {

		return array(
			'labels'             => $this->get_labels(),
			'description'        => esc_html__( 'Description.', 'wp-travel-engine' ),
			'public'             => false,
			'publicly_queryable' => false,
			'show_ui'            => true,
			'menu_icon'          => $this->get_icon(),
			'query_var'          => true,
			'rewrite'            => array( 'slug' => 'booking' ),
			'capability_type'    => 'post',
			'capabilities'       => $this->get_capabilities(),
			'map_meta_cap'       => true, // Set to `false`, if users are not allowed to edit/delete existing posts
			'has_archive'        => true,
			'hierarchical'       => false,
			'menu_position'      => 31,
			'supports'           => array( '' ),
		);
	}

	/**
	 * Get capabilities.
	 *
	 * @return array
	 * @since 6.4.0
	 */
	public function get_capabilities(): array {
		// TODO: Add capabilities for the booking post type specifically once we define particular capabilities for the booking post type.
		return array(
			'edit_post'          => 'edit_trip',
			'read_post'          => 'read_trip',
			'delete_post'        => 'delete_trip',
			'edit_posts'         => 'edit_trips',
			'edit_others_posts'  => 'edit_others_trips',
			'publish_posts'      => 'publish_trips',
			'read_private_posts' => 'read_private_trips',
		);
	}

	/**
	 * Add filter options.
	 *
	 * @param string $post_type Post type.
	 *
	 * @since 5.7.4 - Booking Export button added.
	 * @modified_since 6.3.5 - Trip Name filter and Booking Status filter added.
	 */
	public function add_filter_options( $post_type ) {
		$current_screen = get_current_screen();
		if ( 'booking' !== $post_type && 'edit-booking' !== $current_screen->id ) {
			return;
		}
		remove_all_actions( 'admin_notices' );
		// Booking status and Trip Name filter options.
		$trips            = wp_travel_engine_get_trips_array();
		$status           = wp_travel_engine_get_booking_status();
		$booking_selected = isset( $_REQUEST['booking_status'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['booking_status'] ) ) : 'all';
		$trip_selected    = isset( $_REQUEST['trip_id'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['trip_id'] ) ) : 'all';

		$mappings = array(
			'trip_id'        => array(
				'data'     => $trips,
				'label'    => __( 'Trip Name', 'wp-travel-engine' ),
				'selected' => $trip_selected,
			),
			'booking_status' => array(
				'data'     => $status,
				'label'    => __( 'Booking Status', 'wp-travel-engine' ),
				'selected' => $booking_selected,
			),
		);
		foreach ( $mappings as $id => $data ) { ?>
			<select id="<?php echo esc_attr( $id ); ?>_filter" name="<?php echo esc_attr( $id ); ?>">
				<option value="all"> <?php echo esc_html( $data['label'] ); ?> </option>
				<?php
				foreach ( $data['data'] as $key => $value ) :
					$display = 'booking_status' === $id ? $value['text'] : $value;
					?>
					<option value="<?php echo esc_html( $key ); ?>" <?php selected( $data['selected'], $key ); ?>>
						<?php echo esc_html( $display ); ?>
					</option>
				<?php endforeach; ?>
			</select>
			<?php
		}
	}

	/**
	 * Remove date filter given by WordPress
	 *
	 * @return bool
	 * @since 6.3.5
	 */
	public function remove_date_filter() {
		return isset( $_GET['post_type'] ) && 'booking' === $_GET['post_type'] ? true : false;
	}

	/**
	 * Export bookings.
	 *
	 * @since 5.7.4
	 */
	public function export_bookings() {
		require_once plugin_dir_path( WP_TRAVEL_ENGINE_FILE_PATH ) . '/admin/class-wp-travel-engine-booking-export.php';
		$booking_export = new \WP_Travel_Engine_Booking_Export();
		$booking_export->init();
	}

	/**
	 * Add Booking export button.
	 *
	 * @since 5.7.4
	 * @modified_since 6.3.5 - Added the booking export button to the booking page.
	 */
	public function add_booking_export_button() {
		global $post_type;

		$current_screen = get_current_screen();

		if ( 'edit-booking' !== $current_screen->id ) {
			return;
		}

		if ( isset( $_GET['post_type'] ) && 'booking' === $_GET['post_type'] && 'booking' == $post_type ) {
			// Remove admin notices.
			remove_all_actions( 'admin_notices' );

			$trips = wp_travel_engine_get_trips_array() ?? array();
			$trips = array( 'all' => __( 'Select Trip', 'wp-travel-engine' ) ) + $trips;

			$status = wp_travel_engine_get_booking_status() ?? array();
			$status = array_merge(
				array(
					'all' => array(
						'color' => '',
						'text'  => 'Select Booking Status',
					),
				),
				$status
			);

			$trip_selected   = isset( $_REQUEST['trip_id'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['trip_id'] ) ) : 'all';
			$status_selected = isset( $_REQUEST['booking_status'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['booking_status'] ) ) : 'all';

			?>
			<form id="wpte-booking-export-form" class="wpte-export-form" method="post">
				<?php wp_nonce_field( 'booking_export_nonce_action', 'booking_export_nonce' ); ?>
				<input type="text" data-fpconfig='{"mode":"range","showMonths":"2"}' id="wte-flatpickr__date-range"
						class="wte-flatpickr">
				<button id="wpte-booking-export-open-modal" type="button" class="button button-primary">
					<?php esc_html_e( 'Export Bookings', 'wp-travel-engine' ); ?>
				</button>
				<div class="wpte-booking-export-modal-overlay">
					<div class="wpte-booking-export-modal">
						<div class="wpte-booking-export-modal-header">
							<h2><?php esc_html_e( 'Export Bookings', 'wp-travel-engine' ); ?></h2>
							<button type="button" class="wpte-booking-modal-close">
								<svg width="24" height="24" viewBox="0 0 24 24" fill="none"
									xmlns="http://www.w3.org/2000/svg">
									<path d="M18 6L6 18M6 6L18 18" stroke="#F04438" stroke-width="2"
											stroke-linecap="round" stroke-linejoin="round" />
								</svg>
							</button>
						</div>
						<div class="wpte-booking-export-modal-body">
							<div class="wpte-field">
								<label
									for="wpte-booking-export-date"><?php esc_html_e( 'Date', 'wp-travel-engine' ); ?></label>
								<input style="max-width: 320px;" id="wpte-booking-export-date" type="text"
										name="wte_booking_range" data-fpconfig='{"mode":"range","showMonths":"2"}'
										name="wte-flatpickr__date"
										value="<?php echo esc_attr( isset( $_POST['wte_booking_range'] ) ? $_POST['wte_booking_range'] : '' ); ?>"
										class="wte-flatpickr">
							</div>
							<div class="wpte-field">
								<label
									for="wpte-booking-export-trip"><?php esc_html_e( 'Trip', 'wp-travel-engine' ); ?></label>
								<select name="wptravelengine_trip_id" id="wpte-booking-export-trip">
									<?php foreach ( $trips as $key => $value ) : ?>
										<option value="<?php echo esc_attr( $key ); ?>"
												name="wptravelengine_trip_id" <?php selected( $trip_selected, $key ); ?>><?php echo esc_html( $value ); ?></option>
									<?php endforeach; ?>
								</select>
							</div>
							<div class="wpte-field">
								<label
									for="wpte-booking-export-status"><?php esc_html_e( 'Booking Status', 'wp-travel-engine' ); ?></label>
								<select style="max-width: 320px;" name="wptravelengine_booking_status"
										id="wpte-booking-export-status">
									<?php foreach ( $status as $key => $value ) : ?>
										<option value="<?php echo esc_attr( $key ); ?>"
												name="wptravelengine_booking_status" <?php selected( $status_selected, $key ); ?>><?php echo esc_html( $value['text'] ); ?></option>
									<?php endforeach; ?>
								</select>
							</div>
						</div>
						<div class="wpte-booking-export-modal-footer">
							<input type="submit" name="booking_export_submit" class="wpte-booking-export-submit button"
									value="<?php esc_html_e( 'Export', 'wp-travel-engine' ); ?>">
						</div>
					</div>
				</div>
			</form>
			<?php
		}
		?>
		<?php
	}

	/**
	 * Save booking data.
	 *
	 * @param int      $post_id Post ID.
	 * @param \WP_Post $post Post object.
	 * @param bool     $update Whether this is an update.
	 * @return void
	 * @since 6.4.0
	 */
	public function save( $post_id, $post, $update = false ) {
		// Verify nonce.
		if ( $this->post_type !== $post->post_type || ! isset( $_POST['wptravelengine_new_booking_nonce'] ) || ! wp_verify_nonce( $_POST['wptravelengine_new_booking_nonce'], 'wptravelengine_new_booking' ) ) {
			return;
		}
		$this->handle_save( $post_id, $post, $update );
	}


	/**
	 * Handle save.
	 *
	 * @param int      $post_id Post ID.
	 * @param \WP_Post $post Post object.
	 * @param bool     $update Whether this is an update.
	 * @return void
	 */
	private function handle_save( $post_id, $post, $update = false ) {

		$booking        = new BookingModel( $post_id );
		$this->request  = Functions::create_request( 'POST' );
		$form_validator = new Validator();

		// Ensure draft posts are published.
		$this->maybe_publish_draft( $post_id, $post );

		$booking->set_meta( '_user_edited', 'yes' );

		// Save core booking data (billing & notes) regardless of cart info.
		$this->process_billing_details( $booking, $post_id, $form_validator );
		$this->process_notes( $booking );

		// Get and validate trip info.
		$trip_info = $this->request->get_param( 'order_trip' ) ?? array();
		if ( is_array( $trip_info ) && isset( $trip_info['id'] ) ) {
			$this->handle_booking_save( $booking, $trip_info, $form_validator, $update, $post_id );
		}

		$booking->save();

		if ( ! $update ) {
			do_action( 'wptravelengine.booking.created', $booking->get_data(), $booking );
		} else {
			do_action( 'wptravelengine.booking.updated', $booking->get_data(), $booking );
		}
	}


	/**
	 * Handle booking save with trip info.
	 *
	 * @param BookingModel $booking Booking model.
	 * @param array        $trip_info Trip info.
	 * @param Validator    $form_validator Form validator.
	 * @param bool         $update Update.
	 * @param int          $post_id Post ID.
	 * @return void
	 */
	private function handle_booking_save( $booking, $trip_info, $form_validator, $update, $post_id ) {
		global $wte_cart;
		// If "Other" or empty/zero is selected with a custom trip name, create a new trip and use its ID.
		if ( isset( $trip_info['id'] ) && ( 'other' === $trip_info['id'] || '' === $trip_info['id'] || 0 === (int) $trip_info['id'] ) ) {
			$custom_trip_title = isset( $trip_info['custom_trip'] ) ? sanitize_text_field( $trip_info['custom_trip'] ) : '';
			if ( '' !== $custom_trip_title ) {
				$existing_trip_id = 0;

				// Check if trip already exists for this booking
				$stored_trip_id = (int) $booking->get_meta( 'custom_trip_post_id' );
				if ( $stored_trip_id && get_post( $stored_trip_id ) ) {
					$existing_trip_id = $stored_trip_id;
				}

				if ( $existing_trip_id ) {
					// Use existing trip
					$trip_code                        = 'WTE-' . $existing_trip_id;
					$_POST['order_trip']['id']        = $existing_trip_id; // phpcs:ignore
					$_POST['order_trip']['trip_code'] = $trip_code; // phpcs:ignore
					$trip_info['id']                  = $existing_trip_id;
					$trip_info['trip_code']           = $trip_code;
				} else {
					// Create new trip
					$new_trip_id = wp_insert_post(
						array(
							'post_type'   => WP_TRAVEL_ENGINE_POST_TYPE,
							'post_title'  => wp_slash( $custom_trip_title ),
							'post_status' => 'draft',
							'post_author' => get_current_user_id(),
						)
					);

					if ( ! is_wp_error( $new_trip_id ) && $new_trip_id ) {
						$trip_code = 'WTE-' . $new_trip_id;

						$custom_trip_meta_value = apply_filters(
							'wptravelengine_custom_trip_edit_settings',
							array(
								'trip_code'               => $trip_code,
								'partial_payment_enable'  => 'yes',
								'partial_payment_use'     => 'global',
								'partial_payment_amount'  => 0,
								'partial_payment_percent' => 0,
								'partial_payment_type'    => 'amount',
								'partial_payment_use_global' => 'yes',
							),
							$new_trip_id
						);

						update_post_meta( $new_trip_id, 'wp_travel_engine_setting', $custom_trip_meta_value );
						update_post_meta( $new_trip_id, 'is_created_from_booking', 'yes' );

						// Store to booking meta to prevent duplicates
						$booking->set_meta( '__customized', 'yes' );

						// Update trip info
						$_POST['order_trip']['id']        = $new_trip_id; // phpcs:ignore
						$_POST['order_trip']['trip_code'] = $trip_code; // phpcs:ignore
						$trip_info['id']                  = $new_trip_id;
						$trip_info['trip_code']           = $trip_code;

						$custome_trips   = get_option( 'wptravelengine_custom_trips', array() );
						$custome_trips[] = $new_trip_id;
						update_option( 'wptravelengine_custom_trips', $custome_trips );
					}
				}
			}
		}

		/**
		 * Set new trip datetime meta.
		 *
		 * @since 6.7.1
		 */

		if ( isset( $trip_info['start_date'] ) ) {
			$booking->set_meta( 'trip_datetime', sanitize_text_field( $trip_info['start_date'] ) );
		}

		$trip_id      = $trip_info['id'];
		$package_name = sanitize_text_field( $trip_info['package_name'] ?? '' );

		// Process cart info and line items.

		$cart_info = $this->process_cart_line_items( $booking->get_cart_info() ?? array() );

		// Recreate request AFTER we may have updated $_POST for custom trips
		$this->request = Functions::create_request( 'POST' );

		// Get price key for cart item.
		$price_key = $this->get_price_key( $trip_id, $trip_info, $package_name );

		// Save travel insurance meta.
		$this->save_travel_insurance_meta( $booking );

		// Store initial booking data for first update.
		$this->store_initial_booking_data( $booking, $update );

		// Process traveller details.
		$this->process_traveller_details( $booking, $form_validator );

		// Process emergency contacts.
		$this->process_emergency_contacts( $booking, $form_validator );

		// Process payment amounts.
		$this->process_payment_amounts( $booking );

		// Update cart info with trip details.
		$cart_info = $this->update_cart_trip_info( $booking, $cart_info );

		// Process discounts/deductible items.
		if ( $booking->is_curr_cart( '<' ) ) {
			$cart_info = $this->process_discounts( $cart_info );
		}

		// Process commission.
		$this->process_commission( $booking );

		// Process line items.
		$cart_info = $this->process_line_items( $cart_info );

		// Update cart totals.
		$cart_info = $this->update_cart_totals( $booking, $cart_info );

		// Prepare and process cart items (moved here to use updated line items and subtotal_reservations).
		$cart_items = $this->prepare_cart_items( $trip_info, $trip_id, $price_key, $cart_info );
		$this->process_cart_and_booking( $wte_cart, $cart_items, $post_id, $booking );

		$cart_info['version'] ??= $this->request->get_param( 'wptravelengine_cart_version' );

		// Process payment records.
		$this->process_payments( $booking, $cart_info );

		// Process fees.
		$cart_info = $this->process_fees( $cart_info );

		// Save cart info to booking.
		$booking->set_cart_info( $cart_info );

		$booking->ensure_payment_key();

		do_action( 'wptravelengine_save_addon_meta', $this->request, $booking, $cart_info, $update );
	}

	/**
	 * @return void
	 * @since 6.4.0
	 */
	public function meta_box_booking() {
		add_meta_box(
			'booking_details_id',
			__( 'Booking Details', 'wp-travel-engine' ),
			array( $this, 'meta_box_booking_callback' ),
			'booking',
			'normal',
			'high'
		);
	}

	/**
	 * @return void
	 * @since 6.4.0
	 */
	public function meta_box_booking_callback() {
		global $post;
		global $current_screen;
		$booking = BookingModel::for( $post->ID, $post );

		$is_curr_cart = $booking->is_curr_cart();
		if ( ! $is_curr_cart ) {
			wp_enqueue_script( 'wptravelengine-booking-legacy-edit' );
		} else {
			wp_enqueue_script( 'wptravelengine-booking-edit' );
		}

		$action = '';

		if ( 'booking' === $current_screen->id && $current_screen->action == 'add' ) {
			$action = 'create';
		} elseif ( ( $_GET['wptravelengine_action'] ?? '' ) === 'edit' ) {
			$action = 'update';
		}

		switch ( $action ) {
			case 'update':
			case 'create':
				$this->create( $booking );
				break;
			default:
				$this->view( $booking );
				break;
		}
	}

	/**
	 * Prepares template arguments for Booking Page.
	 *
	 * @param BookingModel $booking
	 * @param string       $mode
	 *
	 * @return array
	 * @since 6.4.0
	 */
	protected function get_template_args( BookingModel $booking, string $mode = 'view' ): array {
		$package_name = $booking->get_order_items()[0]['package_name'] ?? '';

		$cart_info = $booking->get_cart_info() ?? array();

		if ( empty( $cart_info ) ) {
			$cart_info['version'] = Cart::CURRENT_VERSION;
			$booking->sync_metas( array( 'cart_info' => $cart_info ) );
		}

		$items = $cart_info['items'] ?? array();

		$items[0] = array_merge(
			$items[0] ?? array(),
			array(
				'package_name' => isset( $package_name ) && $package_name !== ''
					? $package_name
					: ( isset( $items[0]['package_name'] ) ? $items[0]['package_name'] : '' ),
			)
		);

		$cart_info['items'] = $items;

		$cart_info = new CartInfoParser( $cart_info );

		$order_trip = $cart_info->get_item();

		$package_name = $items[0]['trip_package'] ?? $items[0]['package_name'] ?? $order_trip->get_package_name();

		$mode = 'view' === $mode ? 'readonly' : 'edit';

		/**
		 * Filter the mode for each form field section.
		 *
		 * Allows addons to selectively override the mode for specific sections.
		 * For example, keep billing in 'edit' mode while making others 'readonly'.
		 *
		 * @since 6.7.1
		 *
		 * @param array   $field_modes Array of modes keyed by section name.
		 * @param string  $mode        The default mode ('edit' or 'readonly').
		 * @param Booking $booking     The booking object.
		 */
		$field_modes = apply_filters(
			'wptravelengine_booking_form_field_modes',
			array(
				'template_mode'      => $mode,
				'order_trip'         => $mode,
				'travellers'         => $mode,
				'emergency_contacts' => $mode,
				'billing'            => $mode,
				'payments'           => $mode,
				'admin_notes'        => $mode,
			),
			$mode,
			$booking
		);

		$order_trips        = $booking->get_meta( 'order_trips' );
		$cart_data          = $booking->get_cart_info();
		$pricing_line_items = $cart_data['items'][0]['line_items']['pricing_category'] ?? array();

		$travellers_with_categories = $this->map_travellers_to_pricing_categories(
			$booking->get_travelers(),
			$pricing_line_items
		);

		$payment_data = $cart_info->is_curr_cart_ver( '>=' ) ? $booking->get_payments_data( false )['payments'] ?? array() : array();

		$calculator = PaymentCalculator::for( 'USD' );

		$payment_status = array(
			'success' => wptravelengine_success_payment_status(),
			'pending' => wptravelengine_pending_payment_status(),
			'failed'  => wptravelengine_failed_payment_status(),
		);

		$order_trip_defaults = array(
			'id'                  => $order_trip->get_trip_id(),
			'booked_date'         => $booking->post->post_date ?? '',
			'start_date'          => $order_trip->get_trip_date(),
			'end_date'            => $order_trip->get_end_date(),
			'trip_code'           => ( $order_trip->get_trip_code() ?: ( $order_trip->get_trip_id() ? 'WTE-' . $order_trip->get_trip_id() : '' ) ),
			'number_of_travelers' => $order_trip->travelers_count(),
			'package_id'          => $order_trip->get_trip_package_id(),
			'package_name'        => $package_name,
		);

		if ( wptravelengine_toggled( get_post_meta( $order_trip_defaults['id'], 'is_created_from_booking', true ) ) ) {
			$order_trip_defaults['custom_trip'] = $order_trip->get_custom_trip();
		}

		return array(
			'booking'                        => $booking,
			'cart_info'                      => $cart_info,
			'template_mode'                  => $field_modes['template_mode'],
			'order_trip_form_fields'         => new OrderTripEditFormFields( $order_trip_defaults, $field_modes['order_trip'] ),
			'travellers_form_fields'         => array_map(
				function ( array $traveller, $index ) use ( $field_modes, $booking ) {
					$traveller['index'] = $index;

					return new TravellerEditFormFields( $traveller, $field_modes['travellers'], $booking );
				},
				$travellers_with_categories,
				array_keys( $travellers_with_categories )
			),
			'emergency_contacts_form_fields' => array_map(
				function ( array $emergency_contact, $index ) use ( $field_modes, $booking ) {
					$emergency_contact['index'] = $index;

					return new EmergencyEditFormFields( $emergency_contact, $field_modes['emergency_contacts'], $booking );
				},
				$booking->get_emergency_contacts(),
				array_keys( $booking->get_emergency_contacts() )
			),
			'billing_edit_form_fields'       => new BillingEditFormFields( $booking->get_billing_info(), $field_modes['billing'] ),
			'admin_notes_edit_form_fields'   => $field_modes['admin_notes'] ?? 'readonly',
			'payments_edit_form_fields'      => array_map(
				function ( Payment $payment ) use ( $field_modes, $booking, $payment_data, $payment_status ) {
					$gateway_response = $payment->get_gateway_response();
					$response         = '';
					if ( ! empty( $gateway_response ) ) :
						if ( is_array( $gateway_response ) || is_object( $gateway_response ) ) {
							$response = wp_json_encode( $gateway_response, JSON_PRETTY_PRINT );
						} else {
							$response = $gateway_response;
						}
					endif;

					$p_id = $payment->get_id();
					$_payment_data = $payment_data[ $p_id ] ?? array();

					$status = $payment->get_payment_status();

					if ( isset( $payment_status['success'][ $status ] ) ) {
						$status = 'completed';
					} elseif ( isset( $payment_status['pending'][ $status ] ) ) {
						$status = 'pending';
					} elseif ( isset( $payment_status['failed'][ $status ] ) ) {
						$status = 'failed';
					}

					// Get payment_source with fallback for existing payments.
					$payment_source = $payment->get_payment_source();

					$defaults = array(
						'id'               => $p_id,
						'status'           => $status,
						'gateway'          => $payment->get_payment_gateway(),
						'deposit'          => $_payment_data['deposit'] ?? 0,
						'amount'           => $payment->get_amount(),
						'date'             => $payment->get_transaction_date() ?: ( $payment->post->post_date ?? '' ),
						'currency'         => $payment->get_payable_currency(),
						'transaction_id'   => $payment->get_transaction_id(),
						'gateway_response' => $response,
						'payment_source'   => $payment_source,
					);

					$defaults['payable'] = $payment->get_payable_amount();

					$labels = array();

					$fees = $booking->get_fees();
					foreach ( $fees as $value ) {
						$slug = $value['name'];
						$labels[ $slug ] = $value['label'];
						$defaults[ $slug ] = $_payment_data[ $slug ] ?? 0;
					}

					return new PaymentEditFormFields(
						apply_filters(
							'wptravelengine_payment_edit_form_fields',
							$defaults,
							$payment
						),
						$field_modes['payments'],
						$labels
					);
				},
				$booking->get_payments()
			),
			'pricing_arguments'              => array(
				'currency_code' => $cart_info->get_currency(),
			),
			'calculator'                     => $calculator,
		);
	}

	/**
	 * Maps travellers to their corresponding pricing categories
	 *
	 * Converts pricing category names to term IDs for travellers.
	 * Sets default 'selectoption' for travellers without a pricing category.
	 *
	 * @param array $travellers Array of traveller data.
	 * @param array $pricing_line_items Array of pricing category line items (unused but kept for compatibility).
	 * @return array Array of travellers with pricing category information.
	 * @since 6.4.0
	 */
	private function map_travellers_to_pricing_categories( array $travellers, $pricing_line_items ): array {
		// Early return if no travellers.
		if ( empty( $travellers ) ) {
			return $travellers;
		}

		// Build a name-to-ID mapping cache for efficient lookups.
		$pricing_categories = get_terms(
			array(
				'taxonomy'   => 'trip-packages-categories',
				'hide_empty' => false,
				'orderby'    => 'term_id',
				'fields'     => 'id=>name',
			)
		);

		// Handle get_terms error.
		if ( is_wp_error( $pricing_categories ) ) {
			$pricing_categories = array();
		}

		// Flip array to create name => ID lookup map.
		$category_name_to_id = array_flip( $pricing_categories );

		// Process each traveller.
		foreach ( $travellers as &$traveller ) {
			// Skip if pricing_category not set or empty
			if ( ! isset( $traveller['pricing_category'] ) || empty( $traveller['pricing_category'] ) ) {
				continue;
			}

			$category = $traveller['pricing_category'];

			// Skip if already numeric (term ID)
			if ( is_numeric( $category ) ) {
				continue;
			}

			// Convert category name to term ID using cached lookup.
			if ( isset( $category_name_to_id[ $category ] ) ) {
				$traveller['pricing_category'] = $category_name_to_id[ $category ];
			}
		}

		return $travellers;
	}

	/**
	 *
	 *
	 * @return void
	 * @since 6.4.0
	 */
	protected function create( BookingModel $booking ) {
		$args = $this->get_template_args( $booking, 'edit' );
		wptravelengine_get_admin_template( $this->get_path_prefix( $args['cart_info'] ) . 'create.php', $args );
	}

	/**
	 * @param BookingModel $booking
	 *
	 * @return void
	 * @since 6.4.0
	 */
	protected function view( BookingModel $booking ) {
		$args = $this->get_template_args( $booking );
		wptravelengine_get_admin_template( $this->get_path_prefix( $args['cart_info'] ) . 'index.php', $args );
	}

	/**
	 * Get path prefix for template.
	 *
	 * @param CartInfoParser $cart_info Cart info parser.
	 * @return string
	 * @since 6.7.0
	 */
	protected function get_path_prefix( CartInfoParser $cart_info ): string {
		return $cart_info->is_curr_cart_ver( '>=' ) ? 'booking/' : 'booking/legacy/';
	}

	/**
	 * Return query after filtering bookings.
	 *
	 * @param object $query Query.
	 *
	 * @modified_since 6.4.0 Modified the query for the selected trip name and date range filter option.
	 *
	 * @return object $query
	 */
	public function filter_bookings( $query ) {
		// Modify the query only if it is admin and main query.
		if ( ! is_admin() || ! $query->is_main_query() ) {
			return $query;
		}
		$current_screen = get_current_screen();
		$trip_id        = 'all';
		$booking_status = 'all';
		if ( isset( $_REQUEST['trip_id'] ) ) {
			$trip_id = sanitize_text_field( wp_unslash( $_REQUEST['trip_id'] ) );
		}
		if ( isset( $_REQUEST['booking_status'] ) ) {
			$booking_status = sanitize_text_field( wp_unslash( $_REQUEST['booking_status'] ) );
		}
		$date_range = isset( $_REQUEST['wte_booking_range'] ) ? sanitize_text_field( $_REQUEST['wte_booking_range'] ) : '';
		$dates      = explode( ' to ', $date_range );

		// Store the dates in separate variables.
		$start_date = isset( $dates[0] ) ? $dates[0] : '';
		$end_date   = isset( $dates[1] ) ? $dates[1] : '';

		// Modify the query for the targeted screen and filter option.
		if ( ( 'edit-booking' !== $current_screen->id ) || ( 'all' === $booking_status && 'all' === $trip_id && empty( $date_range ) ) ) {
			return $query;
		}
		$filter_ids = wptravelengine_get_booking_ids( (int) $trip_id );
		$filter_ids = empty( $filter_ids ) ? array( 0 ) : $filter_ids;

		// Add query for selected booking status.
		if ( 'all' !== $booking_status ) {
			$query->set(
				'meta_query',
				array(
					array(
						'key'     => 'wp_travel_engine_booking_status',
						'compare' => '=',
						'value'   => $booking_status,
						'type'    => 'string',
					),
				)
			);
		}

		// Add query for selected trip ids.
		if ( 'all' !== $trip_id ) {
			$query->set(
				'post__in',
				$filter_ids
			);
		}
		// Add query for selected date range.
		if ( ! empty( $start_date ) && ! empty( $end_date ) ) {
			$query->set(
				'date_query',
				array(
					array(
						'after'     => $start_date,
						'before'    => $end_date,
						'inclusive' => true,
					),
				)
			);
		} elseif ( ! empty( $start_date ) ) {
			$get_specific_date = explode( '-', $start_date );
			$query->set(
				'date_query',
				array(
					array(
						'year'  => $get_specific_date[0],
						'month' => $get_specific_date[1],
						'day'   => $get_specific_date[2],
					),
				)
			);
		}

		return $query;
	}

	/**
	 * Verify nonce for booking save.
	 *
	 * @param \WP_Post $post Post object.
	 * @return bool
	 * @since 6.4.0
	 */
	private function verify_nonce( $post ): bool {
		return $this->post_type === $post->post_type
			&& isset( $_POST['wptravelengine_new_booking_nonce'] )
			&& wp_verify_nonce( $_POST['wptravelengine_new_booking_nonce'], 'wptravelengine_new_booking' );
	}

	/**
	 * Maybe publish draft post.
	 *
	 * @param int      $post_id Post ID.
	 * @param \WP_Post $post Post object.
	 * @return void
	 * @since 6.4.0
	 */
	private function maybe_publish_draft( int $post_id, \WP_Post $post ): void {
		if ( isset( $post->ID ) && $post->post_status === 'draft' ) {
			wp_update_post(
				array(
					'ID'          => $post_id,
					'post_status' => 'publish',
				)
			);
			clean_post_cache( $post_id );
			$post->post_status = 'publish';
		}
	}

	/**
	 * Create subtotal reservations for line items.
	 *
	 * @param string $key Line item key (e.g., 'extra_service', 'accommodation').
	 * @param array  $items Line items array.
	 * @return array Array with [reservation_key, subtotal_reservations].
	 * @since 6.7.0
	 */
	private function create_subtotal_reservations( string $key, array $items ): array {
		if ( 'extra_service' === $key ) {
			$subtotal_reservations = array_map(
				function ( $extra_service ) {
					return array(
						'id'       => $extra_service['id'] ?? 'es_' . uniqid(),
						'quantity' => (int) ( $extra_service['quantity'] ?? 1 ),
					);
				},
				$items
			);
			return array( 'extraServices', $subtotal_reservations );
		} else {
			$subtotal_reservations = array_map(
				function ( $acc_item ) use ( $key ) {
					return array(
						'id'       => null,
						'manual'   => true,
						'label'    => $acc_item['label'] ?? 'Manual ' . $key,
						'quantity' => (int) ( $acc_item['quantity'] ?? 1 ),
						'price'    => (float) ( $acc_item['price'] ?? 0 ),
						'total'    => (float) ( $acc_item['total'] ?? 0 ),
					);
				},
				$items
			);
			return array( $key, $subtotal_reservations );
		}
	}

	/**
	 * Assign subtotal reservations to cart info.
	 *
	 * @param array  $cart_info Cart info array (passed by reference).
	 * @param string $reservation_key Reservation key.
	 * @param array  $subtotal_reservations Subtotal reservations array.
	 * @return void
	 * @since 6.7.0
	 */
	private function assign_subtotal_reservations( array &$cart_info, string $reservation_key, array $subtotal_reservations ): void {
		$cart_info['items'][0]['subtotal_reservations'][ $reservation_key ] = $subtotal_reservations;
		$cart_info['subtotal_reservations'][ $reservation_key ]             = $subtotal_reservations;
	}

	/**
	 * Remove line item and its reservations from cart.
	 *
	 * @param array  $cart_info Cart info array (passed by reference).
	 * @param string $key Line item key to remove.
	 * @return void
	 * @since 6.7.0
	 */
	private function remove_line_item_from_cart( array &$cart_info, string $key ): void {
		unset( $cart_info['items'][0]['line_items'][ $key ] );
		unset( $cart_info['items'][0]['subtotal_reservations'][ $key ] );
		unset( $cart_info['subtotal_reservations'][ $key ] );
	}

	/**
	 * Process cart line items and subtotal reservations.
	 *
	 * @param array $cart_info Cart info array.
	 * @return array Modified cart info.
	 * @since 6.7.0
	 */
	private function process_cart_line_items( array $cart_info ): array {
		if ( ! isset( $cart_info['items'][0]['line_items'] ) || ! is_array( $cart_info['items'][0]['line_items'] ) ) {
			return $cart_info;
		}

		foreach ( $cart_info['items'][0]['line_items'] as $key => $line_item ) {
			if ( 'pricing_category' === $key || empty( $line_item ) ) {
				continue;
			}

			list( $reservation_key, $subtotal_reservations ) = $this->create_subtotal_reservations( $key, $line_item );
			$this->assign_subtotal_reservations( $cart_info, $reservation_key, $subtotal_reservations );
		}

		return $cart_info;
	}

	/**
	 * Get price key for cart item.
	 *
	 * @param string|int $trip_id Trip ID or 'other'.
	 * @param array      $trip_info Trip info array.
	 * @param string     $package_name Package name.
	 * @return string|int Price key.
	 * @since 6.7.0
	 */
	private function get_price_key( $trip_id, array $trip_info, string $package_name ) {
		if ( ! $trip_id ) {
			return 0;
		}

		if ( $trip_id === 'other' ) {
			return $trip_info['custom_trip'] ?? 'other';
		}

		if ( ! get_post( $trip_id ) ) {
			return $trip_id;
		}

		$trip          = new Trip( $trip_id );
		$trip_packages = new TripPackageIterator( $trip );

		foreach ( $trip_packages as $trip_package ) {
			/** @var TripPackage $trip_package */
			if ( isset( $trip_package->post->post_title ) && $trip_package->post->post_title === $package_name ) {
				return $trip_package->post->ID ?? $trip_id;
			}
		}

		return $trip_id;
	}

	/**
	 * Save travel insurance meta.
	 *
	 * @param BookingModel $booking Booking model.
	 * @return void
	 * @since 6.7.0
	 */
	private function save_travel_insurance_meta( BookingModel $booking ): void {
		if ( $travel_insurance = $this->request->get_param( 'travel_insurance_meta' ) ) {
			$booking->set_meta( 'wptravelengine_travel_insurance', $travel_insurance );
		}
	}

	/**
	 * Prepare cart items array.
	 *
	 * @param array      $trip_info Trip info.
	 * @param string|int $trip_id Trip ID.
	 * @param string|int $price_key Price key.
	 * @param array      $cart_info Cart info.
	 * @return array Cart items array.
	 * @since 6.7.0
	 */
	private function prepare_cart_items( array $trip_info, $trip_id, $price_key, array $cart_info ): array {
		$line_items = $this->request->get_param( 'line_items' );
		$pax        = array();
		if ( is_array( $line_items ) && isset( $line_items['pricing_category']['quantity'] ) ) {
			$pax = $line_items['pricing_category']['quantity'];
		}

		$cart_items = array(
			'trip_id'               => $trip_id,
			'trip_date'             => date( self::DATE_FORMAT, strtotime( $trip_info['start_date'] ?? '' ) ),
			'trip_time'             => date( self::DATETIME_FORMAT, strtotime( $trip_info['start_date'] ) ),
			'price_key'             => $price_key ?? 0,
			'pax'                   => $pax ?? array(),
			'pax_cost'              => array(),
			'trip_price'            => 0,
			'multi_pricing_used'    => false,
			'trip_extras'           => array(),
			'package_name'          => $trip_info['package_name'] ?? '',
			'subtotal_reservations' => $cart_info['subtotal_reservations'] ?? array(),
			'line_items'            => $cart_info['items'][0]['line_items'] ?? array(),
			'travelers_count'       => $trip_info['number_of_travelers'] ?? $this->get_travelers_count(),
			'trip_end_date'         => date( self::DATETIME_FORMAT, strtotime( $trip_info['end_date'] ) ),
			'trip_end_time'         => date( self::DATETIME_FORMAT, strtotime( $trip_info['end_date'] ) ),
		);

		if ( isset( $trip_info['end_date'] ) ) {
			$cart_items['trip_time_range'] = array(
				date( self::DATETIME_FORMAT, strtotime( $trip_info['start_date'] ?? '' ) ),
				date( self::DATETIME_FORMAT, strtotime( $trip_info['end_date'] ?? '' ) ),
			);
		}

		return $cart_items;
	}

	/**
	 * Process cart and booking.
	 *
	 * @param object       $wte_cart Cart object.
	 * @param array        $cart_items Cart items.
	 * @param int          $post_id Post ID.
	 * @param BookingModel $booking Booking model.
	 * @return void
	 * @since 6.7.0
	 */
	private function process_cart_and_booking( $wte_cart, array $cart_items, int $post_id, BookingModel $booking ): void {
		$wte_cart->clear();

		if ( is_numeric( $cart_items['trip_id'] ) ) {
			$item = new \WPTravelEngine\Core\Cart\Item( $wte_cart, $cart_items );
			$wte_cart->setItems( array( $cart_items['trip_id'] => $item ) );
			$wte_cart->add( $item );
			$wte_cart->set_booking_ref( $post_id );
		}

		$booking_process = new BookingProcess( $this->request, $wte_cart );

		if ( ! empty( $wte_cart->getItems( true ) ) && ! empty( $post_id ) && get_post( $post_id ) ) {
			$booking_post = BookingModel::make( $post_id );
			$booking_post->set_order_items( $wte_cart->getItems( true ) );
			$booking_process->set_order_items( $booking_post );
		}
	}

	/**
	 * Store initial booking data.
	 *
	 * @param BookingModel $booking Booking model.
	 * @param bool         $update Whether this is an update.
	 * @return void
	 * @since 6.7.0
	 */
	private function store_initial_booking_data( BookingModel $booking, bool $update ): void {
		if ( ! $update ) {
			return;
		}

		$cart_info = $booking->get_cart_info();
		if ( $booking->get_meta( '_initial_cart_info' ) === '' ) {
			$booking->set_meta( '_initial_cart_info', wp_json_encode( $cart_info ) );
		}

		$order_items = $booking->get_order_items();
		if ( $booking->get_meta( '_initial_order_items' ) === '' ) {
			$booking->set_meta( '_initial_order_items', wp_json_encode( $order_items ) );
		}
	}

	/**
	 * Process traveller details.
	 *
	 * @param BookingModel $booking Booking model.
	 * @param Validator    $form_validator Form validator.
	 * @return void
	 * @since 6.7.0
	 */
	private function process_traveller_details( BookingModel $booking, Validator $form_validator ): void {
		$travellers = $this->request->get_param( 'travellers' );

		if ( ! $travellers ) {
			$booking->set_traveller_details( array() );
			return;
		}

		$sanitized_data = array();

		foreach ( $travellers as $field => $values ) {
			if ( ! is_array( $values ) || 'pricing_category' === $field ) {
				continue;
			}

			foreach ( $values as $index => $value ) {
				if ( ! isset( $sanitized_data[ $index ] ) ) {
					$sanitized_data[ $index ] = array();
				}

				if ( is_array( $value ) ) {
					$sanitized_data[ $index ][ $field ] = $value;
					continue;
				}

				if ( ! empty( $value ) || $value === '' ) {
					$sanitized_data[ $index ][ $field ] = $this->sanitize_traveller_field( $field, $value, $form_validator );
				}
			}
		}

		$booking->set_traveller_details( array_values( $sanitized_data ) );
	}

	/**
	 * Sanitize traveller field.
	 *
	 * @param string    $field Field name.
	 * @param mixed     $value Field value.
	 * @param Validator $form_validator Form validator.
	 * @return mixed Sanitized value.
	 * @since 6.4.0
	 */
	private function sanitize_traveller_field( string $field, $value, Validator $form_validator ) {
		// Handle null or empty values.
		if ( $value === null ) {
			return '';
		}

		switch ( $field ) {
			case 'email':
				return sanitize_email( $value );
			case 'phone':
				return $form_validator->sanitize_phone( $value );
			case 'country':
				return $form_validator->sanitize_country( $value );
			default:
				return sanitize_text_field( $value );
		}
	}

	/**
	 * Process emergency contacts.
	 *
	 * @param BookingModel $booking Booking model.
	 * @param Validator    $form_validator Form validator.
	 * @return void
	 * @since 6.7.0
	 */
	private function process_emergency_contacts( BookingModel $booking, Validator $form_validator ): void {
		$emergency_contacts = $this->request->get_param( 'emergency_contacts' );
		if ( ! $emergency_contacts ) {
			return;
		}

		$data = array();
		foreach ( array_keys( $emergency_contacts ) as $entity ) {
			foreach ( $emergency_contacts[ $entity ] as $index => $value ) {
				$data[ $index ][ $entity ] = $value;
			}
		}

		$sanitized_data = array_map(
			function ( $emergency_contact ) use ( $form_validator ) {
				$sanitized = array();
				foreach ( $emergency_contact as $field => $value ) {
					if ( is_array( $value ) ) {
						$sanitized[ $field ] = $value;
						continue;
					}
					$sanitized[ $field ] = $this->sanitize_traveller_field( $field, $value, $form_validator );
				}
				return $sanitized;
			},
			$data
		);

		$booking->set_emergency_contact_details( $sanitized_data );
	}

	/**
	 * Process billing details.
	 *
	 * @param BookingModel $booking Booking model.
	 * @param int          $post_id Post ID.
	 * @param Validator    $form_validator Form validator.
	 * @return void
	 * @since 6.7.0
	 */
	private function process_billing_details( BookingModel $booking, int $post_id, Validator $form_validator ): void {
		$billing_details = $this->request->get_param( 'billing' );
		if ( ! $billing_details ) {
			return;
		}

		$billing_email = $billing_details['email'] ?? '';

		if ( ! empty( $billing_email ) && is_email( $billing_email ) ) {
			$customer_id    = Customer::is_exists( $billing_email );
			$customer_model = null;

			if ( $customer_id ) {
				try {
					$customer_model = new Customer( $customer_id );
				} catch ( \Exception $e ) {
					$customer_model = null;
				}
			} else {
				$customer_model = Customer::create_post(
					array(
						'post_status' => 'publish',
						'post_type'   => 'customer',
						'post_title'  => sanitize_email( $billing_email ),
					)
				);
			}

			if ( $customer_model instanceof Customer ) {
				if ( ! email_exists( $billing_email ) ) {
					$customer_model->maybe_register_as_user( true );
				}

				do_action( 'wptravelengine_after_customer_created', $customer_model->ID );

				$customer_model->update_customer_bookings( $post_id );
				$customer_model->update_customer_meta( $post_id );
				$customer_model->save();
			}
		}

		$sanitized_billing = array();
		foreach ( $billing_details as $field => $value ) {
			if ( is_array( $value ) ) {
				$sanitized_billing[ $field ] = array_map( 'sanitize_text_field', $value );
				continue;
			}
			if ( is_string( $value ) && filter_var( $value, FILTER_VALIDATE_URL ) ) {
				$sanitized_billing[ $field ] = basename( $value );
				continue;
			}
			$sanitized_billing[ $field ] = $this->sanitize_traveller_field( $field, $value, $form_validator );
		}

		$booking->set_meta( 'wptravelengine_billing_details', $sanitized_billing );
		$booking->set_meta( 'billing_info', $sanitized_billing );
	}

	/**
	 * Process payment amounts.
	 *
	 * @param BookingModel $booking Booking model.
	 * @return void
	 * @since 6.7.0
	 */
	private function process_payment_amounts( BookingModel $booking ): void {
		if ( is_numeric( $paid_amount = $this->request->get_param( 'paid_amount' ) ) ) {
			$booking->set_meta( 'paid_amount', (float) $paid_amount );
		}

		if ( is_numeric( $due_amount = $this->request->get_param( 'due_amount' ) ) ) {
			$booking->set_meta( 'due_amount', (float) $due_amount );
		}
	}

	/**
	 * Process notes.
	 *
	 * @param BookingModel $booking Booking model.
	 * @return void
	 * @since 6.7.0
	 */
	private function process_notes( BookingModel $booking ): void {
		if ( $additional_note = $this->request->get_param( 'additional_details' ) ) {
			$booking->set_additional_details( sanitize_text_field( $additional_note ) );
		}

		if ( $admin_notes = $this->request->get_param( 'admin_notes' ) ) {
			$booking->set_notes( sanitize_text_field( $admin_notes ) );
		}
	}

	/**
	 * Update cart info with trip details.
	 *
	 * @param BookingModel $booking Booking model.
	 * @return array Modified cart info.
	 * @since 6.7.0
	 */
	private function update_cart_trip_info( BookingModel $booking, array $cart_info ): array {
		$trip_info = $this->request->get_param( 'order_trip' );
		if ( ! $trip_info ) {
			return $cart_info;
		}

		$sanitized_trip_info = array(
			'id'                  => absint( $trip_info['id'] ?? 0 ),
			'start_date'          => sanitize_text_field( $trip_info['start_date'] ?? '' ),
			'end_date'            => sanitize_text_field( $trip_info['end_date'] ?? '' ),
			'trip_code'           => sanitize_text_field( $trip_info['trip_code'] ?? '' ),
			'number_of_travelers' => absint( $trip_info['number_of_travelers'] ?? $this->get_travelers_count() ),
			'package_name'        => sanitize_text_field( $trip_info['package_name'] ?? '' ),
			'custom_trip'         => sanitize_text_field( $trip_info['custom_trip'] ?? '' ),
		);

		// If id resolved to 0 (e.g., custom trip with request created before id override), preserve existing numeric trip_id from cart_info
		if ( 0 === $sanitized_trip_info['id'] && isset( $cart_info['items'][0]['trip_id'] ) && is_numeric( $cart_info['items'][0]['trip_id'] ) ) {
			$sanitized_trip_info['id'] = (int) $cart_info['items'][0]['trip_id'];
		}

		$start_date = DateTime::createFromFormat( 'Y-m-d H:i', $sanitized_trip_info['start_date'] );
		$end_date   = DateTime::createFromFormat( 'Y-m-d H:i', $sanitized_trip_info['end_date'] );

		$sanitized_trip_info['start_date'] = $start_date ? $start_date->format( self::DATETIME_FORMAT ) : current_time( self::DATETIME_FORMAT );
		$sanitized_trip_info['end_date']   = $end_date ? $end_date->format( self::DATETIME_FORMAT ) : current_time( self::DATETIME_FORMAT );

		// Ensure items array exists.
		if ( ! isset( $cart_info['items'][0] ) ) {
			$cart_info['items'][0] = array();
		}

		$cart_info['items'][0]['trip_id']          = $sanitized_trip_info['id'];
		$cart_info['items'][0]['trip_date']        = $sanitized_trip_info['start_date'];
		$cart_info['items'][0]['trip_time']        = $sanitized_trip_info['start_date'];
		$cart_info['items'][0]['end_date']         = $sanitized_trip_info['end_date'];
		$cart_info['items'][0]['travelers_count']  = $sanitized_trip_info['number_of_travelers'];
		$cart_info['items'][0]['trip_package']     = $sanitized_trip_info['package_name'];
		$cart_info['items'][0]['custom_trip_name'] = $sanitized_trip_info['custom_trip'];

		return $cart_info;
	}

	/**
	 * Process discounts.
	 *
	 * @param array $cart_info Cart info.
	 * @return array Modified cart info.
	 * @since 6.7.0
	 */
	private function process_discounts( array $cart_info ): array {
		if ( ! $this->request->get_param( 'discounts' ) ) {
			unset( $cart_info['totals']['total_discount'] );
			unset( $cart_info['deductible_items'] );
			return $cart_info;
		}

		$deductible_items = $this->request->get_param( 'discounts' );
		$items            = ArrayUtility::normalize( $deductible_items, 'label' );
		$_items           = array();

		foreach ( $items as $index => $item ) {
			$percentage = '';
			if ( preg_match( '/(\d+)%/', $item['label'], $matches ) ) {
				$percentage = $matches[1];
			}

			$_items[] = wp_parse_args(
				$item,
				array(
					'name'                     => 'discount' . $index,
					'order'                    => $index,
					'label'                    => $item['label'],
					'description'              => '',
					'adjustment_type'          => 'percentage',
					'apply_to_actual_subtotal' => false,
					'percentage'               => $percentage,
					'value'                    => $item['value'],
					'_class_name'              => CouponAdjustment::class,
					'type'                     => 'deductible',
				)
			);
			$cart_info['totals'][ 'total_discount' . $index ] = $item['value'];
		}

		$cart_info['deductible_items'] = $_items;
		return $cart_info;
	}

	/**
	 * Process commission.
	 *
	 * @param BookingModel $booking Booking model.
	 * @return void
	 * @since 6.7.0
	 */
	private function process_commission( BookingModel $booking ): void {
		$commission_amount = $this->request->get_param( 'wptravelengine_commission_amount' );
		if ( ! $commission_amount || ! function_exists( 'slicewp_get_commissions' ) ) {
			return;
		}

		$commissions = slicewp_get_commissions(
			array(
				'reference' => $booking->ID,
				'origin'    => 'wptravelengine',
				'number'    => 1,
			)
		);

		if ( ! empty( $commissions ) && function_exists( 'slicewp_update_commission' ) ) {
			slicewp_update_commission(
				$commissions[0]->get( 'id' ),
				array(
					'amount' => $commission_amount,
				)
			);
		}
	}

	/**
	 * Process fees.
	 *
	 * @param array $cart_info Cart info.
	 * @return array Modified cart info.
	 * @since 6.7.0
	 */
	private function process_fees( array $cart_info ): array {
		if ( ! $this->request->get_param( 'fees' ) ) {
			unset( $cart_info['tax_amount'] );
			unset( $cart_info['totals']['total_fee'] );
			unset( $cart_info['fees'] );
			return $cart_info;
		}

		$fees  = $this->request->get_param( 'fees' );
		$items = ArrayUtility::normalize( $fees, 'label' );

		$_items    = array();
		$def_class = TaxAdjustment::class;
		$tax       = new Tax();

		foreach ( $items as $index => $item ) {

			if ( isset( $item['slug'] ) && ! isset( $this->set_fees[ $item['slug'] ] ) ) {
				continue;
			}

			$percentage = '';
			if ( preg_match( '/(\d+)%/', $item['label'], $matches ) ) {
				$percentage = $matches[1];
			}

			$_items[ $index ] = wp_parse_args(
				$item,
				array(
					'order'                    => $index,
					'description'              => '',
					'apply_to_actual_subtotal' => false,
					'type'                     => 'fee',
				)
			);

			$item_class = ( $item['_class_name'] ?? '' ) ?: null;

			if ( ! isset( $item_class ) ) {
				$percentage = $tax->get_tax_percentage();
				$item_class = $def_class;
			}

			$_items[ $index ]['name']            = ( $item['slug'] ?? '' ) ?: ( '_fee' . $index );
			$_items[ $index ]['label']           = ( $item['label'] ?? '' ) ?: '';
			$_items[ $index ]['value']           = ( $item['value'] ?? '' ) ?: 0;
			$_items[ $index ]['_class_name']     = $item_class;
			$_items[ $index ]['adjustment_type'] = ( $item['adjustment_type'] ?? '' ) ?: 'percentage';
			$_items[ $index ]['percentage']      = ( $item['percentage'] ?? '' ) ?: $percentage;
			$_items[ $index ]['apply_tax']       = wptravelengine_toggled( ( $item['apply_tax'] ?? '' ) ?: true );
			$_items[ $index ]['apply_upfront']   = wptravelengine_toggled( ( $item['apply_upfront'] ?? '' ) ?: false );

			$cart_info['totals'][ 'total_fee' . $index ] = $item['value'];
		}

		$cart_info['fees'] = $_items;
		return $cart_info;
	}

	/**
	 * Process line items.
	 *
	 * @param array $cart_info Cart info.
	 * @return array Modified cart info.
	 * @since 6.7.0
	 */
	private function process_line_items( array $cart_info ): array {
		$line_items = $this->request->get_param( 'line_items' );

		// If no line items submitted, clear all non-pricing_category line items from cart
		if ( ! $line_items ) {
			if ( isset( $cart_info['items'][0]['line_items'] ) ) {
				foreach ( $cart_info['items'][0]['line_items'] as $key => $value ) {
					if ( 'pricing_category' !== $key ) {
						unset( $cart_info['items'][0]['line_items'][ $key ] );
					}
				}
			}
			return $cart_info;
		}

		// Remove line items from cart that weren't submitted in the form
		if ( isset( $cart_info['items'][0]['line_items'] ) ) {
			foreach ( $cart_info['items'][0]['line_items'] as $key => $value ) {
				if ( 'pricing_category' !== $key && ! isset( $line_items[ $key ] ) ) {
					// This line item type exists in cart but wasn't submitted - remove it
					$this->remove_line_item_from_cart( $cart_info, $key );
					error_log( "REMOVED LINE ITEM TYPE '$key' - not in form submission" );
				}
			}
		}

		foreach ( $line_items as $key => $item ) {
			if ( 'pricing_category' === $key && ! isset( $item['label'] ) ) {
				// Get existing pricing category data from cart
				$existing_items = $cart_info['items'][0]['line_items'][ $key ] ?? array();

				$_items = array();

				// Build a lookup map: category_id => label from term taxonomy
				$category_labels = array();
				foreach ( $item as $cat_id => $cat_data ) {
					if ( is_numeric( $cat_id ) && $cat_id > 0 ) {
						$term = get_term( $cat_id, 'trip-packages-categories' );
						if ( $term && ! is_wp_error( $term ) ) {
							$category_labels[ $cat_id ] = $term->name;
						}
					}
				}

				// New format: line_items[pricing_category][category_id][price][] and [quantity][]
				foreach ( $item as $category_id => $category_data ) {
					if ( ! is_array( $category_data ) ) {
						continue;
					}

					$cart_info['items'][0]['travelers'][ $category_id ] = $category_data['quantity'][0] ?? $cart_info['items'][0]['travelers'][ $category_id ] ?? 0;

					// Get price and quantity from the new structure
					$price    = isset( $category_data['price'][0] ) ? (float) $category_data['price'][0] : 0;
					$quantity = isset( $category_data['quantity'][0] ) ? (float) $category_data['quantity'][0] : 1;

					// Find existing item - first try by category_id, then by label
					$existing_item = null;
					$label         = $category_labels[ $category_id ] ?? '';

					foreach ( $existing_items as $existing ) {
						// Try matching by category_id first
						if ( isset( $existing['category_id'] ) && $existing['category_id'] == $category_id ) {
							$existing_item = $existing;
							break;
						}
						// Fall back to matching by label if category_id not present
						if ( ! empty( $label ) && isset( $existing['label'] ) && $existing['label'] === $label ) {
							$existing_item = $existing;
							break;
						}
					}

					if ( $existing_item ) {
						// Preserve existing data and update price, quantity, and add category_id
						$_items[] = array_merge(
							$existing_item,
							array(
								'category_id' => $category_id, // Add category_id for future lookups
								'price'       => $price,
								'quantity'    => $quantity,
								'total'       => $price * $quantity,
							)
						);
					} else {
						// No existing item found - create new item with minimal required data
						error_log( "INFO: Creating new pricing category item for category_id: $category_id (label: $label)" );
						$_items[] = array(
							'label'       => $label ?: 'Category ' . $category_id,
							'category_id' => $category_id,
							'quantity'    => $quantity,
							'price'       => $price,
							'total'       => $price * $quantity,
							'pricingType' => 'per-person', // Default pricing type
							'_class_name' => PricingCategory::class,
						);
					}
				}

				if ( ! empty( $_items ) ) {
					$cart_info['items'][0]['line_items'][ $key ] = $_items;
				}
				continue;
			}

			// First check if the raw item data is empty (all arrays are empty)
			$is_empty_submission = true;
			if ( is_array( $item ) ) {
				foreach ( $item as $field_values ) {
					if ( is_array( $field_values ) && ! empty( $field_values ) ) {
						// Check if any value in the array is non-empty
						foreach ( $field_values as $value ) {
							if ( ! empty( trim( $value ) ) ) {
								$is_empty_submission = false;
								break 2; // Break out of both loops
							}
						}
					}
				}
			}

			// If submission is completely empty, remove from cart and skip
			if ( $is_empty_submission ) {
				$this->remove_line_item_from_cart( $cart_info, $key );
				continue;
			}

			// Normalize the item to get proper structure
			$items = ArrayUtility::normalize( $item, 'label' );

			$_items = array();

			foreach ( $items as $_item ) {
				if ( ! is_numeric( $_item['price'] ?: '' ) || empty( $_item['label'] ) ) {
					continue;
				}

				$_class_name = apply_filters(
					'wptravelengine_custom_line_item_class',
					$key === 'pricing_category' ? PricingCategory::class : ( $key === 'extra_service' ? ExtraService::class : false ),
					$key
				);

				if ( ! is_subclass_of( $_class_name, CartItem::class ) ) {
					continue;
				}

				$_item['total'] = (float) $_item['price'] * (float) $_item['quantity'];

				$_items[] = wp_parse_args(
					$_item,
					array(
						'label'       => $_item['label'],
						'quantity'    => $_item['quantity'],
						'price'       => $_item['price'],
						'_class_name' => $_class_name,
					)
				);
			}

			$cart_info['items'][0]['line_items'][ $key ] = $_items;

			// Process subtotal_reservations
			if ( 'pricing_category' === $key || empty( $_items ) ) {
				continue;
			}

			list( $reservation_key, $subtotal_reservations ) = $this->create_subtotal_reservations( $key, $_items );
			$this->assign_subtotal_reservations( $cart_info, $reservation_key, $subtotal_reservations );
		}

		return $cart_info;
	}

	/**
	 * Update cart totals.
	 *
	 * @param BookingModel $booking Booking model.
	 * @param array        $cart_info Cart info.
	 * @since 6.7.0
	 * @since 6.7.6 - removed partial total from cart info.
	 */
	private function update_cart_totals( BookingModel $booking, array $cart_info ): array {
		if ( is_numeric( $total = $this->request->get_param( 'total' ) ) ) {
			$cart_info['total']           = (float) $total;
			$cart_info['totals']['total'] = (float) $total;
		}

		if ( is_numeric( $subtotal = $this->request->get_param( 'subtotal' ) ) ) {
			$cart_info['subtotal']           = (float) $subtotal;
			$cart_info['totals']['subtotal'] = (float) $subtotal;
		}

		if ( is_numeric( $due_amount = $this->request->get_param( 'due_amount' ) ) ) {
			$booking->set_total_due_amount( (float) $due_amount );
			$cart_info['totals']['due_total'] = (float) $due_amount;
		}

		return $cart_info;
	}

	/**
	 * Process payments.
	 *
	 * @param BookingModel $booking Booking model.
	 * @param array        $cart_info Booking Cart Info.
	 * @return void
	 */
	private function process_payments( BookingModel $booking, array &$cart_info ): void {
		$payments = $this->request->get_param( 'payments' );
		$fees     = $this->request->get_param( 'fees' );
		$fees     = $fees ? ( $fees['slug'] ?? array() ) : array();

		if ( ! $payments ) {
			array_map(
				function ( $fee ) {
					$this->set_fees[ $fee ] = true;
				},
				$fees
			);
			return;
		}

		$items = ArrayUtility::normalize( $payments, 'gateway' );
		$calc  = PaymentCalculator::for( 'USD' );

		$_payments = array();

		$actual_deposit_                            = '0.00';
		$cart_info['totals']['deposit']             = '0.00';
		$cart_info['totals']['total_extra_charges'] = '0.00';

		$success_status = wptravelengine_success_payment_status();

		foreach ( $items as $payment_data ) {
			try {
				$payment_model = new Payment( (int) $payment_data['id'] );
			} catch ( \Exception $e ) {
				$payment_model = Payment::create_post(
					array(
						'post_type'   => 'wte-payments',
						'post_status' => 'publish',
						'post_title'  => 'Payment',
					)
				);
			}

			if ( $status = $payment_data['status'] ?? null ) {
				$payment_model->set_status( sanitize_text_field( $status ) );
			} else {
				$status = $payment_model->get_meta( 'payment_status' );
			}

			$payment_cart_total = ArrayUtility::make( $payment_model->get_cart_totals() ?: $cart_info['totals'] );

			if ( is_numeric( $p_deposit = $payment_data['deposit'] ?? null ) ) {
				$p_deposit = $calc->normalize( (string) $p_deposit );
				$payment_cart_total->set( 'deposit', $p_deposit );
				$cart_info['totals']['deposit'] = $calc->add(
					(string) $cart_info['totals']['deposit'],
					$p_deposit
				);
				if ( isset( $success_status[ $status ] ) ) {
					$actual_deposit_ = $calc->add( $actual_deposit_, $p_deposit );
				}
			}

			$extra_fee = '0';
			foreach ( $fees as $fee ) {
				$p_fee = floatval( $payment_data[ $fee ] ?? '' );
				if ( $p_fee > 0.00 ) {
					$p_fee = $calc->normalize( (string) $p_fee );
					$payment_cart_total->set( 'total_' . $fee, $p_fee );
					$extra_fee              = $calc->add( $extra_fee, $p_fee );
					$this->set_fees[ $fee ] = true;
				}
			}

			if ( '0' !== $extra_fee ) {
				$payment_cart_total->set( 'total_extra_charges', $extra_fee );
				$cart_info['totals']['total_extra_charges'] = $extra_fee;
			}

			$payment_model->set_meta( 'cart_totals', $payment_cart_total->value() );

			if ( $gateway = $payment_data['gateway'] ?? null ) {
				$payment_model->set_meta( 'payment_gateway', sanitize_text_field( $gateway ) );
				$booking->set_meta( 'wp_travel_engine_booking_payment_gateway', sanitize_text_field( $gateway ) );
			}

			$payment_currency = sanitize_text_field( $payment_data['currency'] ?? '' );
			if ( '' === $payment_currency ) {
				$payment_currency = $payment_model->get_currency();
			}
			if ( '' === $payment_currency ) {
				$payment_currency = $cart_info['currency'] ?? wptravelengine_settings()->get( 'currency_code', 'USD' );
			}
			$payment_currency = $payment_currency ?: 'USD';

			$paid_amount = $payment_data['amount'] ?? null;
			$payment_model->set_meta(
				'payment_amount',
				array(
					'value'    => is_numeric( $paid_amount ) ? (float) $paid_amount : 0,
					'currency' => $payment_currency,
				)
			);

			if ( is_numeric( $due_amount = $this->request->get_param( 'due_amount' ) ) ) {
				$payment_model->set_meta(
					'payable',
					array(
						'currency' => $payment_currency,
						'amount'   => (float) $due_amount,
					)
				);
			}

			if ( $transaction_id = $payment_data['transaction_id'] ?? null ) {
				$payment_model->set_transaction_id( sanitize_text_field( $transaction_id ) );
			}

			$transaction_date = $payment_data['date'] ?? $payment_data['transaction_date'] ?? null;
			if ( $transaction_date ) {
				$payment_model->set_transaction_date( sanitize_text_field( $transaction_date ) );
			}

			if ( $gateway_response = $payment_data['gateway_response'] ?? null ) {
				$payment_model->set_meta( 'gateway_response', sanitize_text_field( $gateway_response ) );
			}

			if ( empty( $payment_model->get_meta( 'booking_id' ) ) ) {
				$payment_model->set_meta( 'booking_id', $booking->ID );
			}

			if ( empty( $payment_model->get_meta( 'payment_source' ) ) ) {
				$payment_model->set_meta( 'payment_source', $payment_model->get_payment_source() );
			}

			$payment_model->save();
			$_payments[] = $payment_model->get_id();
		}

		if ( version_compare( $cart_info['version'], '4.0', '>=' ) ) {
			if ( is_numeric( $total = $this->request->get_param( 'total' ) ) ) {
				$due_exclusive = $calc->subtract( (string) $total, (string) $actual_deposit_ );
				$booking->set_meta( 'total_due_amount', $due_exclusive );
			}
		}

		$last_status = $payment_model->get_meta( 'payment_status' );
		$booking->set_meta( 'wp_travel_engine_booking_payment_status', $last_status );

		$booking->set_meta( 'payments', $_payments );
		/*
		Moved from update_cart_totals to process_payments.
		*  @since 6.7.6
		*/
		$booking->set_total_paid_amount( (float) $paid_amount );
	}

	/**
	 * Get travelers count.
	 *
	 * @return int Travelers count.
	 * @since 6.7.0
	 */
	private function get_travelers_count(): int {
		$line_items = $this->request->get_param( 'line_items' );

		$travelers_count = 0;
		foreach ( $line_items['pricing_category'] ?? array() as $_val ) {
			$travelers_count += array_sum( array_map( 'intval', $_val['quantity'] ?? array() ) );
		}

		return $travelers_count;
	}
}
