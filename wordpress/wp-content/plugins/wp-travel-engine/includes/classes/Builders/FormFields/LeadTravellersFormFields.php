<?php
/**
 * Lead Travellers Form Fields.
 *
 * @package WPTravelEngine\Builders\FormFields
 * @since 6.4.3
 */

namespace WPTravelEngine\Builders\FormFields;

use WPTravelEngine\Core\Models\Post\Customer as CustomerModel;

/**
 * Form field class to render lead travellers form fields.
 */
class LeadTravellersFormFields extends LeadTravellerFormFields {

	/**
	 * Form fields configuration.
	 *
	 * @var array
	 */
	public $fields;

	/**
	 * Constructor.
	 *
	 * @param array $args Optional arguments (unused, for compatibility).
	 */
	public function __construct( $args = array() ) {
		$this->fields = DefaultFormFields::lead_traveller_form_fields();
		parent::__construct();
	}

	/**
	 * Render the form fields.
	 *
	 * @return void
	 */
	public function render() {
		if ( empty( $this->fields ) ) {
			return;
		}
		$this->lead_traveller_form_fields();
	}

	/**
	 * Render the traveler fields.
	 *
	 * @param array $fields Form fields.
	 */
	protected function render_traveler_fields( array $fields ) {
		$instance = new parent();
		$fields   = $this->map_fields( $fields );
		$instance->init( $fields )->render();
	}

	/**
	 * Render the lead traveller form fields.
	 */
	public function lead_traveller_form_fields() {
		?>
			<div id="wpte-lead-traveller" class="wpte-checkout__form-section">
				<h5 class="wpte-checkout__form-title"><?php echo esc_html( sprintf( /* translators: %d: traveller number */ __( 'Lead Traveller %d', 'wp-travel-engine' ), 1 ) ); ?></h5>
				<?php
				$this->render_traveler_fields( $this->fields );
				?>
			</div>
		<?php
	}


	/**
	 * Get lead traveller data from customer meta (logged-in users).
	 *
	 * Uses in-request static cache and WordPress object cache (when available) to avoid
	 * repeated user/customer/meta queries when the form is rendered multiple times or across requests.
	 *
	 * @since 6.7.6
	 * @return array Lead traveller data (index 0) from customer meta, or empty array.
	 */
	protected function get_customer_lead_cached() {
		static $cache = array();
		$user_id      = get_current_user_id();
		if ( isset( $cache[ $user_id ] ) ) {
			return $cache[ $user_id ];
		}
		$cache_group       = 'wptravelengine_lead_traveller';
		$cache_key         = 'customer_lead_' . $user_id;
		$from_object_cache = function_exists( 'wp_cache_get' ) ? wp_cache_get( $cache_key, $cache_group ) : false;
		if ( is_array( $from_object_cache ) ) {
			$cache[ $user_id ] = $from_object_cache;
			return $from_object_cache;
		}
		$customer_lead = array();
		if ( $user_id && is_user_logged_in() ) {
			$user_data   = get_user_by( 'id', $user_id );
			$customer_id = $user_data ? CustomerModel::is_exists( $user_data->user_email ) : 0;
			if ( $customer_id ) {
				$customer_data = new CustomerModel( $customer_id );
				$customer_meta = $customer_data->get_customer_meta();
				if ( isset( $customer_meta['wptravelengine_traveller_details'][0] ) && is_array( $customer_meta['wptravelengine_traveller_details'][0] ) ) {
					$customer_lead = $customer_meta['wptravelengine_traveller_details'][0];
				}
			}
		}
		$cache[ $user_id ] = $customer_lead;
		if ( function_exists( 'wp_cache_set' ) ) {
			wp_cache_set( $cache_key, $customer_lead, $cache_group, 300 );
		}
		return $customer_lead;
	}

	/**
	 * Map the fields.
	 *
	 * @param array $fields Form fields.
	 * @return array
	 */
	protected function map_fields( $fields ) {
		// Session lead traveller (index 0); merged on top of customer data so current-session input is preserved (e.g. after validation errors).
		$form_data    = WTE()->session->get( 'travellers_form_data' );
		$session_lead = array();
		if ( is_array( $form_data ) && isset( $form_data[0] ) && is_array( $form_data[0] ) ) {
			$session_lead = $form_data[0];
		}

		// Saved lead traveller from customer meta (logged-in users only); used as base so users do not re-enter every time.
		// Cached per request to avoid repeated user/customer/meta queries when the form is rendered multiple times.
		$customer_lead = $this->get_customer_lead_cached();

		// Start with customer data; overlay only non-empty session values so empty session keys don't blank out customer data. Allow 0 and '0' as valid.
		$merged_lead = $customer_lead;
		foreach ( $session_lead as $key => $value ) {
			if ( ! empty( $value ) || $value === '0' || $value === 0 ) {
				$merged_lead[ $key ] = $value;
			}
		}

		$fields = array_map(
			function ( $field ) use ( $merged_lead ) {
				$name = preg_match( '#\[([^\[]+)]$#', $field['name'], $matches ) ? $matches[1] : $field['name'];
				if ( $name ) {
					$field['class']         = 'wpte-checkout__input';
					$field['wrapper_class'] = 'wpte-checkout__form-col';
					$field['name']          = sprintf( 'travellers[%d][%s]', 0, $name );
					$field['id']            = sprintf( 'travellers_%d_%s', 0, $name );
				}
				$field['field_label'] = isset( $field['placeholder'] ) && '' !== $field['placeholder'] ? $field['placeholder'] : $field['field_label'];
				$field['default']     = $merged_lead[ $name ] ?? $field['default'] ?? '';

				return $field;
			},
			$fields
		);

		return $fields;
	}
}
