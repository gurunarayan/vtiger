<?php
/*+**********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.1
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 ************************************************************************************/

class Settings_OS2TimeTracker_Settings_View extends Settings_Vtiger_Index_View {
	
	
	public function process(Vtiger_Request $request) {
		$db = PearDatabase::getInstance();
		$viewer = $this->getViewer($request);
		$qualifiedModuleName = $request->getModule(false);
		
		
		$modulelist = ['Accounts','Contacts','Leads','Potentials','HelpDesk','Project','ProjectTask'];
		
		$eventModule = Vtiger_Module_Model::getInstance('Events');

		$fieldname = ['subject','eventstatus','taskpriority','activitytype','visibility','description'];

		$query = $db->pquery("SELECT * FROM vtiger_timetracker_settings",array());
		$row = $db->num_rows($query);
		if($row > 0){
			$modules = unserialize(base64_decode($db->query_result($query,0,'modules')));
			$fields = unserialize(base64_decode($db->query_result($query,0,'fields')));
		
		}
		
		$viewer->assign('SETTING_FIELDS',$fields);
		$viewer->assign('SETTING_MODULES',$modules);
		$viewer->assign('FIELD_NAME',$fieldname);
		$viewer->assign('EVENT_MODULE',$eventModule);
		$viewer->assign('MODULES_LIST',$modulelist);
		$viewer->assign('MODULE',$qualifiedModuleName);
        $viewer->view('Settings.tpl', $qualifiedModuleName);
	}
	
	 public function getHeaderScripts(Vtiger_Request $request) {
		$headerScriptInstances = parent::getHeaderScripts($request);
	
		$jsFileNames = array(
			'modules.Settings.OS2TimeTracker.resources.Settings',
			//'modules.Settings.TimeTracker.resources.OS2TimeTracker',
		);
	
		$jsScriptInstances = $this->checkAndConvertJsScripts($jsFileNames);
		$headerScriptInstances = array_merge($headerScriptInstances, $jsScriptInstances);
		return $headerScriptInstances;
    }
}
