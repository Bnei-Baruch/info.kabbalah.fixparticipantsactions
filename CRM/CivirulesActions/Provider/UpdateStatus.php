<?php
class CRM_CivirulesActions_Provider_UpdateStatus extends CRM_Civirules_Action {
  // SQL:
  // INSERT INTO civirule_action (name, label, class_name, is_active) 
  // VALUES("participant_update_status", "Update Participant Status", "CRM_CivirulesActions_Provider_UpdateStatus", 1)

  /**
   * Method processAction to execute the action
   *
   * @param CRM_Civirules_TriggerData_TriggerData $triggerData
   * @access public
   * 105610
   */
  public function processAction(CRM_Civirules_TriggerData_TriggerData $triggerData) {
    try {
	    $triggerContribution = $triggerData->getEntityData('Contribution');
	    $contribution_id = $triggerContribution['contribution_id'];
	    $result = civicrm_api3('ParticipantPayment', 'get', [
		  'sequential' => 1,
		  'return' => ['participant_id'],
		  'contribution_id' => $contribution_id,
	    ]);
	    foreach ($result['values'] as $participant) {
		$participant_id = $participant['participant_id'];
		$registered = civicrm_api3('Participant', 'create', [
			'id' => $participant_id,
			'status_id' => 'Registered',
		]);
	    }
    } catch (Exception $e) {
	    CRM_Core_Error::debug_log_message("Exception: " . $e->getMessage());
    }

  }

  /**
   * Method to return the url for additional form processing for action
   * and return false if none is needed
   *
   * @param int $ruleActionId
   * @return bool
   * @access public
   */
  public function getExtraDataInputUrl($ruleActionId) {
    return FALSE;
  }

}

