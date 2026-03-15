<?php
/**
 * Main SEO class.
 *
 * @package WPTravelEngine\Core
 * @since 1119-schema-issue
 */

namespace WPTravelEngine\Core;

use WPTravelEngine\Core\Models\Post\Trip;

class SEO {

	public function __construct() {

		add_action( 'display_wte_rich_snippet', array( $this, 'wp_travel_engine_json_ld' ) );
	}

	public static function wp_travel_engine_json_ld( $post_id = false ) {

		/**
		 * Escaped schema values are stored in $get_schemas variable.
		*/
		$get_schemas = self::wp_travel_engine_schema_values( $post_id );

		foreach ( $get_schemas as $schema ) {
			if ( ! empty( $schema ) ) {
				echo '<script type="application/ld+json">';
				echo wp_json_encode( $schema ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped.
				echo '</script>';
			}
		}
	}

	/**
	 * Get the schema values for the trip.
	 *
	 * @param int $post_id
	 * @return array
	 */
	public static function wp_travel_engine_schema_values( $post_id = false ) {
		// Get basic post data.
		$post_data = self::get_post_data( $post_id );
		$post_id   = $post_data['post_id'];

		// Get trip data.
		$trip  = self::get_trip_basic_data( $post_id, $post_data['post'] );
		$costs = self::get_trip_costs( $trip['settings'], $trip['trip_data'] );

		// Build schemas.
		$itinerary      = self::build_itinerary_schema( $trip['settings'] );
		$faq_schema     = self::build_faq_schema( $trip['settings'], $post_id );
		$trip_schema    = self::build_trip_schema( $post_id, $trip, $costs, $post_data['content'], $itinerary );
		$product_schema = self::build_product_schema( $post_id, $trip, $post_data['content'] );

		// Return final schema array.
		return apply_filters(
			'wp_travel_engine_single_trip_all_schema',
			array(
				'faq'     => $faq_schema,
				'trip'    => $trip_schema,
				'product' => $product_schema,
			),
			$post_id,
			$trip['trip_data'],
			$trip['settings']
		);
	}

	/**
	 * Get information about the post.
	 *
	 * @param int $post_id
	 * @return array
	 */
	private static function get_post_data( $post_id ) {
		if ( empty( $post_id ) ) {
			global $post;
			$post_id = $post->ID;
		}
		$post = get_post( $post_id );
		return array(
			'post'    => $post,
			'content' => strip_tags( strip_shortcodes( $post->post_content ) ),
			'post_id' => $post_id,
		);
	}

	/**
	 * Get basic data about the trip.
	 *
	 * @param int    $post_id
	 * @param object $post
	 * @return array
	 */
	private static function get_trip_basic_data( $post_id, $post ) {
		$obj       = \wte_functions(); // Backward compatibility.
		$trip_data = new Trip( $post_id );

		$price = $trip_data->has_sale() ? $trip_data->get_sale_price() : $trip_data->get_price();

		return array(
			'settings'  => get_post_meta( $post_id, 'wp_travel_engine_setting', true ),
			'thumbnail' => has_post_thumbnail( $post_id ) ? get_the_post_thumbnail_url( $post_id, 'wp_travel_engine_single_trip_feat_img_size' ) : '',
			'blog'      => get_bloginfo( 'name' ),
			'url'       => get_bloginfo( 'url' ),
			'trip_url'  => get_permalink( $post_id ),
			'currency'  => $obj->trip_currency_code( $post ),
			'price'     => $obj->trip_price( $post_id ) ?: $price,
			'trip_data' => $trip_data,
		);
	}

	/**
	 * Get the costs of the trip.
	 *
	 * @param array  $settings
	 * @param object $trip_data
	 * @return array
	 */
	private static function get_trip_costs( $settings, $trip_data ) {
		$prev_cost = $trip_data->get_price();
		$cost      = $trip_data->has_sale() ? $trip_data->get_sale_price() : $prev_cost;

		return array(
			'cost'      => $cost,
			'prev_cost' => $prev_cost,
		);
	}

	/**
	 * Build the itinerary schema.
	 *
	 * @param array $settings
	 * @return array
	 */
	private static function build_itinerary_schema( $settings ) {
		if ( empty( $settings['itinerary']['itinerary_title'] ) ) {
			return array();
		}

		$items         = array();
		$arr_keys      = array_keys( $settings['itinerary']['itinerary_title'] );
		$max_itinerary = max( $arr_keys );

		foreach ( $arr_keys as $value ) {
			if ( ! array_key_exists( $value, $settings['itinerary']['itinerary_title'] ) ) {
				continue;
			}

			$title             = $settings['itinerary']['itinerary_title'][ $value ] ?? '';
			$content_itinerary = $settings['itinerary']['itinerary_content_inner'][ $value ] ??
								$settings['itinerary']['itinerary_content'][ $value ] ?? '';

			$content_itinerary = preg_replace( '/<p\b[^>]*>(.*?)<\/p>/i', '', strip_tags( $content_itinerary ) );

			$items[] = array(
				'@type'    => 'ListItem',
				'position' => esc_attr( $value ),
				'item'     => array(
					'@type'       => 'TouristAttraction',
					'name'        => esc_attr( $title ),
					'description' => esc_attr( $content_itinerary ),
				),
			);
		}

		return array(
			'@type'           => 'ItemList',
			'numberOfItems'   => esc_attr( $max_itinerary ),
			'itemListElement' => $items,
		);
	}

	/**
	 * Build the FAQ schema.
	 *
	 * @param array $settings
	 * @param int   $post_id
	 * @return array|null
	 */
	private static function build_faq_schema( $settings, $post_id ) {
		if ( empty( $settings['faq']['faq_title'] ) || ! is_array( $settings['faq']['faq_title'] ) ) {
			return null;
		}

		$faqs = array();
		foreach ( array_keys( $settings['faq']['faq_title'] ) as $value ) {
			$question = $settings['faq']['faq_title'][ $value ] ?? '';
			$answer   = $settings['faq']['faq_content'][ $value ] ?? '';

			$question = preg_replace( '/<p\b[^>]*>(.*?)<\/p>/i', '', strip_tags( $question ) );
			$answer   = preg_replace( '/<p\b[^>]*>(.*?)<\/p>/i', '', strip_tags( $answer ) );

			$faqs[] = array(
				'@type'          => 'Question',
				'name'           => esc_attr( $question ),
				'acceptedAnswer' => array(
					'@type' => 'Answer',
					'text'  => esc_attr( $answer ),
				),
			);
		}

		return apply_filters(
			'wp_travel_engine_faq_schema_array',
			array(
				'@context'   => 'https://schema.org',
				'@type'      => 'FAQPage',
				'mainEntity' => $faqs,
			),
			$post_id,
			$settings
		);
	}

	/**
	 * Build the trip schema.
	 *
	 * @param int    $post_id
	 * @param array  $trip
	 * @param array  $costs
	 * @param string $content
	 * @param array  $itinerary
	 * @return array
	 */
	private static function build_trip_schema( $post_id, $trip, $costs, $content, $itinerary ) {
		return apply_filters(
			'wp_travel_engine_schema_array',
			array(
				'@context'    => 'https://schema.org',
				'@type'       => 'Trip',
				'name'        => get_the_title( $post_id ),
				'description' => esc_html( $content ),
				'image'       => esc_url( $trip['thumbnail'] ),
				'url'         => esc_url( $trip['trip_url'] ),
				'itinerary'   => $itinerary,
				'provider'    => array(
					'@type' => 'Organization',
					'name'  => esc_html( $trip['blog'] ),
					'url'   => esc_url( $trip['url'] ),
				),
				'offers'      => array(
					'@type'     => 'AggregateOffer',
					'highPrice' => esc_html( $costs['prev_cost'] ),
					'lowPrice'  => esc_html( $costs['cost'] ),
					'offers'    => array(
						'@type'           => 'Offer',
						'name'            => esc_html( get_the_title( $post_id ) ),
						'availability'    => 'https://schema.org/InStock',
						'price'           => esc_html( $trip['price'] ),
						'priceCurrency'   => esc_html( $trip['currency'] ) ?: 'USD',
						'priceValidUntil' => '2030-12-31',
						'url'             => esc_url( $trip['trip_url'] ),
					),
				),
			),
			$post_id,
			$trip['settings']
		);
	}

	/**
	 * Build the product schema.
	 *
	 * @param int    $post_id
	 * @param array  $trip
	 * @param string $content
	 * @return array
	 */
	private static function build_product_schema( $post_id, $trip, $content ) {
		return apply_filters(
			'wp_travel_engine_single_trip_product_schema',
			array(
				'@context'    => 'https://schema.org',
				'@type'       => 'Product',
				'name'        => esc_html( get_the_title( $post_id ) ),
				'description' => esc_html( $content ),
				'image'       => esc_url( $trip['thumbnail'] ),
				'url'         => esc_url( $trip['trip_url'] ),
				'brand'       => array(
					'@type' => 'Brand',
					'name'  => esc_html( get_the_title( $post_id ) ),
				),
				'offers'      => array(
					'@type'           => 'Offer',
					'url'             => esc_url( $trip['trip_url'] ),
					'price'           => esc_html( $trip['price'] ),
					'priceCurrency'   => esc_html( $trip['currency'] ) ?: 'USD',
					'availability'    => 'https://schema.org/InStock',
					'priceValidUntil' => '2030-12-31',
				),
			),
			$post_id,
			$trip['trip_data'],
			$trip['settings']
		);
	}
}
