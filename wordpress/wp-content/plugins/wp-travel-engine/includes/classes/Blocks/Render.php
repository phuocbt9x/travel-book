<?php
/**
 * Render Class for blocks.
 *
 * @since 5.8.3
 */

namespace WPTravelEngine\Blocks;

use WPTravelEngine\Blocks\Attributes;
class Render {

	protected array $attributes;
	protected string $content = '';
	protected object $block;
	protected string $template_path;

	public function __construct( $args ) {
		$args = wp_parse_args(
			$args,
			array(
				'attributes'    => array(),
				'content'       => '',
				'block'         => array(),
				'template_path' => '',
			)
		);

		$this->attributes    = $args['attributes'];
		$this->content       = $args['content'];
		$this->block         = $args['block'];
		$this->template_path = $args['template_path'];
	}

	public function render(): void {
		$attributes_parser  = new Attributes( $this->attributes );
		$attributes         = $this->attributes;
		$render             = $this;
		$wrapper_attributes = '';

		global $post;
		if ( ! isset( $GLOBALS['wtetrip'] ) && isset( $post->ID ) ) {
			$GLOBALS['wtetrip'] = \WPTravelEngine\Posttype\Trip::instance( $post->ID );
		}

		if ( file_exists( $this->template_path ) ) {
			$attributes_parser->print_styles();
			include $this->template_path;
		}
	}

	public function is_editor() {
		return $this->attributes['editor'] ?? false;
	}
}
