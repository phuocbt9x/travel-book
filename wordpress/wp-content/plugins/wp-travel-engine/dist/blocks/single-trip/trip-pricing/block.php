<?php
/**
 * Render File for Price block.
 *
 * @var string $wrapper_attributes
 * @var Attributes $attributes_parser
 * @var Render $render
 * @package Wp_Travel_Engine
 * @since 5.9
 */

use WPTravelEngine\Blocks\SampleData;

global $wtetrip;
$form_layout = get_option( 'wp_travel_engine_settings', array() )[ 'pricing_section_layout' ] ?? 'layout-1';
$pricing     = [];
if ( $render->is_editor() ) {
	$pricing = SampleData::prices();
} else {
	if ( isset( $wtetrip ) && WP_TRAVEL_ENGINE_POST_TYPE != $wtetrip->post->post_type ) {
		return;
	}
	$package = $wtetrip->default_package;
	if ( isset( $package->{'package-categories'} ) ) {
		$package_categories       = (object) $package->{'package-categories'};
		$categories_in_package    = $package_categories->c_ids;
		$pricing_categories       = wte_get_terms_by_id( 'trip-packages-categories', [ 'hide_empty' => false ] );
		$primary_pricing_category = get_option( 'primary_pricing_category', 0 );
		$dynamic_package          = $attributes_parser->get( 'priceCategory' ) ?: [ $primary_pricing_category ];
		$filtered_ids             = array_intersect( $categories_in_package, $dynamic_package );
		$categories_in_package    = array_values( $filtered_ids );
		// To display primary pricing category first.
		if ( in_array( $primary_pricing_category, $categories_in_package ) ) {
			$ids_as_key = array_combine( $categories_in_package, range( 1, count( $categories_in_package ) ) );
			unset( $ids_as_key[ $primary_pricing_category ] );
			$categories_in_package = array_keys( $ids_as_key );
			array_unshift( $categories_in_package, $primary_pricing_category );
		}

		foreach ( $categories_in_package as $c_id ) {
			$price = $package_categories->prices[ $c_id ];
			if ( '' === $price ) {
				continue;
			}
			$has_sale   = isset( $package_categories->enabled_sale[ $c_id ] ) && ( '1' === $package_categories->enabled_sale[ $c_id ] );
			$sale_price = $has_sale ? $package_categories->sale_prices[ $c_id ] : $price;
			$per_label  = ! empty( $pricing_categories[ $c_id ]->name ) ? $pricing_categories[ $c_id ]->name : $package_categories->labels[ $c_id ];

			$pricing[] = [
				'price'      => $price,
				'sale_price' => $sale_price,
				'has_sale'   => $has_sale,
				'per_label'  => $per_label,
			];
		}
	}
}
?>
<div <?php $attributes_parser->wrapper_attributes(); ?>>
	<div class="wpte-bf-price-wrap<?php echo $attributes_parser->get("enableDivider") ? ' has-divider' : ''; ?>">
		<?php foreach ( $pricing as $data ): ?>
			<div class="wpte-bf-price">
				<span class="wpte-bf-reg-price">
					<?php
					$regular_price = wte_get_formated_price( $data[ 'price' ], '', $attributes_parser->get( 'displayFormat' ) );
					echo wp_kses(
						str_replace( '%regular_price%', $data[ 'has_sale' ] ? "<del>{$regular_price}</del>" : '', $attributes_parser->get( 'pricePrefix' ) ),
						array(
							'span' => array(
								'class' => array(),
							),
							'del'  => array(
								'class' => array(),
							),
						)
					);
					?>
                </span>
				<span class="wpte-bf-offer-price">
					<?php
					$sale_price = is_numeric( $data[ 'sale_price' ] ) ? $data[ 'sale_price' ] : $data[ 'price' ];
					$sale_price = wte_get_formated_price( $sale_price, '', $attributes_parser->get( 'displayFormat' ) );
					$label              = $data[ 'per_label' ];
					$priceDisplayFormat = $attributes_parser->get( 'priceDisplayFormat' );
					$priceDisplayFormat = str_replace( '%price_category%', $label, $priceDisplayFormat );
					echo wp_kses(
						str_replace(
							'%sale_price%',
							"<ins class='wpte-bf-offer-amount'>{$sale_price}</ins>",
							$priceDisplayFormat
						),
						array(
							'ins' => array( 'class' => array(), ),
							'div' => array( 'class' => array(), ),
						)
					);
					?>
                </span>
			</div>
		<?php endforeach; ?>
	</div>
</div>
