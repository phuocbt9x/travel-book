<?php

namespace WPTravelEngine\Blocks\Styles;

class Colors extends Style {

	protected array $color_instances = array();

	public function __construct( string $key, $value, array $settings ) {
		parent::__construct( $key, $value, $settings );

		foreach ( $value as $color_key => $color_value ) {
			$_settings = $settings;
			if ( isset( $this->control['labels'][ $color_key ]['selectors'] ) || isset( $settings['selectors'] ) ) {
				$_settings['selectors'] = $this->control['labels'][ $color_key ]['selectors'] ?? $settings['selectors'];
			}

			$_settings['label']            = $this->control['labels'][ $color_key ]['label'] ?? $settings['label'];
			$_settings['variableName']     = $this->control['labels'][ $color_key ]['variableName'] ?? $settings['variableName'] ?? $key . '-' . $color_key;
			$_settings['default']          = $color_value;
			$_settings['control']['style'] = $this->control['labels'][ $color_key ]['style'] ?? $settings['control']['style'] ?? false;

			unset( $_settings['control']['labels'] );

			$this->color_instances[ $color_key ] = Color::parse( $color_key, $color_value, $_settings );
		}
	}

	public function styles(): array {
		$styles = array();
		foreach ( $this->color_instances as $color_instance ) {
			$styles[] = $color_instance->styles();
		}

		return array_merge_recursive( ...$styles );
	}
}
