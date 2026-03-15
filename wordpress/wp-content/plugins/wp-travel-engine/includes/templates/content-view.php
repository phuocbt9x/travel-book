<?php
/**
 * Template part for displaying posts
 *
 * This template can be overridden by copying it to yourtheme/wp-travel-engine/content-view.php
 *
 * @package Wp_Travel_Engine
 * @subpackage Wp_Travel_Engine/includes/templates
 * @since 6.0
 */
use WPTravelEngine\Core\Models\Post\Trip;
use WPTravelEngine\Core\Models\Settings\PluginSettings;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( isset( $details ) ) {
	extract( $details );
	unset( $details );
}

global $post;
$trip_instance            = new Trip( $post );
$plugin_settings_instance = new PluginSettings();
$plugin_settings          = $plugin_settings_instance->get();

$is_featured              = 'yes' === $trip_instance->get_meta( 'wp_travel_engine_featured_trip' );
$related_new_trip_listing = 'yes' === ( $plugin_settings['related_display_new_trip_listing'] ?? '' );
$new_trip_listing         = 'yes' === ( $plugin_settings['display_new_trip_listing'] ?? '' );
$wpte_trip_images         = $trip_instance->get_meta( 'wpte_gallery_id' );
$wp_travel_engine_setting = $trip_instance->get_meta( 'wp_travel_engine_setting' );

$related_trip = ( '' === $view_mode );

$trip_carousel            = $related_trip ? $show_related_trip_carousel : $show_trip_carousel;
$trip_listing             = $related_trip ? $related_new_trip_listing : $new_trip_listing;
$display_available_months = $related_trip ? $show_related_available_months : $show_available_months;
$display_map              = $related_trip ? $show_related_map : $show_map;
$display_wishlist         = $related_trip ? $show_related_wishlist : $show_wishlist;
$display_trip_tags        = $related_trip ? $show_related_trip_tags : $show_trip_tags;
$display_difficulty_tax   = $related_trip ? $show_related_difficulty_tax : $show_difficulty_tax;
$display_date_layout      = $related_trip ? $show_related_date_layout : $show_date_layout;
$featured_tag             = $related_trip ? $show_related_featured_tag : $show_featured_tag;

$new_date_layout = ( $trip_listing && $display_available_months ) || ( ! $trip_listing && ! $display_available_months );
$new_date_layout = ! isset( $plugin_settings['display_new_trip_listing'] ) || ( isset( $plugin_settings['display_new_trip_listing'] ) && $new_date_layout );

$set_duration_type = ( '' === ( $plugin_settings['set_duration_type'] ?? '' ) ) ? 'days' : $plugin_settings['set_duration_type'];

$display_new_trip_listing = $related_trip ? ( $plugin_settings['related_display_new_trip_listing'] ?? false ) : ( $plugin_settings['display_new_trip_listing'] ?? false );

$featured = ( ! isset( $display_new_trip_listing ) || 'no' === $display_new_trip_listing ) && $is_featured;

$trip_thumbnail = $trip_carousel && $wpte_trip_images['enable'] == 1 && count( $wpte_trip_images ) > 1;

$fsds = apply_filters( 'trip_card_fixed_departure_dates', $post->ID );

if ( $display_wishlist && is_array( $user_wishlists ?? '' ) ) {
	$active_class    = in_array( $post->ID, $user_wishlists ) ? ' active' : '';
	$title_attribute = in_array( $post->ID, $user_wishlists ) ? __( 'Already in wishlist', 'wp-travel-engine' ) : __( 'Add to wishlist', 'wp-travel-engine' );
}

if ( $trip_listing && ( in_array( $view_mode, array( '', 'list', 'grid' ) ) ) ) {
	wp_enqueue_script( 'wte-popper' );
	wp_enqueue_script( 'wte-tippyjs' );
}

?>
<div data-thumbnail="default" class="category-trips-single
<?php
echo $is_featured ? ' __featured-trip' : '';
echo $trip_listing ? ' wpte_new-layout' : '';
?>
" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
	<div class="category-trips-single-inner-wrap">
		<figure class="category-trip-fig">
			<?php
			if ( $trip_listing && $is_featured && $featured_tag ) :
				wte_get_template( 'layouts/featured-tag.php' );
			endif;

			if ( $featured ) :
				wte_get_template( 'layouts/featured-tag.php' );
			endif;

			// Trip thumbnail.
			if ( $trip_thumbnail ) {
				wte_get_template( 'single-trip/gallery.php', $related_trip ? $args : array() );
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

			// Group Discount.
			if ( $group_discount ) :
				wte_get_template( 'layouts/group-discount.php' );
			endif;

			// Show Map.
			if ( $display_map ) :
				wte_get_template( 'layouts/display-map.php', array( 'post_id' => $post->ID ) );
			endif;
			?>
		</figure>

		<div class="category-trip-content-wrap">
			<div class="category-trip-prc-title-wrap">
				<h2 class="category-trip-title" itemprop="name">
					<a itemprop="url" href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
				</h2>
				<?php
				if ( $display_wishlist ) {
					?>
					<span class="wishlist-title"><?php __( 'Add to wishlist', 'wp-travel-engine' ); ?></span>
					<a class="wishlist-toggle<?php echo esc_attr( $active_class ?? '' ); ?>" data-product="<?php echo esc_attr( $post->ID ); ?>" title="<?php echo esc_attr( $title_attribute ?? '' ); ?>">
						<svg width="20" height="19" viewBox="0 0 20 19" fill="none" xmlns="http://www.w3.org/2000/svg">
							<path d="M10 19L8.55 17.7C6.86667 16.1834 5.475 14.875 4.375 13.775C3.275 12.675 2.4 11.6874 1.75 10.812C1.1 9.93736 0.646 9.13336 0.388 8.40002C0.129333 7.66669 0 6.91669 0 6.15002C0 4.58336 0.525 3.27502 1.575 2.22502C2.625 1.17502 3.93333 0.650024 5.5 0.650024C6.36667 0.650024 7.19167 0.833358 7.975 1.20002C8.75833 1.56669 9.43333 2.08336 10 2.75002C10.5667 2.08336 11.2417 1.56669 12.025 1.20002C12.8083 0.833358 13.6333 0.650024 14.5 0.650024C16.0667 0.650024 17.375 1.17502 18.425 2.22502C19.475 3.27502 20 4.58336 20 6.15002C20 6.91669 19.871 7.66669 19.613 8.40002C19.3543 9.13336 18.9 9.93736 18.25 10.812C17.6 11.6874 16.725 12.675 15.625 13.775C14.525 14.875 13.1333 16.1834 11.45 17.7L10 19Z" fill="#C6C6C6" />
						</svg>
					</a>
					<?php
				}

				if ( ! empty( $j ) ) :
					?>
					<meta itemprop="position" content="<?php echo esc_attr( $j ); ?>"/>
				<?php endif; ?>
				<?php echo wte_get_the_trip_reviews(); ?>
			</div>
			<div class="category-trip-detail-wrap">
				<div class="category-trip-prc-wrap">
					<div class="category-trip-desti">
						<?php
							$tag_terms = get_the_terms( $post->ID, 'trip_tag' );
						if ( $display_trip_tags && ! empty( $tag_terms ) && ! is_wp_error( $tag_terms ) ) :
							?>
								<span class="category-trip-wtetags">
								<?php
								foreach ( $tag_terms as $term ) :
									$tags_description = term_description( $term->term_id );
									$tags_attribute   = $tags_description ? 'data-content="' . $tags_description . '"' : '';
									printf(
										'<span class="%s" %s><a rel="tag" target="_self" href="%s">%s</a></span>',
										esc_attr( ( '' != ( $tags_attribute ?? '' ) ) ? 'tippy-exist' : '' ),
										wp_kses_post( ( '' != ( $tags_attribute ?? '' ) ) ? $tags_attribute : '' ),
										esc_url( get_term_link( $term ) ),
										esc_html( $term->name )
									);
								endforeach;
								?>
								</span>
							<?php
							endif;
						if ( ! empty( $destination ) ) :
							wte_get_template( 'layouts/destination.php', compact( 'destination' ) );
							endif;
							wte_get_template( 'components/content-trip-card-duration.php', compact( 'trip_duration_unit', 'trip_duration', 'trip_duration_nights', 'set_duration_type' ) );
						if ( ! empty( $pax ) ) :
							?>
							<span class="category-trip-pax">
								<i><svg width="18" height="13" viewBox="0 0 18 13" fill="none"
										xmlns="http://www.w3.org/2000/svg"><path
											d="M9.225 6.665C9.62518 6.3186 9.94616 5.89016 10.1662 5.40877C10.3861 4.92737 10.5 4.40428 10.5 3.875C10.5 2.88044 10.1049 1.92661 9.40165 1.22335C8.69839 0.520088 7.74456 0.125 6.75 0.125C5.75544 0.125 4.80161 0.520088 4.09835 1.22335C3.39509 1.92661 3 2.88044 3 3.875C2.99999 4.40428 3.11385 4.92737 3.33384 5.40877C3.55384 5.89016 3.87482 6.3186 4.275 6.665C3.22511 7.14041 2.33435 7.90815 1.70924 8.87641C1.08412 9.84467 0.751104 10.9725 0.75 12.125C0.75 12.3239 0.829018 12.5147 0.96967 12.6553C1.11032 12.796 1.30109 12.875 1.5 12.875C1.69891 12.875 1.88968 12.796 2.03033 12.6553C2.17098 12.5147 2.25 12.3239 2.25 12.125C2.25 10.9315 2.72411 9.78693 3.56802 8.94302C4.41193 8.09911 5.55653 7.625 6.75 7.625C7.94347 7.625 9.08807 8.09911 9.93198 8.94302C10.7759 9.78693 11.25 10.9315 11.25 12.125C11.25 12.3239 11.329 12.5147 11.4697 12.6553C11.6103 12.796 11.8011 12.875 12 12.875C12.1989 12.875 12.3897 12.796 12.5303 12.6553C12.671 12.5147 12.75 12.3239 12.75 12.125C12.7489 10.9725 12.4159 9.84467 11.7908 8.87641C11.1657 7.90815 10.2749 7.14041 9.225 6.665ZM6.75 6.125C6.30499 6.125 5.86998 5.99304 5.49997 5.74581C5.12996 5.49857 4.84157 5.14717 4.67127 4.73604C4.50097 4.3249 4.45642 3.8725 4.54323 3.43605C4.63005 2.99959 4.84434 2.59868 5.15901 2.28401C5.47368 1.96934 5.87459 1.75505 6.31105 1.66823C6.7475 1.58142 7.1999 1.62597 7.61104 1.79627C8.02217 1.96657 8.37357 2.25496 8.62081 2.62497C8.86804 2.99498 9 3.42999 9 3.875C9 4.47174 8.76295 5.04403 8.34099 5.46599C7.91903 5.88795 7.34674 6.125 6.75 6.125ZM14.055 6.365C14.535 5.8245 14.8485 5.15679 14.9579 4.44225C15.0672 3.72772 14.9677 2.99681 14.6713 2.3375C14.375 1.67819 13.8943 1.1186 13.2874 0.726067C12.6804 0.333538 11.9729 0.124807 11.25 0.125C11.0511 0.125 10.8603 0.204018 10.7197 0.34467C10.579 0.485322 10.5 0.676088 10.5 0.875C10.5 1.07391 10.579 1.26468 10.7197 1.40533C10.8603 1.54598 11.0511 1.625 11.25 1.625C11.8467 1.625 12.419 1.86205 12.841 2.28401C13.2629 2.70597 13.5 3.27826 13.5 3.875C13.4989 4.26893 13.3945 4.65568 13.197 4.99657C12.9996 5.33745 12.7162 5.62054 12.375 5.8175C12.2638 5.88164 12.1709 5.97325 12.1053 6.08356C12.0396 6.19386 12.0034 6.31918 12 6.4475C11.9969 6.57482 12.0262 6.70085 12.0852 6.81369C12.1443 6.92654 12.2311 7.02249 12.3375 7.0925L12.63 7.2875L12.7275 7.34C13.6315 7.76879 14.3942 8.44699 14.9257 9.29474C15.4572 10.1425 15.7354 11.1245 15.7275 12.125C15.7275 12.3239 15.8065 12.5147 15.9472 12.6553C16.0878 12.796 16.2786 12.875 16.4775 12.875C16.6764 12.875 16.8672 12.796 17.0078 12.6553C17.1485 12.5147 17.2275 12.3239 17.2275 12.125C17.2336 10.9741 16.9454 9.84069 16.3901 8.83255C15.8348 7.8244 15.031 6.97499 14.055 6.365Z"
											fill="currentColor" /></svg></i>
								<span><?php printf( __( '%s People', 'wp-travel-engine' ), implode( '-', $pax ) ); ?></span>
							</span>
							<?php
							endif;
							$tax_terms = $trip_instance->get_trip_difficulty_term( $post->ID );
						if ( $display_difficulty_tax && ! empty( $tax_terms ) ) :
							foreach ( $tax_terms as $value ) {
								?>
									<span class="category-trip-difficulty">
									<?php
									if ( isset( $value['term_id'] ) ) {
										if ( 0 != ( $value['term_thumbnail'] ?? 0 ) ) {
											?>
													<i>
												<?php
													$value['term_thumbnail'] && print( \wp_get_attachment_image(
														$value['term_thumbnail'],
														array( '16', '16' ),
														false,
														array( 'itemprop' => 'image' )
													)
													);
												?>
													</i>
												<?php
										} else {
											?>
													<i>
														<svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
															<path d="M3.33333 13.3334V9.33335H5.33333V13.3334H3.33333ZM7.33333 13.3334V6.00002H9.33333V13.3334H7.33333ZM11.3333 13.3334V2.66669H13.3333V13.3334H11.3333Z" fill="currentColor" />
														</svg>
													</i>
											<?php
										}
									}
									?>
										<?php
										printf(
											'<span class="%s" %s><a rel="difficulty" target="_self" href="%s">%s</a></span>',
											esc_attr( $value['difficulty_span_class'] ),
											wp_kses_post( $value['difficulty_data_content'] ),
											esc_url( $value['difficulty_link'] ),
											esc_html( $value['difficulty_name'] ),
											wp_kses_post( $value['difficulty_levels'] )
										);
										?>
									</span>
									<?php
							}
							endif;
						if ( $trip_listing && $show_excerpt && $display_date_layout ) :
							?>
								<div class="category-trip-desc">
								<?php wptravelengine_the_trip_excerpt(); ?>
								</div>
						<?php endif; ?>
					</div>
					<?php if ( ! empty( $display_price ) || $display_date_layout ) : ?>
						<div class="category-trip-budget">
							<?php if ( $discount_percent ) : ?>
								<div class="category-disc-feat-wrap">
									<div class="category-trip-discount">
										<span class="discount-offer">
											<span><?php printf( __( '%1$s%% ', 'wp-travel-engine' ), (float) $discount_percent ); ?></span>
										<?php esc_html_e( 'Off', 'wp-travel-engine' ); ?></span>
									</div>
								</div>
								<?php
							endif;
							if ( ! empty( $display_price ) ) :
								?>
								<span class="price-holder">
									<span class="actual-price"><?php echo wte_esc_price( wte_get_formated_price( $display_price ) ); ?></span>
									<?php if ( $on_sale ) : ?>
									<span class="striked-price"><?php echo wte_esc_price( wte_get_formated_price( $trip_price ) ); ?></span>
									<?php endif; ?>
								</span>
								<?php
							endif;
							if ( $display_date_layout && $fsds && is_array( $fsds ) ) {
								wte_get_template(
									'layouts/date-layout.php',
									array(
										'fsds'       => $fsds,
										'is_fsds'    => true,
										'list_count' => (int) PluginSettings::make()->get( 'trip_dates.number', 3 ),
									)
								);
							}
							if ( $display_date_layout && ( empty( $fsds ) || is_numeric( $fsds ) ) ) {
								if ( '' !== $view_mode ) {
									wte_get_template( 'layouts/date-layout.php', array( 'is_fsds' => false ) );
								} else {
									?>
									<div class="category-trip-dates">
										<span class="trip-dates-title"><?php echo esc_html__( 'Next Departure', 'wp-travel-engine' ); ?></span>
										<?php
										foreach ( range( 0, 2 ) as $_day ) {
											?>
											<span class="category-trip-start-date">
												<span>
													<?php echo wte_get_new_formated_date( wp_date( 'Y-m-d', strtotime( " + {$_day} day" ) ) ); ?>
												</span>
											</span>
											<?php
										}
										?>
									</div>
									<?php
								}
							}
							?>
						</div>
					<?php endif; ?>
				</div>
				<?php
				if ( ( ! $trip_listing || ! $display_date_layout ) && $show_excerpt ) :
					?>
					<div class="category-trip-desc">
						<?php wptravelengine_the_trip_excerpt(); ?>
					</div>
					<?php
				endif;
				if ( 'list' !== $view_mode ) :
					?>
					<div class="wpte_trip-details-btn-wrap">
						<a href="<?php the_permalink(); ?>" class="button category-trip-viewmre-btn"><?php echo esc_html( apply_filters( 'wp_travel_engine_view_detail_txt', __( 'View Details', 'wp-travel-engine' ) ) ); ?></a>
				<?php endif; ?>
					</div>
			</div>
			<?php
			if ( $new_date_layout && false !== $fsds ) :
				echo '<div class="category-trip-aval-time">';
					$new_date_layout_atts = compact( 'fsds', 'view_mode', 'trip_listing', 'show_excerpt', 'trip_instance' );
				if ( false !== $fsds ) :
					if ( ( $fsds == get_the_ID() || empty( $fsds ) ) ) :
						$new_date_layout_atts += compact( 'display_available_months' );
						elseif ( is_array( $fsds ) && count( $fsds ) > 0 ) :
							$new_date_layout_atts += compact( 'dates_layout', 'related_trip', 'show_available_dates', 'display_available_months', 'show_related_available_dates' );
						endif;
					endif;
					wte_get_template( 'layouts/new-date-layout.php', $new_date_layout_atts );
				echo '</div>';
			endif;
				echo 'list' === $view_mode ? '' : '</div>';
			?>
	</div>
</div>
