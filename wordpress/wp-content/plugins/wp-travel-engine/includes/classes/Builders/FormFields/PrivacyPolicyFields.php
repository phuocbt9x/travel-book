<?php
/**
 * Billing Form Fields.
 *
 * @since 6.3.0
 */

namespace WPTravelEngine\Builders\FormFields;

/**
 * Form field class to render billing form fields.
 *
 * @since 6.3.0
 */
class PrivacyPolicyFields extends FormField {

	public function __construct() {
		parent::__construct( false );

		$fields = DefaultFormFields::privacy_form_fields();

		$this->init( $fields );
	}

	public function render() {
		parent::render();
	}
}
