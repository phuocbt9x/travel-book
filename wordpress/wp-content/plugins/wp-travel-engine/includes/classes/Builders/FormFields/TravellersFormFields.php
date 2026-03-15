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
class TravellersFormFields extends TravellerFormFields {

	protected int $traveller_number = 1;

	/**
	 * @var int
	 */
	public int $number_of_travellers;

	/**
	 * @var int
	 */
	protected $number_of_lead_travellers;

	/**
	 * @var array
	 */
	public $fields;


	public function __construct( $args = array() ) {
		$args = wp_parse_args(
			$args,
			array(
				'number_of_travellers'      => 1,
				'number_of_lead_travellers' => 1,
			)
		);

		$this->number_of_travellers      = (int) $args['number_of_travellers'];
		$this->number_of_lead_travellers = (int) $args['number_of_lead_travellers'];

		$this->fields = DefaultFormFields::traveller_form_fields();
		parent::__construct();
	}

	/**
	 * @return TravellerFormFields[]
	 * @since 6.4.0
	 */
	public function get_traveller_form_fields( $traveller_data = array() ): array {
		$instances = array();

		for ( $i = 1; $i <= $this->number_of_travellers; $i++ ) {
			$instance    = new parent();
			$fields      = $this->map_fields( $this->fields );
			$instances[] = $instance->init( $fields );
		}

		return $instances;
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
		$this->fellow_traveller_form_fields();
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
	 * @return void
	 */
	public function fellow_traveller_form_fields() {
		for ( $i = 0; $i < ( $this->number_of_travellers - $this->number_of_lead_travellers ); $i++ ) :
			?>
			<div class="wpte-checkout__form-section">
				<h5 class="wpte-checkout__form-title">
					<?php
					/* translators: %d: Traveller number */
					printf( __( 'Traveler %d', 'wp-travel-engine' ), $this->traveller_number + 1 );
					?>
				</h5>
				<?php $this->render_traveler_fields( $this->fields ); ?>
			</div>
			<?php
		endfor;
	}

	protected function map_fields( $fields ) {
		$form_data = WTE()->session->get( 'travellers_form_data' );
		if ( ! $form_data ) {
			$form_data = array();
		}

		$fields = array_map(
			function ( $field ) use ( $form_data ) {
				$name = preg_match( '#\[([^\[]+)]$#', $field['name'], $matches ) ? $matches[1] : $field['name'];
				if ( $name ) {
						$field['class']         = 'wpte-checkout__input';
						$field['wrapper_class'] = 'wpte-checkout__form-col';
						$field['name']          = sprintf( 'travellers[%d][%s]', $this->traveller_number, $name );

						$field['id'] = sprintf( 'travellers_%d_%s', $this->traveller_number, $name );

				}
				$field['field_label'] = isset( $field['placeholder'] ) && $field['placeholder'] !== '' ? $field['placeholder'] : $field['field_label'];
				$field['default']     = $form_data[ $this->traveller_number ][ $name ] ?? $field['default'] ?? '';

				return $field;
			},
			$fields
		);

		++$this->traveller_number;

		return $fields;
	}
}
