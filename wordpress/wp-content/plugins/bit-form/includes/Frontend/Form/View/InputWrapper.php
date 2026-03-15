<?php

namespace BitCode\BitForm\Frontend\Form\View;

use BitCode\BitForm\Core\Util\FrontendHelpers;

class InputWrapper
{
  private $_fieldData;
  private $_fieldKey;
  private $_fieldName;
  private $_formID;
  private $_error;
  private $_value;
  private $_fieldHelpers;

  public function __construct($field, $rowID, $field_name, $form_atomic_Cls_map, $formID, $error = null, $value = null)
  {
    $this->_fieldData = $field;
    $this->_fieldKey = $rowID;
    $this->_fieldName = $field_name;
    $this->_formID = $formID;
    $this->_error = $error;
    $this->_value = $value;
    $this->_fieldHelpers = new FieldHelpers($field, $rowID, $form_atomic_Cls_map);
  }

  public function wrapper($field, $noLabel = false, $noErrMsg = false)
  {
    $labelWrapper = !$noLabel ? $this->labelWrapper() : '';
    $helperText = $this->helperText();
    $error = !$noErrMsg ? $this->errorMessages() : '';

    return sprintf(
      '<div %1$s class="%2$s %3$s">
  %4$s
  <div %5$s class="%6$s %7$s">
    %8$s
    %9$s
    %10$s
  </div>
</div>',
      $this->_fieldHelpers->getCustomAttributes('fld-wrp'),
      $this->_fieldHelpers->getAtomicCls('fld-wrp'),
      $this->_fieldHelpers->getCustomClasses('fld-wrp'),
      $labelWrapper,
      $this->_fieldHelpers->getCustomAttributes('inp-wrp'),
      $this->_fieldHelpers->getAtomicCls('inp-wrp'),
      $this->_fieldHelpers->getCustomClasses('inp-wrp'),
      $field,
      $helperText,
      $error
    );
  }

  private function labelWrapper()
  {
    $_label = '';
    $_lblPreIcn = $this->_fieldHelpers->icon('lblPreIcn', 'lbl-pre-i');
    $_lblSufIcn = $this->_fieldHelpers->icon('lblSufIcn', 'lbl-suf-i');
    $_reqPre = '';
    $_reqPost = '';
    $_lbl = '';
    $_subtitle = '';
    $_subtitleText = '';
    $_subtitlePreIcn = $this->_fieldHelpers->icon('subTlePreIcn', 'sub-titl-pre-i');
    $_subtitlePostIcn = $this->_fieldHelpers->icon('subTleSufIcn', 'sub-titl-suf-i');
    if (property_exists($this->_fieldData, 'lbl')) {
      $replaceToBackSlash = $this->_fieldHelpers->replaceToBackSlash($this->_fieldData->lbl);
      $_lbl = wp_kses_post($this->_fieldHelpers->renderHTMR($replaceToBackSlash));
    }

    if (property_exists($this->_fieldData, 'subtitle')) {
      $replaceToBackSlash = $this->_fieldHelpers->replaceToBackSlash($this->_fieldData->subtitle);
      $_subtitleText = wp_kses_post($this->_fieldHelpers->renderHTMR($replaceToBackSlash));
    }

    // for pre required icon
    if (
      $this->_fieldHelpers->property_exists_nested($this->_fieldData, 'valid->req')
      && $this->_fieldHelpers->property_exists_nested($this->_fieldData, 'valid->reqShow', true)
      && $this->_fieldHelpers->property_exists_nested($this->_fieldData, 'valid->reqPos', 'before')
    ) {
      $_reqPre = sprintf(
        '<span %1$s class="%2$s %3$s">*</span>',
        $this->_fieldHelpers->getCustomAttributes('req-smbl'),
        $this->_fieldHelpers->getAtomicCls('req-smbl'),
        $this->_fieldHelpers->getCustomClasses('req-smbl')
      );
    }
    // for post required icon
    if (
      $this->_fieldHelpers->property_exists_nested($this->_fieldData, 'valid->req')
      && $this->_fieldHelpers->property_exists_nested($this->_fieldData, 'valid->reqShow', true)
      && $this->_fieldHelpers->property_exists_nested($this->_fieldData, 'valid->reqPos', 'before', 1)
    ) {
      $_reqPost = sprintf(
        '<span %1$s class="%2$s %3$s">*</span>',
        $this->_fieldHelpers->getCustomAttributes('req-smbl'),
        $this->_fieldHelpers->getAtomicCls('req-smbl'),
        $this->_fieldHelpers->getCustomClasses('req-smbl')
      );
    }

    // for Label
    if (
      property_exists($this->_fieldData, 'lbl')
      // && isset($this->_fieldData->valid->hideLbl)
      // && $this->_fieldData->valid->hideLbl !== true
    ) {
      $bfFrontendFormIds = FrontendHelpers::$bfFrontendFormIds;
      $contentCount = count($bfFrontendFormIds);
      $_label = sprintf(
        '<label %1$s class="%2$s %3$s" for="%4$s-%5$d">%6$s%7$s%8$s%9$s%10$s</label>',
        $this->_fieldHelpers->getCustomAttributes('lbl'),
        $this->_fieldHelpers->getAtomicCls('lbl'),
        $this->_fieldHelpers->getCustomClasses('lbl'),
        $this->_fieldKey,
        $contentCount,
        $_reqPre,
        $_lblPreIcn,
        $_lbl,
        $_lblSufIcn,
        $_reqPost
      );
    }

    // for subtitle
    if (property_exists($this->_fieldData, 'subtitle') && $this->_fieldData->subtitleHide) {
      $_subtitle = sprintf(
        '<div %1$s class="%2$s %3$s">%4$s%5$s%6$s</div>',
        $this->_fieldHelpers->getCustomAttributes('sub-titl'),
        $this->_fieldHelpers->getAtomicCls('sub-titl'),
        $this->_fieldHelpers->getCustomClasses('sub-titl'),
        $_subtitlePreIcn,
        $_subtitleText,
        $_subtitlePostIcn
      );
    }

    return sprintf(
      '<div %1$s class="%2$s %3$s">%4$s%5$s</div>',
      $this->_fieldHelpers->getCustomAttributes('lbl-wrp'),
      $this->_fieldHelpers->getAtomicCls('lbl-wrp'),
      $this->_fieldHelpers->getCustomClasses('lbl-wrp'),
      $_label,
      $_subtitle
    );
  }

  private function helperText()
  {
    $_helperTxtPreIcn = $this->_fieldHelpers->icon('hlpPreIcn', 'hlp-txt-pre-i');
    $_helperTxtSufIcn = $this->_fieldHelpers->icon('hlpSufIcn', 'hlp-txt-suf-i');
    $_helperTxt = '';

    if (isset($this->_fieldData->helperTxt)) {
      $_helperTxt = wp_kses_post($this->_fieldHelpers->renderHTMR($this->_fieldData->helperTxt));
    }

    if (property_exists($this->_fieldData, 'helperTxt') && $this->_fieldData->helperTxt) {
      $_helperTxt = sprintf(
        '<div %1$s class="%2$s %3$s">%4$s%5$s%6$s</div>',
        $this->_fieldHelpers->getCustomAttributes('hlp-txt'),
        $this->_fieldHelpers->getAtomicCls('hlp-txt'),
        $this->_fieldHelpers->getCustomClasses('hlp-txt'),
        $_helperTxtPreIcn,
        $_helperTxt,
        $_helperTxtSufIcn
      );
    }

    // for helper text
    return $_helperTxt;
  }

  public function errorMessages()
  {
    $_errorPreIcn = $this->_fieldHelpers->icon('errPreIcn', 'err-txt-pre-i');
    $_errorSufIcn = $this->_fieldHelpers->icon('errSufIcn', 'err-txt-suf-i');
    $_error = '';
    $_style = 'opacity: 0 !important; height: 0px !important;';

    if ($this->_error) {
      $_error = wp_kses_post($this->_error);
      $_style = 'margin-top: 5px !important; height: 9px !important;';
    }

    $errMsgStart = '';
    $errMsgEnd = '';
    $msgStyle = "style='display: none !important;'";
    $errMsgAtomicCls = $this->_fieldHelpers->getAtomicCls('err-msg');
    $errMsgCustomCls = $this->_fieldHelpers->getCustomClasses('err-msg');
    $errMsgCustomAttr = $this->_fieldHelpers->getCustomAttributes('err-msg');
    if (!(empty($_errorPreIcn) && empty($_errorSufIcn))) {
      $errMsgStart = "<div {$errMsgCustomAttr} class='{$errMsgAtomicCls} {$errMsgCustomCls}' {$msgStyle}>";
      $errMsgEnd = '</div>';
      $msgStyle = '';
      $errMsgAtomicCls = '';
      $errMsgCustomCls = '';
      $errMsgCustomAttr = '';
    }

    return sprintf(
      '<div class="%1$s" style="%2$s">
  <div class="%3$s">
    %4$s
      %5$s
      <div %6$s %7$s class="%8$s %9$s %10$s %11$s" %12$s>%13$s</div>
      %14$s
    %15$s
  </div>
</div>',
      $this->_fieldHelpers->getAtomicCls('err-wrp'),
      $_style,
      $this->_fieldHelpers->getAtomicCls('err-inner'),
      $errMsgStart,
      $_errorPreIcn,
      $this->_fieldHelpers->getCustomAttributes('err-txt'),
      $errMsgCustomAttr,
      $errMsgAtomicCls,
      $errMsgCustomCls,
      $this->_fieldHelpers->getAtomicCls('err-txt'),
      $this->_fieldHelpers->getCustomClasses('err-txt'),
      $msgStyle,
      $_error,
      $_errorSufIcn,
      $errMsgEnd
    );
  }
}
