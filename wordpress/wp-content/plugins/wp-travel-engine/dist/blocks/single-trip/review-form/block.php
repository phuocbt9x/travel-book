<?php

/**
 * Review Form Block.
 *
 * @package Wp_Travel_Engine
 * @since 5.9
 * @var Render $render
 * @var Attributes $attributes_parser
 * @var string $wrapper_attributes
 */

if ( ! defined( 'WTE_TRIP_REVIEW_VERSION' ) ) {
	return;
}

global $wtetrip;

$obj = new Wte_Trip_Review_Init();

$button_label = $attributes_parser->get( 'buttonLabel' );

$comments_args                 = array(
	'label_submit' => $button_label,
	'title_reply'  => '',
);
$wtetrip->post->comment_status = 'open';

?>
<div <?php $attributes_parser->wrapper_attributes(); ?>>
	<div class="<?php echo ( $attributes_parser->get( 'formLabel' ) == true ? 'form-label-enabled ' : '' ) . ( $attributes_parser->get( 'fullWidth' ) == true ? 'buttom-full-width' : '' ); ?>">
		<?php
		comment_form( $comments_args, $wtetrip->post );
		?>
	</div>
</div>
