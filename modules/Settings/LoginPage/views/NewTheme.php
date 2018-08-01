<?php
/*+**********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.1
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 ************************************************************************************/

class Settings_LoginPage_NewTheme_View extends Settings_Vtiger_Index_View {

	function __construct() {
		parent::__construct();
	}

	public function process(Vtiger_Request $request) {
		global $adb;
		$viewer = $this->getViewer($request);
		
		#$rows	=	$request->get('x');
		#$columns	=	$request->get('y');

		#TheraCann
		$rows = 1;
		$columns = 2;
		#end
		$viewer->assign('ROWS',$rows);
		$viewer->assign('COLUMNS',$columns);
		$viewer->view('VtigressNewThemeCreate.tpl', $request->getModule(false));
	}
	function getHeaderScripts(Vtiger_Request $request) {
		$headerScriptInstances = parent::getHeaderScripts($request);
		
		$jsFileNames = array(
			'modules.Settings.LoginPage.resources.List'			 
		);

		$jsScriptInstances = $this->checkAndConvertJsScripts($jsFileNames);
		$headerScriptInstances = array_merge($headerScriptInstances, $jsScriptInstances);
		return $headerScriptInstances;
	}
}
