<?php

namespace WPTravelEngine\Email;

/**
 * Template Tags class
 */
abstract class TemplateTags {

	/**
	 * Tags collection
	 */
	protected $tags = array();

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->tags = array(
			'{sitename}'         => get_bloginfo( 'name' ),
			'{site_admin_email}' => get_bloginfo( 'admin_email' ),
			'{ip_address}'       => $_SERVER['REMOTE_ADDR'],
		);
	}

	/**
	 * Set tags
	 *
	 * @param array $tags
	 * @return void
	 */
	public function set_tags( array $tags ) {
		$this->tags = array_merge( $this->tags, $tags );
	}

	/**
	 * Apply tags to content
	 *
	 * @param mixed $content
	 * @return array|string
	 */
	public function apply_tags( $content ) {
		return str_replace( array_keys( $this->tags ), array_values( $this->tags ), $content );
	}
}
