<?php

use WPTravelEngine\Helpers\Currencies;

/**
 * Countries dropdown field class
 *
 * @package WP Travel Engine
 */
class WP_Travel_Engine_Form_Field_Currency_Picker extends WP_Travel_Engine_Form_Field_Select {

	/**
	 * Field type name
	 *
	 * @var string
	 */
	protected $field_type = 'currency_picker';

	/**
	 * Initialize class
	 *
	 * @param obj $field
	 *
	 * @return void
	 */
	function init( $field ) {

		$currency_options = Currencies::list();

		$currency_options = apply_filters( 'wptravelengine_modify_currency_selector', array( '' => __( 'Choose a currency*', 'wp-travel-engine' ) ) + $currency_options );

		$this->field = $field;

		$this->field['options'] = $currency_options;

		return $this;
	}
}
