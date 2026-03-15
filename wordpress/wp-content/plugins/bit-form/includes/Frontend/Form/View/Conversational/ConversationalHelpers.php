<?php

namespace BitCode\BitForm\Frontend\Form\View\Conversational;

use BitCode\BitForm\Admin\Form\Helpers;

class ConversationalHelpers
{
  private $_formId;
  private $_conversationalSettings;
  private $_stepListObject;

  public function __construct($formId, $conversationalSettings)
  {
    $this->_formId = $formId;
    $this->_conversationalSettings = $conversationalSettings;
    $this->_stepListObject = $this->_conversationalSettings->stepListObject;
  }

  public function getWelcomePageView()
  {
    $welcomePageSettings = $this->getMergedStepSettings('welcomePage');
    $welcomeTitle = '';
    $welcomePageMarkup = '';
    $buttonMarkup = '';

    if (!$welcomePageSettings->enable) {
      return '';
    }
    if (!empty($welcomePageSettings->title)) {
      $welcomeTitle =
        '      <div class="bc' . $this->_formId . '-welcome-title">' . "\n"
        . '        <h2>' . $welcomePageSettings->title . '</h2>' . "\n"
        . '      </div>';
    }

    $startBtnPreIcn = $this->conversationalSettingsIcon($welcomePageSettings, 'startBtnPreIcn', 'btn-pre-icon', 'Start Button Pre Icon');
    $startBtnSufIcn = $this->conversationalSettingsIcon($welcomePageSettings, 'startBtnSufIcn', 'btn-suf-icon', 'Start Button Suf Icon');

    $startBtnText = !empty($welcomePageSettings->btnTxt) ? $welcomePageSettings->btnTxt : 'Start';

    $stepHints = !empty($welcomePageSettings->stepHints) ? $welcomePageSettings->stepHints : '';

    $buttonMarkup =
      '    <div class="bc' . $this->_formId . '-step-btn-wrpr">' . "\n"
      . '      <div class="bc' . $this->_formId . '-step-btn-inner-wrpr">' . "\n"
      . '          <div class="bc' . $this->_formId . '-step-btn-cntnt">' . "\n"
      . '            <button class="bc' . $this->_formId . '-start-btn bc' . $this->_formId . '-btn" type="button">' . "\n"
      . '              ' . $startBtnPreIcn . "\n"
      . '              ' . $startBtnText . "\n"
      . '              ' . $startBtnSufIcn . "\n"
      . '            </button>' . "\n"
      . '            <span class="bc' . $this->_formId . '-step-hints">' . "\n"
      . '              ' . $stepHints . "\n"
      . '            </span>' . "\n"
      . '          </div>' . "\n"
      . '      </div>' . "\n"
      . '    </div>';

    $welcomePageMarkup =
      '    <div class="bc' . $this->_formId . '-welcome-content">' . "\n"
      . '      ' . $welcomeTitle . "\n"
      . '      ' . $welcomePageSettings->content . "\n"
      . '      ' . $buttonMarkup . "\n"
      . '    </div>';
    $imageContent = $this->conversationalSettingsIcon($welcomePageSettings, 'layoutImage', 'step-img', 'Background Image');
    $welcomeLayout = $welcomePageSettings->layout;
    $welcomeLayoutClsNames = "bc{$this->_formId}-welcome $welcomeLayout";
    return $this->getStepLayout($this->_formId, $imageContent, $welcomePageMarkup, $welcomeLayout, $welcomeLayoutClsNames);
  }

  public function getNavigationView()
  {
    $navigationSettings = $this->_conversationalSettings->navigationSettings;
    $formId = $this->_formId;
    $progressLabelMarkup = '';
    $progressBarMarkup = '';
    $progressMarkup = '';
    $brandingMarkup = '';
    $navBtnMarkup = '';

    if ($navigationSettings->showProgressLabel) {
      $progressLabelMarkup =
        '      <div class="bc' . $formId . '-progress-lbl-wrpr">' . "\n"
        . '        <span class="bc' . $formId . '-progress-lbl">' . "\n"
        . '          ' . $navigationSettings->progressLabel . "\n"
        . '        </span>' . "\n"
        . '      </div>';
    }

    if ($navigationSettings->showProgressBar) {
      $progressBarMarkup =
        '      <div class="bc' . $formId . '-progress-bar-wrpr">' . "\n"
        . '        <div class="bc' . $formId . '-progress-bar">' . "\n"
        . '          <div class="bc' . $formId . '-progress-fill" style="width: 40%"></div>' . "\n"
        . '        </div>' . "\n"
        . '      </div>';
    }

    if ($navigationSettings->showProgressBar || $navigationSettings->showProgressLabel) {
      $progressMarkup =
        '        <div class="bc' . $formId . '-progress-wrpr">' . "\n"
        . '          <div class="bc' . $formId . '-progress-cntnt" >' . "\n"
        . '          ' . $progressLabelMarkup . "\n"
        . '          ' . $progressBarMarkup . "\n"
        . '          </div>' . "\n"
        . '        </div>';
    }

    if ($navigationSettings->showBranding) {
      $brandingMarkup =
        '          <div class="bc' . $formId . '-branding-wrpr">' . "\n"
        . '            <div class="bc' . $formId . '-branding-cntnt">' . "\n"
        . '              <div class="bc' . $formId . '-branding-lbl">' . "\n"
        . '                <span class="bc' . $formId . '-prowered-by-lbl">Powered by</span>' . "\n"
        . '                <span class="bc' . $formId . '-bit-form-lbl">Bit Form</span>' . "\n"
        . '              </div>' . "\n"
        . '            </div> ' . "\n"
        . '          </div>';
    }

    if ($navigationSettings->showNavigateBtn) {
      $navBtnMarkup =
        '      <div class="bc' . $formId . '-nav-btn-wrpr">' . "\n"
        . '        <div class="bc' . $formId . '-nav-btn-cntnt">' . "\n"
        . '          <button class="bc' . $formId . '-nav-btn bc' . $formId . '-nav-btn-up" type="button">' . "\n"
        . '            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-up"><line x1="12" y1="19" x2="12" y2="5"></line><polyline points="5 12 12 5 19 12"></polyline></svg>' . "\n"
        . '          </button>' . "\n"
        . '          <button class="bc' . $formId . '-nav-btn bc' . $formId . '-nav-btn-down" type="button">' . "\n"
        . '            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-up"><line x1="12" y1="19" x2="12" y2="5"></line><polyline points="5 12 12 5 19 12"></polyline></svg>' . "\n"
        . '          </button>' . "\n"
        . '        </div>' . "\n"
        . '      </div>';
    }
    $navBtnContainer = '';
    if ($navigationSettings->showNavigateBtn || $navigationSettings->showBranding) {
      $navBtnContainer =
        '      <div class="bc' . $formId . '-nav-btn-container">' . "\n"
        . '        ' . $brandingMarkup . "\n"
        . '        ' . $navBtnMarkup . "\n"
        . '      </div>';
    }

    if ($navigationSettings->show) {
      return
        '      <div class="bc' . $formId . '-nav-wrpr">' . "\n"
        . '        <div class="bc' . $formId . '-nav-wrpr-cntnt">' . "\n"
        . '          ' . $progressMarkup . "\n"
        . '          ' . $navBtnContainer . "\n"
        . '        </div>' . "\n"
        . '      </div>';
    }
    return '';
  }

  public function getStepButtonsView($fieldData, $stepSettings)
  {
    $formId = $this->_formId;
    $isFieldRequired = !empty($fieldData->valid->req) && $fieldData->valid->req;
    $btnTxt = $isFieldRequired ? $stepSettings->nextBtnTxt : $stepSettings->btnTxt;
    $btnPreIcn = $this->conversationalSettingsIcon($stepSettings, 'btnPreIcn', 'btn-pre-icon', 'Button Leading Icon');
    $btnSufIcn = $this->conversationalSettingsIcon($stepSettings, 'btnSufIcn', 'btn-suf-icon', 'Button Trailing Icon');
    $hiddenClass = $isFieldRequired ? 'bc-grid-hide' : '';
    $stepHints = !empty($stepSettings->stepHints) ? $stepSettings->stepHints : '';

    $btnWrapperMarkup =
      '    <div class="' . $hiddenClass . ' bc' . $formId . '-step-btn-wrpr">' . "\n"
      . '      <div class="bc' . $formId . '-step-btn-inner-wrpr">' . "\n"
      . '        <div class="bc' . $formId . '-step-btn-cntnt">' . "\n"
      . '          <button class="bc' . $formId . '-btn bc' . $formId . '-btn-ok" type="button">' . "\n"
      . '            ' . $btnPreIcn . "\n"
      . '            ' . $btnTxt . "\n"
      . '            ' . $btnSufIcn . "\n"
      . '          </button>' . "\n"
      . '          <span class="bc' . $formId . '-step-hints">' . "\n"
      . '            ' . $stepHints . "\n"
      . '          </span>' . "\n"
      . '        </div>' . "\n"
      . '      </div>' . "\n"
      . '    </div>';

    return $btnWrapperMarkup;
  }

  public static function getStepLayout($formID, $imageContent, $fieldContent, $layoutName, $layoutClassNames = '')
  {
    $imageContentWrapper =
      '    <div class="bc' . $formID . '-step-img-cntnr">' . "\n"
      . '      <picture class="bc' . $formID . '-step-img-wrpr">' . "\n"
      . '        ' . $imageContent . "\n"
      . '      </picture>' . "\n"
      . '    </div>';
    $imageContentWrapper = 'normal-layout' === $layoutName ? '' : $imageContentWrapper;

    return
      '    <div class="bc' . $formID . '-step-wrapper bc' . $formID . '-step bc-step-deactive ' . $layoutClassNames . '">' . "\n"
      . '      <div class="bc' . $formID . '-step-content">' . "\n"
      . '        ' . $imageContentWrapper . "\n"
      . '        <div class="bc' . $formID . '-step-fld-wrpr">' . "\n"
      . '          ' . $fieldContent . "\n"
      . '        </div>' . "\n"
      . '      </div>' . "\n"
      . '    </div>';
  }

  public function conversationalSettingsIcon($data, $icnPropName, $element, $alt = 'Image')
  {
    if (Helpers::property_exists_nested($data, $icnPropName, '', 1)) {
      $url = esc_url($data->$icnPropName);
      return
        '              <img' . "\n"
        . '                class="bc' . $this->_formId . '-' . $element . '"' . "\n"
        . '                src="' . $url . '"' . "\n"
        . '                alt=' . $alt . "\n"
        . '              />';
    }
    return '';
  }

  public function filterConversationalAllowedFields($layout, $fields)
  {
    $ignoreFields = $this::getConversationalIgnoredFields();
    $newLayouts = (object) ['lg' => []];
    foreach ($layout->lg as $row) {
      if (!in_array($fields->{$row->i}->typ, $ignoreFields)) {
        $newLayouts->lg[] = $row;
      }
    }
    return $newLayouts;
  }

  public static function getConversationalIgnoredFields()
  {
    return [
      'button',
      'divider',
      'spacer',
      'title',
      'html',
      'shortcode',
      'recaptcha',
      'turnstile',
      'hcaptcha',
      'image',
      'stripe',
      'paypal',
      'razorpay',
    ];
  }

  public function getMergedStepSettings($stepPropertyName)
  {
    $allStepsSettings = $this->_stepListObject->allSteps;
    $stepSettings = !empty($this->_stepListObject->{$stepPropertyName}) ? $this->_stepListObject->{$stepPropertyName} : (object) [];
    $mergedSettings = (object) array_merge((array) $allStepsSettings, (array) $stepSettings);

    return $mergedSettings;
  }
}
