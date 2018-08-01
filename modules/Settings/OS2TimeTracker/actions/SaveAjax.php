<?php
/*+***********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 *************************************************************************************/

require_once 'include/Webservices/Create.php';	

class Settings_OS2TimeTracker_SaveAjax_Action extends Settings_Vtiger_Basic_Action {

	function __construct() {
		$this->exposeMethod('getSelectedModules');
		$this->exposeMethod('createEvents');
		$this->exposeMethod('saveTrackerData');
		$this->exposeMethod('cancelTimer');
		$this->exposeMethod('deleteTimer');
	}
	function checkPermission(Vtiger_Request $request) {
		return;
	}

	public function process(Vtiger_Request $request) {
		$db = PearDatabase::getInstance();
		//$currentUser = Users_Record_Model::getCurrentUserModel();

		$mode = $request->getMode();
		if(!empty($mode) && $this->isMethodExposed($mode)) {
			$this->invokeExposedMethod($mode, $request);
			return;
		}
		
		$deleted_q = $db->pquery("SELECT * FROM timetracker_data LEFT JOIN vtiger_crmentity ON vtiger_crmentity.crmid = timetracker_data.userid WHERE deleted=1",array());
		$num_q = $db->num_rows($deleted_q);
		if($num_q > 0){
			for($i=0;$i<$num_q;$i++){
				$id = $db->query_result($deleted_q,$i,'recordid');
				$db->pquery("DELETE FROM timetracker_data WHERE recordid=?",array($id));
			}
		}
		
		$setting_array =base64_encode(serialize($request->get('field_settings')));
		
		$modules = base64_encode(serialize($request->get('selected_modules')));
		
		$query = $db->pquery("SELECT * FROM vtiger_timetracker_settings",array());
		if($db->num_rows($query) > 0) {
			$db->pquery('UPDATE vtiger_timetracker_settings SET modules=?, fields=?', array($modules, $setting_array));
		} else {
			$db->pquery('INSERT INTO vtiger_timetracker_settings (modules, fields) VALUES(?, ?)', array($modules, $setting_array));
		}
		
		$response = new Vtiger_Response();
		$response->setResult(array('success' => true));
		$response->emit();
	}

	public function getSelectedModules(Vtiger_Request $request) {
		$db = PearDatabase::getInstance();
		$moduleName = $request->get('module');
		
		$query = $db->pquery("SELECT modules FROM vtiger_timetracker_settings",array());
		$selectedModule = unserialize(base64_decode($db->query_result($query,0,'modules')));
		
		$response = new Vtiger_Response();
        $response->setResult($selectedModule);
        $response->emit();
	}

	/*public function createEvents(Vtiger_Request $request){
		$db = PearDatabase::getInstance();
		//$db->setDebug(true);
		
		$currentUser = Users_Record_Model::getCurrentUserModel();
		$usr_timezone = $currentUser->get('time_zone');
		
		$form_data = $request->get('form_data');
        
		$subject = $form_data['subject'];
		$module = $form_data['module'];
		$eventstatus = $form_data['eventstatus'];
		$activitytype = $form_data['activitytype'];
		$taskpriority = $form_data['taskpriority'];
		$visibility = $form_data['visibility'];
		$description = $form_data['description'];

                    
		$dateformat = $currentUser->get('date_format');
		$timeformat = $currentUser->get('hour_format');
		$startdate_arr = $form_data['startdate']; //02-07-2018 06:11 PM
		
		$sdate_arr = explode(' ',$startdate_arr);
		$start_date = $sdate_arr[0];
		$start_time = $sdate_arr[1];
		$s_am_pm = $sdate_arr[2];

		if($dateformat == 'mm-dd-yyyy'){
			$s_d_arr = explode('-',$start_date);
			$mon = $s_d_arr[0];
			$day = $s_d_arr[1];
			$year = $s_d_arr[2];
			$start_d_final = $year.'-'.$mon.'-'.$day;
			
		}elseif($dateformat == 'dd-mm-yyyy'){
			$s_d_arr = explode('-',$start_date);
			$day = $s_d_arr[0];
			$mon = $s_d_arr[1];
			$year = $s_d_arr[2];
			$start_d_final = $year.'-'.$mon.'-'.$day;

		}else{
			$start_d_final = $s_d_arr;
		}
		
		if($timeformat == 24){
			if($s_am_pm == ''){
				$start_t_final = $start_time;
			}else{
				$start_t_final = $start_time.' '.$s_am_pm;
			}
		}else{
			$start_t_final = date("H:i", strtotime($start_time.' '.$s_am_pm));
		}
		
		
		$start_date_f = $start_d_final;
		$start_time_f = $start_t_final;
		
		$enddate_arr = $form_data['enddate'];

		$edate_arr = explode(' ',$enddate_arr);
		$end_date = $edate_arr[0];
		$end_time = $edate_arr[1];
		$e_am_pm = $edate_arr[2];

		if($dateformat == 'mm-dd-yyyy'){
			$e_d_arr = explode('-',$end_date);
			$mon = $e_d_arr[0];
			$day = $e_d_arr[1];
			$year = $e_d_arr[2];
			$end_d_final = $year.'-'.$mon.'-'.$day;
			
		}elseif($dateformat == 'dd-mm-yyyy'){
			$e_d_arr = explode('-',$end_date);
			$day = $e_d_arr[0];
			$mon = $e_d_arr[1];
			$year = $e_d_arr[2];
			$end_d_final = $year.'-'.$mon.'-'.$day;

		}else{
			$end_d_final = $e_d_arr;
		}
		
		if($timeformat == 24){
			if($e_am_pm == ''){
				$end_t_final = $end_time;
			}else{
				$end_t_final = $end_time.' '.$e_am_pm;
			}
		}else{			
			$end_t_final = date("H:i", strtotime($end_time.' '.$e_am_pm));
		}
		//echo $end_t_final;
		
		
		$end_date_f = $end_d_final;
		$end_time_f = $end_t_final;
		
		$new_focus = array();
	    $new_focus['subject'] = $subject;
	    $new_focus['date_start'] = $start_date_f;
	    $new_focus['time_start'] = $start_time_f;
	    $new_focus['due_date'] = $end_date_f;
	    $new_focus['time_end'] = $end_time_f ;
	    $new_focus['eventstatus'] = $eventstatus;
	    $new_focus['activitytype'] = $activitytype;
	    $new_focus['taskpriority'] = $taskpriority;
	    $new_focus['assigned_user_id'] = $currentUser->get('id');
	    $new_focus['description'] = $description;
	    $new_focus['visibility'] = $visibility;
	    //$new_focus['defaultCallDuration'] = 5;
	    //$new_focus['defaultOtherEventDuration'] = 5;
		$new_focus['duration_hours'] = 5;
	   	//$new_focus['parent_id'] = $request->get('parentId');
		
	   	$activity=vtws_create("Events", $new_focus, $currentUser);
	    $activitys=$activity['id'];
		$activityid = explode("x",$activitys);
		$db->pquery("UPDATE vtiger_activity SET semodule=? where activityid=?",array($module,$activityid[1]));
		$db->pquery("INSERT INTO vtiger_seactivityrel VALUES(?,?)",array( $request->get('parentId'),$activityid[1]));
		$db->pquery("DELETE FROM timetracker_data WHERE recordid=? AND userid=?",array($request->get('parentId'), $currentUser->get('id')));
	    $response = new Vtiger_Response();
        $response->setResult(true);
        $response->emit();     

	}*/
	
	public function createEvents(Vtiger_Request $request){
		$db = PearDatabase::getInstance();
		//$db->setDebug(true);
		date_default_timezone_set('UTC');
		
		$currentUser = Users_Record_Model::getCurrentUserModel();
		$usr_timezone = $currentUser->get('time_zone');
		
		$form_data = $request->get('form_data');
        
		$subject = $form_data['subject'];
		$module = $form_data['module'];
		$eventstatus = $form_data['eventstatus'];
		$activitytype = $form_data['activitytype'];
		$visibility = $form_data['visibility'];
		$description = $form_data['description'];
		$priority = $form_data['taskpriority'];

		$dateformat = $currentUser->get('date_format');
		$timeformat = $currentUser->get('hour_format');
		$startdate_arr = $form_data['startdate']; //02-07-2018 06:11 PM
		
		$sdate_arr = explode(' ',$startdate_arr);
		$start_date = $sdate_arr[0];
		$start_time = $sdate_arr[1];
		$s_am_pm = $sdate_arr[2];

		if($dateformat == 'mm-dd-yyyy'){
			$s_d_arr = explode('-',$start_date);
			$mon = $s_d_arr[0];
			$day = $s_d_arr[1];
			$year = $s_d_arr[2];
			$start_d_final = $year.'-'.$mon.'-'.$day;
			
		}elseif($dateformat == 'dd-mm-yyyy'){
			$s_d_arr = explode('-',$start_date);
			$day = $s_d_arr[0];
			$mon = $s_d_arr[1];
			$year = $s_d_arr[2];
			$start_d_final = $year.'-'.$mon.'-'.$day;

		}else{
			$start_d_final = $s_d_arr;
		}
		
		if($timeformat == 24){
			if($s_am_pm == ''){
				$start_t_final = $start_time;
			}else{
				$start_t_final = $start_time.' '.$s_am_pm;
			}
		}else{
			$start_t_final = date("H:i", strtotime($start_time.' '.$s_am_pm));
		}
		
		$std = new DateTime($start_d_final.' '.$start_t_final, new DateTimeZone($usr_timezone));
		$std->setTimeZone(new DateTimeZone('UTC'));
		$startdate_arr1 =  $std->format('Y-m-d H:i:s');

		$start_total = explode(' ',$startdate_arr1);
		$start_date_f = $start_total[0];
		$start_time_f = $start_total[1];
		
		$enddate_arr = $form_data['enddate'];

		$edate_arr = explode(' ',$enddate_arr);
		$end_date = $edate_arr[0];
		$end_time = $edate_arr[1];
		$e_am_pm = $edate_arr[2];

		if($dateformat == 'mm-dd-yyyy'){
			$e_d_arr = explode('-',$end_date);
			$mon = $e_d_arr[0];
			$day = $e_d_arr[1];
			$year = $e_d_arr[2];
			$end_d_final = $year.'-'.$mon.'-'.$day;
			
		}elseif($dateformat == 'dd-mm-yyyy'){
			$e_d_arr = explode('-',$end_date);
			$day = $e_d_arr[0];
			$mon = $e_d_arr[1];
			$year = $e_d_arr[2];
			$end_d_final = $year.'-'.$mon.'-'.$day;

		}else{
			$end_d_final = $e_d_arr;
		}
		
		if($timeformat == 24){
			if($e_am_pm == ''){
				$end_t_final = $end_time;
			}else{
				$end_t_final = $end_time.' '.$e_am_pm;
			}
		}else{			
			$end_t_final = date("H:i", strtotime($end_time.' '.$e_am_pm));
		}
		//echo $end_t_final;
		
		$etd = new DateTime($end_d_final.' '.$end_t_final, new DateTimeZone($usr_timezone));
		$etd->setTimeZone(new DateTimeZone('UTC'));
		$enddate_arr1 =  $etd->format('Y-m-d H:i:s');

		$end_total = explode(' ',$enddate_arr1);
		$end_date_f = $end_total[0];
		$end_time_f = $end_total[1];
		
		$new_focus = array();
	    $new_focus['subject'] = $subject;
	    $new_focus['semodule'] = $module;
	    $new_focus['date_start'] = $start_date_f;
	    $new_focus['time_start'] = $start_time_f;
	    $new_focus['due_date'] = $end_date_f;
	    $new_focus['time_end'] = $end_time_f ;
	    $new_focus['eventstatus'] = $eventstatus;
	    $new_focus['activitytype'] = $activitytype;
	    $new_focus['assigned_user_id'] = $currentUser->get('id');
	    $new_focus['defaultCallDuration'] = 5;
	    $new_focus['defaultOtherEventDuration'] = 5;
	    $new_focus['duration_hours'] = 5;
	    $new_focus['taskpriority'] = $priority;
	    $new_focus['visibility'] = $visibility;
	    $new_focus['description'] = $description;
	   	//$new_focus['parent_id'] = $request->get('parentId');
	  
	   	$activity=vtws_create("Events", $new_focus, $currentUser);
	    $activitys=$activity['id'];
		$activityid = explode("x",$activitys);
		if($module == 'Contacts'){
			$db->pquery("INSERT INTO vtiger_cntactivityrel VALUES(?,?)",array( $request->get('parentId'),$activityid[1]));
		}else{
			$db->pquery("INSERT INTO vtiger_seactivityrel VALUES(?,?)",array( $request->get('parentId'),$activityid[1]));
		}
		$db->pquery("INSERT INTO vtiger_seactivityrel VALUES(?,?)",array( $request->get('parentId'),$activityid[1]));
		$db->pquery("DELETE FROM timetracker_data WHERE recordid=? AND userid=?",array($request->get('parentId'), $currentUser->get('id')));
	    $response = new Vtiger_Response();
        $response->setResult(true);
        $response->emit();     

	}

	public function saveTrackerData(Vtiger_Request $request){
		$db = PearDatabase::getInstance();
		$currentUser = Users_Record_Model::getCurrentUserModel();

		$form_data = base64_encode(serialize($request->get('form_data')));
		//print_r($currentUser->get('id'));
 
		$parentId = $request->get('parentId');
		$module = $request->get('currentModule');
		/*$subject = $form_data['subject'];
		$eventstatus = $form_data['eventstatus'];
		$taskpriority = $form_data['taskpriority'];
		$activitytype = $form_data['activitytype'];
		$visibility = $form_data['visibility'];
		$description = $form_data['description'];
		$startdate = $form_data['startdate'];
		$timeTrackerTotal = $form_data['timeTrackerTotal'];*/

		$trackerStatus = $request->get('trackerStatus');
		$query = $db->pquery("SELECT * FROM timetracker_data WHERE recordid =? AND userid=?",array($parentId, $currentUser->get('id')));
		if($db->num_rows($query) > 0) {
			$db->pquery("UPDATE timetracker_data SET form_data=?, status=?, record_name=? WHERE recordid=? AND userid=?",array($form_data, $trackerStatus, $request->get('record_name'), $parentId, $currentUser->get('id')));
		}else{
			$db->pquery("INSERT INTO timetracker_data(userid, recordid, form_data, status, record_name) values(?, ?,?,?,?)",array($currentUser->get('id'), $parentId, $form_data, $trackerStatus, $request->get('record_name')));
		}

		$response = new Vtiger_Response();
        $response->setResult(unserialize(base64_decode($form_data)));
        $response->emit();
		
	}

	public function cancelTimer(Vtiger_Request $request){
		$db = PearDatabase::getInstance();
		//$db->setDebug(true);
		$deletd_record = $db->pquery("SELECT * FROM timetracker_data LEFT JOIN vtiger_crmentity ON vtiger_crmentity.crmid = timetracker_data.recordid WHERE recordid=? AND vtiger_crmentity.deleted=1",array($request->get('parentId')));
		if($db->num_rows($deletd_record) > 0){
			$db->pquery("DELETE FROM timetracker_data WHERE recordid=?",array($request->get('parentId')));
		}
		
		$db->pquery("UPDATE timetracker_data SET status =? WHERE recordid=?",array('pause',$request->get('parentId')));
		$db->pquery("DELETE FROM timetracker_data WHERE recordid=?",array($request->get('parentId')));
		
		$check = $db->pquery("SELECT * FROM timetracker_data WHERE recordid=?",array($request->get('parentId')));
		if($db->num_rows($check) == 0){
			$result = 'true';
		}else{
			$result = 'false';
		}
		$response = new Vtiger_Response();
        $response->setResult($result);
        $response->emit();
	}
	
	public function deleteTimer(Vtiger_Request $request){
		$db = PearDatabase::getInstance();
		//$db->setDebug(true);
		$deletd_record = $db->pquery("SELECT recordid FROM timetracker_data LEFT JOIN vtiger_crmentity ON vtiger_crmentity.crmid = timetracker_data.recordid WHERE vtiger_crmentity.deleted=1");
		$row = $db->num_rows($deletd_record);
		if($row > 0){
			for($i=0;$i<$row;$i++){
				$id = $db->query_result($deletd_record,$i,'recordid');
				
				$db->pquery("DELETE FROM timetracker_data WHERE recordid=?",array($id));
			}
		}
		
		$response = new Vtiger_Response();
        $response->setResult();
        $response->emit();
	}

}
