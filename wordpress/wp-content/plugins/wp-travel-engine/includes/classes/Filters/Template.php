<?php

namespace WPTravelEngine\Filters;

use WP_Block_Template;
use WPTravelEngine\Assets;
use Elementor\Plugin;
use WPTravelEngine\Modules\TripSearch;

class Template {

	/**
	 * Initializes hooks for template inclusion and excerpt modification.
	 */
	public function hooks() {
		add_filter( 'template_include', array( $this, 'include_trip_template' ) );

		add_filter( 'template_include', array( $this, 'filter_template_include' ), 11 );

		add_filter( 'wptravelengine_hide_traveler_emergency_form', array( $this, 'modify_traveller_emergency_form' ) );

		add_filter( 'wptravelengine_hide_traveler_form', array( $this, 'modify_traveller_form' ) );

		add_filter( 'wptravelengine_hide_emergency_form', array( $this, 'modify_emergency_form' ) );

		$fse_templates = new FSE_Template();
		$fse_templates->hooks();
	}

	/**
	 * Includes the appropriate template based on the post type and context.
	 *
	 * @param string $template_path The current template path.
	 *
	 * @return string The modified template path.
	 */
	public function include_trip_template( string $template_path ): string {

		$optimized_loading = wptravelengine_toggled( wptravelengine_settings()->get( 'enable_optimize_loading', false ) );
		\WP_Travel_Engine_Template_Hooks::get_instance();

		$all_taxonomies = get_object_taxonomies( 'trip', 'names' );

		if ( current_theme_supports( 'wptravelengine-templates' ) || ( wp_is_block_theme() && 'yes' == wptravelengine_settings()->get( 'enable_fse_template', 'no' ) ) ) {
			if ( is_single() ) {
				Assets::instance()
						->enqueue_script( 'trip-booking-modal' )
						->dequeue_script( 'wp-travel-engine' )
						->dequeue_style( 'wp-travel-engine' )
						->enqueue_script( 'wp-api-request' )
						->enqueue_script( 'single-trip' )
						->enqueue_style( 'single-trip' )
						->enqueue_style( 'style-trip-booking-modal' );
			}

			if ( is_post_type_archive( WP_TRAVEL_ENGINE_POST_TYPE ) || is_tax( $all_taxonomies ) ) {
				TripSearch::enqueue_assets();
			}

			return $template_path;
		}

		if ( is_singular( WP_TRAVEL_ENGINE_POST_TYPE ) ) {
			if ( ! $optimized_loading ) {
				Assets::instance()
					->enqueue_script( 'wte-fpickr-lib' )
					->enqueue_script( 'wp-api-request' )
					->enqueue_script( 'wte-redux' )
					->enqueue_style( 'wte-fpickr' );
			}
			Assets::instance()
					->dequeue_script( 'comment-reply' )
					->dequeue_script( 'wp-travel-engine' )
					->dequeue_script( 'wte-extra-services' )
					->dequeue_script( 'wp-travel-engine-group-discount' )
					->dequeue_style( 'wp-travel-engine' )
					->dequeue_style( 'wte-extra-services' )
					->dequeue_style( 'wp-travel-engine-group-discount' )
					->enqueue_script( 'wp-api-request' )
					->enqueue_script( 'single-trip' )
					->enqueue_style( 'single-trip' );
			$template_path = wte_locate_template( 'single-trip.php' );
		} elseif ( is_post_type_archive( WP_TRAVEL_ENGINE_POST_TYPE ) ) {
			TripSearch::enqueue_assets();
			$template_path = wte_locate_template( 'archive-trip.php' );
		} else {
			foreach ( $all_taxonomies as $tax ) {
				if ( is_tax( $tax ) ) {
					TripSearch::enqueue_styles();
					$template_path = wte_locate_template( 'taxonomy-' . $tax . '.php' );
					if ( ! file_exists( $template_path ) ) {
						$template_path = wte_locate_template( 'archive-trip.php' );
					}
					break;
				}
			}
		}

		if ( wp_travel_engine_is_account_page() ) {
			Assets::instance()
				->dequeue_script( 'wp-travel-engine' )
				->dequeue_style( 'wp-travel-engine' )
				->enqueue_script( 'my-account' )
				->enqueue_style( 'my-account' );
		}

		$this->enqueue_elementor_assets();

		return $template_path;
	}

	/**
	 * Enqueue Elementor assets if the current page is built with Elementor.
	 */
	public function enqueue_elementor_assets() {
		global $post;
		if ( $post && class_exists( '\Elementor\Plugin' ) && defined( 'WPTRAVELENGINEEB_VERSION' ) ) {
			$elementor_page = \Elementor\Plugin::$instance->documents->get( $post->ID );
			if ( $elementor_page ) {
				$settings = $elementor_page->get_properties() ?? false;
				if ( $settings && $elementor_page->is_built_with_elementor() && isset( $settings['support_wp_page_templates'] ) ) {
					Assets::instance()->enqueue_script( 'wp-travel-engine' )->enqueue_style( 'wp-travel-engine' );
					wp_enqueue_style( 'wte-blocks-index' );
				}
			}
		}
	}


	/**
	 * Filter the template to include the custom template file.
	 *
	 * @param string $template The current template file.
	 *
	 * @return string The modified template file.
	 */
	public function filter_template_include( string $template ): string {
		global $wptravelengine_template_args, $wte_cart;

		if ( ( $template_slug = get_page_template_slug() ) === 'template-checkout.php' ) {
			$custom_template = wte_locate_template( $template_slug );
			if ( file_exists( $custom_template ) ) {
				Assets::instance()
						->enqueue_script( 'trip-checkout' )
						->enqueue_style( 'trip-checkout' )
						->enqueue_script( 'parsley' )
						->dequeue_script( 'wp-travel-engine' )
						->dequeue_style( 'wp-travel-engine' );

				$checkout_page   = new \WPTravelEngine\Pages\Checkout( $wte_cart );
				$tour_details    = $checkout_page->get_tour_details();
				$cart_line_items = $checkout_page->get_cart_line_items();

				$wptravelengine_template_args = array_merge(
					$wptravelengine_template_args,
					wptravelengine_get_checkout_template_args(
						array(
							'tour_details'    => $tour_details,
							'cart_line_items' => $cart_line_items,
							'has_cart_items'  => count( $wte_cart->getItems() ) > 0,
						)
					)
				);

				return $custom_template;
			}
		}

		return $template;
	}

	/**
	 * Hide traveller emergency information.
	 *
	 * @return boolean true if traveller emergency information should be hidden, false otherwise.
	 */
	public function modify_traveller_emergency_form() {
		$settings          = get_option( 'wp_travel_engine_settings', array() );
		$checkout_template = $settings['checkout_page_template'] ?? '1.0';

		if ( $checkout_template == '1.0' ) {
			return wptravelengine_replace( $settings['travelers_information'], 'no', false, true );
		}

		if ( $checkout_template == '2.0' ) {
			return ! ( wptravelengine_toggled( $settings['display_travellers_info'] ) && wptravelengine_replace( $settings['traveller_emergency_details_form'], 'after_checkout', true, false ) );
		}

		return false;
	}

	/**
	 * Modify traveller information.
	 *
	 * @return string 'yes' if traveller information should be hidden, false otherwise.
	 */
	public function modify_traveller_form() {
		$wptravelengine_settings          = get_option( 'wp_travel_engine_settings', array() );
		$checkout_page_template           = $wptravelengine_settings['checkout_page_template'] ?? '1.0';
		$is_enabled_travellers_info       = $wptravelengine_settings['display_travellers_info'] ?? 'no';
		$traveller_emergency_details_form = $wptravelengine_settings['traveller_emergency_details_form'] ?? 'after_checkout';

		if ( $checkout_page_template == '2.0' && $traveller_emergency_details_form == 'on_checkout' && $is_enabled_travellers_info == 'yes' ) {
			return 'yes';
		}

		return 'no';
	}

	/**
	 * Modify emergency information.
	 *
	 * @return boolean true if traveller emergency information should be hidden, false otherwise.
	 */
	public function modify_emergency_form() {
		$settings          = get_option( 'wp_travel_engine_settings', array() );
		$checkout_template = $settings['checkout_page_template'] ?? '1.0';

		// Handle legacy checkout template (1.0).
		if ( $checkout_template == '1.0' ) {
			return wptravelengine_replace( $settings['emergency'], '1', true, false );
		}

		// Handle new checkout template (2.0).
		if ( $checkout_template == '2.0' ) {
			return ! ( wptravelengine_toggled( $settings['display_emergency_contact'] ) && wptravelengine_toggled( $settings['display_travellers_info'] ) && in_array( $settings['traveller_emergency_details_form'] ?? 'after_checkout', array( 'on_checkout', 'after_checkout' ) ) );
		}

		return false;
	}
}
