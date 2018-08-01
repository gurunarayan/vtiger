<?php
/*+**********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.1
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 ************************************************************************************/

class Settings_LoginPage_ThemeStatus_View extends Vtiger_View_Controller {
	function loginRequired() {
		return false;
	}
	
	function checkPermission(Vtiger_Request $request) {
		return true;
	}
	public function process(Vtiger_Request $request) {
		global $adb;
		$name = $request->get('themename');
		$mode = $request->get('mode');
		if($mode == 0){

			$adb->pquery("update vtigress_themes set status=? where name =?",array('0',$name));
		}else{
			$adb->pquery("update vtigress_themes set status=? where name =?",array('1',$name));
			echo "else loop";
			$adb->pquery("update vtigress_themes set status=? where name != ?",array('0',$name));
		}
		
		$response = new Vtiger_Response();
		$response->setResult('1');
		$response->emit();
	}
}
