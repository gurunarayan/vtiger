<?php
/*+**********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.1
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 ************************************************************************************/

class Settings_LoginPage_List_View extends Settings_Vtiger_Index_View {

	function __construct() {
		parent::__construct();
	}

	public function process(Vtiger_Request $request) {
		global $adb;
		$viewer = $this->getViewer($request);
		$rows	=	$request->get('x');
		$columns	=	$request->get('y');
		$viewer->assign('ROWS',$rows);
		$viewer->assign('COLUMNS',$columns);
		$viewer->assign('THEMELIST',$this->getThemesList());
		$viewer->view('ListViewContents.tpl', $request->getModule(false));
	}
	public function getThemesList(){
		global $adb;
        $query = 'SELECT * FROM vtigress_themes';
	    $params = array();
		$themeslist = array();
        $result = $adb->pquery($query,$params);
		$i=1;
		while($row = $adb->fetch_array($result)){
			$themes['id'] = $i;
			$themes['name'] = $row['name'];
			$themes['status'] = $row['status'];
			$themes['deleted'] = $row['deleted'];
			$themes['previewurl'] = "index.php?module=LoginPage&parent=Settings&view=Preview&name=".$row['name'];
			$themeslist[] = $themes;
			$i++;
		}
        return $themeslist;
	}
	function getHeaderScripts(Vtiger_Request $request) {
		$headerScriptInstances = parent::getHeaderScripts($request);
		
		$jsFileNames = array(
			'modules.Settings.LoginPage.resources.ListView'			 
		);

		$jsScriptInstances = $this->checkAndConvertJsScripts($jsFileNames);
		$headerScriptInstances = array_merge($headerScriptInstances, $jsScriptInstances);
		return $headerScriptInstances;
	}
}