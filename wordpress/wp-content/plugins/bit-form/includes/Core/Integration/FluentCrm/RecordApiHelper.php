<?php

/**
 * Fluent CRM Record Api
 *
 */

namespace BitCode\BitForm\Core\Integration\FluentCrm;

use BitCode\BitForm\Core\Util\ApiResponse as UtilApiResponse;
use FluentCrm\App\Models\Subscriber;
use FluentCrm\Includes\Helpers\Arr;

/**
 * Provide functionality for Record insert
 */
class RecordApiHelper
{
  private $_integrationID;
  private $_logID;
  private $_logResponse;

  private $_entryID;

  public function __construct($integId, $logID, $entryID)
  {
    $this->_integrationID = $integId;
    $this->_logID = $logID;
    $this->_logResponse = new UtilApiResponse();
    $this->_entryID = $entryID;
  }

  public function insertRecord($data, $actions)
  {
    // $contact = Arr::only($data, ['first_name', 'last_name', 'email']);

    $contact = $data;
    unset($contact['list_id'], $contact['tags']);

    // for full name
    if (!$contact['first_name'] && !$contact['last_name']) {
      $fullName = Arr::get($data, 'full_name');
      if ($fullName) {
        $nameArray = explode(' ', $fullName);
        if (count($nameArray) > 1) {
          $contact['last_name'] = array_pop($nameArray);
          $contact['first_name'] = implode(' ', $nameArray);
        } else {
          $contact['first_name'] = $fullName;
        }
      }
    }

    // for exsist user
    $subscriber = Subscriber::where('email', $contact['email'])->first();

    if ($subscriber && isset($actions->skip_if_exists) && $actions->skip_if_exists) {
      $response = [
        'success'  => false,
        'messages' => 'Contact already exists!'
      ];
    } else {
      $hasDoubleOptIn = isset($actions->double_opt_in) && $actions->double_opt_in;
      $forceSubscribed = false;

      if (!$subscriber) {
        $contact['status'] = $hasDoubleOptIn ? 'pending' : 'subscribed';
      } else {
        $forceSubscribed = !$hasDoubleOptIn && ('subscribed' !== $subscriber->status);
        if ($forceSubscribed) {
          $contact['status'] = 'subscribed';
        }
      }

      if ($listId = Arr::get($data, 'list_id')) {
        $contact['lists'] = [$listId];
      }
      $contact['tags'] = $data['tags'];

      $isNewSubscriber = !$subscriber;
      $subscriber = FluentCrmApi('contacts')->createOrUpdate($contact, $forceSubscribed, false);

      $shouldSendDoubleOptIn = $isNewSubscriber
        ? ('pending' === $subscriber->status)
        : ($hasDoubleOptIn && in_array($subscriber->status, ['pending', 'unsubscribed'], true));

      if ($shouldSendDoubleOptIn) {
        $subscriber->sendDoubleOptinEmail();
      }

      $response = $subscriber
        ? ['success' => true, 'messages' => 'Insert successfully!']
        : ['success' => false, 'messages' => 'Something wrong!'];
    }
    return $response;
  }

  public function executeRecordApi(
    $fieldValues,
    $fieldMap,
    $actions,
    $list_id,
    $tags,
    $formId
  ) {
    $fieldData = [];

    foreach ($fieldMap as $fieldPair) {
      if (!empty($fieldPair->fluentCRMField)) {
        if ('custom' === $fieldPair->formField && isset($fieldPair->customValue)) {
          $fieldData[$fieldPair->fluentCRMField] = $fieldPair->customValue;
        } else {
          $fieldData[$fieldPair->fluentCRMField] = $fieldValues[$fieldPair->formField];
        }
      }
    }
    $fieldData['list_id'] = $list_id;
    $fieldData['tags'] = $tags;

    $recordApiResponse = $this->insertRecord($fieldData, $actions);

    $entryDetails = [
      'formId'      => $formId,
      'entryId'     => $this->_entryID,
      'fieldValues' => $fieldValues
    ];

    if ($recordApiResponse['success']) {
      $this->_logResponse->apiResponse($this->_logID, $this->_integrationID, ['type' =>  'record', 'type_name' => 'insert'], 'success', $recordApiResponse, $entryDetails);
    } else {
      $this->_logResponse->apiResponse($this->_logID, $this->_integrationID, ['type' =>  'record', 'type_name' => 'insert'], 'error', $recordApiResponse, $entryDetails);
    }
    return $recordApiResponse;
  }
}
