<?php
/**
 * The template for displaying trips according to trip_types .
 *
 * @package Wp_Travel_Engine
 * @subpackage Wp_Travel_Engine/includes/templates
 * @since 1.0.0
 */
?>
<div id="wte-crumbs">
	<?php do_action( 'wp_travel_engine_breadcrumb_holder' ); ?>
</div>
<div
	id="wp-travel-trip-wrapper"
	class="trip-content-area"
	itemscope
	itemtype="https://schema.org/LocalBusiness">
	<div class="wp-travel-inner-wrapper">
		<div class="wp-travel-engine-archive-outer-wrap">
			<?php
				global $post;
				// $termID       = get_queried_object()->term_id; // Parent A ID
				$selected_order = ! empty( $_GET['wte_orderby'] ) ? sanitize_text_field( wp_unslash( $_GET['wte_orderby'] ) ) : 'ASC';
				$termchildren   = get_terms(
					$taxonomy,
					array(
						'orderby'      => apply_filters( "wpte_{$taxonomy}_terms_order_by", 'name' ),
						'order'        => apply_filters( "wpte_{$taxonomy}_terms_order", $selected_order ),
						'hierarchical' => true,
					)
				);
				$terms_by_ids   = array();

				if ( is_array( $termchildren ) ) {
					foreach ( $termchildren as $term_object ) {
						$term_object->children  = array();
						$term_object->link      = get_term_link( $term_object->term_id );
						$term_object->thumbnail = (int) get_term_meta( $term_object->term_id, 'category-image-id', true );

						$terms_by_ids[ $term_object->term_id ] = $term_object;
					}

					// Second pass: Organize children (maintaining sort order)
					foreach ( $terms_by_ids as $term_id => $term_object ) {
						if ( ! empty( $term_object->parent ) && isset( $terms_by_ids[ $term_object->parent ] ) ) {
							$terms_by_ids[ $term_object->parent ]->children[] = $term_object;
						}
					}
				}
				if ( ! empty( $terms_by_ids ) ) {
					?>
					<div class="page-header">
							<?php
							$display_title = apply_filters( 'wpte_display_taxonomy_page_title', false );
							if ( $display_title ) :
								?>
								<h1 class="page-title" data-id="<?php echo esc_attr( $taxonomy ); ?>"><?php the_title(); ?></h1>
								<?php
								endif;
							if ( isset( $post->ID ) ) :
								?>
							<div class="page-feat-image">
								<?php
								$image_id    = get_post_thumbnail_id( $post->ID );
								$banner_size = apply_filters( 'wp_travel_engine_template_banner_size', 'full' );
								echo wp_get_attachment_image( $image_id, $banner_size );
								?>
							</div>
								<?php
							endif;
							if ( isset( $post->post_content ) ) :
								?>
							<div class="page-content">
								<p>
									<?php
									$content = apply_filters( 'the_content', $post->post_content );
									echo $content;
									?>
								</p>
							</div>
							<?php endif; ?>
					</div>
					<div class="wp-travel-toolbar clearfix" style="margin-bottom: 24px;">
						<div class="wte-filter-foundposts"></div>
						<div class="wp-travel-engine-toolbar wte-filterby-dropdown">
							<form class="wte-ordering" method="get">
								<span><?php esc_html_e( 'Sort:', 'wp-travel-engine' ); ?></span>
								<div class="wpte-trip__adv-field wpte__select-field">
									<?php
									$selected_label = $selected_order === 'ASC' ?
													__( 'a - z', 'wp-travel-engine' ) :
													__( 'z - a', 'wp-travel-engine' );
									?>
									<span class="wpte__input"><?php echo esc_html( $selected_label ); ?></span>
									<input type="hidden" class="wpte__input-value" name="wte_orderby" value="<?php echo esc_attr( $selected_order ); ?>">
									<div id="sort-options" class="wpte__select-options">
										<ul>
											<li class="wpte__select-options__label"><?php esc_html_e( 'Name', 'wp-travel-engine' ); ?></li>
											<li data-value="ASC"
												data-label="<?php esc_attr_e( 'a - z', 'wp-travel-engine' ); ?>"
												<?php
												if ( $selected_order === 'ASC' ) {
													echo 'class="selected"';}
												?>
												>
												<span><?php esc_html_e( 'a - z', 'wp-travel-engine' ); ?></span>
											</li>
											<li data-value="DESC"
												data-label="<?php esc_attr_e( 'z - a', 'wp-travel-engine' ); ?>"
												<?php
												if ( $selected_order === 'DESC' ) {
													echo 'class="selected"';}
												?>
												>
												<span><?php esc_html_e( 'z - a', 'wp-travel-engine' ); ?></span>
											</li>
										</ul>
									</div>
								</div>
							</form>
						</div>
					</div>
					<div class="<?php echo esc_attr( $taxonomy ); ?>-holder wpte-trip-list-wrapper">
							<?php
								$show_taxonomy_children = wte_array_get( get_option( 'wp_travel_engine_settings', array() ), 'show_taxonomy_children', 'no' ) === 'yes';

							foreach ( $terms_by_ids as $term_id => $term_object ) {
								if ( $term_object->parent && ! $show_taxonomy_children ) {
									continue;
								}
								?>
								<div class="item wpte-trip-category">
									<address
										itemprop="address"
										style="display: none;"><?php echo esc_html( $term_object->name ); ?></address>
									<div class="wpte-trip-category-img-wrap">
										<figure class="thumbnail">
											<a href="<?php echo esc_url( $term_object->link ); ?>">
											<?php
														$term_object->thumbnail && print( \wp_get_attachment_image(
															$term_object->thumbnail,
															apply_filters( 'wp_travel_engine_activities_img_size', 'activities-thumb-size' ),
															false,
															array( 'itemprop' => 'image' )
														) );
											?>
											</a>
										</figure>
										<div class="wpte-trip-category-overlay">
											<div class="wpte-trip-subcat-wrap">
												<?php
												if ( count( $term_object->children ) > 0 ) :
													foreach ( $term_object->children as $child_term ) {
														printf(
															'<a href="%1$s">%2$s</a>',
															esc_url( $child_term->link ),
															esc_html( $child_term->name )
														);
													}
												endif;
												?>
											</div>
											<div class="wpte-trip-category-btn">
												<?php printf( '<a href="%1$s" class="wpte-trip-cat-btn">%2$s</a>', esc_url( $term_object->link ), __( 'View All', 'wp-travel-engine' ) ); ?>
											</div>
										</div>
									</div>
									<div class="wpte-trip-category-text-wrap">
										<h2 class="wpte-trip-category-title" itemprop="name">
											<a href="<?php echo esc_url( $term_object->link ); ?>">
												<?php echo esc_html( $term_object->name ); ?></a><span class="trip-count"><?php printf( _n( '(%d Trip)', '(%d Trips)', (int) $term_object->count, 'wp-travel-engine' ), (int) $term_object->count ); ?></span>
										</h2>
									</div>
								</div>
								<?php
							}
							?>
					</div>
					<?php
				} else {
					?>
			<div class="page-header">
				<h1 class="page-title"><?php the_title(); ?></h1>
				<div class="page-feat-image">
					<?php
							the_post_thumbnail();
					?>
				</div>
				<div class="page-content">
					<?php
							$content = apply_filters( 'the_content', $post->post_content );
							echo $content;
					?>
				</div>
			</div>
					<?php
				}
				?>
		</div>
	</div>
</div>
