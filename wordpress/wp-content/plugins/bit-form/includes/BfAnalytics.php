<?php

namespace BitCode\BitForm;

use BitCode\BitForm\BitApps\WPTelemetry\Telemetry\Telemetry;

if (!\defined('ABSPATH')) {
  exit;
}
class BfAnalytics
{
  private $allForms;

  public function __construct()
  {
    global $wpdb;
    $this->allForms = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}bitforms_form"); // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
  }

  public function modifyTelemetryData()
  {
    $bfInfo = [];
    $formsArr = [];
    foreach ($this->allForms as $form) {
      $formData = [];
      $formData['id'] = $form->id;
      $formContent = json_decode($form->form_content, true);
      $formData['fields'] = $this->fieldCount($formContent['fields']);
      $formData['workflows'] = $this->countRow($form->id, 'bitforms_workflows');
      $formData['pdfTemplate'] = $this->countRow($form->id, 'bitforms_pdf_template');
      $formData['emailTemplate'] = $this->countRow($form->id, 'bitforms_email_template');
      $formData['integrations'] = $this->getIntegrations($form->id);
      $formData['formAbandonment'] = $this->getFormAbandonment($form->id);
      $formData['doubleOptin'] = $this->getDoubleOptin($form->id);
      $formData['tableView'] = $this->countRow($form->id, 'bitforms_frontend_views');
      $formsArr[] = $formData;
    }
    $bfInfo['forms'] = $formsArr;
    $bfInfo['totalForms'] = count($this->allForms);
    $bfInfo['reCaptchaV3'] = $this->getReCaptchaV3();
    $bfInfo['paymentGateway'] = $this->getPaymentGateway();
    $bfInfo['paymentInfo'] = $this->getPaymentInfo();
    $bfInfo['smtp'] = $this->isSMTPExist();

    return $bfInfo;
  }

  public function analyticsOptIn($permission)
  {
    if (true === $permission) {
      Telemetry::report()->trackingOptIn();
      return true;
    }
    Telemetry::report()->trackingOptOut();
    return false;
  }

  public function isTrackingEnabled()
  {
    return (bool) Telemetry::report()->isTrackingAllowed();
  }

  private function fieldCount($fields)
  {
    $count = [];
    foreach ($fields as $field) {
      if (isset($count[$field['typ']])) {
        $count[$field['typ']]++;
      } else {
        $count[$field['typ']] = 1;
      }
    }
    return $count;
  }

  private function countRow($formId, $tablename)
  {
    global $wpdb;
    $safeTable = esc_sql($tablename);
    $totalRow = $wpdb->get_results(
      $wpdb->prepare(
        "SELECT COUNT(*) as count FROM `{$wpdb->prefix}{$safeTable}` WHERE form_id = %d;",
        $formId
      )
    );
    return $totalRow[0]->count ?? 0;
  }

  private function getIntegrations($formId)
  {
    global $wpdb;
    $integrations = $wpdb->get_results(
      $wpdb->prepare(
        "SELECT integration_type FROM `{$wpdb->prefix}bitforms_integration` WHERE form_id = %d AND category='form';",
        $formId
      )
    );

    $integrationNameArr = array_map(function ($integration) {
      return $integration->integration_type;
    }, $integrations);

    return $integrationNameArr;
  }

  private function getFormAbandonment($formId)
  {
    global $wpdb;
    $formAbandonment = $wpdb->get_results(
      $wpdb->prepare(
        "SELECT integration_details FROM `{$wpdb->prefix}bitforms_integration` WHERE form_id = %d AND category='formAbandonment';",
        $formId
      )
    );
    if (count($formAbandonment) > 0) {
      $formAbandonment = json_decode($formAbandonment[0]->integration_details, true);
    } else {
      $formAbandonment = (object)[];
    }
    return $formAbandonment;
  }

  private function getDoubleOptin($formId)
  {
    global $wpdb;
    $doubleOptin = $wpdb->get_results(
      $wpdb->prepare(
        "SELECT integration_details FROM `{$wpdb->prefix}bitforms_integration` WHERE category='doubleOptin' AND form_id = %d;",
        $formId
      )
    );
    if (count($doubleOptin) > 0) {
      return true;
    }
    return false;
  }

  private function getIntegType($integration_type)
  {
    global $wpdb;
    return $wpdb->get_results(
      $wpdb->prepare(
        "SELECT integration_type, integration_details FROM `{$wpdb->prefix}bitforms_integration` WHERE integration_type=%s;",
        $integration_type
      )
    );
  }

  private function getReCaptchaV3()
  {
    $name = $this->getIntegType('gReCaptchaV3');
    if (count($name) > 0) {
      return true;
    }
    return false;
  }

  private function getPaymentGateway()
  {
    $types = $this->getIntegType('payments');

    $paymentTypeArr = array_map(function ($type) {
      $integration_details = json_decode($type->integration_details, true);
      return $integration_details['type'];
    }, $types);

    return $paymentTypeArr;
  }

  private function isSMTPExist()
  {
    $type = $this->getIntegType('smtp');

    if (count($type) > 0) {
      return true;
    }
    return false;
  }

  private function getPaymentInfo()
  {
    if (!defined('BITFORMPRO_PLUGIN_DIR')) {
      return [];
    }

    global $wpdb;

    // Fetch all transactions
    // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
    $allTransactions = $wpdb->get_results(
      "SELECT * FROM `{$wpdb->prefix}bitforms_payments`;"
    );

    if (empty($allTransactions)) {
      return [];
    }

    $result = [
      'totalPaymentFormEntries' => 0,
      'payments'                => [],
      'totalForm'               => 0,
    ];

    $tempTotalForms = [];

    foreach ($allTransactions as $trx) {
      $gateway = strtolower($trx->payment_name);

      $paymentResponse = json_decode($trx->payment_response, true) ?? [];

      if (false === in_array($trx->form_id, $tempTotalForms)) {
        $tempTotalForms[] = $trx->form_id;
      }

      // Initialize gateway
      if (!isset($result['payments'][$gateway])) {
        $result['payments'][$gateway] = [
          'totalTransaction'   => 0,
          'transactionDetails' => []
        ];
      }

      $gatewayRef = &$result['payments'][$gateway];

      if (!$this->isLiveMode($gateway, $paymentResponse)) {
        continue;
      }
      $gatewayRef['totalTransaction'] += 1;
      // Determine amount and currency
      [$amount, $currency] = $this->extractAmountAndCurrency($gateway, $paymentResponse);

      // Add each transaction as a new object in the array
      $gatewayRef['transactionDetails'][] = [
        'amount'           => $amount,
        'currency'         => $currency
      ];
      $result['totalPaymentFormEntries'] += 1;
    }

    $result['totalForm'] = count($tempTotalForms);

    return $result;
  }

  /**
   * Extracts amount and currency from a payment response for any gateway
   */
  private function extractAmountAndCurrency($gateway,  $response)
  {
    $amount = 0;
    $currency = 'unknown';

    switch ($gateway) {
      case 'stripe':
        $amount = isset($response['amount']) && is_numeric($response['amount'])
            ? $response['amount'] / 100
            : 0;
        $currency = strtolower($response['currency'] ?? 'usd');
        break;

      case 'razorpay':
        $amount = isset($response['amount']) && is_numeric($response['amount'])
            ? $response['amount'] / 100
            : 0;
        $currency = strtolower($response['currency'] ?? 'inr');
        break;

      case 'paypal':
        $unit = $response['purchase_units'][0]['amount'] ?? [];
        $amount = floatval($unit['value'] ?? 0);
        $currency = strtolower($unit['currency_code'] ?? 'usd');
        break;

      case 'mollie':
        $amount = floatval($response['amount']['value'] ?? 0);
        $currency = strtolower($response['amount']['currency'] ?? 'eur');
        break;
    }

    return [$amount, $currency];
  }

  private function isLiveMode(string $gateway, array $response): bool
  {
    switch ($gateway) {
      case 'stripe':
        return !empty($response['livemode']);

      case 'razorpay':
        return isset($response['status']) && 'captured' === $response['status'];

      case 'paypal':
        return isset($response['status']) && 'COMPLETED' === $response['status'];

      case 'mollie':
        return isset($response['mode']) && 'live' === $response['mode'];

      default:
        return false;
    }
  }
}
