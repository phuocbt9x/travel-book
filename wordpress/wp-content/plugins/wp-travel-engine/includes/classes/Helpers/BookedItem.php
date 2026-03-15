<?php
/**
 *
 * @since 6.4.0
 */

namespace WPTravelEngine\Helpers;

use WPTravelEngine\Core\Cart\Items\ExtraService;
use WPTravelEngine\Core\Cart\Items\PricingCategory;
use WPTravelEngine\Core\Models\Post\Trip;
class BookedItem {

	/**
	 * @var array
	 */
	protected array $data;

	/**
	 * @var array
	 */
	protected array $totals = array();

	/**
	 * @var array
	 */
	protected array $deductible_items = array();

	/**
	 * @var array
	 */
	protected array $fees = array();

	/**
	 * @var array|mixed
	 */
	protected array $items = array();

	/**
	 * @var mixed
	 */
	protected $id;

	/**
	 * @var mixed
	 */
	protected $trip_id;

	/**
	 * @var mixed
	 */
	protected $trip_package_id;

	/**
	 * @var mixed
	 */
	protected $trip_date;

	/**
	 * @var mixed
	 */
	protected $line_items;

	/**
	 * @var int
	 */
	public int $travelers_count = 0;

	protected string $trip_code;

	/**
	 * @var mixed|string
	 */
	protected $package_name;

	/**
	 * @var mixed|string
	 */
	protected $end_date;

	/**
	 * @var mixed|string
	 */
	protected $trip_end_time;

	public function __construct( array $data ) {
		$this->data = $data;
		$this->parse( $data );
	}

	protected function parse( $data ) {
		$this->id              = $data['id'] ?? '';
		$this->trip_id         = $data['trip_id'] ?? $data['ID'] ?? 0;
		$this->trip_package_id = $data['price_key'] ?? 0;
		$this->trip_date       = empty( $data['trip_time'] ?? '' ) ? ( $data['trip_date'] ?? $data['datetime'] ?? wp_date( 'Y-m-d H:i:s' ) ) : $data['trip_time'];
		$this->package_name    = isset( $data['trip_package'] ) && $data['trip_package'] !== '' ? $data['trip_package'] : ( isset( $data['package_name'] ) && $data['package_name'] !== '' ? $data['package_name'] : '' );
		$this->line_items      = $this->parse_line_items( $data );
		$this->travelers_count = $data['travelers_count'] ?? array_sum( $data['travelers'] ?? $data['pax'] ?? array() );
		$this->trip_end_time   = isset( $data['trip_time_range'] ) && is_array( $data['trip_time_range'] ) && isset( $data['trip_time_range'][1] ) ? $data['trip_time_range'][1] : '';
		$this->custom_trip     = $data['custom_trip_name'] ?? '';

		try {
			$trip            = new Trip( (int) $this->trip_id );
			$this->trip_code = $trip->get_trip_code();
			$this->end_date  = isset( $data['end_date'] ) && $data['end_date'] !== '' ? $data['end_date'] : ( isset( $this->trip_end_time ) && $this->trip_end_time !== '' ? $this->trip_end_time : wptravelengine_format_trip_end_datetime( $this->trip_date, $trip, 'Y-m-d H:i:s' ) ?? wp_date( 'Y-m-d H:i:s' ) );
		} catch ( \Exception $e ) {
			$this->trip_code = $this->trip_id ? "WTE-{$this->trip_id}" : '';
			$this->end_date  = isset( $data['end_date'] ) && $data['end_date'] !== '' ? $data['end_date'] : ( isset( $this->trip_end_time ) && $this->trip_end_time !== '' ? $this->trip_end_time : wp_date( 'Y-m-d H:i:s' ) );
		}
	}

	protected function parse_line_items( $data ) {
		$line_items = array( 'pricing_category' => array() );
		if ( defined( 'WTE_EXTRA_SERVICES_FILE_PATH' ) ) {
			$line_items = array_merge( $line_items, array( 'extra_service' => array() ) );
		}
		if ( $data['line_items'] ?? false ) {
			$line_items = ! $data['line_items'] ? array() : $data['line_items'];
		} elseif ( isset( $data['_cart_item_object'] ) ) { // Legacy Support.
			if ( $category_info = ( $data['_cart_item_object']['category_info'] ?? false ) ) {
				$line_items['pricing_category'] = array_map(
					function ( $item, $index ) use ( $data ) {
						$pax   = $data['_cart_item_object']['pax'][ $index ] ?? 0;
						$price = $item['price'] ?? 0;

						return array(
							'label'       => $item['label'] ?? '',
							'quantity'    => $pax,
							'price'       => $price,
							'total'       => $data['_cart_item_object']['pax_cost'][ $index ] ?? ( $pax * $price ),
							'_class_name' => PricingCategory::class,
						);
					},
					$category_info,
					array_keys( $category_info )
				);
			}
			if ( $extra_line_items = ( $data['_cart_item_object']['trip_extras'] ?? false ) ) {
				$line_items['extra_service'] = array_map(
					function ( $item ) {
						$qty   = $item['qty'] ?? 0;
						$price = $item['price'] ?? 0;

						return array(
							'label'       => $item['extra_service'] ?? '',
							'quantity'    => $qty,
							'price'       => $price,
							'total'       => ( $qty * $price ),
							'_class_name' => '',
						);
					},
					$extra_line_items
				);
			}
		}

		return apply_filters( 'wptravelengine_edit_default_line_items', $line_items, $data, $this );
	}

	public function get_id() {
		return $this->id;
	}

	public function get_trip_id() {
		return $this->trip_id;
	}

	public function get_trip_title() {
		return get_the_title( $this->trip_id );
	}

	public function get_trip_package_id() {
		return $this->trip_package_id;
	}

	public function get_trip_date() {
		return $this->trip_date;
	}

	public function get_end_date() {
		return $this->end_date;
	}

	public function get_line_items(): array {
		return apply_filters( 'wptravelengine_booking_line_items', $this->line_items, $this );
	}

	public function travelers_count(): int {
		return $this->travelers_count;
	}

	public function get_trip_code(): string {
		return $this->trip_code;
	}

	public function get_package_name(): string {
		return $this->package_name;
	}

	public function get_custom_trip(): string {
		return ( $this->custom_trip ?? '' ) ?: get_the_title( $this->trip_id );
	}
}
