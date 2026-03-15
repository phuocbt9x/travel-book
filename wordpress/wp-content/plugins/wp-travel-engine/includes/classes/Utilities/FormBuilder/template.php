<?php
/**
 * Field template.
 *
 * @package WPTravelEngine
 * @var string $wrapper_classnames Wrapper Class
 * @var string $label_classnames Label Class
 * @var string $field_id Field ID
 * @var string $field_label Field Label
 * @var Base $renderer Instance of Field Type Class.
 */

use WPTravelEngine\Utilities\FormBuilder\Fields\Base;

?>
<div class="<?php echo esc_attr( $wrapper_classnames ); ?>">
	<label class="<?php echo esc_attr( $label_classnames ); ?>"
			for="<?php echo esc_attr( $field_id ); ?>">
		<?php echo esc_html( $field_label ); ?>
		<?php if ( $renderer->is_required() && $field_label ) : ?>
			<span class="required">*</span>
		<?php endif; ?>
	</label>
	<?php $renderer->render(); ?>
</div>
