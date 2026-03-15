<?php

namespace BitCode\BitForm;

use BitCode\BitForm\Core\Util\Log;

if (!\defined('ABSPATH')) {
  exit;
}

class GlobalHelper
{
  /**
   * Get all forms.
   *
   * @return array
   */
  public static function getForms()
  {
    global $wpdb;

    // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
    $allForms = $wpdb->get_results("SELECT forms.id,forms.entries as fm_entries,forms.form_name,forms.status,forms.views,forms.created_at,COUNT(entries.id) as entries FROM `{$wpdb->prefix}bitforms_form` as forms LEFT JOIN `{$wpdb->prefix}bitforms_form_entries` as entries ON forms.id = entries.form_id GROUP BY forms.id");

    if (is_wp_error($allForms)) {
      return $allForms;
    }
    $forms = array_reduce($allForms, function ($carry, $form) {
      $carry[$form->id] = $form->form_name . ' (' . $form->id . ')';
      return $carry;
    }, []);

    if (!empty($forms)) {
      $forms[0] = __('Select a Bitform', 'bit-form');
    }
    return $forms;
  }

  /**
   * Request data formatting.
   *
   * Receives raw data from ajax request and formats it into an object.
   * @param mixed $data
   * @return object
   */
  public static function formatRequestData(): object
  {
    $data = $_POST['data'] ?? null;
    if (null === $data) {
      throw new \InvalidArgumentException('The "data" parameter is required.');
    }

    if (is_array($data)) {
      return (object) $data;
    }
    // Unslash input
    $userData = wp_unslash($data);
    return json_decode($userData);

    // // Normalize to array
    // if (is_string($userData)) {
    //   $decoded = json_decode($userData, true);
    //   if (JSON_ERROR_NONE !== json_last_error()) {
    //     throw new \InvalidArgumentException('Invalid JSON provided in data.');
    //   }
    //   $userData = $decoded ?? [];
    // } elseif (is_object($userData)) {
    //   $userData = (array) $userData;
    // }

    // if (!is_array($userData)) {
    //   // You may want to throw here instead of returning empty object
    //   return new \stdClass();
    // }

    // // Recursive sanitizer — always returns objects
    // $sanitizeRecursive = function ($value) use (&$sanitizeRecursive) {
    //   if (is_array($value)) {
    //     $clean = new \stdClass();
    //     foreach ($value as $k => $v) {
    //       $clean->$k = $sanitizeRecursive($v);
    //     }
    //     return $clean;
    //   } elseif (is_object($value)) {
    //     $clean = new \stdClass();
    //     foreach ($value as $k => $v) {
    //       $clean->$k = $sanitizeRecursive($v);
    //     }
    //     return $clean;
    //   } else {
    //     return sanitize_text_field($value);
    //   }
    // };

    // return $sanitizeRecursive($userData); // already an object
  }

  public static function requirePostMethod(): void
  {
    if (!isset($_SERVER['REQUEST_METHOD']) || 'POST' !== sanitize_text_field(wp_unslash($_SERVER['REQUEST_METHOD']))) {
      Log::debug_log('Invalid request method. POST required.');
      wp_send_json_error(__('Invalid request method. POST required.', 'bit-form'), 405);
      return;
    }
  }
}
