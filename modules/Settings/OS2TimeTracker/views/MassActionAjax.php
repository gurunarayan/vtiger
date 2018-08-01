<?php
/*+***********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 *************************************************************************************/

class Settings_OS2TimeTracker_MassActionAjax_View extends Settings_Vtiger_IndexAjax_View {

    function __construct() {
        parent::__construct();
        $this->exposeMethod('getTimeTrackerPopup');
        $this->exposeMethod('getTimeTrackerPopupForAll');
        //$this->exposeMethod('getStartTime');
    }

    function checkPermission(Vtiger_Request $request) {
		return;
	}
	
	/*function getStartTime(Vtiger_Request $request){
        $db = PearDatabase::getInstance();
		$currentUser = Users_Record_Model::getCurrentUserModel();
		$timezone = $currentUser->get('time_zone');
		$timeformat = $request->get('timeFormat');
		//date_default_timezone_set($timezone);
		
		if($timeformat == 12){
			$date = date("d-m-Y h:i a");
		}else{
			$date = date("d-m-Y H:i");
		}
		
		$response = new Vtiger_Response();
        $response->setResult($date);      
        $response->emit();
	}*/
        
	function getTimeTrackerPopup(Vtiger_Request $request){
        $db = PearDatabase::getInstance();
		
		$viewer = $this->getViewer($request);
		$currentUser = Users_Record_Model::getCurrentUserModel();
		
		$moduleName = $request->get('module');
        $record = $request->get('record');
        $recordModel = Vtiger_Record_Model::getInstanceById($record);
        $eventModule = Vtiger_Module_Model::getInstance('Events');
		
		$deleted_q = $db->pquery("SELECT * FROM timetracker_data LEFT JOIN vtiger_crmentity ON vtiger_crmentity.crmid = timetracker_data.userid WHERE deleted=1",array());
		$num_q = $db->num_rows($deleted_q);
		if($num_q > 0){
			for($i=0;$i<$num_q;$i++){
				$id = $db->query_result($deleted_q,$i,'recordid');
				$db->pquery("DELETE FROM timetracker_data WHERE recordid=?",array($id));
			}
		}

        $query = $db->pquery("SELECT fields FROM vtiger_timetracker_settings",array());
        $settingfields = unserialize(base64_decode($db->query_result($query,0,'fields')));
				
        //$query2 = $db->pquery("SELECT * FROM timetracker_data LEFT JOIN vtiger_crmentity ON vtiger_crmentity.crmid = timetracker_data.recordid WHERE recordid=? AND userid=? AND vtiger_crmentity.deleted=0 ",array($record, $currentUser->get('id')));
        $query2 = $db->pquery("SELECT * FROM timetracker_data WHERE recordid=? AND userid=? ",array($record, $currentUser->get('id')));
        $row2 = $db->num_rows($query2);
        $form_data = unserialize(base64_decode($db->query_result($query2, 0, 'form_data')));
        $recordId = $db->query_result($query2, 0, 'recordid');
        $tracker_status = $db->query_result($query2, 0, 'status');
        $record_name = $db->query_result($query2, 0, 'record_name');

        $record_running = array();
        if($record != $recordId && $row2 > 0){
            $record_running['record'] = $recordId;
            $record_running['form_data'] = $form_data;
            $record_running['status'] = $tracker_status;
            $record_running['name'] = $record_name;

            if($tracker_status == 'running'){
                $viewer->assign('RECORD_RUNNING', $record_running);
            }
        }

 
        if($record == $recordId){
            $viewer->assign('STATUS', $tracker_status);
        }

        //$query3 = $db->pquery("SELECT * FROM timetracker_data LEFT JOIN vtiger_crmentity ON vtiger_crmentity.crmid = timetracker_data.recordid WHERE status IN('running','pause') AND userid=? AND vtiger_crmentity.deleted=0",array($currentUser->get('id')));
        $query3 = $db->pquery("SELECT * FROM timetracker_data WHERE status IN('running','pause') AND userid=? ",array($currentUser->get('id')));
        $rows3 = $db->num_rows($query3);
        for($i=0; $i<$rows3; $i++){
            $recordid = $db->query_result($query3, $i, 'recordid');
            $formdata = unserialize(base64_decode($db->query_result($query3, $i, 'form_data')));
            $status = $db->query_result($query3, $i, 'status');
            $name = $db->query_result($query3, $i, 'record_name');

            $val = $formdata['timeTrackerTotal'];
            $time = gmdate("H:i:s", (int)$val);

            $activetimer[$i] = ['recordid'=> $recordid, 'formdata'=>$formdata, 'status'=>$status, 'name'=>$name, 'timeTrackerTotal' => $time];
        }

        //$query4 = $db->pquery("SELECT * FROM timetracker_data LEFT JOIN vtiger_crmentity ON vtiger_crmentity.crmid = timetracker_data.recordid WHERE recordid=? AND userid=? AND vtiger_crmentity.deleted=0",array($record,$currentUser->get('id')));
        $query4 = $db->pquery("SELECT * FROM timetracker_data WHERE recordid=? AND userid=?",array($record,$currentUser->get('id')));
        $form_data_4 = unserialize(base64_decode($db->query_result($query4, 0, 'form_data')));
        $recordId_4 = $db->query_result($query4, 0, 'recordid');
        $tracker_status_4 = $db->query_result($query4, 0, 'status');
        $record_name_4 = $db->query_result($query4, 0, 'record_name');

        $viewer->assign('RECORD_RUNNING_NAME', $record_name_4);
        $viewer->assign('FORM_DATA', $form_data_4);

        $viewer->assign('ACTIVE_TIMER', $activetimer);
        


        $viewer->assign('EVENT_MODEL', $eventModule);
        $viewer->assign('RECORD_MODEL', $recordModel);
		$viewer->assign('MODULE', $moduleName);
        $viewer->assign('USER_MODEL', $currentUser);
        $viewer->assign('RECORD_ID', $record);
        $viewer->assign('SETTING_FIELD', $settingfields);

    

        $content = $viewer->view('TimeTrackerPopup.tpl', 'Settings:OS2TimeTracker',true);
        
        $response = new Vtiger_Response();
        $response->setResult($content);
        $response->emit();
    }
    
    function getTimeTrackerPopupForAll(Vtiger_Request $request){

        $db = PearDatabase::getInstance();
		$viewer = $this->getViewer($request);
		$currentUser = Users_Record_Model::getCurrentUserModel();
		$record = $request->get('record');
		
        //$query2 = $db->pquery("SELECT * FROM timetracker_data LEFT JOIN vtiger_crmentity ON vtiger_crmentity.crmid = timetracker_data.recordid WHERE recordid=? AND userid=? AND vtiger_crmentity.deleted=0",array($record, $currentUser->get('id')));
        $query2 = $db->pquery("SELECT * FROM timetracker_data WHERE recordid=? AND userid=? ",array($record, $currentUser->get('id')));
        $row2 = $db->num_rows($query2);
        $form_data = unserialize(base64_decode($db->query_result($query2, 0, 'form_data')));
        $recordId = $db->query_result($query2, 0, 'recordid');
        $tracker_status = $db->query_result($query2, 0, 'status');
        $record_name = $db->query_result($query2, 0, 'record_name');

        $record_running = array();
        if($record != $recordId && $row2 > 0){
            $record_running['record'] = $recordId;
            $record_running['form_data'] = $form_data;
            $record_running['status'] = $tracker_status;
            $record_running['name'] = $record_name;

            if($tracker_status == 'running'){
                $viewer->assign('RECORD_RUNNING', $record_running);
            }
        }

 
        if($record == $recordId){
            $viewer->assign('STATUS', $tracker_status);
        }

        //$query3 = $db->pquery("SELECT * FROM timetracker_data LEFT JOIN vtiger_crmentity ON vtiger_crmentity.crmid = timetracker_data.recordid WHERE status IN('running','pause') AND userid=? AND vtiger_crmentity.deleted=0",array($currentUser->get('id')));
        $query3 = $db->pquery("SELECT * FROM timetracker_data WHERE status IN('running','pause') AND userid=? ",array($currentUser->get('id')));
        $rows3 = $db->num_rows($query3);
        for($i=0; $i<$rows3; $i++){
            $recordid = $db->query_result($query3, $i, 'recordid');
            $formdata = unserialize(base64_decode($db->query_result($query3, $i, 'form_data')));
            $status = $db->query_result($query3, $i, 'status');
            $name = $db->query_result($query3, $i, 'record_name');

            $val = $formdata['timeTrackerTotal'];
            $time = gmdate("H:i:s", (int)$val);

            $activetimer[$i] = ['recordid'=> $recordid, 'formdata'=>$formdata, 'status'=>$status, 'name'=>$name, 'timeTrackerTotal' => $time];
        }

        //$query4 = $db->pquery("SELECT * FROM timetracker_data LEFT JOIN vtiger_crmentity ON vtiger_crmentity.crmid = timetracker_data.recordid WHERE recordid=? AND userid=? AND vtiger_crmentity.deleted=0",array($record,$currentUser->get('id')));
        $query4 = $db->pquery("SELECT * FROM timetracker_data WHERE recordid=? AND userid=? ",array($record,$currentUser->get('id')));
        $form_data_4 = unserialize(base64_decode($db->query_result($query4, 0, 'form_data')));

        $viewer->assign('FORM_DATA', $form_data_4);

        $viewer->assign('ACTIVE_TIMER', $activetimer);
        $viewer->assign('RECORD_ID', $record);
       
        $content = $viewer->view('TimeTrackerPopupForAll.tpl', 'Settings:OS2TimeTracker',true);     
        $response = new Vtiger_Response();
        $response->setResult($content);      
        $response->emit();
	}
}
