<?php
/**
 * Trip Save and Continue Controller.
 *
 * @package WPTravelEngine/Core/Controllers
 * @since 6.0.0
 */

namespace WPTravelEngine\Core\Controllers\Ajax;

use WPTravelEngine\Abstracts\AjaxController;
use WPTravelEngine\Core\Models\Post\Trip;

/**
 * Saves the trip.
 */
class TripSaveAndContinue extends AjaxController {

	const NONCE_KEY    = 'nonce';
	const NONCE_ACTION = 'wpte_tab_trip_save_and_continue';
	const ACTION       = 'wpte_tab_trip_save_and_continue';
	const ALLOW_NOPRIV = false;
	/**
	 * Process Request.
	 */
	public function process_request() {
		$this->trip_save_and_continue( $this->sanitize_post_data( $this->request->get_body_params() ) );
	}

	/**
	 * Save and continue button callback.
	 *
	 * @param array $post_data Post data.
	 * @return void
	 */
	public function trip_save_and_continue( $post_data ) {
		if ( empty( $post_data['post_id'] ) ) {
			wp_send_json_error( array( 'message' => esc_html__( 'Post ID not found', 'wp-travel-engine' ) ) );
		}
		if ( 'wpte_tab_trip_save_and_continue' === ( $post_data['action'] ?? null ) ) {
			$post_model_instance            = new Trip( $post_data['post_id'] );
			$obj                            = \wptravelengine_functions();
			$wp_travel_engine_setting_saved = $post_model_instance->get_meta( 'wp_travel_engine_setting' );
			if ( empty( $wp_travel_engine_setting_saved ) ) {
				$wp_travel_engine_setting_saved = array();
			}
			$wp_travel_engine_setting_saved = $obj->recursive_html_entity_decode( $wp_travel_engine_setting_saved );

			$meta_to_save = $post_data['wp_travel_engine_setting'] ?? array();

			// Merge data.
			$metadata_merged_with_saved = array_merge( $wp_travel_engine_setting_saved, $meta_to_save );
			$checkboxes_array           = array(
				'general' => array(
					'trip_cutoff_enable',
					'min_max_age_enable',
					'minmax_pax_enable',
				),
				'pricing' => array(
					'sale',
				),
				'gallery' => array(
					'enable_video_gallery',
				),
			);
			$trip_meta_checkboxes       = apply_filters( 'wp_travel_engine_trip_meta_checkboxes', $checkboxes_array );
			if ( isset( $post_data['tab'] ) ) {
				$active_tab = $post_data['tab'];
				if ( isset( $trip_meta_checkboxes[ $active_tab ] ) ) {
					foreach ( $trip_meta_checkboxes[ $active_tab ] as $checkbox ) {
						if ( isset( $metadata_merged_with_saved[ $checkbox ] ) && ! isset( $meta_to_save[ $checkbox ] ) ) {
							unset( $metadata_merged_with_saved[ $checkbox ] );
						}
					}
				}
			}
			$arrays_in_meta = array(
				'itinerary',
				'faq',
				'trip_facts',
				'trip_highlights',
			);

			$arrays_in_meta = apply_filters( 'wpte_trip_meta_array_key_bases', $arrays_in_meta );

			foreach ( $arrays_in_meta as $arr_key ) {
				if ( isset( $meta_to_save[ $arr_key ] ) && ! is_array( $meta_to_save[ $arr_key ] ) ) {
					unset( $metadata_merged_with_saved[ $arr_key ] );
				}
			}

			$post_model_instance->set_meta( 'wp_travel_engine_setting', $metadata_merged_with_saved )->save();

			/**
			 * Hook for Save& Continue support on addons.
			 */
			do_action( 'wpte_save_and_continue_additional_meta_data', $post_data['post_id'], $post_data );

			$meta_data_meta_keys = array(
				'trip_price'      => 'wp_travel_engine_setting_trip_price',
				'trip_prev_price' => 'wp_travel_engine_setting_trip_prev_price',
				'trip_duration'   => 'wp_travel_engine_setting_trip_duration',
			);
			foreach ( $meta_data_meta_keys as $data_key => $meta_key ) {
				if ( isset( $metadata_merged_with_saved[ $data_key ] ) ) {
					$post_model_instance->set_meta( $meta_key, $metadata_merged_with_saved[ $data_key ] );
				}
			}

			$post_data_meta_keys = array(
				'wpte_gallery_id'               => 'wpte_gallery_id',
				'enable_video_gallery'          => 'wpte_vid_gallery',
				'wp_travel_engine_trip_min_age' => 'wp_travel_engine_trip_min_age',
				'wp_travel_engine_trip_max_age' => 'wp_travel_engine_trip_max_age',
			);
			foreach ( $post_data_meta_keys as $data_key => $meta_key ) {
				if ( isset( $post_data[ $data_key ] ) || ( 'enable_video_gallery' === $data_key && isset( $post_data['wp_travel_engine_setting'][ $data_key ] ) ) ) {
					$post_model_instance->set_meta( $meta_key, $post_data[ $data_key ] );
				}
			}

			$post_model_instance->save();

			wp_send_json_success( array( 'message' => 'Trip settings saved successfully.' ) );
		}
	}

	/**
	 * Sanitize_post_data.
	 *
	 * @param array $posted_data Post data.
	 * @return array
	 */
	public function sanitize_post_data( $posted_data ) {

		$special_fields = array(
			'type'       => 'array',
			'properties' => array(
				'wp_travel_engine_setting' => array(
					'type'       => 'array',
					'properties' => array(
						'tab_content' => array(
							'type'  => 'array',
							'items' => array(
								'type'              => 'string',
								'sanitize_callback' => 'wp_kses_post',
							),
						),
						'itinerary'   => array(
							'type'       => 'array',
							'properties' => array(
								'itinerary_content' => array(
									'type'  => 'array',
									'items' => array(
										'type' => 'string',
										'sanitize_callback' => 'wp_kses_post',
									),
								),
							),
						),
						'cost'        => array(
							'type'       => 'array',
							'properties' => array(
								'cost_includes' => array(
									'type'              => 'string',
									'sanitize_callback' => 'sanitize_textarea_field',
								),
								'cost_excludes' => array(
									'type'              => 'string',
									'sanitize_callback' => 'sanitize_textarea_field',
								),
							),
						),
						'map'         => array(
							'type'       => 'array',
							'properties' => array(
								'iframe' => array(
									'type'              => 'string',
									'sanitize_callback' => function ( $value ) {
										return wp_kses( $value, 'wte_iframe' );
									},
								),
							),
						),
						'faq'         => array(
							'type'       => 'array',
							'properties' => array(
								'faq_content' => array(
									'type'  => 'array',
									'items' => array(
										'type' => 'string',
										'sanitize_callback' => 'wp_kses_post',
									),
								),
							),
						),
					),
				),
				'packages_descriptions'    => array(
					'type'  => 'array',
					'items' => array(
						'type'              => 'string',
						'sanitize_callback' => 'sanitize_textarea_field',
					),
				),

			),
		);

		$sanitized_data = wte_input_clean( $posted_data, $special_fields );

		// Handle Trip facts differently.
		$_trip_facts = array();
		if ( isset( $posted_data['wp_travel_engine_setting']['trip_facts'] ) && is_array( $posted_data['wp_travel_engine_setting']['trip_facts'] ) ) {
			$trip_facts = wp_unslash( $posted_data['wp_travel_engine_setting']['trip_facts'] );
			foreach ( $trip_facts as $key => $_trip_fact ) {
				if ( in_array( $key, array( 'field_id', 'field_type' ), true ) ) {
					$_trip_facts[ $key ] = wte_input_clean( $_trip_fact );
				} else {
					array_walk_recursive(
						$_trip_fact,
						function ( $value, $k ) use ( $key, &$_trip_facts ) {
							$_trip_facts[ $key ][ $k ] = wp_kses_post( $value );
						}
					);
				}
			}
			$sanitized_data['wp_travel_engine_setting']['trip_facts'] = $_trip_facts;
		}

		return $sanitized_data;
	}
}
