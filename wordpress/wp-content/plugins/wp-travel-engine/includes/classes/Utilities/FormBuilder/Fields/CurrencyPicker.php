<?php
/**
 * Currency Picker.
 *
 * @package WPTravelEngine
 * @since 6.0.4
 */

namespace WPTravelEngine\Utilities\FormBuilder\Fields;

use WPTravelEngine\Helpers\Currencies;

/**
 * Trips List field class.
 */
class CurrencyPicker extends Select {

	/**
	 * @var array|null $trips_options Trips options.
	 */
	protected static ?array $trips_options = null;

	/**
	 * Get Select options.
	 *
	 * @return array
	 */
	protected function get_options(): array {
		return Currencies::list();
	}

	/**
	 * Initialize field type class.
	 *
	 * @param array $field Field attributes.
	 *
	 * @return Base
	 */
	public function init( array $field ): Base {

		$field['options'] = array( '' => __( 'Currency', 'wp-travel-engine' ) ) + $this->get_options();

		return parent::init( $field );
	}
}
