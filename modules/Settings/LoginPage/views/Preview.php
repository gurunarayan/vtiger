<?php
/*+***********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 *************************************************************************************/

Class Settings_LoginPage_Preview_View extends Vtiger_View_Controller {
	
	function loginRequired() {
		return false;
	}
	
	function checkPermission(Vtiger_Request $request) {
		return true;
	}
	
	public function process(Vtiger_Request $request) {
	    $qualifiedModuleName = $request->getModule(false);
        $viewer = $this->getViewer($request);
		$themename = $request->get('name');
		$loginpage = $request->get('loginpagename');
		if($loginpage == ''){
			$loginpage = 'Login.Custom';
		}
		switch($themename){
			case $themename:
					$viewer->view($themename.'.tpl', $qualifiedModuleName); 
					break;
			 default:
					$viewer->view($loginpage.'.tpl', $qualifiedModuleName); 
		}
	}	
}