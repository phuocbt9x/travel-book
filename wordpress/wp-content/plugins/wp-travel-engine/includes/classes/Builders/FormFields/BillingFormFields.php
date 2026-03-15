<?php
/**
 * Billing Form Fields.
 *
 * @since 6.3.0
 */

namespace WPTravelEngine\Builders\FormFields;

use WPTravelEngine\Helpers\Countries;
use WPTravelEngine\Core\Models\Post\Customer;


/**
 * Form field class to render billing form fields.
 *
 * @since 6.3.0
 */
class BillingFormFields extends FormField {

	public function __construct( array $args = array() ) {
		parent::__construct( false );

		$this->init( $this->map_fields( \WTE_Default_Form_Fields::billing_form_fields(), $args['booking_ref'] ?? null ) );
	}

	/**
	 * @inheritDoc
	 */
	public function render(): void {
		?>
		<div class="wpte-checkout__form-section">
			<div class="wpte-checkout__form-row">
				<?php parent::render(); ?>
			</div>
		</div>
		<?php
	}

	protected function map_fields( $fields, $booking_ref ) {
		// Initialize billing form data with session data as fallback.
		$billing_form_data = WTE()->session->get( 'billing_form_data' ) ?? array();

		// Get logged in user data if available.
		$user_data = get_user_by( 'id', get_current_user_id() );

		if ( $user_data ) {
			$customer_id = Customer::is_exists( $user_data->user_email );
			if ( $customer_id ) {
				$customer_data     = new Customer( $customer_id );
				$billing_form_data = array_merge(
					array(
						'fname' => $customer_data->get_customer_fname(),
						'lname' => $customer_data->get_customer_lname(),
						'email' => $customer_data->get_customer_email(),
					),
					$customer_data->get_customer_addresses()
				);
			}
		}

		// Override with booking data if available.
		if ( $booking_ref ) {
			$booking_data = get_post_meta( $booking_ref, 'wptravelengine_billing_details', true );
			if ( $booking_data ) {
				$billing_form_data = $booking_data;
			}
		}
		if ( ! $billing_form_data ) {
			$billing_form_data = array();
		}

		return array_map(
			function ( $field ) use ( $billing_form_data ) {
				$name = null;

				// Extract the name using regex patterns.
				if ( preg_match( '#\[([^\[]+)]$#', $field['name'], $matches ) ) {
						$name = $matches[1];
				} elseif ( preg_match( '/^[^\s]+$/', $field['name'], $matches ) ) {
					$name = $matches[0];
				}

				// If a name was found, set field attributes.
				if ( $name ) {
					$field['class']         = 'wpte-checkout__input';
					$field['wrapper_class'] = 'wpte-checkout__form-col';
					if ( $field['type'] === 'file' ) {
						$field['name'] = sprintf( '%s', $name );
						$field['id']   = sprintf( '%s', $name );
					} else {
						$field['name'] = sprintf( 'billing[%s]', $name );
						$field['id']   = sprintf( 'billing_%s', $name );
					}
					$field['field_label'] = isset( $field['placeholder'] ) && $field['placeholder'] !== '' ? $field['placeholder'] : $field['field_label'];
					$field['default']     = $billing_form_data[ $name ] ?? $field['default'] ?? '';

				}

				return $field;
			},
			$fields
		);
	}

	/**
	 * @param array $form_data
	 * @return array
	 */
	public function with_values( array $form_data ) {
		$this->fields = DefaultFormFields::billing_form_fields();

		return array_map(
			function ( $field ) use ( $form_data ) {
				$name = null;

				// Extract the name using regex patterns.
				if ( preg_match( '#\[([^\[]+)]$#', $field['name'], $matches ) ) {
						$name = $matches[1];
				} elseif ( preg_match( '/^[^\s]+$/', $field['name'], $matches ) ) {
					$name = $matches[0];
				}

				// If a name was found, set field attributes.
				if ( $name ) {
					$field['class']         = 'wpte-checkout__input';
					$field['wrapper_class'] = 'wpte-checkout__form-col';
					if ( $field['type'] === 'file' ) {
						$field['name'] = sprintf( '%s', $name );
						$field['id']   = sprintf( '%s', $name );
					} else {
						$field['name'] = sprintf( 'billing[%s]', $name );
						$field['id']   = sprintf( 'billing_%s', $name );
					}
					$field['field_label'] = isset( $field['placeholder'] ) && $field['placeholder'] !== '' ? $field['placeholder'] : $field['field_label'];
					$field['default']     = $billing_form_data[ $name ] ?? $field['default'] ?? '';

					$field['value'] = $form_data[ $name ] ?? $field['default'] ?? '';
					// Convert country code to country name to show in the billing form.
					$countries_list = Countries::list();
					if ( $field['type'] == 'country' ) {
						if ( isset( $field['value'] ) && is_string( $field['value'] ) && isset( $countries_list[ $field['value'] ] ) ) {
							$field['value'] = $countries_list[ $field['value'] ];
						}
					}
				}

				return $field;
			},
			$this->fields
		);
	}
}
