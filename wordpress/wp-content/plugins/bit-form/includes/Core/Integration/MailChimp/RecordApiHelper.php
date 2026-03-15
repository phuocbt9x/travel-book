<?php

/**
 * MailChimp Record Api
 *
 */

namespace BitCode\BitForm\Core\Integration\MailChimp;

use BitCode\BitForm\Core\Database\FormEntryLogModel;
use BitCode\BitForm\Core\Util\ApiResponse;
use BitCode\BitForm\Core\Util\HttpHelper;

/**
 * Provide functionality for Record insert,upsert
 */
class RecordApiHelper
{
  private $_defaultHeader;
  private $_tokenDetails;
  private $_integrationID;
  private $_logID;
  private $_logResponse;
  private $_entryID;

  public function __construct($tokenDetails, $integId, $logID, $entryID)
  {
    $this->_defaultHeader['Authorization'] = "Bearer {$tokenDetails->access_token}";
    $this->_defaultHeader['Content-Type'] = 'application/json';
    $this->_tokenDetails = $tokenDetails;
    $this->_integrationID = $integId;
    $this->_logID = $logID;
    $this->_logResponse = new ApiResponse();
    $this->_entryID = $entryID;
  }

  private function _apiEndPoint()
  {
    return "https://{$this->_tokenDetails->dc}.api.mailchimp.com/3.0";
  }

  public function insertRecord($listId, $data)
  {
    $insertRecordEndpoint = $this->_apiEndPoint() . "/lists/{$listId}/members";
    return HttpHelper::post($insertRecordEndpoint, $data, $this->_defaultHeader);
  }

  public function updateRecord($listId, $subscriberHash, $jsonData)
  {
    $updateRecordEndpoint = $this->_apiEndPoint() . "/lists/{$listId}/members/{$subscriberHash}";
    $response = HttpHelper::request($updateRecordEndpoint, 'PUT', $jsonData, $this->_defaultHeader);
    return $response;
  }

  public function existContact($listId, $queryParam)
  {
    $existSearchEnpoint = $this->_apiEndPoint() . "/search-members?query={$queryParam}&list_id={$listId}";
    return HttpHelper::get($existSearchEnpoint, $queryParam, $this->_defaultHeader);
  }

  public function executeRecordApi($listId, $tags, $defaultConf, $fieldValues, $fieldMap, $actions, $addressFields, $formId)
  {
    $fieldData = [];
    $mergeFields = [];
    foreach ($fieldMap as $fieldPair) {
      if (!empty($fieldPair->mailChimpField)) {
        if ('email_address' === $fieldPair->mailChimpField) {
          $fieldData['email_address'] = $fieldValues[$fieldPair->formField];
        } elseif ('custom' === $fieldPair->formField && isset($fieldPair->customValue)) {
          $mergeFields[$fieldPair->mailChimpField] = $fieldPair->customValue;
        } elseif ('BIRTHDAY' === $fieldPair->mailChimpField) {
          $mergeFields[$fieldPair->mailChimpField] = !empty($fieldValues[$fieldPair->formField]) ? gmdate('m/d', strtotime($fieldValues[$fieldPair->formField])) : '';
        } else {
          $mergeFields[$fieldPair->mailChimpField] = $fieldValues[$fieldPair->formField];
        }
      }
    }

    $doubleOptIn = !empty($actions->double_opt_in) && $actions->double_opt_in ? true : false;

    $fieldData['merge_fields'] = (object) $mergeFields;
    $fieldData['email_type'] = 'text';
    $fieldData['tags'] = !empty($tags) ? $tags : [];
    $fieldData['status'] = $doubleOptIn ? 'pending' : 'subscribed';
    $fieldData['double_optin'] = $doubleOptIn;

    $contactEmail = $fieldData['email_address'];
    $subscriberHash = md5(strtolower($contactEmail));

    $model = new FormEntryLogModel();

    $recordApiResponse = null;
    if ($this->_entryID) {
      $result = $model->entryLogCheck($this->_entryID, $this->_integrationID);
      if (!count($result) || isset($result->errors['result_empty'])) {
        if (!empty($actions->address)) {
          $fvalue = [];
          foreach ($addressFields as $key) {
            foreach ($fieldValues as $k => $v) {
              if ($key->formField === $k) {
                $fvalue[$key->mailChimpAddressField] = $v;
              }
            }
          }
          $fieldData['merge_fields']->ADDRESS = (object) $fvalue;
        }

        $recordApiResponse = $this->insertRecord($listId, wp_json_encode($fieldData));
        $type = 'insert';

        if (!empty($actions->update) && !empty($recordApiResponse->title) && 'Member Exists' === $recordApiResponse->title) {
          $foundContact = $this->existContact($listId, $contactEmail);
          if (count($foundContact->exact_matches->members)) {
            $recordApiResponse = $this->updateRecord($listId, $subscriberHash, wp_json_encode($fieldData));
            $type = 'update';
          }
        }
      } else {
        if (!empty($actions->address)) {
          $fvalue = [];
          foreach ($addressFields as $key) {
            foreach ($fieldValues as $k => $v) {
              if ($key->formField === $k) {
                $fvalue[$key->mailChimpAddressField] = $v;
              }
            }
          }
          $fieldData['merge_fields']->ADDRESS = (object) $fvalue;
        }
        $recordApiResponse = $this->updateRecord($listId, $subscriberHash, wp_json_encode($fieldData));
        $type = 'update';
      }

      $entryDetails = [
        'formId'      => $formId,
        'entryId'     => $this->_entryID,
        'fieldValues' => $fieldValues
      ];

      if (400 === $recordApiResponse->status) {
        $this->_logResponse->apiResponse($this->_logID, $this->_integrationID, ['type' =>  'record', 'type_name' => $type], 'error', $recordApiResponse, $entryDetails);
      } else {
        $responseInfo = (object) [
          'success'       => true,
          'id'            => $recordApiResponse->id,
          'status'        => $recordApiResponse->status,
          'email_address' => $recordApiResponse->email_address,
        ];
        $this->_logResponse->apiResponse($this->_logID, $this->_integrationID, ['type' =>  'record', 'type_name' => $type], 'success', $responseInfo, $entryDetails);
      }
    }

    return $recordApiResponse;
  }
}
