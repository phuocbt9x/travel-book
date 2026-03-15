<?php
/**
 * @var int $trip_id
 */

$tag_terms = get_the_terms( $trip_id, 'trip_tag' );

if ( empty( $tag_terms ) || is_wp_error( $tag_terms ) ) {
	return;
}
?>

<span class="category-trip-wtetags">
	<?php
	foreach ( $tag_terms as $tg ) :
		$tags_description = term_description( $tg->term_id );
		$tags_attribute   = $tags_description ? 'data-content="' . $tags_description . '"' : '';
		$tag_link         = get_term_link( $tg );
		$tag_name         = $tg->name;
		$tag_span_class   = isset( $tags_attribute ) && $tags_attribute != '' ? 'tippy-exist' : '';
		$tag_data_content = isset( $tags_attribute ) && $tags_attribute != '' ? $tags_attribute : '';

		printf(
			'<span class="%s" %s><a rel="tag" target="_self" href="%s">%s</a></span>',
			esc_attr( $tag_span_class ),
			wp_kses_post( $tag_data_content ),
			esc_url( $tag_link ),
			esc_html( $tag_name )
		);
	endforeach;
	?>
</span>
