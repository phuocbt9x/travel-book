<?php
/**
 * @var \WPTravelEngine\Core\Models\Post\Trip $trip_instance
 * @var array $engine_settings
 * @var array $user_wishlists
 * @var boolean $group_discount
 * @var boolean $related_query
 * @var boolean $show_related_featured_tag
 * @var boolean $show_related_trip_carousel
 * @var boolean $show_related_wishlist
 * @var boolean $show_related_map
 * @var boolean $show_wishlist
 * @var boolean $show_map
 * @var boolean $show_carousel
 * @var boolean $show_featured_tag
 */

global $post;

$is_featured       = $trip_instance->is_featured();
$discount_percent  = $trip_instance->get_discount_percent();
$show_featured_tag = $related_query ? $show_related_featured_tag : $show_featured_tag;
$show_map          = $related_query ? $show_related_map : $show_map;
$show_carousel     = $related_query ? $show_related_trip_carousel : $show_trip_carousel;
$show_wishlist     = $related_query ? $show_related_wishlist : $show_wishlist;

echo '<figure class="category-trip-fig">';

if ( $is_featured && $show_featured_tag ) : ?>
	<div class="category-feat-ribbon">
		<span class="category-feat-ribbon-txt"><?php echo esc_html__( 'Featured', 'wp-travel-engine' ); ?></span>
		<!-- <span class="cat-feat-shadow"></span> -->
	</div>
	<?php
endif;

if ( $discount_percent > 0 ) :
	?>
	<div class="category-disc-feat-wrap">
		<div class="category-trip-discount">
			<span class="discount-offer">
				<span><?php echo esc_html( wptravelengine_get_discount_label( $trip_instance->get_primary_package() ) ); ?></span>
			</span>
		</div>
	</div>
	<?php
endif;

if ( $show_carousel && $trip_instance->is_enabled_image_gallery() && count( $trip_instance->get_gallery_images() ) > 1 ) {
	wptravelengine_get_template( 'single-trip/main-gallery.php' );
} else {
	?>
	<a href="<?php the_permalink(); ?>">
		<?php
		$size = apply_filters( 'wp_travel_engine_archive_trip_feat_img_size', 'trip-single-size' );
		if ( has_post_thumbnail() ) :
			the_post_thumbnail( $size, array( 'loading' => 'lazy' ) );
		endif;
		?>
	</a>
	<?php
}

if ( $show_wishlist ) {
	$active_class    = '';
	$title_attribute = '';
	if ( is_array( $user_wishlists ) ) {
		$active_class    = in_array( get_the_ID(), $user_wishlists ) ? ' active' : '';
		$title_attribute = in_array( get_the_ID(), $user_wishlists ) ? __( 'Already in wishlist', 'wp-travel-engine' ) : __( 'Add to wishlist', 'wp-travel-engine' );
	}
	?>
	<div class="wishlist-toggle-wrap">
		<span class="wishlist-title"><?php __( 'Add to wishlist', 'wp-travel-engine' ); ?></span>
		<a class="wishlist-toggle<?php echo esc_attr( $active_class ); ?>" data-product="<?php echo esc_attr( get_the_ID() ); ?>" title="<?php echo esc_attr( $title_attribute ); ?>">
			<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
				<path d="M14.1961 12.8006C13.2909 13.7057 12.1409 14.7871 10.7437 16.046C10.7435 16.0461 10.7433 16.0463 10.7431 16.0465L9.99999 16.7127L9.25687 16.0465C9.25664 16.0463 9.25642 16.0461 9.2562 16.0459C7.85904 14.787 6.70905 13.7057 5.80393 12.8006C4.90204 11.8987 4.19779 11.1019 3.6829 10.4088C3.16746 9.71494 2.82999 9.1068 2.64509 8.5819C2.45557 8.04391 2.36166 7.49694 2.36166 6.93783C2.36166 5.80532 2.7337 4.89533 3.4706 4.15843C4.20749 3.42153 5.11748 3.04949 6.24999 3.04949C6.8706 3.04949 7.45749 3.17999 8.01785 3.44228C8.5793 3.70508 9.06198 4.07407 9.47044 4.55461L9.99999 5.17761L10.5295 4.55461C10.938 4.07407 11.4207 3.70508 11.9821 3.44228C12.5425 3.17999 13.1294 3.04949 13.75 3.04949C14.8825 3.04949 15.7925 3.42153 16.5294 4.15843C17.2663 4.89533 17.6383 5.80532 17.6383 6.93783C17.6383 7.49694 17.5444 8.04391 17.3549 8.5819C17.17 9.1068 16.8325 9.71494 16.3171 10.4088C15.8022 11.1019 15.0979 11.8987 14.1961 12.8006Z" stroke="currentColor" stroke-width="1.39"></path>
			</svg>
		</a>
	</div>
	<?php
}

if ( $group_discount ) {
	?>
	<div class="category-trip-group-avil">
		<span class="pop-trip-grpavil-icon">
			<svg xmlns="http://www.w3.org/2000/svg" width="17.492" height="14.72"
				viewBox="0 0 17.492 14.72">
				<g id="Group_898" data-name="Group 898" transform="translate(-452 -737)">
					<g id="Group_757" data-name="Group 757" transform="translate(12.114)">
						<g id="multiple-users-silhouette" transform="translate(439.886 737)">
							<path id="Path_23387" data-name="Path 23387" d="M10.555,8.875a3.178,3.178,0,0,1,1.479,2.361,2.564,2.564,0,1,0-1.479-2.361ZM8.875,14.127a2.565,2.565,0,1,0-2.566-2.565A2.565,2.565,0,0,0,8.875,14.127Zm1.088.175H7.786A3.289,3.289,0,0,0,4.5,17.587v2.662l.007.042.183.057a14.951,14.951,0,0,0,4.466.72,9.168,9.168,0,0,0,3.9-.732l.171-.087h.018V17.587A3.288,3.288,0,0,0,9.963,14.3Zm4.244-2.648h-2.16a3.162,3.162,0,0,1-.976,2.2,3.9,3.9,0,0,1,2.788,3.735v.82a8.839,8.839,0,0,0,3.443-.723l.171-.087h.018V14.938A3.288,3.288,0,0,0,14.207,11.654Zm-9.834-.175a2.548,2.548,0,0,0,1.364-.4A3.175,3.175,0,0,1,6.931,9.058c0-.048.007-.1.007-.144a2.565,2.565,0,1,0-2.565,2.565Zm2.3,2.377a3.163,3.163,0,0,1-.975-2.19c-.08-.006-.159-.012-.241-.012H3.285A3.288,3.288,0,0,0,0,14.938V17.6l.007.041L.19,17.7a15.4,15.4,0,0,0,3.7.7v-.8A3.9,3.9,0,0,1,6.677,13.856Z" transform="translate(0 -6.348)" fill="#fff" />
						</g>
					</g>
				</g>
			</svg>
		</span>
		<span class="pop-trip-grpavil-txt"><?php echo esc_html( apply_filters( 'wp_travel_engine_group_discount_available_text', __( 'Group discount Available', 'wp-travel-engine' ) ) ); ?></span>
	</div>
	<?php
}

if ( $show_map ) {
	?>
	<div class="trip-map-wrapper">
		<?php
		echo wp_kses(
			\WP_Travel_Engine_Custom_Shortcodes::wte_show_trip_map_shortcodes_callback(
				array(
					'id'   => $post->ID,
					'show' => 'iframe|image',
				)
			),
			array(
				'div'    => array(
					'class' => array(),
					'style' => array(),
				),
				'img'    => array(
					'class'   => array(),
					'src'     => array(),
					'style'   => array(),
					'loading' => array(),
				),
				'iframe' => array(
					'src'             => array(),
					'allowfullscreen' => array(),
					'loading'         => array(),
					'style'           => array(),
					'width'           => array(),
					'height'          => array(),
				),
			)
		);
		?>
	</div>
	<button data-thumbnail-toggler class="toggle-map">
		<svg width="16" height="17" viewBox="0 0 16 17" fill="none" xmlns="http://www.w3.org/2000/svg">
			<path d="M7.99996 8.83337C9.10453 8.83337 9.99996 7.93794 9.99996 6.83337C9.99996 5.7288 9.10453 4.83337 7.99996 4.83337C6.89539 4.83337 5.99996 5.7288 5.99996 6.83337C5.99996 7.93794 6.89539 8.83337 7.99996 8.83337Z" stroke="currentColor" stroke-width="1.33333" stroke-linecap="round" stroke-linejoin="round" />
			<path d="M7.99996 15.1667C9.33329 12.5 13.3333 10.7789 13.3333 7.16671C13.3333 4.22119 10.9455 1.83337 7.99996 1.83337C5.05444 1.83337 2.66663 4.22119 2.66663 7.16671C2.66663 10.7789 6.66663 12.5 7.99996 15.1667Z" stroke="currentColor" stroke-width="1.33333" stroke-linecap="round" stroke-linejoin="round" />
		</svg>
	</button>
	<?php
}

// Allows third party to add content after the map
do_action( 'wptravelengine_trip_archive_map', $post );

echo '</figure>';
