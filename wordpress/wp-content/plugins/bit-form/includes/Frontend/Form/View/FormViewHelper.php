<?php

namespace BitCode\BitForm\Frontend\Form\View;

use BitCode\BitForm\Frontend\Form\FrontendFormManager;

class FormViewHelper
{
  private $_formContents;
  private $_formInfo;
  private $_layout;
  private $_form;
  private $_fieldHelpers;

  public function __construct(FrontendFormManager $form, $form_contents)
  {
    $this->_form = $form;
    $this->_formContents = $form_contents;
    $this->_formInfo = isset($form_contents->formInfo) ? $form_contents->formInfo : '';
    $this->_layout = isset($form_contents->layout) ? $form_contents->layout : '';
  }

  private function getFormInfoProperty($propertyName)
  {
    return isset($this->_formInfo->{$propertyName}) ? $this->_formInfo->{$propertyName} : null;
  }

  private function getFormId()
  {
    return $this->_form->getFormID();
  }

  public function filterHtml($str)
  {
    return wp_kses_post(str_replace('$_bf_$', '\\', $str));
  }

  private function getIconMarkup($source, $classElement)
  {
    if (empty($source)) {
      return '';
    }
    return sprintf(
      '<img
          class="%1$s _frm-b-stp-icn"
          src="%2$s"
          alt=""
       />
      ',
      $this->_form->getAtomicCls($classElement),
      $source
    );
  }

  private function getHeaderLabelMarkup($lbl, $preIcn, $sufIcn, $uniqClass)
  {
    $formID = $this->getFormId();
    return sprintf(
      '<span class="%1$s _frm-b-stp-hdr-%2$s">
              %3$s
              %4$s
              %5$s
      </span>',
      $this->_form->getAtomicCls("_frm-b{$formID}-stp-hdr-{$uniqClass}"),
      $uniqClass,
      $this->getIconMarkup($preIcn, "_frm-b{$formID}-stp-{$uniqClass}-pre-i"),
      $this->filterHtml($lbl),
      $this->getIconMarkup($sufIcn, "_frm-b{$formID}-stp-{$uniqClass}-suf-i")
    );
  }

  private function getStepButton($btnSettings)
  {
    $key = $btnSettings->key;
    $txt = $btnSettings->txt;
    $btnTyp = $btnSettings->typ;
    $formID = $this->getFormId();
    $preIcn = isset($btnSettings->preIcn) ? $btnSettings->preIcn : null;
    $sufIcn = isset($btnSettings->sufIcn) ? $btnSettings->sufIcn : null;
    $preIcnMrkp = $this->getIconMarkup($preIcn, "_frm-b{$formID}-{$key}-pre-i");
    $sufIcnMrkp = $this->getIconMarkup($sufIcn, "_frm-b{$formID}-{$key}-suf-i");
    $btnSpinner = '<span class="bf-spinner d-none"></span>';
    return sprintf(
      '<button
          class="%1$s %2$s"
          name="%2$s"
          type="%3$s"
        >
          %4$s
          %5$s
          %6$s
          %7$s
        </button>',
      $this->_form->getAtomicCls("_frm-b{$formID}-{$key}"),
      $key,
      $btnTyp,
      $preIcnMrkp,
      $this->filterHtml($txt),
      $sufIcnMrkp,
      $btnSpinner
    );
  }

  public function getStepHeaderHtml()
  {
    $multiStepSettings = $this->getFormInfoProperty('multiStepSettings');

    if (isset($multiStepSettings->showStepHeader) && !$multiStepSettings->showStepHeader) {
      return '';
    }

    $formID = $this->_form->getFormID();
    $layout = $this->_layout;
    $stepHeaderHtml = sprintf(
      '<div class="%1$s _frm-b-stp-hdr-cntnr">
       <div class="%2$s _frm-b-stp-hdr-wrpr">',
      $this->_form->getAtomicCls("_frm-b{$formID}-stp-hdr-cntnr"),
      $this->_form->getAtomicCls("_frm-b{$formID}-stp-hdr-wrpr")
    );

    $showHeadarIcon = isset($multiStepSettings->headerIcon->show) ? $multiStepSettings->headerIcon->show : false;
    $showLbl = isset($multiStepSettings->showLbl) ? $multiStepSettings->showLbl : false;
    $showSubtitle = isset($multiStepSettings->showSubtitle) ? $multiStepSettings->showSubtitle : false;

    $iconType = isset($multiStepSettings->headerIcon->iconType) ? $multiStepSettings->headerIcon->iconType : 'number';

    $stepValidation = isset($multiStepSettings->validateOnStepChange) ? $multiStepSettings->validateOnStepChange : false;
    foreach ($layout as $key => $lay) {
      $step = $key + 1;
      $settings = isset($lay->settings) ? $lay->settings : null;

      if (empty($settings)) {
        continue;
      }
      $iconWrapper = '';
      $stepIcon = '';

      if ('icon' === $iconType) {
        $stepIcon = "<img src='{$settings->icon}' class={$this->_form->getAtomicCls("_frm-b{$formID}-stp-icn")} alt='Step Icon' />";
      } else {
        $stepIcon = "<span class='{$this->_form->getAtomicCls("_frm-b{$formID}-stp-num")}'>{$step}</span>";
      }
      if ($showHeadarIcon) {
        $iconWrapper = sprintf(
          '<div class="%1$s _frm-b-stp-hdr-icn-wrp">
          <div class="%2$s _frm-b-stp-icn-cntn">
            %3$s
          </div>
        </div>',
          $this->_form->getAtomicCls("_frm-b{$formID}-stp-hdr-icn-wrp"),
          $this->_form->getAtomicCls("_frm-b{$formID}-stp-icn-cntn"),
          $stepIcon
        );
      }
      $showStepLbl = $showLbl && isset($settings->showLbl) ? $settings->showLbl : false;
      $showStepSubtitle = $showSubtitle && isset($settings->showSubtitle) ? $settings->showSubtitle : false;
      $lblPreIcn = isset($settings->lblPreIcn) ? $settings->lblPreIcn : null;
      $lblSufIcn = isset($settings->lblSufIcn) ? $settings->lblSufIcn : null;
      $subTlePreIcn = isset($settings->subTlePreIcn) ? $settings->subTlePreIcn : null;
      $subTleSufIcn = isset($settings->subTleSufIcn) ? $settings->subTleSufIcn : null;
      $stepLabelMarkup = $showStepLbl ? $this->getHeaderLabelMarkup($settings->lbl, $lblPreIcn, $lblSufIcn, 'lbl') : '';
      $stepSubtitleMarkup = $showStepSubtitle ? $this->getHeaderLabelMarkup($settings->subtitle, $subTlePreIcn, $subTleSufIcn, 'sub-titl') : '';

      $activeClass = 0 === $key ? 'active' : '';
      $disableClass = (0 !== $key && $stepValidation) ? 'disabled' : '';
      $stepHeaderHtml .= sprintf(
        '
      <div class="%1$s _frm-b-stp-hdr %2$s %3$s" data-step="%4$s">
        <div class="%5$s _frm-b-stp-hdr-cntnt">
          %6$s
          <span class="%7$s _frm-b-stp-hdr-titl-wrpr">
            %8$s
            %9$s
          </span>
        </div>
      </div>',
        $this->_form->getAtomicCls("_frm-b{$formID}-stp-hdr"),
        $activeClass,
        $disableClass,
        $step,
        $this->_form->getAtomicCls("_frm-b{$formID}-stp-hdr-cntnt"),
        $iconWrapper,
        $this->_form->getAtomicCls("_frm-b{$formID}-stp-hdr-titl-wrpr"),
        $stepLabelMarkup,
        $stepSubtitleMarkup
      );
    }
    $stepHeaderHtml .= '
      </div>
    </div>';
    return $stepHeaderHtml;
  }

  public function getProgressBarMarkup()
  {
    $multiStepSettings = $this->getFormInfoProperty('multiStepSettings');
    $progressBarSettings = isset($multiStepSettings->progressSettings) ? $multiStepSettings->progressSettings : null;
    if (isset($progressBarSettings->show) && !$progressBarSettings->show) {
      return '';
    }
    $precentage = (isset($progressBarSettings->showPercentage) && $progressBarSettings->showPercentage) ? '0%' : '';
    $formID = $this->getFormId();
    return sprintf(
      '<div class="%1$s">
      <div class="%2$s">
        <div class="%3$s">
          <div class="%4$s" style="width: 0%%;">
            %5$s
          </div>
        </div>
      </div>
    </div>',
      $this->_form->getAtomicCls("_frm-b{$formID}-stp-progress-wrpr"),
      $this->_form->getAtomicCls("_frm-b{$formID}-stp-progress"),
      $this->_form->getAtomicCls("_frm-b{$formID}-stp-progress-bar"),
      $this->_form->getAtomicCls("_frm-b{$formID}-progress-fill"),
      $precentage
    );
  }

  public function getStepButtonMarkup()
  {
    $multiStepSettings = $this->getFormInfoProperty('multiStepSettings');
    $btnSettings = isset($multiStepSettings->btnSettings) ? $multiStepSettings->btnSettings : null;
    if (is_null($btnSettings) || (empty($btnSettings->show))) {
      return '';
    }
    $formID = $this->getFormId();
    return sprintf(
      '<div class="%1$s">
              <div class="%2$s">
                %3$s
                %4$s
              </div>
      </div>',
      $this->_form->getAtomicCls("_frm-b{$formID}-stp-btn-wrpr"),
      $this->_form->getAtomicCls("_frm-b{$formID}-stp-btn-cntnt"),
      $this->getStepButton($btnSettings->prevBtn),
      $this->getStepButton($btnSettings->nextBtn)
    );
  }
}
