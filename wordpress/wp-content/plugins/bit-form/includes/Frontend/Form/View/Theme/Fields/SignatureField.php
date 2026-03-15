<?php

namespace BitCode\BitForm\Frontend\Form\View\Theme\Fields;

use BitCode\BitForm\Core\Util\FrontendHelpers;

class SignatureField
{
  public static function init($field, $rowID, $field_name, $form_atomic_Cls_map, $formID, $error = null, $value = null)
  {
    $inputWrapper = new ClassicInputWrapper($field, $rowID, $field_name, $form_atomic_Cls_map, $formID, $error, $value);
    $input = self::field($field, $rowID, $form_atomic_Cls_map, $value);
    return $inputWrapper->wrapper($input);
  }

  private static function field($field, $rowID, $form_atomic_Cls_map, $value)
  {
    $fieldHelpers = new ClassicFieldHelpers($field, $rowID, $form_atomic_Cls_map);

    $name = $fieldHelpers->name();
    $req = $fieldHelpers->required();

    $clrBtnContent = '';
    $undoBtnContent = '';
    $redoBtnContent = '';

    $bfFrontendFormIds = FrontendHelpers::$bfFrontendFormIds;
    $contentCount = count($bfFrontendFormIds);

    $clrBtnPreIcn = $fieldHelpers->icon('clrPreIcn', 'clr-btn-pre-i');
    $clrBtnSufIcn = $fieldHelpers->icon('clrSufIcn', 'clr-btn-suf-i');
    $undoBtnPreIcn = $fieldHelpers->icon('undoPreIcn', 'undo-btn-pre-i');
    $undoBtnSufIcn = $fieldHelpers->icon('undoSufIcn', 'undo-btn-suf-i');
    $redoBtnPreIcn = $fieldHelpers->icon('redoPreIcn', 'redo-btn-pre-i');
    $redoBtnSufIcn = $fieldHelpers->icon('redoSufIcn', 'redo-btn-suf-i');

    if (property_exists($field, 'clrBtnHide') && !$field->clrBtnHide) {
      $clrBtnContent = sprintf(
      '<button
        aria-label="Signature pad clear button"
        class="%1$s %2$s"
        type="button"
        %3$s
      >
        %4$s
        %5$s
        %6$s
      </button>',
        $fieldHelpers->getAtomicCls('clr-btn'),
        $fieldHelpers->getCustomClasses('clr-btn'),
        $fieldHelpers->getCustomAttributes('clr-btn'),
        $clrBtnPreIcn,
        $field->clrBtn,
        $clrBtnSufIcn
      );
    }

    if (property_exists($field, 'undoBtnHide') && !$field->undoBtnHide) {
      $undoBtnContent = sprintf(
        '<button
          aria-label="Signature pad undo button"
          class="%1$s %2$s"
          type="button"
          %3$s
        >
          %4$s  
          %5$s
          %6$s
        </button>',
        $fieldHelpers->getAtomicCls('undo-btn'),
        $fieldHelpers->getCustomClasses('undo-btn'),
        $fieldHelpers->getCustomAttributes('undo-btn'),
        $undoBtnPreIcn,
        $field->undoBtn,
        $undoBtnSufIcn
      );
    }

    if (property_exists($field, 'redoBtnHide') && !$field->redoBtnHide) {
      $redoBtnContent = sprintf(
        '<button
          aria-label="Signature pad redo button"
          class="%1$s %2$s"
          type="button"
          %3$s
        >
          %4$s  
          %5$s
          %6$s
        </button>',
        $fieldHelpers->getAtomicCls('redo-btn'),
        $fieldHelpers->getCustomClasses('redo-btn'),
        $fieldHelpers->getCustomAttributes('redo-btn'),
        $redoBtnPreIcn,
        $field->redoBtn,
        $redoBtnSufIcn
      );
    }

    return sprintf(
    '<div 
      %1$s
      class="%2$s %3$s"
    >
      <input
        type="text"
        %4$s
        class="d-none %5$s-signature-fld bf-signature-hidden-input"
        %6$s
        value=""
      />
      <canvas
        tabindex="0"
        aria-label="Signature pad"
        id="%5$s-%7$s"
        class="%8$s %9$s"
        %10$s
      >
      </canvas>

      <div aria-label="Signature pad control buttons" class="%11$s %12$s">
        %13$s
        %14$s
        %15$s
      </div>

      <iframe class="%16$s"></iframe>
    </div>',
      $fieldHelpers->getCustomAttributes('inp-fld-wrp'),
      $fieldHelpers->getAtomicCls('inp-fld-wrp'),
      $fieldHelpers->getCustomClasses('inp-fld-wrp'),
      $name,
      $rowID,
      $req,
      $contentCount,
      $fieldHelpers->getAtomicCls('signature-pad'),
      $fieldHelpers->getCustomClasses('signature-pad'),
      $fieldHelpers->getCustomAttributes('signature-pad'),
      $fieldHelpers->getAtomicCls('ctrl'),
      $fieldHelpers->getCustomClasses('ctrl'),
      $clrBtnContent,
      $undoBtnContent,
      $redoBtnContent,
      $fieldHelpers->getAtomicCls('signature-iframe')
    );
  }
}
