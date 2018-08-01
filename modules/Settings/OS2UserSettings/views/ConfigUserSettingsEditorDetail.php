<?php
/*+**********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.1
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 ************************************************************************************/

class Settings_OS2UserSettings_ConfigUserSettingsEditorDetail_View extends Settings_Vtiger_Index_View {

	public function process(Vtiger_Request $request) {
		//echo "<pre>"; print_r($_REQUEST); echo "</pre>";exit;
		$qualifiedName = $request->getModule(false);
		$moduleModel = Settings_OS2UserSettings_ConfigUserSettingsModule_Model::getInstance();
		$viewer = $this->getViewer($request);
		$viewer->assign('MODEL', $moduleModel);
		$viewer->assign('USERSETTINGS', $this->getUserSettingsInfo());	 
		$viewer->assign('QUALIFIED_MODULE', $qualifiedName);	 
		$viewer->assign('CURRENT_USER_MODEL', Users_Record_Model::getCurrentUserModel());
		$viewer->view('ConfigUserSettingsEditorDetail.tpl', $qualifiedName);
	}
	
	function getPageTitle(Vtiger_Request $request) {
		$qualifiedModuleName = $request->getModule(false);
		return vtranslate('User Settings Details',$qualifiedModuleName);
	}
    
	/**
	 * Function to get the list of Script models to be included
	 * @param Vtiger_Request $request
	 * @return <Array> - List of Vtiger_JsScript_Model instances
	 */
	function getHeaderScripts(Vtiger_Request $request) {
		$headerScriptInstances = parent::getHeaderScripts($request);
		$moduleName = $request->getModule();

		$jsFileNames = array(
			"modules.Settings.$moduleName.resources.ConfigUserSettingsEditor"
		);

		$jsScriptInstances = $this->checkAndConvertJsScripts($jsFileNames);
		$headerScriptInstances = array_merge($headerScriptInstances, $jsScriptInstances);
		return $headerScriptInstances;
	}
	
	public function getUserSettingsInfo()
	{
		global $adb;
		$query = "SELECT * from vtiger_user_config WHERE status = 1";
		$result = $adb->query($query); 
		$usersetting_arr = array();
		$i=0;
		$listitems = $adb->fetchByAssoc($result);
		$weeks = $listitems['weeks'];		 
		$weeks = json_decode(stripslashes(html_entity_decode($weeks)), true);
		$listitems['weeks'] = $weeks;
		
		$holiday_lbl_val = $listitems['holiday_lbl_val'];		 
		$holiday_lbl_val = json_decode(stripslashes(html_entity_decode($holiday_lbl_val)), true);
		$listitems['holiday_lbl_val'] = $holiday_lbl_val;
		
		return $listitems;
	}
}
