<?php

namespace BitCode\BitForm\Core\Util;

use BitCode\BitForm\Core\Database\FormEntryModel;

/**
 * Entry Limit Helper Class
 * This class handles all advanced entry limit functionality
 */
class EntryLimitHelper
{
  private $formId;
  private $settings;
  private $enabled;

  public function __construct($formId, $settings, $enabled)
  {
    $this->formId = $formId;
    $this->settings = $settings;
    $this->enabled = $enabled;
  }

  /**
   * Check all entry limits and return restriction messages
   */
  public function checkAllLimits($ipAddress, $currentUserId)
  {
    $restrictionMessages = [];

    // Check submission count limits
    if ($this->isEnabled('entry_limit')) {
      $message = $this->checkSubmissionCountLimit();
      if ($message) {
        $restrictionMessages[] = $message;
      }
    }

    // Check per user limits
    if ($this->isEnabled('entry_limit_by_user')) {
      $message = $this->checkPerUserLimit($ipAddress, $currentUserId);
      if ($message) {
        $restrictionMessages[] = $message;
      }
    }
    return $restrictionMessages;
  }

  /**
   * Check submission count limit
   */
  private function checkSubmissionCountLimit()
  {
    $limit = intval($this->getSetting('entry_limit'));
    $type = $this->getSetting('entry_limit_count_type', 'total');
    $message = $this->getSettingMessages('entry_limit_message', __('Sorry! Submission limit exceeded.', 'bit-form'));

    if (!$limit) {
      return null;
    }

    $count = $this->getSubmissionCountByType($type);

    if ($count >= $limit) {
      return apply_filters(
        'bitform_filter_restriction_entry_limit_message',
        $message,
        $this->formId
      );
    }

    return null;
  }

  /**
   * Check per user limit
   */
  private function checkPerUserLimit($ipAddress, $currentUserId)
  {
    $limit = intval($this->getSetting('entry_limit_by_user'));
    $userType = $this->getSetting('entry_limit_by_user_type', 'ip');
    $timeframe = $this->getSetting('entry_limit_by_user_count_type', 'total');
    $message = $this->getSettingMessages('entry_limit_per_user_message', __('Sorry! You have exceeded the submission limit.', 'bit-form'));

    if (!$limit) {
      return null;
    }

    $count = 0;
    if ('per_user_ip' === $userType) {
      $count = $this->getSubmissionCountByUserAndType('per_user_ip', $ipAddress, $timeframe);
    } elseif ('per_user_id' === $userType && $currentUserId > 0) {
      $count = $this->getSubmissionCountByUserAndType('per_user_id', $currentUserId, $timeframe);
    } elseif ('per_user_id' === $userType && 0 === $currentUserId) {
      // Skip check for non-logged users when logged_user is selected
      return null;
    }

    if ($count >= $limit) {
      return apply_filters(
        'bitform_filter_restriction_entry_limit_message',
        $message,
        $this->formId
      );
    }

    return null;
  }

  /**
   * Get submission count by type
   */
  private function getSubmissionCountByType($type)
  {
    $formEntry = new FormEntryModel();

    // Build base condition
    $condition = ['form_id' => $this->formId];

    // Add time-based conditions using raw SQL for date functions
    $this->addTimeCondition($condition, $type);

    $countResult = $formEntry->count($condition);

    // Handle the result according to your existing pattern
    $count = !empty($countResult[0]) && !empty($countResult[0]->count) ? $countResult[0]->count : 0;

    return intval($count);
  }

  /**
   * Get submission count by user and timeframe
   */
  private function getSubmissionCountByUserAndType($userType, $userValue, $timeframe)
  {
    $formEntry = new FormEntryModel();

    // Build base condition
    $condition = ['form_id' => $this->formId];

    // Add user condition
    if ('per_user_ip' === $userType) {
      $condition['user_ip'] = ip2long($userValue);
    } elseif ('per_user_id' === $userType) {
      $condition['user_id'] = $userValue;
    } else {
      return 0;
    }

    // Add time condition using helper
    $this->addTimeCondition($condition, $timeframe);

    $countResult = $formEntry->count($condition);

    // Handle the result according to your existing pattern
    $count = !empty($countResult[0]) && !empty($countResult[0]->count) ? $countResult[0]->count : 0;

    return intval($count);
  }

  /**
   * Get burst submission count
   */
  private function getBurstSubmissionCount($ipAddress, $timeframe)
  {
    $formEntry = new FormEntryModel();

    $condition = [
      'form_id' => $this->formId,
      'user_ip' => ip2long($ipAddress)
    ];

    // Burst timeframes use different intervals
    $burstIntervals = [
      'minute'     => '1 MINUTE',
      '5minutes'   => '5 MINUTE',
      '15minutes'  => '15 MINUTE'
    ];

    $interval = isset($burstIntervals[$timeframe]) ? $burstIntervals[$timeframe] : '1 MINUTE';

    $condition['created_at'] = [
      'operator' => '>=',
      'raw'      => "DATE_SUB(NOW(), INTERVAL {$interval})"
    ];

    $countResult = $formEntry->count($condition);
    $count = !empty($countResult[0]) && !empty($countResult[0]->count) ? $countResult[0]->count : 0;

    return intval($count);
  }

  /**
   * Check if a restriction is enabled
   */
  private function isEnabled($key)
  {
    return isset($this->enabled->{$key}) && $this->enabled->{$key};
  }

  /**
   * Get setting value
   */
  private function getSetting($key, $default = null)
  {
    return isset($this->settings->{$key})
        ? $this->settings->{$key}
        : $default;
  }

  /**
       * Get setting value
       */
  private function getSettingMessages($key, $default = null)
  {
    return isset($this->settings->messages->{$key})
        ? $this->settings->messages->{$key}
        : $default;
  }

  /**
 * Add this helper method to your EntryLimitHelper class
 */
  private function addTimeCondition(&$condition, $timeframe)
  {
    if ('total' === $timeframe) {
      return; // No time condition needed
    }

    $intervals = [
      'per_minute' => '1 MINUTE',
      'per_hour'   => '1 HOUR',
      'per_day'    => '1 DAY',
      'per_week'   => '1 WEEK',
      'per_month'  => '1 MONTH',
      'per_year'   => '1 YEAR'
    ];

    if (isset($intervals[$timeframe])) {
      $condition['created_at'] = [
        'operator' => '>=',
        'raw'      => "DATE_SUB(NOW(), INTERVAL {$intervals[$timeframe]})"
      ];
    }
  }
}
