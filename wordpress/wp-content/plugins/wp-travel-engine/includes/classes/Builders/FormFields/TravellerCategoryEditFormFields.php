<?php
/**
 *
 * @since 6.4.0
 */

namespace WPTravelEngine\Builders\FormFields;

use WPTravelEngine\Abstracts\BookingEditFormFields;
use WPTravelEngine\Core\Models\Post\Booking;

class TravellerCategoryEditFormFields extends BookingEditFormFields {

	protected $form_type = 'traveller_category';

	public function __construct( array $defaults = array(), string $mode = 'edit' ) {
		parent::__construct( $defaults, $mode );
		$this->init( $this->map_fields( static::structure( $mode ) ) );
	}

	protected function map_field( $field ) {
		?>
		<tr>
			<td>
				<div style="display: flex;align-items:center;gap:.5em;">
					<input type="text"
						name="traveller_pricing[label][]"
						placeholder="<?php echo esc_attr( __( 'Adult', 'wp-travel-engine' ) ); ?>"
						>
					<input type="number"
						name="traveller_pricing[quantity][]"
						placeholder="3"
						min="0"> ×
					<input type="number"
						name="traveller_pricing[price][]"
						placeholder="100"
						min="0" step="0.01">
				</div>
			</td>
			<td>
				<input type="number"
					name="traveller_pricing[sum][]"
					>
			</td>
		</tr>
		
		<?php
		return;
	}

	public static function create( ...$args ): TravellerCategoryEditFormFields {
		return new static( ...$args );
	}

	public static function structure( string $mode = 'edit' ): array {
		return DefaultFormFields::order_trip_form_fields( $mode );
	}
}
