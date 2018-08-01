<?php
/*+**********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.1
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 ************************************************************************************/

class PointSale_Settings_View extends Vtiger_Index_View {
	
	public function process(Vtiger_Request $request) {
		$adb = PearDatabase::getInstance();
		$viewer = $this->getViewer($request);
		$qualifiedModuleName = $request->getModule(false);
		
		$query = $adb->pquery("SELECT * FROM vtiger_pos_settings",array());
		$row = $adb->num_rows($query);
		if($row > 0){
			$tax = $adb->query_result($query,0,'tax');
			$currency = $adb->query_result($query,0,'currency');
			
			$currency_arr = explode(',', $currency);
		} 
		
		$viewer->assign('TAX',$tax);
		$viewer->assign('CURRENCY',$currency_arr);
		$viewer->assign('MODULE',$qualifiedModuleName);
        $viewer->view('Settings.tpl', $qualifiedModuleName);
	}
	
	 public function getHeaderScripts(Vtiger_Request $request) {
		$headerScriptInstances = parent::getHeaderScripts($request);
	
		$jsFileNames = array(
			'modules.PointSale.resources.Settings',
			//'modules.Settings.TimeTracker.resources.OS2TimeTracker',
		);
	
		$jsScriptInstances = $this->checkAndConvertJsScripts($jsFileNames);
		$headerScriptInstances = array_merge($headerScriptInstances, $jsScriptInstances);
		return $headerScriptInstances;
    }
}
