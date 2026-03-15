<?php

namespace BitCode\BitForm\Core\Fallback;

use BitCode\BitForm\Admin\Form\Helpers;

class AppOptionsFallback
{
  public function appSettingsWithGlobalMessages()
  {
    $appSettings = get_option('bitform_app_settings', (object) []);
    if (!isset($appSettings->globalMessages)) {
      $appSettings->globalMessages = Helpers::getDefaultGlobalMessages();
      update_option('bitform_app_settings', $appSettings);
    }
  }
}
