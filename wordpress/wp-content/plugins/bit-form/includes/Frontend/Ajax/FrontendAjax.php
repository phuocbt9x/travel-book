<?php

namespace BitCode\BitForm\Frontend\Ajax;

if (!defined('ABSPATH')) {
  exit;
}

use BitCode\BitForm\Admin\Form\AdminFormManager;
use BitCode\BitForm\Admin\Form\Helpers;
use BitCode\BitForm\Core\Database\FormEntryLogModel;
use BitCode\BitForm\Core\Database\FormEntryMetaModel;
use BitCode\BitForm\Core\Database\FormEntryModel;
use BitCode\BitForm\Core\Util\FieldValueHandler;
use BitCode\BitForm\Core\Util\FrontendHelpers;
use BitCode\BitForm\Core\Util\Log;
use BitCode\BitForm\Core\Util\MailNotifier;
use BitCode\BitForm\Core\WorkFlow\WorkFlow;
use BitCode\BitForm\Frontend\Form\FrontendFormManager;
use WP_Error;

final class FrontendAjax
{
  public function register()
  {
    add_action('wp_ajax_nopriv_bitforms_submit_form', [$this, 'submit_form']);
    add_action('wp_ajax_bitforms_submit_form', [$this, 'submit_form']);
    add_action('wp_ajax_bitforms_entry_update', [$this, 'update_entry']);
    add_action('wp_ajax_nopriv_bitforms_entry_update', [$this, 'update_entry']);
    add_action('wp_ajax_bitforms_update_form_entry', [$this, 'update_entry']);
    add_action('wp_ajax_nopriv_bitforms_update_form_entry', [$this, 'update_entry']);
    add_action('wp_ajax_bitforms_before_submit_validate', [$this, 'beforeSubmittedValidate']);
    add_action('wp_ajax_nopriv_bitforms_before_submit_validate', [$this, 'beforeSubmittedValidate']);
    add_action('wp_ajax_nopriv_bitforms_trigger_workflow', [$this, 'triggerWorkFlow']);
    add_action('wp_ajax_bitforms_trigger_workflow', [$this, 'triggerWorkFlow']);
    add_action('wp_ajax_bitforms_onload_added_field_and_property', [$this, 'addHiddenFieldAndProperty']);
    add_action('wp_ajax_nopriv_bitforms_onload_added_field_and_property', [$this, 'addHiddenFieldAndProperty']);
  }

  public function beforeSubmittedValidate()
  {
    // phpcs:ignore WordPress.Security.NonceVerification.Missing -- Need form ID from POST before nonce can be verified (nonce action is per-form 'bitforms_' . $formId). Custom CSRF verified later via verifySubmissionNonce().
    $form_id = isset($_POST['bitforms_id']) ? str_replace('bitforms_', '', sanitize_text_field(wp_unslash($_POST['bitforms_id']))) : '';
    $FrontendFormManager = FrontendFormManager::getInstance($form_id);
    $FrontendFormManager->fieldNameReplaceOfPost();
    $validateStatus = $FrontendFormManager->beforeSubmittedValidate(false);
    if (is_wp_error($validateStatus)) {
      wp_send_json_error($validateStatus->get_error_message(), 400);
    } else {
      wp_send_json_success($validateStatus);
    }
  }

  public function submit_form()
  {
    \ignore_user_abort();
    // phpcs:ignore WordPress.Security.NonceVerification.Missing -- Need form ID from POST before nonce can be verified (nonce action is per-form 'bitforms_' . $formId). Custom CSRF verified later via verifySubmissionNonce().
    $form_id = isset($_POST['bitforms_id']) ? str_replace('bitforms_', '', sanitize_text_field(wp_unslash($_POST['bitforms_id']))) : '';
    $FrontendFormManager = FrontendFormManager::getInstance($form_id);
    $submitSatus = $FrontendFormManager->handleSubmission();
    if (is_wp_error($submitSatus)) {
      do_action('bitform_submit_error', $form_id, $submitSatus);
      wp_send_json_error($submitSatus->get_error_message(), 400);
    } else {
      wp_send_json_success($submitSatus);
    }
  }

  public function update_entry()
  {
    \ignore_user_abort();
    $form_id = isset($_POST['bitforms_id']) ? str_replace('bitforms_', '', sanitize_text_field(wp_unslash($_POST['bitforms_id']))) : '';
    $entryId = isset($_REQUEST['entryID']) ? sanitize_text_field(wp_unslash($_REQUEST['entryID'])) : '';
    $entryToken = isset($_REQUEST['entryToken']) ? sanitize_text_field(wp_unslash($_REQUEST['entryToken'])) : '';
    // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
    $GLOBALS['bf_entry_id'] = $entryId;
    if (Helpers::validateEntryTokenAndUser($entryToken, $entryId) || FrontendHelpers::is_current_user_can_access($form_id, 'entryEditAccess')) {
      $FrontendFormManager = FrontendFormManager::getInstance($form_id);
      $updateStatus = $FrontendFormManager->handleUpdateEntry();
      if (is_wp_error($updateStatus)) {
        do_action('bitform_update_error', $form_id, $updateStatus);
        wp_send_json_error($updateStatus->get_error_message(), 400);
      } else {
        wp_send_json_success($updateStatus);
      }
    } else {
      wp_send_json_error('Entry Token or User is not Authorized', 401);
    }
  }

  public function hiddenFields($formId)
  {
    $tokens = Helpers::csrfEecrypted();
    $fields = [
      [
        'name'  => 'csrf',
        'value' => $tokens['csrf'],
      ],
      [
        'name'  => 't_identity',
        'value' => $tokens['t_identity'],
      ]
    ];
    $frontendFormManger = FrontendFormManager::getInstance($formId);
    if ($frontendFormManger->isHoneypotActive()) {
      $time = time();
      $honeypodFldName = Helpers::honeypotEncryptedToken("_bitforms_{$formId}_{$time}_");
      $fields[] = [
        'name'  => 'b_h_t',
        'value' => $honeypodFldName,
      ];
    }
    return $fields;
  }

  public function hiddenPropeties($formId)
  {
    $properties = [];
    $properties[] = [
      'name'  => 'nonce',
      'value' => wp_create_nonce('bitforms_' . $formId),
    ];
    return $properties;
  }

  public function addHiddenFieldAndProperty()
  {
    \ignore_user_abort();
    $request = file_get_contents('php://input');
    if ($request) {
      $data = is_string($request) ? \json_decode($request) : $request;
      if (!isset($data->formId)) {
        wp_send_json_error('Form Id not found', 400);
      } else {
        $fields = $this->hiddenFields($data->formId);
        $properties = $this->hiddenPropeties($data->formId);
        wp_send_json_success(['hidden_fields'=>$fields, 'hidden_properties'=>$properties]);
      }
    }
  }

  public function triggerWorkFlow()
  {
    \ignore_user_abort(true);

    $inputJSON = file_get_contents('php://input');

    if ($inputJSON) {
      $request = is_string($inputJSON) ? \json_decode($inputJSON) : $inputJSON;
      $submitted_fields = [];
      if (isset($request->id, $request->cronNotOk)) {
        $formID = str_replace('bitforms_', '', $request->id);
        $cronNotOk = $request->cronNotOk;

        // Validate and sanitize entry ID and log ID
        if (!isset($cronNotOk[0]) || !is_numeric($cronNotOk[0]) || !isset($cronNotOk[1]) || !is_numeric($cronNotOk[1])) {
          Log::debug_log('Invalid cronNotOk data for formID=' . $formID);
          wp_send_json_error(['message' => 'Invalid request data'], 400);
        }

        $entryID = absint($cronNotOk[0]);
        $logID = absint($cronNotOk[1]);
        // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
        $GLOBALS['bf_entry_id'] = $entryID;
        $entryLog = new FormEntryLogModel();

        // Quick admin check to allow retry of workflows
        $isAdmin = false;
        if (is_user_logged_in()) {
          $user = wp_get_current_user();
          $isAdmin = in_array('administrator', $user->roles) || current_user_can('manage_bitform');
        }

        // Check if already processed (skip for administrators to allow retries)
        if (!$isAdmin) {
          if (isset($cronNotOk[2]) && \is_int($cronNotOk[2])) {
            $queueudEntry = $entryLog->get(
              'response_obj',
              ['id' => $cronNotOk[2]]
            );
            if ($queueudEntry) {
              if (!empty($queueudEntry[0]->response_obj) && \strpos($queueudEntry[0]->response_obj, 'processed') > 0) {
                Log::debug_log('Cron Not Ok[2] Already Processed');
                wp_send_json_error();
              }
            } else {
              Log::debug_log('Cron Not Ok[2] Query Entry data not found');
              wp_send_json_error();
            }
          } else {
            Log::debug_log('Cron Not Ok[2](Log Id) data not found');
            wp_send_json_error();
          }
        } else {
          Log::debug_log('Admin bypass: Skipping "already processed" check for workflow retry');
        }

        // SECURITY CHECK: Validate trigger token using helper function
        $validation = Helpers::validateWorkflowTriggerToken($request, $formID);

        if (!$validation['valid']) {
          wp_send_json_error(['message' => $validation['error']], 403);
        }

        // Use validated trigger data if available (prevents transient overwrite bug)
        $triggerData = null;
        if ($validation['triggerData']) {
          // Token was valid and transient data retrieved
          $triggerData = $validation['triggerData'];
        } else {
          // Admin bypass or transient not found - fetch from transient/database
          $trnasientData = get_transient("bitform_trigger_transient_{$entryID}");

          if (!empty($trnasientData)) {
            delete_transient("bitform_trigger_transient_{$entryID}");
            $triggerData = is_string($trnasientData) ? json_decode($trnasientData) : $trnasientData;
          } else {
            $formManager = new AdminFormManager($formID);
            if (!$formManager->isExist()) {
              Log::debug_log('provided form does not exists');
              return wp_send_json(new WP_Error('trigger_empty_form', __('provided form does not exists', 'bit-form')));
            }
            $formEntryModel = new FormEntryModel();
            $entryMeta = new FormEntryMetaModel();

            $formEntry = $formEntryModel->get(
              '*',
              [
                'form_id' => $formID,
                'id'      => $entryID,
              ]
            );

            if (!$formEntry) {
              Log::debug_log('provided form entries does not exists. EntryId=' . $entryID . ', FormId=' . $formID);
              return new WP_Error('trigger_empty_form', __('provided form entries does not exists', 'bit-form'));
            }
            $formEntryMeta = $entryMeta->get(
              [
                'meta_key',
                'meta_value',
              ],
              [
                'bitforms_form_entry_id' => $entryID,
              ]
            );
            $entries = [];
            foreach ($formEntryMeta as $key => $value) {
              $entries[$value->meta_key] = $value->meta_value;
            }
            $formContent = $formManager->getFormContent();
            $submitted_fields = $formContent->fields;
            foreach ($submitted_fields as $key => $value) {
              if (isset($entries[$key])) {
                $submitted_fields->{$key}->val = $entries[$key];
                $submitted_fields->{$key}->name = $key;
              }
            }

            $workFlowRunHelper = new WorkFlow($formID);
            $workFlowreturnedOnSubmit = $workFlowRunHelper->executeOnSubmit(
              'create',
              $submitted_fields,
              $entries,
              $entryID,
              $logID
            );

            $triggerData = isset($workFlowreturnedOnSubmit['triggerData']) ? $workFlowreturnedOnSubmit['triggerData'] : null;
            $triggerData['fields'] = $entries;
          }
        } // Close else block

        if (!empty($triggerData)) {
          if (isset($triggerData['integrationRun']) && !$triggerData['integrationRun']) {
            $entryModel = new FormEntryModel();
            $updatedStatus = $entryModel->update(
              [
                'status' => 2,
              ],
              [
                'form_id' => $triggerData['formID'],
                'id'      => $entryID,
              ]
            );
            if (is_wp_error($updatedStatus)) {
              wp_send_json_error($updatedStatus->get_error_message(), 411);
            } else {
              if ($triggerData['dbl_opt_dflt_template']) {
                do_action('bf_double_optin_confirmation', $triggerData['dbl_opt_donf'], $triggerData); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound
              } elseif (isset($triggerData['dblOptin'])) {
                foreach ($triggerData['dblOptin'] as $value) {
                  MailNotifier::notify($value, $triggerData['formID'], $triggerData['fields'], $entryID, true, $logID);
                }
              }
              wp_send_json_success();
            }
          }

          if (isset($triggerData['mail'])) {
            $formManager = new AdminFormManager($formID);
            $formContent = $formManager->getFormContent();
            $submitted_fields = $formContent->fields;
            $fieldValueForMail = FieldValueHandler::formatFieldValueForMail($submitted_fields, $triggerData['fields']);
            foreach ($triggerData['mail'] as $value) {
              MailNotifier::notify($value, $triggerData['formID'], $fieldValueForMail, $entryID);
            }
          }

          do_action('bitforms_exec_integrations', $triggerData['integrations'], $triggerData['fields'], $triggerData['formID'], $triggerData['entryID'], $triggerData['logID']);
          if (isset($cronNotOk[2]) && \is_int($cronNotOk[2])) {
            $queueuEntry = $entryLog->update(
              [
                'response_type' => 'success',
                'response_obj'  => wp_json_encode(['status' => 'processed']),
              ],
              ['id' => $cronNotOk[2]]
            );
          }
        } else {
          Log::debug_log('No Trigger Data Found');
        }
      } else {
        Log::debug_log('Cron Not Ok data not found');
        wp_send_json_error('Cron Not Ok data found', 400);
      }
    } else {
      Log::debug_log('No Input data found');
      wp_send_json_error('Invalid Request', 400);
    }

    wp_send_json_success();
  }
}
