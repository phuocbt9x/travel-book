<?php

namespace WPTravelEngine\Filters;

/**
 * Trip Meta Tabs Filter.
 *
 * @since 6.2.2
 */
class TripMetaTabs {

	/**
	 * Instance.
	 *
	 * @var TripMetaTabs
	 */
	protected static $instance;

	/**
	 * Global settings.
	 *
	 * @var array
	 */
	protected static array $global_settings;

	/**
	 * Returns trip api schema instance.
	 */
	public static function instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->hooks();
	}

	/**
	 * Hooks into `wptravelengine_admin_trip_meta_tabs` filter.
	 *
	 * @return void
	 */
	public function hooks() {
		add_action(
			'init',
			function () {
				do_action( 'wptravelengine_trip_api_schema_hooks', $this );
			}
		);
		add_filter( 'wptravelengine_tripedit:extensions:fields', array( $this, 'fixed_starting_dates_fields' ), 10, 2 );
		add_filter( 'wptravelengine_tripedit:extensions:fields', array( $this, 'partial_payment_fields' ), 10, 2 );
		add_filter( 'wptravelengine_tripedit:extensions:fields', array( $this, 'file_downloads_fields' ), 10, 2 );
		add_filter( 'wptravelengine_tripedit:extensions:fields', array( $this, 'extra_services_fields' ), 10, 2 );
		add_filter( 'wptravelengine_tripedit:extensions:fields', array( $this, 'shortcodes_fields' ), 10, 2 );
		add_filter( 'wptravelengine_tripedit:extensions:fields', array( $this, 'itinerary_downloader_fields' ), 10, 2 );
		add_filter( 'wptravelengine_tripedit:extensions:fields', array( $this, 'custom_booking_link_fields' ), 10, 2 );
	}

	/**
	 * Filters the custom booking link fields.
	 *
	 * @param array  $fields
	 * @param string $extension
	 *
	 * @return array
	 */
	public function custom_booking_link_fields( array $fields, string $extension ) {
		if ( 'custom-booking-link' === $extension ) {
			$fields = array(
				array(
					'field' => array(
						'type'    => 'ALERT',
						'content' => __( '<strong>NOTE:</strong> Do you want to use a custom booking link for this trip instead of the default booking process? The Custom Booking Link addon allows you to replace the default booking link with any URL of your choice. <a href="https://wptravelengine.com/plugins/custom-booking-link/?utm_source=free_plugin&utm_medium=pro_addon&utm_campaign=upgrade_to_pro" target="_blank">Get Custom Booking Link addon now</a>', 'wp-travel-engine' ),
						'status'  => 'upgrade',
					),
				),
				array(
					'field' => array(
						'type'       => 'SCREENSHOT',
						'url'        => WP_TRAVEL_ENGINE_URL . '/assets/images/custom-booking-link-screenshot.png',
						'visibility' => ! wptravelengine_is_addon_active( 'custom-booking-link' ),
					),
				),
			);
		}
		return $fields;
	}

	/**
	 * Filters the trip meta fields.
	 *
	 * @param array  $fields
	 * @param string $extension
	 *
	 * @return array
	 */
	public function fixed_starting_dates_fields( array $fields, string $extension ): array {
		if ( 'fixed-starting-dates' === $extension && wptravelengine_is_addon_active( 'fixed-starting-dates' ) ) {
			$fields = array(
				array(
					'label'       => __( 'Section Title', 'wp-travel-engine' ),
					'description' => __( 'Enter title for the Availability section.', 'wp-travel-engine' ),
					'divider'     => true,
					'field'       => array(
						'name'        => 'fsd.title',
						'type'        => 'TEXT',
						'placeholder' => __( 'Enter here', 'wp-travel-engine' ),
					),
				),
				array(
					'divider'     => true,
					'label'       => __( 'Hide Fixed Trip Starts Dates section', 'wp-travel-engine' ),
					'description' => __( 'Check this if you want to disable fixed trip starting dates section between featured image/slider and trip content sections.', 'wp-travel-engine' ),
					'field'       => array(
						'name' => 'fsd.hide',
						'type' => 'SWITCH',
					),
				),
			);
		}
		return $fields;
	}

	/**
	 * Returns partial payment fields.
	 *
	 * @param array  $fields
	 * @param string $extension
	 *
	 * @return array
	 */
	public function partial_payment_fields( array $fields, string $extension ): array {
		if ( 'partial-payment' === $extension ) {
			$global_settings            = self::get_global_settings();
			$is_partial_payment_active  = wptravelengine_is_addon_active( 'partial-payment' );
			$is_percentage              = 'percent' === ( $global_settings['partial_payment_option'] ?? false );
			$is_partial_payment_enabled = wptravelengine_toggled( $global_settings['partial_payment_enable'] ?? false );

			$fields = array(
				array(
					'field' => array(
						'type'  => 'TITLE',
						'title' => __( 'Partial Payment', 'wp-travel-engine' ),
					),
				),
				array(
					'field'      => array(
						'type'    => 'ALERT',
						'content' => __( 'Want to collect upfront or partial payment? Partial Payment extension allows you to set upfront payment in percentage or fixed amount which travellers can pay when booking a tour. <a href="https://wptravelengine.com/plugins/partial-payment/?utm_source=free_plugin&utm_medium=pro_addon&utm_campaign=upgrade_to_pro" target="_blank">Get Partial Payment extension now</a>', 'wp-travel-engine' ),
					),
					'visibility' => ! $is_partial_payment_active,
				),
				array(
					'field'      => array(
						'type'    => 'ALERT',
						'content' => __( 'Partial Payment is disabled. Please enable Partial Payment via <strong>WP Travel Engine > Settings > Extensions > Partial Payment.</strong>', 'wp-travel-engine' ),
					),
					'visibility' => $is_partial_payment_active && ! $is_partial_payment_enabled,
				),
				array(
					'label'       => __( 'Enable Partial Payment', 'wp-travel-engine' ),
					'description' => __( 'Toggle the switch to enable partial payment for this trip on checkout. You can manually enter payout amount/percentage in the field below.', 'wp-travel-engine' ),
					'field'       => array(
						'name' => 'partial_payment.enable',
						'type' => 'SWITCH',
					),
					'visibility'  => $is_partial_payment_active && $is_partial_payment_enabled,
				),
				array(
					'visibility'  => $is_partial_payment_active && $is_partial_payment_enabled,
					'condition'   => 'partial_payment.enable == true',
					'label'       => $is_percentage ? __( 'Partial Payment Percentage', 'wp-travel-engine' ) : __( 'Partial Payment Amount', 'wp-travel-engine' ),
					'description' => $is_percentage ? __( 'Please enter the desired partial percentage to be applied.', 'wp-travel-engine' ) : __( 'Please enter the desired partial amount to be applied.', 'wp-travel-engine' ),
					'field'       => array(
						'name'       => $is_percentage ? 'partial_payment.percentage' : 'partial_payment.amount',
						'type'       => 'NUMBER',
						'min'        => 0,
						'attributes' => array(
							'min'   => array(
								'value'   => 0,
								'message' => __( 'Minimum value must be greater than 0', 'wp-travel-engine' ),
							),
							'style' => array(
								'width'     => '100px',
								'textAlign' => 'center',
							),
						),
						'suffix'     => $is_percentage
							? array(
								'type'    => 'field',
								'field'   => array(
									'defaultValue' => __( '%', 'wp-travel-engine' ),
									'type'         => 'TEXT',
									'readOnly'     => true,
									'attributes'   => array(
										'style' => array( 'width' => '45px' ),
									),
								),
								'variant' => 'solid',
							) : false,
						'prefix'     => ! $is_percentage
						? array(
							'type'    => 'field',
							'field'   => array(
								'defaultValue' => $global_settings['currency_code'] ?? '',
								'type'         => 'TEXT',
								'readOnly'     => true,
								'attributes'   => array(
									'style' => array(
										'width'     => '60px',
										'textAlign' => 'center',
									),
								),
							),
							'variant' => 'solid',
						) : false,
					),
				),
				array(
					'visibility'  => $is_partial_payment_active && $is_partial_payment_enabled,
					'condition'   => 'partial_payment.enable == true',
					'label'       => __( 'Enable Full Payment', 'wp-travel-engine' ),
					'description' => __( 'Toggle the switch to enable full payment for this trip on checkout.', 'wp-travel-engine' ),
					'field'       => array(
						'name' => 'full_payment_enable',
						'type' => 'SWITCH',
					),
				),
			);
		}
		return $fields;
	}

	/**
	 * Returns file downloads fields.
	 *
	 * @param array  $fields
	 * @param string $extension
	 *
	 * @return array
	 */
	public function file_downloads_fields( array $fields, string $extension ): array {
		if ( 'file-downloads' === $extension ) {
			$global_files = (array) ( self::get_global_settings()['file_downloads']['wte_files_downloadable'] ?? array() );
			$files        = array(
				array(
					'label' => __( 'File Downloads', 'wp-travel-engine' ),
					'value' => '',
				),
			);
			foreach ( $global_files as $value ) {
				$files[] = array(
					'value'    => (int) ( $value['id'] ?? 0 ),
					'label'    => (string) ( $value['title'] ?? '' ),
					'dataType' => (string) get_post_mime_type( $value['id'] ?? '' ),
					'dataUrl'  => (string) ( $value['url'] ?? '' ),
				);
			}
			$fields = array(
				array(
					'field'      => array(
						'type'    => 'ALERT',
						'content' => __( 'Want to provide downloadable files such as brochures, guidebooks, offline maps, etc? File Downloads extension allows you to upload files in various formats that can be downloaded by travellers. <a href="https://wptravelengine.com/plugins/file-downloads/?utm_source=free_plugin&utm_medium=pro_addon&utm_campaign=upgrade_to_pro" target="_blank">Get File Downloads extension now</a>', 'wp-travel-engine' ),
						'status'  => 'upgrade',
					),
					'visibility' => ! wptravelengine_is_addon_active( 'file-downloads' ),
				),
				array(
					'field'      => array(
						'type'    => 'ALERT',
						'content' => __( 'You can add, edit and delete the global files via <strong>WP Travel Engine > Settings > Extensions > File Downloads.</strong> <a href="' . admin_url() . 'edit.php?post_type=booking&page=class-wp-travel-engine-admin.php#extension-file-downloads">Go To Settings</a>', 'wp-travel-engine' ),
						'status'  => 'notice',
					),
					'visibility' => wptravelengine_is_addon_active( 'file-downloads' ),
				),
				array(
					'field'      => array(
						'name'        => 'file_downloads',
						'type'        => 'FILE_DOWNLOADS',
						'globalFiles' => $files,
					),
					'visibility' => wptravelengine_is_addon_active( 'file-downloads' ),
				),
			);
		}
		return $fields;
	}

	/**
	 * Filters the extra services fields.
	 *
	 * @param array  $fields
	 * @param string $extension
	 *
	 * @return array
	 */
	public function extra_services_fields( array $fields, string $extension ): array {
		if ( 'extra-services' === $extension ) {
			$services = get_posts(
				array(
					'post_type'      => 'wte-services',
					'post_status'    => 'publish',
					'posts_per_page' => - 1,
					'orderby'        => 'post__in',
				)
			);

			$extra_services = array();
			foreach ( $services ?? array() as $service ) {
				if ( $service_data = get_post_meta( $service->ID, 'wte_services', true ) ) {
					$service_data['service_type'] = $service_data['service_type'] == 'custom' ? __( 'Advanced', 'wp-travel-engine' ) : __( 'Default', 'wp-travel-engine' );
					$extra_services[]             = array(
						'id'           => (int) $service->ID ?? 0,
						'label'        => (string) $service->post_title ?? '',
						'type'         => (string) $service_data['service_type'] ?? '',
						'options'      => (array) ( $service_data['options'] ?? array() ),
						'prices'       => isset( $service_data['service_cost'] ) && $service_data['service_cost'] > 0 && $service_data['service_type'] === 'Default' ? (array) floatval( $service_data['service_cost'] ) : (array) ( $service_data['prices'] ?? array() ),
						'descriptions' => (array) (
							isset( $service_data['service_type'] ) && $service_data['service_type'] === 'Advanced'
								? ( $service_data['descriptions'] ?? array() )
								: (
									! empty( $service_data['default_descriptions'] )
										? $service_data['default_descriptions']
										: apply_filters( 'the_content', get_the_content( '', false, $service->ID ) )
								)
						),
					);
				}
			}

			$fields = array(
				array(
					'field'      => array(
						'type'    => 'ALERT',
						'content' => __( '<p><strong>NOTE:</strong> Do you want to provide additional services such as supplementary room, hotel upgrade, airport pick and drop, etc? Extra Services extension allows you to create add-on services and sell more to your customer. <a href="https://wptravelengine.com/plugins/extra-services/?utm_source=free_plugin&utm_medium=pro_addon&utm_campaign=upgrade_to_pro" target="_blank">Get Extra Services extension now</a></p>', 'wp-travel-engine' ),
					),
					'visibility' => ! wptravelengine_is_addon_active( 'extra-services' ),
				),
				array(
					'field'      => array(
						'type'    => 'ALERT',
						'content' => __( '<strong>NOTE:</strong> You can add, edit and delete the global extra services via <strong>WP Travel Engine > Extra Services</strong>.', 'wp-travel-engine' ),
						'status'  => 'notice',
					),
					'visibility' => wptravelengine_is_addon_active( 'extra-services' ),
				),
				array(
					'label'       => __( 'Section Extra Service', 'wp-travel-engine' ),
					'description' => __( 'Choose and select the global Extra Service.', 'wp-travel-engine' ),
					'divider'     => true,
					'field'       => array(
						'type'    => 'EXTRA_SERVICES',
						'name'    => 'trip_extra_services',
						'options' => $extra_services,
					),
					'visibility'  => wptravelengine_is_addon_active( 'extra-services' ),
				),
			);
		}
		return $fields;
	}

	/**
	 * Filters the shortcodes fields.
	 *
	 * @param array  $fields
	 * @param string $extension
	 *
	 * @return array
	 */
	public function shortcodes_fields( array $fields, string $extension ): array {
		if ( 'shortcodes' === $extension ) {
			global $post;
			$fields = array(
				array(
					'field'      => array(
						'type'  => 'SHORTCODE',
						'title' => __( 'To display fixed starting dates in page/post use the following <strong>Shortcode.</strong>', 'wp-travel-engine' ),
						'code'  => "[WTE_Fixed_Starting_Dates id='{$post->ID}']",
					),
					'visibility' => wptravelengine_is_addon_active( 'fixed-starting-dates' ),
				),
				array(
					'divider'    => true,
					'field'      => array(
						'type'  => 'SHORTCODE',
						'title' => __( 'To display fixed starting dates in theme/template, please use below <strong>PHP Funtion.</strong>', 'wp-travel-engine' ),
						'code'  => "<?php echo do_shortcode('[WTE_Fixed_Starting_Dates id={$post->ID}]'); ?>",
					),
					'visibility' => wptravelengine_is_addon_active( 'fixed-starting-dates' ),
				),
				array(
					'visibility' => wptravelengine_is_addon_active( 'itinerary-downloader' ),
					'divider'    => true,
					'field'      => array(
						'type'  => 'SHORTCODE',
						'title' => __( 'To display Itinerary Downloader in current trip, please use below <strong>Shortcode.</strong>', 'wp-travel-engine' ),
						'code'  => '[wte_itinerary_downloader]',
					),
				),
				array(
					'field' => array(
						'type'  => 'SHORTCODE',
						'title' => __( 'To display Trip Info of this trip in posts/pages/tabs, please use following <strong>Shortcode.</strong>', 'wp-travel-engine' ),
						'code'  => "[Trip_Info_Shortcode id='{$post->ID}']",
					),
				),
				array(
					'divider' => true,
					'field'   => array(
						'type'  => 'SHORTCODE',
						'title' => __( 'To display Trip Info in templates, please use below <strong>PHP Funtion.</strong>', 'wp-travel-engine' ),
						'code'  => "<?php echo do_shortcode('[Trip_Info_Shortcode id={$post->ID}]'); ?>",
					),
				),
				array(
					'field' => array(
						'type'  => 'SHORTCODE',
						'title' => __( '<p>To display Video Gallery of this trip in posts/pages/tabs/templates, please use following <strong>Shortcode.</strong> <br/>Additional attributes are: type=\'popup/slider\' title=\'\' label=\'\', where type displays either a popup or slider layout, defaults popup layout.</p>', 'wp-travel-engine' ),
						'code'  => "[wte_video_gallery trip_id='{$post->ID}']",
					),
				),
				array(
					'divider' => true,
					'field'   => array(
						'type'  => 'SHORTCODE',
						'title' => __( 'To display Tour Map of this tour in posts/pages/tabs/widgets use the following <strong>Shortcode.</strong>', 'wp-travel-engine' ),
						'code'  => "[wte_trip_map id='{$post->ID}']",
					),
				),
				array(
					'divider' => false,
					'field'   => array(
						'type'  => 'SHORTCODE',
						'title' => __( 'To display Tour Map of this tour in posts/pages/tabs/widgets, please use below <strong>PHP Funtion.</strong>', 'wp-travel-engine' ),
						'code'  => "<?php echo do_shortcode('[wte_trip_map id={$post->ID}]'); ?>",
					),
				),
				array(
					'field'      => array(
						'type'  => 'SHORTCODE',
						'title' => __( 'To display downloadable file list in posts/pages/tabs and widget, please use following <strong>Shortcode.</strong>', 'wp-travel-engine' ),
						'code'  => "[trip_file_downloads trip_id='{$post->ID}']",
					),
					'visibility' => wptravelengine_is_addon_active( 'file-downloads' ),
				),
			);
		}
		return $fields;
	}

	/**
	 * Filters the itinerary downloader fields.
	 *
	 * @param array  $fields
	 * @param string $extension
	 *
	 * @return array
	 */
	public function itinerary_downloader_fields( array $fields, string $extension ): array {
		if ( 'itinerary-downloader' === $extension ) {
			$fields = array(
				array(
					'field' => array(
						'type'  => 'TITLE',
						'title' => __( 'Itinerary Downloader', 'wp-travel-engine' ),
					),
				),
				array(
					'visibility' => ! wptravelengine_is_addon_active( 'itinerary-downloader' ),
					'field'      => array(
						'type'    => 'ALERT',
						'content' => __( '<strong>NOTE:</strong> Want travellers to download the tour details in PDF format & read later? <a href="https://wptravelengine.com/plugins/itinerary-downloader/?utm_source=free_plugin&utm_medium=pro_addon&utm_campaign=upgrade_to_pro" target="_blank">Get Itinerary Downloader extension now</a>', 'wp-travel-engine' ),
						'status'  => 'upgrade',
					),
				),
				array(
					'visibility' => wptravelengine_is_addon_active( 'itinerary-downloader' ),
					'field'      => array(
						'type'    => 'ALERT',
						'content' => __( '<strong>NOTE:</strong> Want travellers to download the tour details in PDF format and read later? You can configure Itinerary Downloader via <b>WP Travel Engine &gt; Settings &gt; Extensions &gt; Itinerary Downloader', 'wp-travel-engine' ),
						'status'  => 'info',
					),
				),
			);
		}
		return $fields;
	}

	/**
	 * Returns global settings.
	 *
	 * @return array
	 */
	public static function get_global_settings(): array {
		if ( ! isset( self::$global_settings ) ) {
			self::$global_settings = wptravelengine_settings()->get();
		}
		return self::$global_settings;
	}
}
