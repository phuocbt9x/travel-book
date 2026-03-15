<?php
/**
 * Single Trip Faqs Template
 *
 * @var string $wrapper_attributes
 * @var Render $render
 * @var Attributes $attributes_parser
 * @package Wp_Travel_Engine
 * @since 5.9
 */

use WPTravelEngine\Blocks\SampleData;

global $wtetrip;

if ( $render->is_editor() ) {
	$faqs = SampleData::faqs();
} else {

	$post_meta = get_post_meta( $wtetrip->post->ID, 'wp_travel_engine_setting', true );
	$trip_faqs = ( $post_meta[ 'faq' ] ?? false );

	if ( ! $trip_faqs || ! is_array( $trip_faqs[ 'faq_title' ] ) ) {
		return;
	}

	$faqs = array();
	foreach ( $trip_faqs[ 'faq_title' ] as $key => $faq_title ) {
		if ( isset( $trip_faqs[ 'faq_content' ][ $key ] ) ) {
			$faqs[] = array(
				'question' => $faq_title,
				'answer' => $trip_faqs['faq_content'][$key]
			);
		}
	}
}

?>
	<div <?php $attributes_parser->wrapper_attributes(); ?>>
		<div class="post-data faq">
			<div class="wp-travel-engine-faq-tab-content<?php echo $attributes_parser->get('showDivider') ? ' has-divider' : ''; ?>">
				<?php
				foreach ( $faqs as $index => $faq ) {
					$active = ( 0 === $index );
					?>
					<div id="faq-tabs" class="faq-row">
						<a class="accordion-tabs-toggle <?php echo esc_attr( $active ? 'active' : '' ); ?>"
						   href="javascript:void(0);">
							<span
								class="dashicons dashicons-arrow-down custom-toggle-tabs rotator <?php echo esc_attr( $active ? 'open' : '' ); ?>"></span>
							<div class="faq-title">
								<?php echo esc_html( $faq['question'] ); ?>
							</div>
						</a>
						<div class="faq-content" style="<?php echo( ! $active ? 'display: none;' : '' ); ?>">
							<?php echo wp_kses_post( $faq['answer'] ); ?>
						</div>
					</div>
					<?php
				}
				?>
			</div>
		</div>
	</div>
<?php
