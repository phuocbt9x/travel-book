<?php
/**
 * Thank You Page Template.
 *
 * @since 6.3.3
 */

get_header();

do_action( 'wptravelengine_thankyou_before_content' );

do_action( 'wptravelengine_thankyou_content' );

do_action( 'wptravelengine_thankyou_after_content' );

get_footer();
