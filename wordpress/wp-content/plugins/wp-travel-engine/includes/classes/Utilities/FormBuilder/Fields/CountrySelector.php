<?php
/**
 * Country select field class.
 *
 * @package WPTravelEngine
 * @since 6.0.0
 */

namespace WPTravelEngine\Utilities\FormBuilder\Fields;

use WPTravelEngine\Helpers\Countries;

/**
 * Select field class.
 */
class CountrySelector extends Select {

	/**
	 * Initialize field type class.
	 *
	 * @param array $field Field attributes.
	 *
	 * @return Base
	 */
	public function init( array $field ): Base {

		$field['options'] = array( '' => __( 'Choose a country', 'wp-travel-engine' ) ) + Countries::list();

		return parent::init( $field );
	}
}
