<?php
/**
 * Render File for Fixed Starting Date block.
 *
 * @var string $wrapper_attributes
 * @var Attributes $attributes_parser
 * @var Render $render
 * @package Wp_Travel_Engine
 * @since 5.9
 */

global $wtetrip;

// Define defaults and fetch settings
$today          = gmdate('Y-m-d');
$global_settings = wptravelengine_settings()->get();
$pagination_num  = (int) ($attributes_parser->get('noofRow') ?? $global_settings['pagination_number'] ?? 10);

// Define and assign attributes
$attributes_to_assign = [
    'book_now_btn_txt'       => 'bookingLabel',
    'sold_out_btn_txt'       => 'soldoutLabel',
    'time_slots_label'       => 'timeSlotsLabel',
    'group_discount_label'   => 'groupDiscountLabel',
    'show_more_btn_txt'      => 'showMoreLabel',
    'show_less_btn_txt'      => 'showLessLabel',
    'show_start_date'        => 'startDate',
    'show_end_date'          => 'endDate',
    'show_trip_title'        => false, // Static value
    'show_space_left_column' => 'spaceColumn',
    'show_price_column'      => 'priceColumn',
];

// Map attributes using a loop
$modified_attributes = [];
foreach ($attributes_to_assign as $arg_key => $attribute_name) {
    $modified_attributes[$arg_key] = is_string($attribute_name)
        ? $attributes_parser->get($attribute_name)
        : $attribute_name;
}
$date_format        = $attributes_parser->get('dateFormat');
$custom_date_format = $attributes_parser->get('customDateFormat');
$modified_attributes['date_format'] = $date_format === 'custom' ? $custom_date_format : $date_format;


// Fetch fixed starting date details
$args = \WTE_Fixed_Starting_Dates::$general->get_all_fsd_details([
    'trip_id'      => $post->ID,
    'is_shortcode' => true,
    'html'         => true,
]);

// Render the block
?>
<div <?php echo esc_attr($attributes_parser->wrapper_attributes()); ?>>
    <?php
    echo \WTE_Fixed_Starting_Dates::$general::get_new_table_html(array_replace($args, $modified_attributes));
    ?>
</div>
