<?php

namespace BitCode\BitForm\Frontend;

use BitCode\BitForm\Core\Database\FormModel;
use BitCode\BitForm\Core\Form\FormManager;
use BitCode\BitForm\Core\Util\Render;
use BitCode\BitForm\Frontend\Form\FrontendFormHandler;

class StandaloneFormView
{
  public static function preview()
  {
    if (!(current_user_can('manage_options') || current_user_can('manage_bitform'))) {
      auth_redirect();
      return;
    }
    // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- REQUEST_URI is parsed/compared, not output directly.
    $requestUri = isset($_SERVER['REQUEST_URI']) ? wp_unslash($_SERVER['REQUEST_URI']) : '';
    ('Request URI: ' . $requestUri);
    $uri = explode('/', rtrim($requestUri, '/'));

    if (is_array($uri) && count($uri) >= 3) {
      $formID = $uri[2];
      $attr = ['form_id' => $formID, 'form_preview' => true];

      $frontendFormHandler = new FrontendFormHandler();
      $formViewObject = $frontendFormHandler->handleFrontendRenderRequest($attr);
      if ('string' === gettype($formViewObject)) {
        Render::view('views/form-not-found');
        exit;
      }
      $formHTML = $formViewObject->html;
      $font = $formViewObject->font;
      $bfGlobals = $formViewObject->bfGlobals;

      set_transient('bitform_form_preview', true);
      $frontendFormHandler->generateJs($formID);
      $title = 'BitForm Preview page';
      Render::view('views/preview-page', compact('formID', 'title', 'formHTML', 'font', 'bfGlobals'));
    }
  }

  private static function getCustomUrlFormId()
  {
    // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- REQUEST_URI is parsed/compared, not output directly.
    $currentUrl = get_site_url() . (isset($_SERVER['REQUEST_URI']) ? wp_unslash($_SERVER['REQUEST_URI']) : '');
    $parsedCurrentUrl = wp_parse_url($currentUrl);
    $formModel = new FormModel();
    $forms = $formModel->get(
      ['id', 'form_content']
    );

    if (!is_wp_error($forms)) {
      foreach ($forms as $form) {
        $formID = $form->id;
        $formContent = json_decode($form->form_content);
        $isStandaloneActive = !empty($formContent->formInfo->standaloneSettings->active);
        if ($isStandaloneActive) {
          $standaloneSettings = $formContent->formInfo->standaloneSettings;
          $hasCustomUrl = !empty($standaloneSettings->customUrl);
          if (!$hasCustomUrl) {
            continue;
          }
          $customUrl = get_site_url() . '/' . $standaloneSettings->customUrl;
          $parsedCustomUrl = wp_parse_url($customUrl);
          $pathMatched = $parsedCustomUrl['path'] === $parsedCurrentUrl['path'];
          if (!$pathMatched) {
            continue;
          }
          if (!isset($parsedCustomUrl['query'])) {
            return $formID;
          }
          parse_str($parsedCustomUrl['query'], $parsedCustomQueries);
          parse_str($parsedCurrentUrl['query'], $parsedCurrentQueries);
          $diff = array_diff_assoc($parsedCustomQueries, $parsedCurrentQueries);
          if (empty($diff)) {
            return $formID;
          }
        }
      }
    }
  }

  public static function standaloneFormView()
  {
    $hasBitFormParam = isset($_GET['bit-form']);
    $formID = isset($_GET['bit-form']) ? sanitize_text_field(wp_unslash($_GET['bit-form'])) : '';

    if (empty($formID)) {
      $formID = self::getCustomUrlFormId();
    }

    if (empty($formID)) {
      return '';
    }

    $formID = intval($formID);
    $attr = ['form_id' => $formID, 'form_preview' => true];

    $FormManager = FormManager::getInstance($formID);
    $formContent = $FormManager->getFormContent();
    if (empty($formContent->formInfo->standaloneSettings->active)) {
      return;
    }

    $standaloneSettings = $formContent->formInfo->standaloneSettings;

    $frontendFormHandler = new FrontendFormHandler();
    $formViewObject = $frontendFormHandler->handleFrontendRenderRequest($attr);
    if ('string' === gettype($formViewObject)) {
      Render::view('views/form-not-found');
      exit;
    }

    $formHTML = $formViewObject->html;
    $font = $formViewObject->font;
    $bfGlobals = $formViewObject->bfGlobals;

    set_transient('bitform_form_preview', true);
    $frontendFormHandler->generateJs($formID);
    $title = !empty($standaloneSettings->pageTitle) ? $standaloneSettings->pageTitle : 'Bit Form';
    Render::view('views/standalone-form', compact('formID', 'title', 'formHTML', 'font', 'bfGlobals'));

    exit(200);
  }
}
