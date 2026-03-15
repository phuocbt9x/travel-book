<?php
/**
 * Number field template.
 *
 * @package Wp Travel Engine
 */

use WPTravelEngine\Core\Models\Post\Booking as BookingModel;

class WP_Travel_Engine_Form_Field_Number extends WP_Travel_Engine_Form_Field_Text {

	// Defind field type.
	protected $field_type = 'number';

	/**
	 * Initialize field type class.
	 *
	 * @param array $field
	 * @return void
	 * @since 6.7.0
	 */
	function init( $field ) {

		$this->field = $field;

		return $this;
	}

	/**
	 * Field type render.
	 *
	 * @param boolean $display Display field or return.
	 * @return string|void
	 * @since 6.7.0
	 */
	function render( $display = true ) {
		global $post;
		$post_id = isset( $post->ID ) ? $post->ID : null;
		if ( $post_id && get_post_type( $post_id ) === 'booking' ) {
			global $post;
			$booking      = BookingModel::for( $post_id, $post );
			$is_curr_cart = $booking->is_curr_cart();
			if ( $is_curr_cart ) {
				return $this->render_in_v4( $display );
			} else {
				return $this->render_in_v4( $display );
			}
		} else {
			return $this->render_before_v4( $display );
		}
	}

	/**
	 * Render field before v4.
	 *
	 * @param boolean $display Display field or return.
	 * @return void
	 * @since 6.7.0
	 */
	function render_before_v4( $display = true ) {
		$validations = '';

		if ( isset( $this->field['validations'] ) ) :

			foreach ( $this->field['validations'] as $key => $attr ) :

				$validations .= sprintf( ' %1$s="%2$s" ', $key, $attr );

			endforeach;

		endif;

		$attributes = '';

		if ( isset( $this->field['attributes'] ) ) :

			foreach ( $this->field['attributes'] as $attribute => $attribute_val ) :
				/*
				Added to handle data-options attribute
				* @since 6.4.0
				*/
				if ( $attribute === 'data-options' ) {
					$attribute_val = htmlspecialchars( json_encode( $attribute_val, JSON_HEX_QUOT ), ENT_QUOTES, 'UTF-8' );
					$attributes   .= sprintf( ' %1$s=\'%2$s\'', $attribute, $attribute_val );
				} else {
					$attributes .= sprintf( ' %1$s="%2$s"', $attribute, $attribute_val );
				}

			endforeach;

		endif;

		$before_field = '';

		if ( isset( $this->field['before_field'] ) ) :

			$before_field_class = isset( $this->field['before_field_class'] ) ? $this->field['before_field_class'] : '';
			$before_field       = sprintf( '<span class="%1$s">%2$s</span>', $before_field_class, $this->field['before_field'] );

		endif;

		$after_field = '';

		if ( isset( $this->field['after_field'] ) ) :

			$after_field_class = isset( $this->field['after_field_class'] ) ? $this->field['after_field_class'] : '';
			$after_field       = sprintf( '<span class="%1$s">%2$s</span>', $after_field_class, $this->field['after_field'] );

		endif;

		$output = sprintf( '%1$s<input type="%2$s" id="%3$s" name="%4$s" value="%5$s" %6$s class="%7$s" %8$s>%9$s', $before_field, esc_attr( $this->field_type ), esc_attr( $this->field['id'] ), esc_attr( $this->field['name'] ), esc_attr( $this->field['default'] ), $validations, esc_attr( $this->field['class'] ), $attributes, $after_field );

		if ( ! $display ) {

			return $output;

		}

		echo $output;
	}

	/**
	 * Render field in v4.
	 *
	 * @param boolean $display Display field or return.
	 * @return void
	 * @since 6.7.0
	 */
	function render_in_v4( $display = true ) {
		$validations = '';

		if ( isset( $this->field['validations'] ) ) :

			foreach ( $this->field['validations'] as $key => $attr ) :

				$validations .= sprintf( ' %1$s="%2$s" ', $key, $attr );

			endforeach;

		endif;

		$attributes    = '';
		$prefix        = '';
		$wrapper_class = '';
		$prefix_class  = '';

		if ( isset( $this->field['attributes'] ) ) :

			foreach ( $this->field['attributes'] as $attribute => $attribute_val ) :
				/*
				Added to handle data-options attribute
				* @since 6.7.0
				*/
				if ( $attribute === 'data-options' ) {
					$attribute_val = htmlspecialchars( json_encode( $attribute_val, JSON_HEX_QUOT ), ENT_QUOTES, 'UTF-8' );
					$attributes   .= sprintf( ' %1$s=\'%2$s\'', $attribute, $attribute_val );
				} elseif ( $attribute === 'prefix' ) {
					// Extract prefix for wrapper, don't add to input attributes.
					$prefix = $attribute_val;
				} elseif ( $attribute === 'wrapper_class' ) {
					// Allow custom wrapper class.
					$wrapper_class = $attribute_val;
				} elseif ( $attribute === 'prefix_class' ) {
					// Allow custom prefix class.
					$prefix_class = $attribute_val;
				} else {
					$attributes .= sprintf( ' %1$s="%2$s"', $attribute, $attribute_val );
				}

			endforeach;

		endif;

		$before_field = '';

		if ( isset( $this->field['before_field'] ) ) :

			$before_field_class = isset( $this->field['before_field_class'] ) ? $this->field['before_field_class'] : '';
			$before_field       = sprintf( '<span class="%1$s">%2$s</span>', $before_field_class, $this->field['before_field'] );

		endif;

		$after_field = '';

		if ( isset( $this->field['after_field'] ) ) :

			$after_field_class = isset( $this->field['after_field_class'] ) ? $this->field['after_field_class'] : '';
			$after_field       = sprintf( '<span class="%1$s">%2$s</span>', $after_field_class, $this->field['after_field'] );

		endif;

		$input_element = sprintf( '<input type="%1$s" id="%2$s" name="%3$s" value="%4$s" %5$s class="%6$s" %7$s>', esc_attr( $this->field_type ), esc_attr( $this->field['id'] ), esc_attr( $this->field['name'] ), esc_attr( $this->field['default'] ), $validations, esc_attr( $this->field['class'] ), $attributes );

		// Wrap input with prefix structure if prefix and wrapper classes exist.
		if ( ! empty( $prefix ) && ! empty( $wrapper_class ) && ! empty( $prefix_class ) ) {
			$wrapped_input = sprintf(
				'<div class="%1$s"><div class="%2$s">%3$s</div>%4$s</div>',
				esc_attr( $wrapper_class ),
				esc_attr( $prefix_class ),
				esc_html( $prefix ),
				$input_element
			);
		} else {
			$wrapped_input = $input_element;
		}

		// Combine before_field, wrapped input, and after_field.
		$output = $before_field . $wrapped_input . $after_field;

		// Allow filtering of output.
		$output = apply_filters( 'wte_form_field_number_output', $output, $this->field, $prefix );

		if ( ! $display ) {

			return $output;

		}

		echo $output;
	}
}
