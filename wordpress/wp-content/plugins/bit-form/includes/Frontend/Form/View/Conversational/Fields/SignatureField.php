<?php

namespace BitCode\BitForm\Frontend\Form\View\Conversational\Fields;

use BitCode\BitForm\Core\Util\FrontendHelpers;

class SignatureField
{
  public static function init($field, $rowID, $field_name, $form_atomic_Cls_map, $formID, $error = null, $value = null)
  {
    $inputWrapper = new ConversationalInputWrapper($field, $rowID, $field_name, $form_atomic_Cls_map, $formID, $error, $value);
    $input = self::field($field, $rowID, $form_atomic_Cls_map, $formID,  $value);
    return $inputWrapper->wrapper($input);
  }

  private static function field($field, $rowID, $form_atomic_Cls_map, $formID, $value)
  {
    $fieldHelpers = new ConversationalFieldHelpers($formID, $field, $rowID, $form_atomic_Cls_map);

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
      $clrBtnClass = $fieldHelpers->getConversationalMultiCls('clr-btn') . ' ' . $fieldHelpers->getCustomClasses('clr-btn');
      $clrBtnAttrs = $fieldHelpers->getCustomAttributes('clr-btn');
      $clrBtnContent = sprintf(
        '<button
         aria-label="Signature pad clear button"
         class="%1$s"
         type="button"
         %2$s
      >
        %3$s
        %4$s
        %5$s
      </button>',
        $clrBtnClass,
        $clrBtnAttrs,
        $clrBtnPreIcn,
        $field->clrBtn,
        $clrBtnSufIcn
      );
    }

    if (property_exists($field, 'undoBtnHide') && !$field->undoBtnHide) {
      $undoBtnClass = $fieldHelpers->getConversationalMultiCls('undo-btn') . ' ' . $fieldHelpers->getCustomClasses('undo-btn');
      $undoBtnAttrs = $fieldHelpers->getCustomAttributes('undo-btn');
      $undoBtnContent = sprintf(
        '<button
          aria-label="Signature pad undo button"
          class="%1$s"
          type="button"
          %2$s
        >
          %3$s
          %4$s
          %5$s
        </button>
',
        $undoBtnClass,
        $undoBtnAttrs,
        $undoBtnPreIcn,
        $field->undoBtn,
        $undoBtnSufIcn
      );
    }

    if (property_exists($field, 'redoBtnHide') && !$field->redoBtnHide) {
      $redoBtnClass = $fieldHelpers->getConversationalMultiCls('redo-btn') . ' ' . $fieldHelpers->getCustomClasses('redo-btn');
      $redoBtnAttrs = $fieldHelpers->getCustomAttributes('redo-btn');
      $redoBtnContent = sprintf(
        '<button
          aria-label="Signature pad redo button"
          class="%1$s"
          type="button"
          %2$s
        >
          %3$s
          %4$s
          %5$s
        </button>
',
        $redoBtnClass,
        $redoBtnAttrs,
        $redoBtnPreIcn,
        $field->redoBtn,
        $redoBtnSufIcn
      );
    }

    $wrapperAttrs = $fieldHelpers->getCustomAttributes('inp-fld-wrp');
    $wrapperClass = $fieldHelpers->getConversationalMultiCls('inp-fld-wrp') . ' ' . $fieldHelpers->getCustomClasses('inp-fld-wrp');
    $signatureFieldClass = "{$rowID}-signature-fld";
    $signaturePadId = "{$rowID}-{$contentCount}";
    $signaturePadClass = $fieldHelpers->getConversationalMultiCls('signature-pad') . ' ' . $fieldHelpers->getCustomClasses('signature-pad');
    $signaturePadAttrs = $fieldHelpers->getCustomAttributes('signature-pad');
    $controlClass = $fieldHelpers->getConversationalCls('ctrl') . ' ' . $fieldHelpers->getCustomClasses('ctrl');
    $signatureIframeClass = $fieldHelpers->getConversationalMultiCls('signature-iframe');

    return sprintf(
      '<div
      %1$s
      class="%2$s"
    >
      <input
        type="text"
        %3$s
        class="d-none %4$s bf-signature-hidden-input"
        %5$s
        value=""
      />
      <canvas
        tabindex="0"
        aria-label="Signature pad"
        id="%6$s"
        class="%7$s"
        %8$s
      >
      </canvas>

      <div aria-label="Signature pad control buttons" class="%9$s">
         %10$s
         %11$s
         %12$s
      </div>
      <iframe class="%13$s"></iframe>
    </div>',
      $wrapperAttrs,
      $wrapperClass,
      $name,
      $signatureFieldClass,
      $req,
      $signaturePadId,
      $signaturePadClass,
      $signaturePadAttrs,
      $controlClass,
      $clrBtnContent,
      $undoBtnContent,
      $redoBtnContent,
      $signatureIframeClass
    );
  }
}
