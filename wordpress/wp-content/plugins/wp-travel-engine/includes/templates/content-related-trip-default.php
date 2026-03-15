<?php

/**
 * Template part for displaying grid posts in single trip related section
 *
 * This template can be overridden by copying it to yourtheme/wp-travel-engine/content-related-trip-default.php.
 *
 * @package Wp_Travel_Engine
 * @subpackage Wp_Travel_Engine/includes/templates
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use WPTravelEngine\Core\Models\Post\Trip;

global $post;
$trip_data   = new Trip( get_the_ID() );
$is_featured = wte_is_trip_featured( get_the_ID() );

if ( class_exists( 'Wte_Trip_Review_Init' ) ) {
	$review_obj    = new Wte_Trip_Review_Init();
	$comment_datas = $review_obj->pull_comment_data( get_the_ID() );
	$star_data     = wptravelengine_reviews_get_trip_reviews( get_the_ID() );
}

$duration_label = wptravelengine_get_trip_duration_arr( $trip_data ?? $post, 'days' );

?>
<div class="category-trips-single wpte-layout-6">
	<div class="category-trips-single-inner-wrap">
		<figure class="category-trip-fig">
			<?php if ( $is_featured ) { ?>
				<div class="category-feat-ribbon">
					<span class="category-feat-ribbon-txt"><?php echo esc_html__( 'Featured', 'wp-travel-engine' ); ?></span>
					<!-- <span class="cat-feat-shadow"></span> -->
				</div>
			<?php } ?>
			<!-- Trip Image -->
			<a href="<?php the_permalink(); ?>">
				<?php
				$size = apply_filters( 'wp_travel_engine_archive_trip_feat_img_size', 'trip-single-size' );
				if ( has_post_thumbnail() ) :
					the_post_thumbnail( $size, array( 'loading' => 'lazy' ) );
				endif;
				?>
			</a>

			<!-- Discount ribbon -->
			<?php if ( $trip_data->has_sale() ) : ?>
				<span class="category-trip-discount-ribbon">
					<?php echo wptravelengine_get_discount_label( $trip_data->get_primary_package() ); ?>
				</span>
				<?php
			endif;
			?>

			<!-- Wishlist Toggle Button -->
			<div class="wishlist-toggle-wrap">
				<?php
					$active_class    = '';
					$title_attribute = '';
				if ( is_array( $user_wishlists ) ) {
					$active_class    = in_array( get_the_ID(), $user_wishlists ) ? ' active' : '';
					$title_attribute = in_array( get_the_ID(), $user_wishlists ) ? __( 'Already in wishlist', 'wp-travel-engine' ) : __( 'Add to wishlist', 'wp-travel-engine' );
				}
				?>
				<a class="wishlist-toggle<?php echo esc_attr( $active_class ); ?>" data-product="<?php echo esc_attr( get_the_ID() ); ?>" href="#" title="<?php echo esc_attr( $title_attribute ); ?>">
					<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
						<path d="M14.1961 12.8006C13.2909 13.7057 12.1409 14.7871 10.7437 16.046C10.7435 16.0461 10.7433 16.0463 10.7431 16.0465L9.99999 16.7127L9.25687 16.0465C9.25664 16.0463 9.25642 16.0461 9.2562 16.0459C7.85904 14.787 6.70905 13.7057 5.80393 12.8006C4.90204 11.8987 4.19779 11.1019 3.6829 10.4088C3.16746 9.71494 2.82999 9.1068 2.64509 8.5819C2.45557 8.04391 2.36166 7.49694 2.36166 6.93783C2.36166 5.80532 2.7337 4.89533 3.4706 4.15843C4.20749 3.42153 5.11748 3.04949 6.24999 3.04949C6.8706 3.04949 7.45749 3.17999 8.01785 3.44228C8.5793 3.70508 9.06198 4.07407 9.47044 4.55461L9.99999 5.17761L10.5295 4.55461C10.938 4.07407 11.4207 3.70508 11.9821 3.44228C12.5425 3.17999 13.1294 3.04949 13.75 3.04949C14.8825 3.04949 15.7925 3.42153 16.5294 4.15843C17.2663 4.89533 17.6383 5.80532 17.6383 6.93783C17.6383 7.49694 17.5444 8.04391 17.3549 8.5819C17.17 9.1068 16.8325 9.71494 16.3171 10.4088C15.8022 11.1019 15.0979 11.8987 14.1961 12.8006Z" stroke="currentColor" stroke-width="1.39" />
					</svg>
				</a>
			</div>

			<!-- Rating & Prices -->
			<div class="category-trip-image-overlay">
				<?php
				if ( ! empty( $comment_datas ) && function_exists( 'wptravelengine_reviews_star_markup' ) ) {
					?>
						<span class="wpte-trip-review-stars">
							<?php wptravelengine_reviews_star_markup( (float) $star_data['average'] ); ?>
							<span class="rating-star"><?php echo number_format( (float) $star_data['average'], 1 ); ?></span>
						</span>
					<?php
				}
				if ( ! empty( $display_price ) ) :
					?>
					<div class="wte-trip-price-wrapper">
						<?php if ( $on_sale ) : ?>
							<del class="wte-trip-regular-price">
								<?php echo wte_esc_price( wte_get_formated_price( $trip_price ) ); ?>
							</del>
						<?php endif; ?>
						<span class="wte-trip-sale-price">
							<?php echo wte_esc_price( wte_get_formated_price( $display_price ) ); ?>
						</span>
					</div>
					<?php
				endif;
				?>
			</div>
			<?php do_action( 'wptravelengine_trip_archive_map', $post ); ?>
		</figure>

		<!-- Trip Content -->
		<div class="category-trip-content-wrap">
			
			<?php
			$trip_activities = $trip_data->get_trip_terms( 'activities' );

			if ( ! empty( $trip_activities ) && ! is_wp_error( $trip_activities ) ) {
				?>
				<!-- Trip types -->
				<span class="category-trip-types">
					<?php echo wp_kses_post( $trip_activities ); ?>
				</span>
			<?php } ?>

			<!-- Trip Title -->
			<h3 class="category-trip-title">
				<a itemprop="url" href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
			</h3>

			<!-- Trip meta infos -->
			<div class="category-trip-meta-infos">
				<?php if ( ! empty( $duration_label ) ) : ?>
				<div class="category-trip-meta-info">
					<span class="category-trip-meta-info-icon">
						<svg width="24" height="25" viewBox="0 0 24 25" fill="none" xmlns="http://www.w3.org/2000/svg">
							<path d="M21 10.3018H3M16 2.30176V6.30176M8 2.30176V6.30176M7.8 22.3018H16.2C17.8802 22.3018 18.7202 22.3018 19.362 21.9748C19.9265 21.6872 20.3854 21.2282 20.673 20.6637C21 20.022 21 19.1819 21 17.5018V9.10176C21 7.4216 21 6.58152 20.673 5.93979C20.3854 5.3753 19.9265 4.91636 19.362 4.62874C18.7202 4.30176 17.8802 4.30176 16.2 4.30176H7.8C6.11984 4.30176 5.27976 4.30176 4.63803 4.62874C4.07354 4.91636 3.6146 5.3753 3.32698 5.93979C3 6.58152 3 7.4216 3 9.10176V17.5018C3 19.1819 3 20.022 3.32698 20.6637C3.6146 21.2282 4.07354 21.6872 4.63803 21.9748C5.27976 22.3018 6.11984 22.3018 7.8 22.3018Z" stroke="currentColor" stroke-width="1.39" stroke-linecap="round" stroke-linejoin="round" />
						</svg>
					</span>
					<div>
						<span class="category-trip-meta-info-label">
							<?php echo esc_html__( 'Duration', 'wp-travel-engine' ); ?>
						</span>
						<span class="category-trip-meta-info-value">
							<?php
							/* translators: %1$s: Trip duration number, %2$s: Trip duration unit (days/hours etc) */
							printf(
								'%1$s %2$s',
								esc_html( $trip_data->get_trip_duration() ),
								wptravelengine_get_label_by_slug( $trip_data->get_trip_duration_unit(), $trip_data->get_trip_duration() )
							);
							?>
						</span>
					</div>
				</div>
				<?php endif; ?>

				<?php if ( ! empty( $trip_data->get_trip_difficulty_term() ) ) { ?>
					<div class="category-trip-meta-info">
						<span class="category-trip-meta-info-icon">
							<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
								<path d="M21.5833 11.8021C21.5833 17.0948 17.2927 21.3854 12 21.3854C6.70726 21.3854 2.41666 17.0948 2.41666 11.8021M21.5833 11.8021C21.5833 6.50935 17.2927 2.21875 12 2.21875M21.5833 11.8021H19.1875M2.41666 11.8021C2.41666 6.50935 6.70726 2.21875 12 2.21875M2.41666 11.8021H4.81249M12 2.21875V4.61458M18.7835 5.09375L13.4374 10.3646M18.7835 18.5856L18.5881 18.3902C17.9251 17.7272 17.5936 17.3957 17.2068 17.1586C16.8638 16.9485 16.4898 16.7936 16.0987 16.6997C15.6575 16.5938 15.1887 16.5938 14.2511 16.5938L9.74882 16.5938C8.81123 16.5938 8.34243 16.5938 7.90127 16.6997C7.51013 16.7936 7.13621 16.9485 6.79323 17.1587C6.40639 17.3957 6.0749 17.7272 5.41192 18.3902L5.21655 18.5856M5.21655 5.09375L6.88065 6.75785M13.9167 11.8021C13.9167 12.8606 13.0585 13.7188 12 13.7188C10.9414 13.7188 10.0833 12.8606 10.0833 11.8021C10.0833 10.7435 10.9414 9.88542 12 9.88542C13.0585 9.88542 13.9167 10.7435 13.9167 11.8021Z" stroke="currentColor" stroke-width="1.39" stroke-linecap="round" stroke-linejoin="round" />
							</svg>
						</span>
						<div>
							<span class="category-trip-meta-info-label">
								<?php echo esc_html__( 'Difficulty', 'wp-travel-engine' ); ?>
							</span>
							<span class="category-trip-meta-info-value">
								<?php
								$get_difficulty = $trip_data->get_trip_difficulty_term()[0];
								echo esc_html( $get_difficulty['difficulty_name'] );
								?>
							</span>
						</div>
					</div>
					<?php
				}

				$activities_term = wp_get_post_terms( get_the_ID(), 'activities' );
				if ( ! empty( $activities_term ) && ! is_wp_error( $activities_term ) ) {
					?>
					<div class="category-trip-meta-info">
						<span class="category-trip-meta-info-icon">
							<svg width="24" height="25" viewBox="0 0 24 25" fill="none" xmlns="http://www.w3.org/2000/svg">
								<path d="M18 10.3018L15 11.8018L11 8.80176L10 14.3018L13.5 17.3018L14 21.8018M18 8.80176V21.8018M10 17.3018L8 21.8018M8.5 8.80176C7 9.80176 6 12.3018 6 12.3018L8 13.3018M12 6.80176C12.5304 6.80176 13.0391 6.59104 13.4142 6.21597C13.7893 5.8409 14 5.33219 14 4.80176C14 4.27132 13.7893 3.76262 13.4142 3.38754C13.0391 3.01247 12.5304 2.80176 12 2.80176C11.4696 2.80176 10.9609 3.01247 10.5858 3.38754C10.2107 3.76262 10 4.27132 10 4.80176C10 5.33219 10.2107 5.8409 10.5858 6.21597C10.9609 6.59104 11.4696 6.80176 12 6.80176Z" stroke="currentColor" stroke-width="1.39" stroke-linecap="round" stroke-linejoin="round" />
							</svg>
						</span>
						<div>
							<span class="category-trip-meta-info-label">
								<?php echo esc_html__( 'Activity', 'wp-travel-engine' ); ?>
							</span>
							<span class="category-trip-meta-info-value">
								<?php
								/* translators: %d: Number of activities */
								printf(
									_n( '%d Activity', '%d Activities', count( $activities_term ), 'wp-travel-engine' ),
									esc_html( count( $activities_term ) )
								);
								?>
							</span>
						</div>
					</div>
				<?php } ?>
			</div>
		</div>
	</div>
</div>
