<?php

/**
 * Labels.
 *
 * @since 6.2.0
 */

return apply_filters(
	'modify_labels',
	array(
		'title'  => __( 'Labels', 'wp-travel-engine' ),
		'order'  => 30,
		'id'     => 'modify_labels',
		'fields' => array(
			array(
				'field_type' => 'ALERT',
				'content'    => __( 'The Custom Labels feature in our plugin provides you with the flexibility to personalize static strings on your website. For instance, if the default label in the plugin setting is "Travellers," you can modify it to "Travelers." This feature can also serve as a basic tool for translation. <br> Please note, this feature leverages the __() translation function in WordPress and is designed for simple, static strings. It may not support complex or lengthy strings. For advanced modifications or longer strings, you might need to explore alternative solutions or seek professional assistance.', 'wp-travel-engine' ),
				'status'     => 'info',
			),
			array(
				'label'      => __( 'Labels', 'wp-travel-engine' ),
				'field_type' => 'TEXT_REPLACER',
				'name'       => 'custom_strings',
			),
		),
	)
);
