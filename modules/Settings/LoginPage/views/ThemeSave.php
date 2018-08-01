<?php
/*+**********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.1
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 ************************************************************************************/

class Settings_LoginPage_ThemeSave_View extends Vtiger_View_Controller {
	function loginRequired() {
		return false;
	}
	
	function checkPermission(Vtiger_Request $request) {
		return true;
	}
	public function process(Vtiger_Request $request) {
		global $adb;
		$name = $request->get('themename');
		$count = $adb->num_rows($adb->pquery("SELECT name FROM vtigress_themes WHERE name=?",array($name)));
		if($count == 0){
			$params = array($name);
			$query = "INSERT INTO vtigress_themes(name) VALUES (?)";
			$adb->pquery($query,$params);
			$count =0;
		}
		$response = new Vtiger_Response();
		$response->setResult($count);
		$response->emit();
	}
}
